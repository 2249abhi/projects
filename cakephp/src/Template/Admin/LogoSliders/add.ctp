<?php
/**
 * @author \Nilesh Kushvaha
 * @version \1.1
 * @since \November 27, 2018, 10:52 am
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\LogoSlider $logoSlider
 */
$this->assign('title',__('Add New Logo Slider'));
$this->Breadcrumbs->add(__('Logo Sliders'),['action'=>'index']);
$this->Breadcrumbs->add(__('Add New Logo Slider'));
$userId  = $this->request->getSession()->read('Auth.User.id');
?>

  <!-- Main content -->
  <section class="content logoSlider add form">
    <div class="row">
      <!-- left column -->
      <div class="col-md-12">
        <!-- general form elements -->
        <div class="box box-success">
          <div class="box-header with-border">
            <h3 class="box-title">
            <?= __('Logo Slider') ?>
              <small><?= __('Add New Logo Slider') ?></small>
            </h3>
            <div class="box-tools pull-right">
              <?=$this->Html->link(
                      '<i class="fa fa-arrow-circle-left"></i>',
                      ['action' => 'index'],
                      ['class' => 'btn btn-info btn-xs','title' => __('Back to Logo Sliders'),'escape' => false]
                  );?>
            </div>
          </div>
          <!-- /.box-header -->
          <!-- form start -->
          <?php
          echo $this->Form->create($logoSlider,['id' => 'logoSlider-add-frm','type'=>'file']); ?>
          <div class="box-body">
            <?php
            echo $this->Form->control('title');
            echo $this->Form->control('logo_image',['type'=>'file','accept'=>'image/*', 'onchange'=>'return imageFileValidation(this)']); ?>
            <p class="help-block">Allowed File : jpeg, jpg, png and gif</p>
          <?php  echo $this->Form->control('logo_cat_id', ['options' => $logoCategory,'empty'=>'--Select--']);
            echo $this->Form->control('website');
            echo $this->Form->control('status');
            ?>
          </div>
          <input type="hidden" name="user_id" value="<?=$userArray['id']?>">
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
            $("#logoSlider-add-frm").validate();
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