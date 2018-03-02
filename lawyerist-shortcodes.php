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
- List Products
- Get Scorecard Grade
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
List Products
------------------------------*/

function lawyerist_products_list( $atts ) {

	$parent = get_the_ID();

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
  		'order'						=> 'ASC',
  		'orderby'					=> 'title',
  		'post_parent'			=> $atts['portal'],
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
  	$product_num = 1;

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

              // Rating
              if ( comments_open() && function_exists( 'wp_review_show_total' ) ) {

                $rating       = get_post_meta( $featured_page_ID, 'wp_review_comments_rating_value', true );
                $review_count = lawyerist_get_review_count();

                echo '<div class="user-rating">';

                  if ( !empty( $rating ) ) {
                    echo '<a href="' . $featured_page_URL . '#comments">';
                      wp_review_show_total();
                    echo ' (' . $review_count . ')</a>';
                  } else {
                    echo '<a href="' . $featured_page_URL . '#respond">Leave a review.</a>';
                  }

                 echo '</div>';

              }

  					echo '</div>'; // End .title_container

  					echo '<div class="trial-button">';
  						if ( $product_num == 1 ) {

                ob_start();
                ?>
                  <div id='div-gpt-ad-1517464941516-2' style='height:50px; width:170px;'>
                  <script>
                  googletag.cmd.push(function() { googletag.display('div-gpt-ad-1517464941516-2'); });
                  </script>
                  </div>
                <?php

              } elseif ( $product_num == 2 ) {

                ob_start();
                ?>
                  <div id='div-gpt-ad-1517464941516-3' style='height:50px; width:170px;'>
                  <script>
                  googletag.cmd.push(function() { googletag.display('div-gpt-ad-1517464941516-3'); });
                  </script>
                  </div>
                <?php

              } elseif ( $product_num == 3 ) {

                ob_start();
                ?>
                  <div id='div-gpt-ad-1517464941516-4' style='height:50px; width:170px;'>
                  <script>
                  googletag.cmd.push(function() { googletag.display('div-gpt-ad-1517464941516-4'); });
                  </script>
                  </div>
                <?php

              } elseif ( $product_num == 4 ) {

                ob_start();
                ?>
                  <div id='div-gpt-ad-1517464941516-5' style='height:50px; width:170px;'>
                  <script>
                  googletag.cmd.push(function() { googletag.display('div-gpt-ad-1517464941516-5'); });
                  </script>
                  </div>
                <?php

              } elseif ( $product_num == 5 ) {

                ob_start();
                ?>
                  <div id='div-gpt-ad-1517464941516-6' style='height:50px; width:170px;'>
                  <script>
                  googletag.cmd.push(function() { googletag.display('div-gpt-ad-1517464941516-6'); });
                  </script>
                  </div>
                <?php

              }

              $button = ob_get_clean();
              echo $button;

  					echo '</div>';

  					echo '<div class="clear"></div>';

  					echo '<span class="excerpt">' . $page_excerpt . ' <a href="' . $featured_page_URL . '">Learn more about ' . $featured_page_title . '.</a></span>';

  					$product_num++; // Increment counter.

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

  				echo '<li class="listing-item">';

  					if ( has_post_thumbnail() ) {
  						echo '<a class="image" href="' . $product_page_URL . '">';
  						the_post_thumbnail( 'thumbnail' );
  						echo '</a>';
  					}

  					echo '<a class="title" href="' . $product_page_URL . '">' . $product_page_title . '</a>';

            // Rating
            if ( comments_open() && function_exists( 'wp_review_show_total' ) ) {

              $rating       = get_post_meta( $product_page_ID, 'wp_review_comments_rating_value', true );
              $review_count = lawyerist_get_review_count();

              echo '<div class="user-rating">';

                if ( !empty( $rating ) ) {
                  echo '<a href="' . $product_page_URL . '#comments">';
                    wp_review_show_total();
                  echo ' (' . $review_count . ')</a>';
                } else {
                  echo '<a href="' . $product_page_URL . '#respond">Leave a review.</a>';
                }

               echo '</div>';

            }

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
        'form_id'   => '',
        'raw_score' => '',
        'q1'        => '',
        'q2'        => '',
        'q3'        => '',
    ), $atts );

    $raw_score    = $atts['raw_score'];
    $goals_score  = $atts['q1'] + $atts['q2'] + $atts['q3'];

    // Checks to see which form was submitted.
    switch ( $atts['form_id'] ) {
      case 45: // Small Firm Scorecard
        $total = 500;
        break;
      case 46: // Solo Practice Scorecard
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
            <div class="score"><?php echo round( $score ); ?>/100</div>
          </div>
          <div id="get_results">
            <a class="button" href="#interpret_results">Interpret Your Results</a>
          </div>
          <div class="clear"></div>
        </div>

      <?php

      if ( $goals_score <= 15 ) {

      ?>

        <p class="alert">Regardless of your score, it looks like your goals need your attention. Before you do anything else, make sure you take the time to set goals and make sure you can achieve them at this firm.</p>

      <?php

      }

      echo '<div id="interpret_results"></div>'

    $scorecard_results = ob_get_clean();

    return $scorecard_results;

}
add_shortcode( 'get_grade', 'lawyerist_get_scorecard_grade' );
