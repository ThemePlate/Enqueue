<?php

/**
 * Helper for registered dependencies
 *
 * @package ThemePlate
 * @since   0.1.0
 */

namespace ThemePlate\Enqueue;

class Dynamic {

	private array $scripts;
	private array $styles;


	public function action(): void {

		foreach ( array( 'scripts', 'styles' ) as $type ) {
			if ( ! empty( $this->{$type} ) ) {
				foreach ( $this->{$type} as $handle => $src ) {
					$function = 'wp_enqueue_' . rtrim( $type, 's' );

					$function( $handle, $src );
				}
			}
		}

	}


	public function script( string $handle, string $src = '' ): void {

		$this->scripts[ $handle ] = $src;

	}


	public function style( string $handle, string $src = '' ): void {

		$this->styles[ $handle ] = $src;

	}

}
