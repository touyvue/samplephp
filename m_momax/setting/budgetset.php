<?php
	include($p_guest); //get guest info
	include('m_momax/setting/con_log/log_budgetset.php'); 
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
                <div class="col-md-9">
                    <!-- Begin budget setup widget -->
                    <div class="widget">
                        <!-- Begin widget-head -->
                        <div class="widget-head">
                          <div class="pull-left"><?php echo $budgetSetting ?></div>
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
                                      <th>Order</th>
                                      <th>Budget</th>
                                      <th>Active</th>
                                      <th>Description</th>
                                      <th></th>
                                    </tr>
                                    <?php echo $budgetList; ?>   
                                  </table>
                              </div>
                          <div class="widget-foot"><?php echo $budgetListAdd ?></div>
                        </div>
                        <!-- End widget-content -->
                    </div>
                    <!-- End budget setup widget -->
                    
                    <!-- add budget setup modal -->
                    <div id="myModalAdd" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class='fa fa-close'></i></button>
                                <h4 class="modal-title">Budget</h4>
                              </div>
                              <div class="modal-body">
                                
                                <!-- form -->
                                  <div class="padd">
                
                                    <!-- Form starts.  -->
                                     <form class="form-horizontal" role="form">
                                        <div class="form-group">
                                          <label class="col-lg-3 control-label">Budget Name*</label>
                                          <div class="col-lg-7">
                                            <input type="text" class="form-control" id="budName" name="budName" />
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
                                        <input type="hidden" id="edtBudID" name="edtBudID" value="" />
	                                      </form>
                                  </div>
                                <!-- form -->
                                
                              </div>
                              <div class="modal-footer">
                                <button type="button" id="insertNewBudget" class="btn btn-primary"><span id="eBudType"></span></button>
                              </div>
                            </div>
                        </div>
                    </div>
                    <!-- end budget setup modal -->
                    
                    <?php if ($consAdmin=="yes" or $orgAdmin=="yes"){ ?>
                    <!-- Begin budget setup widget -->
                    <div class="widget">
                        <!-- Begin widget-head -->
                        <div class="widget-head">
                          <div class="pull-left">Set Fiscal Start Month</div>
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
                                      <td><label>Fiscal Start Month</label></td>
                                      <td> Chose Month: <select id="fiscalmon" name="fiscalmon">
                                        <option value="1" <?php if($fiscalMon==1){echo 'selected="selected"';}?>>January</option>
                                        <option value="2" <?php if($fiscalMon==2){echo 'selected="selected"';}?>>February</option>
                                        <option value="3" <?php if($fiscalMon==3){echo 'selected="selected"';}?>>March</option>
                                        <option value="4" <?php if($fiscalMon==4){echo 'selected="selected"';}?>>April</option>
                                        <option value="5" <?php if($fiscalMon==5){echo 'selected="selected"';}?>>May</option>
                                        <option value="6" <?php if($fiscalMon==6){echo 'selected="selected"';}?>>June</option>
                                        <option value="7" <?php if($fiscalMon==7){echo 'selected="selected"';}?>>July</option>
                                        <option value="8" <?php if($fiscalMon==8){echo 'selected="selected"';}?>>August</option>
                                        <option value="9" <?php if($fiscalMon==9){echo 'selected="selected"';}?>>September</option>
                                        <option value="10" <?php if($fiscalMon==10){echo 'selected="selected"';}?>>October</option>
                                        <option value="11" <?php if($fiscalMon==11){echo 'selected="selected"';}?>>November</option>
                                        <option value="12" <?php if($fiscalMon==12){echo 'selected="selected"';}?>>December</option>
                                    	</select>
                                    </td>
                                      <td><a href="#" onclick="uptFiscalMon(<?php echo $orgID.",".$memberID;?>)"><button type="button" class="btn btn-xs btn-primary">Update</button></a></td>
                                    </tr>
                                  </table>
                              </div>
                          <div class="widget-foot"></div>
                        </div>
                        <!-- End widget-content -->
                    </div>
                    <!-- End budget setup widget -->
                    <?php } ?>
                    
                    <!-- Begin budget chart display setting widget -->
                    <div class="widget">
                        <!-- Begin widget-head -->
                        <div class="widget-head">
                          <div class="pull-left">Set Budget to Display Charts</div>
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
                                  <div class="table-responsive">
                                  <table class="table table-striped table-bordered table-hover">
                                    <tr>
                                      <th>Budget</th>
                                      <th>Show Chart on Dashboard</th>
                                    </tr>
                                    <?php echo $accountBudList; ?>   
                                  </table>
                              </div>
                              </div>
                          <div class="widget-foot"></div>
                        </div>
                        <!-- End widget-content -->
                    </div>
                    <!-- End budget chart display setting widget -->
                                        
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