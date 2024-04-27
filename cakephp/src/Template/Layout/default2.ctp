<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>Minister of Cooperative</title>
<meta content="width=device-width, initial-scale=1.0" name="viewport" />
<meta content="" name="keywords" />
<meta content="" name="description" />
<!-- Favicon -->
<!-- Google Web Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link
      href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&family=Poppins:wght@600;700&display=swap"
      rel="stylesheet"
    />
<!-- Icon Font Stylesheet -->
<link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css"
      rel="stylesheet"
    />
<link
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css"
      rel="stylesheet"
    />
<!-- Libraries Stylesheet -->
<link href="https://cdn.jsdelivr.net/npm/font-awesome-animation@1.1.1/css/font-awesome-animation.css" rel="stylesheet" />
<!-- Customized Bootstrap Stylesheet -->
<link href="<?=$this->request->webroot?>frontend/css/bootstrap.min.css" rel="stylesheet" />
<!-- Template Stylesheet -->
<link href="<?=$this->request->webroot?>frontend/css/style.css" rel="stylesheet" />
<link href="<?=$this->request->webroot?>frontend/css/custom.css" rel="stylesheet" />
<link href="<?=$this->request->webroot?>frontend/css/custom1.css" rel="stylesheet" />
<link href="<?=$this->request->webroot?>frontend/css/owl.carousel.css" rel="stylesheet" />
<link href="<?=$this->request->webroot?>frontend/css/jquery.mCustomScrollbar.css" rel="stylesheet" />

</head>
<body>
<!-- Spinner Start -->
<div
      id="spinner"
      class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center"
    >
  <div class="spinner-grow text-primary" role="status"></div>
</div>
<!-- Spinner End -->

<?= $this->element('topbar'); ?>
<?= $this->element('header'); ?>

<?= $this->fetch('content'); ?>
<?= $this->element('footer'); ?>
<!-- Back to Top -->
<a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"
      ><i class="bi bi-arrow-up"></i
    >
</a>
<!-- JavaScript Libraries -->
<script src="<?=$this->request->webroot?>frontend/js/jquery-3.4.1.min.js"></script>
<script src="<?=$this->request->webroot?>frontend/js/owl.carousel.js"></script>

<!-- Template Javascript -->
<script src="<?=$this->request->webroot?>frontend/js/main.js"></script>

<script>
$(document).ready(function() {
           $("#owl-carousel").owlCarousel({
               nav : false, // Show next and prev buttons
               slideSpeed : 300,
         autoplay:true,
               paginationSpeed : 400,
               singleItem:true,
               items : 6, 
              itemsDesktop : false,
            itemsDesktopSmall : false,
            itemsTablet: false,
       itemsMobile : false
          
           });
          
         });

            $(document).ready(function() {
              var owl = $('#banner-slider-xs');
              owl.owlCarousel({
                margin: 10,
        autoplay:true,
                nav: false,
                loop: true,
                responsive: {
                  0: {
                    items: 1
                  },
                  600: {
                    items: 1
                  },
                  1000: {
                    items: 1
                  }
                }
              })
            })
      
      
  </script>
</body>
</html>
