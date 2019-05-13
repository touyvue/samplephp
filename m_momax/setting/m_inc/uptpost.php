<?php
	include ('../../../m_inc/m_config.php'); //mysql cridential
	include ('../../../m_class/class_lib.php');
	include ('../../../m_inc/def_value.php');
			
	$memberID = $_POST['mid'];
	$state = $_POST['state'];
	$rights = $_POST['rights'];
	$rightsCt = $_POST['rightsCt'];
	$rightsArr = $_POST['rightsArr'];
	$active = "yes";
	$foundItems = 0;
	try{//check if post_rights is existed.
		$result = $db->prepare("SELECT member_id FROM $db_post_rights WHERE member_id=? AND active=?");
		$result->execute(array($memberID,$yeVal));
	} catch(PDOException $e) {
		echo "message001 - Sorry, system is experincing problem. Please check back.";
	}
	$foundItems = $result->rowCount(); 
	if ($foundItems < 1){
		try{//insert new group rights
			$result = $db->prepare("INSERT INTO $db_post_rights (rights_type_id,member_id,active) VALUES (?,?,?)");
			$result->execute(array($rights,$memberID,$active));
		} catch(PDOException $e) {
			echo "message002 - Sorry, system is experincing problem. Please check back.";
		}
	}
	if ($foundItems > 0){
		try{//update project details
			$result = $db->prepare("UPDATE $db_post_rights SET rights_type_id=? WHERE member_id=?");
			$result->execute(array($rights,$memberID));
		} catch(PDOException $e) {
			echo "message003 - Sorry, system is experincing problem. Please check back.";
		}
		try{//delete all group rights
			$result = $db->prepare("DELETE FROM $db_post_group WHERE member_id=?");
			$result->execute(array($memberID));
		} catch(PDOException $e) {
			echo "message004 - Sorry, system is experincing problem. Please check back.";
		}
	}
	if ($rights == 2){
		if ($rightsCt > 1){
			$grpIDsArr = array();
			$grpIDsArr = explode(",",$rightsArr);
			$grpIDsLength = count($grpIDsArr);
			for ($i=0; $i<$grpIDsLength; $i++){
				try{//insert new group rights
					$result = $db->prepare("INSERT INTO $db_post_group (group_id,member_id,active) VALUES (?,?,?)");
					$result->execute(array($grpIDsArr[$i],$memberID,$active));
				} catch(PDOException $e) {
					echo "message005 - Sorry, system is experincing problem. Please check back.";
				}
			}
		}else{
			$grpID = $rightsArr;
			try{//insert new group rights
				$result = $db->prepare("INSERT INTO $db_post_group (group_id,member_id,active) VALUES (?,?,?)");
				$result->execute(array($grpID,$memberID,$active));
			} catch(PDOException $e) {
				echo "message006 - Sorry, system is experincing problem. Please check back.";
			}
		}
	}
	echo "done";
?>