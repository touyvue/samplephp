<?php
	////Get guest information////
	$guest_id = $_SESSION['p_start'];  //guest id
	$page_pre = $_SERVER['HTTP_REFERER']; //previous page
	$page_now = $active_page; //$_SERVER['SCRIPT_FILENAME'];  //current path page
	$datetime = date('Y-m-d-h-i-s-a');
	$datetime = (string)$datetime; //datetime stamp
	$ip_address = $_SERVER['REMOTE_ADDR'];  //ip address
	$guest_info = $_SERVER['HTTP_USER_AGENT'];  //user info
	$server_nam = $_SERVER['SERVER_NAME'];  //server name
	$request_met = $_SERVER['REQUEST_METHOD']; //how the page was requested

	function crawlerDetect($USER_AGENT) {    
	 	$crawlers_agents = 'Google|msnbot|Rambler|Yahoo|AbachoBOT|accoona|AcioRobot|ASPSeek|CocoCrawler|Dumbot|FAST-WebCrawler|GeonaBot|Gigabot|Lycos|MSRBOT|Scooter|AltaVista|IDBot|eStyle|Scrubby|Baiduspider|YandexBot';       
	 	if ( strpos($crawlers_agents , $USER_AGENT) === false )        
	 		return false;     
		else {        return array_search($USER_AGENT, $crawlers);     }     
	}   

	$crawler = crawlerDetect($_SERVER['HTTP_USER_AGENT']);   
	if ($crawler) {    
	 	// it is crawler, it's name in $crawler variable and do nothing 
	} 
	else{    
		try{
			$result = $db->prepare("INSERT INTO $db_guest(guest_id,page_pre,page_now,datetime,ip_address,guest_info,server_nam,request_met) VALUES (?,?,?,?,?,?,?,?)");
			$result->execute(array($guest_id,$page_pre,$page_now,$datetime,$ip_address,$guest_info,$server_nam,$request_met));
		} catch(PDOException $e) {
			echo "Sorry, the system is experiencing difficulty.";
		}
	}
?>