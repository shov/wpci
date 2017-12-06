<?php declare(strict_types=1);

namespace Wpci\App;

use Wpci\App\Pages\PagesController;
use Wpci\Core\Facades\RouterStore;
use Wpci\Core\Http\Action;
use Wpci\Core\Http\WpQueryCondition;

RouterStore::add(
    new WpQueryCondition('index|home'),
    new Action(PagesController::class . '::index'),
    'pages.home'
);

RouterStore::add(
    new WpQueryCondition('category'),
    new Action(PagesController::class . '::category'),
    'pages.category'
);

RouterStore::add(
    new WpQueryCondition('single'),
    new Action(PagesController::class . '::single'),
    'pages.post'
);

RouterStore::add(
    new WpQueryCondition('single', ['name' => 'hello-world']),
    new Action(PagesController::class . '::helloWorld'),
    'pages.post.hello_world'
);