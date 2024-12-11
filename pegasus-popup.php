<?php
/*
Plugin Name: Pegasus Popup Plugin
Plugin URI:  https://developer.wordpress.org/plugins/the-basics/
Description: This allows you to create a modal/pop-up on your website with just a shortcode.
Version:     1.0
Author:      Jim O'Brien
Author URI:  https://visionquestdevelopment.com/
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: wporg
Domain Path: /languages
*/

/**
 * Silence is golden; exit if accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function popup_check_main_theme_name() {
	$current_theme_slug = get_option('stylesheet'); // Slug of the current theme (child theme if used)
	$parent_theme_slug = get_option('template');    // Slug of the parent theme (if a child theme is used)

	//error_log( "current theme slug: " . $current_theme_slug );
	//error_log( "parent theme slug: " . $parent_theme_slug );

	if ( $current_theme_slug == 'pegasus' ) {
		return 'Pegasus';
	} elseif ( $current_theme_slug == 'pegasus-child' ) {
		return 'Pegasus Child';
	} else {
		return 'Not Pegasus';
	}
}

function pegasus_popup_menu_item() {
	if ( popup_check_main_theme_name() == 'Pegasus' || popup_check_main_theme_name() == 'Pegasus Child' ) {
		//do nothing
	} else {
		//echo 'This is NOT the Pegasus theme';
		add_menu_page(
			"Popup", // Page title
			"Popup", // Menu title
			"manage_options", // Capability
			"pegasus_popup_plugin_options", // Menu slug
			"pegasus_popup_plugin_settings_page", // Callback function
			null, // Icon
			89 // Position in menu
		);
	}
}
add_action("admin_menu", "pegasus_popup_menu_item");

function pegasus_popup_plugin_settings_page() { ?>
	<div class="wrap pegasus-wrap">
		<h1>Popup Usage</h1>

		<div>
			<h3>Popup Usage 1:</h3>
			<style>
				pre {
					background-color: #f9f9f9;
					border: 1px solid #aaa;
					page-break-inside: avoid;
					font-family: monospace;
					font-size: 15px;
					line-height: 1.6;
					margin-bottom: 1.6em;
					max-width: 100%;
					overflow: auto;
					padding: 1em 1.5em;
					display: block;
					word-wrap: break-word;
				}

				input[type="text"].code {
					width: 100%;
				}
			</style>
			<pre >[popup]<?php echo htmlspecialchars('<img src="//farm9.staticflickr.com/8241/8589392310_7b6127e243_b.jpg" >'); ?>

[/popup]</pre>

			<input
				type="text"
				readonly
				value="<?php echo esc_html('[popup] <img src="//farm9.staticflickr.com/8241/8589392310_7b6127e243_b.jpg" > [/popup]'); ?>"
				class="regular-text code"
				id="my-shortcode"
				onClick="this.select();"
			>
		</div>

		<p style="color:red;">MAKE SURE YOU DO NOT HAVE ANY RETURNS OR <?php echo htmlspecialchars('<br>'); ?>'s IN YOUR SHORTCODES, OTHERWISE IT WILL NOT WORK CORRECTLY</p>

	</div>
<?php
}

// function pegasus_popup_menu_item() {
//     //add_menu_page("Popup", "Popup", "manage_options", "pegasus_popup_plugin_options", "pegasus_popup_plugin_settings_page", null, 99);
// }
// add_action("admin_menu", "pegasus_popup_menu_item");

/*function pegasus_popup_plugin_settings_page() { ?>
    <div class="wrap pegasus-wrap">
    <h1>Popup</h1>
        <p>Section shortcode Usage: <pre>[popup] image content goes here [/popup]</pre></p>
        <p style="color:red;">MAKE SURE YOU DO NOT HAVE ANY RETURNS OR <?php echo htmlspecialchars('<br>'); ?>'s IN YOUR SHORTCODES, OTHERWISE IT WILL NOT WORK CORRECTLY</p>
    </div>
<?php }*/

function pegasus_popup_plugin_styles() {
    //wp_enqueue_style( 'masonry-css', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'css/masonry.css', array(), null, 'all' );
    wp_register_style( 'magnific-popup-css', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'css/magnific-popup.css', array(), null, 'all' );
}
add_action( 'wp_enqueue_scripts', 'pegasus_popup_plugin_styles' );

/**
* Proper way to enqueue JS
*/
function pegasus_popup_plugin_js() {
    //wp_enqueue_script( 'one-page-scroll-js', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'js/jquery.onepage-scroll.js', array( 'jquery' ), null, true );
    //wp_enqueue_script( 'snap-scroll-js', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'js/jquery.snapscroll.js', array( 'jquery' ), null, true );
    //wp_enqueue_script( 'scrollspy-js', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'js/scrollspy.js', array( 'jquery' ), null, true );
    //wp_enqueue_script( 'images-loaded-js', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'js/imagesLoaded.js', array( 'jquery' ), null, true );
    wp_register_script( 'magnific-popup-js', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'js/magnific-popup.js', array( 'jquery' ), null, 'all' );
    wp_register_script( 'pegasus-popup-plugin-js', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'js/plugin.js', array( 'jquery' ), null, 'all' );
} //end function
add_action( 'wp_enqueue_scripts', 'pegasus_popup_plugin_js' );

/*~~~~~~~~~~~~~~~~~~~~
    popup
~~~~~~~~~~~~~~~~~~~~~*/
// [popup ] content [/popup]
function pegasus_popup_func( $atts, $content = null ) {
    $a = shortcode_atts( array(
        'class' => '',
        'type' => '',
        'text' => '',
		'caption' => '',
    ), $atts );

    $output = '';
    global $pegasus_popup_counter;
    $unique_id = $pegasus_popup_counter ? $pegasus_popup_counter : 0;
    //$id_chk = "{$a['id']}";
    $class_chk = "{$a['class']}";
    $type = "{$a['type']}" ? "{$a['type']}" : 'image';
    $text = "{$a['text']}" ? "{$a['text']}" : 'Learn More';
	$caption = "{$a['caption']}" ? "{$a['caption']}" : '';

		switch ( $type ) {
			case 'inline':
				$output .= '<div id="pegasus-popup-' . $unique_id . '" class="white-popup mfp-hide' . $class_chk . '" >';
				$output .=   do_shortcode( $content );
				$output .= '</div>';

				$output .= '<a href="#pegasus-popup-' . $unique_id . '" class="btn popup" title="' . $caption . '" >' . $text . '</a>';

				$pegasus_popup_counter++;
				break;

			case 'gallery':
				$output .= '<div id="pegasus-popup-' . $unique_id . '" class="white-popup mfp-hide' . $class_chk . '" >';
				$output .=   do_shortcode( $content );
				$output .= '</div>';

				$output .= '<a href="#pegasus-popup-' . $unique_id . '" class="btn popup" title="' . $caption . '" >' . $text . '</a>';
				$pegasus_popup_counter++;
				break;

			default:
				$output .= '<div id="pegasus-popup-' . $unique_id . '" class="white-popup mfp-hide' . $class_chk . '" >';
				$output .=   do_shortcode( $content );
				$output .= '</div>';

				$output .= '<a href="#pegasus-popup-' . $unique_id . '" class="btn popup" title="' . $caption . '">' . $text . '</a>';
				$pegasus_popup_counter++;
				//$output .= '<div id="" class="popup" >';
				//$output .=   do_shortcode( $content );
				//$output .= '</div>';
				break;

		}


		wp_enqueue_style( 'magnific-popup-css' );
		wp_enqueue_script( 'magnific-popup-js' );
		wp_enqueue_script( 'pegasus-popup-plugin-js' );

		return $output;
	}
	add_shortcode( 'popup', 'pegasus_popup_func' );
