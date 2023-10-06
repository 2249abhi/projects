<?php
/**
 * @author \Nilesh Kushvaha
 * @version \1.1
 * @since \December 17, 2018, 3:58 pm
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Language $language
 */
$this->assign('title',__('Edit Language ( ' . $language->name .' ) '));
$this->Breadcrumbs->add(__('Languages'),['action'=>'index']);
$this->Breadcrumbs->add(__('Edit Language ( ' . $language->name .' ) '));
?>

  <!-- Main content -->
  <section class="content language edit form">
    <div class="row">
      <!-- left column -->
      <div class="col-md-12">
        <?= $this->Flash->render() ; ?>
        <!-- general form elements -->
        <div class="box box-success">
          <div class="box-header with-border">
            <h3 class="box-title">
            <?= __('Language') ?>
              <small><?= __('Edit Language  ( ' . $language->name .' ) ') ?></small>
          </h3>
          <div class="box-tools pull-right">
              <?=$this->Html->link(
                      '<i class="fa fa-arrow-circle-left"></i>',
                      ['action' => 'index'],
                      ['class' => 'btn btn-info btn-xs','title' => __('Back to Languages'),'escape' => false]
                  );?>
                    <?= $this->Form->postLink(
                      '<i class="fa fa-trash-o"></i>',
                      ['action' => 'delete', $language->id],
                      ['confirm' => __('Are you sure you want to delete this Language?', $language->id),'class' => 'btn btn-danger btn-xs','title' => __('Delete'),'escape' => false]
                  )?>
            </div>
          </div>
          <!-- /.box-header -->
          <!-- form start -->
          <?php
              echo $this->Form->create($language,['id' => 'language-edit-frm']); ?>
              <div class="box-body">
              <?php
                echo $this->Form->control('name');
                echo $this->Form->control('culture');
                echo $this->Form->control('direction');
                echo $this->Form->control('is_default');
                echo $this->Form->control('is_system');
                echo $this->Form->control('status');
                //echo $this->Form->control('modified_at');
           ?>
           <input type="hidden" name="modified_ats" value="<?= time();?>">
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
            $("#language-edit-frm").validate();
        }
    });
})($);
</script>
<?php $this->end(); ?>