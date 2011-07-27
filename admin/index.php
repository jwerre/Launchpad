<?php
	include '../lib/initialize.php';	
    include '../lib/helper/gapi.class.php';
	
    define('VIMEO_ID', 'user7117833');
    define('VID_WIDTH', '450');
    define('VID_HEIGHT', '253');

    $ga_email = isset($_POST['ga_email']) ? $_POST['ga_email'] : $cookie->ga_email;
    $ga_password = isset($_POST['ga_password']) ? $_POST['ga_password'] : $cookie->ga_password;
    if ( !empty($ga_email) && !empty($ga_password) ) {
        try{
            $ga = new gapi($ga_email, $ga_password);
        } catch (Exception $error) {
            unset($cookie->ga_email);
            unset($cookie->ga_password);
			$session->msg_type = 'error_msg';
			$session->message('Could not log into your Google account using that username and password.');
			redirect_to('index.php');
        }

		if ( isset( $_POST['ga_remember'] ) ) {
			$cookie->ga_email = $ga_email;
			$cookie->ga_password = $ga_password;
		}
	}
	include_layout("header.php", "layouts");
	$user = User::find_by_id($session->user_id);
?>
	<h1>Home</h1>
<?php
    if ( isset($ga) && !empty($ga) ) {

        $ga->requestAccountData();
        $ga_results = $ga->getResults(); 
		$this_url = parse_url(BASE_URL, PHP_URL_HOST);

		foreach($ga_results as $result)
		{
			if ( $result == $this_url ) {
				$site_id = $result->getProfileId();
			}
		}

        if(isset($site_id)){
            $today =  date("Y-m-d");
            $last_month = date("Y-m-d", strtotime( date("Y-m-d", strtotime($today)) . "-1 month" ) );
            $ga->requestReportData($site_id,array('date', "month", "day", "year"),array('visitors','pageviews','visits','newVisits', 'percentNewVisits', 'avgTimeOnSite','entranceBounceRate'),array('date'),NULL, $last_month, $today);

            $graph_data = array();
            $visitors_data = array();
            // $pageviews_data = array();
            foreach($ga->getResults() as $result)
            {
                $visitors_data['data'][] =  array(strtotime($result->getDate())*1000, $result->getVisits() );
                // $pageviews_data['data'][] =  array(strtotime($result->getDate())*1000, $result->getPageViews() );
                // $visitors_data['label'] = 'Unique Visits';
                // $pageviews_data['label'] = 'Page Views';
            }
            $graph_data[0] = $visitors_data;
            // $graph_data[1] = $pageviews_data;
            $output = '<script type="text/javascript" charset="utf-8">';
            $output .= 'var gaData = '. json_encode( $graph_data ).';';
            $output .= '</script/>';
            $output .= '<div class="section_box">';

            $output .= '<h3>Site Usage <small class="right">'.date("F jS, Y", strtotime( date("Y-m-d", strtotime($today)) . "-1 month" ) ).' - '.date("F jS, Y").'</small></h3>';
            $output .= '<div id="visitor_graph" style="height:300px;"></div>';

            $output .= '<ul class="ga_totals clearfix">';
            $output .= '<li><strong>Page Views</strong> ' . $ga->getPageviews() . '</li>';
            $output .= '<li><strong>Visits</strong> ' . $ga->getVisits() . '</li>';
            // $output .= '<li><strong>Unique Visits</strong> ' .$ga->getVisitors().'</li>';
            $output .= '<li><strong>New Visits</strong> ' .$ga->getNewVisits().'</li>';
            $output .= '<li><strong>Percent New Visits</strong> ' .round($ga->getPercentNewVisits(), 2).'%</li>';
            $output .= '<li><strong>Average Time on Site</strong> ' .date("H:i:s", mktime( 0,0, $ga->getAvgTimeOnSite(),0,0,0 )  ).'</li>';
            $output .= '<li><strong>Bouce Rate</strong> ' .round($ga->getEntranceBounceRate(), 2).'%</li>';
			$output .= '<li><a class="big_btn" href="http://www.google.com/analytics" target="_blank">Visit Google Analytics</a></li>';
			$output .= '</ul>';

            $output .= '</div>';
            echo $output;
        }else{
			$output = '<div id="message" class="hidden error_msg"><p>Could not find Analytics for '.$this_url;
			$output .= ( !empty($ga_results) ) ? '</p><p>You are currently signed up for the following site: '. implode(', ', $ga_results) : "";
			$output .= '</p></div>'; 
			echo $output;
        }

    }else {
?>
    <div class="section_box">
        <h3>Google Analytics</h3>
        <div class="info">
            <h4>What are Google Analytics?</h4>
            <p>Google Analytics is a web analytics solution that gives you rich insights into your website traffic. <a href="http://www.google.com/analytics" target="_blank">Get started</a></p>
        </div>
        <form action="" method="post" accept-charset="utf-8">
            <fieldset>
            <p class="half">
            <label for="ga_email">Google Analytics Username</label>
            <input type="text" name="ga_email" value="<?php echo $user->email?>">
            </p>
            <p class="half">
            <label for="ga_password">Google Analytics Password</label>
            <input type="password" name="ga_password" value="">
            </p>
            <p>
            <label for="ga_remember">Remeber my account details</label><input type="checkbox" name="ga_remember" value="1" id="ga_remember">
            </p>
            <p><button type="submit" >Submit</button></p>
            </fieldset>
        </form>
    </div>
<?php } ?>

<?php
	$playlist_xml = 'http://vimeo.com/api/v2/'.VIMEO_ID.'/all_videos.xml';
	if( function_exists('php_network_getaddresses')){ // TODO: make sure this works with internet connection
		$playlist = simplexml_load_file($playlist_xml);
	}
?>
    <div id="tutorials" class="section_box">
        <h3>Tutorials</h3>

<?php if ( !isset($playlist) || empty($playlist)) { 
    echo '<div id="message" class="error_msg"><p>Could not find the data for tutorials at: You can view the tutorials at <a href="http://www.vimeo.com/launchpad">http://www.vimeo.com/launchpad<a></p></div>'; 
}else {
    $output = '<ul>';
	if(count($playlist['video']) > 0){
    foreach ($playlist as $video){
        $output .= '<li>';
        $output .= '<a href="?v_id='.$video->id.'&ap=1">';
        $output .= $video->title;
        $output .= '</a></li>';
	}
	}else{
        $output .= '<li>Sorry, There are no tutorial videos at this time.</li>';
	}
    $output .= '</ul>';
    $output .= '<div class="video_player"><iframe src="http://player.vimeo.com/video/';
    $output .= (isset($_GET['v_id'])) ? $_GET['v_id'] : $playlist->video[0]->id; 
    $output .= (isset($_GET['ap'])) ? '?autoplay='.$_GET['ap'] : ""; 
    $output .= '"';
    $output .= 'width="'.VID_WIDTH.'" height="'.VID_HEIGHT.'" frameborder="0"></iframe></div>';
    echo $output;
}
?>
    </div>

<?php
	include_layout("footer.php" ,"layouts");
?>
