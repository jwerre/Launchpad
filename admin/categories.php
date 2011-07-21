<?php
	include '../lib/initialize.php';
	
	if( $session->role > UserRole::ADMIN ){
		$session->msg_type = 'info_msg';
		$session->message('You do not have sufficient privileges to access this page');
		redirect_to("index.php");		
	}	
	
    $category = new Category();

	if (isset($_POST['submit']) && !empty($_POST['title']) ){
		
		$category->id = ( !empty($_POST['id']) ) ? $_POST['id'] : NULL;
		$category->title = $_POST['title'];
		$category->description = ( !empty($_POST['description']) ) ? stripslashes($_POST['description']) : NULL;
		$category->slug = slug($_POST['title']);
		
        try{
            if($category->save()){
                $session->message('The category "'.$category->title.'" has been saved');
                redirect_to("categories.php");
            }else{
                $session->message('there was a problem saving the category');
				$session->msg_type = 'error_msg';
                redirect_to("categories.php");
            }
        } catch (PDOException $error) {
            $error_msg = ( $error->getCode() == 23000 ) ? 'There is already a Category with the title: "'.$category->title.'"' : 'there was a problem saving the category';
			$session->msg_type = 'error_msg';
            $session->message( $error_msg );
            redirect_to("categories.php");
        }

	}
	
	if (isset($_GET['id'])){
		$category = Category::find_by_id($_GET['id']);
	}

    $categories = Category::find_all();
    include_layout('header.php', 'layouts');
?>

<h1 id="categories">Categories</h1>
<section class="right_side half">
    <?php if (!empty($categories)) : ?>
	<ul id="category_list" class="content_list" >
    <?php foreach ($categories as $cat) :?>
		<li class="category clearfix" id="<?php echo $cat->id; ?>">
			<a href="?id=<?php echo $cat->id ?>" title="<?php echo $cat->description; ?>" class=""><?php echo $cat->title;?></a>
            <nav class="small_btn right">
                <ul>
                    <li><a href="?id=<?php echo $cat->id; ?>">edit</a></li>
                    <li><a href="" class="category_delete">delete</a></li>
                </ul>				
            </nav>
		</li>
    <?php endforeach; ?>
	</ul>
    <?php endif; ?>
</section>
<form enctype="multipart/form-data" action="" method="post">
	<fieldset class="left_side">
		<input type="hidden" name="id" value="<?php echo $category->id; ?>">		
		<p><label for="title">Title</label><input type="text" class="title" name="title" value="<?php echo $category->title; ?>" /></p>
		<p><label for="description">description</label><textarea name="description" class="description"><?php echo $category->description ?></textarea></p>
		<nav>
			<ul>
                <li><button type="submit" name="submit" class="big_button" ><span><?php echo !empty( $category->id ) ? 'Update' : 'Submit'; ?></span></button></li>
                <?php if( !empty( $category->id) ) :?>
                <li><a href="categories.php" class="big_btn right">Cancel</a></li>				
                <?php endif;?>
				<li class="right"> <a href="" id="suggested_categories">suggestions</a> </li>
			</ul>
		</nav>
	</fieldset>
</form>
<div id="delete_category_dialog" class="ui-helper-hidden" title="Are you sure you want to delete this Category">
    <p><strong>This cannot be undone</strong> and will remove the Category from your site.</p>
</div>

<?php
	include_layout("footer.php" ,"layouts");
?>
