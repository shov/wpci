<?php declare(strict_types=1);

namespace App;

use Wpci\Core\Contracts\AbstractApp;
use Wpci\Core\Facades\Core;
use Wpci\Core\Facades\RouterStore;
use Wpci\Core\Facades\View;
use Wpci\Core\Flow\PromiseManager;
use Wpci\Core\Http\Drops\Action;
use Wpci\Core\Http\JsonResponse;
use Wpci\Core\Http\Drops\WpQueryCondition;
use Wpci\Core\Facades\Assets;
use Wpci\Core\Http\Drops\WpRestCondition;

/**
 * The Application
 */
class App extends AbstractApp
{
    /**
     * The method which core call at the time to run the app
     * @throws \Exception
     */
    public function run()
    {
        $this->registerAssets();
        $this->registerMenus();
        $this->registerRoutes();
    }

    /**
     * {@inheritdoc}
     */
    protected function getEnvironmentVars(): array
    {
        return array_merge(parent::getEnvironmentVars(), []);
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

        /** http://localhost/wp-json/myapi/v2/hello */
        WpRestCondition::prefix('myapi/v2', function() {

            /** TODO: 1 before test don't forget turn on permalinks... not best behaviour */
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
     * @throws \Exception
     */
    protected function registerMenus()
    {
        /** @var PromiseManager $promiseManager */
        $promiseManager = Core::get(PromiseManager::class);

        $promiseManager->addPromise('after_setup_theme', function () {
            ;
        });
    }
}