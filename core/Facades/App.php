<?php declare(strict_types=1);

namespace Wpci\Core\Facades;

use Symfony\Component\DependencyInjection\Container;
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

    /**
     * Get the container
     * @return Container
     */
    public static function getContainer(): Container
    {
        return TheApp::getInstance()->getContainer();
    }

    public static function environment(string $var)
    {
        return TheApp::getInstance()->getEnvVar($var);
    }
}