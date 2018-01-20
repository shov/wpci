<?php declare(strict_types=1);

namespace App;

use Wpci\Core\Core;
use Wpci\Core\Facades\RouterStore;
use Wpci\Core\Facades\View;
use Wpci\Core\Http\Action;
use Wpci\Core\Http\JsonResponse;
use Wpci\Core\Http\WpQueryCondition;
use Wpci\Core\Facades\Assets;
use Wpci\Core\Http\WpRestCondition;

class App implements \Wpci\Core\Contracts\App
{

    /**
     * Getting the core to handle it
     * @param Core $core
     */
    public function handle(Core $core)
    {
        $core->run([$this, 'run']);
    }

    /**
     * Waiting for the core who call it at the time
     */
    public function run()
    {
        $this->registerAssets();
        $this->registerMenus();
        $this->registerRoutes();
    }

    /**
     * Using Assets facade include to wp-head and wp-footer styles and scripts
     * @see Assets::registerStyle()
     * @see Assets::registerFooterScript()
     */
    protected function registerAssets()
    {

    }

    /**
     * Register your routes here. It would be:
     * Wordpress pages @see WpQueryCondition
     * WP REST API @see WpRestCondition
     * Wordpress AJAX callback @see WpAjaxCondition
     */
    protected function registerRoutes()
    {
        /** http://localhost/ */
        RouterStore::add(
            new WpQueryCondition('index|home|any'),
            new Action(function () {
                return View::display(
                    '<h1 style="{{{style}}}">{{{hello}}}</h1>',
                    [
                        'hello' => 'Hello Wpci!',
                        'style' => 'line-height: 10; color: white; text-align: center; background: dimgrey',
                    ]
                );
            }),
            'pages.home'
        );

        WpRestCondition::prefix('myapi/v2', function() {

            /** http://localhost/wp-json/myapi/v2/hello before test don't forget turn on permalinks */
            RouterStore::add(
                WpRestCondition::get('/hello'),
                new Action(function() {
                    return new JsonResponse([
                        'message' => 'Hello Wpci!',
                    ]);
                })
            );

        });
    }

    /**
     * Here make registration all your menu places
     */
    protected function registerMenus()
    {
        add_action('after_setup_theme', function () {
            ;
        });
    }
}