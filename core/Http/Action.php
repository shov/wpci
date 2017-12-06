<?php declare(strict_types=1);

namespace Wpci\Core\Http;

use Wpci\Core\Contracts\Action as ActionInterface;

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
        switch (true) {

            case (is_callable($reference)):
                $this->callback = $reference;
                break;

            case (is_string($reference) && !empty($reference)):
                $matches = [];
                $test = preg_match_all(
                    '^(?<class>[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*(\\[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)*)::(?<method>[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)$',
                    $reference,
                    $matches
                );

                dump($test);
                dump($matches);

                $class= $matches[0]['class'];
                $method = $matches[0]['method'];

                $this->callback = [new $class(), $method];
                break;
        }
    }

    /**
     * @inheritdoc
     */
    public function call(...$arguments): Response
    {
        $callback = $this->callback;
        $response = $callback(...$arguments);

        ($response instanceof Response) ?: $response = new Response($response);
        return $response;
    }
}