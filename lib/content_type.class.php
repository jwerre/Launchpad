<?php
	/**
	* 
	*/
	class ContentType
	{
        /**
         * A constant for specifying "post" Content type
         * @var string = "post"
         **/
		const POST = "post";
        /**
         * A constant for specifying "page" Content type
         * @var string = "page"
         **/
		const PAGE = "page";
		
		/**
		 * Retieves all ContentType constants
		 * @var array
		 **/
		public static function get_types()
		{
			$roles = new ReflectionClass('ContentType');
		 	return $roles->getConstants();		 
		}
	}
?>
