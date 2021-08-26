<?php

namespace webzop\notifications\model;

use Yii;

/**
 * This is the model class for table "{{%notifications_user_channels}}".
 *
 * @property int $id
 * @property int $user
 * @property string $channel
 * @property string|null $receiver
 * @property bool $active
 */
class UserChannels extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%notifications_user_channels}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['active'], 'default', 'value' => true],
            [['user', 'channel'], 'required'],
            [['user'], 'integer'],
            [['active'], 'boolean'],
            [['channel', 'receiver'], 'string', 'max' => 255],
            [
                ['channel'],
                'unique',
                'attributes' => ['user', 'channel'],
                'message' => Yii::t('app','The channel {value} is already set for this user.'),
            ],
            [['receiver'], 'required', 'when' => function($model) {return $model->active;}, 'enableClientValidation' => false]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user' => Yii::t('app', 'User'),
            'channel' => Yii::t('app', 'Channel'),
            'receiver' => Yii::t('app', 'Receiver'),
            'active' => Yii::t('app', 'Active'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return UserChannelsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserChannelsQuery(get_called_class());
    }

    /**
     * @param integer $user The user id
     * @param string $id The channel id.
     * @return UserChannels|null
     */
    public static function findByChannel($user, $id)
    {
        return static::findOne(['user' => $user, 'channel' => $id]);
    }
}
