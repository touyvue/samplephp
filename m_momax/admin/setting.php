<?php
	include($p_guest); //get guest info
	include('com_momax/admin/left_col.php');
	include('com_momax/admin/con_log/log_setting.php'); 
?>
<!-- insert the page content here -->
<div id="border-round"><div class="top">
	<div id="tryhead">
      <b class="b_box">
	  <b class="b_box1"><b></b></b>
	  <b class="b_box2"><b></b></b>
	  <b class="b_box3"></b>
	  <b class="b_box4"></b>
	  <b class="b_box5"></b></b>
      <div class="b_box_pri">
		<?php if ($active_page!="") { ?>
	  		<div id="main_header"><a href="<?php echo $mx009 ?>">SETTING</a></div>
		<?php } ?>
	  </div>
</div></div>
<div class="center-content">
<div id="main_pri">
	<div id="main_pri_col1">
		<?php echo $post_pix; ?>
		<p>&nbsp;</p>
	</div>
	<div id="main_pri_col2">
	<div id="contain_main">
		<div id="report">
			
            <h2>Account Setting</h2>
		    <?php if ($seclevel==1) { ?>
            	<ul>		    		
		    		<li><a href="<?php echo $mx005ua?>&wk=acct">Add/update Accounts</a></li>
		    		<li><a href="<?php echo $mx005ua?>&wk=act">Add/update Categories</a></li>	
		    	</ul>		
                <h2>Budget Expenses vs. Actual Expenses</h2>
                <ul>
                    <li><a href="<?php echo $mx005ua?>&wk=bgact">Budget vs. account(s) setting<a/></li>
                </ul>
                
			<?php } ?>
            
		    <p>&nbsp;</p>
			<p>&nbsp;</p>
            <p>&nbsp;</p>
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