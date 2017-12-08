<?php declare(strict_types=1);

namespace Wpci\Core\Http;

use Symfony\Component\HttpFoundation\JsonResponse as BaseJsonResponse;
use Wpci\Core\Facades\ShutdownPromisePull;

/**
 * Class JsonResponce
 * @package Wpci\Core\Http
 */
class JsonResponse extends BaseJsonResponse implements \Wpci\Core\Contracts\Response
{
    public function send()
    {
        $result = parent::send();
        ShutdownPromisePull::callAllPromises();
        return $result;
    }
}