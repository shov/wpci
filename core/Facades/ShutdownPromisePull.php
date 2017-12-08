<?php declare(strict_types=1);

namespace Wpci\Core\Facades;

use Wpci\Core\Helpers\Facade;

/**
 * Class ShutdownPromisePull
 * @package Wpci\Core\Facades
 *
 * @method static addPromise(callable $promise, ?int $priority = null)
 * @method static callAllPromises()
 */
class ShutdownPromisePull extends Facade
{
    /**
     * Return the facade root object
     * @return mixed
     * @throws \Exception
     */
    public static function getFacadeRoot()
    {
        return App::get('promise-pull.shutdown');
    }
}