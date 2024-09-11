<?php

namespace Core\Base\Cron;

use Core\Base\Model;

class TaskCronModel extends Model
{
    protected $id;
    protected $action;
    protected $execute_at;
    protected $completed_at;

    protected array $required = [
        'action',
        'execute_at'
    ];

    public function getId()
    {
        return $this->id;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function getExecuteAt()
    {
        return $this->execute_at;
    }

    public static function getCreateScheduleCron()
    {
        return new TaskCronModel([
            'action' => 'App\Cron\CreateScheduleCron',
            'execute_at' => (new CronScheduler("0 0 * * *"))->getNextRunDate()->format("Y-m-d H:i:s")
        ]);
    }

    public static function getGetTwoWeatherForecastCron()
    {
        return new TaskCronModel([
            'action' => 'App\Cron\GetTwoWeatherForecastCron',
            'execute_at' => (new CronScheduler("0 7 * * *"))->getNextRunDate()->format("Y-m-d H:i:s")
        ]);
    }

    public static function getSendTwoActirovkaCron()
    {
        return new TaskCronModel([
            'action' => 'App\Cron\SendTwoActirovkaCron',
            'execute_at' => (new CronScheduler("0 8 * * *"))->getNextRunDate()->format("Y-m-d H:i:s")
        ]);
    }

    public static function getGetOneForecastCron()
    {
        return new TaskCronModel([
            'action' => 'App\Cron\GetOneForecastCron',
            'execute_at' => (new CronScheduler("0 19 * * *"))->getNextRunDate()->format("Y-m-d H:i:s")
        ]);
    }

    public static function getSendOneActirovkaCron()
    {
        return new TaskCronModel([
            'action' => 'App\Cron\SendOneActirovkaCron',
            'execute_at' => (new CronScheduler("0 20 * * *"))->getNextRunDate()->format("Y-m-d H:i:s")
        ]);
    }
}