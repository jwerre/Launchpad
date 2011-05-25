<?php
	/**
	* 
	*/
	class Category extends DatabaseObject
	{
		protected static $table_name = "categories";
		protected static $db_fields = array( 'id', 'title', 'description', 'slug');

		public $id;
		public $title;
		public $description;
		public $slug;
		
		
		
	}
?>