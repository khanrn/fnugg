<?php // -*- coding: utf-8 -*-
declare(strict_types=1);

namespace Fnugg\Data;

/**
 * Defining base constant.
 */
defined('ABSPATH') || die;

use \Fnugg\Shared\Helpers;

/**
 * Fetching data from fnugg.no.
 * This class is extensible!
 *
 * @author  Khan Mohammad R. <codemascot@hotmail.com>
 *
 * @package Fnugg\Data
 */
class Fetch implements Data
{
    /**
     * Remote API URL.
     *
     * @var string
     */
    protected string $url = '';

    /**
     * `wp_remote_get()` arguments.
     *
     * @var array
     */
    protected array $args = [];

    /**
     * Constructor.
     *
     * @param string $url Remote API URL.
     *
     * @return void
     */
    public function __construct(string $url)
    {
        $this->url  = untrailingslashit(esc_url_raw($url));

        /**
         * Filters `wp_remote_get()` arguments for Fetch.
         *
         * @param array  $args
         * @param string $url
         */
        $this->args = apply_filters('fnugg_wp_remote_get_args', [
            'timeout'             => 10,
            'redirection'         => 0,
            'limit_response_size' => 153600, // 150 KB
        ], $this->url);
    }

    /**
     * Getting remote API data.
     *
     * @param string $url Remote API URL.
     *
     * @return array
     */
    protected function get_remote_json(string $url) : array
    {
        $response = wp_safe_remote_get($url, $this->args);
        $result   = json_decode(wp_remote_retrieve_body($response), true);

        if (empty($result)) {
            return [];
        }

        return $result;
    }

    /**
     * Getting `autocomplete` remote API data.
     *
     * @param array $q GET query parameters.
     *
     * @return array
     */
    public function autocomplete(array $q) : array
    {
        $url = add_query_arg($q, $this->url . '/suggest/autocomplete/');
        return $this->get_remote_json($url);
    }

    /**
     * Getting `search` remote API data.
     *
     * @param array $q GET query parameters.
     *
     * @return array
     */
    public function search(array $q) : array
    {
        $url = add_query_arg($q, $this->url . '/search/');
        return $this->get_remote_json($url);
    }
}
