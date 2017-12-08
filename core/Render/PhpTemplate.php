<?php declare(strict_types=1);

namespace Wpci\Core\Render;

use Wpci\Core\Contracts\Template;
use Wpci\Core\Facades\Path;

/**
 * Class PhpTemplate
 * @package Wpci\Core\Render
 */
class PhpTemplate implements Template
{

    /**
     * @inheritdoc
     */
    public function render(string $key, array $data = []): string
    {
        extract($data);

        if ("@" === $key[0]) {
            $key = str_replace(["@", ":"], "/", $key) . '.php';
        }

        if (!is_readable(Path::getTplPath($key))) {

            $tmpFilePath = tempnam(sys_get_temp_dir(), 'phpTemplate_');

            if (false === $tmpFilePath) {
                throw new \Exception("Can't create temp file!");
            }

            file_put_contents($tmpFilePath, $key);

            ob_start();
            include $tmpFilePath;
            $rendered = ob_get_clean();

            @unlink($tmpFilePath);

        } else {

            ob_start();
            include Path::getTplPath($key);
            $rendered = ob_get_clean();

        }

        return $rendered;
    }
}