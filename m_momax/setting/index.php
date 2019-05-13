<?php
	include($p_guest); //get guest info
	include('m_momax/setting/con_log/log_index.php'); 
?>

<!-- Begin main content -->
<div class="content">
  	<!-- Sidebar -->
	<?php require_once('m_content/sidebar.php'); ?>
    <!-- Sidebar ends -->

  	<!-- Begin main -->
  	<div class="mainbar">
	    <!-- Begin breadcrumb1 -->
            <div class="page-head">
              <h2 class="pull-left"><a href="index.php"><i class="fa fa-home"></i></a> <a href="<?php echo $mx005 ?>">Setting</a></h2>
              <h2 class="pull-left">&nbsp;</h2>
              <div class="bread-crumb pull-right"><a href="<?php echo $mx001hp?>"><i class="fa fa-question"></i>Help</a></div>
              <div class="clearfix"></div>
            </div>
	    <!-- End breadcrumb1 -->

	    <!-- Begin matter -->
	    <div class="matter">
        	<!-- Begin container -->
            <div class="container">
              <!-- Begin row1 -->
              <div class="row">
                <div class="col-md-9">
                    
                    <!-- Begin profile widget -->
                    <div class="widget">
                        <!-- Begin widget-head -->
                        <div class="widget-head">
                          <div class="pull-left"><?php echo $profileSetting ?></div>
                          <div class="widget-icons pull-right">
                            <a href="#" class="wminimize"><i class="fa fa-chevron-up"></i></a> 
                            <a href="#" class="wclose"><i class="fa fa-times"></i></a>
                          </div>  
                          <div class="clearfix"></div>
                        </div>
                        <!-- End widget-head -->
                            <!-- Begin widget-content -->
                        <div class="widget-content referrer">
                              <div class="table-responsive">
                                  <table class="table table-striped table-bordered table-hover">
                                    <tr>
                                      <th><a href="<?php echo $mx005pu ?>">Profile</a><br /><span class="smtext">Edit your name, email, and password.</span></th>
                                    </tr>
                                    <?php echo $groupSetup; ?>
                                  </table>
                              </div>
                          <div class="widget-foot"></div>
                        </div>
                        <!-- End widget-content -->
                    </div>
                    <!-- End profile widget -->
                    
                    <!-- Begin accounts widget -->
                    <div class="widget">
                        <!-- Begin widget-head -->
                        <div class="widget-head">
                          <div class="pull-left"><?php echo $adminSetting ?></div>
                          <div class="widget-icons pull-right">
                            <a href="#" class="wminimize"><i class="fa fa-chevron-up"></i></a> 
                            <a href="#" class="wclose"><i class="fa fa-times"></i></a>
                          </div>  
                          <div class="clearfix"></div>
                        </div>
                        <!-- End widget-head -->
                            <!-- Begin widget-content -->
                        <div class="widget-content referrer">
                              <div class="table-responsive">
                                  <table class="table table-striped table-bordered table-hover">
                                    <tr><th><a href="<?php echo $mx005as ?>">Account Setup</a><br /><span class="smtext">Add, edit, and delete accounts.</span></th>
                                    	<?php if($licenseID=="1003" or $licenseID=="1002" or $licenseID=="1001"){ ?>
                                    		<th><a href="<?php echo $mx005sa ?>">Share Accounts</a><br /><span class="smtext">Share any of your accounts with anyone within your group(s).</span></th></tr>
                                    	<?php }else{ ?><th></th></tr><?php } ?>
                                    <tr><th><a href="<?php echo $mx005bs ?>">Budget Setup</a><br /><span class="smtext">Add, edit, and delete budget sheets.</span></th>
                                    	<?php if($licenseID=="1003" or $licenseID=="1002" or $licenseID=="1001"){ ?>
                                    		<th><a href="<?php echo $mx005sb ?>">Share Budgets</a><br /><span class="smtext">Share any of your budgets with anyone within your group(s).</span></th></tr>
                                    	<?php }else{ ?><th></th></tr><?php } ?>
									
                                    <tr><th><a href="<?php echo $mx005ps ?>">Event Setup</a><br /><span class="smtext">Add, edit, and delete event.</span></th>
                                    	<?php if($licenseID=="1003" or $licenseID=="1002" or $licenseID=="1001"){ ?>
                                    		<th><a href="<?php echo $mx005sp ?>">Share Event</a><br /><span class="smtext">Share any of your events with anyone within your group(s).</span></th></tr>
                                    	<?php }else{ ?><th></th></tr><?php } ?>
                                    
                                    <tr><th><a href="<?php echo $mx005cs ?>">Transaction Category Setup</a><br /><span class="smtext">Add, edit, and delete category.</span></th>                                    
                                    	<th><a href="<?php echo $mx005tc ?>">General Tracking Category Setup</a><br /><span class="smtext">Add, edit, and delete category.</span></th>
                                    </tr>
                                    
                                    <tr><th><a href="<?php echo $mx005pt ?>">Post Setting</a><br /><span class="smtext">Set rights who can see your posting.</span></th>
                                    	<th><a href="<?php echo $mx005tt ?>">Tag Setting</a><br /><span class="smtext">Set tag for grouping category.</span></th>
                                    </tr>
                                    <tr><th></th><th></th></tr>
                                  </table>
                              </div>
                          <div class="widget-foot"></div>
                        </div>
                        <!-- End widget-content -->
                    </div>
                    <!-- End accounts widget -->
                    
                </div>
                <div class="col-md-3">
                    <?php require_once('m_momax/message/message.php'); ?>
                </div>
              </div>
              <!-- End row1 -->
            </div>
			<!-- End container -->
        </div>
		<!-- End matter -->
    </div>
<!-- End main -->
    <div class="clearfix"></div>
</div>
<!-- End content -->