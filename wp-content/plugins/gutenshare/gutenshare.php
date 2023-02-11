<?php
/**
 * Plugin Name:       Gutenshare
 * Description:       A Gutenberg block to show your pride! This block enables you to type text and style it with the color font Gilbert from Type with Pride.
 * Version:           0.1.0
 * Requires at least: 6.1
 * Requires PHP:      7.0
 * Author:            The WordPress Contributors
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       gutenshare
 *
 * @package           create-block
 */

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function create_block_gutenshare_block_init() {
	register_block_type( __DIR__ . '/build', [
		'render_callback' => 'dynamicblock_renderer1',
	] );
}
add_action( 'init', 'create_block_gutenshare_block_init' );


function dynamicblock_renderer1($attributes){
	//    echo '<pre>';
	// 	print_r($attributes);
	//    echo '</pre>';
	//    die();
	// echo "<h2>"."Gutenshare >>> Result from guten pride API: "."</h2>";
	   ob_start();
	  
	   $cats = $attributes["categories"];
	   foreach($cats as $cat) {
		// print_r($cat["title"]);
		$title = $cat["title"];
		echo "<h5>".$title."</h5>";
	   }
	
	return ob_get_clean();
		// return '<div>Success</div>';
	
	}