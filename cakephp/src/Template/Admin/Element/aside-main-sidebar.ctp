<?php
/**
 * Element : Sidebar
 * Sidebar controll all list of navigation.
 * @author : Nilesh Kushvaha
 * @version : 1.1
 * @since : 24 October 2018
 */
?>
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <?php echo $this->element('aside/user-panel'); ?>

        <!-- search form -->
        <?php //echo $this->element('aside/form'); ?>
        <!-- /.search form -->

        <!-- sidebar menu: : style can be found in sidebar.less -->
        <?php echo $this->element('aside/sidebar-menu'); ?>

    </section>
    <!-- /.sidebar -->
</aside>
<?php //} ?>
