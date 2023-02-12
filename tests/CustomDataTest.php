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

	public function for_methods_will_trigger_an_error_on_unwanted_type(): array {
		return array(
			'with unknown type passed'   => array( 'try' ),
			'with incorrect type passed' => array( 'StYlE' ),
		);
	}

	/**
	 * @dataProvider for_methods_will_trigger_an_error_on_unwanted_type
	 */
	public function test_add_method_asset_triggers_an_error_on_unwanted_type( string $type ): void {
		stubEscapeFunctions();
		expect( '_doing_it_wrong' )->withAnyArgs()->once();

		( new CustomData() )->add( $type, '', array() );

		$this->assertTrue( true );
	}

	/**
	 * @dataProvider for_methods_will_trigger_an_error_on_unwanted_type
	 */
	public function test_filter_method_data_triggers_an_error_on_unwanted_type( string $type ): void {
		stubEscapeFunctions();
		expect( '_doing_it_wrong' )->withAnyArgs()->once();

		( new CustomData() )->filter( array(), $type );

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
		stubEscapeFunctions();
		expect( '_deprecated_function' )->withAnyArgs()->once();

		$actual = ( new CustomData() )->filter( $data, $type );

		$this->assertSame( $expected, $actual );
	}

	public function for_action_has_wanted_filter(): array {
		// phpcs:disable WordPress.Arrays.MultipleStatementAlignment
		return array(
			'with scripts and no custom data added to handles' => array(
				'script',
				false,
			),
			'with scripts and custom data added to handles'    => array(
				'script',
				true,
			),
			'with styles and no custom data added to handles'  => array(
				'style',
				false,
			),
			'with styles and custom data added to handles'     => array(
				'style',
				true,
			),
		);
		// phpcs:enable WordPress.Arrays.MultipleStatementAlignment
	}

	/**
	 * @dataProvider for_action_has_wanted_filter
	 */
	public function test_action_has_wanted_filter( string $type, bool $with_data ): void {
		$custom = new CustomData();

		if ( $with_data ) {
			$custom->$type( 'test', array( 'this' => 'please' ) );
		}

		$custom->action();

		$type_class = 'ThemePlate\Enqueue\\' . ucfirst( $type ) . 'sTag';
		$has_filter = has_filter( $type . '_loader_tag', $type_class . '->filter()' );

		if ( $with_data ) {
			$this->assertSame( 10, $has_filter );
		} else {
			$this->assertFalse( $has_filter );
		}
	}
}
