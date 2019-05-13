<?php
	include($p_guest); //get guest info
	include('m_momax/nonfin/con_log/log_memtrack.php'); 
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
              <h2 class="pull-left"><a href="index.php"><i class="fa fa-home"></i></a> <a href="<?php echo $mx009 ?>">Member Tracking</a></h2>
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
                    <!-- Begin member tracking info widget -->
                    <div class="widget">
                        <!-- Begin widget-head -->
                        <div class="widget-head">
                          <div class="pull-left">Tracking</div>
                          
                          <div class="widget-icons pull-right">
                            <a href="#" class="wminimize"><i class="fa fa-chevron-up"></i></a> 
                            <a href="#" class="wclose"><i class="fa fa-times"></i></a>
                          </div>  
                          <div class="clearfix"></div>
                        </div>
                        <!-- End widget-head -->
                            <!-- Begin widget-content -->
                        <div class="widget-content referrer">
                            <br />
                            <form class="form-horizontal" role="form">
                                <div class="form-group">
                                  <label class="col-lg-2 control-label">Group:</label>
                                  <div class="col-lg-5">
                                    <input type="text" class="form-control" disabled="disabled"  value="<?php echo $grpName ?>" id="trkpurpose">
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label class="col-lg-2 control-label">Purpose:</label>
                                  <div class="col-lg-5">
                                    <input type="text" class="form-control" disabled="disabled"  value="<?php echo $tkName ?>" id="trkpurpose">
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label class="col-lg-2 control-label">Value:</label>
                                  <div class="col-lg-5">
                                    <input type="text" class="form-control" disabled="disabled"  value="<?php echo $genTot ?>" id="trkValue">
                                  </div>
                                </div>
                            </form>
                                                      
                          <div class="widget-foot">
                        	<div class="clearfix"></div>
                          </div>
                        </div>
                        <!-- End widget-content -->
                    </div>
                    <!-- End member tracking info widget -->
                    
                    
                    <!-- Begin member tracking details widget -->
                    <div class="widget">
                        <!-- Begin widget-head -->
                        <div class="widget-head">
                          <div class="pull-left"><?php echo $tkName." (".$predate.")"; ?></div>
                          
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
                                <thead>
                                    <tr>
                                      <th>Member</th>
                                      <th>Amount</th>
                                      <th>Present</th>
                                      <th>Note</th>
                                      <th></th>
                                    </tr>
                                </thead>
                                <tbody>
									<?php echo $itemList ?>  
                                </tbody>
                              </table>
                              
                              <!-- add budget modal -->
                                <div id="myModalAdd" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                      <div class="modal-content">
                                          <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class='fa fa-close'></i></button>
                                            <h4 class="modal-title">Add Member</h4>
                                          </div>
                                          <div class="modal-body">
                                            
                                            <!-- form -->
                                              <div class="padd">
                            
                                                <!-- Form starts.  -->
                                                 <form class="form-horizontal" role="form" name="trkmemdet_frm" id="trkmemdet_frm" >
                                                    <div class="form-group">
                                                      <label class="col-lg-3 control-label">Group Member:</label>
                                                      <div class="col-lg-5">
                                                        <select class="form-control" id="delmemberGrp" name="delmemberGrp">
                                                          <?php echo $memberList; ?>
                                                        </select>
                                                      </div>
                                                    </div> 
                                                    <input type="hidden" id="accountid" name="accountid" value="<?php echo $accountID ?>" />  
                                                    <input type="hidden" id="trkmemberid" name="trkmemberid" value="<?php echo $trackID ?>" />
                                               
                                                  </form>
                                              </div>
                                            <!-- form -->
                                            
                                          </div>
                                          <div class="modal-footer">
                                            <button type="button" id="addTrkmemDet" class="btn btn-primary"><span id="addSubDet"></span></button>
                                          </div>
                                    	</div>
                                    </div>
                                </div>
                                <!-- end add budget modal -->
                              
                          </div>
                          
                          <div class="widget-foot">
                            <?php if ($trkmemDetCt < $allGrpMemCt and $memberID == $trkmemberOwner){ ?>
                            <button id="trkmenDetModal" class="btn btn-xs btn-success pull-left">Add Member</button>
                            <?php } ?>
                        	<div class="clearfix"></div>
                          </div>
                          
                        </div>
                        <!-- End widget-content -->
                    </div>
                    <!-- End member tracking details widget -->
                    
                    
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