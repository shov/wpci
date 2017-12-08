<?php declare(strict_types=1);

namespace Wpci\Core\Http;

use Symfony\Component\HttpFoundation\Response as BaseResponse;
use Wpci\Core\Facades\ShutdownPromisePull;

/**
 * Class RegularResponse
 * @package Wpci\Core\Http
 */
class RegularResponse extends BaseResponse implements \Wpci\Core\Contracts\Response
{
    public function send()
    {
        $result = parent::send();
        ShutdownPromisePull::callAllPromises();
        return $result;
    }
}