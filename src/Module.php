<?php

namespace webzop\notifications;

use webzop\notifications\model\Notifications;
use Yii;
use yii\base\InvalidArgumentException;
use yii\console\Application as ConsoleApplication;
use yii\base\BootstrapInterface;
use yii\di\Instance;
use yii\helpers\VarDumper;
use yii\mutex\Mutex;

class Module extends \yii\base\Module
{

    public $channels = [];

    protected $_channels = [];

    public $controllerNamespace = 'webzop\notifications\controllers';

    public $attachmentsPath = '@app/documents/notifications';

    public $identityClass;

    public $mutex;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        if($this->attachmentsPath && !file_exists(Yii::getAlias($this->attachmentsPath))) {;
            mkdir(Yii::getAlias($this->attachmentsPath), 0775, true);
        }
        if($this->mutex) {
            $this->mutex = Instance::ensure($this->mutex, Mutex::class);
        }
        parent::init();
    }

    /**
     * Send a notification to all channels
     *
     * @param Notification $notification
     * @param array|null $channels
     * @return bool If the sending was successful or not.
     */
    public function send($notification, array $channels = null){
        
        if($channels === null){
            $channels = array_keys($this->channels);
        }
        
        foreach ((array)$channels as $channelId) {
            
            $channel = $this->getChannel($channelId);
            $type = $notification->ensureType();
            if(!$notification->shouldSend($channel) || !$channel->shouldSend($notification)){
                continue;
            }

            $model = new Notifications([
                'notification' => $notification,
                'channel' => $channelId,
                'type' => $type,
            ]);
            
            if(!$model->save()) {
                Yii::error('Cannot save notifications: '.VarDumper::dumpAsString($model->errors), __METHOD__);
                return FALSE;
            }

            // The notification has to be sent in the future
            if($notification->sendAt > date('Y-m-d H:i:s')) {
                continue;
            }

            $handle = 'to'.ucfirst($channelId);
            try {
                if($notification->hasMethod($handle)){
                    $success = call_user_func([clone $notification, $handle], $channel);
                }
                else {
                    $success = $channel->send(clone $notification);
                }
                // Notification was successfully sent with this channel it can be set in the database
                // using updateAttributes since validation errors could cause the notification to be sent continuously.
                if($success) {
                    $model->updateAttributes(['sent' => true]);
                }
            } catch (\Exception $e) {
                if (YII_DEBUG) {
                    throw $e;
                }
                Yii::warning("Notification sent by channel '$channelId' has failed: " . $e->getMessage(), __METHOD__);
                Yii::warning($e, __METHOD__);
            }

        }
        return TRUE;
    }

    /**
     * Gets the channel instance
     *
     * @param string $id the id of the channel
     * @param bool $forceReload forces the load of the channel even if it was cached, defaults to `false`
     * @return Channel|null return the channel
     * @throws InvalidArgumentException
     */
    public function getChannel($id, $forceReload = false){
        if(!isset($this->channels[$id])){
            throw new InvalidArgumentException("Unknown channel '{$id}'.");
        }

        if (!isset($this->_channels[$id]) || $forceReload) {
            $this->_channels[$id] = $this->createChannel($id, $this->channels[$id]);
        }

        return $this->_channels[$id];
    }

    protected function createChannel($id, $config){
        return Yii::createObject($config, [$id]);
    }

}
