<?php
	if ($_SESSION['login']=="yes" and $_SESSION['log_ss'] != ""){
		$memberID = $_SESSION['mid'];
		$projectInfo = new projectInfoC();
		$accountInfo = new accountInfoC();
		$budgetTotInst = new budgetInfoC();
		$memberTotInst = new memberInfoC();
		$generalInfo = new generalInfoC();
		if ($_GET['cy']==""){
			$currentYr = date(Y);
			$currentMon = date(F);	
			$budgetMonth = date(m);
		}else{
			$currentYr = $_GET['cy'];
			$budgetMonth = date(m);
		}
		$todayDate = strtotime(date('Y-m-d'));
		$firstDayYear = date('m-01-Y', $todayDate);
		$lastDayYear  = date('m-t-Y', $todayDate);
		$thisYear = date('Y',$todayDate);
		
		$myMileage = "Mileage Tracking";		
		$yes = "yes";
		
////////post form/////////////////////////////////////////////////////////////////
		if($_SERVER['REQUEST_METHOD']=="POST"){
			if ($_POST['miltrackty'] != "" and $_POST['miltrackty'] == "mile"){
				$mileageID = $_POST['milid'];
				$name = $_POST['name'];
				$rate = $_POST['rate'];
				$rate = preg_replace('/[\$,]/', '', $rate); //remove $ and common from amount and assign zero if needs
				if ($rate=="" or $rate==0 or $rate==0.00 or $rate<0){
					$rate = 0;
				}
				$note = $_POST['note'];
				$note = preg_replace("/[^A-Za-z0-9\-()<>= '\/]/", "", $note);
				$note = str_replace('"', "", $note); //remove " from note
				
				if ($_POST['state']== "add"){
					try{//add mileage
						$result = $db->prepare("INSERT INTO $db_mileage (name,rate,note,member_id,active) VALUES (?,?,?,?,?)");
						$result->execute(array($name,$rate,$note,$memberID,$yes));
					} catch(PDOException $e) {
						print "<script> self.location='".$index_url."?err=d1000'; </script>";//echo $e->getMessage();
					}
				}
				if ($_POST['state']== "update" and $mileageID != ""){
					try{//update mileage
						$result = $db->prepare("UPDATE $db_mileage SET name=?,rate=?,note=? WHERE mileage_id=?");
						$result->execute(array($name,$rate,$note,$mileageID));
					} catch(PDOException $e) {
						print "<script> self.location='".$index_url."?err=d1000'; </script>";//echo $e->getMessage();
					}
				}
				if ($_POST['state']== "delete" and $mileageID != ""){
					try{//delete mileage
						$result = $db->prepare("DELETE FROM $db_mileage WHERE mileage_id=? AND member_id=?");
						$result->execute(array($mileageID,$memberID));
					} catch(PDOException $e) {
						print "<script> self.location='".$index_url."?err=d1000'; </script>";//echo $e->getMessage();
					}
					try{//delete all details mileages
						$result = $db->prepare("DELETE FROM $db_mileage_detail WHERE mileage_id=?");
						$result->execute(array($mileageID));
					} catch(PDOException $e) {
						print "<script> self.location='".$index_url."?err=d1000'; </script>";//echo $e->getMessage();
					}
				}
			}//end of mile tracking form post
			
			//begin general tracking
			if ($_POST['gentrackty'] != "" and $_POST['gentrackty'] == "gen"){
				$trackID = $_POST['trkid'];
				$name = $_POST['trkname'];
				$note = $_POST['trknote'];
				$note = preg_replace('/[^A-Za-z0-9\-()<>= "\/]/', '', $note);
				
				if ($_POST['trkstate']== "add"){
					try{//add general tracking
						$result = $db->prepare("INSERT INTO $db_track (name,note,member_id,active) VALUES (?,?,?,?)");
						$result->execute(array($name,$note,$memberID,$yes));
					} catch(PDOException $e) {
						print "<script> self.location='".$index_url."?err=d1000'; </script>";//echo $e->getMessage();
					}
				}
				if ($_POST['trkstate']== "update" and $trackID != ""){
					try{//update general tracking
						$result = $db->prepare("UPDATE $db_track SET name=?,note=? WHERE track_id=?");
						$result->execute(array($name,$note,$trackID));
					} catch(PDOException $e) {
						print "<script> self.location='".$index_url."?err=d1000'; </script>";//echo $e->getMessage();
					}
				}
				if ($_POST['trkstate']== "delete" and $trackID != ""){
					try{//delete general tracking
						$result = $db->prepare("DELETE FROM $db_track WHERE track_id=? AND member_id=?");
						$result->execute(array($trackID,$memberID));
					} catch(PDOException $e) {
						print "<script> self.location='".$index_url."?err=d1000'; </script>";//echo $e->getMessage();
					}
					try{//delete all details general tracking
						$result = $db->prepare("DELETE FROM $db_track_detail WHERE track_id=?");
						$result->execute(array($trackID));
					} catch(PDOException $e) {
						print "<script> self.location='".$index_url."?err=d1000'; </script>";//echo $e->getMessage();
					}
				}
			}//end of general tracking form post
						
			//begin member tracking
			if ($_POST['trkmemty'] != "" and $_POST['trkmemty'] == "mem"){
				$trkmemid = $_POST['trkmemid'];
				$name = $_POST['mempurpose'];
				$name = str_replace('"', '', $name); //remove " from note
				$note = $_POST['memnote'];
				//$note = preg_replace('/[^A-Za-z0-9\-()<>= "\/]/', '', $note);
				$note = str_replace('"', '', $note); //remove " from note
				$orgDate = $_POST['mem_date']; //alter mm-dd-yyyy to yyyy-mm-dd 
				$partsArr = explode("-",$orgDate);
				$date = $partsArr[2]."-".$partsArr[0]."-".$partsArr[1];
				$setGrpID = $_POST['trkmemberGrp'];
				$categoryID = $_POST['trkmemberCategory'];
				$trkmemberAcct = $_POST['trkmemberAccount'];
				$trkmemberBud = 0; //can add this later - 10/12/15
				if ($trkmemberAcct == "none"){
					$trkmemberAcct = 0;
				}
				
				if ($_POST['trkmemstate']== "add"){
					$genNum = date(h).date(i).date(s).rand(100,999);
					try{//add general tracking
						$result = $db->prepare("INSERT INTO $db_trkmember (name,group_id,category_id,account_id,budget_id,note,member_id,date,active) VALUES (?,?,?,?,?,?,?,?,?)");
						$result->execute(array($name,$setGrpID,$categoryID,$trkmemberAcct,$trkmemberBud,$note,$genNum,$date,$yes));
					} catch(PDOException $e) {
						print "<script> self.location='".$index_url."?err=d1000'; </script>";//echo $e->getMessage();
					}
					try{//
						$result = $db->prepare("SELECT trkmember_id FROM $db_trkmember WHERE member_id=?");
						$result->execute(array($genNum)); 
					} catch(PDOException $e) {
						print "<script> self.location='".$index_url."?err=d1000'; </script>";
					}
					foreach ($result as $row){
						$newTrkmember_id = $row['trkmember_id'];
					}
					try{//update memberID
						$result = $db->prepare("UPDATE $db_trkmember SET member_id=? WHERE trkmember_id=? AND member_id=?");
						$result->execute(array($memberID,$newTrkmember_id,$genNum));
					} catch(PDOException $e) {
						print "<script> self.location='".$index_url."?err=d1000'; </script>";//echo $e->getMessage();
					}
					//insert all group member to trkmember_datail table for tracking
					try{//get all groups with admin rights
						$result = $db->prepare("SELECT DISTINCT member_id FROM $db_group_rights WHERE group_id=?");
						$result->execute(array($setGrpID)); 
					} catch(PDOException $e) {
						print "<script> self.location='".$index_url."?err=d1000'; </script>";
					}
					foreach ($result as $row){
						try{//add all member with specific group
							$result = $db->prepare("INSERT INTO $db_trkmember_detail (trkmember_id,value,member_id,present,note) VALUES (?,?,?,?,?)");
							$result->execute(array($newTrkmember_id,0,$row['member_id'],"no",""));
						} catch(PDOException $e) {
							print "<script> self.location='".$index_url."?err=d1000'; </script>";//echo $e->getMessage();
						}
					}
				}
				
				if ($_POST['trkmemstate']== "update" and $trkmemid != ""){
					try{//update member tracking
						$result = $db->prepare("UPDATE $db_trkmember SET name=?,group_id=?,date=?,category_id=?,account_id=?,budget_id=?,note=? WHERE trkmember_id=?");
						$result->execute(array($name,$setGrpID,$date,$categoryID,$trkmemberAcct,$trkmemberBud,$note,$trkmemid));
					} catch(PDOException $e) {
						print "<script> self.location='".$index_url."?err=d1000'; </script>";//echo $e->getMessage();
					}
					
					//update either budget or account 
					//add member track to transaction
					if ($trkmemberAcct != 0){
						$pathLink = "log";
						$generalInfo->addTrkmemberRecordF($memberID,$trkmemid,$pathLink);
					}//end of inserting member tracking
					
				}
				if ($_POST['trkmemstate']== "delete" and $trkmemid != ""){
					try{//delete member tracking
						$result = $db->prepare("DELETE FROM $db_trkmember WHERE trkmember_id=? AND member_id=?");
						$result->execute(array($trkmemid,$memberID));
					} catch(PDOException $e) {
						print "<script> self.location='".$index_url."?err=d1000'; </script>";//echo $e->getMessage();
					}
					try{//delete all details member tracking
						$result = $db->prepare("DELETE FROM $db_trkmember_detail WHERE trkmember_id=?");
						$result->execute(array($trkmemid));
					} catch(PDOException $e) {
						print "<script> self.location='".$index_url."?err=d1000'; </script>";//echo $e->getMessage();
					}
					try{//delete member tracking transaction
						$result = $db->prepare("DELETE FROM $db_transaction WHERE trkmember_id=?");
						$result->execute(array($trkmemid));
					} catch(PDOException $e) {
						print "<script> self.location='".$index_url."?err=d1000'; </script>";//echo $e->getMessage();
					}
					
					//delete all transactions in budget and account
				}
			}//end of member form post
		}//end of form post data
		
////////get mileages tracking////////////////////////////////
		$mileageIDArr = array();
		$nameArr = array();
		$noteArr = array();
		$rateArr = array();
		$mileageIDCt = 0;
		$itemCount = 0;
		$milesTot = 0;
		$reimbTot = 0;
		$mileageList = "";
		$mileageListAll = "";
		try{					
			$result = $db->prepare("SELECT mileage_id,name,rate,note FROM $db_mileage WHERE member_id=? AND active=? ORDER BY name ASC");
			$result->execute(array($memberID,$yes));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";//echo $e->getMessage();
		}
		$itemCount = $result->rowCount();
		if ($itemCount > 0){
			foreach ($result as $row) {
				$mileageIDArr[$mileageIDCt] = $row['mileage_id'];
				$nameArr[$mileageIDCt] = str_replace('\\','',$row['name']);
				$noteArr[$mileageIDCt] = str_replace('\\','',$row['note']);
				$rateArr[$mileageIDCt] = $row['rate'];
				$mileageIDCt++;
			}
			$mileageListCt = count($mileageIDArr);
			$eachMileage = 0;
			$eachReimb = 0;
			for ($i = 0; $i < $mileageListCt; $i++){
				$milesTot = 0;
				$reimbTot = 0;
				try{					
					$result = $db->prepare("SELECT start_o,end_o FROM $db_mileage_detail WHERE mileage_id=? ORDER BY mileage_detail_id ASC");
					$result->execute(array($mileageIDArr[$i]));
				} catch(PDOException $e) {
					print "<script> self.location='".$index_url."?err=d1000'; </script>";//echo $e->getMessage();
				}
				$itemCount = $result->rowCount();
				if ($itemCount > 0){
					foreach ($result as $row) {
						$eachMileage = $row['end_o']-$row['start_o'];
						$milesTot = $milesTot + $eachMileage;
						$reimbTot = $reimbTot + ($eachMileage * $rateArr[$i]);
					}
				}
				$mileageList = $mileageList."<tr><td><a href='".$mx009ml."&mlid=".$mileageIDArr[$i]."'>".$nameArr[$i]."</a></td><td>".$milesTot."</td><td>".$budgetTotInst->convertDollarF($reimbTot)."</td>";
				$mileageList = $mileageList.'<td><button class="btn btn-xs btn-warning" onclick="editTrack('.$mileageIDArr[$i].','.$memberID.',1)"><i class="fa fa-pencil"></i></button>';
				$mileageList = $mileageList.'<button class="btn btn-xs btn-danger" onclick="editTrack('.$mileageIDArr[$i].','.$memberID.',2)"><i class="fa fa-times"></i> </button></td></tr>';

			}//end of for loop	
		}else{
			$mileageList = $mileageList."<tr><td colspan='3'>No mileage tracking</td></tr>";
		}

////////get general 1st tracking////////////////////////////////		
		$genIDArr = array();
		$gennameArr = array();
		$gennoteArr = array();
		$genIDCt = 0;
		$itemCount = 0;
		$genTot = 0;
		$genList = "";
		$genListAll = "";
		try{					
			$result = $db->prepare("SELECT track_id,name,note FROM $db_track WHERE member_id=? AND active=? ORDER BY name ASC");
			$result->execute(array($memberID,$yes));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";//echo $e->getMessage();
		}
		$itemCount = $result->rowCount();
		if ($itemCount > 0){
			foreach ($result as $row) {
				$genIDArr[$genIDCt] = $row['track_id'];
				$gennameArr[$genIDCt] = str_replace('\\','',$row['name']);
				$gennoteArr[$genIDCt] = str_replace('\\','',$row['note']);
				$genIDCt++;
			}
			$genListCt = count($genIDArr);
			for ($i = 0; $i < $genListCt; $i++){
				$genTot = 0;
				try{					
					$result = $db->prepare("SELECT value FROM $db_track_detail WHERE track_id=? ORDER BY track_detail_id ASC");
					$result->execute(array($genIDArr[$i]));
				} catch(PDOException $e) {
					print "<script> self.location='".$index_url."?err=d1000'; </script>";//echo $e->getMessage();
				}
				$itemCount = $result->rowCount();
				if ($itemCount > 0){
					foreach ($result as $row) {
						$genTot = $genTot + $row['value'];
					}
				}
				$genList = $genList."<tr><td><a href='".$mx009gt."&trkid=".$genIDArr[$i]."'>".$gennameArr[$i]."</a></td><td>".$budgetTotInst->convertDollarF($genTot)."</td><td>".$gennoteArr[$i]."</td>";
				$genList = $genList.'<td><button class="btn btn-xs btn-warning" onclick="editGenTrack('.$genIDArr[$i].','.$memberID.',1)"><i class="fa fa-pencil"></i></button>';
				$genList = $genList.'<button class="btn btn-xs btn-danger" onclick="editGenTrack('.$genIDArr[$i].','.$memberID.',2)"><i class="fa fa-times"></i> </button></td></tr>';

			}//end of for loop	
		}else{
			$genList = $genList."<tr><td colspan='3'>No general tracking</td></tr>";
		}

////////get general 2nd tracking////////////////////////////////
		//get category dropdown
		$catItemsList = $generalInfo->getCategoryDropdownListF($memberID);
		$trkcatItemsList = $generalInfo->getTrkcategoryDropdownListF($memberID);
		
		try{
			$result = $db->prepare("SELECT trkcategory_id,sub_yn,type FROM $db_trkcategory WHERE member_id=? AND active=? ORDER BY category ASC");
			$result->execute(array($memberID,$yes));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; script>";
		} 
		$firstTrkCatId = "";
		$firstTrkCatVal = "";
		$firstTrkCatTyp = "";
		$firstTrkCatCt = 0;
		foreach ($result as $row) {
			if ($firstTrkCatCt == 0){
				$firstTrkCatId = $row['trkcategory_id'];
				$firstTrkCatTyp = $row['type'];
				if ($row['sub_yn'] == "yes"){
					$firstSubcat = "ye";
				}else{
					$firstSubcat = "no";
				}
				if ($row['type'] == "numb"){
					$firstTrkCatVal = '<div id="trkcat'.$row['trkcategory_id'].'"><label class="col-lg-3 control-label" id="mainct">Number:</label><div class="col-lg-3"><input type="text" name="ct'.$row['trkcategory_id'].'" id="ct'.$row['trkcategory_id'].'" class="form-control" placeholder="0" onchange="isNumberOnly(this.id,this.value)" ></div>';
					if ($row['sub_yn'] == "yes"){
						$firstTrkCatVal = $firstTrkCatVal.'<div class="col-lg-1 control-label"><a href="#" onclick="addSubCatF(1)"><button class="btn btn-xs btn-warning">Add Breakdown</button></a></div></div>';
					}else{
						$firstTrkCatVal = $firstTrkCatVal.'</div>';
					}
					$subCatCt = '';
					$subCatNum = '';
				}
				if ($row['type'] == "mone"){
					$firstTrkCatVal = '<div id="trkcat'.$row['trkcategory_id'].'"><label class="col-lg-3 control-label" id="mainct">Amount:</label><div class="col-lg-3"><input type="text" name="ct'.$row['trkcategory_id'].'" id="ct'.$row['trkcategory_id'].'" class="form-control" placeholder="$0.00" onchange="isNumber_chk(this.id,this.value)" ></div>';
					if ($row['sub_yn'] == "yes"){
						$firstTrkCatVal = $firstTrkCatVal.'<div class="col-lg-1 control-label"><a href="#" onclick="addSubCatF(2)"><button class="btn btn-xs btn-warning">Add Breakdown</button></a></div></div>';
					}else{
						$firstTrkCatVal = $firstTrkCatVal.'</div>';
					}
					$subCatCt = '';
					$subCatNum = '';
				}
				if ($row['type'] == "text"){
					$firstTrkCatVal = '<div id="trkcat'.$row['trkcategory_id'].'"><label class="col-lg-3 control-label" id="mainct">Text:</label><div class="col-lg-3"><input type="text" name="ct'.$row['trkcategory_id'].'" id="ct'.$row['trkcategory_id'].'" class="form-control" ></div>';
					if ($row['sub_yn'] == "yes"){
						$firstTrkCatVal = $firstTrkCatVal.'<div class="col-lg-1 control-label"><a href="#" onclick="addSubCatF(3)"><button class="btn btn-xs btn-warning">Add</button></a></div></div>';
					}else{
						$firstTrkCatVal = $firstTrkCatVal.'</div>';
					}
					$subCatCt = '';
					$subCatNum = '';
				}
				$firstTrkCatCt++;
			}//end if no trkcategoryID
		}//end for each loop
		
		//get general tracks details for editing
		try{
			$result = $db->prepare("SELECT grpattend_id,group_id,owner_id,member_id,trkcategory_id,subcat_yn,name,value,count,text,date,note FROM $db_grpattend WHERE owner_id=? AND YEAR(date)=? ORDER BY grpattend_id DESC");
			$result->execute(array($memberID,$thisYear));
		} catch(PDOException $e) {
			echo $e->getMessage();
		} 
		$generalTrkDetList = "";
		foreach ($result as $row) {
			$orgDateStart = $row['date']; //alter yyyy-mm-dd to mm-dd-yyyy
			$partsArr = explode("-",$orgDateStart);
			$date = $partsArr[1]."/".$partsArr[2]."/".substr($partsArr[0],-2);
			if ($row['name'] == ""){
				$generalTrkDetList = $generalTrkDetList.'<tr><td>General</td>';
			}else if($row['group_id']!="" and $row['name']=="All Member"){
						$generalTrkDetList = $generalTrkDetList.'<tr><td>All '.$memberTotInst->memberGroupF($row['group_id']).'</td>';
					}else{
						$generalTrkDetList = $generalTrkDetList.'<tr><td>'.$row['name'].'</td>';
					}
			if ($row['subcat_yn'] == "yes"){
				$subcat10 = 1;
				$generalTrkDetList = $generalTrkDetList.'<td><span class="sim_formcontrol">'.$generalInfo->getTrkcategoryNameF($row['trkcategory_id']).'</span>'.$generalInfo->getSubCatEdtF($row['grpattend_id']).'</td>';
			}else{
				$subcat10 = 0;
				$generalTrkDetList = $generalTrkDetList.'<td>'.$generalInfo->getTrkcategoryNameF($row['trkcategory_id']).'</td>'; 	
            }
			if ($generalInfo->getTrkcategoryTypeF($row['trkcategory_id']) == "mone"){
				//$generalTrkDetList = $generalTrkDetList.'<td><input type="text" class="form-control" id="val'.$row['grpattend_id'].'" name="val'.$row['grpattend_id'].'" value="'.$budgetTotInst->convertDollarF($row['value']).'" onchange="isNumber_chk(this.id,this.value)">';
				if ($row['subcat_yn'] == "yes"){
					$generalTrkDetList = $generalTrkDetList.'<td><input type="text" disabled="disabled" class="form-control" id="val'.$row['grpattend_id'].'" name="val'.$row['grpattend_id'].'" value="'.$budgetTotInst->convertDollarF($row['value']).'" onchange="isNumber_chk(this.id,this.value)">';
					$generalTrkDetList = $generalTrkDetList.$generalInfo->getSubCatValF($row['grpattend_id'],"mone").'</td>';
				}else{
					$generalTrkDetList = $generalTrkDetList.'<td><input type="text" class="form-control" id="val'.$row['grpattend_id'].'" name="val'.$row['grpattend_id'].'" value="'.$budgetTotInst->convertDollarF($row['value']).'" onchange="isNumber_chk(this.id,this.value)"></td>';
				}
				$typeNum = 1;
			}
			if ($generalInfo->getTrkcategoryTypeF($row['trkcategory_id']) == "numb"){
				//$generalTrkDetList = $generalTrkDetList.'<td><input type="text" class="form-control" id="num'.$row['grpattend_id'].'" name="num'.$row['grpattend_id'].'" value="'.$row['count'].'" onchange="isNumberOnly(this.id,this.value)">';
				if ($row['subcat_yn'] == "yes"){
					$generalTrkDetList = $generalTrkDetList.'<td><input type="text" disabled="disabled" class="form-control" id="num'.$row['grpattend_id'].'" name="num'.$row['grpattend_id'].'" value="'.$row['count'].'" onchange="isNumberOnly(this.id,this.value)">';
					$generalTrkDetList = $generalTrkDetList.$generalInfo->getSubCatValF($row['grpattend_id'],"numb").'</td>';
				}else{
					$generalTrkDetList = $generalTrkDetList.'<td><input type="text" class="form-control" id="num'.$row['grpattend_id'].'" name="num'.$row['grpattend_id'].'" value="'.$row['count'].'" onchange="isNumberOnly(this.id,this.value)"></td>';
				}
				$typeNum = 2;
			}
			if ($generalInfo->getTrkcategoryTypeF($row['trkcategory_id']) == "text"){
				$generalTrkDetList = $generalTrkDetList.'<td><input type="text" class="form-control" id="txt'.$row['grpattend_id'].'" name="txt'.$row['grpattend_id'].'" value="'.str_replace('\\','',$row['text']).'">';
				if ($row['subcat_yn'] == "yes"){
					$generalTrkDetList = $generalTrkDetList.$generalInfo->getSubCatValF($row['grpattend_id'],"text").'</td>';
				}else{
					$generalTrkDetList = $generalTrkDetList.'</td>';
				}
				$typeNum = 3;
			}
			$subcatCount = $generalInfo->getSubCatCountF($row['grpattend_id']);
			$generalTrkDetList = $generalTrkDetList.'<td>'.$date.'</td><td><input type="text" class="form-control" id="nte'.$row['grpattend_id'].'" name="nte'.$row['grpattend_id'].'" value="'.str_replace('\\','',$row['note']).'"></td>';
            $generalTrkDetList = $generalTrkDetList.'<td nowrap><button class="btn btn-xs btn-warning" onclick="savgeneraltrkF(1,'.$row['grpattend_id'].','.$typeNum.','.$subcat10.','.$subcatCount.')"><i class="fa fa-save"></i></button><button class="btn btn-xs btn-danger" onclick="savgeneraltrkF(2,'.$row['grpattend_id'].','.$typeNum.','.$subcat10.','.$subcatCount.')"><i class="fa fa-times"></i></button></td></tr>';
		}
			
////////get member tracking//////////////////////////////////////////////
		$memIDArr = array();
		$memNameArr = array();
		$memNoteArr = array();
		$memDateArr = array();
		$memIDCt = 0;
		$itemCount = 0;
		$memTot = 0;
		$memList = "";
		$memListAll = "";
		try{					
			$result = $db->prepare("SELECT trkmember_id,name,note,date FROM $db_trkmember WHERE member_id=? AND active=? ORDER BY date ASC");
			$result->execute(array($memberID,$yes));
		} catch(PDOException $e) {
			echo $e->getMessage();
		}
		$grpMemberCount = $result->rowCount();
		if ($grpMemberCount > 0){
			foreach ($result as $row) {
				$memIDArr[$memIDCt] = $row['trkmember_id'];
				$memNameArr[$memIDCt] = str_replace('\\','',$row['name']);
				$memNoteArr[$memIDCt] = str_replace('\\','',$row['note']);
				$orgDate = $row['date'];
				$partsArr = explode("-",$orgDate);
				$memDateArr[$memIDCt] = $partsArr[1]."/".$partsArr[2]."/".$partsArr[0];//alter yyyy-mm-dd to mm-dd-yyyy 
				$memIDCt++;
			}
			$memListCt = count($memIDArr);
			for ($i = 0; $i < $memListCt; $i++){
				$memTot = 0;
				$memPresentCt = 0;
				try{ //add up total for group specific					
					$result = $db->prepare("SELECT value,present FROM $db_trkmember_detail WHERE trkmember_id=? ORDER BY trkmember_detail_id ASC");
					$result->execute(array($memIDArr[$i]));
				} catch(PDOException $e) {
					echo $e->getMessage();
				}
				$itemCount = $result->rowCount();
				if ($itemCount > 0){
					foreach ($result as $row) {
						$memTot = $memTot + $row['value'];
						if ($row['present'] == "yes"){
							$memPresentCt++;
						}
					}
				}
				$memList = $memList."<tr><td><a href='".$mx009mt."&trkid=".$memIDArr[$i]."&mgp=100'>".$memNameArr[$i]."</a></td><td>".$memPresentCt."</td><td>".$budgetTotInst->convertDollarF($memTot)."</td><td>".$memDateArr[$i]."</td>";
				$memList = $memList.'<td><button class="btn btn-xs btn-warning" onclick="editTrkMember('.$memIDArr[$i].','.$memberID.',1)"><i class="fa fa-pencil"></i></button>';
				$memList = $memList.'<button class="btn btn-xs btn-danger" onclick="editTrkMember('.$memIDArr[$i].','.$memberID.',2)"><i class="fa fa-times"></i> </button></td></tr>';

			}//end of for loop	
		}else{
			$memList = $memList."<tr><td colspan='3'>No member tracking</td></tr>";
		}
		
		//get distinct group with admin rights
		$dateStartEnd = "00000000N00000000";
		$trackGroupIdArr = array();
		$trackGroupIdArrCt = 0;
		try{//get all groups with admin rights
			$result = $db->prepare("SELECT DISTINCT group_id FROM $db_group_rights WHERE member_id=? AND group_rights=?");
			$result->execute(array($memberID,3)); 
		} catch(PDOException $e) {
			echo $e->getMessage();
		}
		$itemCount = $result->rowCount();
		$grpAdminList = "";
		$grpLinkList = "";
		if ($itemCount > 0){
			foreach ($result as $row){
				$trackGroupIdArr[$trackGroupIdArrCt] = $row['group_id'];
				if ($generalInfo->getGrpDropdownListF($memberID,$row['group_id']) == "yes"){
					$grpAdminList = $grpAdminList."<option value='".$row['group_id']."'>".$generalInfo->returnGroupNameF($row['group_id'])."</option>";
					$trackGroupIdArrCt++;
				}
				if ($generalInfo->checkGrpAttendF($row['group_id']) > 0){
					$grpLinkList = $grpLinkList.'<li><a href="'.$mx004tk.'&trkg='.$row['group_id'].'&det=1000'.$dateStartEnd.'">'.$generalInfo->returnGroupNameF($row['group_id']).'</a></li>';
				}
			}
		}
		if ($grpLinkList != ""){ //if a group has data, create a list
			$grpLinkList = "<ul>".$grpLinkList."</ul>";
		}
		
		//get distinct account with admin rights
		$accountIdArr = array();
		$accountIdArrCt = 0;
		try{//get all groups with admin rights
			$result = $db->prepare("SELECT account_id,name FROM $db_account WHERE member_id=?");
			$result->execute(array($memberID)); 
		} catch(PDOException $e) {
			echo $e->getMessage();
		}
		$itemCount = $result->rowCount();
		$accountList = "";
		if ($itemCount > 0){
			foreach ($result as $row){
				$accountList = $accountList."<option value='".$row['account_id']."'>".str_replace('\\','',$row['name'])."</option>";
			}
		}
		
		//check if part of a group to be tracked
		$inGrpMemberCount = 0;
		try{					
			$result = $db->prepare("SELECT trkmember_id FROM $db_trkmember_detail WHERE member_id=?");
			$result->execute(array($memberID));
		} catch(PDOException $e) {
			echo $e->getMessage();
		}
		$itemCount = $result->rowCount();
		$memListBelong = "";
		if ($itemCount > 0){
			$belongNameDate = array();
			foreach ($result as $row) {
				$trackGroupYN = $generalInfo->checkGroupTrack($row['trkmember_id']);
				if ($trackGroupYN == "yes"){
					$inGrpMemberCount++;
					$belongNameDate = $generalInfo->trackMemberGrpF($row['trkmember_id']);
					$belongGrpName = $belongNameDate[0];
					$belongGrpDate = $belongNameDate[1];
					
					if ($trackGroupIdArrCt == 0){ //no group admin with track turn on - just belong to a tracked group
						$memListBelong = $memListBelong."<tr><td colspan='2'><a href='".$mx009mt."&trkid=".$row['trkmember_id']."&mgp=200'>".$belongGrpName."</a></td><td>Need to check in</td><td>".$belongGrpDate."</td><td></td></tr>";					
					}
					if ($trackGroupIdArrCt > 0){
						$isMyMemTrack = "no";
						for ($i = 0; $i < $memListCt; $i++){
							if ($row['trkmember_id'] == $memIDArr[$i]){
								$isMyMemTrack = "yes";
							}
						}
						if ($isMyMemTrack == "no"){
							$memListBelong = $memListBelong."<tr><td colspan='2'><a href='".$mx009mt."&trkid=".$row['trkmember_id']."&mgp=200'>".$belongGrpName."</a></td><td>Need to check in</td><td>".$belongGrpDate."</td><td></td></tr>";
						}
					}
				}//end of Yes tracking group
			}
		}
	}else{  //if statement - not login
		print "<script> self.location='".$index_url."'; </script>";
	}
?>