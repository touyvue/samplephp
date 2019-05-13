<?php
	include($p_guest); //get guest info
	include('m_momax/project/con_log/log_index.php'); 
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
              <h2 class="pull-left"><a href="index.php"><i class="fa fa-home"></i></a> <a href="<?php echo $mx002pj ?>">Events</a></h2>
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
                    <!-- Begin my budget widget -->
                    <div class="widget">
                        <!-- Begin widget-head -->
                        <div class="widget-head">
                          <div class="pull-left">Event Overview</div>
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
                                      <th>Event</th>
                                      <th>Amount</th>
                                      <th>Date</th>
                                    </tr>
                                    <?php echo $projectList; ?>   
                                    <?php echo $projectListTot; ?>                                                             
                                  </table>
                              </div>
                          <div class="widget-foot">
                          	<a href="<?php echo $mx005ps ?>">Setting</a>
                            <?php if($licenseID=="1003" or $licenseID=="1002" or $licenseID=="1001"){ ?><span class="divider"> / </span><a href="<?php echo $mx005sp ?>">Share Events</a><?php } ?>
                          </div>
                        </div>
                        <!-- End widget-content -->
                    </div>
                    <!-- End my budget widget -->
                    
                    <?php if ($projectSharedCt > 0){ ?>
                    <!-- Begin shared budgets widget -->
                    <div class="widget">
                        <!-- Begin widget-head -->
                        <div class="widget-head">
                          <div class="pull-left">Shared Events</div>
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
                                  <th>Owner</th>
                                  <th>Event</th>
                                  <th>Amount</th>
                                  <th>Date</th>
                                </tr>
                                <?php echo $projectSharedList ?>
                                <?php echo $projectSharedListTot ?>
                              </table>
                            </div>
                          <div class="widget-foot"></div>
                        </div>
                        <!-- End widget-content -->
                    </div>
                    <!-- End shared budgets widget -->
                    <?php } ?>
                    
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