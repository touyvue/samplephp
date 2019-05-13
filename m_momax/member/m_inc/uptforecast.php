<?php
	include ('../../../m_inc/m_config.php'); //mysql cridential
	include ('../../../m_class/class_lib.php');
	include ('../../../m_inc/def_value.php');
	
	$memberTotInst = new memberInfoC();
	$budgetTotInst = new budgetInfoC();
	$state = $_POST['state'];
	$yes = "yes";
	
	if ($state == "new"){
		$consortiumID = $_POST['consortiumid'];
		$memberID = $_POST['mid'];
		$active = $_POST['active'];
		$budgetname = $_POST['budgetname'];
		$start = $_POST['start'];
		$partsArr = explode("-",$start);//alter mm-dd-yyyy to yyyy-mm-dd
		$start = $partsArr[2]."-".$partsArr[0]."-".$partsArr[1];
		$end = $_POST['end'];
		$partsArr = explode("-",$end);//alter mm-dd-yyyy to yyyy-mm-dd
		$end = $partsArr[2]."-".$partsArr[0]."-".$partsArr[1];
		$note = $_POST['note'];
		try{
			$result = $db->prepare("INSERT INTO $db_memberbudget (consortium_id,member_id,name,start,end,note,active) VALUES (?,?,?,?,?,?,?)");
			$result->execute(array($consortiumID,$memberID,$budgetname,$start,$end,$note,$active));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}		
	}
	if ($state == "update"){
		$membudgetid = $_POST['membudgetid'];
		$active = $_POST['active'];
		$budgetname = $_POST['budgetname'];
		$start = $_POST['start'];
		$partsArr = explode("-",$start);//alter mm-dd-yyyy to yyyy-mm-dd
		$start = $partsArr[2]."-".$partsArr[0]."-".$partsArr[1];
		$end = $_POST['end'];
		$partsArr = explode("-",$end);//alter mm-dd-yyyy to yyyy-mm-dd
		$end = $partsArr[2]."-".$partsArr[0]."-".$partsArr[1];
		$note = $_POST['note'];
		try{
			$result = $db->prepare("UPDATE $db_memberbudget SET name=?,start=?,end=?,note=?,active=? WHERE memberbudget_id=?");
			$result->execute(array($budgetname,$start,$end,$note,$active,$membudgetid));
		} catch(PDOException $e) {
			echo "message002 - Sorry, system is experincing problem. Please check back.";
		} 
	}
	if ($state == "getinfo"){
		$processid = $_POST['processid'];
		$flag = $_POST['flag'];
		
		try{					
			$result = $db->prepare("SELECT memberbudget_id,name,start,end,note,active FROM $db_memberbudget WHERE memberbudget_id=?");
			$result->execute(array($processid));
		} catch(PDOException $e) {
			echo "message003 - Sorry, system is experincing problem. Please check back.";
		}
		$itemsFound = $result->rowCount();
		$memberCt = 0;
		foreach ($result as $row) {
			$name = str_replace('\\','',$row['name']);
			$name = str_replace('"','',$name);
			$startDate = $row['start']; //alter yyyy-mm-dd to mm-dd-yyyy mx002gb
			$partsArr = explode("-",$startDate);
			$startDate = $partsArr[1]."-".$partsArr[2]."-".$partsArr[0];
			$endDate = $row['end']; //alter yyyy-mm-dd to mm-dd-yyyy mx002gb
			$partsArr = explode("-",$endDate);
			$endDate = $partsArr[1]."-".$partsArr[2]."-".$partsArr[0];
			$note = str_replace('\\','',$row['note']);
			$note = str_replace('"','',$note);
			$memberForecast = '{"mbid":"'.$row['memberbudget_id'].'","name":"'.$name.'","start":"'.$startDate.'","end":"'.$endDate.'","note":"'.$note.'","act":"'.$row['active'].'"}';
		}
		echo $memberForecast;
	}//end of getinfo

	if ($state == "delete"){
		$membudgetid = $_POST['membudgetid'];
		try{//delete project item
			$result = $db->prepare("DELETE FROM $db_memberbudget WHERE memberbudget_id=?");
			$result->execute(array($membudgetid));
		} catch(PDOException $e) {
			echo "message004 - Sorry, system is experincing problem. Please check back.";
		}
	}
	
	/////////////////////////////////////////////////////
	$useMemberID = "";
	if ($state == "addmember"){
		$viewmemid = $_POST['viewmemid'];
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$relation = $_POST['relation'];
		$note = $_POST['note'];
		try{
			$result = $db->prepare("INSERT INTO $db_memberplus (member_id,first_name,last_name,relationship,note) VALUES (?,?,?,?,?)");
			$result->execute(array($viewmemid,$firstname,$lastname,$relation,$note));
		} catch(PDOException $e) {
			echo "message005 - Sorry, system is experincing problem. Please check back.";
		}	
		$state = "memberinfo"; //get new family name list	
		$useMemberID = $viewmemid;
	}
	if ($state == "delmember"){
		$memberplustID = $_POST['memberplustID'];
		$viewmemid = $_POST['viewmemid'];
		try{
			$result = $db->prepare("DELETE FROM $db_memberplus WHERE memberplus_id=?");
			$result->execute(array($memberplustID));
		} catch(PDOException $e) {
			echo "message006 - Sorry, system is experincing problem. Please check back.";
		}
		$state = "memberinfo"; //get new family name list
		$useMemberID = $viewmemid;	
	}
	if ($state == "memberinfo"){//get family member
		if ($useMemberID != ""){
			$memberID = $useMemberID;
		}else{
			$memberID = $_POST['memberid'];
		}
		try{					
			$result = $db->prepare("SELECT memberplus_id,first_name,last_name,relationship FROM $db_memberplus WHERE member_id=? ORDER BY first_name ASC");
			$result->execute(array($memberID));
		} catch(PDOException $e) {
			echo "message007 - Sorry, system is experincing problem. Please check back.";
		}
		$itemsFound = $result->rowCount();
		$memberPlus = "[";
		$memberCt = 0;
		foreach ($result as $row) {
			$firstName = $state = str_replace("'", "", $row['first_name']);
			$lastName = $state = str_replace("'", "", $row['last_name']);
			if ($row['relationship'] === NULL or $row['relationship'] == ""){
				$relation = "";
			}else{
				$relation = $row['relationship'];
			}
			if ($memberCt > 0){
				$memberPlus = $memberPlus.",";
			}
			$memberCt++;
			$memberPlus = $memberPlus.'{"mid":"'.$row['memberplus_id'].'",';
			$memberPlus = $memberPlus.'"relation":"'.$relation.'",';
			$memberPlus = $memberPlus.'"first":"'.$firstName.'",';
			$memberPlus = $memberPlus.'"last":"'.$lastName.'"}';
		}
		$memberPlus = $memberPlus."]";
		echo "=".$itemsFound."<>".$memberPlus;
	}//end of get family member	
	if ($state == "field"){
		$memberID = $_POST['memberID'];
		$memberbudgetID = $_POST['memberbudgetID'];
		$chValue = $_POST['chValue'];
		
		//check for existing record
		$itemsFound = 0;
		$amtTot = 0;
		try{					
			$result = $db->prepare("SELECT amount FROM $db_memberbudget_amt WHERE memberbudget_id=? AND member_id=?");
			$result->execute(array($memberbudgetID,$memberID));
		} catch(PDOException $e) {
			echo "message008 - Sorry, system is experincing problem. Please check back.";
		}
		$itemsFound = $result->rowCount();
		
		if ($itemsFound == 0){
			try{
				$result = $db->prepare("INSERT INTO $db_memberbudget_amt (memberbudget_id,member_id,amount) VALUES (?,?,?)");
				$result->execute(array($memberbudgetID,$memberID,$chValue));
			} catch(PDOException $e) {
				echo "message001 - Sorry, system is experincing problem. Please check back.";
			}
		}else{
			try{
				$result = $db->prepare("UPDATE $db_memberbudget_amt SET amount=? WHERE memberbudget_id=? AND member_id=?");
				$result->execute(array($chValue,$memberbudgetID,$memberID));
			} catch(PDOException $e) {
				echo "message009 - Sorry, system is experincing problem. Please check back.";
			}
		}
		$amtTot = $memberTotInst->getMemberbudgetTotF($memberbudgetID);
		$amtTot = $budgetTotInst->convertDollarF($amtTot);
				
		echo $amtTot;
	}	
?>