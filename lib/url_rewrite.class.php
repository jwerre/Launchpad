<?php
	
	/**
	* Class for writing and modifyling rewrite map. It's very crude but it's a start
	*
	* TODO:: this class needs to set up the initial rewrite settings in .htdocs and web.config
	*/
	class URLRewrite
	{
		
		const APACHE = 'apache';
		const IIS = 'iis';
		
		public $page;
		public $key_prefix = ""; // for prepeding stings to url key
		
		function __construct($page)
		{
			$this->page = $page;
		}
		
		/**
		* Adds a rule to rewrite map
		*
		**/
		public function add_rule()
		{
			
			switch ( $this->server_type() ) {
				case self::APACHE:
					$this->add_apache_rule();
					break;
				case self::IIS:
					$this->add_iis_rule();
					break;
				default:
					try {
						throw new Exception("Please specify a value for the REWRITE_MAP constant in the configuration file");
					} catch(Exception $e) {
					    echo $e->getMessage();
					}
					break;
			}
		}
		
		/**
		* Remove rule from rewrite map
		*
		**/
		public function remove_rule()
		{
			
			switch ( $this->server_type() ) {
				case self::APACHE:
					$this->remove_apache_rule();
					break;
				case self::IIS:
					$this->remove_iis_rule();
					break;
				default:
					try {
						throw new Exception("Please specify a value for the REWRITE_MAP constant in the configuration file");
					} catch(Exception $e) {
					    echo $e->getMessage();
					}
					break;
			}
		}
		
		/**
		* Adds a rule to rewrite map on an IIS web server
		*
		**/
		private function add_iis_rule()
		{	
			if (file_exists(REWRITE_MAP)) {
				
				$map_name = $this->get_map_name();
				
			    $web_config = simplexml_load_file(REWRITE_MAP);
				
				$map_node = $web_config->xpath( sprintf('//rewrite/rewriteMaps/rewriteMap[@name="%s"]', $map_name) );
				$duplicate = $web_config->xpath( sprintf('//rewrite/rewriteMaps/rewriteMap[@name="%s"]/add[@value="%d"]', $map_name, $this->page->id) );
				$slug = !empty( $this->key_prefix ) ? $this->key_prefix.$this->page->slug : $this->page->slug ;
				$id = $this->page->id;
				
				if (!empty($map_node)) {
					// if rule already exitst remove so it will be updated
					if( $duplicate && !empty($duplicate) ){
						$duplicate[0]['key'] = $slug;
						$duplicate[0]['value'] = $id;
					}else{
						$new_rule = $map_node[0]->addChild('add');
						$new_rule->addAttribute('key', $slug);
						$new_rule->addAttribute('value', $id);
					}				
					return $this->save_xml($web_config); 
				}else{
					return false;
				}
				
			
			} else {
			    echo 'Failed to open '.REWRITE_MAP.'. check that the file exists and that it has read and write privileges enabled';
				return false;
			}
		}

		/**
		* Removes rule from rewrite map on an IIS web server
		*
		**/
		private function remove_iis_rule()
		{
			echo "ULRRewrite::remove_iis_rule();<br><br>";
			$map_name = $this->get_map_name();

			if (file_exists(REWRITE_MAP)) {
			    $web_config = new SimpleXMLElement(REWRITE_MAP,null,true);
				$map_node = $web_config->xpath( sprintf('//rewrite/rewriteMaps/rewriteMap[@name="%s"]/add[@value="%d"]', $map_name, $this->page->id) );
				if (!empty($map_node)) {
					$dom=dom_import_simplexml($map_node[0]);
			        $dom->parentNode->removeChild($dom);
					return $this->save_xml($web_config); 
				}else{
					return false;
				}
			} else {
			    echo 'Failed to open '.REWRITE_MAP.'. check that the file exists and that it has read and write privileges enabled';
				return false;
			}
		}
		
		/**
		* Adds a rule to rewrite map on an Apache web server
		*  -- problematic if two page titles are the same.
		*  -- maybe a better way to do this is to query to database and rebuild the file with each add
		*
		**/
		private function add_apache_rule()
		{	
			
			$delimiter = "\t\t\t"; // should use a regular expresion for any amount of white spave: [:blank:]
			$file_arr = array();
			
			if( file_exists(REWRITE_MAP) && is_writable(REWRITE_MAP) ){
				
				$file = @fopen(REWRITE_MAP, "r"); // open the file for reading only
				
				if ($file) {
				    while (($buffer = fgets($file, 4096)) !== false) {
						$temp = explode($delimiter, $buffer);
						if( !empty($temp[0]) )
							$file_arr[trim($temp[1])] = trim($temp[0]);				
				    }
				}
				
			    fclose($file);
			}
			
			$file_arr[$this->page->id] = !empty( $this->key_prefix ) ? $this->key_prefix.$this->page->slug : $this->page->slug; // add page to array
			$file = fopen(REWRITE_MAP, "w"); // open the file and truncate it for rewriting
			
			foreach ($file_arr as $id => $slug) {
				$pagedata = trim($slug).$delimiter.trim($id)."\n"; 
				fwrite($file, $pagedata);
			}
			
			fclose($file);
		    			
		}
		/**
		* Removes rule from rewrite map on an Apache web server
		* 
		**/
		private function remove_apache_rule()
		{
						
			if( file_exists(REWRITE_MAP) && is_writable(REWRITE_MAP) ){
				
				$delimiter = "\t\t\t"; // should use a regular expresion for any amount of white spave: [:blank:]
				
				$map = file(REWRITE_MAP);
				$temp_array = array();				
				foreach ($map as $value) {
					$new_value = explode($delimiter, $value ); 
					$temp_array[ trim($new_value[1]) ] = trim($new_value[0]);
				}
				
				unset($temp_array[$this->page->id]);
				
				$map = fopen(REWRITE_MAP, 'w+');
				foreach ($temp_array as $key => $value) {
					$pagedata = $value.$delimiter.$key."\n";
					fwrite($map, $pagedata);
				}
				fclose($map);
				
				return true;
			}else{
				return false;
			}
		}
		
		/*
		*  Saves XML file - used to format and save web.config files
		*/		
		private function save_xml($simple_xml)
		{
			$dom = new DOMDocument('1.0');
			$dom->preserveWhiteSpace = false;
			$dom->formatOutput = true;
			$dom->loadXML($simple_xml->asXML());
			$dom->saveXML();
			return $dom->save(REWRITE_MAP);
		}
		
		
		/*
		*  Get's the type of server REWTIRE_MAP belongs to
		*/
		private function server_type()
		{
			// could use $_SERVER['SERVER_SOFTWARE']
			// it's a little messy though, would need to parse string.
			
			if( !defined('REWRITE_MAP') ){ // exit function if no rewite map file specified
				return false;
			}else{
				return strtolower( pathinfo(REWRITE_MAP, PATHINFO_EXTENSION) ) == 'config' ? self::IIS : self::APACHE ;				
			}
			
		}
		
		/*
		*  Gets attribute name of rewrite map for IIS web.config
		*/
		private function get_map_name(){
			switch ($this->page->type) {
				case ContentType::POST:
					return 'PostIdToTitle';
					break;
				case ContentType::PAGE:
					return 'PageIdToTitle';
					break;				
				default:
					throw new Exception("Error creating rewrite map. The type {$this->page->type} unknown");
					break;
			}
		}
		
		
		
	}
	
	

	
	
	
?>
