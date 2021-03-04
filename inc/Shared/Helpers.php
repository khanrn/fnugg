<?php // -*- coding: utf-8 -*-
declare(strict_types=1);

namespace Fnugg\Shared;

/**
 * Defining base constant.
 */
defined('ABSPATH') || die;

/**
 * Helper `static` methods will be here.
 *
 * @author  Khan Mohammad R. <codemascot@hotmail.com>
 *
 * @package Fnugg\Helpers
 */
final class Helpers
{
    /**
     * Getting remote API data.
     *
     * @param string $url Remote API URL.
     *
     * @return array
     */
    public static function get_remote_json(string $url) : array
    {
        /**
         * Filters `wp_remote_get()` arguments for Fetch.
         *
         * @param array  $args
         * @param string $url
         */
        $args = apply_filters('fnugg_wp_remote_get_args', [
            'timeout'             => 10,
            'redirection'         => 0,
            'limit_response_size' => 153600, // 150 KB
        ], $url);

        $result = wp_remote_get($url, $args);
        $result = wp_remote_retrieve_body($result);
        $result = json_decode($result, true);

        if (empty($result)) {
            return [];
        }

        return $result;
    }

    /**
     * Creating transient ID from query array.
     *
     * @param array       $q Query array
     * @param string|bool $t Token to differentiate the transients
     *
     * @return string
     */
    public static function trans_id(array $q, $t) : string
    {
        if (empty($t)) {
            $t = '_';
        }

        // We need to hash it as the query can contain
        // multiple types of characters and languages
        return hash('sha256', $t . '_' . http_build_query($q));
    }
}
