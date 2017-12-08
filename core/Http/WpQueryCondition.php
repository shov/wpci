<?php declare(strict_types=1);

namespace Wpci\Core\Http;

use Wpci\Core\Contracts\Action;
use Wpci\Core\Contracts\RouteCondition;
use Wpci\Core\Facades\App;

/**
 * Class WpQueryCondition
 * @package Wpci\Core\Http
 */
class WpQueryCondition implements RouteCondition
{
    /**
     * Because only one action can be bound
     * this flag signal to stop binding for WpQueryConditions
     * @var bool
     */
    protected static $wpQueryActionHasBound = false;

    /** @var \WP_Query */
    protected $wpQuery;

    /** @var array */
    protected $keywords;

    /** @var array */
    protected $queryParams;

    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function __construct(string $keyword, array $queryParams = [])
    {
        $this->wpQuery = App::get("wp.query");
        $this->keywords = explode('|', $keyword);
        $this->queryParams = $queryParams;
    }

    /**
     * @inheritdoc
     */
    public function bindWithAction(Action $action)
    {
        if (static::$wpQueryActionHasBound) return;

        add_action('template_redirect', function () use ($action) {
            if (static::$wpQueryActionHasBound) return;

            $gotKeywords = $this->parseKeywords();

            $haveToBind = false;
            foreach ($this->keywords as $keyword) {
                $haveToBind = in_array($keyword, $gotKeywords) || $haveToBind;
            }

            if ($haveToBind) {
                foreach ($this->queryParams as $expectedParam => $value) {

                    if (!in_array($expectedParam, $this->wpQuery->query_vars)) {
                        continue;
                    }

                    if ($value !== $this->wpQuery->query_vars[$expectedParam]) {

                        $haveToBind = false;
                        break;
                    }

                }
            }

            if ($haveToBind) {
                static::$wpQueryActionHasBound = true;

                add_action('template_include', function () use ($action) {
                    return $action->call($this->wpQuery)->send();
                });
            }
        });
    }

    /**
     * @return array
     */
    public function getKeywords(): array
    {
        return $this->keywords;
    }

    /**
     * @return array
     */
    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    /**
     * Set array of keywords to condition comparation
     * @return array
     */
    protected function parseKeywords(): array
    {
        $wpq = $this->wpQuery;

        $keywords = [];
        if ($wpq->is_single) $keywords[] = 'single';
        if ($wpq->is_preview) $keywords[] = 'preview';
        if ($wpq->is_page) $keywords[] = 'page';
        if ($wpq->is_archive) $keywords[] = 'archive';
        if ($wpq->is_date) $keywords[] = 'date';
        if ($wpq->is_year) $keywords[] = 'year';
        if ($wpq->is_month) $keywords[] = 'month';
        if ($wpq->is_day) $keywords[] = 'day';
        if ($wpq->is_time) $keywords[] = 'time';
        if ($wpq->is_author) $keywords[] = 'author';
        if ($wpq->is_category) $keywords[] = 'category';
        if ($wpq->is_tag) $keywords[] = 'tag';
        if ($wpq->is_tax) $keywords[] = 'tax';
        if ($wpq->is_search) $keywords[] = 'search';
        if ($wpq->is_feed) $keywords[] = 'feed';
        if ($wpq->is_comment_feed) $keywords[] = 'comment_feed';
        if ($wpq->is_trackback) $keywords[] = 'trackback';
        if ($wpq->is_home) $keywords[] = 'home';
        if ($wpq->is_404) $keywords[] = '404';
        if ($wpq->is_embed) $keywords[] = 'embed';
        if ($wpq->is_paged) $keywords[] = 'paged';
        if ($wpq->is_admin) $keywords[] = 'admin';
        if ($wpq->is_attachment) $keywords[] = 'attachment';
        if ($wpq->is_singular) $keywords[] = 'singular';
        if ($wpq->is_robots) $keywords[] = 'robots';
        if ($wpq->is_posts_page) $keywords[] = 'posts_page';
        if ($wpq->is_post_type_archive) $keywords[] = 'post_type_archive';
        if (0 === count($keywords)) $keywords[] = 'index';

        $frontPageId = get_option('page_on_front');
        //ignore zero value as well
        if (!empty($wpq->query_vars['p']) && $frontPageId == $wpq->query_vars['p']) {
            $keywords[] = 'index';
        }

        $keywords[]= 'any';

        return array_unique($keywords);
    }
}