<?php

/**
 * A class connecting and executing queries using PDO extention
 *
 * @author Jonah Werre <jonahwerre@gmail.com>
 * @version 1.0
 * @copyright Jonah Werre <jonahwerre@gmail.com>, 28 June, 2011
 * @package DatabasePDO
 **/
class DatabasePDO {
	
		
	/**
	 * The name of the database server. Most likely "localhost"
	 * @var string
	 **/
	public $db_server;
	/**
	 * The username with read and write privileges for the database
	 * @var string
	 **/
	public $db_user;
	/**
	 * The name of the database
	 * @var string
	 **/
	public $db_name;
	/**
	 * The password to the database
	 * @var string
	 **/
	public $db_password;
	
	/**
	 * The last query preformed
	 * @var PDOStatement | false
	 **/
	public $last_query;
	/**
	 * The result from the last query preformed
	 * @var PDOStatement | false
	 **/
	public $last_result;
	/**
	 * The current PDO instance
	 * @var PDO
	 **/
	private $connection;
	
	function __construct(){
		$this->db_server = DB_SERVER;
		$this->db_user = DB_USER;
		$this->db_name = DB_NAME;
		$this->db_password = DB_PASSWORD;
		$this->open_connection();
        @set_exception_handler(array($this, 'exception_handler')); 
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
			throw new Exception( $e->getMessage() );
		}
	}
		
	/**
	* Preform a query on Database.
	*
	* @param string $sql - A valid MySQL statement
	* @return PDOStatement 
	*/
	public function query($sql)
	{
		try {
			$this->last_result = $this->connection->query($sql);			
		} catch (PDOException $e) {
			throw new Exception( $e->getMessage()."<br><strong>".$sql."</strong>" );
		}
		return $this->last_result;
	}
	
	/**
	* Executes a statement on Database
	*
	* @param string $sql - A valid MySQL statement
	* @param array $values - An array of values with as many elements as there are bound parameters in the SQL statement being executed
	* @return boolean 
	*/
	public function execute($sql, $values=NULL)
	{
		$this->last_result = $this->connection->prepare($sql);
		$this->last_result->execute($values);			
		return ($this->affected_rows() > 0) ? true : false;
	}
	
	/**
	* Prepares a statement for execution and returns a statement object
	*
	* @param string $sql - A valid MySQL statement
	* @return PDOStatement | false
	*/
	public function escape_value($sql)
	{
		return $this->connection->quote($sql);
	}
	
	/**
	* Closees a connection to Database.
	* @return null
	*/
	public function close_connection()
	{
		if( isset($this->connection) ){
			$this->connection = null;
		}
	}
	
	/**
	* returns an array indexed by column name as returned in your result set
	* @return array
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
	 * @return string
	*/	
	public function last_insert_id()
	{
		return $this->connection->lastInsertId();
	}
	
	/**
	 * Returns the number of rows affected by the last DELETE, INSERT, or UPDATE statement executed by the corresponding PDOStatement object
	 * @return string 
	*/	
	public function affected_rows()
	{
		return $this->last_result->rowCount();
	}

	/**
	 * Handels Exceptions for the class
	 *
	 * @param Object $exception
	 * @return string
	 **/
    public static function exception_handler($exception) { 
        echo "Exception caught in: ".get_called_class().":". $exception->getMessage() ."\n"; 
    } 	
}


$database = new DatabasePDO();

?>
