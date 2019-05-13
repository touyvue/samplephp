<?php
	include ('../../../m_inc/m_config.php'); //mysql cridential
	include ('../../../m_class/class_lib.php');
	include ('../../../m_inc/def_value.php');
		
	$groupID = $_POST['groupID']; //GroupID
	$state = $_POST['state']; //state of processing
	$no = "no";
	$yes = "yes";
	
	try{//update group status
		$result = $db->prepare("UPDATE $db_group SET active=? WHERE group_id=?");
		$result->execute(array($no,$groupID));
	} catch(PDOException $e) {
		echo "message001 - Sorry, system is experincing problem. Please check back.";
	}
	try{//update group_rights status
		$result = $db->prepare("UPDATE $db_group_rights SET org_rights=?,group_rights=?,active=? WHERE group_id=?");
		$result->execute(array(0,0,$no,$groupID));
	} catch(PDOException $e) {
		echo "message001 - Sorry, system is experincing problem. Please check back.";
	}

	echo "good"; //all executions are good.	
?>