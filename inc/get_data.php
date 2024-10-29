<?php
	global $wpdb;
	
	$table_name = $wpdb->prefix . "video_gallery";
	$user = $wpdb->get_results( "SELECT * FROM $table_name" );
   ?>
   <h1>Aqua Video Gallery</h1>
<table id="all_video" class="display" style="margin-top: 50px; width: 100%;">
       <thead>
            <tr>
                <th>Video Title</th>
                <th>Video URL</th>
                <th>Edit</th>
                <th>Delete</th>
                <th>Date </th>
            </tr>
        </thead>
   <tbody id="the-list">
   <?php
   foreach ($user as $users) { ?>
   <tr>
       <td><?php echo $users->video_title; ?></td>
       <td><?php echo $users->video_url; ?></td>
       <td class="align-center"><a href="<?php echo get_site_url().'/wp-admin/admin.php?page=editvideo&videoid='.$users->id.'';?>"><span class="dashicons dashicons-edit"></span></a></td>
       <td class="align-center"><?php $nonce = wp_create_nonce( 'my-nonce' ); ?><a href="<?php echo get_site_url().'/wp-admin/admin.php?page=deletevideo&videoid='.$users->id.'&nonce='.$nonce.'';?>"><span class="dashicons dashicons-trash"></span></a></td>
       <td><?php echo date('d/m/Y h:i A', strtotime($users->time));?></td>
   </tr>
   <?php }?>
   </tbody>
      <tfoot>
         <tr>
             <th>Video Title</th>
             <th>Video URL</th>
             <th>Edit</th>
             <th>Delete</th>
             <th>Date </th>
         </tr>
      </tfoot>
   </table>
