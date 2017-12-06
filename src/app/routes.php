<?php declare(strict_types=1);

namespace Wpci\App;

use Wpci\App\Pages\PagesController;
use Wpci\Core\Facades\AppMake;
use Wpci\Core\Facades\RouterStore;

/**
 * General route for regular wordpress pages queries
 */
RouterStore::add(
    AppMake::WpQueryCondition(),
    AppMake::Action(PagesController::class . '::index'),
    'general-pages-route'
);