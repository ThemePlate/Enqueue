<?php

/**
 * Helper for registered dependencies
 *
 * @package ThemePlate
 * @since   0.1.0
 */

namespace ThemePlate\Enqueue;

abstract class LoaderTag {

	private array $dependencies;

	public const MAIN_PROPERTY = '';

	public const ATTRIBUTES = array(
		'blocking',
		'crossorigin',
		'integrity',
		'referrerpolicy',
		'type',
	);

	/**
	 * @param array $dependencies List of dependencies to be handled with their attributes
	 */
	public function __construct( array $dependencies ) {

		$this->dependencies = $dependencies;

	}


	public function filter( string $tag, string $handle ): string {

		if ( array_key_exists( $handle, $this->dependencies ) ) {
			$property   = static::MAIN_PROPERTY;
			$attributes = $this->stringify( $this->dependencies[ $handle ] );

			return str_replace( " $property=", "$attributes $property=", $tag );
		}

		return $tag;

	}


	private function stringify( array $attributes ): string {

		$string = '';

		foreach ( array_filter( $attributes ) as $attr => $value ) {
			if ( is_array( $value ) ) {
				$value = $value[0];
			}

			if ( is_bool( $value ) ) {
				$string .= " $attr";
			} elseif ( ! is_array( $value ) ) {
				$value   = esc_attr( $value );
				$string .= " $attr='$value'";
			}
		}

		return $string;

	}

}
