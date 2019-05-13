<?php
	if ($_SESSION['login']=="yes" and $_SESSION['log_ss'] != ""){
		$memberID = $_SESSION['mid'];
		$generalInfo = new generalInfoC();		
		$profileSetting = "Post Setting";		
		$yes = "yes";
		
		$grpList = "";
		$grpCt = 0;
		$grpListArr = "";
		try{//
			$result = $db->prepare("SELECT DISTINCT group_id FROM $db_group_rights WHERE member_id=? AND active=?");
			$result->execute(array($memberID,$yes));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; script>";
		}
		$foundItems = $result->rowCount(); 
		if ($foundItems > 0){
			foreach ($result as $row) {
				try{//check if group rights is found
					$resultGrp = $db->prepare("SELECT member_id FROM $db_post_group WHERE member_id=? AND group_id=?");
					$resultGrp->execute(array($memberID,$row['group_id']));
				} catch(PDOException $e) {
					print "<script> self.location='".$index_url."?err=d1000'; script>";
				}
				$foundGrp = $resultGrp->rowCount();
				if ($foundGrp > 0){
					$grpList = $grpList.'<label class="checkbox-inline"><input type="checkbox" checked="checked" id="g'.$row['group_id'].'" value="'.$row['group_id'].'">'.$generalInfo->returnGroupNameF($row['group_id']).'</label><br>';
				}else{
					$grpList = $grpList.'<label class="checkbox-inline"><input type="checkbox" id="g'.$row['group_id'].'" value="'.$row['group_id'].'">'.$generalInfo->returnGroupNameF($row['group_id']).'</label><br>';
				}
				$grpListArr = $grpListArr."g".$row['group_id'].",";
				$grpCt++;
			}
		}else{
			$grpList = "No group available";
		}
		$grpListArr = trim($grpListArr, ",");		
		
		try{//
			$result = $db->prepare("SELECT rights_type_id FROM $db_post_rights WHERE member_id=?");
			$result->execute(array($memberID));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; script>";
		}
		foreach ($result as $row) {
			$curRights = $row['rights_type_id'];
		}
		
	}else{  //if statement - not login
		print "<script> self.location='".$index_url."'; </script>";
	}
?>