<?php

use yii\db\Migration;

/**
 * Class m010101_100001_init_notifications
 */
class m010101_100001_add_type_column_add_managed_column_to_notifications_table extends Migration
{
    /**
     * Create table `notifications`
     */
    public function up()
    {
        $this->addColumn('notifications', 'type', $this->integer());
        $this->addColumn('notifications', 'managed', $this->boolean());
        
        $this->createIndex('index_5', '{{%notifications}}', ['type']);

        $this->addForeignKey('fk-notification-type', 'notifications', 'type', 'notification_type', 'id', 'RESTRICT', 'RESTRICT');
    }

    /**
     * Drop table `notifications`
     */
    public function down()
    {
        $this->dropTable('{{%notifications}}');
    }
}
