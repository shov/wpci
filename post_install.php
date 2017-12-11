<?php

namespace PostInstallScript;

/**
 * Do not run this script manually if u don't know what you doing
 */

define('BASE_PATH', __DIR__);
define('WP_PATH', BASE_PATH . '/wordpress');

if (!is_dir(WP_PATH)) {
    throw new \Exception("Have no installed wordpress!");
}

$themeFolder = WP_PATH . '/wp-content/themes/wpci';

$foldersToCreate = [
    $themeFolder . '/css',
    $themeFolder . '/fonts',
    $themeFolder . '/images',
    $themeFolder . '/js',
    BASE_PATH . '/src',
    BASE_PATH . '/src/app',
    BASE_PATH . '/src/app/templates',
];

$createDir = function ($dirPath) {
    if (!is_dir($dirPath)) {
        if (is_file($dirPath)) {
            @unlink($dirPath);
        }
        mkdir($dirPath);
    }
};

$createDir($themeFolder);
foreach ($foldersToCreate as $dirPath) {
    $createDir($dirPath);
}

/**
 * functions.php, to start Wpci Application
 */
$functionsPhpPath = $themeFolder . '/functions.php';
$functionsPhpContent = <<<'END'
<?php
/**
 * Composer autoload
 **/
require_once ABSPATH . '/../vendor/autoload.php';
 
/**
 * Load wpci app
 */
$app = require_once ABSPATH . '/../bootstrap/app.php';
$app->run();
END;

file_put_contents($functionsPhpPath, $functionsPhpContent);

/**
 * Just stubs to guarantee WP correct work
 */
$indexPhpPath = $themeFolder . '/index.php';
$indexPhpContent = <<<'END'
<?php
/**
 * Keep the silence
 */
END;

file_put_contents($indexPhpPath, $indexPhpContent);

$styleSheetsStubPath = $themeFolder . '/style.css';
$styleSheetsStubContent = <<<'END'
/*
Theme Name: WPCI
Theme URI: https://github.com/shov/wpci
Author: Alexandr Shevchenko [Shov] ls.shov@gmail.com
Author URI: https://github.com/shov/
*/

END;

file_put_contents($styleSheetsStubPath, $styleSheetsStubContent);

$screenshotPath = $themeFolder . '/screenshot.png';

$screenshotImg = imagecreatetruecolor(300, 300);
$bgColor = imagecolorallocate($screenshotImg, 255, 255, 255);
$textColor = imagecolorallocate($screenshotImg, 0, 0, 0);

imagefilledrectangle($screenshotImg, 0, 0, 300, 300, $bgColor);
imagestring($screenshotImg, 5, 100, 100, "WPCI", $textColor);

imagepng($screenshotImg, $screenshotPath);
