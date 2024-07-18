<?php

namespace webzop\notifications\channels;

use webzop\notifications\Channel;

class ScreenChannel extends Channel
{
    /**
     * {@inheritdoc}
     */
    public function sendNotification($notification)
    {
        // The notification will be shown via JS
        return true;
    }
}
