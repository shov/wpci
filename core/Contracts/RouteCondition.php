<?php declare(strict_types=1);

namespace Wpci\Core\Contracts;

/**
 * Interface RouteCondition
 * @package Wpci\Core\Contracts
 */
interface RouteCondition
{
    public function bindWithAction(Action $action);
}