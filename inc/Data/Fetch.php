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
     * Constructor.
     *
     * @param string $url Remote API URL.
     *
     * @return void
     */
    public function __construct(string $url)
    {
        $this->url  = untrailingslashit(esc_url_raw($url));
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
        return Helpers::get_remote_json($url);
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
        return Helpers::get_remote_json($url);
    }
}
