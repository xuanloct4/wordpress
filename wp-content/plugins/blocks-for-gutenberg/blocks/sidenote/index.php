<?php

add_action( 'enqueue_block_editor_assets', function() {

	wp_enqueue_script(
		'blocks-for-gutenberg/admin/sidenote',
		plugins_url( 'block.js', __FILE__ ),
		array( 'wp-blocks', 'wp-i18n', 'wp-element' ),
		filemtime( plugin_dir_path( __FILE__ ) . 'block.js' )
	);

	wp_enqueue_style(
		'blocks-for-gutenberg/admin/sidenote',
		plugins_url( 'editor.css', __FILE__ ),
		array( 'wp-edit-blocks' ),
		filemtime( plugin_dir_path( __FILE__ ) . 'editor.css' )
	);
} );

if ( ! is_admin() ) :

	add_action( 'enqueue_block_assets', function() {

		wp_enqueue_style(
			'blocks-for-gutenberg/front/sidenote',
			plugins_url( 'style.css', __FILE__ ),
			array( 'wp-blocks' ),
			filemtime( plugin_dir_path( __FILE__ ) . 'editor.css' )
		);

	} );

endif;
