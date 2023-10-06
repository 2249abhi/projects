<?php
/**
 * @author \Nilesh Kushvaha
 * @version \1.1
 * @since \December 27, 2018, 11:25 am
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\News[]|\Cake\Collection\CollectionInterface $news
 */
$this->assign('title',__('Photo/Video'));
$this->Breadcrumbs->add(__('Photo/Video'));
$title = isset($title)?$title:'';
$status = isset($status)?$status:'';
?>
  <!-- Main content -->
  <section class="content news index">
    <div class="row">
      <div class="col-md-12">
        <?= $this->Flash->render(); ?>
        <div class="box box-primary">
          <div class="box-header">
              <h3 class="box-title"><?= __('Search - Photo/Video'); ?></h3>
          </div> 
          <?= $this->Form->create('searchNews',['id' =>'searchNews','type'=>'get']); ?>
            <div class="box-body table-responsive">
              <?= $this->element('errorlist'); ?> 
              <div class="row2">
                <div class="col-md-1"></div>
                <div class="col-md-1"><label for="title">Title</label></div>
                <div class="col-md-4">
                  <div class="form-group">
                    <input type="text" name="title" id="title" maxlength="40" placeholder="Enter title" class="form-control" value="<?=$title?>">
                  </div>
                </div>
                <div class="col-md-1"><label for="status">Status</label></div>
                <div class="col-md-4">
                  <div class="form-group">
                    <?php   $sOption = [0=>'Inactive',1=>'Active']; ?>
                  <?=$this->form->select('status',$sOption,['class'=>'form-control','empty'=>'--Select--','value'=>@$status])?>
                  </div>
                </div>
              </div>
              <div class="clearfix"></div>
              <div class="row3">
                <div class="col-md-5"></div>
                <div class="col-md-1"><button name="search_button" value="search_button" type="submit" class="btn btn-primary btn-green">Search</button></div>
                <div class="col-md-1">
                  <?php if(empty($title) && empty($status)){ ?>
                    <button type="reset" class="btn btn-danger">Reset</button>                  
                  <?php } else { ?>
                    <?= $this->Html->link(__('Reset'),['controller'=>'news','action'=>'photoList'],['class'=>'btn btn-danger']); ?>
                  <?php }?>
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
            <h3 class="box-title"><?= __('List of') ?> <?= __('Photo/Video') ?></h3>
            <div class="box-tools">
              <?=$this->Html->link(
                      __('<i class="glyphicon glyphicon-plus"></i> New Photo/Video'),
                      ['action' => 'addPhoto'],
                      ['class' => 'btn btn-success', 'escape' => false, 'title' => __('Add New Photo/Video')]
                  );?>
            </div>
          </div>
          <!-- /.box-header -->
          <div class="box-body table-responsive">
            <table class="table table-hover table-bordered">
              <thead>
                <tr>
                  <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                  <th scope="col" width="45%"><?= $this->Paginator->sort('title') ?></th>
                  <th scope="col" width="10%"><?= $this->Paginator->sort('category') ?></th>
                  <th width="120" scope="col"><?= $this->Paginator->sort('photo_date',"Date") ?></th>
                  <th width="90" scope="col"><?= $this->Paginator->sort('photo_order',"Order") ?></th>
                  <th scope="col"><?= $this->Paginator->sort('status') ?></th>
                  <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                  <th width="100" scope="col" class="actions"><?= __('Actions') ?></th>
                </tr>
              </thead>
              <tbody>
			  <?php $category = [1=>'Photo',2=>'Video'];?>
                <?php foreach ($news as $news): ?>
                  <tr>
                    <td><?= $this->Number->format($news->id) ?></td>
                    <td><?= h($news->title) ?></td>
                    <td><?= h($category[$news['category']]) ?></td>
                    <td><?= date('Y-m-d',strtotime($news->photo_date)) ?></td>
                    <td><?= $this->Number->format($news->photo_order) ?></td>
                    <td><?= $news->status ? __('Active') : __('InActive'); ?></td>
                    <td><?= date('Y-m-d H:i A',strtotime($news->created)) ?></td>
                    <td class="actions">
                      <?= $this->Html->link('<i class="fa fa-eye"></i>', ['action' => 'view', $news->id],['class' => 'btn btn-info btn-xs', 'title' => __('View'), 'escape' => false]) ?>
                      <?= $this->Html->link('<i class="fa fa-edit"></i>', ['action' => 'edit', $news->id],['class' => 'btn btn-warning btn-xs', 'title' => __('Edit'), 'escape' => false]) ?>
                      <?= $this->Form->postLink('<i class="fa fa-trash-o"></i>', ['action' => 'delete', $news->id], ['confirm' => __('Are you sure you want to delete this News?', $news->id),'class' => 'btn btn-danger btn-xs', 'title' => __('Delete'), 'escape' => false]) ?>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                  
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