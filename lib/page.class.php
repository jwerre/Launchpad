<?php
	/**
	* 
	*/
	class Page extends Post
	{
		/**
		* Returns the Page's child pages
		*
		* @return array
		**/
		public function children()
		{
			$sql = "SELECT * FROM ". static::$table_name ." WHERE parent_id={$this->id}" . " ORDER BY weight ASC";
			$result = static::find_by_sql($sql);
			return $result;
		}
		
		/**
		* Returns the Page's sibling pages
		*
		* @return array
		**/
		public function siblings()
		{
			if(!empty($this->parent_id)){
				$sql = "SELECT * FROM ". static::$table_name ." WHERE parent_id={$this->parent_id}" . " AND parent_id != 0 ORDER BY weight ASC";
				$result = static::find_by_sql($sql);
			}else{
				$result = false;
			}
			return $result;
		}
		
		/**
		 * Retrives all the files in the templates directory
		 *
		 * @return array
		 **/
		
		public static function get_templates()
		{	
			$pattern = theme_directory(true)."/templates/*.template.php";
			$templates = array();
            foreach (glob($pattern) as $template) {
		        $templates[] = pathinfo($template, PATHINFO_BASENAME);
            }
			return $templates;
		}
		
		public static function clean_template_name($name)
		{
			$name = basename($name,".template.php");
			$name = preg_replace('/[^a-z0-9]/', ' ', $name);
			return ucwords($name);
			
		}
		
		/**
		 * Outputs the title, body and or template
		 *
		 * @return null
		 **/
		public function output_page_data()
		{
			if(!empty($this->title))
				echo $this->title;
			
			if(!empty($this->body))
				echo stripslashes($this->body);
			
			if(!empty($this->template)) 
				include($this->template); 
		}
		
	}
	
	
?>
