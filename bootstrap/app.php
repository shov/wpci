<?php declare(strict_types=1);

use App\App;
use Monolog\Formatter\HtmlFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Wpci\Core\Core;
use Wpci\Core\Facades\Path;

$core = new Core();

(new App(__DIR__ . DIRECTORY_SEPARATOR . '..'))
    ->beforeRun(function (Core $core) {

        /** @var string $logPath */
        $logPath = $core->env('DEBUG_LOG', '/debug.log.html');

        $core->setLogger((new Logger('debug'))
            ->pushHandler(
                (new StreamHandler(
                    Path::getProjectRoot($logPath),
                    Logger::DEBUG))
                    ->setFormatter(
                        new HtmlFormatter()
                    )
            ));
    })
    ->handle($core);