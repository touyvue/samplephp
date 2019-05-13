<?php	include($p_guest); //ge guest info ?>
<!-- intro Starts -->
<div class="container">
  <div class="intorp">
    <div class="row">
      <div class="col-md-7 col-sm-7">
        <div class="intropa">
          <h1>Money Management for Groups!</h1><br />
          <form class="form-horizontal" id="loginform" name="loginform" action="<?php echo $index_url?>?p=login&amp;pa=<?php echo $_GET['pa']?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
              <div class="col-lg-9">
                <input type="text" class="llogin-frm" id="inputEmail" name="inputEmail" placeholder="email">
              </div>
            </div>
            <div class="form-group">
              <div class="col-lg-9">
                <input type="password" class="llogin-frm" id="inputPassword" name="inputPassword" placeholder="password">
              </div>
            </div>
            <div class="col-lg-9">
                <button type="submit" class="btn btn-success btn-sm" id="btnSignin">Sign in</button>
                <button type="reset" class="btn btn-default btn-sm" id="btnReset">Reset</button><br />
            </div>
            <div class="col-lg-9">
            <?php  
                if ($chk_login == 3){
                    echo "Enter E-mail and Password.";
                }
                if ($chk_login == 2){
                    echo "E-mail and Password are incorrect.";
                }
                if ($chk_login == 1){
                    echo "Incorrect e-mail.  Contact administrator.";
                }
            ?>
            </div>
            <br />
          </form>
          <p>
			<script type="text/javascript">
				window.onload=my_main_login;
			</script>  
          </p><br />
                
        </div>
      </div>
      <div class="col-md-5 col-sm-5">
        <div class="intropa">
          <center><img src="img/techimg-sm.png">
          <p class="stext">Tracking expenses in any devices <br />(mobile, tablet & computer)</p>
          </center>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- intro Ends -->
<!-- Below slider starts -->
<div class="content">
<div class="slider-features">
      <div class="container">
      <div class="col-md-10>
        <div class="row">
                  <div class="col-md-4 col-sm-4">
                    <div class="onethree">
                      <div class="onethree-left">
                        <!-- Font awesome icon -->
                        <div class="img"><i class="fa fa-book"></i></div>
                      </div>
                      <div class="onethree-right">
                        <!-- Title and meta -->
                        <h4>Track Personal Expenses</h4>
                        <p class="meta">Create personal monthly budget by outlining incomes and expenses.  
                         Track daily expenses and any unknown expenses to make sure spending account stays positive 
                         and saving goals are met. 
                         </p>
                      </div>
                      <div class="clearfix"></div>
                    </div>
                  </div>
                  <div class="col-md-4 col-sm-4">
                    <div class="onethree">
                      <div class="onethree-left">
                        <div class="img"><i class="fa fa-group"></i></div>
                      </div>
                      <div class="onethree-right">
                        <h4>Track Group Expenses</h4>
                        <p class="meta">Create a spending plan and allow group members to track their own expenses. 
                        Keep everyone up-to-date on daily expenses so everyone stays within spending budget.</p>
                      </div>
                      <div class="clearfix"></div>   
                    </div>     
                  </div>
                  <div class="col-md-4 col-sm-4">
                    <div class="onethree">
                      <div class="onethree-left">
                        <div class="img"><i class="fa fa-briefcase"></i></div>
                      </div>
                      <div class="onethree-right">
                        <h4>Share Daily Expenses</h4>
                        <p class="meta"> Share itemized expenses real-time within group(s).  
                            Share daily expense reports as well as forecast expense reports for transparency and reconciliation.</p>
                      </div>
                      <div class="clearfix"></div>  
                    </div>      
                  </div>
        </div>
      </div>
      </div>
</div>
</div>
<!-- Below slider ends -->

<!-- CTA Starts -->
<div class="container">
  <div class="cta">
    <div class="row">
      <div class="col-md-5 col-sm-5">
        <div class="ctas">
          <!-- Title and Para -->
          <h4>Why Maxmoni?</h4>
          <p>We help individuals and groups to achieve financial goals by providing a simple way for them to plan and track their daily expenses.
             Our mobile web app enables an easier and smarter way to control spending and to save more.  It keeps daily expenses transparent for group(s) through real-time sharing.  
             </p>
        </div>
      </div>
      <div class="col-md-4 col-sm-4">
        <div class="ctas">
          <!-- List -->
          <h4>Who is Maxmoni for?</h4>
          <ul>
            <li>Family (parents and children)</li>
            <li>Student organizations</li>
            <li>Offices/Departments</li>
            <li>Special interest groups</li>
            <li>Churches</li>
            <li>Non-profit organizations</li>
          </ul>
        </div>
      </div>
      <div class="col-md-3 col-sm-3">
        <div class="ctas">
          <!-- Button -->
          <a href="mailto:info@maxmoni.com" class="btn btn-info" data-toggle="modal">Try it today!</a>
          <p>Get in touch with us for more information and a demo. <a href="mailto:info@maxmoni.com">We're here 24/7 for you</a>.</p>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- CTA Ends -->

<!-- Service style #2 starts -->
		<div class="container">
		  <div class="service-two">
			  <div class="row">
				<div class="col-md-12">
				  <h4 class="title">Features</h4>
				</div>
				 <div class="col-md-3 col-sm-3">
					<h5><i class="fa fa-dollar"></i>Plan Budget</h5>
					<p>Lay out a simple budget with money coming in and money going out. Link budget items for 
                    real-time tracking. </p>
				 </div>
				 <div class="col-md-3 col-sm-3">
					<h5><i class="fa fa-gift"></i>Track Spending</h5>
					<p>Track daily expenses real-time on your mobile device. Find smarter way to spend and to save more.</p>
			    </div>
				 <div class="col-md-3 col-sm-3">
					<h5><i class="fa fa-group"></i>Collabrate With Others</h5>
					<p>Collaborate to create a budget for a group. Group members can track their daily expenses in one place.</p>
				 </div>
				 <div class="col-md-3 col-sm-3">
					<h5><i class="fa fa-bullhorn"></i>Share Expense Reports</h5>
					<p>Itemized expenses are easily shared within a group. Keep up-to-date to make informed financial decisions.</p>
			    </div>
			  </div>
		   </div>
		   <hr />
		</div>
<!-- Service style #2 ends -->