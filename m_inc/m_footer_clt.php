<footer>
  <div class="container">
    <div class="row">
      <div class="col-md-12">

            <div class="row">

              <div class="col-md-4">
                <div class="widget">
                  <h5><?php echo $disName; ?></h5>
                  <hr />
                  <p>For support, please contact your <a href="mailto:<?php echo $disEmail?>">Administrator</a>. </p>
                  <hr />
                  Admin <i class="fa fa-envelope-o"></i> &nbsp; <a href="mailto:<?php echo $disEmail?>"><?php echo $disEmail?></a>
                  <hr />
                    <div class="social">
                      <a href="#"><i class="fa fa-facebook facebook"></i></a>
                      <a href="#"><i class="fa fa-twitter twitter"></i></a>
                      <a href="#"><i class="fa fa-linkedin linkedin"></i></a>
                      <a href="#"><i class="fa fa-google-plus google-plus"></i></a> 
                    </div>
                </div>
              </div>

              <div class="col-md-4">
                <div class="widget">
                  <h5><?php echo $disName; ?></h5>
                  <hr />
                  <ul>
                        <li><a href="mailto:<?php echo $disEmail?>">Contact</a></li>
                        <li><?php if($disWebsite != ""){echo '<a href="http://'.$disWebsite.'">'.$disName.'</a>';}else{echo $disName;} ?></li>
                      </ul>
                </div>
              </div>

              <div class="col-md-4">
                <div class="widget">
                  <h5><a href="<?php echo $mx00100 ?>">Home</a> </h5>
                  <hr />
                  <div class="two-col">
                    <div class="col-left">
                      <ul>
                      	<li><a href="<?php echo $mx00200 ?>">Budget</a></li>
                        <li><a href="<?php echo $mx00300 ?>">Account</a></li>
                        <li><a href="<?php echo $mx00600 ?>">Event</a></li>
                      </ul>
                    </div>
                    <div class="clearfix"></div>
                  </div>
                </div>
              </div>
              
            </div>

            <hr />
            <!-- Copyright info -->
            <p class="copy">Copyright &copy; 2016 | <strong>Powered by MAXMONI</strong> </p>
      </div>
    </div>
  <div class="clearfix"></div>
  </div>
</footer>