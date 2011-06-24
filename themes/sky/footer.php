<?php 
global $options;
?>
		</div> <!-- end of #main -->
		<br class="clear">
		<footer>
			<div class="divide">&nbsp;</div>
			<div class="three_col">
				<h3><?php echo $options->site_name; ?></h3>
				<p style="line-height:1;"><strong><?php echo $options->address; ?></strong></p>
				<ul>
					<li class="phone"><?php echo $options->phone; ?></li>
					<li class="mail"><a href="mailto:<?php echo $options->email; ?>"><?php echo $options->email; ?></a></li>
				</ul>
			</div>
			<div class="three_col last">
				<h3>Follow us on...</h3>
				<a href="<?php echo $options->facebook; ?>"><img src="<?php echo image_directory() ?>/icn_facebook_32.png" width="32" height="32" alt="Icn Facebook 32"></a>
				<a href="<?php $options->twitter;?>"><img src="<?php echo image_directory() ?>/icn_twitter_32.png" width="32" height="32" alt="Icn Twitter 32"></a>
			</div>
		</footer>
	</div> <!-- end of #container -->


	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.js"></script>
	<script>!window.jQuery && document.write(unescape('%3Cscript src="js/libs/jquery-1.4.2.js"%3E%3C/script%3E'))</script>
	<script src="<?php echo js_directory() ?>/libs/validate/jquery.validate.min.js" ></script>
	<script src="<?php echo js_directory() ?>/libs/jquery.hoverIntent.min.js" ></script>
	<script src="<?php echo js_directory() ?>/libs/loopedslider.js" ></script>

	<script src="<?php echo js_directory() ?>/plugins.js"></script>
	<script src="<?php echo js_directory() ?>/script.js"></script>
	<!-- end concatenated and minified scripts-->


	<!--[if lt IE 7 ]>
	  <script src="js/libs/dd_belatedpng.js"></script>
	  <script> DD_belatedPNG.fix('img, .png_bg'); </script>
	<![endif]-->

	<!-- change the UA-XXXXX-X to be your site's ID -->
	<!-- <script>
	 var _gaq = [['_setAccount', '<?php echo $options->analytics_id ?>'], ['_trackPageview']];
	 (function(d, t) {
	  var g = d.createElement(t),
	      s = d.getElementsByTagName(t)[0];
	  g.async = true;
	  g.src = ('https:' == location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	  s.parentNode.insertBefore(g, s);
	 })(document, 'script');
	</script> -->
  
</body>
</html>
