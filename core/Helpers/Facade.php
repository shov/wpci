<?php declare(strict_types=1);

namespace Wpci\Core\Helpers;

/**
 * Class Facade
 * @package Wpci\Core\Helpers
 */
abstract class Facade
{
    /**
     * Redirect calls
     * @param $method
     * @param $args
     * @return mixed
     */
    public static function __callStatic($method, $args)
    {
        $instance = static::getFacadeRoot();

        if (! $instance) {
            throw new \RuntimeException('A facade root has not been set.');
        }

        return $instance->$method(...$args);
    }

    /**
     * Return the facade root object
     * @return mixed
     */
    abstract public static function getFacadeRoot();
}