<?php

use yii\db\Migration;

/**
 * Class m220626_165153_create_notification_type
 */
class m220626_165153_create_notification_type extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('notification_type', [
            'id' => $this->primaryKey(),
            'code' => $this->string(5)->unique(),
            'name' => $this-> string(20),
            'check_management' => $this->boolean(),
            'color' => $this->string(7),
            'priority' => $this->string(20),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //echo "m220626_165153_create_notification_type cannot be reverted.\n";
        $this->dropTable('notification_type');
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220626_165153_create_notification_type cannot be reverted.\n";

        return false;
    }
    */
}
