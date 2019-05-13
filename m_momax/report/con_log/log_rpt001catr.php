<?php
	if ($_SESSION['login']=="yes" and $_SESSION['log_ss'] != ""){
		$memberID = $_SESSION['mid'];
		
		$projectInfo = new projectInfoC();
		$budgetTotInst = new budgetInfoC();
		$accountInfo = new accountInfoC();
		$memberTotInst = new memberInfoC();
		$generalInfoC = new generalInfoC();
		
		if ($_GET['cy']==""){
			$currentYr = date(Y);
			$currentMon = date(F);	
			$budgetMonth = date(m);
		}else{
			$currentYr = $_GET['cy'];
			$budgetMonth = date(m);
		}
		$todayDate = strtotime(date('Y-m-d'));
		$firstDay = date('m-01-Y', $todayDate);
		$lastDay  = date('m-t-Y', $todayDate); 
		
		$myGenReports = "Budgeted Report";
		$yes = "yes";
		$showReport = "no";
		
		$categoryList = "";
		try{					
			$result = $db->prepare("SELECT category_id,category,description FROM $db_category WHERE member_id=? AND active=? ORDER BY category ASC");
			$result->execute(array($memberID,$yes));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		foreach ($result as $row){
			$categoryList = $categoryList."<option value='".$row['category_id']."'>".str_replace('\\','',$row['category'])."</option>";
		}
		//check account rights - own and shared accounts
		$accountListArr = array();
		$accountListArrCt = 0;
		try{					
			$result = $db->prepare("SELECT DISTINCT account_id FROM $db_account_rights WHERE member_id=? AND active=?");
			$result->execute(array($memberID,$yes));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		foreach ($result as $row) {
			$accountListArr[$accountListArrCt] = $row['account_id'];
			$accountListArrCt++;
		}
		$accountList = "";
		$accountCount = 0;
		for ($i=0; $i<$accountListArrCt; $i++){
			try{//build account dropdown	
				$result = $db->prepare("SELECT account_id,member_id,name FROM $db_account WHERE account_id=? AND active=? ORDER BY name");
				$result->execute(array($accountListArr[$i],$yes));
			} catch(PDOException $e) {
				print "<script> self.location='".$index_url."?err=d1000'; </script>";
			}
			foreach ($result as $row){
				$accountList = $accountList."<option value='".$row['account_id']."'>".str_replace('\\','',$row['name'])."</option>";
				$accountCount++;
			}
		}//end of for loop
		
		$categoryIdsArrCt = 0;
		$accountIdsArrCt = 0;
		if($_SERVER['REQUEST_METHOD'] == "POST"){
			$showReport = "yes";
			$categoryIds = $_POST['subcategoryid'];
			$categoryIdsArr = explode(",", $categoryIds);
			$categoryIdsArrCt = count($categoryIdsArr);
			$accountIds = $_POST['subaccountid'];
			$accountIdsCt = $_POST['subaccountct'];
			if ($accountIds != "" and $accountIdsCt > 0){
				$accountIdsArr = explode(",", $accountIds);
				$accountIdsArrCt = count($accountIdsArr);
			}else{
				$accountIdsArrCt = 0;
			}
			
			$rptStartDate = $_POST['start_date'];
			$partsArr = explode("-",$rptStartDate);
			$rptStartDate = $partsArr[2]."-".$partsArr[0]."-".$partsArr[1];
			$rptEndDate = $_POST['end_date'];
			$partsArr = explode("-",$rptEndDate);
			$rptEndDate = $partsArr[2]."-".$partsArr[0]."-".$partsArr[1];
			
			//get total amount for each selected categories
			$categoryAmtList = "";
			$categoryAmtListTot = "";
			$categoryAmtTot = 0;
			for ($i=0; $i<$categoryIdsArrCt; $i++){
				try{
					$result = $db->prepare("SELECT amount,category_id,transaction_type_id FROM $db_transaction WHERE category_id=? AND member_id=? AND transaction_date>=? AND transaction_date<=?");
					$result->execute(array($categoryIdsArr[$i],$memberID,$rptStartDate,$rptEndDate));
				} catch(PDOException $e) {
					print "<script> self.location='".$index_url."?err=d1000'; script>";
				}
				$itemsFound = $result->rowCount();
				$categoryAmt = 0;
				if ($itemsFound > 0){
					foreach ($result as $row) {
						if ($row['transaction_type_id'] == "1000"){
							$categoryAmt =  $categoryAmt + $row['amount'];
							$categoryAmtTot = $categoryAmtTot + $row['amount'];
						}
						if ($row['transaction_type_id'] == "1001"){
							$categoryAmt =  $categoryAmt - $row['amount'];
							$categoryAmtTot = $categoryAmtTot - $row['amount'];
						}
					}
				}
				if ($categoryAmt != 0){
					$categoryAmtList = $categoryAmtList."<tr><td>".$generalInfoC->getCategoryNameOnly($categoryIdsArr[$i])."</td><td>".$budgetTotInst->convertDollarF($categoryAmt)."</td></tr>";
				}
			}
			$categoryAmtListTot = $categoryAmtListTot."<tr><th>Total</th><th>".$budgetTotInst->convertDollarF($categoryAmtTot)."</th></tr>";
			
			if ($accountIdsArrCt > 0){
				$categoryGrandAmtTot = 0;
				$accountAmtList = "";
				$accountAmtListTot = "";
				$accountNameArr = array();	
				for ($h=0; $h<$accountIdsArrCt; $h++){//loop through accounts
					$accountNameArr = $accountInfo->accountNameF($accountIdsArr[$h]);
					$categoryAmtTot = 0;
					for ($i=0; $i<$categoryIdsArrCt; $i++){//loop through categories
						try{
							$result = $db->prepare("SELECT amount,category_id,transaction_type_id FROM $db_transaction WHERE account_id=? AND category_id=? AND member_id=? AND transaction_date>=? AND transaction_date<=?");
							$result->execute(array($accountIdsArr[$h],$categoryIdsArr[$i],$memberID,$rptStartDate,$rptEndDate));
						} catch(PDOException $e) {
							print "<script> self.location='".$index_url."?err=d1000'; script>";
						}
						$itemsFound = $result->rowCount();
						$categoryAmt = 0;
						if ($itemsFound > 0){
							foreach ($result as $row) {
								if ($row['transaction_type_id'] == "1000"){
									$categoryAmt =  $categoryAmt + $row['amount'];
									$categoryAmtTot = $categoryAmtTot + $row['amount'];
									$categoryGrandAmtTot = $categoryGrandAmtTot + $row['amount'];
								}
								if ($row['transaction_type_id'] == "1001"){
									$categoryAmt =  $categoryAmt - $row['amount'];
									$categoryAmtTot = $categoryAmtTot - $row['amount'];
									$categoryGrandAmtTot = $categoryGrandAmtTot - $row['amount'];
								}
							}
						}
						if ($categoryAmt != 0){
							$accountAmtList = $accountAmtList."<tr><td>".$generalInfoC->getCategoryNameOnly($categoryIdsArr[$i])."</td><td>".$accountNameArr[0]."</td><td>".$budgetTotInst->convertDollarF($categoryAmt)."</td></tr>";
						}
					}//end category for loop
				}//end account for loop
				$accountAmtListTot = $accountAmtListTot."<tr><th colspan='2'>Total</th><th>".$budgetTotInst->convertDollarF($categoryGrandAmtTot)."</th></tr>";
			}
						
		}//end of post form
		
	}else{  //if statement - not login
		print "<script> self.location='".$index_url."'; </script>";
	}
?>