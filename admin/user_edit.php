<?php
	include '../lib/initialize.php';

    if( isset($_GET['id']) ){
	    $id = $_GET['id'];
    }
	if( $session->role == UserRole::ADMIN ){
		if(!empty($id)){
			$id = $session->user_id;
		}
	}elseif( $session->role >= UserRole::CONTRIBUTOR ){
		$id = $session->user_id;
	}
	
	$user = new User();
    
    if (isset($_POST['save_button']) && isset($_POST['password'])){

		$user->id = ( !empty($_POST['user_profile_id']) ) ? $_POST['user_profile_id'] : NULL;
		$user->first_name = trim($_POST['first_name']);
		$user->last_name = trim($_POST['last_name']);
		$user->username = trim($_POST['username']);
		$user->email = $_POST['email'];
		$user->password = ( !empty($_POST['password']) ) ? md5($_POST['password']) : $user->password;
		$user->role = ( isset($_POST['role']) ) ? $_POST['role'] : UserRole::GUEST;
		
		try{
			$user->save();
			$session->message('You\'re profile has been updated.');
		}catch(PDOException $e) {
			if($e->getCode() == 23000 ){
				$session->message('The username or email you entered is already in use.');
			}else{
				$session->message('There was a problem updating the profile.');
			}
		}		
	}
	
	if ( !empty($id) ) {
		$user = User::find_by_id($id);
		$user_image = $user->profile_image();
	}	
	
	$user_roles = array_reverse( UserRole::get_roles() );
	include_layout("header.php", "layouts");
?>
<h1><?php echo (isset($id) && !empty($id) ) ? 'Edit' : 'Create New' ; ?> User</h1>
<form id="user_edit" action="" id="settings" method="post" accept-charset="utf-8">
	<fieldset class="right_side clearfix">
		<section class="button_area clearfix">
		<p><button name="save_button" type="submit" id="save_button">Save</button></p>
		<?php if(isset($id) && !empty($id)): ?>
		<nav>
			<ul>
				<li><a href="" class="big_btn">Delete</a></li>
				<li><a href="mailto:<?php echo $user->email; ?>" class="big_btn right">Email</a></li>				
			</ul>
		</nav>
		</section>
		<div id="profile_img" class="section_box">
			<h3>Profile Image</h3>
			<?php if( !empty($user_image) ) : ?>
			<p id="image_placholder" class="center" style="width:auto;"><img src="<?php echo $user_image; ?>" /></p>
            <?php endif; ?>
			<p class="center"><input type="file" name="file_upload" id="file_upload" class="hidden"/></p>
		</div>
		<?php endif; ?>
	</fieldset>
	<fieldset class="left_side">
		<p><label for="first_name">First Name</label><input type="text" id="first_name" name="first_name" value="<?php echo $user->first_name ?>"/></p>
		<p><label for="last_name">Last Name</label><input type="text" id="last_name" name="last_name" value="<?php echo $user->last_name ?>"/></p>
		<p><label for="username">Username</label><input type="text" id="username" name="username" class="required" value="<?php echo $user->username ?>"/></p>
		<p><label for="email">Email</label><input type="text" id="email" name="email" class="email" value="<?php echo $user->email ?>"/></p>
		<p><label for="password">Password</label><input type="password" name="password" id="password" class="<?php if(empty($id)) echo 'required' ?>" /></p>
		<p><label for="password_confirm">Confirm Password</label><input type="password" id="password_confirm" name="password_confirm" /></p>
		<?php if($user->role == UserRole::SUPER): ?>
		<p>
			<label for="role">Role</label>
			<select name="role" id="role">
				<?php foreach($user_roles as $key => $value): ?>
				<option value="<?php echo $value ?>" <?php if($value == $user->role || $value == UserRole::GUEST) echo "selected"; ?>><?php echo ucwords( strtolower( $key ) ) ?></option>
				<?php endforeach; ?>
			</select>
		</p>
		<?php endif; ?>
		<div class="section_box">
			<h3>Bio</h3>
			<p><textarea name="bio" id="bio"><?php echo $user->bio ?></textarea></p>			
		</div>
		<input type="hidden" name="user_profile_id" id="user_profile_id" value="<?php echo $user->id ?>" />
		<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo MAX_FILE_SIZE ?>" />
		<input type="hidden" name="profile_image_width" id="profile_image_width" value="<?php echo PROFILE_IMG_WIDTH ?>" />
		<input type="hidden" name="profile_image_height" id="profile_image_height" value="<?php echo PROFILE_IMG_HEIGHT ?>" />
	</fieldset>
</form>


<?php
	include_layout("footer.php" ,"layouts");
?>
