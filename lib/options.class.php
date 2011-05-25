<?php
	/**
	* 
	*/
	class Options extends DatabaseObject
	{
				
        /**
         *  Default site options
         **/
        const THEME = 'theme';
        const SITE_NAME = 'site_name';
        const TAGLINE = 'tagline';
        const DESCRIPTION = 'description';
        const ANALYTICS_ID = 'analytics_id';

		protected static $table_name = "options";
		protected static $db_fields = array('id', 'name', 'value' );

		public $id;
		public $name;
		public $value;

		function __construct()
		{
			
		}
		
		public function __get($name)
		{
			global $database;
			$sql = "SELECT value FROM ".static::$table_name." WHERE name = '$name'";
			$database->query($sql);
			$result = $database->fetch_array();
			return ( isset($result['value']) && !empty($result['value']) ) ? $result['value'] : false;
		}
		
		public function __set($name, $value)
		{
			global $database;
			$result = false;
			
			$sql = "INSERT INTO ".static::$table_name." ( name, value) VALUES (?, ?)";
			try {
				$result = $database->execute( $sql, array($name, $value) );				
			} catch (PDOException $e) {
                if($e->getCode() == 23000) {
					try {
						$sql = "UPDATE ".static::$table_name." SET value = ? WHERE name = ?";
						$result = $database->execute( $sql, array($value, $name) );				
					} catch(PDOException $e) {
						echo "there was an error updating the option ". $e->getMessage();
					}
				} else {
					echo "there was an error ". $e->getMessage();
				}
			}
			return $result;
		}

        public function __isset($name) {
			$sql = "WHERE name = '$name'";
            return static::count_all($sql);
        }

        public function __unset($name) {
            global $database;
            $sql = "DELETE FROM ". static::$table_name." WHERE name=? LIMIT 1";
            return $database->execute($sql, array($name) );		
        }
        
		
		public static function get_options( $exclude_constants )
		{
            $sql = " SELECT * FROM " . static::$table_name. " WHERE id IS NOT NULL ";
			if($exclude_constants){
				$obj = new ReflectionClass('Options');
			 	$constants = $obj->getConstants();
				foreach ($constants as $name => $value) {
					if(!empty($value)){
						$sql .= " AND name != '$value'";
					}
				}
				
			}
			return static::find_by_sql($sql);
		}
		
		
	}
	
	$options = new Options();
?>
