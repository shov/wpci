<?php declare(strict_types=1);

namespace Wpci\App;

use Wpci\Core\Facades\App;
use Wpci\Core\Http\WpQueryCondition;
use Wpci\Core\Facades\RouterStore;

RouterStore::add(App::get(WpQueryCondition::class), '');