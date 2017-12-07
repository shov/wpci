<?php declare(strict_types=1);

namespace Wpci\Core\Helpers;

/**
 * Trait Decorator
 * @package Wpci\Core\Helpers
 *
 * Required on host
 * @method getDecoratedObject()
 */
trait Decorator
{
    /**
     * @param $method
     * @param $args
     * @return mixed
     * @throws \Exception
     */
    public function __call($method, $args)
    {
        if (is_callable([$this->getDecoratedObject(), $method])) {
            return call_user_func_array([$this->getDecoratedObject(), $method], $args);
        }

        throw new \Exception(
            sprintf("Call undefined method: %s::%s", get_class($this->getDecoratedObject()), $method));
    }

    /**
     * @param $property
     * @return null
     */
    public function __get($property)
    {
        $publicVars = get_object_vars($this->getDecoratedObject());
        if (array_key_exists($property, $publicVars)) {
            return $this->getDecoratedObject()->$property;
        }
        return null;
    }

    /**
     * @param $property
     * @param $value
     * @return $this
     */
    public function __set($property, $value)
    {
        $this->getDecoratedObject()->$property = $value;
        return $this;
    }
}