<?php	
	////set javascript file base on active page id
	switch ($active_page){
		case "mx00100": //main page
		  	$js_file = 'm_js/mx_js/mx_100.js';
		  	break;
		case "mx001lg": 
		  	$js_file = 'm_js/mx_js/mx_1lg.js';
		  	break;
		case "mx001fp": 
		  	$js_file = 'm_js/mx_js/mx_1lp.js';
		  	break;
		case "mx001hp": 
		  	$js_file = 'm_js/mx_js/mx_1hp.js';
			$js_base = 'm_js/mx_js/mx_100.js';
		  	break;
		case "mx002gb": 
		  	$js_file = 'm_js/mx_js/mx_2gb.js';
			$js_base = 'm_js/mx_js/mx_100.js';
		  	break;
		case "mx002ab": 
		  	$js_file = 'm_js/mx_js/mx_2ab.js';
		  	$js_base = 'm_js/mx_js/mx_100.js';
		  	break;
		case "mx002mb": 
		  	//$js_file = 'm_js/mx_js/mx_2mb.js';
		  	$js_base = 'm_js/mx_js/mx_100.js';
		  	break;
		case "mx002pb": 
		  	$js_file = 'm_js/mx_js/mx_2ab.js';
		  	$js_base = 'm_js/mx_js/mx_100.js';
		  	break;
		case "mx002pj": 
		  	$js_file = 'm_js/mx_js/mx_2pj.js';
			$js_base = 'm_js/mx_js/mx_100.js';
		  	break;
		case "mx002eb": 
		  	$js_file = 'm_js/mx_js/mx_2eb.js';
		  	$js_base = 'm_js/mx_js/mx_100.js';
		  	break;
		case "mx00300":
			$js_base = 'm_js/mx_js/mx_100.js';
			$js_file = 'm_js/mx_js/mx_3ac.js';
			break;
		case "mx003ac":
			$js_file = 'm_js/mx_js/mx_3ac.js';
		  	$js_base = 'm_js/mx_js/mx_100.js';
			break;
		case "mx003at": 
		  	$js_file = 'm_js/mx_js/mx_3at.js';
		  	$js_base = 'm_js/mx_js/mx_100.js';
		  	break;
		case "mx003et": 
		  	$js_file = 'm_js/mx_js/mx_3et.js';
		  	$js_base = 'm_js/mx_js/mx_100.js';
		  	break;
		case "mx003dt": 
		  	$js_file = 'm_js/mx_js/mx_3dt.js';
		  	$js_base = 'm_js/mx_js/mx_100.js';
		  	break;
		case "mx00400":
			$js_base = 'm_js/mx_js/mx_100.js';
		  	break;
		case "mx004ba"://budget 001 report
			$js_base = 'm_js/mx_js/mx_100.js';
		  	break;
		case "mx004aa"://budget 001 report
			$js_base = 'm_js/mx_js/mx_100.js';
		  	break;
		case "mx004ct"://budget 001 report
			$js_base = 'm_js/mx_js/mx_100.js';
			$js_file = 'm_js/mx_js/mx_4ct.js';
		  	break;
		case "mx004tk"://budget 001 report
			$js_base = 'm_js/mx_js/mx_100.js';
			$js_file = 'm_js/mx_js/mx_4rp.js';
		  	break;
		case "mx00500": //admin general
			$js_file = 'm_js/mx_js/mx_5st.js';
			$js_base = 'm_js/mx_js/mx_100.js';
		  	break;
		case "mx005as": //account setting
			$js_file = 'm_js/mx_js/mx_5as.js';
			$js_base = 'm_js/mx_js/mx_100.js';
		  	break;
		case "mx005bl": //budget setting
			$js_file = 'm_js/mx_js/mx_5bl.js';
			$js_base = 'm_js/mx_js/mx_100.js';
		  	break;
		case "mx005bs": //budget setting
			$js_file = 'm_js/mx_js/mx_5bs.js';
			$js_base = 'm_js/mx_js/mx_100.js';
		  	break;
		case "mx005ps": //project setting
			$js_file = 'm_js/mx_js/mx_5ps.js';
			$js_base = 'm_js/mx_js/mx_100.js';
		  	break;
		case "mx005cs": //category setting
			$js_file = 'm_js/mx_js/mx_5cs.js';
			$js_base = 'm_js/mx_js/mx_100.js';
		  	break;
		case "mx005pu": //profile setting
			$js_file = 'm_js/mx_js/mx_5pu.js';
			$js_base = 'm_js/mx_js/mx_100.js';
		  	break;
		case "mx005gs": //profile setting
			$js_file = 'm_js/mx_js/mx_5gs.js';
			$js_base = 'm_js/mx_js/mx_100.js';
		  	break;
		case "mx005ct": //profile setting
			$js_file = 'm_js/mx_js/mx_5gs.js';
			$js_base = 'm_js/mx_js/mx_100.js';
		  	break;
		case "mx005ot": //profile setting
			$js_file = 'm_js/mx_js/mx_5gs.js';
			$js_base = 'm_js/mx_js/mx_100.js';
		  	break;
		case "mx005gt": //profile setting
			$js_file = 'm_js/mx_js/mx_5gs.js';
			$js_base = 'm_js/mx_js/mx_100.js';
		  	break;
		case "mx005sa": //account sharing
			$js_base = 'm_js/mx_js/mx_100.js';
			$js_file = 'm_js/mx_js/mx_5sa.js';
		  	break;
		case "mx005sb": //account sharing
			$js_base = 'm_js/mx_js/mx_100.js';
			$js_file = 'm_js/mx_js/mx_5sa.js';
		  	break;
		case "mx005sp": //account sharing
			$js_base = 'm_js/mx_js/mx_100.js';
			$js_file = 'm_js/mx_js/mx_5sa.js';
		  	break;
		case "mx005pt": //account sharing
			$js_base = 'm_js/mx_js/mx_100.js';
		  	break;
		case "mx005tc": //account sharing
			$js_base = 'm_js/mx_js/mx_100.js';
			$js_file = 'm_js/mx_js/mx_5tc.js';
		  	break;
		case "mx005tt": //set tag
			$js_base = 'm_js/mx_js/mx_100.js';
			$js_file = 'm_js/mx_js/mx_5tt.js';
		  	break;
		case "mx006pj":
			$js_dat_file = 'm_js/mx_js/mx_6pj.js';
			$js_base = 'm_js/mx_js/mx_100.js';
		  	break;
		case "mx00700":
			$js_base = 'm_js/mx_js/mx_100.js';
		  	$js_file = 'm_js/mx_js/mx_700.js';
			break;
		case "mx007fb":
			$js_base = 'm_js/mx_js/mx_100.js';
		  	$js_file = 'm_js/mx_js/mx_700.js';
			break;
		case "mx007tb":
			$js_base = 'm_js/mx_js/mx_100.js';
		  	$js_file = 'm_js/mx_js/mx_700.js';
			break;
		case "mx007fq":
			$js_base = 'm_js/mx_js/mx_100.js';
		  	break;
		case "mx00900":
			$js_file = 'm_js/mx_js/mx_9ml.js';
			$js_base = 'm_js/mx_js/mx_100.js';
		  	break;
		case "mx009gt":
			$js_file = 'm_js/mx_js/mx_9ml.js';
			$js_base = 'm_js/mx_js/mx_100.js';
		  	break;
		case "mx009ml":
			$js_file = 'm_js/mx_js/mx_9ml.js';
			$js_base = 'm_js/mx_js/mx_100.js';
		  	break;
		case "mx009mt":
			$js_file = 'm_js/mx_js/mx_9ml.js';
			$js_base = 'm_js/mx_js/mx_100.js';
		  	break;
		default: //main page
		  	$js_file = 'm_js/mx_js/mx_100.js';
	}
?>