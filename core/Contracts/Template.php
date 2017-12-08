<?php declare(strict_types=1);

namespace Wpci\Core\Contracts;

/**
 * Interface Template
 * @package Wpci\Core\Contracts
 */
interface Template
{
    /**
     * Render the content with given data
     * @param string $key
     * to looking for the template source, e.g. blade/twig/php file
     * Can be: row template source as string, part of path from template dir,
     * special formatted path from template dir like "@blog:post"
     *
     * @param array $data
     * @return string
     */
    public function render(string $key, array $data = []): string;
}