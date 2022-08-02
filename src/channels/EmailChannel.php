<?php

namespace webzop\notifications\channels;

use webzop\notifications\model\UserChannels;
use webzop\notifications\Module;
use yetopen\helpers\ArrayHelper;
use Yii;
use yii\di\Instance;
use yii\base\InvalidConfigException;
use webzop\notifications\Channel;
use webzop\notifications\Notification;

class EmailChannel extends Channel
{
    /**
     * @var array the configuration array for creating a [[\yii\mail\MessageInterface|message]] object.
     * Note that the "to" option must be set, which specifies the destination email address(es).
     */
    public $message = [];

    /**
     * @var \yii\mail\MailerInterface|array|string the mailer object or the application component ID of the mailer object.
     * After the EmailChannel object is created, if you want to change this property, you should only assign it
     * with a mailer object.
     * Starting from version 2.0.2, this can also be a configuration array for creating the object.
     */
    public $mailer = 'mailer';


    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->mailer = Instance::ensure($this->mailer, 'yii\mail\MailerInterface');
    }

    /**
     * {@inheritdoc}
     */
    public function sendNotification($notification)
    {
        $message = $this->composeMessage($notification);
        return $message->send($this->mailer);
    }

    /**
     * Composes a mail message with the given body content.
     * @param \webzop\notifications\Notification $notification the body content
     * @return \yii\mail\MessageInterface $message
     * @throws InvalidConfigException
     */
    protected function composeMessage($notification)
    {
        $this->mailer->getView()->params['language'] = $notification->language;
        $message = $this->mailer->compose('@vendor/webzop/yii2-notifications/src/views/notification/mail', [
            'content' => (string)$notification->getDescription(),
            'language' => $notification->language,
        ]);
        Yii::configure($message, $this->message);
        if(empty($message->getTo())){
            $message->setTo($this->getEmailTo($notification));
        }
        Yii::debug("Sending email to ".implode(', ', array_keys($message->getTo())), __METHOD__);
        $message->setSubject($notification->getTitle());
        foreach ($notification->getAttachments() as $attachment) {
            $message->attachContent(file_get_contents($attachment['path']), [
                'fileName' => $attachment['filename'],
                'contentType' => $attachment['type']
            ]);
        }
        return $message;
    }

    /**
     * Gets the email for the user of the given notification.
     * @param $notification
     * @return mixed
     * @throws InvalidConfigException
     */
    protected function getEmailTo($notification)
    {
        // Checking if set as a user notification channel
        $userChannel = UserChannels::findByChannel($notification->userId, $this->id);
        if($userChannel && $userChannel->active) {
            return $userChannel->receiver;
        }
        // Checking in case it's available directly on the user
        if(
            ($user = Module::getInstance()->identityClass::findOne($notification->userId)) &&
            ($email = ArrayHelper::getValue($user, 'email'))
        ) {
            return $email;
        }
        throw new InvalidConfigException('The "to" option must be set in EmailChannel::message.');
    }
}
