<?php 
/**
 * A class for processing theme templates
 **/
class Template
{
	/**
	 * Stores the entries to be parsed into the template
	 * @param array
	 **/
	public $entries = array();
	/**
	 * Sotres the location of the template file
	 * @var string
	 **/
	public $template;
	/**
	 * Stores the contents of the template
	 * @var string
	 **/
	public $template_content;


	function __construct()
	{
		
	}

	private function load_template()
	{
		$template_file = theme_directory().$this->template;
	}

	/**
	 * Parses the template
	 *
	 * @param array  - Additional content
	 * @return string -  The markup
	 **/
	public function parse_template($extra=NULL)
	{
		global $page;
		$template = $this->template;
		$comment_patterns = ('#/\*.*?\*/#s', '#(?<!:)//.*#');
		$template = preg_replace($comment_patterns, NULL, $template);
        $tag_pattern = '{{([\s\S]*)}}'; // any characters between '{{}}'
		return $template;
	}

	/**
	 * description
	 *
	 * @param string  -  param description
	 * @return string -  return description
	 **/
	static function include_template()
	{
        $directory = theme_directory(true).DS.'templates';
		include($directory.DS.$template);
	}
}
	
?>
