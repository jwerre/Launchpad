<?php
	include 'lib/initialize.php';
    include theme_directory(true).'/functions.php';

	if( isset($_GET['page']) ){
		$page_id = $_GET['page'];
		if(is_numeric($page_id)){
			$page = Page::find_by_id($page_id);
		}else{
			$page = Page::find_by_title($page_id);
		}
	}
	elseif( isset($_GET['post']) ){
		$post_id = $_GET['post'];
		if(is_numeric($post_id)){
			$post = Post::find_by_id($post_id);
		}else{
			$post = Page::find_by_title($post_id);
		}
	}
    elseif( isset($_GET['search']) ){
        $search_term = $_GET['search'];       
    }
    else{
		$page = Page::find_by_id(1);
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
    // SEARCH
    elseif(!empty($search_term)){
        include_layout('search.php');
    }
    // 440
    else{
        include_layout('404.php');
    }
	// include footer
	include_layout('footer.php');
?>
