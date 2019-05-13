<?php
	include ('../../../m_inc/m_config.php'); //mysql cridential
	include ('../../../m_class/class_lib.php');
	include ('../../../m_inc/def_value.php');
		
	$memberID = $_POST['mid'];
	$budgetID = $_POST['budID'];
	$state = $_POST['state'];
	$yes = "yes";
	
	//update budget list order
	if ($state == "editlist"){
		$listOrd = $_POST['listOrd'];
		try{//update project details
			$result = $db->prepare("UPDATE $db_budget SET list_order=? WHERE budget_id=? AND member_id=?");
			$result->execute(array($listOrd,$budgetID,$memberID));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
	}
	
	//budget active status
	if ($state == "updatesta"){
		$chgStatus = $_POST['chgStatus'];
		try{//update project details
			$result = $db->prepare("UPDATE $db_budget SET active=? WHERE budget_id=? AND member_id=?");
			$result->execute(array($chgStatus,$budgetID,$memberID));
		} catch(PDOException $e) {
			echo "message002 - Sorry, system is experincing problem. Please check back.";
		}
	}
	
	echo "done";
?>