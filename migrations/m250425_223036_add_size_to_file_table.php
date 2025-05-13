<?php

use yii\db\Migration;

class m250425_223036_add_size_to_file_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {
        $this->addColumn('files', 'size', $this->integer()->comment('Размер файла в байтах'));
        $this->createIndex('idx-file-task_id', 'files', 'task_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): void
    {
        $this->dropIndex('idx-file-task_id', 'files');
        $this->dropColumn('files', 'size');
    }
}
