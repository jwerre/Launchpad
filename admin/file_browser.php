<?php include '../lib/initialize.php';
	
	$columns = 4;
    $limit = $columns*4;
	$group = 1;
	$cookie_name = 'browser_group';
	if( isset( $_GET['group']) ){
		$group = $_GET['group'];
		$cookie->$cookie_name = $group;
	}
	elseif( isset($cookie->$cookie_name) ){
		$group = $cookie->$cookie_name;
	}
	
	$total = Image::count_all('WHERE type LIKE "image%" ');
	$pagination = new Pagination($group, $limit, $total);
	$offset = $pagination->offset();
	
	$media_files = Image::find_by_sql("SELECT * FROM media WHERE type LIKE 'image%' ORDER BY 'id' DESC LIMIT $offset, $limit");
?>
<!DOCTYPE HTML>
<html>
<head>
	<title>File Browser</title>
	<link rel="stylesheet" href="<?php echo BASE_URL?>/admin/css/style.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
	<script>!window.jQuery && document.write(unescape('%3Cscript src="js/libs/jquery-1.5.0.js"%3E%3C/script%3E'))</script>
	<script type="text/javascript" charset="utf-8">
		function getUrlParam(paramName) {
		  var reParam = new RegExp('(?:[\?&]|&amp;)' + paramName + '=([^&]+)', 'i') ;
		  var match = window.location.search.match(reParam) ;
		  return (match && match.length > 1) ? match[1] : '' ;
		}
		$(function() {
			$('.browser_img').click(function(event){
				var funcNum = getUrlParam('CKEditorFuncNum');
				var fileUrl = $(event.target).attr("alt");
				window.opener.CKEDITOR.tools.callFunction(funcNum, fileUrl);
				window.close();
			});
		});

	</script>
</head>
<body class="no_bg">
	<table id="image_browser">
		<thead>
			<tr> <th colspan ="<?php echo $columns; ?>"></th> </tr>
		</thead>
		
		<tbody>
		<?php 
		if( !empty($media_files) ){
			$count = 0;
			$new_row = false;
			foreach ($media_files as $media){ 
				echo ($count % $columns == 0) ? "<tr>": "";
				echo '<td class="center">';
				echo '<img class="browser_img" src="imageprocessor.php?src='.$media->filename.'&w=110&h=110&mode=fit" alt="'.$media->filename.'"/>';
				// echo '<figcaption>'.$media->caption.'</figcaption>';
				echo '</td>';
				$count++;
				echo ($count % $columns == 0) ? "</tr>" : "";
			}
		}else{
			echo '<tr><td colspan="'.$columns.'"><div id="message" class="info_msg"><p>there are no images.</p></div></td></tr>';
		}
		?>
		</tbody>
		<?php if( $pagination->necessary()) : ?>
		<tfoot>
			<tr>
				<td colspan="<?php echo $columns; ?>">
					<div id="tab_navigation" class="clearfix">
						<nav class="pagination">
						<?php $pagination->output_controls(); ?>
						</nav>
					</div>
				</td>
			</tr>
		</tfoot>
		<?php endif; ?>
	 </table>
</body>
</html>
