<?php
	include($p_guest); //get guest info
	include('com_momax/admin/left_col.php');
	include('com_momax/admin/con_log/log_cr_invite_accpt.php'); 
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
		Grant Permission
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
			<?php if ($dir_flag=="") { ?>
			<h2>Confirm Sharing Budget and/or Accounts</h2>
		    <p>You should have received an invited code from this user.    
		    You need to paste the invited code in the textbox next to the name and either accept or denied it.</p>
	          
	        <form name="invite_frm_view" method="post">
	        <p><br><table>	
	        <tr><th class="tablepad1">Name</th><th class="tablepad">E-mail</th><th class="tablepad">Invited Code</th><th class="tablepad"></th></tr>
	        <?php echo $requestor_display ?>
	        </table>
	        </p>
	        </form>
	        <br>        
			<?php } if ($dir_flag=="den") { ?>
			<h2>Denied Requestor Permission</h2><br>
			<b>Requestor, <?php echo $first_name ?>, has been denied permission.  Thank you.</b>
			<?php } if ($dir_flag=="acc") { ?>
			<h2>Grant Requestor Permission</h2>
			<p>Give requestor permission to add/edit/view accounts below.</p><br>
		    <form action="<?php echo $mx005cr?>&act=go" name="crinvite_form" method="post">          
		        <p>        
		        <table>
		        <tr><td class="tablepad1">Requestor:</td>
		        	<td  class="tablepad2"><input type "text" size="50" disabled="true" name="first_name" value="<?php echo $first_name ?>"></td></tr>
		
		        <tr><td class="tablepad">E-mail:</td>
		        	<td  class="tablepad"><input type "text" size="50" disabled="true" name="email" value="<?php echo $email ?>"></td></tr>	
		        <tr><td class="tablepad1"><b>Security Level:</b></td>
		        	<td  class="tablepad2">
		        	<b>Level 1</b> - Read only&nbsp;&nbsp;&nbsp;&nbsp;<b>Level 3</b> - Add/edit/delete account records<br>
		        	<b>Level 2</b> - Add/edit account records 
		        	</td></tr>	
		        <tr><td class="tablepad1">Budgeting:</td>
			       	<td  class="tablepad2">
			        	<table><tr><td class='inntablepad'>
				        	<input type="checkbox" name="grant_budget" id="grant_budget" value="yes" onclick="chk_budget_inv()" /> Budget sheet 
				        	</td><td class='inntablepad'>
				        	<input name="budget_sl" type="radio" value="3" disabled="true" /> Level 1
				        	<input name="budget_sl" type="radio" value="2" disabled="true" /> Level 2
				        	<input name="budget_sl" type="radio" value="1" disabled="true" /> Level 3
			        	</td></tr></table>
			    	</td></tr>
		        <tr><td class="tablepad1">Account:</td>
		        	<td class="tablepad2">
		            	<table>
		            	<?php echo $account_type ?>
		            	</table>
		        	</td></tr>            			
		        <tr><td  class="tablepad"></td>
		        <td  class="tablepad">
		        <div class="form_trans">
		      	  <span class="formw"><input class="submit" type="submit" name="contact_submitted" value=" Grant Permission " /></span>       
		      	</div>
		        </td></tr>
		        </table>            
		        </p> 
		        <input name="requestor_id" type="hidden" value="<?php echo $requestor_id ?>"/>
		        <input name="requestor_no" type="hidden" value="<?php echo $requestor_no ?>"/>
			</form>	
			<?php } if ($dir_flag=="no") {?>
			<h2>No permission was granted to the requestor.  If you still want to grant permission, please <a href="<?php echo $mx005cr?> >"click here to reconfirm</a>.</h2>
			<?php } ?>
		</div>
	</div>
</div>
</div>
</div>
</div>  
 <div class="bottom"><span></span></div>
</div>