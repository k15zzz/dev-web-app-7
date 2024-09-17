<?php

namespace Core;

use Core\Base\Container;
use Exception;
use ReflectionClass;

/**
 * Главный класс всего приложения.
 * Выполняет главную логику всей надстройки.
 */
class App
{
    private Container $container;

    /**
     * @param Container $container - хранит классы, необходимые для создания слоев приложения
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Инициализирует класс.
     * Приложение само находит по конструктору необходимые зависимости и подключает их.
     * Если зависимости не найдены, то она их создает, спускаясь рекурсивно.
     * Работает не только с __construct, но и с любым методом класса.
     *
     * @param string $class - название класса, которое используем для вызова функции
     * @param string $method - метод, который надо вызвать в классе
     * @return mixed - возвращает результат вызова метода в классе
     * @throws Exception
     */
    public function make(string $class, string $method = '__construct')
    {
        if (!class_exists($class)) {
            throw new Exception("Class $class not found");
        }

        if ($this->container->in($class)) {
            return $this->invokeMethod($this->container->get($class), $method);
        }

        $reflectionClass = new ReflectionClass($class);

        // Создаем класс через конструктор
        $constructor = $reflectionClass->getConstructor();
        $instance = $constructor ? $this->createInstance($reflectionClass, $constructor) : new $class();

        // Сохраняем в контейнере созданный экземпляр класса
        $this->container->set($class, $instance);

        // Вызываем нужный метод, если это не конструктор
        if ($method !== '__construct') {
            return $this->invokeMethod($instance, $method);
        }

        return $instance;
    }

    /**
     * Создает экземпляр класса, разрешая его зависимости.
     *
     * @param ReflectionClass $reflectionClass - отражение класса
     * @param \ReflectionMethod $constructor - конструктор класса
     * @return mixed - возвращает созданный экземпляр класса
     * @throws Exception
     */
    private function createInstance(ReflectionClass $reflectionClass, \ReflectionMethod $constructor)
    {
        $params = $this->resolveParameters($constructor);
        return $reflectionClass->newInstanceArgs($params);
    }

    /**
     * Вызывает указанный метод на экземпляре класса.
     *
     * @param object $instance - экземпляр класса
     * @param string $method - метод, который нужно вызвать
     * @return mixed - результат вызова метода
     * @throws Exception
     */
    private function invokeMethod(object $instance, string $method)
    {
        if (!method_exists($instance, $method)) {
            throw new Exception("Method $method not found in class " . get_class($instance));
        }

        $reflectionMethod = new \ReflectionMethod($instance, $method);
        $params = $this->resolveParameters($reflectionMethod);

        return $reflectionMethod->invokeArgs($instance, $params);
    }

    /**
     * Разрешает параметры метода или конструктора, рекурсивно создавая зависимости.
     *
     * @param \ReflectionMethod $method - метод или конструктор
     * @return array - массив разрешенных параметров
     * @throws Exception
     */
    private function resolveParameters(\ReflectionMethod $method): array
    {
        $params = [];

        foreach ($method->getParameters() as $param) {
            $type = $param->getType();

            if (!$type) {
                throw new Exception("Parameter {$param->getName()} does not have a type hint");
            }

            $typeName = $type->getName();

            if ($this->isInterface($typeName) && $param->isVariadic()) {
                $variadicImplementsTypeName = $method->getDeclaringClass()->getName()::getVariadicImplements($typeName);

                foreach ($variadicImplementsTypeName as $variadicImplementTypeName) {
                    if (!$this->container->in($variadicImplementTypeName)) {
                        $this->container->set($variadicImplementTypeName, $this->make($variadicImplementTypeName));
                    }
                    $params[] = $this->container->get($variadicImplementTypeName);
                }

                continue;
            }

            if ($this->isInterface($typeName)) {
                $implementTypeName = $method->getDeclaringClass()->getName()::getImplement($typeName);

                if (!$implementTypeName) {
                    throw new Exception("Interface not implement $typeName or not used in " . $method->getDeclaringClass()->getName());
                }

                $typeName = $implementTypeName;
            }

            if (!$this->container->in($typeName)) {
                $this->container->set($typeName, $this->make($typeName));
            }

            $params[] = $this->container->get($typeName);
        }

        return $params;
    }

    private function isInterface($class): bool
    {
        if (!class_exists($class) && !interface_exists($class)) {
            return false;
        }

        $reflection = new ReflectionClass($class);
        return $reflection->isInterface();
    }
}
