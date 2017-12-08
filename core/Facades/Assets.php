<?php declare(strict_types=1);

namespace Wpci\Core\Facades;

use Wpci\Core\Contracts\Response;
use Wpci\Core\Helpers\Facade;
use Wpci\Core\Http\RegularResponse;

/**
 * Class Assets
 * @package Wpci\Core\Facades
 *
 * @method static $this registerStyle(string $key, ?string $path = null, array $deps = [], ?string $ver = null)
 * @method static \Wpci\Core\Render\Assets registerFooterScript(string $key, ?string $path = null, array $deps = [], ?string $ver = null)
 * @method static \Wpci\Core\Render\Assets registerHeaderScript(string $key, ?string $path = null, array $deps = [], ?string $ver = null)
 * @method static \Wpci\Core\Render\Assets addVariableToScript(string $key, string $name, $value = null)
 */
class Assets extends Facade
{

    /**
     * Return the facade root object
     * @return mixed
     * @throws \Exception
     */
    public static function getFacadeRoot()
    {
        return App::get(\Wpci\Core\Render\Assets::class);
    }
}