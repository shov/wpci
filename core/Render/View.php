<?php declare(strict_types=1);

namespace Wpci\Core\Render;

use Wpci\Core\Contracts\Response;
use Wpci\Core\Contracts\Template;
use Wpci\Core\Http\RegularResponse;

/**
 * Class View, build Html Requests with templates
 * @package Wpci\Core\Render
 */
class View
{
    protected $templateStrategy;

    protected $responseStrategy;

    /**
     * View constructor.
     * @param Template $templateStrategy
     * @param null|Response $responseStrategy
     */
    public function __construct(Template $templateStrategy, ?Response $responseStrategy = null)
    {
        $this->templateStrategy = $templateStrategy;
        $this->responseStrategy = $responseStrategy ?? new RegularResponse();
    }

    /**
     * Make templating and give the response
     * @param string $key
     * @param array $data
     * @param int $status
     * @return RegularResponse
     */
    public function display(string $key, array $data, int $status = RegularResponse::HTTP_OK): Response
    {
        $content = $this->templateStrategy->render($key, $data);

        return $this->responseStrategy
            ->setContent($content)
            ->setStatusCode($status);
    }
}