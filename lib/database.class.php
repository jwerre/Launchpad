<?php

/**
* A class connecting and executing queries using PDO extention
* 
* @author     Jonah Werre <jonahwerre@gmail.com>
* @version    1.0 6/10/2010
*/
// include_once("config.php");

class DatabasePDO {
	
		
	public $db_server;
	public $db_user;
	public $db_name;
	public $db_password;
	
	public $last_query;
	public $last_result;
	
	private $connection;
	
	function __construct(){
		$this->db_server = DB_SERVER;
		$this->db_user = DB_USER;
		$this->db_name = DB_NAME;
		$this->db_password = DB_PASSWORD;
		$this->open_connection();
	}
	
	/**
	* Opens a connection to Database.
	* @return null 
	*/
	public function open_connection()
	{
		try {
			$this->connection = new PDO('mysql:host='.$this->db_server.';dbname='.$this->db_name, "$this->db_user", "$this->db_password" );
			$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch(PDOException $e) {
			echo $e->getMessage();
		}
	}
		
	/**
	* Preform a query on Database.
	*
	* @param $sql
	* @return 
	*/
	public function query($sql)
	{
		try {
			$this->last_result = $this->connection->query($sql);			
		} catch (PDOException $e) {
			echo $sql . "<br><br>";
			echo $e->getMessage();
		}
		return $this->last_result;
	}
	
	/**
	* Executes a statement on Database
	*
	* @param $sql
	* @return 
	*/
	public function execute($sql, $values=NULL)
	{
		$this->last_result = $this->connection->prepare($sql);
		// print $sql."<br>";
		// print implode(" , ", $values)."<br>";
		// try {
			$this->last_result->execute($values);			
		// } catch (PDOException $e) {
			// echo $e->getMessage();
		// }
		return ($this->affected_rows() > 0) ? true : false;
	}
	
	/**
	* Prepairs string for Database entry.
	*
	* @return string - 
	*/
	public function escape_value($value)
	{
		return $this->connection->quote($value);
	}
	
	/**
	* Closees a connection to Database.
	*/
	public function close_connection()
	{
		if( isset($this->connection) ){
			$this->connection = null;
		}
	}
	
	/**
	* Returns an array containing all of the result set rows
	*/
	public function fetch_array()
	{
		return $this->last_result->fetch(PDO::FETCH_ASSOC);
	}

	/**
	* Returns how many rows are in a result set
	*/
	public function num_rows()
	{
		return $this->last_result->rowCount();
	}
	
	/**
	* Returns the last inserted id of the current database connection
	*/	
	public function last_insert_id()
	{
		return $this->connection->lastInsertId();
	}
	
	/**
	* Returns the number of rows affected by the last SQL statement
	*/	
	public function affected_rows()
	{
		return $this->last_result->rowCount();
	}

}


$database = new DatabasePDO();

?>
