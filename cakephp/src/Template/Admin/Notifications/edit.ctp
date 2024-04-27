<?php
/**
 * @author \Abhay Singh
 * @version \1.1
 * @since \July 07, 2020, 10s:13 am
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Role $role
 */
$this->assign('title',__('Edit News ( ' . $doc_type->description .' ) '));
$this->Breadcrumbs->add(__('Notifications'),['action'=>'index']);
$this->Breadcrumbs->add(__('Edit News ( ' . $doc_type->description .' ) '));
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
            <?= __('News') ?>
                          <small><?= __('Edit News  ( ' . $doc_type->description .' ) ') ?></small>
          </h3>
          <div class="box-tools pull-right">
              <?=$this->Html->link(
                      '<i class="fa fa-arrow-circle-left"></i>',
                      ['action' => 'index'],
                      ['class' => 'btn btn-info btn-xs','title' => __('Back to News'),'escape' => false]
                  );?>
                    <?= $this->Form->postLink(
                      '<i class="fa fa-trash-o"></i>',
                      ['action' => 'delete', $doc_type->id],
                      ['confirm' => __('Are you sure you want to delete this News?', $doc_type->id),'class' => 'btn btn-danger btn-xs','title' => __('Delete'),'escape' => false]
                  )?>
            </div>
          </div>
          <!-- /.box-header -->
          <!-- form start -->
          <?php
              echo $this->Form->create($doc_type,['id' => 'role-edit-frm']); ?>
              <div class="row">
                <div class="col-md-12">
                   <div class="box-body">
                        <div><label> News Description</label></div>
                          <?php echo $this->Form->control('description',['class'=>'','id'=>'body_desc','label'=>false,'value'=>@$doc_type['description'],'required'=>false]); ?>
                    </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="box-body">
                    <div><label>News Link</label></div>
            <?php echo $this->Form->control('link',['label' => false,'type' =>'text','required'=>true]);?>
            </div>
            </div>
				<div class="col-md-4">
                   <div class="box-body">
                       <div><label>News Date</label></div>
              <?php echo $this->Form->control('notification_date',['class'=>'datepicker','label' => false,'type' =>'text','required'=>true,'value'=>date('Y-m-d',strtotime($doc_type['notification_date']))]);?>
                    </div>
                </div>
				<div class="col-md-4">
                   <div class="box-body">
                       <div><label>News Order</label></div>
              <?php echo $this->Form->control('notification_order',['label' => false,'type' =>'number','required'=>true,'min'=>0]);?>
                    </div>
                </div>
                <div class="col-md-4">
                  <div class="box-body">
                   <div><label for="status">Status</label></div> 
					<?php $sOption = [0=>'Inactive',1=>'Active'];?>
					<?=$this->form->select('status',$sOption,['required'=>true,'class'=>'form-control','empty'=>'--Select--','value'=>@$doc_type['status']])?>
				  </div>
                </div>
                </div>
				<div class="row">
					<div class="col-md-4">
						<div class="box-body">
							<?php echo $this->Form->control('is_notification',['type'=>'checkbox']);?>
						</div>
					</div>
                
                <div class="col-md-12">
                   <div class="box-footer text-center">
      <?php 
      echo $this->Form->button(__('Submit'),['class' => 'btn btn-primary']);
      echo $this->Html->link(__('Cancel'),['action' => 'index'],['class' => 'btn btn-danger mx-1']); ?>
    </div>
                </div>
              </div>
              
   
      <?php echo $this->Form->end();?>

      </div>
    </div>
  </section>
  <?php $this->Html->script(['AdminLTE./plugins/ckeditor/ckeditor'],['block' => 'bottom-script']); ?>
<?php $this->append('bottom-script');?>
<script>
  $( function() {
    $( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
  } );
  </script>
<script>
(function($){
    $(document).ready(function(){
        if(typeof $.validator !== "undefined"){
            $("#role-edit-frm").validate();
        }
    });
})($);
</script>
<?php $this->end(); ?>