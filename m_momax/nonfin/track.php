<?php
	include($p_guest); //get guest info
	include('m_momax/nonfin/con_log/log_track.php'); 
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
              <h2 class="pull-left"><a href="index.php"><i class="fa fa-home"></i></a> <a href="<?php echo $mx009 ?>">Mileage Tracking</a></h2>
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
                                  <label class="col-lg-2 control-label">Name:</label>
                                  <div class="col-lg-5">
                                    <input type="text" class="form-control" disabled="disabled"  value="<?php echo $tkName ?>" id="tkname">
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label class="col-lg-2 control-label">Miles:</label>
                                  <div class="col-lg-5">
                                    <input type="text" class="form-control" disabled="disabled" value="<?php echo $mileageTot?>" id="tkmileage">
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label class="col-lg-2 control-label">Rate($/mile):</label>
                                  <div class="col-lg-5">
                                    <input type="text" class="form-control" disabled="disabled"  value="<?php echo $tkRate ?>" id="tkrate">
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label class="col-lg-2 control-label">Total Reimb:</label>
                                  <div class="col-lg-5">
                                    <input type="text" class="form-control" disabled="disabled" value="<?php echo $reimbTot ?>" id="tkreimb">
                                  </div>
                                </div>
                            </form>
                                                      
                          <div class="widget-foot">
                        	<div class="clearfix"></div>
                          </div>
                        </div>
                        <!-- End widget-content -->
                    </div>
                    <!-- End my budget widget -->
                    
                    
                    <!-- Begin my budget widget -->
                    <div class="widget">
                        <!-- Begin widget-head -->
                        <div class="widget-head">
                          <div class="pull-left">Mileages</div>
                          
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
                                      <th>Date</th>
                                      <th>Purpose</th>
                                      <th>Start Odo</th>
                                      <th>End Odo</th>
                                      <th>Miles</th>
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
                                            <h4 class="modal-title">Mileage</h4>
                                          </div>
                                          <div class="modal-body">
                                            
                                            <!-- form -->
                                              <div class="padd">
                            
                                                <!-- Form starts.  -->
                                                 <form class="form-horizontal" role="form" action="<?php echo $mx009ml.'&mlid='.$milID ;?>" name="detmil_frm" id="detmil_frm" method="post" enctype="multipart/form-data">
                                                    <div class="form-group">
                                                      <label class="col-lg-3 control-label">Date:*</label>
                                                      <div class="col-lg-4">
                                                         <div id="datetimepicker1" class="input-append input-group dtpicker">
                                                            <input data-format="MM-dd-yyyy" type="text" id="mile_date" name="mile_date" class="form-control" value="<?php echo date("m-d-Y");?>">
                                                            <span class="input-group-addon add-on"><i data-time-icon="fa fa-times" data-date-icon="fa fa-calendar"></i></span>
                                                         </div>
                                                      </div>
                                                    </div>
                                                    <div class="form-group">
                                                      <label class="col-lg-3 control-label">Purpose*</label>
                                                      <div class="col-lg-5">
                                                        <input type="text" class="form-control" id="purpose" name="purpose" placeholder="" />
                                                      </div>
                                                    </div> 
                                                                                                      
                                                    <div class="form-group">
                                                      <label class="col-lg-3 control-label">Start Odometer*</label>
                                                      <div class="col-lg-5">
                                                        <input type="text" class="form-control" id="starto" name="starto" placeholder="0" onchange="isNumberOnly(this.id,this.value)">
                                                      </div>
                                                    </div>
                                                    <div class="form-group">
                                                      <label class="col-lg-3 control-label">End Odometer*</label>
                                                      <div class="col-lg-5">
                                                        <input type="text" class="form-control" id="endo" name="endo" placeholder="0" onchange="isNumberOnly(this.id,this.value)">
                                                      </div>
                                                    </div>
                                                                                                                                                            
                                                    <div class="form-group">
                                                      <label class="col-lg-3 control-label">Note</label>
                                                      <div class="col-lg-5">
                                                        <input type="text" class="form-control" id="note" name="note" placeholder="e.g. go to A and back" />
                                                      </div>
                                                    </div>    
													<input type="hidden" name="state" id="state" value="add" />
                                        			<input type="hidden" name="milid" id="milid" value="<?php echo $milID?>" />
                                                    <input type="hidden" name="mildetid" id="mildetid" value="" />
                                                  </form>
                                              </div>
                                            <!-- form -->
                                            
                                          </div>
                                          <div class="modal-footer">
                                            <button type="button" id="addSubDet" class="btn btn-primary"><span id="aValType"></span></button>
                                          </div>
                                    	</div>
                                    </div>
                                </div>
                                <!-- end add budget modal -->
                              
                          </div>
                          
                          <div class="widget-foot">
                            <button id="addnewmildet" class="btn btn-xs btn-success pull-left">Add mileage</button>
                        	<div class="clearfix"></div>
                          </div>
                          
                        </div>
                        <!-- End widget-content -->
                    </div>
                    <!-- End my budget widget -->
                    
                    
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