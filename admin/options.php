<?php
	include '../lib/initialize.php';
	if( isset($_POST['save_button']) ){
		$options->site_name = $_POST['site_name'];
	    $options->tagline = $_POST['tagline'];
	    $options->description = $_POST['description'];
 	    $options->analytics_id = $_POST['analytics_id'];
		
		$session->msg_type = 'success_msg';
		$session->message('Your options have been saved.');
	}
	$custom_options = Options::get_options(true);
	include_layout("header.php", "layouts");
?>
<h1 id="add_contnet">Options</h1>
<form id="add_content" action="" id="settings" method="post" accept-charset="utf-8">
	<fieldset class="right_side clearfix" >
		<section class="button_area clearfix">
		<p><button type="submit" class="save" id="save_button" name="save_button">Save</button></p>
		</section>
		<div class="section_box">
			<h3>Current Theme</h3>
            <p class="center preview" style="float:none">
            <a href="themes.php"> <img class="theme_thumbnail" src="<?php echo theme_directory().'/preview.png'; ?>" alt="" /> </a>
            <div class="hidden">
				<h4 class="center">Suggested Custom Options</h4><hr>
				<ul id="option_suggestions" class="" style="margin-bottom:20px;">
				</ul>
            </div>
			<p><a href="themes.php" class="big_btn">Change Theme</a></p>
		</div>
	</fieldset>
	<fieldset class="left_side">
		<p><label for="site_name">Site Name</label><input type="text" name="site_name" value="<?php echo isset($options->site_name) ? $options->site_name : "NONE"; ?>" id="site_name"></p>
		<p><label for="tagline">Tagline</label><input type="text" name="tagline" value="<?php echo (isset($options->tagline)) ? $options->tagline : ""; ?>" id="tagline"></p>
		<p><label for="description">Site Description</label><textarea name="description" id="description"><?php echo (isset($options->description)) ? $options->description : ""; ?></textarea></p>
		<p><label for="analytics_id">Google Analytics Id</label><input type="text" name="analytics_id" value="<?php echo (isset($options->analytics_id)) ? $options->analytics_id : ""; ?>" id="analytics_id"></p>
	</fieldset>
</form>

<section class="left_side" >
	<div id="snippet" class="section_box">
		<h3>Custom Options<small class="right"><a href="#" id="option_suggestions">Option suggestions</a></small></h3>
		<?php foreach ($custom_options as $option) : ?>
		<fieldset class="name_value last">
			<p class="half left"><input type="text" name="snippet_name" value="<?php echo $option->name; ?>" id="snippet_name"></p>
			<p class="half right"><textarea type="text" name="snippet_value" id="snippet_value"><?php echo $option->value; ?></textarea></p>
			<input type="hidden" name="snippet_id" value="<?php echo $option->id ?>" id="snippet_id">
			<nav class="left">
				<ul>
					<li><button class="snippet_update" >update</button></li>
					<li><button class="snippet_delete">delete</button></li>
				</ul>				
			</nav>
		</fieldset>
		<?php endforeach; ?>
		<fieldset id="new_snippet_fields">
			<p><input type="text" name="new_snippet_name" id="new_snippet_name" placeholder="Option Name" /></p>
			<p><textarea name="new_snippet_value" id="new_snippet_value" placeholder="Option Value" ></textarea></p>
			<input type="hidden" name="snippet_content_id" value="0" id="snippet_content_id">
			<p><button id="new_snippet_save">Submit</button></p>
		</fieldset>
	</div>
</section>

<?php
	include_layout("footer.php" ,"layouts");
?>
