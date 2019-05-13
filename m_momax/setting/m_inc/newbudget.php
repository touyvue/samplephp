<?php
	include ('../../../m_inc/m_config.php'); //mysql cridential
	include ('../../../m_class/class_lib.php');
	include ('../../../m_inc/def_value.php');
		
	$budgetTotInst = new budgetInfoC();
	$accountInfo = new accountInfoC();
	
	$memberID = $_POST['mid'];
	$state = $_POST['state'];
	$active = "yes";
	$no = "no";
	
	if ($state == "add"){
		$budName = $_POST['budName'];
		$budName = str_replace('"', "", $budName); //remove " from note
		$budDesc = $_POST['budDesc'];
		$budDesc = str_replace('"', "", $budDesc); //remove " from note
		try{//get new budgetID
			$result = $db->prepare("SELECT list_order FROM $db_budget WHERE member_id=? ORDER BY list_order ASC");
			$result->execute(array($memberID)); 
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		$itemCount = $result->rowCount();
		$itemCount++;
		try{
			$result = $db->prepare("INSERT INTO $db_budget (name,list_order,description,member_id,chartyn,active) VALUES (?,?,?,?,?,?)");
			$result->execute(array($budName,$itemCount,$budDesc,$memberID,$no,$active));
		} catch(PDOException $e) {
			echo "message002 - Sorry, system is experincing problem. Please check back.";
		}
		try{//get new budgetID
			$result = $db->prepare("SELECT budget_id FROM $db_budget WHERE member_id=? ORDER BY budget_id ASC");
			$result->execute(array($memberID)); 
		} catch(PDOException $e) {
			echo "message003 - Sorry, system is experincing problem. Please check back.";
		}
		foreach ($result as $row){
			$budgetID = $row['budget_id'];
		}
		if ($budgetID != ""){
			try{//update budget details
				$result = $db->prepare("INSERT INTO $db_budget_rights (member_id,access_rights,budget_id,active) VALUES (?,?,?,?)");
				$result->execute(array($memberID,3,$budgetID,$active));
			} catch(PDOException $e) {
				echo "message004 - Sorry, system is experincing problem. Please check back.";
			}
		}	
		echo "done";
	}//end of add state
	
	//get budget info
	if ($state == "getdata"){
		$budgetID = $_POST['budID'];
	
		try{//get new budgetID
			$result = $db->prepare("SELECT budget_id,name,description FROM $db_budget WHERE member_id=? AND budget_id=?");
			$result->execute(array($memberID,$budgetID)); 
		} catch(PDOException $e) {
			echo "message005 - Sorry, system is experincing problem. Please check back.";
		}
		foreach ($result as $row){
			$budgerInfo = '{"id":"'.$row['budget_id'].'","name":"'.str_replace('\\','',$row['name']).'","desc":"'.str_replace('\\','',$row['description']).'"}';
		}
		echo $budgerInfo;
	}
	
	//update budget info
	if ($state == "edit"){
		$budgetID = $_POST['budID'];
		
		$budName = $_POST['budName'];
		$budName = str_replace('"', "", $budName); //remove " from note
		$budDesc = $_POST['budDesc'];
		$budDesc = str_replace('"', "", $budDesc); //remove " from note
		
		try{//update budget details
			$result = $db->prepare("UPDATE $db_budget SET name=?,description=? WHERE budget_id=?");
			$result->execute(array($budName,$budDesc,$budgetID));
		} catch(PDOException $e) {
			echo "message006 - Sorry, system is experincing problem. Please check back.";
		}
	}
	
	//delete budgetlist
	if ($state == "delete"){
		$budgetID = $_POST['budID'];
		$budName = $_POST['budName'];
		$budDesc = $_POST['budDesc'];
				
		try{//delete budgetlist
			$result = $db->prepare("DELETE FROM $db_budget WHERE budget_id=? AND member_id=?");
			$result->execute(array($budgetID,$memberID));
		} catch(PDOException $e) {
			echo "message007 - Sorry, system is experincing problem. Please check back.";
		}
		try{//delete budgetlist details
			$result = $db->prepare("DELETE FROM $db_budget_rights WHERE budget_id=?");
			$result->execute(array($budgetID));
		} catch(PDOException $e) {
			echo "message008 - Sorry, system is experincing problem. Please check back.";
		}
		
		//delete all budgetlist items tie to this specific budget
		try{
			$result = $db->prepare("SELECT budgetlist_id FROM $db_budgetlist WHERE member_id=? AND budget_id=?");
			$result->execute(array($memberID,$budgetID)); //get recurring info
		} catch(PDOException $e) {
			echo "message009 - Sorry, system is experincing problem. Please check back.";
		}
		foreach ($result as $row){
			try{//delete budgetlist_rights_id
				$result = $db->prepare("DELETE FROM $db_budgetlist_rights WHERE budgetlist_id=?");
				$result->execute(array($row['budgetlist_id']));
			} catch(PDOException $e) {
				echo "message010 - Sorry, system is experincing problem. Please check back.";
			}
		}
		try{//delete budgetlist
			$result = $db->prepare("DELETE FROM $db_budgetlist WHERE budget_id=? AND member_id=?");
			$result->execute(array($budgetID,$memberID));
		} catch(PDOException $e) {
			echo "message011 - Sorry, system is experincing problem. Please check back.";
		}
		
	}
?>