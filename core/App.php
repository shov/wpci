<?php declare(strict_types=1);

namespace Wpci\Core;

use Monolog\Formatter\HtmlFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

require __DIR__ . '/../vendor/autoload.php';

/**
 * Class App
 * @package Wpci\App
 */
final class App
{
    private const PROJECT_ROOT = __DIR__ . '/..';

    /** @var ContainerBuilder */
    protected $container;

    public function __construct()
    {
        $this->container = new ContainerBuilder();

        /** @var Logger $logger */
        $logger = new Logger('general');

        $debugHandler = new StreamHandler($this->getProjectRoot() . '/debug.log.html', $logger::DEBUG);
        $debugHandler->setFormatter(new HtmlFormatter());
        $logger->pushHandler($debugHandler);

        $eerorHandler = new StreamHandler($this->getProjectRoot() . '/error.log.html', $logger::ERROR);
        $eerorHandler->setFormatter(new HtmlFormatter());
        $logger->pushHandler($eerorHandler);

        $this->container->set('logger', $logger);

        $serviceConfigLoader = new YamlFileLoader($this->container, new FileLocator($this->getConfigPath()));
        $serviceConfigLoader->load('services.yaml');

        $this->container->compile();
    }

    /**
     * Run the App
     */
    public function run()
    {
        try {
            /** @var WpFrontController $wpFrontController */
            $wpFrontController = $this->container->get(WpFrontController::class);
            $wpFrontController->routing();

        } catch (\Throwable $e) {
            /** @var Logger $logger */
            $logger = $this->container->get('logger');
            $logger->error($e->getMessage(), [
                'stacktrace' => $e->getTrace(),
            ]);
        }
    }

    /**
     * Get absolute project directory path
     * @return string
     */
    public function getProjectRoot(): string
    {
        return realpath(self::PROJECT_ROOT);
    }

    /**
     * Get absolute path to config folder
     * @return string
     */
    public function getConfigPath(): string
    {
        return $this->getProjectRoot() . '/config';
    }

    /**
     * Get absolute path to wordpress folder
     * @return string
     */
    public function getWpPath(): string
    {
        return $this->getProjectRoot() . '/wordpress';
    }

    /**
     * Get absolute path to Core folder
     * @return string
     */
    public function getCorePath(): string
    {
        return $this->getProjectRoot() . '/core';
    }

    /**
     * Get absolute path to Application folder
     * @return string
     */
    public function getAppPath(): string
    {
        return $this->getSrcPath() . '/app';
    }

    /**
     * Get absolut path to source folder
     * @return string
     */
    public function getSrcPath(): string
    {
        return $this->getProjectRoot() . '/src';
    }
}

return new App();