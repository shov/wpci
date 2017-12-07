<?php declare(strict_types=1);

namespace Wpci\Core\Http;

use Wpci\Core\Contracts\Response;
use Wpci\Core\Facades\App;

/**
 * Class ApiController
 * @package Wpci\Core\Http
 */
class ApiController extends Responder
{
    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    protected function makeResponse(int $status, $content, ?\Throwable $e = null): Response
    {
        $exceptionMarker = (2 != intval($status / 100));

        if ($exceptionMarker && App::environment('testing')) {

            $logData = [$content, $status];
            is_null($e) ?: $logData[] = $e->getTraceAsString();
            App::get('Logger')->info($logData);

            if (!is_array($content)) {
                $content = ['message' => $content];
            }

            return new JsonResponse($content, $status);
        }

        if($exceptionMarker) {
            $content = [];

        } elseif (!is_array($content)) {
            if (is_object($content)) {
                $content = get_object_vars($content);
            } else {
                $content = ['data' => $content];
            }
        }

        return new JsonResponse($content, $status);
    }
}