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
    .banner-cont-2 {
    background: url('../../webroot/img/image2/4.jpg');
    background-size: 100%;
    background-repeat: no-repeat;
    width: 100%;
    position: relative;
    overflow: hidden;
    height: 50vh;
}
section.banner-cont-2::before {
    content: '';
    position: absolute;
    width: calc(50% + 120px);
    background: rgba(107,22,81,0.9);
    left: -120px;
    top: 0;
    height: 105%;
    transform: skewX(15deg);
    z-index: 0;
}
.box-1{
    text-align: center;
    margin-top: 5%;
}
.box-1 span{
    color:rgba(107,22,81,0.9) ;
}
.box-1 p{
    font-weight: normal;
    text-align: justify;
    margin-top: 2%;
}



.contact-info-box-2 {
    text-align: center;
    transition: 0.5s;
    box-shadow: 0 2px 48px 0 rgb(0 0 0 / 8%);
    background: #ffffff;
    padding: 30px;
    min-height: 237px;
    border: 4px double rgba(107,22,81,0.9) ;
    border-top-left-radius: 40px;
    border-bottom-right-radius: 40px;
}
.contact-info-box-2 .icon {
    display: inline-block;
    width: 80px;
    height: 80px;
    line-height: 80px;
    background: #edf5ff;
    border-radius: 50%;
    font-size: 30px;
    color: rgba(107,22,81,0.9);
    transition: 0.5s;
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
                                <?php echo $this->Html->image('image2/header-logo.png', array('alt' => 'img')); ?>
                                
                                </a>
                            </div>
                        </div>
                        <div class=" col-4 col-md-4">
                            <button class="toglesearch" id="toglesearch"> <?php echo $this->Html->image('image2/search.png', array('alt' => 'img')); ?></button>
                            <div class="searchBox1">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="" placeholder="Search" />
                                    <button>
                                    <?php echo $this->Html->image('image2/search.png', array('alt' => 'img')); ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="logo-rt">
                            <?php echo $this->Html->image('image2/g20.png', array('alt' => 'img')); ?>

                            </div>
                            <div class="logo-rt mscs">
                            <?php echo $this->Html->image('image2/mscs.png', array('alt' => 'img')); ?>

                            </div>
                            <div class="logo-rt">
                            <?php echo $this->Html->image('image2/swatchh.png', array('alt' => 'img')); ?>
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
    <section class="banner-cont-2" >
        <div class="container">
            <div class="row">
                <div class="col-6">
                    <div class="banner-cont">
                  
                        <h2 style="margin-top: 7%;">Training and Demonstration</h2>
                       
                        <h3>One day Farmers Training and Demonstration</h3>
                        <p>Objectives : To create awareness at village level about organic farming, its need, practices and benefits and to provide knowledge about organic certification, branding and marketing including PGS and Jaivik Kheti portal operation</p>
                        <div class="tas">
                            <span>
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
  


<div class="mid-contaner-inner">
    <div class="guidline-principle">
      <div class="container">
         <div class="row">
  <section class="contactus" id="contactus">
  <div class="section-title con">
  <h2>Training and<span style="color: rgba(107,22,81,0.9);"> Demonstration</span></h2>
  </div>
  
  <div class="addresb-s">
  <p>Various scholars have widely studied the potential benefits of technology in improving rice production whereby the impact of technology adoption is well analyzed [[1], [2], [3], [4], [5], [6], [7]]. Technical development is reported to increase agricultural productivity by introducing improved agricultural technologies.

  </div>
  
  <div class="contact-info-area pt-120">
  <div class="container">
  <div class="row">
  <div class="col-lg-4 col-md-6">
  <div class="contact-info-box-2">
  <div class="icon"><i class="fa fa-podcast"></i></div>
  
  <h4 style="margin-top: 5%;">Abstract</h4>
  
  <p style="text-align: justify;">Farmers' participation in extension training and demonstration programs may unleash farmers' potential and enable them to adopt improved production techniques to bring about sustainable farm productivity improvement. This study applies Poisson model with the endogenous covariate in primary data from 469 farming households in the Mvomero district of Tanzania to determine the effect of the agricultural extension training and demonstration programs on the adoption</p>
  </div>
  </div>
  
  <div class="col-lg-4 col-md-6">
  <div class="contact-info-box-2">
  <div class="icon"><i class="fa fa-user-circle-o" aria-hidden="true"></i></div>
  
  <h4 style="margin-top: 5%;">Introduction</h4>
  
  <p style="text-align: justify;">Various scholars have widely studied the potential benefits of technology in improving rice production whereby the impact of technology adoption is well analyzed. Technical development is reported to increase agricultural productivity by introducing improved agricultural technologies. For example, these studies show that rice yield increased from 1 MT/ha in 2013 and 2.7 MT/ha in 2014 when farmers adopted the recommended rice cultivation practices [6]. The adoption </p>
  </div>
  </div>
  
  <div class="col-lg-4 col-md-6 offset-lg-0 offset-md-3">
  <div class="contact-info-box-2">
  <div class="icon"><i class="fa fa-window-restore"></i></div>
  <h4 style="margin-top: 5%;">Section Snippets</h4>

  <p style="text-align: justify;">This research applies the diffusion of innovations theory [30] to explain agricultural technology adoption among individual farmers. Based on the theory, diffusion is linked to technology transfer, which infers the process of disseminating a given technology among economic units through a specific channel. Adoption is the full decision to use or not to use a given technology as the best course of action available following the diffusion process. This theory acknowledges that technology</p>
  

 
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
  <!--------start-->
  
<!-- Our Business Section Start -->
<div class="white-bg"></div>
<section class="ourBusiness info">
	<div class="ourBusinessBg">
		<div class="parallaxBg" style="background: rgba(107,22,81,0.9);"></div>
	</div>
	<div class="container">
		<div class="row align-items-center">
			<div class="col-lg-6">
				<div class="info_sec d-flex flex-column justify-content-center p-50">
					<h3 style="color: white; font-size: 2em;">Assistance for Training and capacity building of Farmers</h3>
                <p style="text-align: justify; color: white;">National Mission on Natural Farming in the state will be functioning under the overall control of State Level Sanctioning Committee, headed by the Principal Secretary/ Secretary Agriculture. For effective implementation the States shall create a dedicated mission management cell at head quarter. The district level implementation will be supervised by a committee under the chairmanship of District Collector and having Project Director ATMA, Head of Krishi Vigyan Kendra and District Agriculture, Horticulture and Animal Husbandry Officers as members.

                </p>
					
				</div>
			</div>
			<div class="col-lg-6">
				<div class="info_sec info_s">
                    
					<h5>Training and Extension for Farmers

                    </h5>
                    <?php echo $this->Html->image('image2/banner.jpg', array('class' => 'img-thumbnail')); ?>

                        </div>





                        
                      </div>
				</div>
			</div>
		
			
			</div>
		</div>
	</div>



    
</section>
<!-- Our Business Section End -->
<!-- Our Business Section Start -->
<div class="white-bg"></div>
<section class="ourBusiness info">
	<div class="ourBusinessBg">
		<div class="parallaxBg" style="background: url('../../../webroot/img/image2/info.jpg'); background-repeat: no-repeat; background-size: cover;"></div>
	</div>
	<div class="container">
		<div class="row align-items-center">
			<div class="col-lg-6">
                <div class="info_sec info_s">
					<h5>National Mission on Natural Farming </h5>
                    <?php echo $this->Html->image('image2/banner.jpg', array('class' => 'img-thumbnail')); ?>

					
				</div>
			</div>
			<div class="col-lg-6">
				<div class="info_sec info_s">
					<h5>Pradhan Mantri Krishi Sinchai Yojana (PMKSY)
                    </h5>
               
                    <?php echo $this->Html->image('image2/working-hothouse.jpg', array('class' => 'img-thumbnail')); ?>

                    <!-- <img src="images/working-hothouse.jpg" class="img-thumbnail" alt="..."> -->






           

				</div>
			</div>
			<div class="col-lg-6">
				<div class="info_sec info_s">
					<h5>Horticulture </h5>
          <?php echo $this->Html->image('image2/4.jpg', array('class' => 'img-thumbnail')); ?>
                   
                    <!-- <img src="images/4.jpg" class="img-thumbnail" alt="..."> -->

			
				</div>
			</div>
			<div class="col-lg-6">
                <div class="info_sec d-flex flex-column justify-content-center p-50">
					<h3>What to Do?
                    </h3>
					<p style="text-align: justify;">States can engage consultants, technical assistants at state and district level having technical and administrative expertise for providing technical guidance and effective monitoring of the scheme implementation. States can decide the number based on their requirements at State and District level.

                    </p>
					
			
				</div>
			</div>
		</div>
	</div>
</section>
<!-- Our Business Section End -->



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
<!-- Footer Section End -->

</div>
<a href="#top" title="Back to Top" id="backtotop"><i class="fa fa-angle-up" aria-hidden="true"></i></a>
<script src="<?=$this->request->webroot?>frontend/js2/functions.js"></script>
<script src="<?=$this->request->webroot?>frontend/js2/general.js"></script>
<script src="<?=$this->request->webroot?>frontend/js2/indiamap.js"></script>
<script src="<?=$this->request->webroot?>frontend/js2/countrymap.js"></script>

</body>
</html>
