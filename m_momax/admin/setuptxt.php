<?php
	include($p_guest); //get guest info
	include('com_momax/admin/left_col.php');
	include('com_momax/admin/con_log/log_setuptxt.php'); 
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
	  		<div id="main_header"><a href="<?php echo $mx009 ?>">SETTING</a> / Set up Text Message</div>
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
			<h2>Set Up Text Message</h2>

		    <form name="setuptxt_frm" method="post">
            	<table width="50%" border="0"><tr><td>Phone Carrier</td>
                <td>
		    	<select name="phone_carrier">
		    		<option value="">Choose carrier</option>
		    		<option value="Verizon">Verizon</option>
		    		<option value="Sprint">Sprint</option>
		    		<option value="US Cellular">US Cellular</option>
		    	</select></td></tr>
                <tr><td>When to receive text message</td>
		    	<td>
                <input type="checkbox" name="daily"> Daily
		    	<input type="checkbox" name="weekly"> Weekly
		    	<input type="checkbox" name="bi_weekly"> Bi-weekly
		    	<input type="checkbox" name="monthly"> Monthly
		    	</td></tr>
                <tr><td>Time</td>
                <td>
		    	<select name="daily_time">
		    			<option value="">Choose time</option>
		    			<option value="">8:00 a.m.</option>
		    			<option value="">9:00 a.m.</option>
		    			<option value="">10:00 a.m.</option>
		    		</select>
		    	</td></tr>
                <tr><td>
		    	Information would like to receive:</td>
		    	<td><input type="checkbox" name="acct_balance"> Account forecast and actual balance</td></tr>
		    	<tr><td></td><td><input type="checkbox" name="budget_amt"> Budget amount</td></tr></table>
		    </form>

		    <p>&nbsp;</p>
		</div>
	</div>
</div>
</div>
</div>
</div>  
 <div class="bottom"><span></span></div>
</div>