<?php
	include ('../../../m_inc/m_config.php'); //mysql cridential
	include ('../../../m_class/class_lib.php');
	include ('../../../m_inc/def_value.php');
		
	$budgetTotInst = new budgetInfoC();
	$accountInfo = new accountInfoC();
	
	$memberID = $_POST['mid'];
	$accountID = $_POST['aid'];
	$transID = $_POST['transID'];
	$actState = $_POST['actState'];
	$active = "yes";
	
	///get budget transaction data
	try{//get order id
		$result = $db->prepare("SELECT transaction_date,amount,category_id,posted,description,transaction_type_id,budget_id,recurring_id,group_set_id,budgetlist_id,tag_id FROM $db_transaction WHERE member_id=? AND account_id=? AND transaction_id=?");
		$result->execute(array($memberID,$accountID,$transID)); //get the current transaction record
	} catch(PDOException $e) {
		echo "message001 - Sorry, system is experincing problem. Please check back.";
	}
	
	foreach ($result as $row){
		$transactionDate = $row['transaction_date'];
		$amount = $row['amount'];
		$categoryID = $row['category_id'];
		$note = str_replace('\\','',$row['description']);
		$posted = $row['posted'];
		
		$transTypeID = $row['transaction_type_id']; //***
		$recurringID = $row['recurring_id'];
		$groupSetID = $row['group_set_id'];
		$budgetID = $row['budget_id'];
		
		$budgetlistID = $row['budgetlist_id'];
		$tagID = $row['tag_id'];
	}
		
	try{//get category name
		$result = $db->prepare("SELECT * FROM $db_category WHERE category_id=? AND member_id=? AND active=?");
		$result->execute(array($categoryID,$memberID,$active)); //get the category name
	} catch(PDOException $e) {
		echo "message001 - Sorry, system is experincing problem. Please check back.";
	}
	foreach ($result as $row){
		$category = str_replace('\\','',$row['category']);
	}
	//check for image
	try{//get pix name
		$result = $db->prepare("SELECT attach_name FROM $db_transaction_attach WHERE transaction_id=?");
		$result->execute(array($transID)); 
	} catch(PDOException $e) {
		echo "message001 - Sorry, system is experincing problem. Please check back.";
	}
	$itemsFound = $result->rowCount(); 
	if ($itemsFound > 0){
		foreach ($result as $row){
			$img = str_replace('\\','',$row['attach_name']);
		}
	}else{
		$img = "none";
	}
	///get recurring info
	if ($recurringID !="" and $recurringID !=0 and !is_null($recurringID)){
		try{///get starting date if this is a recurring
			$result = $db->prepare("SELECT transaction_date FROM $db_transaction WHERE member_id=? AND account_id=? AND recurring_id=? ORDER BY transaction_date DESC");
			$result->execute(array($memberID,$accountID,$recurringID)); //getting the first transaction date if recurring
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		foreach ($result as $row){
			$recurringStartDate = $row['transaction_date'];
		}
		
		$recurringYN = "yes";
		try{//get order id
			$result = $db->prepare("SELECT recurring_type,no_of_recurring FROM $db_recurring WHERE recurring_id=? AND active=?");
			$result->execute(array($recurringID,$active)); //get recurring info
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
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
			$result->execute(array($memberID,$groupSetID,$active)); //create json data of this groupSet of transaction
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		$acctItems = $result->rowCount(); 
		if ($acctItems > 0){
			foreach ($result as $row){
				if ($row['account_id'] != $accountID){
					if ($acctCt > 1){
						$selectAccountAll = $selectAccountAll.",";
					}
					$acctCt+= 1;
					$selectAccountAll = $selectAccountAll.'{"transID":"'.$row['transaction_id'].'",';
					$selectAccountAll = $selectAccountAll.'"acctID":"'.$row['account_id'].'",';
					$selectAccountAll = $selectAccountAll.'"transTypeid":"'.$row['transaction_type_id'].'",';
					$selectAccountAll = $selectAccountAll.'"grpSetID":"'.$row['group_set_id'].'",';
					$selectAccountAll = $selectAccountAll.'"recurrID":"'.$row['recurring_id'].'",';
					$selectAccountAll = $selectAccountAll.'"acctAmt":"'.$row['amount'].'"}';
				}
			}
		}
		$selectAccountAll = $selectAccountAll."]";
		$accountYN = "yes";
	}else{
		$acctCt = 1;
		$accountYN = "";
		$selectAccountAll = "";
	}
	
	$orgDate = $transactionDate;
	$partsArr = explode("-",$orgDate);
	$transactionDate = $partsArr[1]."-".$partsArr[2]."-".$partsArr[0];//alter yyyy-mm-dd to mm-dd-yyyy 
	
	///1 set of data 
	$setOneData = '{"transID":"'.$transID.'","transDate":"'.$transactionDate.'","amount":"'.$amount.'","acctType":"'.$transTypeID.'","accountID":"'.$accountID.'","categoryID":"'.$categoryID.'","posted":"'.$posted.'","note":"'.$note.'","img":"'.$img.'","annbudID":"'.$budgetlistID.'","tagID":"'.$tagID.'","groupSetID":"'.$groupSetID;
	$setOneData = $setOneData.'","recurringYN":"'.$recurringYN.'","recurringID":"'.$recurringID.'","recurringType":"'.$recurringType.'","recurringNum":"'.$recurringNum.'","recurringStartDate":"'.$recurringStartDate.'","acctCtFound":"'.$acctCt.'"}';

	echo $setOneData."<=>".$acctCt."<#>".$selectAccountAll; //return json data
?>