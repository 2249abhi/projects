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
    .page-link {
    position: relative;
    display: block;
    color: rgba(107,22,81,0.9);
    text-decoration: none;
    background-color: #fff;
    border: 1px solid #dee2e6;
    transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
}




.banner-i {
    background: url('../../webroot/img/image2/0.jpg');
    background-size: 100%;
    background-repeat: no-repeat;
    width: 100%;
    position: relative;
    overflow: hidden;
}
section.banner-i::before {
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
                                <a href="#" title="Logo">                                <?php echo $this->Html->image('image2/header-logo.png', array('alt' => 'img')); ?>
</a>
                            </div>
                        </div>
                        <div class=" col-4 col-md-4">
                            <button class="toglesearch" id="toglesearch"> <?php echo $this->Html->image('image2/search.png', array('alt' => 'img')); ?></button>
                            <div class="searchBox1">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="" placeholder="Search" />
                                    <button> <?php echo $this->Html->image('image2/search.png', array('alt' => 'img')); ?></button>
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
    <section class="banner-i" style="padding-top: 1%;">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="banner-cont">
                   
                        <h2>Notification of Cooperative Database</h2>
                        <p>The primary goal and purpose of building National Cooperative<br>Database is to have Information based Decision Support System. </p>
                        <ul>
                            <li>Taking the cooperatives movement in the country to a new level by strengthening grassroots </li>
                            
                        </ul>
                        <h3>Technical and Administrative Help Desk</h3>
                        <div class="tas">
                            <span>  <?php echo $this->Html->image('image2/ts.png', array('alt' => 'img')); ?></span>
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
                        <div class="mins">  <?php echo $this->Html->image('image2/mini1.jpg', array('alt' => 'img')); ?></div>
                        <div class="mins_cont">
                            <h2>Shri Narendra Modi</h2>
                            <p>Prime Minister</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 border-right">
                    <div class="mis_sc">
                        <div class="mins"> <?php echo $this->Html->image('image2/mini2.jpg', array('alt' => 'img')); ?></div>
                        <div class="mins_cont">
                            <h2>Shri Amit Shah</h2>
                            <p>Union Minister</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mis_sc">
                        <div class="mins">   <?php echo $this->Html->image('image2/mini3.jpg', array('alt' => 'img')); ?></div>
                        <div class="mins_cont">
                            <h2>Shri B.L Verma</h2>
                            <p>Minister of State</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    





<!--Main layout-->
<div class="container" style="margin-top: 5%;">
    <!--Section: News of the day-->
    <section class="border-bottom pb-4 mb-5">
      <div class="row gx-5">
        <div class="col-md-6 mb-4">
          <div class="bg-image hover-overlay ripple shadow-2-strong rounded-5" data-mdb-ripple-color="light">
            
          <?php echo $this->Html->image('image2/4.jpg', array('class' => 'img-fluid')); ?>
          <!-- <img src="images/4.jpg" class="img-fluid" /> -->
            <a href="#!">
              <div class="mask" style="background-color: rgba(251, 251, 251, 0.15);"></div>
            </a>
          </div>
        </div>
  
        <div class="col-md-6 mb-4">
          <span class="badge bg-danger px-2 py-1 shadow-1-strong mb-3">News of the day</span>
          <h4><strong style="color: rgba(107,22,81,0.9);">DA&FW ORGANISATION</strong></h4>
          <p class="text-muted">
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Facilis consequatur
            eligendi quisquam doloremque vero ex debitis veritatis placeat unde animi laborum
            sapiente illo possimus, commodi dignissimos obcaecati illum maiores corporis.
          </p>
          <button type="button" class="btn btn-primary" style="background: rgba(107,22,81,0.9); border-color: rgba(107,22,81,0.9); font-size: 1.1em;">Read more</button>
        </div>
      </div>
    </section>
    <!--Section: News of the day-->
  
    <!--Section: Content-->
    <section>
      <div class="row gx-lg-5">
        <div class="col-lg-4 col-md-12 mb-4 mb-lg-0">
          <!-- News block -->
          <div>
            <!-- Featured image -->
            <div class="bg-image hover-overlay shadow-1-strong ripple rounded-5 mb-4"
              data-mdb-ripple-color="light">
          <?php echo $this->Html->image('image2/working-hothouse.jpg', array('class' => 'img-fluid')); ?>

              <!-- <img src="images/working-hothouse.jpg" class="img-fluid" /> -->
              <a href="#!">
                <div class="mask" style="background-color: rgba(251, 251, 251, 0.15);"></div>
              </a>
            </div>
  
            <!-- Article data -->
            <div class="row mb-3">
              <div class="col-6">
                <a href="" class="text-info">
                  <i class="fas fa-plane"></i>
                  Travels
                </a>
              </div>
  
              <div class="col-6 text-end">
                <u> 15.07.2020</u>
              </div>
            </div>
  
            <!-- Article title and description -->
            <a href="" class="text-dark">
              <h5>This is title of the news</h5>
  
              <p>
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Odit, iste aliquid. Sed
                id nihil magni, sint vero provident esse numquam perferendis ducimus dicta
                adipisci iusto nam temporibus modi animi laboriosam?
              </p>
            </a>
  
            <hr />
  
            <!-- News -->
            <a href="" class="text-dark">
              <div class="row mb-4 border-bottom pb-2">
                <div class="col-3">
                  <img src="https://mdbcdn.b-cdn.net/img/new/standard/city/041.webp"
                    class="img-fluid shadow-1-strong rounded" alt="Hollywood Sign on The Hill" />
                </div>
  
                <div class="col-9">
                  <p class="mb-2"><strong>Lorem ipsum dolor sit amet</strong></p>
                  <p>
                    <u> 15.07.2020</u>
                  </p>
                </div>
              </div>
            </a>
  
            <!-- News -->
            <a href="" class="text-dark">
              <div class="row mb-4 border-bottom pb-2">
                <div class="col-3">
                  <img src="https://mdbcdn.b-cdn.net/img/new/standard/city/042.webp"
                    class="img-fluid shadow-1-strong rounded" alt="Palm Springs Road" />
                </div>
  
                <div class="col-9">
                  <p class="mb-2"><strong>Lorem ipsum dolor sit amet</strong></p>
                  <p>
                    <u> 15.07.2020</u>
                  </p>
                </div>
              </div>
            </a>
  
            <!-- News -->
            <a href="" class="text-dark">
              <div class="row mb-4 border-bottom pb-2">
                <div class="col-3">
                  <img src="https://mdbcdn.b-cdn.net/img/new/standard/city/043.webp"
                    class="img-fluid shadow-1-strong rounded" alt="Los Angeles Skyscrapers" />
                </div>
  
                <div class="col-9">
                  <p class="mb-2"><strong>Lorem ipsum dolor sit amet</strong></p>
                  <p>
                    <u> 15.07.2020</u>
                  </p>
                </div>
              </div>
            </a>
  
            <!-- News -->
            <a href="" class="text-dark">
              <div class="row mb-4 border-bottom pb-2">
                <div class="col-3">
                  <img src="https://mdbcdn.b-cdn.net/img/new/standard/city/044.webp"
                    class="img-fluid shadow-1-strong rounded" alt="Skyscrapers" />
                </div>
  
                <div class="col-9">
                  <p class="mb-2"><strong>Lorem ipsum dolor sit amet</strong></p>
                  <p>
                    <u> 15.07.2020</u>
                  </p>
                </div>
              </div>
            </a>
          </div>
          <!-- News block -->
        </div>
  
        <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
          <!-- News block -->
          <div>
            <!-- Featured image -->
            <div class="bg-image hover-overlay shadow-1-strong rounded-5 ripple mb-4"
              data-mdb-ripple-color="light">
              <!-- <img src="images/6.avif" class="img-fluid"
                alt="Brooklyn Bridge" /> -->
          <?php echo $this->Html->image('image2/6.avif', array('class' => 'img-fluid', 'alt' => 'Brooklyn Bridge')); ?>

              <a href="#!">
                <div class="mask" style="background-color: rgba(251, 251, 251, 0.15);"></div>
              </a>
            </div>
  
            <!-- Article data -->
            <div class="row mb-3">
              <div class="col-6">
                <a href="" class="text-danger">
                  <i class="fas fa-chart-pie"></i>
                  Business
                </a>
              </div>
  
              <div class="col-6 text-end">
                <u> 15.07.2020</u>
              </div>
            </div>
  
            <!-- Article title and description -->
            <a href="" class="text-dark">
              <h5>This is title of the news</h5>
  
              <p>
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Odit, iste aliquid. Sed
                id nihil magni, sint vero provident esse numquam perferendis ducimus dicta
                adipisci iusto nam temporibus modi animi laboriosam?
              </p>
            </a>
  
            <hr />
  
            <!-- News -->
            <a href="" class="text-dark">
              <div class="row mb-4 border-bottom pb-2">
                <div class="col-3">
                <?php echo $this->Html->image('image2/7.avif', array('class' => 'img-fluid shadow-1-strong rounded', 'alt' => 'Five Lands National Park')); ?>

                  <!-- <img src="images/7.avif"
                    class="img-fluid shadow-1-strong rounded" alt="Five Lands National Park" /> -->
                </div>
  
                <div class="col-9">
                  <p class="mb-2"><strong>Lorem ipsum dolor sit amet</strong></p>
                  <p>
                    <u> 15.07.2020</u>
                  </p>
                </div>
              </div>
            </a>
  
            <!-- News -->
            <a href="" class="text-dark">
              <div class="row mb-4 border-bottom pb-2">
                <div class="col-3">
                  <img src="https://mdbcdn.b-cdn.net/img/new/standard/city/032.webp"
                    class="img-fluid shadow-1-strong rounded" alt="Paris - Eiffel Tower" />
                </div>
  
                <div class="col-9">
                  <p class="mb-2"><strong>Lorem ipsum dolor sit amet</strong></p>
                  <p>
                    <u> 15.07.2020</u>
                  </p>
                </div>
              </div>
            </a>
  
            <!-- News -->
            <a href="" class="text-dark">
              <div class="row mb-4 border-bottom pb-2">
                <div class="col-3">
                  <img src="https://mdbcdn.b-cdn.net/img/new/standard/city/033.webp"
                    class="img-fluid shadow-1-strong rounded" alt="Louvre" />
                </div>
  
                <div class="col-9">
                  <p class="mb-2"><strong>Lorem ipsum dolor sit amet</strong></p>
                  <p>
                    <u> 15.07.2020</u>
                  </p>
                </div>
              </div>
            </a>
  
            <!-- News -->
            <a href="" class="text-dark">
              <div class="row mb-4 border-bottom pb-2">
                <div class="col-3">
                  <img src="https://mdbcdn.b-cdn.net/img/new/standard/city/034.webp"
                    class="img-fluid shadow-1-strong rounded" alt="Times Square" />
                </div>
  
                <div class="col-9">
                  <p class="mb-2"><strong>Lorem ipsum dolor sit amet</strong></p>
                  <p>
                    <u> 15.07.2020</u>
                  </p>
                </div>
              </div>
            </a>
          </div>
          <!-- News block -->
        </div>
  
        <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
          <!-- News block -->
          <div>
            <!-- Featured image -->
            <div class="bg-image hover-overlay shadow-1-strong rounded-5 ripple mb-4"
              data-mdb-ripple-color="light">
              <?php echo $this->Html->image('image2/7.avif', array('class' => 'img-fluid', 'alt' => 'Golden Gate National Recreation Area')); ?>

              <!-- <img src="images/7.avif" class="img-fluid"
                alt="Golden Gate National Recreation Area" /> -->
              <a href="#!">
                <div class="mask" style="background-color: rgba(251, 251, 251, 0.15);"></div>
              </a>
            </div>
  
            <!-- Article data -->
            <div class="row mb-3">
              <div class="col-6">
                <a href="" class="text-warning">
                  <i class="fas fa-code"></i>
                  Technology
                </a>
              </div>
  
              <div class="col-6 text-end">
                <u> 15.07.2020</u>
              </div>
            </div>
  
            <!-- Article title and description -->
            <a href="" class="text-dark">
              <h5>This is title of the news</h5>
  
              <p>
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Odit, iste aliquid. Sed
                id nihil magni, sint vero provident esse numquam perferendis ducimus dicta
                adipisci iusto nam temporibus modi animi laboriosam?
              </p>
            </a>
  
            <hr />
  
            <!-- News -->
            <a href="" class="text-dark">
              <div class="row mb-4 border-bottom pb-2">
                <div class="col-3">
                  <img src="https://mdbcdn.b-cdn.net/img/new/standard/city/011.webp"
                    class="img-fluid shadow-1-strong rounded" alt="Brooklyn Bridge" />
                </div>
  
                <div class="col-9">
                  <p class="mb-2"><strong>Lorem ipsum dolor sit amet</strong></p>
                  <p>
                    <u> 15.07.2020</u>
                  </p>
                </div>
              </div>
            </a>
  
            <!-- News -->
            <a href="" class="text-dark">
              <div class="row mb-4 border-bottom pb-2">
                <div class="col-3">
                  <img src="https://mdbcdn.b-cdn.net/img/new/standard/city/012.webp"
                    class="img-fluid shadow-1-strong rounded" alt="Hamilton Park" />
                </div>
  
                <div class="col-9">
                  <p class="mb-2"><strong>Lorem ipsum dolor sit amet</strong></p>
                  <p>
                    <u> 15.07.2020</u>
                  </p>
                </div>
              </div>
            </a>
  
            <!-- News -->
            <a href="" class="text-dark">
              <div class="row mb-4 border-bottom pb-2">
                <div class="col-3">
                  <img src="https://mdbcdn.b-cdn.net/img/new/standard/city/013.webp"
                    class="img-fluid shadow-1-strong rounded" alt="Perdana Botanical Garden Kuala Lumpur" />
                </div>
  
                <div class="col-9">
                  <p class="mb-2"><strong>Lorem ipsum dolor sit amet</strong></p>
                  <p>
                    <u> 15.07.2020</u>
                  </p>
                </div>
              </div>
            </a>
  
            <!-- News -->
            <a href="" class="text-dark">
              <div class="row mb-4 border-bottom pb-2">
                <div class="col-3">
                  <img src="https://mdbcdn.b-cdn.net/img/new/standard/city/014.webp"
                    class="img-fluid shadow-1-strong rounded" alt="Perdana Botanical Garden" />
                </div>
  
                <div class="col-9">
                  <p class="mb-2"><strong>Lorem ipsum dolor sit amet</strong></p>
                  <p>
                    <u> 15.07.2020</u>
                  </p>
                </div>
              </div>
            </a>
          </div>
          <!-- News block -->
        </div>
      </div>
    </section>
    <!--Section: Content-->
  
    <!-- Pagination -->
    <nav class="my-4" aria-label="...">
      <ul class="pagination pagination-circle justify-content-center">
        <li class="page-item">
          <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
        </li>
        <li class="page-item"><a class="page-link" href="#">1</a></li>
        <li class="page-item active" aria-current="page">
          <a class="page-link" href="#">2 <span class="sr-only">(current)</span></a>
        </li>
        <li class="page-item"><a class="page-link" href="#">3</a></li>
        <li class="page-item">
          <a class="page-link" href="#">Next</a>
        </li>
      </ul>
    </nav>
  </div>
  <!--Main layout-->

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
                                <?php echo $this->Html->image('image2/search.png', array('alt' => 'img')); ?>
								<button> <?php echo $this->Html->image('image2/search.png', array('alt' => 'img')); ?></button>
							</div>
						</div>
						<div class="col-md-6">
							<div class="row">
								<div class="col-md-12">
									<div class="ps ps2 ps0">
                                <?php echo $this->Html->image('image2/gall2.jpg', array('alt' => 'img')); ?>

										<!-- <img src="images/gall2.jpg" alt="img" /> -->
										<button> <?php echo $this->Html->image('image2/search.png', array('alt' => 'img')); ?></button>
									</div>
								</div>
								<div class="col-md-12">
									<div class="ps ps2 ps0">
                                <?php echo $this->Html->image('image2/gall3.jpg', array('alt' => 'img')); ?>

										<!-- <img src="images/gall3.jpg" alt="img" /> -->
										<button> <?php echo $this->Html->image('image2/search.png', array('alt' => 'img')); ?></button>
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
<!-- Logo Slider Section end -->

<!-- Logo Slider Section Start -->
<section class="logosSection" style="margin-top: 3%;">
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
