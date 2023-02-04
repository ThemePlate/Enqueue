<?php

/**
 * Helper for registered dependencies
 *
 * @package ThemePlate
 * @since   0.1.0
 */

namespace ThemePlate\Enqueue;

class LoaderTag {

	private array $scripts;
	private array $styles;


	public function __construct( array $scripts, array $styles ) {

		$this->scripts = $scripts;
		$this->styles  = $styles;

	}


	public function script( string $tag, string $handle ): string {

		if ( array_key_exists( $handle, $this->scripts ) ) {
			$string = $this->stringify( $this->scripts[ $handle ] );

			return str_replace( ' src', "$string src", $tag );
		}

		return $tag;

	}


	public function style( string $tag, string $handle ): string {

		if ( array_key_exists( $handle, $this->styles ) ) {
			$string = $this->stringify( $this->styles[ $handle ] );

			return str_replace( ' href=', "$string href=", $tag );
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
