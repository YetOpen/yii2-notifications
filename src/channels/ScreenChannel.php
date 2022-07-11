<?php

namespace webzop\notifications\channels;

use Yii;
use webzop\notifications\Channel;
use webzop\notifications\Notification;

class ScreenChannel extends Channel
{
    /**
     * {@inheritdoc}
     */
    public function sendNotification($notification)
    {
        $notification->getTitle();
        // The notification will be shown via JS
        return TRUE;
    }

}
