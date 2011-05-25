<?php

/*
 * This is a helper class to make paginating records easy.
 */ 

class Pagination {
	
	public $current_page;
	public $per_page;
	public $total_items;
	public $total_pages;

	public function __construct($current_page=1, $per_page=ITEMS_PER_PAGE, $total_items=0)
	{
		$this->current_page = (int)$current_page;
		$this->per_page = (int)$per_page;
		$this->total_items = (int)$total_items;
		$this->total_pages = ceil($this->total_items / $this->per_page);
	}	

	/*
	* Determins the start of the current group
	* @return int
	*/
	public function offset()
	{
        // if current page is a negative number force 0
        $curr_page = max(0, $this->current_page-1);
        // $curr_page = $this->current_page > 0 ? $this->current_page-1 : 0;
		return $curr_page * $this->per_page;
	}
	
	/*
	* returns the previous page
	* @return int
	*/
	public function previous_page()
	{
		return ($this->current_page > 1) ? $this->current_page - 1 : 1;
	}
  	
	/*
	* returns the next page
	* @return int
	*/
	public function next_page()
	{
		return ($this->current_page < $this->total_pages) ? $this->current_page + 1 : $this->total_pages;
	}

	/*
	* Determins whether there is a previous page
	* @return boolean
	*/
	public function has_previous_page()
	{
		return ($this->current_page > 1) ? true : false;
	}

	/*
	* Determins whether there is a next page
	* @return boolean
	*/
	public function has_next_page()
	{
		return ($this->current_page < $this->total_pages) ? true : false;
	}

	/*
	* Determins whether pagination is necessary
	* @return boolean
	*/ 
	public function necessary()
	{
		return ($this->total_items>$this->per_page) ? true : false;
	}

	/*
	* outputs pagination controls
	* @return string
	*/
	public function output_controls()
	{
		if($this->necessary()){
			$controls = '<ul id="pagination">';

			$controls .= '<li class="page_left"><a href="';
			$controls .= append_query_string( array('group'=>$this->previous_page()) );
			$controls .= '" class="previous'; 
			$controls .= ($this->has_previous_page()) ? '' : ' disabled';
			$controls .= '"';
			$controls .= ($this->has_previous_page()) ? '' : ' onclick="return(false);"';
			$controls .= '>';
			$controls .= '<span>Previous</span></a></li>';

			$controls .= '<li class="page_num" ><span>';
			$controls .= $this->current_page;
			$controls .= '</span> of <span>';
			$controls .= $this->total_pages;
			$controls .= '</span></li>';

			$controls .= '<li class="page_right"><a href="';
			$controls .= append_query_string( array('group'=>$this->next_page()) );
			$controls .= '" class="next';
			$controls .=  ($this->has_next_page()) ? '' : ' disabled';
			$controls .= '"';
			$controls .= ($this->has_next_page()) ? '' : ' onclick="return(false);"';
			$controls .= '>';
			$controls .= '<span>Next</span></a></li>';

			$controls .= '</ul>';

			echo $controls;
		}else{
			return false;
		}
	}

}

?>
