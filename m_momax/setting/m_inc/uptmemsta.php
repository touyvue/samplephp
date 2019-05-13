<?php
	include ('../../../m_inc/m_config.php'); //mysql cridential
	include ('../../../m_class/class_lib.php');
	include ('../../../m_inc/def_value.php');
	include ('../../../m_inc/p_hash.php');
	
	//org_rights is 0, nothing is active within this org for this member
	//group_rights is 0, nothing is active with this group for this member
	
	$memberID = $_POST['mid']; //memberID
	$active = $_POST['sta']; //assign active status
	$level = $_POST['level']; //license level
	$admin = $_POST['admin']; //consortium admin 
	$id = $_POST['id']; //consortID, OrgID, or GroupID
	$state = $_POST['state']; //state of processing
	$empty = "NULL";
	$flag = "none";
	
	if ($level == 3){
		try{//update member status
			$result = $db->prepare("UPDATE $db_member SET active=? WHERE member_id=?");
			$result->execute(array($active,$memberID));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		if ($admin == "yes" and $active == "no"){
			try{//get admin status
				$result = $db->prepare("SELECT $db_consortium.consortium_id,$db_consortium.admin1,$db_consortium.admin2 FROM $db_consortium,$db_member WHERE $db_member.member_id=? AND $db_member.consortium_id=$db_consortium.consortium_id");
				$result->execute(array($memberID)); 
			} catch(PDOException $e) {
				echo "message002 - Sorry, system is experincing problem. Please check back.";
			}
			$rights = 0;
			if ($active == "yes"){
				$rights = 1;
			}
			if ($active == "yes" and $admin == "yes"){
				$rights = 3;
			}
			try{//update member status
				$result = $db->prepare("UPDATE $db_group_rights SET org_rights=?,active=? WHERE member_id=?");
				$result->execute(array($rights,$active,$memberID));
			} catch(PDOException $e) {
				echo "message002 - Sorry, system is experincing problem. Please check back.";
			}
			foreach ($result as $row){
				$admin1 = $row['admin1'];
				$admin2 = $row['admin2'];
				$consortID = $row['consortium_id'];
			}
			if ($admin1 == $memberID){
				$result = $db->prepare("UPDATE $db_consortium SET admin1=? WHERE consortium_id=?");
			}
			if ($admin2 == $memberID){
				$result = $db->prepare("UPDATE $db_consortium SET admin2=? WHERE consortium_id=?");
			}
			try{//update consortium admin status
				$result->execute(array($empty,$consortID));
			} catch(PDOException $e) {
				echo "message003 - Sorry, system is experincing problem. Please check back.";
			}
			$flag = "no admin";
		}
	}
	if ($level == 2){
		try{//update member status
			$result = $db->prepare("UPDATE $db_member SET active=? WHERE member_id=?");
			$result->execute(array($active,$memberID));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		$rights = 0;
		if ($active == "yes"){
			$rights = 1;
		}
		if ($active == "yes" and $admin == "yes"){
			$rights = 3;
		}
		try{//update member status
			$result = $db->prepare("UPDATE $db_group_rights SET org_rights=?,active=? WHERE member_id=?");
			$result->execute(array($rights,$active,$memberID));
		} catch(PDOException $e) {
			echo "message002 - Sorry, system is experincing problem. Please check back.";
		}
		if ($active == "no" and $admin == "yes"){//turn off Org admin
			try{//get admin status
				$result = $db->prepare("SELECT admin1,admin2 FROM $db_organization WHERE organization_id=?");
				$result->execute(array($id)); 
			} catch(PDOException $e) {
				echo "message002 - Sorry, system is experincing problem. Please check back.";
			}
			foreach ($result as $row){
				$admin1 = $row['admin1'];
				$admin2 = $row['admin2'];
			}
			if ($admin1 == $memberID){
				$result = $db->prepare("UPDATE $db_organization SET admin1=? WHERE organization_id=?");
			}
			if ($admin2 == $memberID){
				$result = $db->prepare("UPDATE $db_organization SET admin2=? WHERE organization_id=?");
			}
			try{//update consortium admin status
				$result->execute(array($empty,$id));
			} catch(PDOException $e) {
				echo "message003 - Sorry, system is experincing problem. Please check back.";
			}
			$flag = "no admin";
		}
	}
	echo $flag; //all executions are good.	
?>