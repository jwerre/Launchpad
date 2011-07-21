<?php
	include '../lib/initialize.php';
	if( isset($session->role) && $session->role > UserRole::CONTRIBUTOR ){
		$session->msg_type = 'info_msg';
		$session->message('You do not have sufficient privileges to access this page');
		redirect_to("index.php");		
	}	

	$user = User::find_by_id($session->user_id);
	
    $content = new Content();
	$content->id = isset( $_GET['id'] ) ? $_GET['id'] : NULL;
    $content->type = isset( $_GET['type'] ) ? $_GET['type'] : ContentType::PAGE;	

	if (isset($_POST['save_button']) && !empty($_POST['content_title'])){
		// echo '<pre>' . print_r($_POST, true) . '</pre>';

		$hours = empty($_POST['created_hour']) ? date("H") : $_POST['created_hour'];
		$minutes = empty($_POST['created_minutes']) ? date("i") : $_POST['created_minutes'];
		$seconds = empty($_POST['created_seconds']) ? date("s") : $_POST['created_seconds'];

		$content->id = ( !empty($_POST['content_id']) ) ? $_POST['content_id'] : NULL;
		$content->author_id = ( !empty($_POST['author_id']) ) ? $_POST['author_id'] : $user->id;
		$content->type = ( !empty($_POST['content_type']) ) ? $_POST['content_type'] : $content->type;
		$content->status = ( !empty($_POST['status']) ) ? $_POST['status'] : ContentStatus::PUBLISHED;
		$content->updated = date( "Y-m-d H:i:s");
		$content->created = (isset($_POST['created_date']) && !empty($_POST['created_date'])) ? date("Y-m-d", strtotime($_POST['created_date'])).' '.$hours.':'.$minutes.':'.$seconds : date( "Y-m-d H:i:s");
		$content->title = ( !empty($_POST['content_title']) ) ? $_POST['content_title'] : "";
		$content->body = ( !empty($_POST['content_body']) ) ? stripslashes($_POST['content_body']) : NULL;
		$content->weight = ( !empty($_POST['weight']) ) ? $_POST['weight'] : 0;
		$content->slug = ( !empty($_POST['slug']) ) ? slug($_POST['slug']) : slug($_POST['content_title']);
		$content->parent_id = ( !empty($_POST['parent_id']) ) ? $_POST['parent_id'] : NULL;
		$content->category_id = ( !empty($_POST['category']) ) ? $_POST['category'] : NULL;
		$content->template = ( !empty($_POST['template']) ) ? $_POST['template'] : NULL;
		
		// echo '<pre>' . print_r($content, true) . '</pre>'; exit;
		if($content->save()){
			if( defined('REWRITE_MAP') ){
				$rewriter = new URLRewrite($content);
				$rewriter->add_rule();
			}
			$session->msg_type = 'success_msg';		
			$session->message('The '.$content->type.' "'.$content->title.'" was saved');
			redirect_to('content_edit.php?type='.$content->type.'&id='.$content->id);
			
		}else{
			$session->message('There was a problem saving the '.$content->type);
		}

	}elseif( isset($_POST['save_button']) && empty($_POST['content_title']) ){
		$session->msg_type = 'error_msg';		
		$session->message('You must provide a title for this '. $content->type);
	}
	
	if( !empty($content->id) )
	{
		$content = Content::find_by_id($content->id);
		if(empty($content)){
			$session->msg_type = 'warning_msg';		
			$session->message('Sorry, that doesn\'t exist');
			redirect_to('content.php');
		}
		$tags = $content->tags();
		$snippets = $content->snippets_complex();
		$headline = 'Edit '.ucwords($content->type);
	}else{
        $headline = 'Create New '.ucwords($content->type); 
    } 
    
	$exclude = ( !empty($content->id) ) ? array($content->id) : NULL;
	$parent_pages = Page::find_all_by_type('title', NULL, $exclude );
	$types = ContentType::get_types();
	$status_types = ContentStatus::get_types();
	$content_templates = Page::get_templates();	
	$categories = Category::find_all();
	include_layout("header.php", "layouts");
?>

<h1 id="add_contnet"><?php echo $headline ?></h1>
<form id="add_content" action="" id="settings" method="post" onsubmit="editor.post()" accept-charset="utf-8">
	<fieldset class="right_side clearfix">
		<section class="button_area clearfix">
		<p><button type="submit" class="save" id="save_button" name="save_button">Save</button></p>
		<nav <?php if(empty($content->id)) echo 'class="hidden"' ?> >
			<ul>
                <li><a href="ajax/content_delete.php?id=<?php echo $content->id ?>" id="content_delete" class="big_btn">Delete</a></li>
                <li><a href="<?php echo BASE_URL."/index.php?".$content->type."=".$content->id ?>" target="_blank" class="big_btn right">View</a></li>				
			</ul>
		</nav>
		</section>
		<div class="section_box">
			<h3>Options</h3>
			<p>
				<label for="status">Status</label>
				<select name="status" size="1" id="status" value="">
				<?php foreach( $status_types as $status): ?>
					<option value="<?php echo $status; ?>" <?php if(isset($content->status) && $content->status == $status){ echo 'selected="selected"';} ?>> <?php echo $status; ?></option>
				<?php endforeach?>
				</select>
			</p>
			<?php if($content->type == ContentType::PAGE): ?>
			<p>
				<label for="parent_id">Parent</label>
				<select name="parent_id" size="1" id="parent_id" value="">
					<option value="0"> none </option>
					<?php 
						foreach( $parent_pages as $parent){ 
							$option = '<option value="' .$parent->id.'"';
							if(isset($content->parent_id) && $content->parent_id == $parent->id){ 
								$option.= 'selected="selected"';
							}
							$option .='>'. $parent->title . '</option>';
							echo $option;
						}
					?>
				</select>
			</p>
			<p>
				<label for="template">Template</label>
				<select name="template" size="1" id="template" value="">
				<option value="0"> none </option>
				<?php for ($i=0; $i < count($content_templates); $i++): ?>
				<option value="<?php echo $content_templates[$i]; ?>" <?php if(isset($content->template) && $content->template == $content_templates[$i]){ echo 'selected="selected"';} ?> > <?php echo Page::clean_template_name($content_templates[$i]); ?></option>
				<?php endfor; ?>
				</select>
			</p>
			<p>
				<label for="weight">Weight</label>
				<input type="text" name="weight" value="<?php echo $content->weight ?>" id="weight">
			</p>
			<?php endif; ?>
			<p>
				<label for="slug">Slug</label><input type="text" name="slug" value="<?php echo $content->slug; ?>" id="slug">
            </p>
            <input type="hidden" id="content_id" name="content_id" value="<?php echo $content->id ?>">		
			<input type="hidden" name="author_id" value="<?php echo $content->author_id ?>">		
			<!-- <input type="hidden" name="created" value="<?php //echo $content->created ?>">		 -->
			<input type="hidden" id="content_type" name="content_type" value="<?php echo $content->type ?>">
		</div>
        <?php if($content->type == ContentType::POST) : ?>
		<div id="category_box" class="section_box clearfix">
            <h3>Category</h3>
			<p>
				<label for="category">Choose One</label>
				<select name="category" size="1" id="category">
					<option value="">None</option>
					<?php foreach( $categories as $cat): ?>
					<option value="<?php echo $cat->id; ?>" <?php if(isset($content->category_id) && $content->category_id == $cat->id){ echo 'selected="selected"';} ?> > <?php echo $cat->title; ?></option>
					<?php endforeach?>
				</select>
			</p>
            <p style="text-align:right;" > <a href="" id="toggle_category_input">create new category</a> </p>
			<div class="hidden" id="new_category_box">
                <p><a href="categories.php" class="right">Edit Categories</a></p>
                <p>
                    <label for="category_title">Title</label>
                    <input type="text" id="category_title" value="" />
                </p>
                <p>
                    <label for="category_description">Description</label>
                <textarea id="category_description" name="" rows="5" cols="30"></textarea>
                </p>
                <p class="" ><a href="#" class="big_btn create_category">Create Category</a></p>
			</div>
		</div>
		<?php endif; ?>
		<?php if ($content->id) : ?>
		<div id="date_box" class="section_box clearfix">
            <h3>Created On</h3>
			<p><strong id="created_string"><?php echo date("M d, Y g:i A", strtotime($content->created)); ?></strong> <a href="#" id="toggle_date_input" class="right">change date</a></p>
			<p id="change_date_inputs" class="hidden">
				<input type="text" style="width:140px" name="created_date" id="created_date" value="<?php echo empty($content->created) ?  date("M d, Y") : date("M d, Y", strtotime($content->created) ); ?>">
				<input type="text" style="width:25px" name="created_hour" id="created_hour" value="<?php echo empty($content->created) ?  "" : date("H", strtotime($content->created)); ?>"> :
				<input type="text" style="width:25px" name="created_minutes" id="created_minutes" value="<?php echo empty($content->created) ?  "" : date("i", strtotime($content->created)); ?>">
				<input type="hidden" name="created_seconds" id="created_seconds" value="<?php echo empty($content->created) ?  "" : date("s", strtotime($content->created)); ?>">
			</p>
		</div>
		<div id="tag_box" class="section_box clearfix">
            <h3>Tags</h3>
			<p><input type="text" id="tags_input" value="" /><small class="note" >separate multiple tags with commas<br>(orange, blue, red)</small></p>
            <p class="half" ><a href="ajax/tags_create.php" class="big_btn create_tags">Create Tag</a></p>
            <ul id="tag_list" class="clearfix">
            <?php if( isset($tags) && !empty($tags)) :?>
            <?php foreach ($tags as $tag) : ?>
            	<li> <a href="" id="<?php echo $tag->id;?>" class="delete_tag_btn">delete</a> <span><?php echo $tag->tag; ?></span> </li>
            <?php endforeach; ?>
			<?php else : ?>
				<p class="warning_msg"><strong>You have no tags for this <?php echo $content->type; ?>.</strong> Tags offer your visitors another way to navigate content. Tags are also very effective for Search Engine Optimization (SEO). It is highly recommended that you enter a few descriptive words about this <?php echo $content->type; ?>.</p>
            <?php endif;?>
            </ul>
		</div>
		<?php endif; ?>
	</fieldset>
	<fieldset class="left_side">
		<p><input type="text" id="content_title" name="content_title" placeholder="Title" autofocus value="<?php echo $content->title ?>" /></p>
		<p><textarea name="content_body" id="content_body" class=""><?php echo $content->body; ?></textarea></p>
        <?php if ($content->id) : ?>
            <div id="snippet" class="section_box">
                <h3><?php echo ucwords($content->type) ?> Snippets <small class="right"><a href="#" id="snippet_suggestions">Snippet suggestions</a></small></h3>
                <?php foreach ($snippets as $snippet) : ?>
                <fieldset class="name_value last">
                    <p class="half left"><input type="text" name="snippet_name" value="<?php echo $snippet->name; ?>" id="snippet_name"></p>
                    <p class="half right"><textarea type="text" name="snippet_value" id="snippet_value"><?php echo $snippet->value; ?></textarea></p>
                    <input type="hidden" name="snippet_id" value="<?php echo $snippet->id ?>" id="snippet_id">
                    <nav class="left">
                        <ul>
                            <li><button class="snippet_update" >update</button></li>
                            <li><button class="snippet_delete">delete</button></li>
                        </ul>				
                    </nav>
                </fieldset>
                <?php endforeach; ?>
                <fieldset id="new_snippet_fields">
                    <p><input type="text" name="new_snippet_name" id="new_snippet_name" placeholder="Snippet Name" /></p>
                    <p><textarea name="new_snippet_value" id="new_snippet_value" placeholder="Snippet Value" ></textarea></p>
                    <input type="hidden" name="snippet_content_id" value="<?php echo $content->id ?>" id="snippet_content_id">
                    <p><button id="new_snippet_save">Submit</button></p>
                </fieldset>
            </div>
        <?php endif; ?>
	</fieldset>
</form>

<div id="delete_content_dialog" class="ui-helper-hidden" title="Are you sure you want to delete this <?php echo $content->type ?>"><p><strong>This cannot be undone</strong> and will remove the <?php echo $content->type ?> from your site.</p></div>

<?php
	include_layout("footer.php" ,"layouts");
?>
