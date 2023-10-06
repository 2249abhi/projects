<?php
/**
 * @author \Nilesh Kushvaha
 * @version \1.1
 * @since \November 29, 2018, 10:24 am
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\LocaleSource $localeSource
 */
$this->assign('title',__('Edit Locale Source ( ' . $localeSource->id .' ) '));
$this->Breadcrumbs->add(__('Locale Sources'),['action'=>'index']);
$this->Breadcrumbs->add(__('Edit Locale Source ( ' . $localeSource->id .' ) '));
?>

  <!-- Main content -->
  <section class="content localeSource edit form">
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
                          <small><?= __('Edit Locale Source  ( ' . $localeSource->id .' ) ') ?></small>
          </h3>
          <div class="box-tools pull-right">
              <?=$this->Html->link(
                      '<i class="fa fa-arrow-circle-left"></i>',
                      ['action' => 'index'],
                      ['class' => 'btn btn-info btn-xs','title' => __('Back to Locale Sources'),'escape' => false]
                  );?>
                    <?= $this->Form->postLink(
                      '<i class="fa fa-trash-o"></i>',
                      ['action' => 'delete', $localeSource->id],
                      ['confirm' => __('Are you sure you want to delete this Locale Source?', $localeSource->id),'class' => 'btn btn-danger btn-xs','title' => __('Delete'),'escape' => false]
                  )?>
            </div>
          </div>
          <!-- /.box-header -->
          <!-- form start -->
          <?php
              echo $this->Form->create($localeSource,['id' => 'localeSource-edit-frm']); ?>
              <div class="box-body">
              <?php
                echo $this->Form->control('source');
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
            $("#localeSource-edit-frm").validate();
        }
    });
})($);
</script>
<?php $this->end(); ?>