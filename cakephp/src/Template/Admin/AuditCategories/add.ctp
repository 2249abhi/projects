<?php
/**
 * @author \Nilesh Kushvaha
 * @version \1.1
 * @since \October 29, 2018, 7:07 am
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SecondaryActivities $SecondaryActivities
 */
$this->assign('title',__('Add New Audit Category'));
$this->Breadcrumbs->add(__('Audit Category'),['action'=>'index']);
$this->Breadcrumbs->add(__('Add New Audit Category'));
?>

  <!-- Main content -->
  <section class="content sactivity add form">
    <div class="row">
      <!-- left column -->
      <div class="col-md-12">
        <!-- general form elements -->
        <div class="box box-success">
          <div class="box-header with-border">
            <h3 class="box-title">
            <?= __('Audit Category') ?>
                          <small><?= __('Add New Audit Category') ?></small>
          </h3>
          <div class="box-tools pull-right">
              <?=$this->Html->link(
                      '<i class="fa fa-arrow-circle-left"></i>',
                      ['action' => 'index'],
                      ['class' => 'btn btn-info btn-xs','title' => __('Back to Audit Categories'),'escape' => false]
                  );?>
            </div>
          </div>
          <!-- /.box-header -->
          <!-- form start -->
          <?php
              echo $this->Form->create($acategories,['id' => 'sactivity-add-frm']); ?>
              <div class="box-body">
              <div class="row">
                  <div class="col-sm-6">
                    <?= $this->Form->control('name') ?>  
                  </div>  
                  <div class="col-sm-6">
                    <?= $this->Form->input('orderseq', array('label' => 'Order')) ?>  
                  </div>    
              </div>
              <div class="row">
                  <div class="col-sm-6"> 
                    <?php  //$status=[1=>'Active',0=>'Inactive']; ?>
                    <?= $this->Form->control('status',['type'=>'checkbox']) ?>
                  </div>  
              </div>
              <?php
              /*
              echo $this->Form->control('sector_of_operation',['options' => $soperations]);
              echo $this->Form->control('name');
              echo $this->Form->control('orderseq');
              echo $this->Form->control('status');
              */
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
            $("#sactivity-add-frm").validate();
        }
    });
})($);
</script>
<?php $this->end(); ?>