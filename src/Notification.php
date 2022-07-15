<?php

namespace webzop\notifications;

use webzop\notifications\model\Notifications;
use webzop\notifications\model\NotificationType;
use Yii;
use yii\base\InvalidConfigException;

/**
 * This is the base class for a notification.
 *
 * @property string $key
 * @property integer $userId
 * @property array $data
 */
class Notification extends \yii\base\BaseObject
{
    public $key;

    public $userId = 0;

    public $typeCode = 'GN';

    public $sendAt = NULL;

    public $title = '';

    public $description = '';

    public $route = NULL;

    public $attachments = [];

    public $language;

    public $data = [];

    /**
     * @var string|null
     */
    public $tag = null;

    /**
     * @var string|null
     */
    public $priority = null;

    /**
     * @var string|null
     */
    public $ttl = null;


    const PRIORITY_LOWEST = 'very-low';
    const PRIORITY_LOW = 'low';
    const PRIORITY_NORMAL = 'normal';
    const PRIORITY_HIGH = 'high';


    const DEFAULT_TTL = 7200;               // [in seconds]. 7200 = valid for two hours

    /**
     * Time that has to pass until a notification with same user and key con be sent again.
     * It has to be a string that can be passed to @see \DateInterval (@link http://php.net/manual/en/dateinterval.construct.php)
     * If FALSE, this control is disabled
     * @var string
     */
    protected $renotification_time = FALSE;

    /**
     * Create an instance
     *
     * @param string $key
     * @param array $params notification properties
     * @return static the newly created Notification
     * @throws \Exception
     */
    public static function create($key, $params = []){
        $params['key'] = $key;
        return new static($params);
    }

    /**
     * Determines if the notification can be sent.
     *
     * @param Channel $channel
     * @return bool
     * @throws \Exception
     */
    public function shouldSend($channel)
    {
        // If the re-notification time params is false we don't need to check the interval, the notification should
        // be sent only if it was set to be sent at the current time or before
        if (empty($this->renotification_time)) {
            return TRUE;
        }

        // Workaround:
        // After the notification on the screen channel, the next are not sent because it finds the one just sent.
        // Adds 1 second to solve this problem.
        $margin = static::getLimit('PT1S')->format('Y-m-d H:i:s');

        // The notification can be sent if there aren't others with same user/key sent in the period specified in
        // renotification_time params
        $end = static::getLimit($this->renotification_time)->format('Y-m-d H:i:s');
        $className = $this->className();
        $notifications = Notifications::find()
            ->andWhere([
                'channel'   => $channel->id,
                'user_id'   => $this->userId,
                'key'       => $this->key,
                'class'     => strtolower(substr($className, strrpos($className, '\\')+1, -12)),
                'sent'      => false,
            ])
            ->andWhere(['>', 'send_at', $end])
            ->andWhere(['<', 'send_at', $margin])
            ->exists();

        return !$notifications;
    }

    /**
     * Ensures the exsistence of the type, if it's not present in the table it will be created
     *
     * @return int id of the type
     */
    public function ensureType(){
        $type = NotificationType::find()->andWhere(['code' => $this->typeCode])->one();
        if(is_null($type)){
            $type = new NotificationType([
                'code' => $this->typeCode,
                'name' => $this->typeCode,
                'check_management' => false,
                'color' => '#ead1dc',
                'priority' => 1
            ]);
            $type->save();

        }

        return $type->id;
    }

    /**
     * Gets the notification title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Gets the notification description
     *
     * @return string|null
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Gets the notification language code
     *
     * @return string|null
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Gets the notification attachments
     *
     * @return array
     */
    public function getAttachments(){
        $attachments = [];
        foreach ($this->attachments as $attachment) {
            // The attachment was set as an array already parsed
            if(!is_string($attachment)) {
                $attachments[] = $attachment;
                continue;
            }

            $attachments[] = [
                'path' => $attachment,
                'filename' => basename($attachment),
                'type' => mime_content_type($attachment),
            ];
        }
        return $attachments;
    }

    /**
     * Gets the notification route
     *
     * @return array|null
     */
    public function getRoute(){
        return $this->route;
    }

    /**
     * Gets notification data
     *
     * @return array
     */
    public function getData(){
        return $this->data;
    }

    /**
     * Sets notification data
     *
     * @param array $data
     * @return self
     */
    public function setData($data = []){
        $this->data = $data;
        return $this;
    }

    /**
     * Gets notification tag
     *
     * @return string|null
     */
    public function getTag(){
        return $this->tag;
    }

    /**
     * Sets notification tag
     *
     * @param string|null $tag
     * @return self
     */
    public function setTag($tag = null){
        $this->tag = $tag;
        return $this;
    }

    /**
     * Sets notification priority
     *
     * @param string $priority
     * @return self
     */
    public function setPriority($priority){
        $this->priority = $priority;
        return $this;
    }

    /**
     * Gets notification priority
     *
     * @return string
     */
    public function getPriority(){
        if($this->priority) {
            return $this->priority;
        }
        return Notification::PRIORITY_NORMAL;
    }

    /**
     * Sets notification TTL
     *
     * @param string $ttl
     * @return self
     */
    public function setTTL($ttl){
        $this->ttl = $ttl;
        return $this;
    }

    /**
     * Gets notification TTL
     *
     * @return string
     */
    public function getTTL(){
        if($this->ttl) {
            return $this->ttl;
        }
        return Notification::DEFAULT_TTL;
    }


    /**
     * Gets the UserId
     *
     * @return int
     */
    public function getUserId(){
        return $this->userId;
    }

    /**
     * Sets the UserId
     *
     * @param int $id
     * @return self
     */
    public function setUserId($id){
        $this->userId = $id;
        return $this;
    }

    /**
     * Sends this notification to all channels
     *
     * @return bool If the sending of the notification was successful.
     */
    public function send(){
        $module = Yii::$app->getModule('notifications');
        if(is_null($module)) {
            throw new InvalidConfigException("Please set up the module in the web/console settings, see README for instructions");
        }
        return $module->send($this);
    }

    /**
     * Calculate a time limit subtractive the interval to the current moment
     * @param string $time interval, string passed to the constructor of \DateInterval
     * @return \DateTime
     * @throws \Exception
     */
    public static function getLimit($time)
    {
        return (new \DateTime())->sub(new \DateInterval($time));
    }

    /**
     * @return string
     */
    public function getRenotificationTime(): string
    {
        return $this->renotification_time;
    }

    /**
     * @param string $renotification_time
     */
    public function setRenotificationTime(string $renotification_time): void
    {
        $this->renotification_time = $renotification_time;
    }
}
