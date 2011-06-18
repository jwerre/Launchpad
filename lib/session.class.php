<?php

/**
* 
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
	
	
	//--------------------------------------
	//	GETTERS AND SETTER
	//--------------------------------------
	
	// $session->foo = 'bar';
	public function __set($key, $value)
	{
        $_SESSION[$key] = $value;
    }

	//echo $session->foo // 'bar';
	public function __get($key)
	{
        if (array_key_exists($key, $_SESSION)) {
            return $_SESSION[$key];
		}else{
			return false;
		}
    }

	//isset($session->foo) // true;
    public function __isset($key) {
        return isset($_SESSION[$key]);
    }

	//unset($session->foo);
    public function __unset($key) {
        unset($_SESSION[$key]);
    }

	//--------------------------------------
	//  PUBLIC METHODS
	//--------------------------------------
	public function is_logged_in()
	{
		return $this->logged_in;
	}
	
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

	public function logout()
	{
		session_destroy();
		session_unset();
		$this->logged_in = false;
	}
	
	/**
	* If parameter is passed sets 'message' SESSION else unsets the 'message' SESSION variable.
	*
	* @param string $msg;
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
	
	public function open() {
		$this->database = new DatabasePDO();
        return true;
    }

    public function close() {
		//$this->database->close_connection();
		//$this->database = NULL;
        return true;
    }
		
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
	
	public function destroy( $id ) {
		$sql = "DELETE FROM sessions WHERE id = '$id'";
		return $this->database->execute($sql);
	}
	
	public function clean($timeout) {
		$max_time = time() - $timeout;
		$sql = "DELETE FROM sessions WHERE expires < ?";
		$this->database->execute($sql, array($max_time));
	}
	
}

$session = new Session();
?>
