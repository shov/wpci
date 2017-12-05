<?php

namespace PostInstallScript;

/**
 * Do not run this script manually if u don't know what you doing
 */

define('BASE_PATH', __DIR__);
define('WP_PATH', BASE_PATH . '/wordpress');

if(!is_dir(WP_PATH)) {
    throw new \Exception("Have no installrd wordpress!");
}

$themeFolder = WP_PATH . '/themes/wpci';
if(!is_dir($themeFolder)) {
    if(is_file($themeFolder)) {
        @unlink($themeFolder);
    }
    mkdir($themeFolder);
}

$functionsPhpPath = $themeFolder . '/functions.php';

$functionsPhpContent = <<<'END'
<?php
/**
 * Load wpci app
 */

$app = require(ABSPATH . '/../app/App.php');
$app->run();
END;

file_put_contents($functionsPhpPath, $functionsPhpContent);