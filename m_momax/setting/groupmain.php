<?php
	include($p_guest); //get guest info
	include('m_momax/setting/con_log/log_groupmain.php'); 
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
                <div class="col-md-8">
                    
                    <!-- Begin main group setting widget -->
                    <div class="widget">
                        <!-- Begin widget-head -->
                        <div class="widget-head">
                          <div class="pull-left"><?php echo $groupSetting ?></div>
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
                                  	<?php if($memberID=="100100100"){?>
                                    	<tr><td><a href="<?php echo $mx005ct ?>">[Set up Consortium]</a></td><td></td></tr>
									<?php } ?>
									<?php echo $consortSetup; ?>
									<?php echo $organSetup; ?>
                                    <?php echo $groupSetup; ?>
                                  </table>
                                  
                              </div>
                          <div class="widget-foot"></div>
                        </div>
                        <!-- End widget-content -->
                    </div>
                    <!-- End main group setting widget -->
                    
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