<?php

/**
 * @package ThemePlate
 */

namespace Tests;

use Brain\Monkey;
use PHPUnit\Framework\TestCase;
use ThemePlate\Enqueue\CustomData;
use function Brain\Monkey\Functions\expect;
use function Brain\Monkey\Functions\stubEscapeFunctions;

class CustomDataTest extends TestCase {
	protected function setUp(): void {
		parent::setUp();
		Monkey\setUp();
	}

	protected function tearDown(): void {
		Monkey\tearDown();
		parent::tearDown();
	}

	public function for_add_method_asset_triggers_an_error_on_unwanted_type(): array {
		return array(
			'with unknown type passed'   => array( 'try' ),
			'with incorrect type passed' => array( 'StYlE' ),
		);
	}

	/**
	 * @dataProvider for_add_method_asset_triggers_an_error_on_unwanted_type
	 */
	public function test_old_method_asset_triggers_an_error_on_unwanted_type( string $type ): void {
		stubEscapeFunctions();
		expect( '_doing_it_wrong' )->withAnyArgs()->once();


		( new CustomData() )->add( $type, '', array() );

		$this->assertTrue( true );
	}

	public function for_filter_only_return_wanted_attributes(): array {
		return array(
			'with script and wanted attributes' => array(
				'scripts',
				array(
					'async'       => true,
					'crossorigin' => 'anonymous',
					'my-attr'     => 'hello',
					'data-custom' => 'hello',
				),
				array(
					'async'       => true,
					'crossorigin' => 'anonymous',
					'data-custom' => 'hello',
				),
			),
			'with style and wanted attributes'  => array(
				'styles',
				array(
					'hreflang'       => 'en-tl',
					'referrerpolicy' => 'origin',
					'my-attr'        => 'hello',
					'data-custom'    => 'hello',
				),
				array(
					'hreflang'       => 'en-tl',
					'referrerpolicy' => 'origin',
					'data-custom'    => 'hello',
				),
			),
		);
	}
	/**
	 * @dataProvider for_filter_only_return_wanted_attributes
	 */
	public function test_filter_only_return_wanted_attributes( string $type, array $data, array $expected ): void {
		$actual = ( new CustomData() )->filter( $data, $type );

		$this->assertSame( $expected, $actual );
	}
}
