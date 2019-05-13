	<?php
	if ($_SESSION['login']=="yes" and $_SESSION['log_ss'] != ""){
		$memberID = $_SESSION['mid'];
		$budgetTotInst = new budgetInfoC();
		$accountInfo = new accountInfoC();
		$memberTotInst = new memberInfoC();
		$projectInfo = new projectInfoC();
		$generalInfo = new generalInfoC();
		$yes = "yes";
		
		//begin post info processing
		if($_SERVER['REQUEST_METHOD'] == "POST"){

		}
        
	//begin all processing///////////////////////////////////////////////////
		$accountID = $_GET['aid'];
		if ($_GET['cy']==""){
			$currentYr = date(Y);
			$currentMonL = date(F);
			$currentMon = date(m);	
		}else{
			$currentYr = $_GET['cy'];
			$currentMonL = date(F);
			$currentMon = date(m);
		}
		
		$fiscalMonOrgId = $generalInfo->returnOrgIdF($memberID);
		$beginFiscalYMonth = $generalInfo->returnFiscalMonF($fiscalMonOrgId);
		if ($beginFiscalYMonth == "" or $beginFiscalYMonth < 1){
			$monthFY = 01;
		}else{
			$monthFY = $beginFiscalYMonth; //set up in the setting inc
		}
		
		$monthName = array();
		for ($i = 0; $i < $numMonthShow; $i++){
			if ($i == 0){
				$budgetMonth = date('Y-m-d', strtotime($currentYr."-".$monthFY."-15"));
				$monthName[$i] = date('M', strtotime($budgetMonth));
			}else{
				$monthName[$i] = date('M', strtotime($budgetMonth));
			}
			$budgetMonth = date('Y-m-d', strtotime($budgetMonth. ' + 30 days'));
		}

		$beginYearDate = date('Y-m-d', strtotime($currentYr."-".$monthFY."-01"));
		$endYearDate = date('Y-m-t', strtotime($budgetMonth. ' - 30 days'));
		
		/////////////////////////////////////////////////////
		$accountNameMemberID = $accountInfo->accountNameF($accountID); //return accountID and accountName
		$accountRightsLevel = $accountInfo->accountSpcRightF($memberID,$accountID); //return accountAccessRights
		
		if ($_GET['smid'] != ""){
			$myAccountOverview = $memberTotInst->memberNameF($accountNameMemberID[1]).": ".$actSelectedMonth." ".$selectedYear;
			$memberID = $_GET['smid'];
			$sharedMemberID = $_GET['smid'];
		}else{
			$myAccountOverview = $actSelectedMonth." ".$selectedYear;
		}
		
		///////////////////////////////////////////////////
		try{ //get unique categoryIDs for all income
			$result = $db->prepare("SELECT DISTINCT category_id FROM $db_transaction WHERE account_id=? AND member_id=? AND transaction_type_id=? AND transaction_date>=? AND transaction_date<=? AND active=? ORDER BY category_id ASC");
			$result->execute(array($accountID,$memberID,1000,$beginYearDate,$endYearDate,$yes));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; script>";
		}
		$ctItems = $result->rowCount();
		$categoryIdIncArr = array();
		$categoryNameIncArr = array();
		$categoryIdIncArrCt = 0;
		if ($ctItems > 0){
			foreach ($result as $row) {
				$categoryIdIncArr[$categoryIdIncArrCt] = $row['category_id'];
				$categoryNameIncArr[$categoryIdIncArrCt] = $accountInfo->categoryNameF($row['category_id']);
				$categoryIdIncArrCt++;
			}
		}
		$categoryIncCt = count($categoryIdIncArr); //determine the count income category
		
		try{//get unique categorIDs for all savings and expenses
			$result = $db->prepare("SELECT DISTINCT category_id FROM $db_transaction WHERE account_id=? AND member_id=? AND transaction_type_id=? AND transaction_date>=? AND transaction_date<=? AND active=? ORDER BY category_id ASC");
			$result->execute(array($accountID,$memberID,1001,$beginYearDate,$endYearDate,$yes));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; script>";
		}
		$ctItems = $result->rowCount();
		$categoryIdExpArr = array();
		$categoryNameExpArr = array();
		$categoryFoundCt = 0;
		$categoryIdExpArrCt = 0;
		if ($ctItems > 0){
			foreach ($result as $row) {
				$categoryIdExpArr[$categoryIdExpArrCt] = $row['category_id'];
				$categoryNameExpArr[$categoryIdExpArrCt] = $accountInfo->categoryNameF($row['category_id']);
				$categoryIdExpArrCt++;
			}
		}
		$categoryExpCt = count($categoryIdExpArr); //determine the count expense category
		
		///////////////////////////////////////////////////
		//new
		$endLastYearDate = date("Y-m-d", strtotime($beginYearDate ." -30 day"));
		$lyDate = new DateTime($endLastYearDate); 
		$lastDayLastYear = $lyDate->format('Y-m-t');
		
		$totAccountItems = $accountInfo->eachAccountTotF($accountID,$memberID,$lastDayLastYear);
		$forecastBalance = $totAccountItems[0];
		$actualBalance = $totAccountItems[1];
		
		$monthlyIncTotList = array();
		$monthIncValFound = array();
		$monthlyExpTotList = array();
		$monthExpValFound = array();
		
		for ($i = 0; $i < $numMonthShow; $i++){ // loop through all the months and calculate totals
			if ($i == 0){
				$budgetMonth = date('Y-m-d', strtotime($currentYr."-".$monthFY."-15"));
			}
			$actMonth = date("m", strtotime($budgetMonth));
			$actYear = date("Y", strtotime($budgetMonth));
			
			try{//get transaction info from account table
				$result = $db->prepare("SELECT transaction_id,amount,transaction_type_id,category_id,transaction_date,posted FROM $db_transaction WHERE account_id=? AND member_id=? AND MONTH(transaction_date)=? AND YEAR(transaction_date)=? AND active=? ORDER BY category_id ASC");
				$result->execute(array($accountID,$memberID,$actMonth,$actYear,$yes));
			} catch(PDOException $e) {
				print "<script> self.location='".$index_url."?err=d1000'; script>";
			}
			$transItems = $result->rowCount();
			$creditTotal = array();
			$debitTotal = array();
			$numMonthShow = 12;
			
			if ($transItems > 0){//if data is found, build table to display
				foreach ($result as $row) {
					$monCreditTot = 0;
					$monDebitTot = 0;
					$orgDate = $row['transaction_date'];
					$partsArr = explode("-",$orgDate);
					$transaction_date = $partsArr[1]."/".$partsArr[2];//."/".substr($partsArr[0],2,2);
					$transaction_yr = $partsArr[0]; 
					$transaction_mo = $partsArr[1];
				
					if ($row['transaction_type_id']=="1000"){//credit
						for ($j = 0; $j < $categoryIncCt; $j++){//looop throuhg incomes
							if ($row['category_id']==$categoryIdIncArr[$j]){
								$monthlyIncTotList[$i][$j] = $monthlyIncTotList[$i][$j] + $row['amount'];
								$monthIncValFound[$i][$j] = $transaction_mo.$transaction_yr;
							}
						}
					}
					if ($row['transaction_type_id']=="1001"){//debit
						for ($k = 0; $k < $categoryExpCt; $k++){//looop throuhg expanses
							if ($row['category_id']==$categoryIdExpArr[$k]){
								$monthlyExpTotList[$i][$k] = $monthlyExpTotList[$i][$k] + $row['amount'];
								$monthExpValFound[$i][$j] = $transaction_mo.$transaction_yr;
							}
						}
					}					
				}//end foreach
			}//end more than 1 record
			
			$budgetMonth = date('Y-m-d', strtotime($budgetMonth. ' + 30 days'));
		}//end for loop 12 months
		
		$creditRowsList = "";
		$creditColArr = array();		
		$debitRowsList = "";
		$debitColArr = array();
		$debitSignleRowTot = 0;
		for ($j = 0; $j < $categoryIncCt; $j++){
			$creditRowsList = $creditRowsList.'<tr><td><div class="rowIndent">'.$categoryNameIncArr[$j].'</div></td>';
			$creditRow = "";
			$creditSignleRowTot = 0;
			for ($i = 0; $i < $numMonthShow; $i++){
				if ($monthlyIncTotList[$i][$j] > 0){
					$creditRow = $creditRow.'<td><a href="'.$mx003ac.'&aid='.$accountID.'&mo='.$monthIncValFound[$i][$j].'&smid='.$sharedMemberID.'">'.$budgetTotInst->convertDollarF($monthlyIncTotList[$i][$j]).'</a></td>';
				}else{
					$creditRow = $creditRow."<td>".$budgetTotInst->convertDollarF($monthlyIncTotList[$i][$j])."</td>";
				}
				$creditColArr[$i] = $creditColArr[$i] + $monthlyIncTotList[$i][$j];
				$creditSignleRowTot = $creditSignleRowTot + $monthlyIncTotList[$i][$j];
			}
			$creditRowsList = $creditRowsList.$creditRow.'<td><div class="pull-right">'.$budgetTotInst->convertDollarF($creditSignleRowTot)."</div></td></tr>";
		}
		
		for ($k = 0; $k < $categoryExpCt; $k++){
			$debitRowsList = $debitRowsList.'<tr><td><div class="rowIndent">'.$categoryNameExpArr[$k].'</div></td>';
			$debitRow = "";
			$debitSignleRowTot = 0;
			for ($i = 0; $i < $numMonthShow; $i++){
				if ($monthlyExpTotList[$i][$k] > 0){
					$debitRow = $debitRow.'<td><a href="'.$mx003ac.'&aid='.$accountID.'&mo='.$monthExpValFound[$i][$j].'&smid='.$sharedMemberID.'">'.$budgetTotInst->convertDollarF($monthlyExpTotList[$i][$k]).'</a></td>';
				}else{
					$debitRow = $debitRow."<td>".$budgetTotInst->convertDollarF($monthlyExpTotList[$i][$k])."</td>";
				}
				$debitColArr[$i] = $debitColArr[$i] + $monthlyExpTotList[$i][$k];
				$debitSignleRowTot = $debitSignleRowTot + $monthlyExpTotList[$i][$k];
			}
			$debitRowsList = $debitRowsList.$debitRow.'<td><div class="pull-right">'.$budgetTotInst->convertDollarF($debitSignleRowTot).'</div></td></tr>';
		}
		
		$creditRowNetTot = 0;
		$creditColTotList = "<tr><th>Total</th>";
		$debitRowNetTot = 0;
		$debitColTotList = "<tr><th>Total</th>";
		$netRowTotList = "<tr><th>Running Net Total</th>";
		$netRowTot = 0;
		$nextNetRollingTot = 0;
		
		for ($i = 0; $i < $numMonthShow; $i++){
			$creditRowNetTot = $creditRowNetTot + $creditColArr[$i];
			$creditColTotList =  $creditColTotList."<th>".$budgetTotInst->convertDollarF($creditColArr[$i])."</th>";
			
			$debitRowNetTot = $debitRowNetTot + $debitColArr[$i];
			$debitColTotList = $debitColTotList."<th>".$budgetTotInst->convertDollarF($debitColArr[$i])."</th>";
			$netRowTot = $netRowTot + ($creditColArr[$i]-$debitColArr[$i]);
			$netRowTotList = $netRowTotList."<th>".$budgetTotInst->convertDollarF($nextNetRollingTot+($creditColArr[$i]-$debitColArr[$i]))."</th>";
			$nextNetRollingTot = $nextNetRollingTot + ($creditColArr[$i]-$debitColArr[$i]);
		}
		$creditColTotList =  $creditColTotList.'<th><div class="pull-right">'.$budgetTotInst->convertDollarF($creditRowNetTot).'</div></th></tr>';
		$debitColTotList = $debitColTotList.'<th><div class="pull-right">'.$budgetTotInst->convertDollarF($debitRowNetTot).'</div></th></tr>';
		$netRowTotList = $netRowTotList.'<th><div class="pull-right">'.$budgetTotInst->convertDollarF($netRowTot).'</div></th></tr>';
////////////////////////////////////////////////////////////////////
		//report://
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
		$datarpt = $datarpt.'<table class="table table-striped table-bordered table-hover" id="budgetrowtable">
                                    <tr>
                                      <th nowrap="nowrap">Income/revenue</th>
                                      <th>'.$monthName[0].'</th>
                                      <th>'.$monthName[1].'</th>
                                      <th>'.$monthName[2].'</th>
                                      <th>'.$monthName[3].'</th>
                                      <th>'.$monthName[4].'</th>
                                      <th>'.$monthName[5].'</th>
                                      <th>'.$monthName[6].'</th>
                                      <th>'.$monthName[7].'</th>
                                      <th>'.$monthName[8].'</th>
                                      <th>'.$monthName[9].'</th>
                                      <th>'.$monthName[10].'</th>
                                      <th>'.$monthName[11].'</th>
                                      <th><div class="pull-right">Total</div></th>
                                    </tr>
                                    '.$creditRowsList.'
                                    '.$creditColTotList.'
                                    <tr><th colspan="14"></th></tr>
                                    <tr><th colspan="14">Expenses</th></tr>
                                    '.$debitRowsList.'
                                    '.$debitColTotList.'
                                    <tr><th colspan="14"></th></tr>
                                    '.$netRowTotList.'
                                  </table>
		</body></html>';
		$datarpt = $datarpt."'; ?>";
		
		$myMonthlyFile = 'm_reports/act001mview_r'.$memberID.'.php';
		$fh = fopen($myMonthlyFile, 'w') or die("can't open file");
		fwrite($fh, "");
		fwrite($fh, $datarpt);
		fclose($fh);
		
////////////////////////////////////////////////////////////////////		
		$qrtlyIncTotList = array();
		$qrtlyExpTotList = array();
		$qrtMonArr = array();
		$qrtMonArrCt = 0;
		
		for ($i = 0; $i < $numMonthShow; $i+=3){ 
			if ($i == 0){
				$tempstartQuarter = date('Y-m-d', strtotime($currentYr."-".$monthFY."-15"));
				$tempendQuarter = date('Y-m-d', strtotime($tempstartQuarter. ' + 2 months'));
				$startQuarter = date('Y-m-01', strtotime($tempstartQuarter));
				$endQuarter = date('Y-m-t', strtotime($tempendQuarter));
			}
			$qrtMonArr[$qrtMonArrCt] = date('M', strtotime($tempstartQuarter))."-".date('M', strtotime($tempendQuarter));
			$qrtMonArrCt++;
			//echo $startQuarter.">".$endQuarter."<br>";			
			try{//get transaction info from account table
				$result = $db->prepare("SELECT transaction_id,amount,transaction_type_id,category_id,transaction_date,posted FROM $db_transaction WHERE account_id=? AND member_id=? AND transaction_date>=? AND transaction_date<=? AND active=? ORDER BY category_id ASC");
				$result->execute(array($accountID,$memberID,$startQuarter,$endQuarter,$yes));
			} catch(PDOException $e) {
				print "<script> self.location='".$index_url."?err=d1000'; script>";
			}
			$transItems = $result->rowCount();
			
			if ($transItems > 0){//if data is found, build table to display
				foreach ($result as $row) {
					$qrtCreditTot = 0;
					$qrtDebitTot = 0;
					$orgDate = $row['transaction_date'];
					$partsArr = explode("-",$orgDate);
					$transaction_yr = $partsArr[0]; 
					$transaction_mo = $partsArr[1];
				
					if ($row['transaction_type_id']=="1000"){//credit
						for ($j = 0; $j < $categoryIncCt; $j++){//looop throuhg incomes
							if ($row['category_id']==$categoryIdIncArr[$j]){
								$qrtlyIncTotList[$i][$j] = $qrtlyIncTotList[$i][$j] + $row['amount'];
							}
						}
					}
					if ($row['transaction_type_id']=="1001"){//debit
						for ($k = 0; $k < $categoryExpCt; $k++){//looop throuhg expanses
							if ($row['category_id']==$categoryIdExpArr[$k]){
								$qrtlyExpTotList[$i][$k] = $qrtlyExpTotList[$i][$k] + $row['amount'];
							}
						}
					}					
				}//end foreach
			}//end more than 1 record
			
			$tempstartQuarter = date('Y-m-d', strtotime($tempendQuarter. ' + 1 months'));
			$tempendQuarter = date('Y-m-d', strtotime($tempstartQuarter. ' + 2 months'));
			$startQuarter = date('Y-m-01', strtotime($tempstartQuarter));
			$endQuarter = date('Y-m-t', strtotime($tempendQuarter));
		}//end for loop
				
		$qrtcreditRowsList = "";
		$qrtcreditColArr = array();		
		$qrtdebitRowsList = "";
		$qrtdebitColArr = array();
		$qrtdebitSignleRowTot = 0;
		for ($j = 0; $j < $categoryIncCt; $j++){
			$qrtcreditRowsList = $qrtcreditRowsList.'<tr><td><div class="rowIndent">'.$categoryNameIncArr[$j].'</div></td>';
			$qrtcreditRow = "";
			$qrtcreditSignleRowTot = 0;
			for ($i = 0; $i < $numMonthShow; $i+=3){
				$qrtcreditRow = $qrtcreditRow."<td>".$budgetTotInst->convertDollarF($qrtlyIncTotList[$i][$j])."</td>";
				$qrtcreditColArr[$i] = $qrtcreditColArr[$i] + $qrtlyIncTotList[$i][$j];
				$qrtcreditSignleRowTot = $qrtcreditSignleRowTot + $qrtlyIncTotList[$i][$j];
			}
			$qrtcreditRowsList = $qrtcreditRowsList.$qrtcreditRow.'<td><div class="pull-right">'.$budgetTotInst->convertDollarF($qrtcreditSignleRowTot).'</div></td></tr>';
		}
		
		for ($k = 0; $k < $categoryExpCt; $k++){
			$qrtdebitRowsList = $qrtdebitRowsList.'<tr><td><div class="rowIndent">'.$categoryNameExpArr[$k].'</div></td>';
			$qrtdebitRow = "";
			$qrtdebitSignleRowTot = 0;
			for ($i = 0; $i < $numMonthShow; $i+=3){
				$qrtdebitRow = $qrtdebitRow."<td>".$budgetTotInst->convertDollarF($qrtlyExpTotList[$i][$k])."</td>";
				$qrtdebitColArr[$i] = $qrtdebitColArr[$i] + $qrtlyExpTotList[$i][$k];
				$qrtdebitSignleRowTot = $qrtdebitSignleRowTot + $qrtlyExpTotList[$i][$k];
			}
			$qrtdebitRowsList = $qrtdebitRowsList.$qrtdebitRow.'<td><div class="pull-right">'.$budgetTotInst->convertDollarF($qrtdebitSignleRowTot).'</div></td></tr>';
		}
		
		$qrtcreditRowNetTot = 0;
		$qrtcreditColTotList = "<tr><th>Total</th>";
		$qrtdebitRowNetTot = 0;
		$qrtdebitColTotList = "<tr><th>Total</th>";
		$qrtnetRowTotList = "<tr><th>Running Net Total</th>";
		$qrtnetRowTot = 0;
		$qrtnextNetRollingTot = 0;
		
		for ($i = 0; $i < $numMonthShow; $i+=3){
			$qrtcreditRowNetTot = $qrtcreditRowNetTot + $qrtcreditColArr[$i];
			$qrtcreditColTotList =  $qrtcreditColTotList."<th>".$budgetTotInst->convertDollarF($qrtcreditColArr[$i])."</th>";
			
			$qrtdebitRowNetTot = $qrtdebitRowNetTot + $qrtdebitColArr[$i];
			$qrtdebitColTotList = $qrtdebitColTotList."<th>".$budgetTotInst->convertDollarF($qrtdebitColArr[$i])."</th>";
			$qrtnetRowTot = $qrtnetRowTot + ($qrtcreditColArr[$i]-$qrtdebitColArr[$i]);
			$qrtnetRowTotList = $qrtnetRowTotList."<th>".$budgetTotInst->convertDollarF($qrtnextNetRollingTot+($qrtcreditColArr[$i]-$qrtdebitColArr[$i]))."</th>";
			$qrtnextNetRollingTot = $qrtnextNetRollingTot + ($qrtcreditColArr[$i]-$qrtdebitColArr[$i]);
		}
		$qrtcreditColTotList =  $qrtcreditColTotList.'<th><div class="pull-right">'.$budgetTotInst->convertDollarF($qrtcreditRowNetTot)."</div></th></tr>";
		$qrtdebitColTotList = $qrtdebitColTotList.'<th><div class="pull-right">'.$budgetTotInst->convertDollarF($qrtdebitRowNetTot)."</div></th></tr>";
		$qrtnetRowTotList = $qrtnetRowTotList.'<th><div class="pull-right">'.$budgetTotInst->convertDollarF($qrtnetRowTot)."</div></th></tr>";
		
		//report://
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
                                    <tr>
                                      <th nowrap="nowrap">Income/revenue</th>
                                      <th>'.$qrtMonArr[0].'</th>
                                      <th>'.$qrtMonArr[1].'</th>
                                      <th>'.$qrtMonArr[2].'</th>
                                      <th>'.$qrtMonArr[3].'</th>
                                      <th class="pull-right">Total</th>
                                    </tr>
                                    '.$qrtcreditRowsList.'
                                    '.$qrtcreditColTotList.'
                                    <tr>
                                      <th>Expenses </th>
                                      <th></th>
                                      <th></th>
                                      <th></th>
                                      <th></th>
                                      <th></th>
                                    </tr>   
                                    '.$qrtdebitRowsList.'   
                                    '.$qrtdebitColTotList.'   
                                    <tr>
                                      <th colspan="6"></th>
                                    </tr>
                                    '.$qrtnetRowTotList.'                                                      
                                  </table>
		</body></html>';
		$datarpt = $datarpt."'; ?>";
		
		$myQuarterlyFile = 'm_reports/act001qview_r'.$memberID.'.php';
		$fh = fopen($myQuarterlyFile, 'w') or die("can't open file");
		fwrite($fh, "");
		fwrite($fh, $datarpt);
		fclose($fh);
		
	//End all processing///////////////////////////////////////////////////
	}else{  //if statement - not login
		print "<script> self.location='".$index_url."'; </script>";
	}
?>