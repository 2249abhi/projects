<?php
/**
 * @author \Nilesh Kushvaha
 * @version \1.1
 * @since \November 29, 2018, 10:24 am
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\LocaleSource $localeSource
 */
$this->assign('title',__('Add New Locale Source'));
$this->Breadcrumbs->add(__('Locale Sources'),['action'=>'index']);
$this->Breadcrumbs->add(__('Add New Locale Source'));
?>

  <!-- Main content -->
  <section class="content localeSource add form">
    <div class="row">
      <!-- left column -->
      <div class="col-md-12">
        <?= $this->Flash->render(); ?>
        <?= $this->Flash->render('auth'); ?>
        <!-- general form elements -->
        <div class="box box-success">
          <div class="box-header with-border">
            <h3 class="box-title">
            <?= __('Locale Source') ?>
                          <small><?= __('Add New Locale Source') ?></small>
          </h3>
          <div class="box-tools pull-right">
              <?=$this->Html->link(
                      '<i class="fa fa-arrow-circle-left"></i>',
                      ['action' => 'index'],
                      ['class' => 'btn btn-info btn-xs','title' => __('Back to Locale Sources'),'escape' => false]
                  );?>
            </div>
          </div>
          <!-- /.box-header -->
          <!-- form start -->
          <?php
              echo $this->Form->create($localeSource,['id' => 'localeSource-add-frm']); ?>
              <div class="box-body">
              <?php
                echo $this->Form->control('source',['autocomplete'=>'off']);
 ?>
    <div class="box-footer">
      <?php 
      echo $this->Form->button(__('Submit'),['class' => 'btn btn-primary']);
      echo $this->Html->link(__('Cancel'),['action' => 'index'],['class' => 'btn btn-danger mx-1']); ?>
    </div>
      <?php echo $this->Form->end();?>
    </div>
      </div>
    </div>
  </section>
<?php $this->append('bottom-script');?>
<script>
(function($){
    $(document).ready(function(){
        if(typeof $.validator !== "undefined"){
            $("#localeSource-add-frm").validate();
        }
    });
})($);
</script>
<?php $this->end(); ?>