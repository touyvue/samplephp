<?php
	include ('../../../m_inc/m_config.php'); //mysql cridential
	include ('../../../m_class/class_lib.php');
	include ('../../../m_inc/def_value.php');
	
	$memberID = $_POST['memID']; //memberID
	$groupID = $_POST['groupID']; //GroupID
	$state = $_POST['state']; //state of processing
	$no = "no";
	$yes = "yes";
	$good = "no";
	
	try{//get all members for the given groupID
		$result = $db->prepare("SELECT member_id FROM $db_group_rights WHERE group_id=?");
		$result->execute(array($groupID)); 
	} catch(PDOException $e) {
		echo "message001 - Sorry, system is experincing problem. Please check back.";
	}
	$itemCount = $result->rowCount();
	if ($itemCount > 1){	
		try{//delete account
			$result = $db->prepare("DELETE FROM $db_group_rights WHERE member_id=? AND group_id=?");
			$result->execute(array($memberID,$groupID));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		$good = "yes";
	}else{
		$good = "no";
	}

	echo $good; //all executions are good.	
?>