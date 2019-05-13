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
		$acctName = $_POST['acctName'];
		$acctName = str_replace('"', "", $acctName); //remove " from note
		$acctDesc = $_POST['acctDesc'];
		$acctDesc = str_replace('"', "", $acctDesc); //remove " from note
		$itemCount = 1;
		try{//get new accountID
			$result = $db->prepare("SELECT list_order FROM $db_account WHERE member_id=? ORDER BY list_order ASC");
			$result->execute(array($memberID)); 
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		$itemCount = $result->rowCount();
		$itemCount++;
		
		try{//new account
			$result = $db->prepare("INSERT INTO $db_account (name,list_order,description,member_id,budgetyn,chartyn,active) VALUES (?,?,?,?,?,?,?)");
			$result->execute(array($acctName,$itemCount,$acctDesc,$memberID,$no,$no,$active));
		} catch(PDOException $e) {
			echo "message002 - Sorry, system is experincing problem. Please check back.";
		}
		try{//get new accountID
			$result = $db->prepare("SELECT account_id FROM $db_account WHERE member_id=? ORDER BY account_id ASC");
			$result->execute(array($memberID)); 
		} catch(PDOException $e) {
			echo "message003 - Sorry, system is experincing problem. Please check back.";
		}
		foreach ($result as $row){
			$accountID = $row['account_id'];
		}
		if ($accountID != ""){
			try{//insert account rights
				$result = $db->prepare("INSERT INTO $db_account_rights (member_id,access_rights,account_id,active) VALUES (?,?,?,?)");
				$result->execute(array($memberID,3,$accountID,$active));
			} catch(PDOException $e) {
				echo "message004 - Sorry, system is experincing problem. Please check back.";
			}
		}	
		echo "done";
	}//end of add state
	
	//get account info
	if ($state == "getdata"){
		$accountID = $_POST['acctID'];
	
		try{//get new accountID
			$result = $db->prepare("SELECT account_id,name,description FROM $db_account WHERE member_id=? AND account_id=?");
			$result->execute(array($memberID,$accountID)); 
		} catch(PDOException $e) {
			echo "message005 - Sorry, system is experincing problem. Please check back.";
		}
		foreach ($result as $row){
			$accountInfo = '{"id":"'.$row['account_id'].'","name":"'.str_replace('\\','',$row['name']).'","desc":"'.str_replace('\\','',$row['description']).'"}';
		}
		echo $accountInfo;
	}
	
	//update account info
	if ($state == "edit"){
		$accountID = $_POST['acctID'];
		$acctName = $_POST['acctName'];
		$acctName = str_replace('"', "", $acctName); //remove " from note
		$acctDesc = $_POST['acctDesc'];
		$acctDesc = str_replace('"', "", $acctDesc); //remove " from note
		
		try{//update account details
			$result = $db->prepare("UPDATE $db_account SET name=?,description=? WHERE account_id=?");
			$result->execute(array($acctName,$acctDesc,$accountID));
		} catch(PDOException $e) {
			echo "message006 - Sorry, system is experincing problem. Please check back.";
		}
	}
	
	//delete account
	if ($state == "delete"){
		$accountID = $_POST['acctID'];
		$acctName = $_POST['acctName'];
		$acctDesc = $_POST['acctDesc'];
				
		try{//delete account
			$result = $db->prepare("DELETE FROM $db_account WHERE account_id=? AND member_id=?");
			$result->execute(array($accountID,$memberID));
		} catch(PDOException $e) {
			echo "message007 - Sorry, system is experincing problem. Please check back.";
		}
		try{//delete account details
			$result = $db->prepare("DELETE FROM $db_account_rights WHERE account_id=?");
			$result->execute(array($accountID));
		} catch(PDOException $e) {
			echo "message008 - Sorry, system is experincing problem. Please check back.";
		}
	}
?>