<?php
/**
 * @author \Nilesh Kushvaha
 * @version \1.1
 * @since \February 2, 2019, 11:47 am
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\GalleryCategory $galleryCategory
 */
$this->assign('title',__('Add New Video Multimedia '));
$this->Breadcrumbs->add(__('Video Multimedia '),['action'=>'index']);
$this->Breadcrumbs->add(__('Add New Video'));
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
              <small><?= __('Add New Video') ?></small>
            </h3>
            <div class="box-tools pull-right">
              <?=$this->Html->link(
                '<i class="fa fa-arrow-circle-left"></i>',
                ['action' => 'index'],
                ['class' => 'btn btn-info btn-xs','title' => __('Back to Videos'),'escape' => false]
              );?>
            </div>
          </div>
          <!-- /.box-header -->
          <!-- form start -->
          <?= $this->Form->create($galleryCategory,['id' => 'galleryCategory-add-frm', 'type'=>'file']); ?>
            <div class="box-body">
              <?php
                echo $this->Form->control('multimedia_category_id', ['options' => $category, 'empty' =>'--Select--', 'required'=>'required']);
                /*echo $this->Form->control('multimedia_subcategory_id', ['empty' =>'--Select--', 'required'=>'required']);*/
                echo "<br> Title".$this->Form->textarea('title', ['required'=>'required']);
                echo $this->Form->input('video_link', ['required'=>'required']);
                echo "<br><b>Image</b>  ". $this->Form->file('image');
              ?>
              <div class="box-body">
                <div><label>Event Date</label></div>
                <?php echo $this->Form->control('event_date',['label' => false,'type' =>'text','required'=>true,'class'=>'datepicker']);?>
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

  $( function() {
    $( ".datepicker" ).datepicker({ dateFormat: 'dd-mm-yy' });
  } );
  

$('#multimedia-category-id').on('change', function(){
  var category_id = $('#multimedia-category-id').val();
  $.ajax({
    url: "<?php echo $this->Url->build(['controller'=>'PhotoMultimedia', 'action'=>'getSubCategory']) ?>", 
    type: "POST",             
    data : {category_id : category_id},
    beforeSend: function (xhr) {
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
    },
    success: function(data) {
      $('#multimedia-subcategory-id').html(data);
    }
  });
})
</script>
<?php $this->end(); ?>