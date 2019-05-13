<?php
	if ($_SESSION['login']=="yes" and $_SESSION['log_ss'] != ""){
		$memberID = $_SESSION['mid'];
		
		$projectInfo = new projectInfoC();
		$budgetTotInst = new budgetInfoC();
		$memberTotInst = new memberInfoC();
		$generalInfo = new generalInfoC();
		$groupSetting = "Group Setting";
		$yes = "yes";
		$yesAdmin = "no";
		
		//gert consortiumID - determine licenseType and consortium level admin
		$consortSetup = "";
		try{				
			$result = $db->prepare("SELECT $db_member.consortium_id,$db_consortium.consortium,$db_consortium.license_id,$db_consortium.admin1,$db_consortium.admin2 FROM $db_member,$db_consortium WHERE $db_member.member_id=? AND $db_member.consortium_id=$db_consortium.consortium_id AND $db_member.active=?");
			$result->execute(array($memberID,$yes));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		$itemCount = $result->rowCount();
		if ($itemCount > 0){
			$consortSetup = "";
			foreach ($result as $row){
				$consortiumID = $row['consortium_id'];
				$licenseID =  $row['license_id'];
				if ($licenseID == "1003" and ($memberID == $row['admin1'] or $memberID == $row['admin2'])){
					$consortSetup = $consortSetup.'<tr><th><a href="'.$mx005ct.'&sid='.$licenseID.'&cid='.$consortiumID.'">'.str_replace('\\','',$row['consortium']).'</a></th><th><a href="'.$mx005ot.'&sid='.$licenseID.'&cid='.$consortiumID.'&oid=new"><button type="button" class="btn btn-xs btn-warning">New Organization</button></a></th></tr>';
					$consAdmin = "yes"; //yes, consort
				}
			}
		}
		
		try{//get array of all organizationID/Name				
			$result = $db->prepare("SELECT organization,organization_id,admin1,admin2 FROM $db_organization WHERE consortium_id=?");
			$result->execute(array($consortiumID));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		$itemCount = $result->rowCount();
		$organCt = 0;
		$organName = array();
		$organID = array();
		$organAdmin = array();
		$orgGroupTot = array();
		if ($itemCount > 0){
			foreach ($result as $row){
				$organName[$organCt] = str_replace('\\','',$row['organization']);
				$organID[$organCt] = $row['organization_id'];
				if ($memberID == $row['admin1'] or $memberID == $row['admin2']){
					$organAdmin[$organCt] = "yes";
				}else{
					$organAdmin[$organCt] = "no";
				}
				$orgGroupTot[$organCt] = $generalInfo->returnGroupsPerOrgF($row['organization_id']);
				$organCt++;
			}
		}
		
		$organSetup = ""; //this isn't being used.
		$orgTitle = "";// org title
		
		$groupSetup = ""; //get organization and groups with the org
		$showOrg = ""; //flag that Org is display
		if ($organCt > 0){
			for ($i = 0; $i < $organCt; $i++){
				$showOrg = "no";
				$orgTitle = "";
				if (($consAdmin=="yes" or $organAdmin[$i]=="yes") and ($licenseID == "1003" or $licenseID == "1002")){
					$orgTitle = '<tr><th><a href="'.$mx005ot.'&sid='.$licenseID.'&oid='.$organID[$i].'">'.str_replace('\\','',$organName[$i]).'</a></th><th><a href="'.$mx005gt.'&sid='.$licenseID.'&oid='.$organID[$i].'&gid=new"><button type="button" class="btn btn-xs btn-success">New Group</button></a></th></tr>';
					$showOrg = "yes";
				}
				
				try{//create account list				 
					$result = $db->prepare("SELECT $db_group.group_id,$db_group.group_name,$db_group_rights.group_rights,$db_group_rights.member_id FROM $db_group,$db_group_rights WHERE $db_group.organization_id=? AND $db_group.group_id=$db_group_rights.group_id AND $db_group.active=?");
					$result->execute(array($organID[$i],$yes));
				} catch(PDOException $e) {
					print "<script> self.location='".$index_url."?err=d1000'; </script>";
				}
				$itemCount = $result->rowCount();
				
				$grpListFlag = array();
				$grpListFlagCt = 0;
				if ($itemCount > 0){
					if ($showOrg != "yes"){//if groups found, member isn't admin, display unlinked Org name
						if ($licenseID == "1001" or $licenseID == "1000"){
							$orgTitle = '<tr><th><a href="'.$mx005ot.'&sid='.$licenseID.'&oid='.$organID[$i].'">'.str_replace('\\','',$organName[$i]).'</a></th><th></th></tr>';
						}else{
							$orgTitle = '<tr><th>'.str_replace('\\','',$organName[$i]).'</th><th></th></tr>';
						}
					}
					$groupCt = 0;
					$orgGroupList = ""; //
					foreach ($result as $row){
						//echo ">".$row['group_id']."<br>";
						$displayAcct = "no";
						if ($row['member_id']==$memberID){
							if ($consAdmin=="yes" or $organAdmin[$i]=="yes" or ($row['member_id']==$memberID and $row['group_rights']==3)){
								if ($grpListFlagCt > 0){
									for ($j = 0; $j < $grpListFlagCt; $j++){
										if ($row['group_id'] == $grpListFlag[$j]){
											$displayAcct = "yes";
										}
									}
								}
								if ($displayAcct == "no"){
									$orgGroupList = $orgGroupList.'<tr><td><label class="checkbox-inline"><a href="'.$mx005gt.'&sid='.$licenseID.'&oid='.$organID[$i].'&gid='.$row['group_id'].'">'.str_replace('\\','',$row['group_name']).'</label></td><td><a href="#" onclick="delGrpOut('.$row['group_id'].')"><button class="btn btn-xs btn-danger"><i class="fa fa-times"></i> </button></a></td></tr>';
									$grpListFlag[$grpListFlagCt] = $row['group_id'];
									$grpListFlagCt++;
								}
							}
						}else{
							if ($grpListFlagCt > 0){
								for ($j = 0; $j < $grpListFlagCt; $j++){
									if ($row['group_id'] == $grpListFlag[$j]){
										$displayAcct = "yes";
									}
								}
							}
							if (($consAdmin=="yes" or $organAdmin[$i]=="yes") and $displayAcct == "no"){
								$orgGroupList = $orgGroupList.'<tr><td><label class="checkbox-inline"><a href="'.$mx005gt.'&sid='.$licenseID.'&oid='.$organID[$i].'&gid='.$row['group_id'].'">'.str_replace('\\','',$row['group_name']).'</label></td><td><a href="#" onclick="delGrpOut('.$row['group_id'].')"><button class="btn btn-xs btn-danger"><i class="fa fa-times"></i> </button></a></td></tr>';
								$grpListFlag[$grpListFlagCt] = $row['group_id'];
								$grpListFlagCt++;
							}
						}//end of else
					}//end foreach
				}//end of group found 
				if ($orgGroupList != "" and $itemCount > 0){
					$groupSetup = $groupSetup.$orgTitle.$orgGroupList;
				}else{
					if ($organCt > 0){
						$groupSetup = $groupSetup.$orgTitle;
					}
				
				}
			}
		}//end if $orgCt > 0				
	}else{  //if statement - not login
		print "<script> self.location='".$index_url."'; </script>";
	}
?>