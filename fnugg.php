<?php // -*- coding: utf-8 -*-
declare(strict_types=1);

/**
 * Plugin Name:     Fnugg
 * Plugin URI:      https://github.com/codemascot/fnugg
 * Description:     A Gutenberg block for Fnugg API.
 * Author:          Khan Mohammad R. <codemascot@hotmail.com>
 * Author URI:      https://www.codemascot.com/
 * Text Domain:     fnugg
 * Domain Path:     /languages
 * Version:         1.1.0
 *
 * @package         Fnugg
 */

namespace Fnugg;

/**
 * Defining base constant.
 */
defined('ABSPATH') || die;

/**
 * Initialize all the plugin things.
 *
 * @throws \Throwable
 *
 * @return array|void
 */
function initialize()
{
    try {
        // Translation directory updated !
        load_plugin_textdomain(
            'fnugg',
            true,
            basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'languages'
        );

        $autoload = __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

        /**
         * Checking if vendor/autoload.php exists or not.
         */
        if (file_exists($autoload)) {
            /** @noinspection PhpIncludeInspection */
            require_once $autoload;
        }

        // Loading the core modules of the plugin
        $modules = [
            'api'   => (new Api\Api())->init(),
            'block' => (new Block\Block([
                'dir'  => __DIR__,
                'file' => __FILE__
            ]))->init(),
        ];

        /**
         * Filters the core modules of the plugin.
         *
         * @param array $modules
         */
        return apply_filters('fnugg_core_modules', $modules);
    } catch (\Throwable $throwable) {
        if (defined('WP_DEBUG') && WP_DEBUG) {
            throw $throwable;
        }

        /**
         * Fires when any error happens.
         *
         * @param \Throwable $throwable
         */
        do_action('fnugg_error', $throwable);
    }
}
add_action('plugins_loaded', __NAMESPACE__ . '\\initialize');
