<?php
	include($p_guest); //get guest info
	include('m_momax/account/con_log/log_account.php'); 
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
              <h2 class="pull-left"><a href="index.php"><i class="fa fa-home"></i></a></h2>
              <h2 class="pull-left">&nbsp;<a href="<?php echo $mx003ac."&aid=".$accountID."&smid=".$_GET['smid']; ?>"><?php echo str_replace('\\','',$accountNameMemberID[0]);?></a></h2>
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
                          <div class="pull-left"><?php echo $myAccountOverview ?></div>
                          <div class="widget-icons pull-right">
                            <a href="<?php echo $mx003ac."&aid=".$accountID."&mo=".$preBudgetMonth."&smid=".$sharedMemberID;?>"><i class="fa fa-backward"></i></a>
							<?php if ($accountRightsLevel == 3){ ?>
                            <a href="#" onclick='addBudgetItem(<?php echo $accountID?>,1)'><button class="btn btn-xs btn-success">add</button></a>
                            <?php }else{ ?>
                            <a href="<?php echo $mx003ac."&aid=".$accountID."&mo=".$selectedMonth.$selectedYear."&smid=".$sharedMemberID;?>"><?php echo $actSelectedMonth; ?></a>
                            <?php } ?>
                            <a href="<?php echo $mx003ac."&aid=".$accountID."&mo=".$nextBudgetMonth."&smid=".$sharedMemberID;?>"><i class="fa fa-forward"></i></a>
                          </div>  
                          <div class="clearfix"></div>
                        </div>
                        <!-- End widget-head -->
                            <!-- Begin widget-content -->
                        <div class="widget-content referrer">
                          
                                <div class="table-responsive">
                                  <table class="table table-striped table-bordered table-hover">
                                    <tr>
                                      <th>Date</th>
                                      <th>Amount</th>
                                      <th>Description</th>
                                      <th>Actual Balance</th>
                                      <th>Forecast Balance</th>
                                      <th></th>
                                    </tr>
                                    <?php echo $transList ?>  
                                  </table>
                                
                                <!-- add budget modal -->
                                <div id="myModalAdd" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                      <div class="modal-content">
                                          <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class='fa fa-close'></i></button>
                                            <h4 class="modal-title"><span id="aTitle"></span></h4>
                                          </div>
                                          <div class="modal-body">
                                            
                                            <!-- form -->
                                              <div class="padd">
                                                <!-- Form starts.  -->
                                                 <form class="form-horizontal" role="form" id="addaccttrans" action="<?php echo $mx003ac."&aid=".$accountID."&mo=".$selectedMonth.$selectedYear."&smid=".$sharedMemberID;?>" method="post" enctype="multipart/form-data">
                                                    
                                                    <div class="form-group">
                                                      <label class="col-lg-3 control-label">Date:*</label>
                                                      <div class="col-lg-4">
                                                         <div id="datetimepicker1" class="input-append input-group dtpicker">
                                                            <input data-format="MM-dd-yyyy" type="text" id="trans_date" name="trans_date" class="form-control" value="<?php echo date("m-d-Y");?>">
                                                            <span class="input-group-addon add-on"><i data-time-icon="fa fa-times" data-date-icon="fa fa-calendar"></i></span>
                                                         </div>
                                                         <label class="checkbox-inline"><input type="checkbox" name="posted" id="posted" />&nbsp;&nbsp;Posted/Completed</label>
                                                      </div>
                                                    </div>
                                                    <div class="form-group">
                                                      <label class="col-lg-3 control-label">Description:</label>
                                                      <div class="col-lg-6">
                                                        <input type="tex" class="form-control" id="note" name="note"  />
                                                      </div>
                                                    </div>
                                                    <div class="form-group">
                                                      <label class="col-lg-3 control-label">Amount:*</label>
                                                      <div class="col-lg-3">
                                                        <input type="text" class="form-control" id="budgetAmount" name="budgetAmount" placeholder="$0.00" onchange="isNumber_amtina(this.id,this.value)">
                                                      </div>
                                                      <div class="col-lg-3">
                                                      	<div class='radio'>
                                                            <label><input type='radio' checked="checked" name='cd<?php echo $accountID ?>' id='d<?php echo $accountID ?>' value='1001'>Debit</label>&nbsp;&nbsp;
                                                            <label><input type='radio' name='cd<?php echo $accountID ?>' id='c<?php echo $accountID ?>' value='1000'>Credit</label>
                                                        </div>
                                                      </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                      <label class="col-lg-3 control-label">Category:</label>
                                                      <div class="col-lg-4">
                                                        <select class="form-control" id="budCategory" name="budCategory">
                                                          <?php echo $catItemsList; ?>
                                                        </select>
                                                      </div>
                                                      <?php if ($tagList != ""){ ?>
                                                      <div class="col-lg-1">
                                                      		<div class="tagPadding">
                                                      	    <button type="button" onclick="showtag()" id="cattag" class="btn btn-xs btn-default">Tag</button>
                                                      		</div>
                                                      </div>
                                                      <?php } ?>
                                                    </div>
                                                    
													<?php if ($budgetAnnList != ""){ ?>
                                                    <div class="form-group">
                                                      <label class="col-lg-3 control-label">Budget:</label>
                                                      <div class="col-lg-5">
                                                        <select id="budgerannset" name="budgerannset" class="form-control" >
                                                        	<option value="none">Choose Budget</option>
                                                            <?php echo $budgetAnnList ?>
                                                        </select>
                                                      </div>
                                                    </div>
                                                    <?php } ?>
                                                    
                                                    <div class="form-group">
                                                      <label class="col-lg-3 control-label">Receipt:</label>
                                                      <div class="col-lg-5">
                                                        <input type="file" class="form-control" name="mx_attach" id="mx_attach" accept="image/*" onChange="fileyype(this.id,this.value,['gif','GIF','jpg','JPG','png','PNG','jpeg','JPEG']);" />
                                                      	<span id="on_receipt_img" style="display: none;">
                                                        	<a href="#" id="attimg" class="prettyPhoto">Receipt Image</a>
                                                        </span>
                                                        <span id="on_canvas" style="display:none;">
                                                            <canvas id="myreceipt">Your browser does not support the HTML5 canvas tag.</canvas>
                                                        </span>
                                                      </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                      <label class="col-lg-3 control-label">Recurring:</label>
                                                      <div class="col-lg-7">
                                                        <div class="radio">
                                                          	<label><input type="radio" name="recurring" id="recurring1" value="yes" onclick="add_recurring('yes', 6)">YES</label>&nbsp;&nbsp;
                                                          	<label><input type="radio" name="recurring" id="recurring2" value="no" checked="yes" onclick="add_recurring('no', 6)">NO</label>
                                                        </div>
                                                        
                                                        <span id="on_recurring_insert" style="display: none;">
                                                             <div class="input-group">
                                                                <div class="input-group-btn">
                                                                    <button tabindex="-1" class="btn btn-default" id="chRecurringLabel" type="button">Monthly</button>
                                                                    <button tabindex="-1" data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button">
                                                                    <span class="caret"></span>
                                                                    </button>
                                                                    <ul role="menu" class="dropdown-menu">
                                                                        <li><a href="#" onclick="turnon_no(1,'Daily')"><input name="recurring_ty" id="recurring_ty1" disabled="true" type="radio" value="Daily" onchange="turnon_no(1,this.value)"> Daily</a></li>
                                                                        <li><a href="#" onclick="turnon_no(2,'Weekly')"><input name="recurring_ty" id="recurring_ty2" disabled="true" type="radio" value="Weekly" onchange="turnon_no(2,this.value)"> Weekly</a></li>
                                                                        <li><a href="#" onclick="turnon_no(3,'Bi-weekly')"><input name="recurring_ty" id="recurring_ty3" disabled="true" type="radio" value="Bi-weekly" onchange="turnon_no(3,this.value)"> Bi-weekly</a></li>
                                                                        <li><a href="#" onclick="turnon_no(4,'Monthly')"><input name="recurring_ty" id="recurring_ty4" disabled="true" type="radio" value="Monthly" onchange="turnon_no(4,this.value)"> Monthly</a></li>
                                                                        <li><a href="#" onclick="turnon_no(5,'Quarterly')"><input name="recurring_ty" id="recurring_ty5" disabled="true" type="radio" value="Quarterly" onchange="turnon_no(5,this.value)"> Quarterly</a></li>
                                                                        <li><a href="#" onclick="turnon_no(6,'Yearly')"><input name="recurring_ty" id="recurring_ty6" disabled="true" type="radio" value="Yearly" onchange="turnon_no(6,this.value)"> Yearly</a></li>
                                                                    </ul>
                                                                </div>
                                                                <input type="text" class="sm_textbox" size="1" name="recurring_no" id="recurring_no" disabled="true" value="Number recurring" onchange="isNumberNumRecurring(this.id,this.value,30)"><label id="noRecurringLabel">&nbsp;times</label>
                                                            </div>
                                                         </span>
                                                         <span id="upt_edirecurring" style="display: none;">
                                                             <div class="radio"><strong>Update:</strong> 
                                                              <label><input type="radio" name="edtrecurringacct" id="edtrecurringacct1" value="one" checked="yes" >This recurring only</label>&nbsp;&nbsp;
                                                              <label><input type="radio" name="edtrecurringacct" id="edtrecurringacct2" value="all" >All recurring</label>
                                                            </div>
                                                         </span>
                                                      </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                      <label class="col-lg-3 control-label">Linking:</label>
                                                      <div class="col-lg-7">
                                                        <div class="radio">
                                                          <label><input type="radio" name="addacct" id="addacct1" value="yes" onclick="insertbudget('yes',<?php echo $acctNumAllCt ?>,'<?php echo $acctNumAll ?>')">YES</label>&nbsp;&nbsp;
                                                          <label><input type="radio" name="addacct" id="addacct2" value="no" checked="yes" onclick="insertbudget('no',<?php echo $acctNumAllCt ?>,'<?php echo $acctNumAll ?>')">NO</label>
                                                        </div>
                                                        <span id="on_acct_insert" style="display: none;">
															<?php echo $acctItemsList; ?>
                                                        </span>
                                                        
                                                      </div>
                                                    </div>
 													
                                                    <input type="hidden" id="actState" name="actState" value="add" />                                                                                                                                                          
                                                    <input type="hidden" id="allActiveAcct" name="allActiveAcct" value="<?php echo $acctNumAll ?>" />
                                                    <input type="hidden" id="allActivdAcctCt" name="allActivdAcctCt" value="<?php echo $acctNumAllCt ?>" /> 
                                                    <input type="hidden" id="mid" name="mid" value="<?php echo $memberID ?>" />
                                                    <input type="hidden" id="aid" name="aid" value="<?php echo $accountID ?>" />
                                                    
                                                    <input type="hidden" id="orgTransID" name="orgTransID" value="" />
                                                    <input type="hidden" id="orgRecurringID" name="orgRecurringID" value="" />
                                                    <input type="hidden" id="orgGroupSetID" name="orgGroupSetID" value="" />
                                                    
                                                    <input type="hidden" id="acctTypeID" name="acctTypeID" value="" />
                                                    
                                                    <input type="hidden" id="mainGroupAmt" value="" />
                                                    <input type="hidden" id="mainGroupSetID" value="" />   
                                                    <input type="hidden" id="newTransIds" name="newTransIds" value="" />   
                                                    <input type="hidden" id="hidimg" name="hidimg" />  
                                                    
                                                    <input type="hidden" id="annAccountSet" name="annAccountSet" value="<?php echo $annBudgetSet?>" />                                                                                                                    
                                                    <input type="hidden" id="selecttagid" name="selecttagid" value="" />
                                                    <input type="hidden" id="offerbudlistid" name="offerbudlistid" value="<?php echo $budgetListOffer?>" />
                                                    <input type="hidden" id="offerbudName" name="offerbudName" value="<?php echo $budgetListOfferName?>" /> 
                                              		<input type="hidden" id="offerbudState" name="offerbudState" value="0" />
                                              </div>
                                            <!-- form -->
                                            
                                          </div>
                                          <div class="modal-footer">
                                            <button type="button" id="addTrans" class="btn btn-primary"><span id="aValType"></span></button>
                                          </div>
                                          </form>
                                    	</div>
                                    </div>
                                </div>
                                <!-- end add budget modal --> 
                                
                                <!-- delete budget modal -->
                                <div id="myModalDelete" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                      <div class="modal-content">
                                          <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class='fa fa-close'></i></button>
                                            <h4 class="modal-title"><span id="dTitle"></span></h4>
                                          </div>
                                          <div class="modal-body">
                                          	<!-- form -->
                                              <div class="padd">
                            
                                                <!-- Form starts.  -->
                                                 <form class="form-horizontal" role="form" id="delaccttrans" action="<?php echo $mx003ac."&aid=".$accountID."&mo=".$selectedMonth.$selectedYear."&smid=".$sharedMemberID;?>" method="post" enctype="multipart/form-data">
                                                    
                                                    <div class="form-group">
                                                      <label class="col-lg-2 control-label">Date:</label>
                                                      <div class="col-lg-4">
                                                         <div id="deldatetimepicker" class="input-append input-group dtpicker">
                                                            <input data-format="MM-dd-yyyy" type="text" id="delTrans_date" disabled="disabled" class="form-control" value="<?php echo date("m-d-Y");?>">
                                                         	<span class="input-group-addon add-on"><i data-time-icon="fa fa-times" data-date-icon="fa fa-calendar"></i></span>
                                                         </div>
                                                      </div>
                                                    </div>
                                                    <div class="form-group">
                                                      <label class="col-lg-2 control-label">Description:</label>
                                                      <div class="col-lg-5">
                                                        <input type="tex" class="form-control" id="delNote" name="delNote" disabled="disabled"  />
                                                      </div>
                                                    </div>
                                                    <div class="form-group">
                                                      <label class="col-lg-2 control-label">Amount:</label>
                                                      <div class="col-lg-5">
                                                        <input type="text" class="form-control" id="delBudgetAmount" name="delBudgetAmount" disabled="disabled" placeholder="$0.00">
                                                      </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                      <label class="col-lg-2 control-label">Category:</label>
                                                      <div class="col-lg-5">
                                                        <select class="form-control" id="delBudCategory" name="delBudCategory" disabled="disabled" >
                                                          <?php echo $catItemsList; ?>
                                                        </select>
                                                      </div>
                                                    </div>

                                                    <span id="del_recurring_insert" style="display: none;">
                                                    <div class="form-group">
                                                      <label class="col-lg-2 control-label">Recurring:</label>
                                                      <div class="col-lg-7">
                                                         <div class="input-group">
                                                            <div class="input-group-btn">
                                                                <button tabindex="-1" class="btn btn-default" id="delRecurringLabel" disabled="disabled" type="button">Monthly</button>
                                                                <button tabindex="-1" data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button">
                                                                <span class="caret"></span>
                                                                </button>
                                                                <ul role="menu" class="dropdown-menu">
                                                                    <li><input name="delRecurring_ty" id="delRecurring_ty1" disabled="true" type="radio" value="Daily" > Daily</a></li>
                                                                    <li><input name="delRecurring_ty" id="delRecurring_ty2" disabled="true" type="radio" value="Weekly" > Weekly</a></li>
                                                                    <li><input name="delRecurring_ty" id="delRecurring_ty3" disabled="true" type="radio" value="Bi-weekly" > Bi-weekly</a></li>
                                                                    <li><input name="delRecurring_ty" id="delRecurring_ty4" disabled="true" type="radio" value="Monthly" > Monthly</a></li>
                                                                    <li><input name="delRecurring_ty" id="delRecurring_ty5" disabled="true" type="radio" value="Quarterly" > Quarterly</a></li>
                                                                    <li><input name="delRecurring_ty" id="delRecurring_ty6" disabled="true" type="radio" value="Yearly" > Yearly</a></li>
                                                                </ul>
                                                            </div>
                                                            <input type="text" class="sm_textbox" size="1" name="delRecurring_no" id="delRecurring_no" disabled="true" >&nbsp;times
                                                        </div>
                                                        <div class="radio">
                                                          <label><input type="radio" name="delBudItem" id="delBudItem1" value="one" checked="yes" >This recurring only</label>&nbsp;&nbsp;
                                                          <label><input type="radio" name="delBudItem" id="delBudItem2" value="all" >All recurring</label>
                                                        </div>
                                                      </div>
                                                    </div>
                                                    </span>
                                                    
 													<input type="hidden" id="actStateDel" name="actStateDel" value="delete" />  
                                                    <input type="hidden" id="allActiveAcctDel" name="allActiveAcctDel" value="<?php echo $acctNumAll ?>" />
                                                    <input type="hidden" id="allActivdAcctCtDel" name="allActivdAcctCtDel" value="<?php echo $acctNumAllCt ?>" /> 
                                                    <input type="hidden" id="midDel" name="midDel" value="<?php echo $memberID ?>" />
                                                    <input type="hidden" id="actidDel" name="actidDel" value="<?php echo $accountID ?>" />
                                                    <input type="hidden" id="orgDelTransID" name="orgDelTransID" value="" />
                                                    
                                                    <input type="hidden" id="recurringDelYN" name="recurringDelYN" value="" />
                                                    <input type="hidden" id="recurringDelID" name="recurringDelID" value="" />
                                                    <input type="hidden" id="recurringDelNum" name="recurringDelNum" value="" />
                                                    
                                                    <input type="hidden" id="insertAcctDelYN" name="insertAcctDelYN" value="" />
                                                    <input type="hidden" id="delGroupSetID" name="delGroupSetID" value="" />
                                                    
                                                    <input type="hidden" id="actTypeidDel" name="actTypeidDel" value="" />                                                                                                                            
                                                  </form>
                                              </div>
                                            <!-- form -->
                                          </div>
                                          <div class="modal-footer">
                                            <button type="button" id="deleteIncome" class="btn btn-primary"><span id="dValType"></button>
                                          </div>
                                    	</div>
                                    </div>
                                </div>
                                <!-- end delete budget modal -->
                                
                                <!-- image modal -->
                                <div id="showCatTag" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                      <div class="modal-content">
                                          <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class='fa fa-close'></i></button>
                                            <h4 class="modal-title">Add Tag</h4>
                                          </div>
                                          <div class="modal-body">
                                          	<!-- form -->
                                              <div class="padd">
                                              <form class="form-horizontal" role="form">
                                              	<div class="form-group">
                                                  <label class="col-lg-3 control-label">Search Tag:</label>
                                                  <div class="col-lg-4">
                                                  	<select class="form-control" id="setgrpcategory" name="setgrpcategory">
                                                      <option value="nono">Choose Tag</option>
                                                      <?php echo $tagList; ?>
                                                    </select>
                                                  </div>
                                                </div>
                                                <div class="form-group">
                                                  <label class="col-lg-3 control-label"></label>
                                                  <div class="col-lg-4">
                                                  	<a href="<?php echo $mx005tt ?>">Addd New Tag</a>
                                                  </div>
                                                </div>
                                              </form>
                                              </div>
                                            <!-- form -->
                                          </div>
                                          <div class="modal-footer"><button type="button" id="choosetag" class="btn btn-primary">Choose</button></div>
                                    	</div>
                                    </div>
                                </div>
                                <!-- end image modal -->
                                
                                <!-- image modal -->
                                <div id="showImage" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                      <div class="modal-content">
                                          <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class='fa fa-close'></i></button>
                                            <h4 class="modal-title">Receipt</h4>
                                          </div>
                                          <div class="modal-body">
                                          	<!-- form -->
                                              <div class="padd">
                                              <form class="form-horizontal" role="form">
                                              	<div class="form-group">
                                                      <span id="imgTitle"></span>
                                                </div>
                                              </form>
                                              </div>
                                            <!-- form -->
                                          </div>
                                          <div class="modal-footer"></div>
                                    	</div>
                                    </div>
                                </div>
                                <!-- end image modal -->
                                
                          </div>
                          <div class="widget-foot">
                          	<ul class="pagination pagination-sm pull-right">
                              <li><a href="<?php echo $mx003ac."&aid=".$accountID."&mo=".$preBudgetMonth."&smid=".$sharedMemberID;?>"><i class="fa fa-backward"></i></a></li>
                              <li><a href="<?php echo $mx003ac."&aid=".$accountID."&mo=".$selectedMonth.$selectedYear."&smid=".$sharedMemberID;?>"><?php echo $actSelectedMonth; ?></a></li>
                              <li><a href="<?php echo $mx003ac."&aid=".$accountID."&mo=".$nextBudgetMonth."&smid=".$sharedMemberID;?>"><i class="fa fa-forward"></i></a></li>
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
<script>
var imageLoader = document.getElementById('mx_attach');
    imageLoader.addEventListener('change', handleImage, false);

var canvas = document.getElementById('myreceipt');
var ctx = canvas.getContext('2d');

function handleImage(e){
    var reader = new FileReader();
    reader.onload = function(event){
        var img = new Image();
        img.onload = function(){
			orgwidth = img.width;
			orgheight = img.height;
			newwidth = 900;
			newheight = Math.round((orgheight/orgwidth) * newwidth);
			if (orgwidth <= newwidth && orgheight <= newheight){
				newwidth = orgwidth;
				newheight = orgheight;
			} 
			canvas.width = newwidth;//img.width;
            canvas.height = newheight; //img.height;
			//ctx.clear();
            ctx.drawImage(img,0,0,newwidth,newheight);
        }
        img.src = event.target.result;
    }
    reader.readAsDataURL(e.target.files[0]);     
}
</script>