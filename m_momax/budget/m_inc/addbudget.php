<?php
	include ('../../../m_inc/m_config.php'); //mysql cridential
	include ('../../../m_class/class_lib.php');
	include ('../../../m_inc/def_value.php');
	
	$budgetTotInst = new budgetInfoC();
	$accountInfo = new accountInfoC();
	$generalInfo = new generalInfoC();
	
	$processState = $_POST['budState']; //both
	$memberID = $_POST['mid'];
	$budgetID = $_POST['budid'];
	$budgetTypeID = $_POST['budTypeid'];
	$transDate = $_POST['transDate'];
	$amount = $_POST['amount'];
	$categoryID = $_POST['category'];
	$note = $_POST['note'];
	
	$recurringYN = $_POST['recurringYN'];
	$recurringType = $_POST['recurringType'];
	$recurringNum = $_POST['recurringNum'];
	
	$accountYN = $_POST['accountYN'];
	$accountAllCt = $_POST['accountCt'];
	$accountAllID = $_POST['accountID'];
	$accountAllSelect = $_POST['accountInfo'];
	$accountAllSelect = stripslashes($accountAllSelect);
	$accountSelectCt = $_POST['accountSelectCt'];
	
	$budTransID = $_POST['budTransID'];	//edit
	$accountChgYN = $_POST['editAcctY'];
	$accountDelSelect = $_POST['delAccountInfo'];
	$accountDelSelect = stripslashes($accountDelSelect);
	$accountDelSelectCt = $_POST['delAccountSelectCt'];
	$recurringChgYN = $_POST['editRecurringY'];
	$recurringOrgStart = $_POST['recurringStart'];
		
	$amount = preg_replace('/[\$,]/', '', $amount); //remove $ and common from amount and assign zero if needs
	if ($amount=="" or $amount==0 or $amount==0.00 or $amount<0){
		$amount = 0;
	}
	
	$orgDate = $transDate; //alter mm-dd-yyyy to yyyy-mm-dd (if change in recurring in edit state, create recurring with orginal start date)
	$partsArr = explode("-",$orgDate);
	$transactionDate = $partsArr[2]."-".$partsArr[0]."-".$partsArr[1];
	
	$note = preg_replace("/[^A-Za-z0-9\-()<>= '\/]/", "", $note);//
	$note = str_replace('"', "", $note); //remove " from note
	$referenceNumber = ""; //not being used
	$projectID = ""; //project_id will not include in here
	$active = "yes";
	$posted = "no";	
	$forModule = "budget"; //indicator for class processing for modules (budget and account)
	$trkmemberID = 0;
	
	$budgerannsetID = 0;
	$tagID = 0;
	
	if ($processState=="edit"){ //check for edit state
		$edtStage = "no"; //set flag for edit changes
		if ($recurringChgYN =="yes"){ //check for recurring - delete all and recreate all using orginal start date
			$budgetTotInst->delBudRecurringChgF($memberID,$budgetID,$budTransID);//if edit and change in recurring - delete all and insert new budgets
			$edtStage = "yes";
			if ($recurringOrgStart == ""){
				$transactionDate = $transactionDate; //no recurring before - use current transaction date
			}else{
				$transactionDate = $recurringOrgStart; //if change in recurring in edit state, create recurring with orginal start date
			}
			$processState = "add";
		} 
		
		if ($edtStage == "no" and $accountChgYN =="yes"){ //check for edit state and change in recurring
			$newGroupSetCreated = "no"; //set flag for new group inserted account
			if ($accountSelectCt > 0){
				$selectID = json_decode($accountAllSelect, true);//$abc = $obj->acctID[0]; //$selectID is not an object, cannot get the property[0]
				$controlSetID = 1;
				foreach($selectID as $row) {
					$transID = $row['transID'];
					$acctId = $row['acctID'];
					$acctTypeTrans = $row['acctType'];
					$acctChange = $row['chgOnAcct'];
					$acctAmtTemp = $row['acctAmt'];
					$acctAmtTemp = preg_replace('/[\$,]/', '', $acctAmtTemp);
					if ($acctAmtTemp=="" or $acctAmtTemp==0 or $acctAmtTemp==0.00 or $acctAmtTemp<0){
						$acctAmtTemp = 0;
					}
					$recurringID = $row['recurringSetID'];
					$groupSetID = $row['groupSetID'];
					
					if ($acctChange == "update"){ //update inserted account
						$accountInfo->updateChgAcct($memberID,$transID,$transactionDate,$posted,$categoryID,$acctAmtTemp,$acctId,$acctTypeTrans,$note,$groupSetID,$acctChange);
					}
					if ($acctChange == "new"){ //add new fresh inserted account
						if ($groupSetID == "" and $controlSetID == 1){ //total new inserted -- already have another inserted account and has a groupSetId already...
							$non_amount = 0; //no need to assign amount since this is not the main budtransaction_id
							$tempGroupSetID = $generalInfo->getGroupSetIDBudArrF($recurringID,$non_amount,$active);
							$controlSetID += 1;
							$groupSetID = $tempGroupSetID;
							$newGroupSetCreated = "yes"; //flag for new inserted account group - to update main_amount and main_trans_id
						}
						if ($groupSetID == "" and $controlSetID > 1){ 
							$groupSetID = $tempGroupSetID;//use the same groupSetID for more inserted accounts
						}
						$transOrderCounter = 0;
						$accountInfo->getTransOrderNum($transOrderCounter,$acctId,$transDate);
						$referenceNumber = "no"; //set referencenumber to no to indicate not the main grouping
						$accountInfo->insertAcctTransBudF($acctId,$memberID,$transactionDate,$posted,$transOrderCounter,$acctAmtTemp,$acctTypeTrans,$categoryID,$note,$referenceNumber,$budgetID,$budgetTypeID,$projectID,$recurringID,$groupSetID,$trkmemberID,$budgerannsetID,$tagID,$active);
					}
				}
			}else{
				$acctId = "";
				$acctTypeTrans = "";
				$groupSetID = "";
				$acctChange = "";
			}
			
			//if newly inserted account, need to establish groupSetID main_trans_id and main_amount
			if($newGroupSetCreated == "yes"){
				$generalInfo->updateMainGroupSetID($groupSetID,$budTransID,$amount);
			}
			
			//update budtransaction all cases
			$budgetTotInst->updateChgBudgetF($memberID,$budTransID,$transactionDate,$categoryID,$amount,$acctId,$acctTypeTrans,$note,$groupSetID,$acctChange); //update budtransaction table

			if ($accountDelSelectCt > 0){ //check for deleted inserted accounts - delete from transaction table
				$selectDelID = json_decode($accountDelSelect, true);
				foreach($selectDelID as $row) {
					$transID = $row['transID'];
					$acctId = $row['acctID'];
					$acctTypeTrans = $row['acctType'];
					$acctChange = $row['chgOnAcct'];
					$acctAmtTemp = $row['acctAmt'];
					$acctAmtTemp = preg_replace('/[\$,]/', '', $acctAmtTemp);
					if ($acctAmtTemp=="" or $acctAmtTemp==0 or $acctAmtTemp==0.00 or $acctAmtTemp<0){
						$acctAmtTemp = 0;
					}
					$recurringID = $row['recurringSetID'];
					$groupSetID = $row['groupSetID'];
					
					if ($acctChange == "delete"){
						$accountInfo->delChgAcct($memberID,$transID);
					}
				}
				if ($accountSelectCt == 0){ //only one groupSetID for this group
					$generalInfo->delNoMoreGroup($groupSetID);
				}
			}			
			$edtStage = "yes";
		}
		if ($edtStage == "no"){ //if no changes to recurring and inserted accounts, update budtransaction table only
			$acctChange = "no";
			$budgetTotInst->updateChgBudgetOnlyF($memberID,$budTransID,$transactionDate,$categoryID,$amount,$note,$acctChange,$forModule); //just budtransaction data
			if ($accountSelectCt > 0){//if inserted account - update records
				$groupSetID = $generalInfo->returnGroupSetID($budTransID);
				$accountInfo->updateAcctBySetID($memberID,$transactionDate,$categoryID,$amount,$note,$groupSetID);
			}
		}
	}	
	
	if ($processState=="add"){ //check for add state
		///1. take care of recurring and inserted accounts
		if ($recurringYN=="yes"){
			if ($recurringNum < 2 or $recurringNum == ""){
				$recurringNum = 2;
			}
			$transDateArr = array(); 
			$balDateArr = array();
			$transDateArr = $generalInfo->createRecurringDateBudF($transactionDate,$recurringType,$recurringNum);//create recurring date array()
			$recurringID = $generalInfo->getRecurringBudIDF($recurringType,$recurringNum,$active);//create and return recurringID
			
			if ($accountYN == "yes"){///create group set it
				$groupSetIdArr = array();
				$groupSetIdArr = $generalInfo->getGroupSetIDBudArrF($recurringID,$recurringNum,$amount,$active); //create multiple recurring groupSetID array()
			}else{
				$groupSetID = "";
			}	
		}else{
			$recurringID = "";
			if ($accountYN == "yes"){///create one group set it
				$groupSetID = $generalInfo->getGroupSetIDBudF($recurringID,$amount,$active);
			}else{
				$groupSetID = "";
			}
		}
	
		///2. take care of inserted accounts, no recurring
		if ($accountYN == "yes"){
			$selectID = json_decode($accountAllSelect, true);//$abc = $obj->acctID[0]; //$selectID is not an object, cannot get the property[0]
			$acctIdArr = array();
			$acctTypeTransArr = array();
			$acctChange = array();
			$acctAmt = array();
			$arrCt = 0;
			foreach($selectID as $row){
				$acctIdArr[$arrCt] = $row['acctID'];
				$acctTypeTransArr[$arrCt] = $row['acctType'];
				$acctChange[$arrCt] = $row['chgOnAcct'];
				$acctAmtTemp = $row['acctAmt'];
				$acctAmtTemp = preg_replace('/[\$,]/', '', $acctAmtTemp);
				if ($acctAmtTemp=="" or $acctAmtTemp==0 or $acctAmtTemp==0.00 or $acctAmtTemp<0){
					$acctAmtTemp = 0;
				}
				$acctAmt[$arrCt] = $acctAmtTemp;
				$arrCt += 1;
			}
		}else{
			$accountID = "";
			$transactionTypeID = "";
		}
				
		///3. insert data - determine transaction_order on specific date//
		if ($accountYN == "yes"){
			$accountID = "";//budget doesn't need accountID
			$transactionTypeID = "";//budget doesn't need transactionTypeID
			$transOrderCounter = 0;
			if ($recurringYN=="yes"){
				for ($i=0; $i<$recurringNum; $i++){ //create transactionOrder and insert the data
					$transOrderCounter = $budgetTotInst->getOrderNumBudF($transOrderCounter,$transDateArr[$i]);
					$transOrderCounter += 1; //set the next transaction order
					$referenceNumber = "yes"; //set referencenumber to yes to indicate grouping
					$flag = $budgetTotInst->insertBudTransF($accountID,$memberID,$transDateArr[$i],$posted,$transOrderCounter,$amount,$transactionTypeID,$categoryID,$note,$referenceNumber,$budgetID,$budgetTypeID,$projectID,$recurringID,$groupSetIdArr[$i],$active);
					$transOrderCounter = 0; //reset to start over
				}
			}else{
				$transOrderCounter = $budgetTotInst->getOrderNumBudF($transOrderCounter,$transactionDate);
				$transOrderCounter += 1;
				$referenceNumber = "yes"; //set referencenumber to yes to indicate grouping
				$flag = $budgetTotInst->insertBudTransF($accountID,$memberID,$transactionDate,$posted,$transOrderCounter,$amount,$transactionTypeID,$categoryID,$note,$referenceNumber,$budgetID,$budgetTypeID,$projectID,$recurringID,$groupSetID,$active);
			}
			
			$transOrderCounter = 0;
			for ($i=0; $i<$accountSelectCt; $i++){ //loop through to insert all inserted accounts - recurring or not
				if ($recurringYN=="yes"){
					for ($j=0; $j<$recurringNum; $j++){
						$transOrderCounter = $accountInfo->getTransOrderNum($transOrderCounter,$acctIdArr[$i],$transDateArr[$j]);
						$transOrderCounter += 1;
						$referenceNumber = "no"; //set referencenumber to no to indicate not the main grouping
						$flag = $accountInfo->insertAcctTransBudF($acctIdArr[$i],$memberID,$transDateArr[$j],$posted,$transOrderCounter,$acctAmt[$i],$acctTypeTransArr[$i],$categoryID,$note,$referenceNumber,$budgetID,$budgetTypeID,$projectID,$recurringID,$groupSetIdArr[$j],$trkmemberID,$budgerannsetID,$tagID,$active);
						$transOrderCounter = 0;
					}
				}else{				
					$transOrderCounter = $accountInfo->getTransOrderNum($transOrderCounter,$acctIdArr[$i],$transactionDate);
					$transOrderCounter += 1;
					$referenceNumber = "no"; //set referencenumber to no to indicate not the main grouping
					$flag = $accountInfo->insertAcctTransBudF($acctIdArr[$i],$memberID,$transactionDate,$posted,$transOrderCounter,$acctAmt[$i],$acctTypeTransArr[$i],$categoryID,$note,$referenceNumber,$budgetID,$budgetTypeID,$projectID,$recurringID,$groupSetID,$trkmemberID,$budgerannsetID,$tagID,$active);
					$transOrderCounter = 0;
				}
			}
		}else{ //end of yes inserted accounts - if else
			$transOrderCounter = 0;
			if ($recurringYN=="yes"){
				for ($i=0; $i<$recurringNum; $i++){
					$transOrderCounter = $budgetTotInst->getOrderNumBudF($transOrderCounter,$transDateArr[$i]);					
					$transOrderCounter += 1;
					$referenceNumber = "no"; //set referencenumber to no to indicate no grouping
					$flag = $budgetTotInst->insertBudTransF($accountID,$memberID,$transDateArr[$i],$posted,$transOrderCounter,$amount,$transactionTypeID,$categoryID,$note,$referenceNumber,$budgetID,$budgetTypeID,$projectID,$recurringID,$groupSetID,$active);
					$transOrderCounter = 0;
				}
			}else{
				$transOrderCounter = $budgetTotInst->getOrderNumBudF($transOrderCounter,$transactionDate);				
				$transOrderCounter += 1;
				$referenceNumber = "no"; //set referencenumber to no to indicate no grouping
				$flag = $budgetTotInst->insertBudTransF($accountID,$memberID,$transactionDate,$posted,$transOrderCounter,$amount,$transactionTypeID,$categoryID,$note,$referenceNumber,$budgetID,$budgetTypeID,$projectID,$recurringID,$groupSetID,$active);
			}
		}
	}//end of if statement for add state
	echo "result==>pass>";//confirm of success
?>