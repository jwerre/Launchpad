<?php
	/**
	* 
	*/
	class ContentStatus
	{		
		const PUBLISHED = "published";
		const DRAFT = "draft";
		
		public static function get_types()
		{
			$reflector = new ReflectionClass('ContentStatus');
		 	return $reflector->getConstants();		 
		}
	}
?>