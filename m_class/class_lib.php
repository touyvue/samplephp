<?php
////Begin budgetInfoC class //////////////////////////////////////////////////////
class budgetInfoC{
	//Begin eachBudgetTotF - get monthly and weekly totals per budgetTypeID//
	//budget/log_budgetmonth*php)budget/log_index*php)
	function eachBudgetTotF($budgetID,$member_id,$budgetMon,$currentYr){
		include('m_inc/def_value.php');
		include ('m_inc/m_config.php');
		$transArr = array();
		$transWeekArr = array();
		$yeVal = "yes";
		$transArr[0] = 0;
		$transArr[1] = 0;
		$transArr[2] = 0;
		$transWeekArr[0] = 0;
		$transWeekArr[1] = 0;
		$transWeekArr[2] = 0;
		$transItems = 0;
			
		$startWk = (date('D') != 'Sun') ? date('Y-m-d', strtotime('last Sunday')) : date('Y-m-d'); //start of the week
  		$finishWk = (date('D') != 'Sat') ? date('Y-m-d', strtotime('next Saturday')) : date('Y-m-d'); //end of the week
		
		try{//calculate current month
			$result = $db->prepare("SELECT amount,budget_type_id,transaction_date FROM $db_budtransaction WHERE budget_id=? AND member_id=? AND MONTH(transaction_date)=? AND YEAR(transaction_date)=? AND active=?");
			$result->execute(array($budgetID,$member_id,$budgetMon,$currentYr,$yeVal));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; script>";
		}
		$transItems = $result->rowCount();
		if ($transItems > 0){
			foreach ($result as $row) {
				if ($row['budget_type_id']=="1000"){
					$transArr[0] = $transArr[0] + $row['amount'];
				}
				if ($row['budget_type_id']=="1001"){
					$transArr[1] = $transArr[1] + $row['amount'];
				}
				if ($row['budget_type_id']=="1002"){
					$transArr[2] = $transArr[2] + $row['amount'];
				}
			}//end foreach loop
		}	
		
		$transItems = 0;
		try{///calculate current week
			$result = $db->prepare("SELECT amount,budget_type_id,transaction_date FROM $db_budtransaction WHERE budget_id=? AND member_id=? AND transaction_date>=? AND transaction_date<=? AND active=?");
			$result->execute(array($budgetID,$member_id,$startWk,$finishWk,$yeVal));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; script>";
		}
		$transItems = $result->rowCount();
		if ($transItems > 0){
			foreach ($result as $row) {
				if ($row['budget_type_id']=="1000"){
					$transWeekArr[0] = $transWeekArr[0] + $row['amount'];
				}
				if ($row['budget_type_id']=="1001"){
					$transWeekArr[1] = $transWeekArr[1] + $row['amount'];
				}
				if ($row['budget_type_id']=="1002"){
					$transWeekArr[2] = $transWeekArr[2] + $row['amount'];
				}
			}//end foreach loop
		}
		
		return array($transArr,$transWeekArr);
	}//end eachBudgetTotF//
	
	//Begin totByMonthlyCategoryF - total per categoryID list
	//budget/log_budgetmonth*php)
	function totByMonthlyCategoryF($categoryIncIDArr,$categoryExpIDArr,$budgetID,$memberID,$numMonth,$actMonth,$actYear){
		include('m_inc/def_value.php');
		include ('m_inc/m_config.php');
		
		$yes = "yes";
		$categoryCt = 0;
		$monthIncCatTot = array();
		$monthExpCatTot = array();
		$categoryIncCt = count($categoryIncIDArr);
		$categoryExpCt = count($categoryExpIDArr);
		$budTypeInc = "1000";
		$budTypeSav = "1001";
		$budTypeExp = "1002";
		
		for ($i = 0; $i < $categoryIncCt; $i++){
			try{	
				$result = $db->prepare("SELECT amount,budget_type_id FROM $db_budtransaction WHERE budget_id=? AND member_id=? AND category_id=? AND budget_type_id=? AND MONTH(transaction_date)=? AND YEAR(transaction_date)=? AND active=?");
				$result->execute(array($budgetID,$memberID,$categoryIncIDArr[$i],$budTypeInc,$actMonth,$actYear,$yes));
			} catch(PDOException $e) {
				print "<script> self.location='".$index_url."?err=d1000'; script>";
			}
			$catIncTotal = 0;
			foreach ($result as $row) {
				$catIncTotal = $catIncTotal + $row['amount'];
			}
			$monthIncCatTot[$i] = $catIncTotal;
		}	
		//get savings and expenses
		for ($i = 0; $i < $categoryExpCt; $i++){
			try{	
				$result = $db->prepare("SELECT amount,budget_type_id FROM $db_budtransaction WHERE budget_id=? AND member_id=? AND category_id=? AND (budget_type_id=? OR budget_type_id=?) AND MONTH(transaction_date)=? AND YEAR(transaction_date)=? AND active=?");
				$result->execute(array($budgetID,$memberID,$categoryExpIDArr[$i],$budTypeSav,$budTypeExp,$actMonth,$actYear,$yes));
			} catch(PDOException $e) {
				print "<script> self.location='".$index_url."?err=d1000'; script>";
			}
			$catExpTotal = 0;
			foreach ($result as $row) {
				$catExpTotal = $catExpTotal + $row['amount'];
			}
			$monthExpCatTot[$i] = $catExpTotal;
		}	
		
		return array($monthIncCatTot,$monthExpCatTot);
	}//end totByMonthlyCategoryF
		
	//Begin budgetTotByCategoryF - total per categoryID list
	//budget/log_budgetmonth*php)
	function budgetTotByCategoryF($categoryIDArr,$budgetID,$budgetTypeID,$memberID,$quarterCt,$actMonth,$actYear){
		include('m_inc/def_value.php');
		include ('m_inc/m_config.php');
		
		$yeVal = "yes";
		$categoryCt = 0;
		$budQuarter = array(); //first quarter total by category
		$budQuarterIDs = array(); //array of individual budtransactionID
		$budQuarterIDsCt = 0;
		
		$categoryCt = count($categoryIDArr);
		$budTypeInc = "1000";
		$budTypeSav = "1001";
		$budTypeExp = "1002";
		
		for ($i = 0; $i < 3; $i++){
			if ($i == 0){
				$budgetMonth = date('Y-m-d', strtotime($actYear."-".$actMonth."-15"));
			}
			$tempActMonth = date("m", strtotime($budgetMonth));
			$tempActYear = date("Y", strtotime($budgetMonth));
			try{
				if ($budgetTypeID == "1000"){
					$result = $db->prepare("SELECT budtransaction_id,amount,category_id FROM $db_budtransaction WHERE budget_id=? AND member_id=? AND MONTH(transaction_date)=? AND YEAR(transaction_date)=? AND budget_type_id=? AND active=?");
					$result->execute(array($budgetID,$memberID,$tempActMonth,$tempActYear,$budTypeInc,$yeVal));
				}
				if ($budgetTypeID == "1001"){
					$result = $db->prepare("SELECT budtransaction_id,amount,category_id FROM $db_budtransaction WHERE budget_id=? AND member_id=? AND MONTH(transaction_date)=? AND YEAR(transaction_date)=? AND (budget_type_id=? OR budget_type_id=?) AND active=?");
					$result->execute(array($budgetID,$memberID,$tempActMonth,$tempActYear,$budTypeSav,$budTypeExp,$yeVal));
				}
				
			} catch(PDOException $e) {
				print "<script> self.location='".$index_url."?err=d1000'; script>";
			}
			$transItems = $result->rowCount();
			if ($transItems > 0){
				foreach ($result as $row) {
					for ($j = 0; $j < $categoryCt; $j++){						
						if ($row['category_id'] == $categoryIDArr[$j]){
							if ($row['amount'] == 0 or $row['amount'] < 0){
								$budQuarter[$j] = $budQuarter[$j] + 0;
							}else {
								$budQuarter[$j] = $budQuarter[$j] + $row['amount'];
								if ($budQuarterIDsCt == 0){
									$currentCatId = $categoryIDArr[$j];
								}
								if ($currentCatId == $categoryIDArr[$j]){
									$budQuarterIDs[$j][$budQuarterIDsCt] = $row['budtransaction_id'];
									$budQuarterIDsCt++;
								}else{
									$budQuarterIDsCt = 0;
									$budQuarterIDs[$j][$budQuarterIDsCt] = $row['budtransaction_id'];
								}
							}
						}else{
							$budQuarter[$j] = $budQuarter[$j] + 0;
						}
					}//end for loop
				}//end foreach loop
			}
			$budgetMonth = date('Y-m-d', strtotime($budgetMonth. ' + 30 days'));
		}//end for loop
		return $budQuarter;
	}//end budgetTotByCategoryF
	
	//Return budget name
	//(budget/log_budgert)budget/log_budgetmonth)budget/log_index)setting/log_project)report/log_rpt001buda)
	function budgetNameF($budgetID){
		include('m_inc/def_value.php');
		include ('m_inc/m_config.php');
		$yeVal = "yes";
		try{
			$result = $db->prepare("SELECT name, member_id FROM $db_budget WHERE budget_id=? AND active=?");
			$result->execute(array($budgetID,$yeVal));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; script>";
		}
		$budgetNameMemberID = array();
		foreach ($result as $row) {
			$budgetNameMemberID[0] = str_replace('\\','',$row['name']);
			$budgetNameMemberID[1] = $row['member_id'];
		}
		return $budgetNameMemberID;
	}//end budgetNameF
	
	//Return budget owner's name
	//)budget/log_index))
	function budgetOwnerNameF($budgetID){
		include('m_inc/def_value.php');
		include ('m_inc/m_config.php');
		$yeVal = "yes";
		try{
			$result = $db->prepare("SELECT member_id FROM $db_budget WHERE budget_id=? AND active=?");
			$result->execute(array($budgetID,$yeVal));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; script>";
		}
		foreach ($result as $row) {
			$budgetOwnerMemberID = $row['member_id'];
		}
		try{
			$result = $db->prepare("SELECT first_name,last_name FROM $db_member WHERE member_id=?");
			$result->execute(array($budgetOwnerMemberID));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; script>";
		}
		foreach ($result as $row) {
			$firstName = $row['first_name'];
		}
		return $firstName;
	}//end budgetNameF
	
	//Begin budgetRightF - Return member budgetRight level 1-3
	//budget/log_budget)
	function budgetRightF($memberID,$budgetID){
		include('m_inc/def_value.php');
		include ('m_inc/m_config.php');
		$yeVal = "yes";
		try{
			$result = $db->prepare("SELECT access_rights FROM $db_budget_rights WHERE budget_id=? AND member_id=? AND active=?");
			$result->execute(array($budgetID,$memberID,$yeVal));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; script>";
		}
		$budgetAccessRight = 0;
		foreach ($result as $row) {
			$budgetAccessRight = $row['access_rights'];
		}
		return $budgetAccessRight;
	}//end budgetRightF
	
	//Begin retProjBudgetIDF - Return budtransactionID based on projectID
	//project/log_project)
	function retProjBudgetIDF($memberID,$projectID){
		include('m_inc/def_value.php');
		include ('m_inc/m_config.php');
		$budgetID = "0";
		
		try{//get Budget ID
			$result = $db->prepare("SELECT budget_id FROM $db_budtransaction WHERE member_id=? AND project_id=?");
			$result->execute(array($memberID,$projectID));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; script>";
		}
		foreach ($result as $row){
			$budgetID = $row['budget_id'];
		}
		return $budgetID;
	}//end retProjBudgetIDF
	
	//Begin insertBudTransF - Insert new data into budTransaction table 
	//account/addaccount)budget/addbudget)budget/uptproject.php)
	function insertBudTransF($accountID,$memberID,$transactionDate,$posted,$transactionOrder,$amount,$transactionTypeID,$categoryID,$description,$referenceNumber,$budgetID,$budgetTypeID,$projectID,$recurringID,$groupSetID,$active){
		include ('../../../m_inc/m_config.php'); //mysql cridential
		include ('../../../m_inc/def_value.php');
		$flag = "99";
		if ($referenceNumber == "yes"){
			$referenceNumber = date('m').date('d').date('H').date('i').date('s').rand(100000, 999999);
		}else{
			$referenceNumber = "";
		}
		try{//insert just the budget
			$result = $db->prepare("INSERT INTO $db_budtransaction (account_id,member_id,transaction_date,posted,transaction_order,amount,transaction_type_id,category_id,description,reference_number,budget_id,budget_type_id,project_id,recurring_id,group_set_id,active) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
			$result->execute(array($accountID,$memberID,$transactionDate,$posted,$transactionOrder,$amount,$transactionTypeID,$categoryID,$description,$referenceNumber,$budgetID,$budgetTypeID,$projectID,$recurringID,$groupSetID,$active));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		
		//update the recurring_group with main_trans_id equal this budtransaction_id
		if ($referenceNumber != ""){
			try{//get recurring id from $db_recurring
				$result = $db->prepare("SELECT budtransaction_id FROM $db_budtransaction WHERE reference_number=?");
				$result->execute(array($referenceNumber));
			} catch(PDOException $e) {
				echo "message001 - Sorry, system is experincing problem. Please check back.";
			}
			foreach ($result as $row){
				$mainTransID = $row['budtransaction_id'];
			}
			try{//update reference number to empty
				$result = $db->prepare("UPDATE $db_recurring_group SET main_trans_id=? WHERE recurring_group_id=?");
				$result->execute(array($mainTransID,$groupSetID));
			} catch(PDOException $e) {
				echo "message001 - Sorry, system is experincing problem. Please check back.";
			}
			try{//update recurring_group main_trans_id
				$result = $db->prepare("UPDATE $db_budtransaction SET reference_number='' WHERE reference_number=?");
				$result->execute(array($referenceNumber));
			} catch(PDOException $e) {
				echo "message001 - Sorry, system is experincing problem. Please check back.";
			}
		}			
		return $flag;
	}//end insertBudTransF
	
	//Begin delBudRecurringChgF - Delete all related records based on budgetID for recurring changes situation
	//budget/addbudget)budget/delbudget)
	function delBudRecurringChgF($memberID,$budgetID,$budTransID){
		include ('../../../m_inc/m_config.php'); //mysql cridential
		include ('../../../m_inc/def_value.php');
		$recurringID = "";
		$groupSetID = "";
		
		///get budget transaction data
		try{//get order id
			$result = $db->prepare("SELECT recurring_id,group_set_id FROM $db_budtransaction WHERE member_id=? AND budget_id=? AND budtransaction_id=?");
			$result->execute(array($memberID,$budgetID,$budTransID));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		foreach ($result as $row){
			$recurringID = $row['recurring_id'];
			$groupSetID = $row['group_set_id'];
		}
		
		if ($recurringID == "" or $recurringID == "0"){
			if ($groupSetID == "" or $groupSetID == "0"){
				try{//delete transaction from $db_transaction since there's not recurring previously
					$result = $db->prepare("DELETE FROM $db_budtransaction WHERE member_id=? AND budget_id=? AND budtransaction_id=?");
					$result->execute(array($memberID,$budgetID,$budTransID));
				} catch(PDOException $e) {
					echo "message001 - Sorry, system is experincing problem. Please check back.";
				}
			}else{
				try{//delete transaction from $db_transaction since there's not recurring previously
					$result = $db->prepare("DELETE FROM $db_budtransaction WHERE member_id=? AND budget_id=? AND group_set_id=?");
					$result->execute(array($memberID,$budgetID,$groupSetID));
				} catch(PDOException $e) {
					echo "message001 - Sorry, system is experincing problem. Please check back.";
				}
				try{//delete all in $db_budtransaction
					$result = $db->prepare("DELETE FROM $db_recurring_group WHERE recurring_group_id=?");
					$result->execute(array($groupSetID));
				} catch(PDOException $e) {
					echo "message001 - Sorry, system is experincing problem. Please check back.";
				}
			}
		}
		
		try{//delete all in $db_budtransaction
			$result = $db->prepare("DELETE FROM $db_budtransaction WHERE member_id=? AND recurring_id=? AND budget_id=?");
			$result->execute(array($memberID,$recurringID,$budgetID));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		try{//delete all in $db_budtransaction
			$result = $db->prepare("DELETE FROM $db_transaction WHERE member_id=? AND recurring_id=?");
			$result->execute(array($memberID,$recurringID));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		try{//delete all in $db_budtransaction
			$result = $db->prepare("DELETE FROM $db_recurring WHERE recurring_id=?");
			$result->execute(array($recurringID));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		try{//delete all in $db_budtransaction
			$result = $db->prepare("DELETE FROM $db_recurring_group WHERE recurring_id=?");
			$result->execute(array($recurringID));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
	}//end delBudRecurringChgF
	
	//Begin getBudOrderNumF - Return transaction order for new budget amount
	//account/addaccount)budget/addbudget)
	function getBudOrderNumF($transOrderCounter,$transDate){
		include ('m_inc/m_config.php'); //mysql cridential
		include ('m_inc/def_value.php');
		
		try{//get order id
			$result = $db->prepare("SELECT transaction_id FROM $db_transaction WHERE transaction_date=?");
			$result->execute(array($transDate));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		foreach ($result as $row){
			$transOrderCounter++;
		}
		
		return $transOrderCounter;
	}//end getBudOrderNumF
	
	//Begin getBudActualF
	//)
	function getBudActualF($memberID,$budgetlistID,$orgDate,$lastDaySelectDate){
		include ('m_inc/m_config.php'); //mysql cridential
		include ('m_inc/def_value.php');
		$yes = "yes";
		$totAmt = 0;
		
		try{//get order id
			$result = $db->prepare("SELECT amount,transaction_type_id,budgetlist_id FROM $db_transaction WHERE member_id=? AND budgetlist_id=? AND transaction_date>=? AND transaction_date<=? AND posted=? ORDER BY transaction_date");
			$result->execute(array($memberID,$budgetlistID,$orgDate,$lastDaySelectDate,$yes));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		foreach ($result as $row){
			if ($row['transaction_type_id'] == "1000"){
				$totAmt = $totAmt + abs($row['amount']);
			}
			if ($row['transaction_type_id'] == "1001"){
				$totAmt = $totAmt - $row['amount'];
			}
		}
				
		return $totAmt;
	}//end getBudOrderNumF
	
	//Begin getProjActualF
	//)
	function getProjActualF($memberID,$budgetlistID,$transTypeId){
		include ('m_inc/m_config.php'); //mysql cridential
		include ('m_inc/def_value.php');
		$yes = "yes";
		$totAmt = 0;
		
		//get projectID
		$projectID = "";
		try{//get order id
			$result = $db->prepare("SELECT amount FROM $db_project_detail WHERE budgetlist_id=? AND completed=?");
			$result->execute(array($budgetlistID,$yes));
		} catch(PDOException $e) {
			echo "message002 - Sorry, system is experincing problem. Please check back.";
		}
		foreach ($result as $row){
			$totAmt = $totAmt + abs($row['amount']);
		}
				
		return $totAmt;
	}//end getBudOrderNumF
	
	//find different between two months
	function diffMonthF($startDate, $endDate) {

       	$retval = "";

		// Assume YYYY-mm-dd - as is common MYSQL format
		$splitStart = explode('-', $startDate);
		$splitEnd = explode('-', $endDate);
	
		if (is_array($splitStart) && is_array($splitEnd)) {
			$difYears = $splitEnd[0] - $splitStart[0];
			$difMonths = $splitEnd[1] - $splitStart[1];
			$difDays = $splitEnd[2] - $splitStart[2];
	
			$retval = ($difDays > 0) ? $difMonths : $difMonths - 1;
			$retval += $difYears * 12;
		}
		return $retval;
    }
	
	//Begin getBudOrderNumF - Return transaction order for new budget amount
	//account/addaccount)budget/addbudget)
	function getOrderNumBudF($transOrderCounter,$transDate){
		include ('../../../m_inc/m_config.php'); //mysql cridential
		include ('../../../m_inc/def_value.php');
		try{//get order id
			$result = $db->prepare("SELECT transaction_date FROM $db_budtransaction WHERE transaction_date=? ORDER BY transaction_date ASC");
			$result->execute(array($transDate));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		foreach ($result as $row){
			$transOrderCounter += 1;
		}
		return $transOrderCounter;
	}//end getBudOrderNumF
	
	//Begin updateChgBudgetF - Update related changes per budget transaction in budtransaction table
	//budget/addbudget)
	function updateChgBudgetF($memberID,$budTransID,$transDate,$categoryID,$amount,$acctId,$acctTypeTrans,$note,$groupSetID,$acctChange,$forModule){
		include ('../../../m_inc/m_config.php'); //mysql cridential
		include ('../../../m_inc/def_value.php');
		
		try{//update accounts
			$result = $db->prepare("UPDATE $db_budtransaction SET transaction_date=?,amount=?,category_id=?,description=?,group_set_id=? WHERE member_id=? AND budtransaction_id=?");
			$result->execute(array($transDate,$amount,$categoryID,$note,$groupSetID,$memberID,$budTransID));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
	}//end updateChgBudgetF
	
	//Begin updateChgBudgetOnlyF - Update specific changes per budget transaction in budtransaction table
	//account/addaccount)budget/addbudget)
	function updateChgBudgetOnlyF($memberID,$budTransID,$transDate,$categoryID,$amount,$note,$acctChange,$forModule){
		include ('../../../m_inc/m_config.php'); //mysql cridential
		include ('../../../m_inc/def_value.php');
		
		try{//update accounts
			if ($forModule == "account"){
				$result = $db->prepare("UPDATE $db_budtransaction SET transaction_date=?,category_id=?,description=? WHERE member_id=? AND budtransaction_id=?");
				$result->execute(array($transDate,$categoryID,$note,$memberID,$budTransID));
			}
			if ($forModule == "budget"){
				$result = $db->prepare("UPDATE $db_budtransaction SET transaction_date=?,amount=?,category_id=?,description=? WHERE member_id=? AND budtransaction_id=?");
				$result->execute(array($transDate,$amount,$categoryID,$note,$memberID,$budTransID));
			}
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
	}//end updateChgBudgetOnlyF
	
	//Begin retBudtransactionIDF - Return budtransactionID based on groupSetID
	//account/addaccount)account/delaccount)
	function retBudtransactionIDF($memberID,$budgetID,$groupSetID){
		include ('m_inc/m_config.php'); //mysql cridential
		include ('m_inc/def_value.php');
		$budtransaction = "";
		
		///get transaction data
		try{
			$result = $db->prepare("SELECT budtransaction_id FROM $db_budtransaction WHERE member_id=? AND budget_id=? AND group_set_id=?");
			$result->execute(array($memberID,$budgetID,$groupSetID));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		foreach ($result as $row){
			$budtransaction = $row['budtransaction_id'];
		}
		return $budtransaction;
	}//end retBudtransactionIDF
	
	//Begin 
	//
	function retBudTrackTotF($memberID,$budgetlistID){
		include ('m_inc/m_config.php'); //mysql cridential
		include ('m_inc/def_value.php');
		$budgetTot = 0;
		$amountArr = array();
		
		try{
			$result = $db->prepare("SELECT amount,startdate FROM $db_budgetlist WHERE member_id=? AND budgetlist_id=?");
			$result->execute(array($memberID,$budgetlistID));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		foreach ($result as $row){
			$budgetTot = $row['amount'];
			$budgetlistDate = $row['startdate'];
			$amountArr[0] = $row['amount'];
		}
		
		try{
			$result = $db->prepare("SELECT amount,transaction_type_id FROM $db_transaction WHERE member_id=? AND budgetlist_id=? AND transaction_date>=?");
			$result->execute(array($memberID,$budgetlistID,$budgetlistDate));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		$amount = 0;
		foreach ($result as $row){
			if ($row['transaction_type_id'] == "1000"){
				$amount = $amount + $row['amount'];
			}
			if ($row['transaction_type_id'] == "1001"){
				$amount = $amount - $row['amount'];
			}
		}
		$amountArr[1] = $amount;
		
		return $amountArr;
	}//end retBudtransactionIDF
	
	//Begin 
	//
	function retTagTotF($memberID,$tagID){
		include ('m_inc/m_config.php'); //mysql cridential
		include ('m_inc/def_value.php');
		$amountTot = 0;
		
		try{
			$result = $db->prepare("SELECT amount,transaction_type_id FROM $db_transaction WHERE member_id=? AND tag_id=?");
			$result->execute(array($memberID,$tagID));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		foreach ($result as $row){
			if ($row['transaction_type_id'] == "1000"){
				$amountTot = $amountTot + $row['amount'];
			}
			if ($row['transaction_type_id'] == "1001"){
				$amountTot = $amountTot - $row['amount'];
			}
		}
		
		return $amountTot;
	}//end retBudtransactionIDF
	
	//Cover number to dollar amount
	//**all $ format
	function convertDollarF($amount){
		if ($amount < 0){
				$amountVal = "(".moneyFormat((float)abs($amount)).")"; 
		} else{
				$amountVal = moneyFormat((float)$amount);
		}
		return $amountVal;
	}
}
///End of budgetInfoC //////////////////////////////////////////////////////

///Begin of accountInfoC class /////////////////////////////////////////////
class accountInfoC{
	//account/log_account)account/log_index)
	function eachAccountTotF($accountID,$member_id,$todayDate){
		include('m_inc/def_value.php');
		include ('m_inc/m_config.php');
		$transArr = array();
		$yeVal = "yes";
		$transArr[0] = 0;
		$transArr[1] = 0;
		$processDate = $todayDate;//date($currentYr."-".$budgetMon."-d");
		
		try{
			$result = $db->prepare("SELECT amount,transaction_type_id,transaction_date,posted FROM $db_transaction WHERE account_id=? AND member_id=? AND transaction_date<=? AND active=? ORDER BY transaction_date ASC");
			$result->execute(array($accountID,$member_id,$processDate,$yeVal));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; script>";
		}
		$transItems = $result->rowCount();
		if ($transItems > 0){
			foreach ($result as $row) {
				if ($row['posted']=="yes"){
					if ($row['transaction_type_id']=="1000"){
						$transArr[1] = $transArr[1] + $row['amount'];
					}
					if ($row['transaction_type_id']=="1001"){
						$transArr[1] = $transArr[1] - $row['amount'];
					}
				}
				if ($row['transaction_type_id']=="1000"){
					$transArr[0] = $transArr[0] + $row['amount'];
				}
				if ($row['transaction_type_id']=="1001"){
					$transArr[0] = $transArr[0] - $row['amount'];
				}
			}//end foreach loop
		}	
		return $transArr;
	}//end eachAccountTotF
	
	//Begin categoryNameF -  category name 
	//account/log_account)budget/log_budget)budget/log_budgetmonth)report/log_rpt001acta)report/log_rept001buda
	function categoryNameF($categoryID){
		include('m_inc/def_value.php');
		include ('m_inc/m_config.php');
		$yeVal = "yes";
		try{
			$result = $db->prepare("SELECT category FROM $db_category WHERE category_id=? AND active=?");
			$result->execute(array($categoryID,$yeVal));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; script>";
		}
		foreach ($result as $row) {
			$categoryName = str_replace('\\','',$row['category']);
		}
		return $categoryName;
	}
	
	//Begin accountNameF - return account name
	//?
	function accountNameF($accountID){
		include('m_inc/def_value.php');
		include ('m_inc/m_config.php');
		$yeVal = "yes";
		try{
			$result = $db->prepare("SELECT name, member_id FROM $db_account WHERE account_id=? AND active=?");
			$result->execute(array($accountID,$yeVal));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; script>";
		}
		$accountNameMemberID = array();
		foreach ($result as $row) {
			$accountNameMemberID[0] = str_replace('\\','',$row['name']);
			$accountNameMemberID[1] = $row['member_id'];
		}
		return $accountNameMemberID;
	}//end accountNameF
	
	//Begin accountSpcRightF - return account specific rights
	//?
	function accountSpcRightF($memberID,$accountID){
		include('m_inc/def_value.php');
		include ('m_inc/m_config.php');
		$yeVal = "yes";
		try{
			$result = $db->prepare("SELECT access_rights FROM $db_account_rights WHERE member_id=? AND account_id=? AND active=?");
			$result->execute(array($memberID,$accountID,$yeVal));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; script>";
		}
		$accountRights = 0;
		foreach ($result as $row) {
			$accountRights = $row['access_rights'];
		}
		return $accountRights;
	}//end accountSpcRightF
	
	//Begin insertAcctTrans - 
	//addaccount)addbudget)uptproject)
	function insertAcctTrans($accountID,$memberID,$transactionDate,$posted,$transactionOrder,$amount,$transactionTypeID,$categoryID,$description,$referenceNumber,$budgetID,$budgetTypeID,$projectID,$recurringID,$groupSetID,$trkmemberID,$budgerannsetID,$tagID,$active){
		include(dirname(__FILE__)."/../m_inc/def_value.php");
		include(dirname(__FILE__)."/../m_inc/m_config.php");
		
		$referenceNumber = date('m').date('d').date('H').date('i').date('s').rand(100000, 999999);
		try{//insert just the budget
			$result = $db->prepare("INSERT INTO $db_transaction (account_id,member_id,transaction_date,posted,transaction_order,amount,transaction_type_id,category_id,description,reference_number,budget_id,budget_type_id,project_id,recurring_id,group_set_id,trkmember_id,budgetlist_id,tag_id,active) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
			$result->execute(array($accountID,$memberID,$transactionDate,$posted,$transactionOrder,$amount,$transactionTypeID,$categoryID,$description,$referenceNumber,$budgetID,$budgetTypeID,$projectID,$recurringID,$groupSetID,$trkmemberID,$budgerannsetID,$tagID,$active));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		
		try{//get recurring id from $db_recurring
			$result = $db->prepare("SELECT transaction_id FROM $db_transaction WHERE reference_number=?");
			$result->execute(array($referenceNumber));
		} catch(PDOException $e) {
			echo "message002 - Sorry, system is experincing problem. Please check back.";
		}
		foreach ($result as $row){
			$mainTransID = $row['transaction_id'];
		}
			//update the recurring_group with main_trans_id equal this budtransaction_id
			//not sure if this is needed - tou 11/18
		if ($referenceNumber != ""){
			try{
				$result = $db->prepare("UPDATE $db_recurring_group SET main_trans_id=? WHERE recurring_group_id=?");
				$result->execute(array($mainTransID,$groupSetID));
			} catch(PDOException $e) {
				echo "message003 - Sorry, system is experincing problem. Please check back.";
			}
		}
		try{//set reference_number to empty
			$result = $db->prepare("UPDATE $db_transaction SET reference_number='' WHERE reference_number=?");
			$result->execute(array($referenceNumber));
		} catch(PDOException $e) {
			echo "message004 - Sorry, system is experincing problem. Please check back.";
		}
		return $mainTransID;
	}//end insertAcctTrans
	
	//Begin insertAcctTransPrj for project only - 
	function insertAcctTransPrj($accountID,$memberID,$transactionDate,$posted,$transactionOrder,$amount,$transactionTypeID,$categoryID,$description,$referenceNumber,$budgetID,$budgetTypeID,$projectID,$recurringID,$groupSetID,$trkmemberID,$budgerannsetID,$tagID,$membertrackID,$active){
		include(dirname(__FILE__)."/../m_inc/def_value.php");
		include(dirname(__FILE__)."/../m_inc/m_config.php");
		
		$referenceNumber = date('m').date('d').date('H').date('i').date('s').rand(100000, 999999);
		try{//insert just the budget
			$result = $db->prepare("INSERT INTO $db_transaction (account_id,member_id,transaction_date,posted,transaction_order,amount,transaction_type_id,category_id,description,reference_number,budget_id,budget_type_id,project_id,recurring_id,group_set_id,trkmember_id,budgetlist_id,tag_id,membertrack_id,active) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
			$result->execute(array($accountID,$memberID,$transactionDate,$posted,$transactionOrder,$amount,$transactionTypeID,$categoryID,$description,$referenceNumber,$budgetID,$budgetTypeID,$projectID,$recurringID,$groupSetID,$trkmemberID,$budgerannsetID,$tagID,$membertrackID,$active));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		
		try{//get recurring id from $db_recurring
			$result = $db->prepare("SELECT transaction_id FROM $db_transaction WHERE reference_number=?");
			$result->execute(array($referenceNumber));
		} catch(PDOException $e) {
			echo "message002 - Sorry, system is experincing problem. Please check back.";
		}
		foreach ($result as $row){
			$mainTransID = $row['transaction_id'];
		}
		try{//set reference_number to empty
			$result = $db->prepare("UPDATE $db_transaction SET reference_number='' WHERE reference_number=?");
			$result->execute(array($referenceNumber));
		} catch(PDOException $e) {
			echo "message004 - Sorry, system is experincing problem. Please check back.";
		}
		return $mainTransID;
	}//end insertAcctTransPrj for projects
	
	//Begin insertAcctTrans - 
	//addaccount)addbudget)uptproject)
	function insertAcctTransBudF($accountID,$memberID,$transactionDate,$posted,$transactionOrder,$amount,$transactionTypeID,$categoryID,$description,$referenceNumber,$budgetID,$budgetTypeID,$projectID,$recurringID,$groupSetID,$trkmemberID,$budgerannsetID,$tagID,$active){
		include(dirname(__FILE__)."/../m_inc/def_value.php");
		include(dirname(__FILE__)."/../m_inc/m_config.php");
		
		$referenceNumber = date('m').date('d').date('H').date('i').date('s').rand(100000, 999999);
		try{//insert just the budget
			$result = $db->prepare("INSERT INTO $db_transaction (account_id,member_id,transaction_date,posted,transaction_order,amount,transaction_type_id,category_id,description,reference_number,budget_id,budget_type_id,project_id,recurring_id,group_set_id,trkmember_id,budgetlist_id,tag_id,active) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
			$result->execute(array($accountID,$memberID,$transactionDate,$posted,$transactionOrder,$amount,$transactionTypeID,$categoryID,$description,$referenceNumber,$budgetID,$budgetTypeID,$projectID,$recurringID,$groupSetID,$trkmemberID,$budgerannsetID,$tagID,$active));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		
		try{//get recurring id from $db_recurring
			$result = $db->prepare("SELECT transaction_id FROM $db_transaction WHERE reference_number=?");
			$result->execute(array($referenceNumber));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		foreach ($result as $row){
			$mainTransID = $row['transaction_id'];
		}
			//update the recurring_group with main_trans_id equal this budtransaction_id
			//not sure if this is needed - tou 11/18
		if ($referenceNumber != ""){
			try{
				$result = $db->prepare("UPDATE $db_recurring_group SET main_trans_id=? WHERE recurring_group_id=?");
				$result->execute(array($mainTransID,$groupSetID));
			} catch(PDOException $e) {
				echo "message001 - Sorry, system is experincing problem. Please check back.";
			}
		}
		try{//set reference_number to empty
			$result = $db->prepare("UPDATE $db_transaction SET reference_number='' WHERE reference_number=?");
			$result->execute(array($referenceNumber));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		return $mainTransID;
	}//end insertAcctTrans
		
	//Begin getTransOrderNum -
	//?
	function getTransOrderNum($transOrderCounter,$acctId,$transDate){
		include ('../../../m_inc/m_config.php'); //mysql cridential
		include ('../../../m_inc/def_value.php');
		try{//get order id
			$result = $db->prepare("SELECT transaction_date FROM $db_transaction WHERE account_id=? AND transaction_date=? ORDER BY transaction_date ASC");
			$result->execute(array($acctId,$transDate));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		foreach ($result as $row){
			$transOrderCounter += 1;
		}
		return $transOrderCounter;
	}//end getTransOrderNum
	
	//update a single transaction
	function updateChgAcct($memberID,$transID,$transDate,$posted,$categoryID,$acctAmtTemp,$acctId,$acctTypeTrans,$note,$groupSetID,$acctChange){
		include ('../../../m_inc/m_config.php'); //mysql cridential
		include ('../../../m_inc/def_value.php');
		
		try{//update accounts
			$result = $db->prepare("UPDATE $db_transaction SET transaction_date=?,amount=?,transaction_type_id=?,category_id=?,description=?,posted=?,group_set_id=? WHERE member_id=? AND transaction_id=?");
			$result->execute(array($transDate,$acctAmtTemp,$acctTypeTrans,$categoryID,$note,$posted,$groupSetID,$memberID,$transID));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		$fDone = "good";
		return $fDone;
	}
	
	//Begin updateChgAcctGroup - update transaction for the group
	//?
	function updateChgAcctGroup($memberID,$transID,$transDate,$posted,$categoryID,$acctAmtTemp,$acctId,$acctTypeTrans,$note,$groupSetID,$acctChange){
		include ('../../../m_inc/m_config.php'); //mysql cridential
		include ('../../../m_inc/def_value.php');
		
		try{//update accounts
			$result = $db->prepare("UPDATE $db_transaction SET transaction_date=?,amount=?,transaction_type_id=?,category_id=?,description=?,posted=? WHERE member_id=? AND group_set_id=?");
			$result->execute(array($transDate,$acctAmtTemp,$acctTypeTrans,$categoryID,$note,$posted,$memberID,$groupSetID));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		$fDone = "good";
		return $fDone;
	}//end updateChgAcctGroup
	
	//Begin updateAcctBySetID -
	//?
	function updateAcctBySetID($memberID,$transDate,$categoryID,$amount,$note,$groupSetID){
		include ('../../../m_inc/m_config.php'); //mysql cridential
		include ('../../../m_inc/def_value.php');
		
		try{//update accounts
			$result = $db->prepare("UPDATE $db_transaction SET transaction_date=?,category_id=?,amount=?,description=? WHERE member_id=? AND group_set_id=?");
			$result->execute(array($transDate,$categoryID,$amount,$note,$memberID,$groupSetID));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
	}//end updateAcctBySetID
	
	//Begin delChgAcct - 
	//?
	function delChgAcct($memberID,$transID){
		include ('../../../m_inc/m_config.php'); //mysql cridential
		include ('../../../m_inc/def_value.php');
		try{//delete account in $db_transaction
			$result = $db->prepare("DELETE FROM $db_transaction WHERE member_id=? AND transaction_id=?");
			$result->execute(array($memberID,$transID));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
	}//end delChgAcct
	
	//Begin delAcctRecurringChg - delete all transaction records including budtransaction
	//?
	function delAcctRecurringChg($memberID,$accountID,$transID){
		include ('m_inc/m_config.php'); //mysql cridential
		include ('m_inc/def_value.php');
		$budgetTransInfo = array();
		$recurringID = "";
		$groupSetID = "";
		
		///get transaction data
		try{//get order id
			$result = $db->prepare("SELECT budget_id,budget_type_id,recurring_id,group_set_id FROM $db_transaction WHERE member_id=? AND account_id=? AND transaction_id=?");
			$result->execute(array($memberID,$accountID,$transID));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		foreach ($result as $row){
			$recurringID = $row['recurring_id'];
			$groupSetID = $row['group_set_id'];
			$budgetTransInfo[0] = $row['budget_id'];
			$budgetTransInfo[1] = $row['budget_type_id'];
		}
		
		if ($recurringID == "" or $recurringID == "0" or $recurringID == "NULL"){
			if ($groupSetID == "" or $groupSetID == "0" or $groupSetID == "NULL"){
				try{//delete transaction from $db_transaction since there's not recurring previously
					$result = $db->prepare("DELETE FROM $db_transaction WHERE member_id=? AND transaction_id=?");
					$result->execute(array($memberID,$transID));
				} catch(PDOException $e) {
					echo "message001 - Sorry, system is experincing problem. Please check back.";
				}
			}else{
				try{//delete transaction from $db_transaction since there's not recurring previously
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
			}
		}
		
		if ($recurringID != "" and $recurringID != "0" and $recurringID != "NULL"){
			try{//delete all in $db_transaction
				$result = $db->prepare("DELETE FROM $db_transaction WHERE member_id=? AND recurring_id=?");
				$result->execute(array($memberID,$recurringID));
			} catch(PDOException $e) {
				echo "message001 - Sorry, system is experincing problem. Please check back.";
			}
			try{//delete all in $db_transaction
				$result = $db->prepare("DELETE FROM $db_recurring WHERE recurring_id=?");
				$result->execute(array($recurringID));
			} catch(PDOException $e) {
				echo "message001 - Sorry, system is experincing problem. Please check back.";
			}
			try{//delete all in $db_transaction
				$result = $db->prepare("DELETE FROM $db_recurring_group WHERE recurring_id=?");
				$result->execute(array($recurringID));
			} catch(PDOException $e) {
				echo "message001 - Sorry, system is experincing problem. Please check back.";
			}
		}
		return $budgetTransInfo;
	}//end delAcctRecurringChg
	
	//Begin getAcctBudgetID - return inserted account Buget ID
	//?
	function getAcctBudgetID($memberID,$acctId,$transID){
		include ('m_inc/m_config.php'); //mysql cridential
		include ('m_inc/def_value.php');
		$budgetTransInfo = array();
		$budgetTransInfo[0] = "";
		$budgetTransInfo[1] = "";
		$budgetTransInfo[2] = "";
		///get transaction data
		try{//get order id
			$result = $db->prepare("SELECT budget_id,budget_type_id,group_set_id FROM $db_transaction WHERE member_id=? AND account_id=? AND transaction_id=?");
			$result->execute(array($memberID,$acctId,$transID));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		foreach ($result as $row){
			$budgetTransInfo[0] = $row['budget_id'];
			$budgetTransInfo[1] = $row['budget_type_id'];
			$budgetTransInfo[2] = $row['group_set_id'];
		}
		return $budgetTransInfo;
	}//end getAcctBudgetID
	
}
///End of accountInfoC class //////////////////////////////////////////////////////

///Begin of memberInfoC class //////////////////////////////////////////////////////
class memberInfoC{
	//Begin memberActiveF - check for active member
	//m_momax/setting/con_log/log_groupset.php)
	function memberActiveF($memberID){
		include('m_inc/def_value.php');
		include ('m_inc/m_config.php');
		$active = "no";
		try{
			$result = $db->prepare("SELECT active FROM $db_member WHERE member_id=?");
			$result->execute(array($memberID));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; script>";
		}
		foreach ($result as $row) {
			if ($row['active'] == "yes"){
				$active = "yes";
			}else{
				$active = "no";
			}
		}
		return $active;
	}//end memberActiveF
	
	//Begin memberNameF - return member name
	//?
	function memberNameF($memberID){
		include('m_inc/def_value.php');
		include ('m_inc/m_config.php');
		
		$yeVal = "yes";
		try{
			$result = $db->prepare("SELECT first_name FROM $db_member WHERE member_id=?");
			$result->execute(array($memberID));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; script>";
		}
		foreach ($result as $row) {
			$memberName = str_replace('\\','',$row['first_name']);
		}
		return $memberName;
	}//end memberNameF
	
	//Begin memberFLNameF - return member name
	//m_momax/setting/con_log/log_groupset)
	function memberFLNameF($memberID){
		include('m_inc/def_value.php');
		include ('m_inc/m_config.php');
		
		$yeVal = "yes";
		try{
			$result = $db->prepare("SELECT first_name, last_name FROM $db_member WHERE member_id=?");
			$result->execute(array($memberID));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; script>";
		}
		foreach ($result as $row) {
			$memberName = str_replace('\\','',$row['first_name'])." ".str_replace('\\','',$row['last_name']);
		}
		return $memberName;
	}//end memberFLNameF
	
	//Begin memberPixF - return name of member's pix
	//?
	function memberPixF($memberID){
		include('m_inc/def_value.php');
		include ('m_inc/m_config.php');
		$yeVal = "yes";
		try{
			$result = $db->prepare("SELECT pix_name FROM $db_member_pix WHERE member_id=? AND active=?");
			$result->execute(array($memberID,$yeVal));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; script>";
		}
		foreach ($result as $row) {
			$memberPostPix = str_replace('\\','',$row['pix_name']);
		}
		return $memberPostPix;
	}//end memberPixF
	
	//Begin memberGrpRightsF - return member group's rights
	//?
	function memberGrpRightsF($memberID){
		include('m_inc/def_value.php');
		include ('m_inc/m_config.php');
		$yeVal = "yes";
		try{
			$result = $db->prepare("SELECT group_rights FROM $db_group_member WHERE member_id=? AND active=?");
			$result->execute(array($memberID,$yeVal));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; script>";
		}
		foreach ($result as $row) {
			$grpRights = $row['group_rights'];
		}
		return $grpRights;
	}//end memberGrpRightsF
	
	//Begin memberGroupF - return member's group
	//log_budgetshare)log_accountshare)log_projectshare)mx009mt)
	function memberGroupF($groupID){
		include('m_inc/def_value.php');
		include ('m_inc/m_config.php');
		$yes = "yes";
		
		try{
			$result = $db->prepare("SELECT group_name FROM $db_group WHERE group_id=?");
			$result->execute(array($groupID));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; script>";
		}
		foreach ($result as $row) {
			$grpName = str_replace('\\','',$row['group_name']);
		}
		return $grpName;
	}//end memberGroupF
	
	//Begin memberNameReqF - return member name
	//?
	function memberNameReqF($memberID){
		include('../../../m_inc/def_value.php');
		include ('../../../m_inc/m_config.php');
				
		$yeVal = "yes";
		try{
			$result = $db->prepare("SELECT first_name, last_name FROM $db_member WHERE member_id=? AND active=?");
			$result->execute(array($memberID,$yeVal));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		foreach ($result as $row) {
			$memberName = str_replace('\\','',$row['first_name'])." ".str_replace('\\','',$row['last_name']);
		}
		return $memberName;
	}//end memberNameReqF
	//return budgettrack name
	function getMembertrackNameF($membertrackID){
		include(dirname(__FILE__)."/../m_inc/def_value.php");
		include(dirname(__FILE__)."/../m_inc/m_config.php");
		
		try{
			$result = $db->prepare("SELECT name FROM $db_membertrack WHERE membertrack_id=?");
			$result->execute(array($membertrackID));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; script>";
		}
		$name = "";
		foreach ($result as $row) {
			$name = $row['name'];
		}
		return $name;
	}//end budgettrack name
	//return forecasting budget amount 
	function getMemberbudgetTotF($memberbudgetID){
		include(dirname(__FILE__)."/../m_inc/def_value.php");
		include(dirname(__FILE__)."/../m_inc/m_config.php");
		
		try{
			$result = $db->prepare("SELECT amount FROM $db_memberbudget_amt WHERE memberbudget_id=?");
			$result->execute(array($memberbudgetID));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; script>";
		}
		$totAmt = 0;
		foreach ($result as $row) {
			$totAmt = $totAmt + $row['amount'];
		}
		return $totAmt;
	}//end forecasting budget amount
	//return forecasting budget amount for specific memberbudget
	function getMemberbudgetAmtF($memberID,$memberbudgetID){
		include(dirname(__FILE__)."/../m_inc/def_value.php");
		include(dirname(__FILE__)."/../m_inc/m_config.php");
		
		try{
			$result = $db->prepare("SELECT amount FROM $db_memberbudget_amt WHERE member_id=? AND memberbudget_id=?");
			$result->execute(array($memberID,$memberbudgetID));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; script>";
		}
		$totAmt = 0;
		foreach ($result as $row) {
			$totAmt = $totAmt + $row['amount'];
		}
		return $totAmt;
	}//end forecasting budget amount for specific memberbudget
	//return forecasting budget amount 
	function getMembertrackTotF($membertrackID){
		include(dirname(__FILE__)."/../m_inc/def_value.php");
		include(dirname(__FILE__)."/../m_inc/m_config.php");
		
		try{
			$result = $db->prepare("SELECT amount FROM $db_membertrack_amt WHERE membertrack_id=?");
			$result->execute(array($membertrackID));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; script>";
		}
		$totAmt = 0;
		foreach ($result as $row) {
			$totAmt = $totAmt + $row['amount'];
		}
		return $totAmt;
	}//end forecasting budget amount
	//return budget tracking amount for specific memberbudget
	function getMembertrackAmtF($memberID,$membertrackID){
		include(dirname(__FILE__)."/../m_inc/def_value.php");
		include(dirname(__FILE__)."/../m_inc/m_config.php");
		
		try{
			$result = $db->prepare("SELECT amount FROM $db_membertrack_amt WHERE member_id=? AND membertrack_id=?");
			$result->execute(array($memberID,$membertrackID));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; script>";
		}
		$totAmt = 0;
		foreach ($result as $row) {
			$totAmt = $totAmt + $row['amount'];
		}
		return $totAmt;
	}//end budget tracking amount for specific memberbudget
	//return budget tracking amount for specific memberbudget
	function getMembertrackActAmtF($memberbudgetID,$memberID){
		include(dirname(__FILE__)."/../m_inc/def_value.php");
		include(dirname(__FILE__)."/../m_inc/m_config.php");
		
		$membertrackIdArr = array();
		$membertrackIdArrCt = 0;
		try{
			$result = $db->prepare("SELECT membertrack_id FROM $db_membertrack WHERE member_id=? AND memberbudget_id=?");
			$result->execute(array($memberID,$memberbudgetID));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; script>";
		}
		foreach ($result as $row) {
			$membertrackIdArr[$membertrackIdArrCt] = $row['membertrack_id'];
			$membertrackIdArrCt++;
		}
		$totAmt = 0;
		for ($i = 0; $i < $membertrackIdArrCt; $i++){
			try{
				$result = $db->prepare("SELECT amount FROM $db_membertrack_amt WHERE membertrack_id=?");
				$result->execute(array($membertrackIdArr[$i]));
			} catch(PDOException $e) {
				print "<script> self.location='".$index_url."?err=d1000'; script>";
			}
			foreach ($result as $row) {
				$totAmt = $totAmt + $row['amount'];
			}
		}
		return $totAmt;
	}//end budget tracking amount for specific memberbudget
	

}
///End of memberInfoC class //////////////////////////////////////////////////////

///Begin of projectInfoC class //////////////////////////////////////////////////////
class projectInfoC{
	var $name;
	function set_name($new_name) {
			$this->name = $new_name;
	}
	function get_name() {
			return $this->name;
	}

	//Begin projectNameIDF - return proectID based on memberID
	//log_project*php/log_budget)
	function projectNameIDF($projectID){
		include('m_inc/def_value.php');
		include ('m_inc/m_config.php');
		$yeVal = "yes";
		$projectNameID = array();
		$projectNameID[0] = "";
		$projectNameID[1] = "";
		$projectNameID[2] = "";
		$projectNameID[3] = "";
		$projectNameID[4] = "";
		try{
			$result = $db->prepare("SELECT name,member_id,date,note,budgetlist_id FROM $db_project WHERE project_id=? AND active=?");
			$result->execute(array($projectID,$yeVal));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; script>";
		}
		foreach ($result as $row) {
			$projectNameID[0] = str_replace('\\','',$row['name']);
			$projectNameID[1] = $row['member_id'];
			$orgDate = $row['date'];
			$partsArr = explode("-",$orgDate);
			$projectNameID[2] = $partsArr[1]."-".$partsArr[2]."-".$partsArr[0];
			$projectNameID[3] = $row['note'];
			$projectNameID[4] = $row['budgetlist_id'];
		}
		return $projectNameID;
	}//end projectNameIDF
	
	//Begin projectTotAmtF - return total amount per project
	//?
	function projectTotAmtF($memberID,$projectID){
		include('m_inc/def_value.php');
		include ('m_inc/m_config.php');
		$yeVal = "yes";
		$projectAmt = 0;
		try{
			$result = $db->prepare("SELECT amount FROM $db_project_detail WHERE project_id=?");
			$result->execute(array($projectID));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; script>";
		}
		foreach ($result as $row) {
			$projectAmt = $projectAmt + $row['amount'];
		}
		return $projectAmt;
	}//end projectTotAmtF
	
	//Begin retProjAccountID - return inserted accounts 
	//?
	function retProjAccountID($memberID,$projectID){
		include('m_inc/def_value.php');
		include ('m_inc/m_config.php');
		$insertedAcctID = "0";
		$projAccountIDArr = array();
		$projAccountTransTypeArr = array();
		$projAccountAmtArr = array();
		$projAccountArrCt = 0;
		
		try{//get Budget ID
			$result = $db->prepare("SELECT account_id,transaction_type_id,amount FROM $db_transaction WHERE member_id=? AND project_id=?");
			$result->execute(array($memberID,$projectID));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; script>";
		}
		
		foreach ($result as $row){
			$projAccountIDArr[$projAccountArrCt] = $row['account_id'];
			$projAccountTransTypeArr[$projAccountArrCt] = $row['transaction_type_id'];
			$projAccountAmtArr[$projAccountArrCt] = $row['amount'];
			$projAccountArrCt++;
		}
		return array($projAccountIDArr,$projAccountTransTypeArr,$projAccountAmtArr);
	}//end retProjAccountID
	
}
///End of projectInfoC class //////////////////////////////////////////////////////

///Begin of generalInfoC class //////////////////////////////////////////////////////
class generalInfoC{
	var $name;
	function set_name($new_name) {
			$this->name = $new_name;
	}
	function get_name() {
			return $this->name;
	}

	//Begin getCategoryNameID - return list of categories
	//?
	function getCategoryNameID($memberID){
		include('m_inc/def_value.php');
		include ('m_inc/m_config.php');
		
		$categoryIDArr = array();
		$categoryNameArr = array();
		$yeVal = "yes";
		
		try{
			$result = $db->prepare("SELECT category_id,category FROM $db_category WHERE member_id=? AND active=? ORDER BY list_order ASC");
			$result->execute(array($memberID,$yeVal));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		$catItems = $result->rowCount(); 
		$arrCt = 0;
		if ($catItems > 0){
			foreach ($result as $row) {
				$categoryIDArr[$arrCt] = $row['category_id'];
				$categoryNameArr[$arrCt] = str_replace('\\','',$row['category']); 
				$arrCt += 1;
			}
		}
		return array($categoryIDArr, $categoryNameArr);
	}//end getCategoryNameID
	
	//Begin getCategoryDropdownListF - return dropdown category list for member 
	//mx009)
	function getCategoryDropdownListF($memberID){
		include('m_inc/def_value.php');
		include ('m_inc/m_config.php');
		//get category data
		$catItemsList = "";
		$yes = "yes";
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
		return $catItemsList;
	}//end getCategoryDropdownListF
	
	//Begin getTrkcategoryNameF - return trkcategory name 
	//mx009)
	function getTrkcategoryNameF($trkcategoryID){
		include('m_inc/def_value.php');
		include ('m_inc/m_config.php');
		//get category data
		$trkcatName = "";
		try{
			$result = $db->prepare("SELECT category FROM $db_trkcategory WHERE trkcategory_id=?");
			$result->execute(array($trkcategoryID));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; script>";
		} 
		foreach ($result as $row) {
			$trkcatName = str_replace('\\','',$row['category']);
		}
		return $trkcatName;
	}//end getTrkcategoryNameF
	
	//Begin getTrkcategoryTypeF - return trkcategory name 
	//mx009)
	function getTrkcategoryTypeF($trkcategoryID){ //Fix **************************
		include('m_inc/def_value.php');
		include ('m_inc/m_config.php');
		//get category data
		$trkcatType = "";
		try{
			$result = $db->prepare("SELECT type FROM $db_trkcategory WHERE trkcategory_id=?");
			$result->execute(array($trkcategoryID));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; script>";
		} 
		foreach ($result as $row) {
			$trkcatType = $row['type'];
		}
		return $trkcatType;
	}//end getTrkcategoryTypeF
	
	//Begin getSubCatCountF - return trkcategory name 
	//mx009)
	function getSubCatCountF($grpattendID){ //Fix **************************
		include('m_inc/def_value.php');
		include ('m_inc/m_config.php');
		//get category data
		$trkcatCt = 0;
		try{
			$result = $db->prepare("SELECT grpattend_sub_id FROM $db_grpattend_sub WHERE grpattend_id=?");
			$result->execute(array($grpattendID));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; script>";
		} 
		foreach ($result as $row) {
			$trkcatCt++;
		}
		return $trkcatCt;
	}//end getSubCatCountF
	
	//Begin getSubCatEdtF - return trkcategory name 
	//mx009)
	function getSubCatEdtF($grpattendID){
		include('m_inc/def_value.php');
		include ('m_inc/m_config.php');
		
		$generalInfo = new generalInfoC();
		
		//get category data
		$trkcatName = "";
		try{
			$result = $db->prepare("SELECT grpattend_id,trkcategory_id FROM $db_grpattend_sub WHERE grpattend_id=? ORDER BY grpattend_sub_id");
			$result->execute(array($grpattendID));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; script>";
		} 
		foreach ($result as $row) {
			$trkcatName = $trkcatName.'<span class="sim_formcontrol_ind">'.$generalInfo->getTrkcategoryNameF($row['trkcategory_id']).'</span>';
		}
		return $trkcatName;
	}//end getSubCatEdtF
	
	//Begin getSubCatValF - return trkcategory name 
	//mx009)
	function getSubCatValF($grpattendID,$type){
		include('m_inc/def_value.php');
		include ('m_inc/m_config.php');
		
		$budgetTotInst = new budgetInfoC();
		//get category data
		$trkcatName = "";
		try{
			$result = $db->prepare("SELECT grpattend_sub_id,trkcategory_id,value,count,text FROM $db_grpattend_sub WHERE grpattend_id=? ORDER BY grpattend_sub_id");
			$result->execute(array($grpattendID));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; script>";
		} 
		$itemCount = $result->rowCount();
		$subCatCt = 1;
		foreach ($result as $row) {
			if ($type == "mone"){
				$trkcatName = $trkcatName.'<input type="text" class="form-control" id="sub'.$grpattendID.'n'.$subCatCt.'" name="'.$row['grpattend_sub_id'].'" placeholder="$0.00" value="'.$budgetTotInst->convertDollarF($row['value']).'" onchange="isNumber_chksub(this.id,this.value,'.$itemCount.')">';
			}
			if ($type == "numb"){
				$trkcatName = $trkcatName.'<input type="text" class="form-control" id="sub'.$grpattendID.'n'.$subCatCt.'" name="'.$row['grpattend_sub_id'].'" placeholder="0" value="'.$row['count'].'" onchange="isNumberOnly_sub(this.id,this.value,'.$itemCount.')">';
			}
			if ($type == "text"){
				$trkcatName = $trkcatName.'<input type="text" class="form-control" id="sub'.$grpattendID.'n'.$subCatCt.'" name="'.$row['grpattend_sub_id'].'" value="'.str_replace('\\','',$row['text']).'">';
			}
			$subCatCt++;
		}
		return $trkcatName;
	}//end getSubCatValF
	
	//Begin getTrkcategoryDropdownListF - return dropdown category list for member 
	//mx009)
	function getTrkcategoryDropdownListF($memberID){
		include('m_inc/def_value.php');
		include ('m_inc/m_config.php');
		//get category data
		$catItemsList = "";
		$yes = "yes";
		try{
			$result = $db->prepare("SELECT trkcategory_id,category FROM $db_trkcategory WHERE member_id=? AND active=? ORDER BY category ASC");
			$result->execute(array($memberID,$yes));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; script>";
		} 
		$catItems = $result->rowCount(); 
		if ($catItems > 0){
			foreach ($result as $row) {
				$catItemsList = $catItemsList."<option value='".$row['trkcategory_id']."'>".str_replace('\\','',$row['category'])."</option>";
			}
		}
		return $catItemsList;
	}//end getTrkcategoryDropdownListF
	
	//Begin getCategoryDropdownListF - return dropdown group list with admin rights and track turn on
	//mx009)
	function getGrpDropdownListF($memberID,$groupID){
		include('m_inc/def_value.php');
		include ('m_inc/m_config.php');
		//get category data
		$grpTrack = "no";
		$yes = "yes";
		try{
			$result = $db->prepare("SELECT group_id,group_name FROM $db_group WHERE group_id=? AND track=? AND active=? ORDER BY group_name ASC");
			$result->execute(array($groupID,$yes,$yes));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; script>";
		} 
		$grpItems = $result->rowCount(); 
		if ($grpItems > 0){
			foreach ($result as $row) {
				$grpTrack = "yes";
				//$grpItemsList = $grpItemsList."<option value='".$row['group_id']."'>".$row['group_name']."</option>";
			}
		}
		return $grpTrack;
	}//end getGrpDropdownListF
	
	//Begin checkGrpAttendF - return dropdown group list with admin rights and track turn on
	//mx009)
	function checkGrpAttendF($groupID){
		include('m_inc/def_value.php');
		include ('m_inc/m_config.php');
		
		$grpItems = 0;
		try{
			$result = $db->prepare("SELECT member_id FROM $db_grpattend WHERE group_id=?");
			$result->execute(array($groupID));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; script>";
		} 
		$grpItems = $result->rowCount(); 
		
		return $grpItems;
	}//end checkGrpAttendF
	
	//Begin trackMemberGrpF - return member track name and date
	//mx009mt)
	function trackMemberGrpF($trkMemberID){
		include ('m_inc/m_config.php'); //mysql cridential
		include ('m_inc/def_value.php');
		try{//delete account in $db_transaction
			$result = $db->prepare("SELECT name,date FROM $db_trkmember WHERE trkmember_id=?");
			$result->execute(array($trkMemberID));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		foreach ($result as $row) {
			$trkMemberName = str_replace('\\','',$row['name']);
			$orgDate = $row['date'];
			$partsArr = explode("-",$orgDate);
			$date = $partsArr[1]."/".$partsArr[2]."/".$partsArr[0];//alter yyyy-mm-dd to mm/dd/yyyy 
		}
		return array($trkMemberName,$date);
	}//end trackMemberGrpF
	
	//Begin getSubCatF - return rows of subcategory 
	//mx004rp)
	function getSubCatF($grpattendID,$trkcategoryID){
		include ('m_inc/m_config.php'); //mysql cridential
		include ('m_inc/def_value.php');
		$generalInfo = new generalInfoC();
		$budgetTotInst = new budgetInfoC();
		
		$subCatRow = "";
		try{//
			$result = $db->prepare("SELECT trkcategory_id,value,count,text FROM $db_grpattend_sub WHERE grpattend_id=?");
			$result->execute(array($grpattendID));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		foreach ($result as $row) {
			if ($generalInfo->getTrkcategoryTypeF($trkcategoryID) == "mone"){
				$subCatRow = $subCatRow.'<tr><td></td><td>'.$generalInfo->getTrkcategoryNameF($row['trkcategory_id']).'</td><td>'.$budgetTotInst->convertDollarF($row['value']).'</td><td></td><td></td></tr>'; 
			}
			if ($generalInfo->getTrkcategoryTypeF($trkcategoryID) == "numb"){
				$subCatRow = $subCatRow.'<tr><td></td><td>'.$generalInfo->getTrkcategoryNameF($row['trkcategory_id']).'</td><td>'.$row['count'].'</td><td></td><td></td></tr>'; 
			}
			if ($generalInfo->getTrkcategoryTypeF($trkcategoryID) == "text"){
				$subCatRow = $subCatRow.'<tr><td></td><td>'.$generalInfo->getTrkcategoryNameF($row['trkcategory_id']).'</td><td>'.str_replace('\\','',$row['text']).'</td><td></td><td></td></tr>'; 
			}
		}
		return $subCatRow;
	}//end getSubCatF
	
	//Begin returnGroupNameF - return group name
	//?
	function returnGroupNameF($groupID){
		include ('m_inc/m_config.php'); //mysql cridential
		include ('m_inc/def_value.php');
		try{//delete account in $db_transaction
			$result = $db->prepare("SELECT group_name FROM $db_group WHERE group_id=?");
			$result->execute(array($groupID));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		foreach ($result as $row) {
			$groupName = str_replace('\\','',$row['group_name']);
		}
		return $groupName;
	}//end returnGroupNameF
	
	//Begin returnGroupsPerOrgF - return total number of groups per organization
	//?
	function returnGroupsPerOrgF($orgID){
		include ('m_inc/m_config.php'); //mysql cridential
		include ('m_inc/def_value.php');
		try{//delete account in $db_transaction
			$result = $db->prepare("SELECT group_id FROM $db_group WHERE organization_id=?");
			$result->execute(array($orgID));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		$itemCount = $result->rowCount();
		return $itemCount;
	}//end returnGroupsPerOrgF
	
	//Begin returnFiscalMonF - get fiscal starting month
	//?
	function returnFiscalMonF($orgID){
		include ('m_inc/m_config.php'); //mysql cridential
		include ('m_inc/def_value.php');
		try{//delete account in $db_transaction
			$result = $db->prepare("SELECT begin_fiscal_mon FROM $db_setting WHERE organization_id=?");
			$result->execute(array($orgID));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		foreach ($result as $row) {
			$fiscalStartMon = $row['begin_fiscal_mon'];
		}
		return $fiscalStartMon;
	}//end returnFiscalMonF
		
	//Begin returnOrgIdF - get orgID
	//?
	function returnOrgIdF($memberID){
		include ('m_inc/m_config.php'); //mysql cridential
		include ('m_inc/def_value.php');
		try{//get consortiumID
			$result = $db->prepare("SELECT consortium_id FROM $db_member WHERE member_id=?");
			$result->execute(array($memberID)); 
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		foreach ($result as $row){
			$consortID = $row['consortium_id'];
		}
		try{//get consortiumID
			$result = $db->prepare("SELECT organization_id FROM $db_organization WHERE consortium_id=?");
			$result->execute(array($consortID)); 
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		foreach ($result as $row){
			$orgID = $row['organization_id'];
		}
		return $orgID;
	}//end returnOrgIdF
	
	//Begin checkGroupTrack - check if group tracking is turned on.
	//mx009)
	function checkGroupTrack($trkmemberID){
		include ('m_inc/m_config.php'); //mysql cridential
		include ('m_inc/def_value.php');
		$trackYN = "no";
		try{
			$result = $db->prepare("SELECT $db_group.track FROM $db_trkmember,$db_group WHERE $db_trkmember.trkmember_id=? AND $db_trkmember.group_id=$db_group.group_id");
			$result->execute(array($trkmemberID)); 
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		foreach ($result as $row){
			$trackYN = $row['track'];
		}
		return $trackYN;
	}//end checkGroupTrack
	
	//Begin getCategoryNameOnly - return name only
	//mx009)	
	function getCategoryNameOnly($categoryID){
		//include ('../../../m_inc/m_config.php'); //mysql cridential
		//include ('../../../m_inc/def_value.php');
		include(dirname(__FILE__)."/../m_inc/def_value.php");
		include(dirname(__FILE__)."/../m_inc/m_config.php");
		
		$categoryName = "";
		$yes = "yes";
		
		try{
			$result = $db->prepare("SELECT category FROM $db_category WHERE category_id=? AND active=?");
			$result->execute(array($categoryID,$yes));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		foreach ($result as $row) {
			$categoryName = str_replace('\\','',$row['category']); 
		}
		return $categoryName;
	}//end getCategoryNameOnly
	
	//Begin createRecurringDate - return recurring dates
	//?
	function createRecurringDate($transactionDate,$recurringType,$recurringNum){
		include ('m_inc/m_config.php'); //mysql cridential
		include ('m_inc/def_value.php');
		$transDateArr = array();
		$balDateArr = array();
		
		$transDateArr[0] = $transactionDate;  //start the recurring date
		if ($recurringType=="Daily"){
			for ($i=1; $i<$recurringNum; $i++){
				$transDateArr[$i] = date( 'Y-m-j' , strtotime("+1 day",strtotime($transDateArr[$i-1])));
			}
		}
		if ($recurringType=="Weekly"){
			for ($i=1; $i<$recurringNum; $i++){
				$transDateArr[$i] = date( 'Y-m-j' , strtotime("+7 day",strtotime($transDateArr[$i-1])));
			}
		}
		if ($recurringType=="Bi-weekly"){
			for ($i=1; $i<$recurringNum; $i++){
				$transDateArr[$i] = date( 'Y-m-j' , strtotime("+14 day",strtotime($transDateArr[$i-1])));
				$balDateArr[$i]= "";
			}
		}
		if ($recurringType=="Monthly"){
			for ($i=1; $i<$recurringNum; $i++){
				$transDateArr[$i] = date( 'Y-m-j' , strtotime("+1 month",strtotime($transDateArr[$i-1])));
			}
		}
		if ($recurringType=="Quarterly"){
			for ($i=1; $i<$recurringNum; $i++){
				$transDateArr[$i] = date( 'Y-m-j' , strtotime("+3 month",strtotime($transDateArr[$i-1])));
			}
		}
		if ($recurringType=="Yearly"){
			for ($i=1; $i<$recurringNum; $i++){
				$transDateArr[$i] = date( 'Y-m-j' , strtotime("+1 year",strtotime($transDateArr[$i-1])));
			}
		}
		return $transDateArr;
	}//end createRecurringDate
	
	//Begin createRecurringDate - return recurring dates
	//?
	function createRecurringDateBudF($transactionDate,$recurringType,$recurringNum){
		include ('../../../m_inc/m_config.php'); //mysql cridential
		include ('../../../m_inc/def_value.php');
		$transDateArr = array();
		$balDateArr = array();
		
		$transDateArr[0] = $transactionDate;  //start the recurring date
		if ($recurringType=="Daily"){
			for ($i=1; $i<$recurringNum; $i++){
				$transDateArr[$i] = date( 'Y-m-j' , strtotime("+1 day",strtotime($transDateArr[$i-1])));
			}
		}
		if ($recurringType=="Weekly"){
			for ($i=1; $i<$recurringNum; $i++){
				$transDateArr[$i] = date( 'Y-m-j' , strtotime("+7 day",strtotime($transDateArr[$i-1])));
			}
		}
		if ($recurringType=="Bi-weekly"){
			for ($i=1; $i<$recurringNum; $i++){
				$transDateArr[$i] = date( 'Y-m-j' , strtotime("+14 day",strtotime($transDateArr[$i-1])));
				$balDateArr[$i]= "";
			}
		}
		if ($recurringType=="Monthly"){
			for ($i=1; $i<$recurringNum; $i++){
				$transDateArr[$i] = date( 'Y-m-j' , strtotime("+1 month",strtotime($transDateArr[$i-1])));
			}
		}
		if ($recurringType=="Quarterly"){
			for ($i=1; $i<$recurringNum; $i++){
				$transDateArr[$i] = date( 'Y-m-j' , strtotime("+3 month",strtotime($transDateArr[$i-1])));
			}
		}
		if ($recurringType=="Yearly"){
			for ($i=1; $i<$recurringNum; $i++){
				$transDateArr[$i] = date( 'Y-m-j' , strtotime("+1 year",strtotime($transDateArr[$i-1])));
			}
		}
		return $transDateArr;
	}//end createRecurringDate
	
	//Begin getRecurringID - create recurring start dates
	//?
	function getRecurringID($recurringType,$recurringNum,$active){
		include ('m_inc/m_config.php'); //mysql cridential
		include ('m_inc/def_value.php');
		$tempTransAcctId = date('m').date('d').date('H').date('i').date('s').rand(100000, 999999);
		try{//insert recurring info to $db_recurring table
			$result = $db->prepare("INSERT INTO $db_recurring(recurring_type,no_of_recurring,reference_number,active) VALUES (?,?,?,?)");
			$result->execute(array($recurringType,$recurringNum,$tempTransAcctId,$active));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		try{//get recurring id from $db_recurring
			$result = $db->prepare("SELECT recurring_id FROM $db_recurring WHERE reference_number=? ORDER BY recurring_id ASC");
			$result->execute(array($tempTransAcctId));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		foreach ($result as $row){
			$recurringID = $row['recurring_id'];
		}
		try{//update reference number to empty
			$result = $db->prepare("UPDATE $db_recurring SET reference_number='' WHERE recurring_id=? AND reference_number=?");
			$result->execute(array($recurringID,$tempTransAcctId));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		return $recurringID;
	}//end getRecurringID
	
	//Begin getRecurringID - create recurring start dates
	//?
	function getRecurringBudIDF($recurringType,$recurringNum,$active){
		include ('m_inc/m_config.php'); //mysql cridential
		include ('m_inc/def_value.php');
		$tempTransAcctId = date('m').date('d').date('H').date('i').date('s').rand(100000, 999999);
		try{//insert recurring info to $db_recurring table
			$result = $db->prepare("INSERT INTO $db_recurring(recurring_type,no_of_recurring,reference_number,active) VALUES (?,?,?,?)");
			$result->execute(array($recurringType,$recurringNum,$tempTransAcctId,$active));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		try{//get recurring id from $db_recurring
			$result = $db->prepare("SELECT recurring_id FROM $db_recurring WHERE reference_number=? ORDER BY recurring_id ASC");
			$result->execute(array($tempTransAcctId));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		foreach ($result as $row){
			$recurringID = $row['recurring_id'];
		}
		try{//update reference number to empty
			$result = $db->prepare("UPDATE $db_recurring SET reference_number='' WHERE recurring_id=? AND reference_number=?");
			$result->execute(array($recurringID,$tempTransAcctId));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		return $recurringID;
	}//end getRecurringID
	
	//Begin getGroupSetIDArr - create multiple groupSetID in recurring situation
	//?
	function getGroupSetIDArr($recurringID,$recurringNum,$amount,$active){
		include ('m_inc/m_config.php'); //mysql cridential
		include ('m_inc/def_value.php');
		$groupSetIdArr = array();
		for ($i=0; $i<$recurringNum; $i++){
			$tempTransAcctId = date('m').date('d').date('H').date('i').date('s').rand(100000, 999999);
			try{//insert recurring group id
				$result = $db->prepare("INSERT INTO $db_recurring_group(recurring_id,main_amount,reference_number,active) VALUES (?,?,?,?)");
				$result->execute(array($recurringID,$amount,$tempTransAcctId,$active));
			} catch(PDOException $e) {
				echo "message001 - Sorry, system is experincing problem. Please check back.";
			}
			try{//get recurring id from $db_recurring
				$result = $db->prepare("SELECT recurring_group_id FROM $db_recurring_group WHERE reference_number=? ORDER BY recurring_group_id ASC");
				$result->execute(array($tempTransAcctId));
			} catch(PDOException $e) {
				echo "message001 - Sorry, system is experincing problem. Please check back.";
			}
			foreach ($result as $row){
				$groupSetIdArr[$i] = $row['recurring_group_id'];
			}
			try{//update reference number to empty
				$result = $db->prepare("UPDATE $db_recurring_group SET reference_number='' WHERE reference_number=?");
				$result->execute(array($tempTransAcctId));
			} catch(PDOException $e) {
				echo "message001 - Sorry, system is experincing problem. Please check back.";
			}	
		}//end for looop
		return $groupSetIdArr;
	}//end getGroupSetIDArr
	
	//Begin getGroupSetIDArr - create multiple groupSetID in recurring situation
	//?
	function getGroupSetIDBudArrF($recurringID,$recurringNum,$amount,$active){
		include ('../../../m_inc/m_config.php'); //mysql cridential
		include ('../../../m_inc/def_value.php');
		$groupSetIdArr = array();
		for ($i=0; $i<$recurringNum; $i++){
			$tempTransAcctId = date('m').date('d').date('H').date('i').date('s').rand(100000, 999999);
			try{//insert recurring group id
				$result = $db->prepare("INSERT INTO $db_recurring_group(recurring_id,main_amount,reference_number,active) VALUES (?,?,?,?)");
				$result->execute(array($recurringID,$amount,$tempTransAcctId,$active));
			} catch(PDOException $e) {
				echo "message001 - Sorry, system is experincing problem. Please check back.";
			}
			try{//get recurring id from $db_recurring
				$result = $db->prepare("SELECT recurring_group_id FROM $db_recurring_group WHERE reference_number=? ORDER BY recurring_group_id ASC");
				$result->execute(array($tempTransAcctId));
			} catch(PDOException $e) {
				echo "message001 - Sorry, system is experincing problem. Please check back.";
			}
			foreach ($result as $row){
				$groupSetIdArr[$i] = $row['recurring_group_id'];
			}
			try{//update reference number to empty
				$result = $db->prepare("UPDATE $db_recurring_group SET reference_number='' WHERE reference_number=?");
				$result->execute(array($tempTransAcctId));
			} catch(PDOException $e) {
				echo "message001 - Sorry, system is experincing problem. Please check back.";
			}	
		}//end for looop
		return $groupSetIdArr;
	}//end getGroupSetIDArr
	
	//Begin getGroupSetID - create single groupSetID - for recurring
	//?
	function getGroupSetID($recurringID,$amount,$active){
		include ('m_inc/m_config.php'); //mysql cridential
		include ('m_inc/def_value.php');
		$tempTransAcctId = date('m').date('d').date('H').date('i').date('s').rand(100000, 999999);
		try{//insert recurring group id
			$result = $db->prepare("INSERT INTO $db_recurring_group(recurring_id,main_amount,reference_number,active) VALUES (?,?,?,?)");
			$result->execute(array($recurringID,$amount,$tempTransAcctId,$active));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		try{//get recurring id from $db_recurring
			$result = $db->prepare("SELECT recurring_group_id FROM $db_recurring_group WHERE reference_number=? ORDER BY recurring_group_id ASC");
			$result->execute(array($tempTransAcctId));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		foreach ($result as $row){
			$groupSetID = $row['recurring_group_id'];
		}
		try{//update reference number to empty
			$result = $db->prepare("UPDATE $db_recurring_group SET reference_number='' WHERE reference_number=?");
			$result->execute(array($tempTransAcctId));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		return $groupSetID;
	}//end getGroupSetID
	
	//Begin getGroupSetID - create single groupSetID - for recurring
	//?
	function getGroupSetIDBudF($recurringID,$amount,$active){
		include ('../../../m_inc/m_config.php'); //mysql cridential
		include ('../../../m_inc/def_value.php');
		$tempTransAcctId = date('m').date('d').date('H').date('i').date('s').rand(100000, 999999);
		try{//insert recurring group id
			$result = $db->prepare("INSERT INTO $db_recurring_group(recurring_id,main_amount,reference_number,active) VALUES (?,?,?,?)");
			$result->execute(array($recurringID,$amount,$tempTransAcctId,$active));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		try{//get recurring id from $db_recurring
			$result = $db->prepare("SELECT recurring_group_id FROM $db_recurring_group WHERE reference_number=? ORDER BY recurring_group_id ASC");
			$result->execute(array($tempTransAcctId));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		foreach ($result as $row){
			$groupSetID = $row['recurring_group_id'];
		}
		try{//update reference number to empty
			$result = $db->prepare("UPDATE $db_recurring_group SET reference_number='' WHERE reference_number=?");
			$result->execute(array($tempTransAcctId));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		return $groupSetID;
	}//end getGroupSetID
	
	//Begin delNoMoreGroup
	//?
	function delNoMoreGroup($groupSetID){
		include ('../../../m_inc/m_config.php'); //mysql cridential
		include ('../../../m_inc/def_value.php');
		try{//delete account in $db_transaction
			$result = $db->prepare("DELETE FROM $db_recurring_group WHERE recurring_group_id=?");
			$result->execute(array($groupSetID));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
	}//end delNoMoreGroup
	
	//Begin returnGroupSetID
	//?
	function returnGroupSetID($budTransID){
		include ('../../../m_inc/m_config.php'); //mysql cridential
		include ('../../../m_inc/def_value.php');
		try{//delete account in $db_transaction
			$result = $db->prepare("SELECT group_set_id FROM $db_budtransaction WHERE budtransaction_id=?");
			$result->execute(array($budTransID));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		foreach ($result as $row) {
			$groupSetID = $row['group_set_id'];
		}
		return $groupSetID;
	}//end returnGroupSetID
	
	//Begin updateMainGroupSetID - newly inserted account, need to establish groupSetID main_trans_id and main_amount
	//?
	function updateMainGroupSetID($groupSetID,$budTransID,$amount){
		include ('../../../m_inc/m_config.php'); //mysql cridential
		include ('../../../m_inc/def_value.php');
		
		try{//update reference number to empty
			$result = $db->prepare("UPDATE $db_recurring_group SET main_amount=?,main_trans_id=? WHERE recurring_group_id=?");
			$result->execute(array($amount,$budTransID,$groupSetID));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
	}//end updateMainGroupSetID
	
	//addTrkmemberRecordF - add a member tracking record into transaction table
	//addmemtracking)max009)
	function addTrkmemberRecordF($memberID,$trkmemberID,$pathLink){
		$accountInfo = new accountInfoC();
		if ($pathLink == "nonlog"){
			include ('../../../m_inc/m_config.php'); //mysql cridential
			include ('../../../m_inc/def_value.php');
		}else{
			include ('m_inc/m_config.php'); //mysql cridential
			include ('m_inc/def_value.php');
		}
		
		$totAmount = 0;
		try{ //get total amount for this member tracking event			
			$result = $db->prepare("SELECT value FROM $db_trkmember_detail WHERE trkmember_id=?");
			$result->execute(array($trkmemberID));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";//echo $e->getMessage();
		}
		foreach ($result as $row) {
			$totAmount = $totAmount + $row['value'];
		}
		if ($totAmount > 0){
			try{					
				$result = $db->prepare("SELECT name,category_id,account_id,budget_id,note,date FROM $db_trkmember WHERE trkmember_id=?");
				$result->execute(array($trkmemberID));
			} catch(PDOException $e) {
				print "<script> self.location='".$index_url."?err=d1000'; </script>";//echo $e->getMessage();
			}
			foreach ($result as $row){
				$acctId = $row['account_id'];
				$memberID = $memberID;
				$transactionDate = $row['date'];
				$posted = "no";
				$transOrderCounter = 1;
				$acctAmtTemp = $totAmount;
				$acctTypeTrans = "1000";
				$categoryID = $row['category_id'];
				$note = $row['name'];
				$referenceNumber = "";
				$budgetID = 0; //$row['budget_id']
				$budgetTypeID = 0;
				$projectID = 0;
				$recurringID = 0;
				$groupSetID = 0;
				$active = "yes";
				
			}
			//if trkmember is already in transaction table, do update
			//if trkmember is not in transaxtion table, insert new record
			$itemCount = 0;
			try{ //get total amount for this member tracking event			
				$result = $db->prepare("SELECT transaction_id FROM $db_transaction WHERE trkmember_id=?");
				$result->execute(array($trkmemberID));
			} catch(PDOException $e) {
				print "<script> self.location='".$index_url."?err=d1000'; </script>";//echo $e->getMessage();
			}
			$itemCount = $result->rowCount();
			if ($itemCount == 0){
				if ($pathLink != "nonlog"){
					//$trkmemberID = "w".$trkmemberID;
					$trkmemberID = $trkmemberID;
				}
				$budgerannsetID = 0;
				$tagID = 0;
				if ($pathLink == "nonlog"){
					$accountInfo->insertAcctTransBudF($acctId,$memberID,$transactionDate,$posted,$transOrderCounter,$acctAmtTemp,$acctTypeTrans,$categoryID,$note,$referenceNumber,$budgetID,$budgetTypeID,$projectID,$recurringID,$groupSetID,$trkmemberID,$budgerannsetID,$tagID,$active);
				}else{
					$accountInfo->insertAcctTrans($acctId,$memberID,$transactionDate,$posted,$transOrderCounter,$acctAmtTemp,$acctTypeTrans,$categoryID,$note,$referenceNumber,$budgetID,$budgetTypeID,$projectID,$recurringID,$groupSetID,$trkmemberID,$budgerannsetID,$tagID,$active);
				}
			}else{
				try{//update trkmember in tranasction table
					$result = $db->prepare("UPDATE $db_transaction SET transaction_date=?,amount=?,category_id=?,account_id=?,description=? WHERE trkmember_id=?");
					$result->execute(array($transactionDate,$totAmount,$categoryID,$acctId,$note,$trkmemberID));
				} catch(PDOException $e) {
					print "<script> self.location='".$index_url."?err=d1000'; </script>";
				}
			}
		}
		//return $totAmount;
	}//end of addTrkmemberRecordF
	
}
///End of generalInfoC class //////////////////////////////////////////////////////

//begin old classes*************************************************************************************
////generate code for unquie pages and public session indentifier////
function get_link_code(){
	$len = 19;
	$base='ABCDEFGHKLMNOPQRSTWXYZabcdefghjkmnpqrstwxyz123456789';
	$max=strlen($base)-1;
	$pa_code='';
	mt_srand((double)microtime()*1000000);
	while (strlen($pa_code)<$len+1)
	$pa_code.=$base{mt_rand(0,$max)};
	return $pa_code;
}
////create $ sign formatting for number////
function moneyFormat($number, $currencySymbol = '$',$decPoint = '.', $thousandsSep = ',', $decimals = 2) {
	return $currencySymbol . number_format($number, $decimals,$decPoint, $thousandsSep);
}

//progress-bar
function progress_bar($percentage, $value, $color){
	$prog_bar_result = "<div id='progress-bar'>";
	$prog_bar_result = $prog_bar_result."<div id='progress-bar-percentage' style='width:".$percentage."%; background:".$color."'>";
	//if ($percentage > 5) 
	//	{
		$prog_bar_result = $prog_bar_result."".moneyFormat($value);
	//	} 
	//else 
	//	{$prog_bar_result = $prog_bar_result."<div class='progress-spacer'>&nbsp;</div>";}
	$prog_bar_result = $prog_bar_result."</div></div>";
	return $prog_bar_result;
}

//sort
function subval_sort($a,$subkey) {
	foreach($a as $k=>$v) {
		$b[$k] = strtolower($v[$subkey]);
	}
	asort($b);
	foreach($b as $key=>$val) {
		$c[] = $a[$key];
	}
	return $c;
}

//////get image extension
function getExtension($str) {
	$i = strrpos($str,".");
    if (!$i) { return ""; } 
    $l = strlen($str) - $i;
    $ext = substr($str,$i+1,$l);
    return $ext;
}

//end old classes*************************************************************************************
?>