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


<!-- <link href="css/bootstrap.min.css" rel="stylesheet">
<link type="text/css" href="css/style.css" rel="stylesheet">
<link type="text/css" href="css/responsive.css" rel="stylesheet"> -->
<script src="<?=$this->request->webroot?>frontend/js2/jquery.min.js"></script>
<script src="<?=$this->request->webroot?>frontend/js2/jquery-migrate.min.js"></script>
<script src="<?=$this->request->webroot?>frontend/js2/bootstrap.bundle.min.js"></script>

<!-- <script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery-migrate.min.js"></script>
<script type="text/javascript" src="js/bootstrap.bundle.min.js"></script> -->
<style>
	.headerSearch ul{
		list-style: none;
		display:flex;
		padding-top:15px;
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
							<?php echo $this->Html->image('image2/header-logo.png', array('alt' => 'User Image')); ?>
								<!-- <img src="images/header-logo.png" alt=""> -->
							</a>
						</div>
					</div>
					<div class=" col-4 col-md-4">
						<button class="toglesearch" id="toglesearch">
						<?php echo $this->Html->image('image2/search.png', array('alt' => 'User Image')); ?>

							<!-- //<img src="images/search.png" alt="img" /> -->
						</button>
						<div class="searchBox1">
							<div class="form-group">
								<input type="text" class="form-control" name="" placeholder="Search" />
								<button>
								<?php echo $this->Html->image('image2/search.png', array('alt' => 'User Image')); ?>

									<!-- <img src="images/search.png" alt="img" /> -->
								</button>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="logo-rt">
						<?php echo $this->Html->image('image2/g20.png', array('alt' => 'User Image')); ?>

							<!-- <img src="images/g20.png" alt="img" /> -->
						</div>
						<div class="logo-rt mscs">
						<?php echo $this->Html->image('image2/mscs.png', array('alt' => 'User Image')); ?>

							<!-- <img src="images/mscs.png" alt="img" /> -->
						</div>
						<div class="logo-rt">
						<?php echo $this->Html->image('image2/swatchh.png', array('alt' => 'User Image')); ?>

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
							<li><a href="#" title="Sign up" class="sign_up" style="color: #f1f9f5 !important;">Sign Up</a><li>
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

<section class="banner" style="background-position: center !important;">
	<div class="container">
		<div class="row">
			<div class="col-6">
				<div class="banner-cont">
					<h5>About</h5>
					<h2>National Cooperative Database</h2>
					<p>The primary goal and purpose of building National Cooperative<br>Database is to have Information based Decision Support System. </p>
					<ul>
						<li>Taking the cooperatives movement in the country to a new level<br>by strengthening grassroots-level reach and coordination of activities<br>across sectors.</li>
						<li>Promoting cooperatives-based inclusive and sustainable model of<br>economic development. </li>
						<li>Easing operational processes.</li>
					</ul>
					<h3>Technical and Administrative Help Desk</h3>
					<div class="tas">
						<span>
							<!-- <img src="images/ts.png" alt="img" /> -->
							<?php echo $this->Html->image('image2/ts.png', array('alt' => 'User Image')); ?>
						
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
					<?php echo $this->Html->image('image2/mini1.jpg', array('alt' => 'User Image')); ?>

						<!-- <img src="images/mini1.jpg" alt="img" /> -->
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
					<?php echo $this->Html->image('image2/mini2.jpg', array('alt' => 'User Image')); ?>

						<!-- <img src="images/mini2.jpg" alt="img" /> -->
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
					<?php echo $this->Html->image('image2/mini3.jpg', array('alt' => 'User Image')); ?>

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
<section class="ourBusiness">
	<div class="ourBusinessBg">
		<div class="parallaxBg" style=""></div>
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
                                    <div  class="maps-height-box" id="map"> </div>
                                </span> 
                            </div>
                        </div>
                    </div>
                </div>
			</div>
			<div class="col-lg-6">
				<div class="map_tabs">
					<div class="tabs">
					  <ul id="tabs-nav">
					    <li><a href="#tab1"><?php echo $this->Html->image('image2/tb1.png', array('alt' => 'User Image')); ?> PACS</a></li>
					    <li><a href="#tab2"><?php echo $this->Html->image('image2/tb2.png', array('alt' => 'User Image')); ?>Daily Cooperation</a></li>
					    <li><a href="#tab3"><?php echo $this->Html->image('image2/tb3.png', array('alt' => 'User Image')); ?>Fishery Cooperation</a></li>
					  </ul> <!-- END tabs-nav -->
					  <div id="tabs-content">
					    <div id="tab1" class="tab-content">
					      <h2>PACS</h2>
					      <ul>
					      	<li>No. of PACS <span>12877</span></li>
					      	<li>State Milk Union <span>25</span></li>
					      	<li>District Milk Union <span>201</span></li>
					      </ul>
					    </div>
					    <div id="tab2" class="tab-content">
					      <h2>Dairy Cooperation</h2>
					      <ul>
					      	<li>No. of PACS <span>12877</span></li>
					      	<li>State Milk Union <span>25</span></li>
					      	<li>District Milk Union <span>201</span></li>
					      </ul>
					    </div>
					    <div id="tab3" class="tab-content">
					      <h2>Fishery Cooperation</h2>
					      <ul>
					      	<li>No. of PACS <span>12877</span></li>
					      	<li>State Milk Union <span>25</span></li>
					      	<li>District Milk Union <span>201</span></li>
					      </ul>
					    </div>
					    <div id="tab4" class="tab-content">
					      <h2>Jay</h2>
					      <p>"I don't care if she's my cousin or not, I'm gonna knock those boots again tonight."</p>
					    </div>
					  </div> <!-- END tabs-content -->
					</div> <!-- END tabs -->
				</div>
			</div>
		</div>
	</div>
</section>
<!-- Our Business Section End -->

<!-- Latest Updates Section Start -->
<section class="latestUpdates">
	<div class="container">
		<div class="newsWrap d-flex flex-wrap">
			<div class="col-4">
				<div class="titleLine">
					<h2>Important Links</h2>
				</div>
				<div class="newsSliderArrows d-flex align-items-start justify-content-end">
					<div class="newsSlider-next sliderArrow blueArrow"><i class="fa fa-angle-up arrow-color" aria-hidden="true"></i></div>
					<div class="newsSlider-prev sliderArrow blueArrow"><i class="fa fa-angle-down arrow-color" aria-hidden="true"></i></div>
				</div>
				<div class="newsSliderWrap">
					<div class="swiper newsSlider">
						<div class="swiper-wrapper">
							<div class="swiper-slide">
								<div class="newsBox newsBoxListStyle">
									<div class="imgWrap">
										<a  title="Celebrating One Nation One Grid One Frequency under Azadi Ka Amrit Mahotsav"><?php echo $this->Html->image('image2/news-img1.jpg', array('alt' => 'Celebrating One Nation One Grid One Frequency under Azadi Ka Amrit Mahotsav')); ?></a>
									</div>
									<div class="newsBoxWrap">
										<a  href="https://doe.gov.in/Advances-to-Government-Servants" target="_blank">National Cooperative Database</a>
									</div>
								</div>
							</div>
							<div class="swiper-slide">
								<div class="newsBox newsBoxListStyle">
									<div class="imgWrap">
										<a  title=" signs MoU with NFCH foreducation of violence affected children"><?php echo $this->Html->image('image2/news-img2.jpg', array('alt' => 'signs MoU with NFCH foreducation of violence affected children')); ?></a>
									</div>
									<div class="newsBoxWrap">
										<a href="https://doe.gov.in/Air-Travel-Instruction" target="_blank">National Council for Cooperative Training (NCCT)</a>
									</div>
								</div>
							</div>
							<div class="swiper-slide">
								<div class="newsBox newsBoxListStyle">
									<div class="imgWrap">
										<a  title="Celebrating One Nation One Grid One Frequency under Azadi Ka Amrit Mahotsav"><?php echo $this->Html->image('image2/news-img1.jpg', array('alt' => 'Celebrating One Nation One Grid One Frequency under Azadi Ka Amrit Mahotsav')); ?></a>
									</div>
									<div class="newsBoxWrap">
										<a href="https://doe.gov.in/House-Rent-Allowance" target="_blank">National Council Cooperative Development Corporation (NCDC)</a>
									</div>
								</div>
							</div>
							<div class="swiper-slide">
								<div class="newsBox newsBoxListStyle">
									<div class="imgWrap">
										<a  title=" signs MoU with NFCH foreducation of violence affected children">
										<?php echo $this->Html->image('image2/news-img4.jpg', array('alt' => ' signs MoU with NFCH foreducation of violence affected children')); ?>
										
										</a>
									</div>
									<div class="newsBoxWrap">
										<a href="https://doe.gov.in/Appraisal-%26-Approval" target="_blank">National Cooperative Union of India (NCUI)</a>
									</div>
								</div>
							</div>
							<div class="swiper-slide">
								<div class="newsBox newsBoxListStyle">
									<div class="imgWrap">
										<a  title=" signs MoU with NFCH foreducation of violence affected children">
										<?php echo $this->Html->image('image2/news-img5.jpg', array('alt' => 'signs MoU with NFCH foreducation of violence affected children')); ?>

											<!-- <img src="images/news-img5.jpg" alt=" signs MoU with NFCH foreducation of violence affected children"> -->
										</a>
									</div>
									<div class="newsBoxWrap">
										<a href="https://doe.gov.in/CGEGI" target="_blank">Multi-State Cooperative Societies (MSCS)</a>
									</div>
								</div>
							</div>
							<div class="swiper-slide">
								<div class="newsBox newsBoxListStyle">
									<div class="imgWrap">
										<a  title=" signs MoU with NFCH foreducation of violence affected children">
											<!-- <img src="images/news-img6.jpg" alt=" signs MoU with NFCH foreducation of violence affected children"> -->
										<?php echo $this->Html->image('image2/news-img6.jpg', array('alt' => 'igns MoU with NFCH foreducation of violence affected children')); ?>

										</a>
									</div>
									<div class="newsBoxWrap">
										<a href="https://doe.gov.in/Expenditure-Management-Related-Orders" target="_blank">Vaikunth Mehta National Institute of Cooperative Management</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="sectionLinks d-flex flex-wrap justify-content-md-end">
			<a href="#" class="arrowLink blueLink" title="View All News">View All<i class="fa fa-angle-right" aria-hidden="true"></i></a>
		</div>
			</div><!-- ./col-4 -->
			<div class="col-4 project_scope">
				<div class="titleLine">
					<h2>Project Scope</h2>
				</div>
				<div class="newsSliderArrows d-flex align-items-start justify-content-end">
					<div class="newsSlider-next2 sliderArrow blueArrow"><i class="fa fa-angle-up arrow-color" aria-hidden="true"></i></div>
					<div class="newsSlider-prev2 sliderArrow blueArrow"><i class="fa fa-angle-down arrow-color" aria-hidden="true"></i></div>
				</div>
				<div class="newsSliderWrap">
					<div class="swiper newsSlider2">
						<div class="swiper-wrapper">
							<div class="swiper-slide">
								<div class="newsBox newsBoxListStyle">
									<div class="newsBoxWrap">
										<a href="#" >Building a multitiered database will contain information    pertaining to national, state, district, block and primary cooperative level.</a>
									</div>
								</div>
							</div>
							<div class="swiper-slide">
								<div class="newsBox newsBoxListStyle">
									<div class="newsBoxWrap">
										<a href="#" >Building a responsive web application portal which will interface with the proposed database.</a>
									</div>
								</div>
							</div>
							<div class="swiper-slide">
								<div class="newsBox newsBoxListStyle">
									<div class="newsBoxWrap">
										<a href="#" >National Council Cooperative Development Corporation (NCDC)</a>
									</div>
								</div>
							</div>
							<div class="swiper-slide">
								<div class="newsBox newsBoxListStyle">
									<div class="newsBoxWrap">
										<a href="#" >Enabling system access using web portal as well as mobile   application.</a>
									</div>
								</div>
							</div>
							<div class="swiper-slide">
								<div class="newsBox newsBoxListStyle">
									<div class="newsBoxWrap">
										<a href="#" >In Phase-1 of the project three priority sectors namely OACS, Dairy and Fishery shall be covered.</a>
									</div>
								</div>
							</div>
							<div class="swiper-slide">
								<div class="newsBox newsBoxListStyle">
									<div class="newsBoxWrap">
										<a href="#" >Enabling system access using web portal as well as mobile application.</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="sectionLinks d-flex flex-wrap justify-content-md-end">
			<a href="#" class="arrowLink blueLink" title="View All News">View All<i class="fa fa-angle-right" aria-hidden="true"></i></a>
		</div>
			</div><!-- ./col-4 -->
			<div class="col-4 objectivesbox">
				<div class="titleLine">
					<h2>Objectives</h2>
				</div>
				<div class="newsSliderArrows d-flex align-items-start justify-content-end">
					<div class="newsSlider-next3 sliderArrow blueArrow"><i class="fa fa-angle-up arrow-color" aria-hidden="true"></i></div>
					<div class="newsSlider-prev3 sliderArrow blueArrow"><i class="fa fa-angle-down arrow-color" aria-hidden="true"></i></div>
				</div>
				<div class="newsSliderWrap">
					<div class="swiper newsSlider3">
						<div class="swiper-wrapper">
							<div class="swiper-slide">
								<div class="newsBox newsBoxListStyle">
									<div class="newsBoxWrap">
										<a href="#" >Capture data on cooperatives across sectors and regions</a>
									</div>
								</div>
							</div>
							<div class="swiper-slide">
								<div class="newsBox newsBoxListStyle">
									<div class="newsBoxWrap">
										<a href="#" >Identifying gaps in existing processes through analysis of data and taking corrective measures</a>
									</div>
								</div>
							</div>
							<div class="swiper-slide">
								<div class="newsBox newsBoxListStyle">
									<div class="newsBoxWrap">
										<a href="#" >Improved governance with higher visibility and transparency</a>
									</div>
								</div>
							</div>
							<div class="swiper-slide">
								<div class="newsBox newsBoxListStyle">
									<div class="newsBoxWrap">
										<a href="#" >Aid in policy making and formulation of government schemes and other initiatives backed by data</a>
									</div>
								</div>
							</div>
							<div class="swiper-slide">
								<div class="newsBox newsBoxListStyle">
									<div class="newsBoxWrap">
										<a href="#" >Exploring untapped opportunities using technologies such as geotagging, mobile apps, data analytics and AI.</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="sectionLinks d-flex flex-wrap justify-content-md-end">
			<a href="#" class="arrowLink blueLink" title="View All News">View All<i class="fa fa-angle-right" aria-hidden="true"></i></a>
		</div>
			</div><!-- ./col-4 -->
		</div>
	</div>
</section>
<!-- Latest Updates Section End -->

<!-- Our Business Section Start -->
<div class="white-bg"></div>
<section class="ourBusiness info">
	<div class="ourBusinessBg">
		<div class="parallaxBg" style="background-image:<?=$this->request->webroot ?>images/image2/info.jpg;"></div>
	</div>
	<div class="container">
		<div class="row align-items-center">
			<div class="col-lg-6">
				<div class="info_sec d-flex flex-column justify-content-center p-50">
					<h3>Information NCD collects and its Methodology</h3>
					
				</div>
			</div>
			<div class="col-lg-6">
				<div class="info_sec info_s">
					<h5>What data will be captured?</h5>
					<ul>
						<li>Identification-related data</li>
						<li>Society particulars</li>
						<li>Published financial data</li>
						<li>Operative Details</li>
						<li>Infrastructure and employment details</li>
						<li>Products, services and schemes</li>
						<li>Status on use of ICT</li>
						<li>Related data from other domains/organizations</li>
					</ul>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="info_sec info_s">
					<h5>What will the primary and secondary sources of data?</h5>
					<h6>Primary data sources</h6>
					<ul>
						<li>Primary cooperative societies</li>
						<li>District unions/federations</li>
						<li>State cooperatives, national cooperatives</li>
					</ul>
					<h6>Secondary data sources</h6>
					<ul>
						<li>State federations, national federations</li>
						<li>Government institutions such as Central RCS, State RCS, NABARD, etc.</li>
						<li>Other related government departments</li>
						<li>Research and academic institutions</li>
					</ul>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="info_sec info_s">
					<h5>How will the data be captured?</h5>
					<p>There will be 3 ways in which the data will be fed<br>into the database portal:</p>
					<ul>
						<li>Manual data entry using web forms</li>
						<li>Data migration utility</li>
						<ul class="sub_inner">
							<li>API handshaking approach</li>
							<li>File upload process</li>
						</ul>
					</ul>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- Our Business Section End -->

<section class="media_gallery">
	<div class="container">
		<div class="row">
			<div class="col-4">
				<div class="info_sec med_t">
					<div class="d-flex justify-content-between align-items-baseline">
						<h3>Photos</h3>
						<span class="view-all">View All</span>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="ps ps0">
							<?php echo $this->Html->image('image2/gall1.jpg', array('alt' => 'img')); ?>

								<!-- <img src="images/gall1.jpg" alt="img" /> -->
								<button>
									<!-- <img src="images/search.png" alt="img" /> -->
									<?php echo $this->Html->image('image2/search.png', array('alt' => 'img')); ?>
								
								</button>
							</div>
						</div>
						<div class="col-md-6">
							<div class="row">
								<div class="col-md-12">
									<div class="ps ps2 ps0">
									<?php echo $this->Html->image('image2/gall2.jpg', array('alt' => 'img')); ?>
										<!-- <img src="images/gall2.jpg" alt="img" /> -->
										<button>
											<!-- <img src="images/search.png" alt="img" /> -->
											<?php echo $this->Html->image('image2/search.png', array('alt' => 'img')); ?>
										</button>
									</div>
								</div>
								<div class="col-md-12">
									<div class="ps ps2 ps0">
									<?php echo $this->Html->image('image2/gall3.jpg', array('alt' => 'img')); ?>

										<!-- <img src="images/gall3.jpg" alt="img" /> -->
										<button>
										<?php echo $this->Html->image('image2/search.png', array('alt' => 'img')); ?>
											<!-- <img src="images/search.png" alt="img" /> -->
										</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-4">
				<div class="info_sec med_t">
					<div class="d-flex justify-content-between align-items-baseline">
						<h3>Videos</h3>
						<span class="view-all">View All</span>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="ps">
								<iframe width="100%" height="300" src="https://www.youtube.com/embed/RbsgGt15zHU" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-4">
				<div class="info_sec med_t">
					<h3>Twitter</h3>
					<div class="row">
						<div class="col-md-12">
							<div class="ps">
								<div class="ibContentBox"><a class="twitter-timeline" href="https://twitter.com/MinOfCooperatn?ref_src=twsrc%5Egoogle%7Ctwcamp%5Eserp%7Ctwgr%5Eauthor" data-dnt="true" data-widget-id="478522991832092674">@MinOfCooperatn</a>
								<script id="twitter-wjs" src="https://platform.twitter.com/widgets.js"></script><script>window.twttr = (function(d, s, id) {
								  var js, fjs = d.getElementsByTagName(s)[0],
								    t = window.twttr || {};
								  if (d.getElementById(id)) return t;
								  js = d.createElement(s);
								  js.id = id;
								  js.src = "https://platform.twitter.com/widgets.js";
								  fjs.parentNode.insertBefore(js, fjs);

								  t._e = [];
								  t.ready = function(f) {
								    t._e.push(f);
								  };

								  return t;
								}(document, "script", "twitter-wjs"));</script>


								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- Logo Slider Section Start -->
<section class="logosSection">
	<div class="container">
		<div class="logosSliderWrap">
			<div class="swiper logosSlider">
				<div class="swiper-wrapper">
					<div class="swiper-slide">
						<div class="logoWrap"><span><a href="https://india.gov.in/" target="_blank">
						<?php echo $this->Html->image('image2/ext-logo1.png', array('title' => 'https://india.gov.in/, The National Portal of India : External website that opens in a new window')); ?>
							<!-- <img alt="" src="images/ext-logo1.png" title="https://india.gov.in/, The National Portal of India : External website that opens in a new window" />  -->
						</a> </span></div>
					</div>

					<div class="swiper-slide">
						<div class="logoWrap"><span><a href="https://amritmahotsav.nic.in/" target="_blank">
						<?php echo $this->Html->image('image2/ext-logo2.png', array('title' => 'https://amritmahotsav.nic.in/, External website that opens in a new window')); ?>

							<!-- <img alt="External website that opens in a new window" src="images/ext-logo2.png" title="https://amritmahotsav.nic.in/, External website that opens in a new window" />  -->
						</a> </span></div>
					</div>

					

					<div class="swiper-slide">
						<div class="logoWrap"><span><a href="#" target="_blank">
						<?php echo $this->Html->image('image2/ext-logo4.png', array('title' => '#, External website that opens in a new window')); ?>

							<!-- <img alt="" src="images/ext-logo4.png" title="#, External website that opens in a new window" />  -->
						</a> </span></div>
					</div>
					<div class="swiper-slide">
						<div class="logoWrap"><span><a href="https://swachhbharat.mygov.in/" target="_blank">
						<?php echo $this->Html->image('image2/ext-logo3.png', array('title' => 'https://swachhbharat.mygov.in/, External website that opens in a new window')); ?>

							<!-- <img alt="" src="images/ext-logo3.png" title="https://swachhbharat.mygov.in/, External website that opens in a new window" /> -->
						 </a> </span></div>
					</div>

					<div class="swiper-slide">
						<div class="logoWrap"><span><a href="#" target="_blank">
						<?php echo $this->Html->image('image2/ext-logo5.png', array('title' => '#, External website that opens in a new window')); ?>

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
<!-- <script type="text/javascript" src="js/functions.js"></script>
<script type="text/javascript" src="js/general.js"></script>
<script type="text/javascript" src="js/indiamap.js"></script>
<script type="text/javascript" src="js/countrymap.js"></script> -->

<script src="<?=$this->request->webroot?>frontend/js2/functions.js"></script>
<script src="<?=$this->request->webroot?>frontend/js2/general.js"></script>
<script src="<?=$this->request->webroot?>frontend/js2/indiamap.js"></script>
<script src="<?=$this->request->webroot?>frontend/js2/countrymap.js"></script>



</body>
</html>