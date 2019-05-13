<?php
	include ('../../../m_inc/m_config.php'); //mysql cridential
	include ('../../../m_class/class_lib.php');
	include ('../../../m_inc/def_value.php');
			
	$memberID = $_POST['mid'];
	$budlistID = $_POST['budlistid'];
	$active = "yes";
	
	try{					
		$result = $db->prepare("SELECT budgetlist_id,name,member_id FROM $db_budgetlist WHERE member_id=? AND active=? ORDER BY list_order ASC");
		$result->execute(array($memberID,$yes));
	} catch(PDOException $e) {
		print "<script> self.location='".$index_url."?err=d1000'; </script>";
	}
	$budgetAnnList = "";
	foreach ($result as $row) {
		if ($row['member_id'] == "100100146" and $row['budgetlist_id'] == "100171"){
			$budgetAnnList = $budgetAnnList.'<option value="'.$row['budgetlist_id'].'">'.str_replace('\\','',$row['name']).'</option>';
		}
	}
	echo $budgetAnnList;
?>