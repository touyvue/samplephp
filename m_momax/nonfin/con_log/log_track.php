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
		$milID = $_GET['mlid'];
		$yes = "yes";
		
		if($_SERVER['REQUEST_METHOD']=="POST"){
			$mileageID = $_POST['milid'];
			$mileageDetID = $_POST['mildetid'];
			$orgDate = $_POST['mile_date']; //alter mm-dd-yyyy to yyyy-mm-dd 
			$partsArr = explode("-",$orgDate);
			$date = $partsArr[2]."-".$partsArr[0]."-".$partsArr[1];
			$purpose = $_POST['purpose'];
			$starto = $_POST['starto'];
			$endo = $_POST['endo'];
			$note = $_POST['note'];
			$note = preg_replace("/[^A-Za-z0-9\-()<>= '\/]/", "", $note);
			$note = str_replace('"', "", $note); //remove " from note
			
			if ($_POST['state']== "add"){
				try{//add mileage
					$result = $db->prepare("INSERT INTO $db_mileage_detail (mileage_id,purpose,start_o,end_o,date,note) VALUES (?,?,?,?,?,?)");
					$result->execute(array($mileageID,$purpose,$starto,$endo,$date,$note));
				} catch(PDOException $e) {
					print "<script> self.location='".$index_url."?err=d1000'; </script>";//echo $e->getMessage();
				}
			}
			if ($_POST['state']== "update" and $mileageDetID != ""){
				try{//update mileage
					$result = $db->prepare("UPDATE $db_mileage_detail SET purpose=?,start_o=?,end_o=?,date=?,note=? WHERE mileage_detail_id=?");
					$result->execute(array($purpose,$starto,$endo,$date,$note,$mileageDetID));
				} catch(PDOException $e) {
					print "<script> self.location='".$index_url."?err=d1000'; </script>";//echo $e->getMessage();
				}
			}
			if ($_POST['state']== "delete" and $mileageDetID != ""){
				try{//delete mileage
					$result = $db->prepare("DELETE FROM $db_mileage_detail WHERE mileage_detail_id=?");
					$result->execute(array($mileageDetID));
				} catch(PDOException $e) {
					print "<script> self.location='".$index_url."?err=d1000'; </script>";//echo $e->getMessage();
				}
			}
		}
		
		
		$mileageTot = 0;
		$reimbTot = 0;
		$itemList = "";
		$tkName = "";
		$tkRate = "";
		try{					
			$result = $db->prepare("SELECT name,rate FROM $db_mileage WHERE member_id=? AND mileage_id=? AND active=?");
			$result->execute(array($memberID,$milID,$yes));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";//echo $e->getMessage();
		}
		$itemCount = $result->rowCount();
		if ($itemCount > 0){
			foreach ($result as $row) {
				$tkName = str_replace('\\','',$row['name']);;
				$tkRate = $budgetTotInst->convertDollarF($row['rate']);
				$rate = $row['rate'];
			}
		}
		
		try{					
			$result = $db->prepare("SELECT mileage_detail_id,purpose,start_o,end_o,date,note FROM $db_mileage_detail WHERE mileage_id=? ORDER BY purpose ASC");
			$result->execute(array($milID));
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
				$itemList = $itemList."<td>".$row['start_o']."</td>";
				$itemList = $itemList."<td>".$row['end_o']."</td>";
				$itemList = $itemList."<td>".($row['end_o']-$row['start_o'])."</td>";
				$itemList = $itemList.'<td><button class="btn btn-xs btn-warning" onclick="editMilDet('.$row['mileage_detail_id'].','.$memberID.',1)"><i class="fa fa-pencil"></i></button>';
				$itemList = $itemList.'<button class="btn btn-xs btn-danger" onclick="editMilDet('.$row['mileage_detail_id'].','.$memberID.',2)"><i class="fa fa-times"></i> </button>';
				$itemList = $itemList."</tr>";
				$mileageTot = $mileageTot + ($row['end_o']-$row['start_o']);
			}
			$reimbTot = $budgetTotInst->convertDollarF($rate * $mileageTot);
		}else{
			$itemList = $itemList.'<tr><th colspan="6">No mileages</th></tr>';
		}
		
	}else{  //if statement - not login
		print "<script> self.location='".$index_url."'; </script>";
	}
?>