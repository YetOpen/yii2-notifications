<?php

namespace webzop\notifications\model;

use Yii;

/**
 * This is the model class for table "{{%notification_contents}}".
 *
 * @property int $notification_id
 * @property string $title
 * @property string $content
 * @property string|null $attachments
 *
 * @property Notifications $notification
 */
class NotificationContents extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%notification_contents}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['notification_id', 'title', 'content'], 'required'],
            [['notification_id'], 'integer'],
            [['content'], 'string'],
            [['attachments'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['notification_id'], 'exist', 'skipOnError' => true, 'targetClass' => Notifications::className(), 'targetAttribute' => ['notification_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'notification_id' => Yii::t('app', 'Notification ID'),
            'title' => Yii::t('app', 'Title'),
            'content' => Yii::t('app', 'Content'),
            'attachments' => Yii::t('app', 'Attachments'),
        ];
    }

    /**
     * Gets query for [[Notification]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNotification()
    {
        return $this->hasOne(Notifications::className(), ['id' => 'notification_id']);
    }
}
