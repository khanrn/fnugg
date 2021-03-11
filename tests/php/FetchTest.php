<?php // -*- coding: utf-8 -*-
declare(strict_types=1);

namespace Fnugg\Tests;

use \WP_Mock\Tools\TestCase;
use \Fnugg\Data;
use \Fnugg\Shared\Helpers;
use \Fnugg\Block\Block;

class FetchTest extends TestCase
{
	private $body = [
		'total'        => 14,
		'more_results' => true,
		'result'       => [
			0 => [
				'name'      => 'Feforbakken',
				'site_path' => '/feforbakken/',
			],
			1 => [
				'name'      => 'Myrkdalen Fjellheiser',
				'site_path' => '/myrkdalen/',
			],
			2 => [
				'name'      => 'Fagerfjell skisenter',
				'site_path' => '/fagerfjell/',
			],
			3 => [
				'name'      => 'Høgevarde Fjellpark',
				'site_path' => '/hogevarde/',
			],
			4 => [
				'name'      => 'Sulitjelma Fjellandsby',
				'site_path' => '/sulitjelma/',
			],
			5 => [
				'name'      => 'Furedalen Alpin',
				'site_path' => '/furedalen/',
			],
			6 => [
				'name'      => 'Fjellhaugen Skisenter',
				'site_path' => '/fjellhaugen/',
			],
			7 => [
				'name'      => 'Sunnmørsalpane Skiarena Fjellsætra',
				'site_path' => '/sunnmorsalpene/',
			],
			8 => [
				'name'      => 'Fjellsyn skisenter',
				'site_path' => '/fjellsyn/',
			],
			9 => [
				'name'      => 'Fulufjellet Alpinsenter',
				'site_path' => '/fulufjellet/',
			],
		],
	];

	private $url = 'https://api.fnugg.no/';
	private $uri = '/suggest/autocomplete/';

	private $q = [
		'name'         => 'Test Name',
		'sourceFields' => 'name,description',
	];


	private $fetch;

	public function __construct()
	{
		parent::__construct();
		$this->fetch = new Data\Fetch($this->url);
	}

	public function setUp(): void
	{
		parent::setUp();
		\WP_Mock::userFunction(
            'wp_remote_get',
            [
                'return' => [
                    'response' => [
                        'code'    => 200,
                        'message' => 'OK',
                    ],
                    'body'     => json_encode($this->body),
                ],
            ]
        );

        \WP_Mock::userFunction(
            'wp_remote_retrieve_body',
            [
                'return' => json_encode($this->body),
            ]
        );

		\WP_Mock::userFunction(
			'add_query_arg',
			[
				'return' => $this->url . $this->uri . str_replace('%2C', ',', http_build_query($this->q)),
			]
		);
	}

	public function test_get_remote_json_method() : void
    {
		$r = Helpers::get_remote_json($this->url);
        $this->assertTrue(is_array($r));
	}

	public function test_trans_id_string_length_is_64() : void
    {
        $h = Helpers::trans_id($this->q, 'test');

        //@see https://stackoverflow.com/a/53207044/2740232
        $s = preg_match('/^([a-f0-9]{64})$/', $h) === 1;

        $this->assertEquals(64, strlen($h));
        $this->assertTrue($s);
    }

	public function test_autocompelte_api_call() : void
	{
		$r = $this->fetch->autocomplete($this->q);
		$this->assertNotNull($r);
		$this->assertTrue(is_array($r));
		$this->assertCount(3, $r);
		$this->assertTrue($r['more_results']);
		$this->assertEquals(14, $r['total']);
		$this->assertCount(10, $r['result']);
		$this->assertNotNull($r['result'][5]['name']);
	}
}
