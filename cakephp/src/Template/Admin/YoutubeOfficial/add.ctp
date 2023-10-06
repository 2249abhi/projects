<?php
/**
 * @author \Abhay Singh
 * @version \1.1
 * @since \July 16, 2020, 7:13 am
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Role $role
 */
$this->assign('title',__('Add New Youtube Official'));
$this->Breadcrumbs->add(__('Youtube Official'),['action'=>'index']);
$this->Breadcrumbs->add(__('Add New Youtube Official'));
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
            <?= __('Youtube Official') ?>
                          <small><?= __('Add New Youtube Official') ?></small>
          </h3>
          <div class="box-tools pull-right">
              <?=$this->Html->link(
                      '<i class="fa fa-arrow-circle-left"></i>',
                      ['action' => 'index'],
                      ['class' => 'btn btn-info btn-xs','title' => __('Back to Youtube Official'),'escape' => false]
                  );?>
            </div>
          </div> 
          <!-- /.box-header -->
          <!-- form start -->
			<?php
              echo $this->Form->create($doc_type,['id' => 'role-add-frm','type'=>'file']); ?>
              <div class="row">
                <div class="col-md-12">
                  <div class="box-body">
                    <div><label>Youtube Official Title</label></div>
                  <?php echo $this->Form->control('title',['label' => false,'required'=>true]);?>
                </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-8">
                  <div class="box-body">
                    <div><label>Youtube Link</label></div>
                      <?php echo $this->Form->control('youtube_link',['label' => false,'type' =>'text','required'=>true,'class'=>'']);?>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="box-body">
                    <div><label>Youtube ID</label></div>
                      <?php echo $this->Form->control('youtube_id',['label' => false,'type' =>'text','required'=>true,'class'=>'']);?>
                  </div>
                </div>
              </div>

              <div class="row">
                <!-- <div class="col-md-4">
                  <div class="box-body">
                    <div><label> Order</label></div>
                      <?php echo $this->Form->control('event_order',['label' => false,'type' =>'number','required'=>true,'min'=>0]);?>
                  </div>
                </div> -->
				        <div class="col-md-4">
                  <div class="box-body">
                    <div><label>Publish Date</label></div>
                      <?php echo $this->Form->control('publish_date',['label' => false,'type' =>'type','required'=>true,'class'=>'datepicker']);?>
                  </div>
                </div>
                
                <div class="col-md-4"> 
                  <div class="box-body">
                    <div><label for="status">Status</label></div> 
                    <?php $sOption = [0=>'Inactive',1=>'Active'];?>
                    <?=$this->form->select('status',$sOption,['required'=>true,'class'=>'form-control','empty'=>'--Select--','value'=>@$status])?>
                  </div>
                </div>
              </div>
                
				      <div class="row">
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
            $("#role-add-frm").validate();
        }
    });
})($);
</script>
<?php $this->end(); ?>