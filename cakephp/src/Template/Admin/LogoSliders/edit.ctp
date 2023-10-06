<?php
/**
 * @author \Nilesh Kushvaha
 * @version \1.1
 * @since \November 27, 2018, 10:52 am
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\LogoSlider $logoSlider
 */
$this->assign('title',__('Edit Logo Slider ( ' . $logoSlider->title .' ) '));
$this->Breadcrumbs->add(__('Logo Sliders'),['action'=>'index']);
$this->Breadcrumbs->add(__('Edit Logo Slider ( ' . $logoSlider->title .' ) '));
?>

  <!-- Main content -->
  <section class="content logoSlider edit form">
    <div class="row">
      <!-- left column -->
      <div class="col-md-12">
        <!-- general form elements -->
        <div class="box box-success">
          <div class="box-header with-border">
            <h3 class="box-title">
            <?= __('Logo Slider') ?>
                          <small><?= __('Edit Logo Slider  ( ' . $logoSlider->title .' ) ') ?></small>
          </h3>
          <div class="box-tools pull-right">
              <?=$this->Html->link(
                      '<i class="fa fa-arrow-circle-left"></i>',
                      ['action' => 'index'],
                      ['class' => 'btn btn-info btn-xs','title' => __('Back to Logo Sliders'),'escape' => false]
                  );?>
                    <?= $this->Form->postLink(
                      '<i class="fa fa-trash-o"></i>',
                      ['action' => 'delete', $logoSlider->id],
                      ['confirm' => __('Are you sure you want to delete this Logo Slider?', $logoSlider->id),'class' => 'btn btn-danger btn-xs','title' => __('Delete'),'escape' => false]
                  )?>
            </div>
          </div>
          <!-- /.box-header -->
          <!-- form start -->
          <?= $this->Form->create($logoSlider,['id' => 'logoSlider-edit-frm','type'=>'file']); ?>
              <div class="box-body">
              <?php
                echo $this->Form->control('title'); ?>
                <div class="input-area file">
                  <div class="form-group">
                    <label for="logo-image">Logo Image <?php if ($logoSlider->logo_image) { ?><a title="View" target="_blank" href="<?= $this->Url->build('/files/logo/'. $logoSlider->logo_image);?>"><?=$logoSlider->logo_image?></a> <?php } ?></label>
                    <input type="file" name="logo_image" accept="image/*" id="logo-image" class="form-control" onchange="return imageFileValidation(this)">
                    <input type="hidden" name="old_logo_image" value="<?=$logoSlider->logo_image?>">
                    <p class="help-block">Allowed File : jpeg, jpg, png and gif</p>
                  </div>
                </div>
                <?php 
              echo $this->Form->control('logo_cat_id', ['options' => $logoCategory,'empty'=>'--Select--']);
              echo $this->Form->control('website');
              echo $this->Form->control('status');
           ?>
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
            $("#logoSlider-edit-frm").validate();
        }
    });
})($);

function imageFileValidation(fileInput){
  var filePath = fileInput.value;
  var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif)$/i;
  var invalidfile = filePath.match(/\./g).length;
  if (invalidfile>1) {
    alert("Invalid File. e.g. double dot.");
    fileInput.value = '';
    return false;
  }else if(!allowedExtensions.exec(filePath)){
    alert('Please upload file having extensions .jpeg/.jpg/.png/.gif only.');
    fileInput.value = '';
    return false;
  }
}
</script>
<?php $this->end(); ?>