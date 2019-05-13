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
		
		$myGenReports = "Budget Report";
		$yes = "yes";
		$showReport = "no";
		$budgetNameMemberID = array();
		$projectNameID = array();
		
		//check budget rights
		$budgetArr = array();
		$budgetArrCt = 0;
		try{					
			$result = $db->prepare("SELECT DISTINCT budget_id FROM $db_budget_rights WHERE member_id=? AND active=?");
			$result->execute(array($memberID,$yes));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		$acctItems = $result->rowCount();
		if ($acctItems > 0){
			foreach ($result as $row) {
				$budgetArr[$budgetArrCt] = $row['budget_id'];
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
				$result = $db->prepare("SELECT budget_id,member_id,name FROM $db_budget WHERE budget_id=? AND active=?");
				$result->execute(array($budgetArr[$i],$yes));
			} catch(PDOException $e) {
				print "<script> self.location='".$index_url."?err=d1000'; </script>";
			}
			$itemsFound = $result->rowCount();
			if ($itemsFound > 0){
				foreach ($result as $row){
					if ($row['member_id'] == $memberID){
						$budgetList = $budgetList.'<input type="checkbox" id="a'.$row['budget_id'].'" name="a'.$row['budget_id'].'" value="'.$row['budget_id'].'"> '.str_replace('\\','',$row['name']).'<br>';
					}else{
						$budgetList = $budgetList.'<input type="checkbox" id="a'.$row['budget_id'].'" name="a'.$row['budget_id'].'" value="'.$row['budget_id'].'"> '.str_replace('\\','',$row['name']).' ('.$memberTotInst->memberNameF($row['member_id']).')<br>';
					}
					$budgetListArr[$budgetListArrCt] = $row['budget_id'];
					$budgetListArrCt++;
				}
			}
		}//end of for loop
		
		if($_SERVER['REQUEST_METHOD'] == "POST"){
			$showReport = "yes";
			$reportName = $_POST['rname'];
			
			if ($reportName == ""){
				$reportName = $memberTotInst->memberFLNameF($memberID)." Report";
			}else{
				$reportName = preg_replace('/[^A-Za-z0-9\-()<>= "\/]/', '', $reportName);
			}
			
			$orgDateStart = $_POST['start_date']; //alter mm-dd-yyyy to yyyy-mm-dd
			$partsArr = explode("-",$orgDateStart);
			$orgDateStart = $partsArr[0]."/".$partsArr[1]."/".$partsArr[2];
			$startDate = $partsArr[2]."-".$partsArr[0]."-".$partsArr[1];
			$orgDateEnd = $_POST['end_date']; //alter mm-dd-yyyy to yyyy-mm-dd
			$partsArr = explode("-",$orgDateEnd);
			$orgDateEnd = $partsArr[0]."/".$partsArr[1]."/".$partsArr[2];
			$endDate = $partsArr[2]."-".$partsArr[0]."-".$partsArr[1];
			
			$budTypeInc = "1000";
			$budTypeExp = "1002";
			
			$showBudgetListArr = array();
			$showBudgetListArrCt = 0;
			$incomeAmtList = "";
			$expenseAmtList = "";
			$incomeTot = 0;
			$expenseTot = 0;
			$grandBalance = 0;
			for ($i=0; $i<$budgetListArrCt; $i++){
				$budgetTempID = "a".$budgetListArr[$i]; 
				if ($_POST[$budgetTempID] == $budgetListArr[$i]){
					$showBudgetListArr[$i] = "1";
					try{					
						$result = $db->prepare("SELECT transaction_date,category_id,budget_type_id,amount,description,project_id FROM $db_budtransaction WHERE budget_id=? AND (budget_type_id=? OR budget_type_id=?) AND member_id=? AND transaction_date>=? AND transaction_date<=? ORDER BY transaction_date ASC");
						$result->execute(array($budgetListArr[$i],$budTypeInc,$budTypeExp,$memberID,$startDate,$endDate));
					} catch(PDOException $e) {
						print "<script> self.location='".$index_url."?err=d1000'; </script>";
					}
					$itemsFound = $result->rowCount();
					
					if ($itemsFound > 0){
						foreach ($result as $row){
							$orgDate = $row['transaction_date']; //alter yyyy-mm-dd to mm-dd
							$partsArr = explode("-",$orgDate);
							$transDate = $partsArr[1]."/".$partsArr[2];
							$budgetNameMemberID = $budgetTotInst->budgetNameF($budgetListArr[$i]);
							if ($row['project_id'] == "" or $row['project_id'] == 0 or $row['project_id'] == "NULL"){
								$categoryName = $accountInfo->categoryNameF($row['category_id']);
							}else{	
								$projectNameID = $projectInfo->projectNameIDF($row['project_id']);
								$categoryName = str_replace('\\','',$projectNameID[0]);
							}
							if ($row['budget_type_id'] == "1000"){
								$incomeTot = $incomeTot + $row['amount'];
								$incomeAmtList = $incomeAmtList.'<tr><td><span class="ind-row">'.$categoryName.'</span></td><td>'.str_replace('\\','',$row['description']).'</td><td>'.str_replace('\\','',$budgetNameMemberID[0]).'</td><td>'.$transDate.'</td><td>'.$budgetTotInst->convertDollarF($row['amount']).'</td></tr>';
							}
							if ($row['budget_type_id'] == "1002"){
								$expenseTot = $expenseTot + $row['amount'];
								$expenseAmtList = $expenseAmtList.'<tr><td><span class="ind-row">'.$categoryName.'</span></td><td>'.str_replace('\\','',$row['description']).'</td><td>'.str_replace('\\','',$budgetNameMemberID[0]).'</td><td>'.$transDate.'</td><td>'.$budgetTotInst->convertDollarF($row['amount']).'</td></tr>';
							}
						}
					}
				}//if budgetID is checked
			}//end of for loop
			if ($incomeTot > 0){
				$incomeAmtList = $incomeAmtList.'<tr><th colspan="4"></th><th>'.$budgetTotInst->convertDollarF($incomeTot).'</th></tr>';
				$grandBalance = $budgetTotInst->convertDollarF($incomeTot);
			}
			if ($expenseTot > 0){
				$expenseAmtList = $expenseAmtList.'<tr><th colspan="4"></th><th>'.$budgetTotInst->convertDollarF($expenseTot).'</th></tr>';
				$grandBalance = $budgetTotInst->convertDollarF($expenseTot);
			}
			if ($incomeTot > 0 and $expenseTot > 0){
				$grandBalance = $budgetTotInst->convertDollarF($incomeTot-$expenseTot);
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
                                            <th>Income Category</th>
                                            <th>Description</th>
                                            <th>Budget Name</th>
                                            <th>Date</th>
                                            <th>Amount</th>
                                        </tr>
                                        '.$incomeAmtList.'
										<tr><th>Expense Category</th><th colspan="4"></th></tr>
										'.$expenseAmtList.'
										<tr>
                                      	<th>Net Balance</th><th colspan="3"></th>
										<th>'.$grandBalance.'</th></tr>
                                  </table>
			</body></html>';
			$datarpt = $datarpt."'; ?>";
			
			$myFile = 'm_reports/rpt001bud_r'.$memberID.'.php';
			$fh = fopen($myFile, 'w') or die("can't open file");
			fwrite($fh, "");
			fwrite($fh, $datarpt);
			fclose($fh);
			
		}
	}else{  //if statement - not login
		print "<script> self.location='".$index_url."'; </script>";
	}
?>