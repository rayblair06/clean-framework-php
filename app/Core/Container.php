<?php

namespace App\Core;

use Closure;
use Exception;

final class Container
{
    /**
     * The container's bindings.
     *
     * @var Closure[]
     */
    protected array $bindings = [];

    /**
     * The container's shared instances.
     *
     * @var array
     */
    protected array $instances = [];

    /**
     * Bind a resolver to the container.
     *
     * @param string $key The abstract type or identifier.
     * @param Closure $resolver The resolver closure.
     * @return void
     */
    public function bind(string $key, Closure $resolver): void
    {
        $this->bindings[$key] = $resolver;
    }

    /**
     * Bind a singleton instance in the container.
     *
     * @param string $key The abstract type or identifier.
     * @param mixed $instance The instance to bind as a singleton.
     * @return void
     */
    public function singleton(string $key, $instance): void
    {
        $this->instances[$key] = $instance;
    }

    /**
     * Resolve a binding from the container.
     *
     * @param string $key The abstract type or identifier to resolve.
     * @return mixed The resolved instance.
     *
     * @throws Exception If the binding does not exist.
     */
    public function resolve(string $key)
    {
        if (isset($this->instances[$key])) {
            return $this->instances[$key];
        }

        if (!array_key_exists($key, $this->bindings)) {
            throw new Exception("No binding found for {$key}.");
        }

        $resolved = call_user_func($this->bindings[$key], $this);

        return $resolved;
    }
}
