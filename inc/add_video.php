<?php

if ( isset( $_POST['submit'] ) ){

    global $wpdb;
    $tablename = $wpdb->prefix.'video_gallery';
    $nonce = $_REQUEST['nonce'];
    if( current_user_can('editor') || current_user_can('administrator') || current_user_can('author')){
    	if( ! wp_verify_nonce( $nonce, 'my-nonce' )){
    		echo '<div class="error notice"><p>You are not allowed to Upload Videos</p></div>';
    	 	die(); 
	    }else{
	    	
	    	$q_result = $wpdb->prepare( "INSERT INTO `$tablename` (`user_id`, `video_title`, `video_url`) VALUES (%d, %s, %s)", array( $_REQUEST['AQVG_video_added_user'], sanitize_text_field($_REQUEST['AQVG_video_title']), esc_url_raw($_REQUEST['AQVG_video_url'])));
	    	
	    	$wpdb->query($q_result);

		    if($q_result == false){
		    	echo '<div class="error notice"><p>There was a problem adding your video</p></div>';	
		    }else{
		    	echo '<div class="updated notice"><p>Video Added Successfully</p></div>';
		    }
		    
		    $wpdb->flush();
	    }
    }else{
    	echo '<div class="error notice"><p>You dont have right or permission to upload video</p></div>';
    	die(); 
    }
}
?>
<H1>Add New Video</H1>
<div class="wrap">
	<form id="addingvideo" action="?page=addvideo" method=post>

		<table class="form-table">
			<tbody>
				<tr>
					<?php $nonce = wp_create_nonce( 'my-nonce' ); ?>
					<input type="hidden" name="nonce" value="<?php echo $nonce;?>">
					<input type="hidden" name="AQVG_video_added_user" value="<?php echo get_current_user_id();?>">
					<th scope="row">Video Title</th>
					<td><input type="text" name="AQVG_video_title"></td>
				</tr>
				<tr>
					<th scope="row">Video URL</th>
					<td><input type="text" name="AQVG_video_url"></td>
				</tr>
			</tbody>
		</table>
		<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Add Video"></p>
	</form>
</div>
