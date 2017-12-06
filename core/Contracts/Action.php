<?php declare(strict_types=1);

namespace Wpci\Core\Contracts;

use Wpci\Core\Http\Response;

/**
 * Interface Action
 * @package Wpci\Core\Contracts
 */
interface Action
{
    /**
     * Action constructor.
     * @param callable $callback, the method which will calling for the time
     */
    public function __construct(callable $callback);

    /**
     * Call action
     * @param array ...$arguments
     * @return Response
     */
    public function call(...$arguments): Response;
}