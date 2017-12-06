<?php declare(strict_types=1);

namespace Wpci\Core\Http;

use Symfony\Component\DependencyInjection\Container;
use Wpci\Core\Contracts\Action;
use Wpci\Core\Contracts\RouteCondition;

class WpQueryCondition implements RouteCondition
{
    /** @var Container */
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @inheritdoc
     */
    public function bindWithAction(Action $action)
    {
        add_action('template_redirect', function () use ($action) {
            $wpQuery = $this->container->get("wp.query");
            $action->call($wpQuery)->send();
        });
    }
}