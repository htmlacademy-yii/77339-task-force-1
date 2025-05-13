<?php

use yii\db\Migration;

class m250329_220333_add_accepts_orders_to_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {
        if (!$this->db->getTableSchema('users')->getColumn('accepts_orders')) {
            $this->addColumn('users', 'accepts_orders', 'TINYINT(1) NOT NULL DEFAULT 1 COMMENT "0 — не принимает заказы, 1 — принимает"');
        }

        if ($this->db->getTableSchema('users')->getColumn('accepts_orders')) {
            $this->execute("UPDATE users SET accepts_orders = FLOOR(RAND() * 2) WHERE role = 'executor'");
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): void
    {
        $this->dropColumn('users', 'accepts_orders');
    }
}
