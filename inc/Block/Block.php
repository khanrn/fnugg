<?php // -*- coding: utf-8 -*-
declare(strict_types=1);

namespace Fnugg\Block;

/**
 * Defining base constant.
 */
defined('ABSPATH') || die;

use Fnugg\Shared\Helpers;

/**
 * Initiating Gutenberg Fnugg Block.
 *
 * @author  Khan Mohammad R. <codemascot@hotmail.com>
 *
 * @package Fnugg\Block
 */
final class Block
{
    /**
     * Arguments.
     *
     * @var array
     */
    protected array $args = [];

    /**
     * __DIR__ and __FILE__ values.
     *
     * @var array
     */
    protected string $asset = '';

    /**
     * Constructor.
     *
     * @param array $args Arguments.
     *
     * @return void
     */
    public function __construct(array $args)
    {
        $this->args = $args;
        $this->asset = $this->args['dir'] . '/build/index.asset.php';
    }

    /**
     * Initializing hooks.
     *
     * @return void
     */
    public function init() : void
    {
        add_action('init', [$this, 'init_block']);
        add_action('enqueue_block_editor_assets', [$this, 'block_editor_assets']);
        add_action('enqueue_block_assets', [$this, 'block_assets']);
    }

    /**
     * Registers block editor assets, for backend.
     *
     * @return void
     */
    public function block_editor_assets() : void
    {
        $index_js     = 'build/index.js';
        $script_asset = require $this->asset;
        wp_register_script(
            'codemascot-fnugg-block-editor',
            plugins_url($index_js, $this->args['file']),
            $script_asset['dependencies'],
            $script_asset['version']
        );
        wp_set_script_translations('codemascot-fnugg-block-editor', 'fnugg');

        $editor_css = 'build/index.css';
        wp_register_style(
            'codemascot-fnugg-block-editor',
            plugins_url($editor_css, $this->args['file']),
            [],
            filemtime($this->args['dir'] . '/' . $editor_css)
        );
    }

    /**
     * Registers block assets, for frontend.
     *
     * @return void
     */
    public function block_assets() : void
    {
        $style_css = 'build/style-index.css';
        wp_register_style(
            'codemascot-fnugg-block',
            plugins_url($style_css, $this->args['file']),
            [],
            filemtime($this->args['dir'] . '/' . $style_css)
        );
    }

    /**
     * Frontend redering in server side
     *
     * @see wp-includes/class-wp-block-type.php, WP_Block_Type():render()
     *
     * @param array $atts
     *
     * @return string
     */
    public function render($atts)
    {
        if (is_admin()) {
            return;
        }

        /**
         * Filters search API GET params.
         *
         * @param array $q
         * @param array $atts
         */
        $q = apply_filters('fnugg_frontend_self_api_search_params', ['q' => $atts['name']], $atts);

        /**
         * Filters frontend search API response.
         *
         * @param array $resp
         * @param array $atts
         */
        $response = apply_filters(
            'fnugg_frontend_self_api_search_response',
            Helpers::get_remote_json(
                add_query_arg(
                    $q,
                    get_rest_url(null, 'codemascot/v1/search/')
                )
            ),
            $atts
        );

        ob_start();
        /**
         * Fires at HTML frontend render.
         *
         * @param array $response
         * @param array $atts
         */
        do_action('fnugg_frontend_render_html', $response, $atts);
        return ob_get_clean();
    }

    /**
     * Initiating the block.
     *
     * @return void
     */
    public function init_block() : void
    {
        if (! file_exists($this->asset)) {
            throw new \Error(
                'You need to run `npm start` or `npm run build` for the "codemascot/fnugg" block first.'
            );
        }

        register_block_type(
            'codemascot/fnugg',
            [
                'editor_script'   => 'codemascot-fnugg-block-editor',
                'editor_style'    => 'codemascot-fnugg-block-editor',
                'style'           => 'codemascot-fnugg-block',
                'attributes'      => [
                    'name'      => ['type' => 'string', 'default' => ''],
                    'site_path' => ['type' => 'string', 'default' => ''],
                ],
                'render_callback' => [$this, 'render'],
            ]
        );
    }
}