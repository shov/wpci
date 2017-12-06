<?php declare(strict_types=1);

namespace Wpci\Core\Facades;

use Wpci\Core\App as TheApp;

class App
{
    public static function get($id)
    {
        return TheApp::getInstance()->getContainer()->get($id);
    }
}