<?php
	include ('../../../m_inc/m_config.php'); //mysql cridential
	include ('../../../m_class/class_lib.php');
	include ('../../../m_inc/def_value.php');
	include ('../../../m_inc/p_hash.php');
		
	$state = $_POST['state']; //state of processing
	$id = $_POST['id']; //consortID, OrgID, or GroupID
	$level = $_POST['level']; //license level
	$memberID = $_POST['mid']; //memberID
	$adminCon = "0";
	$adminCt = 0;
	$flag = "none";
	$remove = "";
	$empty = "NULL";
	
	if ($level == 3){
		try{//get consortiumID
			$result = $db->prepare("SELECT consortium_id FROM $db_member WHERE member_id=?");
			$result->execute(array($memberID)); 
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		foreach ($result as $row){
			$consortID = $row['consortium_id'];
		}
		try{//get consortium admin
			$result = $db->prepare("SELECT admin1,admin2 FROM $db_consortium WHERE consortium_id=?");
			$result->execute(array($consortID)); 
		} catch(PDOException $e) {
			echo "message002 - Sorry, system is experincing problem. Please check back.";
		}
		foreach ($result as $row){
			if ($row['admin1'] != "" or $row['admin1'] != NULL or $row['admin1'] != 0){
				$admin1 = $row['admin1'];
				if ($row['admin1'] == $memberID){
					$remove = "admin1";
				}
			}else{
				$admin1 = 0;
			}
			if ($row['admin2'] != "" or $row['admin2'] != NULL or $row['admin2'] != 0){
				$admin2 = $row['admin2'];
				if ($row['admin2'] == $memberID){
					$remove = "admin2";
				}
			}else{
				$admin2 = 0;
			}
		}
		
		if ($state == "yes"){
			try{
				$result = $db->prepare("SELECT member_id FROM $db_member WHERE consortium_id=?");
				$result->execute(array($consortID));
			} catch(PDOException $e) {
				echo "message003 - Sorry, system is experincing problem. Please check back.";
			}
			$itemCount = $result->rowCount();
			if ($itemCount > 0){
				foreach ($result as $row) {
					if ($row['member_id'] == $admin1 or $row['member_id'] == $admin2){
						$adminCt++; //count how many consortium admin
					}
					if ($row['member_id'] == $memberID){
						$admin = "1"; //self is admin
					}
				}
			}
			if ($adminCt < 2){
				if ($admin2 == 0){ //empty admin2
					$result = $db->prepare("UPDATE $db_consortium SET admin2=? WHERE consortium_id=?");
				}
				if ($admin1 == 0){ //empty admin1
					$result = $db->prepare("UPDATE $db_consortium SET admin1=? WHERE consortium_id=?");
				}
				try{//update member status
					$result->execute(array($memberID,$consortID));
				} catch(PDOException $e) {
					echo "message004 - Sorry, system is experincing problem. Please check back.";
				}
				$flag = "update";
			}else{
				$flag = "over";
			}
		}//end of state "yes"
		if ($state == "no"){
			if ($remove != ""){ //$remove musth not be empty
				if ($remove == "admin1"){
					$result = $db->prepare("UPDATE $db_consortium SET admin1=? WHERE consortium_id=?");
				}
				if ($remove == "admin2"){
					$result = $db->prepare("UPDATE $db_consortium SET admin2=? WHERE consortium_id=?");
				}
				try{//update member status
					$result->execute(array($empty,$consortID));
				} catch(PDOException $e) {
					echo "message005 - Sorry, system is experincing problem. Please check back.";
				}
				$flag = "remove";
			}
		}
	}//end of level 3 consortium processing
	if ($level == 2){
		try{//get consortium admin
			$result = $db->prepare("SELECT admin1,admin2 FROM $db_organization WHERE organization_id=?");
			$result->execute(array($id)); 
		} catch(PDOException $e) {
			echo "message002 - Sorry, system is experincing problem. Please check back.";
		}
		foreach ($result as $row){
			if ($row['admin1'] != "" or $row['admin1'] != NULL or $row['admin1'] != 0){
				$admin1 = $row['admin1'];
				if ($row['admin1'] == $memberID){
					$remove = "admin1";
				}
			}else{
				$admin1 = 0;
			}
			if ($row['admin2'] != "" or $row['admin2'] != NULL or $row['admin2'] != 0){
				$admin2 = $row['admin2'];
				if ($row['admin2'] == $memberID){
					$remove = "admin2";
				}
			}else{
				$admin2 = 0;
			}
		}//end foreach
		
		if ($state == "yes"){
			try{
				$result = $db->prepare("SELECT $db_group_rights.member_id FROM $db_organization,$db_group,$db_group_rights WHERE $db_organization.organization_id=? AND $db_organization.organization_id=$db_group.organization_id AND $db_group.group_id=$db_group_rights.group_id GROUP BY $db_group_rights.member_id");
				$result->execute(array($id));
			} catch(PDOException $e) {
				echo "message003 - Sorry, system is experincing problem. Please check back.";
			}
			$itemCount = $result->rowCount();
			if ($itemCount > 0){
				foreach ($result as $row) {
					if ($row['member_id'] == $admin1 or $row['member_id'] == $admin2){
						$adminCt++;
					}
					if ($row['member_id'] == $memberID){
						$admin = "1";
					}
				}
			}
			if ($adminCt < 2){
				if ($admin2 == 0){
					$result = $db->prepare("UPDATE $db_organization SET admin2=? WHERE organization_id=?");
				}
				if ($admin1 == 0){
					$result = $db->prepare("UPDATE $db_organization SET admin1=? WHERE organization_id=?");
				}
				try{//update member status
					$result->execute(array($memberID,$id));
				} catch(PDOException $e) {
					echo "message004 - Sorry, system is experincing problem. Please check back.";
				}
				$flag = "update";
			}else{
				$flag = "over";
			}
		}//end of state "yes"
		if ($state == "no"){
			if ($remove != ""){
				if ($remove == "admin1"){
					$result = $db->prepare("UPDATE $db_organization SET admin1=? WHERE organization_id=?");
				}
				if ($remove == "admin2"){
					$result = $db->prepare("UPDATE $db_organization SET admin2=? WHERE organization_id=?");
				}
				try{//update member status
					$result->execute(array($empty,$id));
				} catch(PDOException $e) {
					echo "message005 - Sorry, system is experincing problem. Please check back.";
				}
				$flag = "remove";
			}
		}
		
	}
	echo $flag;	
?>