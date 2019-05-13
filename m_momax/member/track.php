<?php
	include($p_guest); //get guest info
	include('m_momax/member/con_log/log_track.php'); 
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
              <h2 class="pull-left"><a href="index.php"><i class="fa fa-home"></i></a> <a href="<?php echo $mx002pj ?>">Member</a></h2>
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
                
                	<!-- Begin budget setup widget -->
                    <div class="widget">
                        <!-- Begin widget-head -->
                        <div class="widget-head">
                          <div class="pull-left">Individual Budget Tracking</div>
                          <div class="widget-icons pull-right">
                            <a href="#" class="wminimize"><i class="fa fa-chevron-up"></i></a> 
                            <a href="#" class="wclose"><i class="fa fa-times"></i></a>
                          </div>  
                          <div class="clearfix"></div>
                        </div>
                        <!-- End widget-head -->
                        <!-- Begin widget-content -->
                        <div class="widget-content">
                            <div class="padd">
                              <strong>Track Name:</strong> <?php echo $budgetName?><br /><strong>Date:</strong> <?php echo $date?>
                              <hr />
                              <div class="page-tables">
                              <div class="table-responsive">
                                  <table cellpadding="0" cellspacing="0" border="0" id="forecast_detail" width="100%" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                          <th class="ysort">Name</th>
                                          <th class="nsort">Family</th>
                                          <th class="nsort">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                      <?php echo $memberList ?>
                                  	</tbody>
                                    <tfoot>
                                    	<tr>
                                          <th colspan="2">Total</th>
                                          <th><span id="tracktotal" class='sinform-mem-tot'><?php echo $allTotAmount?></span></th>
                                        </tr>
                                    </tfoot>
                                  </table>
                              </div>
                              </div>
                          	  <div class="widget-foot"></div>
                        	</div>
                        </div>
                        <!-- End widget-content -->
                    </div>
                    <!-- End budget setup widget -->
                                    
                    <!-- add family member modal -->
                    <div id="familyMember" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class='fa fa-close'></i></button>
                                <h4 class="modal-title"><span id="memTitle"></span></h4>
                              </div>
                              <div class="modal-body">
                                
                                <!-- form -->
                                  <div class="padd">
                                    <!-- Form starts.  -->
                                     <form class="form-horizontal" role="form">
                                        <div class="form-group">
                                          <label class="col-lg-3 control-label"></label>
                                          <div class="col-lg-5">
                                            <strong><span id="headmember"></span> Family:</strong><br /><span id="memlisttbl"></span>
                                          </div>
                                        </div>
                                        <div class="form-group">
                                          <label class="col-lg-3 control-label"></label>
                                          <div class="col-lg-5"><strong>Add New Member:</strong></div>
                                        </div>
                                        <div class="form-group">
                                          <label class="col-lg-3 control-label">First Name:*</label>
                                          <div class="col-lg-5">
                                            <input type="text" class="form-control" id="firstname" name="firstname" placeholder="" />
                                          </div>
                                        </div> 
                                        <div class="form-group">
                                          <label class="col-lg-3 control-label">Last Name:*</label>
                                          <div class="col-lg-5">
                                            <input type="text" class="form-control" id="lastname" name="lastname" placeholder="" />
                                          </div>
                                        </div>                                                  
                                        <div class="form-group">
                                          <label class="col-lg-3 control-label">Relationship:</label>
                                          <div class="col-lg-5">
                                            <input type="text" class="form-control" id="relation" name="relation" placeholder="" />
                                          </div>
                                        </div>
                                        <div class="form-group">
                                          <label class="col-lg-3 control-label">Note</label>
                                          <div class="col-lg-5">
                                            <input type="text" class="form-control" id="mnote" name="mnote" placeholder="comment" />
                                          </div>
                                        </div>
                                        <div class="form-group">
                                          <label class="col-lg-3 control-label"></label>
                                          <div class="col-lg-5">
                                            <a href="#" onclick="addMember()"><button type="button" class="btn btn-primary">Add</button></a>
                                          </div>
                                        </div>
                                        <input type="hidden" id="consortiumid" name="consortiumid" value="<?php echo $consortiumID?>"  />
                                        <input type="hidden" id="memberid" name="memberid" value="<?php echo $memberID?>"  />
                                        <input type="hidden" id="mbid" name="mbid" value="<?php echo $memberbudgetID?>" />
                                        <input type="hidden" id="viewmemid" name="viewmemid" />
                                        <input type="hidden" id="viewmemname" name="viewmemname" />
                                        
                                      </form>
                                  </div>
                                <!-- form -->
                                
                              </div>
                              <div class="modal-footer"></div>
                            </div>
                        </div>
                    </div>
                    <!-- end family member modal -->
                    
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