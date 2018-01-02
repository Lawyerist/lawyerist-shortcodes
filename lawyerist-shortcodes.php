<?php

/*
Plugin Name: Lawyerist Shortcodes
Plugin URI: http://lawyerist.com
Description: A plugin with shortcodes for Lawyerist.com.
Author: Sam Glover
Version: [See README.md for changelog]
Author URI: http://samglover.net
*/


/*--------------------------------------------------
Preserve Markdown in Shortcodes
--------------------------------------------------*/

add_filter( 'jetpack_markdown_preserve_shortcodes', '__return_false' );


/*--------------------------------------------------
Index
--------------------------------------------------*/

function lawyerist_index_shortcode( $atts, $content = null ) {
  return '<div class="post_index" markdown="1">' . $content . '</div>';
}
add_shortcode('index','lawyerist_index_shortcode');


/*--------------------------------------------------
Image Credits
--------------------------------------------------*/

function lawyerist_image_credit_shortcode( $atts, $content = null ) {
  return '<small>' . $content . '</small>';
}
add_shortcode('image_credit','lawyerist_image_credit_shortcode');


/*--------------------------------------------------
Pullquotes
--------------------------------------------------*/

function lawyerist_pullquote_shortcode( $atts, $content = null ) {
  return '<aside><blockquote class="pullquote" markdown="1">' . $content . '</blockquote></aside>';
}
add_shortcode( 'pullquote', 'lawyerist_pullquote_shortcode' );


/*--------------------------------------------------
Pullouts
--------------------------------------------------*/

function lawyerist_pullout_shortcode( $atts, $content = null ) {
  return '<aside class="pullout"><p class="pullout" markdown="1"><span class="pullout_label">Related </span>' . $content . '</p></aside>';
}
add_shortcode( 'pullout', 'lawyerist_pullout_shortcode' );


/*--------------------------------------------------
Testimonials
--------------------------------------------------*/

function lawyerist_testimonial_shortcode( $atts, $quotation = null ) {

  $attributes = shortcode_atts( array(
    'source'  => '',
  ), $atts );

  $source = $attributes['source'];

  return '<aside><blockquote class="testimonial" markdown="1"><span class="sponsored_testimonial_label">Sponsored Testimonial Placement</span><span class="sponsored_testimonial_quotation">' . $quotation . '</span><span class="sponsored_testimonial_source postmeta">—' . $source . '</span></blockquote></aside>';
}
add_shortcode( 'testimonial', 'lawyerist_testimonial_shortcode' );


/*--------------------------------------------------
Get Script
--------------------------------------------------*/

function lawyerist_get_script_shortcode( $atts ) {
    $a = shortcode_atts( array(
        'file' => ''
    ), $atts );

    $dir = get_template_directory_uri();

    return '<script type="text/javascript" src="' . $dir . '/js/' . $a['file'] . '"></script>';

}
add_shortcode( 'get-script', 'lawyerist_get_script_shortcode' );


/*------------------------------
List Authors
------------------------------*/

function list_authors_shortcode() {

  global $wpdb;
  $blog_id = get_current_blog_id();

  $active_writer_args = array(
    'role__in'  => array( 'Administrator', 'Editor', 'Author', 'Contributor' ),
    'exclude'   => array( 78, 5, 95, 26, 32, 37 ), // Aaron, Sam, Lisa, Guest, Sponsor, and Lawyerist users
    'orderby'   => 'post_count',
    'order'     => 'DESC'
  );

  $active_writers = new WP_User_Query( $active_writer_args );

  ob_start();

    echo '<ul class="author_list">';

    if ( ! empty( $active_writers->results ) ) {
      foreach ( $active_writers->results as $writer ) {
        if ( count_user_posts($writer->ID) > 0 ) {
          echo '<li><a href="' . get_author_posts_url( $writer->ID ) . '">' . get_avatar( $writer->ID, 100 ) . '<br />' . $writer->display_name . '</a></li>';
        }
      }
    } else {
      echo 'No writers found.';
    }

    echo '</ul>';

  $active_writers_list = ob_get_clean();

  return $active_writers_list;

}
add_shortcode('author-list','list_authors_shortcode');
