<?php
/**
 * A class for creating and modifying Content
 *
 * @author Jonah Werre <jonahwerre@gmail.com>
 * @version 1.0
 * @copyright Jonah Werre <jonahwerre@gmail.com>, 28 June, 2011
 * @package Cookie
 **/
class Cookie
{
	
	function __construct()
	{

	}
	//--------------------------------------
	//	GETTERS AND SETTER
	//--------------------------------------
	
	/**
	 * sets a cookie veriable. Use: $cookie->foo = bar;
	 *
	 * @param string $key - the variable name
	 * @param string $value - the variable value
	 * @return null
	 **/
	public function __set($key, $value)
	{
        setcookie($key, $value, time()+COOKIE_EXPIRE );
    }
	/**
	 * Gets a sessin variable. Use: $cookie->foo;
	 *
	 * @param string $key - the cookie to retrieve 
	 * @return string
	 **/
	public function __get($key)
	{
        if (array_key_exists($key, $_COOKIE)) {
            return $_COOKIE[$key];
		}else{
			return false;
		}
    }
	/**
	 * check whether a cookie variable is set. Use: isset($cookie->foo);
	 *
	 * @param string $key - The variable to check
	 * @return boolean
	 **/
    public function __isset($key) {
		return isset($_COOKIE[$key]);
    }
	/**
	 * unsets a cookie variable is set. Use: unset($cookie->foo);
	 *
	 * @param string $key - The variable to unset
	 * @return null
	 **/
    public function __unset($key) {
        unset($_COOKIE[$key]);
    }
}
$cookie = new Cookie();
?>
