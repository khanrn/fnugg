<?php // -*- coding: utf-8 -*-
declare(strict_types=1);

namespace Fnugg\Tests;

use \Fnugg\Block\Block;
use \WP_Mock\Tools\TestCase;

class BlockTest extends TestCase
{
    private $block;
    private $atts = [
        'name'         => 'Test Name',
        'sourceFields' => 'name,description',
    ];

    public function setUp() : void
    {
        parent::setUp();
        $this->block = new Block([
            'dir'  => realpath(__DIR__ . '/../../'),
            'file' => realpath(__DIR__ . '/../../') . '/fnugg.php',
        ]);

        \WP_Mock::userFunction(
            'is_admin',
            [
                'return' => true,
            ]
        );
    }

    public function tearDown() : void
    {
        parent::tearDown();
    }

    public function test_init_block_added_to_action_hook_init() : void
    {
        \WP_Mock::expectActionAdded('init', [$this->block, 'init_block']);
        $this->block->init();
        $this->assertActionsCalled();
    }

    public function test_block_editor_assets_added_to_action_hook_enqueue_block_editor_assets() : void
    {
        \WP_Mock::expectActionAdded('enqueue_block_editor_assets', [$this->block, 'block_editor_assets']);
        $this->block->init();
        $this->assertActionsCalled();
    }

    public function test_block_assets_added_to_action_hook_enqueue_block_assets() : void
    {
        \WP_Mock::expectActionAdded('enqueue_block_assets', [$this->block, 'block_assets']);
        $this->block->init();
        $this->assertActionsCalled();
    }
}
