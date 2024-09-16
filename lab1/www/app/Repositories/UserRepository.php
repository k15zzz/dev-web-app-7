<?php

namespace App\Repositories;

use App\Repositories\Exceptions\UserNotAuthException;
use Core\Base\Layers\Repository;

class UserRepository extends Repository
{
    private const USER_KEY = 'user';

    private const USERS = [
        'k15zzz' => 'local.work.ru'
    ];

    /**
     * Проверяет наличие пользователя
     *
     * @param string $name
     * @param string $dbName
     * @return bool
     */
    public function check(string $name, string $dbName): bool
    {
        return array_key_exists($name, $this::USERS) && $this::USERS[$name] === $dbName;
    }

    /**
     * @return string
     * @throws UserNotAuthException
     */
    public function getAuth(): string
    {
        if (!$_SESSION) {
            throw new UserNotAuthException();
        }

        if (!array_key_exists($this::USER_KEY, $_SESSION)) {
            throw new UserNotAuthException();
        }

        if (!array_key_exists($_SESSION[$this::USER_KEY], $this::USERS)) {
            throw new UserNotAuthException();
        }

        return $_SESSION[$this::USER_KEY];
    }

    /**
     * @param $username
     * @return void
     */
    public function auth($username): void
    {
        $_SESSION[$this::USER_KEY] = $username;
    }

    /**
     * @return void
     */
    public function logout(): void
    {
        $_SESSION[$this::USER_KEY] = null;
    }
}