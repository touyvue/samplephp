<?php
	if ($_SESSION['login']=="yes" and $_SESSION['log_ss'] != ""){
		$memberID = $_SESSION['mid'];
		
		$projectInfo = new projectInfoC();
		$budgetTotInst = new budgetInfoC();
		$accountInfo = new accountInfoC();
		$memberTotInst = new memberInfoC();
		
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
		
		$showBudgetlistID = $_GET['blid'];
		$trkBudgetList = "";
		$trkBudgetArr = array();
		
		try{					
			$result = $db->prepare("SELECT budgetlist_id,account_id,name,startdate FROM $db_budgetlist WHERE member_id=? AND active=? ORDER BY startdate ASC");
			$result->execute(array($memberID,$yes));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		$itemsFound = $result->rowCount();
		$netAmount = 0;
		if ($itemsFound > 0){
			foreach ($result as $row){
				$orgDate = $row['startdate'];
				$partsArr = explode("-",$orgDate);
				$date = $partsArr[1]."/".$partsArr[2]."/".$partsArr[0];
				$trkBudgetArr = $budgetTotInst->retBudTrackTotF($memberID,$row['budgetlist_id']);
				
				if ($trkBudgetArr[1]<=0){
					$netAmount = $trkBudgetArr[0] + $trkBudgetArr[1];
				}else{
					$netAmount = $trkBudgetArr[0] - $trkBudgetArr[1];
				}
				$trkBudgetList =  $trkBudgetList.'<tr><td><a href="'.$mx004as.'&blid='.$row['budgetlist_id'].'">'.str_replace('\\','',$row['name']).'</a></td><td>'.$budgetTotInst->convertDollarF($trkBudgetArr[0]).'</td><td>'.$budgetTotInst->convertDollarF($trkBudgetArr[1]).'</td><td>'.$budgetTotInst->convertDollarF($netAmount).'</td><td>'.$date.'</td></tr>';
			}
		}
			
		if ($showBudgetlistID != ""){
			$reportName = "";
			$budgetlistAmount = 0;
			try{					
				$result = $db->prepare("SELECT budgetlist_id,budget_type_id,amount,account_id,name,startdate FROM $db_budgetlist WHERE member_id=? AND budgetlist_id=? AND active=?");
				$result->execute(array($memberID,$showBudgetlistID,$yes));
			} catch(PDOException $e) {
				print "<script> self.location='".$index_url."?err=d1000'; </script>";
			}
			foreach ($result as $row){
				$reportName = str_replace('\\','',$row['name']);
				$budgetlistAmount = $row['amount'];
				$budgetlistAmountSave = $row['amount'];
				$budgetlistDate = $row['startdate'];
				$partsArr = explode("-",$budgetlistDate);
				$orgDate = $partsArr[1]."/".$partsArr[2]."/".$partsArr[0];
				$budgetTypeId = $row['budget_type_id'];
			}
			
			try{
				$result = $db->prepare("SELECT transaction_date,amount,transaction_type_id,category_id,posted FROM $db_transaction WHERE member_id=? AND budgetlist_id=? AND transaction_date>=? ORDER BY transaction_date ASC");
				$result->execute(array($memberID,$showBudgetlistID,$budgetlistDate));
			} catch(PDOException $e) {
				echo "message001 - Sorry, system is experincing problem. Please check back.";
			}
			$itemsFound = $result->rowCount();
			$amount = 0;
			$transList = '<tr><td>'.$orgDate.'</td><td>'.$budgetTotInst->convertDollarF($budgetlistAmountSave).'</td><td>Budget amount</td><th>'.$budgetTotInst->convertDollarF($budgetlistAmountSave).'</th></tr>';
			if ($itemsFound > 0){
				foreach ($result as $row){
					$orgDate = $row['transaction_date'];
					$partsArr = explode("-",$orgDate);
					$date = $partsArr[1]."/".$partsArr[2]."/".$partsArr[0];
					if ($row['transaction_type_id'] == "1000"){
						if ($budgetTypeId == "1000"){
							$budgetlistAmount = $budgetlistAmount - $row['amount'];
						}else{
							$budgetlistAmount = $budgetlistAmount + $row['amount'];
						}
						$amount = $budgetTotInst->convertDollarF($row['amount']);
					}
					if ($row['transaction_type_id'] == "1001"){
						$budgetlistAmount = $budgetlistAmount - $row['amount'];
						$amount = "(".$budgetTotInst->convertDollarF($row['amount']).")";
					}	
					if ($row['posted'] == "yes"){//posted transaction
						$transList =  $transList."<tr><td>".$date."<i class='fa fa-check'></i></td>";
					}else{//not posted
						$transList =  $transList."<tr><td>".$date."</td>";
					}		
					$transList =  $transList.'<td>'.$amount.'</td><td>'.$accountInfo->categoryNameF($row['category_id']).'</a></td><th>'.$budgetTotInst->convertDollarF($budgetlistAmount).'</th></tr>';
				}
			}
		}
		
	}else{  //if statement - not login
		print "<script> self.location='".$index_url."'; </script>";
	}
?>