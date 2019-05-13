<?php
	if ($_SESSION['login']=="yes" and $_SESSION['log_ss'] != ""){
		$memberID = $_SESSION['mid'];
		
		$projectInfo = new projectInfoC();
		$budgetTotInst = new budgetInfoC();
		$accountInfo = new accountInfoC();
		$memberTotInst = new memberInfoC();
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
		
		$myGenReports = "Account Report";
		$yes = "yes";
		$showReport = "no";
		$accountNameMemberID = array();
		$projectNameID = array();
		
		//check budget rights
		$budgetArr = array();
		$budgetArrCt = 0;
		try{					
			$result = $db->prepare("SELECT DISTINCT account_id FROM $db_account_rights WHERE member_id=? AND active=?");
			$result->execute(array($memberID,$yes));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		$acctItems = $result->rowCount();
		if ($acctItems > 0){
			foreach ($result as $row) {
				$budgetArr[$budgetArrCt] = $row['account_id'];
				$budgetArrCt++;
			}
		}else{
			//no budget available
		}
		
		$budgetList = "";
		$budgetListArr = array();
		$budgetListArrCt = 0;
		for ($i=0; $i<$budgetArrCt; $i++){
			try{					
				$result = $db->prepare("SELECT account_id,member_id,name FROM $db_account WHERE account_id=? AND active=?");
				$result->execute(array($budgetArr[$i],$yes));
			} catch(PDOException $e) {
				print "<script> self.location='".$index_url."?err=d1000'; </script>";
			}
			$itemsFound = $result->rowCount();
			if ($itemsFound > 0){
				foreach ($result as $row){
					if ($row['member_id'] == $memberID){
						$budgetList = $budgetList.'<input type="checkbox" id="a'.$row['account_id'].'" name="a'.$row['account_id'].'" value="'.$row['account_id'].'"> '.str_replace('\\','',$row['name']).'<br>';
					}else{
						$budgetList = $budgetList.'<input type="checkbox" id="a'.$row['account_id'].'" name="a'.$row['account_id'].'" value="'.$row['account_id'].'"> '.str_replace('\\','',$row['name']).' ('.$memberTotInst->memberNameF($row['member_id']).')<br>';
					}
					$budgetListArr[$budgetListArrCt] = $row['account_id'];
					$budgetListArrCt++;
				}
			}
		}//end of for loop
		
		if($_SERVER['REQUEST_METHOD'] == "POST"){
			$showReport = "yes";
			$reportName = $_POST['rname'];
			$grpbyCat = $_POST['gcategory'];
			
			if ($reportName == ""){
				$reportName = $memberTotInst->memberFLNameF($memberID)." Report";
			}else{
				$reportName = preg_replace('/[^A-Za-z0-9\-()<>= \/]/', '', $reportName);
				$reportName = str_replace('"', "", $reportName); //remove " from note
			}
			
			$orgDateStart = $_POST['start_date']; //alter mm-dd-yyyy to yyyy-mm-dd
			$partsArr = explode("-",$orgDateStart);
			$orgDateStart = $partsArr[0]."/".$partsArr[1]."/".$partsArr[2];
			$startDate = $partsArr[2]."-".$partsArr[0]."-".$partsArr[1];
			$orgDateEnd = $_POST['end_date']; //alter mm-dd-yyyy to yyyy-mm-dd
			$partsArr = explode("-",$orgDateEnd);
			$orgDateEnd = $partsArr[0]."/".$partsArr[1]."/".$partsArr[2];
			$endDate = $partsArr[2]."-".$partsArr[0]."-".$partsArr[1];
			
			$categoryIdArr = array();
			$categoryIdDebCreArr = array();
			$categoryIdArrCt = 0;
			for ($i=0; $i<$budgetListArrCt; $i++){
				$budgetTempID = "a".$budgetListArr[$i]; 
				if ($_POST[$budgetTempID] == $budgetListArr[$i]){
					try{//get account info based on accountID					
						$result = $db->prepare("SELECT DISTINCT category_id,transaction_type_id FROM $db_transaction WHERE account_id=? AND transaction_date>=? AND transaction_date<=? ORDER BY category_id ASC");
						$result->execute(array($budgetListArr[$i],$startDate,$endDate));
					} catch(PDOException $e) {
						print "<script> self.location='".$index_url."?err=d1000'; </script>";
					}
					$uniqueCategoryIdCt = $result->rowCount();
					if ($uniqueCategoryIdCt > 0){
						foreach ($result as $row){
							$foundCategoryID = "no";
							if ($categoryIdArrCt == 0){
								$categoryIdArr[$categoryIdArrCt] = $row['category_id'];
								$categoryIdDebCreArr[$categoryIdArrCt] = $row['transaction_type_id'];
								$categoryIdArrCt++;
							}else{
								for ($j=0; $j<$categoryIdArrCt; $j++){
									if ($categoryIdArr[$j] == $row['category_id']){
										$foundCategoryID = "yes";
									}
								}
								if ($foundCategoryID == "no"){
									$categoryIdArr[$categoryIdArrCt] = $row['category_id'];
									$categoryIdDebCreArr[$categoryIdArrCt] = $row['transaction_type_id'];
									$categoryIdArrCt++;
								}
							}
						}
					}//end if ($uniqueCategoryIdCt > 0)
				}//end if ($_POST[$budgetTempID] == $budgetListArr[$i])
			}
			
			$showBudgetListArr = array();
			$showBudgetListArrCt = 0;
			$incomeAmtList = "";
			$expenseAmtList = "";
			$incomeTot = 0;
			$expenseTot = 0;
			$grandBalance = 0;
			
			$accountListByCategoryArr = array();
			$accountListPerCategoryCtArr = array();
			$accountListByCategoryTotArr = array();
			for ($j=0; $j<$categoryIdArrCt; $j++){
				$accountListByCategoryArr[$j] = "";
				$accountListByCategoryTotArr[$j] = 0;
				$accountListPerCategoryCtArr[$j] = 0;
			}
			
			//group by category
			$grpCatTot = 0;
			$tempCatID = 0;
			$tempCatIDCt = 0;
			$changeCatID = "no";
			$categoryCt = 0;
			for ($i=0; $i<$budgetListArrCt; $i++){
				$budgetTempID = "a".$budgetListArr[$i]; 
				if ($_POST[$budgetTempID] == $budgetListArr[$i]){
					try{//get account info based on accountID					
						$result = $db->prepare("SELECT transaction_id,transaction_date,category_id,transaction_type_id,amount,description,project_id FROM $db_transaction WHERE account_id=? AND transaction_date>=? AND transaction_date<=? ORDER BY category_id ASC,transaction_date ASC");
						$result->execute(array($budgetListArr[$i],$startDate,$endDate));
					} catch(PDOException $e) {
						print "<script> self.location='".$index_url."?err=d1000'; </script>";
					}
					$itemsFound = $result->rowCount();
					if ($itemsFound > 0){
						foreach ($result as $row){
							$orgDate = $row['transaction_date']; //alter yyyy-mm-dd to mm-dd
							$partsArr = explode("-",$orgDate);
							$transDate = $partsArr[1]."/".$partsArr[2]; //get month/date
							$description = str_replace("'", "", $row['description']); //remove " from note
							$description = str_replace('\\','',$description);
							$accountNameMemberID = $accountInfo->accountNameF($budgetListArr[$i]); //get account name
							$tempAcctMemName = str_replace('\\','',$accountNameMemberID[0]);
							$tempAcctMemName = str_replace("'", "",$tempAcctMemName);
							if ($row['project_id'] == "" or $row['project_id'] == 0 or $row['project_id'] == "NULL"){ 
								$categoryName = $accountInfo->categoryNameF($row['category_id']);//get category name
							}else{
								$projectNameID = $projectInfo->projectNameIDF($row['project_id']);//get project name
								$categoryName = $projectNameID[0];
							}
							$categoryName = str_replace('\\','',$categoryName);
							$categoryName = str_replace("'", "",$categoryName);
							
							if ($row['transaction_type_id'] == "1000"){
								$incomeTot = $incomeTot + $row['amount'];
							}
							if ($row['transaction_type_id'] == "1001"){
								$expenseTot = $expenseTot + $row['amount'];
							}
							
							for ($j=0; $j<$categoryIdArrCt; $j++){
								if ($categoryIdArr[$j] == $row['category_id']){
									if ($row['transaction_type_id'] == "1000"){
										if ($j % 2 == 0){
											$accountListByCategoryArr[$j] = $accountListByCategoryArr[$j].'<tr><td class="row-bg_alt">'.$categoryName.'</span></td><td class="row-bg_alt">'.$description.'</td><td class="row-bg_alt">'.$tempAcctMemName.'</td><td class="row-bg_alt">'.$transDate.'</td><td class="row-bg_alt">'.$budgetTotInst->convertDollarF($row['amount']).'</td></tr>';
										}
										if ($j % 2 != 0){
											$accountListByCategoryArr[$j] = $accountListByCategoryArr[$j].'<tr><td>'.$categoryName.'</span></td><td>'.$description.'</td><td>'.$tempAcctMemName.'</td><td>'.$transDate.'</td><td>'.$budgetTotInst->convertDollarF($row['amount']).'</td></tr>';
										}
										$accountListByCategoryTotArr[$j] = $accountListByCategoryTotArr[$j] + $row['amount'];
									}
									if ($row['transaction_type_id'] == "1001"){
										if ($j % 2 == 0){
											$accountListByCategoryArr[$j] = $accountListByCategoryArr[$j].'<tr><td class="row-bg_alt">'.$categoryName.'</span></td><td class="row-bg_alt">'.$description.'</td><td class="row-bg_alt">'.$tempAcctMemName.'</td><td class="row-bg_alt">'.$transDate.'</td><td class="row-bg_alt">('.$budgetTotInst->convertDollarF($row['amount']).')</td></tr>';
										}
										if ($j % 2 != 0){
											$accountListByCategoryArr[$j] = $accountListByCategoryArr[$j].'<tr><td>'.$categoryName.'</span></td><td>'.$description.'</td><td>'.$tempAcctMemName.'</td><td>'.$transDate.'</td><td>('.$budgetTotInst->convertDollarF($row['amount']).')</td></tr>';
										}
										$accountListByCategoryTotArr[$j] = $accountListByCategoryTotArr[$j] - $row['amount'];
									}
									$accountListPerCategoryCtArr[$j] = $accountListPerCategoryCtArr[$j] + 1;  
								}
							}
						}
					}
				}//if budgetID is checked
			}//end of for loop
			
			for ($j=0; $j<$categoryIdArrCt; $j++){
				$incomeAmtList = $incomeAmtList.$accountListByCategoryArr[$j];
				if ($accountListPerCategoryCtArr[$j] > 1 and $grpbyCat == "yes"){
					if ($j % 2 == 0){
						$incomeAmtList = $incomeAmtList.'<tr><th colspan="4"  class="row-bg_alt"></th><th  class="row-bg_alt">'.$budgetTotInst->convertDollarF($accountListByCategoryTotArr[$j]).'</th></tr>';
					}
					if ($j % 2 != 0){
						$incomeAmtList = $incomeAmtList.'<tr><th colspan="4"></th><th>'.$budgetTotInst->convertDollarF($accountListByCategoryTotArr[$j]).'</th></tr>';
					}
				}
				$grandBalance = $grandBalance + $accountListByCategoryTotArr[$j];
			}
			if ($categoryIdArrCt == 0){
				$incomeAmtList = '<tr><td colspan="4">No transactions</td></tr>';
			}
			if ($categoryIdArrCt % 2 == 0){
				$incomeAmtList = $incomeAmtList.'<tr><th colspan="4" class="row-bg_alt">Balance</th><th class="row-bg_alt">'.$budgetTotInst->convertDollarF($grandBalance).'</th></tr>';
			}
			if ($categoryIdArrCt % 2 != 0){
				$incomeAmtList = $incomeAmtList.'<tr><th colspan="4">Balance</th><th>'.$budgetTotInst->convertDollarF($grandBalance).'</th></tr>';
			}
			
			$datarpt = "<?php 
				header('Content-type: application/excel');
				header('Content-Disposition: attachment; filename=mx_report_file.xls');";
			$datarpt = $datarpt."echo '";
			$datarpt = $datarpt.'<html xmlns:x="urn:schemas-microsoft-com:office:excel">;
				<head>
					<!--[if gte mso 9]>
					<xml>
						<x:ExcelWorkbook>
							<x:ExcelWorksheets>
								<x:ExcelWorksheet>
									<x:Name>Sheet 1</x:Name>
									<x:WorksheetOptions>
										<x:Print>
											<x:ValidPrinterInfo/>
										</x:Print>
									</x:WorksheetOptions>
								</x:ExcelWorksheet>
							</x:ExcelWorksheets>
						</x:ExcelWorkbook>
					</xml>
					<![endif]-->
				</head>
				<body>';
			$datarpt = $datarpt.'<table class="table table-striped table-bordered table-hover">
                                    <tr><td colspan="5">Report Name: '.$reportName.'</td></tr>
									<tr><td colspan="5">&nbsp;&nbsp;Reporting Date: '.$orgDateStart.' to '.$orgDateEnd.'</td></tr>
                                        <tr>
                                            <th>Category</th>
                                            <th>Description</th>
                                            <th>Account Name</th>
                                            <th>Date</th>
                                            <th>Amount</th>
                                        </tr>
                                        '.$incomeAmtList.'
                                  </table>
			</body></html>';
			$datarpt = $datarpt."'; ?>";
			
			$myFile = 'm_reports/rpt001act_r'.$memberID.'.php';
			$fh = fopen($myFile, 'w') or die("can't open file");
			fwrite($fh, "");
			fwrite($fh, $datarpt);
			fclose($fh);
		}
	}else{  //if statement - not login
		print "<script> self.location='".$index_url."'; </script>";
	}
?>