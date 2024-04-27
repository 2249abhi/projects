<?php
/**
 * @author \Nikhil Tiwari
 * @version \1.1
 * @since \January 28, 2021, 8:32 pm
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Role $role
 */
$this->assign('title',__('Add New PoliticalLife'));
$this->Breadcrumbs->add(__('PoliticalLife'),['action'=>'index']);
$this->Breadcrumbs->add(__('Add New PoliticalLife'));
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
            <?= __('Blogs') ?>
                          <small><?= __('Add New PoliticalLife') ?></small>
          </h3>
          <div class="box-tools pull-right">
              <?=$this->Html->link(
                      '<i class="fa fa-arrow-circle-left"></i>',
                      ['action' => 'index'],
                      ['class' => 'btn btn-info btn-xs','title' => __('Back to PoliticalLife'),'escape' => false]
                  );?>
            </div>
          </div> 
          <!-- /.box-header -->
          <!-- form start -->
			<?php
              echo $this->Form->create($doc_type,['id' => 'role-add-frm','type'=>'file','autocomplete'=>'off']); ?>
              <div class="row">
                <div class="col-md-6">
                  <div class="box-body">
                    <div><label>PoliticalLife Title</label></div>
					            <?php echo $this->Form->control('title',['class'=>'','id'=>'title','type'=>'text','label'=>false,'value'=>@$doc_type['title'],'required'=>false]); ?>
                  </div>
                </div> 

                <div class="col-md-4">
                  <div class="box-body">
                    <div><label>Image</label></div>
                    <?php echo $this->Form->control('image',['label' => false,'type' =>'file','accept'=>'image/*', 'onchange'=>'return imageFileValidation(this)']);?>
                  </div>
                </div>				
              </div>
            <div class="row">
              <div class="col-md-12">
                <div class="box-body">
                  <div><label>Content</label></div>
                  <?php echo $this->Form->control('content',['class'=>'ckeditor','id'=>'body_desc','type' =>'textarea','label'=>false,'value'=>@$doc_type['content'],'required'=>false]); ?>
                </div>
              </div>
			      </div>
			      <div class="row">
              <div class="col-md-4">
                <div class="box-body">
                  <div><label>Oredr No</label></div>
                  <?php echo $this->Form->control('orderno',['id'=>'orderno','type' =>'number','label'=>false,'value'=>@$doc_type['orderno'],'required'=>false]); ?>
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