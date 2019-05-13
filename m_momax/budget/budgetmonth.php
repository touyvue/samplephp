<?php
	include($p_guest); //get guest info
	include('m_momax/budget/con_log/log_budgetmonth.php'); 
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
              <h2 class="pull-left"><a href="index.php"><i class="fa fa-home"></i></a> <a href="<?php echo $mx002 ?>">Budgets</a></h2>
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
                          <div class="pull-left"><?php echo $myBudgetOverview ?></div>
                          
                          <div class="widget-icons pull-right">
                            <a href="#" class="wminimize"><i class="fa fa-chevron-up"></i></a> 
                            <a href="#" class="wclose"><i class="fa fa-times"></i></a>
                          </div>  
                          <div class="clearfix"></div>
                        </div>
                        <!-- End widget-head -->
                            <!-- Begin widget-content -->
                        <div class="widget-content referrer">
                        
                        <ul id="myTab" class="nav nav-tabs">
                          <li><a href="#profile" data-toggle="tab">By Month</a></li>
                          <li class="active"><a href="#home" data-toggle="tab">By Quarter</a></li>
                        </ul>

                        <div id="myTabContent" class="tab-content">
                          <div class="tab-pane fade in active" id="home">
                              <div class="table-responsive">
                                  <table class="table table-striped table-bordered table-hover">
                                    <tr>
                                      <th nowrap="nowrap">Planned Budgets</th>
                                      <th><?php echo $q1?></th>
                                      <th><?php echo $q2?></th>
                                      <th><?php echo $q3?></th>
                                      <th><?php echo $q4?></th>
                                      <th class="pull-right">Total</th>
                                    </tr>
                                    <?php echo $qrtBudgetTotList ?>
                                    <?php echo $myQuarterAnnIncListTot ?>
                                    <tr>
                                      <th>Actual Expenses</th>
                                      <th></th>
                                      <th></th>
                                      <th></th>
                                      <th></th>
                                      <th></th>
                                    </tr>   
                                    <?php echo $myQuarterSavExpList ?>   
                                    <?php echo $myQuarterSavExpListTot ?>   
                                    <tr>
                                      <th colspan="6"></th>
                                    </tr>
                                    <?php echo $myQuarterAllTotNet ?>                                                      
                                  </table>
                              </div>
                          </div>
                          
                          <div class="tab-pane fade" id="profile">
                            <div class="table-responsive">
                                  <table class="table table-striped table-bordered table-hover" id="budgetrowtable">
                                    <tr>
                                      <th>Planned Budgets</th>
                                      <th><?php echo $monthName[0]?></th>
                                      <th><?php echo $monthName[1]?></th>
                                      <th><?php echo $monthName[2]?></th>
                                      <th><?php echo $monthName[3]?></th>
                                      <th><?php echo $monthName[4]?></th>
                                      <th><?php echo $monthName[5]?></th>
                                      <th><?php echo $monthName[6]?></th>
                                      <th><?php echo $monthName[7]?></th>
                                      <th><?php echo $monthName[8]?></th>
                                      <th><?php echo $monthName[9]?></th>
                                      <th><?php echo $monthName[10]?></th>
                                      <th><?php echo $monthName[11]?></th>
                                      <th class="pull-right">Total</th>
                                    </tr>
									<?php echo $annBudgetTotList?>
                                    <?php echo $monthlyAnnTotRow?>
                                    <tr><th colspan="14"></th></tr>
                                    <tr><th colspan="14">Actual Expenses</th></tr>
                                    <?php echo $expMonthlyTotList ?>
                                    <?php echo $monthlyExpTotRow ?>
                                    <tr><th colspan="14"></th></tr>
                                    <?php echo $monthlyNetTotRow ?>
                                  </table>
                                  <input type="hidden" id="budrowcount" name="budrowcount" value="4"  />
                                  <input type="hidden" id="exprowcount" name="exprowcount" value="9"  />
                                  <br />
									
                              </div>
                          </div>
                          
                        </div>
                          
                          <div class="widget-foot">
                          	<span class="pull-left">[ <a href="<?php echo $start_p.$myMonthlyFile; ?>">Monthly Data</a> ] [ <a href="<?php echo $start_p.$myQuarterlyFile; ?>">Quarterly Data</a> ]</span>
                            <ul class="pagination pagination-sm pull-right">
                              <li><a href="<?php echo $mx002mb.'&bid='.$budgetID.'&cy='.($currentYr-1)."&smid=".$sharedMemberID;?>"><i class="fa fa-backward"></i></a></li>
                              <li><a href="<?php echo $mx002mb.'&bid='.$budgetID.'&cy='.$currentYr."&smid=".$sharedMemberID;?>"><?php echo $currentYr; ?></a></li>
                              <li><a href="<?php echo $mx002mb.'&bid='.$budgetID.'&cy='.($currentYr+1)."&smid=".$sharedMemberID;?>"><i class="fa fa-forward"></i></a></li>
                            </ul>
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