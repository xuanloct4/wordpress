<?php
/**
 * Plugin Name:       An example block for Kinsta readers
 * Description:       Businessperson
 * Requires at least: 5.8
 * Requires PHP:      7.0
 * Version:           0.1.0
 * Author:            Carlo
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       author-plugin
 *
 * @package           author-box
 */


function author_box_author_plugin_render_author_content( $attr ) {
	$args = array(
		'numberposts'	=> $attr['numberOfItems']
	);
	$my_posts = get_posts( $args );
	
	if( ! empty( $my_posts ) ){
		$output = '<div ' . get_block_wrapper_attributes() . '>';
		
		if( $attr['displayAuthorInfo'] ){
			$output .= '<div class="wp-block-author-box-author-plugin__author">';
			
			if( $attr['showAvatar'] ){
				$output .= '<div class="wp-block-author-box-author-plugin__avatar">' 
					. get_avatar( get_the_author_meta( 'ID' ), $attr['avatarSize'] ) 
					. '</div>';
			}

			$output .= '<div class="wp-block-author-box-author-plugin__author-content">';
			
			$output .= '<div class="wp-block-author-box-author-plugin__name">' 
				. get_the_author_meta( 'display_name' ) 
				. '</div>';

			if( $attr['showBio'] ){
				$output .= '<div class="wp-block-author-box-author-plugin__description">' 
					. get_the_author_meta( 'description' ) 
					. '</div>';
			}

			$output .= '</div>';
			$output .= '</div>';
		}

		$num_cols = $attr['columns'] > 1 ? strval( $attr['columns'] ) : '1';

		$output .= '<ul class="wp-block-author-box-author-plugin__post-items columns-' . $num_cols . '">';
		foreach ( $my_posts as $p ){
			
			$title = $p->post_title ? $p->post_title : 'Default title';
			$url = esc_url( get_permalink( $p->ID ) );
			$thumbnail = has_post_thumbnail( $p->ID ) ? get_the_post_thumbnail( $p->ID, 'large', array( 'class' => 'wp-block-author-box-author-plugin__post-thumbnail' ) ) : '';

			$output .= '<li>';
			if( ! empty( $thumbnail ) && $attr['displayThumbnail'] ){
				$output .= $thumbnail;
			}
			$output .= '<h5 class="wp-block-author-box-author-plugin__post-title"><a href="' . $url . '">' . $title . '</a></h5>';
			if( $attr['displayDate'] ){
				$output .= '<time datetime="' . esc_attr( get_the_date( 'c', $p ) ) . '" class="wp-block-author-box-author-plugin__post-date">' . esc_html( get_the_date( '', $p ) ) . '</time>';
			}
			if( get_the_excerpt( $p ) && $attr['displayExcerpt'] ){
				$output .= '<div class="wp-block-author-box-author-plugin__post-excerpt">' . get_the_excerpt( $p ) . '</div>';
			}
			$output .= '</li>';
		}
		$output .= '</ul>';
		$output .= '</div>';
	}
	return $output ?? '<strong>Sorry. No posts matching your criteria!</strong>';
}
/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function author_box_author_plugin_block_init() {
	register_block_type( __DIR__ . '/build', array(
		'render_callback' => 'author_box_author_plugin_render_author_content'
	) );
}
add_action( 'init', 'author_box_author_plugin_block_init' );
