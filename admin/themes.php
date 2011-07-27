<?php
	include '../lib/initialize.php';

	if( $session->role > UserRole::ADMIN ){
		$session->msg_type = 'info_msg';
		$session->message('You do not have sufficient privileges to access this page');
		redirect_to("index.php");		
	}

    if (isset($_GET['theme'])) {
        $options->theme = $_GET['theme'];
    }
	//if(isset($_GET['remove'])){
	// $theme_dir = THEMES_PATH.'/'.$_GET['remove'];
	// $owner = fileperms(fileowner($theme_dir)); echo '<pre>'; print_r($owner); echo '</pre>';exit;
	//try{
	// delete_directory($theme_dir);
	//} catch (Exception $error) {
	// var_dump($error->getMessage());
	//}
	//}

    $current_theme_name = $options->theme;
    $current_theme_xml;
    $theme_paths = glob(THEMES_PATH.'/*', GLOB_ONLYDIR);

	foreach( $theme_paths as $path ){
		//echo strrchr('/', $path);
	}

	$theme_paths = array_filter($theme_paths,
	   function($value) {
        global $current_theme_name;
        global $current_theme_xml;
        if(strstr($value, $current_theme_name)){
            $current_theme_xml = $value;
            return false;
        }
        return true;
    });

	// if(is_readable(theme_directory().'/theme.xml')){
		$current_theme = simplexml_load_file( theme_directory().'/theme.xml' , 'SimpleXMLElement', LIBXML_NOBLANKS );
	//}
	include_layout("header.php" ,"layouts");
?>
<script>
	var allThemes = <?php echo json_encode($theme_paths); ?>
</script>
<h1>Themes</h1>
<section class="right_side">
	<div class="section_box" id="current_theme">
    <h3>Current Theme</h3>
    <ul class="themes">
		<div class="preview" style="">
		<img src="<?php echo (isset($current_theme->preview) && !empty($current_theme->preview)) ? $current_theme->preview : theme_directory().'/preview.png'; ?>" alt="" />
		</div>
		<div class="theme_desciption">
			<h4><?php echo (isset($current_theme->title) && !empty($current_theme->title)) ? ucwords($current_theme->title) : ucwords(substr(strrchr(theme_directory(), "/"), 1)); ?></h4>
			<p><?php echo (isset($current_theme->description) && !empty($current_theme->description)) ? $current_theme->description : 'No description'; ?></p>
		</div>
		<?php if( isset($current_theme->category) && !empty($current_theme->category) ): ?>
			<br><h4>Suggested Categories</h4>
			<ul>
				<?php foreach ($current_theme->category as $cat) : ?>
				<li><?php echo ucwords($cat->name); ?></li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>
</section>
<section id="available_themes" class=" left_side section_box">
<h3>Available Themes</h3>
	<?php if(!empty($theme_paths)) :?>
	<ul class="themes">
        <?php foreach ($theme_paths as $path):
			$filename = substr(strrchr($path, "/"), 1);
			if(file_exists($path.'/theme.xml')){
				$theme = simplexml_load_file( $path.'/theme.xml', 'SimpleXMLElement', LIBXML_NOBLANKS );
			}
            $preview = (isset($theme->preview) && !empty($theme->preview)) ? $theme->preview : BASE_URL.'/themes/'.$filename.'/preview.png';
            $title = (isset($theme->title) && !empty($theme->title)) ? $theme->title : $filename;
            $descrption = (isset($theme->description) && !empty($theme->description)) ? $theme->description : 'No description';
        ?>
        <li class="theme clearfix">
            <div class="preview left">
            <img src="<?php echo $preview;  ?>" alt="" />
            </div>
            <div class="theme_desciption">
                <h4><?php echo ucwords($title); ?></h4>
                <p><?php echo $descrption; ?></p>
            </div>
            <nav class="small_btn">
                <ul>
                    <!-- <li><a href="">preview</a></li> -->
                    <!-- <li><a href="?remove=<?php echo $filename ?>">remove</a></li> -->
                    <li><a href="?theme=<?php echo $filename ?>">use</a></li>
                </ul>				
            </nav>
        </li>
        <?php endforeach; ?>
	</ul>
    <?php endif; ?>
</section>
<?php
	include_layout("footer.php" ,"layouts");
?>
