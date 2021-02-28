<?php // -*- coding: utf-8 -*-
declare(strict_types=1);

namespace Fnugg\Data;

/**
 * Defining base constant.
 */
defined('ABSPATH') || die;

/**
 * Fnugg API data related abstract functions.
 *
 * @author Khan Mohammad R. <codemascot@hotmail.com>
 */
interface Data
{
    /**
     * Getting `autocomplete` remote API data.
     *
     * @param array $q GET query parameters.
     *
     * @return array
     */
    public function autocomplete(array $q) : array;

    /**
     * Getting `search` remote API data.
     *
     * @param array $q GET query parameters.
     *
     * @return array
     */
    public function search(array $q) : array;
}
