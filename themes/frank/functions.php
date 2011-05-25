<?php
    include_once(theme_directory(true).'/imageprocessor.php');
    function getGalleryPhotos($numRows = 3, $width = 60, $height= 44)
    {
        $photos = glob(image_directory(true).'/gallery_photos/{*.jpg,*.gif,*.png,*.jpeg}', GLOB_BRACE);
        for($i = 0; $i < count($photos); $i++){ 
            $img_name = image_directory().'/gallery_photos/'.pathinfo($photos[$i], PATHINFO_BASENAME);
            $class = ( ($i+1) % $numRows == 0) ? 'class="padding_no_side"' : "";
            $output = '<li '.$class.'>';
            $output .= '<a href="'.$img_name.'" rel="gallery">';
            $output .= '<img src="'.theme_directory().'/imageprocessor.php?src='.$photos[$i].'&amp;w='.$width.'&amp;h='.$height.'&mode=fit" alt="" /></a>';
            $output .= '</li>';
            echo $output;
        }
    }

?>
