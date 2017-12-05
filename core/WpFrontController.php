<?php declare(strict_types=1);

namespace Wpci\Core;

/**
 * Front controller for already routed request from wordpress
 * Class WpFrontController
 * @package Wpci\Core
 */
class WpFrontController
{
    public function __construct()
    {
        //add_action('template_redirect', [$this, 'routingCallback']);
    }

    /**
     * Called when time to choose the template
     */
    public function routing()
    {
        echo "Hey there!";
    }
}