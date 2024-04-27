<?php
/**
 * @author \Nilesh Kushvaha
 * @version \1.1
 * @since \November 27, 2018, 10:52 am
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\LogoSlider[]|\Cake\Collection\CollectionInterface $logoSliders
 */
$this->assign('title',__('Logo Sliders'));
$this->Breadcrumbs->add(__('Logo Sliders'));
?>
  <!-- Main content -->
  <section class="content logoSlider index">
    <div class="row">
      <div class="col-xs-12">
      <?= $this->Flash->render() ; ?>
        <div class="box box-primary">
          <div class="box-header">
            <h3 class="box-title"><?= __('List of') ?> <?= __('Logo Sliders') ?></h3>
            <div class="box-tools">
              <?=$this->Html->link(
                      __('<i class="glyphicon glyphicon-plus"></i> New Logo Slider'),
                      ['action' => 'add'],
                      ['class' => 'btn btn-success', 'escape' => false, 'title' => __('Add New Logo Slider')]
                  );?>
            </div>
          </div>
          <!-- /.box-header -->
          <div class="box-body table-responsive">
            <table class="table table-hover table-bordered">
              <thead>
                <tr>
                    <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('title') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('logo_image') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('logo_cat_id','Logo Category') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('status') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                    <th width="100" scope="col" class="actions"><?= __('Actions') ?></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($logoSliders as $logoSlider): ?>
                  <tr>
                        <td><?= $this->Number->format($logoSlider->id) ?></td>
                        <td><?= h($logoSlider->title) ?></td>
                        <td><a title="View" class="btn btn-primary btn-sm" target="_blank" href="<?= $this->Url->build('/files/logo/'. $logoSlider->logo_image);?>">View</a></td>
                        <td><?= $logoCategory[$logoSlider->logo_cat_id]; ?></td>
                        <td><?= $logoSlider->status ? __('Active') : __('InActive'); ?></td>
                        <td><?= date('Y-m-d H:i A',strtotime($logoSlider->created)) ?></td>
                        <td class="actions">
                            <?= $this->Html->link('<i class="fa fa-eye"></i>', ['action' => 'view', $logoSlider->id],['class' => 'btn btn-info btn-xs', 'title' => __('View'), 'escape' => false]) ?>
                            <?= $this->Html->link('<i class="fa fa-edit"></i>', ['action' => 'edit', $logoSlider->id],['class' => 'btn btn-warning btn-xs', 'title' => __('Edit'), 'escape' => false]) ?>
                            <?= $this->Form->postLink('<i class="fa fa-trash-o"></i>', ['action' => 'delete', $logoSlider->id], ['confirm' => __('Are you sure you want to delete this Logo Slider?', $logoSlider->id),'class' => 'btn btn-danger btn-xs', 'title' => __('Delete'), 'escape' => false]) ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(count($logoSliders) == 0):?>
                    <tr>
                        <td colspan="7"><?= __('Record not found!'); ?></td>
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