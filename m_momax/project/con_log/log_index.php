<?php
	if ($_SESSION['login']=="yes" and $_SESSION['log_ss'] != ""){
		$memberID = $_SESSION['mid'];
		$projectInfo = new projectInfoC();
		$budgetTotInst = new budgetInfoC();
		$memberTotInst = new memberInfoC();
		if ($_GET['cy']==""){
			$currentYr = date(Y);
			$currentMon = date(F);	
			$budgetMonth = date(m);
		}else{
			$currentYr = $_GET['cy'];
			$budgetMonth = date(m);
		}
		$yes = "yes";
		
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
		
		//get all my projects
		$projectIDArr = array();
		$projectNameArr = array();
		$projectDateArr = array();
		$projectCt = 0;
		$itemCount = 0;
		$projectTotAmt = 0;
		$projectList = "";
		$projectListTot = "";
		try{					
			$result = $db->prepare("SELECT project_id, name, date FROM $db_project WHERE member_id=? AND active=? ORDER BY date ASC");
			$result->execute(array($memberID,$yes));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		$itemCount = $result->rowCount();
		if ($itemCount > 0){
			foreach ($result as $row) {
				$projectIDArr[$projectCt] = $row['project_id'];
				$projectNameArr[$projectCt] = str_replace('\\','',$row['name']);
				$projectDateArr[$projectCt] = $row['date'];
				$projectCt++;
				
				$orgDate = $row['date']; //alter yyyy-mm-dd to mm-dd-yyyy mx002gb
				$partsArr = explode("-",$orgDate);
				$projectSpcificDate = $partsArr[1]."-".$partsArr[2]."-".$partsArr[0];
				$projectAmt = $projectInfo->projectTotAmtF($memberID,$row['project_id']);				
				$projectList = $projectList."<tr><td><a href='".$mx006pj."&pid=".$row['project_id']."&smid='>".str_replace('\\','',$row['name'])."</a></td><td>".$budgetTotInst->convertDollarF($projectAmt)."</td><td>".$projectSpcificDate."</td></tr>";
				$projectTotAmt = $projectTotAmt + $projectAmt;
			}
			$projectListTot = $projectListTot."<tr><th>Total</th><th>".$budgetTotInst->convertDollarF($projectTotAmt)."</th><th></th></tr>";
		}else{
			$projectList = "<tr><td>No projects available</td><td colspan=2>Create <a href=".$mx005ps.">New Project</a></td></tr>";
		}
		
		//get shared projects
		$projectSharedIDArr = array();
		$projectSharedMemberArr = array();
		$projectSharedAccessArr = array();
		$projectNameID = array();
		$projectSharedCt = 0;
		$itemCount = 0;
		$projectSharedTotAmt = 0;
		$projectSharedList = "";
		$projectSharedListTot = "";
		
		try{					
			$result = $db->prepare("SELECT project_id,member_id,access_rights FROM $db_project_rights WHERE member_id=? AND active=?");
			$result->execute(array($memberID,$yes));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		$itemCount = $result->rowCount();
		if ($itemCount > 0){
			foreach ($result as $row) {
				$flagProject = "no";
				for ($i = 0; $i < $projectCt; $i++){
					if ($row['project_id'] == $projectIDArr[$i]){
						$flagProject = "yes";
					}
				}
				if ($flagProject == "no"){
					$projectSharedIDArr[$projectSharedCt] = $row['project_id'];
					$projectNameID = $projectInfo->projectNameIDF($row['project_id']);
					$projectSharedMemberArr[$projectSharedCt] = $projectNameID[1];
					$projectSharedAccessArr[$projectSharedCt] = $row['access_rights'];
					$projectSharedCt++;
				}
			}
			$itemCount = 0;
			for ($i = 0; $i < $projectSharedCt; $i++){
				try{					
					$result = $db->prepare("SELECT project_id, name, date,member_id FROM $db_project WHERE project_id=? AND active=? ORDER BY name");
					$result->execute(array($projectSharedIDArr[$i],$yes));
				} catch(PDOException $e) {
					print "<script> self.location='".$index_url."?err=d1000'; </script>";
				}
				$itemCount = $result->rowCount();
				if ($itemCount > 0){
					foreach ($result as $row) {
						$orgDate = $row['date']; //alter yyyy-mm-dd to mm-dd-yyyy
						$partsArr = explode("-",$orgDate);
						$projectSpcificDate = $partsArr[1]."-".$partsArr[2]."-".$partsArr[0];
						$projectSharedAmt = $projectInfo->projectTotAmtF($memberID,$row['project_id']);				
						$projectSharedList = $projectSharedList."<tr><td>".$memberTotInst->memberNameF($row['member_id'])."</td><td><a href='".$mx006pj."&pid=".$row['project_id']."&smid=".$row['member_id']."'>".str_replace('\\','',$row['name'])."</a></td><td>".$budgetTotInst->convertDollarF($projectSharedAmt)."</td><td>".$projectSpcificDate."</td></tr>";
						$projectSharedTotAmt = $projectSharedTotAmt + $projectSharedAmt;
					}
				}
				$projectSharedListTot = "<tr><th></th><th>Total</th><th>".$budgetTotInst->convertDollarF($projectSharedTotAmt)."</th><th></th></tr>";
			}
			if ($projectSharedCt <= 0){
				$projectSharedList = "<tr><td colspan=4>No shared projects available</td></tr>";
			}
		}
		
	}else{  //if statement - not login
		print "<script> self.location='".$index_url."'; </script>";
	}
?>