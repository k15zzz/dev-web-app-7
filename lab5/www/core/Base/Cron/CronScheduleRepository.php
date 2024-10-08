<?php

namespace Core\Base\Cron;

class CronScheduleRepository
{
    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function createTask(TaskCronModel $task)
    {
        $insert = $this->pdo->prepare("
            INSERT INTO wf_cron_schedule(action, execute_at)
            VALUES (:action, :execute_at)
        ");

        return $insert->execute($task->toExecuteFormat(['action', 'execute_at']));
    }

    public function getNextTask(): TaskCronModel
    {
        $taskStat = $this->pdo->prepare("
            SELECT id, 
                action, 
                execute_at, 
                created_at
            FROM wf_cron_schedule
            WHERE completed_at IS NULL 
            ORDER BY id DESC;
        ");
        $taskStat->execute([]);

        if ($taskStat->rowCount() <= 0)
            return new TaskCronModel();

        return new TaskCronModel($taskStat->fetch());
    }

    public function getExecuteTask(): TaskCronModel
    {
        $taskStat = $this->pdo->prepare("
            SELECT id, 
                action, 
                execute_at, 
                created_at
            FROM wf_cron_schedule
            WHERE TRUE AND 
                execute_at < NOW() AND 
                completed_at IS NULL;
        ");
        $taskStat->execute([]);

        if ($taskStat->rowCount() <= 0)
            return new TaskCronModel();

        return new TaskCronModel($taskStat->fetch());
    }

    public function completeTask(TaskCronModel $task)
    {
        $taskStat = $this->pdo->prepare("
            UPDATE wf_cron_schedule
            SET completed_at=NOW()
            WHERE id = :id;
        ");

        return $taskStat->execute($task->toExecuteFormat(['id']));
    }
}