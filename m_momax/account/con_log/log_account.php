<?php
	if ($_SESSION['login']=="yes" and $_SESSION['log_ss'] != ""){
		$memberID = $_SESSION['mid'];
		$budgetTotInst = new budgetInfoC();
		$accountInfo = new accountInfoC();
		$memberTotInst = new memberInfoC();
		$projectInfo = new projectInfoC();
		$generalInfo = new generalInfoC();
		$yes = "yes";
		$active = "yes";
		
		$month = $_GET['mo'];
		if ($month != ""){
			if (strlen($month)>5){
				$selectedMonth = substr($month,0,2);
				$selectedYear = substr($month,2,6);
			}
			else{
				$selectedMonth = substr($month,0,1);
				$selectedYear = substr($month,1,5);
			}
		}else{
			$selectedMonth = date("m");
			$selectedYear = date("Y");
		}		
		$actSelectedMonth = date("F", strtotime($selectedMonth."/1/".$selectedYear));
		$budgetMonth = date($selectedMonth."/15/".$selectedYear);
		$lastMonthDate = date($selectedYear."-".$selectedMonth."-15");
		
		$prePart = explode('-', date("m-d-Y", strtotime($budgetMonth ." -30 day")));
		$nextPart = explode('-', date("m-d-Y", strtotime($budgetMonth ." +30 day")));
		$preBudgetMonth = $prePart[0].$prePart[2];
		$nextBudgetMonth = $nextPart[0].$nextPart[2];
		
		$fiscalMonOrgId = $generalInfo->returnOrgIdF($memberID);
		$beginFiscalYMonth = $generalInfo->returnFiscalMonF($fiscalMonOrgId);
		if ($beginFiscalYMonth == "" or $beginFiscalYMonth < 1){
			$monthFY = 01;
		}else{
			$monthFY = $beginFiscalYMonth; //set up in the setting inc
		}
		$yearFY = date("Y");
		$strDateSelectedMonth = date("Y-m-d", strtotime($selectedYear."-".$selectedMonth."-1"));
		$strDateFiscalYear = date("Y-m-d", strtotime($selectedYear."-".$monthFY."-1"));
		$endDateFiscalYear = date("Y-m-d", strtotime($selectedYear."-".$monthFY."-15"));
		$endDateFiscalYear = date("Y-m-t", strtotime($endDateFiscalYear."+ 11 months"));
		
		//Get mybudget list//////////////////////////////////
		$accountID = $_GET['aid'];
		$accountNameMemberID = array();		
		$projectNameID = array();	
		$forecastBalance = 0;
		$actualBalance = 0;
		$incomeRows = ""; //all transaction rows
		$transList = ""; //transaction totals
		$accountNameMemberID = $accountInfo->accountNameF($accountID); //return accountID and accountName
		$accountRightsLevel = $accountInfo->accountSpcRightF($memberID,$accountID); //return accountAccessRights
		
		//determine if shared account or not///////////////////////////////////////
		if ($_GET['smid'] != ""){
			$myAccountOverview = $memberTotInst->memberNameF($accountNameMemberID[1]).": ".$actSelectedMonth." ".$selectedYear;
			$memberID = $_GET['smid'];
				$sharedMemberID = $_GET['smid'];
		}else{
			$myAccountOverview = $actSelectedMonth." ".$selectedYear;
		}
		
////////begin post info processing
		if($_SERVER['REQUEST_METHOD'] == "POST"){
			//standard parameters
			$actState = $_POST['actState'];
			
			if ($actState == "add" or $actState == "edit"){
				$allActiveAcct = $_POST['allActiveAcct'];
				$allActivdAcctCt = $_POST['allActivdAcctCt'];
				$memberID = $_POST['mid']; //use post data in case shared account
				$accountID = $_POST['aid'];
					$acctTypeID = $_POST['acctTypeID']; //not being use
				
				//new transaction
				$orgTransID = $_POST['orgTransID']; //previous transactionID if edit state
				$transDate = $_POST['trans_date'];//alter mm-dd-yyyy to yyyy-mm-dd
				$partsArr = explode("-",$transDate);
				$transactionDate = $partsArr[2]."-".$partsArr[0]."-".$partsArr[1];
				$posted = $_POST['posted'];
				if ($posted == ""){
					$posted = "no";
				}else{
					$posted = "yes";
				}
				$note = $_POST['note'];
				$note = str_replace('"', "", $note); //remove " from note 
				$referenceNumber = "";
				$budgetID = 0;
				$budgetTypeID = 0;
				$projectID = 0;
				$orgGroupSetID = $_POST['orgGroupSetID']; //previous GroupSetID if edit state
				$groupSetID = 0; //set later below 
				$trkmemberID = 0;
				$amount = $_POST['budgetAmount'];
				$amount = preg_replace('/[\$,]/', '', $amount);
				if ($amount=="" or $amount==0 or $amount==0.00 or $amount<0){
					$amount = 0;
				}
				$transactionTypeID = $_POST['cd'.$accountID];
				$categoryID = $_POST['budCategory'];
				$tagid = $_POST['selecttagid'];
				$annAccountSet = $_POST['annAccountSet'];
				if ($annAccountSet == "yes"){
					$budgerannsetID = $_POST['budgerannset'];
				}else{
					$budgerannsetID = 0;
				}
				$orgRecurringID = $_POST['orgRecurringID']; //previous RecurringID if edit state
				$recurring = $_POST['recurring'];
				$edtrecurringacct = $_POST['edtrecurringacct'];
				if ($recurring == "yes"){
					$recurringType = $_POST['recurring_ty'];
					$recurringNum = $_POST['recurring_no'];
					if ($recurringNum < 2 or $recurringNum == ""){ //make sure recurring num is 2 or more
						$recurringNum = 2;
					}
					$transDateArr = array(); 
					$transDateArr = $generalInfo->createRecurringDate($transactionDate,$recurringType,$recurringNum);//create recurring date array()
					$recurringID = $generalInfo->getRecurringID($recurringType,$recurringNum,$active);//create and return recurringID				
				}else{
					$recurringID = 0;//not recurring, set id to 0
				}
				
				$addacct = $_POST['addacct'];
				$acctLinkTotCt = 0;
				if ($addacct == "yes"){
					$acctLinkIdArr = array();
					$acctLinkTypeArr = array();
					$acctLinkAmtArr = array();
					$strVal = 0;
					$arrCt = 0;
					for ($i=0; $i<$allActivdAcctCt; $i++){
						$tempAcctId = substr($allActiveAcct,$strVal,9);
						if (isset($_POST['at'.$tempAcctId])){
							$acctLinkIdArr[$arrCt] = $tempAcctId;
							$tempAmtIn = preg_replace('/[\$,]/', '', $_POST['da'.$tempAcctId]);
							if ($tempAmtIn=="" or $tempAmtIn==0 or $tempAmtIn==0.00){
								$tempAmtIn = 0;
							}
							$acctLinkAmtArr[$arrCt] = $tempAmtIn;
							$acctLinkTypeArr[$arrCt] = $_POST['cd'.$tempAcctId];
							$arrCt++;
						}
						$strVal += 9;
					}
					$acctLinkTotCt = count($acctLinkIdArr);
				}
				
				//if state is edit, delete all transactions (current, recurring, or link accounts transactions then recreate)
				if ($actState == "edit"){
					if ($orgRecurringID != "" and $orgRecurringID != 0){
						try{//check for recurring difference, if not, do nothing
							$result = $db->prepare("SELECT recurring_type,no_of_recurring FROM $db_recurring WHERE recurring_id=?");
							$result->execute(array($orgRecurringID)); //get recurring info
						} catch(PDOException $e) {
							echo "message001 - Sorry, system is experincing problem. Please check back.";
						}
						$recurringChange = "no";
						foreach ($result as $row){
							if ($recurringType != $row['recurring_type']){
								$recurringChange = "yes";
							}
							if ($recurringNum != $row['no_of_recurring']){
								$recurringChange = "yes"; 
							}
						}
						
						if ($recurringChange == "yes"){ //only update when changes
							try{//update accounts
								$result = $db->prepare("UPDATE $db_transaction SET recurring_id=?,group_set_id=? WHERE member_id=? AND recurring_id=? AND posted=?");
								$result->execute(array(0,0,$memberID,$recurringID,"yes"));
							} catch(PDOException $e) {
								echo "message002 - Sorry, system is experincing problem. Please check back.";
							}
							try{//delete account in $db_transaction
								$result = $db->prepare("DELETE FROM $db_transaction WHERE member_id=? AND recurring_id=?");
								$result->execute(array($memberID,$orgRecurringID));
							} catch(PDOException $e) {
								echo "message003 - Sorry, system is experincing problem. Please check back.";
							}	
							$orgGroupSetID = "";
							$orgRecurringID = "";
							//$recurring = "no";
						}else{
							if ($edtrecurringacct == "all"){
								try{//recurring but no differences - update this single record info only
									//$result = $db->prepare("UPDATE $db_transaction SET transaction_date=?,amount=?,transaction_type_id=?,posted=?,description=?,category_id=?,budgetlist_id=?,tag_id=? WHERE member_id=? AND recurring_id=?");
									//$result->execute(array($transactionDate,$amount,$transactionTypeID,$posted,$note,$categoryID,$budgerannsetID,$tagid,$memberID,$orgRecurringID));
									$result = $db->prepare("UPDATE $db_transaction SET amount=?,transaction_type_id=?,posted=?,description=?,category_id=?,budgetlist_id=?,tag_id=? WHERE member_id=? AND recurring_id=?");
									$result->execute(array($amount,$transactionTypeID,$posted,$note,$categoryID,$budgerannsetID,$tagid,$memberID,$orgRecurringID));
								} catch(PDOException $e) {
									echo "message004 - Sorry, system is experincing problem. Please check back.";
								}
							
							}else{
								try{//recurring but no differences - update this single record info only
									$result = $db->prepare("UPDATE $db_transaction SET transaction_date=?,amount=?,transaction_type_id=?,posted=?,description=?,category_id=?,budgetlist_id=?,tag_id=? WHERE member_id=? AND transaction_id=?");
									$result->execute(array($transactionDate,$amount,$transactionTypeID,$posted,$note,$categoryID,$budgerannsetID,$tagid,$memberID,$orgTransID));
								} catch(PDOException $e) {
									echo "message004 - Sorry, system is experincing problem. Please check back.";
								}
							}
							$recurring = "no";
						}
					}else{
						$orgRecurringID = ""; //make sure it's empty
					}
					
					if ($orgGroupSetID != "" and $orgGroupSetID != 0){
						try{//check for link-account difference, if not, do nothing
							$result = $db->prepare("SELECT account_id,amount,transaction_type_id FROM $db_transaction WHERE member_id=? AND group_set_id=?");
							$result->execute(array($memberID,$orgGroupSetID)); //get recurring info
						} catch(PDOException $e) {
							echo "message005 - Sorry, system is experincing problem. Please check back.";
						}
						$itemsFound = ($result->rowCount())-1;
						$addacctChange = "no";
						if ($itemsFound == $acctLinkTotCt){
							foreach ($result as $row){
								if ($row['account_id'] != $accountID){
									for ($i=0; $i<$acctLinkTotCt; $i++){
										$compareMatch = "no";
										if ($row['account_id']==$acctLinkIdArr[$i]){
											$compareMatch = "yes";
											break; //when difference found, exit for loop
										}
									}
									if ($compareMatch == "no"){
										$addacctChange = "yes";
										break; //when difference found, exit foreach loop
									}
								}
							}
						}else{
							$addacctChange = "yes"; //link-accounts difference found
						}
						
						if ($addacctChange == "yes"){//only if there is change
							if ($recurringChange == "no"){//delete all recurring as well
								try{//update accounts
									$result = $db->prepare("UPDATE $db_transaction SET recurring_id=?,group_set_id=? WHERE member_id=? AND recurring_id=? AND posted=?");
									$result->execute(array(0,0,$memberID,$recurringID,"yes"));
								} catch(PDOException $e) {
									echo "message006 - Sorry, system is experincing problem. Please check back.";
								}
								try{//delete account in $db_transaction
									$result = $db->prepare("DELETE FROM $db_transaction WHERE member_id=? AND recurring_id=?");
									$result->execute(array($memberID,$orgRecurringID));
								} catch(PDOException $e) {
									echo "message007 - Sorry, system is experincing problem. Please check back.";
								}
								$recurring = "yes";
							}else{
								try{//update accounts
									$result = $db->prepare("UPDATE $db_transaction SET group_set_id=? WHERE member_id=? AND group_set_id=? AND posted=?");
									$result->execute(array(0,$memberID,$orgGroupSetID,"yes"));
								} catch(PDOException $e) {
									echo "message008 - Sorry, system is experincing problem. Please check back.";
								}
								try{//delete account in $db_transaction
									$result = $db->prepare("DELETE FROM $db_transaction WHERE member_id=? AND group_set_id=?");
									$result->execute(array($memberID,$orgGroupSetID));
								} catch(PDOException $e) {
									echo "message009 - Sorry, system is experincing problem. Please check back.";
								}
							}
							$orgGroupSetID = "";
							$orgRecurringID = "";
						}else{
							//recurring but no differences - update this single record info only
							try{//update accounts
								$result = $db->prepare("UPDATE $db_transaction SET transaction_date=?,amount=?,transaction_type_id=?,posted=?,description=?,category_id=?,budgetlist_id=?,tag_id=? WHERE member_id=? AND transaction_id=?");
								$result->execute(array($transactionDate,$amount,$transactionTypeID,$posted,$note,$categoryID,$budgerannsetID,$tagid,$memberID,$orgTransID));
							} catch(PDOException $e) {
								echo "message010 - Sorry, system is experincing problem. Please check back.";
							}
							for ($i=0; $i<$acctLinkTotCt; $i++){
								try{//update accounts
									$result = $db->prepare("UPDATE $db_transaction SET amount=?,transaction_type_id=? WHERE member_id=? AND group_set_id=? AND account_id=?");
									$result->execute(array($acctLinkAmtArr[$i],$acctLinkTypeArr[$i],$memberID,$orgGroupSetID,$acctLinkIdArr[$i]));
								} catch(PDOException $e) {
									echo "message011 - Sorry, system is experincing problem. Please check back.";
								}
							}
							$addacct = "no";
						}
					}else{
						$orgGroupSetID = ""; //make sure it's empty
						if ($recurringChange == "no" and $addacct == "yes"){
							try{//update accounts
								$result = $db->prepare("UPDATE $db_transaction SET recurring_id=?,group_set_id=? WHERE member_id=? AND recurring_id=? AND posted=?");
								$result->execute(array(0,0,$memberID,$recurringID,"yes"));
							} catch(PDOException $e) {
								echo "message002 - Sorry, system is experincing problem. Please check back.";
							}
							try{//delete account in $db_transaction
								$result = $db->prepare("DELETE FROM $db_transaction WHERE member_id=? AND recurring_id=?");
								$result->execute(array($memberID,$orgRecurringID));
							} catch(PDOException $e) {
								echo "message003 - Sorry, system is experincing problem. Please check back.";
							}
							$recurring = "yes";
						}
					}
					
					if ($orgRecurringID=="" and $orgGroupSetID == ""){
						try{//delete account in $db_transaction
							$result = $db->prepare("DELETE FROM $db_transaction WHERE member_id=? AND transaction_id=?");
							$result->execute(array($memberID,$orgTransID));
						} catch(PDOException $e) {
							echo "message012 - Sorry, system is experincing problem. Please check back.";
						}
					}
				}//end state: edit
				
				//if ($actState == "add"){	
					//insert the record for the account and do the rest below
					if ($recurring != "yes" and $addacct != "yes"){
						if (($orgRecurringID=="" and $orgGroupSetID == "")or $actState == "add") {
							$transOrderCounter = 0;
							$transOrderCounter = $budgetTotInst->getBudOrderNumF($transOrderCounter,$transactionDate);
							$transOrderCounter += 1; //set the next transaction order
							
							$flag = $accountInfo->insertAcctTrans($accountID,$memberID,$transactionDate,$posted,$transOrderCounter,$amount,$transactionTypeID,$categoryID,$note,$referenceNumber,$budgetID,$budgetTypeID,$projectID,$recurringID,$groupSetID,$trkmemberID,$budgerannsetID,$tagid,$active);
							$newTransIDs = $flag; //only the main transactionID
						}
					}			
					
					if ($recurring == "yes" and $addacct == "yes"){
						$groupSetIdArr = array();
						$groupSetIdArr = $generalInfo->getGroupSetIDArr($recurringID,$recurringNum,$amount,$active); //create multiple recurring groupSetID array()
						$transOrderCounter = 0;
						for ($j=0; $j<$recurringNum; $j++){ //create transactionOrder and insert the data
							$transOrderCounter = $budgetTotInst->getBudOrderNumF($transOrderCounter,$transDateArr[$j]);
							$transOrderCounter += 1; //set the next transaction order
							$flag = $accountInfo->insertAcctTrans($accountID,$memberID,$transDateArr[$j],$posted,$transOrderCounter,$amount,$transactionTypeID,$categoryID,$note,$referenceNumber,$budgetID,$budgetTypeID,$projectID,$recurringID,$groupSetIdArr[$j],$trkmemberID,$budgerannsetID,$tagid,$active);
							$transOrderCounter = 0; //reset to start over
							if ($j == 0){
								$newTransIDs = $flag; //only the main transactionID
							}
						}
						for ($i=0; $i<$acctLinkTotCt; $i++){ //loop through all link accountID and create recurring records
							$transOrderCounter = 0;
							for ($j=0; $j<$recurringNum; $j++){ //create transactionOrder and insert the data
								$transOrderCounter = $budgetTotInst->getBudOrderNumF($transOrderCounter,$transDateArr[$j]);
								$transOrderCounter += 1; //set the next transaction order
								$flag = $accountInfo->insertAcctTrans($acctLinkIdArr[$i],$memberID,$transDateArr[$j],$posted,$transOrderCounter,$acctLinkAmtArr[$i],$acctLinkTypeArr[$i],$categoryID,$note,$referenceNumber,$budgetID,$budgetTypeID,$projectID,$recurringID,$groupSetIdArr[$j],$trkmemberID,$budgerannsetID,$tagid,$active);
								$transOrderCounter = 0; //reset to start over
							}	
						}
					}
					if ($recurring != "yes" and $addacct == "yes"){
						$groupSetID = $generalInfo->getGroupSetID($recurringID,$amount,$active);
						$transOrderCounter = 0;
						$transOrderCounter = $budgetTotInst->getBudOrderNumF($transOrderCounter,$transactionDate);
						$transOrderCounter += 1; //set the next transaction order
						$flag = $accountInfo->insertAcctTrans($accountID,$memberID,$transactionDate,$posted,$transOrderCounter,$amount,$transactionTypeID,$categoryID,$note,$referenceNumber,$budgetID,$budgetTypeID,$projectID,$recurringID,$groupSetID,$trkmemberID,$budgerannsetID,$tagid,$active);
						$newTransIDs = $flag; //only the main transactionID
						
						$transOrderCounter = 0;
						for ($i=0; $i<$acctLinkTotCt; $i++){//loop through all link accountID and create recurring records
							$transOrderCounter = $budgetTotInst->getBudOrderNumF($transOrderCounter,$transactionDate);
							$transOrderCounter += 1; //set the next transaction order
							$flag = $accountInfo->insertAcctTrans($acctLinkIdArr[$i],$memberID,$transactionDate,$posted,$transOrderCounter,$acctLinkAmtArr[$i],$acctLinkTypeArr[$i],$categoryID,$note,$referenceNumber,$budgetID,$budgetTypeID,$projectID,$recurringID,$groupSetID,$trkmemberID,$budgerannsetID,$tagid,$active);
							$transOrderCounter = 0; //reset to start over
						}
					
					}
					if ($recurring == "yes" and $addacct != "yes"){
						$transOrderCounter = 0;
						for ($j=0; $j<$recurringNum; $j++){ //create transactionOrder and insert the data
							$transOrderCounter = $budgetTotInst->getBudOrderNumF($transOrderCounter,$transDateArr[$j]);
							$transOrderCounter += 1; //set the next transaction order
							$flag = $accountInfo->insertAcctTrans($accountID,$memberID,$transDateArr[$j],$posted,$transOrderCounter,$amount,$transactionTypeID,$categoryID,$note,$referenceNumber,$budgetID,$budgetTypeID,$projectID,$recurringID,$groupSetID,$trkmemberID,$budgerannsetID,$tagid,$active);
							$transOrderCounter = 0; //reset to start over
							if ($j == 0){
								$newTransIDs = $flag; //only the main transactionID
							}
						}
					}
				//}//end of add state			
				
				//begin upload image////////////////////////////////
				if ($_FILES['mx_attach']['name']!=""){
					//$newTransIDs = $_POST['newTransIds'];
					
					if ($newTransIDs != ""){
						//rename and upload file
						$target_path_on = $_SERVER['DOCUMENT_ROOT']."/m_attach/"; //all pics save in the main root
						$target_path = $_SERVER['DOCUMENT_ROOT']."/m_attach/"; //all pics save in the main root
						$target_path = $target_path.$newTransIDs.".". end(explode(".", $_FILES['mx_attach']['name']));
						$pix_name = $newTransIDs.".". end(explode(".", $_FILES['mx_attach']['name']));
						try{//get pix name
							$result = $db->prepare("SELECT attach_name FROM $db_transaction_attach WHERE transaction_id=?");
							$result->execute(array($newTransIDs)); //get recurring info
						} catch(PDOException $e) {
							print "<script> self.location='".$index_url."?err=d1000'; script>";
						}
						foreach ($result as $row){
							$cur_pix = str_replace('\\','',$row['attach_name']);
						}
							
						if (file_exists($target_path_on.$cur_pix)){
							unlink($target_path_on.$cur_pix); //delete file
						}
						
						$filename = stripslashes($_FILES['mx_attach']['name']);
						$extension = getExtension($filename);
						$extension = strtolower($extension);
						if (($extension == "jpg") or ($extension == "jpeg") or ($extension == "png") or ($extension == "gif")){ 
							// requires php5
							define('UPLOAD_DIR', 'm_attach/');
							$imgRece = $_POST['hidimg'];
							$imgRece = str_replace('data:image/png;base64,', '', $imgRece);
							$imgRece = str_replace(' ', '+', $imgRece);
							$data = base64_decode($imgRece);
							$file = UPLOAD_DIR . $newTransIDs .".png";
							$nfileName = $newTransIDs .".png";
							$success = file_put_contents($file, $data);
						}
						if ($cur_pix == ""){
							try{//if first time, insert pix 
								$result = $db->prepare("INSERT INTO $db_transaction_attach (attach_name,transaction_id,member_id,active) VALUES (?,?,?,?)");
								$result->execute(array($nfileName,$newTransIDs,$memberID,$yes));
							} catch(PDOException $e) {
								print "<script> self.location='".$index_url."?err=d1000'; script>";
							}
						}
						$target_path = $m_attach_path; //reset the attach path to orginal
					}//end of Yes transID
				}//end upload image////////////////////////////////
			
			}//end state: add and edit
			////////////////////////////////////////////////////////////////////////
			$actState = $_POST['actStateDel'];
			if ($actState == "delete"){
				$memberID = $_POST['midDel'];
				$accountID = $_POST['actidDel'];
				$transID = $_POST['orgDelTransID'];
				
				$delRecurringSelect = $_POST['delBudItem'];
				
				$recurringYN = $_POST['recurringDelYN'];
				$recurringID = $_POST['recurringDelID'];
				$recurringNum = $_POST['recurringDelNum'];
				$insertAcctYN = $_POST['insertAcctDelYN'];
				$groupSetID = $_POST['delGroupSetID'];
					//$delRecurring = $_POST['delRecurring'];

				$budgetTransInfo = array();
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
				}
				
			}//end state: delete
			
		}//end form post////////////////////////////////
        
////////begin all processing///////////////////////////////////////////////////
		
		//get total forecast and actual up until the end of last month, then add on to this month only...
		$totAccountItems = array();
		$lastMonthDate = date("Y-m-d", strtotime($budgetMonth ." -30 day"));
		$d = new DateTime($lastMonthDate); 
		$lastMonthDate = $d->format('Y-m-t');
				
		$totAccountItems = $accountInfo->eachAccountTotF($accountID,$memberID,$lastMonthDate);
		$forecastBalance = $totAccountItems[0];
		$actualBalance = $totAccountItems[1];
		
		try{//get transaction info from account table
			$result = $db->prepare("SELECT transaction_id,amount,transaction_type_id,category_id,description,transaction_date,posted,project_id,recurring_id,membertrack_id FROM $db_transaction WHERE account_id=? AND member_id=? AND MONTH(transaction_date)=? AND YEAR(transaction_date)=? AND active=? ORDER BY transaction_date ASC,transaction_id ASC");
			$result->execute(array($accountID,$memberID,$selectedMonth,$selectedYear,$yes));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; script>";
		}
		$transItems = $result->rowCount();
		if ($transItems > 0){//if data is found, build table to display
			foreach ($result as $row) {
				$orgDate = $row['transaction_date'];
				$partsArr = explode("-",$orgDate);
				$transaction_date = $partsArr[1]."/".$partsArr[2];//."/".substr($partsArr[0],2,2);
	  			$transaction_yr = $partsArr[0]; 
	  			$transaction_mo = $partsArr[1];
				
				if ($row['transaction_type_id']=="1000"){
					if($row['posted'] == "yes"){
						$actualBalance = $actualBalance + $row['amount'];
					}
					$forecastBalance = $forecastBalance + $row['amount'];
				}
				if ($row['transaction_type_id']=="1001"){
					if($row['posted'] == "yes"){
						$actualBalance = $actualBalance - $row['amount'];
					}
					$forecastBalance = $forecastBalance - $row['amount'];
				}
				if ($row['project_id'] == "" or $row['project_id'] == 0 or $row['project_id'] == "NULL"){
					if ($row['membertrack_id'] == "" or $row['membertrack_id'] == 0 or $row['membertrack_id'] == "NULL"){
						$actNameLink = "<a href='#' title='".$accountInfo->categoryNameF($row['category_id'])."' onclick='editBudgetItem(".$memberID.",".$accountID.",".$row['transaction_id'].",1)'>";
						$actEditLink = "<a href='#' onclick='editBudgetItem(".$memberID.",".$accountID.",".$row['transaction_id'].",1)'>";
						$actDeleteLink = "<a href='#' onclick='deleteBudgetItem(".$memberID.",".$accountID.",".$row['transaction_id'].",1)'>";
					}else{
						$actNameLink = "<a href='".$mx007tb."&mtid=".$row['membertrack_id']."' title='budget tracking'>";
						$actEditLink = "<a href='".$mx007tb."&mtid=".$row['membertrack_id']."' title='budget tracking'>";
						$actDeleteLink = "<a href='".$mx007tb."&mtid=".$row['membertrack_id']."' title='budget tracking'>";
					}
					if ($row['description'] == ""){
						$accountItemName = $accountInfo->categoryNameF($row['category_id']);
					}else{
						$accountItemName = str_replace('\\','',$row['description']);
					}
				}else{
					if ($_GET['smid'] != ""){
						$actNameLink = "<a href='".$mx006pj."&pid=".$row['project_id']."&smid=".$memberID."#pdetail' title='".str_replace('\\','',$row['description'])."'>";
						$actEditLink = "<a href='".$mx006pj."&pid=".$row['project_id']."&smid=".$memberID."#pdetail'>";
					}else{
						$actNameLink = "<a href='".$mx006pj."&pid=".$row['project_id']."&smid=#pdetail' title='".str_replace('\\','',$row['description'])."'>";
						$actEditLink = "<a href='".$mx006pj."&pid=".$row['project_id']."&smid=#pdetail'>";
					}
					$actDeleteLink = "<a href='".$mx005ps."'>";
					$projectNameID = $projectInfo->projectNameIDF($row['project_id']);
					$accountItemName = $projectNameID[0];
				}
				$incomeRows = "";
				if ($row['posted'] == "yes"){//posted transaction
					$incomeRows = "<tr><td nowrap>".$transaction_date."<i class='fa fa-check'></i></td>";
				}else{//not posted
					$incomeRows = "<tr><td nowrap>".$transaction_date."</td>";
				}
				if ($row['transaction_type_id']=="1000"){//credited transaction	
					$incomeRows = $incomeRows."<td>".$budgetTotInst->convertDollarF($row['amount'])."</td>";
				}
				if ($row['transaction_type_id']=="1001"){//debited transaction
					$incomeRows = $incomeRows."<td>(".$budgetTotInst->convertDollarF($row['amount']).")</td>";
				}
				if ($accountRightsLevel == 2 or $accountRightsLevel == 3){//edit rights
					if ($row['membertrack_id'] == "" or $row['membertrack_id'] == 0 or $row['membertrack_id'] == "NULL"){
						$incomeRows = $incomeRows."<td>".$actNameLink.$accountItemName."</a></td>";
					}else{
						$incomeRows = $incomeRows."<td>".$actNameLink.$memberTotInst->getMembertrackNameF($row['membertrack_id'])."</a></td>";
					}
				}else{ //read only rights
					if ($row['membertrack_id'] == "" or $row['membertrack_id'] == 0 or $row['membertrack_id'] == "NULL"){
						$incomeRows = $incomeRows."<td>".$accountItemName."</td>";
					}else{
						$incomeRows = $incomeRows."<td>".$actNameLink.$memberTotInst->getMembertrackNameF($row['membertrack_id'])."</a></td>";
					}
				}
				$incomeRows = $incomeRows."<td>".$budgetTotInst->convertDollarF($actualBalance)."</td><td>".$budgetTotInst->convertDollarF($forecastBalance)."</td>";
				
				if ($accountRightsLevel == 2 or $accountRightsLevel == 3){//edit rights
					$incomeRows = $incomeRows."<td nowrap>".$actEditLink."<button class='btn btn-xs btn-warning'><i class='fa fa-pencil'></i></button></a>";
				}else{//read only rights
					$incomeRows = $incomeRows."<td>";
				}
				if ($accountRightsLevel == 3){//delete rights
					$incomeRows = $incomeRows.$actDeleteLink."<button class='btn btn-xs btn-danger'><i class='fa fa-times'></i> </button></a></div></td></tr>";
				}else{//read only rights
					$incomeRows = $incomeRows."</div></td></tr>";
				}
				$transList = $incomeRows.$transList;
			}//end foreach loop
		} 
		
		//create total rows/////////////////////////////////////
		if ($transList == ""){
			if ($accountRightsLevel == 3){
				$transList = $transList."<tr><td>Balance</td><td></td><td></td><td>".$budgetTotInst->convertDollarF($actualBalance)."</td><td>".$budgetTotInst->convertDollarF($forecastBalance)."</td><td></td></tr>";
				$transList = $transList."<tr><th><a href='#' onclick='addBudgetItem(".$accountID.",1);return false;'><button class='btn btn-xs btn-success pull-left'>add transaction&nbsp;&nbsp;</button></a></th><th colspan='5'>No transaction available</th></tr>";
			}else{
				$transList = "<tr><th></th><th colspan='5'>No transaction available</th></tr>";
			}
		}else{
			if ($accountRightsLevel == 3){
				$transList = $transList."<tr><th colspan='6'><a href='#' onclick='addBudgetItem(".$accountID.",1);return false;'><button class='btn btn-xs btn-success pull-left'>add transaction&nbsp;&nbsp;</button></a></th></tr>";
			}else{
				$transList = $transList."<tr><th colspan='6'></th></tr>";
			}
		}
		
		//get category data list////////////////////////////////////
		$catItemsList = "";
		try{
			$result = $db->prepare("SELECT category_id,category FROM $db_category WHERE member_id=? AND active=? ORDER BY category ASC");
			$result->execute(array($memberID,$yes));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; script>";
		} 
		$catItems = $result->rowCount(); 
		if ($catItems > 0){
			foreach ($result as $row) {
				$catItemsList = $catItemsList."<option value='".$row['category_id']."'>".str_replace('\\','',$row['category'])."</option>";
			}
		}
		
		//get account data list/////////////////////////////
		$acctItemsList = "";
		$acctNumAll = "";
		$acctNumAllCt = 0;
		$annBudgetSet = "no";
		try{
			$result = $db->prepare("SELECT account_id,name,budgetyn FROM $db_account WHERE member_id=? AND active=? ORDER BY name ASC");
			$result->execute(array($memberID,$yes));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; script>";
		}
		$acctItems = $result->rowCount(); 
		if ($acctItems > 0){
			foreach ($result as $row) {
				if ($row['account_id'] != $accountID){
					$acctNumAll = $acctNumAll.$row['account_id'];
					$acctNumAllCt++;
					
				}
			}
			$result->execute(array($memberID,$yes));
			foreach ($result as $row) {
				if ($row['account_id'] != $accountID){
					$acctItemsList = $acctItemsList."<span id='dis".$row['account_id']."' style='display: ;'><label class='checkbox-inline'><input type='checkbox' id='at".$row['account_id']."' name='at".$row['account_id']."' value='".$row['account_id']."' onclick=turn_credit_debit(".$row['account_id'].",".$acctNumAllCt.",'".$acctNumAll."')>".str_replace('\\','',$row['name'])."</label><div class='radio'><input type='text' size='4' disabled='true' class='smtextbox_div' id='da".$row['account_id']."' name='da".$row['account_id']."' placeholder='$0.00' onchange='isNumber_amtina(this.id,this.value)'>&nbsp;<label><input type='radio' disabled='true' name='cd".$row['account_id']."' id='d".$row['account_id']."' value='1001'>Debit</label>&nbsp;&nbsp;<label><input type='radio' disabled='true' name='cd".$row['account_id']."' id='c".$row['account_id']."' value='1000'>Credit</label></div></span>";
				}else{
					if ($row['budgetyn']=="yes"){
						$annBudgetSet = "yes";
					}
				}
			}
		}
		if ($acctNumAllCt == 0){
			$acctItemsList = "No accounts avaiable";
		}
		
		//create account list for annual budgeting///////////////////////////
		if ($annBudgetSet == "yes"){
			try{					
				$result = $db->prepare("SELECT budgetlist_id,name,budget_id FROM $db_budgetlist WHERE member_id=? AND (startdate>=? and startdate<=?) and (enddate>=? and enddate<=?) AND active=? ORDER BY list_order ASC");
				$result->execute(array($memberID,$strDateFiscalYear,$endDateFiscalYear,$strDateSelectedMonth,$endDateFiscalYear,$yes));
			} catch(PDOException $e) {
				print "<script> self.location='".$index_url."?err=d1000'; </script>";
			}
			$budgetAnnList = "";
			$budgetListOffer = "";
			$budgetListOfferName = "";
			$budgetListOfferCt = 0;
			foreach ($result as $row) {
				$budgetAnnList = $budgetAnnList.'<option value="'.$row['budgetlist_id'].'">'.str_replace('\\','',$row['name']).'</option>';
				if ($memberID == 100100146 and $row['budget_id'] != 100171){
					if ($budgetListOfferCt == 0){
						$budgetListOffer = $row['budgetlist_id'];
						$budgetListOfferName = $row['name'];
					}else{
						$budgetListOffer = $budgetListOffer.",".$row['budgetlist_id'];
						$budgetListOfferName = $budgetListOfferName.",".$row['name'];
					}
					$budgetListOfferCt++;
				}
			}
		}		
		
		try{//get all groups with admin rights
			$result = $db->prepare("SELECT tag_id,name FROM $db_tag WHERE member_id=? AND active=? ORDER BY list_order ASC");
			$result->execute(array($memberID,$yes)); 
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		$tagFoundCt = $result->rowCount(); 
		$tagList = "";
		foreach ($result as $row){
			$tagList = $tagList."<option value='".$row['tag_id']."'>".str_replace('\\','',$row['name'])."</option>";	
		}
		
////////End all processing///////////////////////////////////////////////////
	}else{  //if statement - not login
		print "<script> self.location='".$index_url."'; </script>";
	}
?>