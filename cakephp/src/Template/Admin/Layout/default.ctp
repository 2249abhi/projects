<?php 
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

use Cake\Core\Configure;
use Cake\Routing\Router;
$lang = 'en';
$dir  = 'ltr';
if ($Configure::check('language')) {
    $lang = $Configure::read('language.culture');
    $dir  = $Configure::read('language.direction');
}
?>
<?= $this->Html->docType();?>
<html>
<head>
    <?= $this->Html->charset();?>
	<?php 
	  $loginUser = $this->request->getSession()->read('Auth.User');
	?>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <!--meta http-equiv="X-UA-Compatible" content="IE=edge"-->
    
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <?=$this->fetch('meta');?>

    <title>Ministry of Cooperation |  <?php if($this->request->params['controller']=='Dashboard'){ echo "Dashboard"; }else { ?><?= $this->fetch('title');?> <?php } ?> - <?= Configure::read('Theme.title'); ?></title>
    <!-- Favicon -->
    <?= $this->Html->meta('favicon.ico','/favicon.ico',['type' => 'icon']);?>
    <!-- Bootstrap 3.3.5 -->
    <?= $this->Html->css('../bootstrap/css/bootstrap.min'); ?>
    <!-- Font Awesome -->
    <?= $this->Html->css('../font-awesome/css/font-awesome.min'); ?>
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <?= $this->Html->css('Admin.min'); ?>
    <!-- Custom style -->
    <?= $this->Html->css(['custom']); ?>
	<?= $this->Html->css(['jquery-ui']); ?>
    <!-- Admin Skins. -->
    <?= $this->Html->css('skins/skin-'. Configure::read('Theme.skin') .'.min'); ?>

    <?= $this->fetch('css'); ?>
    <!-- head style -->
    <?=$this->fetch('head-style');?>
    <!-- /head style -->
    <!-- head script -->
    <link rel="stylesheet" href="<?=$this->request->webroot?>select2/select2.min.css">
    
    <?=$this->Html->script([]);?>
    <?=$this->fetch('head-script')?>
    <script>
        var baseUrl="<?php echo $this->Url->build('/admin/en', true);?>"
    </script>
    <?= $this->Html->script(['jquery.min']); ?>
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
<body class="hold-transition <?= $controller.'-'.$action ?> skin-<?= Configure::read('Theme.skin'); ?> sidebar-mini">
    <div class="loading"></div>
    <div id="loader"></div>
    <!-- Site wrapper -->
    <div class="wrapper">
        <header class="main-header">
            <!-- Logo -->
            <a href="<?= $this->Url->build('/admin/en'); ?>" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini"><?= Configure::read('Theme.logo.mini'); ?></span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg">NCD</span>
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <?= $this->element('nav-top') ?>
        </header>

        <!-- Left side column. contains the sidebar -->
        <?= $this->element('aside-main-sidebar'); ?>

        <!-- =============================================== -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">            
            <section class="content-header" style="display:none;">
                <h1><?php if(empty($this->fetch('content_header'))){
                         // echo 'Dashboard';
                        } else {
                            echo $this->fetch('content_header');
                        } ?>
                    <!--<small>Control panel</small>-->
                </h1>
                <?php $this->Breadcrumbs->prepend('Dashboard',['controller'=>'Dashboard','action' =>'index']);?>
                <?= $this->Breadcrumbs->render(['class' => 'breadcrumb rounded-0']);?>
            </section>
            <section class="content-header">
				<div style="float:right;"><h4><b>Name</b>: <?=$loginUser['name']?>&nbsp;<b>Designation</b>: <?=$loginUser['designation']?>&nbsp;<b>Role</b>: <?= $cuser['name']?>&nbsp;</h4></div>
			</section>
            <?= $this->fetch('content'); ?>
        </div>
        <!-- /.content-wrapper -->

        <?= $this->element('footer'); ?>

        <!-- Control Sidebar -->
        <?= $this->element('aside-control-sidebar'); ?>

        <!-- /.control-sidebar -->
<!-- Add the sidebar's background. This div must be placed
    immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- jQuery 3.3.1 -->

<!-- Bootstrap 3.3.5 -->
<?= $this->Html->script('../bootstrap/js/bootstrap.min'); ?>
<!-- SlimScroll -->
<?= $this->Html->script('AdminLTE./plugins/slimScroll/jquery.slimscroll.min'); ?>
<!-- FastClick -->
<?= $this->Html->script('AdminLTE./plugins/fastclick/fastclick'); ?>
<!-- Validate -->
<?= $this->Html->script('jquery.validate.min'); ?>
<!-- Additional Methods -->
<?= $this->Html->script('jquery-additional-methods.min'); ?>
<!-- Admin App -->
<?= $this->Html->script('app.min'); ?>
<?= $this->Html->script('jqueryui'); ?>

<script src="<?=$this->request->webroot?>select2/select2.full.min.js"></script>
<!-- Admin -->
<?= $this->fetch('script'); ?>
<noscript>
    <?= $this->element('noscript'); ?>
</noscript>
<script type="text/javascript">
    $(document).ready(function(){
        $(".navbar .menu").slimscroll({
            height: "200px",
            alwaysVisible: false,
            size: "3px"
        }).css("width", "100%");

        var a = $('a[href="<?php echo $this->request->webroot . $this->request->url ?>"]');
        if (!a.parent().hasClass('treeview') && !a.parent().parent().hasClass('pagination')) {
            a.parent().addClass('active').parents('.treeview').addClass('active');
        }
    });

    $(document).ajaxSend(function(e, xhr, settings) {
        xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
    });

    $(window).on('load', function(){
      $(".loading").fadeOut('slow');
      //alert('Nilesh');
    });  

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
