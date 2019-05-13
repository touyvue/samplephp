<?php
	include($p_guest); //get guest info
	include('com_momax/admin/left_col.php');
	include('com_momax/admin/con_log/log_partner.php'); 
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
	  		<div id="main_header"><a href="<?php echo $mx005pn ?>"> ASSOCIATES</a></div>
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
			<h2>Business Associates</h2>
		    <?php if ($temp_email!="") { //only sign-up member ?>
				<?php if ($temp_email!="" and $grp_admin=="yes") { ?>
                    <p>
                    <h6>Subscription</h6>
                    <ul>
                    	<li><a href="<?php echo $mx005us?>">Update subscription</a></li>
                    </ul>
                    </p>
                <?php } ?>
                <p>
                <?php if ($grp_admin=="yes" and $grp_sub=="L-Group" and $grp_sub_active=="yes"){ ?>
                    <h6>Partner Group Setting</h6>
                    <ul>
                        <li><a href="<?php echo $mx005ac?>">Group admin</a></li>
                    </ul>
                <?php }else{ ?>
                    <h6>Become a Business Associate</h6>
                    <p>
                    Would you like to share Maxmoni with others?  Help others to take control of their financial matters just like how you do it and, at
                    the same time, enjoy some compensation and profit sharing. By upgrading your subscription to <u>L-Group level</u>, you can sign up
                    families, teams, clients, or affiliates to use Maxmoni. Partnership compensation and profit sharing are described below.  
                    </p>
                    <p></p>
                    <h6>Compensation and Profit Sharing</h6>
                    <ul>
                        <li>Compensation - 35% of your subscription sales for the first 12 months</li>
                        <li>Profit sharing - 10% residual of your subscription sales after the first 12 months</li>
                    </ul> 
                    <h6><a href="<?php echo $mx005us ?>">Start today!</a></h6>Update subscription now to activate your
                    Business Associate partnership with Maxmoni.
                <?php } ?>
                </p>
           	<?php }else { ?>
                    <a href="<?php echo $mx005ma?>">Get my Login ID and Password</a><?php echo $nofound ?><br><br><br>
                    <b>You have not signed up.  Once you log out or close out, you will not be able to log back in.  <br>You need your Login ID and Password.  <a href="<?php echo $mx005ma?>">Get my Login ID and Password</a></b><br>
		 	<?php } ?>
		    <p>&nbsp;</p>
		</div>
	</div>
</div>
</div>
</div>
</div>  
 <div class="bottom"><span></span></div>
</div>