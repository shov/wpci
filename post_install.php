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
if (!is_dir($themeFolder)) {
    if (is_file($themeFolder)) {
        @unlink($themeFolder);
    }
    mkdir($themeFolder);
}

/**
 * index.php, to start Wpci Application
 */
$indexPhpPath = $themeFolder . '/index.php';
$indexPhpContent = <<<'END'
<?php
/**
 * Load wpci app
 */

$app = require(ABSPATH . '/../app/App.php');
$app->run();
END;

file_put_contents($indexPhpPath, $indexPhpContent);

/**
 * Just stubs to guarantee WP correct work
 */
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
