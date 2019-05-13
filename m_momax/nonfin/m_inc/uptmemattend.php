<?php
	include ('../../../m_inc/m_config.php'); //mysql cridential
	include ('../../../m_class/class_lib.php');
	include ('../../../m_inc/def_value.php');
	
	$budgetTotInst = new budgetInfoC();
	$accountInfo = new accountInfoC();
	$memberTotInst = new memberInfoC();
	$generalInfo = new generalInfoC();
	
	$memberID = $_POST['mid'];
	$selectgrpid = $_POST['selectgrpid'];
	$selectname = $_POST['selectname'];
	$selectnameid = $_POST['selectnameid'];
	$valCatId = $_POST['valCatId'];
	$valCatType = $_POST['valCatType'];
	$valCatCt = $_POST['valCatCt'];
	
	//$valCatVal = $_POST['valCatVal'];
	$valCatArr = $_POST['valCatArr'];
	$purCatArr = $_POST['purCatArr'];
	$subNumArrCt = $_POST['subNumArrCt']; 
	if ($subNumArrCt > 1){
		$valCatValArr = array();
		$valCatCtArr = array();
		$valCatTxArr = array();
		$valCatArr = array();
		$purCatArr = array();
		$valCatArr = explode(',', $_POST['valCatArr']);
		$purCatArr = explode(',', $_POST['purCatArr']);
		//loop through each array to check for " and *
		for ($i = 0; $i < $subNumArrCt; $i++){
			if ($valCatType == "mone" or $valCatType == "numb"){
				$tempVal = preg_replace('/[\$,]/', '', $valCatArr[$i]); //remove $ and common
				if ($tempVal=="" or $tempVal==0 or $tempVal==0.00 or $tempVal<0){
					$tempVal = 0;
				}
			}
			if ($valCatType == "mone"){
				$valCatValArr[$i] = $tempVal;
				$valCatCtArr[$i] = 0;
				$valCatTxArr[$i] = "";
			}
			if ($valCatType == "numb"){
				$valCatCtArr[$i] = $tempVal;
				$valCatValArr[$i] = 0;
				$valCatTxArr[$i] = "";
			}
			if ($valCatType == "text"){
				$tempVal = str_replace('"', "", $valCatArr[$i]);
				$valCatTxArr[$i] = $tempVal;
				$valCatValArr[$i] = 0;
				$valCatCtArr[$i] = 0;
			} 
			$purCatArr[$i] = $purCatArr[$i];
		}
		$referenceNumber = date('m').date('d').date('H').date('i').date('s').rand(100000, 999999); //reference for subcategory		
		$subcat_yn = "yes";
	}else{
		$referenceNumber = str_replace('"', "", $_POST['note']); //not subcategory - use $note
		$subcat_yn = "no";
	}

	$date = $_POST['date'];
	$state = $_POST['actState'];
		
	if ($valCatType == "mone"){
		$valCatCt = preg_replace('/[\$,]/', '', $valCatCt); //remove $ and common
		if ($valCatCt=="" or $valCatCt==0 or $valCatCt==0.00 or $valCatCt<0){
			$valCatCt = 0;
		}
		$valCatVal = $valCatCt;
		$valCatCt = 0;
		$valCatTx = "";
	}
	if ($valCatType == "numb"){
		$valCatCt = preg_replace('/[\$,]/', '', $valCatCt); //remove $ and common
		if ($valCatCt=="" or $valCatCt==0 or $valCatCt==0.00 or $valCatCt<0){
			$valCatCt = 0;
		}
		$valCatVal = 0;
		$valCatTx = "";
	}
	if ($valCatType == "text"){
		$valCatCt = str_replace('"', "", $valCatCt);
		$valCatTx = $valCatCt;
		$valCatVal = 0;
		$valCatCt = 0;
	}
		
	$orgDate = $date; //alter mm-dd-yyyy to yyyy-mm-dd
	$partsArr = explode("-",$orgDate);
	$date = $partsArr[2]."-".$partsArr[0]."-".$partsArr[1];
	
	if ($valCatType == "mone" or $valCatType == "numb" or $valCatType == "text"){
		try{//add specific member member attendance
			$result = $db->prepare("INSERT INTO $db_grpattend (group_id,owner_id,member_id,trkcategory_id,subcat_yn,name,value,count,text,date,note) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
				$result->execute(array($selectgrpid,$memberID,$selectnameid,$valCatId,$subcat_yn,$selectname,$valCatVal,$valCatCt,$valCatTx,$date,$referenceNumber));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		
	}
	
	if ($subNumArrCt > 0){
		$note = str_replace('"', "", $_POST['note']);
		try{					
			$result = $db->prepare("SELECT grpattend_id FROM $db_grpattend WHERE note=? ORDER BY grpattend_id ASC");
			$result->execute(array($referenceNumber));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		foreach ($result as $row) {
			$grpattendID = $row['grpattend_id'];
		}
		try{//set note to its value
			$result = $db->prepare("UPDATE $db_grpattend SET note=? WHERE note=?");
			$result->execute(array($note,$referenceNumber));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		for ($i = 0; $i < $subNumArrCt; $i++){
			try{//add specific member member attendance
				$result = $db->prepare("INSERT INTO $db_grpattend_sub (grpattend_id,trkcategory_id,value,count,text) VALUES (?,?,?,?,?)");
				$result->execute(array($grpattendID,$purCatArr[$i],$valCatValArr[$i],$valCatCtArr[$i],$valCatTxArr[$i]));
			} catch(PDOException $e) {
				echo "message001 - Sorry, system is experincing problem. Please check back.";
			}
		}
	}
	echo "done";

?>