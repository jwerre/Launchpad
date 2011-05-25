<?php 

/**
 * A class for polling
 **/
class Poll extends DatabaseObject
{
	

    private $scale = 2; //number of pixels per 1% on display bars 
    private $poll_id = '';

    public $question = '';
    public $options = array();
	public $voted; 
	

	function __construct($question, $options)
	{
		$this->question = $question;
		$this->options = $options;
		$this->poll_id = $pid = md5($this->question);
		global $cookie;
		$this->voted = isset( $cookie->$pid );
	}

	public function initialize()
	{
		if( $this->voted ){
			$this->output_results(); 
		}else{
			$this->output_options();
		};		
	}
	
	public function output_options($class="poll_option") {
		
		$list = '<form action="" class="" method="get" accept-charset="utf-8" style="margin-top: 20px">';
		$list .= '<ul class="poll options">';
		for ($i = 0; $i < count($this->options); $i++) {
			$list.='<li class="'.$class.'">';
			$list.='<input type="radio" name="poll_option" value="'.$i.'">';
			$list.= '<label for="poll_option">'.$this->options[$i].'</label></li>'."\n";
		}
		$list .= '</ul>';
		$list .= '<p class="center" style="margin-top: 20px"><button type="submit">Vote</button></p>';
		$list .= '</form>';
		echo $list;
	}

	public function output_results()
	{
		$results = $this->get_results();
		if( !empty($results) ){
			$votes = array_sum($results);
			$list = '<ul class="poll results">';
			for ($i = 0; $i < count($this->options); $i++) {
				$val = ( isset($results[$i]) ) ? $results[$i] : 0;
				$percent = round(($val/$votes)*100);
				$width = $percent * $this->scale;
				$list .= '<li><div class="result_bar option_'.$i.'" style="width:'.$width.'px;">&nbsp;</div>';
				$list .= '<strong>'.$percent.'% </strong>'.$this->options[$i];
				$list .= '</li>';
			}
			$list .= "</ul>";
			echo $list; 
		}else{
			echo "<p>sorry ther are no results for this poll</p>";
		}
	}
	
	public function get_results() {
		
		global $database;
		$sql = "SELECT option_id, votes FROM poll WHERE poll_id = '$this->poll_id'";
		$database->execute($sql);

		while( $row = $database->fetch_array() ) {
			$results[$row['option_id']] = $row['votes'];
		}
		return $results;
	}
	
	public function vote($answer)
	{
		global $database;

		if( $this->voted ){
			return;
		}
		
		$sql = "INSERT INTO poll (poll_id, option_id, votes) values ( ?,?,? )";
		try{
			$database->execute($sql, array($this->poll_id, $answer, 1) );
		} catch(PDOException $e) { //23000 error code means the key already exists, so UPDATE!
			if($e->getCode() == 23000) {
				try {
					$sql = "UPDATE poll SET votes = votes+1 WHERE poll_id = '$this->poll_id' AND option_id = '$answer'";
					$database->execute($sql); 
				} catch(PDOException $e) {
					echo "there was an error". $e->getCode();
				}
			} else {
				echo "there was an error". $e->getCode();
			}
		}
		
		if($database->affected_rows() >= 1) {  
			global $cookie;
			$id = $this->poll_id;
			$cookie->$id = 1;
			$this->voted = true;
		}	
	}	
	
}

?>
