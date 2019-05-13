<?php
	include($p_guest); //get guest info
	include('com_momax/admin/left_col.php');
	include('com_momax/admin/con_log/log_index.php'); 
?>

<!-- insert the page content here -->
<div id="border-round">
<div class="top">
	<div id="tryhead">
      <b class="b_box">
	  <b class="b_box1"><b></b></b>
	  <b class="b_box2"><b></b></b>
	  <b class="b_box3"></b>
	  <b class="b_box4"></b>
	  <b class="b_box5"></b></b>
      <div class="b_box_pri">
	<?php if ($active_page!="") { ?>
	  		<div id="main_header"><a href="<?php echo $mx005 ?>">ADMIN</a></div>
<?php } ?>
</div></div></div>
<div class="center-content">
<div id="main_pri">
	<div id="main_pri_col1">
		<?php echo $post_pix; ?>
		<p>&nbsp;</p>
	</div>
	<div id="main_pri_col2">
		<div id="contain_main">
		<div id="report">
			<h2>Admin Center</h2>
		    <p>
		    	<h6>Profile Setting</h6>
		    	<ul>
		    		<li>
		    		<?php if ($temp_email=="" or $temp_pass=="") { ?>
		    			<a href="<?php echo $mx005ma?>">Get my Login ID and Password</a><?php echo $nofound ?><br><br><br>
		    			<b>You have not signed up.  Once you log out or close out, you will not be able to log back in.  <br>You need your Login ID and Password.  <a href="<?php echo $mx005ma?>">Get my Login ID and Password</a></b><br>
		    		<?php }else { ?>
		    			<a href="<?php echo $mx005ma?>">My profile</a><?php echo $nofound ?>
		    		<?php } ?>
		    		</li>
                    <?php if ($grp_admin=="yes" and $user_id=="100101"){ ?>
                    	<li><a href="<?php echo $mx005us?>">Subscription</a></li>
                    <?php } ?>
		    	</ul>
                </p>
                <p>
		    		<?php if ($seclevel==1 and $temp_email!="" and $temp_pass!="") { ?>
		        	<h6>Group Users Setting</h6>
		        	<ul>
                        <?php if($grp_admin=="yes"){ ?><li><a href="<?php echo $mx005eu?>">Group users</a></li><?php } ?>
						<?php if ($num_group>=1) { ?><li><a href="<?php echo $mx005uu?>">Group users' rights</a></li>
		    			<?php } if ($num_invite>=1) { ?><li><a href="<?php echo $mx005cr?>">Grant rights to user's request</a></li>
		    			<?php } if ($num_group==0 and $num_invite==0) {?><li>No Sharing with any user!</li><?php } ?>
                        <li><a href="<?php echo $mx005ci?>">Request rights to another user's budget or accounts</a><?php echo $nofound ?><br>
		    		</ul>
		        	<?php } ?>   
		    </p>
		    <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
		</div>
	</div>
</div>
</div>
</div>
</div>  
 <div class="bottom"><span></span></div>
</div>