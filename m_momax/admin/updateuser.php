<?php
	include($p_guest); //get guest info
	include('com_momax/admin/left_col.php');
	include('com_momax/admin/con_log/log_updateuser.php'); 
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
		<a href="<?php echo $mx005uu?>">Update User Rights</a>
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
			<h2>Update Group and Invited users rights</h2>
		    <?php if($_GET['act']=="") { ?>
		        <br />
                Choose a group user to update.
		        <table style="width:100%; border-spacing:0;">
		        <tr><th class="utbl1">Name</th><th class="utbl2">E-mail</th><th class="utbl3">Type</th><th class="utbl4">Status</th><th class="utbl5">Action</th></tr>
		        <?php if ($group_mem=="") {echo "<tr><td colspan='5'>No group users</td></tr>"; } else {echo $group_mem;} ?>
		        </table>
                <br />
                Choose an invited user to update.
		        <table style="width:100%; border-spacing:0;">
		        <tr><th class="utbl1">Name</th><th class="utbl2">E-mail</th><th class="utbl3">Type</th><th class="utbl4">Status</th><th class="utbl5">Action</th></tr>
		        <?php if ($user_group=="") {echo "<tr><td colspan='5'>No invited users</td></tr>"; } else {echo $user_group;} ?>
		        </table>
                </p>
		    <?php } if($_GET['act']=="search") { ?>
		    <p>Please grant rights for <b><?php echo $first_name ?></b> below.</p>
		    <form action="<?php echo $mx005uu?>&act=go" name="uuser_form" onsubmit="return validateForm_uu()" method="post">          
		        <p>        
		        <table>
		        <tr><td class="tablepad1">User Status:*</td>
		        	<td  class="tablepad2"><?php echo $status ?></td></tr>
		        <tr><td class="tablepad1">User Name:</td>
		        	<td  class="tablepad2"><input type "text" <?php if ($_GET['mty']=="invited") { echo "disabled='true'"; } ?> size="50" name="first_name" value="<?php echo $first_name ?>"></td></tr>
		
		        <tr><td class="tablepad">User E-mail:*</td>
		        	<td  class="tablepad"><input type "text"  <?php if ($_GET['mty']=="invited") { echo "disabled='true'"; } ?> size="50" name="email" id="email" value="<?php echo $email ?>" onchange="beta_user_reg(document.uuser_form.email.value,'<?php echo $update_user_id ?>')"></td></tr>	
		        <tr><td class="tablepad1"><b>Security Info:</b></td>
		        	<td  class="tablepad2">
		        	<b>Level 1</b> - Read only &nbsp;&nbsp;&nbsp;&nbsp;<b>Level 2</b> - Add/edit account records<br>
		        	<b>Level 3</b> - Add/edit/delete account records 
		        	</td></tr>	
			        <tr><td class="tablepad1">Budgeting:</td>
			        	<td  class="tablepad2">
			        	<table><tr><td class='inntablepad'>
				        	<input type="checkbox" name="grant_budget" id="grant_budget" value="yes" onclick="chk_budget_sec()" <?php if ($budget_sec==1 or $budget_sec==2 or $budget_sec==3) {echo "checked='yes'";} ?> /> Budget sheet 
				        	</td><td class='inntablepad'>
				        	<input name="budget_sl" type="radio" value="3" <?php if ($budget_sec==3) {echo "checked='yes'";} ?> /> Level 1
				        	<input name="budget_sl" type="radio" value="2" <?php if ($budget_sec==2) {echo "checked='yes'";} ?> /> Level 2
				        	<input name="budget_sl" type="radio" value="1" <?php if ($budget_sec==1) {echo "checked='yes'";} ?> /> Level 3
			        	</td></tr></table>
			        	</td></tr>
			        <?php if ($_GET['mty']=="invited123") { //turn off?>
			        <tr><td class="tablepad1">Saving Sheet Rights:</td>
			        	<td  class="tablepad2">
			        	<input name="saving_sl" type="radio" value="3" <?php if ($saving_sec==3) {echo "checked='yes'";} ?> /> Level 1
			        	<input name="saving_sl" type="radio" value="2" <?php if ($saving_sec==2) {echo "checked='yes'";} ?> /> Level 2
			        	<input name="saving_sl" type="radio" value="1" <?php if ($saving_sec==1) {echo "checked='yes'";} ?> /> Level 3
			        	</td></tr>
				<?php } ?>
		        <tr><td class="tablepad1">Accounts:</td>
		        	<td class="tablepad2">
		            	<table>
		            	<?php echo $account_type ?>
		            	</table>
		        	</td></tr>    
		        <tr><td  class="tablepad"></td>
		        <td  class="tablepad">
		        <div class="form_trans">
		      	  <span class="formw"><input class="submit" type="submit" name="contact_submitted" value=" update user rights " /></span>       
		      	</div>
		        </td></tr>
		        </table>            
		        </p> 
		        <input type="hidden" name="uid"  value="<?php echo $update_user_id ?>"/>
		        <input type="hidden" name="mty"  value="<?php echo $_GET['mty'] ?>" />
                <input type="hidden" name="not_added"  value="<?php echo $add_invited ?>" />
			</form>	
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