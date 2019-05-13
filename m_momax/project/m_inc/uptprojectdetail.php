<?php
	include ('../../../m_inc/m_config.php'); //mysql cridential
	include ('../../../m_class/class_lib.php');
	include ('../../../m_inc/def_value.php');
	
	$projectInfo = new projectInfoC();
	$state = $_POST['actState'];
	$projectID = $_POST['projID'];
	$memberID = $_POST['mid'];
	$pdetailID = $_POST['pdid'];
	$pItem = $_POST['itemName'];
	$pAmount = $_POST['itemAmt'];
	$pAmount = preg_replace('/[\$,]/', '', $pAmount); //remove $ and common from amount and assign zero if needs
	if ($pAmount=="" or $pAmount==0 or $pAmount==0.00 or $pAmount<0){
		$pAmount = 0;
	}
	$pNote = $_POST['itemNote'];
	$pNote = preg_replace("/[^A-Za-z0-9\-()<>= \/]/", "", $pNote);
	$pNote = str_replace('"', "", $pNote); //remove " from note
	$pDone = $_POST['pdone'];
	$budlistit = $_POST['budlistit'];
	$todayDate = date('Y-m-d');
	$yes = "yes";
	
	if ($state == "new"){
		try{//update project details
			$result = $db->prepare("INSERT INTO $db_project_detail (project_id,item,amount,completed,budgetlist_id,note) VALUES (?,?,?,?,?,?)");
			$result->execute(array($projectID,$pItem,$pAmount,$pDone,$budlistit,$pNote));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}		
	}
	if ($state == "update"){
		try{//update project details
			$result = $db->prepare("UPDATE $db_project_detail SET item=?,amount=?,completed=?,budgetlist_id=?,note=? WHERE pdetail_id=?");
			$result->execute(array($pItem,$pAmount,$pDone,$budlistit,$pNote,$pdetailID));
		} catch(PDOException $e) {
			echo "message002 - Sorry, system is experincing problem. Please check back.";
		} 
	}	
	if ($state == "delete"){
		try{//delete project item
			$result = $db->prepare("DELETE FROM $db_project_detail WHERE pdetail_id=?");
			$result->execute(array($pdetailID));
		} catch(PDOException $e) {
			echo "message003 - Sorry, system is experincing problem. Please check back.";
		}
	}
	
	//update total amount for inserted budget and account
	$itemTotAmt = 0;
	$projAccountIDArr = array();
	$projAccountTransTypeArr = array();
	$projAccountAmtArr = array();
	$posted = "no";
	$postedCt = 0;
	try{					
		$result = $db->prepare("SELECT amount,completed FROM $db_project_detail WHERE project_id=?");
		$result->execute(array($projectID));
	} catch(PDOException $e) {
		echo "message004 - Sorry, system is experincing problem. Please check back.";
	}
	$itemCount = $result->rowCount();
	if ($itemCount > 0){
		foreach ($result as $row) {
			$itemTotAmt = $itemTotAmt + $row['amount'];
			if ($row['completed'] == "no"){
				$postedCt++;
			}
		}
		if ($postedCt == 0){
			$posted = "yes";
		}
				
		try{//get Budget ID
			$result = $db->prepare("SELECT account_id FROM $db_transaction WHERE member_id=? AND project_id=?");
			$result->execute(array($memberID,$projectID));
		} catch(PDOException $e) {
			echo "message005 - Sorry, system is experincing problem. Please check back.";
		}
		$itemAccount = $result->rowCount();
		if ($itemAccount == 1){
			try{//update budget total
				$result = $db->prepare("UPDATE $db_transaction SET amount=?,posted=? WHERE member_id=? AND project_id=?");
				$result->execute(array($itemTotAmt,$posted,$memberID,$projectID));
			} catch(PDOException $e) {
				echo "message006 - Sorry, system is experincing problem. Please check back.";
			}
		}
	}else{
		try{//delete transaction record since no project items
			$result = $db->prepare("DELETE FROM $db_transaction WHERE member_id=? AND project_id=?");
			$result->execute(array($memberID,$projectID));
		} catch(PDOException $e) {
			echo "message003 - Sorry, system is experincing problem. Please check back.";
		}
	}
	
	echo "good";

?>