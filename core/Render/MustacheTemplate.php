<?php declare(strict_types=1);

namespace Wpci\Core\Render;

use Mustache_Engine;
use Mustache_Exception_UnknownTemplateException;
use Mustache_Loader;
use Mustache_Loader_FilesystemLoader;
use Wpci\Core\Contracts\Template;
use Wpci\Core\Facades\Path;
use Wpci\Core\Helpers\KeyToFile;

/**
 * Class MustacheTemplate
 * @package Wpci\Core\Render
 */
class MustacheTemplate implements Template
{
    use KeyToFile;

    const TPL_EXT = '.html';

    protected $engine;

    /**
     * MustacheTemplate constructor.
     */
    public function __construct()
    {
        $options = [
            'extension' => static::TPL_EXT,
        ];

        $this->engine = new Mustache_Engine([

            'loader' => new Mustache_Loader_FilesystemLoader('/', $options),

            'partials_loader' =>
                new class(Path::getTplPath(), $options)

                    extends Mustache_Loader_FilesystemLoader
                    implements Mustache_Loader
                {
                    protected function loadFile($name)
                    {
                        $fileName = $this->getFileName($name);
                        $fileName = str_replace(['/../', '/./'], '/blocks/', $fileName);
                        if ($this->shouldCheckPath() && !file_exists($fileName)) {
                            throw new Mustache_Exception_UnknownTemplateException($name);
                        }

                        return file_get_contents($fileName);
                    }
                },
        ]);
    }

    /**
     * @inheritdoc
     */
    public function render(string $key, array $data = []): string
    {
        return $this->keyToFileForProcess(Path::getTplPath(), $key, function ($filePath) use ($data) {
            return $this->engine->render($filePath, $data);
        }, static::TPL_EXT);
    }
}