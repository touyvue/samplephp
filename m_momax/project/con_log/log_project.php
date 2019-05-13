<?php
	if ($_SESSION['login']=="yes" and $_SESSION['log_ss'] != ""){
		$memberID = $_SESSION['mid'];
		$projectInfo = new projectInfoC();
		$budgetTotInst = new budgetInfoC();
		$memberTotInst = new memberInfoC();
		$accountInfo = new accountInfoC();
		$generalInfo = new generalInfoC();
		
		if ($_GET['cy']==""){
			$currentYr = date(Y);
			$currentMonL = date(F);
			$currentMon = date(m);	
		}else{
			$currentYr = $_GET['cy'];
			$currentMonL = date(F);
			$currentMon = date(m);
		}
				
		//Get mybudget list
		$projectID = $_GET['pid'];
		$yes = "yes";
		
		//project 
		$projectNameID = array();
		$projectNameID = $projectInfo->projectNameIDF($projectID);
		
		if ($_GET['smid'] != ""){
			$myBudgetOverview = $memberTotInst->memberNameF($_GET['smid'])." : ".$projectNameID[0];
			$sharedMemberID = $_GET['smid'];
		}else{
			$myBudgetOverview = $projectNameID[0];
		}
		
		if ($_GET['smid'] == ""){
			//get all my projects
			$projectIDArr = array();
			$projectNameArr = array();
			$projectDateArr = array();
			$projectCt = 0;
			$itemCount = 0;
			$itemTotAmt = 0;
			$itemList = "";
			$itemListTot = "";

			try{					
				$result = $db->prepare("SELECT pdetail_id,item,level_one,amount,completed,budgetlist_id,note FROM $db_project_detail WHERE project_id=? ORDER BY level_one");
				$result->execute(array($projectID));
			} catch(PDOException $e) {
				print "<script> self.location='".$index_url."?err=d1000'; </script>";
			}
			$itemCount = $result->rowCount();
			$itemValSp = "''";
			$itemAmtSp = "0";
			$itemNoteSp = "''";
			$itemCompleted = 0; //not completed
			if ($itemCount > 0){
				foreach ($result as $row) {
					$pCompleted = "no";
					$projectIDArr[$projectCt] = $row['project_id'];
					$projectNameArr[$projectCt] = str_replace('\\','',$row['name']);
					$projectCt++;
					$itemVal = "'".$row['item']."'";
					$itemCompleted = 0; //not completed
					if ($row['completed'] == "yes"){
						$itemCompleted = 1; //yes completed
						$pCompleted = "yes";
					}
					if ($row['budgetlist_id'] == "" or $row['budgetlist_id'] == "NULL" or $row['budgetlist_id'] == 0){
						$budgetlistID = 0;
					}else{
						$budgetlistID = $row['budgetlist_id'];
					}
					
					$itemNote = "'".str_replace('\\','',$row['note'])."'";
					$itemList = $itemList.'<tr><td><a href="#" onclick="turnOnTxtBox('.$row['pdetail_id'].','.$itemVal.','.$row['amount'].','.$itemNote.',2,'.$itemCompleted.','.$budgetlistID.')">'.str_replace('\\','',$row['item']).'</td>';
					$itemList = $itemList."<td>".$budgetTotInst->convertDollarF($row['amount'])."</td>";
					$itemList = $itemList."<td>".$pCompleted."</td>";
					$itemList = $itemList.'<td><button class="btn btn-xs btn-warning" onclick="turnOnTxtBox('.$row['pdetail_id'].','.$itemVal.','.$row['amount'].','.$itemNote.',2,'.$itemCompleted.','.$budgetlistID.')"><i class="fa fa-pencil"></i></button>';
					$itemList = $itemList.'<button class="btn btn-xs btn-danger" onclick="turnOnTxtBox('.$row['pdetail_id'].','.$itemVal.','.$row['amount'].','.$itemNote.',3,'.$itemCompleted.','.$budgetlistID.')"><i class="fa fa-times"></i> </button>';
					$itemList = $itemList."</td></tr>";
					$itemTotAmt = $itemTotAmt + $row['amount'];
				}
				$projectTotAmt = $budgetTotInst->convertDollarF($itemTotAmt);
				$itemListTot = $itemListTot."<tr><th>Total</th><th>".$budgetTotInst->convertDollarF($itemTotAmt)."</th><th></th>";
				$itemListTot = $itemListTot.'<th><button onclick="turnOnTxtBox('.$row['pdetail_id'].','.$itemValSp.','.$itemAmtSp.','.$itemNoteSp.',1,0,0)" class="btn btn-xs btn-success">add item</button></th></tr>';
			}else{
				$itemListTot = $itemListTot."<tr><th>Total</th><th>$0.00</th><th></th>";
				$itemListTot = $itemListTot.'<th><button onclick="turnOnTxtBox(0,'.$itemValSp.','.$itemAmtSp.','.$itemNoteSp.',1,0,0)" class="btn btn-xs btn-success">add item</button></th></tr>';
			}
		}
		
		if ($_GET['smid'] != ""){
			//get all my projects
			$projectIDArr = array();
			$projectNameArr = array();
			$projectDateArr = array();
			$projectCt = 0;
			$itemCount = 0;
			$itemTotAmt = 0;
			$itemList = "";
			$itemListTot = "";
			
			try{					
				$result = $db->prepare("SELECT access_rights FROM $db_project_rights WHERE member_id=? AND project_id=?");
				$result->execute(array($memberID,$projectID));
			} catch(PDOException $e) {
				print "<script> self.location='".$index_url."?err=d1000'; </script>";
			}
			foreach ($result as $row) {
				$projectRights = $row['access_rights'];
			}

			try{					
				$result = $db->prepare("SELECT pdetail_id,item,level_one,amount,completed,note FROM $db_project_detail WHERE project_id=? ORDER BY level_one");
				$result->execute(array($projectID));
			} catch(PDOException $e) {
				print "<script> self.location='".$index_url."?err=d1000'; </script>";
			}
			$itemCount = $result->rowCount();
			$itemValSp = "''";	
			$itemAmtSp = "0";
			$itemNoteSp = "''";
			
			if ($itemCount > 0){
				foreach ($result as $row) {
					$pCompleted = "no";
					$itemCompleted = 0; //not completed
					$projectIDArr[$projectCt] = $row['project_id'];
					$projectNameArr[$projectCt] = str_replace('\\','',$row['name']);
					$projectCt++;
					$itemVal = "'".str_replace('\\','',$row['item'])."'";
					$itemCompleted = 0; //not completed
					if ($row['completed'] == "yes"){
						$itemCompleted = 1; //yes completed
						$pCompleted = "yes";
					}
					$itemNote = "'".str_replace('\\','',$row['note'])."'";
					$itemList = $itemList."<tr><td>".str_replace('\\','',$row['item'])."</td>";
					$itemList = $itemList."<td>".$budgetTotInst->convertDollarF($row['amount'])."</td>";
					$itemList = $itemList."<td>".$pCompleted."</td><td>";
					if ($projectRights == 2){
						$itemList = $itemList.'<button class="btn btn-xs btn-warning" onclick="turnOnTxtBox('.$row['pdetail_id'].','.$itemVal.','.$row['amount'].','.$itemNote.',2,'.$itemCompleted.',0)"><i class="fa fa-pencil"></i></button>';
					}
					if ($projectRights == 3){
						$itemList = $itemList.'<button class="btn btn-xs btn-warning" onclick="turnOnTxtBox('.$row['pdetail_id'].','.$itemVal.','.$row['amount'].','.$itemNote.',2,'.$itemCompleted.',0)"><i class="fa fa-pencil"></i></button>';
						$itemList = $itemList.'<button class="btn btn-xs btn-danger" onclick="turnOnTxtBox('.$row['pdetail_id'].','.$itemVal.','.$row['amount'].','.$itemNote.',3,'.$itemCompleted.',0)"><i class="fa fa-times"></i> </button>';
					}
					$itemList = $itemList."</td></tr>";
					$itemTotAmt = $itemTotAmt + $row['amount'];
				}
				
				$projectTotAmt = $budgetTotInst->convertDollarF($itemTotAmt);
				$itemListTot = $itemListTot."<tr><th>Total</th><th>".$projectTotAmt."</th><th></th>";
				if ($projectRights == 3){
					$itemListTot = $itemListTot.'<th><button onclick="turnOnTxtBox('.$row['pdetail_id'].','.$itemValSp.','.$itemAmtSp.','.$itemNoteSp.',1,0,0)" class="btn btn-xs btn-success">add item</button></th></tr>';
				}else {
					$itemListTot = $itemListTot.'<th></th></tr>';
				}
			}else{
				$itemList = '<tr><td colspan="4">No project items available</td></tr>';
				$itemListTot = $itemListTot."<tr><th>Total</th><th>$0.00</th><th></th>";
				if ($projectRights == 3){
					$itemListTot = $itemListTot.'<th><button onclick="turnOnTxtBox(0,'.$itemValSp.','.$itemAmtSp.','.$itemNoteSp.',1,0,0)" class="btn btn-xs btn-success">add item</button></th></tr>';
				}else {
					$itemListTot = $itemListTot.'<th></th></tr>';
				}
			}
		}
		
		//create budget dropdown list
		$projBudgetArr = array();
		$budgetList = "";
		if ($_GET['smid'] != ""){
			$memberID = $_GET['smid'];
		}
		try{					
			$result = $db->prepare("SELECT budget_id, name FROM $db_budget WHERE member_id=? AND active=?");
			$result->execute(array($memberID,$yes));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		$acctItems = $result->rowCount();
		$projBudgetID = $budgetTotInst->retProjBudgetIDF($memberID,$projectID); //get inserted budget info
		$projBudgetArr = $budgetTotInst->budgetNameF($projBudgetID); 
		
		if ($acctItems > 0){
			foreach ($result as $row) {
				if ($projBudgetID == $row['budget_id']){
					$budgetList = $budgetList."<option selected value='".$row['budget_id']."'>".str_replace('\\','',$row['name'])."</option>";
				}else{
					$budgetList = $budgetList."<option value='".$row['budget_id']."'>".str_replace('\\','',$row['name'])."</option>";
				}
			}
		}
		
		//create avaialable account checkboxes based on active memberID
		$projAccountIDArr = array();
		$projAccountTransTypeArr = array();
		$projAccountAmtArr = array();
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
			//get inserted accounts from transactions
			list($projAccountIDArr,$projAccountTransTypeArr,$projAccountAmtArr) = $projectInfo->retProjAccountID($memberID,$projectID);
			$projAccountFound = count($projAccountIDArr);
			
			$result->execute(array($memberID,$yes));
			$acctItems = $result->rowCount();
			
			foreach ($result as $row) {
				$insertedFound = "no";
				for ($i = 0; $i < $projAccountFound; $i++){
					if ($projAccountIDArr[$i] == $row['account_id']){
						$acctItemsList = $acctItemsList."<label class='checkbox-inline'><input type='checkbox' checked='checked' id='at".$row['account_id']."' value='".$row['account_id']."' onclick=turn_credit_debit(".$row['account_id'].",".$acctItems.",'".$acctNumAll."')>".str_replace('\\','',$row['name'])."</label><div class='radio'><input type='text' size='4' class='smtextbox_div' id='da".$row['account_id']."' value='".$budgetTotInst->convertDollarF($projAccountAmtArr[$i])."' onchange='isNumber_amtina(this.id,this.value)'>&nbsp;";
						if ($projAccountTransTypeArr[$i]=="1000"){
							$acctItemsList = $acctItemsList."<label><input type='radio' name='cd".$row['account_id']."' id='d".$row['account_id']."' value='1001'>Debit</label>&nbsp;&nbsp;<label><input type='radio' checked='checked' name='cd".$row['account_id']."' id='c".$row['account_id']."' value='1000'>Credit</label></div>";
						}else{
							$acctItemsList = $acctItemsList."<label><input type='radio' checked='checked' name='cd".$row['account_id']."' id='d".$row['account_id']."' value='1001'>Debit</label>&nbsp;&nbsp;<label><input type='radio' name='cd".$row['account_id']."' id='c".$row['account_id']."' value='1000'>Credit</label></div>";
						}
						$insertedFound = "yes";
					}
				}
				if ($insertedFound == "no"){
					$acctItemsList = $acctItemsList."<label class='checkbox-inline'><input type='checkbox' id='at".$row['account_id']."' value='".$row['account_id']."' onclick=turn_credit_debit(".$row['account_id'].",".$acctItems.",'".$acctNumAll."')>".str_replace('\\','',$row['name'])."</label><div class='radio'><input type='text' size='4' disabled='true' class='smtextbox_div' id='da".$row['account_id']."' placeholder='$0.00' onchange='isNumber_amtina(this.id,this.value)'>&nbsp;<label><input type='radio' disabled='true' name='cd".$row['account_id']."' id='d".$row['account_id']."' value='1001'>Debit</label>&nbsp;&nbsp;<label><input type='radio' disabled='true' name='cd".$row['account_id']."' id='c".$row['account_id']."' value='1000'>Credit</label></div>";
				}
			}
		}
		if ($acctNumAllCt == 0){
			$acctItemsList = "No accounts avaiable";
		}
		
		
		
		$selectedMonth = date("m");
		$selectedYear = date("Y");
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
		try{					
			$result = $db->prepare("SELECT budgetlist_id,name,budget_id FROM $db_budgetlist WHERE member_id=? AND (startdate>=? and startdate<=?) and (enddate>=? and enddate<=?) AND active=? ORDER BY list_order ASC");
			$result->execute(array($memberID,$strDateFiscalYear,$endDateFiscalYear,$strDateSelectedMonth,$endDateFiscalYear,$yes));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		$budgetAnnList = "";
		$budgetlistMain = "";
		$budgetListOffer = "";
		$budgetListOfferName = "";
		$budgetListOfferCt = 0;
		
		foreach ($result as $row) {
			$budgetAnnList = $budgetAnnList.'<option value="'.$row['budgetlist_id'].'">'.str_replace('\\','',$row['name']).'</option>';
			if ($projectNameID[4] == $row['budgetlist_id']){
				$budgetlistMain = $budgetlistMain.'<option value="'.$row['budgetlist_id'].'" selected>'.str_replace('\\','',$row['name']).'</option>';
			}else{
				$budgetlistMain = $budgetlistMain.'<option value="'.$row['budgetlist_id'].'">'.str_replace('\\','',$row['name']).'</option>';
			}
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
		
	}else{  //if statement - not login
		print "<script> self.location='".$index_url."'; </script>";
	}
?>