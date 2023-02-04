<?php

/**
 * Helper for registered dependencies
 *
 * @package ThemePlate
 * @since   0.1.0
 */

namespace ThemePlate\Enqueue;

class LoaderTag {

	private string $attribute;
	private array $dependencies;


	/**
	 * @param string $attribute   Where the stringified attributes will be prepended
	 * @param array $dependencies List of dependencies to be handled with their attributes
	 */
	public function __construct( string $attribute, array $dependencies ) {

		$this->attribute    = $attribute;
		$this->dependencies = $dependencies;

	}


	public function filter( string $tag, string $handle ): string {

		if ( array_key_exists( $handle, $this->dependencies ) ) {
			$string = $this->stringify( $this->dependencies[ $handle ] );

			return str_replace( " {$this->attribute}=", "$string {$this->attribute}=", $tag );
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
