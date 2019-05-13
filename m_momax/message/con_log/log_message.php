<?php
	if ($_SESSION['login']=="yes" and $_SESSION['log_ss'] != ""){
		$memberID = $_SESSION['mid'];
		$memberTotInst = new memberInfoC();
		$yes = "yes";
		$today = time();
		$dayinpass = "2015-07-03";
		$dayinpass= strtotime($dayinpass);
		
		$newPost = "";
		$postRights = 0;
		try{//get consortiumID
			$result = $db->prepare("SELECT consortium_id FROM $db_member WHERE member_id=? AND active=?");
			$result->execute(array($memberID,$yes));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; script>";
		}
		foreach ($result as $row) {
			$consortiumID = $row['consortium_id'];
		}
		$orgIDArr = array();
		$orgIDCt = 0;
		try{//get all available orgID's
			$result = $db->prepare("SELECT DISTINCT organization_id FROM $db_organization WHERE consortium_id=? AND active=?");
			$result->execute(array($consortiumID,$yes));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; script>";
		}
		foreach ($result as $row) {
			$orgIDArr[$orgIDCt] = $row['organization_id'];
			$orgIDCt++;
		}
		$groupIDArr = array();
		$groupIDCt = 0;
		$orgsFoundTot = count($orgIDArr);
		for ($i = 0; $i < $orgsFoundTot; $i++){
			try{//get all available groupIDs that part of the organization
				$result = $db->prepare("SELECT DISTINCT group_id FROM $db_group WHERE organization_id=? AND active=?");
				$result->execute(array($orgIDArr[$i],$yes));
			} catch(PDOException $e) {
				print "<script> self.location='".$index_url."?err=d1000'; script>";
			}
			foreach ($result as $row) {
				$groupIDArr[$groupIDCt] = $row['group_id'];
				$groupIDCt++;
			}
		}
		
		$myGroupIDArr = array();
		$myGroupIDCt = 0;
		$groupsFoundTot = count($groupIDArr);
		for ($i = 0; $i < $groupsFoundTot; $i++){
			try{//get all unqine groupIDs that I'm part of it
				$result = $db->prepare("SELECT DISTINCT group_id FROM $db_group_rights WHERE group_id=? AND member_id=? AND active=?");
				$result->execute(array($groupIDArr[$i],$memberID,$yes));
			} catch(PDOException $e) {
				print "<script> self.location='".$index_url."?err=d1000'; script>";
			}
			foreach ($result as $row) {
				$myGroupIDArr[$myGroupIDCt] = $groupIDArr[$i];
				$myGroupIDCt++;
			}
		}
		
		$groupMemberIDArr = array();
		$groupMemberIDCt = 0;
		$groupsFoundTot = count($myGroupIDArr);
		for ($i = 0; $i < $groupsFoundTot; $i++){
			try{//get members that part of my Groups only
				$result = $db->prepare("SELECT DISTINCT member_id FROM $db_group_rights WHERE group_id=? AND active=?");
				$result->execute(array($myGroupIDArr[$i],$yes));
			} catch(PDOException $e) {
				print "<script> self.location='".$index_url."?err=d1000'; script>";
			}
			foreach ($result as $row) {
				if ($i > 0){
					$memberTotSoFar = count($groupMemberIDArr);
					$foundSame = "no";
					for ($j = 0; $j < $memberTotSoFar; $j++){
						if ($row['member_id'] == $groupMemberIDArr[$j]){
							$foundSame = "yes";
						}
					}
					if ($foundSame == "no"){
						$groupMemberIDArr[$groupMemberIDCt] = $row['member_id'];
						$groupMemberIDCt++;
					}
				}else{
					$groupMemberIDArr[$groupMemberIDCt] = $row['member_id'];
					$groupMemberIDCt++;
				}
			}
		}
		//now I have all my Groups, all members belong to my Groups
		$myViewPostMemberIDArr = array();
		$myViewPostMemberIDCt = 0;
		$postMemberTot = count($groupMemberIDArr); //all the members below to all my Groups
		for ($i = 0; $i < $postMemberTot; $i++){
			try{//loop through each members and check their rights to share posts to me
				$result = $db->prepare("SELECT rights_type_id FROM $db_post_rights WHERE member_id=? AND active=?");
				$result->execute(array($groupMemberIDArr[$i],$yes));
			} catch(PDOException $e) {
				print "<script> self.location='".$index_url."?err=d1000'; script>";
			}
			foreach ($result as $row) {
				if ($row['rights_type_id'] == "2"){ //view post only part of the same Group
					$groupsFoundTot = count($myGroupIDArr);
					$memberKeep = "no";
					for ($j = 0; $j < $groupsFoundTot; $j++){ //loop through my Groups I belong to
						try{//get all groups this specific members allow to see posts
							$result = $db->prepare("SELECT group_id FROM $db_post_group WHERE member_id=?");
							$result->execute(array($groupMemberIDArr[$i]));
						} catch(PDOException $e) {
							print "<script> self.location='".$index_url."?err=d1000'; script>";
						}
						foreach ($result as $row) {//compare each of my groups to the allowed groups, one-by-one
							if ($myGroupIDArr[$j] == $row['group_id']){//if specific allowed group is same as my group, grant rights
								$memberKeep = "yes";
							}
						}
					}
					if ($memberKeep == "yes"){
						$myViewPostMemberIDArr[$myViewPostMemberIDCt]= $groupMemberIDArr[$i];
						$myViewPostMemberIDCt++;
					}
				}
				if ($row['rights_type_id'] == "3"){// 3 is automatically view all
					$myViewPostMemberIDArr[$myViewPostMemberIDCt]= $groupMemberIDArr[$i];
					$myViewPostMemberIDCt++;
				}
			}
		}
			
		try{
			$result = $db->prepare("SELECT post_id,post_name,post_date,member_id FROM $db_post WHERE active=? ORDER BY post_id DESC LIMIT 50");
			$result->execute(array($yes));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; script>";
		}

		$altValue = 0;
		foreach ($result as $row) {
			$myPostMemberTot = count($myViewPostMemberIDArr);
			$yesMemberPost = "no";
			for ($i = 0; $i < $myPostMemberTot; $i++){ //only loop through other members' post, not self-post
				if ($myViewPostMemberIDArr[$i]==$row['member_id']){
					$yesMemberPost = "yes";
					$memName = $memberTotInst->memberNameF($myViewPostMemberIDArr[$i]);
					$memPix = $memberTotInst->memberPixF($myViewPostMemberIDArr[$i]);
				}
			}	
			if ($yesMemberPost == "yes" or $row['member_id']==$memberID){
				if ($row['member_id']==$memberID and $yesMemberPost == "no"){ //display selfpost
					$memName = $memberTotInst->memberNameF($memberID);
					$memPix = $memberTotInst->memberPixF($memberID);
				}
				if ($memPix == ""){
					$memPix = "person.png";
				}
				$dayinpass = $row['post_date'];
				$dayinpass= strtotime($dayinpass);
				$postMin = round(abs($today-$dayinpass)/60);
				$postHour = round(abs($today-$dayinpass)/60/60);
				$postDay = round(abs($today-$dayinpass)/60/60/24);
				if ($postDay < 1){
					if ($postHour <= 1){
						$timePost = $postMin."min ago";
					}else{
						$timePost = $postHour." hrs ago";
					}
				}else{
					if ($postDay == 1){
						$timePost = $postDay." day ago";
					}else{
						$timePost = $postDay." days ago";
					}
				}
				if ($altValue == 0){
					$newPost = $newPost.'<li class="by-me"><div class="avatar pull-left"><img src="images/m_pix/'.$memPix.'" alt="'.$memName.'" height="40" width="40" />';
					if ($row['member_id'] == $memberID){
						$newPost = $newPost.'<br><span class="chat-meta"><a href="#" onclick="removePost('.$row['post_id'].')">Remove</a></span>';
					}
					$newPost = $newPost.'</div><div class="chat-content"><div class="chat-meta"><a href="'.$mx007.'&sid='.$row['member_id'].'">'.$memName.'</a><span class="pull-right">'.$timePost.'</span></div>';
					$newPost = $newPost.str_replace('\\','',$row['post_name']).'<div class="clearfix"></div></div></li>';
					$altValue = 1;
				}else{
					$newPost = $newPost.'<li class="by-other"><div class="avatar pull-right"><img src="images/m_pix/'.$memPix.'" alt="'.$memName.'" height="40" width="40" />';
					if ($row['member_id'] == $memberID){
						$newPost = $newPost.'<br><span class="chat-meta pull-right"><a href="#"  onclick="removePost('.$row['post_id'].')">Remove</a></span>';
					}
					$newPost = $newPost.'</div><div class="chat-content"><div class="chat-meta">'.$timePost.'<span class="pull-right"><a href="'.$mx007.'&sid='.$row['member_id'].'">'.$memName.'</a></span></div>';
					$newPost = $newPost.str_replace('\\','',$row['post_name']).'<div class="clearfix"></div></div></li>';
					$altValue = 0;
				}
			}//end if
		}//end of forloop
		if ($newPost == ""){
			$newPost = "No post available";
		}
		
		//check budgetlist
		try{					
			$result = $db->prepare("SELECT budgetlist_id FROM $db_budgetlist WHERE member_id=? AND active=?");
			$result->execute(array($memberID,$yes));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		$budgetlistFound = $result->rowCount();
		
		//check tag
		try{					
			$result = $db->prepare("SELECT tag_id FROM $db_tag WHERE member_id=? AND active=?");
			$result->execute(array($memberID,$yes));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		$tagFound = $result->rowCount();
		
	}else{  //if statement - not login
		print "<script> self.location='".$index_url."'; </script>";
	}
?>