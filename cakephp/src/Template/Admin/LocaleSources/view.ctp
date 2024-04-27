<?php
/**
 * @author \Nilesh Kushvaha
 * @version \1.1
 * @since \November 29, 2018, 10:24 am
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\LocaleSource $localeSource
 */
$this->assign('title',__('Locale Source ( ' . $localeSource->id . ' ) '));
$this->Breadcrumbs->add(__('Locale Sources'),['action'=>'index']);
$this->Breadcrumbs->add(__('Locale Source ( ' . $localeSource->id . ' ) '));
?>

  <!-- Main content -->
  <section class="content localeSource view">
     <div class="row">
        <div class="col-md-12">
           <div class="box box-solid">
              <div class="box-header with-border">
                 <i class="fa fa-info"></i>
                 <h3 class="box-title"><?= __('Locale Source ( ' . $localeSource->id . ' ) ') ?></h3>
                 <div class="box-tools pull-right">
                  <?=$this->Html->link(
                    '<i class="glyphicon glyphicon-arrow-left"></i>',
                    ['action' => 'index'],
                    ['class' => 'btn btn-info btn-xs','title' => __('Back to Locale Source'),'escape' => false]
                  );?>
                  <?= $this->Html->link('<i class="glyphicon glyphicon-pencil"></i>', ['action' => 'edit', $localeSource->id ],['class' => 'btn btn-warning  btn-xs', 'title' => __('Edit'), 'escape' => false]) ?>

                  <?= $this->Form->postLink(
                    '<i class="fa fa-trash-o"></i>',
                    ['action' => 'delete', $localeSource->id],
                    ['confirm' => __('Are you sure you want to delete this Locale Source?', $localeSource->id),'class' => 'btn btn-danger btn-xs','title' => __('Delete'),'escape' => false]
                )?>
              </div>
              </div>
              <!-- /.box-header -->
              <div class="box-body">
                 <dl class="dl-horizontal">
              <dt><?= __('Source') ?></dt>
              <dd><?= h($localeSource->source) ?></dd>
              <dt><?= __('Id') ?></dt>
              <dd><?= $this->Number->format($localeSource->id) ?></dd>
          </dl>
              </div>
              <!-- /.box-body -->
           </div>
           <!-- /.box -->
        </div>
        <!-- ./col -->
     </div>
     <!-- div -->
          <div class="related-locale_targets view">
      <div class="row">
        <div class="col-xs-12">
           <div class="box">
              <div class="box-header">
                 <i class="fa fa-share-alt"></i>
                 <h3 class="box-title"><?= __('Related Locale Targets') ?></h3>
              </div>
              <!-- /.box-header -->
              <div class="box-body table-responsive no-padding">
                 <table class="table table-hover table-bordered">
                    <thead>
                       <tr>
                          <th scope="col"><?= __('Id') ?></th>
                          <th scope="col"><?= __('Locale Source Id') ?></th>
                          <th scope="col"><?= __('Translation') ?></th>
                          <th scope="col"><?= __('Language') ?></th>
                          <th scope="col" class="actions"><?= __('Actions') ?></th>
                       </tr>
                   </thead>
                       <tbody>
                      <?php if (!empty($localeSource->locale_targets)): ?>
                          <?php foreach ($localeSource->locale_targets as $localeTargets): ?>
                          <tr>
                              <td><?= h($localeTargets->id) ?></td>
                              <td><?= h($localeTargets->locale_source_id) ?></td>
                              <td><?= h($localeTargets->translation) ?></td>
                              <td><?= h($localeTargets->language) ?></td>
                              <td class="actions">
                                  <?= $this->Html->link('<i class="fa fa-eye"></i>', ['controller' => 'LocaleTargets', 'action' => 'view', $localeTargets->id],['class' => 'btn btn-info btn-xs', 'title' => __('View'), 'escape' => false]) ?>
                                  <?= $this->Html->link('<i class="fa fa-edit"></i>', ['controller' => 'LocaleTargets', 'action' => 'edit', $localeTargets->id],['class' => 'btn btn-warning btn-xs', 'title' => __('Edit'), 'escape' => false]) ?>
                                  <?= $this->Form->postLink('<i class="fa fa-trash-o"></i>', ['controller' => 'LocaleTargets', 'action' => 'delete', $localeTargets->id], ['confirm' => __('Are you sure you want to delete this Locale Targets?', $localeTargets->id),'class' => 'btn btn-danger btn-xs', 'title' => __('Delete'), 'escape' => false]) ?>
                              </td>
                          </tr>
                          <?php endforeach; ?>
                      <?php else: ?>
                          <tr>
                              <td colspan="5"><?= __('Record not found!'); ?></td>
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