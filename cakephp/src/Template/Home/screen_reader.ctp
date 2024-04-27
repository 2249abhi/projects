<!DOCTYPE html>
<html lang="en-US">
<head>
    <?= $this->element('head') ?>
</head>
<body>
    <!-- Header Section Start -->
    <?= $this->element('header') ?>
    
    <!-- Header Section End -->
   
    
   
<div class="white-background"></div>    

<!-- Our Business Section Start -->

<section class="org-str about-us">
	<div class="container">
		<div class="row">
            <div class="col-sm-12">
            <div class="about-us_intro">
                <h2>OPERATIONAL STRUCTURE</h2>
                <p>Committees formed for National Cooperative Database (NCD) Implementation:</p>
                <div class="row my-40">
                    <div class="col-sm-4">
                        <div class="org_link">
                            <h4><a href="<?=$this->request->webroot?>files/OM for Steering Committee on NCD (1).pdf" target="_blank" download>Steering Committee<i class="fa fa-download"></i></a></h4>
                            <!-- <h4><a href="http://134.209.222.136/cooperative/steer.pdf"> Steering Committee </a></h4> -->
                            <p>Steering Committee under chairmanship of Addl. Secretary constituted for monitoring of progress and implementation of strategy for development for National Cooperative database. </p>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="org_link">
                            <h4>
                            <a href="<?=$this->request->webroot?>files/constitution_of_advisory_committee_on_NCD.pdf" target="_blank" download>Advisory Committee<i class="fa fa-download"></i></a>
                                <!-- <a href="http://134.209.222.136/cooperative/adv.pdf">Advisory Committee  </a> -->
                            </h4>
                            <p>An Advisory Committee formed for advising Steering Committee and Project Management Group for Development of Nation Cooperative Database.</p>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="org_link">
                            <h4>
                            <a href="<?=$this->request->webroot?>files/OM for Setting up Project Management Group for NCD (2).pdf" target="_blank" download>Project Management Group<i class="fa fa-download"></i></a>
                            
                            <!-- <a href="http://134.209.222.136/cooperative/projct.pdf">Project Management Group (PMG)  </a> -->
                        </h4>
                            <p>Project Management Group formed for execution and operation of National Cooperative Database.</p>
                        </div>
                    </div>               
                </div>
                <p class="mt-30">Various teams like Development Team, Coordination Team and Team of State Officers were engaged to execute the NCD. </p>
				</div>
            </div>
        </div>
	</div>
</section>
<!-- Our Business Section End -->





<!-- Our Business Section Start -->

<section class="about-us org-img">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="about-us_intro">
                    <h2>NCD Stakeholders</h2>
                </div>
            </div>
            <div class="col">
                <div class="text-center org-img_inner">
                    <?php echo $this->Html->image('../frontend/img/Picture3.png', array('class' => 'card-img-top')); ?>
                    <!-- <img src="images/Directorate_Agri-culture-Food-Production.jpg" class="card-img-top" alt="..."> -->
                </div>
            </div>
        </div>
    </div>
</section>
<div class="white-bg"></div>


<!-- Our Business Section End -->





<!----section end-->





    <!-- Footer Section Start -->
    <?= $this->element('footer') ?>

</body>
</html>
