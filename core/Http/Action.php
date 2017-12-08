<?php declare(strict_types=1);

namespace Wpci\Core\Http;

use Wpci\Core\App;
use Wpci\Core\Contracts\Action as ActionInterface;
use Wpci\Core\Contracts\Response;

/**
 * Class Action, regular (simple) action
 * @package Wpci\Core\Http
 */
class Action implements ActionInterface
{
    protected $callback;

    /**
     * @inheritdoc
     */
    public function __construct($reference)
    {
        if(is_callable($reference)) {
            if(!is_string($reference)) {
                $this->callback = $reference;
            } else {
                $parts = explode("::", $reference);
                $this->callback = [new $parts[0](), $parts[1]];
            }
        } else {
            $this->callback = function () use ($reference) {
                new RegularResponse($reference);
            };
        }
    }

    /**
     * @inheritdoc
     */
    public function call(...$arguments): Response
    {
        $callback = $this->callback;
        $response = call_user_func($callback, ...$arguments);

        if(!in_array(Response::class, class_implements($response))) {
            $response = new RegularResponse($response);
        }
        return $response;
    }
}