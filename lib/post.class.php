<?php
	/**
	* 
	*/
	class Post extends Content
	{										
		
		/**
		 * Checks to see if the post is in a Category
		 *
		 * @param int | string $category - The category
		 * @return boolean
		 **/
		public function is_in_category($category)
		{
			if(empty($this->category_id)){
				return false;
			}else{
				if(is_numeric($category)){
					return $category == $this->category_id;
				}elseif( is_string ($category) ){
					$sql = "SELECT id FROM categories WHERE title = '$category'";				
					$result = Category::find_by_sql($sql);
					return ($result > 0 && $result[0]->id == $this->category_id) ? true : false;
				}
			
			}
		}

		/**
		 * Retuns the current category name
		 * @return string
		 **/
		public function category_name(){
			$cat = Category::find_by_id($this->category_id);
			return $cat->title;	
		}
		/**
		 * Returns the next created post
		 *
		 * @param string | number $category = NULL - restrict posts to specified category
		 * @return Post
		 **/
		public function get_next_post($category=NULL)
		{
			return $this->get_adjacent_post($category, false);
		}
		/**
		 * Returns the previoulsly created post
		 *
		 * @param string | number $category = NULL - restrict posts to specified category
		 * @return Post
		 **/
		public function get_previous_post($category=NULL)
		{
			return $this->get_adjacent_post($category, true);
		}
		/**
		 * Returns the an adjacently created post
		 *
		 * @param string | number $category = NULL - restrict posts to specified category
		 * @param boolean $previous = true - true will return the next post false will return the previous
		 * @return Post
		 **/
		public function get_adjacent_post($category=NULL, $previous=false )
		{
			$created_str = ($previous) ? " AND created > '$this->created'" : " AND created < '$this->created'";
			
			$sql = "SELECT * FROM content";
			$sql .= " WHERE type ='".ContentType::POST."'";
			$sql .= " AND status ='".ContentStatus::PUBLISHED."'";
			$sql .= $created_str;
			if( isset($category) ){
				if(is_numeric($category)){
					$sql .= " AND category_id = $category";
				}elseif( is_string ($category) ){
					$sql .= " AND category_id = (SELECT id FROM categories WHERE title = '$category')";				
				}
			}
			$sql .= " ORDER BY created";
			$sql .= ($previous) ? ' ASC' : ' DESC'; 
			$sql .= " LIMIT 1";
			$result = Post::find_by_sql($sql);
			if( empty($result)) {
				$sql=str_replace($created_str, '', $sql);
				$result = Post::find_by_sql($sql);
				return !empty($result) ? array_pop($result) : false;
			}else{
                return array_pop($result);
			}
		}
		/**
		* Returns an array of content by
		*
		* @param int id - category id
		* @param string $order = "created DESC" - The field name by which to oder the results. Use DESC or ASC to swap order
		* @param $limit int - limit the amount of content to retrieve
		* @param $offset int - offset of the first row to return
		* @param $return_count boolen - If true will return a row count instead of an array of Post object. Usefull for doing pagination.
		* @return array
		**/
		public static function find_by_category_id($id, $order="created DESC", $limit=NULL, $offset=NULL, $return_count=false)
		{
			$select = ($return_count) ? "COUNT(*) as count": "*";
			$sql = "SELECT $select FROM content";
			$sql .= " WHERE type ='".ContentType::POST."'";
			$sql .= " AND status ='".ContentStatus::PUBLISHED."'";
			$sql .= " AND category_id={$id}";
			if(isset($order)){
				$sql .= " ORDER BY ".$order;
			}
			if(isset($offset) && isset($limit)){
				$sql .= " LIMIT ".$offset.", ".$limit;
			}elseif(isset($limit)){
				$sql .= " LIMIT ".$limit;
			}
			$result = Post::find_by_sql($sql);
			if($return_count) $result = $result[0]->count;
			return !empty($result) ? $result : false;
		}
		/**
		* Returns an array of Posts by category name
		*
		* @param string | array $titles - The Category title as a string or an array of strings
		* @param string $order = "created DESC" - The field name by which to oder the results. Use DESC or ASC to swap order
		* @param $limit int - limit the amount of content to retrieve
		* @param $offset int - offset of the first row to return
		* @param $return_count boolen - If true will return a row count instead of an array of Post object. Usefull for doing pagination.
		* @return array
		**/
		public static function find_by_category_title($titles, $order="created DESC", $limit=NULL, $offset=NULL, $return_count=false)
		{
			$select = ($return_count)?"COUNT(*) as count": "content.".implode(", content.", parent::$db_fields);
			$sql = "SELECT $select FROM content, categories";
			$sql .= " WHERE content.type ='".ContentType::POST."'";
			$sql .= " AND content.status ='".ContentStatus::PUBLISHED."'";
			$sql .= " AND content.category_id=categories.id";
			if(is_string($titles)){
				$sql .= " AND categories.title='$titles'";
			} elseif( is_array($titles) ) {
				$sql .= " AND categories.title IN ('".implode( "','" , $titles )."')";
			}
			if(isset($order)){
				$sql .= " ORDER BY ".$order;
			}
			if(isset($offset) && isset($limit)){
				$sql .= " LIMIT ".$offset.", ".$limit;
			}elseif(isset($limit)){
				$sql .= " LIMIT ".$limit;
			}
			$result = Post::find_by_sql($sql);
			if($return_count) $result = $result[0]->count;
			return !empty($result) ? $result : false;
		}
		
		/**
		* Returns an array content excluding provided categories ids
		*
		* @param categories array - categories to exclude
		* @param string $order = "created DESC" - The field name by which to order the results. Use DESC or ASC to swap order
		* @param integer $limit - limit the amount of content to retrieve
		* @param integer $offset - offset of the first row to return
		* @param $return_count boolen - If true will return a row count instead of an array of Post object. Usefull for doing pagination.
		* @return array
		**/
		public static function find_except_in_categories( $categories, $order='created DESC', $limit=NULL, $offset=NULL, $return_count=false)
		{
			$select = ($return_count) ? "COUNT(*) as count": "*";
			$sql = "SELECT $select FROM content";
			$sql .= " WHERE type ='".ContentType::POST."'";
			$sql .= ' AND category_id NOT IN ('.implode("," , $categories ).")";
			$sql .= " AND status ='".ContentStatus::PUBLISHED."'";
			if(isset($order)){
				$sql .= " ORDER BY ".$order;
			}
			if(isset($offset) && isset($limit)){
				$sql .= " LIMIT ".$offset.", ".$limit;
			}elseif(isset($limit)){
				$sql .= " LIMIT ".$limit;
			}
			
			$result = Post::find_by_sql($sql);
			if($return_count) $result = $result[0]->count;
			return !empty($result) ? $result : false;
		}				
		
		/**
		* Returns an array content linked to specified tag
		*
		* @param string | integer $tag - A Tag id or tag name
		* @param string $order = "created DESC" - The field name by which to order the results. Use DESC or ASC to swap order
		* @param integer $limit - limit the amount of content to retrieve
		* @param integer $offset - offset of the first row to return
		* @param $return_count boolen - If true will return a row count instead of an array of Post object. Usefull for doing pagination.
		* @return array
		**/
		public static function find_by_tag($tag, $order='created DESC', $limit=NULL, $offset=NULL, $return_count=false ) 
		{
			$select = ($return_count) ? "COUNT(*) as count": "*";
			$sql = "SELECT $select FROM content";
			$sql .= " JOIN content_x_tags ON content.id = content_x_tags.content_id";
			$sql .= " JOIN tags ON tags.id = content_x_tags.tag_id";
			if( is_numeric($tag) ){
				$sql .= " WHERE tags.id = $tag";
			}elseif( is_string($tag) ){
				$sql .= " WHERE tags.tag = '$tag'";
			}
			$sql .= " AND status='". ContentStatus::PUBLISHED."'"; 
			$sql .= " AND type='".ContentType::POST."'";
			if(isset($order)){
				$sql .= " ORDER BY ".$order;
			}
			if(isset($offset) && isset($limit)){
				$sql .= " LIMIT ".$offset.", ".$limit;
			}elseif(isset($limit)){
				$sql .= " LIMIT ".$limit;
			}
			$result= Post::find_by_sql($sql);
			if($return_count) $result = $result[0]->count;
			return !empty($result) ? $result : false ;
		}

		/**
		 * Retrives all the post created on a specific month day year. 
		 *
		 * @param integer $month - The Post's month it was created in
		 * @param integer $day=NULL - The Post's day it was created in
		 * @param integer $year=NULL - The Post's year it was created in
		 * @param array | string $categories=NULL - Only retive posts of specific categories
		 * @param string $order = "created DESC" - The field name by which to order the results. Use DESC or ASC to swap order
		 * @param integer $limit - limit the amount of content to retrieve
		 * @param integer $offset - offset of the first row to return
		 * @param $return_count boolen - If true will return a row count instead of an array of Post object. Usefull for doing pagination.
		 * @return array - Array containg result Posts
		 **/
		public static function find_by_dates($month, $day=NULL, $year=NULL, $categories=NULL, $order='created DESC', $limit=NULL, $offset=NULL, $return_count=false)
		{
			$date = mktime(0, 0, 0, $month); 
			if (isset($year)){
			   $date = ( isset($day) ) ? mktime(0, 0, 0, $month, $day, $year) : mktime(0, 0, 0, $month, 1, $year); 
			};
			$date = date("Y-m-d H:i:s", $date);
			$select = ($return_count) ? "COUNT(*) as count": "content.".implode(", content.", parent::$db_fields).", categories.id";
			$sql = "SELECT $select FROM content, categories WHERE content.created BETWEEN '".$date."' AND LAST_DAY('".$date."')";
			$sql .= " AND content.type ='".ContentType::POST."'";
			$sql .= " AND content.status ='".ContentStatus::PUBLISHED."'";
			if(isset($categories)){
				$sql .= " AND content.category_id = categories.id";
				if(is_string($categories)){
					$sql .= " AND categories.title='$categories'";
				} elseif( is_array($categories) ) {
					$sql .= " AND categories.title IN ('".implode( "','" , $categories )."')";
				}
			}
			if(isset($year)){
				$sql .= " AND YEAR(content.created) = $year";
			}                                                     
			if(isset($month)){
				$sql .= " AND MONTH(content.created) = $month";
			}                                                     
			if(isset($order)){
				$sql .= " ORDER BY ".$order;
			}
			if(isset($offset) && isset($limit)){
				$sql .= " LIMIT ".$offset.", ".$limit;
			}elseif(isset($limit)){
				$sql .= " LIMIT ".$limit;
			}

			$result = Post::find_by_sql($sql);
			if($return_count) $result = $result[0]->count;
			return !empty($result) ? $result : false;
		}

		/**
		 * Retrives all the months and or years Posts have been created. This is useful when created navigation based on dates;
		 *
		 * @param integer $month - The month to search
		 * @param integer $year=NULL - The year to search
		 * @param array | string $categories=NULL - Only search Posts of specific categories
		 * @return array - An array of months all posts were created
		 **/
		public static function get_dates($month=NULL, $year=NULL, $categories=NULL )
		{
			global $database;
			$sql="SELECT DAY(content.created) AS day, MONTH(content.created) AS month, YEAR(content.created) AS year";
			$sql .= " FROM content, categories";
			$sql .= " WHERE type ='".ContentType::POST."'";
			$sql .= " AND status ='".ContentStatus::PUBLISHED."'";
			if(isset($categories)){
				$sql .= " AND content.category_id = categories.id";
				if(is_string($categories)){
					$sql .= " AND categories.title='$categories'";
				} elseif( is_array($categories) ) {
					$sql .= " AND categories.title IN ('".implode( "','" , $categories )."')";
				}
			}
			if(isset($year)){
				$sql .= " AND YEAR(content.created) = $year";
			}                                                     
			if(isset($month)){
				$sql .= " AND MONTH(content.created) = $month";
			}                                                     
			$sql .= " GROUP BY month, year";
			$sql .= " ORDER BY created";
			$result = $database->query($sql);
			$result = $result->fetchAll(PDO::FETCH_ASSOC);
			return $result;
		}

		/**
		 * Returns all the usres who have publish Posts
		 *
		 * @param array | string  -  Specify a specific category of Posts;
		 * @return array -  Returns and array of User objects
		 **/
		public static function get_authors($categories)
		{
			$sql = "SELECT * FROM users WHERE users.id IN ( ";
			$sql .= "SELECT author_id FROM content";
			$sql .= " WHERE type ='".ContentType::POST."'";
			$sql .= " AND status ='".ContentStatus::PUBLISHED."'";
			if(isset($categories)){
				$sql .= " AND content.category_id = categories.id";
				if(is_string($categories)){
					$sql .= " AND categories.title='$categories'";
				} elseif( is_array($categories) ) {
					$sql .= " AND categories.title IN ('".implode( "','" , $categories )."')";
				}
			}
			$sql .= "GROUP BY author_id )";
			$result = User::find_by_sql($sql);
			return $result;
		}

		/**
		* Returns all categories
		*
		* @return array
		**/
		public static function get_categories()
		{
			$categories = Category::find_all();
			return !empty($categories) ? $categories : false;
		}


				
	}
	
?>
