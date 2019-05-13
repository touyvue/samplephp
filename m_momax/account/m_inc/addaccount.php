<?php
	include ('../../../m_inc/m_config.php'); //mysql cridential
	include ('../../../m_class/class_lib.php');
	include ('../../../m_inc/def_value.php');
	
	$budgetTotInst = new budgetInfoC();
	$accountInfo = new accountInfoC();
	$generalInfo = new generalInfoC();
	
	//general variables
	$processState = $_POST['actState'];
		
	//add state
	$memberID = $_POST['mid'];
	$accountID = $_POST['aid'];
	$transactionTypeID = $_POST['acctType']; //$transactionTypeID //$acctType
	$transDate = $_POST['transDate'];
	$amount = $_POST['amount'];
	$categoryID = $_POST['category'];
	$note = $_POST['note'];
	$posted = $_POST['posted']; //use in account only to indicate posting transaction
	
	$recurringYN = $_POST['recurringYN'];
	$recurringType = $_POST['recurringType'];
	$recurringNum = $_POST['recurringNum'];
	
	$accountYN = $_POST['accountYN'];
	$accountAllCt = $_POST['accountCt'];
	$accountAllID = $_POST['accountID'];
	$accountAllSelect = $_POST['accountInfo'];
	$accountAllSelect = stripslashes($accountAllSelect);
	$accountSelectCt = $_POST['accountSelectCt'];
	
	//edit state
	$transID = $_POST['transID'];
	$accountDelSelect = $_POST['delAccountInfo'];
	$accountDelSelect = stripslashes($accountDelSelect);
	$accountDelSelectCt = $_POST['delAccountSelectCt'];
	$accountChgYN = $_POST['editAcctY'];
	$recurringChgYN = $_POST['editRecurringY'];
	$recurringOrgStart = $_POST['recurringStart'];
	
	$budgetTransInfoArr = array(); // array to keep track of budgetID and budgetTransID
		
	$amount = preg_replace('/[\$,]/', '', $amount); //remove $ and common from amount and assign zero if needs
	if ($amount=="" or $amount==0 or $amount==0.00 or $amount<0){
		$amount = 0;
	}
	//$note = preg_replace("/[^A-Za-z0-9\-()<>= '\/]/", "", $note);
	$note = str_replace('"', "", $note); //remove " from note
	$referenceNumber = ""; //not being used
	$projectID = ""; //project_id will not include in here
	$active = "yes";
	$forModule = "account";//indicator for class processing for modules (budget and account)
	$trkmemberID = 0;
	
	$orgDate = $transDate; //alter mm-dd-yyyy to yyyy-mm-dd (if change in recurring in edit state, create recurring with orginal start date)
	$partsArr = explode("-",$orgDate);
	$transactionDate = $partsArr[2]."-".$partsArr[0]."-".$partsArr[1];
	
	if ($processState=="add"){
		$budgetID = "";
		$budgetTypeID = "";
	}
	$budgetYN = "no"; //set flag to indicate no budget 
	
	if ($processState=="edit"){ //check for edit state
		$edtStage = "no"; //set flag for edit changes
		if ($recurringChgYN =="yes"){ //check for recurring - delete all and recreate all using orginal start date
			$budgetTransInfoArr = $accountInfo->delAcctRecurringChg($memberID,$accountID,$transID);//if edit and change in recurring - delete all and insert new budgets
			$edtStage = "yes";
			if ($recurringOrgStart == ""){
				$transactionDate = $transactionDate; //no recurring before - use current transaction date
			}else{
				$transactionDate = $recurringOrgStart; //if change in recurring in edit state, create recurring with orginal start date
			}
			$processState = "add";
			if ($budgetTransInfoArr[0] !="" and $budgetTransInfoArr[0] !=0 and !is_null($budgetTransInfoArr[0])){ 
				$budgetYN = "yes";
				$budgetID = $budgetTransInfoArr[0];
				$budgetTypeID = $budgetTransInfoArr[1];
			}else{
				$budgetID = "";
				$budgetTypeID = "";
			}
		} 
		
		if ($edtStage == "no"){
			//check for budtransaction amount - if yes, get budtransactionID to be updated
			$foundGroupSetID = "";
			$budgetTransInfoArr = $accountInfo->getAcctBudgetID($memberID,$accountID,$transID);
			if ($budgetTransInfoArr[0] !="" and $budgetTransInfoArr[0] !=0 and !is_null($budgetTransInfoArr[0])){ 
				$budgetYN = "yes"; //get budtransID
				$budgetID = $budgetTransInfoArr[0]; //get budgetID for new account
				$budgetTypeID = $budgetTransInfoArr[1]; //get budgetTransID for new account
				$foundGroupSetID = $budgetTransInfoArr[2]; //groupSetID - for use with budget and account situation
				$budTransID = $budgetTotInst->retBudtransactionIDF($memberID,$budgetTransInfoArr[0],$budgetTransInfoArr[2]);
			}else{
				$budgetID = ""; 
				$budgetTypeID = "";
			}
			if ($accountSelectCt > 0){
				$foundGroupSetID = $budgetTransInfoArr[2]; //groupSetID - for use with budget and account situation
			}
		}
		
		if ($edtStage == "no" and $accountChgYN =="yes"){ //check for edit state and change in recurring
			$newGroupSetCreated = "no"; //set flag for new group inserted account
			if ($accountSelectCt > 0){
				$selectID = json_decode($accountAllSelect, true);//$abc = $obj->acctID[0]; //$selectID is not an object, cannot get the property[0]
				$controlSetID = 1;
				foreach($selectID as $row) {
					$transIDSpt = $row['transID'];
					$acctId = $row['acctID'];
					$acctTypeTransSpt = $row['acctType'];
					$acctChange = $row['chgOnAcct'];
					$acctAmtTemp = $row['acctAmt'];
					$acctAmtTemp = preg_replace('/[\$,]/', '', $acctAmtTemp);
					if ($acctAmtTemp=="" or $acctAmtTemp==0 or $acctAmtTemp==0.00 or $acctAmtTemp<0){
						$acctAmtTemp = 0;
					}
					$recurringID = $row['recurringSetID'];
					$groupSetID = $row['groupSetID'];
					
					if ($acctChange == "update"){ //update inserted account
						$accountInfo->updateChgAcct($memberID,$transIDSpt,$transactionDate,$posted,$categoryID,$acctAmtTemp,$acctId,$acctTypeTransSpt,$note,$groupSetID,$acctChange);
					}
					if ($acctChange == "new"){ //add new fresh inserted account
						if ($groupSetID == "" and $controlSetID == 1){ //total new inserted -- already have another inserted account and has a groupSetId already...
							$tempGroupSetID = $generalInfo->getGroupSetID($recurringID,$amount,$active);
							$controlSetID += 1;
							$groupSetID = $tempGroupSetID;
							$newGroupSetCreated = "yes"; //flag for new inserted account group - to update main_amount and main_trans_id
						}
						if ($groupSetID == "" and $controlSetID > 1){ 
							$groupSetID = $tempGroupSetID;//use the same groupSetID for more inserted accounts
						}
						
						if ($foundGroupSetID != ""){ //if there is budget, there's a groupSetID
							$groupSetID = $foundGroupSetID; //use the budgeted groupSetID, instead
						}
						
						$transOrderCounter = 0;
						$accountInfo->getTransOrderNum($transOrderCounter,$acctId,$transDate);
						$referenceNumber = "no"; //set referencenumber to no to indicate not the main grouping
						$accountInfo->insertAcctTrans($acctId,$memberID,$transactionDate,$posted,$transOrderCounter,$acctAmtTemp,$acctTypeTransSpt,$categoryID,$note,$referenceNumber,$budgetID,$budgetTypeID,$projectID,$recurringID,$groupSetID,$trkmemberID,$active);
					}
				}
			}else{
				$acctId = "";
				$acctTypeTrans = "";
				$groupSetID = "";
				$acctChange = "";
			}
			
			//update budtransaction all cases
			if ($budgetYN == "yes"){
				$acctIdTemp = "";
				$acctTypeTransTemp = "";
				$budgetTotInst->updateChgBudgetOnlyF($memberID,$budTransID,$transactionDate,$categoryID,$amount,$note,$acctChange,$forModule); //just budtransaction data
			}else{
				//if newly inserted account and no budgeted, need to establish groupSetID main_trans_id and main_amount
				if($newGroupSetCreated == "yes"){
					$generalInfo->updateMainGroupSetID($groupSetID,$transID,$amount);
				}
			}
			//update main account
			$acctChange = "no";
			$accountInfo->updateChgAcct($memberID,$transID,$transactionDate,$posted,$categoryID,$amount,$accountID,$transactionTypeID,$note,$groupSetID,$acctChange);
			
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
		
		$flag = "00"; //no image insert
		if ($edtStage == "no"){ //if no changes to recurring and inserted accounts, update budtransaction table only
			$acctChange = "no";
			$budgetTransInfoArr = $accountInfo->getAcctBudgetID($memberID,$accountID,$transID); //check for groupSetID
			if ($budgetTransInfoArr[2] !="" and $budgetTransInfoArr[2] !=0 and !is_null($budgetTransInfoArr[2])){
				$groupSetID = $budgetTransInfoArr[2];
			}else{
				$groupSetID = 0;
			}
			if ($budgetYN == "yes"){
				$budgetTotInst->updateChgBudgetOnlyF($memberID,$budTransID,$transactionDate,$categoryID,$amount,$note,$acctChange,$forModule); //just budtransaction data
				if ($groupSetID == 0){
					$accountInfo->updateChgAcct($memberID,$transID,$transactionDate,$posted,$categoryID,$amount,$accountID,$transactionTypeID,$note,$groupSetID,$acctChange);
				}else{
					$accountInfo->updateChgAcctGroup($memberID,$transID,$transactionDate,$posted,$categoryID,$amount,$accountID,$transactionTypeID,$note,$groupSetID,$acctChange);
				}
			}else{
				//???? no info in $groupSetID 
				$accountInfo->updateChgAcct($memberID,$transID,$transactionDate,$posted,$categoryID,$amount,$accountID,$transactionTypeID,$note,$groupSetID,$acctChange);
				$flag = $transID; //update image insert, if new insert 
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
			$transDateArr = $generalInfo->createRecurringDate($transactionDate,$recurringType,$recurringNum);//create recurring date array()
			$recurringID = $generalInfo->getRecurringID($recurringType,$recurringNum,$active);//create and return recurringID
			$groupSetIdArr = array();
			if ($accountYN == "yes"){///create group set it
				$groupSetIdArr = $generalInfo->getGroupSetIDArr($recurringID,$recurringNum,$amount,$active); //create multiple recurring groupSetID array()
			}else{
				$groupSetID = ""; //since there's no insertd accounts, no needed to create groupSetID
				if ($budgetYN == "yes"){
					$groupSetIdArr = $generalInfo->getGroupSetIDArr($recurringID,$recurringNum,$amount,$active); //create multiple recurring groupSetID array()
				}
			}	
		}else{
			$recurringID = "";
			if ($accountYN == "yes"){///create one group set it
				$groupSetID = $generalInfo->getGroupSetID($recurringID,$amount,$active);
			}else{
				$groupSetID = "";
				if ($budgetYN == "yes"){
					$groupSetID = $generalInfo->getGroupSetID($recurringID,$amount,$active); //create groupSetID if previously has budget
				}
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
			foreach($selectID as $row) {
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
		}
		
		///3. insert data - determine transaction_order on specific date//
		if ($accountYN == "yes"){
			$transOrderCounter = 0;
			if ($recurringYN=="yes"){
				for ($i=0; $i<$recurringNum; $i++){ //create transactionOrder and insert the data
					$transOrderCounter = $budgetTotInst->getBudOrderNumF($transOrderCounter,$transDateArr[$i]);
					$transOrderCounter += 1; //set the next transaction order
					if ($budgetYN == "yes"){
						$accountIDTemp = "";//budget doesn't need accountID
						$transactionTypeIDTemp = "";//budget doesn't need transactionTypeID
						$referenceNumber = "yes"; //set referencenumber to yes to indicate grouping
						$flag = $budgetTotInst->insertBudTransF($accountIDTemp,$memberID,$transDateArr[$i],$posted,$transOrderCounter,$amount,$transactionTypeIDTemp,$categoryID,$note,$referenceNumber,$budgetID,$budgetTypeID,$projectID,$recurringID,$groupSetIdArr[$i],$active);
						$referenceNumber = "no"; //reset to no so main_id isn't created in the account side
					}else{
						$referenceNumber = "yes"; //set referencenumber to yes to indicate grouping
					}
					$flag = $accountInfo->insertAcctTrans($accountID,$memberID,$transDateArr[$i],$posted,$transOrderCounter,$amount,$transactionTypeID,$categoryID,$note,$referenceNumber,$budgetID,$budgetTypeID,$projectID,$recurringID,$groupSetIdArr[$i],$trkmemberID,$active);
					$flag = "00"; //no image insert 
					$transOrderCounter = 0; //reset to start over
				}
			}else{
				$transOrderCounter = $budgetTotInst->getBudOrderNumF($transOrderCounter,$transactionDate);
				$transOrderCounter += 1;
				if ($budgetYN == "yes"){
					$accountIDTemp = "";//budget doesn't need accountID
					$transactionTypeIDTemp = "";//budget doesn't need transactionTypeID
					$referenceNumber = "yes"; //set referencenumber to yes to indicate grouping
					$flag = $budgetTotInst->insertBudTransF($accountIDTemp,$memberID,$transactionDate,$posted,$transOrderCounter,$amount,$transactionTypeIDTemp,$categoryID,$note,$referenceNumber,$budgetID,$budgetTypeID,$projectID,$recurringID,$groupSetID,$active);
					$referenceNumber = "no"; //reset to no so main_id isn't created in the account side
				}else{
					$referenceNumber = "yes"; //set referencenumber to yes to indicate grouping
				}
				$flag = $accountInfo->insertAcctTrans($accountID,$memberID,$transactionDate,$posted,$transOrderCounter,$amount,$transactionTypeID,$categoryID,$note,$referenceNumber,$budgetID,$budgetTypeID,$projectID,$recurringID,$groupSetID,$trkmemberID,$active);
				$flag = "00"; //no image insert
			}
			//******insert the spliting accounts
			$transOrderCounter = 0;
			for ($i=0; $i<$accountSelectCt; $i++){ //loop through to insert all inserted accounts - recurring or not
				if ($recurringYN=="yes"){
					for ($j=0; $j<$recurringNum; $j++){
						$transOrderCounter = $accountInfo->getTransOrderNum($transOrderCounter,$acctIdArr[$i],$transDateArr[$j]);
						$transOrderCounter += 1;
						$referenceNumber = "no"; //set referencenumber to no to indicate not the main grouping
						$flag = $accountInfo->insertAcctTrans($acctIdArr[$i],$memberID,$transDateArr[$j],$posted,$transOrderCounter,$acctAmt[$i],$acctTypeTransArr[$i],$categoryID,$note,$referenceNumber,$budgetID,$budgetTypeID,$projectID,$recurringID,$groupSetIdArr[$j],$trkmemberID,$active);
						$flag = "00"; //no image insert
						$transOrderCounter = 0;
					}
				}else{				
					$transOrderCounter = $accountInfo->getTransOrderNum($transOrderCounter,$acctIdArr[$i],$transactionDate);
					$transOrderCounter += 1;
					$referenceNumber = "no"; //set referencenumber to no to indicate not the main grouping
					$flag = $accountInfo->insertAcctTrans($acctIdArr[$i],$memberID,$transactionDate,$posted,$transOrderCounter,$acctAmt[$i],$acctTypeTransArr[$i],$categoryID,$note,$referenceNumber,$budgetID,$budgetTypeID,$projectID,$recurringID,$groupSetID,$trkmemberID,$active);
					$flag = "00"; //no image insert
					$transOrderCounter = 0;
				}
			}
		}else{ //end of yes inserted accounts - if else
			$transOrderCounter = 0;
			//*** insert the recurring of the current transaction
			if ($recurringYN=="yes"){
				for ($i=0; $i<$recurringNum; $i++){
					$transOrderCounter = $budgetTotInst->getBudOrderNumF($transOrderCounter,$transDateArr[$i]);					
					$transOrderCounter += 1;
					$referenceNumber = "no"; //set referencenumber to no to indicate no grouping
					if ($budgetYN == "yes"){
						$accountIDTemp = "";//budget doesn't need accountID
						$transactionTypeIDTemp = "";//budget doesn't need transactionTypeID
						$referenceNumber = "yes"; //set referencenumber to yes to indicate grouping
						$flag = $budgetTotInst->insertBudTransF($accountIDTemp,$memberID,$transDateArr[$i],$posted,$transOrderCounter,$amount,$transactionTypeIDTemp,$categoryID,$note,$referenceNumber,$budgetID,$budgetTypeID,$projectID,$recurringID,$groupSetIdArr[$i],$active);
						$referenceNumber = "no"; //set referencenumber to no to indicate not the main grouping
						$flag = $accountInfo->insertAcctTrans($accountID,$memberID,$transDateArr[$i],$posted,$transOrderCounter,$amount,$transactionTypeID,$categoryID,$note,$referenceNumber,$budgetID,$budgetTypeID,$projectID,$recurringID,$groupSetIdArr[$i],$trkmemberID,$active);
					}else{
						$referenceNumber = "no"; //set referencenumber to no to indicate not the main grouping
						$flag = $accountInfo->insertAcctTrans($accountID,$memberID,$transDateArr[$i],$posted,$transOrderCounter,$amount,$transactionTypeID,$categoryID,$note,$referenceNumber,$budgetID,$budgetTypeID,$projectID,$recurringID,$groupSetID,$trkmemberID,$active);
						$flag = "00"; //no image insert
					}
					$transOrderCounter = 0;
				}
			}else{
				//****** insert the current transaction
				$transOrderCounter = $budgetTotInst->getBudOrderNumF($transOrderCounter,$transactionDate);				
				$transOrderCounter += 1;
				if ($budgetYN == "yes"){
					$accountIDTemp = "";//budget doesn't need accountID
					$transactionTypeIDTemp = "";//budget doesn't need transactionTypeID
					$referenceNumber = "yes"; //set referencenumber to yes to indicate grouping
					$flag = $budgetTotInst->insertBudTransF($accountIDTemp,$memberID,$transactionDate,$posted,$transOrderCounter,$amount,$transactionTypeIDTemp,$categoryID,$note,$referenceNumber,$budgetID,$budgetTypeID,$projectID,$recurringID,$groupSetID,$active);
				}
				$referenceNumber = "no"; //set referencenumber to no to indicate not the main grouping
				$flag = $accountInfo->insertAcctTrans($accountID,$memberID,$transactionDate,$posted,$transOrderCounter,$amount,$transactionTypeID,$categoryID,$note,$referenceNumber,$budgetID,$budgetTypeID,$projectID,$recurringID,$groupSetID,$trkmemberID,$active);
				//allow image insert for single transaction for now! Tou 8/6
			}
		}
	}//end of if statement for add state
	echo $flag; //"result==>pass>";//confirm of success
?>