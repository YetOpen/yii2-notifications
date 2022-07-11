<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_channel}}`.
 */
class m270825_102524_create_notifications_user_channels_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%notifications_user_channels}}', [
            'id' => $this->primaryKey(),
            'user' => $this->integer()->notNull(),
            'channel' => $this->string()->notNull(),
            'receiver' => $this->string(),
            'active' => $this->boolean()->defaultValue(true),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%notifications_user_channels}}');
    }
}