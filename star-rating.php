<?php
/**
* Plugin Name: Book Collection Star Rating
* Plugin URI: http://kimwoodbridge.com
* Description: Simple internal rating plugin for your own collection rating.  Uses WordPress star rating function
* Version: 1.0
* Author: Kim Woodbridge
* Author URI: http://kimwoodbridge.com
**/

if ( ! defined( 'STAR_PLUGIN_DIR' ) ) {
	define( 'STAR_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

}

define( 'STAR_PLUGIN_DIR', dirname( __FILE__ ) );

if ( ! defined( 'STAR_PLUGIN_URL' ) ) {
	define( 'STAR_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

define( 'STAR_PLUGIN_FILE', STAR_ROOT_PATH . 'star-rating.php' );

if ( !defined( 'ABSPATH' ) ) {
    wp_die( __( "Sorry, you are not allowed to access this page directly.", 'star' ) );
}


require_once STAR_PLUGIN_DIR . 'includes/functions.php';




add_action( 'wp_enqueue_scripts', 'STAR_register_styles', 15 );
function STAR_register_styles() {
	wp_register_style( 'STAR-styles', STAR_PLUGIN_URL .  'includes/style.css' ) ;
	wp_enqueue_style( 'STAR-styles' );
}

function star_custom_meta() {
    add_meta_box( 'star_meta', __( 'Rating', 'star-textdomain' ), 'star_meta_callback', 'post' );
}
add_action( 'add_meta_boxes', 'star_custom_meta' );

function star_meta_callback( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'star_nonce' );
    $star_stored_meta = get_post_meta( $post->ID );
    ?>
 
    <p>
        <label for="meta-text" class="star-row-title"><?php _e( 'My Rating', 'star-textdomain' )?></label>
        <input type="text" style="width:50px !important;" name="meta-text" id="meta-text" value="<?php if ( isset ( $star_stored_meta['meta-text'] ) ) echo $star_stored_meta['meta-text'][0]; ?>" />
    </p>
 
    <?php
}

/**
 * Saves the custom meta input
 */
function star_meta_save( $post_id ) {
 
    // Checks save status
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST[ 'star_nonce' ] ) && wp_verify_nonce( $_POST[ 'star_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
 
    // Exits script depending on save status
    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
        return;
    }
 
    // Checks for input and sanitizes/saves if needed
    if( isset( $_POST[ 'meta-text' ] ) ) {
        update_post_meta( $post_id, 'meta-text', sanitize_text_field( $_POST[ 'meta-text' ] ) );
    }
 
}
add_action( 'save_post', 'star_meta_save' );

