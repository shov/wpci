<?php declare(strict_types=1);

namespace Wpci\Core\Http;

use Wpci\Core\Contracts\Action;
use Wpci\Core\Contracts\RouteCondition;
use Wpci\Core\Http\WpQueryCondition;

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
        $this->sortByConditionPriority();

        foreach ($this->routes as $route) {
            /** @var RouteCondition $condition */
            $condition = $route['condition'];

            /** @var Action $action */
            $action = $route['action'];

            $condition->bindWithAction($action);
        }
    }

    /**
     * Sort routes by priority
     */
    protected function sortByConditionPriority()
    {
        //Split
        $wpQueryRoutes = [];
        $otherRoutes = [];

        foreach ($this->routes as $key => $curRoute)
        {
            if($curRoute['condition'] instanceof WpQueryCondition) {
                if(!is_numeric($key)) {
                    $wpQueryRoutes[$key] = $curRoute;
                } else {
                    $wpQueryRoutes[] = $curRoute;
                }
            } else {
                if(!is_numeric($key)) {
                    $otherRoutes[$key] = $curRoute;
                } else {
                    $otherRoutes[] = $curRoute;
                }
            }
        }

        //Sort wpQueryRoutes
        uasort($wpQueryRoutes, function($a, $b) {
            /** @var WpQueryCondition $aCondition */
            $aCondition = $a['condition'];

            /** @var WpQueryCondition $bCondition */
            $bCondition = $b['condition'];

            $aAnyFactor = (int)(!in_array('any' ,$aCondition->getKeywords()));
            $bAnyFactor = (int)(!in_array('any' ,$bCondition->getKeywords()));

            if($aAnyFactor !== $bAnyFactor) {
                return $bAnyFactor - $aAnyFactor;
            }

            $aQueryParamsCount = count($aCondition->getQueryParams());
            $bQueryParamsCount = count($bCondition->getQueryParams());

            $qpFactor = $aQueryParamsCount - $bQueryParamsCount;

            if(0 !== $qpFactor) {
                return -$qpFactor;
            }

            $aKeywordsCount = count($aCondition->getKeywords());
            $bKeywordsCount = count($bCondition->getKeywords());

            $kwFactor = $aKeywordsCount - $bKeywordsCount;

            return $kwFactor;
        });

        //Glue back
        $this->routes = array_merge($otherRoutes, $wpQueryRoutes);
    }
}