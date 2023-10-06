<?php
/**
 * @author \Nilesh Kushvaha
 * @version \1.1
 * @since \November 29, 2018, 10:44 am
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\LocaleTarget[]|\Cake\Collection\CollectionInterface $localeTargets
 */
$this->assign('title',__('Locale Targets'));
$this->Breadcrumbs->add(__('Locale Targets'));
?>
  <!-- Main content -->
  <section class="content localeTarget index">
    <div class="row">
      <div class="col-xs-12">
        <?= $this->Flash->render(); ?>
        <?= $this->Flash->render('auth'); ?>
        <div class="box box-primary">
          <div class="box-header">
            <h3 class="box-title"><?= __('List of') ?> <?= __('Locale Targets') ?></h3>
            <div class="box-tools">
              <?=$this->Html->link(
                      __('<i class="glyphicon glyphicon-plus"></i> New Locale Target'),
                      ['action' => 'add'],
                      ['class' => 'btn btn-success', 'escape' => false, 'title' => __('Add New Locale Target')]
                  );?>
            </div>
          </div>
          <!-- /.box-header -->
          <div class="box-body table-responsive">
            <table class="table table-hover table-bordered">
              <thead>
                <tr>
                    <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('locale_source_id') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('translation') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('language') ?></th>
                    <th width="100" scope="col" class="actions"><?= __('Actions') ?></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($localeTargets as $localeTarget): ?>
                  <tr>
                        <td><?= $this->Number->format($localeTarget->id) ?></td>
                        <td><?= $localeTarget->has('locale_source') ? $this->Html->link($localeTarget->locale_source->source, ['controller' => 'LocaleSources', 'action' => 'view', $localeTarget->locale_source->id]) : '' ?></td>
                        <td><?= h($localeTarget->translation) ?></td>
                        <td><?= h($localeTarget->language) ?></td>
                        <td class="actions">
                            <?= $this->Html->link('<i class="fa fa-eye"></i>', ['action' => 'view', $localeTarget->id],['class' => 'btn btn-info btn-xs', 'title' => __('View'), 'escape' => false]) ?>
                            <?= $this->Html->link('<i class="fa fa-edit"></i>', ['action' => 'edit', $localeTarget->id],['class' => 'btn btn-warning btn-xs', 'title' => __('Edit'), 'escape' => false]) ?>
                            <?= $this->Form->postLink('<i class="fa fa-trash-o"></i>', ['action' => 'delete', $localeTarget->id], ['confirm' => __('Are you sure you want to delete this Locale Target?', $localeTarget->id),'class' => 'btn btn-danger btn-xs', 'title' => __('Delete'), 'escape' => false]) ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(count($localeTargets) == 0):?>
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