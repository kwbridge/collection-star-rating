<?php

function wp_star_myrating( $args = array() ) {
	$defaults = array(
		'rating' => 0,
		'type'   => 'rating',
		'number' => 0,
		'echo'   => false,
	);
	$r = wp_parse_args( $args, $defaults );

	// Non-english decimal places when the $rating is coming from a string
	$rating = str_replace( ',', '.', $r['rating'] );

	// Convert Percentage to star rating, 0..5 in .5 increments
	if ( 'percent' == $r['type'] ) {
		$rating = round( $rating / 10, 0 ) / 2;
	}

	// Calculate the number of each type of star needed
	$full_stars = floor( $rating );
	$half_stars = ceil( $rating - $full_stars );
	$empty_stars = 5 - $full_stars - $half_stars;

	if ( $r['number'] ) {
		/* translators: 1: The rating, 2: The number of ratings */
		$format = _n( '%1$s rating based on %2$s rating', '%1$s rating based on %2$s ratings', $r['number'] );
		$title = sprintf( $format, number_format_i18n( $rating, 1 ), number_format_i18n( $r['number'] ) );
	} else {
		/* translators: 1: The rating */
		$title = sprintf( __( '%s rating' ), number_format_i18n( $rating, 1 ) );
	}

	$output = '<span class="star-rating">';
	$output .= '<span class="screen-reader-text">' . $title . '</span>';
	$output .= str_repeat( '<div class="star star-full"></div>', $full_stars );
	$output .= str_repeat( '<div class="star star-half"></div>', $half_stars );
	$output .= str_repeat( '<div class="star star-empty"></div>', $empty_stars );
	$output .= '</span>';

	if ( $r['echo'] ) {
		echo $output;
	}

	return $output;
}

add_shortcode('star', 'wp_star_shortcode');
function wp_star_shortcode(){
$rate = get_post_meta( get_the_ID(), 'meta-text', true );
if ( $rate ) {
$args = array(
   'rating' => $rate,
   'type' => '',
   'number' => '',
);
$mytest = "<div class='entry-terms'><strong>My Rating: " . wp_star_myrating( $args ) . "</strong></div>" ;


	}
return $mytest ;
    }



function has_star_shortcode( $shortcode = NULL ) {

    $post_to_check = get_post( get_the_ID() );

    // false because we have to search through the post content first
    $found = false;

    // if no short code was provided, return false
    if ( ! $shortcode ) {
        return $found;
    }
    // check the post content for the short code
    if ( stripos( $post_to_check->post_content, '[' . $shortcode) !== FALSE ) {
        // we have found the short code
        $found = TRUE;
    }

    // return our final results
    return $found;


}

function star_enqueue_plugin_scripts($plugin_array)
{
    //enqueue TinyMCE plugin script with its ID.
    $plugin_array["star_button_plugin"] =  STAR_PLUGIN_URL . "includes/index.js";
    return $plugin_array;
}

add_filter("mce_external_plugins", "star_enqueue_plugin_scripts");

function star_register_buttons_editor($buttons)
{
    //register buttons with their id.
    array_push($buttons, "star");
    return $buttons;
}

add_filter("mce_buttons", "star_register_buttons_editor");
function star_shortcode_button_script()
{
    if(wp_script_is("quicktags"))
    {
        ?>
            <script type="text/javascript">
               
                //this function is used to retrieve the selected text from the text editor
				/*
                function getSel()
                {
                    var txtarea = document.getElementById("content");
                    var start = txtarea.selectionStart;
                    var finish = txtarea.selectionEnd;
                    return txtarea.value.substring(start, finish);
                }
*/
                QTags.addButton(
                    "star_shortcode",
                    "Star Rating",
                    callback
                );

                function callback()
                {
                    
                    QTags.insertContent("[star]");
                }
            </script>
        <?php
    }
}

add_action("admin_print_footer_scripts", "star_shortcode_button_script");