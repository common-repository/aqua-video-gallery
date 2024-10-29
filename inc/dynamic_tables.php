<?php

function AQVG_video_gallery_table(){
	global $wpdb;

	$table_name = $wpdb->prefix . "video_gallery";
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE IF NOT EXISTS $table_name (
	  id mediumint(9) NOT NULL AUTO_INCREMENT,
	  time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	  user_id mediumint(9) NOT NULL,
	  video_title text NOT NULL,
	  video_url varchar(400) DEFAULT '' NOT NULL,
	  PRIMARY KEY  (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
}

?>