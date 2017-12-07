<?php declare(strict_types=1);

namespace Wpci\Core\Contracts;

/**
 * Interface Response
 * @package Wpci\Core\Contracts
 */
interface Response
{
    public function send();

    public function setStatusCode(int $code, $text = null);
}