<?php
	if ($_SESSION['login']=="yes" and $_SESSION['log_ss'] != ""){
		$memberID = $_SESSION['mid'];
		$budgetTotInst = new budgetInfoC();
		$accountInfo = new accountInfoC();
		$generalInfo = new generalInfoC();
		$memberTotInst = new memberInfoC();
		
		if ($_GET['cy']==""){
			$currentYr = date(Y);
			$currentMonL = date(F);
			$currentMon = date(m);	
		}else{
			$currentYr = $_GET['cy'];
			$currentMonL = date(F);
			$currentMon = date(m);
		}
		
		$month = $_GET['mo'];
		if (strlen($month)>5){
			$selectedMonth = substr($month,0,2);
			$selectedYear = substr($month,2,6);
		}
		else{
			$selectedMonth = substr($month,0,1);
			$selectedYear = substr($month,1,5);
		}

		$actSelectedMonth = date("F", strtotime($selectedMonth."/1/".$selectedYear));
		$budgetMonth = date($selectedMonth."/15/".$selectedYear);
		$prePart = explode('-', date("m-d-Y", strtotime($budgetMonth ." -30 day")));
		$nextPart = explode('-', date("m-d-Y", strtotime($budgetMonth ." +30 day")));
		$preBudgetMonth = $prePart[0].$prePart[2];
		$nextBudgetMonth = $nextPart[0].$nextPart[2];
		
		$myBudgetOverview = $currentYr." Budget Overview";
		$shareBudgetOverview = "Shared Budget - ".$currentMonL;
		
		//Get mybudget list
		$budgetID = $_GET['bid'];
		$yes = "yes";
		$myBudgetList = "";
		$myQuarterList = "";
		$totMyBudgetCount = 0;
		$budgetMonth = date("m/d/Y");
		$fiscalMonOrgId = $generalInfo->returnOrgIdF($memberID);
		$beginFiscalYMonth = $generalInfo->returnFiscalMonF($fiscalMonOrgId);
		//$monthFY = $beginFiscalYMonth; //set up in the setting inc
		if ($beginFiscalYMonth == "" or $beginFiscalYMonth < 1){
			$monthFY = 01;
		}else{
			$monthFY = $beginFiscalYMonth; //set up in the setting inc
		}
		
		$categoryIDArr = array(); //category ID
		$categoryNameArr = array(); //category Name
		$budgetNameMemberID = array(); //budget ID
		$totBudgetItems = array(); //total amount of budget
		$totBudgetItemsWk = array(); //total amount of budget
		$budQuarter1 = array(); //first quarter total by category
		$budQuarter2 = array(); //first quarter total by category
		$budQuarter3 = array(); //first quarter total by category
		$budQuarter4 = array(); //first quarter total by category
		$expQuarter1 = array(); //first quarter total by category
		$expQuarter2 = array(); //first quarter total by category
		$expQuarter3 = array(); //first quarter total by category
		$expQuarter4 = array(); //first quarter total by category
		$budFY = array(); //fiscal year total by category
		$budFYTot = 0;
		$budgetIncomeTot = 0;
		$budgetSavingTot = 0;
		$budgetExpenseTot = 0;
		
		
		$q1Start = date('Y-m-d', strtotime($currentYr."-".$monthFY."-15"));
		$annMonth = date('M', strtotime($currentYr."-".$monthFY."-15"));
		
		$q1Start = date('Y-m-d', strtotime($currentYr."-".$monthFY."-15"));
		$q1End = date('Y-m-d', strtotime($q1Start. ' + 60 days'));
		$q1Mon = date('M', strtotime($currentYr."-".$monthFY."-15"));
		$q1 = $q1Mon."-".date('M', strtotime($q1Start. ' + 60 days'));
		
		$q2Start = date('Y-m-d', strtotime($q1End. ' + 30 days'));
		$q2End = date('Y-m-d', strtotime($q2Start. ' + 60 days'));
		$q2Mon = date('M', strtotime($q1End. ' + 30 days'));
		$q2 = $q2Mon."-".date('M', strtotime($q2Start. ' + 60 days'));
		
		$q3Start = date('Y-m-d', strtotime($q2End. ' + 30 days'));
		$q3End = date('Y-m-d', strtotime($q3Start. ' + 60 days'));
		$q3Mon = date('M', strtotime($q2End. ' + 30 days'));
		$q3 = $q3Mon."-".date('M', strtotime($q3Start. ' + 60 days'));
		
		$q4Start = date('Y-m-d', strtotime($q3End. ' + 30 days'));
		$q4End = date('Y-m-d', strtotime($q2Start. ' + 60 days'));
		$q4Mon = date('M', strtotime($q3End. ' + 30 days'));
		$q4 = $q4Mon."-".date('M', strtotime($q4Start. ' + 60 days'));
				
		$budgetNameMemberID = $budgetTotInst->budgetNameF($budgetID);
		if ($_GET['smid'] != ""){
			$myBudgetOverview = $memberTotInst->memberNameF($_GET['smid'])." : ".$budgetNameMemberID[0]." (".$currentYr." Overview)";
			$memberID = $_GET['smid'];
			$sharedMemberID = $_GET['smid'];
		}else{
			$myBudgetOverview = str_replace('\\','',$budgetNameMemberID[0])." (".$currentYr." Overview)";
		}
		
		//////////////////////////////////////////////////////////////////////////////////////////////////
		$monthName = array();
		for ($i = 0; $i < $numMonthShow; $i++){ // loop through all the months and calculate totals
			if ($i == 0){
				$budgetMonth = date('Y-m-d', strtotime($currentYr."-".$monthFY."-15"));
				$monthName[$i] = date('M', strtotime($budgetMonth));
			}
			$writeMonth = date("F", strtotime($budgetMonth));
			$actMonth = date("m", strtotime($budgetMonth));
			$actYear = date("Y", strtotime($budgetMonth));
			
			list($totBudgetItems,$totBudgetItemsWk) = $budgetTotInst->eachBudgetTotF($budgetID,$memberID,$actMonth,$actYear);
			$budgetIncomeTot = $budgetIncomeTot + $totBudgetItems[0]; //add income total
			$budgetSavingTot = $budgetSavingTot + $totBudgetItems[1]; //add saving total
			$budgetExpenseTot = $budgetExpenseTot + $totBudgetItems[2]; //add expenses total
			$myBudgetList = $myBudgetList."<tr><td><a href='".$mx002pb."&bid=".$budgetID."&mo=".$actMonth.$actYear."&smid=".$_GET['smid']."'>".$writeMonth."</a></td><td>".$budgetTotInst->convertDollarF($totBudgetItems[0])."</td><td>".$budgetTotInst->convertDollarF($totBudgetItems[1]+$totBudgetItems[2])."</td><td>".$budgetTotInst->convertDollarF($totBudgetItems[0]-($totBudgetItems[1]+$totBudgetItems[2]))."</td></tr>";
			
			if ($i > 0){
				$monthName[$i] = date('M', strtotime($budgetMonth));
			}
			
			$budgetMonth = date('Y-m-d', strtotime($budgetMonth. ' + 30 days'));
		}//end for loop
		$myBudgetGrandTot = "<tr><th></th><th>".$budgetTotInst->convertDollarF($budgetIncomeTot)."</th><th>".$budgetTotInst->convertDollarF($budgetSavingTot+$budgetExpenseTot)."</th><th>".$budgetTotInst->convertDollarF($budgetIncomeTot-($budgetSavingTot+$budgetExpenseTot))."</th></tr>";
			
//***************************************************************				
		//create a unique list of budgetTypeID(1000) for all income 
		//create a unique list of budgetTypeID(1001 & 1002) for all expenese
		$startCatDate = date('Y-m-d', strtotime($currentYr."-".$monthFY."-1")); //begin fiscal year
		$endCatDate = date('Y-m-t', strtotime($startCatDate. ' + 11 months')); //end fiscal year
		
		$categoryAllIdArr = array();
		
		$categoryIdIncArr = array();
		$categoryNameIncArr = array();
		$categoryIdExpArr = array();
		$categoryNameExpArr = array();
		
		$categoryIdSavExpArr = array();
		$categoryNameSavExpArr = array();
		$accountIDArr = array();
		$accountNameArr = array();
		$accountBudYNArr = array();
		$budTypeInc = "1000";
		$budTypeSav = "1001";
		$budTypeExp = "1002";
		
		//************************************************************
		//get all categoryID with budget items listed
		//************************************************************
		$ctItems = 0;
		try{ //get unique categoryIDs for all income
			$result = $db->prepare("SELECT DISTINCT category_id,budget_type_id FROM $db_budtransaction WHERE budget_id=? AND member_id=? AND budget_type_id=? AND transaction_date>=? AND transaction_date<=? AND active=? ORDER BY category_id ASC");
			$result->execute(array($budgetID,$memberID,$budTypeInc,$startCatDate,$endCatDate,$yes));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; script>";
		}
		$ctItems = $result->rowCount();

		$categoryIdIncArrCt = 0;
		if ($ctItems > 0){
			foreach ($result as $row) {
				$categoryIdIncArr[$categoryIdIncArrCt] = $row['category_id'];
				$categoryNameIncArr[$categoryIdIncArrCt] = $accountInfo->categoryNameF($row['category_id']);
				$categoryIdIncArrCt++;
			}
		}
		$categoryIncCt = count($categoryIdIncArr); //determine the count income category
		
		$ctItems = 0;
		try{//get unique categorIDs for all savings and expenses
			$result = $db->prepare("SELECT DISTINCT category_id FROM $db_budtransaction WHERE budget_id=? AND member_id=? AND (budget_type_id=? OR budget_type_id=?) AND transaction_date>=? AND transaction_date<=? AND active=? ORDER BY category_id ASC");
			$result->execute(array($budgetID,$memberID,$budTypeSav,$budTypeExp,$startCatDate,$endCatDate,$yes));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; script>";
		}
		$ctItems = $result->rowCount();

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
		
		//************************************************************
		//get account with setting for budgeting
		//************************************************************
			try{//get account set for budgeting	
				$result = $db->prepare("SELECT account_id,budgetyn FROM $db_account WHERE member_id=? AND active=? ORDER BY list_order ASC");
				$result->execute(array($memberID,$yes));
			} catch(PDOException $e) {
				print "<script> self.location='".$index_url."?err=d1000'; script>";
			}
			$ctItems = $result->rowCount();
	
			$accountFoundCt = 0;
			if ($ctItems > 0){
				foreach ($result as $row) {
					if ($row['budgetyn'] == "yes"){
						$accountIDArr[$accountFoundCt] = $row['account_id'];
						$accountNameArr[$accountFoundCt] = $accountInfo->accountNameF($row['account_id']);
						$accountBudYNArr[$accountFoundCt] = $row['budgetyn'];
						$accountFoundCt++;
					}
				}
			}
			$accountIDCt = count($accountIDArr);
		
		//************************************************************
		//process income by month for selected year
		//************************************************************
		$ctItems = 0;
		try{
			$result = $db->prepare("SELECT budgetlist_id,budget_id,budget_type_id,name,list_order,amount,startdate,enddate,ann_mon,description,active FROM $db_budgetlist WHERE member_id=? AND budget_id=? AND (startdate>=? AND startdate<=?) AND (enddate>=? AND enddate<=?) AND active=? ORDER BY list_order ASC");
			$result->execute(array($memberID,$budgetID,$startCatDate,$endCatDate,$startCatDate,$endCatDate,$yes));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; script>";
		}
		$ctItems = $result->rowCount();
		$budgetlistIdArr = array();
		$budgetlistNameArr = array();
		$budgetlistIdArrCt = 0;
		
		$categoryFoundCt = 0;
		if ($ctItems > 0){
			foreach ($result as $row) {
				$budgetlistIdArr[$budgetlistIdArrCt] = $row['budgetlist_id'];
				$budgetlistNameArr[$budgetlistIdArrCt] = $row['name'];
				$budgetlistIdArrCt++;
			}
		}
		
		//if there are project details amount, get project date
		$budgetlistDateArr = array();	
		for ($j = 0; $j < $budgetlistIdArrCt; $j++){
			try{					
				$result = $db->prepare("SELECT project_id FROM $db_project_detail WHERE budgetlist_id=?");
				$result->execute(array($budgetlistIdArr[$j]));
			} catch(PDOException $e) {
				print "<script> self.location='".$index_url."?err=d1000'; </script>";
			}
			$itemCount = $result->rowCount();
			foreach ($result as $row) {
				$projectID = $row['project_id'];
			}
			if ($itemCount > 0){
				try{					
					$result = $db->prepare("SELECT date FROM $db_project WHERE project_id=?");
					$result->execute(array($projectID));
				} catch(PDOException $e) {
					print "<script> self.location='".$index_url."?err=d1000'; </script>";
				}
				foreach ($result as $row) {
					$budgetlistDateArr[$j] = $row['date']; 
				}	
			}
		}
		
		$monthlyIncTot = array();
		$monthlyIncTotList = array();
		$monthlyExpTot = array(); 
		$monthlyExpTotList = array();
		$quarterCt = 1;
		
		$monthlyExpColTot = array();
		$monthlyExpColTotCt = 0;
		
		$expMonthlyTotList = "";
		$expMonthlyGrdTot = 0;
		for ($i = 0; $i < $budgetlistIdArrCt; $i++){
			$expMonthlyTot = 0;
			$expMonthlyTotList = $expMonthlyTotList.'<tr><td><div class="rowIndent"><a href="'.$mx003.'">'.$budgetlistNameArr[$i]."</a></div></td>";
			for ($j = 0; $j < $numMonthShow; $j++){ 
				if ($j == 0){
					$budgetMonthly = date('Y-m-d', strtotime($currentYr."-".$monthFY."-15")); //start date based on predetermine month and year
				}
				$writeMonth = date("F", strtotime($budgetMonthly));
				$monStrDate = date('Y-m-1', strtotime($budgetMonthly));
				$monEndDate = date('Y-m-t', strtotime($budgetMonthly));
				
				$monBudTot = 0;
				$monBudTot = $budgetTotInst->getBudActualF($memberID,$budgetlistIdArr[$i],$monStrDate,$monEndDate);
				if ($monStrDate <= $budgetlistDateArr[$i] and $budgetlistDateArr[$i] <= $monEndDate){
					$monBudTot = $monBudTot + $budgetTotInst->getProjActualF($memberID,$budgetlistIdArr[$i],1000);;
				}
				$monBudTot = abs($monBudTot); //turn monthly expenses to positive
				$expMonthlyTotList = $expMonthlyTotList."<td>".$budgetTotInst->convertDollarF($monBudTot)."</td>";
				$expMonthlyTot = $expMonthlyTot + $monBudTot; //accommulate total actual expenses for each budgetID
				$monthlyExpColTot[$j] = $monthlyExpColTot[$j] + $monBudTot;
				
				$budgetMonthly = date('Y-m-d', strtotime($budgetMonthly. ' + 1 months')); //increment by month
			}
			
			$expMonthlyGrdTot = $expMonthlyGrdTot + $expMonthlyTot;
			$expMonthlyTotList = $expMonthlyTotList.'<td><span class="pull-right">'.$budgetTotInst->convertDollarF($expMonthlyTot)."</span></td></tr>";
		}
		
		//************************************************************
		//Get annual budgets - operating incomes/funds
		//************************************************************
		$monthlyIncTot = array(); //each month total
		$annBudgetTotList = "";
		$annBudgetTotListTemp = "";
		$annBudgetMonthTot = array();
		$annBudgetGrandTot = 0;
		
		try{
			$result = $db->prepare("SELECT name,list_order,amount,startdate FROM $db_budgetlist WHERE member_id=? AND budget_id=? AND YEAR(startdate)=? AND active=? ORDER BY list_order ASC");
			$result->execute(array($memberID,$budgetID,$currentYr,$yes));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; script>";
		}
		$ctItems = $result->rowCount();
		$ctItemsCt = 0;
		
		if ($ctItems > 0){
			foreach ($result as $row){
				$orgDate = $row['startdate'];
				$partsArr = explode("-",$orgDate);
				$tempBudMon = $partsArr[1]; //return record month
				$tempBudYrs = $partsArr[0]; //return record year
				
				$annBudgetTotListTemp = ""; //set string to empty for each record
				$currentBudgetFound = 0; //set budget amount to 0
				$currentBudgetFoundSave = 0; //start with 0
				$foundBudgetFlag = "no"; //set flag if budget amount is found or not
				$checkMonthlyValExist = "no"; //set flag if amount is greater than 0
				$annBudgetTotListTemp = $annBudgetTotListTemp.'<tr><td nowrap><div class="rowIndent"><a href="'.$mx002.'">'.str_replace('\\','',$row['name'])."</a></div></td>";
				for ($i = 0; $i < $numMonthShow; $i++){
					if (($i+$monthFY) == $tempBudMon and $currentYr == $tempBudYrs){ //check for specific month and year
						$annBudgetTotListTemp = $annBudgetTotListTemp.'<td>'.$budgetTotInst->convertDollarF($row['amount']).'</td>';
						$monthlyIncTot[$i] = $monthlyIncTot[$i] + $row['amount']; //add budget amount to the total first month non-operating income
						$currentBudgetFoundSave = $row['amount']; //set budget amount found
						$foundBudgetFlag = "yes"; //found budget amount
						$checkMonthlyValExist = "yes"; //amount great than $0 found
						$amtStartMonthArr[$ctItemsCt] = $i; //month amount found - set this for quarterly calucation
						$ctItemsCt++;
					}else{
						$annBudgetTotListTemp = $annBudgetTotListTemp."<td>$0.00</td>"; //if budget amount not found yet, set $0
					}
				}//end of for loop 12 months
				if ($checkMonthlyValExist == "yes"){ //only display annual budget if has at least one value.
					$annBudgetTotList = $annBudgetTotList.$annBudgetTotListTemp.'<td><span class="pull-right">'.$budgetTotInst->convertDollarF($currentBudgetFoundSave)."</span></td></tr>";
					$annBudgetGrandTot = $annBudgetGrandTot + $currentBudgetFoundSave; //grand total of operating budget/funds
				}else{
					$annBudgetTotList = $annBudgetTotList.$annBudgetTotListTemp.'<td><span class="pull-right">$0.00</span></td></tr>';
				}
			}//end of foreach reccord
		}//end of more than 1 record found
		
//////////////////////////////////////////////////////////////////		
		//create monthly total row
		$monthlyAnnTotRow = "";
		$monthlyAnnGrandTot = 0;
		$monthlyIncTotRow = "";
		$monthlyIncGrandTot = 0;
		$monthlyExpTotRow = "";
		$monthlyExpGrandTot = 0;
		$monthlyNetTotRow = "";
		$monthlyNetGrandTot = 0;
		$lastAnnBudgetTot = 0;
		for ($i = 0; $i < $numMonthShow; $i++){
			$monthlyAnnTotRow = $monthlyAnnTotRow."<th>".$budgetTotInst->convertDollarF($monthlyIncTot[$i])."</th>";
			$monthlyAnnGrandTot = $monthlyAnnGrandTot + $monthlyIncTot[$i];
			
			$monthlyExpTotRow = $monthlyExpTotRow."<th>".$budgetTotInst->convertDollarF($monthlyExpColTot[$i])."</th>";
			$monthlyExpGrandTot = $monthlyExpGrandTot + $monthlyExpColTot[$i];
			
			if ($i == 0){
				$tempNetGranTot = $monthlyIncTot[$i] - $monthlyExpColTot[$i];
			}else{
				$tempNetGranTot = (($monthlyIncTot[$i]+$monthlyIncColTot[$i])-$monthlyExpColTot[$i])+$tempNetGranTot;
			}
			$monthlyNetTotRow = $monthlyNetTotRow."<th>".$budgetTotInst->convertDollarF($tempNetGranTot)."</th>";
			$monthlyNetGrandTot = $monthlyNetGrandTot + ($monthlyIncColTot[$i] - $monthlyExpColTot[$i]);
		}
		
		$monthlyAnnTotRow = "<tr><th></th>".$monthlyAnnTotRow.'<th><span class="pull-right">'.$budgetTotInst->convertDollarF($monthlyAnnGrandTot)."</span></th></tr>";
		$monthlyExpTotRow = "<tr><th></th>".$monthlyExpTotRow.'<th><span class="pull-right">'.$budgetTotInst->convertDollarF($monthlyExpGrandTot)."</span></th></tr>";
		$monthlyNetTotRow = "<tr><th>Running Variance</th>".$monthlyNetTotRow.'<th><span class="pull-right">'.$budgetTotInst->convertDollarF(($monthlyIncGrandTot+$annBudgetGrandTot)-$monthlyExpGrandTot)."</span></th></tr>";

//////////////////////////////////////////////////////////////////		
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
                                      <th>Operating Income</th>
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
                                      <th class="pull-right">Total</th>
                                    </tr>
									'.$annBudgetTotList.'
                                    '.$monthlyAnnTotRow.'
                                    <tr><th colspan="14"></th></tr>
                                    <tr><th colspan="14">Expenses</th></tr>
                                    '.$expMonthlyTot.'
                                    '.$monthlyExpTotRow.'
                                    <tr><th colspan="14"></th></tr>
                                    '.$monthlyNetTotRow.'
                                  </table>
		</body></html>';
		$datarpt = $datarpt."'; ?>";
		
		$myMonthlyFile = 'm_reports/bud001mview_r'.$memberID.'.php';
		$fh = fopen($myMonthlyFile, 'w') or die("can't open file");
		fwrite($fh, "");
		fwrite($fh, $datarpt);
		fclose($fh);

////////////////////////////////////////////////////////////////////////////////		
		//****************************************
		//process both saving and expense by quarters
		//****************************************	
		$budgetQuarter = date('Y-m-d', strtotime($currentYr."-".$monthFY."-15"));
		$monStrQuarter = date('Y-m-d', strtotime($currentYr."-".$monthFY."-1"));
		$monEndQuarter = date('Y-m-t', strtotime($budgetQuarter.' + 2 months'));
		$qrt1BudTot = array();
		for ($j = 0; $j < $budgetlistIdArrCt; $j++){
			$qrt1BudTot[$j] = $budgetTotInst->getBudActualF($memberID,$budgetlistIdArr[$j],$monStrQuarter,$monEndQuarter);
			if ($monStrQuarter <= $budgetlistDateArr[$j] and $budgetlistDateArr[$j] <= $monEndQuarter){
				$qrt1BudTot[$j] = $qrt1BudTot[$j] + $budgetTotInst->getProjActualF($memberID,$budgetlistIdArr[$j],1000);
			}
		}
		$budgetQuarter = date('Y-m-d', strtotime($budgetQuarter.' + 3 months'));
		$monStrQuarter = date('Y-m-1', strtotime($budgetQuarter));
		$monEndQuarter = date('Y-m-t', strtotime($budgetQuarter.' + 2 months'));
		$qrt2BudTot = array();
		for ($j = 0; $j < $budgetlistIdArrCt; $j++){
			$qrt2BudTot[$j] = $budgetTotInst->getBudActualF($memberID,$budgetlistIdArr[$j],$monStrQuarter,$monEndQuarter);
			if ($monStrQuarter <= $budgetlistDateArr[$j] and $budgetlistDateArr[$j] <= $monEndQuarter){
				$qrt2BudTot[$j] = $qrt2BudTot[$j] + $budgetTotInst->getProjActualF($memberID,$budgetlistIdArr[$j],1000);
			}
		}
		$budgetQuarter = date('Y-m-d', strtotime($budgetQuarter.' + 3 months'));
		$monStrQuarter = date('Y-m-1', strtotime($budgetQuarter));
		$monEndQuarter = date('Y-m-t', strtotime($budgetQuarter.' + 2 months'));
		$qrt3BudTot = array();
		for ($j = 0; $j < $budgetlistIdArrCt; $j++){
			$qrt3BudTot[$j] = $budgetTotInst->getBudActualF($memberID,$budgetlistIdArr[$j],$monStrQuarter,$monEndQuarter);
			if ($monStrQuarter <= $budgetlistDateArr[$j] and $budgetlistDateArr[$j] <= $monEndQuarter){
				$qrt3BudTot[$j] = $qrt3BudTot[$j] + $budgetTotInst->getProjActualF($memberID,$budgetlistIdArr[$j],1000);
			}
		}
		$budgetQuarter = date('Y-m-d', strtotime($budgetQuarter.' + 3 months'));
		$monStrQuarter = date('Y-m-1', strtotime($budgetQuarter));
		$monEndQuarter = date('Y-m-t', strtotime($budgetQuarter.' + 2 months'));
		$qrt4BudTot = array();
		for ($j = 0; $j < $budgetlistIdArrCt; $j++){
			$qrt4BudTot[$j] = $budgetTotInst->getBudActualF($memberID,$budgetlistIdArr[$j],$monStrQuarter,$monEndQuarter);
			if ($monStrQuarter <= $budgetlistDateArr[$j] and $budgetlistDateArr[$j] <= $monEndQuarter){
				$qrt4BudTot[$j] = $qrt4BudTot[$j] + $budgetTotInst->getProjActualF($memberID,$budgetlistIdArr[$j],1000);
			}
		}
				
		///////////////////////////////////////////////////////////////////////////////		
		$quarterExpTot1 = 0;
		$quarterExpTot2 = 0;
		$quarterExpTot3 = 0;
		$quarterExpTot4 = 0;
		for ($i = 0; $i < $budgetlistIdArrCt; $i++){
			$myQuarterSavExpList = $myQuarterSavExpList.'<tr><td><div class="rowIndent"><a href="'.$mx002pb.'&bid='.$budgetID.'&mo='.$currentMon.$actYear.'&smid='.$_GET['smid'].'">'.$budgetlistNameArr[$i].'</a></div></td><td>'.$budgetTotInst->convertDollarF(abs($qrt1BudTot[$i])).'</td><td>'.$budgetTotInst->convertDollarF(abs($qrt2BudTot[$i]))."</td><td>".$budgetTotInst->convertDollarF(abs($qrt3BudTot[$i]))."</td><td>".$budgetTotInst->convertDollarF(abs($qrt4BudTot[$i])).'</td><td><span class="pull-right">'.$budgetTotInst->convertDollarF(abs($qrt1BudTot[$i]+$qrt2BudTot[$i]+$qrt3BudTot[$i]+$qrt4BudTot[$i]))."</span></td></tr>";
			$quarterExpTot1 = $quarterExpTot1 - abs($qrt1BudTot[$i]);
			$quarterExpTot2 = $quarterExpTot2 - abs($qrt2BudTot[$i]);
			$quarterExpTot3 = $quarterExpTot3 - abs($qrt3BudTot[$i]);
			$quarterExpTot4 = $quarterExpTot4 - abs($qrt4BudTot[$i]);
		}
		$myQuarterSavExpListTot = "<tr><th>";
		if ($myQuarterSavExpList == ""){
		}
		$myQuarterSavExpListTot = $myQuarterSavExpListTot."</th><th>".$budgetTotInst->convertDollarF(abs($quarterExpTot1))."</th><th>".$budgetTotInst->convertDollarF(abs($quarterExpTot2))."</th><th>".$budgetTotInst->convertDollarF(abs($quarterExpTot3))."</th><th>".$budgetTotInst->convertDollarF(abs($quarterExpTot4)).'</th><th><span class="pull-right">'.$budgetTotInst->convertDollarF(abs($quarterExpTot1+$quarterExpTot2+$quarterExpTot3+$quarterExpTot4))."</span></th></tr>";

////////////////////////////////////////////////////////////////////////////////////////////		
		//calculate annual budget for quarterly 
		//************************************************************
		//Get annual budgets - operating incomes/funds
		//************************************************************
		$qrtBudgetIncTot = array(); //each month total
		$qrtBudgetTotList = "";
		$qrtBudgetTotListTemp = "";
		$qrtBudgetTot = array();
		
		try{
			$result = $db->prepare("SELECT budget_id,budget_type_id,name,list_order,amount,startdate FROM $db_budgetlist WHERE member_id=? AND budget_id=? AND YEAR(startdate)=? AND active=? ORDER BY list_order ASC");
			$result->execute(array($memberID,$budgetID,$currentYr,$yes));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; script>";
		}
		$ctItems = $result->rowCount();
		$ctItemsCt = 0;
		$foundExpFlag = "no";
		$currentQrtBudgetFoundSave = 0;
		$qrt1Tot = 0;
		$qrt2Tot = 0;
		$qrt3Tot = 0;
		$qrt4Tot = 0;
		$qrtBudgetGrandTot = 0;
		if ($ctItems > 0){
			$quarterCt = 1;
			foreach ($result as $row){
				$orgDate = $row['startdate'];
				$partsArr = explode("-",$orgDate);
				$tempBudMon = $partsArr[1];
				$tempBudYrs = $partsArr[0];
				
				$qrtBudgetTotListTemp = "";
				$foundExpFlag = "no";
				$currentExpFound = 0;
				$currentQrtBudgetFoundSave = 0;
				
				$qrtBudgetTotListTemp = $qrtBudgetTotListTemp.'<tr><td nowrap><div class="rowIndent"><a href="'.$mx002pb.'&bid='.$row['budget_id'].'&mo='.$tempBudMon.$tempBudYrs.'&smid=">'.str_replace('\\','',$row['name'])."</a></div></td>";
				if (0<=$amtStartMonthArr[$ctItemsCt] and $amtStartMonthArr[$ctItemsCt]<=2){
					$qrtBudgetTotListTemp = $qrtBudgetTotListTemp.'<td>'.$budgetTotInst->convertDollarF($row['amount']).'</td>';
					$qrt1Tot = $qrt1Tot + $row['amount'];
					if ($quarterExpTot1 > 0){
						$currentExpFound = $row['amount'] - $quarterExpTot1;
					}else{
						$currentExpFound = $row['amount'];
					}
					$currentQrtBudgetFoundSave =  $currentQrtBudgetFoundSave + $row['amount'];
					$foundExpFlag = "yes";
				}else{
					$qrt1Tot = $qrt1Tot;
					$qrtBudgetTotListTemp = $qrtBudgetTotListTemp."<td>$0.00</td>";
				}
				if (3<=$amtStartMonthArr[$ctItemsCt] and $amtStartMonthArr[$ctItemsCt]<=5){
					$qrtBudgetTotListTemp = $qrtBudgetTotListTemp.'<td>'.$budgetTotInst->convertDollarF($row['amount']).'</td>';
					$qrt2Tot = $qrt2Tot + $row['amount'];
					if ($quarterExpTot2 > 0){
						$currentExpFound = $row['amount'] - $quarterExpTot2;
					}else{
						$currentExpFound = $row['amount'];
					}
					$currentQrtBudgetFoundSave =  $currentQrtBudgetFoundSave + $row['amount'];
					$foundExpFlag = "yes";
				}else{
					$qrt2Tot = $qrt2Tot;
					$qrtBudgetTotListTemp = $qrtBudgetTotListTemp."<td>$0.00</td>";
				}
				if (6<=$amtStartMonthArr[$ctItemsCt] and $amtStartMonthArr[$ctItemsCt]<=8){
					$qrtBudgetTotListTemp = $qrtBudgetTotListTemp.'<td>'.$budgetTotInst->convertDollarF($row['amount']).'</td>';
					$qrt3Tot = $qrt3Tot + $row['amount'];
					if ($quarterExpTot3 > 0){
						$currentExpFound = $row['amount'] - $quarterExpTot3;
					}else{
						$currentExpFound = $row['amount'];
					}
					$currentQrtBudgetFoundSave =  $currentQrtBudgetFoundSave + $row['amount'];
					$foundExpFlag = "yes";
					
				}else{
					$qrt3Tot = $qrt3Tot;
					$qrtBudgetTotListTemp = $qrtBudgetTotListTemp."<td>$0.00</td>";
				}
				if (9<=$amtStartMonthArr[$ctItemsCt] and $amtStartMonthArr[$ctItemsCt]<=11){
					$qrtBudgetTotListTemp = $qrtBudgetTotListTemp.'<td>'.$budgetTotInst->convertDollarF($row['amount']).'</td>';
					$qrt4Tot = $qrt4Tot + $row['amount'];
					$currentQrtBudgetFoundSave =  $currentQrtBudgetFoundSave + $row['amount'];
					$foundExpFlag = "yes";
					
				}else{
					$qrt4Tot = $qrt4Tot;
					$qrtBudgetTotListTemp = $qrtBudgetTotListTemp."<td>$0.00</td>";
				}
				$ctItemsCt++;
				
				if ($foundExpFlag == "yes"){ //only display annual budget if has at least one value.
					$qrtBudgetTotList = $qrtBudgetTotList.$qrtBudgetTotListTemp.'<td><span class="pull-right">'.$budgetTotInst->convertDollarF($currentQrtBudgetFoundSave)."</span></td></tr>";
					$qrtBudgetGrandTot = $qrtBudgetGrandTot + $currentQrtBudgetFoundSave; //grand total of operating budget/funds
				}else{
					$qrtBudgetTotList = $qrtBudgetTotList.$qrtBudgetTotListTemp."<td></td></tr>";
				}
			}//end foreach
			
		}//end of if record found
		//$quarterExpTot1
		
		if (($ctItems==1 and $qrtBudgetTotListTemp=="")or($ctItems==0)){
			$qrtBudgetTotList = "";//
		}
		$quarterTot1Net = 0;
		$quarterTot2Net = 0;
		$quarterTot3Net = 0;
		$quarterTot4Net = 0;
		$grandTotNet = 0;
		
		$myQuarterAnnIncListTot = "<tr><th>";	
			$quarterTot1Net = $qrt1Tot + $quarterExpTot1;
			$quarterTot2Net = ($quarterTot1Net + $qrt2Tot) + $quarterExpTot2;
			$quarterTot3Net = ($quarterTot2Net + $qrt3Tot) + $quarterExpTot3;
			$quarterTot4Net = ($quarterTot3Net + $qrt4Tot) + $quarterExpTot4;
			$grandTotNet = $qrtBudgetGrandTot+($quarterExpTot1+$quarterExpTot2+$quarterExpTot3+$quarterExpTot4);
		$myQuarterAnnIncListTot = $myQuarterAnnIncListTot."</th><th>".$budgetTotInst->convertDollarF($qrt1Tot)."</th><th>".$budgetTotInst->convertDollarF($qrt2Tot)."</th><th>".$budgetTotInst->convertDollarF($qrt3Tot)."</th><th>".$budgetTotInst->convertDollarF($qrt4Tot).'</th><th><span class="pull-right">'.$budgetTotInst->convertDollarF($qrtBudgetGrandTot)."</span></th></tr>";
		
		$myQuarterAllTotNet = "<tr><th>Running Variance</th><th>".$budgetTotInst->convertDollarF($quarterTot1Net)."</th><th>".$budgetTotInst->convertDollarF($quarterTot2Net)."</th><th>".$budgetTotInst->convertDollarF($quarterTot3Net)."</th><th>".$budgetTotInst->convertDollarF($quarterTot4Net).'</th><th><span class="pull-right">'.$budgetTotInst->convertDollarF($grandTotNet)."</span></th></tr>";	
		
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
                                      <th nowrap="nowrap">Operating Income</th>
                                      <th>'.$q1.'</th>
                                      <th>'.$q2.'</th>
                                      <th>'.$q3.'</th>
                                      <th>'.$q4.'</th>
                                      <th class="pull-right">Total</th>
                                    </tr>
                                    '.$qrtBudgetTotList.'
                                    '.$myQuarterAnnIncListTot.'
                                    <tr>
                                      <th>Expenses</th>
                                      <th></th>
                                      <th></th>
                                      <th></th>
                                      <th></th>
                                      <th></th>
                                    </tr>   
                                    '.$myQuarterSavExpList.'   
                                    '.$myQuarterSavExpListTot.'   
                                    <tr>
                                      <th colspan="6"></th>
                                    </tr>
                                    '.$myQuarterAllTotNet.'                                                      
                                  </table>
		</body></html>';
		$datarpt = $datarpt."'; ?>";
		
		$myQuarterlyFile = 'm_reports/bud001qview_r'.$memberID.'.php';
		$fh = fopen($myQuarterlyFile, 'w') or die("can't open file");
		fwrite($fh, "");
		fwrite($fh, $datarpt);
		fclose($fh);

	}else{  //if statement - not login
		print "<script> self.location='".$index_url."'; </script>";
	}
?>