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
	$mail->message .= !empty($_POST['member']) ? 'Is a member':"Not a member";
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
				<p class="check"><input type="checkbox" name="member" value="" id="member">Do you currently attend North Sound Church</p>
				<p><input type="image" src="<?php echo BASE_URL.'/images/btn_send.png' ?>" value="submit"></p>
			</form>
		</div>
	</div>
	
</div>
<div id="column_right">
	<div id="map">
		<div id="map_canvas"></div>		
	</div>
	<h3>Talk to us.</h3>
	<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>

	<p class="address">404 Bell Street<br>
	Edmonds, WA 98020</p>
	<ul>
	<li class="phone">(425) 776-9800</li>
	<li class="fax">(425) 778-5384</li>
	<li class="mail"><a href="mailto:info@northsoundchurch.com">info@northsoundchurch.com</a></li>
	</ul>
</div>	
	

		<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false">
		</script>
		<script type="text/javascript">
			function initialize() {
				var latlng = new google.maps.LatLng(47.811915250460125, -122.37743854522705);
				var mapOptions = {
					zoom: 15,
					center: latlng,
					streetViewControl: false,
					mapTypeControl: false,
					mapTypeId: google.maps.MapTypeId.ROADMAP
				};
				var map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
				var marker = new google.maps.Marker({
				    map:map,
				    draggable:false,
				    animation: google.maps.Animation.DROP,
				    position: latlng
				});
				// var contentString = '<div id="content">'+'</div>';
				// var infowindow = new google.maps.InfoWindow({
				//     content: contentString
				// });
				// google.maps.event.addListener(marker, 'click', function() {
				//   infowindow.open(map,marker);
				// });				
			}
			initialize();
		</script>
