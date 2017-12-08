<?php declare(strict_types=1);

namespace Wpci\Core\Http;

use Symfony\Component\HttpFoundation\Response as BaseResponse;
use Wpci\Core\Facades\ShutdownPromisePull;

/**
 * Class WpResponse
 * @package Wpci\Core\Http
 */
class WpResponse extends BaseResponse implements \Wpci\Core\Contracts\Response
{
    /**
     * Dump rendered page as temp file and return its name
     * to including in wordpress template-load (or for something else)
     * Also make the promise to unlink this tmp file after shutdown wp or
     * app stop by exception
     */
    public function send()
    {
        status_header($this->getStatusCode());

        $content = $this->getContent();
        $tmpFilePath = tempnam(sys_get_temp_dir(), 'wpResponse_');
        @file_put_contents($tmpFilePath, $content);

        $unlinkTmpFile = function () use ($tmpFilePath) {
            @unlink($tmpFilePath);
        };

        ShutdownPromisePull::addPromise($unlinkTmpFile);

        return $tmpFilePath;
    }
}