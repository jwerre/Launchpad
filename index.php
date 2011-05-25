<?php
	include 'lib/initialize.php';
    include theme_directory(true).'/functions.php';

	global $page;
	global $post;
	global $category;
	global $snippets;
	
	if( isset($_GET['page']) ){
		$page = Page::find_by_id($_GET['page']);
		if( !empty( $page ) )
			$snippets = $page->snippets();
	}
	elseif( isset($_GET['post']) ){
		$post = Post::find_by_id($_GET['post']);
		if( !empty( $post ) )
			$snippets = $post->snippets();
	}
	elseif( isset($_GET['cat']) ) {
		$category = Category::find_by_id($_GET['cat']);
	}
	else{
		$page = Page::find_by_id(1);
		if( !empty( $page ) )
			$snippets = $page->snippets();
	}
	// include header
	include_layout('header.php');	

	// PAGE
	if(!empty($page)){
		include_layout('page.php');
	}
	// POST
	elseif(!empty($post)){
		include_layout('post.php');
	}
	// CATEGORIES
	elseif(!empty($category)){
		include_layout('categories.php');
	}

	// include footer
	include_layout('footer.php');
?>
