<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%notifications}}`.
 */
class m271108_100835_add_channel_column_content_column_attachments_column_language_column_send_at_column_sent_column_to_notifications_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            '{{%notifications}}',
            'channel',
            $this->string()->after('key')
        );
        $this->addColumn(
            '{{%notifications}}',
            'content',
            $this->text()->after('message')
        );
        $this->addColumn(
            '{{%notifications}}',
            'attachments',
            $this->json()->after('content')
        );
        $this->addColumn(
            '{{%notifications}}',
            'language',
            $this->string(5)->after('attachments')->notNull()
        );
        $this->addColumn(
            '{{%notifications}}',
            'send_at',
            $this->dateTime()->after('user_id')
        );
        $this->addColumn(
            '{{%notifications}}',
            'sent',
            $this->boolean()->notNull()->after('send_at')->defaultValue(true)
        );
        $this->update('{{%notifications}}', ['attachments' => "[]", 'language' => Yii::$app->language]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%notifications}}', 'channel');
        $this->dropColumn('{{%notifications}}', 'content');
        $this->dropColumn('{{%notifications}}', 'attachments');
        $this->dropColumn('{{%notifications}}', 'language');
        $this->dropColumn('{{%notifications}}', 'send_at');
        $this->dropColumn('{{%notifications}}', 'sent');
    }
}
