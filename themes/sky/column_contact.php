<?php global $options; ?>
<div id="column_right" class="contact_info">
	<div id="map">
		<div id="map_canvas"></div>		
	</div>
	<!-- <h3>Talk to us.</h3> -->
	<!-- <p>If you have any questions or need to talk to anyone, we'd love to hear from you.</p> -->

	<p class="address"><?php echo $options->address; ?></p>
	<ul>
	<li class="phone"><?php echo $options->phone; ?></li>
	<li class="fax"><?php echo $options->fax; ?></li>
	<li class="mail"><a href="mailto:<?php echo $options->email; ?>"><?php echo $options->email; ?></a></li>
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
