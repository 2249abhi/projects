<!--<marquee width="60%" direction="right" height="100px" style="color:red">
<h4>The portal will be down for maintenance between 6:00 PM to 9:00 PM . Sorry for inconvenience ! </h4>
</marquee>
<br> -->
<?php
//exit; 
?>
<div class="banner-section">
  <div class="container">
    <div class="row">
      <div class="col-sm-7">
        <div class="slidetop">
          <div id="banner-slider-xs" class="owl-carousel owl-theme">
            <div class="item"> <img src="<?=$this->request->webroot?>frontend/img/banner1.jpg" alt="" class=""/>
              <div class="carousel-caption d-none d-md-block" data-wow-duration="2s"> </div>
            </div>
            <div class="item"> <img src="<?=$this->request->webroot?>frontend/img/banner2.jpg" class="d-block w-100" alt=""/>
              <div class="carousel-caption d-none d-md-block" data-wow-duration="2s"> </div>
            </div>
           
          </div>
        </div>
		
		<div class="ministryrow">
		<ul>
		<li>
			<div class="image-card">
			<div class="image-box"><img alt="prime minister" src="<?=$this->request->webroot?>frontend/img/modi.png"></div>
			<h6>Shri Narendra Modi</h6>
			<p>Prime Minister</p>
			</div>
		</li>
		<li>
			<div class="image-card">
			<div class="image-box"><img alt="prime minister" src="<?=$this->request->webroot?>frontend/img/amit.png"></div>
			<h6>Shri Amit Shah</h6>
			<p>Union Minister</p>
			</div>
		</li>
		<li>
			<div class="image-card">
			<div class="image-box"><img alt="prime minister" src="<?=$this->request->webroot?>frontend/img/bl.png"></div>
			<h6>Shri B.L Verma</h6>
			<p>Minister of State</p>
			</div>
		</li>
		
		</ul>
		
		
		</div>
		
		
		
      </div>
      <div class="col-sm-5 blockc-cont">
	  
        <div class="row">
          <div class="about-sectr">
		  
      <h3> National Cooperative Database</h3>
        <p>The primary goal and purpose of building National Cooperative Database is to have Information based Decision Support  System. </p>
      <ul>
      <li>Taking the cooperatives movement in the country to a new level by strengthening  grassroots-level reach  and coordination of activities across sectors.</li>
      <li>Promoting cooperatives-based inclusive and sustainable model of economic development. </li>
	  <li>Easing operational processes.</li>      
      </ul>
       <div><h5>
	   Technical and Administrative Help Desk
	   </h5>
	   <p>Technical Helpdesk<br>
			ncd.tech-coop@gov.in,Tele No: 011 20862616 <br>
			For Administrative related queries
			ncd.dpt-coop@gov.in</p>
	   </div> 
       <div class="alert alert-warning" role="alert">
	   <h5 class="blink">Union Budget 2023-24</h5>
		Important measures have been announced for cooperatives in Union Budget 2023-24. The related contents of Budget Speech are placed herewith for every one's attention.
		<br><strong><?php echo $this->Html->link('Hindi', '/files/download/Budget Hindi Final.pdf',['download'=>'Budget Hindi Final.pdf']); ?> 	| 	
           <?php echo $this->Html->link('English', '/files/download/Budget English Final.pdf',['download'=>'Budget English Final.pdf']); ?></strong>
		</div>	   
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Navbar End -->
<!-- Carousel Start -->
<div class="container-fluid sb scope">
  <div class="container">
    <div class="row">
      <div class="col-sm-4">
        <div class="whats_new_rgn">
          <div class="region region-whats-new-front  ">
            <div id="block-views-home-tabs-block" class="block block-views first last odd">
              <div class="view view-home-tabs view-id-home_tabs view-display-id-block view-dom-id-3bf7ff7a9fc2e201571860e420eb2778 jquery-once-1-processed">
                <div class="view-content">
                  <div class="post-sec">
                    <div class="date-section-y">
                      <h2>Project Scope</h2>
                      <div class="content mCustomScrollbar" data-mcs-theme="minimal">
                        <ul id="CurrentNews" style="text-align:justify;">
                          <li><i class="fas fa-angle-double-right"></i><span>Building a multitiered database will contain information pertaining to national, state, district, block and primary cooperative level.</span></li>
                          <li><i class="fas fa-angle-double-right"></i><span>Building a responsive web application portal which will interface with the proposed database.</span></li>
                          <li><i class="fas fa-angle-double-right"></i><span>Enabling system access using web portal as well as mobile application.</span></li>
                          <li><i class="fas fa-angle-double-right"></i><span>In Phase-1 of the project three priority sectors namely OACS, Dairy and Fishery shall be covered.</span></li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="view-footer" style="display:none;">
                  <div class="more-link whtslink"> <a href="#" title="" role="link">Read More <i class="faa-passing fas fa-angle-double-right animated"></i> </a> </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-4">
        <div class="right-sidebar">
          <div class="in_focus">
            <div class="region region-in-focus">
              <div id="block-views-in-focus-block-1" class="block block-views first last odd">
                <h2 class="block__title block-title">Objectives</h2>
                <div class="view view-in-focus view-id-in_focus view-display-id-block_1 view-dom-id-d5fd30cc97ec2093abb1e1d1cb86993b">
                  <div class="view-content">
                    <div class="what-news-wrapper">
                      <ul id="CurrentNews1" style="text-align:justify;">
                        <li><i class="fas fa-angle-double-right"></i><span>Capture data on cooperatives across sectors and regions</span></li>
                        <li><i class="fas fa-angle-double-right"></i><span>Identifying gaps in existing processes through analysis of data and taking corrective measures</span></li>
                        <li><i class="fas fa-angle-double-right"></i><span>Improved governance with higher visibility and transparency</span></li>
                        <li><i class="fas fa-angle-double-right"></i><span>Aid in policy making and formulation of government schemes and other initiatives backed by data</span></li>
                        <li><i class="fas fa-angle-double-right"></i><span>Exploring untapped opportunities using technologies such as geotagging, mobile apps, data analytics and AI.</span></li>
                        <li><i class="fas fa-angle-double-right"></i><span>Strengthening the rural economy by promoting inclusive cooperation</span></li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-4">
        <div class="circular_om_rgn">
          <div class="region region-circular-om-front">
            <div id="block-views-orders-circulars-block-1" class="block block-views first last odd">
              <div class="view view-orders-circulars view-id-orders_circulars view-display-id-block_1 view-dom-id-1258906637b410891ac27a86ce9ac425">
                <div class="view-content">
                  <div class="post-sec circular">
                    <div class="date-section-y">
                      <h2>Important Links </h2>
                      <ul>
                        <li> <img src="<?=$this->request->webroot?>frontend/img/ncd.jpg" alt=""><span class="content-sect"><a href="https://doe.gov.in/Advances-to-Government-Servants" role="link"> National Cooperative Database</a> </span> </li>
                        <li> <img src="<?=$this->request->webroot?>frontend/img/ncct.jpg" alt=""><span class="content-sect"> <a href="https://doe.gov.in/Air-Travel-Instruction" role="link">National Council for Cooperative Training   (NCCT)</a> </span> </li>
                        <li> <img src="<?=$this->request->webroot?>frontend/img/ncdc.png" alt=""><span class="content-sect"> <a href="https://doe.gov.in/House-Rent-Allowance" role="link"> National Council Cooperative Development Corporation   (NCDC)</a> </span> </li>
                        <li> <img src="<?=$this->request->webroot?>frontend/img/ncui.jpg" alt=""><span class="content-sect"> <a href="https://doe.gov.in/Appraisal-%26-Approval" role="link"> National Cooperative Union of India (NCUI)</a> </span> </li>
                        <li> <img src="<?=$this->request->webroot?>frontend/img/Cooperative.png" alt=""><span class="content-sect"> <a href="https://doe.gov.in/CGEGI" role="link"> Multi-State Cooperative Societies  (MSCS) </a> </span> </li>
                        <li> <img src="<?=$this->request->webroot?>frontend/img/vamnicom.jpg" alt=""><span class="content-sect"> <a href="https://doe.gov.in/Expenditure-Management-Related-Orders" role="link"> Vaikunth Mehta National Institute of Cooperative   Management</a> </span> </li>
                      </ul>
                      <div class="view-footer">
                        <div class="more-link whtslink orangelink"> <a href="#" title="" role="link">View All <i class="faa-passing fas fa-angle-double-right animated"></i> </a> </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="informationncd">
  <div class="container">
    <div class="heading2 text-center">
      <h2>Information NCD collects and its Methodology</h2>
    </div>
    <div class="row">
      <div class="col-md-4">
        <div class="whitebox">
          <div class="heading-block">  <h4>What data will be captured?</h4></div>
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
      <div class="col-md-4">
        <div class="whitebox">
         <div class="heading-block"> <h4>What will the primary and secondary sources of data?</h4></div>
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
      <div class="col-md-4">
        <div class="whitebox">
          <div class="heading-block">  <h4>How will the data be captured?</h4></div>
          <p>There will be 3 ways in which the data will be fed into the database portal:</p>
          <ul>
            <li>Manual data entry using web forms</li>
            <li>Data migration utility
              <ul>
                <li>API handshaking approach</li>
                <li>File upload process</li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Service Start -->
<!-- Service End -->
<div class="footer-logos mt-0">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div id="owl-carousel" class="owl-carousel owl-theme" >
          <div class="item"><a href="#" onClick="external('https://www.india.gov.in/')" class="externalLink" target="_blank"><img src="<?=$this->request->webroot?>frontend/img/datagov.png" class="" alt="India Gov"/>
            <!-- <img src="images/india-gov-in-logo.jpg" alt="" > -->
            </a></div>
          <div class="item"><a href="#" onClick="external('https://www.mygov.in/')" class="externalLink" target="_blank"><img src="<?=$this->request->webroot?>frontend/img/make-india.png" class="" alt="My Gov"/>
            <!-- <img src="images/my-gov.jpg" alt=""> -->
            </a></div>
          <div class="item"><a href="#" onClick="external('https://www.incredibleindia.org/')" class="externalLink" target="_blank"><img src="<?=$this->request->webroot?>frontend/img/indiagov.png" class="" alt="Incredible India"/>
            <!-- <img src="images/incredible-india.jpg" alt="" > -->
            </a></div>
          <div class="item"><a href="#" onClick="external('https://gandhi.gov.in/')" class="externalLink" target="_blank"><img src="<?=$this->request->webroot?>frontend/img/difitalindia.png" class="" alt="150 Years"/>
            <!-- <img src="images/one-fifty-years.jpg" alt="" > -->
            </a></div>
          <div class="item"><a href="#" onClick="external('https://www.data.gov.in/')" class="externalLink" target="_blank"><img src="<?=$this->request->webroot?>frontend/img/pmindia.png" class="" alt="Data Gov"/>
            <!-- <img src="images/datagov.jpg" alt=""> -->
            </a></div>
          <div class="item"><a href="#" onClick="external('https://www.makeinindia.com/home')" class="externalLink" target="_blank"><img src="<?=$this->request->webroot?>frontend/img/mygov.png" class="" alt="Make in India"/>
            <!-- <img src="images/makeindia.jpg"  alt=""> -->
            </a></div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
	.blink {
		animation: blinker 1.5s linear infinite;
		color: red;
		font-family: sans-serif;
	}
	@keyframes blinker {
		50% {
			opacity: 0;
		}
   }
</style>