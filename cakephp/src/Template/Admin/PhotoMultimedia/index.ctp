<?php
/*
 * @author \Aman Tiwari
 * @version \1.1
 * @since \February 2, 2019, 11:47 am
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\GalleryCategory[]|\Cake\Collection\CollectionInterface $galleryCategories
 */
$this->assign('title',__('Multimedia Photos'));
$this->Breadcrumbs->add(__('Multimedia Photos'));
?>
  <!-- Main content -->
  <section class="content galleryCategory index">
    <div class="row">
      <div class="col-md-12">
        <?= $this->Flash->render(); ?>
        <div class="box box-primary">
          <div class="box-header">
              <h3 class="box-title"><?= __('Search - Photos Multimedia'); ?></h3>
          </div> 
          <?= $this->Form->create('PhotoMultimedia',['id' =>'searchPhoto','type'=>'get']); ?>
            <div class="box-body table-responsive">
              <?= $this->element('errorlist'); ?> 
              <div class="row2">
                <div class="col-md-4">
                  <div class="form-group">
                    <?php echo $this->Form->control('multimedia_category_id', ['options' => $category, 'empty' =>'--Select--', 'value'=>$multimedia_category_id]); ?>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <?php echo $this->Form->control('multimedia_subcategory_id', ['empty' =>'--Select--', 'value'=>$multimedia_subcategory_id]); ?>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <?php echo $this->Form->control('title', ['value'=>$title]); ?>
                  </div>
                </div>
              </div>
              <div class="clearfix"></div>
              <div class="row3">
                <div class="col-md-5"></div>
                <div class="col-md-1"><button name="search_button" value="search_button" type="submit" class="btn btn-primary btn-green">Search</button></div>
                <div class="col-md-1">
                  <?= $this->Html->link(__('Reset'),['controller'=>'PhotoMultimedia','action'=>'index'],['class'=>'btn btn-danger']); ?>
                </div>
              </div>
              <div class="clearfix"></div>
          </div>
          <?= $this->Form->end(); ?>
          </div>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-12">
      <?= $this->Flash->render() ; ?>
        <div class="box box-primary">
          <div class="box-header">
            <h3 class="box-title"><?= __('List of') ?> <?= __('Multimedia Photos') ?></h3>
            <div class="box-tools">
              <?=$this->Html->link(
                __('<i class="glyphicon glyphicon-plus"></i> Add New Multimedia Photo'),
                ['action' => 'add'],
                ['class' => 'btn btn-success', 'escape' => false, 'title' => __('Add New Multimedia Photo')]
              );?>
            </div>
          </div>
          <!-- /.box-header -->
          <div class="box-body table-responsive">
            <table class="table table-hover table-bordered">
              <thead>
                <tr>
                    <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                    <!-- <th scope="col"><?= $this->Paginator->sort('category') ?></th>-->
                    <th scope="col"><?php echo 'Category'; ?></th>
                    <th scope="col"><?php echo 'Sub Category'; ?></th>
                    <th scope="col"><?= $this->Paginator->sort('title') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('status') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                    <th width="100" scope="col" class="actions"><?= __('Actions') ?></th>
                </tr>
              </thead>
              <tbody>
                <?php 
                  $paginatorInformation = $this->Paginator->params();
                  $pageOffset=($paginatorInformation['page']-1);
                  $perPage = $paginatorInformation['perPage'];
                  $counter = ($pageOffset*$perPage);
                  $i = 1;
                  foreach ($galleryCategories as $galleryCategory): ?>
                  <tr>
                    <td><?= $this->Number->format($i++) ?></td>
                    <td><?= h($galleryCategory->multimedia_category->title) ?></td>
                    <td><?= h($galleryCategory->multimedia_subcategory->title) ?></td>
                    <td><?= h($galleryCategory->title) ?></td>
                    <td><?= $galleryCategory->status ? __('Active') : __('Inactive'); ?></td>
                    <td><?= h(date('Y-m-d H:i A',strtotime($galleryCategory->created))); ?></td>
                    <td class="actions">
                        <?php  $this->Html->link('<i class="fa fa-eye"></i>', ['action' => 'view', $galleryCategory->id],['class' => 'btn btn-info btn-xs', 'title' => __('View'), 'escape' => false]) ?>
                        <?= $this->Html->link('<i class="fa fa-edit"></i>', ['action' => 'edit', $galleryCategory->id],['class' => 'btn btn-warning btn-xs', 'title' => __('Edit'), 'escape' => false]) ?>
                        <?php echo $this->Form->postLink('<i class="fa fa-trash-o"></i>', ['action' => 'delete', $galleryCategory->id], ['confirm' => __('Are you sure you want to delete this Gallery Category?', $galleryCategory->id),'class' => 'btn btn-danger btn-xs', 'title' => __('Delete'), 'escape' => false]) ?>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(count($galleryCategories) == 0):?>
                    <tr>
                        <td colspan="8"><?= __('Record not found!'); ?></td>
                    </tr>
                    <?php endif;?>
              </tbody>
            </table>
          </div>
          <!-- /.box-body -->
          <div class="box-footer clearfix">
            <ul class="pagination pagination-sm no-margin pull-right">
              <?= $this->Paginator->first('<<') ?>
              <?= $this->Paginator->prev('<') ?>
              <?= $this->Paginator->numbers() ?>
              <?= $this->Paginator->next('>') ?>
              <?= $this->Paginator->last('>>') ?>
            </ul>
          </div>
        </div>
        <!-- /.box -->
      </div>
    </div>
  </section>

  <?php $this->append('bottom-script');?>
<script>
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