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
<link href="<?=$this->request->webroot?>frontend/css2/style.css" rel="stylesheet" />
<link href="<?=$this->request->webroot?>frontend/css2/responsive.css" rel="stylesheet" />

<script src="<?=$this->request->webroot?>frontend/js2/jquery.min.js"></script>
<script src="<?=$this->request->webroot?>frontend/js2/jquery-migrate.min.js"></script>
<script src="<?=$this->request->webroot?>frontend/js2/bootstrap.bundle.min.js"></script>
<style>
	.headerSearch ul{
		list-style: none;
		display:flex;
		padding-top:15px;
	}
    .image4 img{
    margin-top: 10%;
}
	
</style>
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
                            <li><a href="#" title="Screen Reader Access">Contact Us</a></li>
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
                                  <?php echo $this->Html->image('image2/header-logo.png', array('alt' => 'img')); ?>
                                
                                </a>
                            </div>
                        </div>
                        <div class=" col-4 col-md-4">
                            <button class="toglesearch" id="toglesearch"><img src="images/search.png" alt="img" /></button>
                            <div class="searchBox1">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="" placeholder="Search" />
                                    <button>
                                    <?php echo $this->Html->image('image2/search.png', array('alt' => 'img')); ?>
                                      <!-- <img src="images/search.png" alt="img" /> -->
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="logo-rt">
                                <!-- <img src="images/g20.png" alt="img" /> -->
          <?php echo $this->Html->image('image2/g20.png', array('alt' => 'img')); ?>

                            </div>
                            <div class="logo-rt mscs">
          <?php echo $this->Html->image('image2/mscs.png', array('alt' => 'img')); ?>

                                <!-- <img src="images/mscs.png" alt="img" /> -->
                            </div>
                            <div class="logo-rt">
          <?php echo $this->Html->image('image2/swatchh.png', array('alt' => 'img')); ?>

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
    <section class="banner-2n">
        <div class="container">
            <div class="row">
                <div class="col-6">
                    <div class="banner-cont">
                   
                        <h2 style="margin-top: 5%;">OPERATIONAL STRUCTURE</h2>
                        
                        <ul>
                            
                            <li>National Mission on Natural Farming in the state will be functioning under the overall control of State Level Sanctioning Committee, headed by the Principal Secretary/ Secretary Agriculture</li>
                        </ul>
                        <h3>Technical and Administrative Help Desk</h3>
                        <div class="tas">
                            <span>
                              <!-- <img src="images/ts.png" alt="img" /> -->
          <?php echo $this->Html->image('image2/ts.png', array('alt' => 'img')); ?>

                            
                            </span>
                            <p>Technical Helpdesk<br>ncd.tech-coop@gov.in,Tele No: 011 20862616<br>For Administrative related queries ncd.dpt-coop@gov.in</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <section class="ministers">
        <div class="container">
            <div class="row">
                <div class="col-md-4 border-right">
                    <div class="mis_sc">
                        <div class="mins">
                          <!-- <img src="images/mini1.jpg" alt="img" /> -->
          <?php echo $this->Html->image('image2/mini1.jpg', array('alt' => 'img')); ?>
                      
                      </div>
                        <div class="mins_cont">
                            <h2>Shri Narendra Modi</h2>
                            <p>Prime Minister</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 border-right">
                    <div class="mis_sc">
                        <div class="mins">
                          <!-- <img src="images/mini2.jpg" alt="img" /> -->
          <?php echo $this->Html->image('image2/mini2.jpg', array('alt' => 'img')); ?>
                        
                        </div>
                        <div class="mins_cont">
                            <h2>Shri Amit Shah</h2>
                            <p>Union Minister</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mis_sc">
                        <div class="mins">
          <?php echo $this->Html->image('image2/mini3.jpg', array('alt' => 'img')); ?>

                          <!-- <img src="images/mini3.jpg" alt="img" /> -->
                        </div>
                        <div class="mins_cont">
                            <h2>Shri B.L Verma</h2>
                            <p>Minister of State</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    

<!-- Our Business Section Start -->
<div class="white-bg"></div>
<section class="ourBusiness info">
	<div class="ourBusinessBg">
		<div class="parallaxBg bg2" style=""></div>
	</div>
	<div class="container">
		<div class="row align-items-center">
			<div class="col-lg-6">
				<div class="info_sec d-flex flex-column justify-content-center p-50">
					<h3>OPERATIONAL STRUCTURE AND MISSION</h3>
                <p style="text-align: justify;">National Mission on Natural Farming in the state will be functioning under the overall control of State Level Sanctioning Committee, headed by the Principal Secretary/ Secretary Agriculture. For effective implementation the States shall create a dedicated mission management cell at head quarter. The district level implementation will be supervised by a committee under the chairmanship of District Collector and having Project Director ATMA, Head of Krishi Vigyan Kendra and District Agriculture, Horticulture and Animal Husbandry Officers as members.

                </p>
					
				</div>
			</div>
			<div class="col-lg-6">
				<div class="info_sec info_s">
					<h5>National Mission on Natural Farming </h5>
          <?php echo $this->Html->image('image2/banner.jpg', array('class' => 'img-thumbnail')); ?>

                    <!-- <img src="images/banner.jpg" class="img-thumbnail" alt="..."> -->

				</div>
			</div>
			<div class="col-lg-6">
				<div class="info_sec info_s">
					<h5>State Project Management Team (PMT): </h5>
          <?php echo $this->Html->image('image2/banner.jpg', array('class' => 'img-thumbnail')); ?>
                   
                    <!-- <img src="images/banner.jpg" class="img-thumbnail" alt="..."> -->

			
				</div>
			</div>
			<div class="col-lg-6">
                <div class="info_sec d-flex flex-column justify-content-center p-50">
					<h3>How will the data be captured?</h3>
					<p style="text-align: justify;">States can engage consultants, technical assistants at state and district level having technical and administrative expertise for providing technical guidance and effective monitoring of the scheme implementation. States can decide the number based on their requirements at State and District level. Financial support for the same will be drawn from 2% administrative costs provided to the States.

                    </p>
					
			
				</div>
			</div>
		</div>
	</div>
</section>
<!-- Our Business Section End -->





<!-- Our Business Section Start -->

<div class="container">
    <div class="row">
        <div class="col">
            <div class="image4">
          <?php echo $this->Html->image('image2/Directorate_Agri-culture-Food-Production.jpg', array('class' => 'card-img-top')); ?>
<!-- 
            <img src="images/Directorate_Agri-culture-Food-Production.jpg" class="card-img-top" alt="..."> -->
        </div>
        </div>
    </div>
</div>
<div class="white-bg"></div>

<section class="ourBusiness info">
	<div class="ourBusinessBg">
		<div class="parallaxBg" style="background: rgba(107,22,81,0.9);"></div>
	</div>
	<div class="container">
		<div class="row align-items-center">
			<div class="col-lg-6">
				<div class="info_sec d-flex flex-column justify-content-center p-50">
					<h3 style="color: white;">OPERATIONAL STRUCTURE AND MISSION</h3>
                <p style="text-align: justify; color: white;">National Mission on Natural Farming in the state will be functioning under the overall control of State Level Sanctioning Committee, headed by the Principal Secretary/ Secretary Agriculture. For effective implementation the States shall create a dedicated mission management cell at head quarter. The district level implementation will be supervised by a committee under the chairmanship of District Collector and having Project Director ATMA, Head of Krishi Vigyan Kendra and District Agriculture, Horticulture and Animal Husbandry Officers as members.

                </p>
					
				</div>
			</div>
			<div class="col-lg-6">
				<div class="info_sec info_s">
					<h5>Functions of the State Implementing Department:
                    </h5>
                    <div class="accordion" id="accordionExample">
                        <div class="accordion-item">
                          <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                               <h2>Prepare annual State Level</h2>
                            </button>
                          </h2>
                          <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                              <p>Prepare annual State Level Action Plan by compiling District-wise Action Plan and submit to the State Level Executive Committee (SLEC) for approval and there after forward the same to EC</p>

                            </div>
                          </div>
                        </div>
                        <div class="accordion-item">
                          <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                               <h2> Receive funds from DA&FW</h2>
                            </button>
                          </h2>
                          <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                              <p>Receive funds from DA&FW for implementation and oversee its implementation, regular monitoring & review of programme. Facilitate monitoring by INM Division/ NCONF/RCONFs</p>
                                
                            </div>
                          </div>
                        </div>
                        <div class="accordion-item">
                          <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                               <h2> Implementing Department</h2>
                            </button>
                          </h2>
                          <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                             <p>Implementing Department/ state implementing agency shall ensure that the clusters are linked to the market before the closure of the programme.</p>

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
<!-- Our Business Section End -->


<h2 style="font-size: 2em; text-align: center; margin-top: 3%;">Regional / Local Centres<span style="color: #701753;"> of Excellences (RCEs)</span></h2>

<div class="container">
 
        
            <div class="row row-cols-1 row-cols-md-3 g-4">
             
                <div class="col">
              
                  <div class="card h-100">
          <?php echo $this->Html->image('image2/1.jpg', array('class' => 'card-img-top')); ?>
                    
                    <!-- <img src="images/2.jpg" class="card-img-top" alt="..."> -->
                    <div class="card-body">
                      <h5 class="card-title">State Project Management Team</h5>
                      <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This card has even longer content than the first to show that equal height action.</p>
                      <a href="#" class="btn">Click Here</a>
             
                    </div>
                  </div>
                </div>
                <div class="col">
                  <div class="card h-100">
          <?php echo $this->Html->image('image2/1.jpg', array('class' => 'card-img-top')); ?>

                    <!-- <img src="images/1.jpg" class="card-img-top" alt="..."> -->
                    <div class="card-body">
                      <h5 class="card-title">Regional / Local Centres of Excellences</h5>
                      <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This card has even longer content than the first to show that equal height action.</p>
                      <a href="#" class="btn ">Click Here</a>
                    </div>
                  </div>
                </div>
                <div class="col">
                  <div class="card h-100">
          <?php echo $this->Html->image('image2/1.jpg', array('class' => 'card-img-top')); ?>

                    <!-- <img src="images/1.jpg" class="card-img-top" alt="..."> -->
                    <div class="card-body">
                      <h5 class="card-title">Block Level implementation</h5>
                      <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This card has even longer content than the first to show that equal height action.</p>
               
                 
                        <a href="#" class="btn ">Click Here</a>
                        </div>
                    </div>
                  </div>
                </div>
              </div>
        </div>
    </div>
</div>


<!----section end-->

<!-- Logo Slider Section Start -->
<section class="logosSection">
	<div class="container">
		<div class="logosSliderWrap">
			<div class="swiper logosSlider">
				<div class="swiper-wrapper">
					<div class="swiper-slide">
						<div class="logoWrap"><span><a href="https://india.gov.in/" target="_blank">
                        <?php echo $this->Html->image('image2/ext-logo1.png', array('title' => 'https://india.gov.in/, The National Portal of India : External website that opens in a new window')); ?>
 
                        </a> </span></div>
					</div>

					<div class="swiper-slide">
						<div class="logoWrap"><span><a href="https://amritmahotsav.nic.in/" target="_blank">
                        <?php echo $this->Html->image('image2/ext-logo2.png', array('alt' => 'External website that opens in a new window','title' => 'https://amritmahotsav.nic.in/, External website that opens in a new window')); ?>

                        </a> </span></div>
					</div>

					

					<div class="swiper-slide">
						<div class="logoWrap"><span><a href="#" target="_blank">
                        <?php echo $this->Html->image('image2/ext-logo4.png', array('title' => '#, External website that opens in a new window')); ?>

                         </a> </span></div>
					</div>
					<div class="swiper-slide">
						<div class="logoWrap"><span><a href="https://swachhbharat.mygov.in/" target="_blank">
                        <?php echo $this->Html->image('image2/ext-logo3.png', array('title' => 'https://swachhbharat.mygov.in/, External website that opens in a new window')); ?>

                         </a> </span></div>
					</div>

					<div class="swiper-slide">
						<div class="logoWrap"><span><a href="#" target="_blank">
                        <?php echo $this->Html->image('image2/ext-logo5.png', array('title' => 'https://swachhbharat.mygov.in/, External website that opens in a new window')); ?>

                            <img alt="" src="images/ext-logo5.png" title="#, External website that opens in a new window" />
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
<!-- Logo Slider Section End -->



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


</div>
<a href="#top" title="Back to Top" id="backtotop"><i class="fa fa-angle-up" aria-hidden="true"></i></a>
<script src="<?=$this->request->webroot?>frontend/js2/functions.js"></script>
<script src="<?=$this->request->webroot?>frontend/js2/general.js"></script>
<script src="<?=$this->request->webroot?>frontend/js2/indiamap.js"></script>
<script src="<?=$this->request->webroot?>frontend/js2/countrymap.js"></script>

</body>
</html>
