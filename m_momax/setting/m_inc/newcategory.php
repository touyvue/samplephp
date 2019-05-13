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
		//$catName = preg_replace("/[^A-Za-z0-9\-()<>= '\/]/", "", $catName);
		$catName = str_replace('"', "", $catName); //remove " from note
		$catDesc = $_POST['catDesc'];
		//$catDesc = preg_replace("/[^A-Za-z0-9\-()<>= '\/]/", "", $catDesc);
		$catDesc = str_replace('"', "", $catDesc); //remove " from note
		
		$orderNo = 0;
		try{//get new accountID
			$result = $db->prepare("SELECT list_order FROM $db_category WHERE member_id=? ORDER BY list_order ASC");
			$result->execute(array($memberID)); //get recurring info
		} catch(PDOException $e) {
			echo "message002 - Sorry, system is experincing problem. Please check back.";
		}
		foreach ($result as $row){
			$orderNo = $row['list_order'];
		}
		$orderNo = $orderNo + 1;
		try{//update project details
			$result = $db->prepare("INSERT INTO $db_category (list_order,category,description,member_id,active) VALUES (?,?,?,?,?)");
			$result->execute(array($orderNo,$catName,$catDesc,$memberID,$active));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}	
		echo "done";
	}//end of add state
	
	//get account info
	if ($state == "getdata"){
		$categoryID = $_POST['catID'];
	
		try{//get new accountID
			$result = $db->prepare("SELECT category_id,category,description FROM $db_category WHERE member_id=? AND category_id=?");
			$result->execute(array($memberID,$categoryID)); //get recurring info
		} catch(PDOException $e) {
			echo "message004 - Sorry, system is experincing problem. Please check back.";
		}
		foreach ($result as $row){
			$categoryInfo = '{"id":"'.$row['category_id'].'","name":"'.str_replace('\\','',$row['category']).'","desc":"'.str_replace('\\','',$row['description']).'"}';
		}
		echo $categoryInfo;
	}
	
	//update account info
	if ($state == "edit"){
		$categoryID = $_POST['catID'];
		
		$catName = $_POST['catName'];
		//$catName = preg_replace("/[^A-Za-z0-9\-()<>= '\/]/", "", $catName);
		$catName = str_replace('"', "", $catName); //remove " from note
		$catDesc = $_POST['catDesc'];
		//$catDesc = preg_replace("/[^A-Za-z0-9\-()<>= '\/]/", "", $catDesc);
		$catDesc = str_replace('"', "", $catDesc); //remove " from note
		
		try{//update project details
			$result = $db->prepare("UPDATE $db_category SET category=?,description=? WHERE category_id=?");
			$result->execute(array($catName,$catDesc,$categoryID));
		} catch(PDOException $e) {
			echo "message005 - Sorry, system is experincing problem. Please check back.";
		}
	}
	
	//delete account
	if ($state == "delete"){
		$categoryID = $_POST['catID'];
		$catName = $_POST['catName'];
		$catDesc = $_POST['catDesc'];
				
		try{//delete account
			$result = $db->prepare("DELETE FROM $db_category WHERE category_id=? AND member_id=?");
			$result->execute(array($categoryID,$memberID));
		} catch(PDOException $e) {
			echo "message006 - Sorry, system is experincing problem. Please check back.";
		}
	}
?>