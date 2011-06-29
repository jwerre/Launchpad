<?php

/**
 * Base class for uploading and modifying of media files
 *
 * @author Jonah Werre <jonahwerre@gmail.com>
 * @version 1.0
 * @copyright Jonah Werre <jonahwerre@gmail.com>, 28 June, 2011
 * @package DatabaseObject
 **/
class Media extends DatabaseObject
{
	/**
	 * The name of the database table
	 * @var string
	 **/
	protected static $table_name = "media";
	/**
	 * The name of the database fields in $table_name
	 * @var array
	 **/
	protected static $db_fields = array( 'id', 'filename', 'type', 'size', 'caption', 'description', 'author_id');
	/**
	 * temporary path the the file
	 * @var string
	 **/
	private $temp_path;
	/**
	 * The unique id of the image
	 * @var string
	 **/
	public $id;
	/**
	 * The media full filename
	 * @var string
	 **/
	public $filename;
	/**
	 * The mime type of media 
	 * @var string
	 **/
	public $type;
	/**
	 * The size of the media file
	 * @var string
	 **/
	public $size;
	/**
	 * A caption for the media file
	 * @var string
	 **/
	public $caption;
	/**
	 * A description for the media file
	 * @var string
	 **/
	public $description;
    /**
     * The uniqie id of the User who created the Media
     * @var intiger
     **/
	public $author_id;
	/**
	 * A collection of errors created durring Media uploading
	 * @var string
	 **/
	public $errors = array();
	/**
	 * A collection of predefined errors
	 * @var string
	 **/
	public $upload_errors = array(
		UPLOAD_ERR_OK 			=> "No errors",
		UPLOAD_ERR_INI_SIZE  	=> "Larger than upload_max_filesize",
		UPLOAD_ERR_FORM_SIZE 	=> "Larger than MAX_FILE_SIZE",
		UPLOAD_ERR_PARTIAL 		=> "Partial upload",
		UPLOAD_ERR_NO_FILE 		=> "No file",
		UPLOAD_ERR_NO_TMP_DIR 	=> "No temporary directory",
		UPLOAD_ERR_CANT_WRITE 	=> "Can't write to disk",
		UPLOAD_ERR_EXTENSION 	=> "File upload stopped by extension"
	);
		
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
			$this->filename = UPLOADS_URL."/".strtolower( str_replace(" ","_", basename($file['name']) ) );
			$this->type = $file['type'];
			$this->size = $file['size'];
			return true;
		}
	}
	
	/**
	* Saves image
	* @return boolean
	*/
	public function save()
	{
		if( isset($this->id) ){
			return $this->update();
		}else{
			if(!empty($this->errors)) { return false; }
			// check and see if filename and temp_path exists
			if(empty($this->filename) || empty($this->temp_path)) {
				array_push( $this->errors, "The file location was not available.");
				return false; 
			}
			
			$target_path = $this->file_path();
			// make sure the file exists
			if(file_exists($target_path)){
				array_push( $this->errors, "The file {$this->name_of_file()} already exists");
				return false;
			}
			// move the file to permanent location
			if ( move_uploaded_file($this->temp_path, $target_path) ) {
				if($this->create()){
					unset($this->temp_path);
					return true;
				}
			} else {
				array_push( $this->errors, "file ulpoad failed");
				return false;
			}
		}
	}
	
	
	/**
	* Removes DB entry and file on server.
	* @return boolean
	*/
	public function destroy() {
		$this->errors = array();
		if($this->delete()) {
			
			$target_path = $this->file_path();
			
			if (file_exists($target_path)) {
				
				if (unlink($target_path)) {
					array_push( $this->errors, "The file at $target_path was deleted");
					return true;
				}else{
					array_push( $this->errors, "Could not delete file at $target_path");
					return false;
				}
				
			}else{
				array_push( $this->errors, "Could not find the file at $target_path");
				return false;
			}
		
		}else{
			array_push( $this->errors, "No file reference for $target_path. No Files were deleted");
			return false;
		}
		
	}
	
	/**
	 * Guesses the content type of a file by looking for certain magic byte sequences at specific positions within the file.
	 * If Magic File isn't available mime is guessed based on the file extention
	 *
	 * @return string 
	 **/
	public function force_mimetype()
	{
		if(function_exists('finfo_open')){
			$file = finfo_open(FILEINFO_MIME_TYPE);
			$info = finfo_file($file, $this->file_path() );
			$this->type = $info;
			finfo_close($file);		
		}else{
			$mime_types = array(
				'txt' => 'text/plain', 'htm' => 'text/html', 'html' => 'text/html', 'php' => 'text/html', 'css' => 'text/css', 'js' => 'application/javascript', 'json' => 'application/json', 'xml' => 'application/xml', 'swf' => 'application/x-shockwave-flash', 'flv' => 'video/x-flv',
				// images
				'png' => 'image/png', 'jpe' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'jpg' => 'image/jpeg', 'gif' => 'image/gif', 'bmp' => 'image/bmp', 'ico' => 'image/vnd.microsoft.icon', 'tiff' => 'image/tiff', 'tif' => 'image/tiff', 'svg' => 'image/svg+xml', 'svgz' => 'image/svg+xml',
				// archives
				'zip' => 'application/zip', 'rar' => 'application/x-rar-compressed', 'exe' => 'application/x-msdownload', 'msi' => 'application/x-msdownload', 'cab' => 'application/vnd.ms-cab-compressed',
				// audio/video
				'mp3' => 'audio/mpeg', 'qt' => 'video/quicktime', 'mov' => 'video/quicktime','mp4' => 'video/quicktime','m4v' => 'video/quicktime','ogg' => 'audio/ogg', 'oga' => 'audio/ogg','ogv' => 'video/ogg',
				// adobe
				'pdf' => 'application/pdf', 'psd' => 'image/vnd.adobe.photoshop', 'ai' => 'application/postscript', 'eps' => 'application/postscript', 'ps' => 'application/postscript',
				// ms office
				'doc' => 'application/msword', 'rtf' => 'application/rtf', 'xls' => 'application/vnd.ms-excel', 'ppt' => 'application/vnd.ms-powerpoint',
				// open office
				'odt' => 'application/vnd.oasis.opendocument.text', 'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
			);
			$ext = strtolower( pathinfo($this->filename, PATHINFO_EXTENSION) );
			if (array_key_exists($ext, $mime_types)) {
				$this->type = $mime_types[$ext];
			} else {
				$this->type = 'application/octet-stream';
			}
		}
		
	}
	
	/**
	* Reurns author of media by author_id
	* @return User
	**/
	public function author()
	{
		$user = User::find_by_id( $this->author_id );
		return !empty($user) ? $user : false;
	}
	
	/**
	* Returns the file path of the media file.
	* @return string
	*/
	public function file_path()
	{
		$path = substr( $this->filename, strlen(BASE_URL) );
		$path = str_replace('/', DS, SITE_ROOT.'/'.$path);
		return $path;  
	}	
	/**
	* Returns the base name of the file without directories.
	* @return string
	*/
	public function name_of_file()
	{
		$path = substr( $this->filename, strlen(UPLOADS_URL) );
		return ltrim($path, '/');  
	}	
    /**
     * Returns the type of media
     * @return string eg: image, video, audio, text
     **/
    public function simple_type()
    {
        return strstr($this->type, '/', true);
    }
	
	/**
	* Returns returns the extension type of media.
	* @return string
	*/
	public static function extension($filename) {
		//$result_string = substr($this->type,0,strpos($this->type,"/")+strlen("/")-1); 
		return strtolower( pathinfo($filename, PATHINFO_EXTENSION) );
	}
	
	/**
	* Returns the size of the file as string.
	* @return string
	*/
	public function size_as_text() {
		if($this->size < 1024) {
			return "{$this->size} bytes";
		} elseif($this->size < 1048576) {
			$size_kb = round($this->size/1024);
			return "{$size_kb} KB";
		} else {
			$size_mb = round($this->size/1048576, 1);
			return "{$size_mb} MB";
		}
	}
    
    /**
    * Returns last created media
	* @param integer $limit = 1 - Specifies the amount of media files to return
    * @return Media
    **/
    public static function find_last_created($limit = 1)
    {
        $sql = "SELECT * FROM " . self::$table_name; 
        $sql .= " ORDER BY created DESC LIMIT ".$limit;
        $result = static::find_by_sql($sql);
        return !empty($result) ? $result : false;
    }
	
}

?>
