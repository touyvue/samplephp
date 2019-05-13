<?php
	include ('../../../m_inc/m_config.php'); //mysql cridential
	include ('../../../m_class/class_lib.php');
	include ('../../../m_inc/def_value.php');
		
	$budgetTotInst = new budgetInfoC();
	$accountInfo = new accountInfoC();
	
	$memberID = $_POST['mid'];
	$state = $_POST['state'];
	$active = "yes";
	$todayDate = date('Y-m-d');
	
	if ($state == "add"){
		$projName = $_POST['projName'];
		$projName = str_replace('"', "", $projName); //remove " from note
		$projDesc = $_POST['projDesc'];
		$projDesc = str_replace('"', "", $projDesc); //remove " from note
		
		try{//update project details
			$result = $db->prepare("INSERT INTO $db_project (member_id,name,date,note,active) VALUES (?,?,?,?,?)");
			$result->execute(array($memberID,$projName,$todayDate,$projDesc,$active));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		try{//get new accountID
			$result = $db->prepare("SELECT project_id FROM $db_project WHERE member_id=? ORDER BY project_id ASC");
			$result->execute(array($memberID)); 
		} catch(PDOException $e) {
			echo "message002 - Sorry, system is experincing problem. Please check back.";
		}
		foreach ($result as $row){
			$projectID = $row['project_id'];
		}
		if ($projectID != ""){
			try{//update project details
				$result = $db->prepare("INSERT INTO $db_project_rights (member_id,access_rights,project_id,active) VALUES (?,?,?,?)");
				$result->execute(array($memberID,3,$projectID,$active));
			} catch(PDOException $e) {
				echo "message003 - Sorry, system is experincing problem. Please check back.";
			}
		}	
		echo "done";
	}//end of add state
	
	//get account info
	if ($state == "getdata"){
		$projectID = $_POST['projID'];
	
		try{//get new accountID
			$result = $db->prepare("SELECT project_id,name,note FROM $db_project WHERE member_id=? AND project_id=?");
			$result->execute(array($memberID,$projectID)); 
		} catch(PDOException $e) {
			echo "message004 - Sorry, system is experincing problem. Please check back.";
		}
		foreach ($result as $row){
			$projectInfo = '{"id":"'.$row['project_id'].'","name":"'.str_replace('\\','',$row['name']).'","desc":"'.str_replace('\\','',$row['note']).'"}';
		}
		echo $projectInfo;
	}
	
	//update account info
	if ($state == "edit"){
		$projectID = $_POST['projID'];
		
		$projName = $_POST['projName'];
		$projName = str_replace('"', "", $projName); //remove " from note
		$projDesc = $_POST['projDesc'];
		$projDesc = str_replace('"', "", $projDesc); //remove " from note
		
		try{//update project details
			$result = $db->prepare("UPDATE $db_project SET name=?,note=? WHERE project_id=?");
			$result->execute(array($projName,$projDesc,$projectID));
		} catch(PDOException $e) {
			echo "message005 - Sorry, system is experincing problem. Please check back.";
		}
	}
	
	//delete account
	if ($state == "delete"){
		$projectID = $_POST['projID'];
		$projName = $_POST['projName'];
		$projDesc = $_POST['projDesc'];
		
		//delete project and project details, but not record in budtransaction and transaction!
		try{//delete account
			$result = $db->prepare("DELETE FROM $db_project WHERE project_id=? AND member_id=?");
			$result->execute(array($projectID,$memberID));
		} catch(PDOException $e) {
			echo "message006 - Sorry, system is experincing problem. Please check back.";
		}
		try{//delete account details
			$result = $db->prepare("DELETE FROM $db_project_rights WHERE project_id=?");
			$result->execute(array($projectID));
		} catch(PDOException $e) {
			echo "message007 - Sorry, system is experincing problem. Please check back.";
		}
		try{//delete account
			$result = $db->prepare("DELETE FROM $db_account WHERE project_id=? AND member_id=?");
			$result->execute(array($projectID,$memberID));
		} catch(PDOException $e) {
			echo "message008 - Sorry, system is experincing problem. Please check back.";
		}
	}
?>