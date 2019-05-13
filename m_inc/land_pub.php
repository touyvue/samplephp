<?php	//include($p_guest); //ge guest info ?>
<!-- intro Starts -->
<div class="container">
  <div class="intorp">
    <div class="row">
      <div class="col-md-6 col-sm-6">
        <div class="intropa">
          <h2>A simple way to manage money!</h2>
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
                <h1><span class="label label-warning">Start today! <a href="mailto:info@maxmoni.com"><i class="fa fa-arrow-circle-o-right"></i></a></span></h1>
        </div>
      </div>
      <div class="col-md-6 col-sm-6">
        <div class="intropa">
          <video width="300" height="259" autoplay>
              <source src="m_demo/Maxmoni.mp4" type="video/mp4">
              <img src="img/techimg-sm.png">
            </video>
          <p class="stext">Use Maxmoni App in any devices (mobile, tablet & computer)</p>
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
                        <h4>Personal Budgets</h4>
                        <p class="meta">Create budget plans and link expenses to a spending (checking) account.  
                         Track daily spendings and share them with family members. 
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
                        <h4>Group Budgets</h4>
                        <p class="meta">Create budget plans and allow  members to track expenses
                             in one place. Keep everyone up-to-date on budgets and spending for transparency.</p>
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
                        <h4>Real-time Sharing</h4>
                        <p class="meta"> Share budgets and expense reports in real-time.  
                            Review budget progress and download daily receipts for reconciliation.</p>
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
             Our mobile web app enables an easier and smarter way to control spending and to save more.  It keeps daily spending transparent for small groups and teams through real-time sharing.  
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
					<p>Lay out a simple budget with money coming in and money going out. Link budget items to 
                    an account for tracking. </p>
				 </div>
				 <div class="col-md-3 col-sm-3">
					<h5><i class="fa fa-gift"></i>Track Spending</h5>
					<p>Track daily spendings real-time on your mobile device. Find smarter way to spend and to save more.</p>
			    </div>
				 <div class="col-md-3 col-sm-3">
					<h5><i class="fa fa-group"></i>Collabrate With Others</h5>
					<p>Collaborate to create a budget. Group members can track all daily expenses in one place.</p>
				 </div>
				 <div class="col-md-3 col-sm-3">
					<h5><i class="fa fa-bullhorn"></i>Share Information</h5>
					<p>Financial information is easily shared within a group. Keep up-to-date and make informed financial decisions.</p>
			    </div>
			  </div>
		   </div>
		   <hr />
		</div>
<!-- Service style #2 ends -->