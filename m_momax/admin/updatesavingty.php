<?php
	include($p_guest); //get guest info
	include('com_momax/admin/left_col.php');
	include('com_momax/admin/con_log/log_updatesavingty.php'); 
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
	  		<div id="main_header"><a href="<?php echo $mx007 ?>">SAVING</a> &nbsp;/</div>
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
		<div id="saving"><br>
			<h2>Saving</h2>
		    <?php if ($_SESSION['seclevel']==1 or $_SESSION['seclevel']==2) { 
		    		if ($_GET['wk']=="sav") { ?>
		    			<h3>Add/Update Saving Type</h3>
						<form method="post" name="savupdate_form" action="<?php echo $index_url?>?pa=mx007us&wk=sav">
			    			<table style="width:100%; border-spacing:0;">
			    			<?php 	if ($_SESSION['seclevel']==1) { ?>
			      						<tr><th>Owner</th><th>Saving Type</th><th>Saving Description</th><th>Action</th></tr>
			      					<?php } else { ?>
			      						<tr><th>Saving Type</th><th>Saving Description</th><th>Action</th></tr>
			      					<?php } echo $saving_type; ?>
			    			</table>
		    			</form>
		    <?php }} ?>
		</div>
	</div>
</div>
</div>
</div>
</div>  
 <div class="bottom"><span></span></div>
</div>