<!DOCTYPE html>
<html lang="en-US" style="font-size: 62.5%">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>National Cooperative Data Base</title>
<link rel="shortcut icon" href="images/favicon.png">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;700&display=swap" rel="stylesheet">

<link href="<?=$this->request->webroot?>frontend/css2/bootstrap.min.css" rel="stylesheet" />
<link href="<?=$this->request->webroot?>frontend/css2/style2.css" rel="stylesheet" />
<link href="<?=$this->request->webroot?>frontend/css2/responsive.css" rel="stylesheet" />

<!-- <link href="css/bootstrap.min.css" rel="stylesheet">
<link type="text/css" href="css/style.css" rel="stylesheet">
<link type="text/css" href="css/responsive.css" rel="stylesheet"> -->
<script src="<?=$this->request->webroot?>frontend/js2/jquery.min.js"></script>
<script src="<?=$this->request->webroot?>frontend/js2/jquery-migrate.min.js"></script>
<script src="<?=$this->request->webroot?>frontend/js2/bootstrap.bundle.min.js"></script>

<!-- <script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery-migrate.min.js"></script>
<script type="text/javascript" src="js/bootstrap.bundle.min.js"></script> -->
</head>
<body class="noJS">
    <div id="wrapper">
    <!-- Header Section Start -->
    <header>
        <div class="headerWrap">
            <!-- Pre Header -->
            <div class="preHeader">
                <div class="container">
                    <div class="row">
                    <div class="col-6">
                        <div class="preHeaderWrap">
                        <ul class="topLinks lp-left">
                            <li><a tabindex="1" href="#content" class="skipContent" title="Skip to Main Content">Faq</a></li>
                            <li><a href="#" title="Screen Reader Access">Help</a></li>
                            <li><a href="contact-us.html" title="Screen Reader Access">Contact Us</a></li>
                            <li class="social_links">
                                <a href=""><i class="fa fa-facebook" aria-hidden="true"></i></a>
                                <a href=""><i class="fa fa-twitter" aria-hidden="true"></i></a>
                                <a href=""><i class="fa fa-linkedin" aria-hidden="true"></i></a>
                                <a href=""><i class="fa fa-instagram" aria-hidden="true"></i></a>
                            </li>
                        </ul>
                    </div>
                    </div>
                    <div class="col-6 topright">
                        <div class="preHeaderWrap d-flex align-items-center justify-content-end">
                        <ul class="topLinks lp_right">
                            <li><a tabindex="1" href="#content" class="skipContent" title="Skip to Main Content">Skip to Main
                            Content</a></li>
                            <li><a href="#" title="Screen Reader Access">Sitemap</a></li>
                            <li class="fontResize">
                                <div id="accessControl" class="d-flex align-items-center">
                                    <input type="submit" name="font_normal" value="A-" onclick="return validate();" id="font_normal" title="Decrease Font Size" class="fontScaler">
                                    <input type="submit" name="font_large" value="A" onclick="return validate();" id="font_large" title="Normal Font Size" class="fontScaler">
                                    <input type="submit" name="font_larger" value="A+" onclick="return validate();" id="font_larger" title="Increase Font Size" class="fontScaler">
                                    <div class="changeColor d-flex align-items-center">
                                        <input type="submit" name="contrast_normal" value="Standard View" onclick="return false;" id="contrast_normal" accesskey="7" title="Standard View" class="contrastChanger normal">
                                        <input type="submit" name="contrast_wob" value="High Contrast View" onclick="return false;" id="contrast_wob" accesskey="8" title="High Contrast View" class="contrastChanger wob ">
                                    </div>
                                </div>
                            </li>
                            <li class="siteLanguage">
                                <div class="languageWrap">
                                    <select id="language">
                                        <option>English</option>
                                        <option>Hindi</option>
                                    </select>
                                </div>
                            </li>
                        </ul>
                    </div>
                    </div>
                </div></div>
            </div>
            <section>
                <div class="container">
                    <div class="row">
                        <div class="col-8 col-sm-4">
                            <div class="headerLogo">
                                <a href="#" title="Logo">
                                    <!-- <img src="images/header-logo.png" alt=""> -->
                                    <?php echo $this->Html->image('image3/header-logo.png', array('alt' => 'Logo Image')); ?>
                                </a>
                            </div>
                        </div>
                        <div class=" col-4 col-md-4">
                            <button class="toglesearch" id="toglesearch">
                                <!-- <img src="images/search.png" alt="img" /> -->
                                <?php echo $this->Html->image('image3/search.png', array('alt' => 'Logo Image')); ?>
                            
                            </button>
                            <div class="searchBox1">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="" placeholder="Search" />
                                    <button>
                                <?php echo $this->Html->image('image3/search.png', array('alt' => 'Logo Image')); ?>
                                        
                                    <!-- <img src="images/search.png" alt="img" /> -->
                                </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="logo-rt">
                            <?php echo $this->Html->image('image3/g20.png', array('alt' => 'Logo Image')); ?>

                                <!-- <img src="images/g20.png" alt="img" /> -->
                            </div>
                            <div class="logo-rt mscs">
                            <?php echo $this->Html->image('image3/mscs.png', array('alt' => 'Logo Image')); ?>

                                <!-- <img src="images/mscs.png" alt="img" /> -->
                            </div>
                            <div class="logo-rt">
                            <?php echo $this->Html->image('image3/swatchh.png', array('alt' => 'Logo Image')); ?>

                                <!-- <img src="images/swatchh.png" alt="img" /> -->
                            </div>
                        </div>
                    </div>
                </div>
            </section>
    
            <!-- Main Header -->
            <div class="mainHeader">
                <div class="container">
                    <div class="mainHeaderWrap d-flex flex-wrap align-items-end">
                        <div class="headerRight d-flex flex-wrap align-items-center">
                            <!-- Main Menu -->
                            <div class="mainMenu">
                                <div id="mainNav" class="mainNavigation">
                                    <ul id="nav">
                                    <li class="active"><a href="<?=$this->Url->build(['controller'=>'home','action'=>'index'])?>" title="Home">Home</a></li>
                                        <li><a href="<?=$this->Url->build(['controller'=>'home','action'=>'applications'])?>" title="operational Structure">operational Structure</a></li>
                                        <li><a href="<?=$this->Url->build(['controller'=>'home','action'=>'notification'])?>" title="Notification">Notification</a></li>
                                        <li><a href="<?=$this->Url->build(['controller'=>'home','action'=>'training'])?>" title="Training and Demonstration">Training and Demonstration</a></li>
                                        <li><a href="<?=$this->Url->build(['controller'=>'home','action'=>'contact'])?>" title="Contact Us">Contact Us</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="headerSearch">
                                <ul>
                                <li><a href="#" title="Sign up" class="sign_up">Sign Up</a></li>
                                <li><a href="<?=$this->Url->build(['controller'=>'users','action'=>'login','prefix'=>'admin'])?>" title="login" class="login">Login</a><li>
                        
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>





    <!-- Header Section End -->

<section class="banner-98">
	<div class="container">
		<div class="row">
			<div class="col-md-6">
				<div class="banner-cont-98">
					<h5>Contact-Us</h5>

					<h2 style="color: white;">Lets Get a Quotation</h2>
					
				
					<div class="tas">
						<span>
                            <!-- <img src="images/ts.png" alt="img" /> -->
                            <?php echo $this->Html->image('image3/ts.png', array('alt' => 'Logo Image')); ?>
                            
                        </span>
						<p>Technical Helpdesk<br>ncd.tech-coop@gov.in,Tele No: 011 20862616<br>For Administrative related queries ncd.dpt-coop@gov.in</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<div class="mid-contaner-inner">
    <div class="guidline-principle">
      <div class="container">
         <div class="row">
  <section class="contactus" id="contactus">
  <div class="section-title con">
  <h2>Contact<span style="color: rgba(107,22,81,0.9);"> Us</span></h2>
  </div>
  
  <div class="addresb-s">
  <p>ncd.tech-coop@gov.in,Tele No: 011 20862616
    For Administrative related queries ncd.dpt-coop@gov.in<br />
  Website -<a href="#" style="color: rgba(107,22,81,0.9) ; font-weight: bold;"> https://cooperatives.gov.in/en</a></p>
  </div>
  
  <div class="contact-info-area pt-120">
  <div class="container">
  <div class="row">
  <div class="col-lg-4 col-md-6">
  <div class="contact-info-box">
  <div class="icon"><i class="fa fa-envelope"></i></div>
  
  <h3>Mail Here</h3>
  
  <p>abc123@gmail.com</p>
  </div>
  </div>
  
  <div class="col-lg-4 col-md-6">
  <div class="contact-info-box">
  <div class="icon"><i class="fa fa-fax" aria-hidden="true"></i></div>
  
  <h3>Fax</h3>
  
  <p>+91 9876541222</p>
  </div>
  </div>
  
  <div class="col-lg-4 col-md-6 offset-lg-0 offset-md-3">
  <div class="contact-info-box">
  <div class="icon"><i class="fa fa-phone"></i></div>
  
  <h3>Phone No.</h3>
  
  <p>+91 9874563210</p>
 
  </div>
  </div>
  </div>
  </div>
  </div>



</div>
         
         
         
</div>
</div>
</div>

  </section>

<!-- Our Business Section Start -->
<section class="ourBusiness">

	<div class="ourBusinessBg">

		<div class="parallaxBg-45">  
      
        </div>
 
	</div>
  
  
	<div class="container">
        
		<div class="row align-items-center">
			<div class="col-lg-6">
        
				<div class="india-map">
                    <div class="main-card mb-12">
                        <div id="" class="card-body">
                            <div id="maps-1" class="card-body mapcard">
                                <span id="map_id">  
                                    <div id="info-11"> </div>
                                    <div id="info-box"></div>
                                    <h2 style="text-align: center; padding-top: 30%;">Get <span style="color: rgba(107,22,81,0.9);"> In Touch</h2></span>
                                    <?php echo $this->Html->image('image3/csr-bg.jpg', array('alt' => '...','class' => "img-thumbnail")); ?>
                        
                                    <!-- <img src="../National-cooperative-database/images/csr-bg.jpg" class="img-thumbnail" alt="..."> -->
                                </span> 
                            </div>
                        </div>
                    </div>
                </div>
			</div>
            
			<div class="col-lg-6">
                <div class="form" style="height: 550px; border: 5px double white;">
                    <div class="title">Welcome</div>
                    
                    <div class="subtitle">Let's create your account!</div>
                    <div class="input-container ic1">
                      <input id="firstname" class="input" type="text" placeholder="Full Name " />
                  
                      <label for="firstname" ></label>
                    </div>
                    <div class="input-container ic2">
                        <input id="email" class="input" type="text" placeholder="Email " />
                   
                        <label for="email" ></label>
                      </div>
                      <div class="input-container ic2">
                        
                        <textarea  class="input" style="height: 85px;" id="exampleFormControlTextarea1" rows="3" placeholder="Message"></textarea>
                        <label for="exampleFormControlTextarea1" class="form-label"></label>
                      </div>
                   <button type="text" class="submit">submit</button>
                  </div>
					   
					</div> 
				</div>
			</div>
		</div>
	</div>
</section>
<!-- Our Business Section End -->

<div class="container-fluid">
    <div class="map-responsive">
   <iframe src="https://www.google.com/maps/embed/v1/place?key=AIzaSyA0s1a7phLN0iaD6-UE7m4qP-z21pH0eSc&q=Eiffel+Tower+Paris+France" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
</div>
</div>
<!-- Logo Slider Section Start -->
<section class="logosSection" style="margin-top: 3%;">
	<div class="container">
		<div class="logosSliderWrap" >
			<div class="swiper logosSlider">
				<div class="swiper-wrapper">
					<div class="swiper-slide">
						<div class="logoWrap"><span><a href="https://india.gov.in/" target="_blank">
                        <?php echo $this->Html->image('image3/ext-logo1.png', array('title' => 'https://india.gov.in/, The National Portal of India : External website that opens in a new window')); ?>

                            <!-- <img alt="" src="images/ext-logo1.png" title="https://india.gov.in/, The National Portal of India : External website that opens in a new window" />  -->
                        </a> </span></div>
					</div>

					<div class="swiper-slide style=margin-top:5%;">
						<div class="logoWrap"><span><a href="https://amritmahotsav.nic.in/" target="_blank">
                            <!-- <img alt="External website that opens in a new window" src="images/ext-logo2.png" title="https://amritmahotsav.nic.in/, External website that opens in a new window" />  -->
                            <?php echo $this->Html->image('image3/ext-logo2.png', array('title' => 'https://amritmahotsav.nic.in/, External website that opens in a new window', 'alt' => 'External website that opens in a new window')); ?>
                        
                        </a> </span></div>
					</div>

					

					<div class="swiper-slide">
						<div class="logoWrap"><span><a href="#" target="_blank">
                            <!-- <img alt="" src="images/ext-logo4.png" title="#, External website that opens in a new window" /> -->
                        <?php echo $this->Html->image('image3/ext-logo4.png', array('title' => '#, External website that opens in a new window')); ?>
                          
                        </a> </span></div>
					</div>
					<div class="swiper-slide">
						<div class="logoWrap"><span><a href="https://swachhbharat.mygov.in/" target="_blank">
                        <?php echo $this->Html->image('image3/ext-logo3.png', array('title' => 'https://swachhbharat.mygov.in/, External website that opens in a new window')); ?>

                            <!-- <img alt="" src="images/ext-logo3.png" title="https://swachhbharat.mygov.in/, External website that opens in a new window" />  -->
                        </a> </span></div>
					</div>

					<div class="swiper-slide">
						<div class="logoWrap"><span><a href="#" target="_blank">
                        <?php echo $this->Html->image('image3/ext-logo5.png', array('title' => '#, External website that opens in a new window')); ?>
                            <!-- <img alt="" src="images/ext-logo5.png" title="#, External website that opens in a new window" />  -->
                        </a> </span></div>
					</div>
				</div>

			</div>
		</div>
		<div class="logosSliderArrows d-flex align-items-start justify-content-end">
					<div class="logosSlider-next sliderArrow blueArrow"><i class="fa fa-angle-up" aria-hidden="true"></i></div>
					<div class="logosSlider-prev sliderArrow blueArrow"><i class="fa fa-angle-down" aria-hidden="true"></i></div>
				</div>
	</div>
</section>
    <!-- Footer Section Start -->
<footer class="footerSection">
	<div class="container">
			<div class="footerBottom d-flex flex-wrap align-items-center">
			<div class="footerBottomText">
				<div>
					<a href="#" title="Terms & Conditions">Terms & Conditions</a> &nbsp; <span>|</span> &nbsp; <a href="#" title="Privacy Policy">Privacy Policy</a> &nbsp; <span>|</span> &nbsp; <a href="#" title="Copyright Policy">Copyright Policy</a> &nbsp; <span>|</span> &nbsp; <a href="#" title="Hyperlinking Policy">Hyperlinking Policy</a> &nbsp; <span>|</span> &nbsp; <a href="#" title="Disclaimer">Disclaimer</a> &nbsp; <span>|</span> &nbsp; <a href="#" title="Accessibility Statement">Accessibility Statement</a> &nbsp; <span>|</span> &nbsp; <a href="#" title="Help">Help</a>
				</div>
				<div>Website Content Managed & Owned by National Cooperative Database, Government of India</div>
			</div>
			<div class="footerUpdate">
				Last Updated: 27 feb 2023 <br>Visitors: 24,30,980
				<ul class="topLinks lp-left ft">
					<li class="social_links">
						<a href=""><i class="fa fa-facebook" aria-hidden="true"></i></a>
						<a href=""><i class="fa fa-twitter" aria-hidden="true"></i></a>
						<a href=""><i class="fa fa-linkedin" aria-hidden="true"></i></a>
						<a href=""><i class="fa fa-instagram" aria-hidden="true"></i></a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</footer>
<!-- Footer Section End -->

</div>
<a href="#top" title="Back to Top" id="backtotop"><i class="fa fa-angle-up" aria-hidden="true"></i></a>
<script src="<?=$this->request->webroot?>frontend/js2/functions.js"></script>
<script src="<?=$this->request->webroot?>frontend/js2/general.js"></script>
<script src="<?=$this->request->webroot?>frontend/js2/indiamap.js"></script>
<script src="<?=$this->request->webroot?>frontend/js2/countrymap.js"></script>
<!-- <script type="text/javascript" src="js/functions.js"></script>
<script type="text/javascript" src="js/general.js"></script>
<script type="text/javascript" src="js/indiamap.js"></script>
<script type="text/javascript" src="js/countrymap.js"></script> -->
</body>
</html>
