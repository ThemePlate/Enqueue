<?php

/**
 * Helper for registered dependencies
 *
 * @package ThemePlate
 * @since 0.1.0
 */

namespace ThemePlate;

class Enqueue {

	private static $storage = array();

	public static function init() {

		global $wp_scripts;

		if ( empty( $wp_scripts->queue ) ) {
			return;
		}

		foreach ( $wp_scripts->registered as $handle => $dependency ) {
			$attribute = '';

			if ( ! empty( $dependency->extra['async'] ) ) {
				$attribute .= 'async ';
			}

			if ( ! empty( $dependency->extra['defer'] ) ) {
				$attribute .= 'defer ';
			}

			if ( $attribute ) {
				self::$storage[ $handle ] = trim( $attribute );
			}
		}

		if ( ! empty( self::$storage ) ) {
			add_filter( 'script_loader_tag', array( Enqueue::class, 'hooker' ), 10, 2 );
		}

	}


	public static function hooker( $tag, $handle ) {

		if ( array_key_exists( $handle, self::$storage ) ) {
			$attribute = self::$storage[ $handle ];

			return str_replace( ' src', " $attribute src", $tag );
		}

		return $tag;

	}

}
