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
		$accountList = "";
		$accountListAdd = "";
		try{//create account list				
			$result = $db->prepare("SELECT account_id,name,list_order,description FROM $db_account WHERE member_id=? AND active=? ORDER BY list_order ASC");
			$result->execute(array($memberID,$yes));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		$itemCount = $result->rowCount();
		if ($itemCount > 0){
			foreach ($result as $row){
				$accountList = $accountList."<tr><td><span id='olist_view".$row['account_id']."' style='display: ;'><a href='#' onclick='editacctlist(1,".$row['account_id'].",".$row['list_order'].",".$memberID.")'>".$row['list_order']."</a></span><span id='olist_edit".$row['account_id']."' style='display: none ;'><input size='2' value='".$row['list_order']."' class='form-control' type='text' name='od".$row['account_id']."' id='od".$row['account_id']."' onchange='editacctlist(2,".$row['account_id'].",".$row['list_order'].",".$memberID.")' /></span></td>";
				$accountList = $accountList."<td><a href='#' onclick='uptAccountInfo(".$row['account_id'].")'>".str_replace('\\','',$row['name'])."</a></td><td>".str_replace('\\','',$row['description'])."</td>";
				$accountList = $accountList."<td><a href='#' onclick='uptAccountInfo(".$row['account_id'].")'><button class='btn btn-xs btn-warning'><i class='fa fa-pencil'></i></button></a>";
				$accountList = $accountList."<a href='#' onclick='delAccountInfo(".$row['account_id'].")'><button class='btn btn-xs btn-danger'><i class='fa fa-times'></i> </button></a></td></tr>";
			}
		}else{
			$accountListAdd = $accountListAdd."Click here to create new account - ";
		}
		$accountListAdd = $accountListAdd."<tr><th colspan=2><th><button id='addNewAccount' class='btn btn-xs btn-success'>New Account&nbsp;&nbsp;</button></th></tr>";
	
		//create account list for annual budgeting
		$accountBudList = "";
		$accountBudListAdd = "";
		try{//create account list				
			$result = $db->prepare("SELECT account_id,name,list_order,budgetyn,chartyn FROM $db_account WHERE member_id=? AND active=? ORDER BY list_order ASC");
			$result->execute(array($memberID,$yes));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		$itemCount = $result->rowCount();
		if ($itemCount > 0){
			foreach ($result as $row){
				$accountBudList = $accountBudList."<tr><td><lable>".str_replace('\\','',$row['name'])."</lable></td>";
				if ($row['budgetyn']=="yes"){
					$accountBudList = $accountBudList.'<td><div class="radio"><label><input type="radio" checked="checked" name="'.$row['account_id'].'" id="y'.$row['account_id'].'" value="yes" onclick="setAnnualBudget('.$row['account_id'].',1)"> Yes &nbsp;</label>';
					$accountBudList = $accountBudList.'<label><input type="radio" name="'.$row['account_id'].'" id="n'.$row['account_id'].'" value="no" onclick="setAnnualBudget('.$row['account_id'].',0)"> No &nbsp;</label></div></td>';
				}else{
					$accountBudList = $accountBudList.'<td><div class="radio"><label><input type="radio" name="'.$row['account_id'].'" id="y'.$row['account_id'].'" value="yes" onclick="setAnnualBudget('.$row['account_id'].',1)"> Yes &nbsp;</label>';
					$accountBudList = $accountBudList.'<label><input type="radio" checked="checked" name="'.$row['account_id'].'" id="n'.$row['account_id'].'" value="no" onclick="setAnnualBudget('.$row['account_id'].',0)"> No &nbsp;</label></div></td>';
				}
				if ($row['chartyn']=="yes"){
					$accountBudList = $accountBudList.'<td><div class="radio"><label><input type="radio" checked="checked" name="ch'.$row['account_id'].'" id="chy'.$row['account_id'].'" value="yes" onclick="setAnnualBudget('.$row['account_id'].',3)"> Yes &nbsp;</label>';
					$accountBudList = $accountBudList.'<label><input type="radio" name="ch'.$row['account_id'].'" id="chn'.$row['account_id'].'" value="no" onclick="setAnnualBudget('.$row['account_id'].',2)"> No &nbsp;</label></div></td>';
				}else{
					$accountBudList = $accountBudList.'<td><div class="radio"><label><input type="radio" name="ch'.$row['account_id'].'" id="chy'.$row['account_id'].'" value="yes" onclick="setAnnualBudget('.$row['account_id'].',3)"> Yes &nbsp;</label>';
					$accountBudList = $accountBudList.'<label><input type="radio" checked="checked" name="ch'.$row['account_id'].'" id="chn'.$row['account_id'].'" value="no" onclick="setAnnualBudget('.$row['account_id'].',2)"> No &nbsp;</label></div></td>';
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