<?php
	include ('../../../m_inc/m_config.php'); //mysql cridential
	include ('../../../m_class/class_lib.php');
	include ('../../../m_inc/def_value.php');
		
	$budgetTotInst = new budgetInfoC();
	$accountInfo = new accountInfoC();
	
	$memberID = $_POST['mid'];
	$state = $_POST['state'];
	$active = "yes";
	
	if ($state == "add"){
		$activeyn = $_POST['activeyn'];
		$budName = $_POST['budName'];
		$budName = str_replace('"', "", $budName); //remove " from note
		
		$amount = $_POST['setAmount'];
		$amount = preg_replace('/[\$,]/', '', $amount); //remove $ and common from amount and assign zero if needs
		if ($amount=="" or $amount==0 or $amount==0.00 or $amount<0){
			$amount = 0;
		}
		$strdate = $_POST['strdate'];
		$partsArr = explode("-",$strdate);
		$strdate = $partsArr[2]."-".$partsArr[0]."-".$partsArr[1];
		$enddate = $_POST['enddate'];
		$partsArr = explode("-",$enddate);
		$enddate = $partsArr[2]."-".$partsArr[0]."-".$partsArr[1];
		$budMonth = $partsArr[0];
		$budDate = $partsArr[1];
		$budYear = $partsArr[2];
		$budgetSheetID = $_POST['budgetSheetID'];
		$budgetSheetTypeID = $_POST['budgetSheetTypeID'];
		$annmon = $_POST['annmon'];
		$budDesc = $_POST['budDesc'];
		$budDesc = str_replace('"', "", $budDesc); //remove " from note
		
		
		try{//get new budgetID
			$result = $db->prepare("SELECT list_order FROM $db_budgetlist WHERE member_id=? ORDER BY list_order ASC");
			$result->execute(array($memberID)); 
		} catch(PDOException $e) {
			echo "message002 - Sorry, system is experincing problem. Please check back.";
		}
		$itemCount = $result->rowCount();
		$itemCount++;
		try{
			$result = $db->prepare("INSERT INTO $db_budgetlist (budget_id,budget_type_id,name,list_order,amount,startdate,enddate,description,member_id,active) VALUES (?,?,?,?,?,?,?,?,?,?)");
			$result->execute(array($budgetSheetID,$budgetSheetTypeID,$budName,$itemCount,$amount,$strdate,$enddate,$budDesc,$memberID,$activeyn));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
///////////////////////////////////////////		
		try{//get new budgetID
			$result = $db->prepare("SELECT budgetlist_id FROM $db_budgetlist WHERE member_id=? ORDER BY budgetlist_id ASC");
			$result->execute(array($memberID)); 
		} catch(PDOException $e) {
			echo "message002 - Sorry, system is experincing problem. Please check back.";
		}
		foreach ($result as $row){
			$budgetlistID = $row['budgetlist_id'];
		}
		if ($budgetlistID != ""){
			try{//update budget details
				$result = $db->prepare("INSERT INTO $db_budgetlist_rights (member_id,access_rights,budgetlist_id,active) VALUES (?,?,?,?)");
				$result->execute(array($memberID,3,$budgetlistID,$active));
			} catch(PDOException $e) {
				echo "message003 - Sorry, system is experincing problem. Please check back.";
			}
		}	
///////////////////////////////////////////
		echo "done";
	}//end of add state
	
	//get account info
	if ($state == "getdata"){
		$budgetID = $_POST['budID'];
	
		try{//get new accountID
			$result = $db->prepare("SELECT budgetlist_id,budget_id,name,amount,startdate,enddate,description,active FROM $db_budgetlist WHERE member_id=? AND budgetlist_id=?");
			$result->execute(array($memberID,$budgetID)); 
		} catch(PDOException $e) {
			echo "message004 - Sorry, system is experincing problem. Please check back.";
		}
		foreach ($result as $row){
			$orgDateStr = $row['startdate']; //alter yyyy-mm-dd to mm-dd-yyyy
			$partsArr = explode("-",$orgDateStr);
			$strdate = $partsArr[1]."-".$partsArr[2]."-".$partsArr[0];
			
			$orgDateEnd = $row['enddate']; //alter yyyy-mm-dd to mm-dd-yyyy
			$partsArr = explode("-",$orgDateEnd);
			$enddate = $partsArr[1]."-".$partsArr[2]."-".$partsArr[0];
			
			$budgerInfo = '{"id":"'.$row['budgetlist_id'].'","act":"'.$row['active'].'","bid":"'.$row['budget_id'].'","name":"'.str_replace('\\','',$row['name']).'","amount":"'.$row['amount'].'","strdate":"'.$strdate.'","enddate":"'.$enddate.'","desc":"'.str_replace('\\','',$row['description']).'"}';
		}
		echo $budgerInfo;
	}
	
	//update account info
	if ($state == "edit"){
		$budgetID = $_POST['budID'];
		$activeyn = $_POST['activeyn'];
		$budName = $_POST['budName'];
		$budName = str_replace('"', "", $budName); //remove " from note
		
		$amount = $_POST['setAmount'];
		$amount = preg_replace('/[\$,]/', '', $amount); //remove $ and common from amount and assign zero if needs
		if ($amount=="" or $amount==0 or $amount==0.00 or $amount<0){
			$amount = 0;
		}
		$strdate = $_POST['strdate'];
		$partsArr = explode("-",$strdate);
		$strdate = $partsArr[2]."-".$partsArr[0]."-".$partsArr[1];
		$enddate = $_POST['enddate'];
		$partsArr = explode("-",$enddate);
		$enddate = $partsArr[2]."-".$partsArr[0]."-".$partsArr[1];
		
		$budgetSheetID = $_POST['budgetSheetID'];
		$budDesc = $_POST['budDesc'];
		$budDesc = str_replace('"', "", $budDesc); //remove " from note
		
		try{//update project details
			$result = $db->prepare("UPDATE $db_budgetlist SET budget_id=?,name=?,amount=?,startdate=?,enddate=?,description=?,active=? WHERE budgetlist_id=?");
			$result->execute(array($budgetSheetID,$budName,$amount,$strdate,$enddate,$budDesc,$activeyn,$budgetID));
		} catch(PDOException $e) {
			echo "message005 - Sorry, system is experincing problem. Please check back.";
		}
		
		echo $budgetSheetID;
	}
	
	//delete account
	if ($state == "delete"){
		$budgetID = $_POST['budID'];
		$budName = $_POST['budName'];
		$budDesc = $_POST['budDesc'];
				
		try{//delete account
			$result = $db->prepare("DELETE FROM $db_budgetlist WHERE budgetlist_id=? AND member_id=?");
			$result->execute(array($budgetID,$memberID));
		} catch(PDOException $e) {
			echo "message006 - Sorry, system is experincing problem. Please check back.";
		}
		try{//delete account details
			$result = $db->prepare("DELETE FROM $db_budgetlist_rights WHERE budgetlist_id=?");
			$result->execute(array($budgetID));
		} catch(PDOException $e) {
			echo "message007 - Sorry, system is experincing problem. Please check back.";
		}
	}
?>