<?php include '../lib/initialize.php';
	
	$type = 'image';
	if( isset( $_GET['type']) ){
		$type = $_GET['type'];
		$cookie->media_type = $type;
	}
	elseif( isset($cookie->media_type) ){
		$type = $cookie->media_type;
	}

    $limit = ITEMS_PER_PAGE;
	$cookie_name = $type.'_limit';
	if( isset( $_GET['limit']) ){
		$limit = $_GET['limit'];
		$cookie->$cookie_name = $limit;
	}
	elseif( isset($cookie->$cookie_name) ){
		$limit = $cookie->$cookie_name;
	}

	$group = 1;
	$cookie_name = $type.'_group';
	if( isset( $_GET['group']) ){
		$group = $_GET['group'];
		$cookie->$cookie_name = $group;
	}
	elseif( isset($cookie->$cookie_name) ){
		$group = $cookie->$cookie_name;
	}
	
	$where_clause;
	
	switch ($type) {
		case 'image': 
			$where_clause = "WHERE type LIKE 'image%'"; 
			break;
		case 'audio': 
			$where_clause = "WHERE type LIKE 'audio%'";
			break;
		case 'video':
			$where_clause = "WHERE type LIKE 'video%'";
			break;
		case 'pdf': 
			$where_clause = "WHERE type LIKE '%pdf'"; 
			break;
		default: $where_clause = "WHERE type NOT LIKE 'image%' AND type NOT LIKE 'audio%' AND type NOT LIKE 'video%' AND type NOT LIKE '%pdf'"; break;
	}
	
	$total = Media::count_all($where_clause);
	$pagination = new Pagination($group, $limit, $total);
	$offset = $pagination->offset();
	
	$media_files = Media::find_by_sql("SELECT * FROM media ".$where_clause." ORDER BY 'id' DESC LIMIT $offset, $limit");
	include_layout("header.php", "layouts");
?>
<h1>Media</h1>
<div id="tab_container" class="media">
	<nav class="tabs">
	<ul>
		<li <?php if($type == "image") echo 'class="on"' ?>><a href="?type=image">Images</a></li>
		<li <?php if($type == "pdf") echo 'class="on"' ?>><a href="?type=pdf">PDFs</a></li>
		<li <?php if($type == "audio") echo 'class="on"' ?>><a href="?type=audio">Audio</a></li>
		<li <?php if($type == "video") echo 'class="on"' ?>><a href="?type=video">Video</a></li>
		<li <?php if($type == "other") echo 'class="on"' ?>><a href="?type=other">Other</a></li>
		<li class="right"><a href="media_edit.php" class="add_button upload_icn">Upload New Media</a></li>
	</ul>
	</nav>
	<div id="<?php echo $type; ?>">
		<ul id="" class="media_list clearfix" >
		<?php if( !empty($media_files) ): ?>
			<?php foreach ($media_files as $media): ?>
			<li class="<?php echo $media->id; ?>">
				<figure class="media_container">
					<?php if($type == 'image'): ?>
					<img src="imageprocessor.php?src=<?php echo urlencode($media->filename); ?>&w=128&h=128&mode=fit"/>
					<?php else: ?>
					<img src="images/icn_<?php echo $type ?>_big.png"/>
					<?php endif; ?>	
				</figure>
				<figcaption><?php echo $media->caption; ?></figcaption>
				<nav class="small_btn">
					<ul>
						<li><a href="media_edit.php?id=<?php echo $media->id; ?>">edit</a></li>
						<li><a href="" id="" class="img_delete">delete</a></li>
						<?php if($type == 'image'): ?>
                        <li><a href="<?php echo $media->filename; ?>" title="<?php echo $media->caption; ?>"rel="prettyPhoto[<?php echo $type ?>]">view</a></li>
						<?php endif; ?>
						<li><a href="<?php echo $media->filename; ?>" class="embed_btn">embed</a></li>
					</ul>				
				</nav>
			</li>
			<?php endforeach; ?>
		<?php else: ?>
            <div id="message" class='info_msg'>
                <p>there are no <?php echo $type ?> files.</p>			
            </div>
		<?php endif; ?>
		</ul>
	</div>
	<div id="tab_navigation" class="clearfix">
		<nav class="limit">
			<ul>
				<li class="limit_low" ><a title="Show 10 times per page" href="<?php echo append_query_string( array( 'group'=> '1', 'limit'=>'10') );?>" <?php if($limit == 10) echo ' class="on"'?> >10</a></li>
				<li class="limit_mid" ><a title="Show 50 times per page" href="<?php echo append_query_string( array( 'group'=> '1', 'limit'=>'50') );?>" <?php if($limit == 50) echo ' class="on"'?> >50</a></li>
				<li class="limit_high"><a title="Show 100 times per page" href="<?php echo append_query_string( array('group'=> '1', 'limit'=>'100') );?>" <?php if($limit == 100) echo 'class="on"'?> >100</a></li>
			</ul>
		</nav>
		<nav class="pagination right">
		<?php $pagination->output_controls(); ?>
		</nav>
	</div>
</div>

<div id="delete_img_dialog" class="ui-helper-hidden" title="Are you sure you want to delete this image"><p><strong>This cannot be undone</strong> and will remove the image from your site.</p></div>
<div id="embed_img_dialog" class="ui-helper-hidden" title="Copy this to your clipboard">
	<p>
	<fieldset id="" class="">
		<input type="text" name="" value="" id="embed" readonly="readonly">
	</fieldset>
	</p>
</div>
<?php
	include_layout("footer.php" ,"layouts");
?>

