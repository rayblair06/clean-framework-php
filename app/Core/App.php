<?php

namespace App\Core;

use Closure;

final class App
{
    /**
     * The application's service container.
     *
     * @var Container|null
     */
    protected static ?Container $container = null;

    /**
     * Set the application's service container.
     *
     * @param Container $container The container instance.
     * @return void
     */
    public static function setContainer(Container $container): void
    {
        static::$container = $container;
    }

    /**
     * Get the application's service container.
     *
     * @return Container
     *
     * @throws \Exception If the container has not been set.
     */
    public static function container(): Container
    {
        if (static::$container === null) {
            throw new \Exception('Container has not been set.');
        }

        return static::$container;
    }

    /**
     * Bind a service to the container.
     *
     * @param string $key The service identifier.
     * @param Closure $resolver The closure to resolve the service.
     * @return void
     */
    public static function bind(string $key, Closure $resolver): void
    {
        static::container()->bind($key, $resolver);
    }

    /**
     * Resolve a service from the container.
     *
     * @param string $key The service identifier.
     * @return mixed The resolved service.
     */
    public static function resolve(string $key)
    {
        return static::container()->resolve($key);
    }
}
