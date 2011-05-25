<?php

/**
* 
*/
class Tag extends DatabaseObject
{
	protected static $table_name = "tags";
	protected static $db_fields = array('id', 'tag');
	
	protected static $x_table_name = "content_x_tags";
	protected static $db_x_fields = array('content_id', 'tag_id');
	
	public $id;
	public $tag;
		
	public static function find_by_content_id($content_id = 0)
	{
		global $database;
		
		$columns = implode(", ".self::$table_name.".", self::$db_fields);
		$columns = self::$table_name.".".$columns;
				
		$sql = "SELECT ".$columns." FROM " . self::$table_name;
		$sql .= " JOIN ". self::$x_table_name;
		$sql .= " ON tags.id = content_x_tags.tag_id";
		$sql .= " JOIN content ON content.id = content_x_tags.content_id";
		$sql .= " WHERE content.id = $content_id";

		return self::find_by_sql($sql);
	}
	
	/**
	* Check to see if record exists and creates it if not or updates if it does.
	*
	* @return boolean 
	*/
	public function save($content_id)
	{
		if( isset( $content_id ) ){
			global $database;
			
			// SAVE THE TAG
            try{
			    parent::save();
            } catch(PDOException $e) {
                if($e->getCode() == 23000) {
                    try {
                        $sql = ( "SELECT * FROM " . static::$table_name . " WHERE tag='{$this->tag}' LIMIT 1" );
                        $duplicate = parent::find_by_sql($sql);
                        $this->id = $duplicate[0]->id;
                        $this->tag = $duplicate[0]->tag;
                    } catch(PDOException $e) {
                        echo "there was an error". $e->getMessage();
                    }
                } else {
                    echo "there was an error". $e->getMessage();
                }
            }
			
			// SET LINK TABELS
			if ($this->id) {
				$sql = "INSERT IGNORE INTO ".self::$x_table_name;
				$sql .= "( content_id, tag_id ) ";
				$sql .= "VALUES ( ?, ? ) ";
				
				$values = array($content_id, $this->id);
				
				return $database->execute($sql, $values);
			}else{
                return false;
			}
		}
	}
	
	/**
	* Create a new entry
	*
	* @return boolean 
	*/
	// protected function create()
	// {
	// 	global $database;
	// 
	// 	$attributes =  $this->attributes();
	// 	$keys = array_keys($attributes);
	// 	$values = array_values($attributes);
	// 
	// 	$sql = "INSERT IGNORE INTO ". static::$table_name ."(";
	// 	$sql .= implode(", ", $keys );
	// 	$sql .= ") VALUES (";
	// 	$sql .= implode( ", ", array_fill(0, count($attributes) ,'?' ) );
	// 	$sql .= ")";
	// 
	// 	if($database->execute($sql, $values)){
	// 		if( property_exists($this, 'id') ){
	// 			$this->id = $database->last_insert_id();
	// 		}
	// 		return true;
	// 	}else{
	// 		return false;
	// 	}
	// }
    
    /**
     *  Clears all tags for content
     *
     *  @param $content_id - id of content to clear tags
     *  @return boolean
     **/
	public static function clear_tags_for_content($content_id)
	{
		if ($content_id) {
			global $database;
			$sql = "DELETE FROM ".self::$x_table_name;
			$sql .= " WHERE content_id='".$content_id."'";
			
			$database->execute($sql);
		}
	}
}
?>
