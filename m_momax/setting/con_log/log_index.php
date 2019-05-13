<?php
	if ($_SESSION['login']=="yes" and $_SESSION['log_ss'] != ""){
		$memberID = $_SESSION['mid'];
		$projectInfo = new projectInfoC();
		$budgetTotInst = new budgetInfoC();
		$memberTotInst = new memberInfoC();
		$profileSetting = "Profile Setting";
		$generalSetting = "General Setting";
		$adminSetting = "Admin Setting";
		$yes = "yes";
		$yeGuest = "no";
		$showGroupSetup = "no";
		$groupSetup = "";
		try{				
			$result = $db->prepare("SELECT $db_member.consortium_id,$db_consortium.consortium,$db_consortium.license_id,$db_consortium.admin1,$db_consortium.admin2 FROM $db_member,$db_consortium WHERE $db_member.member_id=? AND $db_member.consortium_id=$db_consortium.consortium_id AND $db_member.active=?");
			$result->execute(array($memberID,$yes));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		$itemCount = $result->rowCount();
		if ($itemCount > 0){
			foreach ($result as $row){
				$consortiumID = $row['consortium_id'];
				$licenseID =  $row['license_id'];
				if ($licenseID != "1000" and ($memberID == $row['admin1'] or $memberID == $row['admin2'])){
					$showGroupSetup = "yes";
				}
			}
		}
		
		try{//create list				
			$result = $db->prepare("SELECT admin1,admin2 FROM $db_organization WHERE consortium_id=? AND active=?");
			$result->execute(array($consortiumID,$yes));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		$itemCount = $result->rowCount();
		if ($itemCount > 0){
			foreach ($result as $row){
				if ($licenseID != "1000" and ($memberID == $row['admin1'] or $memberID == $row['admin2'])){
					$showGroupSetup = "yes";
				}
			}
		}
		try{//create account list				
			$result = $db->prepare("SELECT group_rights FROM $db_group_rights WHERE member_id=? AND active=?");
			$result->execute(array($memberID,$yes));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		$itemCount = $result->rowCount();
		if ($itemCount > 0){
			foreach ($result as $row){
				if ($row['group_rights'] == 3 and $licenseID != "1000"){
					$showGroupSetup = "yes";
				}
			}
		}
		
		if ($showGroupSetup == "yes"){
			$groupSetup = $groupSetup.'<tr><th><a href="'.$mx005gs.'">Group Setting</a><br /><span class="smtext">Add, edit, and delete groups and members.</span></th></tr>';
		}
	}else{  //if statement - not login
		print "<script> self.location='".$index_url."'; </script>";
	}
?>