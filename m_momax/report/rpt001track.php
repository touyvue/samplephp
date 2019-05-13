<?php
	include($p_guest); //get guest info
	include('m_momax/report/con_log/log_rpt001track.php'); 
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
              <h2 class="pull-left"><a href="index.php"><i class="fa fa-home"></i></a> <a href="<?php echo $mx004tk?>">Report</a></h2>
              <h2 class="pull-left">&nbsp;</h2>
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
                    
                    <?php //if ($showReport == "yes"){ ?>
                    <!-- Begin my budget widget -->
                    <div class="widget">
                        <!-- Begin widget-head -->
                        <div class="widget-head">
                          <div class="pull-left"><span class="rpt-tit" id="active_rpt"><?php if($detNum=="1000"){echo $speGroupName." ".$currentDatesCategory;}else{ if($detNum=="2000"){echo $memberSpcName." ".$currentDatesCategory;}else{echo "Tracking Report";}}?> </span></div>
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
                            <table class="table table-striped table-bordered"><tr><td>
                        	 <form class="form-horizontal" role="form" >
                                <div class="form-group">
                                      <label class="col-lg-3 control-label">Choose Category:</label>
                                      <div class="col-lg-6">
                                        <select class="form-control" id="trkcategorylist" name="trkcategorylist">
                                          <option value="">All Categories</option>
                                          <?php echo $trkcatItemsList; ?>
                                        </select>
                                      </div>
                                </div>
                                <div class="form-group">
                                      <label class="col-lg-3 control-label">Choose Group:</label>
                                      <div class="col-lg-6">
                                        <select class="form-control" id="trkgrouplist" name="trkgrouplist">
                                          <option value="">All Groups</option>
                                          <?php echo $grpAdminList; ?>
                                        </select>
                                      </div>
                                </div>
                                <div class="form-group">
                                  <label class="col-lg-3 control-label">Start:*</label>
                                  <div class="col-lg-4">
                                     <div id="datetimepicker1" class="input-append input-group dtpicker">
                                        <input data-format="MM-dd-yyyy" type="text" id="start_date" name="start_date" class="form-control" value="<?php echo $defaultStart;?>">
                                        <span class="input-group-addon add-on"><i data-time-icon="fa fa-times" data-date-icon="fa fa-calendar"></i></span>
                                     </div>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label class="col-lg-3 control-label">End:*</label>
                                  <div class="col-lg-4">
                                     <div id="datetimepicker" class="input-append input-group dtpicker">
                                        <input data-format="MM-dd-yyyy" type="text" id="end_date" name="end_date" class="form-control" value="<?php echo $defaultEnd;?>">
                                        <span class="input-group-addon add-on"><i data-time-icon="fa fa-times" data-date-icon="fa fa-calendar"></i></span>
                                     </div>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label class="col-lg-3 control-label"></label>
                                  <div class="col-lg-7">
                                    <button id="filterReport" class="btn btn-xs btn-success">Filter By Date</button>
                                  </div>
                                </div>
                                <input type="hidden" id="currentp" name="currentp" value="<?php echo $filterLink?>" />
                                <input type="hidden" id="reportp" name="reportp" value="<?php echo $detNum?>"  />
                                <input type="hidden" id="selectid" name="selectid" value="<?php echo $selectMemberID?>" />
                             </form>
                            </td></tr></table>

                             <hr />
                             <table class="table table-striped table-bordered"><tr><td>
                             	<?php if ($viewGroupID == ""){echo "<label>".$firstTrkCatName." Category By All Group</label><br>Date: ".$defaultStart."  &ndash; ".$defaultEnd;}else{echo "<label>".$currTrkcategoryName." Category By Group</label><br>Date: ".$defaultStart."  &ndash; ".$defaultEnd; }?>
                             </td></tr></table><hr />
                             
                             <div class="page-tables">
                             <div class="table-responsive">
                                  <table cellpadding="0" cellspacing="0" border="0" id="data_rpt001track" width="100%" class="table table-striped table-bordered">
                                       <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Category</th>
											<th>Value</th>
											<th>Date</th>
											<th>Note</th>
                                        </tr>
                                       </thead>
                                       <tbody>
                                        <?php echo $memberSpcTrack; ?>
                                       </tbody>
                                  </table>
                             </div>    
                             </div>
                             
                          <div class="widget-foot"><a href="<?php echo $start_p.$myFile; ?>">Download File</a></div>
                        </div>
                        <!-- End widget-content -->
                    </div>
                    <!-- End my budget widget -->
                    <?php //} ?>
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