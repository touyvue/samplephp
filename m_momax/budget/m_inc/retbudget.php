<?php
	include ('../../../m_inc/m_config.php'); //mysql cridential
	include ('../../../m_class/class_lib.php');
	include ('../../../m_inc/def_value.php');
	
	$budgetTotInst = new budgetInfoC();
	$accountInfo = new accountInfoC();
	
	$memberID = $_POST['mid'];
	$budgetID = $_POST['budid'];
	$budTransID = $_POST['btid'];
	$budState = $_POST['budgetState'];
	$active = "yes";
	
	///get budget transaction data
	try{//get order id
		$result = $db->prepare("SELECT transaction_date,amount,category_id,description,budget_type_id,recurring_id,group_set_id FROM $db_budtransaction WHERE member_id=? AND budget_id=? AND budtransaction_id=?");
		$result->execute(array($memberID,$budgetID,$budTransID));
	} catch(PDOException $e) {
		echo "result==>fail10";
	}
	
	foreach ($result as $row){
		$transactionDate = $row['transaction_date'];
		$amount = $row['amount'];
		$categoryID = $row['category_id'];
		$note = str_replace('\\','',$row['description']);
		
		$budgetTypeID = $row['budget_type_id'];
		$recurringID = $row['recurring_id'];
		$groupSetID = $row['group_set_id'];
	}
		
	try{//get category name
		$result = $db->prepare("SELECT * FROM $db_category WHERE category_id=? AND member_id=? AND active=?");
		$result->execute(array($categoryID,$memberID,$active));
	} catch(PDOException $e) {
		echo "result==>fail10";
	}
	foreach ($result as $row){
		$category = str_replace('\\','',$row['category']);
	}
	///get recurring info
	if ($recurringID !="" and $recurringID !=0 and !is_null($recurringID)){
		try{///get starting date if this is a recurring
			$result = $db->prepare("SELECT transaction_date FROM $db_budtransaction WHERE member_id=? AND budget_id=? AND recurring_id=? ORDER BY transaction_date DESC");
			$result->execute(array($memberID,$budgetID,$recurringID));
		} catch(PDOException $e) {
			echo "result==>fail10";
		}
		foreach ($result as $row){
			$recurringStartDate = $row['transaction_date'];
			//$orgDate = $recurringDate;
			//$partsArr = explode("-",$orgDate);
			//$recurringStartDate = $partsArr[1]."-".$partsArr[2]."-".$partsArr[0];//month-day-year
		}
		
		$recurringYN = "yes";
		try{//get order id
			$result = $db->prepare("SELECT recurring_type,no_of_recurring FROM $db_recurring WHERE recurring_id=? AND active=?");
			$result->execute(array($recurringID,$active));
		} catch(PDOException $e) {
			echo "result==>fail10";
		}
		foreach ($result as $row){
			$recurringType = $row['recurring_type'];
			$recurringNum = $row['no_of_recurring'];
		}
	}else{
		$recurringYN = "";
		$recurringID = "";
		$recurringType = "";
		$recurringNum = "";
		$recurringStartDate = "";
	}
	
	///get inserted account info - array is needed
	if ($groupSetID !="" and $groupSetID !=0 and !is_null($groupSetID)){
		$acctCt = 1;
		$selectAccountAll = "[";
		try{//get order id
			$result = $db->prepare("SELECT transaction_id,account_id,transaction_type_id,group_set_id,recurring_id,amount FROM $db_transaction WHERE member_id=? AND group_set_id=? AND active=?");
			$result->execute(array($memberID,$groupSetID,$active));
		} catch(PDOException $e) {
			echo "result==>fail10";
		}
		foreach ($result as $row){
			if ($acctCt > 1){
				$selectAccountAll = $selectAccountAll.",";
			}
			$acctCt+= 1;
			$selectAccountAll = $selectAccountAll.'{"transID":"'.$row['transaction_id'].'",';
			$selectAccountAll = $selectAccountAll.'"acctID":"'.$row['account_id'].'",';
			$selectAccountAll = $selectAccountAll.'"acctType":"'.$row['transaction_type_id'].'",';
			$selectAccountAll = $selectAccountAll.'"grpSetID":"'.$row['group_set_id'].'",';
			$selectAccountAll = $selectAccountAll.'"recurrID":"'.$row['recurring_id'].'",';
			$selectAccountAll = $selectAccountAll.'"acctAmt":"'.$row['amount'].'"}';
			$accountID = $row['account_id'];
			$accountAmount = $row['amount'];
			$accountTypeID = $row['transaction_type_id'];
		}
		$selectAccountAll = $selectAccountAll."]";
		$accountYN = "yes";
		$accountID = "";
		$accountTypeID = "";
		$accountAmount = "";
	}else{
		$acctCt = 1;
		$accountYN = "";
		$accountID = "";
		$accountTypeID = "";
		$accountAmount = "";
		$selectAccountAll = "";
	}
	
	$orgDate = $transactionDate;
	$partsArr = explode("-",$orgDate);
	$transactionDate = $partsArr[1]."-".$partsArr[2]."-".$partsArr[0];//alter yyyy-mm-dd to mm-dd-yyyy
	
	///1 set of data 
	$setOneData = '{"budTransID":"'.$budTransID.'","transDate":"'.$transactionDate.'","amount":"'.$amount.'","categoryID":"'.$categoryID.'","note":"'.$note.'","groupSetID":"'.$groupSetID;
	$setOneData = $setOneData.'","recurringYN":"'.$recurringYN.'","recurringID":"'.$recurringID.'","recurringType":"'.$recurringType.'","recurringNum":"'.$recurringNum.'","recurringStartDate":"'.$recurringStartDate.'"}';

	echo $setOneData."<=>".$acctCt."<#>".$selectAccountAll; //return json data
?>