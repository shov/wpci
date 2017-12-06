<?php declare(strict_types=1);

namespace Wpci\Core\Facades;

use Wpci\Core\Http\Action;
use Wpci\Core\Http\WpQueryCondition;

/**
 * Gateway to receive some entities from container
 * Class AppMake
 * @package Wpci\Core\Facades
 */
class AppMake
{
    public static function Action($reference): Action
    {
        /**
         * TODO: change this behaviour
         */
        return new Action($reference);
    }

    public static function WpQueryCondition(): WpQueryCondition
    {
        return App::get(WpQueryCondition::class);
    }
}