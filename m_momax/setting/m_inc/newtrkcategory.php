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
		$catName = $_POST['catName'];
		$catName = str_replace('"', "", $catName); //remove " from note
		$catDesc = $_POST['catDesc'];
		$catDesc = str_replace('"', "", $catDesc); //remove " from note
		$catType = $_POST['catType'];
		$subcat = $_POST['subcat'];
		
		if ($catType == "number"){
			$catType = "numb";
		}
		if ($catType == "money"){
			$catType = "mone";
		}
		if ($catType == "text"){
			$catType = "text";
		}
		
		$orderNo = 1;
		try{//get new accountID
			$result = $db->prepare("SELECT list_order FROM $db_trkcategory WHERE member_id=? ORDER BY list_order ASC");
			$result->execute(array($memberID)); 
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		foreach ($result as $row){
			$orderNo = $row['list_order'];
		}
		$orderNo = $orderNo + 1;
		try{//update project details
			$result = $db->prepare("INSERT INTO $db_trkcategory (list_order,category,sub_yn,type,description,member_id,active) VALUES (?,?,?,?,?,?,?)");
			$result->execute(array($orderNo,$catName,$subcat,$catType,$catDesc,$memberID,$active));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}	
		echo "done";
	}//end of add state
	
	//get account info
	if ($state == "getdata"){
		$categoryID = $_POST['catID'];
	
		try{//get new accountID
			$result = $db->prepare("SELECT trkcategory_id,category,sub_yn,type,description FROM $db_trkcategory WHERE member_id=? AND trkcategory_id=?");
			$result->execute(array($memberID,$categoryID)); 
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		foreach ($result as $row){
			if ($row['type'] == "numb"){
				$type = "number";
			}
			if ($row['type'] == "mone"){
				$type = "money";
			}
			if ($row['type'] == "text"){
				$type = "text";
			}
			$categoryInfo = '{"id":"'.$row['trkcategory_id'].'","name":"'.str_replace('\\','',$row['category']).'","subcat":"'.$row['sub_yn'].'","type":"'.$type.'","desc":"'.str_replace('\\','',$row['description']).'"}';
		}
		echo $categoryInfo;
	}
	
	//update account info
	if ($state == "edit"){
		$categoryID = $_POST['catID'];
		$catName = $_POST['catName'];
		$catName = str_replace('"', "", $catName); //remove " from note
		$catDesc = $_POST['catDesc'];
		$catDesc = str_replace('"', "", $catDesc); //remove " from note
		$catType = $_POST['catType'];
		$subcat = $_POST['subcat'];
		if ($catType == "number"){
			$catType = "numb";
		}
		if ($catType == "money"){
			$catType = "mone";
		}
		if ($catType == "text"){
			$catType = "text";
		}
		
		try{//update project details
			$result = $db->prepare("UPDATE $db_trkcategory SET category=?,sub_yn=?,type=?,description=? WHERE trkcategory_id=?");
			$result->execute(array($catName,$subcat,$catType,$catDesc,$categoryID));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
	}
	
	//delete account
	if ($state == "delete"){
		$categoryID = $_POST['catID'];
		$catName = $_POST['catName'];
		$catDesc = $_POST['catDesc'];
		try{//delete account
			$result = $db->prepare("DELETE FROM $db_trkcategory WHERE trkcategory_id=? AND member_id=?");
			$result->execute(array($categoryID,$memberID));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
	}
?>