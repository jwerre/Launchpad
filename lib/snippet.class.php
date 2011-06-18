<?php
	/**
	* 
	*/
	class Snippet extends DatabaseObject
	{
		protected static $table_name = "snippets";
		protected static $db_fields = array('id', 'content_id', 'name', 'value' );

		public $id;
		public $content_id;
		public $name;
		public $value;
		
		function __construct( $content_id=NULL, $name=NULL, $value=NULL)
		{
			$this->content_id = (isset($content_id)) ? $content_id : $this->content_id;
			$this->name = (isset($name)) ? $name : $this->name;
			$this->value = (isset($value)) ? $value : $this->value;			
		}		
		/**
		*  finds a Snippet object via it's content id
		*/
		public static function find_by_content_id($content_id)
		{
			$sql = "SELECT * FROM ".static::$table_name. " WHERE content_id = '$content_id'";
			return self::find_by_sql($sql);
		}
		
	}
?>
