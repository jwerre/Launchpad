<?php
 /**
  * A class for storing and manipulating images
  *
  * @author Jonah Werre <jonahwerre@gmail.com>
  * @version 1.0
  * @copyright Jonah Werre <jonahwerre@gmail.com>, 28 June, 2011
  * @package DatabaseObject
  **/
class Image extends Media
{
	
	/**
	 * whitelist for possible image types
	 * @var string = 'image/gif'
	 **/
	const IMG_GIF = 'image/gif';
	/**
	 * whitelist for possible image types
	 * @var string = 'image/jpeg'
	 **/
    const IMG_JPEG = 'image/jpeg';
	/**
	 * whitelist for possible image types
	 * @var string = 'image/png'
	 **/
    const IMG_PNG = 'image/png';

	/**
	 * Reszie Image to exact dimensions. This will often result in destoted images
	 * @var string
	 **/
	const EXACT = 'exact';
	/**
	 * Auto size Image
	 * @var string
	 **/
	const AUTO = 'auto';
	/**
	 * Resizes the Image proportionally based on the new height
	 * @var string
	 **/
	const LANDSCAPE = 'landscape';
	/**
	 * Resizes the Image proportionally based on the new width
	 * @var string
	 **/
	const PORTRAIT = 'portrait';
	/**
	 * Crop the Image from center
	 * @var string
	 **/
	const CROP = 'crop';
	
	/**
	 * The source of the image to crop
	 * TODO: remove this. Use $this->filename instead
	 * @var string
	 **/
	private $source;
	/**
	 * The current width of the Image
	 * @var integer
	 **/
	private $width;
	/**
	 * The current height of the Image
	 * @var integer
	 **/
	private $height;
	/**
	 * New resized images
	 * @var integer
	 **/
	private $image_resized;

	function __construct()
	{

 	}

	/**
	 * Sets Image attributes.
	 * Useage: attach_file( $_FILE['uploaded_file'] );
	 *
	 * @param array $file - An associative array of items uploaded to the current script via the HTTP POST method
	 * @return boolean
	 */
	public function attach_file($file)
	{
		if( !$file || empty($file) || !is_array($file) ){
			array_push( $this->errors, "No file was uploaded" );
			return false;
		}elseif($file['error'] != 0){
			array_push( $this->errors, $this->upload_errors[$file['error']] );
			return false;
		}else{
			$this->temp_path = $file['tmp_name'];
			$this->filename = strtolower( str_replace(" ","_", basename($file['name']) ) );
			$this->type = $file['type'];
			$this->size = $file['size'];
			
			// make sure it's an image
			if ( $this->type == self::IMG_GIF || $this->type == self::IMG_JPEG || $this->type == self::IMG_PNG ) {
				return true;
			}else {
				array_push( $this->errors, 'You are trying to upload a "'.$this->type.' ".'.' Images must be either GIF, JPEG or PNG.' );
				return false;
			}
			
		}
	}
	
	/**
	 * Resize and image
	 *
	 * @param integer $new_width - The new width of the image
	 * @param integer $new_height - The new height of the image
	 * @param string $option=self::EXACT - The options to use ( Image::EXACT | Image::PORTRAIT | Images::LANDSCAPE | Images::Crop | Images::AUTO )
	 * @param array $offset = array('x'=>0, 'y'=>0) - An offset for the crop option
	 * @return null
	 **/
	public function resize($new_width, $new_height, $option=self::EXACT, $offset=array('x'=>0,'y'=>0))
	{
		$this->source = $this->file_path();
		$extension = strtolower(strrchr($this->source, '.'));

		switch($extension) {
			case '.jpg':
			case '.jpeg':
				$this->image = @imagecreatefromjpeg($this->source);
				break;
			case '.gif':
				$this->image = @imagecreatefromgif($this->source);
				break;
			case '.png':
				$this->image = @imagecreatefrompng($this->source);
				break;
			default:
				$img = false;
			break;
		}

		$this->width  = (isset($offset['w'])) ? $offset['w'] : imagesx($this->image);  
		$this->height = (isset($offset['h'])) ? $offset['h'] : imagesy($this->image);

		// *** Get optimal width and height - based on $option
		$option_array = $this->get_dimensions($new_width, $new_height, strtolower($option));

		$optimal_width  = $option_array['optimal_width'];
		$optimal_height = $option_array['optimal_height'];
		
		
		$this->image_resized = imagecreatetruecolor($optimal_width, $optimal_height);
		imagecopyresampled($this->image_resized, $this->image, 0, 0, $offset['x'], $offset['y'], $optimal_width, $optimal_height, $this->width, $this->height);

		if ($option == self::CROP) {
			$this->crop($optimal_width, $optimal_height, $new_width, $new_height);
		}
			
		
	}
	
	private function get_dimensions($new_width, $new_height, $option)
	{

	   switch ($option)
		{
			case self::EXACT:
				$optimal_width = $new_width;
				$optimal_height= $new_height;
				break;
			case self::PORTRAIT:
				$optimal_width = $this->get_size_by_fixed_height($new_height);
				$optimal_height= $new_height;
				break;
			case self::LANDSCAPE:
				$optimal_width = $new_width;
				$optimal_height= $this->get_size_by_fixed_width($new_width);
				break;
			case self::AUTO:
				$option_array = $this->get_size_by_auto($new_width, $new_height);
				$optimal_width = $option_array['optimal_width'];
				$optimal_height = $option_array['optimal_height'];
				break;
			case self::CROP:
				$option_array = $this->get_optimal_crop($new_width, $new_height);
				$optimal_width = $option_array['optimal_width'];
				$optimal_height = $option_array['optimal_height'];
				break;
		}
		return array('optimal_width' => $optimal_width, 'optimal_height' => $optimal_height);
	}
		
	private function get_size_by_fixed_height($new_height)
	{
		$ratio = $this->width / $this->height;
		$new_width = $new_height * $ratio;
		return $new_width;
	}

	private function get_size_by_fixed_width($new_width)
	{
		$ratio = $this->height / $this->width;
		$new_height = $new_width * $ratio;
		return $new_height;
	}

	private function get_size_by_auto($new_width, $new_height)
	{
		if ($this->height < $this->width)
		// *** Image to be resized is wider (landscape)
		{
			$optimal_width = $new_width;
			$optimal_height= $this->get_size_by_fixed_width($new_width);
		}
		elseif ($this->height > $this->width)
		// *** Image to be resized is taller (portrait)
		{
			$optimal_width = $this->get_size_by_fixed_height($new_height);
			$optimal_height= $new_height;
		}
		else
		// *** Image to be resizerd is a square
		{
			if ($new_height < $new_width) {
				$optimal_width = $new_width;
				$optimal_height= $this->get_size_by_fixed_width($new_width);
			} else if ($new_height > $new_width) {
				$optimal_width = $this->get_size_by_fixed_height($new_height);
				$optimal_height= $new_height;
			} else {
				// *** Sqaure being resized to a square
				$optimal_width = $new_width;
				$optimal_height= $new_height;
			}
		}

		return array('optimal_width' => $optimal_width, 'optimal_height' => $optimal_height);
	}

	private function get_optimal_crop($new_width, $new_height)
	{

		$heightRatio = $this->height / $new_height;
		$widthRatio  = $this->width /  $new_width;

		if ($heightRatio < $widthRatio) {
			$optimalRatio = $heightRatio;
		} else {
			$optimalRatio = $widthRatio;
		}

		$optimal_height = $this->height / $optimalRatio;
		$optimal_width  = $this->width  / $optimalRatio;

		return array('optimal_width' => $optimal_width, 'optimal_height' => $optimal_height);
	}
	
	private function crop($optimal_width, $optimal_height, $new_width, $new_height)
	{
		$crop_start_x = ( $optimal_width / 2) - ( $new_width /2 );
		$crop_start_y = ( $optimal_height/ 2) - ( $new_height/2 );
		
		$crop = $this->image_resized;
		//imagedestroy($this->image_resized);

		$this->image_resized = imagecreatetruecolor($new_width , $new_height);
		imagecopyresampled($this->image_resized, $crop , 0, 0, $crop_start_x, $crop_start_y, $new_width, $new_height , $new_width, $new_height);
	}
    /**
     * Saves a new resized image
     *
     * @param string $imageQuality="100" - The quality of the image
     * @param string $save_as=NULL - Use to save as a copy without overwriting the current Image
     * @return string
     **/
	public function save_image($imageQuality="100", $save_as=NULL)
	{
		$save_path = ( isset($save_as) ) ? $save_as : $this->source;
		$save_path =  str_replace( '\\' , '/', $save_path); 
		$extension = strrchr($save_path, '.');
		$extension = strtolower($extension);

		switch($extension)
		{
			case '.jpg':
			case '.jpeg':
				if (imagetypes() & IMG_JPG) {
					imagejpeg($this->image_resized, $save_path, $imageQuality);
				}
				break;

			case '.gif':
				if (imagetypes() & IMG_GIF) {
					imagegif($this->image_resized, $save_path);
				}
				break;

			case '.png':
				// *** Scale quality from 0-100 to 0-9
				$scaleQuality = round(($imageQuality/100) * 9);

				// *** Invert quality setting as 0 is best, not 9
				$invertScaleQuality = 9 - $scaleQuality;

				if (imagetypes() & IMG_PNG) {
					imagepng($this->image_resized, $save_path, $invertScaleQuality);
				}
				break;
			default:
				array_push( $this->errors, 'You are trying to upload a '.$this->type.'.'.' Images must be either GIF, JPEG or PNG.' );
				return;
				break;
		}

		imagedestroy($this->image_resized);
	}

    /**
     * checks to see if file is a jpg, jpeg, gif, or png
     *
     * @param string $filename - the file to check
     * @return boolean
     **/
	public static function is_image($filename)
	{
		return preg_match('/[.](jpg)|(jpeg)|(gif)|(png)$/i', $filename);
	}


}


?>
