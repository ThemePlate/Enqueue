<?php

/**
 * Helper for registered dependencies
 *
 * @package ThemePlate
 * @since 0.1.0
 */

namespace ThemePlate;

class Enqueue {

	private static $storage    = array();
	private static $attributes = array(
		'async',
		'crossorigin',
		'defer',
		'integrity',
		'nomodule',
		'nonce',
		'referrerpolicy',
		'type',
	);


	public static function init() {

		add_action( 'wp_enqueue_scripts', array( Enqueue::class, 'action' ), PHP_INT_MAX );

	}


	public static function action() {

		global $wp_scripts;

		if ( empty( $wp_scripts->queue ) ) {
			return;
		}

		foreach ( $wp_scripts->registered as $handle => $dependency ) {
			$specified = array_intersect( array_keys( $dependency->extra ), self::$attributes );

			if ( ! empty( $specified ) ) {
				foreach ( $specified as $attribute ) {
					self::$storage[ $handle ][ $attribute ] = $dependency->extra[ $attribute ];
				}
			}
		}

		if ( ! empty( self::$storage ) ) {
			add_filter( 'script_loader_tag', array( Enqueue::class, 'hooker' ), 10, 2 );
		}

	}


	public static function hooker( $tag, $handle ) {

		if ( array_key_exists( $handle, self::$storage ) ) {
			$string = '';

			foreach ( self::$storage[ $handle ] as $attr => $value ) {
				if ( is_bool( $value ) ) {
					$string .= " $attr";
				} else {
					$value   = esc_attr( $value );
					$string .= " $attr='$value'";
				}
			}

			return str_replace( ' src', "$string src", $tag );
		}

		return $tag;

	}

}
