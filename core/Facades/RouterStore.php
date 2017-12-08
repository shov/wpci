<?php declare(strict_types=1);

namespace Wpci\Core\Facades;

use Wpci\Core\Contracts\Action;
use Wpci\Core\Contracts\RouteCondition;
use Wpci\Core\Helpers\Facade;

/**
 * Class RouterStore
 * @package Wpci\Core\Facades
 * @method static add(RouteCondition $condition, Action $action, ?string $key = null)
 * @method static bool removeByKey(string $key)
 * @method static makeBinding()
 */
class RouterStore extends Facade
{

    /**
     * Return the facade root object
     * @return mixed
     * @throws \Exception
     */
    public static function getFacadeRoot()
    {
        return App::get(\Wpci\Core\Http\RouterStore::class);
    }
}