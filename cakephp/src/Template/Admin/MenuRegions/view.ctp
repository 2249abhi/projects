<?php
/**
 * @author \Nilesh Kushvaha
 * @version \1.1
 * @since \November 30, 2018, 5:00 pm
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\MenuRegion $menuRegion
 */
$this->assign('title',__('Menu Region ( ' . $menuRegion->id . ' ) '));
$this->Breadcrumbs->add(__('Menu Regions'),['action'=>'index']);
$this->Breadcrumbs->add(__('Menu Region ( ' . $menuRegion->id . ' ) '));
?>

  <!-- Main content -->
  <section class="content menuRegion view">
     <div class="row">
        <div class="col-md-12">
           <div class="box box-solid">
              <div class="box-header with-border">
                 <i class="fa fa-info"></i>
                 <h3 class="box-title"><?= __('Menu Region ( ' . $menuRegion->id . ' ) ') ?></h3>
                 <div class="box-tools pull-right">
                  <?=$this->Html->link(
                    '<i class="glyphicon glyphicon-arrow-left"></i>',
                    ['action' => 'index'],
                    ['class' => 'btn btn-info btn-xs','title' => __('Back to Menu Region'),'escape' => false]
                  );?>
                  <?= $this->Html->link('<i class="glyphicon glyphicon-pencil"></i>', ['action' => 'edit', ],['class' => 'btn btn-warning  btn-xs', 'title' => __('Edit'), 'escape' => false]) ?>

                  <?= $this->Form->postLink(
                    '<i class="fa fa-trash-o"></i>',
                    ['action' => 'delete', $menuRegion->id],
                    ['confirm' => __('Are you sure you want to delete this Menu Region?', $menuRegion->id),'class' => 'btn btn-danger btn-xs','title' => __('Delete'),'escape' => false]
                )?>
              </div>
              </div>
              <!-- /.box-header -->
              <div class="box-body">
                 <dl class="dl-horizontal">
              <dt><?= __('Region') ?></dt>
              <dd><?= h($menuRegion->region) ?></dd>
              <dt><?= __('Slug') ?></dt>
              <dd><?= h($menuRegion->slug) ?></dd>
              <dt><?= __('Id') ?></dt>
              <dd><?= $this->Number->format($menuRegion->id) ?></dd>
              <dt><?= __('Status') ?></dt>
              <dd><?= $menuRegion->status ? __('Yes') : __('No'); ?></dd>
          </dl>
              </div>
              <!-- /.box-body -->
           </div>
           <!-- /.box -->
        </div>
        <!-- ./col -->
     </div>
     <!-- div -->
          <div class="related-menus view">
      <div class="row">
        <div class="col-xs-12">
           <div class="box">
              <div class="box-header">
                 <i class="fa fa-share-alt"></i>
                 <h3 class="box-title"><?= __('Related Menus') ?></h3>
              </div>
              <!-- /.box-header -->
              <div class="box-body table-responsive no-padding">
                 <table class="table table-hover table-bordered">
                    <thead>
                       <tr>
                          <th scope="col"><?= __('Id') ?></th>
                          <th scope="col"><?= __('Menu Region Id') ?></th>
                          <th scope="col"><?= __('Parent Id') ?></th>
                          <th scope="col"><?= __('Lft') ?></th>
                          <th scope="col"><?= __('Rght') ?></th>
                          <th scope="col"><?= __('Menu Title') ?></th>
                          <th scope="col"><?= __('Menu Type') ?></th>
                          <th scope="col"><?= __('Custom Link') ?></th>
                          <th scope="col"><?= __('Object Type') ?></th>
                          <th scope="col"><?= __('Object Id') ?></th>
                          <th scope="col"><?= __('Module Id') ?></th>
                          <th scope="col"><?= __('Redirection') ?></th>
                          <th scope="col"><?= __('Sort Order') ?></th>
                          <th scope="col"><?= __('Status') ?></th>
                          <th scope="col" class="actions"><?= __('Actions') ?></th>
                       </tr>
                   </thead>
                       <tbody>
                      <?php if (!empty($menuRegion->menus)): ?>
                          <?php foreach ($menuRegion->menus as $menus): ?>
                          <tr>
                              <td><?= h($menus->id) ?></td>
                              <td><?= h($menus->menu_region_id) ?></td>
                              <td><?= h($menus->parent_id) ?></td>
                              <td><?= h($menus->lft) ?></td>
                              <td><?= h($menus->rght) ?></td>
                              <td><?= h($menus->menu_title) ?></td>
                              <td><?= h($menus->menu_type) ?></td>
                              <td><?= h($menus->custom_link) ?></td>
                              <td><?= h($menus->object_type) ?></td>
                              <td><?= h($menus->object_id) ?></td>
                              <td><?= h($menus->module_id) ?></td>
                              <td><?= h($menus->redirection) ?></td>
                              <td><?= h($menus->sort_order) ?></td>
                              <td><?= h($menus->status) ?></td>
                              <td class="actions">
                                  <?= $this->Html->link('<i class="fa fa-eye"></i>', ['controller' => 'Menus', 'action' => 'view', $menus->id],['class' => 'btn btn-info btn-xs', 'title' => __('View'), 'escape' => false]) ?>
                                  <?= $this->Html->link('<i class="fa fa-edit"></i>', ['controller' => 'Menus', 'action' => 'edit', $menus->id],['class' => 'btn btn-warning btn-xs', 'title' => __('Edit'), 'escape' => false]) ?>
                                  <?= $this->Form->postLink('<i class="fa fa-trash-o"></i>', ['controller' => 'Menus', 'action' => 'delete', $menus->id], ['confirm' => __('Are you sure you want to delete this Menus?', $menus->id),'class' => 'btn btn-danger btn-xs', 'title' => __('Delete'), 'escape' => false]) ?>
                              </td>
                          </tr>
                          <?php endforeach; ?>
                      <?php else: ?>
                          <tr>
                              <td colspan="15"><?= __('Record not found!'); ?></td>
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