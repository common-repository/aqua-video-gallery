<?php


function AQVG_option_page_html(){
	if(!is_admin()){
		return;
	}
	require(plugin_dir_path( __FILE__ ). '/get_data.php');
}


function AQVG_add_video_html(){
	if(!is_admin()){
		return;
	}
	require(plugin_dir_path( __FILE__ ). '/add_video.php');
}


function AQVG_edit_video_html(){
	if(!is_admin()){
		return;
	}
	require(plugin_dir_path( __FILE__ ). '/edit_video.php');
}

function AQVG_delete_video_html(){
	if(!is_admin()){
		return;
	}
	require(plugin_dir_path( __FILE__ ). '/delete_video.php');
}

function AQVG_settings_html(){
	if(!is_admin()){
		return;
	}
	require(plugin_dir_path( __FILE__ ). '/gallery_style.php');
}


function AQVG_register_menu_page(){
	add_menu_page('Aqua Video Gallery', 'Aqua Video Gallery', 'manage_options', 'AQVG', 'AQVG_option_page_html', 'dashicons-images-alt2', 20);

	add_submenu_page( 'AQVG', 'Add New Video', 'Add New Video', 'manage_options', 'addvideo', 'AQVG_add_video_html' );

	add_submenu_page( '', '', '', 'manage_options', 'editvideo', 'AQVG_edit_video_html' );

	add_submenu_page( '', '', '', 'manage_options', 'deletevideo', 'AQVG_delete_video_html' );

}
add_action('admin_menu', 'AQVG_register_menu_page');