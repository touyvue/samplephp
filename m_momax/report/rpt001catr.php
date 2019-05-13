<?php
	include($p_guest); //get guest info
	include('m_momax/report/con_log/log_rpt001catr.php'); 
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
              <h2 class="pull-left"><a href="index.php"><i class="fa fa-home"></i></a> <a href="<?php echo $mx004ct?>">Reports</a></h2>
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
                     
                    <!-- Begin specific budget details widget -->
                    <div class="widget">
                        <!-- Begin widget-head -->
                        <div class="widget-head">
                          <div class="pull-left"><span class="rpt-tit" id="active_rpt">Account Category Report: </span></div>
                          <div class="widget-icons pull-right">
                            <a href="#" class="wminimize"><i class="fa fa-chevron-up"></i></a> 
                            <a href="#" class="wclose"><i class="fa fa-times"></i></a>
                          </div>  
                          <div class="clearfix"></div>
                        </div>
                        <!-- End widget-head -->
                        <!-- Begin widget-content -->
                        <div class="widget-content referrer">
                        	<div class="padd">
                              <form class="form-horizontal" role="form" action="<?php echo $mx004ct?>" name="categroyrpt_frm" id="categroyrpt_frm" method="post" enctype="multipart/form-data">
                                    <div class="form-group">
                                          <label class="col-lg-3 control-label">Choose Category:</label>
                                          <div class="col-lg-5">
                                            <select class="form-control" id="categoryid" name="categoryid"  multiple="multiple">
                                              	<?php echo $categoryList; ?>
                                            </select>
                                          </div>
                                    </div>
                                    <div class="form-group">
                                          <label class="col-lg-3 control-label">Choose Account:</label>
                                          <div class="col-lg-5">
                                            <select class="form-control" id="accountid" name="accountid"  multiple="multiple">
                                              	<?php echo $accountList; ?>
                                            </select>
                                          </div>
                                    </div>
                                    <div class="form-group">
                                      <label class="col-lg-3 control-label">Start:*</label>
                                      <div class="col-lg-5">
                                         <div id="datetimepicker" class="input-append input-group dtpicker">
                                            <input data-format="MM-dd-yyyy" type="text" id="start_date" name="start_date" class="form-control" value="<?php echo $firstDay;?>">
                                            <span class="input-group-addon add-on"><i data-time-icon="fa fa-times" data-date-icon="fa fa-calendar"></i></span>
                                         </div>
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <label class="col-lg-3 control-label">End:*</label>
                                      <div class="col-lg-5">
                                         <div id="datetimepicker1" class="input-append input-group dtpicker">
                                            <input data-format="MM-dd-yyyy" type="text" id="end_date" name="end_date" class="form-control" value="<?php echo $lastDay;?>">
                                            <span class="input-group-addon add-on"><i data-time-icon="fa fa-times" data-date-icon="fa fa-calendar"></i></span>
                                         </div>
                                      </div>
                                    </div>
                                    
                                    <div class="form-group">
                                      <label class="col-lg-3 control-label"></label>
                                      <div class="col-lg-5">
                                        <button type="submit" id="addCatReports" class="btn btn-primary">Show Report</button>
                                      </div>
                                    </div>
                                    <input type="hidden" id="mid" value="<?php echo $memberID ?>" />
                                    <input type="hidden" id="subcategoryid" name="subcategoryid" />
                                    <input type="hidden" id="subaccountid" name="subaccountid" />
                                    <input type="hidden" id="subaccountct" name="subaccountct" value="<?php echo $accountCount?>" />
                                </form>
                          	</div>
                          	<div class="widget-foot"></div>
                        </div>
                        <!-- End widget-content -->
                    </div>
                    <!-- End specific budget details widget -->
                    
                    <?php if ($categoryIdsArrCt > 0){ ?>              
                    <!-- Begin specific budget details widget -->
                    <div class="widget">
                        <!-- Begin widget-head -->
                        <div class="widget-head">
                          <div class="pull-left"><span class="rpt-tit" id="active_rpt">Category Total: </span></div>
                          <div class="widget-icons pull-right">
                            <a href="#" class="wminimize"><i class="fa fa-chevron-up"></i></a> 
                            <a href="#" class="wclose"><i class="fa fa-times"></i></a>
                          </div>  
                          <div class="clearfix"></div>
                        </div>
                        <!-- End widget-head -->
                        <!-- Begin widget-content -->
                        <div class="widget-content referrer">
                           <div class="padd">
                              <div class="page-tables">
                              <div class="table-responsive">
                                  <table cellpadding="0" cellspacing="0" border="0" id="categorytbl" width="100%" class="table table-striped table-bordered">
                                     <thead>
                                        <tr>
                                            <th>Category</th>
                                            <th>Amount</th>
										</tr>
                                     </thead>
                                     <tbody>
                                        <?php echo $categoryAmtList; ?>
                                     </tbody>
                                     <tfoot>
                                     	<?php echo $categoryAmtListTot; ?>
                                     </tfoot>
                                  </table>
                              </div>
                              </div>
                          </div>
                          <div class="widget-foot"></div>
                        </div>
                        <!-- End widget-content -->
                    </div>
                    <!-- End specific budget details widget -->
                    <?php } ?>
                    <?php if ($accountIdsArrCt > 1){ ?>
                    <!-- Begin specific budget details widget -->
                    <div class="widget">
                        <!-- Begin widget-head -->
                        <div class="widget-head">
                          <div class="pull-left"><span class="rpt-tit" id="active_rpt">Account and Category Details:</span></div>
                          <div class="widget-icons pull-right">
                            <a href="#" class="wminimize"><i class="fa fa-chevron-up"></i></a> 
                            <a href="#" class="wclose"><i class="fa fa-times"></i></a>
                          </div>  
                          <div class="clearfix"></div>
                        </div>
                        <!-- End widget-head -->
                        <!-- Begin widget-content -->
                        <div class="widget-content referrer">
                        	<div class="padd">
                              <div class="page-tables">
                              <div class="table-responsive">
                                  <table cellpadding="0" cellspacing="0" border="0" id="categoryaccttbl" width="100%" class="table table-striped table-bordered">
                                     <thead>   
                                        <tr>
                                            <th class="ysort">Category</th>
                                            <th class="ysort">Account</th>
                                            <th class="ysort">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php echo $accountAmtList; ?>
                                    </tbody>
                                    <tfoot>
                                     	<?php echo $accountAmtListTot; ?>
                                    </tfoot>
                                  </table>
                              </div>
                              </div>
                            </div>
                            <div class="widget-foot"></div>
                        </div>
                        <!-- End widget-content -->
                    </div>
                    <!-- End specific budget details widget -->
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