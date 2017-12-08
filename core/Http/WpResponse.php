<?php declare(strict_types=1);

namespace Wpci\Core\Http;

use Symfony\Component\HttpFoundation\Response as BaseResponse;

/**
 * Class WpResponse
 * @package Wpci\Core\Http
 */
class WpResponse extends BaseResponse implements \Wpci\Core\Contracts\Response
{
    /**
     * TODO: write description
     * @return $this|void
     */
    public function send()
    {
        add_filter('status_header', function ($_, int &$code) {
            $code = $this->getStatusCode();
        });

        $content = $this->getContent();
        $tmpFilePath = tempnam(sys_get_temp_dir(), 'wpResponse_');
        @file_put_contents($tmpFilePath, $content);

        include($tmpFilePath);
        dump($tmpFilePath);
        @unlink($tmpFilePath);
    }
}