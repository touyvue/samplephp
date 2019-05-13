<?php
	include($p_guest); //get guest info
	include('com_momax/admin/left_col.php');
	include('com_momax/admin/con_log/log_updateinfo.php'); 
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
		<a href="<?php echo $mx009 ?>">SETTING</a> &nbsp;/&nbsp;
		<?php if ($_GET['wk']=="acct") { echo "Account Type"; }
		      if ($_GET['wk']=="trans") { echo "Transaction Type"; }
		      if ($_GET['wk']=="act") { echo "Category Type"; } ?>
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
		<div id="saving"><br>
		    <?php if ($_SESSION['seclevel']==1) { 
		    if ($_GET['wk']=="acct") { ?>
		    <h2>Add/Update Account Type</h2>
		    <form method="post" name="acctupdate_form" action="<?php echo $mx005ua?>&wk=acct">
		    <table style="width:100%; border-spacing:0;">
		      <tr><th>Account Name</th><th>Account Description</th><th>Status</th><th></th></tr>
		      <?php echo $account_type; ?>
		    </table>
		    </form>
		    <?php }} if ($_SESSION['seclevel']==1) { 
		    if ($_GET['wk']=="trans") { ?>
		    <h2>Add/Update Transaction Type</h2>
		    <form method="post" name="transupdate_form" action="<?php echo $mx005ua?>&wk=trans">
		    <table style="width:100%; border-spacing:0;">
		      <tr><th>Transaction Type</th><th>Transaction Description</th><th></th></tr>
		      <?php echo $transaction_type; ?>
		    </table>
		    </form>
            <p>&nbsp;</p>
		    <p>&nbsp;</p>
		    <p>&nbsp;</p>
		    <p>&nbsp;</p>
		    <?php }} ?>
		    <?php if ($_GET['wk']=="act") { ?>
		    <h2>Add/Update Category Type</h2>
			<form method="post" name="actupdate_form" action="<?php echo $mx005ua?>&wk=act">
		    <table style="width:100%; border-spacing:0;">
		    <?php if ($_SESSION['seclevel']==1) { ?>
		      <tr><th>Category</th><th>Type</th><th>Category Description</th><th></th></tr>
		      <?php echo $activity_type; ?>
		    </table>
		    </form>
		    <?php }} ?>
            <?php if ($_SESSION['seclevel']==1) { 
		    if ($_GET['wk']=="bgact") { ?>
		    <h2>Budget vs. Actual Expenses Setting</h2>
		    <form method="post" name="bud_vs_acct_form" action="<?php echo $mx005ua?>&wk=bgact" onsubmit="return setval_budact()">
		    <table style="width:100%; border-spacing:0;">
		      <tr><th>Budget sheet vs. account(s): </th></tr>
              <tr><td><em>Choose account(s)</em></td></tr>
		      <?php if ($active_acct_ct > 0) {echo $budgetvsactual; } else { echo "<tr><td>No account found!</td></tr>";} ?>
              <tr><td><input class="submit" type="submit" value="Save Setting" /></td></tr>
              <tr><td><em>Weekly and monthly budget vs. actual expenses display on your <a href="<?php echo $mx00100 ?>">Main</a> page.</em></td></tr>
		    </table>
            <input type="hidden" name="all_acct_id" id="all_acct_id" value="<?php echo $active_acct ?>" />
            <input type="hidden" name="acct_ct" id="acct_ct" value="<?php echo $active_acct_ct ?>" />
            <input type="hidden" name="acct_set" id="acct_set" value="yes" />
		    </form>
            <p>&nbsp;</p>
		    <?php }} ?>
		    <p>&nbsp;</p>
	
		</div>
	</div>
</div>
</div>
</div>
</div>  
 <div class="bottom"><span></span></div>
</div>