<?php // -*- coding: utf-8 -*-
declare(strict_types=1);

namespace Fnugg\Api;

/**
 * Defining base constant.
 */
defined('ABSPATH') || die;

use \Fnugg\Data;

/**
 * Initiating Gutenberg Fnugg Block.
 *
 * @author  Khan Mohammad R. <codemascot@hotmail.com>
 *
 * @package Fnugg\Api
 */
final class Api
{
    /**
     * Get remote data.
     *
     * @var Data
     */
    protected Data\Data $fetch;

    /**
     * Get remote data.
     *
     * @var string
     */
    protected string $url;

    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->url = esc_url_raw(
            apply_filters(
                'fnugg_remote_api_url',
                'https://api.fnugg.no/'
            )
        );

        /**
         * Filters the Fetch object.
         *
         * @param Data\Data $fetch
         * @param string    $url
         */
        $this->fetch = apply_filters(
            'fnugg_fetch_object',
            new Data\Fetch($this->url),
            $this->url
        );
    }

    /**
     * Initializing hooks.
     *
     * @return void
     */
    public function init() : void
    {
        add_action('rest_api_init', [$this, 'init_rest']);
    }

    /**
     * Registers block editor assets, for backend.
     *
     * @return array
     */
    public function init_rest() : void
    {
        $routes = [
            'autocomplete' => (new Rest\Autocomplete($this->fetch)),
            'api'          => (new Rest\Search($this->fetch)),
        ];

        /**
         * Filters the REST API routes init.
         *
         * @param array     $routes
         * @param Data\Data $fetch
         * @param string    $url
         */
        $routes = apply_filters('fnugg_rest_routes_init', $routes, $this->fetch, $this->url);

        foreach ($routes as $route) {
            $route->register_routes();
        }
    }
}
