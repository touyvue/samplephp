<?php
	include ('../../../m_inc/m_config.php'); //mysql cridential
	include ('../../../m_class/class_lib.php');
	include ('../../../m_inc/def_value.php');
	
	$memberTotInst = new memberInfoC();
	$accountInfo = new accountInfoC();
	$budgetTotInst = new budgetInfoC();
	$state = $_POST['state'];
	$yes = "yes";
	
	if ($state == "new"){
		$consortiumID = $_POST['consortiumid'];
		$memberID = $_POST['mid'];
		$memtrackid = $_POST['memtrackid'];
		$memberlistIDs = $_POST['memberlistIDs'];
		$membudgetlistid = $_POST['membudgetlistid'];
		$acttracklistid = $_POST['acttracklistid'];
		$transtype = $_POST['type'];
		$trackname = $_POST['trackname'];
		$trackdate = $_POST['trackdate'];
		$partsArr = explode("-",$trackdate);//alter mm-dd-yyyy to yyyy-mm-dd
		$trackdate = $partsArr[2]."-".$partsArr[0]."-".$partsArr[1];
		$tracknote = $_POST['tracknote'];
		
		$referenceNumber = date('m').date('d').date('H').date('i').date('s').rand(100000, 999999);
		try{
			$result = $db->prepare("INSERT INTO $db_membertrack (consortium_id,member_id,memberbudget_id,account_id,transaction_type_id,name,date,note) VALUES (?,?,?,?,?,?,?,?)");
			$result->execute(array($consortiumID,$memberID,$membudgetlistid,$acttracklistid,$transtype,$trackname,$trackdate,$referenceNumber));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}	
		try{//get the new membertrackID					
			$result = $db->prepare("SELECT membertrack_id FROM $db_membertrack WHERE member_id=? AND note=?");
			$result->execute(array($memberID,$referenceNumber));
		} catch(PDOException $e) {
			echo "message002 - Sorry, system is experincing problem. Please check back.";
		}
		foreach ($result as $row){
			$membertrackid = $row['membertrack_id'];
		}
		try{
			$result = $db->prepare("UPDATE $db_membertrack SET note=? WHERE membertrack_id=?");
			$result->execute(array($tracknote,$membertrackid));
		} catch(PDOException $e) {
			echo "message003 - Sorry, system is experincing problem. Please check back.";
		}
		$tempMemberListArrCt = count($memberlistIDs);
		for ($i = 0; $i < $tempMemberListArrCt; $i++){
			try{
				$result = $db->prepare("INSERT INTO $db_membertrack_amt (membertrack_id,member_id,amount) VALUES (?,?,?)");
				$result->execute(array($membertrackid,$memberlistIDs[$i],0));
			} catch(PDOException $e) {
				echo "message004 - Sorry, system is experincing problem. Please check back.";
			}
		}
	}//end of new
	
	if ($state == "update"){
		$consortiumID = $_POST['consortiumid'];
		$memberID = $_POST['mid'];
		$memtrackid = $_POST['memtrackid'];
		$memberlistIDs = $_POST['memberlistIDs'];
		$membudgetlistid = $_POST['membudgetlistid'];
		$acttracklistid = $_POST['acttracklistid'];
		$transtype = $_POST['type'];
		$trackname = $_POST['trackname'];
		$trackdate = $_POST['trackdate'];
		$partsArr = explode("-",$trackdate);//alter mm-dd-yyyy to yyyy-mm-dd
		$trackdate = $partsArr[2]."-".$partsArr[0]."-".$partsArr[1];
		$tracknote = $_POST['tracknote'];
		
		try{
			$result = $db->prepare("UPDATE $db_membertrack SET memberbudget_id=?,account_id=?,transaction_type_id=?,name=?,date=?,note=? WHERE membertrack_id=?");
			$result->execute(array($membudgetlistid,$acttracklistid,$transtype,$trackname,$trackdate,$tracknote,$memtrackid));
		} catch(PDOException $e) {
			echo "message005 - Sorry, system is experincing problem. Please check back.";
		} 
		//delete any existing account transaction
		if ($acttracklistid == "" or $acttracklistid == 0 or $acttracklistid == "NULL"){
			try{//delete any existing account info, first
				$result = $db->prepare("DELETE FROM $db_transaction WHERE membertrack_id=?");
				$result->execute(array($memtrackid));
			} catch(PDOException $e) {
				echo "message006 - Sorry, system is experincing problem. Please check back.";
			}
		}else{
			//check if membertrackID has total greater than 0 and has accountID
			$accountFound = 0;
			$amtTot = 0;
			try{					
				$result = $db->prepare("SELECT member_id,account_id,transaction_type_id,date,note FROM $db_membertrack WHERE membertrack_id=?");
				$result->execute(array($memtrackid));
			} catch(PDOException $e) {
				echo "message007 - Sorry, system is experincing problem. Please check back.";
			}
			$accountFound = $result->rowCount();
			if ($accountFound > 0){
				foreach ($result as $row){
					$memberID = $row['member_id'];
					$accountID = $row['account_id'];
					$transTypeID = $row['transaction_type_id'];
					$date = $row['date'];
					$note = $row['note'];
				}
				$amtTot = $memberTotInst->getMembertrackTotF($memtrackid);
				if ($amtTot > 0){
					//insert accountID and amount to Transaction table
					try{//delete any existing account info, first
						$result = $db->prepare("DELETE FROM $db_transaction WHERE membertrack_id=?");
						$result->execute(array($memtrackid));
					} catch(PDOException $e) {
						echo "message008 - Sorry, system is experincing problem. Please check back.";
					}
					//insert new record
					$transOrderCounter = 0;
					$transOrderCounter = $accountInfo->getTransOrderNum($transOrderCounter,$accountID,$date);
					$transOrderCounter += 1;
					$referenceNumber = "no"; 
					$accountInfo->insertAcctTransPrj($accountID,$memberID,$date,"yes",$transOrderCounter,$amtTot,$transTypeID,0,$note,"",0,0,0,0,0,0,0,0,$memtrackid,"yes");
				}
			}
		}
		
		$tempMemberListArrCt = count($memberlistIDs);
		try{//check for existing member that aren't selected and delete them					
			$result = $db->prepare("SELECT member_id FROM $db_membertrack_amt WHERE membertrack_id=?");
			$result->execute(array($memtrackid));
		} catch(PDOException $e) {
			echo "message009 - Sorry, system is experincing problem. Please check back.";
		}	
		foreach ($result as $row) {
			$selectMemberID = $row['member_id'];
			$idFound = "no";
			for ($i = 0; $i < $tempMemberListArrCt; $i++){
				if ($selectMemberID == $memberlistIDs[$i]){
					$idFound = "yes";
					$i = $tempMemberListArrCt;
				}
			}
			if ($idFound == "no"){
				try{//delete membertrack_amt id
					$result = $db->prepare("DELETE FROM $db_membertrack_amt WHERE membertrack_id=? AND member_id=?");
					$result->execute(array($memtrackid,$selectMemberID));
				} catch(PDOException $e) {
					echo "message011 - Sorry, system is experincing problem. Please check back.";
				}
			}	
		}//end foreach loop
		
		for ($i = 0; $i < $tempMemberListArrCt; $i++){
			$itemsFound = 0;
			try{//check for existing member					
				$result = $db->prepare("SELECT member_id FROM $db_membertrack_amt WHERE membertrack_id=? AND member_id=?");
				$result->execute(array($memtrackid,$memberlistIDs[$i]));
			} catch(PDOException $e) {
				echo "message009 - Sorry, system is experincing problem. Please check back.";
			}
			$itemsFound = $result->rowCount();
			if ($itemsFound == 0){
				try{
					$result = $db->prepare("INSERT INTO $db_membertrack_amt (membertrack_id,member_id,amount) VALUES (?,?,?)");
					$result->execute(array($memtrackid,$memberlistIDs[$i],0));
				} catch(PDOException $e) {
					echo "message004 - Sorry, system is experincing problem. Please check back.";
				}
			}
		}
		
	}//end of update
	
	if ($state == "getinfo"){
		$processid = $_POST['processid'];
		$flag = $_POST['flag'];
		
		$memberListArr = "";
		$memberListArrCt = 0;
		try{					
			$result = $db->prepare("SELECT member_id FROM $db_membertrack_amt WHERE membertrack_id=?");
			$result->execute(array($processid));
		} catch(PDOException $e) {
			echo "message009 - Sorry, system is experincing problem. Please check back.";
		}
		foreach ($result as $row) {
			if ($memberListArrCt == 0){
				$memberListArr = $row['member_id'];
				$memberListArrCt++;
			}else{
				$memberListArr = $memberListArr.",".$row['member_id'];
			}
		}
		try{					
			$result = $db->prepare("SELECT membertrack_id,memberbudget_id,account_id,transaction_type_id,name,date,note FROM $db_membertrack WHERE membertrack_id=?");
			$result->execute(array($processid));
		} catch(PDOException $e) {
			echo "message010 - Sorry, system is experincing problem. Please check back.";
		}
		$itemsFound = $result->rowCount();
		$memberCt = 0;
		$memberForecast = "";
		foreach ($result as $row) {
			$name = str_replace('\\','',$row['name']);
			$name = str_replace('"','',$name);
			$date = $row['date']; //alter yyyy-mm-dd to mm-dd-yyyy mx002gb
			$partsArr = explode("-",$date);
			$date = $partsArr[1]."-".$partsArr[2]."-".$partsArr[0];
			$note = str_replace('\\','',$row['note']);
			$note = str_replace('"','',$note);
			$memberForecast = '{"mtid":"'.$row['membertrack_id'].'","mbid":"'.$row['memberbudget_id'].'","acct":"'.$row['account_id'].'","type":"'.$row['transaction_type_id'].'","name":"'.$name.'","date":"'.$date.'","note":"'.$note.'","list":"'.$memberListArr.'"}';
		}
		echo $memberForecast;
	}//end of getinfo

	if ($state == "delete"){
		$memtrackid = $_POST['memtrackid'];
		try{//delete membertrack
			$result = $db->prepare("DELETE FROM $db_membertrack WHERE membertrack_id=?");
			$result->execute(array($memtrackid));
		} catch(PDOException $e) {
			echo "message011 - Sorry, system is experincing problem. Please check back.";
		}
		try{
			$result = $db->prepare("DELETE FROM $db_membertrack_amt WHERE membertrack_id=?");
			$result->execute(array($memtrackid));
		} catch(PDOException $e) {
			echo "message011 - Sorry, system is experincing problem. Please check back.";
		}
		//delete any existing account transaction
		try{//delete any existing account info, first
			$result = $db->prepare("DELETE FROM $db_transaction WHERE membertrack_id=?");
			$result->execute(array($memtrackid));
		} catch(PDOException $e) {
			echo "message012 - Sorry, system is experincing problem. Please check back.";
		}
	}
	
	/////////////////////////////////////////////////////
	if ($state == "field"){
		$memberID = $_POST['memberID'];
		$membertrackID = $_POST['membertrackID'];
		$chValue = $_POST['chValue'];
		
		//check for existing record
		$itemsFound = 0;
		try{					
			$result = $db->prepare("SELECT amount FROM $db_membertrack_amt WHERE membertrack_id=? AND member_id=?");
			$result->execute(array($membertrackID,$memberID));
		} catch(PDOException $e) {
			echo "message013 - Sorry, system is experincing problem. Please check back.";
		}
		$itemsFound = $result->rowCount();
		
		if ($itemsFound == 0){
			try{
				$result = $db->prepare("INSERT INTO $db_membertrack_amt (membertrack_id,member_id,amount) VALUES (?,?,?)");
				$result->execute(array($membertrackID,$memberID,$chValue));
			} catch(PDOException $e) {
				echo "message014 - Sorry, system is experincing problem. Please check back.";
			}
		}else{
			try{
				$result = $db->prepare("UPDATE $db_membertrack_amt SET amount=? WHERE membertrack_id=? AND member_id=?");
				$result->execute(array($chValue,$membertrackID,$memberID));
			} catch(PDOException $e) {
				echo "message015 - Sorry, system is experincing problem. Please check back.";
			}
		}
		
		//check if membertrackID has total greater than 0 and has accountID
		$accountFound = 0;
		$amtTot = 0;
		try{					
			$result = $db->prepare("SELECT member_id,account_id,transaction_type_id,date,note FROM $db_membertrack WHERE membertrack_id=?");
			$result->execute(array($membertrackID));
		} catch(PDOException $e) {
			echo "message016 - Sorry, system is experincing problem. Please check back.";
		}
		$accountFound = $result->rowCount();
		if ($accountFound > 0){
			foreach ($result as $row){
				$memberID = $row['member_id'];
				$accountID = $row['account_id'];
				$transTypeID = $row['transaction_type_id'];
				$date = $row['date'];
				$note = $row['note'];
			}
			$amtTot = $memberTotInst->getMembertrackTotF($membertrackID);
			if ($amtTot > 0){
				//insert accountID and amount to Transaction table
				try{//delete any existing account info, first
					$result = $db->prepare("DELETE FROM $db_transaction WHERE membertrack_id=?");
					$result->execute(array($membertrackID));
				} catch(PDOException $e) {
					echo "message017 - Sorry, system is experincing problem. Please check back.";
				}
				//insert new record
				$transOrderCounter = 0;
				$transOrderCounter = $accountInfo->getTransOrderNum($transOrderCounter,$accountID,$date);
				$transOrderCounter += 1;
				$referenceNumber = "no"; 
				$accountInfo->insertAcctTransPrj($accountID,$memberID,$date,"yes",$transOrderCounter,$amtTot,$transTypeID,0,$note,"",0,0,0,0,0,0,0,0,$membertrackID,"yes");
			}
		}
		$amtTot = $memberTotInst->getMembertrackTotF($membertrackID);
		$amtTot = $budgetTotInst->convertDollarF($amtTot);
		echo $amtTot;
	}	
?>