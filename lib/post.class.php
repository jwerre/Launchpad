<?php
	/**
	* 
	*/
	class Post extends Content
	{										
		/**
		* Returns all categories
		*
		* @return array
		**/
		public static function categories()
		{
			$categories = Category::find_all();
			return !empty($categories) ? $categories : false;
		}
		
		
		/**
		* Returns an array of content by
		*
		* @param id int - category id
		* @param limit int - limit the amount of content to retrieve
		* @return array
		**/
		public static function posts_by_category_id($id, $order="created DESC", $limit=NULL)
		{
			$sql = "SELECT * FROM content";
			$sql .= " WHERE type ='".ContentType::POST."'";
			$sql .= " AND status ='".ContentStatus::PUBLISHED."'";
			$sql .= " AND category_id={$id}";
            $sql .= " ORDER BY $order";
			if(isset($limit)){
				$sql .= " LIMIT ".$limit;
			}
			$result = static::find_by_sql($sql);
			return !empty($result) ? $result : false;
		}
		/**
		* Returns an array of Posts by category name
		*
		* @param id int - category id
		* @param limit int - limit the amount of content to retrieve
		* @return array
		**/
		public static function posts_by_category_title($title, $order="created DESC", $limit=NULL)
		{
			$sql = "SELECT content.".implode(", content.", parent::$db_fields)." FROM content, categories";
			$sql .= " WHERE content.type ='".ContentType::POST."'";
			$sql .= " AND content.status ='".ContentStatus::PUBLISHED."'";
			$sql .= " AND content.category_id=categories.id";
			$sql .= " AND categories.title='$title'";
            $sql .= " ORDER BY $order";
			if(isset($limit)){
				$sql .= " LIMIT ".$limit;
			}
			$result = static::find_by_sql($sql);
			return !empty($result) ? $result : false;
		}
		
		/**
		* Returns an array content excluding provided categories ids
		*
		* @param categories array - categories to exclude
		* @param limit int - limit the amount of content to retrieve
		* @return array
		**/
		public static function posts_except_in_categories( $categories, $limit=NULL, $order_by=NULL )
		{
			if( !isset($categories) || empty($categories) ){
				return false;
			}

			$sql = "SELECT * from content";
			$sql .= " WHERE type ='".ContentType::POST."'";
			$sql .= ' AND category_id NOT IN ('.implode("," , $categories ).")";
			$sql .= " AND status ='".ContentStatus::PUBLISHED."'";
			if(isset($limit)){
				$sql .= " LIMIT ".$limit;
			}

			if(isset($order_by)){
				$sql .= " ORDER BY '".$order_by."'";
			}
			
			$result = static::find_by_sql($sql);

			return !empty($result) ? $result : false;
		}				
				
	}
	
?>
