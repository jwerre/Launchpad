<?php

/**
* 
*/
class Comment extends DatabaseObject
{
	protected static $table_name = "comments";
	protected static $db_fields = array('id', 'content_id', 'created', 'author', 'body');
	
	public $id;
	public $content_id;
	public $created;
	public $author;
	public $body;
	
	public static function make($content_id, $author="Anonymous", $body)
	{
		if (empty($photo_id) || empty($body)) {
			return false;
		}else{
			$comment = new Comment();
			$comment->content_id = $content_id;
			$comment->created = strftime( "%Y-%m-%d %H:%M:%S", time() );
			$comment->author = $author;
			$comment->body = $body;
			return $comment;
		}
	}
	
	public static function find_by_content_id($content_id = 0)
	{
		global $database;
		
		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE content_id=" . $database->escape_value($content_id);
		$sql .= " ORDER BY created ASC";
		return self::find_by_sql();
	}
	
}



?>