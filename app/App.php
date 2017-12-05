<?php declare(strict_types=1);

namespace Wpci\App;

require __DIR__ . '/../vendor/autoload.php';

/**
 * Class App
 * @package Wpci\App
 */
final class App
{
    /**
     * Run the App
     */
    public function run()
    {
        echo "Here we go!";
        die();
    }
}

return new App();