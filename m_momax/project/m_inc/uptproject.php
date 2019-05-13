<?php
	include ('../../../m_inc/m_config.php'); //mysql cridential
	include ('../../../m_class/class_lib.php');
	include ('../../../m_inc/def_value.php');
	
	$budgetTotInst = new budgetInfoC();
	$accountInfo = new accountInfoC();
	$generalInfo = new generalInfoC();
	$state = "no";
	$memberID = $_POST['mid'];
	$projectID = $_POST['projID'];
	$budgetID = $_POST['budid'];
	$budgetTypeID = $_POST['budTypeid'];

	$orgDate = $_POST['transDate']; //alter mm-dd-yyyy to yyyy-mm-dd 
	$partsArr = explode("-",$orgDate);
	$transactionDate = $partsArr[2]."-".$partsArr[0]."-".$partsArr[1];
	$projectName = $_POST['projName'];
	$amount = $_POST['amount'];
	$amount = preg_replace('/[\$,]/', '', $amount); //remove $ and common from amount and assign zero if needs
	if ($amount=="" or $amount==0 or $amount==0.00 or $amount<0){
		$amount = 0;
	}
	$categoryID = 0; //no category is assigned
	$note = $_POST['note'];
	$note = str_replace('"', '', $note); //remove ' from note
	$budlistit = $_POST['budlistit'];
	
	$accountYN = $_POST['accountYN']; //yes or no inserted account(s)
	$accountAllCt = $_POST['accountCt']; //total number of available accounts
	$accountAllID = $_POST['accountID']; //all account numbers combine
	$accountAllID = stripslashes($accountAllID);
	$accountAllSelect = $_POST['accountInfo']; //JSON of the selected account(s)
	$accountAllSelect = stripslashes($accountAllSelect);
	$accountSelectCt = $_POST['accountSelectCt']; //total number of selected accounts
	
	//update project info
	try{//update project details
		$result = $db->prepare("UPDATE $db_project SET name=?,date=?,note=?,budgetlist_id=? WHERE project_id=?");
		$result->execute(array($projectName,$transactionDate,$note,$budlistit,$projectID));
	} catch(PDOException $e) {
		echo "message001 - Sorry, system is experincing problem. Please check back.";
	}
	
	if ($budlistit != 0 and $budlistit > 0){
		try{//update budgetlist_id to zero
			$result = $db->prepare("UPDATE $db_project_detail SET budgetlist_id=? WHERE project_id=?");
			$result->execute(array(0,$projectID));
		} catch(PDOException $e) {
			echo "message002 - Sorry, system is experincing problem. Please check back.";
		}
	}
		
	//update inserted account info
	try{//delete project item
		$result = $db->prepare("DELETE FROM $db_transaction WHERE member_id=? AND project_id=?");
		$result->execute(array($memberID,$projectID));
	} catch(PDOException $e) {
		echo "message003 - Sorry, system is experincing problem. Please check back.";
	}
	if ($accountYN == "yes"){
		$selectID = json_decode($accountAllSelect, true);
		$acctIdArr = array();
		$acctTypeTransArr = array();
		$acctAmt = array();
		$arrCt = 0;
		foreach($selectID as $row) {
			$acctIdArr[$arrCt] = $row['acctID'];
			$acctTypeTransArr[$arrCt] = $row['acctType'];
			$acctAmtTemp = $row['acctAmt'];
			$acctAmtTemp = preg_replace('/[\$,]/', '', $acctAmtTemp);
			if ($acctAmtTemp=="" or $acctAmtTemp==0 or $acctAmtTemp==0.00 or $acctAmtTemp<0){
				$acctAmtTemp = 0;
			}
			$acctAmt[$arrCt] = $acctAmtTemp;
			$arrCt += 1;
		}
	}
	$transOrderCounter = 0;
	for ($i=0; $i<$accountSelectCt; $i++){ //loop through to insert all inserted accounts - recurring or not
		$transOrderCounter = $accountInfo->getTransOrderNum($transOrderCounter,$acctIdArr[$i],$transactionDate);
		$transOrderCounter += 1;
		$referenceNumber = "no"; //set referencenumber to no to indicate not the main grouping
		$accountInfo->insertAcctTransPrj($acctIdArr[$i],$memberID,$transactionDate,"no",$transOrderCounter,$acctAmt[$i],$acctTypeTransArr[$i],$categoryID,$note,"",$budgetID,$budgetTypeID,$projectID,0,0,0,$budlistit,0,0,"yes");
		$transOrderCounter = 0;
	}
		
	echo "good";

?>