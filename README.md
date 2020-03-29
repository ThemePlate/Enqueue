# ThemePlate Enqueue

## Usage

```php

use ThemePlate\Enqueue;

function theme_scripts() {
	wp_enqueue_script( 'main-script', 'PATH_TO_MAIN_JS' );
	wp_script_add_data( 'main-script', 'async', true );
	wp_enqueue_script( 'extra-script', 'PATH_TO_EXTRA_JS' );
	wp_script_add_data( 'extra-script', 'defer', true );

	Enqueue::init();
}
add_action( 'wp_enqueue_scripts', 'theme_scripts' );
```
