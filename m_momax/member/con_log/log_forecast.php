<?php
	if ($_SESSION['login']=="yes" and $_SESSION['log_ss'] != ""){
		$memberID = $_SESSION['mid'];
		$projectInfo = new projectInfoC();
		$budgetTotInst = new budgetInfoC();
		$memberTotInst = new memberInfoC();
		
		$yes = "yes";
		$memberbudgetID = $_GET['mbid'];
		try{ //get member's consortiumID and licenseID			
			$result = $db->prepare("SELECT $db_member.consortium_id,$db_consortium.consortium,$db_consortium.license_id,$db_consortium.admin1,$db_consortium.admin2 FROM $db_member,$db_consortium WHERE $db_member.member_id=? AND $db_member.consortium_id=$db_consortium.consortium_id AND $db_member.active=?");
			$result->execute(array($memberID,$yes));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		foreach ($result as $row){
			$consortiumID = $row['consortium_id'];
			$licenseID =  $row['license_id'];
		}
		try{					
			$result = $db->prepare("SELECT name,start,end FROM $db_memberbudget WHERE memberbudget_id=?");
			$result->execute(array($memberbudgetID));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		foreach ($result as $row){
			$budgetName = $row['name'];
			$startDate = $row['start']; //alter yyyy-mm-dd to mm-dd-yyyy mx002gb
			$partsArr = explode("-",$startDate);
			$startDate = $partsArr[1]."/".$partsArr[2]."/".$partsArr[0];
			$endDate = $row['end']; //alter yyyy-mm-dd to mm-dd-yyyy mx002gb
			$partsArr = explode("-",$endDate);
			$endDate = $partsArr[1]."/".$partsArr[2]."/".$partsArr[0];
		}
		try{ //check if a track budget is set to this forecast budget				
			$result = $db->prepare("SELECT membertrack_id FROM $db_membertrack WHERE memberbudget_id=?");
			$result->execute(array($memberbudgetID));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		$membertrackIDArr = array();
		$membertrackIDCt = 0;
		foreach ($result as $row){
			$membertrackIDArr[$membertrackIDCt] = $row['membertrack_id'];
			$membertrackIDCt++;
		}
/////////////////////////////////////////////////////////////////
		//get all members
		$memberList = "";	
		$allTotAmount = 0;	
		try{					
			$result = $db->prepare("SELECT member_id,first_name,last_name FROM $db_member WHERE consortium_id=? AND active=? ORDER BY first_name ASC");
			$result->execute(array($consortiumID,$yes));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		$itemCount = $result->rowCount();
		$totAmount = 0;
		$allTotAmount = 0;
		$actAmount = 0;
		$allActTotAmount = 0;
		
		if ($itemCount > 0){
			foreach ($result as $row) {				
				$memberName = str_replace('\\','',$row['first_name'])." ".str_replace('\\','',$row['last_name']);
				$memberNameNo = str_replace("'","",$memberName);
				$memberNameNo = str_replace('"','',$memberNameNo);
				$totAmount = $memberTotInst->getMemberbudgetAmtF($row['member_id'],$memberbudgetID);
				$memberList = $memberList."<tr><td>".$memberName."</td><td><a href='#' onclick='enterfamily(".$row['member_id'].",".'"'.$memberNameNo.'"'.")'>view</a></td><td><input type='text' class='sinform-mem' id='amt".$row['member_id']."' name='".$totAmount."' value='".$budgetTotInst->convertDollarF($totAmount)."' onchange='uptmemfield(this.id,this.value,".$row['member_id'].",".$memberbudgetID.",".'"memamt"'.")'></td>";
				if ($membertrackIDCt > 0){
					for ($i = 0; $i < $membertrackIDCt; $i++){
						$actAmount = $actAmount + $memberTotInst->getMembertrackAmtF($row['member_id'],$membertrackIDArr[$i]);
					}
				}
				$memberList = $memberList."<td>".$budgetTotInst->convertDollarF($actAmount)."</td></tr>";
				$allActTotAmount = $allActTotAmount + $actAmount;
				$actAmount = 0;
			}
		}
		$allTotAmount = $memberTotInst->getMemberbudgetTotF($memberbudgetID);
		$allTotAmount = $budgetTotInst->convertDollarF($allTotAmount);
		$allActTotAmount = $budgetTotInst->convertDollarF($allActTotAmount);

/////////////////////////////////////////////////////////////////		
		
	}else{  //if statement - not login
		print "<script> self.location='".$index_url."'; </script>";
	}
?>