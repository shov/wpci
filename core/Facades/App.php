<?php declare(strict_types=1);

namespace Wpci\Core\Facades;

use Wpci\Core\App as TheApp;

class App
{
    /**
     * Get entity from container
     * @param $id
     * @return object
     * @throws \Exception
     */
    public static function get($id)
    {
        return TheApp::getInstance()->getContainer()->get($id);
    }
}