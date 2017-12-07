<?php declare(strict_types=1);

namespace Wpci\Core\Contracts;



/**
 * Interface Action
 * @package Wpci\Core\Contracts
 */
interface Action
{
    /**
     * Action constructor.
     * @param $reference
     */
    public function __construct($reference);

    /**
     * Call action
     * @param array ...$arguments
     * @return Response
     */
    public function call(...$arguments): Response;
}