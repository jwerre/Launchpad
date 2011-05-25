<?php
	include '../lib/initialize.php';
	include_layout("header.php", "layouts");
?>
<h1 id="settings">Settings</h1>
<section class="left_side">
    <p>These setting will help you modify the look and feel of Launchpad.</p>
	<form action="" id="settings" method="get" accept-charset="utf-8">
		<fieldset>
			<p><label for="page_width">Page Width</label>
			<select name="page_width" id="page_width">
				<option value="layout_100">100 Percent</option>
				<option value="layout_90">90 Percent</option>
				<option value="layout_75">75 Percent</option>
				<option value="layout_960">960 Pixels</option>
				<option value="layout_1280">1280 Pixels</option>
				<option value="layout_1400">1400 Pixels</option>
				<option value="layout_1600">1600 Pixels</option>
			</select></p>
			<p><label for="lp_color">Color</label>
			<select name="lp_color" id="lp_color">
				<option value="red">Red</option>
				<option value="orange">Orange</option>
				<option value="yellow">Yellow</option>
				<option value="blue">Blue</option>
				<option value="green">Green</option>
				<option value="violet">Violet</option>
				<option value="black">Black</option>
				<option value="white">White</option>
			</select></p>
		</fieldset>
		<!-- <button>Submit</button> -->
	</form>
</section>


<?php
	include_layout("footer.php" ,"layouts");
?>
