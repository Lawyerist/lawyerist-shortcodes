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
  return '<p class="pullout" markdown="1"><strong>Related </strong>' . $content . '</p>';
}


/*------------------------------
List Authors
------------------------------*/

add_shortcode('author-list','list_authors_shortcode');
function list_authors_shortcode() {

  $args = array(
    'exclude'			=> '5,26,32,37,50,69,78',
    'number'			=> 27,
    'optioncount' => 1,
    'order'				=> 'DESC',
    'orderby'			=> 'post_count'
  );

  ob_start();
    echo '<ul class="author_list">';
    wp_list_authors($args);
    echo '<li>…</li>';
    echo '</ul>';

  $author_list = ob_get_clean();

  return $author_list;

}
