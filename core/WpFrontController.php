<?php declare(strict_types=1);

namespace Wpci\Core;

use Wpci\Core\Facades\Path;
use Wpci\Core\Facades\RouterStore;

/**
 * Front controller for request from wordpress
 * Class WpFrontController
 * @package Wpci\Core
 */
class WpFrontController
{
    /**
     * Start gluing route conditions with actions
     */
    public function routing()
    {
        require_once Path::getConfigPath('/routes.php');

        RouterStore::makeBinding();
    }
}