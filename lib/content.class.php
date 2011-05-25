<?php
	/**
	* 
	*/
	class Content extends DatabaseObject
	{
		protected static $table_name = "content";
		protected static $db_fields = array( 'id', 'author_id', 'type', 'status', 'updated', 'created', 'weight', 'slug', 'title', 'body', 'parent_id', 'category_id', 'template');
		
		const EXCERPT = "<!--excerpt-->";
		
		public $id;
		public $author_id;
		public $type;
		public $status;
		public $updated;
		public $created;
		public $weight;
		public $slug;
		public $title;
		public $body;
		public $parent_id;
		public $category_id;
		public $template;		
		
		/**
		* Count number of items in table
		* @param $type string accepts ContentType::POST or ContentType::PAGE 
		* @return int
		*/
		public static function count_all()
		{
			global $database;
			$type = array_shift(func_get_args());
			if($type){
				$sql = "SELECT COUNT(*) FROM ".static::$table_name." WHERE type ='".$type."'";
				$result = $database->query($sql);
				$row = $database->fetch_array();
				return array_shift($row);
			}else{
				return false;
			}
		}
		
		/**
		* Returns all content by post type
		*
		* @return array
		**/
		public static function find_all_by_type($order='weight', $status=ContentStatus::PUBLISHED, $exclude=NULL, $include=NULL)
		{
			$sql = "SELECT * FROM " . self::$table_name; 
			$sql .= " WHERE type ='". strtolower( get_called_class() )."'";
			if (isset($exclude)) {
				$sql .= ' AND id NOT IN ('.implode("," , $exclude ).")";
			}
			if (isset($include)) {
				$sql .= ' AND id IN ('.implode("," , $include ).")";
			}
			if( isset($status) ){
				$sql .= " AND status ='".$status."'";
			}
			$sql .= " ORDER BY ".$order." ASC";

			$result = static::find_by_sql($sql);
			return !empty($result) ? $result : false;
		}

		/**
		* Returns all published content by post type
		*
		* @return array
		**/
		public static function find_published_by_type_and_id($id)
		{
			$sql = "SELECT * FROM " . self::$table_name . " WHERE id ='". $id ."' AND type ='". strtolower( get_called_class() ) . "' AND status='". ContentStatus::PUBLISHED ."' LIMIT 1";
			$result = static::find_by_sql($sql);
			return !empty($result) ? $result : false;
		}
				
		/**
		* Returns last created published post
		*
		* @return Post
		**/
		public static function find_last_created($limit = 1)
		{
			$sql = "SELECT * FROM " . self::$table_name; 
			$sql .= " WHERE status='". ContentStatus::PUBLISHED; 
			$sql .= "' AND type ='".strtolower( get_called_class() )."'";
			$sql .= " ORDER BY created DESC LIMIT ".$limit;
			$result = static::find_by_sql($sql);
			return !empty($result) ? $result : false;
		}
		
		/**
		* Find finds and entry by id
		* @param int $id
		* @return User
		*/
		public static function find_by_title($title)
		{
			$sql = "SELECT * FROM " . static::$table_name;
			$sql .= " WHERE title='{$title}'";
			$sql .= " OR slug='{$title}'";
			$result_array = static::find_by_sql($sql);
			return !empty($result_array) ? $result_array[0] : false ;
		}
		
		/**
		* Returns an excerpt from post
		*
		** @param $allow sting = '<a><em><strong><span><br><sup><sub><small><i><strike>' - a list of html tags to allow in excerpt
		* @return string
		**/
		public function excerpt($allow = '<a><em><i><strong><b><span><br><sup><sub><small><strike><abbr><cite><code>')
		{	
			
			$excerpt = explode(self::EXCERPT, $this->body);
			if(count($excerpt)>1){
				$excerpt = $excerpt[0];
			}else{
				$excerpt = substr($this->body, 0, EXCERPT_LENGTH);
				$last_space = strrpos( $excerpt, " " );
				$excerpt = substr($excerpt, 0, $last_space);
			}
			
			if(isset($allow)){
				$excerpt = strip_tags($excerpt, $allow);
			}
			
			return $excerpt;
		}
		
		/**
		* Returns author of post by author_id
		*
		* @return array
		**/
		public function author()
		{
			$user = User::find_by_id( $this->author_id );
			return !empty($user) ? $user : false;
		}
		
		/**
		* sets new tags to content
		*
		* @param $tags array - an array of strings
		* @return array
		**/
		public function set_tags( $tags )
		{
			// 1. DELETE ALL SIMBOLIC LINKS
			// Tag::clear_links_for_content($this->id);
			
			// 2. CREATE AND SAVE NEW TAGS
			for ($i=0; $i < count($tags); $i++) { 
				$tag = new Tag();
				$tag->tag = strtolower( trim($tags[$i]) );
				if( !empty($tag->tag) )
					$tag->save($this->id);
            }
            return $this->tags();
		}
        /**
         *  Deletes a tag based on it's id
         *
         *  @param $tag_id - the id of the tag to be deleted
         *  @return boolean
         **/
        public function delete_tag($tag_id)
        {
            global $database;
            $sql = "DELETE FROM content_x_tags WHERE tag_id = ? AND content_id = ?";

            $result = $database->execute( $sql, array($tag_id, $this->id) );
            if ($result) {
                $sql = "DELETE FROM tags WHERE id = ?";
                try {
                    $database->execute( $sql, array($tag_id) );
                } catch (PDOException $e ) {
                    // tag is being used by other content — not deleted.    
                }
            }
            return $result;
        }
		/**
		* Returns all tags
		*
		* @param $as_string = false - return tag names as string.
		* @param $delimiter = ", " - delimiter for string output.
		* @return array
		**/
		public function tags( $as_string = false, $delimiter = ", " )
		{
			$tags = Tag::find_by_content_id($this->id);

			if( !empty($tags) ){
				if($as_string){
					$tags_as_string = "";
					for ($i=0; $i < count($tags); $i++) { 
						$tags_as_string .= ( $i+1 != count($tags) ) ? $tags[$i]->tag . $delimiter : $tags[$i]->tag;
					}
					return $tags_as_string;
				}else{
					return $tags;
				}
			}else{
				return false;
			}
		}
		
		/**
		* Returns an array or Snippet classes for this content
		*
		* @return array
		**/
		public function snippets_complex()
		{
			$snippets = Snippet::find_by_content_id($this->id);
			return $snippets;
		}
		
		/**
		* Returns an associative array of meta data for this content
		*
		* @return array
		**/
		public function snippets()
		{
			$data = $this->snippets_complex();
			$simple = array();
			if(!empty($data)){
				foreach ($data as $data_obj => $value) {
					$simple[$value->name] = $value->value;
				}
				return $simple;
			}else{
				return false;
			}
		}
		
	}
	
	
?>
