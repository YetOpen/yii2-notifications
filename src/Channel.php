<?php

namespace webzop\notifications;

use webzop\notifications\model\UserChannels;
use Yii;


abstract class Channel extends \yii\base\BaseObject
{

    public $id;

    /**
     * @var bool If set `true`, notifications will be sent to the user only if the channel is set.
     */
    protected $requiresUserChannel = false;

    public function __construct($id, $config = [])
    {
        $this->id = $id;
        parent::__construct($config);
    }

    public abstract function send(Notification $notification);

    /**
     * It returns if the notification should be sent for the current channel.
     * It also sets the `$message['to']` property if it does exist on the channel.
     * @param Notification $notification
     * @return bool
     */
    public function shouldSend(Notification $notification)
    {
        // No user the channel can't determine if the notification should be sent or not.
        if(empty($notification->userId)) {
            return true;
        }

        $userChannel = UserChannels::findByChannel($notification->userId, $this->id);
        // The channel was not set for the user the notification should be sent based on the `requiresUserChannel`
        // property for the current channel
        if(is_null($userChannel)) {
            return !$this->requiresUserChannel;
        }

        // If the channel has the message property we set the to key based on the receiver attribute of the notification
        if(isset($this->message) && !empty($userChannel->receiver)) {
            $this->message['to'] = $userChannel->receiver;
        }

        return $userChannel->active;
    }
}
