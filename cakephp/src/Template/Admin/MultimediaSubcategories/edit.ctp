<?php
/**
 * @author \Nilesh Kushvaha
 * @version \1.1
 * @since \February 2, 2019, 11:47 am
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\GalleryCategory $galleryCategory
 */
$this->assign('title',__('Edit New Photo Multimedia Category'));
$this->Breadcrumbs->add(__('Photo Multimedia Categories'),['action'=>'index']);
$this->Breadcrumbs->add(__('Edit New Photo Multimedia Category'));
$userId  = $this->request->getSession()->read('Auth.User.id');
?>

  <!-- Main content -->
  <section class="content galleryCategory add form">
    <div class="row">
      <!-- left column -->
      <div class="col-md-12">
        <!-- general form elements -->
        <div class="box box-success">
          <div class="box-header with-border">
            <h3 class="box-title">
              <?= __('Multimedia Category') ?>
              <small><?= __('Edit New Photo Multimedia Category') ?></small>
            </h3>
            <div class="box-tools pull-right">
              <?=$this->Html->link(
                '<i class="fa fa-arrow-circle-left"></i>',
                ['action' => 'index'],
                ['class' => 'btn btn-info btn-xs','title' => __('Back to Photo  Gallery Categories'),'escape' => false]
              );?>
            </div>
          </div>
          <!-- /.box-header -->
          <!-- form start -->
          <?= $this->Form->create($galleryCategory,['id' => 'galleryCategory-add-frm', 'type'=>'file']); ?>
            <div class="box-body">
              <?php
                echo $this->Form->control('multimedia_category_id', ['options' => $category, 'empty' =>'--Select--', 'required'=>'required', 'value'=>$galleryCategory['multimedia_category_id']]);
                echo $this->Form->input('title', ['required'=>'required']);
                echo "<br><b>Sub Category Image</b>  ". $this->Form->file('category_image', ['required'=>'required']);
              ?>
            </div>
            <div class="box-body">
              <div><label>Event Date</label></div>
                <?php echo $this->Form->control('event_date',['label' => false,'type' =>'text','required'=>true,'class'=>'datepicker', 'value'=>date('d-m-Y', strtotime($galleryCategory['event_date']))]);?>
            </div>
            <?php 
                echo "<br><b>Priority</b>". $this->Form->number('priority', ['required'=>'required']);
                echo "<br><b>Status</b> ". $this->Form->checkbox('status');
             ?>
           </div>
            <div class="box-footer">
              <?php 
              echo $this->Form->button(__('Submit'),['class' => 'btn btn-primary']);
              echo $this->Html->link(__('Cancel'),['action' => 'index'],['class' => 'btn btn-danger mx-1']); ?>
              <input type="hidden" name="user_id" value="<?=$userId?>">
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
        $("#galleryCategory-add-frm").validate();
        
    });
})($);
</script>
<?php $this->end(); ?>