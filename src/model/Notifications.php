<?php

namespace webzop\notifications\model;

use webzop\notifications\Module;
use webzop\notifications\Notification;
use Yii;
use yii\db\Schema;
use yii\helpers\Json;
use webzop\notifications\helpers\TimeElapsed;


/**
 * This is the model class for table "{{%notifications}}".
 *
 * @property integer $id
 * @property integer $type
 * @property string $class
 * @property string $key
 * @property string $channel
 * @property string $message
 * @property string $content
 * @property array $attachments
 * @property string $language
 * @property string $route
 * @property integer $seen
 * @property integer $read
 * @property integer $user_id
 * @property string $send_at
 * @property bool $sent
 * @property integer $created_at
 * @property integer $managed
 */
class Notifications extends \yii\db\ActiveRecord
{
    /**
     * @var Notification
     */
    public $notification;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%notifications}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sent'], 'default', 'value' => false],
            [['language'], 'default', 'value' => Yii::$app->language],
            [['class', 'key', 'message', 'route', 'channel', 'sent'], 'required'],
            [['seen', 'type', 'read', 'user_id', 'created_at', 'managed'], 'integer'],
            [['send_at'], 'datetime'],
            [['sent'], 'boolean'],
            [['content'], 'string'],
            [['class'], 'string', 'max' => 64],
            [['channel', 'key'], 'string', 'max' => 32],
            [['message', 'route'], 'string', 'max' => 255],
            [['language'], 'string', 'max' => 5],
            [['attachments', 'type', 'seen', 'managed', 'read'], 'safe'],
            [['attachments'], 'default', 'value' => []],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function beforeValidate()
    {
        // Initializing model's attributes through the linked [Notification]
        if($this->isNewRecord && !empty($this->notification)) {
            $className = get_class($this->notification);
            $currTime = time();
            $this->setAttributes([
                'class' => strtolower(substr($className, strrpos($className, '\\')+1, -12)),
                'key' => $this->notification->key,
                'message' => $this->notification->getTitle(),
                'content' => $this->notification->getDescription(),
                'attachments' => $this->notification->getAttachments(),
                'language' => $this->notification->getLanguage(),
                'route' => serialize($this->notification->getRoute()),
                'user_id' => $this->notification->userId,
                'created_at' => $currTime,
                'send_at' => $this->notification->sendAt,
            ]);
        }
        return parent::beforeValidate();
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        // We need to generate a path for the documents so that
        if($insert) {
            $attachments = $this->attachments;
            // Normalizing the attachments so that the path will be the module temporary file, the original path
            // will be stored in realPath so that it can be copied in the afterSave
            foreach ($attachments as $i => $attachment) {
                $attachments[$i]['realPath'] = $attachment['path'];
                $filename = uniqid('notifications_');
                $path = Yii::getAlias(Module::getInstance()->attachmentsPath);
                $attachments[$i]['path'] = "$path/$filename";
            }
            $this->attachments = $attachments;
        }
        $this->encodeAttachments();
        return parent::beforeSave($insert);
    }

    /**
     * {@inheritdoc}
     */
    public function afterSave($insert, $changedAttributes)
    {
        $this->decodeAttachments();
        if($insert) {
            // Copying the files to the temporary folder of the notifications so that they can be deleted after the
            // notification is sent
            foreach ($this->attachments as $attachment) {
                copy($attachment['realPath'], $attachment['path']);
            }
        }
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * {@inheritdoc}
     */
    public function afterFind()
    {
        parent::afterFind();
        $this->decodeAttachments();
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('modules/notifications', 'ID'),
            'type' => Yii::t('modules/notifications', 'Type'),
            'class' => Yii::t('modules/notifications', 'Class'),
            'key' => Yii::t('modules/notifications', 'Key'),
            'channel' => Yii::t('modules/notifications', 'Channel'),
            'message' => Yii::t('modules/notifications', 'Message'),
            'content' => Yii::t('modules/notifications', 'Content'),
            'attachments' => Yii::t('modules/notifications', 'Attachments'),
            'language' => Yii::t('modules/notifications', 'Language'),
            'route' => Yii::t('modules/notifications', 'Route'),
            'seen' => Yii::t('modules/notifications', 'Seen'),
            'read' => Yii::t('modules/notifications', 'Read'),
            'send_at' => Yii::t('modules/notifications', 'Send At'),
            'sent' => Yii::t('modules/notifications', 'Sent'),
            'user_id' => Yii::t('modules/notifications', 'User ID'),
            'created_at' => Yii::t('modules/notifications', 'Created At'),
            'managed' => Yii::t('modules/notifications', 'Managed'),
        ];
    }

    public function getNotificationsType()
    {
        return $this->hasOne(NotificationType::class, ['id' => 'type']);
    }

    /**
     * Decode of the attachment column only if it's not of type `json` on the DB
     */
    protected function encodeAttachments()
    {
        $dbType = static::getDb()->getTableSchema(static::tableName())->getColumn('attachments')->dbType;
        if ($dbType !== Schema::TYPE_JSON) {
            $this->attachments = Json::encode($this->attachments);
        }
    }

    /**
     * Encode of the attachment column only if it's not of type `json` on the DB
     */
    protected function decodeAttachments()
    {
        $dbType = static::getDb()->getTableSchema(static::tableName())->getColumn('attachments')->dbType;
        if ($dbType !== Schema::TYPE_JSON) {
            $this->attachments = Json::decode($this->attachments);
        }
    }

    public function getTimeAgo()
    {
        return TimeElapsed::timeElapsed($this->created_at);
    }

}
