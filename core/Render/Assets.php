<?php declare(strict_types=1);

namespace Wpci\Core\Render;

/**
 * Class Assets, help to provide assets (js, css, images...) to wordpress theme output
 * @package Wpci\Core\Render
 */
class Assets
{
    /**
     * @param string $key
     * @param null|string $path
     * @param array|null $deps
     * @param null|string $ver
     * @return Assets
     */
    public function registerStyle(
        string $key,
        ?string $path = null,
        array $deps = [],
        ?string $ver = null): Assets
    {
        add_action('wp_enqueue_scripts', function () use ($key, $path, $deps, $ver) {
            if (is_null($path)) {
                wp_enqueue_style($key);
            } else {
                (empty($ver)) ? $ver = '' : $ver = '?' . $ver;

                wp_register_style($key, $path, $deps, $ver);
                wp_enqueue_style($key);
            }
        });
        
        return $this;
    }

    /**
     * @param string $key
     * @param null|string $path
     * @param array|null $deps
     * @param null|string $ver
     * @return Assets
     */
    public function registerFooterScript(
        string $key,
        ?string $path = null,
        array $deps = [],
        ?string $ver = null): Assets
    {
        add_action('wp_enqueue_scripts', function () use ($key, $path, $deps, $ver) {
            if (is_null($path)) {
                wp_enqueue_script($key);
            } else {
                (empty($ver)) ? $ver = '' : $ver = '?' . $ver;

                wp_register_script($key, $path, $deps, $ver, true);
                wp_enqueue_script($key);
            }
        });

        return $this;
    }

    /**
     * @param string $key
     * @param null|string $path
     * @param array|null $deps
     * @param null|string $ver
     * @return Assets
     */
    public function registerHeaderScript(
        string $key,
        ?string $path = null,
        array $deps = [],
        ?string $ver = null): Assets
    {
        add_action('wp_enqueue_scripts', function () use ($key, $path, $deps, $ver) {
            if (is_null($path)) {
                wp_enqueue_script($key);
            } else {
                (empty($ver)) ? $ver = '' : $ver = '?' . $ver;

                wp_register_script($key, $path, $deps, $ver, false);
                wp_enqueue_script($key);
            }
        });

        return $this;
    }

    /**
     * @param string $key
     * @param string $name
     * @param null $value
     * @return Assets
     */
    public function addVariableToScript(string $key, string $name, $value = null): Assets
    {
        if (empty($key) || empty($name)) {
            throw new \InvalidArgumentException("Wrong name of script or variable to register!");    
        }
        
        add_action('wp_enqueue_scripts', function () use ($key, $name, $value) {
            wp_localize_script($key, $name, $value);
        });

        return $this;
    }
}