<?php
	if ($_SESSION['login']=="yes" and $_SESSION['log_ss'] != ""){
		$memberID = $_SESSION['mid'];
		$projectInfo = new projectInfoC();
		$budgetTotInst = new budgetInfoC();
		$memberTotInst = new memberInfoC();
		
		$yes = "yes";
		$membertrackID = $_GET['mtid'];
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
			$result = $db->prepare("SELECT name,date FROM $db_membertrack WHERE membertrack_id=?");
			$result->execute(array($membertrackID));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		foreach ($result as $row){
			$budgetName = $row['name'];
			$date = $row['date']; //alter yyyy-mm-dd to mm-dd-yyyy mx002gb
			$partsArr = explode("-",$date);
			$date = $partsArr[1]."/".$partsArr[2]."/".$partsArr[0];
		}
/////////////////////////////////////////////////////////////////
		$memberListIdsArr = array();
		$memberListIdsArrCt = 0;
		try{					
			$result = $db->prepare("SELECT member_id FROM $db_membertrack_amt WHERE membertrack_id=?");
			$result->execute(array($membertrackID));
		} catch(PDOException $e) {
			echo "message009 - Sorry, system is experincing problem. Please check back.";
		}	
		foreach ($result as $row) {
			$memberListIdsArr[$memberListIdsArrCt] = $row['member_id'];
			$memberListIdsArrCt++;
		}
		
		//get all members
		$memberList = "";
		$allTotAmount = 0;	
		for ($i = 0; $i < $memberListIdsArrCt; $i++){
			try{					
				$result = $db->prepare("SELECT member_id,first_name,last_name FROM $db_member WHERE consortium_id=? AND member_id=?");
				$result->execute(array($consortiumID,$memberListIdsArr[$i]));
			} catch(PDOException $e) {
				print "<script> self.location='".$index_url."?err=d1000'; </script>";
			}
			$itemCount = $result->rowCount();
			$totAmount = 0;
			//if ($itemCount > 0){
				foreach ($result as $row) {				
					$memberName = str_replace('\\','',$row['first_name'])." ".str_replace('\\','',$row['last_name']);
					$memberNameNo = str_replace("'","",$memberName);
					$memberNameNo = str_replace('"','',$memberNameNo);
					$totAmount = $memberTotInst->getMembertrackAmtF($row['member_id'],$membertrackID);
					$totAmountDollar = $budgetTotInst->convertDollarF($totAmount);
					$memberList = $memberList."<tr><td>".$memberName."</td><td><a href='#' onclick='enterfamily(".$row['member_id'].",".'"'.$memberNameNo.'"'.")'>view</a></td><td><input type='text' class='sinform-mem' id='amt".$row['member_id']."' name='".$totAmount."' value='".$totAmountDollar."' onchange='uptmemfield(this.id,this.value,".$row['member_id'].",".$membertrackID.",".'"trkamt"'.")'></td></tr>";
				}
			//}
		}	
		$allTotAmount = $memberTotInst->getMembertrackTotF($membertrackID);
		$allTotAmount = $budgetTotInst->convertDollarF($allTotAmount);
/////////////////////////////////////////////////////////////////		
		
	}else{  //if statement - not login
		print "<script> self.location='".$index_url."'; </script>";
	}
?>