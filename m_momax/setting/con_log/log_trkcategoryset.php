<?php
	if ($_SESSION['login']=="yes" and $_SESSION['log_ss'] != ""){
		$memberID = $_SESSION['mid'];
		$projectInfo = new projectInfoC();
		$budgetTotInst = new budgetInfoC();
		$memberTotInst = new memberInfoC();
		$accountSetting = "Account Setting";
		$budgetSetting = "Budget Setting";
		$projectSetting = "Project Setting";
		$categorySetting = "Tracking Category Setting";
		$yes = "yes";
		
		$categoryList = "";
		$categoryListAdd = "";
		try{//create category list				
			$result = $db->prepare("SELECT trkcategory_id,category,sub_yn,type,description FROM $db_trkcategory WHERE member_id=? AND active=? ORDER BY category");
			$result->execute(array($memberID,$yes));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		$itemCount = $result->rowCount();
		if ($itemCount > 0){
			foreach ($result as $row){
				if ($row['type']=="numb"){
					$type = "Number";
				}
				if ($row['type']=="mone"){
					$type = "Money";
				}
				if ($row['type']=="text"){
					$type = "Text";
				}
				$subYN = $row['sub_yn'];
				$categoryList = $categoryList."<tr><td><a href='#' onclick='uptCategoryInfo(".$row['trkcategory_id'].")'>".str_replace('\\','',$row['category'])."</a></td>";
				$categoryList = $categoryList."<td>".$type."</td><td>".$row['sub_yn']."</td>";
				$categoryList = $categoryList."<td>".str_replace('\\','',$row['description'])."</td>";
				$categoryList = $categoryList."<td><a href='#' onclick='uptCategoryInfo(".$row['trkcategory_id'].")'><button class='btn btn-xs btn-warning'><i class='fa fa-pencil'></i></button></a>";
				$categoryList = $categoryList."<a href='#' onclick='delCategoryInfo(".$row['trkcategory_id'].")'><button class='btn btn-xs btn-danger'><i class='fa fa-times'></i> </button></a></td></tr>";
			}
		}else{
			$categoryListAdd = $categoryListAdd."Click here to create new category - ";
		}
		$categoryListAdd = $categoryListAdd."<tr><th colspan=2><th><button id='addNewCategory' class='btn btn-xs btn-success'>New Category&nbsp;&nbsp;</button></th></tr>";
	}else{  //if statement - not login
		print "<script> self.location='".$index_url."'; </script>";
	}
?>