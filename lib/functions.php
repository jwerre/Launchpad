<?php
	
	/**
	 * Automatically load class if not found -- NOT TESTED!
	 * 
	 **/	
	function __autoload($class_name) {
		$class_name = from_camel_case( $class_name );
		$path = LIB_PATH.DS."{$class_name}.class.php";
		$alt_path = LIB_PATH.DS.'helper'.DS."{$class_name}.class.php";
        
        if( file_exists($path) ) {
			require_once($path);
		} elseif( file_exists($alt_path) ) {
			require_once($alt_path);
		} else {
			die("The file $class_name could not be found at:<br>$path <em>or</em> $alt_path.");
		}
	}
	/**
	 * Converts Unix datetime to readable date
	 * @param $datetime string -  A unix datetime
	 * @param $long boolean - Use a longer format (%A, %B %e, %Y) else a shoter one (%m-%d-%Y)  
	 * @param $show_time boolean -  Append the time
	 * @return string
	 **/
	function simple_date($datetime, $long=false, $show_time=false)
	{
		$date_string = ($long) ? '%A, %B %e, %Y' : '%m-%d-%Y';
		if($show_time && preg_match('/[0-9][0-9]:[0-9][0-9]:[0-9][0-9]/', $datetime)){
			$date_string .= ' at %I:%M %p';
		}
		return strftime($date_string, strtotime($datetime) );
	}
	
	/**
	 * Remove all 0 in data string
	 *
	 * @param $marked_string string - The string to remove the "0" from; 
	 * @return string
	 **/	
	function strip_zeros_from_date( $marked_string="" ) {
		// first remove the marked zeros
		$no_zeros = str_replace('*0', '', $marked_string);
		// then remove any remaining marks
		$cleaned_string = str_replace('*', '', $no_zeros);
		return $cleaned_string;
	}
    /**
     * Converts xml to array
     * 
     * @param $filename - path to xml file
     * @return array
     **/
    function xml2array($filename){
        $sxi = new SimpleXmlIterator($filename, null, true);
        return sxiToArray($sxi);
    }

    /**
     * Converts SimpleXmlIterator to array
     * 
     * @param $sxi:SimpleXmlIterator - A SimpleXmlIterator to conver to array
     * @return array
     **/
    function sxiToArray($sxi){
        $a = array();
        for( $sxi->rewind(); $sxi->valid(); $sxi->next() ) {
        if(!array_key_exists($sxi->key(), $a)){
          $a[$sxi->key()] = array();
        }
        if($sxi->hasChildren()){
          $a[$sxi->key()][] = sxiToArray($sxi->current());
        }
        else{
          $a[$sxi->key()][] = strval($sxi->current());
        }
        }
        return $a;
    }
    
	/**
	 * Redirects browser to a new url
	 *
	 * @param string $location - A location to redirect to. Must be called before any output
	 * @return void
	 **/
	function redirect_to( $location = NULL ) {
		if ($location != NULL) {
			header("Location: {$location}");
			exit;
		}
	}
	/**
	 * Returns an error message
	 *
	 * @param $message string - A message to output
	 * @param $type string - A class name for wrapper
	 * @return string
	 **/
	function output_message($message="", $type="success") {
		if (!empty($message)) { 
			return "<div class=\"message {$type}\">{$message}</div>";
		} else {
			return "";
		}
	}

	/**
	 * Makes a srting a url friendly slug
	 *
	 * @param string $phrase - The phrase to convert to a url friendly string
	 * @param number $max=2000 - Max length of the slug
	 * @return string
	 **/
	function slug($phrase, $max=2000)
	{
		$result = strtolower($phrase);
		$result = preg_replace("/[^a-z0-9\s-]/", "", $result);
		$result = trim(preg_replace("/[\s-]+/", " ", $result));
		$result = trim(substr($result, 0, $max));
		$result = preg_replace("/\s/", "-", $result);
		return $result;
	}
	/**
	 * Converts slug to readable title 
	 *
	 * @param $slug string - Slug to create spaces in
	 * @return string
	 **/
	function nice_name($slug)
	{
		$slug = preg_replace( "/\.([^\.]+)$/", '', $slug);
		$slug = preg_replace('/[.|-]/', ' ', $slug);
		return ucwords($slug);
	}
	/**
	 * Returns the url of the current page
	 * @return string
	 **/	
	function current_page_url()
	{
		$pageURL = 'http://';
		
		if ($_SERVER["SERVER_PORT"] != "80") {
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		} else {
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		return $pageURL;
	}
	
	/**
	* Appends a query string
	* @param $query_strings array - a group of key values to append;
	* @return string
	*/	
	function append_query_string($query, $url=NULL )
	{
		$url = (!isset($url)) ? current_page_url() : $url;
		$url = parse_url($url);
		if(isset($url['query'])){
			parse_str($url['query'], $output);
			$query = array_merge( $output, $query);
		}
		$url = $url['scheme'].'://'.$url['host'].$url['path'];
		if(!empty($query)){
		   $url.= "?".http_build_query($query);	
		}
		return $url; 		
	}
	
	/**
	 * Sorts the elements in an array based on a field in the array
	 *
	 * @param $array array - The array to sort
	 * @param $subkey string - A string that identifies a field in an element of the Array to be used as the sort value.
	 * @return array
	 **/
	
	function sort_on($array,$subkey) {
		foreach($array as $key=>$value) {
			$new[$key] = strtolower($value[$subkey]);
		}
		asort($new);
		foreach($new as $key=>$value) {
			$result[] = $array[$key];
		}
		return $result;
	}
	/**
	 * Returns the last value in an associative array
	 *
	 * @param array $assoc - The associative array to return last value from
	 * @return array
	 **/
	function last_in_assoc( $assoc ) {
		foreach($assoc as $key => $value) {
			if($value == end($assoc)) {
				return array($key=>$value);
			}
		}
	}	
	
	/**
	 * Checks to see if string begins with search string
	 *
	 * @param $string string
	 * @param $search string
	 * @return boolean
	 **/
	function string_begins_with($string, $search)
	{
	    return (strncmp($string, $search, strlen($search)) == 0);
	}

	/**
	* Translates a camel case string into a string with a specified character between words (e.g. firstName -&gt; first_name)
	* Thanks to: http://www.paulferrett.com/
	*
	* @param string $str -  String in camel case format
	* @param string $delimiter - String to place between words
	* @return string - Translated into underscore format
	*/
	function from_camel_case($str, $delimiter="-") {
		$str[0] = strtolower($str[0]);
		$func = create_function('$c', 'return $delimiter . strtolower($c[1]);');
		return preg_replace_callback('/([A-Z])/', $func, $str);
	}

	/**
	* Translates a string with underscores into camel case (e.g. first_name -&gt; firstName)
	* Thanks to: http://www.paulferrett.com/
	*
	* @param string $str - String in underscore format
	* @param  bool $capitalise_first_char = false - If true, capitalize the first char in $str
	* @return string
	*/
	function to_camel_case($str, $capitalise_first_char = false) {
		if($capitalise_first_char) {
			$str[0] = strtoupper($str[0]);
		}
		$func = create_function('$c', 'return strtoupper($c[1]);');
		return preg_replace_callback('/_([a-z])/', $func, $str);
	}
	
	/**
	 * converts file size to text
	 *
	 * @param number $size = The size of file
	 * @return string
	 **/
	
	function size_as_text($size) {
		if($size < 1024) {
			return "{$size} bytes";
		} elseif($size < 1048576) {
			$size_kb = round($size/1024);
			return "{$size_kb} KB";
		} else {
			$size_mb = round($size/1048576, 1);
			return "{$size_mb} MB";
		}
	}
	
	/**
	 * recursivly deletes a direcory
	 * @param $dir string - The directoy to delete
	 * @return boolean
	 **/
    function delete_directory($dir) { 
		if (!file_exists($dir)){
			return true;
	   } 
		if (!is_dir($dir) || is_link($dir)){
			return unlink($dir);
		} 
		
        foreach (scandir($dir) as $item) { 
            if ($item == '.' || $item == '..') continue; 
            if (!delete_directory($dir . "/" . $item)) { 
                chmod($dir . "/" . $item, 0777); 
                if (!delete_directory($dir . "/" . $item)) return false; 
            }; 
        } 
        return rmdir($dir); 
    } 
    
	/**
	 * Includes a page layouts
	 * @param string $layout - Name of the php file to include
	 * @param string $directory = NULL - An alternate directory (default is current theme directory)
	 **/
	function include_layout($layout, $directory = NULL) {
        if (!isset($directory) ) {
            $directory = theme_directory(true);
        }
		include($directory.DS.$layout);
	}
	/**
	 * Includes a widget
	 * @param string $widget - Name of the php file in the wigets directory
	 **/
	function include_widget($widget, $directory = NULL) {
		include_layout($widget, WIDGETS_PATH);
	}
	/**
	 * Includes a template
	 * @param string $template - Name of the template.php file to include
	 * @param string $directory = NULL - An alternate directory (default is current template directory)
	 **/
	function include_template($template, $directory = NULL) {
        if (!isset($directory) ) {
            $directory = theme_directory(true).DS.'templates';
        }
		include($directory.DS.$template);
	}
    /**
     * Get the directory for selected theme
     * @param boolean $filepath = false - return the filepath or url
     * @return string
     **/
    function theme_directory($filepath=false)
    {
        global $options;
        return ($filepath) ? THEMES_PATH.DS.$options->theme  : BASE_URL.'/themes/'.$options->theme;
    }
    /**
     * Gets the url to the stylesheet for the selected theme
	 * @param boolean $filepath = false - If true retuns the theme's css file as a file path rather than a url
     * @return string
     **/
    function css($filepath=false)
    {
        return css_directory($filepath).'/style.css';
    }
    /**
     * Get the stylesheet directory for selected theme
	 * @param boolean $filepath = false - If true retuns the css directory as a file path rather than a url
     * @return string
     **/
    function css_directory($filepath=false)
    {
        return theme_directory($filepath).'/css';
    }
    /**
     * Get the stylesheet directory for selected theme
	 * @parma string $image_name = NULL - appends a image file name to the image directory
	 * @param boolean $filepath = false - If true retuns the image directory as a file path rather than a url
     * @return string
     **/
    function image_directory( $image_name=NULL, $filepath=false)
    {
		$result = theme_directory($filepath).'/images';
		if(isset($image_name)){
			$result .= "/".$image_name;
		}
		return $result;
    }
    /**
     * Get the javascript directory for selected theme
	 * @param boolean $filepath = false - If true retuns the js directory as a file path rather than a url
     * @return string
     **/
    function js_directory($filepath=false)
    {
        return theme_directory($filepath).'/js';
    }
    /**
     * Get the template directory for selected theme
	 * @param boolean $filepath = false - If true retuns the template directory as a file path rather than a url
     * @return string
     **/
    function template_directory($filepath=false)
    {
        return theme_directory($filepath).'/templates';
    }




	// if Magic Quotes is turned on strip all the slashes
	if(function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
		// echo "MAGIC QUOTES IS TURNED ON! --- consider turing off magic quotes in you php.ini"
		function remove_magic_quotes($array) {
			$fixed = array();
			foreach ($array as $key=>$val) {
				if (is_array($val)) {
					$fixed[stripslashes($key)] = remove_magic_quotes($val);
				} else {
					$fixed[stripslashes($key)] = stripslashes($val);
				}
			}
			return $fixed;
		}

		$_GET = remove_magic_quotes($_GET);
		$_POST = remove_magic_quotes($_POST);
		$_COOKIE = remove_magic_quotes($_COOKIE);
		$_REQUEST = remove_magic_quotes($_REQUEST);
		$_FILES = remove_magic_quotes($_FILES);
	}
	

?>
