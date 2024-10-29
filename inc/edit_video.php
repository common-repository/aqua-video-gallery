<?php

if(isset($_REQUEST['submit'])){

    global $wpdb;
    $tablename = $wpdb->prefix.'video_gallery';
	$v_id = $_REQUEST['videoid'];
    $nonce = $_REQUEST['nonce'];
    if( current_user_can('editor') || current_user_can('administrator') || current_user_can('author')){
    	if( ! wp_verify_nonce( $nonce, 'my-nonce' )){
    		echo '<div class="error notice"><p>You are not allowed to edit Videos</p></div>';
    	 	die(); 
	    }else{

			$q_result = $wpdb->prepare( "UPDATE `$tablename` SET `video_title` = %s, `video_url` = %s WHERE `id` = %d", array( sanitize_text_field($_REQUEST['AQVG_video_title']), esc_url_raw($_REQUEST['AQVG_video_url']), $v_id));
	    	
	    	$wpdb->query($q_result);

		    if($q_result == false){
		    	echo '<div class="error notice"><p>There was a problem editing your video</p></div>';	
		    }else{
		    	echo '<div class="updated notice"><p>Video Update Successfully</p></div>';
		    }
			$wpdb->flush();
	    }
	}else{
		echo '<div class="error notice"><p>You dont have right or permission to edit video</p></div>';
    	die(); 
	}
}

 if(isset($_REQUEST['videoid'])){ 
		if(is_numeric($_REQUEST['videoid'])){ 
			global $wpdb;
			$tablename = $wpdb->prefix.'video_gallery';
			$v_id = $_REQUEST['videoid'];
			$results = $wpdb->get_results( "SELECT * FROM $tablename WHERE id = $v_id");
			foreach ($results as $result) {
				$video_title = $result->video_title;
				$video_url = $result->video_url;
			}
?>
<H1>Edit Video</H1>
	<div class="wrap">
		<form id="editingvideo" action="?page=editvideo&videoid=<?php echo $v_id; ?>" method=post>

			<table class="form-table">
				<tbody>
					<tr>
						<?php $nonce = wp_create_nonce( 'my-nonce' ); ?>
						<input type="hidden" name="nonce" value="<?php echo $nonce;?>">
						<input type="hidden" name="AQVG_video_added_user" value="<?php echo get_current_user_id();?>">
						<th scope="row">Video Title</th>
						<td><input type="text" name="AQVG_video_title" value="<?php echo $video_title; ?>"></td>
					</tr>
					<tr>
						<th scope="row">Video URL</th>
						<td><input type="text" name="AQVG_video_url" value="<?php echo $video_url; ?>"></td>
					</tr>
				</tbody>
			</table>
			<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Update Video"></p>
		</form>
	</div>
<?php 
 }else{
	echo '<script>window.location.replace("'.get_site_url().'/wp-admin/admin.php?page=AQVG");</script>';
	echo "no";
}}else{
	echo '<script>window.location.replace("'.get_site_url().'/wp-admin/admin.php?page=AQVG");</script>';
	echo "no";
} 
?>