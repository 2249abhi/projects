<?php
/**
 * @author \Nilesh Kushvaha
 * @version \1.1
 * @since \December 17, 2018, 3:58 pm
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Language $language
 */
$this->assign('title',__('Language ( ' . $language->name . ' ) '));
$this->Breadcrumbs->add(__('Languages'),['action'=>'index']);
$this->Breadcrumbs->add(__('Language ( ' . $language->name . ' ) '));
?>

  <!-- Main content -->
  <section class="content language view">
     <div class="row">
        <div class="col-md-12">
           <div class="box box-solid">
              <div class="box-header with-border">
                 <i class="fa fa-info"></i>
                 <h3 class="box-title"><?= __('Language ( ' . $language->name . ' ) ') ?></h3>
                 <div class="box-tools pull-right">
                  <?=$this->Html->link(
                    '<i class="glyphicon glyphicon-arrow-left"></i>',
                    ['action' => 'index'],
                    ['class' => 'btn btn-info btn-xs','title' => __('Back to Language'),'escape' => false]
                  );?>
                  <?= $this->Html->link('<i class="glyphicon glyphicon-pencil"></i>', ['action' => 'edit', ],['class' => 'btn btn-warning  btn-xs', 'title' => __('Edit'), 'escape' => false]) ?>

                  <?= $this->Form->postLink(
                    '<i class="fa fa-trash-o"></i>',
                    ['action' => 'delete', $language->id],
                    ['confirm' => __('Are you sure you want to delete this Language?', $language->id),'class' => 'btn btn-danger btn-xs','title' => __('Delete'),'escape' => false]
                )?>
              </div>
              </div>
              <!-- /.box-header -->
              <div class="box-body">
                 <dl class="dl-horizontal">
              <dt><?= __('Name') ?></dt>
              <dd><?= h($language->name) ?></dd>
              <dt><?= __('Culture') ?></dt>
              <dd><?= h($language->culture) ?></dd>
              <dt><?= __('Direction') ?></dt>
              <dd><?= h($language->direction) ?></dd>
              <dt><?= __('Id') ?></dt>
              <dd><?= $this->Number->format($language->id) ?></dd>
              <dt><?= __('Created At') ?></dt>
              <dd><?= h($language->created_at) ?></dd>
              <dt><?= __('Modified At') ?></dt>
              <dd><?= h($language->modified_at) ?></dd>
              <dt><?= __('Is Default') ?></dt>
              <dd><?= $language->is_default ? __('Yes') : __('No'); ?></dd>
              <dt><?= __('Is System') ?></dt>
              <dd><?= $language->is_system ? __('Yes') : __('No'); ?></dd>
              <dt><?= __('Status') ?></dt>
              <dd><?= $language->status ? __('Yes') : __('No'); ?></dd>
          </dl>
              </div>
              <!-- /.box-body -->
           </div>
           <!-- /.box -->
        </div>
        <!-- ./col -->
     </div>
     <!-- div -->
          <div class="related-tender_translations view">
      <div class="row">
        <div class="col-xs-12">
           <div class="box">
              <div class="box-header">
                 <i class="fa fa-share-alt"></i>
                 <h3 class="box-title"><?= __('Related Tender Translations') ?></h3>
              </div>
              <!-- /.box-header -->
              <div class="box-body table-responsive no-padding">
                 <table class="table table-hover table-bordered">
                    <thead>
                       <tr>
                          <th scope="col"><?= __('Id') ?></th>
                          <th scope="col"><?= __('Tender Id') ?></th>
                          <th scope="col"><?= __('Language Id') ?></th>
                          <th scope="col"><?= __('Culture') ?></th>
                          <th scope="col"><?= __('Title') ?></th>
                          <th scope="col"><?= __('Remarks') ?></th>
                          <th scope="col"><?= __('Content') ?></th>
                          <th scope="col"><?= __('Slug') ?></th>
                          <th scope="col"><?= __('Url') ?></th>
                          <th scope="col" class="actions"><?= __('Actions') ?></th>
                       </tr>
                   </thead>
                       <tbody>
                      <?php if (!empty($language->tender_translations)): ?>
                          <?php foreach ($language->tender_translations as $tenderTranslations): ?>
                          <tr>
                              <td><?= h($tenderTranslations->id) ?></td>
                              <td><?= h($tenderTranslations->tender_id) ?></td>
                              <td><?= h($tenderTranslations->language_id) ?></td>
                              <td><?= h($tenderTranslations->culture) ?></td>
                              <td><?= h($tenderTranslations->title) ?></td>
                              <td><?= h($tenderTranslations->remarks) ?></td>
                              <td><?= h($tenderTranslations->content) ?></td>
                              <td><?= h($tenderTranslations->slug) ?></td>
                              <td><?= h($tenderTranslations->url) ?></td>
                              <td class="actions">
                                  <?= $this->Html->link('<i class="fa fa-eye"></i>', ['controller' => 'TenderTranslations', 'action' => 'view', $tenderTranslations->id],['class' => 'btn btn-info btn-xs', 'title' => __('View'), 'escape' => false]) ?>
                                  <?= $this->Html->link('<i class="fa fa-edit"></i>', ['controller' => 'TenderTranslations', 'action' => 'edit', $tenderTranslations->id],['class' => 'btn btn-warning btn-xs', 'title' => __('Edit'), 'escape' => false]) ?>
                                  <?= $this->Form->postLink('<i class="fa fa-trash-o"></i>', ['controller' => 'TenderTranslations', 'action' => 'delete', $tenderTranslations->id], ['confirm' => __('Are you sure you want to delete this Tender Translations?', $tenderTranslations->id),'class' => 'btn btn-danger btn-xs', 'title' => __('Delete'), 'escape' => false]) ?>
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