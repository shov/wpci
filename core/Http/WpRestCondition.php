<?php declare(strict_types=1);

namespace Wpci\Core\Http;

use WP_REST_Request;
use Wpci\Core\Contracts\Action;
use Wpci\Core\Contracts\RouteCondition;
use Wpci\Core\Exceptions\RoutingException;

/**
 * Class WpRestCondition
 * @package Wpci\Core\Http
 */
class WpRestCondition implements RouteCondition
{
    const SUPPORTED_METHODS = ['GET', 'POST', 'PUT', 'DELETE',];

    const DEFAULT_PREFIX = 'common/v2';

    /**
     * @var string
     */
    protected static $urlPrefixBracket = '';

    /**
     * @param string $urlPrefix
     * @param callable $closure
     */
    public static function prefix(string $urlPrefix, callable $closure)
    {
        static::$urlPrefixBracket .= $urlPrefix;

        $closure();

        static::$urlPrefixBracket = substr(static::$urlPrefixBracket, 0, -strlen($urlPrefix));
    }

    /**
     * Build GET RouteCondition
     * @param string $url
     * @param array $args
     * @param string $urlPrefix
     * @return WpRestCondition
     * @throws RoutingException
     */
    public static function get(string $url, array $args = [], string $urlPrefix = ''): WpRestCondition
    {
        return static::build($url, $args, $urlPrefix, 'GET');
    }

    /**
     * Build POST RouteCondition
     * @param string $url
     * @param array $args
     * @param string $urlPrefix
     * @return WpRestCondition
     * @throws RoutingException
     */
    public static function post(string $url, array $args = [], string $urlPrefix = ''): WpRestCondition
    {
        return static::build($url, $args, $urlPrefix, 'POST');
    }

    /**
     * Build PUT RouteCondition
     * @param string $url
     * @param array $args
     * @param string $urlPrefix
     * @return WpRestCondition
     * @throws RoutingException
     */
    public static function put(string $url, array $args = [], string $urlPrefix = ''): WpRestCondition
    {
        return static::build($url, $args, $urlPrefix, 'PUT');
    }

    /**
     * Build DELETE RouteCondition
     * @param string $url
     * @param array $args
     * @param string $urlPrefix
     * @return WpRestCondition
     * @throws RoutingException
     */
    public static function delete(string $url, array $args = [], string $urlPrefix = ''): WpRestCondition
    {
        return static::build($url, $args, $urlPrefix, 'DELETE');
    }

    /**
     * Build RouteCondition with given method
     * @param string $url
     * @param array $args
     * @param string $urlPrefix
     * @param string $method
     * @return WpRestCondition
     * @throws RoutingException
     */
    protected static function build(string $url, array $args = [], string $urlPrefix = '', string $method): WpRestCondition
    {
        if(empty($urlPrefix) && empty(static::$urlPrefixBracket)) {
            $urlPrefix = static::DEFAULT_PREFIX;
        }

        if(!in_array($method, static::SUPPORTED_METHODS)) {
            throw new RoutingException(
                sprintf("Not supported method to rest route condition, given %s", $method)
            );
        }

        return new static($urlPrefix, $url, $args, $method);
    }

    /** @var string  */
    protected $urlPrefix;

    /** @var string  */
    protected $url;

    /** @var array  */
    protected $args;

    /** @var string  */
    protected $method;

    /**
     * WpRestCondition constructor.
     * @param string $urlPrefix
     * @param string $url
     * @param array $args
     * @param string $method
     */
    public function __construct(string $urlPrefix, string $url, array $args = [], string $method = 'GET')
    {
        $this->urlPrefix = static::$urlPrefixBracket . $urlPrefix;
        $this->url = $url;
        $this->args = $args;
        $this->method = $method;
    }

    /**
     * @inheritdoc
     */
    public function bindWithAction(Action $action)
    {
        add_action('rest_api_init', function () use ($action) {
            register_rest_route($this->urlPrefix, $this->url, [
                'methods' => $this->method,
                'callback' => function (WP_REST_Request $request) use ($action) {
                    $response = $action->call($request);
                    $response->send();
                },
                'args' => $this->args
            ]);
        });
    }
}