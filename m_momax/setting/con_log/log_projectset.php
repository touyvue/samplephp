<?php
	if ($_SESSION['login']=="yes" and $_SESSION['log_ss'] != ""){
		$memberID = $_SESSION['mid'];
		$projectInfo = new projectInfoC();
		$budgetTotInst = new budgetInfoC();
		$memberTotInst = new memberInfoC();
		$accountSetting = "Account Setting";
		$budgetSetting = "Budget Setting";
		$projectSetting = "Event Setting";
		$categorySetting = "Category Setting";
		$yes = "yes";
		$projectList = "";
		$projectListAdd = "";
		try{//create project list				
			$result = $db->prepare("SELECT project_id,name,note FROM $db_project WHERE member_id=? AND active=? ORDER BY name");
			$result->execute(array($memberID,$yes));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		$itemCount = $result->rowCount();
		if ($itemCount > 0){
			foreach ($result as $row){
				$projectList = $projectList."<tr><td><a href='#' onclick='uptProjectInfo(".$row['project_id'].")'>".str_replace('\\','',$row['name'])."</a></td><td>".str_replace('\\','',$row['note'])."</td>";
				$projectList = $projectList."<td><a href='#' onclick='uptProjectInfo(".$row['project_id'].")'><button class='btn btn-xs btn-warning'><i class='fa fa-pencil'></i></button></a>";
				$projectList = $projectList."<a href='#' onclick='delProjectInfo(".$row['project_id'].")'><button class='btn btn-xs btn-danger'><i class='fa fa-times'></i> </button></a></td></tr>";
			}
		}else{
			$projectListAdd = $projectListAdd."Click here to create new event - ";
		}
		$projectListAdd = $projectListAdd."<tr><th colspan=2><th><button id='addNewProject' class='btn btn-xs btn-success'>New Project&nbsp;&nbsp;</button></th></tr>";
	}else{  //if statement - not login
		print "<script> self.location='".$index_url."'; </script>";
	}
?>