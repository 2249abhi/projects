<?php
/**
 * @author \Nilesh Kushvaha
 * @version \1.1
 * @since \November 30, 2018, 5:00 pm
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\MenuRegion[]|\Cake\Collection\CollectionInterface $menuRegions
 */
$this->assign('title',__('Menu Regions'));
$this->Breadcrumbs->add(__('Menu Regions'));
?>
  <!-- Main content -->
  <section class="content menuRegion index">
    <div class="row">
      <div class="col-xs-12">
      <?= $this->Flash->render() ; ?>
        <div class="box box-primary">
          <div class="box-header">
            <h3 class="box-title"><?= __('List of') ?> <?= __('Menu Regions') ?></h3>
            <div class="box-tools">
              <?=$this->Html->link(
                      __('<i class="glyphicon glyphicon-plus"></i> New Menu Region'),
                      ['action' => 'add'],
                      ['class' => 'btn btn-success', 'escape' => false, 'title' => __('Add New Menu Region')]
                  );?>
            </div>
          </div>
          <!-- /.box-header -->
          <div class="box-body table-responsive">
            <table class="table table-hover table-bordered">
              <thead>
                <tr>
                    <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('region') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('slug') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('status') ?></th>
                    <th width="100" scope="col" class="actions"><?= __('Actions') ?></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($menuRegions as $menuRegion): ?>
                  <tr>
                        <td><?= $this->Number->format($menuRegion->id) ?></td>
                        <td><?= h($menuRegion->region) ?></td>
                        <td><?= h($menuRegion->slug) ?></td>
                        <td><?= h($menuRegion->status) ?></td>
                        <td class="actions">
                            <?= $this->Html->link('<i class="fa fa-bars"></i>', ['controller' => 'Menus', 'action' => 'index', $menuRegion->id],['class' => 'btn btn-info btn-xs', 'title' => __('Menu'), 'escape' => false]) ?>

                            <?= $this->Html->link('<i class="fa fa-edit"></i>', ['action' => 'edit', $menuRegion->id],['class' => 'btn btn-warning btn-xs', 'title' => __('Edit'), 'escape' => false]) ?>
                            <?= $this->Form->postLink('<i class="fa fa-trash-o"></i>', ['action' => 'delete', $menuRegion->id], ['confirm' => __('Are you sure you want to delete this Menu Region?', $menuRegion->id),'class' => 'btn btn-danger btn-xs', 'title' => __('Delete'), 'escape' => false]) ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(count($menuRegions) == 0):?>
                    <tr>
                        <td colspan="5"><?= __('Record not found!'); ?></td>
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