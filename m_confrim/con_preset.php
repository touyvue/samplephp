<?php
	include($p_guest); //get guest info
	include_once('m_confrim/con_log/log_con_preset.php'); 
?>

<!-- Begin main content -->
<div class="admin-form">
  <div class="container">
  	<div class="row">
      <div class="col-md-12">
        <!-- Begin widget -->
            <div class="widget worange">
              <div class="widget-head">
                <i class="fa fa-lock"></i> <?php echo $sTitle ?></div>
              <div class="widget-content">
                <div class="padd">
                
                  <?php if ($userFlag=="" and $resetFlag=="") { ?>
                   		<?php if ($newGroup == "new"){ ?>
                        	<br><h3>Welcome to Maxmoni!</h3><p>A Maxmoni account has been set up for you. Please set your password below.</p><br>
                        <?php }else{ ?>
                   			<br><h3>Reset Password for Maxmoni account</h3><br>
                   		<?php } ?>
                   <form name="resetPassFrm" action="<?php echo $mx008cp."&sg=".$sg."&cid=".$resetID ?>" method="post" enctype="multipart/form-data">
                   <p><label>E-mail: <?php echo $email ?></label></p>
                   <p>Enter new password: <input type="password" name="password" id="password" onchange="check_password(this.id,this.value)" />
                    <input type="submit" name="contact_submitted" value="Update" /><br><br><br>
                    </p></form>
                <?php } ?> 
                
				<?php if ($userFlag=="good") { ?>
                	<?php if ($resetGrp == "good"){ ?>
                    	<h2>Password has been set up.  Thank you.</h2><br><br>
                    	<b><a href="<?php echo $mx00100 ?>">Click here</a> to log in and start using Maximoni. </b><br><br>
                    <?php }else{ ?>
                    	<h2>Password has been reset.  Thank you.</h2><br><br>
                    	<b><a href="<?php echo $mx001lg ?>">Click here</a> to log in and continue using Maximoni. </b><br><br>
               		<?php } ?>
                <?php } ?>
                
				<?php if ($resetFlag == "reset") {?>
                		<?php if ($resetGrp == "reset" and $newGroup!="") {?>
                    		<h2>Sorry for any inconvenience.  Your password setup link has been expired. Please contact your Admin to request a new setup link.
                        <?php }else{?>
                        	<h2>Sorry for any inconvenience.  Your password reset link has been expired. Please <a href="<?php echo $mx001fp ?>">click here</a> to request a new password reset link.
                    	<?php } ?>
                    <br><br><br></h2>
                <?php } ?>
                
                <label>Thank you for using Maxmoni!<br><br>
                --The Maxmoni Team </label><br>http://www.maxmoni.com
                  
				</div>
              </div>              
              <div class="widget-foot">
              	                 
              </div>
            </div>  
        <!-- Begin widget -->
      </div>
    </div>
    <div class="clearfix"></div>
  </div>
</div>
<!-- End content -->
<script type="text/javascript">
	window.onload=load_reset;
</script>