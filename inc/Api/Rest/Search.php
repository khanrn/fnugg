<?php // -*- coding: utf-8 -*-
declare(strict_types=1);

namespace Fnugg\Api\Rest;

/**
 * Defining base constant.
 */
defined('ABSPATH') || die;

use \Fnugg\Data\Data;
use \Fnugg\Shared\Helpers;

/**
 * Initiating `search` rest route.
 *
 * @author  Khan Mohammad R. <codemascot@hotmail.com>
 *
 * @package Fnugg\Api\Rest
 */
final class Search extends \WP_REST_Controller
{
    /**
     * Get remote data.
     *
     * @var array
     */
    protected Data $fetch;

    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct(Data $fetch)
    {
        $this->namespace = 'codemascot/v1';
        $this->rest_base = 'search';
        $this->fetch     = $fetch;
    }

    /**
     * Registers the routes for the objects of the controller.
     *
     * @see register_rest_route()
     *
     * @return void
     */
    public function register_routes() : void
    {
        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base,
            [
                [
                    'methods'             => \WP_REST_Server::READABLE,
                    'callback'            => [$this, 'get_items'],
                    'permission_callback' => '__return_true',
                    'args'                => $this->get_collection_params(),
                ],
                'schema' => [$this, 'get_item_schema'],
            ]
        );
    }

    /**
     * Retrieves a collection of search results.
     *
     * @param \WP_REST_Request $request Full details about the request.
     *
     * @return \WP_REST_Response|\WP_Error Response object on success, or WP_Error object on failure.
     */
    public function get_items($request)
    {
        // No need of sanitization as it's not touching our DB.
        $q = $request->get_params();

        /**
         * Filters the search query argument.
         *
         * @param array            $q
         * @param \WP_REST_Request $request
         */
        $q = apply_filters('fnugg_search_query_args', $q, $request);

        $transient = Helpers::trans_id($q, get_class($this));

        $content = get_transient($transient);

        if (! empty($content)) {
            return $content;
        }

        $content = null;

        /**
         * Filters the search query result.
         *
         * @param array $q
         */
        $content = apply_filters('fnugg_search_result', $this->fetch->search($q));

        // Search getting cached for 1 HOUR
        set_transient($transient, $content, HOUR_IN_SECONDS);

        return $content;
    }
}
