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
									<li><a href="#" title="Notification">Notification</a></li>
									<li><a href="#" title="Training and Demonstration">Training and Demonstration</a></li>
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
