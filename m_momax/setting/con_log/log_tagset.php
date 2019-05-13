<?php
	if ($_SESSION['login']=="yes" and $_SESSION['log_ss'] != ""){
		$memberID = $_SESSION['mid'];
		$projectInfo = new projectInfoC();
		$budgetTotInst = new budgetInfoC();
		$memberTotInst = new memberInfoC();
		$yes = "yes";
		$tagList = "";
		$tagListAdd = "";
		try{//create account list				
			$result = $db->prepare("SELECT tag_id,name,list_order,description,active FROM $db_tag WHERE member_id=? ORDER BY list_order ASC");
			$result->execute(array($memberID));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		$itemCount = $result->rowCount();
		if ($itemCount > 0){
			foreach ($result as $row){
				$tagList = $tagList."<tr><td><span id='olist_view".$row['tag_id']."' style='display: ;'><a href='#' onclick='edittaglist(1,".$row['tag_id'].",".$row['list_order'].",".$memberID.")'>".$row['list_order']."</a></span><span id='olist_edit".$row['tag_id']."' style='display: none ;'><input size='2' value='".$row['list_order']."' class='form-control' type='text' name='od".$row['tag_id']."' id='od".$row['tag_id']."' onchange='edittaglist(2,".$row['tag_id'].",".$row['list_order'].",".$memberID.")' /></span></td>";
				$tagList = $tagList."<td><a href='#' onclick='uptTagInfo(".$row['tag_id'].")'>".str_replace('\\','',$row['name'])."</a></td>";
				if ($row['active'] == "yes"){
					$tagList = $tagList."<td>Yes</td>";
				}else{
					$tagList = $tagList."<td>No</td>";
				}
				$tagList = $tagList."<td>".str_replace('\\','',$row['description'])."</td>";
				$tagList = $tagList."<td><a href='#' onclick='uptTagInfo(".$row['tag_id'].")'><button class='btn btn-xs btn-warning'><i class='fa fa-pencil'></i></button></a>";
				$tagList = $tagList."<a href='#' onclick='delTagInfo(".$row['tag_id'].")'><button class='btn btn-xs btn-danger'><i class='fa fa-times'></i> </button></a></td></tr>";
			}
		}else{
			$tagListAdd = $tagListAdd."Click here to create new tag - ";
		}
		$tagListAdd = $tagListAdd."<tr><th colspan=2><th><button id='addNewTag' class='btn btn-xs btn-success'>New Tag&nbsp;&nbsp;</button></th></tr>";
	}else{  //if statement - not login
		print "<script> self.location='".$index_url."'; </script>";
	}
?>