<?php declare(strict_types=1);

namespace Wpci\App\Pages;

use Wpci\Core\Contracts\Response;
use Wpci\Core\DataSource\WpciQuery;
use Wpci\Core\Facades\Assets;
use Wpci\Core\Facades\Path;
use Wpci\Core\Facades\View;
use Wpci\Core\Http\PagesController;
use Wpci\Core\Http\RegularResponse;

/**
 * Class WpQueryController
 * @package Wpci\App\Pages
 */
class SiteController extends PagesController
{
    public function __construct()
    {
        Assets::registerStyle('mystyle', Path::getCssUri('/mystyle.css'), [], (string)time());
    }

    /**
     * @param \WP_Query $query
     * @return RegularResponse
     * @throws \Exception
     */
    public function index(\WP_Query $query): Response
    {
        return $this->wrap(function () use ($query) {
            $data = (new WpciQuery($query))
                ->addPostData()
                ->fetch();

            return View::display('@index', $data);
        });
    }

    /**
     * @param \WP_Query $query
     * @return Response
     * @throws \Exception
     */
    public function category(\WP_Query $query): Response
    {
        return $this->wrap(function () {
            return "Hello SiteController::category";
        });
    }

    /**
     * @param \WP_Query $query
     * @return Response
     * @throws \Exception
     */
    public function single(\WP_Query $query): Response
    {
        return $this->wrap(function () {
            return "Hello SiteController::single";
        });
    }

    /**
     * @param \WP_Query $query
     * @return Response
     * @throws \Exception
     */
    public function helloWorld(\WP_Query $query): Response
    {
        return $this->wrap(function () {
            return "Hello world";
        });
    }
}