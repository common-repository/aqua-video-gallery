<?php
/*
* Plugin Name: Aqua Video Gallery
* Plugin URI: https://wpvideogallery.com
* Author: Nayan Jariwala
* Author URI: https://nayan.com
* Description: This plugin will help you to get Responsive & Lightweight Video gallery. No coding required. Add/Manage videos through a dedicated custom post classic interface, group them by categories, customize the front-end display using the shortcode builder as you need, provide the option for users to search videos.
* Version: 1.0.0
* License: GPL2
* License URI: https://www.gnu.org/licenses/gpl-2.0.html
* Text Domain: aquavideogallery
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if(!defined('WPINC')){
	die;
}

if(!defined('AQVG_PLUGIN_VERSION')){
	define('AQVG_PLUGIN_VERSION', '1.0.0');
}

if(!defined('AQVG_PLUGIN_DIR')){
	define('AQVG_PLUGIN_DIR', plugin_dir_url( __FILE__ ));
}

if(!function_exists('AQVG_plugin_scripts')){
	function AQVG_plugin_scripts(){
		wp_enqueue_style('AQVG_css', AQVG_PLUGIN_DIR. 'assets/css/style.css');
		//wp_enqueue_script('AQVG_js', AQVG_PLUGIN_DIR. 'assets/js/main.js', 'jQuery', '1.0.0', true);
	}
	add_action('wp_enqueue_scripts', 'AQVG_plugin_scripts');
}

// Admin Side Scripts and CSS
function AQVG_admin_scripts_styles($hook) {
        // Load only on ?page=mypluginname
        wp_enqueue_style('AQVG_css', AQVG_PLUGIN_DIR. 'assets/css/jquery.dataTables.min.css');
        wp_enqueue_style('AQVG_admin_css', AQVG_PLUGIN_DIR. 'assets/css/admin.css');
        wp_enqueue_script('AQVG_js', AQVG_PLUGIN_DIR. 'assets/js/jquery.dataTables.min.js', 'jQuery', '1.0.0', true);
        wp_enqueue_script('AQVG_main_js', AQVG_PLUGIN_DIR. 'assets/js/main.js','jQuery', '1.0.0', true);
        wp_enqueue_script('AQVG_validate', AQVG_PLUGIN_DIR. 'assets/js/jQuery.validate.min.js','jQuery', '1.0.0', true);
}
add_action( 'admin_enqueue_scripts', 'AQVG_admin_scripts_styles' );

 
// Client Side Scripts and CSS
function AQVG_client_scripts_styles(){
	wp_register_style( 'AQVG_client_style_css',  AQVG_PLUGIN_DIR. 'assets/css/AQVG.css');
	wp_register_script('AQVG_client_js', AQVG_PLUGIN_DIR. 'assets/js/AQVG.js');

}
add_action( 'wp_enqueue_scripts', 'AQVG_client_scripts_styles' );


// Settings Page Html
require plugin_dir_path( __FILE__ ). 'inc/settings.php';

// AQVG Tables
require plugin_dir_path( __FILE__ ). 'inc/dynamic_tables.php';

// Hooks 
register_activation_hook( __FILE__, 'AQVG_video_gallery_table' );

// Shortcode
// Shortcode for Normal Video
function AQVG_FrontendVideos( $atts ) {

 	wp_enqueue_style( 'AQVG_client_style_css' );
 	wp_enqueue_script( 'AQVG_client_js' );

  	$a = shortcode_atts( array('class' => '','subclass' => '', 'id' => 'AQVG_default', 'video_count' => '10', 'style' => 'grid', 'columns' => '3'), $atts );
    
    global $wpdb;
    $table_name = $wpdb->prefix . "video_gallery";
    $vids = $wpdb->get_results( "SELECT `video_title`,`video_url` FROM $table_name" );

    $getting_style = strtolower(preg_replace("/[^a-zA-Z]/", "", $a['style']));
    $getting_column = preg_replace('/[^0-9]/','',$a['columns']);
    $getting_videocount = preg_replace('/[^0-9]/','',$a['video_count']);
    $getting_id = preg_replace("/[^a-zA-Z]/", "", $a['id']);
	$total_videos = 0;
    $output = '';

    if($getting_column >= 6){
    	return;
    }

    if($getting_style == 'grid' || $getting_style == 'masonry' || $getting_style == 'flat'){

	  $output .= '<div class="'.$a['class'].' '.$getting_style.' AQVG-'.$getting_column.'" id="'.$getting_id.'">';
	    foreach ($vids as $vid) {
	    	
	    	if($total_videos == $getting_videocount){
	    		break;	
	    	}
	    	if( Aqua_CheckYoutube($vid->video_url) == false){
	    		$total_videos ++;
				$output .= '<div class="'.$a['subclass'].' custom_vid">';
		    	$output .= '<video controls controlsList="nodownload">';
		    		$output .= '<source src="'.$vid->video_url.'">';
		    	$output .= '</video>';
		    	$output .= '<div class="AQVG_title"><h3>'.$vid->video_title.'</h3></div>';		
		   		$output .= '</div>';	
	    	}
	    }
      $output .= '</div>';
    }else{
    	return;
    }

	return $output;
}

add_shortcode('aqvg', 'AQVG_FrontendVideos');

function Aqua_CheckYoutube($url)
{
    return preg_match("/^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/", $url);
}

function Aqua_YoutubeID($url) {
   $pattern =
    '%^# Match any youtube URL
    (?:https?://)?  # Optional scheme. Either http or https
    (?:www\.)?      # Optional www subdomain
    (?:             # Group host alternatives
      youtu\.be/    # Either youtu.be,
    | youtube\.com  # or youtube.com
      (?:           # Group path alternatives
        /embed/     # Either /embed/
      | /v/         # or /v/
      | .*v=        # or /watch\?v=
      )             # End path alternatives.
    )               # End host alternatives.
    ([\w-]{10,12})  # Allow 10-12 for 11 char youtube id.
    ($|&).*         # if additional parameters are also in query string after video id.
    $%x'
    ;
    $result = preg_match($pattern, $url, $matches);
    if (false !== $result) {
      return $matches[1];
    }
    return false;
 }



function AQVG_youtube_frontend_videos( $atts ) {

	wp_enqueue_style( 'AQVG_client_style_css' );
	wp_enqueue_script( 'AQVG_client_js' );

    $a = shortcode_atts( array('class' => '','subclass' => '', 'id' => 'AQVG_default', 'video_count' => '10', 'style' => 'grid', 'columns' => '3'), $atts );
	global $wpdb;
    $table_name = $wpdb->prefix . "video_gallery";
    $vids = $wpdb->get_results( "SELECT `video_title`,`video_url` FROM $table_name" );
    
    $getting_style = strtolower(preg_replace("/[^a-zA-Z]/", "", $a['style']));
    $getting_column = preg_replace('/[^0-9]/','',$a['columns']);
    $getting_videocount1 = preg_replace('/[^0-9]/','',$a['video_count']);
    $getting_id = preg_replace("/[^a-zA-Z]/", "", $a['id']);
	$total_videos1 = 0;
    $output = '';

    if($getting_column >= 6){
    	return;
    }

    if($getting_style == 'grid' || $getting_style == 'masonry' || $getting_style == 'flat'){

    	$output .= '<div class="'.$a['class'].' '.$getting_style.' AQVG-'.$getting_column.'" id="'.$getting_id.'">';
	    foreach ($vids as $vid) {
	    	if($total_videos1 == $getting_videocount1){
	    		break;	
	    	}
	    	if( Aqua_CheckYoutube($vid->video_url) == true){
	    		$total_videos1 ++;	
				$getyoutube_vid = Aqua_YoutubeID($vid->video_url);
	    		$output .= '<div class="'.$a['subclass'].' youtube_vid">';
	    			$output .= '<iframe class="youtube_embedded" width="100%" height="100%" src="https://www.youtube.com/embed/'.$getyoutube_vid.'?modestbranding=1" frameborder="0" allowfullscreen></iframe>';
    			$output .= '<div class="AQVG_title"><h3>'.$vid->video_title.'</h3></div>';		
	   			$output .= '</div>';	
	    	}
	    }
    }
    $output .= '</div>';
    return $output;
}
add_shortcode( 'aqvg_youtube', 'AQVG_youtube_frontend_videos' );