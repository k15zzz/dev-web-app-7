<?php

namespace Core\Base\Cron;

use DateTime;
use InvalidArgumentException;

class CronScheduler
{
    private $minute;
    private $hour;
    private $dayOfMonth;
    private $month;
    private $dayOfWeek;

    public function __construct($cronString) {
        $this->parseCronString($cronString);
    }

    private function parseCronString($cronString) {
        // Разбиваем строку на части
        $parts = preg_split('/\s+/', $cronString);
        if (count($parts) !== 5) {
            throw new InvalidArgumentException("Invalid cron string: $cronString");
        }

        list($this->minute, $this->hour, $this->dayOfMonth, $this->month, $this->dayOfWeek) = $parts;
    }

    public function getNextRunDate($fromDate = 'now') {
        $nextRun = new DateTime($fromDate);

        // Проверяем минуты
        if ($this->minute !== '*') {
            $nextRun->setTime($nextRun->format('H'), $this->minute);
        }

        // Проверяем часы
        if ($this->hour !== '*') {
            $nextRun->setTime($this->hour, $nextRun->format('i'));
        }

        // Проверяем дни месяца
        if ($this->dayOfMonth !== '*') {
            $nextRun->setDate($nextRun->format('Y'), $nextRun->format('m'), $this->dayOfMonth);
        }

        // Проверяем месяцы
        if ($this->month !== '*') {
            $nextRun->setDate($nextRun->format('Y'), $this->month, $nextRun->format('d'));
        }

        // Проверяем дни недели
        if ($this->dayOfWeek !== '*') {
            // Это более сложная логика, которая требует дополнительной обработки
            // Здесь просто пример, который не учитывает текущий день недели
            $nextRun->modify("next " . $this->getDayOfWeek($this->dayOfWeek));
        }

        // Если вычисленная дата уже прошла, добавляем 1 день
        if ($nextRun < new DateTime($fromDate)) {
            $nextRun->modify('+1 day');
        }

        return $nextRun;
    }

    private function getDayOfWeek($dayOfWeek) {
        $days = [
            0 => 'Sunday',
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
        ];

        return $days[$dayOfWeek] ?? 'Sunday';
    }
}