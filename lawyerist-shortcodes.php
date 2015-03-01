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
Image Credits
--------------------------------------------------*/

add_shortcode('image_credit','lawyerist_image_credit_shortcode');
function lawyerist_image_credit_shortcode( $atts, $content = null ) {
  return '<small>' . $content . '</small>';
}


/*--------------------------------------------------
Pullquotes
--------------------------------------------------*/

add_shortcode('pullquote','lawyerist_pullquote_shortcode');
function lawyerist_pullquote_shortcode( $atts, $content = null ) {
  return '<aside><blockquote markdown="1">' . $content . '</blockquote></aside>';
}



/*--------------------------------------------------
Pullouts
--------------------------------------------------*/

add_shortcode('pullout','lawyerist_pullout_shortcode');
function lawyerist_pullout_shortcode( $atts, $content = null ) {
  return '<aside><p class="pullout" markdown="1"><span class="pullout_label">Related </span>' . $content . '</p></aside>';
}


/*------------------------------
List Authors
------------------------------*/

add_shortcode('author-list','list_authors_shortcode');
function list_authors_shortcode() {

  $active_writer_args = array(
    'role'    => 'Contributor',
    'exclude' => array(26,32,37), // Exclude Guest, Sponsor, and Lawyerist users
    'orderby' => 'post_count',
    'order' => 'DESC',
  );

  $active_writers = new WP_User_Query( $active_writer_args );

  ob_start();

    echo '<ul class="author_list">';

    if ( ! empty( $active_writers->results ) ) {
      foreach ( $active_writers->results as $writer ) {
        if ( count_user_posts($writer->ID) > 0 ) {
          echo '<li><a href="https://lawyerist.com/author/' . $writer->user_login . '/">' . get_avatar( $writer->ID, 100 ) . '<br />' . $writer->display_name . '</a></li>';
        }
      }
    } else {
      echo 'No writers found.';
    }

    echo '</ul>';

  $active_writers_list = ob_get_clean();

  return $active_writers_list;

}
