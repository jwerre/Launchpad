<?php
/**
 * A class for the creation and modification of Post comments
 *
 * @author Jonah Werre <jonahwerre@gmail.com>
 * @version 1.0
 * @copyright Jonah Werre <jonahwerre@gmail.com>, 28 June, 2011
 * @package Comment
 **/

class Comment extends DatabaseObject
{
	/**
	 * The name of the database table
	 * @var string
	 **/
	protected static $table_name = "comments";
	/**
	 * The name of the database fields in $table_name
	 * @var string
	 **/
	protected static $db_fields = array('id', 'content_id', 'created', 'author', 'body');
	
	/**
	 * The unique id of the category
	 * @var integer
	 **/
	public $id;
	/**
	 * The unique id of the Content for which this comment belongs
	 * @var integer
	 **/
	public $content_id;
	/**
	 * The date this category was created
	 * @var string
	 **/
	public $created;
	/**
	 * The uiniqe id of the User who created this Comment
	 * @var integer
	 **/
	public $author;
	/**
	 * The comment body
	 * @var string
	 **/
	public $body;

	/**
	 * Creates a new instance of a comment and stores it in the database
	 *
	 * @param integer $content_id - The id of the Content to which this Comment belongs
	 * @param integer $author - The $id of the User who created the Comment
	 * @param string $body - The Comment body
	 * @return boolean
	 **/
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
	/**
	 * Finds all the Comments that beling to a particular post
	 *
	 * @param integer $content_id - The id of the Content
	 * @return array
	 **/
	
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
