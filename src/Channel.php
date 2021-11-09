<?php

namespace webzop\notifications;

use webzop\notifications\model\UserChannels;
use webzop\notifications\events\NotificationSendEvent;
use Yii;
use yii\base\Exception;


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


    /**
     * Sends the notification for the current channel.
     * It triggers NotificationSendEvent::BEFORE_SEND and NotificationSendEvent::AFTER_SEND events.
     *
     * @return bool if the sending of the message was successful.
     */
    public function send(Notification $notification)
    {
        if (!$this->beforeSend($notification)) {
            return false;
        }
        try {
            $isSuccessful = $this->sendNotification($notification);
        } catch (Exception $exception) {
            $isSuccessful = false;
            Yii::error($exception, __METHOD__);
        }
        $this->afterSend($notification, $isSuccessful);

        return $isSuccessful;
    }

    /**
     * Performs the actual sending of the notification for the channel.
     * @param Notification $notification
     * @return bool
     */
    abstract public function sendNotification($notification);

    /**
     * This method is invoked right before an sms is sent.
     * You may override this method to do last-minute preparation for the message.
     * If you override this method, please make sure you call the parent implementation first.
     * @param Notification $notification
     * @return bool whether to continue sending an email.
     */
    public function beforeSend($notification)
    {
        $event = new NotificationSendEvent(['notification' => $notification]);
        $event->trigger($this, NotificationSendEvent::BEFORE_SEND);

        return $event->isValid;
    }

    /**
     * This method is invoked right after an sms was send.
     * You may override this method to do some postprocessing or logging based on sms send status.
     * If you override this method, please make sure you call the parent implementation first.
     * @param Notification $notification
     * @param bool $isSuccessful
     */
    public function afterSend($notification, $isSuccessful)
    {
        $event = new NotificationSendEvent(['notification' => $notification, 'isSuccessful' => $isSuccessful]);
        $event->trigger($this, NotificationSendEvent::AFTER_SEND);
    }
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
