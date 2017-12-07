<?php declare(strict_types=1);

namespace Wpci\Core\DataSource;

use stdClass;
use WP_Post;
use WP_Query;
use Wpci\Core\Facades\App;
use Wpci\Core\Facades\Path;
use Wpci\Core\Helpers\DataManipulator;
use Wpci\Core\Helpers\Decorator;

/**
 * Class WpciQuery, general data source uses as wordpress native query wrapper,
 * provides smart and flexible way to collect necessary data in pretty useful shape
 * @package Wpci\Core\DataSource
 */
class WpciQuery
{
    use Decorator;
    use DataManipulator;

    const DEFAULT_CHANNEL = 'default';

    /**
     * Receive default data channel name
     * @see DataManipulator::getDefaultChannel()
     * @return string
     */
    protected static function getDefaultChannel(): string
    {
        return static::DEFAULT_CHANNEL;
    }

    /** @var WP_Query */
    protected $decoratedWpQuery = null;

    /**
     * WpciQuery constructor.
     * @param null $query
     * @param null|string $channel
     * @throws \Exception
     */
    public function __construct($query = null, ?string $channel = null)
    {
        $this->addVariables(static::getBaseDataByChannel($channel));

        switch(true) {
            case ($query instanceof WP_Query):
                $this->decoratedWpQuery = $query;
                break;

            case (!empty($query) && (is_string($query) || is_array($query))):
                $this->decoratedWpQuery = new WP_Query($query);
                break;

            default:
                $this->decoratedWpQuery = App::get('wp.query');
                break;
        }
    }

    /**
     * Add wordpress environment to the data
     * @return WpciQuery
     */
    public function addWpEnv()
    {
        $data = [
            'site-url' => get_bloginfo('url'),
            'site-wpurl' => get_bloginfo('wpurl'),
            'site-description' => get_bloginfo('description'),
            'site-rss_url' => get_bloginfo('rss_url'),
            'site-rss2_url' => get_bloginfo('rss2_url'),
            'site-atom_url' => get_bloginfo('atom_url'),
            'site-comments_atom_url' => get_bloginfo('comments_atom_url'),
            'site-comments_rss2_url' => get_bloginfo('comments_rss2_url'),
            'site-pingback_url' => get_bloginfo('pingback_url'),
            'site-stylesheet_url' => get_bloginfo('stylesheet_url'),
            'site-stylesheet_directory' => get_bloginfo('stylesheet_directory'),
            'site-template_directory' => get_bloginfo('template_directory'),
            'site-admin_email' => get_bloginfo('admin_email'),
            'site-charset' => get_bloginfo('charset'),
            'site-html_type' => get_bloginfo('html_type'),
            'site-version' => get_bloginfo('version'),
            'site-language' => get_bloginfo('language'),
            'site-text_direction' => get_bloginfo('text_direction'),
            'site-name' => get_bloginfo('name'),
        ];

        $this->mergeToTheData($data);
        return $this;
    }

    /**
     * Add to result current post data
     * @param callable|null $anotherElse
     * @param bool $withoutWp
     * @return WpciQuery
     * @throws \Exception
     */
    public function addPostData(?callable $anotherElse = null, bool $withoutWp = false)
    {
        /**
         * @var WP_Post $post
         */
        $post = App::get('wp.post');

        $queryObject = $this->wpQuery();

        $data = [];
        if ($queryObject->have_posts()) {
            $queryObject->the_post();
            $data['title'] = get_the_title();

            ob_start();
            the_content();
            $data['content'] = ob_get_clean();

            $postData['excerpt'] = $post->post_excerpt;

            $data = array_merge($this->getAcfFromPage($post->ID), $data);
            if (!is_null($anotherElse)) {
                $anotherElse($data, $post);
            }
        }

        if (!$withoutWp) {
            ob_start();
            wp_head();
            $data['wp-head'] = ob_get_clean();

            ob_start();
            wp_footer();
            $data['wp-footer'] = ob_get_clean();
        }

        $queryObject->rewind_posts();
        wp_reset_postdata();

        $this->mergeToTheData($data);
        return $this;
    }

    /**
     * Add to result post data in wp loop
     * @param callable|null $anotherElse
     * @param bool $withoutWp
     * @return WpciQuery
     * @throws \Exception
     */
    public function addPostLoopData(?callable $anotherElse = null, bool $withoutWp = false)
    {
        /**
         * @var WP_Post $post
         */
        $post = App::get('wp.post');

        $queryObject = $this->wpQuery();

        $data = [];
        while ($queryObject->have_posts()) {
            $queryObject->the_post();
            $postData = [];
            $postData['title'] = get_the_title();

            ob_start();
            the_content();
            $postData['content'] = ob_get_clean();

            $postData['excerpt'] = $post->post_excerpt;

            $postData = array_merge($this->getAcfFromPage($post->ID), $postData);

            $data['posts'][] = $postData;

            if (!is_null($anotherElse)) {
                $anotherElse($data['posts'][count($data['posts']) - 1], $post);
            }
        }

        if (is_object($queryObject->queried_object)) {
            $data['title'] = $queryObject->queried_object->name;
        }

        if (!$withoutWp) {
            ob_start();
            wp_head();
            $data['wp-head'] = ob_get_clean();

            ob_start();
            wp_footer();
            $data['wp-footer'] = ob_get_clean();
        }

        $queryObject->rewind_posts();
        wp_reset_postdata();

        $this->mergeToTheData($data);
        return $this;
    }

    /**
     * Add menus to template
     * @return $this
     */
    public function addMenu()
    {
        $menuLocations = get_nav_menu_locations();
        $data = [];

        foreach ($menuLocations as $menuLocation => $menuId) {
            $menu = wp_get_nav_menu_items($menuId);

            foreach ($menu as $menuItem) {
                $item = new stdClass();

                /**
                 * @var WP_Post $menuItem
                 */
                $item->item = $menuItem->title;
                $item->link = $menuItem->url;
                $item->active = (Path::getCurrentUrl() === $menuItem->url) ? true : false;

                $data[$menuLocation][] = $item;
            }
        }

        $this->mergeToTheData($data);
        return $this;
    }

    /**
     * Try to add page's ACF date to result
     * @param int $pageId
     * @return $this
     */
    public function addAcfFromPage(int $pageId)
    {
        $this->mergeToTheData($this->getAcfFromPage($pageId));
        return $this;
    }

    /**
     * Try to add homepage ACF data to result
     * @return $this
     */
    public function addHomePageAcf()
    {
        $homePageId = (int)get_option('page_on_front');
        $this->addAcfFromPage($homePageId);
        return $this;
    }

    /**
     * Right way to get current and correct query object
     * @return WP_Query
     * @throws \Exception
     */
    protected function wpQuery(): WP_Query
    {
        $hashDecoratedObject = spl_object_hash($this->decoratedWpQuery);
        $globalObject = spl_object_hash(App::get('wp.query'));

        if ($hashDecoratedObject === $globalObject) {
            wp_reset_query();
        }

        return $this->decoratedWpQuery;
    }

    /**
     * Decorate WP_Query object
     * @see Decorator::getDecoratedObject()
     * @return WP_Query
     */
    protected function getDecoratedObject(): WP_Query
    {
        return $this->decoratedWpQuery;
    }

    /**
     * Try to receive the ACF data by page id, will return empty array if fail
     * @param int $pageId
     * @return array
     */
    protected function getAcfFromPage(int $pageId): array
    {
        if (!function_exists('get_fields')) return [];

        $data = get_fields($pageId);
        if (!is_array($data) || empty($data)) return [];

        return $data;
    }
}