<?php

/**
 * Helper for registered dependencies
 *
 * @package ThemePlate
 * @since   0.1.0
 */

namespace ThemePlate\Enqueue;

class StylesTag extends LoaderTag {

	public const MAIN_PROPERTY = 'href';

	// https://developer.mozilla.org/en-US/docs/Web/HTML/Element/style#attributes
	public const ATTRIBUTES = array(
		'as',
		'href',
		'hreflang',
		'imagesizes',
		'imagesrcset',
		'media',
		'rel',
		'title',
	);

}
