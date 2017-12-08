<?php declare(strict_types=1);

namespace Wpci\Config;

use App\Pages\SiteController;
use Wpci\Core\Facades\RouterStore;
use Wpci\Core\Http\Action;
use Wpci\Core\Http\WpQueryCondition;

RouterStore::add(
    new WpQueryCondition('index|home|any'),
    new Action(SiteController::class . '::index'),
    'pages.home'
);

RouterStore::add(
    new WpQueryCondition('single', ['name' => 'hello-world']),
    new Action(SiteController::class . '::helloWorld'),
    'pages.post.hello_world'
);