<?php

use yii\db\Migration;

/**
 * Class m010101_100001_init_notifications
 */
class m010101_100001_init_notifications extends Migration
{
    /**
     * Create table `notifications`
     */
    public function up()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        // notifications
        $this->createTable('{{%notifications}}', [
            'id' => $this->primaryKey(),
            'type' => $this->string(32)->notNull(),
            'class' => $this->integer()->notNull(),
            'key' => $this->string(32)->notNull(),
            'message' => $this->string(255)->notNull(),
            'route' => $this->string(255)->notNull(),
            'seen' => $this->boolean()->notNull()->defaultValue(false),
            'read' => $this->boolean()->notNull()->defaultValue(false),
            'user_id' => $this->integer(11)->unsigned()->notNull()->defaultValue(0),
            'created_at' => $this->integer(11)->unsigned()->notNull()->defaultValue(0),
            'manged' => $this->boolean()->notNull()->defaultValue(false),
        ], $tableOptions);
        $this->createIndex('index_2', '{{%notifications}}', ['user_id']);
        $this->createIndex('index_3', '{{%notifications}}', ['created_at']);
        $this->createIndex('index_4', '{{%notifications}}', ['seen']);
        $this->createIndex('index_5', '{{%notifications}}', ['type']);

        $this->addForeignKey('fk-notifications-type', 'notifications', 'type', 'notifications_type', 'id', 'RESTRIC', 'RESTRIC');
    }

    /**
     * Drop table `notifications`
     */
    public function down()
    {
        $this->dropTable('{{%notifications}}');
    }
}
