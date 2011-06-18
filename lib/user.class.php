<?php

class User extends DatabaseObject
{
	
	protected static $table_name = "users";
	protected static $db_fields = array( 'id', 'first_name', 'last_name', 'username', 'email', 'password', 'bio', 'role', 'image_id' );
	
	public $id;
	public $username;
	public $first_name;
	public $last_name;
	public $email;
	public $password;
	public $role;
	public $bio;
	public $image_id;
	
	function __construct()
	{
		
	}
	
	/**
	* Athentictates username and password combination.
	*
	* @param string $username;
	* @param string $password;
	* @return User | boolean
	*/
	public static function authenticate($username="", $password="")
	{
		global $database;
		$username = $database->escape_value($username);
		$password = $database->escape_value($password);
		
		$sql = "SELECT * FROM ";
		$sql .= static::$table_name;
		$sql .= " WHERE password = $password AND username = $username OR email = $username LIMIT 1";
		
		$result_array = parent::find_by_sql( $sql );
		return !empty($result_array) ? $result_array[0] : false ;
	}
	
	/**
	* Retrieves user via username or email
	*
	* @param string $user_info
	* @return User | boolean
	*/
	public static function get_user($user_info)
	{
		global $database;
		$user_info = $database->escape_value($user_info);
		
		$sql = "SELECT * FROM ";
		$sql .= static::$table_name;
		$sql .= " WHERE username = $user_info OR password = $user_info LIMIT 1";
		$result_array = parent::find_by_sql( $sql );
		return !empty($result_array) ? $result_array[0] : false ;
	}
	
	/**
	* Checks to see if a username or email already exist
	*
	* @param array $user_info
	* @return boolean
	*/
	public static function exists($user_info)
	{
		foreach ($user_info as $value) {
			if( static::get_user( $value ) ){
				return true;
			}
		}
		return false;
	}
	
	/**
	* Return the full name of the current user
	*
	* @return string
	*/
	public function full_name()
	{
		if( isset($this->first_name) && isset($this->last_name) ){
			return $this->first_name ." ".$this->last_name;
		}else{
			return "";
		}
	}
	
	/**
	 * returns a profile image that corresponds to the user
	 *
	 * @return Image
	 */
	public function profile_image($alternate=NULL)
	{ 
		if( !empty($this->image_id ) ){
			$file = Image::find_by_id($this->image_id);
			if( !empty($file) ){
				$image = $file->filename;
			}else{
				$image = $alternate;
			}
		}else{
			$image = $alternate;
		}
		return $image;
	}
	
	/**
	* Returns the integer role as it's title equivalent 
	*
	* @return string
	*/
	
	public function role_title()
	{
		return UserRole::get_role_by_value($this->role);
	}
		
	/**
	* Generate a random password
	*
	* @param int $length
	* @return string 
	*/
	public function generate_random_password($length=10)
	{
		$password = "";
		$characters = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		for($i = 0; $i < $length; $i++) {
			$password .= $characters{mt_rand(0, strlen($characters)-1)};
		}
		return $password;
	}	
}



?>
