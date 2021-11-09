<?php

namespace webzop\notifications\events;

use webzop\notifications\Notification;
use yii\base\Event;

class NotificationSendEvent extends Event
{
    const BEFORE_SEND = 'beforeSend';
    const AFTER_SEND = 'beforeSend';

    /**
     * @var bool whether the model is in valid status. Defaults to true.
     * A model is in valid status if it passes validations or certain checks.
     */
    public $isValid = true;

    /**
     * @var bool if notification was sent successfully.
     */
    public $isSuccessful;

    /**
     * @var Notification $notification
     */
    public $notification;
}