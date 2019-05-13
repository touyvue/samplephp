<?php
	include($p_guest); //get guest info
	include('m_momax/nonfin/con_log/log_index.php'); 
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
              <h2 class="pull-left"><a href="index.php"><i class="fa fa-home"></i></a> <a href="<?php echo $mx005 ?>">General Tracking</a></h2>
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
                    <?php if ($memberID == "100100100"){ ?>
                    <!-- Begin member widget -->
                    <div class="widget">
                        <!-- Begin widget-head -->
                        <div class="widget-head">
                          <div>
                          <center>
                          <a href="#" onclick="onoff_trackingF(1)"  class="btn btn-default">General Tracking</a>
                          <a href="#" onclick="onoff_trackingF(2)"  class="btn btn-default">Group Tracking</a>
                          </center>
                          </div>
                            
                          <div class="clearfix"></div>
                        </div>
                        <!-- End widget-head -->
                    </div>
                	<!-- End church member widget -->
                    <?php } ?>
                    <!-- Begin general tracking widget -->
                    <span id="link_generaltrk" style="display: ;">
                    <div class="widget">
                        <!-- Begin widget-head -->
                        <div class="widget-head">
                          <div class="pull-left">General Tracking</div>
                          <div class="widget-icons pull-right">
                            <a href="#" class="wminimize"><i class="fa fa-chevron-up"></i></a> 
                            <a href="#" class="wclose"><i class="fa fa-times"></i></a>
                          </div>  
                          <div class="clearfix"></div>
                        </div>
                        <!-- End widget-head -->
                            <!-- Begin widget-content -->
                        <div class="widget-content referrer">
                              <?php //if ($memberID == "100100146" or $memberID == "100100147" or $memberID == "100100148"){ ?>
                              <!-- Form starts.  -->
                              <div class="padd">
                                     <div class="form-horizontal"  >
                                        <div class="form-group">
                                          <label class="col-lg-3 control-label">Choose Group:</label>
                                          <div class="col-lg-6">
                                            <select class="form-control" id="trkmemgrplist" name="trkmemgrplist">
                                              <option value="nogroup">General</option>
                                              <?php echo $grpAdminList; ?>
                                            </select>
                                          </div>
                                        </div>
                                        
                                        <div class="form-group">
                                          <label class="col-lg-3 control-label">Name:</label>
                                          <div class="col-lg-6">
                                          <input type="text" id="selectmemberlist" name="selectmemberlist" placeholder="name" class="form-control" />
                                          </div>
                                        </div>
                                        
                                        <div class="form-group">
                                          <label class="col-lg-3 control-label">Date:</label>
                                          <div class="col-lg-3">
                                             <div id="datetimepicker1" class="input-append input-group dtpicker">
                                                <input data-format="MM-dd-yyyy" type="text" id="onemem_date" name="onemem_date" class="form-control" value="<?php echo date("m-d-Y");?>">
                                                <span class="input-group-addon add-on"><i data-time-icon="fa fa-times" data-date-icon="fa fa-calendar"></i></span>
                                             </div>
                                          </div>
                                        </div>
                                        
                                        <div class="form-group">
                                          <label class="col-lg-3 control-label">Category:</label>
                                          <div class="col-lg-6">
                                            <select class="form-control" id="trkcategorylist" name="trkcategorylist">
                                              <?php echo $trkcatItemsList; ?>
                                            </select>
                                          </div>
                                        </div>
                                        
                                        <div class="form-group" id="CatBoxesGroup">
                                            <?php echo $firstTrkCatVal ?>
                                        </div>
                                        
                                        <div id="SubCatBoxesGroup">
                                            <?php echo $subfirstTrkCatVal ?>
                                        </div>
                                        
                                        <div class="form-group">
                                          <label class="col-lg-3 control-label">Note:</label>
                                          <div class="col-lg-6">
                                            <input type="text" class="form-control" id="mem_note" name="mem_note" />
                                          </div>
                                        </div> 
                                        <div class="form-group">
                                          <label class="col-lg-3 control-label"></label>
                                          <div class="col-lg-6">
                                            <button id="saveindtracking" class="btn btn-xs btn-primary">Save</button>
                                          </div>
                                        </div> 
                                       
                                        <input type="hidden" name="mid" id="mid" value="<?php echo $memberID ?>" />
                                        <input type="hidden" name="selectmember" id="selectmember"  />
                                        <input type="hidden" name="selectmemberid" id="selectmemberid"  />
                                        <input type="hidden" name="current_cat" id="current_cat" value="<?php echo $firstTrkCatId?>"  />
                                        <input type="hidden" name="current_cat_type" id="current_cat_type" value="<?php echo $firstTrkCatTyp?>"  />
                                        <input type="hidden" name="current_subcat" id="current_subcat" value="<?php echo $firstSubcat?>" />
                                     	<input type="hidden" name="subpurposect" id="subpurposect" value="<?php echo $subCatCt?>"  />
                                      	<input type="hidden" name="subpurposenum" id="subpurposenum" value="<?php echo $subCatNum?>" />
                                      </div>
                                      
                                </div>
                                <!-- form -->
                              <?php if ($memberID == "100100100"){//}else{ ?>
                              <div class="table-responsive">
                                  <table class="table table-striped table-bordered table-hover">
                                    <tr>
                                      <th>Purpose</th>
                                      <th>Value</th>
                                      <th>Note</th>
                                      <th></th>
                                    </tr>
                                    <?php echo $genList ?>
                                  </table>
                              </div>
                              <?php } ?>
                          <div class="widget-foot">
                          	<?php if ($memberID == "100100100"){//if ($memberID != "100100146" and $memberID != "100100147" and $memberID != "100100148"){ ?>
                            <button id="addgentrk" class="btn btn-xs btn-success">Add tracking</button>
                            <?php } ?>
                          </div>
                        </div>
                        <!-- End widget-content -->
                    </div>
                	<!-- End general tracking widget -->
                
                	<!-- Begin general tracking editable widget -->
                    <?php if ($generalTrkDetList != ""){?>
                    <div class="widget">
                        <!-- Begin widget-head -->
                        <div class="widget-head">
                          <div class="pull-left">General Tracking Details</div>
                          <div class="widget-icons pull-right">
                            <a href="#" class="wminimize"><i class="fa fa-chevron-up"></i></a> 
                            <a href="#" class="wclose"><i class="fa fa-times"></i></a>
                          </div>  
                          <div class="clearfix"></div>
                        </div>
                        <!-- End widget-head -->
                        <!-- Begin widget-content -->
                        <div class="widget-content referrer">
                          	<div class="page-tables">
                            <div class="table-responsive">
                                  <table cellpadding="0" cellspacing="0" border="0" id="data_generaltrk12" width="100%" class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                      	<th>Name</th>
                                        <th>Category</th>
                                      	<th>Updated Value</th>
                                        <th>Date</th>
                                        <th>Note</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php echo $generalTrkDetList; ?>
                                    </tbody>
                                  </table>
                            </div>
                            </div>
                          <div class="widget-foot"></div>
                        </div>
                        <!-- End widget-content -->
                    </div>
                    <?php } ?>
                    </span>
                	<!-- End general tracking editable widget -->
                
                	<!-- add general tracking modal -->
                    <div id="myModalTrack" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class='fa fa-close'></i></button>
                                <h4 class="modal-title">General Tracking</h4>
                              </div>
                              <div class="modal-body">
                                
                                <!-- form -->
                                  <div class="padd">
                
                                    <!-- Form starts.  -->
                                     <form class="form-horizontal" role="form" action="<?php echo $mx009;?>" name="addgentrk_frm" id="addgentrk_frm" method="post" enctype="multipart/form-data">
                                        <div class="form-group">
                                          <label class="col-lg-2 control-label">Purpose*</label>
                                          <div class="col-lg-5">
                                            <input type="text" class="form-control" id="trkname" name="trkname" rows="1" placeholder="name" />
                                          </div>
                                        </div> 
                                                                                          
                                        <div class="form-group">
                                          <label class="col-lg-2 control-label">Note</label>
                                          <div class="col-lg-5">
                                            <input type="text" class="form-control" id="trknote" name="trknote" rows="1" placeholder="" />
                                          </div>
                                        </div>    
                                        <input type="hidden" name="trkstate" id="trkstate" value="add" />
                                        <input type="hidden" name="trkid" id="trkid" value="" />
                                        <input type="hidden" name="gentrackty" id="gentrackty" value="gen" />
                                      </form>
                                  </div>
                                <!-- form -->
                                
                              </div>
                              <div class="modal-footer">
                                <button type="button" id="addTrkGen" class="btn btn-primary"><span id="trackGen"></span></button>
                              </div>
                            </div>
                        </div>
                    </div>
                    <!-- end add general tracking modal -->
                    
                    <span id="link_grouptrk" style="display:none ;">
                    <?php if ($trackGroupIdArrCt > 0 or $inGrpMemberCount > 0){ ?>
                    <!-- Begin Group Tracking widget -->
                    <div class="widget">
                        <!-- Begin widget-head -->
                        <div class="widget-head">
                          <div class="pull-left">Group Tracking</div>
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
                                  <?php if ($trackGroupIdArrCt > 0){ ?>
                                   <table class="table table-striped table-bordered table-hover">
                                    <tr>
                                      <th>Purpose</th>
                                      <th>Attend</th>
                                      <th>Amount</th>
                                      <th>Date</th>
                                      <th></th>
                                    </tr>
                                    <?php echo $memList ?>
                                   </table><hr />
                                  <?php } ?>
                                  <?php if ($inGrpMemberCount > 0){?>
                                   <table class="table table-striped table-bordered table-hover">
                                    <?php echo $memListBelong ?>
                                   </table>
                                  <?php } ?>
                              </div>
                          <div class="widget-foot">
                            <?php if ($trackGroupIdArrCt > 0){ ?><button id="addchurchtrk" class="btn btn-xs btn-success">Add tracking</button><?php } ?>
                          </div>
                        </div>
                        <!-- End widget-content -->
                    </div>
                    </span>
                	<!-- End Group Tracking widget -->
                    
                	<!-- add church member modal -->
                    <div id="myChurchTrack" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class='fa fa-close'></i></button>
                                <h4 class="modal-title">Member Tracking</h4>
                              </div>
                              <div class="modal-body">
                                
                                <!-- form -->
                                  <div class="padd">
                
                                    <!-- Form starts.  -->
                                     <form class="form-horizontal" role="form" action="<?php echo $mx009;?>" name="addchurch_frm" id="addchurch_frm" method="post" enctype="multipart/form-data">
                                        <div class="form-group">
                                          <label class="col-lg-2 control-label">Date:*</label>
                                          <div class="col-lg-5">
                                             <div id="datetimepicker" class="input-append input-group dtpicker">
                                                <input data-format="MM-dd-yyyy" type="text" id="mem_date" name="mem_date" class="form-control" value="<?php echo date("m-d-Y");?>">
                                                <span class="input-group-addon add-on"><i data-time-icon="fa fa-times" data-date-icon="fa fa-calendar"></i></span>
                                             </div>
                                          </div>
                                        </div>
                                        <div class="form-group">
                                          <label class="col-lg-2 control-label">Purpose:*</label>
                                          <div class="col-lg-5">
                                            <input type="text" class="form-control" id="mempurpose" name="mempurpose" rows="1" placeholder="" />
                                          </div>
                                        </div> 
                                        <div class="form-group">
                                          <label class="col-lg-2 control-label">Note:</label>
                                          <div class="col-lg-5">
                                            <input type="text" class="form-control" id="memnote" name="memnote" rows="1" placeholder="" />
                                          </div>
                                        </div>
                                        <div class="form-group">
                                          <label class="col-lg-2 control-label">Group:</label>
                                          <div class="col-lg-5">
                                            <select class="form-control" id="trkmemberGrp" name="trkmemberGrp">
                                              <?php echo $grpAdminList; ?>
                                            </select>
                                          </div>
                                        </div>
                                        <div class="form-group">
                                          <label class="col-lg-2 control-label">Category:</label>
                                          <div class="col-lg-5">
                                            <select class="form-control" id="trkmemberCategory" name="trkmemberCategory">
                                              <?php echo $catItemsList; ?>
                                            </select>
                                          </div>
                                        </div>
                                          
                                        <div class="form-group">
                                          <label class="col-lg-2 control-label">Tracking:</label>
                                          <div class="col-lg-5">
                                            <select class="form-control" id="trkmemberAccount" name="trkmemberAccount">
                                              <option value="none">Choose an Account</option>
											  <?php echo $accountList; ?>
                                            </select>
                                          </div>
                                        </div>  
                                        <input type="hidden" name="trkmemstate" id="trkmemstate" value="add" />
                                        <input type="hidden" name="trkmemid" id="trkmemid" value="" />
                                        <input type="hidden" name="trkmemty" id="trkmemty" value="mem" />
                                      </form>
                                  </div>
                                <!-- form -->
                                
                              </div>
                              <div class="modal-footer">
                                <button type="button" id="addchrMem" class="btn btn-primary"><span id="addmembut"></span></button>
                              </div>
                            </div>
                        </div>
                    </div>
                    <!-- end add church member modal -->
                    <?php } ?>
                    
                    <!-- Begin mileage widget -->
                    <span id="link_membertrk" style="display:none;">
                    <div class="widget">
                        <!-- Begin widget-head -->
                        <div class="widget-head">
                          <div class="pull-left"><?php echo $myMileage ?></div>
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
                                      <th>Purpose</th>
                                      <th>Miles</th>
                                      <th>Reimb</th>
                                      <th></th>
                                    </tr>
                                    <?php echo $mileageList ?>
                                  </table>
                              </div>
                          <div class="widget-foot">
                          	<button id="addmileage" class="btn btn-xs btn-success">Add tracking</button>
                          </div>
                        </div>
                        <!-- End widget-content -->
                    </div>
                    </span>
                    <!-- End mileage widget -->
                    
                    <!-- add mileage modal -->
                    <div id="myModalAdd" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class='fa fa-close'></i></button>
                                <h4 class="modal-title">Mileage Tracking</h4>
                              </div>
                              <div class="modal-body">
                                
                                <!-- form -->
                                  <div class="padd">
                
                                    <!-- Form starts.  -->
                                     <form class="form-horizontal" role="form" action="<?php echo $mx009;?>" name="addtracking_frm" id="addtracking_frm" method="post" enctype="multipart/form-data">
                                        <div class="form-group">
                                          <label class="col-lg-2 control-label">Business*</label>
                                          <div class="col-lg-5">
                                            <input type="text" class="form-control" id="name" name="name" rows="1" placeholder="name" />
                                          	<span id="trackdelall">delete all</span>
                                          </div>
                                        </div> 
                                                                                          
                                        <div class="form-group">
                                          <label class="col-lg-2 control-label">Rate*</label>
                                          <div class="col-lg-5">
                                            <input type="text" class="form-control" id="rate" name="rate" placeholder="$0.00($/mile)" onchange="isNumber_chk(this.id,this.value)">
                                          </div>
                                        </div>
                                                                                                                                                
                                        <div class="form-group">
                                          <label class="col-lg-2 control-label">Note</label>
                                          <div class="col-lg-5">
                                            <input type="text" class="form-control" id="note" name="note" rows="1" placeholder="" />
                                          </div>
                                        </div>    
                                        <input type="hidden" name="state" id="state" value="add" />
                                        <input type="hidden" name="milid" id="milid" value="" />
                                        <input type="hidden" name="miltrackty" id="miltrackty" value="mile" />
                                      </form>
                                  </div>
                                <!-- form -->
                                
                              </div>
                              <div class="modal-footer">
                                <button type="button" id="addTrackDet" class="btn btn-primary"><span id="trackType"></span></button>
                              </div>
                            </div>
                        </div>
                    </div>
                    <!-- end add mileage modal -->
                    
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