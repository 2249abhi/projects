<?php
/**
 * @author \Nilesh Kushvaha
 * @version \1.1
 * @since \October 29, 2018, 7:06 am
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Department $department
 */
$this->assign('title',__('Edit Department ( ' . $department->name .' ) '));
$this->Breadcrumbs->add(__('Departments'),['action'=>'index']);
$this->Breadcrumbs->add(__('Edit Department ( ' . $department->name .' ) '));
?>

  <!-- Main content -->
  <section class="content department edit form">
    <div class="row">
      <!-- left column -->
      <div class="col-md-12">
        <!-- general form elements -->
        <div class="box box-success">
          <div class="box-header with-border">
            <h3 class="box-title">
            <?= __('Department') ?>
                          <small><?= __('Edit Department  ( ' . $department->name .' ) ') ?></small>
          </h3>
          <div class="box-tools pull-right">
              <?=$this->Html->link(
                      '<i class="fa fa-arrow-circle-left"></i>',
                      ['action' => 'index'],
                      ['class' => 'btn btn-info btn-xs','title' => __('Back to Departments'),'escape' => false]
                  );?>
                    <?= $this->Form->postLink(
                      '<i class="fa fa-trash-o"></i>',
                      ['action' => 'delete', $department->id],
                      ['confirm' => __('Are you sure you want to delete this Department?', $department->id),'class' => 'btn btn-danger btn-xs','title' => __('Delete'),'escape' => false]
                  )?>
            </div>
          </div>
          <!-- /.box-header -->
          <!-- form start -->
          <?php
              echo $this->Form->create($department,['id' => 'department-edit-frm']); ?>
              <div class="box-body">
              <?php
                echo $this->Form->control('name');
              echo $this->Form->control('status');
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
            $("#department-edit-frm").validate();
        }
    });
})($);
</script>
<?php $this->end(); ?>