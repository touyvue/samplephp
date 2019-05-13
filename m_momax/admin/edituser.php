<?php
	include($p_guest); //get guest info
	include('com_momax/admin/left_col.php');
	include('com_momax/admin/con_log/log_edituser.php'); 
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
		<a href="<?php echo $mx005 ?>">ADMIN</a> / Group Users
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
		<h2>Group Users</h2>
	    <table style="width:100%; border-spacing:0;">
        <tr><th>Group Name</th><th>Status</th><th>Action</th></tr>
        <?php echo $my_user_group ?>
        <tr><td colspan="3"><a href="<?php echo $mx005eu."&grp=new";?>">Invite New User</a></td></tr>
        </table>
        
        <?php if ($user_id == "100101"){ ?>
            <br /><p>Tasks:</p>
            <p>Allow to turn off/on user</p>
            <p>Allow to invite new user to the group, using e-mail</p>
        <?php } ?>
        
        <?php if($_GET['grp']=="new"){ ?>
            <form action="<?php echo $mx005eu."&grp=".$_GET['grp']."&act=go" ?>" name="grp_user_form" method="post" onsubmit="return val_new_user()" enctype="multipart/form-data">          
                <p>        
                <table>
                    <tr><td class="tablepad1">Name:</td>
                        <td  class="tablepad2"><input type "text" size="30" name="user_name" id="user_name" /></td></tr>	
                    <tr><td class="tablepad">E-mail:*</td>
                        <td  class="tablepad"><input type "text" name="email" id="email" size="30" onchange="beta_user_reg(this.value,'<?php echo $user_id ?>')" /></td></tr>		
                    <tr><td  class="tablepad">Group ID:</td>
                    <td  class="tablepad"> <?php echo $group_code ?><input type="hidden" id="grp_id" name="grp_id" value="<?php echo $group_code ?>" /></td></tr>
                    <tr><td  class="tablepad"></td>
                    <td  class="tablepad">
                    <div class="form_trans">
                      <span class="formw"><input class="submit" type="submit" name="contact_submitted" value="Invite New User" /></span>       
                    </div>
                    </td></tr>
                </table>            
                </p> 
            </form>
        <?php } ?>
        <?php if($form_submit=="yes"){ ?>
        	<p>Invitation has been sent to <?php echo $grp_email ?></p>
        <?php } ?>
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