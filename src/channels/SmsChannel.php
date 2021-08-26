<?php

namespace webzop\notifications\channels;

use webzop\notifications\Notification;
use yetopen\smssender\SmsSenderInterface;
use Yii;
use yii\base\InvalidConfigException;
use webzop\notifications\Channel;
use yii\di\Instance;

class SmsChannel extends Channel
{
    protected $requiresUserChannel = true;

    /**
     * @var array the configuration array for creating a [[\yii\mail\MessageInterface|message]] object.
     * Note that the "to" option must be set, which specifies the destination email address(es).
     */
    public $message = [];

    /**
     * @var SmsSenderInterface|array|string the object or the application component ID of the sms inteface object.
     * After the SMSChannel object is created, if you want to change this property, you should only assign it
     * with a mailer object.
     */
    public $sender;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->sender = Instance::ensure($this->sender, SmsSenderInterface::class);
    }

    /**
     * Sends a notification in this channel.
     */
    public function send(Notification $notification)
    {
        if(empty($this->message['to'])){
            throw new InvalidConfigException('The "to" option must be set in SmsChannel::message.');
        }
        if(!is_array($this->message['to'])) {
            $this->message['to'] = [$this->message['to']];
        }

        // Text messages have a maximum length if it's exceeded it will be sent split into multiple sms
        $maxLength = $this->sender->maxTextLength;
        foreach (str_split((string)$notification->description, $maxLength) as $text) {
            $this->sender->send(
                $this->message['to'],
                $text
            );
        }
    }
}