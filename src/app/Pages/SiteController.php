<?php declare(strict_types=1);

namespace Wpci\App\Pages;

use Wpci\Core\Contracts\Response;
use Wpci\Core\DataSource\WpciQuery;
use Wpci\Core\Facades\App;
use Wpci\Core\Facades\View;
use Wpci\Core\Http\PagesController;
use Wpci\Core\Http\RegularResponse;

/**
 * Class WpQueryController
 * @package Wpci\App\Pages
 */
class SiteController extends PagesController
{
    /**
     * @param \WP_Query $query
     * @return RegularResponse
     * @throws \Exception
     */
    public function index(\WP_Query $query): Response
    {
        return $this->wrap(function () use ($query) {
            $data = (new WpciQuery($query))
                ->addWpEnv()
                ->addPostLoopData()
                ->fetch();

            return View::display('/Pages/Templates/index.php', $data);
        });
    }

    public function category(\WP_Query $query): Response
    {
        ob_start();

        echo "Hello PagesController::category";
        dump($query);

        return new RegularResponse(ob_get_clean());
    }

    public function single(\WP_Query $query): Response
    {
        ob_start();

        echo "Hello PagesController::single";
        dump(App::get('wp'));
        dump(App::get('wp.post'));
        dump($query);

        return new RegularResponse(ob_get_clean());
    }

    public function helloWorld(\WP_Query $query): Response
    {
        ob_start();

        echo "Hello World";
        dump($query);

        return new RegularResponse(ob_get_clean());
    }
}