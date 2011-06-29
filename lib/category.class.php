<?php
/**
 * A class for creating and managing categories
 *
 * @author Jonah Werre <jonahwerre@gmail.com>
 * @version 1.0
 * @copyright Jonah Werre <jonahwerre@gmail.com>, 28 June, 2011
 * @package Category
 **/
	
class Category extends DatabaseObject
{
	/**
	 * The name of the database table
	 * @var string
	 **/
	protected static $table_name = "categories";
	/**
	 * The name of the database fields in $table_name
	 * @var string
	 **/
	protected static $db_fields = array( 'id', 'title', 'description', 'slug');

	/**
	 * The unique id of the category
	 * @var integer
	 **/
	public $id;
	/**
	 * The title of the category
	 * @var string
	 **/
	public $title;
	/**
	 * The category description
	 * @var string
	 **/
	public $description;
	/**
	 * The category title in a url friendly format
	 * @var string
	 **/
	public $slug;
	
}
?>
