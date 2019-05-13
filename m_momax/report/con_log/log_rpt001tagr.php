<?php
	if ($_SESSION['login']=="yes" and $_SESSION['log_ss'] != ""){
		$memberID = $_SESSION['mid'];
		
		$projectInfo = new projectInfoC();
		$budgetTotInst = new budgetInfoC();
		$accountInfo = new accountInfoC();
		$memberTotInst = new memberInfoC();
		$generalInfoC = new generalInfoC();
		
		if ($_GET['cy']==""){
			$currentYr = date(Y);
			$currentMon = date(F);	
			$budgetMonth = date(m);
		}else{
			$currentYr = $_GET['cy'];
			$budgetMonth = date(m);
		}
		$todayDate = strtotime(date('Y-m-d'));
		$firstDay = date('m-01-Y', $todayDate);
		$lastDay  = date('m-t-Y', $todayDate); 
		
		$myGenReports = "Budgeted Report";
		$yes = "yes";
		$showReport = "no";
		
		$showTaglistID = $_GET['tgid'];
		$trkTagList = "";
		$trkBudgetArr = array();
		
		try{					
			$result = $db->prepare("SELECT tag_id,name,description FROM $db_tag WHERE member_id=? AND active=?");
			$result->execute(array($memberID,$yes));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		$itemsFound = $result->rowCount();
		$netAmount = 0;
		if ($itemsFound > 0){
			foreach ($result as $row){
				$netAmount = $budgetTotInst->retTagTotF($memberID,$row['tag_id']);
				$trkTagList =  $trkTagList.'<tr><td><a href="'.$mx004tg.'&tgid='.$row['tag_id'].'">'.str_replace('\\','',$row['name']).'</a></td><td>'.$budgetTotInst->convertDollarF($netAmount).'</td><td>'.str_replace('\\','',$row['description']).'</td></tr>';
			}
		}
			
		if ($showTaglistID != ""){
			$reportName = "";
			$budgetlistAmount = 0;
			try{					
				$result = $db->prepare("SELECT tag_id,name,description FROM $db_tag WHERE member_id=? AND tag_id=?");
				$result->execute(array($memberID,$showTaglistID));
			} catch(PDOException $e) {
				print "<script> self.location='".$index_url."?err=d1000'; </script>";
			}
			foreach ($result as $row){
				$reportName = str_replace('\\','',$row['name']);
			}
			try{
				$result = $db->prepare("SELECT transaction_date,amount,transaction_type_id,category_id FROM $db_transaction WHERE member_id=? AND tag_id=? ORDER BY transaction_date ASC");
				$result->execute(array($memberID,$showTaglistID));
			} catch(PDOException $e) {
				echo "message001 - Sorry, system is experincing problem. Please check back.";
			}
			$itemsFound = $result->rowCount();
			$amount = 0;
			$transList = '';
			if ($itemsFound > 0){
				foreach ($result as $row){
					$orgDate = $row['transaction_date'];
					$partsArr = explode("-",$orgDate);
					$date = $partsArr[1]."/".$partsArr[2]."/".$partsArr[0];
					if ($row['transaction_type_id'] == "1000"){
						$budgetlistAmount = $budgetlistAmount + $row['amount'];
						$amount = $budgetTotInst->convertDollarF($row['amount']);
					}
					if ($row['transaction_type_id'] == "1001"){
						$budgetlistAmount = $budgetlistAmount - $row['amount'];
						$amount = "(".$budgetTotInst->convertDollarF($row['amount']).")";
					}			
					$transList =  $transList.'<tr><td>'.$date.'</td><td>'.$amount.'</td><td>'.$accountInfo->categoryNameF($row['category_id']).'</a></td><td>'.$budgetTotInst->convertDollarF($budgetlistAmount).'</td></tr>';
				}
			}
		}
		
	}else{  //if statement - not login
		print "<script> self.location='".$index_url."'; </script>";
	}
?>