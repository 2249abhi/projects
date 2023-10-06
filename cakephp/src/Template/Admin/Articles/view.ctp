<?php
/**
 * @author \Nilesh Kushvaha
 * @version \1.1
 * @since \November 30, 2018, 11:57 am
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Article $article
 */
$this->assign('title',__('Article ( ' . $article->title . ' ) '));
$this->Breadcrumbs->add(__('Articles'),['action'=>'index']);
$this->Breadcrumbs->add(__('Article ( ' . $article->title . ' ) '));
?>

  <!-- Main content -->
  <section class="content article view">
     <div class="row">
        <div class="col-md-12">
           <div class="box box-solid">
              <div class="box-header with-border">
                 <i class="fa fa-info"></i>
                 <h3 class="box-title"><?= __('Article ( ' . $article->title . ' ) ') ?></h3>
                 <div class="box-tools pull-right">
                  <?=$this->Html->link(
                    '<i class="glyphicon glyphicon-arrow-left"></i>',
                    ['action' => 'index'],
                    ['class' => 'btn btn-info btn-xs','title' => __('Back to Article'),'escape' => false]
                  );?>
                  <?= $this->Html->link('<i class="glyphicon glyphicon-pencil"></i>', ['action' => 'edit', $article->id],['class' => 'btn btn-warning  btn-xs', 'title' => __('Edit'), 'escape' => false]) ?>

                  <?= $this->Form->postLink(
                    '<i class="fa fa-trash-o"></i>',
                    ['action' => 'delete', $article->id],
                    ['confirm' => __('Are you sure you want to delete this Article?', $article->id),'class' => 'btn btn-danger btn-xs','title' => __('Delete'),'escape' => false]
                )?>
              </div>
              </div>
              <!-- /.box-header -->
              <div class="box-body">
                <dl class="dl-horizontal">
                  <dt><?= __('Id') ?></dt>
                  <dd><?= $this->Number->format($article->id) ?></dd>
                  <dt><?= __('User Id') ?></dt>
                  <dd><?= $this->Number->format($article->user_id) ?></dd>
                  <dt><?= __('Sort Order') ?></dt>
                  <dd><?= $this->Number->format($article->sort_order) ?></dd>
                  <dt><?= __('Created At') ?></dt>
                  <dd><?= h($article->created_at) ?></dd>
                  <dt><?= __('Modified At') ?></dt>
                  <dd><?= h($article->modified_at) ?></dd>
                  <dt><?= __('Status') ?></dt>
                  <dd><?= $article->status ? __('Yes') : __('No'); ?></dd>
                  <dt><?= __('Title') ?></dt>
                  <dd><?= $this->Text->autoParagraph(h($article->title)); ?></dd>
                  <dt><?= __('Slug') ?></dt>
                  <dd><?= $this->Text->autoParagraph(h($article->slug)); ?></dd>
                  <dt><?= __('Excerpt') ?></dt>
                  <dd><?= $this->Text->autoParagraph(h($article->excerpt)); ?></dd>
                  <dt><?= __('Content') ?></dt>
                  <dd><?= $this->Text->autoParagraph(h($article->content)); ?></dd>
                  <dt><?= __('Url') ?></dt>
                  <dd><?= $this->Text->autoParagraph(h($article->url)); ?></dd>
                </dl>
              </div>
              <!-- /.box-body -->
           </div>
           <!-- /.box -->
        </div>
        <!-- ./col -->
     </div>
     <!-- div -->
 </section>