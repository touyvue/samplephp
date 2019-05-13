<?php
	include($p_guest); //get guest info
	//include('com_momax/admin/left_col.php');
	include('com_momax/admin/con_log/log_newuser.php'); 
?>

<!-- insert the page content here -->
<div id="border-round">
<div class="top"><span></span></div>
<div class="center-content">
	<div id="contain_main">
		<?php if ($form_submit == "yes") { ?>
			<p align="center"><h1>Thank you for signing up for Maxmoni.</h1></p>  <p align="center">A confirmation email has been sent to you at <?php echo $email ?>.  
			Please check your e-mail and confirm it.<br><?php echo "=".$confirmcode ?></p>
			<p align="center"><br>Thank you for signing up!<br><br> --The Maxmoni Team<br><a href="www.maxmoni.com">http://www.maxmoni.com</a><br><br><br><br></p>
		<?php } else { ?>
		<div class="log_form">
			<h3>Let's Start</h3>
		    <p><br>Sign up here. 
		     <small><i>(* required fields)</i></small></p><br>
		    <form action="<?php echo $mx005nu ?>" name="nuser_form" onsubmit="return validateForm_nu()" method="post">          
			<div class="form_login">		          
		        <table>
			        <tr><td class="tablepad1">Name:*</td>
			        	<td  class="tablepad2"><input size="30" type "text" name="first_name"></td></tr>
			        <tr><td class="tablepad1">E-mail:*</td>
			        	<td  class="tablepad2"><input size="30" type "text" name="email" id="email" onchange="chk_exist_email(document.nuser_form.email.value,'owner')"></td></tr>	
			        <tr><td  class="tablepad1">Login ID:*</td>
			        	<td  class="tablepad2"><input size="30" type="text" id="loginid_new" name="loginid_new" onchange="checklogin(document.nuser_form.loginid_new.value)" /></td></tr>
			        <tr><td  class="tablepad1">Password:*</td>
			        	<td  class="tablepad2"><input size="30" type="password" name="password_new" /></td></tr>
						<input type="hidden" name="act" value="go">
			        <tr><td  class="tablepad1"></td>
			        	<td  class="tablepad2">
			        	<div class="form_trans"><input class="submit" type="submit" name="contact_submitted" value="Sign Up" /></div>
			        	</td></tr>
		        </table>            
			</div>
			</form>
		<?php } ?>
		<p>&nbsp;</p>
	</div>
	</div>
</div>  
 <div class="bottom"><span></span></div>
</div>