<?php
/**
 * @author \Nilesh Kushvaha
 * @version \1.1
 * @since \December 17, 2018, 3:58 pm
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Language $language
 */
$this->assign('title',__('Add New Language'));
$this->Breadcrumbs->add(__('Languages'),['action'=>'index']);
$this->Breadcrumbs->add(__('Add New Language'));
?>

  <!-- Main content -->
  <section class="content language add form">
    <div class="row">
      <!-- left column -->
      <div class="col-md-12">
        <?= $this->Flash->render() ; ?>
        <!-- general form elements -->
        <div class="box box-success">
          <div class="box-header with-border">
            <h3 class="box-title">
              <?= __('Language') ?>
                <small><?= __('Add New Language') ?></small>
            </h3>
            <div class="box-tools pull-right">
              <?=$this->Html->link(
                      '<i class="fa fa-arrow-circle-left"></i>',
                      ['action' => 'index'],
                      ['class' => 'btn btn-info btn-xs','title' => __('Back to Languages'),'escape' => false]
                  );?>
            </div>
          </div>
          <!-- /.box-header -->
          <!-- form start -->
          <?php
              echo $this->Form->create($language,['id' => 'language-add-frm']); ?>
              <div class="box-body">
              <?php
                echo $this->Form->control('name');
              echo $this->Form->control('culture');
              echo $this->Form->control('direction');
              echo $this->Form->control('is_default');
              echo $this->Form->control('is_system');
              echo $this->Form->control('status');
         ?>
         </div>
    <div class="box-footer">
      <?php 
      echo $this->Form->button(__('Submit'),['class' => 'btn btn-primary']);
      echo $this->Html->link(__('Cancel'),['action' => 'index'],['class' => 'btn btn-danger mx-1']); ?>
    </div>
      <?php echo $this->Form->end();?>
    
      </div>
    </div>
  </section>
<?php $this->append('bottom-script');?>
<script>
(function($){
    $(document).ready(function(){
        if(typeof $.validator !== "undefined"){
            $("#language-add-frm").validate();
        }
    });
})($);
</script>
<?php $this->end(); ?>