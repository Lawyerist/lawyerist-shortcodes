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
- Image Credits
- Pullquotes
- Pullouts
- Testimonials
- Get Script
- List Child Pages
- List Products
- Get Scorecard Grade
- List Authors
*/


/*--------------------------------------------------
Preserve Markdown in Shortcodes
--------------------------------------------------*/

add_filter( 'jetpack_markdown_preserve_shortcodes', '__return_false' );


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
List Child Pages
------------------------------*/

function lawyerist_child_pages_list( $atts ) {

	$parent       = get_the_ID();
  $parent_title = the_title( '', '', FALSE );

	// Shortcode attributes.
	$atts = shortcode_atts( array(
    'portal' => $parent,
  ), $atts );

  // Query variables.
	$child_pages_list_query_args = array(
    'order'           => 'ASC',
    'orderby'         => 'menu_order',
		'post_parent'			=> $atts['portal'],
    'posts_per_page'  => -1,
		'post_type'				=> 'page',
	);

	$child_pages_list_query = new WP_Query( $child_pages_list_query_args );

	if ( $child_pages_list_query->have_posts() ) :

    ob_start();

      echo '<div id="child_pages_list">';

        echo '<div class="post_label child_pages_heading">' . $parent_title . ' Resource Pages</div>';

  			// Start the Loop.
  			while ( $child_pages_list_query->have_posts() ) : $child_pages_list_query->the_post();

  				$child_page_title	  = the_title( '', '', FALSE );
  				$child_page_URL     = get_permalink();

          echo '<div ' ;
    			post_class();
  				echo '>';

            // Starts the link container. Makes for big click targets!
  					echo '<a href="' . $child_page_URL . '" title="' . $child_page_title . '">';

              if ( has_post_thumbnail() ) {
                the_post_thumbnail( 'thumbnail' );
              } else {
                echo '<img class="attachment-thumbnail wp-post-image" src="https://lawyerist.com/lawyerist/wp-content/uploads/2018/02/L-dot.png" />';
              }

              echo '<div class="headline_excerpt">';
                echo '<h2 class="headline" title="' . $child_page_title . '">' . $child_page_title . '</h2>';
              echo '</div>'; // Close .headline_excerpt.

              echo '<div class="clear"></div>';

    				echo '</a>'; // This closes the link container.

          echo '</div>'; // This closes .index_post_container.

  			endwhile;

  			wp_reset_postdata();

  		echo '</div>'; // End #child_pages

    $all_child_pages = ob_get_clean();

	endif; // End product list.

  return $all_child_pages;

}

add_shortcode( 'list-child-pages', 'lawyerist_child_pages_list' );


/*------------------------------
List Products
------------------------------*/

function lawyerist_products_list( $atts ) {

	$parent   = get_the_ID();
  $country  = get_country();

	// Shortcode attributes.
	$atts = shortcode_atts( array(
    'portal'        => $parent,
    'show_featured' => true,
  ), $atts );

  $show_featured = filter_var( $atts['show_featured'], FILTER_VALIDATE_BOOLEAN );

  // Show featured products unless the shortcode contains show_featured="false".
  if ( $show_featured == true ) {

    // Query variables.
  	$featured_products_list_query_args = array(
  		'orderby'					=> 'rand',
  		'post_parent'			=> $atts['portal'],
  		'post_type'				=> 'page',
  		'posts_per_page'	=> -1, // Determines how many page are displayed in the list.
  		'tax_query' => array(
  			array(
  				'taxonomy' => 'page_type',
  				'field'    => 'slug',
  				'terms'    => 'featured-product',
  			),
  		),
  	);

  	$featured_products_list_query = new WP_Query( $featured_products_list_query_args );

  	if ( $featured_products_list_query->have_posts() ) :

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

          // Check for a rating.
          if ( comments_open() && function_exists( 'wp_review_show_total' ) ) {

            $rating       = get_post_meta( $featured_page_ID, 'wp_review_comments_rating_value', true );
            $review_count = lawyerist_get_review_count();

          }

  				echo '<li class="listing-item">';

  					if ( has_post_thumbnail() ) {
  						echo '<a class="image" href="' . $featured_page_URL . '">';
  						the_post_thumbnail( 'thumbnail' );
  						echo '</a>';
  					}

  					echo '<div class="title_container">';

              if ( !empty( $rating ) ) {

                echo '<div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">';
                echo '<a class="title" href="' . $featured_page_URL . '"><span itemprop="itemReviewed">' . $featured_page_title . '</span></a>';

              } else {

                echo '<a class="title" href="' . $featured_page_URL . '">' . $featured_page_title . '</a>';

              }

              // Rating
              echo '<div class="user-rating">';

                if ( !empty( $rating ) ) {

                  echo '<a href="' . $featured_page_URL . '#comments">';
                    wp_review_show_total();
                  echo ' <span class="review_count">(' . $review_count . ')</span></a>';

                } else {

                  echo '<a href="' . $featured_page_URL . '#respond">Leave a review.</a>';

                }

               echo '</div>'; // End .user_rating.

               if ( !empty( $rating ) ) {
                 echo '</div>'; // End aggregateRating schema.
               }

  					echo '</div>'; // End .title_container.

            if ( ( $country == ( US || CA ) ) && has_trial_button( $featured_page_ID ) ) {

              echo '<div class="list-products-trial-button">';
                echo  trial_button( $featured_page_ID );
              echo '</div>';

            }

  					echo '<div class="clear"></div>';

  					echo '<span class="excerpt">' . $page_excerpt . ' <a href="' . $featured_page_URL . '">Learn more about ' . $featured_page_title . '.</a></span>';

  				echo '</li>';

  			endwhile;

  			wp_reset_postdata();

  		echo '</ul>';

  	endif; // End featured products list.

  } // End featured products.

  // Alphabetical list of products.

  // Query variables.
	$product_list_query_args = array(
		'order'						=> 'ASC',
		'orderby'					=> 'title',
		'post_parent'			=> $atts['portal'],
    'posts_per_page'  => -1,
		'post_type'				=> 'page',
    'tax_query' => array(
			array(
				'taxonomy' => 'page_type',
				'field'    => 'slug',
				'terms'    => 'discontinued-product',
        'operator' => 'NOT IN'
			),
		),
	);


	$product_list_query = new WP_Query( $product_list_query_args );

	if ( $product_list_query->post_count > 1 ) :

    ob_start();

      echo '<h2>Alphabetical List</h2>';

  		echo '<ul class="product-pages-list">';

  			// Start the Loop.
  			while ( $product_list_query->have_posts() ) : $product_list_query->the_post();

  				$product_page_ID		= get_the_ID();
  				$product_page_title	= the_title( '', '', FALSE );
  				$product_page_URL		= get_permalink();

          $seo_descr  = get_post_meta( $product_page_ID, '_yoast_wpseo_metadesc', true );

          if ( !empty( $seo_descr ) ) {
            $page_excerpt = $seo_descr;
          } else {
            $page_excerpt = get_the_excerpt();
          }

          // Check for a rating.
          if ( comments_open() && function_exists( 'wp_review_show_total' ) ) {

            $rating       = get_post_meta( $product_page_ID, 'wp_review_comments_rating_value', true );
            $review_count = lawyerist_get_review_count();

          }

  				echo '<li class="listing-item">';

  					if ( has_post_thumbnail() ) {
  						echo '<a class="image" href="' . $product_page_URL . '">';
  						the_post_thumbnail( 'thumbnail' );
  						echo '</a>';
  					}

            echo '<div class="title_container">';

              if ( !empty( $rating ) ) {

                echo '<div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">';
                echo '<a class="title" href="' . $product_page_URL . '"><span itemprop="itemReviewed">' . $product_page_title . '</span></a>';

              } else {

                echo '<a class="title" href="' . $product_page_URL . '">' . $product_page_title . '</a>';

              }

              // Rating
              echo '<div class="user-rating">';

                if ( !empty( $rating ) ) {

                  echo '<a href="' . $product_page_URL . '#comments">';
                    wp_review_show_total();
                  echo ' <span class="review_count">(' . $review_count . ')</span></a>';

                } else {

                  echo '<a href="' . $product_page_URL . '#respond">Leave a review.</a>';

                }

                echo '</div>'; // End .user_rating.

                if ( !empty( $rating ) ) {
                  echo '</div>'; // End aggregateRating schema.
                }

              echo '</div>'; // End .title_container.

              if ( ( $country == ( US || CA ) ) && has_trial_button( $product_page_ID ) ) {

                echo '<div class="list-products-trial-button">';
                  echo  trial_button( $product_page_ID );
                echo '</div>';

              }

    					echo '<div class="clear"></div>';

  					echo '<span class="excerpt">' . $page_excerpt . ' <a href="' . $product_page_URL . '">Learn more about ' . $product_page_title . '.</a></span>';

  				echo '</li>';

  			endwhile;

  			wp_reset_postdata();

  		echo '</ul>';

    $all_products = ob_get_clean();

	endif; // End product list.

  return $all_products;

}

add_shortcode( 'list-products', 'lawyerist_products_list' );


/*------------------------------
Get Scorecard Grade

Returns the Scorecard grade for a given score.
Only useful in Gravity Forms confirmations.
------------------------------*/

function lawyerist_get_scorecard_grade( $atts ) {

    $atts = shortcode_atts( array(
        'form_id'   => null,
        'raw_score' => null,
        'q1'        => null,
        'q2'        => null,
        'q3'        => null,
    ), $atts );

    $form_id      = $atts['form_id'];
    $raw_score    = $atts['raw_score'];
    $goals_score  = $atts['q1'] + $atts['q2'] + $atts['q3'];

    // Checks to see which form was submitted.
    switch ( $form_id ) {

      case $form_id == '45': // Small Firm Scorecard
        $total = 500;
        break;

      case $form_id == 47: // Solo Practice Scorecard
        $total = 400;
        break;

    }

    // Calculates the % score.
    $score = ( $raw_score / $total ) * 100;

    switch ( $score ) {

      case ( $score < 60 ):
        $grade = 'F';
        break;

      case ( $score >= 60 && $score < 70 ):
        $grade = 'D';
        break;

      case ( $score >= 70 && $score < 80 ):
        $grade = 'C';
        break;

      case ( $score >= 80 && $score < 90 ):
        $grade = 'B';
        break;

      case ( $score >= 90 ):
        $grade = 'A';
        break;

    }

    ob_start();

      ?>

        <div id="scorecard_results">
          <div id="grade_box">
            <div class="grade_label">Your Firm's Score</div>
            <div class="grade"><?php echo $grade; ?></div>
            <div class="score"><?php echo $raw_score; ?>/<?php echo $total; ?></div>
          </div>
          <div id="get_results">
            <a class="button" href="#interpret_results">Interpret Your Results</a>
          </div>
          <div class="clear"></div>
        </div>

      <?php

      if ( $goals_score <= 15 ) {

        echo '<p class="alert">Regardless of your overall score, it looks like your goals need your attention. Keep reading for more information.</p>';

      }

      echo '<div id="interpret_results"></div>';

    $scorecard_results = ob_get_clean();

    return $scorecard_results;

}
add_shortcode( 'get_grade', 'lawyerist_get_scorecard_grade' );


/*------------------------------
List Authors
------------------------------*/

function list_authors_shortcode() {

  global $wpdb;

  $blog_id = get_current_blog_id();

  $author_args = array(
    'has_published_posts' => array( 'post', 'page' ),
    'exclude'             => array( 37 ),
    'orderby'             => 'post_count',
    'order'               => 'DESC',
    'role__in'            => array( 'Contributor' ),
  );

  $authors = new WP_User_Query( $author_args );

  $author_num = 1; // Counter for inserting mobile ads and other stuff.

  ob_start();

    echo '<div class="gallery gallery-columns-4">';

    if ( !empty( $authors->results ) ) {

      foreach ( $authors->results as $author ) {

        if ( count_user_posts( $author->ID ) > 0 ) {

          echo '<dl class="gallery-item">';
          echo '<dt class="gallery-icon">' . get_avatar( $author->ID, 150 ) . '</dt>';
          echo '<dd class="wp-caption-text gallery-caption"><a href="' . get_author_posts_url( $author->ID ) . '">' . $author->display_name . '</a></dd>';
          echo '</dl>';

        }

        if ( $author_num % 4 == 0 ) {

          echo '<div class="clear"></div>';

        }

        $author_num++;

      }

    } else {

      echo 'No contributors found.';

    }

    echo '</div>';
    echo '<div class="clear"></div>';

  $authors_list = ob_get_clean();

  return $authors_list;

}

add_shortcode( 'list-authors', 'list_authors_shortcode' );
