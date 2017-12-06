<?php declare(strict_types=1);

namespace Wpci\Core;
use Symfony\Component\DependencyInjection\Container;
use wpdb;

/**
 * Front controller for already routed request from wordpress
 * Class WpFrontController
 * @package Wpci\Core
 */
class WpFrontController
{
    /** @var Container  */
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Called when time to choose the template
     */
    public function routing()
    {
        static $started = false;
        if(!$started) {
            $started = true;

            /** TODO: bind routes here */
        }
    }
}