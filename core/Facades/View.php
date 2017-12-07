<?php declare(strict_types=1);

namespace Wpci\Core\Facades;

use Wpci\Core\Helpers\Facade;
use Wpci\Core\Http\RegularResponse;

/**
 * Class View
 * @package Wpci\Core\Facades
 *
 * @method static RegularResponse display(string $key, array $data, int $status = RegularResponse::HTTP_OK)
 */
class View extends Facade
{

    /**
     * Return the facade root object
     * @return mixed
     * @throws \Exception
     */
    public static function getFacadeRoot()
    {
        return App::get('view.default');
    }
}