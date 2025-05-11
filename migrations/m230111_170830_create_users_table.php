<?php

use yii\db\Migration;

class m230111_170830_create_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%users}}', [
            'id' => $this->primaryKey(),
            'full_name' => $this->string(255)->notNull(),
            'email' => $this->string(255)->notNull()->unique(),
            'password' => $this->string(255)->notNull(),
            'city_id' => $this->integer()->notNull(),
            'avatar_id' => $this->integer(),
            'date_created' => $this->dateTime()->notNull()->defaultValue(new \yii\db\Expression('NOW()')),
        ]);

        $this->createIndex('idx_email', 'users', 'email');
        $this->createIndex('idx_city_id', 'users', 'city_id');
        $this->createIndex('idx_date_created', 'users', 'date_created');

        $this->addForeignKey(
            'fk_users_cities_id',
            'users',
            'city_id',
            'cities',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_users_files_id',
            'users',
            'avatar_id',
            'files',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_users_cities_id', 'users');
        $this->dropForeignKey('fk_users_files_id', 'users');

        $this->dropIndex('idx_email', 'users');
        $this->dropIndex('idx_city_id', 'users');
        $this->dropIndex('idx_date_created', 'users');

        $this->dropTable('{{%users}}');
    }
}
