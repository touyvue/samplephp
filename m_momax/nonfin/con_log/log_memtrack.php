<?php
	if ($_SESSION['login']=="yes" and $_SESSION['log_ss'] != ""){
		$memberID = $_SESSION['mid'];
		$projectInfo = new projectInfoC();
		$budgetTotInst = new budgetInfoC();
		$memberTotInst = new memberInfoC();
		$accountInfo = new accountInfoC();
		$generalInfo = new generalInfoC();
		if ($_GET['cy']==""){
			$currentYr = date(Y);
			$currentMonL = date(F);
			$currentMon = date(m);	
		}else{
			$currentYr = $_GET['cy'];
			$currentMonL = date(F);
			$currentMon = date(m);
		}
		
		//Get member tracking list
		$trackID = $_GET['trkid'];
		$memGp = $_GET['mgp'];
		$yes = "yes";
		
		if($_SERVER['REQUEST_METHOD']=="POST"){
			$trkid = $_POST['trkmemberid'];
			$addDelMember = $_POST['delmemberGrp'];
			
			try{//add specific member member track details
				$result = $db->prepare("INSERT INTO $db_trkmember_detail (trkmember_id,value,member_id,present,note) VALUES (?,?,?,?,?)");
				$result->execute(array($trackID,0,$addDelMember,"no",""));
			} catch(PDOException $e) {
				print "<script> self.location='".$index_url."?err=d1000'; </script>";//echo $e->getMessage();
			}

		}//end of form post
		
		
		$genTot = 0;
		$itemList = "";
		$tkName = "";
		try{					
			$result = $db->prepare("SELECT name,group_id,account_id,member_id,date FROM $db_trkmember WHERE trkmember_id=? AND active=?");
			$result->execute(array($trackID,$yes));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";//echo $e->getMessage();
		}
		$itemCount = $result->rowCount();
		if ($itemCount > 0){
			foreach ($result as $row) {
				$tkName = str_replace('\\','',$row['name']);
				$groupID = $row['group_id'];
				$grpName = $memberTotInst->memberGroupF($row['group_id']);
				$orgDateMain = $row['date'];
				$partsArr = explode("-",$orgDateMain);
				$predate = $partsArr[1]."-".$partsArr[2]."-".$partsArr[0];//alter yyyy-mm-dd to mm-dd-yyyy
				$accountID = $row['account_id'];
				$trkmemberOwner = $row['member_id'];
			}
		}
		
		$todayDate = date("Y-m-d");
		$memberFoundInTracking = array();
		$memberFoundInTrackingCt = 0;
		try{					
			if ($memGp == "100"){
				$result = $db->prepare("SELECT trkmember_detail_id,value,member_id,present,enter_date,note FROM $db_trkmember_detail WHERE trkmember_id=? ORDER BY trkmember_detail_id ASC");
				$result->execute(array($trackID));
			}
			if ($memGp == "200"){
				$result = $db->prepare("SELECT trkmember_detail_id,value,member_id,present,enter_date,note FROM $db_trkmember_detail WHERE trkmember_id=? AND member_id=? ORDER BY trkmember_detail_id ASC");
				$result->execute(array($trackID,$memberID));
			}
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";//echo $e->getMessage();
		}
		$trkmemDetCt = $result->rowCount();
		if ($trkmemDetCt > 0){
			foreach ($result as $row) {
				$orgDate = $row['enter_date'];
				$partsArr = explode("-",$orgDate);
				$date = $partsArr[1]."/".$partsArr[2]."/".$partsArr[0];//alter yyyy-mm-dd to mm-dd-yyyy 
				$memberFoundInTracking[$memberFoundInTrackingCt] = $row['member_id'];
				$memberFoundInTrackingCt++;
				$memName = $memberTotInst->memberFLNameF($row['member_id']);
				$itemList = $itemList.'<tr><td><label>'.$memName.'</label><input type="hidden" value="'.$memName.'" name="mem'.$row['trkmember_detail_id'].'" id="mem'.$row['trkmember_detail_id'].'"></td>';
				if ($row['value'] > 0){
					$memValue = 'value="'.$budgetTotInst->convertDollarF($row['value']).'"';
				}else{
					$memValue = 'placeholder="$0.00"';
				}
				if ($orgDateMain < $todayDate and $memGp == "200"){
					$itemList = $itemList.'<td>'.$budgetTotInst->convertDollarF($row['value']).'</td>';
					$itemList = $itemList.'<td><input type="checkbox" id="p'.$row['trkmember_detail_id'].'" name="p'.$row['trkmember_detail_id'].'"checked="checked" disabled="disabled" value="yes"> Yes</td><td></td><td></td>';
				}else{
					$itemList = $itemList.'<td><input type="text" class="form-control" id="v'.$row['trkmember_detail_id'].'" name="v'.$row['trkmember_detail_id'].'" '.$memValue.'" onchange="isNumber_chk(this.id,this.value)"></td>';
					$itemList = $itemList.'<td><input type="checkbox" id="p'.$row['trkmember_detail_id'].'" name="p'.$row['trkmember_detail_id'].'"';
					if ($row['present']=="yes"){
						$itemList = $itemList.' checked="checked" value="yes"> Yes</td>';
					}else{
						$itemList = $itemList.' value="yes"> Yes</td>';
					}
					$itemList = $itemList.'<td><input type="text" class="form-control" id="nt'.$row['trkmember_detail_id'].'" name="nt'.$row['trkmember_detail_id'].'" value="'.str_replace('\\','',$row['note']).'"></td>';
					$itemList = $itemList.'<td><button class="btn btn-xs btn-warning" onclick="saveTrkmemberDet('.$row['trkmember_detail_id'].','.$memberID.',1)"><i class="fa fa-save"></i></button>';
					$itemList = $itemList.'<button class="btn btn-xs btn-danger" onclick="saveTrkmemberDet('.$row['trkmember_detail_id'].','.$memberID.',2)"><i class="fa fa-times"></i></button>';
				}
				$itemList = $itemList."</tr>";
				$genTot = $genTot + $row['value'];
			}
			$genTot = $budgetTotInst->convertDollarF($genTot);
		}else{
			$itemList = $itemList.'<tr><th colspan="5">No member tracking</th></tr>';
			$genTot = "$0.00";
		}
		
		//create group dropdown list **************************
		try{//get all groups with admin rights
			$result = $db->prepare("SELECT DISTINCT member_id FROM $db_group_rights WHERE group_id=?");
			$result->execute(array($groupID)); 
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		$allGrpMemCt = $result->rowCount();
		$memberList = "";
		foreach ($result as $row){
			$sameMember = "no";
			for ($i=0; $i<$memberFoundInTrackingCt; $i++){
				if ($row['member_id'] == $memberFoundInTracking[$i]){
					$sameMember = "yes";
				}
			}
			if ($sameMember == "no"){
				$memName = $memberTotInst->memberFLNameF($row['member_id']);
				$memberList = $memberList."<option value='".$row['member_id']."'>".$memName."</option>";
			}
		}
	}else{  //if statement - not login
		print "<script> self.location='".$index_url."'; </script>";
	}
?>