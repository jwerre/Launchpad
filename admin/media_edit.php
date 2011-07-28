<?php
	include '../lib/initialize.php';
	
	$media;
	
	if ( isset($_POST['media_url']) ){
		$media = array_pop( Media::find_by_sql("SELECT * FROM media WHERE filename ='".$_POST['media_url']."'") );
		$media->caption=stripslashes( trim($_POST["caption"]) );		
		$media->description=stripslashes( trim($_POST["description"]) );
		if( $media->save() ){
			$session->msg_type = 'success_msg';
			$session->message('"'.$media->caption.'" has been updated.');
			redirect_to('media.php');
		}else{
			$session->msg_type = ( !empty($media->errors) ) ? 'error_msg' : 'warning_msg';
			$msg = ( !empty($media->errors) ) ? implode("<br/>", $media->errors) : "Sorry, No changes were made.";
			$session->message( $msg );
		}
	}
	else if(isset($_GET['id']))
	{
		$media = array_pop( Media::find_by_sql("SELECT * FROM media WHERE id =".$_GET['id']) );
		if( empty($media) ){
			$session->msg_type = 'warning_msg';
			$msg = "Sorry, I couldn't find the image you were looking for.";
			$session->message( $msg );
		}
	}
	
	include_layout("header.php", "layouts");
?>
<h1 id="add_contnet">Upload Media</h1>
		 
<form id="add_media" action="" method="post">
	<fieldset class="right_side clearfix">
		<section class="button_area clearfix">
		<p><button name="save" value="save" type="submit" id="save_button">Save</button></p>
		<nav>
			<ul>
				<li><a href="" class="big_btn">Delete</a></li>
				<li><a href="media.php" class="big_btn right">Cancel</a></li>				
			</ul>
		</nav>
		</section>
	</fieldset>
	<fieldset class="left_side">
		<div id="media_info" class="section_box <?php if( empty($media) ){ echo 'hidden'; } ?>">
			<h3>Media Info <small class="right"><a href="media_edit.php">Upload another file</a></small></h3>
			<p><label for="media_url">URL <small style="color:#409AD4; float:right; font-weight:normal; text-decoration:underline">select</small></label><input type="text" name="media_url" value="<?php if( !empty($media) ){ echo $media->filename; }?>" id="media_url" style="color:#409AD4" readonly="readonly"></p>
			<p><label for="caption">Caption</label><input type="text" name="caption" id="caption" value="<?php if( !empty($media) ){ echo $media->caption; } ?>" /></p>
			<!-- <p class="half left"><label for="width">Width</label><input type="text" name="width" id="width"/></p> -->
			<!-- <p class="half right"><label for="height">Height</label><input type="text" name="height" id="height"/></p> -->
			<p><label for="description">Description</label><textarea name="description" id="description"/><?php if( !empty($media) ){ echo $media->description; } ?></textarea></p>
			<input type="hidden" name="media_id" value="<?php if( !empty($media) ){ echo $media->id; } ?>" id="media_id">
            <p id="image_placholder" class="">
                <?php
                if( !empty($media) ){
                    switch ( $media->simple_type() ) {
                        case 'image':
                            echo '<img src="'.$media->filename.'" title="'.$media->caption.'"/>';  
                            break;

                        case 'audio':
                            echo '<audio src="'.$media->filename.'" controls>Your borwser doesn\'t support html5 audio.</audio>';
                            break;

                        case 'video':
                            echo '<video src="'.$media->filename.'" poster="" controls> Your borwser doesn\'t support html5 video</video>';
                            break;

                        default:
                            break;
                    }
                }
                ?>
            </p>
		</div>
		<div id="media_upload" class="section_box <?php if( !empty($media) ){ echo 'hidden'; } ?>">
			<h3>Choose a file to upload <small>(max: <?php echo size_as_text(MAX_FILE_SIZE) ?>)</small></h3>
			<input type="hidden" name="MAX_FILE_SIZE" value=<?php echo MAX_FILE_SIZE ?> />
			<input type="hidden" name="author_id" value=<?php echo $session->user_id ?> />
			<p><input type="file" name="file_upload" id="file_upload" class="hidden"/></p>
			<!-- <p class="half hidden"><a href="javascript:$('#file_upload').uploadifyUpload();" class="big_btn" style="width:150px;">Upload Files</a></p> -->
		</div>
	</fieldset>
</form>


<?php
	include_layout("footer.php" ,"layouts");
?>
