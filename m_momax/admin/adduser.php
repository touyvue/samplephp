<?php
	include($p_guest); //get guest info
	include('com_momax/admin/left_col.php');
	include('com_momax/admin/con_log/log_adduser.php'); 
?>

<!-- insert the page content here -->
<div id="border-round">
<div class="top"><span><?php if ($active_page!="") { ?>
	<div id="main_header">
		<a href="<?php echo $mx005 ?>">ADMIN</a> &nbsp;/&nbsp;
		Add New User &nbsp;&nbsp;
	</div>
<?php } ?>
</span></div>
<div class="center-content">
<div id="main_pri">
	<div id="main_pri_col1">
		<?php echo $post_pix; ?>
		<p>&nbsp;</p>
	</div>
	<div id="main_pri_col2">
	<div id="contain_main">
		<div id="report">
			<h2>Add New User</h2>
		    <p>Please enter new user information below.</p>
		    
		    <form action="<?php echo $mx005au?>" name="auser_form" onsubmit="return validateForm_au()" method="post">          
		        <p>        
		        <table>
		        <tr><td class="tablepad1">What would you like to be called?:*</td>
		        	<td  class="tablepad2"><input size="50" type "text" name="first_name"></td></tr>
		
		        <tr><td  class="tablepad">Login ID:*</td>
		        <td  class="tablepad"><input size="20" type="text" name="loginid_new" id="loginid_new" onchange="checklogin(document.auser_form.loginid_new.value)" />
		        <span id="loginchk"></span>
		        </td></tr>
		        <tr><td  class="tablepad">Password:*</td>
		        <td  class="tablepad"><input size="20" type="password" name="password_new" /></td></tr>
		
		        <tr><td class="tablepad">E-mail:*</td>
		        	<td  class="tablepad"><input size="50" type "text" name="email" id="email" onchange="chk_exist_email(document.auser_form.email.value,'member')"></td></tr>	
		        <tr><td class="tablepad1"><b>Security Level:</b></td>
		        	<td  class="tablepad2">
		        	<b>Level 1</b> - Add/edit/delete account records &nbsp;&nbsp;&nbsp;&nbsp;<b>Level 3</b> - Read only<br>
		        	<b>Level 2</b> - Add/edit account records 
		        	</td></tr>	
		        <tr><td class="tablepad1">Budget Module:</td>
		        	<td  class="tablepad">
		        	<input name="budget_sl" type="radio" value="2" /> Level 1
		        	<input name="budget_sl" type="radio" value="3" /> Level 2
		        	<input name="budget_sl" type="radio" value="4" /> Level 3
		        	</td></tr>
		        <tr><td class="tablepad1">Saving Module:</td>
		        	<td  class="tablepad">
		        	<input name="saving_sl" type="radio" value="2" /> Level 1
		        	<input name="saving_sl" type="radio" value="3" /> Level 2
		        	<input name="saving_sl" type="radio" value="4" /> Level 3
		        	</td></tr>
		        <tr><td class="tablepad1">Account Module:</td>
		        	<td>
		            	<table>
		            	<?php echo $account_type ?>
		            	</table>
		        	</td></tr>	
		        <tr><td  class="tablepad"></td>
		        <td  class="tablepad">
		        <div class="form_trans">
		      	  <span class="formw"><input class="submit" type="submit" name="contact_submitted" value="add user" /></span>       
		      	</div>
		        </td></tr>
		        </table>            
		        </p> 
			</form>
		</div>
	</div>
</div>
</div>
</div>
</div>  
 <div class="bottom"><span></span></div>
</div>