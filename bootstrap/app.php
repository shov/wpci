<?php declare(strict_types=1);

use App\App;
use Monolog\Formatter\HtmlFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Wpci\Core\Core;
use Wpci\Core\Facades\Path;

$core = new Core(__DIR__ . '/..');


file_put_contents(Path::getProjectRoot('/debug.log.html'), ''); //Cleaning TODO: set in config

$core->setLogger((new Logger('general'))
    ->pushHandler(
        (new StreamHandler(
            Path::getProjectRoot('/debug.log.html'), //Cleaning TODO: set in config
            Logger::DEBUG))
            ->setFormatter(
                new HtmlFormatter()
            )
    ));

(new App())->handle($core);