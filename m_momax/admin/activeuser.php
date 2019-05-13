<?php
	include($p_guest); //get guest info
	include('com_momax/admin/left_col.php');
	include('com_momax/admin/con_log/log_activeuser.php'); 
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
		<h2>Active Partner Group</h2>
	    <p>Invite new group below.</p>
	    <table style="width:100%; border-spacing:0;">
        <tr><th>Group Name</th><th>Group Admin</th><th>Subscription</th><th>Group #</th><th>Active</th></tr>
        <?php echo $user_group; ?>
        <tr><td colspan="5"><a href="<?php echo $mx005ac."&grp=new";?>">Invite New Group</a></td></tr>
        </table>
        <?php if($_GET['grp']=="new" and $form_submit!="yes"){ ?>
        <form action="<?php echo $mx005ac."&grp=".$_GET['grp']."&act=go" ?>" name="grp_form" method="post" onsubmit="return val_new_grp()" enctype="multipart/form-data">          
	        <p>        
	        <table>
		        <tr><td class="tablepad1">Group Name:*</td>
		        	<td  class="tablepad2"><input type "text" size="30" name="grp_nam" id="grp_nam" /></td></tr>
                <tr><td class="tablepad1">Group Owner:</td>
		        	<td  class="tablepad2"><input type "text" size="30" name="grp_owner" id="grp_owner" /></td></tr>	
		        <tr><td class="tablepad">E-mail:*</td>
		        	<td  class="tablepad"><input type "text" name="email" id="email" size="30" onchange="beta_user_reg(this.value,'<?php echo $user_id ?>')" /></td></tr>		
		        <tr><td  class="tablepad"></td>
		        <td  class="tablepad">
		        <div class="form_trans">
		      	  <span class="formw"><input class="submit" type="submit" name="contact_submitted" value="Invite New Group" /></span>       
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
		</div>
	</div>
</div>
</div>
</div>
</div>  
 <div class="bottom"><span></span></div>
</div>