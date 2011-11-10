<?php
/**
 * A class for creating and modifying Content
 *
 * @author Jonah Werre <jonahwerre@gmail.com>
 * @version 1.0
 * @copyright Jonah Werre <jonahwerre@gmail.com>, 28 June, 2011
 * @package DatabaseObject
 **/

class Content extends DatabaseObject
{
	/**
	 * The name of the database table
	 * @var string
	 **/
	protected static $table_name = "content";
	/**
	 * The name of the database fields in $table_name
	 * @var array
	 **/
	protected static $db_fields = array( 'id', 'author_id', 'type', 'status', 'updated', 'created', 'weight', 'slug', 'title', 'body', 'parent_id', 'category_id', 'template');
	/**
	 * A string signifying the end of and excerpt
	 * @var string
	 **/
	const EXCERPT = "<!--excerpt-->";
	
	/**
	 * The unique id of the Content
	 * @var integer
	 **/
	public $id;
	/**
	 * The unique id of the User who created the Content
	 * @var integer
	 **/
	public $author_id;
	/**
	 * The type of Content as defined by ContentType
	 * @var string
	 **/
	public $type;
	/**
	 * The status of Content as defined by ConetentStatus
	 * @var string
	 **/
	public $status;
	/**
	 * The date the content was last modified
	 * @var string
	 **/
	public $updated;
	/**
	 * The date the Content was created
	 * @var string
	 **/
	public $created;
	/**
	 * The weight or order the Content is displayed. This is only valid for ContentType::PAGE
	 * @var string
	 **/
	public $weight;
	/**
	 * The Content title in a url friendly format
	 * @var integer
	 **/
	public $slug;
	/**
	 * The title of the Contnet
	 * @var string
	 **/
	public $title;
	/**
	 * The title of the Body
	 * @var string
	 **/
	public $body;
	/**
	 * The title of the Body
	 * @var string
	 **/
	public $parent_id;
	/**
	 * The $content_id of a parnet Page. This is only valid for ContentType:PAGE
	 * @var integer
	 **/
	public $category_id;
	/**
	 * The url path to a Page template. This is only valid for ContentType:PAGE
	 * @var string
	 **/
	public $template;		
	
	/**
	* Count number of items in $table_name
	* @param $type = '' - ContentType::Page or ContentType::Post
	* @return int
	*/
	public static function count_all($type = NULL) 
	{
		global $database;
		$sql = "SELECT COUNT(*) FROM ".static::$table_name;
		if( isset($type) ) {                       
			$sql .= " WHERE type ='".$type."'";
		}
		$result = $database->query($sql);
		$row = $database->fetch_array();
		return array_shift($row);
	}
	/**
	* Count number of published content items
	* @param $type = '' ContentType::Page or ContentType::Post
	* @return int
	*/
	public static function count_published($type = NULL) 
	{
		global $database;
		$sql = "SELECT COUNT(*) FROM ".static::$table_name." WHERE status = '".ContentStatus::PUBLISHED."'";
		if( isset($type) ) {                       
			$sql .= " WHERE type ='".$type."'";
		}
		$result = $database->query($sql);
		$row = $database->fetch_array();
		return array_shift($row);
	}
	
	/**
	* Returns all Content
	* @param string $order="weight ASC" - The field which the output is orders by  
	* @param string $status=ContentType::PUBLISHED - The status of the contents (ContentStatus::PUBLISHED | ContentStatus::DRAFT | NULL )
	* @param array $exclude = NULL - An array of content ids to exclude
	* @param array $include = NULL - An array of content ids to include (excuding all others)
	* @return array
	**/
	public static function find_all_by_type($order='weight ASC', $status=ContentStatus::PUBLISHED, $exclude=NULL, $include=NULL)
	{
		$sql = "SELECT * FROM " . self::$table_name; 
		$sql .= " WHERE type ='". strtolower( get_called_class() )."'";
		if (isset($exclude)) {
			$sql .= ' AND id NOT IN ('.implode("," , $exclude ).")";
		}
		if (isset($include)) {
			$sql .= ' AND id IN ('.implode("," , $include ).")";
		}
		if( isset($status) ){
			$sql .= " AND status ='".$status."'";
		}
		$sql .= " ORDER BY ".$order;

		$result = static::find_by_sql($sql);
		return !empty($result) ? $result : false;
	}

	/**
	* Returns all published Content by Content id
	* @param integer $id
	* @return array
	**/
	public static function find_published_by_type_and_id($id)
	{
		$sql = "SELECT * FROM " . self::$table_name . " WHERE id ='". $id ."' AND type ='". strtolower( get_called_class() ) . "' AND status='". ContentStatus::PUBLISHED ."' LIMIT 1";
		$result = static::find_by_sql($sql);
		return !empty($result) ? $result : false;
	}
			
	/**
	* Returns last created published Content
	* @param iniger $limit=1 - The max amount of Content objects to return
	* @return array
	**/
	public static function find_last_created($limit = 1)
	{
		$sql = "SELECT * FROM " . self::$table_name; 
		$sql .= " WHERE status='". ContentStatus::PUBLISHED; 
		$sql .= "' AND type ='".strtolower( get_called_class() )."'";
		$sql .= " ORDER BY created DESC LIMIT ".$limit;
		$result = static::find_by_sql($sql);
		return !empty($result) ? $result : false;
	}
	
	/**
	* Finds Content by its title
	* @param string $title - The title or slug of the Content
	* @return Content
	*/
	public static function find_by_title($title)
	{
		$sql = "SELECT * FROM " . static::$table_name;
		$sql .= " WHERE title='{$title}'";
		$sql .= " OR slug='{$title}'";
		$result_array = static::find_by_sql($sql);
		return !empty($result_array) ? $result_array[0] : false ;
	}
	/**
	* Finds all Content link to provided tags
	* @param integer | string $tag - An id or tag name;
	* @return array
	*/
	public static function find_by_tag($tag)
	{
		$sql = "SELECT content.* FROM content";
		$sql .= " JOIN content_x_tags ON content.id = content_x_tags.content_id";
		$sql .= " JOIN tags ON tags.id = content_x_tags.tag_id";
		if( is_numeric($tag) ){
			$sql .= " WHERE tags.id = $tag";
		}elseif( is_string($tag) ){
			$sql .= " WHERE tags.tag = '$tag'";
		}
		$sql .= " AND type='".strtolower( get_called_class() )."'";
		$sql .= " AND status='". ContentStatus::PUBLISHED."'"; 
		$result_array = static::find_by_sql($sql);
		return !empty($result_array) ? $result_array : false ;
	}

	/**
	 * Searches content for related term
	 * @param string $term - search term 
	 * @return array
	 **/
	public static function search($term)
	{
		$terms = array();
		if(gettype($term) == 'string'){
			$term = trim(stripslashes(strip_tags($term)));
			$term = strtr($term, ',/\*&()$%^@~`?;', '               '); 
			$term = trim($term); 
			$term = str_replace('#180', '', $term); 
			$term = html_entity_decode($term, ENT_QUOTES); 
			$terms = explode(' ', $term);
			$terms = array_merge(array_filter($terms));
		}else{
			$terms = $term;
		}
		if(is_array($terms) && count($terms) > 0 ){
			$sql = "SELECT * FROM " . self::$table_name; 
			$sql .= " WHERE status='". ContentStatus::PUBLISHED."'"; 
			for ($i = 0; $i < count($terms); $i++) {
				$term = addslashes($terms[$i]);
				$sql .= ($i == 0) ? " AND " : " OR "; 
				$sql .= "body LIKE '%$term%'";
				$sql .= " OR title LIKE '%$term%'";
			}
		}
		$result_array = static::find_by_sql($sql);
		return !empty($result_array) ? $result_array : false ;
	}
	
	/**
	* Returns an excerpt from post. To specify an excerpt lenght user the <!--excpert--> in the content body or specify an EXCERPT_LENGTH in config.php
	*
	* @param number $length =  EXCERPT_LENGTH -  The lenght of the excerpt to be returned rounded to the last space. This will be ingored if the excerpt flag (<!--excerpt-->) is specified in the body.
	* @param string $link_text =  more... - The text to use use for the "more" link at the end of the excpert. A link will not be used if NULL is given.
	* @param string $allow = '<a><em><i><strong><b><span><br><sup><sub><small><strike><abbr><cite><code>' - a list of html tags to allow in excerpt. inline elements allowed by default
	* @return string
	**/
	public function excerpt( $length=EXCERPT_LENGTH, $link_text='more...', $allow = '<a><em><i><strong><b><span><br><sup><sub><small><strike><abbr><cite><code>')
	{	
		if( strlen($this->body) > $length ){
			$excerpt = explode(self::EXCERPT, $this->body);

			if(count($excerpt)>1){
				$excerpt = $excerpt[0];
			}else{
				$excerpt = substr($this->body, 0, $length);
				$last_space = strrpos( $excerpt, " " );
				$excerpt = substr($excerpt, 0, $last_space);
			}
			if(isset($link_text)){
				$excerpt .= ' <a href='.$this->get_link().' class="more_link">'.$link_text.'</a>';
			}
		}else{
			$excerpt = $this->body;
		}
		
		if(isset($allow)){
			$excerpt = strip_tags($excerpt, $allow);
		}
		return $excerpt;
	}
	
	/**
	* Returns author of the content as a User object
	*
	* @return User
	**/
	public function author()
	{
		$user = User::find_by_id( $this->author_id );
		return !empty($user) ? $user : false;
	}
	
	/**
	* sets new tags to content
	*
	* @param array $tags - an array of strings
	* @return array
	**/
	public function set_tags( $tags )
	{
		// 1. DELETE ALL SIMBOLIC LINKS
		// Tag::clear_links_for_content($this->id);
		
		// 2. CREATE AND SAVE NEW TAGS
		for ($i=0; $i < count($tags); $i++) { 
			$tag = new Tag();
			$tag->tag = strtolower( trim($tags[$i]) );
			if( !empty($tag->tag) )
				$tag->save_tag($this->id);
		}
		return $this->tags();
	}
	/**
	 *  Deletes a tag based on its id
	 *
	 *  @param integer $tag_id - The id of the tag to be deleted
	 *  @return boolean
	 **/
	public function delete_tag($tag_id)
	{
		global $database;
		$sql = "DELETE FROM content_x_tags WHERE tag_id = ? AND content_id = ?";

		$result = $database->execute( $sql, array($tag_id, $this->id) );
		if ($result) {
			$sql = "DELETE FROM tags WHERE id = ?";
			try {
				$database->execute( $sql, array($tag_id) );
			} catch (PDOException $e ) {
				// tag is being used by other content — not deleted.    
			}
		}
		return $result;
	}
	/**
	* Returns all tags attached to Content
	*
	* @param boolean $as_string = false - return tag names as string.
	* @param string $delimiter = ", " - delimiter for string output.
	* @return array
	**/
	public function tags( $as_string = false, $delimiter = ", " )
	{
		$tags = Tag::find_by_content_id($this->id);

		if( !empty($tags) ){
			if($as_string){
				$tags_as_string = "";
				for ($i=0; $i < count($tags); $i++) { 
					$tags_as_string .= ( $i+1 != count($tags) ) ? $tags[$i]->tag . $delimiter : $tags[$i]->tag;
				}
				return $tags_as_string;
			}else{
				return $tags;
			}
		}else{
			return false;
		}
	}
	
	/**
	* Returns an array of Snippet objects for this Content
	*
	* @return array
	**/
	public function snippets_complex()
	{
		$snippets = Snippet::find_by_content_id($this->id);
		return $snippets;
	}
	
	/**
	* Returns an associative array of Snippets for this Content
	*
	* @return array
	**/
	public function snippets()
	{
		$snippets = $this->snippets_complex();
		$simple = array();
		if(!empty($snippets)){
			foreach ($snippets as $data_obj => $value) {
				if (array_key_exists($value->name, $simple)) {
					if( gettype($simple[$value->name]) == 'string'){
						$old_val = $simple[$value->name];
						$simple[$value->name] = array( 0 => $old_val);
					}
					$simple[$value->name][] = $value->value;
				}else{
					$simple[$value->name] = $value->value;
				}
			}
			return $simple;
		}else{
			return false;
		}
	}

	/**
	 * Returns a link for the content
	 *
	 * @param boolean $include_tag = false - if true the link is wapped in an anchor tag 
	 * @return string
	 **/

	public function get_link($include_tag = false){
		return Navigation::get_link($this, $include_tag);				 
	}
}
	
	
?>
