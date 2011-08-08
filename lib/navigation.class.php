<?php
	/**
	* 
	*/
	class Navigation
	{
		/**
		 * The base tags 
		 * @var string = 'ul'
		 **/
		public $nav_tag = 'ul';
		/**
		 * The child tags 
		 * @var string = 'li'
		 **/
		public $child_tag = 'li';
		/**
		 * The class used for the root of the nav
		 * @var string = 'nav'
		 **/
		public $root_class = "nav";
		/**
		 * the class used for the subnav
		 * @var string = 'subnav'
		 **/
		public $subnav_class = "subnav";
		/**
		 * the class used for the second subnav
		 * @var string = 'subnav2'
		 **/
		public $subnav2_class = "subnav2";
		/**
		 * the class used for the second subnav
		 * @var string = 'subnav2'
		 **/
		public $item_class = "nav_item";
        /**
         * $current_page - typically the global page object
         **/
		public $current_page;
        /**
         * an array of page ids not to include
		 * @var array
         **/
		public $exclude = NULL; //an array of page ids not to include
        /**
         * An array of page ids to include (excludes all others)
         * @var array
         **/
		public $include = NULL;
		/**
		 * An array of additional links to include in the nav
		 * @var array - An associative array in this format: array( title=>'My New Page', link=>'http://www.mynewpage.com' );
		 **/
        // public $additional = NULL;

		function __construct()
		{
		}
		
		/**
		 * Outputs a complex navigational menu with sub-naviation
		 * @return void
		 **/
		function output_nav()
		{
			$pages = array();
			if( isset($this->include) ){
                $sql = "SELECT id, title, slug, type, parent_id FROM content WHERE type='".ContentType::PAGE."' AND status ='".ContentStatus::PUBLISHED."'";
				$sql .= " AND id IN (".$this->sanitize_search( $this->include).")";
				$sql .= " OR title IN (".$this->sanitize_search( $this->include).")";
				$sql .= " OR slug IN (".$this->sanitize_search( $this->include).")";
                $sql .= " ORDER BY weight ASC";
			}elseif( isset($this->exclude) ) {
                $sql = "SELECT id, title, slug, type, parent_id FROM content WHERE type='".ContentType::PAGE."' AND status ='".ContentStatus::PUBLISHED."'";
				$sql .= " AND id NOT IN (".$this->sanitize_search($this->exclude ).")";
				$sql .= " AND title NOT IN (".$this->sanitize_search($this->exclude ).")";
				$sql .= " AND slug NOT IN (".$this->sanitize_search($this->exclude ).")";
                $sql .= " ORDER BY weight ASC";
			}else{
                $sql = "SELECT id, title, slug, type, parent_id FROM content WHERE type='".ContentType::PAGE."' AND status ='".ContentStatus::PUBLISHED."' ORDER BY weight ASC";
            }
            $pages = Page::find_by_sql($sql);
			if( !empty( $pages) )  {
                $nav = '<'. $this->nav_tag .' class="'. $this->root_class .'">';
                foreach ($pages as $page) {
                    if(!$page->is_child()){
						//sub-nav
                        $subnav = $page->children();
                        $on = $this->is_on($page->id, $subnav)." ";
                        $href = static::get_link($page);
						//  echo $href; exit;
						$more = (!empty($subnav)) ? "more " : ""; 
						$last = (end($pages) === $page) ? "last " : "";
                        
                        $nav .= '<'.$this->child_tag.' class="'.$this->item_class.' '.$on.$more.$last.$page->slug.'">';
                        $nav .= '<span><a href="'.$href.'" >' . $page->title . '</a></span>';
                        
                        if(count($subnav) > 0){
                            $nav .= (isset($this->nav_tag)) ? '<'. $this->nav_tag .' class="'.$this->subnav_class.'">' : "";
                            foreach ($subnav as $child1) {
								$subnav2 = $child1->children();
                                $sub_on = $this->is_on($child1->id, NULL)." ";
                                $href = static::get_link($child1);
								$more = (!empty($subnav2)) ? "more " : ""; 
								$last = (end($subnav) === $child1) ? "last " : "";

								$nav .= '<'.$this->child_tag.' class="'.$this->item_class.' '.$on.$more.$last.$child1->slug.'">';
                                $nav .= '<span><a href="'.$href.'" >' . $child1->title . '</a></span>';
								if(count($subnav2) > 0){
									$nav .= (isset($this->nav_tag)) ? '<'. $this->nav_tag .' class="'.$this->subnav2_class.'">' : "";
									foreach ($subnav2 as $child2) {
										$sub_on = $this->is_on($child2->id, NULL)." ";
										$href = static::get_link($child2);
										$last = (end($subnav2) === $child2) ? "last " : "";

										$nav .= '<'.$this->child_tag.' class="'.$this->item_class.' '.$on.$last.$child2->slug.'">';
										$nav .= '<span><a href="'.$href.'" >' . $child2->title . '</a></span>';
									}
									$nav .= (isset($this->nav_tag)) ? '</'. $this->nav_tag .'>' : "";
								}

                                $nav .= (isset($this->child_tag_wrapper)) ? "</".$this->child_tag_wrapper.">" : "";
                                $nav .= (isset($this->child_tag)) ? '</'.$this->child_tag.'>' : "";
							}
                            $nav .= (isset($this->nav_tag)) ? '</'. $this->nav_tag .'>' : "";
                        }
                        $nav .= '</'.$this->child_tag.'>';
                    }
                }
                $nav .= '</'.$this->nav_tag.'>';
                echo $nav;
			}
		}
		
		/**
		 * outputs sub-pages in a list
		 *
		 * @param $page Page - The parent page of the subpages to output
		 * @return void
		 **/
		public function subnav()
		{
			global $page;
			if(isset($page) && $page instanceof Content ){
				$children = $page->children();

				if( empty($children) ){
					$children = $page->siblings();
				}
				
				if( !empty($children) ){
					$subnav = '<ul class="'.$this->subnav_class.'">';
					foreach ($children as $child) {
						$last = (end($children) === $child) ? "last" : "";
						$on = ($page->id == $child->id) ? 'on ' : '';
						$href = static::get_link($child);
						if(count($children) > 1 || $page->id != $child->id){
							$subnav .= '<li class="'.$last.'"><a href="'.$href.'" class="'.$this->item_class.' '.$on.$child->slug.'">'.$child->title.'</a></li>';
						}
					}
					$subnav .= '</ul>';
			
					return $subnav;
				}

				return false;
			}
		}
		
		
		/**
		 * Outputs a simple site map
		 * @return void
		 **/
		function output_sitemap()
		{
            $sql = "SELECT id, title, slug, type FROM content WHERE type='".ContentType::PAGE."' AND status ='".ContentStatus::PUBLISHED."'";
			if( isset($this->exclude)) {
			    $sql .= " AND id NOT IN (".implode("," , $this->exclude ).")";
            }
            $sql .= " ORDER BY weight ASC";
            $pages = Page::find_by_sql($sql);
			if ($pages) {
                $nav = '<ul>';
                foreach ($pages as $page) {
                    if($page->parent_id == 0){
                        $children = $page->children();
                        $href = static::get_link($page);
                        if(count($children) > 0){
                            
                            $nav .= '<li class="'.$page->slug.'" ><span><a href="'.$href.'">'.$page->title.'</a></span></li>';
                            $nav .= '<ul>';
                            
                            foreach ($children as $child) {
                                $href = static::get_link($child1);
                                
                                $last = (end($children) === $child) ? "last" : "";
                                $nav .= '<li class="'.$page->slug.' '.$last.'"><a href="'.$href.'">'.$child->title.'</a></li>';
                            }
                            
                            $nav .= '</ul>';
                        }else{
                            $nav .= '<li class="'.$page->slug.'" ><span><a href="'.$href.'">'.$page->title.'</a></span></li>';
                        }
                    }
                }
                $nav .= '</ul>';
                echo $nav;
            }
		}
		
		/**
		 * Outputs a list of categories
		 * @return void
		 **/
		public static function output_categories($exclude = NULL)
		{
			$cat_list = Category::find_all($exclude);
			$nav="";
			if(!empty($cat_list)){
				$nav .= '<ul class="cat_nav">';
				foreach ($cat_list as $cat) {
					$last = (end($cat_list) === $cat) ? "last" : "";
					$href = (CLEAN_URLS) ? BASE_URL.'/cat/'.$cat->id : BASE_URL.'/index.php?cat='.$cat->id;
					
					$nav .= '<li class="'.$last.'"><a href="'.$href.'" class="'.$cat->slug.'">'.$cat->title.'</a></li>';
				}
				$nav .= '</ul>';
				echo $nav;
			}
		}
		
		
		/**
		 * Finds out if the passed page or any of it's children is the current page
		 * @param integer $id - The unique id of the Page to check
		 * @param array $children - An array of child pages to check 
		 **/
		private function is_on($id, $children)
		{
			if( !empty( $this->current_page ) ){
				if ($this->current_page == $id){
					return "on";
				}
				else{
					if(!empty($children)){
						foreach ($children as $child) {
							if ($this->current_page == $child->id)
								return "on";
						}
					}
				}
			}
			else{
				return "";
			}
		}

		/**
		 * Depricated
		 **/		
		private function remove_subpages($pages, $excludes)
		{
			foreach ($pages as $key => $value){
				for ($i=0; $i < count($excludes); $i++) {
					if ($pages[$key]->id == $excludes[$i]){
						unset($pages[$key]);
						break;
					}
				}
			}
			return $pages;
		}
        /**
         * checks to see if the there are strings in array then slug and quotes them 
         *
         * @param $arr
         * @return string
         **/
        private function sanitize_search($arr)
        {
            $new_array = array();
            foreach ($arr as $value) {
                if (is_string($value)) {
                    $new_array[] = "'".$value."'";    
                }else{
                    $new_array[] = $value;
                }
            }    

            return implode( ',', $new_array);
        }

        /**
         * Returns the url for a Page or Post
         * 
         * @param integer | string $content - the id, title, slug, or Content object for which you want the link
         * @param boolean $include_tag - if true, returns the link in an anchor tag
         * @return string
         **/
		public static function get_link($content, $include_tag=false)
		{
            if ( !is_object($content) ){
                $sql = "SELECT id, title, type, slug FROM content WHERE ";
                $sql .= ( gettype($content) == 'integer' ) ? "id=$content" : "slug='".slug($content)."'";
                $result = Content::find_by_sql($sql); 
                $content = ($result) ? $result[0] : false;
            }
			if($content){
				$link = BASE_URL.'/index.php?'.$content->type.'='.$content->id;
				
				if(CLEAN_URLS){
					$link = ( defined('REWRITE_MAP') ) ? BASE_URL.'/'.$content->slug : BASE_URL.'/'.$content->type.'/'.$content->id;				
				}

				if($include_tag){
					global $page;
					$on = (!empty($page) && $page->id == $content->id) ? 'on ' :'';
					$link = '<a href="'.$link.'"class="'.$on.$content->slug.'" alt="'.$content->title.'">'.$content->title.'</a>';
				}
				return $link;
			}else{
				return false;
			}
			
		}
		
	}
	
?>
