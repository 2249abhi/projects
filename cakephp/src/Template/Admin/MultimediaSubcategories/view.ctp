<?php
/**
 * @author \Nilesh Kushvaha
 * @version \1.1
 * @since \February 2, 2019, 11:47 am
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\GalleryCategory $galleryCategory
 */
$this->assign('title',__('Photo Gallery Category ( ' . $galleryCategory->title . ' ) '));
$this->Breadcrumbs->add(__('Photo Gallery Categories'),['action'=>'index']);
$this->Breadcrumbs->add(__('Photo Gallery Category ( ' . $galleryCategory->title . ' ) '));
?>

  <!-- Main content -->
  <section class="content galleryCategory view">
     <div class="row">
        <div class="col-md-12">
           <div class="box box-solid">
              <div class="box-header with-border">
                 <i class="fa fa-info"></i>
                 <h3 class="box-title"><?= __('Photo Gallery Category ( ' . $galleryCategory->title . ' ) ') ?></h3>
                 <div class="box-tools pull-right">
                  <?=$this->Html->link(
                    '<i class="glyphicon glyphicon-arrow-left"></i>',
                    ['action' => 'index'],
                    ['class' => 'btn btn-info btn-xs','title' => __('Back to Photo Gallery Category'),'escape' => false]
                  );?>
                  <?= $this->Html->link('<i class="glyphicon glyphicon-pencil"></i>', ['action' => 'edit', ],['class' => 'btn btn-warning  btn-xs', 'title' => __('Edit'), 'escape' => false]) ?>

                  <?= $this->Form->postLink(
                    '<i class="fa fa-trash-o"></i>',
                    ['action' => 'delete', $galleryCategory->id],
                    ['confirm' => __('Are you sure you want to delete this Photo Gallery Category?', $galleryCategory->id),'class' => 'btn btn-danger btn-xs','title' => __('Delete'),'escape' => false]
                )?>
              </div>
              </div>
              <!-- /.box-header -->
              <div class="box-body">
                 <dl class="dl-horizontal">
<!--              <dt><?= __('Article') ?></dt>
              <dd><?= $galleryCategory->has('article') ? $this->Html->link($galleryCategory->article->title, ['controller' => 'Articles', 'action' => 'view', $galleryCategory->article->id]) : '' ?></dd>-->
              <dt><?= __('User') ?></dt>
              <dd><?= $galleryCategory->has('user') ? $this->Html->link($galleryCategory->user->username, ['controller' => 'Users', 'action' => 'view', $galleryCategory->user->id]) : '' ?></dd>
              <dt><?= __('Title') ?></dt>
              <dd><?= h($galleryCategory->title) ?></dd>
              <dt><?= __('Id') ?></dt>
              <dd><?= $this->Number->format($galleryCategory->id) ?></dd>
              <dt><?= __('Created') ?></dt>
              <dd><?= h($galleryCategory->created) ?></dd>
              <dt><?= __('Status') ?></dt>
              <dd><?= $galleryCategory->status ? __('Yes') : __('No'); ?></dd>
              <dt><?= __('Content') ?></dt>
              <dd><?= $this->Text->autoParagraph(h($galleryCategory->content)); ?></dd>
          </dl>
              </div>
              <!-- /.box-body -->
           </div>
           <!-- /.box -->
        </div>
        <!-- ./col -->
     </div>
     <!-- div -->
          <div class="related-galleries view">
      <div class="row">
        <div class="col-xs-12">
           <div class="box">
              <div class="box-header">
                 <i class="fa fa-share-alt"></i>
                 <h3 class="box-title"><?= __('Related Galleries') ?></h3>
              </div>
              <!-- /.box-header -->
              <div class="box-body table-responsive no-padding">
                 <table class="table table-hover table-bordered">
                    <thead>
                       <tr>
                          <th scope="col"><?= __('Id') ?></th>
                          <th scope="col"><?= __('Gallery Category Id') ?></th>
                          <th scope="col"><?= __('Filename') ?></th>
                          <th scope="col"><?= __('Filemime') ?></th>
                          <th scope="col"><?= __('Filesize') ?></th>
                          <th scope="col"><?= __('Weight') ?></th>
                          <th scope="col"><?= __('Description') ?></th>
                          <th scope="col"><?= __('Status') ?></th>
                          <th scope="col"><?= __('Created') ?></th>
                          <th scope="col" class="actions"><?= __('Actions') ?></th>
                       </tr>
                   </thead>
                       <tbody>
                      <?php if (!empty($galleryCategory->galleries)): ?>
                          <?php foreach ($galleryCategory->galleries as $galleries): ?>
                          <tr>
                              <td><?= h($galleries->id) ?></td>
                              <td><?= h($galleries->gallery_category_id) ?></td>
                              <td><?= h($galleries->filename) ?></td>
                              <td><?= h($galleries->filemime) ?></td>
                              <td><?= h($galleries->filesize) ?></td>
                              <td><?= h($galleries->weight) ?></td>
                              <td><?= h($galleries->description) ?></td>
                              <td><?= h($galleries->status) ?></td>
                              <td><?= h($galleries->created) ?></td>
                              <td class="actions">
                                  <?= $this->Html->link('<i class="fa fa-eye"></i>', ['controller' => 'Galleries', 'action' => 'view', $galleries->id],['class' => 'btn btn-info btn-xs', 'title' => __('View'), 'escape' => false]) ?>
                                  <?= $this->Html->link('<i class="fa fa-edit"></i>', ['controller' => 'Galleries', 'action' => 'edit', $galleries->id],['class' => 'btn btn-warning btn-xs', 'title' => __('Edit'), 'escape' => false]) ?>
                                  <?= $this->Form->postLink('<i class="fa fa-trash-o"></i>', ['controller' => 'Galleries', 'action' => 'delete', $galleries->id], ['confirm' => __('Are you sure you want to delete this Galleries?', $galleries->id),'class' => 'btn btn-danger btn-xs', 'title' => __('Delete'), 'escape' => false]) ?>
                              </td>
                          </tr>
                          <?php endforeach; ?>
                      <?php else: ?>
                          <tr>
                              <td colspan="10"><?= __('Record not found!'); ?></td>
                          </tr>
                      <?php endif;?>
                    </tbody>
                 </table>
              </div>
              <!-- /.box-body -->
           </div>
           <!-- /.box -->
        </div>
     </div>
 </div>
 </section>