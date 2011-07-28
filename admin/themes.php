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
	// if(theme is not current theme){
	// delete_directory($theme_dir);
	// }
	//} catch (Exception $error) {
	// var_dump($error->getMessage());
	//}
	//}

    $current_theme_name = $options->theme;
    $current_theme_xml;
    $theme_paths = glob(THEMES_PATH.'/*', GLOB_ONLYDIR);


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

	foreach( $theme_paths as $path ){
		$p = strrchr( $path , '/');
		$themes[] = BASE_URL.'/themes'.$p;
	}
	include_layout("header.php" ,"layouts");
?>
<script>
	var themeData = <?php echo json_encode($themes); ?>;
</script>
<h1>Themes</h1>
<section class="right_side">
	<div class="section_box" id="current_theme">
		<h3>Current Theme</h3>
		<div class="preview" style="">
			<img src="" alt="" />
		</div>
		<h4></h4>
		<p class="theme_description"></p>
		<p class="right" style="margin: 15px 0;"> <a href="" id="suggested_categories">suggested categories</a> </p>
	</div>
</section>
<section id="available_themes" class=" left_side section_box">
<h3>Available Themes</h3>
	<?php if(!empty($theme_paths)) :?>
	<ul class="themes">
        <li class="theme clearfix hidden">
            <div class="preview left">
				<img src="" alt="" />
            </div>
            <div class="">
                <h4 class="theme_title"></h4>
                <p class="theme_description"></p>
            </div>
            <nav class="small_btn">
                <ul>
                    <!--li><a href="">preview</a></li>
                    <li><a href="?remove=">remove</a></li-->
                    <li><a class="use_button" href="?theme=">use</a></li>
                </ul>				
            </nav>
        </li>
	</ul>
    <?php endif; ?>
</section>
<?php
	include_layout("footer.php" ,"layouts");
?>
