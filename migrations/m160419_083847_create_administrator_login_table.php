<?php

use yii\db\Migration;

class m160419_083847_create_administrator_login_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%admin_login}}', [
            'id'       => $this->primaryKey(),
            'type'     => $this->string()->defaultValue(''),//事件类型
            'uid'      => $this->integer()->defaultValue(0),
            'username' => $this->string()->defaultValue(''),
            'ip'       => $this->string()->defaultValue(''),
            'c_t'      => $this->timestamp(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%admin_login}}');
    }
}
