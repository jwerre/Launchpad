<?php
	include '../lib/initialize.php';
	
	if( $session->role >= UserRole::CONTRIBUTOR ){
		// redirect_to('user_edit.php?id='$session->user_id);
	}

	$role = UserRole::GUEST;
	if( isset( $_GET['role']) ){
		$role = $_GET['role'];
		$cookie->users_role = $role;
	}
	elseif( isset($cookie->users_role) ){
		$role = $cookie->users_role;
	}

    $limit = ITEMS_PER_PAGE;
	$cookie_name = 'user'.$role.'_limit';
	if( isset( $_GET['limit']) ){
		$limit = $_GET['limit'];
		$cookie->$cookie_name = $limit;
	}
	elseif( isset($cookie->$cookie_name) ){
		$limit = $cookie->$cookie_name;
	}

	$group = 1;
	$cookie_name = 'user'.$role.'_group';
	if( isset( $_GET['group']) ){
		$group = $_GET['group'];
		$cookie->$cookie_name = $group;
	}
	elseif( isset($cookie->$cookie_name) ){
		$group = $cookie->$cookie_name;
	}

	$total = Content::count_all($role);
	$pagination = new Pagination($group, $limit, $total);
	$offset = $pagination->offset();
	
	$sql = "SELECT * FROM users WHERE role = '$role' ORDER BY last_name ASC LIMIT $offset, $limit";
	$users = User::find_by_sql($sql);
	$user_roles = array_reverse( UserRole::get_roles() );
		
	include_layout("header.php", "layouts");
?>
<h1 id="">Content</h1>
<div id="tab_container">
	<nav class="tabs">		
	<ul>
		<?php foreach ($user_roles as $key => $value) :?>
		<li <?php if($role == $value) echo 'class="on"' ?> ><a id="target" href="?role=<?php echo $value ?>"><?php echo ucwords( strtolower( $key ) ); ?></a></li>
		<?php endforeach; ?>
		<li class="right"><a href="user_edit.php" class="add_button user_icn">Create New User</a></li>
	</ul>
	</nav>
	<div id="admin">
		<ul id="" class="content_list connected_sortable" >
		<?php if( !empty($users) ): ?>
			<?php foreach ($users as $user): ?>
				<li><a href="user_edit.php?id=<?php echo $user->id ?>"><?php echo $user->last_name.", ".$user->first_name; ?></a>
					<nav class="small_btn right">
						<ul>
							<?php if( $session->role == UserRole::SUPER || $user->id == $session->user_id): ?>
							<li><a href="user_edit.php?id=<?php echo $user->id ?>">edit</a></li>
							<?php endif; ?>
							<li><a href="ajax/user_delte.php?id=?<?php echo $user->id ?>">delete</a></li>
							<?php if( isset($user->email) ): ?>
							<li><a href="mailto:<?php echo $user->email; ?>">email</a></li>
							<?php endif; ?>
						</ul>				
					</nav>
				</li>
			<?php endforeach; ?>
		<?php else: ?>
			<div id="message" class='info_msg'>
				<p>There are no users with this role.</p>			
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

<?php
	include_layout("footer.php" ,"layouts");
?>

