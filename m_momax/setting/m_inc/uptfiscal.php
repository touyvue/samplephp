<?php
	include ('../../../m_inc/m_config.php'); 
	include ('../../../m_class/class_lib.php');
	include ('../../../m_inc/def_value.php');
			
	$memberID = $_POST['memberID'];
	$orgID = $_POST['orgID'];
	$startMon = $_POST['startMon'];
	$state = $_POST['state'];
	$active = "yes";
	
	for($i=0; $i<11; $i++){
		if ($i == 0){
			$endMon = $startMon + 1;
			if ($startMon == 12){
				$endMon = 1;
			}
		}else{
			$endMon = $endMon + 1;
		}
		if ($endMon == 12){
			$endMon = 0;
		}
	}
	if ($endMon == 0){
		$endMon = 12;
	}
	try{//update project details
		$result = $db->prepare("UPDATE $db_setting SET begin_fiscal_mon=?,end_fiscal_mon=? WHERE organization_id=?");
		$result->execute(array($startMon,$endMon,$orgID));
	} catch(PDOException $e) {
		echo "message003 - Sorry, system is experincing problem. Please check back.";
	}

	echo "done";
?>