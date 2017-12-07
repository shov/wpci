<?php declare(strict_types=1);

namespace Wpci\Core\Render;

use Wpci\Core\Contracts\Template;
use Wpci\Core\Http\RegularResponse;

/**
 * Class View, build Html Requests with templates
 * @package Wpci\Core\Render
 */
class View
{
    protected $templateStrategy;

    /**
     * View constructor.
     * @param Template $templateStrategy
     */
    public function __construct(Template $templateStrategy)
    {
        $this->templateStrategy = $templateStrategy;
    }

    /**
     * Make templating and give the response
     * @param string $key
     * @param array $data
     * @param int $status
     * @return RegularResponse
     */
    public function display(string $key, array $data, int $status = RegularResponse::HTTP_OK): RegularResponse
    {
        $content = $this->templateStrategy->render($key, $data);

        return (new RegularResponse($content))->setStatusCode($status);
    }
}