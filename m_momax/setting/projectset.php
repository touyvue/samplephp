<?php
	include($p_guest); //get guest info
	include('m_momax/setting/con_log/log_projectset.php'); 
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
                    <!-- Begin my accounts widget -->
                    <div class="widget">
                        <!-- Begin widget-head -->
                        <div class="widget-head">
                          <div class="pull-left"><?php echo $projectSetting ?></div>
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
                                      <th>Description</th>
                                      <th></th>
                                    </tr>
                                    <?php echo $projectList; ?>   
                                  </table>
                              </div>
                          <div class="widget-foot"><?php echo $projectListAdd ?></div>
                        </div>
                        <!-- End widget-content -->
                    </div>
                    <!-- End my accounts widget -->
                    
                    <!-- add budget modal -->
                    <div id="myModalAdd" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class='fa fa-close'></i></button>
                                <h4 class="modal-title">New Project</h4>
                              </div>
                              <div class="modal-body">
                                
                                <!-- form -->
                                  <div class="padd">
                
                                    <!-- Form starts.  -->
                                     <form class="form-horizontal" role="form">
                                        <div class="form-group">
                                          <label class="col-lg-3 control-label">Project Name*</label>
                                          <div class="col-lg-7">
                                            <input type="text" class="form-control" id="projName" name="projName" />
                                          </div>
                                        </div>
                                        
                                        <div class="form-group">
                                          <label class="col-lg-3 control-label">Description</label>
                                          <div class="col-lg-7">
                                            <input type="text" class="form-control" id="description" name="description" rows="1" placeholder="" />
                                          </div>
                                        </div>
                                                                               
                                        <input type="hidden" id="mid" value="<?php echo $memberID ?>" />
                                        <input type="hidden" id="state" value="new" />
                                        <input type="hidden" id="newstate" value="new" />
                                        <input type="hidden" id="edtProjID" name="edtProjID" value="" />
	                                      </form>
                                  </div>
                                <!-- form -->
                                
                              </div>
                              <div class="modal-footer">
                                <button type="button" id="insertNewProject" class="btn btn-primary"><span id="eProjType"></span></button>
                              </div>
                            </div>
                        </div>
                    </div>
                    <!-- end add budget modal -->
                    
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