<?php
	/**
	* 
	*/
	class UserRole
	{
		const SUPER = 0;
		const ADMIN = 1;
		const CONTRIBUTOR = 2;
		const GUEST = 3;

		/**	
		* Returns all roles and numeric values
		*
		* @return array
		*/						
		public static function get_roles()
		{
			$roles = new ReflectionClass('UserRole');
		 	return $roles->getConstants();		 
		}
		
		/**	
		* Returns role names in an indexed array based on their value ( reverse of UserRole::get_roles() )
		*
		* @return array
		*/
		public static function get_roles_by_values()
		{
			$roles = array();
			foreach (static::get_roles() as $key => $value) {
		        $roles[$value] = ucwords( strtolower($key) );
			}
		    return $roles;
		}

		/**	
		* Returns title-cased role name by it's value
		*
		* @return array
		*/
		public static function get_role_by_value($id)
		{
			$roles = static::get_roles_by_values();			
			return $roles[$id];
		}
		
		
				
	}
	
?>
