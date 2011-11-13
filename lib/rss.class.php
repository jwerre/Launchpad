<?php

/**
 * @author Jonah Werre <jonahwerre@gmail.com>
 * @version 1.0
 * @copyright Jonah Werre <jonahwerre@gmail.com>, 27 June, 2011
 * @package RSS
 **/

/**
 * A class for accessing and RSS feed 
 * 
 * USE:
 * header("Content-Type: application/xml; charset=ISO-8859-1");
 * $rss = new RSS();
 * echo $rss->get_feed();
 */      
	

  class RSS
  {
	
    public $version = "2.0";

	public function __construct()
	{

	}
	/**
	 * Returns published Pages as an RSS feed
	 *
	 */
	public function get_posts_by_category( $cat ){
		$data = Post::find_by_category_title($cat);
		return $this->get_feed( $data );
	}
	/**
	 * Returns published Pages as an RSS feed
	 *
	 */
	public function get_posts(){
		$data = Post::find_all_by_type();
		return $this->get_feed( $data );
	}
	/**
	 * Returns published Pages as an RSS feed
	 *
	 */
	public function get_pages(){
		$data = Page::find_all_by_type();
		return $this->get_feed( $data );
	}
	/**
	 * Returns published Pages and Posts as an RSS feed
	 *
	 */
	public function get_content(){
		$data = Content::find_all();
		return $this->get_feed( $data );
	}

	public function get_feed( $feed_data)
	{
		$feed = '<rss version="'.$this->version.'">';
		foreach( $feed_data as $row ) { 
			$feed .= '<item>';
			foreach($row as $key => $value){
				$feed .= "<$key>". $this->format($value) ."</$key>";
			}
			if(method_exists($row, "get_link"))
				$feed .= "<link>". $row->get_link() ."</link>";

			// if(method_exists($row, "excerpt"))
				// $feed .= "<description>". $this->format($row->excerpt(EXCERPT_LENGTH, NULL)) ."</description>";

			if(method_exists($row, "snippets"))
				$snippets = $row->snippets();
			if(isset($snippets) && !empty($snippets) ){
				$feed .= '<snippets>';
				foreach ($snippets as $name => $snip) {
					if( is_array($snip) ){
						$feed .= "<".slug($name).">";
						for ($i = 0; $i < count($snip); $i++) {
							$feed .= "<item$i>". $this->format($snip[$i]) ."</item$i>";
						}
						$feed .= "</".slug($name).">";
					} else {
						$feed .= "<".slug($name).">". $this->format($snip) ."</".slug( $name ).">";
					}
				}
				$feed .= '</snippets>';
			
			}

			$feed .= '</item>';
		}
		$feed .= '</rss>';
		return $feed;
	}

	private function format($value){
		if( is_string($value) && strlen($value) != strlen( strip_tags($value) ) ){
			return "<![CDATA[". htmlentities($value) ."]]>";
		}else {
			return htmlentities($value);
		}
	}

}
?>
