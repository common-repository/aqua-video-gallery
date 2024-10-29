<?php
   if(isset($_REQUEST['videoid'])){ 
		if(is_numeric($_REQUEST['videoid'])){ 
		    global $wpdb;

		    $tablename = $wpdb->prefix.'video_gallery'; 
		    $nonce = $_REQUEST['nonce'];   
		    if( current_user_can('editor') || current_user_can('administrator') || current_user_can('author')){
		    	if( ! wp_verify_nonce( $nonce, 'my-nonce' )){
		    		echo '<div class="error notice"><p>You are not allowed to Delete Videos</p></div>';
    	 			die();
		    	}else{
		    		$q_result = $wpdb->prepare( "DELETE FROM `$tablename` WHERE `id` = %d", array($_REQUEST['videoid']));
	    			$wpdb->query($q_result);
		    		if($q_result == false){
				    	echo '<div class="error notice"><p>There was a problem deleting your video</p></div>';	
				    }else{
				    	echo '<div class="updated notice"><p>Video Delete Successfully</p></div>';
				    	echo '<script>window.location.replace("'.get_site_url().'/wp-admin/admin.php?page=AQVG");</script>';
				    }
					$wpdb->flush();
		    	}
		    }else{
				echo '<div class="error notice"><p>You dont have right or permission to delete video</p></div>';
		    	die(); 
		    }
		}
	}
?>