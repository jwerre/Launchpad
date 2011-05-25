<?php
	include '../lib/initialize.php';
	
	$type = !isset( $_GET['type'] ) ? ContentType::PAGE : $_GET['type'];
	$start = isset( $_GET['start'] ) ? $_GET['start'] : 0;
    $limit = isset( $_GET['limit'] ) ? $_GET['limit'] : ITEMS_PER_PAGE;
	$group = isset( $_GET['group'] ) ? $_GET['group'] : 1;
	
	$total = Content::count_all($type);
	$pagination = new Pagination($group, $limit, $total);
	$offset = $pagination->offset();

	$sql = "SELECT * FROM content WHERE type = '$type' ORDER BY 'weight', 'updated' DESC LIMIT $offset, $limit";
	$content = Content::find_by_sql($sql);
	include_layout("header.php", "layouts");
?>
<h1>Content</h1>
<div id="tab_container">
	<nav class="tabs">
	<ul>
		<li <?php if( $type == ContentType::PAGE ) echo 'class="on"' ?> ><a href="?type=page">Pages</a></li>
		<li <?php if( $type == ContentType::POST ) echo 'class="on"' ?> ><a href="?type=post">Posts</a></li>
		<li class="right"><a href="content_edit.php?type=<?php echo $type ?>" class="add_button page_icn">Create New <?php echo ucwords($type) ?></a></li>
	</ul>
	</nav>
	<div id="<?php echo $type; ?>">
		<ul id="" class="content_list" >
        <?php if( !empty($content) ): ?>
			<?php foreach ($content as $item) : ?>
			<li id="id_<?php echo $item->id;?>&weight_<?php echo $item->weight;?>" >
				<?php echo $item->title ?>
				<nav class="small_btn right">
					<ul>
						<li><a href="content_edit.php?id=<?php echo $item->id ?>">edit</a></li>
						<li><a href="ajax/content_delete.php?id=<?php echo $item->id ?>" class="content_list_delete">delete</a></li>
						<li><a href="<?php echo BASE_URL."/index.php?".$type."=".$item->id ?>" target="_blank">view</a></li>
					</ul>				
				</nav>
			</li>
			<?php endforeach; ?>
		<?php else: ?>
            <div id="message" class='info_msg'>
                <p>There are no <?php echo $type ?>s. To create one <a href="content_edit.php?type=<?php echo $type ?>">click here</a>.</p>			
            </div>
		<?php endif; ?>
        </ul>
	</div>
	<div id="tab_navigation" class="clearfix">
		<nav class="limit">
			<ul>
				<li class="limit_low" ><a title="Show 10 times per page" href="<?php echo append_query_string( array( 'group'=> '0', 'limit'=>'10') );?>" <?php if($limit == 10) echo ' class="on"'?> >10</a></li>
				<li class="limit_mid" ><a title="Show 50 times per page" href="<?php echo append_query_string( array( 'group'=> '0', 'limit'=>'50') );?>" <?php if($limit == 50) echo ' class="on"'?> >50</a></li>
				<li class="limit_high"><a title="Show 100 times per page" href="<?php echo append_query_string( array('group'=> '0', 'limit'=>'100') );?>" <?php if($limit == 100) echo 'class="on"'?> >100</a></li>
			</ul>
		</nav>
		<nav class="pagination right">
		<?php $pagination->output_controls(); ?>
		</nav>
	</div>
</div>

<div id="delete_content_dialog" class="ui-helper-hidden" title="Are you sure you want to delete this <?php echo $type ?>"><p><strong>This cannot be undone</strong> and will remove the <?php echo $type ?> from your site.</p></div>

<?php
	include_layout("footer.php" ,"layouts");
?>

