<?php
	include ('../../../m_inc/m_config.php'); //mysql cridential
	include ('../../../m_class/class_lib.php');
	include ('../../../m_inc/def_value.php');
		
	$memberID = $_POST['mid'];
	$state = $_POST['state'];
	$yes = "yes";
	
	//update account info
	if ($state == "edit"){
		$budgetID = $_POST['budID'];
		$listOrd = $_POST['listOrd'];
		try{//update project details
			$result = $db->prepare("UPDATE $db_budgetlist SET list_order=? WHERE budgetlist_id=? AND member_id=?");
			$result->execute(array($listOrd,$budgetID,$memberID));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		echo "edit order list";
	}
	
	//set account for annual budget
	if ($state == "setbudget"){
		$accountID = $_POST['accountID'];
		$budgetYN = $_POST['budgetYN'];
		$chartYN = $_POST['chartYN'];
		
		try{//update project details
			$result = $db->prepare("UPDATE $db_account SET budgetyn=?,chartYN=? WHERE account_id=?");
			$result->execute(array($budgetYN,$chartYN,$accountID));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		echo "set accoutn budget";
	}
	//set budget to display on Dashboard
	if ($state == "setchart"){
		$budgetID = $_POST['budgetID'];
		$chartYN = $_POST['chartYN'];
		$budgetFound = 0;
		if ($chartYN == "yes"){
			try{//create budget list				
				$result = $db->prepare("SELECT budget_id FROM $db_budget WHERE member_id=? AND chartyn=? AND active=?");
				$result->execute(array($memberID,$yes,$yes));
			} catch(PDOException $e) {
				print "<script> self.location='".$index_url."?err=d1000'; </script>";
			}
			$budgetFound = $result->rowCount();
		}
		if ($budgetFound < 3){
			try{//update project details
				$result = $db->prepare("UPDATE $db_budget SET chartYN=? WHERE budget_id=?");
				$result->execute(array($chartYN,$budgetID));
			} catch(PDOException $e) {
				echo "message001 - Sorry, system is experincing problem. Please check back.";
			}
			echo "update";
		}else{
			echo "over";
		}
	}
?>