<?php
/**
 * @author \Nilesh Kushvaha
 * @version \1.1
 * @since \October 29, 2018, 7:13 am
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Role $role
 */
$this->assign('title',__('Karmyatra ( ' . $role->title . ' ) '));
$this->Breadcrumbs->add(__('Karmyatras'),['action'=>'index']);
$this->Breadcrumbs->add(__('Karmyatra ( ' . $role->title . ' ) '));
?>

  <!-- Main content -->
  <section class="content role view">
     <div class="row">
        <div class="col-md-12">
           <div class="box box-solid">
              <div class="box-header with-border">
                 <i class="fa fa-info"></i>
                 <h3 class="box-title"><?= __('Karmyatra ( ' . $role->title . ' ) ') ?></h3>
                 <div class="box-tools pull-right">
                  <?=$this->Html->link(
                    '<i class="glyphicon glyphicon-arrow-left"></i>',
                    ['action' => 'index'],
                    ['class' => 'btn btn-info btn-xs','title' => __('Back to Karmyatra'),'escape' => false]
                  );?>
                 <!--  <?= $this->Html->link('<i class="glyphicon glyphicon-pencil"></i>', ['action' => 'edit', ],['class' => 'btn btn-warning  btn-xs', 'title' => __('Edit'), 'escape' => false]) ?> -->

                  <?= $this->Form->postLink(
                    '<i class="fa fa-trash-o"></i>',
                    ['action' => 'delete', $role->id],
                    ['confirm' => __('Are you sure you want to delete this Karmyatra?', $role->id),'class' => 'btn btn-danger btn-xs','title' => __('Delete'),'escape' => false]
                )?>
              </div>
              </div>
              <!-- /.box-header -->
              <div class="box-body">
                 <!-- <dl class="dl-horizontal">
              <dt><?= __('Name') ?></dt>
              <dd><?= h($role->name) ?></dd>
              <dt><?= __('Created') ?></dt>
              <dd><?= date('Y-m-d H:i A',strtotime($role->created)) ?></dd>
              <dt><?= __('Modified') ?></dt>
              <dd><?= date('Y-m-d H:i A',strtotime($role->modified)) ?></dd>
              <dt><?= __('Status') ?></dt>
              <dd><?= $role->status ? __('Yes') : __('No'); ?></dd>
          </dl> -->

          <table class="table table-striped table-bordered">
                    <tr>
                      
                      <th><?= __('Title') ?></th>
                      <td><?= h($role->title) ?></td>
                      <th><?= __('Priority') ?></th>
                      <td><?= h($role->priority) ?></td>
                    </tr>
                   
                    <tr>
                      <th scope="row"><?= __('Created') ?></th>
                      <td><?= date('Y-m-d H:i A',strtotime($role->created)) ?></td>
                      <th><?= __('Status') ?></th>
                      <td><?= $role->status ? __('Active') : __('Inactive'); ?></td>
                    </tr>
                  </table>
              </div>
              <!-- /.box-body -->
           </div>
           <!-- /.box -->
        </div>
        <!-- ./col -->
    </div>
    <!-- div -->
  <!--  <div class="related-users view">
      <div class="row">
        <div class="col-xs-12">
           <div class="box">
              <div class="box-header">
                 <i class="fa fa-share-alt"></i>
                 <h3 class="box-title"><?= __('Related Users') ?></h3>
              </div>
            
              <div class="box-body table-responsive no-padding">
                 <table class="table table-hover table-bordered">
                    <thead>
                       <tr>
                          <th scope="col"><?= __('Id') ?></th>
                          <th scope="col"><?= __('Name') ?></th>
                          <th scope="col"><?= __('Username') ?></th>
                          <th scope="col"><?= __('Email') ?></th>
                          <th scope="col"><?= __('Status') ?></th>
                          <th scope="col"><?= __('Created') ?></th>
                          <th scope="col" class="actions"><?= __('Actions') ?></th>
                       </tr>
                   </thead>
                       <tbody>
                      <?php if (!empty($role->users)): ?>
                          <?php foreach ($role->users as $users): ?>
                          <tr>
                              <td><?= h($users->id) ?></td>
                              <td><?= h($users->name) ?></td>
                              <td><?= h($users->username) ?></td>
                              <td><?= h($users->email) ?></td>
                              <td><?= $role->status ? __('Active') : __('Inactive'); ?></td>
                              <td><?= h($users->created) ?></td>
                              <td class="actions">
                                  <?= $this->Html->link('<i class="fa fa-eye"></i>', ['controller' => 'Users', 'action' => 'view', $users->id],['class' => 'btn btn-info btn-xs', 'title' => __('View'), 'escape' => false]) ?>
                                  <?= $this->Html->link('<i class="fa fa-edit"></i>', ['controller' => 'Users', 'action' => 'edit', $users->id],['class' => 'btn btn-warning btn-xs', 'title' => __('Edit'), 'escape' => false]) ?>
                                  <?= $this->Form->postLink('<i class="fa fa-trash-o"></i>', ['controller' => 'Users', 'action' => 'delete', $users->id], ['confirm' => __('Are you sure you want to delete this Users?', $users->id),'class' => 'btn btn-danger btn-xs', 'title' => __('Delete'), 'escape' => false]) ?>
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
             
           </div>
          
        </div>
     </div>
 </div>  -->
 </section>