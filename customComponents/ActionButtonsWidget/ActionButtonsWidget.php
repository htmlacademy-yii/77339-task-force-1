<?php

namespace app\customComponents\ActionButtonsWidget;

use app\logic\Actions\AbstractAction;
use app\logic\AvailableActions;
use app\models\Response;
use app\models\Task;
use Yii;
use yii\base\Widget;
use yii\helpers\Html;

final class ActionButtonsWidget extends Widget
{
    public AvailableActions $availableActions;
    public int $currentUserId;
    public Task $task;

    public function run() : string
    {
        $actions = $this->availableActions->getAvailableActions($this->currentUserId);

        $buttons = [];

        foreach ($actions as $action) {
            if ($action instanceof AbstractAction) {
                if (
                    $action->getInternalName() === 'act_response' && (Yii::$app->user->identity->role !== 'executor' || $this->hasResponded(
                        ) || $this->currentUserId === $this->task->customer_id)
                ) {
                    return '';
                }

                if ($action->getInternalName() === 'assign') {
                    continue;
                }

                $buttons[] = $this->generateButton($action);
            }
        }

        return implode(PHP_EOL, $buttons);
    }

    private function hasResponded() : bool
    {
        return Response::find()->where(['task_id' => $this->task->id, 'executor_id' => $this->currentUserId])->exists();
    }

    /**
     * Генерирует HTML-код кнопки действия
     *
     * @param AbstractAction $action
     *
     * @return string
     */
    private function generateButton(AbstractAction $action) : string
    {
        $label = $action->getName();
        $actionName = $action->getInternalName();
        $colorClass = $this->getButtonColor($actionName);

        return Html::a(
            $label,
            '#',
            [
                'class' => "button button--{$colorClass} action-btn",
                'data-action' => $actionName,
            ]
        );
    }

    private function getButtonColor(string $actionName) : string
    {
        return match ($actionName) {
            'act_response' => 'blue',
            'refusal', 'cancel' => 'orange',
            'completion' => 'pink',
            default => 'default',
        };
    }
}
