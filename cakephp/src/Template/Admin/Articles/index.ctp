<?php
/**
 * @author \Nilesh Kushvaha
 * @version \1.1
 * @since \November 30, 2018, 11:57 am
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Article[]|\Cake\Collection\CollectionInterface $articles
 */
$this->assign('title',__('Articles'));
$this->Breadcrumbs->add(__('Articles'));
$title = isset($title)?$title:'';
$subTitle = isset($sub_title)?$sub_title:'';
?>
  <!-- Main content -->
  <section class="content article index">
    <div class="row">
      <div class="col-md-12">
        <?= $this->Flash->render(); ?>
        <div class="box box-primary">
          <div class="box-header">
              <h3 class="box-title"><?= __('Search - Articles'); ?></h3>
          </div> 
          <?= $this->Form->create('searchArticles',['id' =>'searchArticles','type'=>'get']); ?>
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
                <div class="col-md-1"><label for="sub_title">Sub Title</label></div>
                <div class="col-md-4">
                  <div class="form-group">
                    <input type="text" name="sub_title" id="sub_title" maxlength="40" placeholder="Enter Sub title" class="form-control" value="<?=$subTitle?>">
                  </div>
                </div>
              </div>
              <div class="clearfix"></div>
              <div class="row3">
                <div class="col-md-5"></div>
                <div class="col-md-1"><button name="search_button" value="search_button" type="submit" class="btn btn-primary btn-green">Search</button></div>
                <div class="col-md-1">
                  <?php if(empty($title) && empty($subTitle)){ ?>
                    <button type="reset" class="btn btn-danger">Reset</button>                  
                  <?php } else { ?>
                    <?= $this->Html->link(__('Reset'),['controller'=>'articles','action'=>'index'],['class'=>'btn btn-danger']); ?>
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
        <div class="box box-primary">
          <div class="box-header">
            <h3 class="box-title"><?= __('List of') ?> <?= __('Articles') ?></h3>
            <div class="box-tools">
              <?=$this->Html->link(
                __('<i class="glyphicon glyphicon-plus"></i> New Article'),
                ['action' => 'add'],
                ['class' => 'btn btn-success', 'escape' => false, 'title' => __('Add New Article')]
              );?>
            </div>
          </div>
          <!-- /.box-header -->
          <div class="box-body table-responsive">
            <table class="table table-hover table-bordered">
              <thead>
                <tr>
                    <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('user_id','Author') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('title') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('url') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('status') ?></th>
                    <th width="140" scope="col"><?= $this->Paginator->sort('created_at') ?></th>
                    <th width="95" scope="col" class="actions"><?= __('Actions') ?></th>
                </tr>
              </thead>
              <tbody>
                <?php 
                $paginatorInformation = $this->Paginator->params();
                $pageOffset=($paginatorInformation['page']-1);
                $perPage = $paginatorInformation['perPage'];
                $counter = ($pageOffset*$perPage);
                $i = 1;
                foreach ($articles as $article): ?>
                  <tr>
                    <td><?= $this->Number->format($i+$counter) ?></td>
                    <td><?= $article->has('user') ? $this->Html->link($article->user->username, ['controller' => 'Users', 'action' => 'view', $article->user->id]) : '' ?></td>
                    <td><?= h($article->title) ?></td>
                    <?php
                      $lOption = ['prefix'=>false,'controller'=>'Articles','action'=>'page','id'=>$article->id];
                      $link = $this->Url->build($lOption, ['pass' => ['id'], 'fullBase' => true]);
                    ?>
                    <td><?=$this->Html->link($link, $link, ['target' => '_blank']);?></td>
                    <td><?=($article->status == 1) ? __('Active') : __('Inactive');?></td>
                    <td><?= date('Y-m-d H:i A',strtotime($article->created_at)) ?></td>
                    <td class="actions">
                      <?= $this->Html->link('<i class="fa fa-eye"></i>', ['action' => 'view', $article->id],['class' => 'btn btn-info btn-xs', 'title' => __('View'), 'escape' => false]) ?>
                      <?= $this->Html->link('<i class="fa fa-edit"></i>', ['action' => 'edit', $article->id],['class' => 'btn btn-warning btn-xs', 'title' => __('Edit'), 'escape' => false]) ?>
                      <?= $this->Form->postLink('<i class="fa fa-trash-o"></i>', ['action' => 'delete', $article->id], ['confirm' => __('Are you sure you want to delete this Article?', $article->id),'class' => 'btn btn-danger btn-xs', 'title' => __('Delete'), 'escape' => false]) ?>
                    </td>
                  </tr>
                  <?php $i++;
                  endforeach; ?>
                  <?php if(count($articles) == 0):?>
                  <tr>
                      <td colspan="13"><?= __('Record not found!'); ?></td>
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