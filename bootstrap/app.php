<?php declare(strict_types=1);

use Monolog\Formatter\HtmlFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Wpci\Core\App;
use Wpci\Core\Facades\Path;

$app = new App(__DIR__ . '/..');


file_put_contents(Path::getProjectRoot('/debug.log.html'), ''); //Cleaning TODO: set in config

$app->setLogger((new Logger('general'))
    ->pushHandler(
        (new StreamHandler(
            Path::getProjectRoot('/debug.log.html'), //Cleaning TODO: set in config
            Logger::DEBUG))
            ->setFormatter(
                new HtmlFormatter()
            )
    ));

require Path::getConfigPath('/routes.php');

$app->run();