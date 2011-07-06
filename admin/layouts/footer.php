<?php
	global $session;
 ?>
    <br class="clear" >
	</div> <!-- CLOSE MAIN -->
</div> <!-- CLOSE CONTAINER -->
<footer class="width">
	<p class="disclaimer"><a href="http://www.asklaunch.com">Launchpad</a> a content management system</p>
</footer>
	<!-- Global javascript variables -->
	<script>
        <?php if( isset($session->user_id) ): ?>
            user_id = <?php echo $session->user_id;?>
        <?php else :?>
            user_id = null;
        <?php endif; ?>
	</script>
	<!-- JavaScript at the bottom for fast page loading -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
	<script>!window.jQuery && document.write(unescape('%3Cscript src="js/libs/jquery-1.5.0.js"%3E%3C/script%3E'))</script>
	<script src="js/libs/jqueryUI/jqueryui-1.8.9.min.js"></script>
	<script src="js/libs/jquery_cookie.js"></script>
	<script src="js/libs/ckeditor/ckeditor.js"></script>
	<script src="js/libs/jquery.prettyPhoto.js"></script>
	<script src="js/libs/jquery.hoverIntent.js"></script>
	<script src="js/libs/uploadify/swfobject.js"></script>
	<script src="js/libs/uploadify/jquery.uploadify.v2.1.4.min.js"></script>
	<script src="js/libs/jquery.validate.js"></script>
	<script src="js/libs/jquery.flot.js"></script>
	
	
	<!-- scripts concatenated and minified via ant build script-->
	<script src="js/plugins.js"></script>
	<script src="js/script.js"></script>
	<!-- end concatenated and minified scripts-->
  
</body>
</html>
