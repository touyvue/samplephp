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
	$recurringYN = $_POST['recurringYN'];
  	$delRecurringSelect = $_POST['delRecurringSelect'];
	$recurringID = $_POST['recurringID'];
  	$insertAcctYN = $_POST['insertAcctYN'];
	$groupSetID = $_POST['groupSetID'];
  	$delRecurring = $_POST['delRecurring'];
	$recurringNum = $_POST['recurringNum'];
	
	$budgetTransInfo = $accountInfo->getAcctBudgetID($memberID,$accountID,$transID);
	if ($budgetTransInfo[0] !="" and $budgetTransInfo[0] !=0 and !is_null($budgetTransInfo[0])){ 
		$budgetYN = "yes"; //get budtransID
		$budgetID = $budgetTransInfo[0]; //get budgetID for new account
		$budgetTypeID = $budgetTransInfo[1]; //get budgetTransID for new account
		$foundGroupSetID = $budgetTransInfo[2]; //groupSetID - for use with budget and account situation
		$budTransID = $budgetTotInst->retBudtransactionIDF($memberID,$budgetTransInfo[0],$budgetTransInfo[2]);
	}else{
		$budgetYN = "no";
		$budgetID = ""; 
		$budgetTypeID = "";
		$foundGroupSetID = "";
		$budTransID = "";
	}
	
	$edtStage = "no"; //set flag for delete
	if ($recurringYN == "yes"){
		if ($delRecurringSelect == "all"){
			$accountInfo->delAcctRecurringChg($memberID,$accountID,$transID);
			$edtStage = "yes";
		}
			
		if ($delRecurringSelect == "one"){
			if ($insertAcctYN == "no"){ //recurring and no inserted accounts
				try{//delete all in $db_budtransaction
					$result = $db->prepare("DELETE FROM $db_transaction WHERE member_id=? AND transaction_id=? AND account_id=?");
					$result->execute(array($memberID,$transID,$accountID));
				} catch(PDOException $e) {
					echo "message001 - Sorry, system is experincing problem. Please check back.";
				}
				
				if ($budgetYN == "yes"){
					try{//delete all in $db_budtransaction
						$result = $db->prepare("DELETE FROM $db_budtransaction WHERE member_id=? AND budtransaction_id=? AND budget_id=?");
						$result->execute(array($memberID,$budTransID,$budgetID));
					} catch(PDOException $e) {
						echo "message001 - Sorry, system is experincing problem. Please check back.";
					}
				}
				if ($recurringNum == 2){//drop recurring if it's the last one
					try{//update accounts
						$result = $db->prepare("UPDATE $db_transaction SET recurring_id=? WHERE member_id=? AND recurring_id=?");
						$result->execute(array(0,$memberID,$recurringID));
					} catch(PDOException $e) {
						echo "message001 - Sorry, system is experincing problem. Please check back.";
					}
					try{//delete all in $db_budtransaction
						$result = $db->prepare("DELETE FROM $db_recurring WHERE recurring_id=?");
						$result->execute(array($recurringID));
					} catch(PDOException $e) {
						echo "message001 - Sorry, system is experincing problem. Please check back.";
					}
				}else{
					try{//reduce recurring by 1
						$result = $db->prepare("UPDATE $db_recurring SET no_of_recurring=? WHERE recurring_id=?");
						$result->execute(array($recurringNum-1,$recurringID));
					} catch(PDOException $e) {
						echo "message001 - Sorry, system is experincing problem. Please check back.";
					}
				}
				$edtStage = "yes";
			}else{
				$edtStage = "no";
			}			
		}
	}
	if ($edtStage == "no" and $insertAcctYN == "yes"){ //inserted accounts and/or one recurring with inserted accounts
		try{//delete all in $db_budtransaction
			$result = $db->prepare("DELETE FROM $db_transaction WHERE member_id=? AND group_set_id=?");
			$result->execute(array($memberID,$groupSetID));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		
		if ($budgetYN == "yes"){
			try{//delete all in $db_budtransaction
				$result = $db->prepare("DELETE FROM $db_budtransaction WHERE member_id=? AND group_set_id=? AND budget_id=?");
				$result->execute(array($memberID,$groupSetID,$budgetID));
			} catch(PDOException $e) {
				echo "message001 - Sorry, system is experincing problem. Please check back.";
			}
		}
				
		try{//delete all in $db_budtransaction
			$result = $db->prepare("DELETE FROM $db_recurring_group WHERE recurring_group_id=?");
			$result->execute(array($groupSetID));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		//drop recurring if it's the last one - recurring but delete only one recurring
		if ($recurringYN == "yes" and $delRecurringSelect == "one"){
			if ($recurringNum == 2){//drop recurring if it's the last one
				if ($budgetYN == "yes"){
					try{//update the remaining recurring to empty
						$result = $db->prepare("UPDATE $db_budtransaction SET recurring_id=? WHERE member_id=? AND recurring_id=? AND budget_id=?");
						$result->execute(array(0,$memberID,$recurringID,$budgetID));
					} catch(PDOException $e) {
						echo "message001 - Sorry, system is experincing problem. Please check back.";
					}
				}
				try{//update the remaining recurring to empty
					$result = $db->prepare("UPDATE $db_transaction SET recurring_id=? WHERE member_id=? AND recurring_id=?");
					$result->execute(array(0,$memberID,$recurringID));
				} catch(PDOException $e) {
					echo "message001 - Sorry, system is experincing problem. Please check back.";
				}
				try{//delete since 1 recurring only
					$result = $db->prepare("DELETE FROM $db_recurring WHERE recurring_id=?");
					$result->execute(array($recurringID));
				} catch(PDOException $e) {
					echo "message001 - Sorry, system is experincing problem. Please check back.";
				}
				try{//delete since 1 recurring only
					$result = $db->prepare("DELETE FROM $db_recurring_group WHERE recurring_id=?");
					$result->execute(array($recurringID));
				} catch(PDOException $e) {
					echo "message001 - Sorry, system is experincing problem. Please check back.";
				}
			}else{
				try{//reduce recurring by 1
					$result = $db->prepare("UPDATE $db_recurring SET no_of_recurring=? WHERE recurring_id=?");
					$result->execute(array($recurringNum-1,$recurringID));
				} catch(PDOException $e) {
					echo "message001 - Sorry, system is experincing problem. Please check back.";
				}
			}
		}
		$edtStage = "yes";
	}
	if ($edtStage == "no"){
		try{//delete all in $db_budtransaction
			$result = $db->prepare("DELETE FROM $db_transaction WHERE member_id=? AND transaction_id=? AND account_id=?");
			$result->execute(array($memberID,$transID,$accountID));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		if ($budgetYN == "yes"){
			try{//delete all in $db_budtransaction
				$result = $db->prepare("DELETE FROM $db_budtransaction WHERE member_id=? AND budtransaction_id=? AND budget_id=?");
				$result->execute(array($memberID,$budTransID,$budgetID));
			} catch(PDOException $e) {
				echo "message001 - Sorry, system is experincing problem. Please check back.";
			}
		}
	}
	
	echo "done";
?>