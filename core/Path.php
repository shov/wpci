<?php declare(strict_types=1);

namespace Wpci\Core;
use WP;

/**
 * Class Path, all important pathes in the application
 * @package Wpci\Core
 */
class Path
{
    /**
     * Get absolute project directory path
     * @param string $tail
     * @return string
     */
    public function getProjectRoot(string $tail = ''): string
    {
        return realpath(App::PROJECT_ROOT) . $tail;
    }

    /**
     * Get absolute path to config folder
     * @param string $tail
     * @return string
     */
    public function getConfigPath(string $tail = ''): string
    {
        return $this->getProjectRoot() . '/config' . $tail;
    }

    /**
     * Get absolute path to wordpress folder
     * @param string $tail
     * @return string
     */
    public function getWpPath(string $tail = ''): string
    {
        return $this->getProjectRoot() . '/wordpress' . $tail;
    }

    /**
     * Get absolute path to Core folder
     * @param string $tail
     * @return string
     */
    public function getCorePath(string $tail = ''): string
    {
        return $this->getProjectRoot() . '/core' . $tail;
    }

    /**
     * Get absolute path to Application folder
     * @param string $tail
     * @return string
     */
    public function getAppPath(string $tail = ''): string
    {
        return $this->getSrcPath() . '/app' . $tail;
    }

    /**
     * Get absolute path to source folder
     * @param string $tail
     * @return string
     */
    public function getSrcPath(string $tail = ''): string
    {
        return $this->getProjectRoot() . '/src' . $tail;
    }

    /**
     * Get current url with WP global query
     * @return string
     * @throws \Exception
     */
    public function getCurrentUrl(): string
    {
        /** @var WP $wp */
        $wp = \Wpci\Core\Facades\App::get('wp');
        return home_url(add_query_arg([], $wp->request));
    }
}