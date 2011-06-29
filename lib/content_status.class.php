<?php
	/**
	* 
	*/
	class ContentStatus
	{		
		/**
		 * A constant for published Content
		 * @var string="published"
		 **/
		const PUBLISHED = "published";
		/**
		 * A constant for draft Content
		 * @var string="draft"
		 **/
		const DRAFT = "draft";
		
		/**
		 * Retieves all ContentStatus constants
		 * @var array
		 **/
		public static function get_types()
		{
			$reflector = new ReflectionClass('ContentStatus');
		 	return $reflector->getConstants();		 
		}
	}
?>
