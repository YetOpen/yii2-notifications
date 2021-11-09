<?php

namespace webzop\notifications\commands;


use yii\console\Exception;
use yii\queue\cli\Command;

class CommandController extends Command
{
    /**
     * @var Worker
     */
    public $worker;
    /**
     * @var string
     */
    public $defaultAction = 'run';

    public $isolate = false;

    public function beforeAction($action)
    {
        $this->worker = \Yii::createObject([
            'class' => Worker::class,
            'module' => $this->module,
        ]);
        return parent::beforeAction($action);
    }

    /**
     * @inheritdoc
     */
    protected function isWorkerAction($actionID)
    {
        return in_array($actionID, ['run', 'listen'], true);
    }

    /**
     * Runs all jobs from db-queue.
     * It can be used as cron job.
     *
     * @return null|int exit code.
     */
    public function actionRun()
    {
        return $this->worker->run(false);
    }

    /**
     * Listens db-queue and runs new jobs.
     * It can be used as daemon process.
     *
     * @param int $timeout number of seconds to sleep before next reading of the queue.
     * @throws Exception when params are invalid.
     * @return null|int exit code.
     */
    public function actionListen($timeout = 3)
    {
        if (!is_numeric($timeout)) {
            throw new Exception('Timeout must be numeric.');
        }
        if ($timeout < 1) {
            throw new Exception('Timeout must be greater than zero.');
        }

        return $this->worker->run(true, $timeout);
    }
}