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
				foreach ( $this->{$type} as $handle ) {
					$function = 'wp_enqueue_' . rtrim( $type, 's' );

					$function( $handle );
				}
			}
		}

	}


	public function script( string $handle ): void {

		$this->scripts[] = $handle;

	}


	public function style( string $handle ): void {

		$this->styles[] = $handle;

	}

}
