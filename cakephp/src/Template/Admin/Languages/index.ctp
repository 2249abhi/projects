<?php
/**
 * @author \Nilesh Kushvaha
 * @version \1.1
 * @since \December 17, 2018, 3:58 pm
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Language[]|\Cake\Collection\CollectionInterface $languages
 */
$this->assign('title',__('Languages'));
$this->Breadcrumbs->add(__('Languages'));
?>
  <!-- Main content -->
  <section class="content language index">
    <div class="row">
      <div class="col-xs-12">
      <?= $this->Flash->render() ; ?>
        <div class="box box-primary">
          <div class="box-header">
            <h3 class="box-title"><?= __('List of') ?> <?= __('Languages') ?></h3>
            <div class="box-tools">
              <?=$this->Html->link(
                      __('<i class="glyphicon glyphicon-plus"></i> New Language'),
                      ['action' => 'add'],
                      ['class' => 'btn btn-success', 'escape' => false, 'title' => __('Add New Language')]
                  );?>
            </div>
          </div>
          <!-- /.box-header -->
          <div class="box-body table-responsive">
            <table class="table table-hover table-bordered">
              <thead>
                <tr>
                    <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('culture') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('direction') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('is_default') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('is_system') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('status') ?></th>
                    <th width="100" scope="col" class="actions"><?= __('Actions') ?></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($languages as $language): ?>
                  <tr>
                        <td><?= $this->Number->format($language['id']) ?></td>
                        <td><?= h($language['name']) ?></td>
                        <td><?= h($language['culture']) ?></td>
                        <td><?= h($language['direction']) ?></td>
                        <td><?= $language['is_default'] ? __('Yes') : __('No'); ?></td>
                        <td><?= h($language['is_system']) ? __('Yes') : __('No'); ?></td>
                        <td><?= h($language['status']) ? __('Active') : __('Inactive'); ?></td>
                        <td class="actions">
                            <?= $this->Html->link('<i class="fa fa-eye"></i>', ['action' => 'view', $language['id']],['class' => 'btn btn-info btn-xs', 'title' => __('View'), 'escape' => false]) ?>
                            <?= $this->Html->link('<i class="fa fa-edit"></i>', ['action' => 'edit', $language['id']],['class' => 'btn btn-warning btn-xs', 'title' => __('Edit'), 'escape' => false]) ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(count($languages) == 0):?>
                    <tr>
                        <td colspan="10"><?= __('Record not found!'); ?></td>
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