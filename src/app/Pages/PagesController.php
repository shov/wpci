<?php declare(strict_types=1);

namespace Wpci\App\Pages;

use Wpci\Core\Http\Response;

/**
 * Class WpQueryController
 * @package Wpci\App\Pages
 */
class PagesController
{
    public function index(\WP_Query $query): Response
    {
        ob_start();

        echo "Hello PagesController::index";
        dump($query);

        return new Response(ob_get_clean());
    }
}