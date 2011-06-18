		</div> <!-- end of #main -->
		<br class="clear">
		<footer>
			<div class="divide">&nbsp;</div>
			<div class="three_col">
				<?php
				$datecalc = strtotime('Sunday');
				$datesearch = date('l, F dS',$datecalc);
				?>
				<h3>Service Times</h3>
				<p><strong><?php echo $datesearch; ?></strong></p>
				<ul>
					<li>8:00 AM - Worship Service</li>
					<li>9:30 AM - Worship Service</li>
					<li>11:00 AM - Worship Service</li>
				</ul>
			</div>
			<div class="three_col">
				<h3>North Sound Church</h3>
				<p style="line-height:1;"><strong>404 Bell Street<br>Edmonds, WA 98020</strong></p>
				<ul>
					<li class="phone">425.776.9800</li>
					<li class="mail"><a href="mailto:">info@northsoundchurch.com</a></li>
				</ul>
			</div>
			<div class="three_col last">
				<h3>Follow us on...</h3>
				<a href="http://www.facebook.com/northsoundchurch"><img src="<?php echo BASE_URL ?>/images/icn_facebook_32.png" width="32" height="32" alt="Icn Facebook 32"></a>
				<a href="http://twitter.com/NSCedmonds"><img src="<?php echo BASE_URL ?>/images/icn_twitter_32.png" width="32" height="32" alt="Icn Twitter 32"></a>
			</div>
		</footer>
	</div> <!-- end of #container -->


	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.js"></script>
	<script>!window.jQuery && document.write(unescape('%3Cscript src="js/libs/jquery-1.4.2.js"%3E%3C/script%3E'))</script>
	<script src="<?php echo BASE_URL ?>/js/libs/validate/jquery.validate.min.js" ></script>
	<script src="<?php echo BASE_URL ?>/js/libs/jquery.hoverIntent.min.js" ></script>
	<script src="<?php echo BASE_URL ?>/js/libs/loopedslider.js" ></script>
	<script src="<?php echo BASE_URL ?>/js/jwplayer/jwplayer.js" ></script>
	
	<!-- scripts concatenated and minified via ant build script-->
	<script src="<?php echo BASE_URL ?>/js/plugins.js"></script>
	<script src="<?php echo BASE_URL ?>/js/script.js"></script>
	<!-- end concatenated and minified scripts-->


	<!--[if lt IE 7 ]>
	  <script src="js/libs/dd_belatedpng.js"></script>
	  <script> DD_belatedPNG.fix('img, .png_bg'); </script>
	<![endif]-->

	<!-- change the UA-XXXXX-X to be your site's ID -->
	<!-- <script>
	 var _gaq = [['_setAccount', 'UA-XXXXX-X'], ['_trackPageview']];
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