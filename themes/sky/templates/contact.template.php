<?php
global $page;
if(isset($_POST['submit'])){
	$mail = new Mail();
	$mail->to_name = EMAIL_FROM_NAME;
	$mail->to_email = EMAIL_FROM_ADDRESS;
	$mail->subject = !empty($_POST['subject']) ? $_POST['subject']:"";
	$mail->message = !empty($_POST['name']) ? 'name: '.$_POST['name']."\n" :"";
	$mail->message .= !empty($_POST['email']) ? 'email: '.$_POST['email']."\n" :"";
	$mail->message .= !empty($_POST['message']) ? $_POST['message']."\n":"";
	$mail->send();
}
?>
<div id="column_left">
	<h1><?php echo $page->title ?></h1>
	<?php echo $page->body ?>
	
	<div class="box long">
		<h3 class="box_head">Complete this form to send us a message</h3>
		<div class="box_bottom">
			<form action="" method="post" id="contact_form" accept-charset="utf-8">
				<p><label for="name">Name</label><input type="text" name="name" value="" class="required" minlength="2"></p>
				<p><label for="email">Email</label><input type="text" name="email" value="" class="required email"></p>
				<p><label for="subject">Subject</label><input type="text" name="subject" value="" class="required" minlength="2"></p>
				<p><textarea name="message" id="message"  class="required" minlength="10"></textarea></p>
				<p><input type="image" src="<?php echo image_directory('btn_send.png')?>" value="submit"></p>
			</form>
		</div>
	</div>
	
</div>

<?php include_layout('column_contact.php') ?>

