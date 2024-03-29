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

	function pegasus_popup_menu_item() {
		//add_menu_page("Popup", "Popup", "manage_options", "pegasus_popup_plugin_options", "pegasus_popup_plugin_settings_page", null, 99);
		
	}
	add_action("admin_menu", "pegasus_popup_menu_item");

	function pegasus_popup_plugin_settings_page() { ?>
	    <div class="wrap pegasus-wrap">
	    <h1>Popup</h1>			
			<p>Section shortcode Usage: <pre>[popup] image content goes here [/popup]</pre></p>	
			<p style="color:red;">MAKE SURE YOU DO NOT HAVE ANY RETURNS OR <?php echo htmlspecialchars('<br>'); ?>'s IN YOUR SHORTCODES, OTHERWISE IT WILL NOT WORK CORRECTLY</p>
		</div>
	<?php
	}

	
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
			'text' => ''
		), $atts );
	
		$output = '';
		global $pegasus_popup_counter;
		$unique_id = $pegasus_popup_counter ? $pegasus_popup_counter : 0;
		//$id_chk = "{$a['id']}";
		$class_chk = "{$a['class']}";
		$type = "{$a['type']}" ? "{$a['type']}" : 'image';
		$text = "{$a['text']}" ? "{$a['text']}" : 'Learn More';
		
		switch ( $type ) {
			case 'inline':
				$output .= '<div id="pegasus-popup-' . $unique_id . '" class="white-popup mfp-hide' . $class_chk . '" >';
				$output .=   do_shortcode( $content );
				$output .= '</div>';
			
				$output .= '<a href="#pegasus-popup-' . $unique_id . '" class="btn popup">' . $text . '</a>';
				$pegasus_popup_counter++;
			case 'gallery':
				//$output .= '<div id="pegasus-popup-' . $unique_id . '" class="white-popup mfp-hide' . $class_chk . '" >';
				//$output .=   do_shortcode( $content );
				//$output .= '</div>';
			
				//$output .= '<a href="#pegasus-popup-' . $unique_id . '" class="btn popup">' . $text . '</a>';
				break;
			default:
				$output .= '<div id="pegasus-popup-' . $unique_id . '" class="white-popup mfp-hide' . $class_chk . '" >';
				$output .=   do_shortcode( $content );
				$output .= '</div>';
			
				$output .= '<a href="#pegasus-popup-' . $unique_id . '" class="btn popup">' . $text . '</a>';
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
	