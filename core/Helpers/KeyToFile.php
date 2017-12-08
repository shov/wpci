<?php declare(strict_types=1);

namespace Wpci\Core\Helpers;

/**
 * Trait KeyToFile, use full, to search file by reference, or make temp file as well
 * @package Wpci\Core\Helpers
 */
trait KeyToFile
{
    /**
     * @param string $basePath
     * @param string $key
     * @param callable $process
     * @return mixed
     * @throws \Exception
     */
    protected function keyToFileForProcess(string $basePath, string $key, callable $process, string $ext)
    {
        $originalKey = $key;

        if ("@" === $key[0]) {
            $key = str_replace(["@", ":"], "/", $key) . $ext;
        }

        if (!is_readable($basePath . $key)) {

            $tmpFilePath = tempnam(sys_get_temp_dir(), 'phpKeyToFile_');

            if (false === $tmpFilePath) {
                throw new \Exception("Can't create temp file!");
            }

            file_put_contents($tmpFilePath, $originalKey);

            $result = $process($tmpFilePath);

            @unlink($tmpFilePath);

            return $result;

        } else {
            return $process($basePath . $key);
        }
    }
}