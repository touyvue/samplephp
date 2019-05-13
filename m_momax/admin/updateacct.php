<?php
	include($p_guest); //get guest info
	include('com_momax/admin/left_col.php');
	include('com_momax/admin/con_log/log_updateacct.php'); 
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
		<a href="<?php echo $mx005 ?>">ADMIN</a> &nbsp;/&nbsp;<?php if ($email!="") { ?>Update Login Info<?php }else { ?>Create Login<?php } ?>
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
		<?php if ($form_submit == "yes") { ?>
			<br /><br />
			<p align="center"><h1>Thank you for signing up for Maxmoni.</h1></p>  <p align="center">A confirmation email has been sent to you at <?php echo $email ?>.  
			Please check your e-mail and confirm it.<br></p>
			<p align="center"><br>Thank you for signing up!<br><br> --The Maxmoni Team<br><a href="www.maxmoni.com"></a>http://www.maxmoni.com<br><br><br><br></p>
		<?php } else { ?>
			
		<?php if ($email!="") { ?>
		<h2>My Login Information</h2>
	    <p>Please update your account information below.</p>
	    <?php }else { ?>
	    <h2>Create Login ID and Password</h2>
	    <p>You have <u>not signed up</u>.  Once you log out or close out, you will <u>NOT</u> be able to log back in.  Please create your login/password below.</p>
	    <?php } ?>
	    <form action="<?php echo $mx005ma?>" name="myacct_form" onsubmit="return validateForm_ma()" method="post" enctype="multipart/form-data">          
	        <p>        
	        <table>
		        <tr><td class="tablepad1">Name:*</td>
		        	<td  class="tablepad2"><input type "text" size="50" name="first_name" value="<?php echo $first_name ?>"></td></tr>	
		        <tr><td class="tablepad">E-mail:*</td>
                        <td  class="tablepad"><input type "text" <?php if ($email!="") { ?>disabled="true"<?php } ?> name="email" id="email" size="50" onchange="beta_user_reg(document.myacct_form.email.value,'<?php echo $user_id ?>')" value="<?php echo $email ?>"></td></tr>		
		        <tr><td  class="tablepad">Password:*</td>
		        <td  class="tablepad"><input size="30" type="password" id="password" name="password" value="<?php echo $password; ?>" onchange="check_password(this.value)" /></td></tr>
		        <tr><td  class="tablepad">Picture:</td><td><input type="file" name="mx_self" id="mx_self" onChange="fileyype(this.value, ['gif', 'jpg', 'png', 'jpeg']);"></td></tr>
		        <tr><td  class="tablepad">Group Code:</td>
		        <td  class="tablepad"><input size="20" type="text" id="grp_cod" name="grp_cod" value="<?php echo $grp_cod ?>" onchange="chk_grp_code(this.value,'<?php echo $grp_cod ?>','<?php echo $user_id ?>')" /> <?php if($gr_admin_set=="yes"){?><input type="checkbox" name="grp_adm" id="grp_adm" checked="checked" disabled="disabled" /> Group Admin<?php } ?>	
                <?php if ($email=="") { ?><br /><small><i>If you're part of a group, enter Group Code.</i></small><?php }?>
                </td></tr>
                <tr><td  class="tablepad"></td>
		        <td  class="tablepad">
		        <div class="form_trans">
		      	  <span class="formw"><input class="submit" type="submit" name="contact_submitted" value="<?php if ($email!="") { ?>Update<?php }else{?>Create Login<?php }?>" /></span>       
		      	</div>
		        </td></tr>
	        </table>            
	        </p> 
	        <?php if ($email=="") { ?><input type="hidden" name="new_user" value="yes"><?php }?>
            <?php if ($email!="") { ?><input type="hidden" name="ex_email" value="<?php echo $email ?>"><?php }?>
            <input type="hidden" name="grp_admin_yn" id="grp_admin_yn" value="<?php echo $gr_admin ?>" />
            <input type="hidden" name="grp_cod_yn" id="grp_cod_yn" value="<?php echo $grp_cod ?>" />
            <input type="hidden" name="mis_pas" id="mis_pas" value="<?php echo $miss_pass ?>" />
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