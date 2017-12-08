<?php declare(strict_types=1);

namespace Wpci\Core;

/**
 * Class PromisePull
 * @package Wpci\Core
 */
class PromisePull
{
    protected $pull = [];

    public function addPromise(callable $promise, ?int $priority = null)
    {
        $priority = $priority ?? 0;

        $pull[] = compact('priority', 'promise');
    }

    public function callAllPromises()
    {
        $pull = $this->pull;

        usort($pull, function ($a, $b) {
            return $a['priority'] - $b['priority'];
        });

        foreach ($pull as $promise) {
            $promise();
        }
    }
}