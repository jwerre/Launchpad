<?php
	/**
	* 
	*/
	class ContentType
	{
		const POST = "post";
		const PAGE = "page";
		
		public static function get_types()
		{
			$roles = new ReflectionClass('ContentType');
		 	return $roles->getConstants();		 
		}
	}
?>