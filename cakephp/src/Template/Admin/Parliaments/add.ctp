<?php
/**
 * @author \Nilesh Kushvaha
 * @version \1.1
 * @since \October 29, 2018, 7:13 am
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Role $role
 */
$this->assign('title',__('Add New Parliament'));
$this->Breadcrumbs->add(__('Parliament'),['action'=>'index']);
$this->Breadcrumbs->add(__('Add New Parliament'));
?>

  <!-- Main content -->
  <section class="content role add form">
    <div class="row">
      <!-- left column -->
      <div class="col-md-12">
        <!-- general form elements -->
        <div class="box box-success">
          <div class="box-header with-border">
            <h3 class="box-title">
            <?= __('Parliament') ?>
                          <small><?= __('Add New Parliament') ?></small>
          </h3>
          <div class="box-tools pull-right">
              <?=$this->Html->link(
                      '<i class="fa fa-arrow-circle-left"></i>',
                      ['action' => 'index'],
                      ['class' => 'btn btn-info btn-xs','title' => __('Back to Parliament'),'escape' => false]
                  );?>
            </div>
          </div>
          <!-- /.box-header -->
          <!-- form start -->
          <?php echo $this->Form->create($role,['id' => 'role-add-frm']); ?>
              <div class="box-body">
              <div class="col-md-2"><label>Parliament Type<sup class="red">*</sup></label></div>
              <?php echo $this->Form->control('parliament_type_id',['options'=>$types,'label' => false,'empty'=>'--Select--','required'=>true]);?>
              <div class="col-md-2"><label>Title<sup class="red">*</sup></label></div>
              <?php echo $this->Form->control('title',['label' => false,'required'=>true]);?>
              <div><label>Content<sup class="red">*</sup></label></div>
              <?php echo $this->Form->control('content',['class'=>'ckeditor','label' => false,'required'=>true]);?>
              <div class="col-md-2"><label>Image</label></div>
              <?php echo $this->Form->control('image',['type'=>'file','label' => false]);?>
              <div class="col-md-2"><label>Priority<sup class="red">*</sup></label></div>
              <?php echo $this->Form->control('priority',['label' => false,'required'=>true,'min'=>1]);?>
              <div class="col-md-2"><label for="status">Status<sup class="red">*</sup></label></div> 
              <?php $sOption = [0=>'Inactive',1=>'Active'];?>
              <?=$this->form->select('status',$sOption,['class'=>'form-control','empty'=>'--Select--','value'=>@$status,'required'=>true])?>
 
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
  <?php $this->Html->script(['AdminLTE./plugins/ckeditor/ckeditor'],['block' => 'bottom-script']); ?>
<?php $this->append('bottom-script');?>
<script>
(function($){
    $(document).ready(function(){
        if(typeof $.validator !== "undefined"){
            $("#role-add-frm").validate();
        }
    });
})($);
</script>
<?php $this->end(); ?>
<style type="text/css">
  .red{
  color:red;
}
</style>