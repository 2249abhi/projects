<?php
/**
 * @author \Nilesh Kushvaha
 * @version \1.1
 * @since \October 29, 2018, 7:06 am
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Department $department
 */
$this->assign('title',__('Department ( ' . $department->name . ' ) '));
$this->Breadcrumbs->add(__('Departments'),['action'=>'index']);
$this->Breadcrumbs->add(__('Department ( ' . $department->name . ' ) '));
?>

  <!-- Main content -->
  <section class="content department view">
     <div class="row">
        <div class="col-md-12">
           <div class="box box-solid">
              <div class="box-header with-border">
                 <i class="fa fa-info"></i>
                 <h3 class="box-title"><?= __('Department ( ' . $department->name . ' ) ') ?></h3>
                 <div class="box-tools pull-right">
                  <?=$this->Html->link(
                    '<i class="glyphicon glyphicon-arrow-left"></i>',
                    ['action' => 'index'],
                    ['class' => 'btn btn-info btn-xs','title' => __('Back to Department'),'escape' => false]
                  );?>
                  <?= $this->Html->link('<i class="glyphicon glyphicon-pencil"></i>', ['action' => 'edit', ],['class' => 'btn btn-warning  btn-xs', 'title' => __('Edit'), 'escape' => false]) ?>

                  <?= $this->Form->postLink(
                    '<i class="fa fa-trash-o"></i>',
                    ['action' => 'delete', $department->id],
                    ['confirm' => __('Are you sure you want to delete this Department?', $department->id),'class' => 'btn btn-danger btn-xs','title' => __('Delete'),'escape' => false]
                )?>
              </div>
              </div>
              <!-- /.box-header -->
              <div class="box-body">
                 <dl class="dl-horizontal">
              <dt><?= __('Name') ?></dt>
              <dd><?= h($department->name) ?></dd>
              <dt><?= __('Id') ?></dt>
              <dd><?= $this->Number->format($department->id) ?></dd>
              <dt><?= __('Created') ?></dt>
              <dd><?= h($department->created) ?></dd>
              <dt><?= __('Status') ?></dt>
              <dd><?= $department->status ? __('Yes') : __('No'); ?></dd>
          </dl>
              </div>
              <!-- /.box-body -->
           </div>
           <!-- /.box -->
        </div>
        <!-- ./col -->
     </div>
     <!-- div -->
          <div class="related-designations view">
      <div class="row">
        <div class="col-xs-12">
           <div class="box">
              <div class="box-header">
                 <i class="fa fa-share-alt"></i>
                 <h3 class="box-title"><?= __('Related Designations') ?></h3>
              </div>
              <!-- /.box-header -->
              <div class="box-body table-responsive no-padding">
                 <table class="table table-hover table-bordered">
                    <thead>
                       <tr>
                          <th scope="col"><?= __('Id') ?></th>
                          <th scope="col"><?= __('Department Id') ?></th>
                          <th scope="col"><?= __('Name') ?></th>
                          <th scope="col"><?= __('Status') ?></th>
                          <th scope="col"><?= __('Created') ?></th>
                          <th scope="col" class="actions"><?= __('Actions') ?></th>
                       </tr>
                   </thead>
                       <tbody>
                      <?php if (!empty($department->designations)): ?>
                          <?php foreach ($department->designations as $designations): ?>
                          <tr>
                              <td><?= h($designations->id) ?></td>
                              <td><?= h($designations->department_id) ?></td>
                              <td><?= h($designations->name) ?></td>
                              <td><?= h($designations->status) ?></td>
                              <td><?= h($designations->created) ?></td>
                              <td class="actions">
                                  <?= $this->Html->link('<i class="fa fa-eye"></i>', ['controller' => 'Designations', 'action' => 'view', $designations->id],['class' => 'btn btn-info btn-xs', 'title' => __('View'), 'escape' => false]) ?>
                                  <?= $this->Html->link('<i class="fa fa-edit"></i>', ['controller' => 'Designations', 'action' => 'edit', $designations->id],['class' => 'btn btn-warning btn-xs', 'title' => __('Edit'), 'escape' => false]) ?>
                                  <?= $this->Form->postLink('<i class="fa fa-trash-o"></i>', ['controller' => 'Designations', 'action' => 'delete', $designations->id], ['confirm' => __('Are you sure you want to delete this Designations?', $designations->id),'class' => 'btn btn-danger btn-xs', 'title' => __('Delete'), 'escape' => false]) ?>
                              </td>
                          </tr>
                          <?php endforeach; ?>
                      <?php else: ?>
                          <tr>
                              <td colspan="6"><?= __('Record not found!'); ?></td>
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