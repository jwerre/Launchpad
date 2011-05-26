<?php
	/**
	* 
	*/
	class Navigation
	{
		const UL = 'ul';
		const LI = 'li';
		const DIV = 'div';
		const P = 'p';
		const SPAN = 'span';
		
		public $root_tag = 'ul';
		public $root_tag_wrapper = NULL;
		public $child_tag = 'li';
		public $child_tag_wrapper = NULL;
		public $child_child_tag = 'ul';
		public $child_child_child_tag = 'li';
		
		public $root_class = "nav";
		public $child_class = "subnav";
		
		public $seperator = "";
		public $current_page;
		public $exclude = NULL; //an array of page ids not to include
		public $include = NULL; //an array of page ids to include (excludes all others)
		
		function __construct()
		{
			
		}
		
		/**
		 * Outputs a complex navigational menu with sub-naviation
		 *
		 * @return void
		 **/
		function output_nav()
		{
			$pages = array();
			if( isset($this->include) ){
                $sql = "SELECT id, title, slug FROM content WHERE type='".ContentType::PAGE."' AND status ='".ContentStatus::PUBLISHED."'";
				$sql .= " AND id IN (".implode("," , $this->include ).")";
                $sql .= " ORDER BY weight ASC";
			}elseif( isset($this->exclude) ) {
                $sql = "SELECT id, title, slug FROM content WHERE type='".ContentType::PAGE."' AND status ='".ContentStatus::PUBLISHED."'";
				$sql .= " AND id NOT IN (".implode("," , $this->exclude ).")";
                $sql .= " ORDER BY weight ASC";
			}else{
                $sql = "SELECT id, title, slug FROM content WHERE type='".ContentType::PAGE."' AND status ='".ContentStatus::PUBLISHED."' ORDER BY weight ASC";
			}
            $pages = Page::find_by_sql($sql);
			if($pages) {
                $nav = '<'. $this->root_tag .' class="'. $this->root_class .'">';
                foreach ($pages as $page) {
                    if($page->parent_id == 0){
                        
                        $children1 = $page->children();
                        $on = $this->is_on($page->id, $children1);
                        $href = static::get_page_link($page);
                        
                        $nav .= '<'.$this->child_tag.' class="'.$on.' '.$page->slug;
                        $nav .= (end($pages) === $page) ? " last" : "";
                        $nav .= '">';
                        $nav .= (isset($this->root_tag_wrapper)) ? "<".$this->root_tag_wrapper.">" : "";
                        $nav .= '<a class="" href="'.$href.'" >' . $page->title . '</a>';
                        $nav .= (isset($this->root_tag_wrapper)) ? "</".$this->root_tag_wrapper.">" : "";
                        
                        if(count($children1) > 0){
                            $nav .= (isset($this->child_child_tag)) ? '<'. $this->child_child_tag .' class="'.$this->child_class.'">' : "";
                            foreach ($children1 as $child1) {
                                $sub_on = $this->is_on($child1->id, NULL);
                                $href = static::get_page_link($child1);
                                
                                $nav .= (isset($this->child_child_child_tag)) ? '<'.$this->child_child_child_tag.' class="' : "";
                                $nav .= (isset($this->child_child_child_tag)) ? (end($children1) === $child1) ? "last" : "" : "";
                                $nav .= (isset($this->child_child_child_tag)) ? '">' : "" ;
                                $nav .= (isset($this->child_tag_wrapper)) ? "<".$this->child_tag_wrapper.">" : "";
                                $nav .= '<a class="'.$child1->slug.' '.$sub_on.'" href="'.$href.'" >' . $child1->title . '</a>';
                                $nav .= (isset($this->child_tag_wrapper)) ? "</".$this->child_tag_wrapper.">" : "";
                                $nav .= (isset($this->child_child_child_tag)) ? '</'.$this->child_child_child_tag.'>' : "";
                            }
                            $nav .= (isset($this->child_child_tag)) ? '</'. $this->child_child_tag .'>' : "";
                        }
                        $nav .= '</'.$this->child_tag.'>';
                    }
                }
                $nav .= '</'.$this->root_tag.'>';
                echo $nav;
			}
		}
		
		
		
		/**
		 * Outputs a simple site map
		 * @param $exclude array
		 * @return void
		 **/
		function get_sitemap($exclude=NULL)
		{
            $sql = "SELECT id, title, slug FROM content WHERE type='".ContentType::PAGE."' AND status ='".ContentStatus::PUBLISHED."'";
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
                        $href = static::get_page_link($page);
                        if(count($children) > 0){
                            
                            $nav .= '<li class="'.$page->slug.'" ><span><a href="'.$href.'">'.$page->title.'</a></span></li>';
                            $nav .= '<ul>';
                            
                            foreach ($children as $child) {
                                $href = static::get_page_link($child1);
                                
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
		 *
		 * @return void
		 **/
		public static function categories($exclude = NULL)
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
		 * outputs sub-pages in a list
		 *
		 * @param $parent Page
		 * @return void
		 **/
		public static function subnav($parent)
		{
			if(isset($parent) && is_a($parent, 'Post') ){
				$children = $parent->children();

				if( empty($children) ){
					$children = $parent->siblings();
				}
				
				if( !empty($children) ){
					$subnav = '<ul class="subnav">';
					foreach ($children as $child) {
						$last = (end($children) === $child) ? "last" : "";
						$on = ($parent->id == $child->id) ? 'on ' : '';
						$href = static::get_page_link($child);
					
						$subnav .= '<li class="'.$last.'"><a href="'.$href.'" class="'.$on.$child->slug.'">'.$child->title.'</a></li>';
					}
					$subnav .= '</ul>';
			
					echo $subnav;
				}
			}
		}
		
		
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
		
		private function remove_excuded($pages, $excludes)
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
         * Returns the url for a page or post
         * 
         * @param $page - the id or Conent object for which you want the link
         * @param $include_tag - boolean - if true, returns the link in an anchor tag
         * @return string
         **/
		public static function get_page_link($page, $include_tag=false)
		{
            if ( gettype($page) == 'integer'){
                $sql = 'SELECT id, title, slug FROM content WHERE id ='. $page;
                $result = Content::find_by_sql($sql); 
                $page = $result[0];
            }
			$link = BASE_URL.'/index.php?page='.$page->id;
			
			if(CLEAN_URLS){
				$link = ( defined('REWRITE_MAP') ) ? BASE_URL.'/'.$page->slug : BASE_URL.'/page/'.$page->id;				
			}

            if($include_tag){
                $link = '<a href="'.$link.'"class="'.$page->slug.'" alt="'.$page->title.'">'.$page->title.'</a>';
            }
			
			return $link;
		}
		
	}
	
?>
