<?php declare(strict_types=1);

namespace Wpci\Core;
use Wpci\Core\Contracts\Action;
use Wpci\Core\Contracts\RouteCondition;

/**
 * Class Route
 * @package Wpci\Core
 */
class RouterStore
{
    protected $routes = [];

    /**
     * Add route
     * @param RouteCondition $condition
     * @param Action $action
     * @param null|string $key
     */
    public function add(RouteCondition $condition, Action $action, ?string $key = null)
    {
        if (empty($key) || is_numeric($key)) {
            $this->routes[] = compact('condition', 'action');
        } else {

            $this->routes[$key] = compact('condition', 'action');
        }
    }

    /**
     * Remove route using the key
     * @param string $key
     * @return bool, will return true if remove rout successfully
     */
    public function removeByKey(string $key): bool
    {
        if (isset($this->routes[$key])) {
            unset($this->routes[$key]);
            return true;
        }
        return false;
    }

    /**
     * Bind all routes' condition-action couples
     */
    public function makeBinding()
    {
        foreach ($this->routes as $route) {
            /** @var RouteCondition $condition */
            $condition = $route['condition'];

            /** @var Action $action */
            $action = $route['action'];

            $condition->bindWithAction($action);
        }
    }
}