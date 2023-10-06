<?php
/**
 * @author \Nilesh Kushvaha
 * @version \1.1
 * @since \November 30, 2018, 5:00 pm
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\MenuRegion $menuRegion
 */
$this->assign('title',__('Edit Menu Region ( ' . $menuRegion->id .' ) '));
$this->Breadcrumbs->add(__('Menu Regions'),['action'=>'index']);
$this->Breadcrumbs->add(__('Edit Menu Region ( ' . $menuRegion->id .' ) '));
?>

  <!-- Main content -->
  <section class="content menuRegion edit form">
    <div class="row">
      <!-- left column -->
      <div class="col-md-12">
        <!-- general form elements -->
        <div class="box box-success">
          <div class="box-header with-border">
            <h3 class="box-title">
            <?= __('Menu Region') ?>
                          <small><?= __('Edit Menu Region  ( ' . $menuRegion->id .' ) ') ?></small>
          </h3>
          <div class="box-tools pull-right">
              <?=$this->Html->link(
                      '<i class="fa fa-arrow-circle-left"></i>',
                      ['action' => 'index'],
                      ['class' => 'btn btn-info btn-xs','title' => __('Back to Menu Regions'),'escape' => false]
                  );?>
                    <?= $this->Form->postLink(
                      '<i class="fa fa-trash-o"></i>',
                      ['action' => 'delete', $menuRegion->id],
                      ['confirm' => __('Are you sure you want to delete this Menu Region?', $menuRegion->id),'class' => 'btn btn-danger btn-xs','title' => __('Delete'),'escape' => false]
                  )?>
            </div>
          </div>
          <!-- /.box-header -->
          <!-- form start -->
          <?php
              echo $this->Form->create($menuRegion,['id' => 'menuRegion-edit-frm']); ?>
              <div class="box-body">
              <?php
                echo $this->Form->control('region');
              echo $this->Form->control('slug');
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
            $("#menuRegion-edit-frm").validate();
        }
    });
})($);
</script>
<?php $this->end(); ?>