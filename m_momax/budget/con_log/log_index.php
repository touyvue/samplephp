<?php
	if ($_SESSION['login']=="yes" and $_SESSION['log_ss'] != ""){
		$memberID = $_SESSION['mid'];
		$budgetTotInst = new budgetInfoC();
		$accountInfo = new accountInfoC();
		$memberTotInst = new memberInfoC();
		$projectInfo = new projectInfoC();
		$generalInfo = new generalInfoC();
		$yes = "yes";
		$no = "no";
		
		if ($_GET['cy']==""){
			$currentYr = date(Y);
			$currentMon = date(F);	
			$budgetMonth = date(m);
		}else{
			$currentYr = $_GET['cy'];
			$budgetMonth = date(m);
		}
		
		try{ //get member's consortiumID and licenseID			
			$result = $db->prepare("SELECT $db_member.consortium_id,$db_consortium.consortium,$db_consortium.license_id,$db_consortium.admin1,$db_consortium.admin2 FROM $db_member,$db_consortium WHERE $db_member.member_id=? AND $db_member.consortium_id=$db_consortium.consortium_id AND $db_member.active=?");
			$result->execute(array($memberID,$yes));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		foreach ($result as $row){
			$consortiumID = $row['consortium_id'];
			$licenseID =  $row['license_id'];
		}
				
		//Get mybudget list
		$myBudgetOverview = "Budgets (".$currentMon." ".$currentYr.")";
		$shareBudgetOverview = "Shared Budgets (".$currentMon." ".$currentYr.")";
		
////////////////////////////////////////////////////////////////////////////////////
		$budgetName = array();
		$myBudgetID = array();		
		$myBudgetList = "";
		$myBudgetListWk = "";
		$totMyBudgetCount = 0;
		//$budgetMonth = date('m', date("m/d/Y"));
		
///////////////////////////////////////////////////////////////////////////////////
		$todayDate = date("Y-m-d");
		$selectedMonth = date("m", strtotime($todayDate));
		$selectedYear = date("Y", strtotime($todayDate));
		
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
		//////////////////////////////////////////////////////

		$spbudgetIncList = "";
		$spbudgetExpList = "";
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
			$result = $db->prepare("SELECT budget_id, name FROM $db_budget WHERE member_id=? AND active=? ORDER BY list_order ASC");
			$result->execute(array($memberID,$yes));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		$spbudgetNameOwner = array();
		$budgetIdFoundArr = array();
		$budgetNameFoundArr = array();
		$budgetIdArrCt = 0;
		foreach ($result as $row){
			$budgetIdFoundArr[$budgetIdArrCt] = $row['budget_id'];
			$spbudgetNameOwner = $budgetTotInst->budgetNameF($row['budget_id']);
			$budgetNameFoundArr[$budgetIdArrCt] = $spbudgetNameOwner[0];
			$budgetIdArrCt++;
		}
		
		$strThisWeek = date('Y-m-d', mktime(1, 0, 0, date('m'), date('d')-date('w'), date('Y')));
		$endThisWeek = date('Y-m-d', strtotime($strThisWeek. ' + 6 days'));
		
		for ($i = 0; $i < $budgetIdArrCt; $i++){
			try{//				
				$result = $db->prepare("SELECT budgetlist_id,budget_id,budget_type_id,name,list_order,amount,startdate,enddate,ann_mon,description,active FROM $db_budgetlist WHERE member_id=? AND budget_id=? AND active=? ORDER BY startdate ASC");
				$result->execute(array($memberID,$budgetIdFoundArr[$i],$yes));
			} catch(PDOException $e) {
				print "<script> self.location='".$index_url."?err=d1000'; </script>";
			}
			$itemCount = $result->rowCount();
			
			$spbudgetIncTotPla = 0;
			$spbudgetIncTotAct = 0;
			$spbudgetExpTotPla = 0;
			$spbudgetExpTotAct = 0;
			$runningTot = 0;
			$spbudgetIncTotPlaWk = 0;
			$spbudgetIncTotActWk = 0;
			$spbudgetExpTotPlaWk = 0;
			$spbudgetExpTotActWk = 0;
			$runningTotWk = 0;
			
			if ($itemCount > 0){
				foreach ($result as $row){
					$orgDate = $row['startdate'];
					$partsArr = explode("-",$orgDate);
					$budgetDate = $partsArr[1]."/".$partsArr[2]."/".$partsArr[0];
					$orgDate = $row['enddate'];
					$endpartsArr = explode("-",$orgDate);
					$budgetlistYear = $endpartsArr[0];
					$budgetlistMonth = $endpartsArr[1];
					$lastDayMon  = date('m-t-Y', strtotime($orgDate));
					$budgetlistID = $row['budgetlist_id'];
					$budgerTypeID = $row['budget_type_id'];
					
					$lastDaySelectDate  = date('Y-m-t', strtotime($selectedYear."-".$selectedMonth."-15"));
					$runningTot = $budgetTotInst->getBudActualF($memberID,$budgetlistID,$orgDate,$lastDaySelectDate);
					$runningTot = $runningTot + $budgetTotInst->getProjActualF($memberID,$budgetlistID,1000);
					$spbudgetExpTotAct = $spbudgetExpTotAct + abs($runningTot);
					$spbudgetExpTotPla = $spbudgetExpTotPla + $row['amount'];
					
					//look at as of this week
					//$runningTotWk = $budgetTotInst->getBudActualF($memberID,$budgetlistID,$orgDate,$endThisWeek);
					//$spbudgetExpTotActWk = $spbudgetExpTotActWk + $runningTotWk;
					//$spbudgetExpTotPlaWk = $spbudgetExpTotPlaWk + $row['amount'];
				}//end of foreach loop
			}//end of if at least 1 or more record
			
			if ($budgetlistYear < $selectedYear){ //take budget to the end of its period
				$viewMonYr = $budgetlistMonth.$budgetlistYear;
			}else{
				$viewMonYr = $selectedMonth.$selectedYear;
			} 
			
			if ($spbudgetExpTotPla > 0){
				$netTot = 0;
				$netTot = $spbudgetExpTotPla - abs($spbudgetExpTotAct);
				$spbudgetExpList = $spbudgetExpList."<tr><td><a href='".$mx002pb."&bid=".$budgetIdFoundArr[$i]."&mo=".$viewMonYr."&smid='>".$budgetNameFoundArr[$i]."</a></td>";
				$spbudgetExpList = $spbudgetExpList."<td>".$budgetTotInst->convertDollarF($spbudgetExpTotPla)."</td><td>".$budgetTotInst->convertDollarF(abs($spbudgetExpTotAct))."</td><td>".$budgetTotInst->convertDollarF($netTot)."</td><td><a href='".$mx002mb."&bid=".$budgetIdFoundArr[$i]."&mo=".$selectedMonth.$selectedYear."&smid='>View</a></td></tr>";
			}else{
				$spbudgetExpList = $spbudgetExpList."<tr><td><a href='".$mx002pb."&bid=".$budgetIdFoundArr[$i]."&mo=".$viewMonYr."&smid='>".$budgetNameFoundArr[$i]."</a></td><td>$0.00</td><td>$0.00</td><td>$0.00</td><td></td></tr>";
			}
		}//end of for loop
		
		if ($spbudgetExpList == ""){
			try{					
				$result = $db->prepare("SELECT budget_id, name FROM $db_budget WHERE member_id=? AND active=? ORDER BY list_order ASC");
				$result->execute(array($memberID,$yes));
			} catch(PDOException $e) {
				print "<script> self.location='".$index_url."?err=d1000'; </script>";
			}
			$totMyBudgetCount = $result->rowCount();
			if ($totMyBudgetCount > 0){
				foreach ($result as $row) {
					$spbudgetExpList = $spbudgetExpList."<tr><td><a href='".$mx002pb."&bid=".$row['budget_id']."&mo=".$selectedMonth.$selectedYear."&smid='>".str_replace('\\','',$row['name'])."</a></td><td>$0.00</td><td>$0.00</td><td>$0.00</td></tr>";
				}
			}else{
				$spbudgetExpList = $spbudgetExpList."<tr><td>No budgets</td><td colspan=3>Create <a href=".$mx005bs.">New Budget</a></td></tr>";
			}
		}
		
		//////////////////////////////////////////////////////////////////////
		//get shared budget list - array $shrBudgetIdArr and $shrBudgetOwnerIdArr created above
		$shrbudgetIncList = "";
		$itemSharedBudCount = 0;
		for ($i = 0; $i < $shrBudgetIdArrCt; $i++){
			try{//				
				$result = $db->prepare("SELECT budgetlist_id,budget_id,budget_type_id,name,list_order,amount,startdate,enddate,ann_mon,description,active FROM $db_budgetlist WHERE member_id=? AND budget_id=? AND (startdate>=? AND startdate<=?) AND (enddate>=? AND enddate<=?) AND active=? ORDER BY startdate ASC");
				$result->execute(array($shrBudgetOwnerIdArr[$i],$shrBudgetIdArr[$i],$budgetMonthStart,$budgetMonthEnd,$budgetMonthBeg,$budgetMonthEndFY,$yes));
			} catch(PDOException $e) {
				print "<script> self.location='".$index_url."?err=d1000'; </script>";
			}
			$itemSharedBudCount = $result->rowCount();
			
			$shrbudgetIncTotPla = 0;
			$shrbudgetIncTotAct = 0;
			$shrbudgetExpTotPla = 0;
			$shrbudgetExpTotAct = 0;
			$shrrunningTot = 0;
			$shrbudgetIncTotPlaWk = 0;
			$shrbudgetIncTotActWk = 0;
			$shrbudgetExpTotPlaWk = 0;
			$shrbudgetExpTotActWk = 0;
			$shrrunningTotWk = 0;
			
			if ($itemSharedBudCount > 0){
				foreach ($result as $row){
					$orgDate = $row['startdate'];
					$partsArr = explode("-",$orgDate);
					$budgetDate = $partsArr[1]."/".$partsArr[2]."/".$partsArr[0];
					$lastDayMon  = date('m-t-Y', strtotime($orgDate));
					$budgetlistID = $row['budgetlist_id'];
					$budgerTypeID = $row['budget_type_id'];
					
					$lastDaySelectDate  = date('Y-m-t', strtotime($selectedYear."-".$selectedMonth."-15"));
					$shrrunningTot = $budgetTotInst->getBudActualF($memberID,$budgetlistID,$orgDate,$lastDaySelectDate);
					$shrrunningTot = $shrrunningTot + $budgetTotInst->getProjActualF($memberID,$budgetlistID,1000);
					$shrbudgetExpTotAct = $shrbudgetExpTotAct + $shrrunningTot;
					$shrbudgetExpTotPla = $shrbudgetExpTotPla + $row['amount'];
					
				}//end of foreach loop
			}//end of if at least 1 or more record
			
			if ($shrbudgetExpTotPla > 0){
				$netTot = 0;
				$netTot = $shrbudgetExpTotPla - abs($shrbudgetExpTotAct);
				
				$shrbudgetExpList = $shrbudgetExpList."<tr><td>".$budgetTotInst->budgetOwnerNameF($shrBudgetIdArr[$i])."</td><td><a href='".$mx002pb."&bid=".$shrBudgetIdArr[$i]."&mo=".$selectedMonth.$selectedYear."&smid=".$shrBudgetOwnerIdArr[$i]."'>".$shrBudgetOwnerNameArr[$i]."</a></td>";
				$shrbudgetExpList = $shrbudgetExpList."<td>".$budgetTotInst->convertDollarF($shrbudgetExpTotPla)."</td><td>".$budgetTotInst->convertDollarF(abs($shrbudgetExpTotAct))."</td><td>".$budgetTotInst->convertDollarF($netTot)."</td><td><a href='".$mx002mb."&bid=".$shrBudgetIdArr[$i]."&mo=".$selectedMonth.$selectedYear."&smid=".$shrBudgetOwnerIdArr[$i]."'>View</a></td></tr>";
			}
		}//end of for loop
		if ($shrbudgetExpList == ""){
			$shrbudgetExpList = $shrbudgetExpList."<tr><td>No shared budgets</td><td>$0.00</td><td>$0.00</td><td>$0.00</td></tr>";
		}
	}else{  //if statement - not login
		print "<script> self.location='".$index_url."'; </script>";
	}
?>