<?php

class DatabaseObject
{
	
	// Public Methods

	/**
	* Find all entires from 
	* @param array $exclude - An array of integers to exculde
	* @return array
	*/
	public static function find_all($exclude=NULL)
	{
		$sql = "SELECT * FROM " . static::$table_name;
		if (isset($exclude)) {
			$exclude = implode(',', $exclude);
			$sql .= " WHERE id NOT IN ({$exclude})";
		}
		return static::find_by_sql($sql);
	}

	/**
	* Find all entires from a list of ids
	* @param array - An array of integers to find
	* @return array
	*/
	public static function find_all_by_ids($ids)
	{
		$result_array = array();
		foreach ($ids as $id) {
			if( is_numeric($id) ){ 
				$result = static::find_by_id($id);
				if($result) {
					$result_array[] = $result;
				}
			}
		}
		return !empty($result_array) ? $result_array : false ;
	}
	
	/**
	 * Count number of items in table
	 * @param string where_clause = '' - A MySQL "WHERE" clause to append. eg: 'WHERE id > 5'
	 * @return int
	 */
	public static function count_all($where_clause = '')
	{
		global $database;
		$sql = "SELECT COUNT(*) FROM ".static::$table_name." ".$where_clause;
		$result = $database->query($sql);
		$row = $database->fetch_array();
		return array_shift($row);
	}
	
	/**
	* Returns a single object by its unique id
	* @param int $id - The id of the object to find
	* @return DatabaseObject
	*/
	public static function find_by_id($id=0)
	{
		if(is_numeric($id)){
			$result_array = static::find_by_sql( "SELECT * FROM " . static::$table_name . " WHERE id={$id} LIMIT 1" );
			return !empty($result_array) ? $result_array[0] : false ;
		}else{
			return false;
		}
	}
	
	/**
	* Returns an array of the current class
	* @param string $sql - A valid MySQL statement
	* @return array
	*/
	public static function find_by_sql($sql)
	{
		global $database;
		$result_set = $database->query($sql);
		$object_array = array();
		$result_set->setFetchMode( PDO::FETCH_CLASS, get_called_class() );
		while ( $row = $result_set->fetch(PDO::FETCH_CLASS)) { 
		      $object_array[] = $row;
		}
		return $object_array;
	}

	/**
	 * Check if the current object had specified attribute
	 * @param string $attribute - The attribute to check
	 * @return boolean
	 **/
	protected function has_attribute($attribute){
		// include private vars
		$object_vars = $this->attributes();
		return array_key_exists($attribute, $object_vars);
	}
	
	/**
	* Gets the properties of class
	* @return array 
	*/
	protected function attributes()
	{
		$attributes = array();
		foreach (static::$db_fields as $field) {
			if(property_exists($this, $field)){
				$attributes[$field] = $this->$field;
			}
		}
		return $attributes;
	}
	/**
	* Gets the properties of class and preps them for DB entry -- DEPRECATED --
	* @return array 
	*/
	protected function sanitize_attributes()
	{
		global $database;
		$clean_attributes = array();
		foreach($this->attributes() as $key => $value){
			$clean_attributes[$key] = $database->escape_value($value);
		}
		return $clean_attributes;
	}
	
	/**
	* Check to see if record exists and creates it if not or updates if it does.
	* @return boolean 
	*/
	public function save()
	{
		return isset($this->id) ? $this->update() : $this->create();
	}
	
	/**
	* Create a new entry
	* @return boolean 
	*/
	protected function create()
	{
		global $database;
		
		$attributes =  $this->attributes();
		$keys = array_keys($attributes);
		$values = array_values($attributes);
		
		$sql = "INSERT INTO ". static::$table_name ."(";
		$sql .= implode(", ", $keys );
		$sql .= ") VALUES (";
		$sql .= implode( ", ", array_fill(0, count($attributes) ,'?' ) );
		$sql .= ")";
		
		if($database->execute($sql, $values)){
			if( property_exists($this, 'id') ){
				$this->id = $database->last_insert_id();
			}
			return true;
		}else{
			return false;
		}
	}
	/**
	* Update an entry
	* @return boolean 
	*/
	protected function update()
	{
		global $database;
		
		$attributes =  $this->attributes();
		$attribute_pairs = array();
		foreach($attributes as $key => $value){
			$attribute_pairs[] = "$key = ?";
		}
		
		$values = array_values($attributes);
		
		$sql = "UPDATE ". static::$table_name ." SET ";
		$sql .= implode(", ", $attribute_pairs);
		$sql .= " WHERE id=".	$database->escape_value($this->id);
		return $database->execute($sql, $values);
	}
	/**
	* Delete an entry
	* @return boolean 
	*/
	public function delete()
	{
		global $database;
		$sql = "DELETE FROM ". static::$table_name." WHERE id=? LIMIT 1";
		return $database->execute($sql, array($this->id) );		
	}
    
    /**
    * Delete by row id
    * @param intiger - The unique id of the entry to delete
    * @return boolean
    */
    public static function delete_by_id($id)
    {
		global $database;
		$sql = "DELETE FROM ". static::$table_name." WHERE id=? LIMIT 1";
		return $database->execute($sql, array($id) );		
    }                                              
}

?>
