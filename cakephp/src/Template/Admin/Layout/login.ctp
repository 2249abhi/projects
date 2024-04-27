<?php use Cake\Core\Configure; ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Ministry of Cooperation | NCD </title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <?= $this->Html->meta('favicon.ico','/favicon.ico',['type' => 'icon']);?>
  <!-- Bootstrap -->
  <?php echo $this->Html->css('../bootstrap/css/bootstrap.min'); ?>
  <!-- Font Awesome -->
  <?php echo $this->Html->css('../font-awesome/css/font-awesome.min'); ?>
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <?php echo $this->Html->css('../assets/css/login'); ?>
  <!-- iCheck -->
  <?php echo $this->Html->css('AdminLTE./plugins/iCheck/square/blue'); ?>

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<?php
$action = !empty($this->request->getParam('action')) ? $this->request->getParam('action') : null;
$controller = !empty($this->request->getParam('controller')) ? $this->request->getParam('controller') : null;
?>
<body class="hold-transition <?= $controller.' '.$action ?> login-page">
  <div class="loading"></div>
  <div id="loader"></div>
  <?=$this->fetch('head-style');?>
  <?=$this->fetch('head-script')?>  
  <?php echo $this->fetch('content'); ?>

<!-- jQuery 3.3.1 -->
<?= $this->Html->script('jquery.min'); ?>
<!-- Bootstrap -->
<?php echo $this->Html->script('../bootstrap/js/bootstrap.min'); ?>
<!-- iCheck -->
<?php echo $this->Html->script('AdminLTE./plugins/iCheck/icheck.min'); ?>
<!-- Validate -->
<?php echo $this->Html->script('jquery.validate.min'); ?>
<!-- Additional Methods -->
<?php echo $this->Html->script('jquery-additional-methods.min'); ?>
<!-- Backstretch -->
<?php echo $this->Html->script('jquery.backstretch.min'); ?>
<!-- Custom Script -->
<?php //echo $this->Html->script('scripts'); ?>
<noscript>
  <?= $this->element('noscript'); ?>
</noscript>
<script type="text/javascript">
  $(window).on('load', function(){
    $(".loading").fadeOut('slow');
  });

  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });

  $.backstretch([
    "<?=$this->Url->build('/img/background1.jpg');?>",
    "<?=$this->Url->build('/img/background2.jpg');?>"
  ], {duration: 3000, fade: 750});

  var loader = $('#loader').hide();
   //Attach the event handler to any element
  $(document)
    .ajaxStart(function () {
    //ajax request went so show the loading image
    loader.show();
    })
    .ajaxStop(function () {
    //got response so hide the loading image
    loader.hide();
  });
</script>
<?=$this->fetch('bottom-script')?>
</body>
</html>
