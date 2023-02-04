<?php

/**
 * Helper for registered dependencies
 *
 * @package ThemePlate
 * @since   0.1.0
 */

namespace ThemePlate\Enqueue;

use WP_Dependencies;

class CustomData {

	private array $scripts = array();
	private array $styles  = array();


	public function filter( array $data, string $type ): array {

		$type_class  = __NAMESPACE__ . '\\' . ucfirst( $type ) . 'Tag';
		$attributes  = array_merge( LoaderTag::ATTRIBUTES, $type_class::ATTRIBUTES );
		$intersected = array_intersect_key( $data, array_fill_keys( $attributes, '' ) );

		$custom = array_filter(
			$data,
			function( $key ) {
				return 'data-' === substr( $key, 0, 5 );
			},
			ARRAY_FILTER_USE_KEY
		);

		return array_merge( $intersected, $custom );

	}


	public function add( string $type, string $handle, array $data ): void {

		if ( ! in_array( $type, array( 'script', 'style' ), true ) ) {
			_doing_it_wrong( __METHOD__, esc_attr( 'Only "script" and "style" are known types' ), '2.2.0' );
			return;
		}

		$this->{$type . 's'}[ $handle ] = $data;

	}


	public function script( string $handle, array $data ): void {

		$this->scripts[ $handle ] = $data;

	}


	public function style( string $handle, array $data ): void {

		$this->styles[ $handle ] = $data;

	}


	public function init(): void {

		global $wp_scripts, $wp_styles;

		/** @var WP_Dependencies $dependencies */
		foreach ( array( $wp_scripts, $wp_styles ) as $dependencies ) {
			if ( empty( $dependencies->queue ) ) {
				continue;
			}

			$type = get_class( $dependencies );
			$type = strtolower( substr( $type, 3 ) );

			foreach ( $dependencies->registered as $dependency ) {
				$specified = $this->filter( $dependency->extra, $type );

				if ( ! empty( $specified ) ) {
					$this->{$type}[ $dependency->handle ] = $specified;
				}
			}
		}

	}


	public function action(): void {

		if ( ! empty( $this->scripts ) ) {
			$script_tag = new ScriptsTag( $this->scripts );

			add_filter( 'script_loader_tag', array( $script_tag, 'filter' ), 10, 2 );
		}

		if ( ! empty( $this->styles ) ) {
			$style_tag = new StylesTag( $this->styles );

			add_filter( 'style_loader_tag', array( $style_tag, 'filter' ), 10, 2 );
		}

	}

}
