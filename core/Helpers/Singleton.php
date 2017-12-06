<?php declare(strict_types=1);

namespace Wpci\Core\Helpers;

/**
 * Trait Singleton
 * @package Wpci\Core\Helpers
 */
trait Singleton
{
    private static $_inst;

    public static function getInstance()
    {
        return isset(static::$_inst)
            ? static::$_inst
            : static::$_inst = new static;
    }

    private function __construct()
    {
    }

    final private function __wakeup()
    {
    }

    final private function __clone()
    {
    }
}