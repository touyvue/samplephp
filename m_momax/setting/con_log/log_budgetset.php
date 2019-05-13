<?php
	if ($_SESSION['login']=="yes" and $_SESSION['log_ss'] != ""){
		$memberID = $_SESSION['mid'];
		$projectInfo = new projectInfoC();
		$budgetTotInst = new budgetInfoC();
		$memberTotInst = new memberInfoC();
		$accountSetting = "Account Setting";
		$budgetSetting = "Budget Setting";
		$projectSetting = "Project Setting";
		$categorySetting = "Category Setting";
		$yes = "yes";
		
		$budgetList = "";
		$budgetListAdd = "";
		try{//create budget list				
			$result = $db->prepare("SELECT budget_id,name,list_order,description,active FROM $db_budget WHERE member_id=? ORDER BY active DESC, list_order ASC");
			$result->execute(array($memberID));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		$itemCount = $result->rowCount();
		if ($itemCount > 0){
			foreach ($result as $row){
				if ($row['active'] == "yes"){
					$status = 1;
				}else{
					$status = 0;
				}
				$budgetList = $budgetList."<tr><td><span id='olist_view".$row['budget_id']."' style='display: ;'><a href='#' onclick='editacctlist(1,".$row['budget_id'].",".$row['list_order'].",".$memberID.")'>".$row['list_order']."</a></span><span id='olist_edit".$row['budget_id']."' style='display: none ;'><input size='2' value='".$row['list_order']."' class='form-control' type='text' name='od".$row['budget_id']."' id='od".$row['budget_id']."' onchange='editacctlist(2,".$row['budget_id'].",".$row['list_order'].",".$memberID.")' /></span></td>";
				$budgetList = $budgetList."<td><a href='#' onclick='uptBudgetInfo(".$row['budget_id'].")'>".str_replace('\\','',$row['name'])."</a></td><td><a href='#' onclick='uptActiveStatus(".$row['budget_id'].",".$memberID.",".$status.")'>".$row['active']."</a></td><td>".str_replace('\\','',$row['description'])."</td>";
				$budgetList = $budgetList."<td><a href='#' onclick='delBudgetInfo(".$row['budget_id'].")'><span class='label label-danger'>x</span></a></td></tr>";
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
		
		//create budget list for display chart
		$accountBudList = "";
		$accountBudListAdd = "";
		try{//create budget list				
			$result = $db->prepare("SELECT budget_id,name,list_order,chartyn FROM $db_budget WHERE member_id=? AND active=? ORDER BY list_order ASC");
			$result->execute(array($memberID,$yes));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		$itemCount = $result->rowCount();
		if ($itemCount > 0){
			foreach ($result as $row){
				$accountBudList = $accountBudList."<tr><td><lable>".str_replace('\\','',$row['name'])."</lable></td>";
				if ($row['chartyn']=="yes"){
					$accountBudList = $accountBudList.'<td><div class="radio"><label><input type="radio" checked="checked" name="ch'.$row['budget_id'].'" id="chy'.$row['budget_id'].'" value="yes" onclick="setAnnualBudget('.$row['budget_id'].',3)"> Yes &nbsp;</label>';
					$accountBudList = $accountBudList.'<label><input type="radio" name="ch'.$row['budget_id'].'" id="chn'.$row['budget_id'].'" value="no" onclick="setAnnualBudget('.$row['budget_id'].',2)"> No &nbsp;</label></div></td>';
				}else{
					$accountBudList = $accountBudList.'<td><div class="radio"><label><input type="radio" name="ch'.$row['budget_id'].'" id="chy'.$row['budget_id'].'" value="yes" onclick="setAnnualBudget('.$row['budget_id'].',3)"> Yes &nbsp;</label>';
					$accountBudList = $accountBudList.'<label><input type="radio" checked="checked" name="ch'.$row['budget_id'].'" id="chn'.$row['budget_id'].'" value="no" onclick="setAnnualBudget('.$row['budget_id'].',2)"> No &nbsp;</label></div></td>';
				}
				$accountBudList = $accountBudList."</tr>";
			}
		}else{
			$accountBudListAdd = $accountBudListAdd."Click here to create new account - ";
		}
		
	}else{  //if statement - not login
		print "<script> self.location='".$index_url."'; </script>";
	}
?>