<?php

namespace webzop\notifications\model;

use Yii;

/**
 * This is the model class for table "notification_type".
 *
 * @property int $id
 * @property string|null $code
 * @property string|null $name
 * @property int|null $check_management
 * @property string|null $color
 * @property int|null $priority
 */
class NotificationType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%notification_type}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['check_management'], 'boolean'],
            [['priority'], 'integer'],
            [['code'], 'string', 'max' => 5],
            [['name'], 'string', 'max' => 20],
            [['color'], 'string', 'max' => 7],
            [['code'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('modules/notifications', 'ID'),
            'code' => Yii::t('modules/notifications', 'Code'),
            'name' => Yii::t('modules/notifications', 'Name'),
            'check_management' => Yii::t('modules/notifications', 'Check Management'),
            'color' => Yii::t('modules/notifications', 'Color'),
            'priority' => Yii::t('modules/notifications', 'Priority'),
        ];
    }
}
