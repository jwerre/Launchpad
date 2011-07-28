<?php
	global $page;
	global $meta_data;
	$staffs = Post::find_by_category_title('Staff');
?>
<div id="column_left">
	<?php
		echo "<h1>".$page->title."</h1>";
		echo $page->body;
		
	 	if(!empty($staffs)):
	 	foreach ($staffs as $staff):
			$staff_meta = $staff->snippets();
	?>
	<div class="bio clearfix">
		<img alt="<?php echo $staff->title; ?>" src="<?php if($staff_meta['portrait']) { echo $staff_meta['portrait']; } ?>">
		<h3><?php echo $staff->title ?></h3>
		<?php if(isset($staff_meta['position'])): ?>
		<p class="title"><?php echo $staff_meta['position']; ?></p>
		<?php endif; ?>
		<?php echo $staff->body ?>
		<ul class="contact_nav">
			<?php if (isset($staff_meta['email'])): ?>
			<li><a class="email" href="mailto:<?php echo $staff_meta['email'] ?>"><?php echo $staff_meta['email'] ?></a></li>				
			<?php endif; ?>
			<?php if (isset($staff_meta['facebook'])) : ?>
			<li><a class="facebook" href="<?php echo $staff_meta['facebook'] ?>">facebook</a></li>
			<?php endif; ?>
			<?php if (isset($staff_meta['twitter'])) : ?>			
			<li><a class="twitter" href="<?php echo $staff_meta['twitter'] ?>">twitter</a></li>
			<?php endif; ?>
		</ul>
	</div>
	<?php
		endforeach;
		else:
	?>
		<h2>Sorry, There is no one on staff at the moment.</h2>
	<?php
		endif;
	?>
</div>
<?php include_layout('column_contact.php') ?>
