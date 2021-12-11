<?php

/**
 * Helper for registered dependencies
 *
 * @package ThemePlate
 * @since   0.1.0
 */

namespace ThemePlate;

use ThemePlate\Enqueue\CustomData;
use ThemePlate\Enqueue\Dynamic;

class Enqueue {

	private static Dynamic $dynamic;


	public static function init(): void {

		$custom_data   = new CustomData();
		self::$dynamic = new Dynamic();

		add_action( 'wp_enqueue_scripts', array( $custom_data, 'action' ), PHP_INT_MAX );
		add_action( 'wp_enqueue_scripts', array( self::$dynamic, 'action' ) );

	}


	public static function asset( string $type, string $handle ): void {

		self::$dynamic->$type( $handle );

	}

}
