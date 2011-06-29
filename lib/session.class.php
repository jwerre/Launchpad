<?php

/**
 * @author Jonah Werre <jonahwerre@gmail.com>
 * @version 1.0
 * @copyright Jonah Werre <jonahwerre@gmail.com>, 27 June, 2011
 * @package default
 **/

/**
 * A class for the creation and acessing of session variables 
 */      

class Session
{
	
	private $logged_in = false;
	private $database;
	
	function __construct()
	{	

		if (USE_DB_SESSION) {
			session_set_save_handler( array( &$this, "open" ), array( &$this, "close" ), array( &$this, "read" ), array( &$this, "write"), array( &$this, "destroy"), array( &$this, "clean" ) );
		}
		
		session_start();

		if( $this->check_login() ) {
			$this->active();
		} else {
			
	    }
	}
	
	/**
	 * sets a session veriable. Use: $session->foo = bar;
	 *
	 * @param string $key - the variable name
	 * @param string $value - the variable value
	 * @return null
	 **/
	public function __set($key, $value)
	{
        $_SESSION[$key] = $value;
    }

	/**
	 * Gets a sessin variable. Use: $session->foo;
	 *
	 * @param string $key - the session to retrieve 
	 * @return string
	 **/
	public function __get($key)
	{
        if (array_key_exists($key, $_SESSION)) {
            return $_SESSION[$key];
		}else{
			return false;
		}
    }

	/**
	 * check whether a session variable is set. Use: isset($session->foo);
	 *
	 * @param string $key - The variable to check
	 * @return boolean
	 **/
    public function __isset($key) {
        return isset($_SESSION[$key]);
    }

	/**
	 * unsets a session variable is set. Use: unset($session->foo);
	 *
	 * @param string $key - The variable to unset
	 * @return null
	 **/
    public function __unset($key) {
        unset($_SESSION[$key]);
    }

	/**
	 * Check whether the current user is logged in
	 *
	 * @return booelan
	 **/
	public function is_logged_in()
	{
		return $this->logged_in;
	}
	/**
	 * Logins in a user
	 *
	 * @param string $user_id - The user id of the user to log in.
	 * @return null
	 **/
	public function login($user_id)
	{
		if($this->logged_in){
			$this->logout();
		}
		if($user_id){
			$this->user_id = $user_id;
			$this->logged_in = true;
		}
	}
	/**
	 * Logs out the currently logged in user
	 *
	 * @return boolean
	 **/
	public function logout()
	{
		session_destroy();
		session_unset();
		$this->logged_in = false;
	}
	
	/**
	* Set the message session variable.
	*
	* @param string $msg - The message to set. Pass null or empty string to unset the current message;
	* @return boolean | string
	*/
	public function message($msg="")
	{
		if (!empty($msg)) {
			$_SESSION['message'] = $msg;
		}else {
			$msg = ( isset($_SESSION['message']) ) ? $_SESSION['message'] : "";
			unset($_SESSION['message']);
		}
		return $msg;
	}
	
	
	//--------------------------------------
	//  PRIVATE & PROTECTED METHODS
	//--------------------------------------
	
	/**
	 * Checks if a logged in user still has an active session. If not, logs the user out.
	 *
	 * @return null
	 **/
	private function active()
	{
		if (isset($_SESSION['last_active']) && (time() - $_SESSION['last_active'] > SESSION_LIFE)) {
			$this->logout();
		}
		$_SESSION['last_active'] = time(); // update last activity time stamp

		// Avoid attacks on sessions by regenerating session id every 30 minutes
		if (!isset($_SESSION['created'])) {
			$_SESSION['created'] = time();
		} else if (time() - $_SESSION['created'] > SESSION_LIFE) {
			session_regenerate_id(true);    // change session ID for the current session and invalidate old session ID
			$_SESSION['created'] = time();  // update creation time
		}
			
	}
	/**
	 * Checks if 'user_id' in session is logged in;
	 *
	 * @return null
	 **/
	private function check_login()
	{
		if( isset($_SESSION['user_id']) ){
			$this->logged_in = true;
		}else{
			unset($this->user_id);
			$this->logged_in = false;
		}
	}
	
	
	//******************************************
	// OVERRDING PHP SESSION FUNCTIONS
	// store session in database instead of file. In confi.php set USE_DB_SESSION to true;
	//******************************************
	/**
	 * Opens a new database connection for string session
	 *
	 * @return boolean
	 **/
	public function open() {
		$this->database = new DatabasePDO();
        return true;
    }
	/**
	 * Closes database connection
	 *
	 * @return boolean
	 **/
    public function close() {
		//$this->database->close_connection();
		//$this->database = NULL;
        return true;
    }
	/**
	 * Reads the session from the database
	 *
	 * @param int $id - The id of the session
	 * @return array - Session data
	 **/
	public function read( $id ) {
		$sql = "SELECT session_data FROM sessions WHERE id = '$id' AND expires > ". time();
		try{
			$this->database->query($sql);
			$result = $this->database->fetch_array();
		}catch(PDOException $e){
			echo "This is the problem: ". $e->getMessage();
		}
		return $result['session_data'];
	}
	
	/**
	 * Writes session data to the database
	 *
	 * @param int $id - The id of the session
	 * @param string $data - The data to be stored
	 * @return boolean - success of execution
	 **/
	public function write( $id, $data ) {
		$time = time() + SESSION_LIFE;
		$sql = "INSERT INTO sessions (id, session_data, expires) VALUES ('$id',?, $time)";
		try{
			return $this->database->execute($sql, array($data));
		} catch(PDOException $e) { 
			if($e->getCode() == 23000) { // duplicate entry
				$sql = "UPDATE sessions SET session_data = ?, expires = $time WHERE id = '$id'";
				return $this->database->execute($sql, array($data) ); 
			}
		}
	}

	/**
	 * Removes a session from the database
	 *
	 * @param int $id - description
	 * @return boolean - success
	 **/
	public function destroy( $id ) {
		$sql = "DELETE FROM sessions WHERE id = '$id'";
		return $this->database->execute($sql);
	}
	/**
	 * Deletes expired sessions
	 *
	 * @param number $timeout - Unix timestamp representing the end of the session life
	 * @return boolean
	 **/
	public function clean($timeout) {
		$max_time = time() - $timeout;
		$sql = "DELETE FROM sessions WHERE expires < ?";
		return $this->database->execute($sql, array($max_time));
	}
	
}

$session = new Session();
?>
