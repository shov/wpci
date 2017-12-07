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
        $tmpFilePath = tempnam(sys_get_temp_dir(), 'phpTemplate_');

        if(false === $tmpFilePath) {
            throw new \Exception("Can't create temp file!");
        }

        file_put_contents($tmpFilePath, $this->getTemplateContent($key));

        ob_start();
        include $tmpFilePath;
        $rendered = ob_get_clean();

        @unlink($tmpFilePath);

        return $rendered;
    }

    /**
     * Receive the content of the template
     * TODO: Add finding methods
     * @param string $key
     * @return string
     */
    protected function getTemplateContent(string $key): string
    {
        /**
         * Search in app
         */
        if(is_readable(Path::getAppPath($key))) {
            return file_get_contents(Path::getAppPath($key));
        }

        /**
         * Just work given string as template content
         */
        return $key;
    }
}