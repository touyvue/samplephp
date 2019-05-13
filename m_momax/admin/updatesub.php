	<?php
	include($p_guest); //get guest info
	include('com_momax/admin/left_col.php');
	include('com_momax/admin/con_log/log_updatesub.php'); 
	//echo 'Curl: ', function_exists('curl_version') ? 'Enabled' : 'Disabled';
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
	<div id="main_header">
		<a href="<?php echo $mx005 ?>">ADMIN</a> / Subscription
	</div>
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
		<h2>Subscription</h2>
        <p>
		<?php if ($gr_admin == "no"){ ?>
            <table>
		        <tr><td class="tablepad1">Name:</td>
		        	<td  class="tablepad2"><?php echo $first_name ?></td></tr>	
		        <tr><td class="tablepad">E-mail:</td>
		        	<td  class="tablepad"><?php echo $email ?></td></tr>
                <tr><td class="tablepad"></td>
		        	<td  class="tablepad">You're currently part of a <?php echo $grp_sub ?> subscription.  To become a Business Associate,  
                    please activate your own L-Group subscription.  After activation, you can invite others to use Maxmoni which will allow you   
                    to receive compensation and profit sharing. Please upgrade your subcription below.<br /><br /></td></tr>
                <tr><td class="tablepad">Subscription:</td>
		        	<td  class="tablepad">
                    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                    <input type="hidden" name="cmd" value="_s-xclick">
                    <input type="hidden" name="hosted_button_id" value="3B2Z9CLS47RNJ">
                    <input type="image" src="images/subscribe_l.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                    <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
                    <br />&nbsp;&nbsp;&nbsp;<strong>L-Group</strong> - $10.00/month (unlimited users)
                    <br />&nbsp;&nbsp;&nbsp;Business Associate - compensation and profit sharing
                    </form>
                    </td></tr>
            </table>
	    <?php }else { ?>        
	        <table>
		        <tr><td class="tablepad1">Name:</td>
		        	<td  class="tablepad2"><?php echo $first_name ?></td></tr>	
		        <tr><td class="tablepad">E-mail:</td>
		        	<td  class="tablepad"><?php echo $email ?></td></tr>
                <tr><td class="tablepad">Subscription:</td>
		        	<td  class="tablepad"><?php echo $grp_sub_txt ?></td></tr>
                <tr><td class="tablepad">Subscription status:</td>
		        	<td  class="tablepad"><?php echo $grp_sub_active ?></td></tr>		
		        <tr><td  class="tablepad">Group Code:</td>
		        <td  class="tablepad"><input size="20" type="text" id="grp_cod" name="grp_cod" value="<?php echo $grp_cod ?>" onchange="chk_grp_code(this.value,'<?php echo $grp_cod ?>','<?php echo $user_id ?>')" /> <?php if($gr_admin_set=="yes"){?><input type="checkbox" name="grp_adm" id="grp_adm" checked="checked" disabled="disabled" /> Group Admin<?php } ?><br /><br /></td></tr>
                <?php //if ($grp_sub_active == "YES") { ?>
                <tr><td  class="tablepad">Choose Subscription:</td>
		        <td  class="tablepad">
                <?php if ($grp_sub != "L-Group") {?>
                    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                    <input type="hidden" name="cmd" value="_s-xclick">
                    <input type="hidden" name="hosted_button_id" value="3B2Z9CLS47RNJ">
                    <input type="hidden" name="custom" value="<?php echo "p10".$grp_cod?>">
                    <input type="hidden" name="notify_url" value="https://www.maxmoni.com/com_momax/success/my_ipn.php">
                    <input type="hidden" name="return" value="https://www.maxmoni.com/com_momax/success/checkout_complete.php?gcode=<?php echo "S10".$grp_cod?>">
                    <input type="hidden" name="rm" value="2">
                    <input type="hidden" name="cbt" value="Return to Maxmoni">
                    <input type="hidden" name="cancel_return" value="https://www.maxmoni.com/com_momax/cancel/paypal_cancel.php?gcode=<?php echo "C10".$grp_cod?>">
                    <input type="hidden" name="lc" value="US">
                    <input type="hidden" name="currency_code" value="USD">
                    <input type="image" src="images/subscribe_l.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                    <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
                    <br />&nbsp;&nbsp;&nbsp;<strong>L-Group</strong> - $10.00/month (unlimited users)
                    </form>
                    <br />
                <?php } ?>
                <?php if ($grp_sub != "M-Group") {?>
                    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                    <input type="hidden" name="cmd" value="_s-xclick">
                    <input type="hidden" name="hosted_button_id" value="7A3EEA3NZ4964">
                    <input type="hidden" name="custom" value="<?php echo "p05".$grp_cod?>">
                    <input type="hidden" name="notify_url" value="https://www.maxmoni.com/com_momax/success/my_ipn.php">
                    <input type="hidden" name="return" value="https://www.maxmoni.com/com_momax/success/checkout_complete.php?gcode=<?php echo "S05".$grp_cod?>">
                    <input type="hidden" name="rm" value="2">
                    <input type="hidden" name="cbt" value="Return to Maxmoni">
                    <input type="hidden" name="cancel_return" value="https://www.maxmoni.com/com_momax/cancel/paypal_cancel.php?gcode=<?php echo "C05".$grp_cod?>">
                    <input type="hidden" name="lc" value="US">
                    <input type="hidden" name="currency_code" value="USD">
                    <input type="image" src="images/subscribe_m.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                    <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
                    <br />&nbsp;&nbsp;&nbsp;<strong>M-Group</strong> - $5.00/month (1-5 users)
                    </form>
                    <br />
                <?php } ?>
                <?php if ($grp_sub != "S-Group") {?>
                    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                    <input type="hidden" name="cmd" value="_s-xclick">
                    <input type="hidden" name="hosted_button_id" value="2TCBSPJZMSBSY">
                    <input type="hidden" name="custom" value="<?php echo "p03".$grp_cod?>">
                    <input type="hidden" name="notify_url" value="https://www.maxmoni.com/com_momax/success/my_ipn.php">
                    <input type="hidden" name="return" value="https://www.maxmoni.com/com_momax/success/checkout_complete.php?gcode=<?php echo "S03".$grp_cod?>">
                    <input type="hidden" name="rm" value="2">
                    <input type="hidden" name="cbt" value="Return to Maxmoni">
                    <input type="hidden" name="cancel_return" value="https://www.maxmoni.com/com_momax/cancel/paypal_cancel.php?gcode=<?php echo "C03".$grp_cod?>">
                    <input type="hidden" name="lc" value="US">
                    <input type="hidden" name="currency_code" value="USD">
                    <input type="image" src="images/subscribe_s.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                    <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
                    <br />&nbsp;&nbsp;&nbsp;<strong>S-Group</strong> - $3.00/month (1-2 users)
                    </form>
                    </td></tr>
				<?php } ?>
	        </table>      
        <?php } ?>          
	    </p> 
		<p>&nbsp;</p>
		</div>
	</div>
</div>
</div>
</div>
</div>  
 <div class="bottom"><span></span></div>
</div>