<?php // -*- coding: utf-8 -*-
declare(strict_types=1);

namespace Fnugg\Admin;

/**
 * Defining base constant.
 */
defined('ABSPATH') || die;

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

        ob_start();
        include_once 'Views/html-render-frontend.php';
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
