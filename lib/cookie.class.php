<?php

/**
* 
*/
class Cookie
{
	
	function __construct()
	{

	}
	//--------------------------------------
	//	GETTERS AND SETTER
	//--------------------------------------
	
	// $cookie->'foo' = 'bar';
	public function __set($key, $value)
	{
        setcookie($key, $value, time()+COOKIE_EXPIRE );
    }

	//echo $cookie->foo // 'bar';
	public function __get($key)
	{
        if (array_key_exists($key, $_COOKIE)) {
            return $_COOKIE[$key];
		}else{
			return false;
		}
    }

	//isset($cookie->foo) // true;
    public function __isset($key) {
		return isset($_COOKIE[$key]);
    }

	//unset($cookie->foo);
    public function __unset($key) {
        unset($_COOKIE[$key]);
    }
	

	//--------------------------------------
	//  PUBLIC METHODS
	//--------------------------------------
	
}
$cookie = new Cookie();
?>
