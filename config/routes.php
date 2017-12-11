<?php declare(strict_types=1);

namespace Wpci\Config;

use Wpci\Core\DataSource\WpciQuery;
use Wpci\Core\Facades\RouterStore;
use Wpci\Core\Facades\View;
use Wpci\Core\Http\Action;
use Wpci\Core\Http\WpQueryCondition;

RouterStore::add(
    new WpQueryCondition('index|home|any'),
    new Action(function ($query) {
        return View::display("<h1>{{hello}}</h1>", ['hello' => "Hello Wpci!"]);
    }),
    'pages.home'
);
