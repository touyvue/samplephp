<?php
	include ('../../../m_inc/m_config.php'); //mysql cridential
	include ('../../../m_class/class_lib.php');
	include ('../../../m_inc/def_value.php');
	include ('../../../m_inc/p_hash.php');
		
	$state = $_POST['state'];
	$num = $_POST['num'];
	$myMemberID = $_POST['mid'];
	$searchMemberID = $_POST['sid'];
	$yeVal = "yes";
	
	if ($state == "info"){
		$memberInfo = "";
		$accountRights = "";
		$accountList = "";
		$myAccounts = array();
		$myAccountsCt = 0;
		if ($num == 1){//process share accounts
			try{//create account list				
				$result = $db->prepare("SELECT account_id,name FROM $db_account WHERE member_id=? AND active=? ORDER BY name");
				$result->execute(array($myMemberID,$yeVal));
			} catch(PDOException $e) {
				echo "message001 - Sorry, system is experincing problem. Please check back.";
			}
			$itemCount = $result->rowCount();
			if ($itemCount > 0){
				foreach ($result as $row){
					$myAccounts[$myAccountsCt] = $row['account_id'];
					$myAccountsCt++;
				}
			}
		}
		if ($num == 2){//process share budgets
			try{//create account list				
				$result = $db->prepare("SELECT budget_id,name FROM $db_budget WHERE member_id=? AND active=? ORDER BY name");
				$result->execute(array($myMemberID,$yeVal));
			} catch(PDOException $e) {
				echo "message001 - Sorry, system is experincing problem. Please check back.";
			}
			$itemCount = $result->rowCount();
			if ($itemCount > 0){
				foreach ($result as $row){
					$myAccounts[$myAccountsCt] = $row['budget_id'];
					$myAccountsCt++;
				}
			}
		}
		if ($num == 3){//process share budgets
			try{//create account list				
				$result = $db->prepare("SELECT project_id,name FROM $db_project WHERE member_id=? AND active=? ORDER BY name");
				$result->execute(array($myMemberID,$yeVal));
			} catch(PDOException $e) {
				echo "message001 - Sorry, system is experincing problem. Please check back.";
			}
			$itemCount = $result->rowCount();
			if ($itemCount > 0){
				foreach ($result as $row){
					$myAccounts[$myAccountsCt] = $row['project_id'];
					$myAccountsCt++;
				}
			}
		}
		$myAccountsLength = count($myAccounts);
		$accountRights = $accountRights."{";
		$accountList = $accountList."{";
		for ($i=0; $i<$myAccountsLength; $i++){
			try{
				if ($num == 1){//process share accounts
					$result = $db->prepare("SELECT access_rights FROM $db_account_rights WHERE member_id=? AND account_id=? AND active=?");
				}
				if ($num == 2){//process share budgets
					$result = $db->prepare("SELECT access_rights FROM $db_budget_rights WHERE member_id=? AND budget_id=? AND active=?");
				}
				if ($num == 3){//process share budgets
					$result = $db->prepare("SELECT access_rights FROM $db_project_rights WHERE member_id=? AND project_id=? AND active=?");
				}
				$result->execute(array($searchMemberID,$myAccounts[$i],$yeVal));
			} catch(PDOException $e) {
				echo "message002 - Sorry, system is experincing problem. Please check back.";
			}
			$accessRights = 0;
			$itemCount = $result->rowCount();
			if ($itemCount > 0){
				foreach ($result as $row) {
					$accessRights = $row['access_rights'];
					if ($i > 0){
						$accountRights = $accountRights.",";
						$accountList = $accountList.",";
					}
					$accountRights = $accountRights.'"a'.$i.'":"'.$accessRights.'"';
					$accountList = $accountList.'"a'.$i.'":"'.$myAccounts[$i].'"';
				}
			}else{
				if ($i > 0){
					$accountRights = $accountRights.",";
					$accountList = $accountList.",";
				}
				$accountRights = $accountRights.'"a'.$i.'":"'.$accessRights.'"';
				$accountList = $accountList.'"a'.$i.'":"'.$myAccounts[$i].'"';
			}
		}	
		$accountRights = $accountRights."}";
		$accountList = $accountList."}";
		
		try{//get new accountID
			$result = $db->prepare("SELECT first_name,last_name,email FROM $db_member WHERE member_id=?");
			$result->execute(array($searchMemberID)); //get recurring info
		} catch(PDOException $e) {
			echo "message003 - Sorry, system is experincing problem. Please check back.";
		}
		foreach ($result as $row){
			$memberInfo = '{"first":"'.str_replace('\\','',$row['first_name']).'","last":"'.str_replace('\\','',$row['last_name']).'","email":"'.$row['email'].'"}';
		}
		echo $memberInfo."<=>".$accountRights."<#>".$accountList;
	}
	if ($state == "change"){
		$processIDs = array();
		$allid = $_POST['allid'];
		$processIDs = explode(",",$allid);
		$processIDsLength = count($processIDs);
		
		$processRights = array();
		$rights = $_POST['rights'];
		$processRights = explode(",",$rights);
		$processRightsLength = count($processRights);
		
		for ($i=0; $i<$processIDsLength; $i++){
			try{//delete old rights
				if ($num == 1){//process share accounts
					$result = $db->prepare("DELETE FROM $db_account_rights WHERE member_id=? AND account_id=?");
				}
				if ($num == 2){//process share budgets
					$result = $db->prepare("DELETE FROM $db_budget_rights WHERE member_id=? AND budget_id=?");
				}
				if ($num == 3){//process share budgets
					$result = $db->prepare("DELETE FROM $db_project_rights WHERE member_id=? AND project_id=?");
				}
				$result->execute(array($searchMemberID,$processIDs[$i]));
			} catch(PDOException $e) {
				echo "message004 - Sorry, system is experincing problem. Please check back.";
			}
			if ($processRights[$i]>0){
				try{//add new rights
					if ($num == 1){//process share accounts
						$result = $db->prepare("INSERT INTO $db_account_rights (member_id,access_rights,account_id,active) VALUES (?,?,?,?)");
					}
					if ($num == 2){//process share budgets
						$result = $db->prepare("INSERT INTO $db_budget_rights (member_id,access_rights,budget_id,active) VALUES (?,?,?,?)");
					}
					if ($num == 3){//process share budgets
						$result = $db->prepare("INSERT INTO $db_project_rights (member_id,access_rights,project_id,active) VALUES (?,?,?,?)");
					}
					$result->execute(array($searchMemberID,$processRights[$i],$processIDs[$i],$yeVal));
				} catch(PDOException $e) {
					echo "message005 - Sorry, system is experincing problem. Please check back.";
				}
			}
		}
		echo "Done";
	}	
?>