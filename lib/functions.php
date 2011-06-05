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
			die("The file {$class_name}.php could not be found.");
		}
	}
	/**
	 * Converts Unix timestamp to readable date
	 *
	 * @param $timestamp string
	 * @return string
	 **/
	function simple_date($timestamp, $use_time = true, $long=false)
	{
		$date_string = ($long) ? 'l, F jS' : 'd/m/y';
		$date_string = ($use_time)? $date_string.' G:i A':$date_string;
		return date($date_string, strtotime($timestamp) );
	}
	
	/**
	 * Remove all 0 in data string
	 *
	 * @param $marked_string string
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
     * @param $sxi:SimpleXmlIterator 
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
	 * @param $location string
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
	 * @param $message string
	 * @param $type string
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
	 * Converts string to slug
	 *
	 * @param $string string
	 * @return string
	 **/
	function slug($string)
	{
		$string = strtolower(trim($string));
		$string = preg_replace('/[^a-z0-9-]/', '-', $string);
		$string = preg_replace('/-+/', "-", $string);
		return $string;
	}
	/**
	 * Converts slug to readable title 
	 *
	 * @param $string string
	 * @return string
	 **/
	function nice_name($string)
	{
		$string = preg_replace( "/\.([^\.]+)$/", '', $string);
		$string = preg_replace('/[.|-|_]/', ' ', $string);
		return ucwords($string);
	}
	/**
	 * Returns the url of the calling page
	 *
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
	*
	* @param $query_strings array - a group of key values to append;
	*/	
	function append_query_string($query)
	{
		$url = current_page_url();
		
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
	 * Sorts array by sub-key
	 *
	 * @param $a array
	 * @param $subkey string
	 * @return void
	 **/
	
	function sort_on($a,$subkey) {
		foreach($a as $k=>$v) {
			$b[$k] = strtolower($v[$subkey]);
		}
		asort($b);
		foreach($b as $key=>$val) {
			$c[] = $a[$key];
		}
		return $c;
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
	* Translates a camel case string into a string with underscores (e.g. firstName -&gt; first_name)
	* Thanks to: http://www.paulferrett.com/
	*
	* @param	string	$str 	String in camel case format
	* @return	string	$str 	Translated into underscore format
	*/
	function from_camel_case($str) {
		$str[0] = strtolower($str[0]);
		$func = create_function('$c', 'return "_" . strtolower($c[1]);');
		return preg_replace_callback('/([A-Z])/', $func, $str);
	}

	/**
	* Translates a string with underscores into camel case (e.g. first_name -&gt; firstName)
	* Thanks to: http://www.paulferrett.com/
	*
	* @param 	string	$str 						String in underscore format
	* @param 	bool	$capitalise_first_char		If true, capitalize the first char in $str
	* @return 	string	$str 						translated into camel caps
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
	 * @param number
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
	 *
	 * @param $dir string
	 **/
    function deleteDirectory($dir) { 
        if (!file_exists($dir)) return true; 
        if (!is_dir($dir) || is_link($dir)) return unlink($dir); 
        foreach (scandir($dir) as $item) { 
            if ($item == '.' || $item == '..') continue; 
            if (!deleteDirectory($dir . "/" . $item)) { 
                chmod($dir . "/" . $item, 0777); 
                if (!deleteDirectory($dir . "/" . $item)) return false; 
            }; 
        } 
        return rmdir($dir); 
    } 
    
	/**
	 * Includes a page layouts
	 *
	 * @param $template string
	 * @param $directory string
	 **/
	function include_layout($layout, $directory = NULL) {
        if (!isset($directory) ) {
            $directory = theme_directory(true);
        }
		include($directory.DS.$layout);
	}
	/**
	 * Includes a template
	 *
	 * @param $template string
	 * @param $directory string
	 **/
	function include_template($template, $directory = NULL) {
        if (!isset($directory) ) {
            $directory = theme_directory(true).DS.'templates';
        }
		include($directory.DS.$template);
	}
    /**
     * Get the directory for selected theme
     *
     * @param $filepath boolean - return the filepath or url
     * @return string
     **/
    function theme_directory($filepath=false)
    {
        global $options;
        return ($filepath) ? THEMES_PATH.DS.$options->theme  : BASE_URL.'/themes/'.$options->theme;
    }
    /**
     * Gets the url to the stylesheet for the selected theme
     *
     * @return string
     **/
    function css($filepath=false)
    {
        return css_directory($filepath).'/style.css';
    }
    /**
     * Get the stylesheet directory for selected theme
     *
     * @return string
     **/
    function css_directory($filepath=false)
    {
        return theme_directory($filepath).'/css';
    }
    /**
     * Get the stylesheet directory for selected theme
     *
     * @return string
     **/
    function image_directory($filepath=false)
    {
        return theme_directory($filepath).'/images';
    }
    /**
     * Get the javascript directory for selected theme
     *
     * @return string
     **/
    function js_directory($filepath=false)
    {
        return theme_directory($filepath).'/js';
    }
    /**
     * Get the template directory for selected theme
     *
     * @return string
     **/
    function template_directory($filepath=false)
    {
        return theme_directory($filepath).'/templates';
    }

?>
