<?php

/*
Plugin Name: Lawyerist Shortcodes
Plugin URI: http://lawyerist.com
Description: A plugin with shortcodes for Lawyerist.com.
Author: Sam Glover
Version: [See README.md for changelog]
Author URI: http://samglover.net
*/

/* INDEX
- Preserve Markdown in Shortcodes
- Index
- Image Credits
- Pullquotes
- Pullouts
- Testimonials
- Get Script
- List Featured Products
- List Authors
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

  return '<aside><blockquote class="testimonial" markdown="1"><span class="sponsored_testimonial_quotation">&ldquo;' . $quotation . '&rdquo;</span><span class="sponsored_testimonial_source postmeta">—' . $source . '</span><span class="sponsored_testimonial_label">Testimonial Provided by Sponsor</span></blockquote></aside>';
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
List Featured Product Shortcode
------------------------------*/

function lawyerist_featured_products_list( $atts ) {

	$parent = get_the_ID();

	// Shortcode attributes.
	$attributes = shortcode_atts( array(
    'parent'  => $parent,
  ), $atts );

	// Query variables.
	$featured_products_list_query_args = array(
		'order'						=> 'ASC',
		'orderby'					=> 'title',
		'post_parent'			=> $parent,
		'post_type'				=> 'page',
		'posts_per_page'	=> 5, // Determines how many page are displayed in the list.
		'tax_query' => array(
			array(
				'taxonomy' => 'page_type',
				'field'    => 'slug',
				'terms'    => 'featured-product',
			),
		),
	);

	// Counter for inserting mobile ads and other stuff.
	$page_num = 1;


	// Create the trial button variables.
	ob_start(); ?>
		<div id='div-gpt-ad-1516133426824-0' style='height:75px; width:300px;'>
		<script>
		googletag.cmd.push(function() { googletag.display('div-gpt-ad-1516133426824-0'); });
		</script>
		</div>
	<?php $button01 = ob_get_clean();

	ob_start(); ?>
		<div id='div-gpt-ad-1516133426824-1' style='height:75px; width:300px;'>
		<script>
		googletag.cmd.push(function() { googletag.display('div-gpt-ad-1516133426824-1'); });
		</script>
		</div>
	<?php $button02 = ob_get_clean();

	ob_start(); ?>
		<div id='div-gpt-ad-1516133426824-2' style='height:75px; width:300px;'>
		<script>
		googletag.cmd.push(function() { googletag.display('div-gpt-ad-1516133426824-2'); });
		</script>
		</div>
	<?php $button03 = ob_get_clean();

	ob_start(); ?>
		<div id='div-gpt-ad-1516133426824-3' style='height:75px; width:300px;'>
		<script>
		googletag.cmd.push(function() { googletag.display('div-gpt-ad-1516133426824-3'); });
		</script>
		</div>
	<?php $button04 = ob_get_clean();

	ob_start(); ?>
		<div id='div-gpt-ad-1516133426824-4' style='height:75px; width:300px;'>
		<script>
		googletag.cmd.push(function() { googletag.display('div-gpt-ad-1516133426824-4'); });
		</script>
		</div>
	<?php $button05 = ob_get_clean();


	$featured_products_list_query = new WP_Query( $featured_products_list_query_args );

	if ( $featured_products_list_query->post_count > 1 ) :

		echo '<div class="featured_products_heading">Featured Products</div>';

		echo '<ul class="product-pages-list featured-products-list">';

			// Start the Loop.
			while ( $featured_products_list_query->have_posts() ) : $featured_products_list_query->the_post();

				$featured_page_ID			= get_the_ID();
				$featured_page_title	= the_title( '', '', FALSE );
				$featured_page_URL		= get_permalink();

        $seo_descr  = get_post_meta( $featured_page_ID, '_yoast_wpseo_metadesc', true );

        if ( !empty( $seo_descr ) ) {
          $page_excerpt = $seo_descr;
        } else {
          $page_excerpt = get_the_excerpt();
        }

				echo '<li class="listing-item">';

					if ( has_post_thumbnail() ) {
						echo '<a class="image" href="' . $featured_page_URL . '">';
						the_post_thumbnail( 'thumbnail' );
						echo '</a>';
					}

					echo '<div class="title_container">';

						echo '<a class="title" href="' . $featured_page_URL . '">' . $featured_page_title . '</a>';

						if ( function_exists( 'wp_review_show_total' ) ) {

		          $rating = get_post_meta( $featured_page_ID, 'wp_review_comments_rating_value', true );

		          echo '<span class="user-rating">';
			          if ( !empty( $rating ) ) {
			            wp_review_show_total();
			          }
							echo '</span>';

		        }

					echo '</div>'; // End .title_container

					echo '<div class="trial-button">';
						if ( $page_num == 1 ) { echo $button01; }
						elseif ( $page_num == 2 ) { echo $button02; }
						elseif ( $page_num == 3 ) { echo $button03; }
						elseif ( $page_num == 4 ) { echo $button04; }
						elseif ( $page_num == 5 ) { echo $button05; }
					echo '</div>';

					echo '<div class="clear"></div>';

					echo '<span class="excerpt">' . $page_excerpt . ' <a href="' . $featured_page_URL . '">Learn more about ' . $featured_page_title . '.</a></span>';

					$page_num++; // Increment counter.

				echo '</li>';

			endwhile;

			wp_reset_postdata();

		echo '</ul>';

	endif; // End featured products list.

}
add_shortcode( 'list-featured-products', 'lawyerist_featured_products_list' );


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
