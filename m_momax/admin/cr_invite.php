<?php
	include($p_guest); //get guest info
	include('com_momax/admin/left_col.php');
	include('com_momax/admin/con_log/log_cr_invite.php'); 
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
		<a href="<?php echo $mx005 ?>">ADMIN</a> &nbsp;/&nbsp;
		Sharing Request
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
			<?php if ($submit_flag=="") { ?>
			<h2>User Sharing Request</h2>
		    <p>Put the owner's e-mail address below.  Without a correct e-mail 
		    address, rights cannot be granted for your request.</p>
		    <form action="<?php echo $mx005ci?>" name="invited_form" onsubmit="return validate_invite()" method="post">          
		        <p>        
		        <table>	
		        <tr><td class="tablepad1">E-mail:</td>
		        	<td  class="tablepad"><input type "text" name="email" disabled="true" size="50" value="<?php echo $email ?>"></td></tr>		
		        <tr><td  class="tablepad1">Invitation ID:</td>
		        <td  class="tablepad"><input size="50" type="text" name="invited_id" value="<?php echo "***".$invited_code."***" ?>" disabled="true" /></td></tr>
		        <tr><td  class="tablepad1">Owner's E-mail:</td>
		        <td  class="tablepad"><input size="50" type="text" name="owner_email" id="owner_email" onchange="check_owneremail(document.invited_form.owner_email.value,<?php echo "'".$user_id."'"?>)" /> 
		        <span id="emailchk"><small><i></i></small></span><br><small><i>Your request will be confirmed and rights will be granted.</i></small></td></tr>
		        <tr><td  class="tablepad1"></td>
		        <td  class="tablepad">
		        <input class="submit" type="submit" name="contact_submitted" value="Submit Request" />   
		        </td></tr>
		        </table>            
		        </p> 
		        <input type="hidden" name="submit_flag" value="yes" />
		        <input type="hidden" name="invited_code" value="<?php echo $invited_code ?>" />
		        <input type="hidden" name="f_name" value="<?php echo $name ?>" />
			</form>
			<?php } if ($submit_flag=="post") { ?> 
			<h2>Request has been submitted</h2>
			<p>The group owner has received your request.  Once the group owner confirms it, you will receive an e-mail stating that budget/accounts are available.</p>
			<p>Thank you.</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
			
			<?php } if ($submit_flag=="no") { ?>
			<h2>You already submitted a request to this group owner.  Once the group owner confirms, you will be notified.  Thank you.</h2>
			<?php } ?>
		</div>
	</div>
</div>
</div>
</div>
</div>  
 <div class="bottom"><span></span></div>
</div>