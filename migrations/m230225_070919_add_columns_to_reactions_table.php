<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%reactions}}`.
 */
class m230225_070919_add_columns_to_reactions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%reactions}}', 'comment', $this->string(255));
        $this->addColumn('{{%reactions}}', 'date_created', $this->datetime()->notNull()->defaultValue(new \yii\db\Expression('NOW()')));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%reactions}}', 'comment');
        $this->dropColumn('{{%reactions}}', 'date_created');
    }
}
