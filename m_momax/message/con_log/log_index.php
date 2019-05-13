<?php
	if ($_SESSION['login']=="yes" and $_SESSION['log_ss'] != ""){
		$memberID = $_SESSION['mid'];
		$memberTotInst = new memberInfoC();
		$accountInfo = new accountInfoC();
		$budgetTotInst = new budgetInfoC();
		$messageRec = "";
		$messageSen = "";
		$reqStatus = "";
		$yes = "yes";
		
		if ($_GET['sid'] != "" and $_GET['sid'] != $memberID){
			$selectID = $_GET['sid'];
			try{//get all unqine groupID's
				$result = $db->prepare("SELECT member_id,first_name,last_name FROM $db_member WHERE member_id=?");
				$result->execute(array($selectID));
			} catch(PDOException $e) {
				print "<script> self.location='".$index_url."?err=d1000'; script>";
			}
			$foundItems = $result->rowCount(); 
			if ($foundItems > 0){
				foreach ($result as $row) {
					$selectName = str_replace('\\','',$row['first_name'])." ".str_replace('\\','',$row['last_name']);
					$selectID = $row['member_id'];
				}
			}
		}
		
		try{//Message Receive		
			$result = $db->prepare("SELECT request_id,sender_id,subject,mdate,status FROM $db_request WHERE receiver_id=? ORDER BY request_id DESC");
			$result->execute(array($memberID));
			
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		$itemCount = $result->rowCount();
		if ($itemCount > 0){
			foreach ($result as $row) {
				$messageRec = $messageRec."<tr><td>".$memberTotInst->memberNameF($row['sender_id'])."</td><td><a href='#' onclick='changeMsgStatus(".$row['request_id'].",1)'>".str_replace('\\','',$row['subject'])."</a></td><td>".date('m/d/Y',strtotime($row['mdate']))."</td><td>".$row['status']."</td></tr>";
			}
		}else{
			$messageRec = "<tr><td colspan=4>No request available</td></tr>";
		}
		try{//message Send			
			$result = $db->prepare("SELECT request_id,receiver_id,subject,mdate,status FROM $db_request WHERE sender_id=? ORDER BY request_id DESC");
			$result->execute(array($memberID));
			
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		$itemCount = $result->rowCount();
		if ($itemCount > 0){
			foreach ($result as $row) {
				$messageSen = $messageSen."<tr><td>".$memberTotInst->memberNameF($row['receiver_id'])."</td><td><a href='#' onclick='changeMsgStatus(".$row['request_id'].",2)'>".str_replace('\\','',$row['subject'])."</a></td><td>".date('m/d/Y',strtotime($row['mdate']))."</td><td>".$row['status']."</td></tr>";
				
			}
		}else{
			$messageSen = "<tr><td colspan=4>No request available</td></tr>";
		}
		
		//get category data
		//$catItemsList = "";
		//try{
		//	$result = $db->prepare("SELECT category_id,category FROM $db_category WHERE member_id=? AND active=? ORDER BY category ASC");
		//	$result->execute(array($memberID,$yes));
		//} catch(PDOException $e) {
		//	print "<script> self.location='".$index_url."?err=d1000'; script>";
		//} 
		//$catItems = $result->rowCount(); 
		//if ($catItems > 0){
		//	foreach ($result as $row) {
		//		$catItemsList = $catItemsList."<option value='".$row['category_id']."'>".$row['category']."</option>";
		//	}
		//}
		//get budget list
		//$budList = "";
		//$budName = array();
		//try{//get all unqine groupID's
		//	$result = $db->prepare("SELECT DISTINCT budget_id FROM $db_budget_rights WHERE member_id=? AND active=?");
		//	$result->execute(array($memberID,$yes));
		//} catch(PDOException $e) {
		//	print "<script> self.location='".$index_url."?err=d1000'; script>";
		//}
		//$foundItems = $result->rowCount(); 
		//if ($foundItems > 0){
		//	foreach ($result as $row) {
		//		$budName = $budgetTotInst->budgetNameF($row['budget_id']);
		//		$budList = $budList."<option value='".$row['budget_id']."'>".$budName[0]."</option>";
		//	}
		//}else{
		//	$budList = $budList."<option value='none'>No Budget</option>";
		//}
		//get account list
		//$actList = "";
		//$actName = array();
		//try{//get all unqine groupID's
		//	$result = $db->prepare("SELECT DISTINCT account_id FROM $db_account_rights WHERE member_id=? AND active=?");
		//	$result->execute(array($memberID,$yes));
		//} catch(PDOException $e) {
		//	print "<script> self.location='".$index_url."?err=d1000'; script>";
		//}
		//$foundItems = $result->rowCount(); 
		//if ($foundItems > 0){
		//	foreach ($result as $row) {
		//		$actName = $accountInfo->accountNameF($row['account_id']);
		//		$actList = $actList."<option value='".$row['account_id']."'>".$actName[0]."</option>";
		//	}
		//}else{
		//	$actList = $actList."<option value='none'>No Account</option>";
		//}
		
	}else{  //if statement - not login
		print "<script> self.location='".$index_url."'; </script>";
	}
?>