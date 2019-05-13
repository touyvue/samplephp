<?php
	if ($_SESSION['login']=="yes" and $_SESSION['log_ss'] != ""){
		$memberID = $_SESSION['mid'];
		$projectInfo = new projectInfoC();
		$budgetTotInst = new budgetInfoC();
		$memberTotInst = new memberInfoC();
		$budgetSetting = "Set Specific Budget (Annually, Monthly or Weekly";
		$yes = "yes";
		
		$budgetList = "";
		$budgetListAdd = "";
		$budgetNameOwner = array();
		try{//create budget list				
			$result = $db->prepare("SELECT budgetlist_id,budget_id,name,list_order,amount,date,description,active FROM $db_budgetlist WHERE member_id=? ORDER BY list_order ASC");
			$result->execute(array($memberID));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		$itemCount = $result->rowCount();
		if ($itemCount > 0){
			foreach ($result as $row){
				$orgDate = $row['date'];
				$partsArr = explode("-",$orgDate);
				$budgetDate = $partsArr[1]."/".$partsArr[2]."/".$partsArr[0];
				$budgetList = $budgetList."<tr><td align='center'><span id='olist_view".$row['budgetlist_id']."' style='display: ;'><a href='#' onclick='editacctlist(1,".$row['budgetlist_id'].",".$row['list_order'].",".$memberID.")'>".$row['list_order']."</a></span><span id='olist_edit".$row['budgetlist_id']."' style='display: none ;'><input size='2' value='".$row['list_order']."' class='form-control' type='text' name='od".$row['budgetlist_id']."' id='od".$row['budgetlist_id']."' onchange='editacctlist(2,".$row['budgetlist_id'].",".$row['list_order'].",".$memberID.")' /></span></td>";
				$budgetList = $budgetList."<td><a href='#' onclick='uptBudgetInfo(".$row['budgetlist_id'].")'>".str_replace('\\','',$row['name'])."</a></td>";
				$budgetList = $budgetList."<td>".$budgetTotInst->convertDollarF($row['amount'])."</td><td>".$budgetDate."</td>";
				$budgetNameOwner = $budgetTotInst->budgetNameF($row['budget_id']);
				$budgetList = $budgetList."<td>".$budgetNameOwner[0]."</td>";
				if ($row['active']=="yes"){
					$budgetList = $budgetList.'<td>Yes</td>';
				}else{
					$budgetList = $budgetList.'<td>No</td>';				}
				$budgetList = $budgetList."<td nowrap><a href='#' onclick='uptBudgetInfo(".$row['budgetlist_id'].")'><button class='btn btn-xs btn-warning'><i class='fa fa-pencil'></i></button></a>";
				$budgetList = $budgetList."<a href='#' onclick='delBudgetInfo(".$row['budgetlist_id'].")'><button class='btn btn-xs btn-danger'><i class='fa fa-times'></i> </button></a></td></tr>";
			}
		}else{
			$budgetListAdd = $budgetListAdd."Click here to create new budget - ";
		}
		$budgetListAdd = $budgetListAdd."<tr><th colspan=2><th><button id='addNewBudget' class='btn btn-xs btn-success'>New Budget&nbsp;&nbsp;</button></th></tr>";
	
		//get fiscal start month
		$consAdmin = "no";
		try{//check consortium admin rights		
			$result = $db->prepare("SELECT $db_member.consortium_id,$db_consortium.license_id,$db_consortium.admin1,$db_consortium.admin2 FROM $db_member,$db_consortium WHERE $db_member.member_id=? AND $db_member.consortium_id=$db_consortium.consortium_id AND $db_member.active=?");
			$result->execute(array($memberID,$yes));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		foreach ($result as $row){
			if ($licenseID == "1003" and ($memberID == $row['admin1'] or $memberID == $row['admin2'])){
				$consAdmin = "yes"; //yes, consort
			}
			$consortID = $row['consortium_id'];
		}
		$orgAdmin = "no";		
		try{//create budget list				
			$result = $db->prepare("SELECT $db_organization.organization_id,$db_organization.admin1,$db_organization.admin2 FROM $db_member,$db_consortium,$db_organization WHERE $db_member.member_id=? AND $db_member.consortium_id=$db_consortium.consortium_id AND $db_consortium.consortium_id=$db_organization.consortium_id");
			$result->execute(array($memberID));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		foreach ($result as $row){
			$orgID = $row['organization_id'];
			if ($memberID == $row['admin1'] or $memberID == $row['admin2']){
				$orgAdmin = "yes";
			}
		}
		
		try{//create budget list				
			$result = $db->prepare("SELECT begin_fiscal_mon FROM $db_setting WHERE organization_id=?");
			$result->execute(array($orgID));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		$itemCount = $result->rowCount();
		if ($itemCount > 0){
			foreach ($result as $row){
				$fiscalMon = $row['begin_fiscal_mon'];
			}
		}else{
			$fiscalMon = "01";
		}
		
		//create account list for annual budgeting
		$accountList = "";
		$accountListAdd = "";
		try{//create account list				
			$result = $db->prepare("SELECT account_id,name,list_order,budgetyn,description FROM $db_account WHERE member_id=? AND active=? ORDER BY list_order ASC");
			$result->execute(array($memberID,$yes));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		$itemCount = $result->rowCount();
		if ($itemCount > 0){
			foreach ($result as $row){
				$accountList = $accountList."<tr><td><lable>".str_replace('\\','',$row['name'])."</lable></td>";
				if ($row['budgetyn']=="yes"){
					$accountList = $accountList.'<td><div class="radio"><label><input type="radio" checked="checked" name="'.$row['account_id'].'" id="y'.$row['account_id'].'" value="yes" onclick="setAnnualBudget('.$row['account_id'].',1)"> Yes &nbsp;</label>';
					$accountList = $accountList.'<label><input type="radio" name="'.$row['account_id'].'" id="n'.$row['account_id'].'" value="no" onclick="setAnnualBudget('.$row['account_id'].',0)"> No &nbsp;</label></div></td>';
				}else{
					$accountList = $accountList.'<td><div class="radio"><label><input type="radio" name="'.$row['account_id'].'" id="y'.$row['account_id'].'" value="yes" onclick="setAnnualBudget('.$row['account_id'].',1)"> Yes &nbsp;</label>';
					$accountList = $accountList.'<label><input type="radio" checked="checked" name="'.$row['account_id'].'" id="n'.$row['account_id'].'" value="no" onclick="setAnnualBudget('.$row['account_id'].',0)"> No &nbsp;</label></div></td>';
				}
				$accountList = $accountList."<td>".str_replace('\\','',$row['description'])."</td></tr>";
			}
		}else{
			$accountListAdd = $accountListAdd."Click here to create new account - ";
		}
		
		//create budget list for annual budgeting
		try{					
			$result = $db->prepare("SELECT budget_id, name FROM $db_budget WHERE member_id=? AND active=? ORDER BY list_order ASC");
			$result->execute(array($memberID,$yes));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		$budgetSheetList = "<option value='1000'>No Budget</option>";
		foreach ($result as $row) {
			$budgetSheetList = $budgetSheetList.'<option value="'.$row['budget_id'].'">'.str_replace('\\','',$row['name']).'</option>';
		}
		
	}else{  //if statement - not login
		print "<script> self.location='".$index_url."'; </script>";
	}
?>