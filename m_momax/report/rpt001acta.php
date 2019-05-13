<?php
	include($p_guest); //get guest info
	include('m_momax/report/con_log/log_rpt001acta.php'); 
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
              <h2 class="pull-left"><a href="index.php"><i class="fa fa-home"></i></a> <a href="#">Reports</a></h2>
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
                    
                    <!-- Begin my budget widget -->
                    <div class="widget">
                        <!-- Begin widget-head -->
                        <div class="widget-head">
                          <div class="pull-left"><?php echo $myGenReports ?></div>
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
                                <form class="form-horizontal" role="form" action="<?php echo $mx004aa.'#addProject';?>" name="budget_rpt_frm" id="budget_rpt_frm" method="post" enctype="multipart/form-data">
                                    <div class="form-group">
                                      <label class="col-lg-3 control-label">Choose Account:</label>
                                      <div class="col-lg-5">
                                        <label class='checkbox-inline'>
                                        <?php echo $budgetList; ?>
                                        </label>
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <label class="col-lg-3 control-label">Group:</label>
                                      <div class="col-lg-5">
                                        <label class='checkbox-inline'><input type="checkbox" id="gcategory" name="gcategory" value="yes"> Group By Category</label>
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <label class="col-lg-3 control-label">Start:*</label>
                                      <div class="col-lg-5">
                                         <div id="datetimepicker1" class="input-append input-group dtpicker">
                                            <input data-format="MM-dd-yyyy" type="text" id="start_date" name="start_date" class="form-control" value="<?php echo $firstDay;?>">
                                            <span class="input-group-addon add-on"><i data-time-icon="fa fa-times" data-date-icon="fa fa-calendar"></i></span>
                                         </div>
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <label class="col-lg-3 control-label">End:*</label>
                                      <div class="col-lg-5">
                                         <div id="datetimepicker" class="input-append input-group dtpicker">
                                            <input data-format="MM-dd-yyyy" type="text" id="end_date" name="end_date" class="form-control" value="<?php echo $lastDay;?>">
                                            <span class="input-group-addon add-on"><i data-time-icon="fa fa-times" data-date-icon="fa fa-calendar"></i></span>
                                         </div>
                                      </div>
                                    </div>
                                    
                                    <div class="form-group">
                                      <label class="col-lg-3 control-label">Report Name:</label>
                                      <div class="col-lg-5">
                                        <input type="text" class="form-control" id="rname" name="rname" value="">
                                      </div>
                                    </div>
                                    
                                    <div class="form-group">
                                      <label class="col-lg-3 control-label"></label>
                                      <div class="col-lg-5">
                                        <button type="submit" id="addProject" class="btn btn-primary">Show Report</button>
                                      </div>
                                    </div>

                                    <input type="hidden" id="budState" value="add" />
                                    <input type="hidden" id="allActiveAcct" value="<?php echo $acctNumAll ?>" />
                                    <input type="hidden" id="allActivdAcctCt" value="<?php echo $acctNumAllCt ?>" /> 
                                    <input type="hidden" id="mid" value="<?php echo $memberID ?>" />
                                    <input type="hidden" id="projectid" value="<?php echo $projectID ?>" />
                                </form>

                          <div class="widget-foot"></div>
                        </div>
                        <!-- End widget-content -->
                    </div>
                    <!-- End my budget widget -->
                    
                    <?php if ($showReport == "yes"){ ?>
                    <!-- Begin my budget widget -->
                    <div class="widget">
                        <!-- Begin widget-head -->
                        <div class="widget-head">
                          <div class="pull-left"><span class="rpt-tit" id="active_rpt">Report Name: </span><?php echo $reportName; ?></div>
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
                                    <tr><td colspan="5">&nbsp;&nbsp;Reporting Date: <?php echo $orgDateStart; ?> to <?php echo $orgDateEnd; ?></td></tr>
                                        <tr>
                                            <th>Category</th>
                                            <th>Description</th>
                                            <th>Account Name</th>
                                            <th>Date</th>
                                            <th>Amount</th>
                                        </tr>
                                        <?php echo $incomeAmtList; ?>
                                  </table>
                              </div>
                          <div class="widget-foot"><a href="<?php echo $start_p.$myFile; ?>">Download File</a></div>
                        </div>
                        <!-- End widget-content -->
                    </div>
                    <!-- End my budget widget -->
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