<?php
	if ($_SESSION['login']=="yes" and $_SESSION['log_ss'] != ""){
		$memberID = $_SESSION['mid'];
		$projectInfo = new projectInfoC();
		$budgetTotInst = new budgetInfoC();
		$memberTotInst = new memberInfoC();		
		$groupSetting = "Group";		
		$yes = "yes";
		$no = "no";
		$todayDate = date('Y-m-d');
		$organID = $_GET['oid'];
		$groupID = $_GET['gid'];
				
		try{//get consortiumID
			$result = $db->prepare("SELECT consortium_id FROM $db_member WHERE member_id=?");
			$result->execute(array($memberID)); 
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		foreach ($result as $row){
			$consortID = $row['consortium_id'];
		}
		
		try{//get license package
			$result = $db->prepare("SELECT $db_license_type.license_type_id,$db_license_type.package FROM $db_consortium,$db_license_type WHERE $db_consortium.license_id=$db_license_type.license_type_id AND $db_consortium.consortium_id=? ");
			$result->execute(array($consortID)); 
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		foreach ($result as $row){
			$licPackage = $row['package'];
			$licenseID =  $row['license_type_id'];
		}
		
		$groupList = "";
		if ($_GET['gid'] != "new"){
			$groupID = $_GET['gid'];
			try{//get avaiable orgIDs
				$result = $db->prepare("SELECT group_name,group_id,track FROM $db_group WHERE group_id=?");
				$result->execute(array($groupID)); 
			} catch(PDOException $e) {
				print "<script> self.location='".$index_url."?err=d1000'; </script>";
			}
			$itemCount = $result->rowCount();
			if ($itemCount > 0){
				foreach ($result as $row){
					$groupList = $groupList."<tr><td><a href='".$mx005gt."&sid=".$licenseID."&gid=".$row['group_id']."#organSetup'>".str_replace('\\','',$row['group_name'])."</a></td><td><button type='button' id='addNewMember' class='btn btn-xs btn-warning'>New member</button></td></tr>";
					$trackgrpID = "gt".$row['group_id'];
					if ($row['track'] == "yes"){
						$trackgroup = "gt".$row['group_id'];
					}else{
						$trackgroup = "";
					}
				}
			}else{
				$groupList = '<tr><td>No organization available</td><td><button type="button" id="addNewMember" class="btn btn-xs btn-warning">New member</button></td></tr>';
			}
			
			//create group member list including admin rights
			$memberList = '';
			$currentGrpMember = array();
			$currentGrpMemberCt = 0;
			try{//get all members for the given groupID
				$result = $db->prepare("SELECT member_id,group_rights,active FROM $db_group_rights WHERE group_id=?");
				$result->execute(array($groupID)); 
			} catch(PDOException $e) {
				print "<script> self.location='".$index_url."?err=d1000'; </script>";
			}
			$itemCount = $result->rowCount();
			if ($itemCount > 0){
				foreach ($result as $row){ 
					if($row['group_rights'] == 3){ //check for admin rights
						$memberList = $memberList.'<tr><td><input type="checkbox" id="a'.$row['member_id'].'" name="a'.$row['member_id'].'" checked="checked" value="yes"></td><td>'.$memberTotInst->memberFLNameF($row['member_id']).'</td>';
					}else{
						$memberList = $memberList.'<tr><td><input type="checkbox" id="a'.$row['member_id'].'" name="a'.$row['member_id'].'" value="yes"></td><td>'.$memberTotInst->memberFLNameF($row['member_id']).'</td>';
					}
					if ($row['active']=="yes"){
						$memberList = $memberList.'<td><input type="radio" id="ms'.$row['member_id'].'" name="ms'.$row['member_id'].'" checked="checked" value="yes" /> Yes</td><td><input type="radio" id="ms'.$row['member_id'].'" name="ms'.$row['member_id'].'" value="no" /> No</td><td><a href="#" onclick="delMemOut('.$row['member_id'].','.$groupID.')"><button class="btn btn-xs btn-danger"><i class="fa fa-times"></i> </button></a></td></tr>';
					}else{ //indicate inactive status
						$memberList = $memberList.'<td><input type="radio" id="ms'.$row['member_id'].'" name="ms'.$row['member_id'].'" value="yes" /> Yes</td><td><input type="radio" id="ms'.$row['member_id'].'" name="ms'.$row['member_id'].'" checked="checked" value="no" /> No</td><td><a href="#" onclick="delMemOut('.$row['member_id'].','.$groupID.')"><button class="btn btn-xs btn-danger"><i class="fa fa-times"></i> </button></a></td></tr>';
					}
					$currentGrpMember[$currentGrpMemberCt] = $row['member_id'];
					$currentGrpMemberCt++;
				}
			}else{
				$memberList = '<tr><td colspan="3"><label class="checkbox-inline">No member</label></td></tr>';
			}	
			
			//get all member belong to the Consortium
			$currentGrpMemberLenght = count($currentGrpMember);
			$curExistMemberList = "";
			try{//get memberID
				$result = $db->prepare("SELECT member_id FROM $db_member WHERE consortium_id=?");
				$result->execute(array($consortID));
			} catch(PDOException $e) {
				print "<script> self.location='".$index_url."?err=d1000'; </script>";
			}
			foreach ($result as $row){
				$memberFound = "no";
				for ($i = 0; $i < $currentGrpMemberLenght; $i++){
					if ($currentGrpMember[$i] == $row['member_id']){
						$memberFound = "yes";
					}
				}
				if ($memberFound == "no"){
					$curExistMemberList = $curExistMemberList.'<option value="'.$row['member_id'].'">'.$memberTotInst->memberFLNameF($row['member_id']).'</option>';
				}
			}
		}
				
		$chgState = "none";
		$groupButton = "Group";
		if ($_GET['gid'] != "" and $_GET['gid'] != "new"){
			$groupID = $_GET['gid'];
			try{//get pix name
				$result = $db->prepare("SELECT group_name,website FROM $db_group WHERE group_id=?");
				$result->execute(array($groupID)); 
			} catch(PDOException $e) {
				print "<script> self.location='".$index_url."?err=d1000'; </script>";
			}
			foreach ($result as $row){
				$groupName = str_replace('\\', '', $row['group_name']);
				$website = str_replace('\\','',$row['website']);
			}
			
			$groupPix = "";
			try{//get pix name
				$result = $db->prepare("SELECT pix_name FROM $db_group_pix WHERE group_id=?");
				$result->execute(array($groupID)); 
			} catch(PDOException $e) {
				print "<script> self.location='".$index_url."?err=d1000'; </script>";
			}
			foreach ($result as $row){
				$groupPix = "<a href='images/g_pix/".$row['pix_name']."' class='prettyPhoto'><img src='images/g_pix/".$row['pix_name']."'></a>";
			}			
			$chgState = "edit";
			$groupButton = "Update Group";
		}
		if ($_GET['gid'] == "new"){
			$chgState = "new";
			$groupID = "";
			$groupButton = "Create Group";
		}
		
		if($_SERVER['REQUEST_METHOD'] == "POST"){
			//new group
			if ($_POST['chgstate'] == "new"){
				if ($_POST['groupname'] != ""){//begin group
					$organID = $_POST['organID'];
					$groupname = $_POST['groupname'];
					//$groupname = preg_replace("/[^A-Za-z0-9\-()<>= '\/]/", "", $groupname);
					$groupname = str_replace('"', "", $groupname); //remove " from note
					$cwebsite = $_POST['cwebsite'];
					$cwebsite = str_replace('"', "", $cwebsite); //remove " from note
					
					try{//insert new group
						$result = $db->prepare("INSERT INTO $db_group (group_name,website,organization_id,track,active) VALUES (?,?,?,?,?)");
						$result->execute(array($groupname,$cwebsite,$organID,"no",$yes));
					} catch(PDOException $e) {
						print "<script> self.location='".$index_url."?err=d1000'; </script>";
					}
					try{//get new groupID
						$result = $db->prepare("SELECT group_id FROM $db_group WHERE organization_id=? ORDER BY group_id ASC");
						$result->execute(array($organID)); 
					} catch(PDOException $e) {
						print "<script> self.location='".$index_url."?err=d1000'; </script>";
					}
					foreach ($result as $row){
						$groupID = $row['group_id'];
					}
					try{//insert group rights for admin1 - commented Tou - 9/10/15
						$result = $db->prepare("INSERT INTO $db_group_rights (group_id,member_id,org_rights,group_rights,guest,active) VALUES (?,?,?,?,?,?)");
						$result->execute(array($groupID,$memberID,3,3,$no,$yes));
					} catch(PDOException $e) {
						print "<script> self.location='".$index_url."?err=d1000'; </script>";
					}
					
					//if upload image
					if ($_FILES['cmx_self']['name']!=""){
						$target_path_on = $_SERVER['DOCUMENT_ROOT']."/images/g_pix/"; //all pics save in the main root
						$target_path = $_SERVER['DOCUMENT_ROOT']."/images/g_pix/"; //all pics save in the main root
						$target_path = $target_path.$groupID.".". end(explode(".", $_FILES['cmx_self']['name']));
						$pix_name = $groupID.".". end(explode(".", $_FILES['cmx_self']['name']));
						try{//update project details
							$result = $db->prepare("INSERT INTO $db_group_pix (pix_name,group_id,active) VALUES (?,?,?)");
							$result->execute(array($pix_name,$groupID,$yes));
						} catch(PDOException $e) {
							print "<script> self.location='".$index_url."?err=d1000'; </script>";
						}
											
						if (file_exists($target_path_on.$pix_name)){
							unlink($target_path_on.$pix_name); //delete old file
						}
						if(move_uploaded_file($_FILES['cmx_self']['tmp_name'], $target_path)) {  
							try{//update logo name
								$result = $db->prepare("UPDATE $db_group_pix SET pix_name=? WHERE group_id=?");
								$result->execute(array($pix_name,$groupID));
							} catch(PDOException $e) {
								print "<script> self.location='".$index_url."?err=d1000'; </script>";
							}
						} 						
					}
				}//end of group
				print "<script> self.location='".$mx005gt."&sid=".$licenseID."&oid=".$organID."&gid=".$groupID."'; </script>";
			}//end of new process
			
			//edit consortium
			if ($_POST['chgstate'] == "edit"){
				if ($_POST['groupname'] != ""){
					$organID = $_POST['organID'];
					$groupID = $_POST['groupID'];
					
					$groupname = $_POST['groupname'];
					//$groupname = preg_replace("/[^A-Za-z0-9\-()<>= '\/]/", "", $groupname);
					$groupname = str_replace('"', "", $groupname); //remove " from note
					$cwebsite = $_POST['cwebsite'];
					$cwebsite = str_replace('"', "", $cwebsite); //remove " from note
					$track = "no";
					if (isset($_POST['gt'.$groupID])){
						$track = "yes";
					}
					
					try{//get avaiable orgIDs
						$result = $db->prepare("SELECT member_id FROM $db_group_rights WHERE group_id=? AND group_rights=?");
						$result->execute(array($groupID,3)); 
					} catch(PDOException $e) {
						print "<script> self.location='".$index_url."?err=d1000'; </script>";
					}
					$adminCount = $result->rowCount();
					$allAdminNoCt = 0;
					$allAdminYesCt = 0;
					if ($adminCount == 1){//only one admin
						foreach ($result as $row){
							$onlyAdminID = $row['member_id'];
						}
					}
					if ($adminCount > 1){//more than 1 admin
						foreach ($result as $row){
							$lastAdminID = $row['member_id'];
							$postMember = "a".$row['member_id'];
							if($_POST[$postMember] == "no"){
								$allAdminNoCt++;
							}else{
								$allAdminYesCt++;
							}
						}
						if ($allAdminNoCt == $adminCount){
							$adminCount = 1;
							$onlyAdminID = $lastAdminID;
						}
					}
					//process member admin rights
					try{//get avaiable orgIDs
						$result = $db->prepare("SELECT member_id,group_rights FROM $db_group_rights WHERE group_id=?");
						$result->execute(array($groupID)); 
					} catch(PDOException $e) {
						print "<script> self.location='".$index_url."?err=d1000'; </script>";
					}
					$itemCount = $result->rowCount();
					if ($itemCount > 0){
						foreach ($result as $row){
							$curMember = $row['member_id'];
							$postMember = "a".$row['member_id'];
							if($_POST[$postMember] == "yes"){
								$valRight = 3;
							}else{
								if ($adminCount == 1 and $curMember == $onlyAdminID){
									$valRight = 3;
								}else{
									$valRight = 1;
								}
							}
							//update member active status
							$activeMember = "ms".$row['member_id'];
							if($_POST[$activeMember] == "yes"){
								$staMem = "yes";
							}
							if($_POST[$activeMember] == "no"){
								$staMem = "no";
								$valRight = 0;
							}
							
							try{//update group rights
								$result = $db->prepare("UPDATE $db_group_rights SET group_rights=?,active=? WHERE group_id=? AND member_id=?");
								$result->execute(array($valRight,$staMem,$groupID,$curMember));
							} catch(PDOException $e) {
								print "<script> self.location='".$index_url."?err=d1000'; </script>";
							}
						}
					}
					
					try{//update profile
						$result = $db->prepare("UPDATE $db_group SET group_name=?,website=?,track=? WHERE group_id=?");
						$result->execute(array($groupname,$cwebsite,$track,$groupID));
					} catch(PDOException $e) {
						print "<script> self.location='".$index_url."?err=d1000'; </script>";
					}
					
					//if upload image
					if ($_FILES['cmx_self']['name']!=""){
						$target_path_on = $_SERVER['DOCUMENT_ROOT']."/images/g_pix/"; //all pics save in the main root
						$target_path = $_SERVER['DOCUMENT_ROOT']."/images/g_pix/"; //all pics save in the main root
						$target_path = $target_path.$groupID.".". end(explode(".", $_FILES['cmx_self']['name']));
						$pix_name = $groupID.".". end(explode(".", $_FILES['cmx_self']['name']));
					
						try{//get pix name
							$result = $db->prepare("SELECT pix_name FROM $db_group_pix WHERE groupID=?");
							$result->execute(array($groupID)); 
						} catch(PDOException $e) {
							print "<script> self.location='".$index_url."?err=d1000'; </script>";
						}
						foreach ($result as $row){
							$cur_pix = $row['pix_name'];
						}
						
						if (file_exists($target_path_on.$cur_pix)){
							unlink($target_path_on.$cur_pix); //delete
						}
						if(move_uploaded_file($_FILES['cmx_self']['tmp_name'], $target_path)) {  
							try{//update logo name
								$result = $db->prepare("UPDATE $db_group_pix SET pix_name=? WHERE groupID=?");
								$result->execute(array($pix_name,$groupID));
							} catch(PDOException $e) {
								print "<script> self.location='".$index_url."?err=d1000'; </script>";
							}
						} 						
					}
				}
				print "<script> self.location='".$mx005gt."&sid=".$licenseID."&oid=".$organID."&gid=".$groupID."'; </script>";
			}//end of edit consortium
			
		}//end of form post
	}else{  //if statement - not login
		print "<script> self.location='".$index_url."'; </script>";
	}
?>