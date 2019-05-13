<?php
	if ($_SESSION['login']=="yes" and $_SESSION['log_ss'] != ""){
		$memberID = $_SESSION['mid'];
		
		$budgetTotInst = new budgetInfoC();
		$accountInfo = new accountInfoC();
		$memberTotInst = new memberInfoC();
		$projectInfo = new projectInfoC();
		$generalInfo = new generalInfoC();
		
		$budgetID = $_GET['bid'];
		$month = $_GET['mo'];
		if (strlen($month)>5){
			$selectedMonth = substr($month,0,2);
			$selectedYear = substr($month,2,6);
		}
		else{
			$selectedMonth = substr($month,0,1);
			$selectedYear = substr($month,1,5);
		}
		
		$todayDate = date("Y-m-d");
		$actSelectedMonth = date("F", strtotime($selectedMonth."/1/".$selectedYear));
		$budgetMonth = date($selectedMonth."/15/".$selectedYear);
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

		$myBudgetOverview = $currentYr." Budget Overview";
		$shareBudgetOverview = "Shared Budget - ".$currentMon;
		$yes = "yes";
		$myBudgetList = "";
		$totMyBudgetCount = 0;
		//$budgetMonth = date("m/d/Y");
					
		$budgetNameMemberID = array();			
		$totBudgetItems = array();
		$budgetIncomeTot = 0;
		$budgetSavingTot = 0;
		$budgetExpenseTot = 0;
		
		$incomeRows = "";
		$savingRows = "";
		$expenseRows = "";
		$incomeRowsCat = "";
		$savingRowsCat = "";
		$expenseRowsCat = "";
		
		$budgetNameMemberID = $budgetTotInst->budgetNameF($budgetID);
		$budgetAccessRight = $budgetTotInst->budgetRightF($memberID,$budgetID);

		if ($_GET['smid'] != ""){
			$myBudgetOverview = $actSelectedMonth." ".$selectedYear." Budget Worksheet";
			$memberID = $_GET['smid'];
			$sharedMemberID = $_GET['smid'];
		}else{
			$myBudgetOverview = $actSelectedMonth." ".$selectedYear. " Budget Worksheet";
		}
		
////////////////////////////////////////////////////////////////////////				
		//get category data
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
		
		//get account data
		$acctItemsList = "";
		$acctNumAll = "";
		$acctNumAllCt = 0;
		try{
			$result = $db->prepare("SELECT account_id,name FROM $db_account WHERE member_id=? AND active=? ORDER BY name ASC");
			$result->execute(array($memberID,$yes));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; script>";
		}
		$acctItems = $result->rowCount(); 
		if ($acctItems > 0){
			foreach ($result as $row) {
				$acctNumAll = $acctNumAll.$row['account_id'];
				$acctNumAllCt++;
			}
			$result->execute(array($memberID,$yes));
			foreach ($result as $row) {
				$acctItemsList = $acctItemsList."<label class='checkbox-inline'><input type='checkbox' id='at".$row['account_id']."' value='".$row['account_id']."' onclick=turn_credit_debit(".$row['account_id'].",".$acctItems.",'".$acctNumAll."')>".str_replace('\\','',$row['name'])."</label><div class='radio'><input type='text' size='4' disabled='true' class='smtextbox_div' id='da".$row['account_id']."' placeholder='$0.00' onchange='isNumber_amtina(this.id,this.value)'>&nbsp;<label><input type='radio' disabled='true' name='cd".$row['account_id']."' id='d".$row['account_id']."' value='1001'>Debit</label>&nbsp;&nbsp;<label><input type='radio' disabled='true' name='cd".$row['account_id']."' id='c".$row['account_id']."' value='1000'>Credit</label></div>";
			}
		}
		if ($acctNumAllCt == 0){
			$acctItemsList = "No accounts avaiable";
		}
		
///////////////////////////////////////////////////////////////////////////////////
		$spbudgetAnnList = "";
		$spbudgetAnnTot = 0;
		$spbudgetAnnTotAct = 0;
		$spbudgetAnnTotNet = 0;
		$spbudgetIncList = "";
		$spbudgetIncTot = 0;
		$spbudgetIncTotAct = 0;
		$spbudgetIncTotNet = 0;
		$spbudgetExpList = "";
		$spbudgetExpTot = 0;
		$spbudgetExpTotAct = 0;
		$spbudgetExpTotNet = 0;
		$spbudgetListAdd = "";
		$spbudgetNameOwner = array();
		
		$budgetMonthBeg  = date('Y-m-d', strtotime($selectedYear."-".$selectedMonth."-1")); //begin of selected month
		$budgetMonthEnd  = date('Y-m-t', strtotime($selectedYear."-".$selectedMonth."-15")); //ended of selected month
		$budgetMonthStart = date('Y-m-d', strtotime($yearFY."-".$monthFY."-1")); //started fiscal 12 month
		if ($budgetMonthStart <= $budgetMonthEnd){
			$countFY = $budgetTotInst->diffMonthF($budgetMonthStart, $budgetMonthEnd);
			$yearFY = $yearFY + intval(($countFY) / 12);
		}else{
			$countFY = $budgetTotInst->diffMonthF($budgetMonthEnd,$budgetMonthStart);
			$yearFY = $yearFY - intval(($countFY+12) / 12);
		}
		
		$budgetMonthStart = date('Y-m-d', strtotime($yearFY."-".$monthFY."-1")); //started fiscal 12 month
		$budgetMonthEndFY = date('Y-m-t', strtotime($budgetMonthStart. ' + 11 months')); //ended fiscal 12 month
		try{//create budget list				
			$result = $db->prepare("SELECT budgetlist_id,budget_id,budget_type_id,name,list_order,amount,startdate,enddate,ann_mon,description,active FROM $db_budgetlist WHERE member_id=? AND budget_id=? AND (startdate>=? AND startdate<=?) AND (enddate>=? AND enddate<=?) AND active=? ORDER BY startdate ASC");
			$result->execute(array($memberID,$budgetID,$budgetMonthStart,$budgetMonthEnd,$budgetMonthBeg,$budgetMonthEndFY,$yes));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		$itemCount = $result->rowCount();
		$mainRunningTot = 0;
		if ($itemCount > 0){
			foreach ($result as $row){
				$orgDate = $row['startdate'];
				$partsArr = explode("-",$orgDate);
				$budgetDate = $partsArr[1]."/".$partsArr[2]."/".$partsArr[0];
				$lastDayMon  = date('m-t-Y', strtotime($orgDate));
				$budgetlistID = $row['budgetlist_id'];
				
				if ($row['budget_type_id']=="1000"){
					$lastDaySelectDate  = date('Y-m-t', strtotime($selectedYear."-".$selectedMonth."-15"));
					$runningTot = $budgetTotInst->getBudActualF($memberID,$budgetlistID,$orgDate,$lastDaySelectDate);
					$runningTot = $runningTot + $budgetTotInst->getProjActualF($memberID,$budgetlistID,1000);
					$runningTot = abs($runningTot);
					
					$netTot = 0;
					$netTot = $row['amount'] - $runningTot;
					
					if ($budgetAccessRight == 2 or $budgetAccessRight == 3){
						$spbudgetIncList = $spbudgetIncList."<tr><td>".$budgetDate."</td><td><a href='#' onclick='uptSpcBudgetInfo(".$row['budgetlist_id'].")'>".str_replace('\\','',$row['name'])."</a></td>";
					}else{
						$spbudgetIncList = $spbudgetIncList."<tr><td>".$budgetDate."</td><td>".str_replace('\\','',$row['name'])."</td>";
					}
					$spbudgetIncList = $spbudgetIncList."<td>".$budgetTotInst->convertDollarF($row['amount'])."</td>";
					$spbudgetIncList = $spbudgetIncList."<td>".$budgetTotInst->convertDollarF($runningTot)."</td><td>".$budgetTotInst->convertDollarF($netTot)."</td>";
					$spbudgetNameOwner = $budgetTotInst->budgetNameF($row['budget_id']);
					if ($budgetAccessRight == 3){
						$spbudgetIncList = $spbudgetIncList."<td nowrap><a href='#' onclick='delBudgetInfo(".$row['budgetlist_id'].")'><span class='label label-danger'>x</span></a></td></tr>";
					}else{
						$spbudgetIncList = $spbudgetIncList."<td nowrap></td></tr>";
					}
					$spbudgetIncTot = $spbudgetIncTot + $row['amount'];
					$spbudgetIncTotAct = $spbudgetIncTotAct + $runningTot;
					$spbudgetIncTotNet = $spbudgetIncTotNet + $netTot;
				}
				if ($row['budget_type_id']=="1002"){
					$lastDaySelectDate  = date('Y-m-t', strtotime($selectedYear."-".$selectedMonth."-15"));
					$runningTot = $budgetTotInst->getBudActualF($memberID,$budgetlistID,$orgDate,$lastDaySelectDate);
					$runningTot = $runningTot + $budgetTotInst->getProjActualF($memberID,$budgetlistID,1000);
					$runningTot = abs($runningTot);
					if ($budgetAccessRight == 2 or $budgetAccessRight == 3){
						$spbudgetExpList = $spbudgetExpList."<tr><td>".$budgetDate."</td><td><a href='#' onclick='uptSpcBudgetInfo(".$row['budgetlist_id'].")'>".str_replace('\\','',$row['name'])."</a></td>";
					}else{
						$spbudgetExpList = $spbudgetExpList."<tr><td>".$budgetDate."</td><td>".str_replace('\\','',$row['name'])."</td>";
					}
					$spbudgetExpList = $spbudgetExpList."<td>".$budgetTotInst->convertDollarF($row['amount'])."</td><td>".$budgetTotInst->convertDollarF($runningTot)."</td><td>".$budgetTotInst->convertDollarF($row['amount'] - $runningTot)."</td>";
					$spbudgetNameOwner = $budgetTotInst->budgetNameF($row['budget_id']);
					if ($budgetAccessRight == 3){
						$spbudgetExpList = $spbudgetExpList."<td nowrap><a href='#' onclick='delBudgetInfo(".$row['budgetlist_id'].")'><span class='label label-danger'>x</span></a></td></tr>";
					}else{
						$spbudgetExpList = $spbudgetExpList."<td nowrap></td></tr>";
					}
					$spbudgetExpTot = $spbudgetExpTot + $row['amount'];
					$spbudgetExpTotAct = $spbudgetExpTotAct + $runningTot;
					$spbudgetExpTotNet = $spbudgetExpTotNet + ($row['amount'] - $runningTot);
				}
			}//end foreach
		}//end if record found
		
		if ($spbudgetIncList != ""){
			$spbudgetIncList = $spbudgetIncList."<tr><th colspan=2><a href='#' onclick='addSpcBud(".$memberID.",".$budgetID.",2)'><button class='btn btn-xs btn-success'>Add Income</button></a></th><th>".$budgetTotInst->convertDollarF($spbudgetIncTot)."</th><th>".$budgetTotInst->convertDollarF($spbudgetIncTotAct)."</th><th>".$budgetTotInst->convertDollarF($spbudgetIncTotNet)."</th><th></th></tr>";
			$spbudgetIncList = $spbudgetIncList."<tr><td colspan=6></td></tr>";
		}else{
			$spbudgetIncList = $spbudgetIncList."<tr><td colspan=2><a href='#' onclick='addSpcBud(".$memberID.",".$budgetID.",2)'><button class='btn btn-xs btn-success'>Add Income</button></a></td><td colspan=4>No income/revenue</td></tr>";
		}
		if ($spbudgetExpList != ""){
			$spbudgetExpList = $spbudgetExpList."<tr><th colspan=2><a href='#' onclick='addSpcBud(".$memberID.",".$budgetID.",3)'><button class='btn btn-xs btn-success'>Add Expense&nbsp;&nbsp;</button></a></th><th>".$budgetTotInst->convertDollarF($spbudgetExpTot)."</th><th>".$budgetTotInst->convertDollarF($spbudgetExpTotAct)."</th><th>".$budgetTotInst->convertDollarF($spbudgetExpTotNet)."</th><th></th></tr>";
			$spbudgetExpList = $spbudgetExpList."<tr><td colspan=6></td></tr>";
		}else{
			$spbudgetExpList = $spbudgetExpList."<tr><td colspan=2><a href='#' onclick='addSpcBud(".$memberID.",".$budgetID.",3)'><button class='btn btn-xs btn-success'>Add Expense&nbsp;&nbsp;</button></a></td><td colspan=4>No expenses</td></tr>";
		}
		if ($spbudgetExpList != ""){
			$netPlanTot = 0;
			$netPlanTot = $spbudgetIncTot-$spbudgetExpTot;
			$netActTot = 0;
			$netActTot = $spbudgetIncTotAct-$spbudgetExpTotAct;
			$netNetTot = 0;
			$netNetTot = $spbudgetIncTotNet-$spbudgetExpTotNet; //don't need to display total variance
			$spbudgetExpList = $spbudgetExpList."<tr><th colspan=2>Total Variance</th><th>".$budgetTotInst->convertDollarF($netPlanTot)."</th><th>".$budgetTotInst->convertDollarF($netActTot)."</th><th></th><th></th></tr>";
		}else{
			$spbudgetExpList = $spbudgetExpList."<tr><td colspan=6></td></tr>";
			$spbudgetExpList = $spbudgetExpList."<tr><td colspan=2></td><td>$0.00</td><td>$0.00</td><td>$0.00</td></tr>";
		}
	
///////////////////////////////////////////////////////////////////////////////////			
	}else{  //if statement - not login
		print "<script> self.location='".$index_url."'; </script>";
	}
?>