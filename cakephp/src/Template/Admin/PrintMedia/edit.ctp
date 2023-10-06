<?php
/**
 * @author \Nilesh Kushvaha
 * @version \1.1
 * @since \October 29, 2018, 7:13 am
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Role $role
 */
$this->assign('title',__('Edit Print Media ( ' . $role->title .' ) '));
$this->Breadcrumbs->add(__('Print Medias'),['action'=>'index']);
$this->Breadcrumbs->add(__('Edit Print Media ( ' . $role->title .' ) '));
?>

  <!-- Main content -->
  <section class="content role edit form">
    <div class="row">
      <!-- left column -->
      <div class="col-md-12">
        <!-- general form elements -->
        <div class="box box-success">
          <div class="box-header with-border">
            <h3 class="box-title">
            <?= __('Print Media') ?>
                          <small><?= __('Edit Print Media  ( ' . $role->title .' ) ') ?></small>
          </h3>
          <div class="box-tools pull-right">
              <?=$this->Html->link(
                      '<i class="fa fa-arrow-circle-left"></i>',
                      ['action' => 'index'],
                      ['class' => 'btn btn-info btn-xs','title' => __('Back to Print Media'),'escape' => false]
                  );?>
                    <?= $this->Form->postLink(
                      '<i class="fa fa-trash-o"></i>',
                      ['action' => 'delete', $role->id],
                      ['confirm' => __('Are you sure you want to delete this Print Media?', $role->id),'class' => 'btn btn-danger btn-xs','title' => __('Delete'),'escape' => false]
                  )?>
            </div>
          </div>
          <!-- /.box-header -->
          <!-- form start -->
          <?php echo $this->Form->create($role,['id' => 'role-add-frm','type'=>'post', 'enctype'=>'multipart/form-data']); ?>
              <div class="box-body"> 
              <div class="col-md-2"><label>Title<sup class="red">*</sup></label></div>
              <?php echo $this->Form->control('title',['label' => false,'required'=>true,'value'=>@$role->title]);?>
              <!-- <div><label>Content<sup class="red">*</sup></label></div>
              <?php echo $this->Form->control('content',['class'=>'ckeditor','label' => false,'required'=>true,'value'=>@$role->content]);?> -->
              <div class="col-md-2"><label>Image</label></div>
              <?php echo $this->Form->control('image',['type'=>'file','label' => false,'class'=>'file','accept'=>'image/x-png,image/gif,image/jpeg', 'value' =>@$role->image]);?>
              <?php echo $this->Form->control('image_old',['type'=>'hidden','label' => false,'class'=>'file','accept'=>'image/x-png,image/gif,image/jpeg', 'value' =>@$role->image]);?> 

              <div class="col-md-2"><label>Download file</label></div>
              <?php echo $this->Form->control('download_doc',['type'=>'file','label' => false,'class'=>'file','accept'=>'application/pdf', 'value' =>@$role->download_doc]);?>
              <?php echo $this->Form->control('download_doc_old',['type'=>'hidden','label' => false,'class'=>'file','accept'=>'application/pdf', 'value' =>@$role->download_doc]);?> 
              <!-- <div class="col-md-2"><label>Priority<sup class="red">*</sup></label></div>
              <?php echo $this->Form->control('priority',['label' => false,'required'=>true,'min'=>1,'value'=>@$role->priority]);?> -->
              
              <div class="col-md-2"><label for="status">Status<sup class="red">*</sup></label></div> 
              <?php $sOption = [0=>'Inactive',1=>'Active'];?>
              <?=$this->form->select('status',$sOption,['class'=>'form-control','empty'=>'--Select--','value'=>@$role->status,'required'=>true])?>
 
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
            $("#role-edit-frm").validate();
        }
    });
      $('#year_from').datepicker({ dateFormat: 'yy' });
     $('#year_to').datepicker({ dateFormat: 'yy' });
})($);
</script>
<?php $this->end(); ?>