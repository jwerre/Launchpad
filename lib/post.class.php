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
		public static function post_by_category_id($id, $limit=NULL)
		{
			$sql = "SELECT * from content WHERE category_id={$id}";
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
			$sql .= " WHERE type ='". strtolower( get_called_class() )."'";
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