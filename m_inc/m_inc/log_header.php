<?php
	if ($_SESSION['login']=="yes" and $_SESSION['log_ss'] != ""){
		$memberID = $_SESSION['mid'];
		$strThisMonth = date("Y-m-1");
		$thisMonth = date("m");
		$thisYear = date("Y");
		$strLastMonth = date('Y-m-1', strtotime($strThisMonth. ' - 1 months'));
		$endLastMonth = date('Y-m-t', strtotime($strLastMonth));
		
		$strThisWeek = date('Y-m-d', mktime(1, 0, 0, date('m'), date('d')-date('w'), date('Y')));
		$endThisWeek = date('Y-m-d', strtotime($strThisWeek. ' + 6 days'));
		$strLastWeek = date('Y-m-d', strtotime($strThisWeek. ' - 7 days'));
		$endLastWeek = date('Y-m-d', strtotime($strLastWeek. ' + 6 days'));
			
		try{//get consortiumID
			$result = $db->prepare("SELECT consortium_id,first_name FROM $db_member WHERE member_id=?");
			$result->execute(array($memberID)); //get recurring info
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		foreach ($result as $row){
			$conID = $row['consortium_id'];
			$firstName = $row['first_name'];
		}
		try{//get consortiumID
			$result = $db->prepare("SELECT organization_id FROM $db_organization WHERE consortium_id=?");
			$result->execute(array($conID)); //get recurring info
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		foreach ($result as $row){
			$orgID = $row['organization_id'];
		}
		try{//get license package
			$result = $db->prepare("SELECT license_id FROM $db_consortium WHERE consortium_id=? ");
			$result->execute(array($conID)); //get recurring info
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		foreach ($result as $row){
			$licID =  $row['license_id'];
		}
		if ($licID == "1003"){
			try{//get consortiumID
				$result = $db->prepare("SELECT consortium,email,website FROM $db_consortium WHERE consortium_id=?");
				$result->execute(array($conID)); //get recurring info
			} catch(PDOException $e) {
				print "<script> self.location='".$index_url."?err=d1000'; </script>";
			}
			foreach ($result as $row){
				$disName =  str_replace('\\','',$row['consortium']);
				$disNameShort =  str_replace('\\','',substr($row['consortium'],0,25));
				$disEmail = $row['email'];
				$disWebsite = $row['website'];
			}
		}
		if ($licID == "1002" or $licID == "1001" or $licID == "1000"){
			try{//get consortiumID
				$result = $db->prepare("SELECT organization,email,website FROM $db_organization WHERE consortium_id=?");
				$result->execute(array($conID)); //get recurring info
			} catch(PDOException $e) {
				print "<script> self.location='".$index_url."?err=d1000'; </script>";
			}
			foreach ($result as $row){
				$disName =  str_replace('\\','',$row['organization']);
				$disNameShort =  str_replace('\\','',substr($row['organization'],0,25));
				$disEmail = $row['email'];
				$disWebsite = $row['website'];
			}
		}
		
		//get attendance and visitors
		$thisweekAttend = 0;
		try{//get consortiumID
			$result = $db->prepare("SELECT count FROM $db_grpattend WHERE owner_id=? AND trkcategory_id=? AND date>=? AND date<=?");
			$result->execute(array($memberID,1000,$strThisWeek,$endThisWeek)); 
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		foreach ($result as $row){
			$thisweekAttend = $thisweekAttend + $row['count'];
		}
		//get attendance 
		$lastweekAttend = 0;
		try{
			$result = $db->prepare("SELECT count FROM $db_grpattend WHERE owner_id=? AND trkcategory_id=? AND date>=? AND date<=?");
			$result->execute(array($memberID,1000,$strLastWeek,$endLastWeek)); 
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		foreach ($result as $row){
			$lastweekAttend = $lastweekAttend + $row['count'];
		}
		
		//get visitors
		$thismonVisitor = 0;
		try{
			$result = $db->prepare("SELECT count FROM $db_grpattend WHERE owner_id=? AND trkcategory_id=? AND MONTH(date)=? AND YEAR(date)=?");
			$result->execute(array($memberID,1003,$thisMonth,$thisYear)); 
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		foreach ($result as $row){
			$thismonVisitor = $thismonVisitor + $row['count'];
		}
		$lastmonVisitor = 0;
		try{//last month visitors
			$result = $db->prepare("SELECT count FROM $db_grpattend WHERE owner_id=? AND trkcategory_id=? AND date>=? AND date<=?");
			$result->execute(array($memberID,1003,$strLastMonth,$endLastMonth)); 
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		foreach ($result as $row){
			$lastmonVisitor = $lastmonVisitor + $row['count'];
		}
		
		//get adult members count
		$adultCount = 0;
		try{//last month visitors
			$result = $db->prepare("SELECT group_id FROM $db_group_rights WHERE group_id=?");
			$result->execute(array(100100132)); 
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		foreach ($result as $row){
			$adultCount = $adultCount + 1;
		}
		//get youth members count
		$youthCount = 0;
		try{//last month visitors
			$result = $db->prepare("SELECT group_id FROM $db_group_rights WHERE group_id=?");
			$result->execute(array(100100134)); 
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		foreach ($result as $row){
			$youthCount = $youthCount + 1;
		}
	}
?>