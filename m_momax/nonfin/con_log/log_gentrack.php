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
		
		//Get mybudget list
		$trackID = $_GET['trkid'];
		$yes = "yes";
		
		if($_SERVER['REQUEST_METHOD']=="POST"){
			$trkid = $_POST['trkid'];
			$trkdetid = $_POST['trkdetid'];
			$orgDate = $_POST['trk_date']; //alter mm-dd-yyyy to yyyy-mm-dd 
			$partsArr = explode("-",$orgDate);
			$date = $partsArr[2]."-".$partsArr[0]."-".$partsArr[1];
			$purpose = $_POST['purpose'];
			$value = $_POST['value'];
			$value = preg_replace('/[\$,]/', '', $value); //remove $ and common from amount and assign zero if needs
			if ($value=="" or $value==0 or $value==0.00 or $value<0){
				$value = 0;
			}
			$category = $_POST['category'];
			$note = $_POST['note'];
			$note = preg_replace("/[^A-Za-z0-9\-()<>= '\/]/", "", $note);
			$note = str_replace('"', "", $note); //remove " from note
			
			if ($_POST['state']== "add"){
				try{//add mileage
					$result = $db->prepare("INSERT INTO $db_track_detail (track_id,purpose,value,category,date,note) VALUES (?,?,?,?,?,?)");
					$result->execute(array($trkid,$purpose,$value,$category,$date,$note));
				} catch(PDOException $e) {
					print "<script> self.location='".$index_url."?err=d1000'; </script>";
				}
			}
			if ($_POST['state']== "update" and $trkdetid != ""){
				try{//update mileage
					$result = $db->prepare("UPDATE $db_track_detail SET purpose=?,value=?,category=?,date=?,note=? WHERE track_detail_id=?");
					$result->execute(array($purpose,$value,$category,$date,$note,$trkdetid));
				} catch(PDOException $e) {
					print "<script> self.location='".$index_url."?err=d1000'; </script>";
				}
			}
			if ($_POST['state']== "delete" and $trkdetid != ""){
				try{//delete mileage
					$result = $db->prepare("DELETE FROM $db_track_detail WHERE track_detail_id=?");
					$result->execute(array($trkdetid));
				} catch(PDOException $e) {
					print "<script> self.location='".$index_url."?err=d1000'; </script>";
				}
			}
		}
		
		
		$genTot = 0;
		$itemList = "";
		$tkName = "";
		try{					
			$result = $db->prepare("SELECT name FROM $db_track WHERE member_id=? AND track_id=? AND active=?");
			$result->execute(array($memberID,$trackID,$yes));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";//echo $e->getMessage();
		}
		$itemCount = $result->rowCount();
		if ($itemCount > 0){
			foreach ($result as $row) {
				$tkName = str_replace('\\','',$row['name']);
			}
		}
		
		try{					
			$result = $db->prepare("SELECT track_detail_id,purpose,value,category,date,note FROM $db_track_detail WHERE track_id=? ORDER BY purpose ASC");
			$result->execute(array($trackID));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";//echo $e->getMessage();
		}
		$itemCount = $result->rowCount();
		if ($itemCount > 0){
			foreach ($result as $row) {
				$orgDate = $row['date'];
				$partsArr = explode("-",$orgDate);
				$date = $partsArr[1]."/".$partsArr[2]."/".$partsArr[0];//alter yyyy-mm-dd to mm-dd-yyyy 
				$itemList = $itemList."<tr><td>".$date."</td>";
				$itemList = $itemList."<td>".str_replace('\\','',$row['purpose'])."</td>";
				$itemList = $itemList."<td>".$budgetTotInst->convertDollarF($row['value'])."</td>";
				$itemList = $itemList."<td>".str_replace('\\','',$row['category'])."</td>";
				$itemList = $itemList.'<td><button class="btn btn-xs btn-warning" onclick="editGenDet('.$row['track_detail_id'].','.$memberID.',1)"><i class="fa fa-pencil"></i></button>';
				$itemList = $itemList.'<button class="btn btn-xs btn-danger" onclick="editGenDet('.$row['track_detail_id'].','.$memberID.',2)"><i class="fa fa-times"></i> </button>';
				$itemList = $itemList."</tr>";
				$genTot = $genTot + $row['value'];
			}
			$genTot = $budgetTotInst->convertDollarF($genTot);
		}else{
			$itemList = $itemList.'<tr><th colspan="5">No general tracking</th></tr>';
			$genTot = "$0.00";
		}
		
	}else{  //if statement - not login
		print "<script> self.location='".$index_url."'; </script>";
	}
?>