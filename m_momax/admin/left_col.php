<?php
	//get posting message
	$invite_gid = array();	
	//get invited member from $db_invite table
	$invite_id_ct = 0;
	$result=mysql_query("select * from $db_invite where group_id='$user_id'") or die(mysql_error());
	while($row = mysql_fetch_array($result)){
		if ($row['active']=="yes"){
			$invite_gid[$invite_id_ct] = $row['user_id'];
			$invite_id_ct += 1;
		}
	}
	//get member images
	$my_member = array();
	for ($i=0; $i<count($invite_gid); $i++){
		$result=mysql_query("select * from $db_user where user_id='$invite_gid[$i]' and active='yes'") or die(mysql_error());
		while($row = mysql_fetch_array($result)){
			$my_member[$i] = $row['first_name'];
		}
	}

	$result=mysql_query("select * from $db_post_pix where user_id='$user_id' and active='yes'") or die(mysql_error());
	while($row = mysql_fetch_array($result)){
		$post_pix = "<p><img class='g_img' src='".$m_img_path."".$row['post_pix_name']."'><br><span class='g_txt'>".$_SESSION['user']."</span></p>";
	}
	if (count($invite_gid)>0){
		$post_pix = $post_pix."<center>Shared</center><div class='m_main'>";
	}else{
		$post_pix = $post_pix."<center></center><div class='m_main'>";
	}
	for ($i=0; $i<count($invite_gid); $i++){
		$result=mysql_query("select * from $db_post_pix where user_id='$invite_gid[$i]' and active='yes'") or die(mysql_error());
		while($row = mysql_fetch_array($result)){
			$post_pix = $post_pix."<div class='m_pos'><img class='m_img' src='".$m_img_path."".$row['post_pix_name']."'><br>".$my_member[$i]."</div>";
		}
	}
	$post_pix = $post_pix."</div>";
?>