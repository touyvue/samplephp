<?php
	if ($_SESSION['login']=="yes" and $_SESSION['log_ss'] != ""){
		$memberID = $_SESSION['mid'];
		$accountInfo = new accountInfoC();
		$projectInfo = new projectInfoC();
		$budgetTotInst = new budgetInfoC();
		$memberTotInst = new memberInfoC();
		$generalInfo = new generalInfoC();
		$accountSetting = "Share Accounts";
		$yes = "yes";
		
		try{//get consortiumID
			$result = $db->prepare("SELECT consortium_id FROM $db_member WHERE member_id=?");
			$result->execute(array($memberID)); 
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		foreach ($result as $row){
			$consortID = $row['consortium_id'];
		}
		try{//count accounts				
			$result = $db->prepare("SELECT account_id FROM $db_account WHERE member_id=? AND active=?");
			$result->execute(array($memberID,$yes));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		$accountCount = $result->rowCount();
		
		////////////////////////////////////////////////////////
		try{//get array of all organizationID/Name				
			$result = $db->prepare("SELECT organization_id FROM $db_organization WHERE consortium_id=?");
			$result->execute(array($consortID));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		$itemCount = $result->rowCount();
		$organCt = 0;
		$organID = array();
		foreach ($result as $row){
			$organID[$organCt] = $row['organization_id'];
			$organCt++;
		}
		$allGrpMembers = array();
		$allGrpMembersCt = 0;
		if ($organCt > 0){
			for ($i = 0; $i < $organCt; $i++){
				try{//get all my groups				 
					$result = $db->prepare("SELECT $db_group.group_id,$db_group_rights.member_id FROM $db_group,$db_group_rights WHERE $db_group.organization_id=? AND $db_group.group_id=$db_group_rights.group_id AND $db_group_rights.member_id=? AND $db_group_rights.active=?");
					$result->execute(array($organID[$i],$memberID,$yes));
				} catch(PDOException $e) {
					print "<script> self.location='".$index_url."?err=d1000'; </script>";
				}
				foreach ($result as $row){
					$allGrpMembers[$allGrpMembersCt] = $row['group_id'];
					$allGrpMembersCt++;
				}
			}//end for loop
		}
		
		//get all member belong to the Consortium
		$shareMember = array();
		$shareMemberCt = 0;
		$curExistMemberList = "";
		if ($allGrpMembersCt > 0){
			for ($i = 0; $i < $allGrpMembersCt; $i++){
				try{
					$result = $db->prepare("SELECT DISTINCT member_id FROM $db_group_rights WHERE group_id=? AND active=?");
					$result->execute(array($allGrpMembers[$i],$yes));
				} catch(PDOException $e) {
					print "<script> self.location='".$index_url."?err=d1000'; </script>";
				}
				
				foreach ($result as $row){
					if ($shareMemberCt == 0 and $row['member_id'] != $memberID){
						$shareMember[$shareMemberCt] = $row['member_id'];
						$shareMemberCt++;
						$curExistMemberList = $curExistMemberList.'<tr><td><a href="#" onclick="shareAccount('.$row['member_id'].','.$memberID.','.$accountCount.',1)">'.$memberTotInst->memberFLNameF($row['member_id']).'</td><td>'.$memberTotInst->memberGroupF($allGrpMembers[$i]).'</td><td></td></tr>';
					}else{
						$shareMemIDAdd = "no";
						for ($j = 0; $j < count($shareMember); $j++){
							if ($row['member_id'] == $shareMember[$j]){
								$shareMemIDAdd = "yes";
							}
						}
						if ($shareMemIDAdd == "no" and $row['member_id'] != $memberID){
							$shareMember[$shareMemberCt] = $row['member_id'];
							$shareMemberCt++;
							$curExistMemberList = $curExistMemberList.'<tr><td><a href="#" onclick="shareAccount('.$row['member_id'].','.$memberID.','.$accountCount.',1)">'.$memberTotInst->memberFLNameF($row['member_id']).'</td><td>'.$memberTotInst->memberGroupF($allGrpMembers[$i]).'</td><td></td></tr>';
						}
					}
				}//end of foreach
			}//end of for loop
		}		
		////////////////////////////////////////////////////////
		
		if ($curExistMemberList == ""){
			$curExistMemberList = "<tr><td colspan='3'>No group member</td></tr>";
		}
		
		$accountList = "";
		try{//create account list				
			$result = $db->prepare("SELECT account_id,name FROM $db_account WHERE member_id=? AND active=? ORDER BY name");
			$result->execute(array($memberID,$yes));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		$itemCount = $result->rowCount();
		if ($itemCount > 0){
			$accountList = $accountList.'<div class="form-group"><label class="col-lg-4 control-label">MY ACCOUNTS:</label><div class="col-lg-7"><div class="radio"><i>Level1(read only), Level2(edit), & Level3(add/delete)</i></div></div></div>';
			foreach ($result as $row){
				$accountList = $accountList.'<div class="form-group"><label class="col-lg-4 control-label">'.str_replace('\\','',$row['name']).' -</label><div class="col-lg-6">';
				$accountList = $accountList.'<div class="radio"><label><input type="radio" name="'.$row['account_id'].'" id="a'.$row['account_id'].'" value="0">No &nbsp;</label>';
				$accountList = $accountList.'<label><input type="radio" name="'.$row['account_id'].'" id="b'.$row['account_id'].'" value="1">Level1&nbsp;</label>';
				$accountList = $accountList.'<label><input type="radio" name="'.$row['account_id'].'" id="c'.$row['account_id'].'" value="2">Level2&nbsp;</label>';
				$accountList = $accountList.'<label><input type="radio" name="'.$row['account_id'].'" id="d'.$row['account_id'].'" value="3">Level3</label></div></div></div>';
			}
		}else{
			$accountList = "No accounts avaialable";
		}
	}else{  //if statement - not login
		print "<script> self.location='".$index_url."'; </script>";
	}
?>