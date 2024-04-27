	<?php
/**
 * @author \Nilesh Kushvaha
 * @version \1.1
 * @since \October 29, 2018, 7:07 am
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\AuditCategories $acategories
 */
$this->assign('title',__('Edit Audit Category  ( ' . $acategories->name .' ) '));
$this->Breadcrumbs->add(__('Audit Category'),['action'=>'index']);
$this->Breadcrumbs->add(__('Edit Audit Category ( ' . $acategories->name .' ) '));
?>

  <!-- Main content -->
  <section class="content sactivity edit form">
    <div class="row">
      <!-- left column -->
      <div class="col-md-12">
        <!-- general form elements -->
        <div class="box box-success">
          <div class="box-header with-border">
            <h3 class="box-title">
            <?= __('Secondary Activity') ?>
                          <small><?= __('Edit Secondary Activity  ( ' . $acategories->name .' ) ') ?></small>
          </h3>
          <div class="box-tools pull-right">
              <?=$this->Html->link(
                      '<i class="fa fa-arrow-circle-left"></i>',
                      ['action' => 'index'],
                      ['class' => 'btn btn-info btn-xs','title' => __('Back to Secondary Activities'),'escape' => false]
                  );?>
                    <?= $this->Form->postLink(
                      '<i class="fa fa-trash-o"></i>',
                      ['action' => 'delete', $acategories->id],
                      ['confirm' => __('Are you sure you want to delete this Secondary Activity?', $acategories->id),'class' => 'btn btn-danger btn-xs','title' => __('Delete'),'escape' => false]
                  )?>
            </div>
          </div>
          <!-- /.box-header -->
          <!-- form start -->
          <?php
              echo $this->Form->create($acategories,['id' => 'pactivity-edit-frm']); ?>
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
                  <?php // $status=[1=>'Active',0=>'Inactive']; ?>
                  <?= $this->Form->control('status',['type'=>'checkbox']) ?>
                  </div>  
                  <div class="col-sm-6">
                  </div>  
              </div>
              <?php
              /*
                echo $this->Form->control('sector_of_operation', ['options' => $soptions]);
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
            $("#sactivity-edit-frm").validate();
        }
    });
})($);
</script>
<?php $this->end(); ?>