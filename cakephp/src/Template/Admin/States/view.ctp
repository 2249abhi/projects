<?php
/**
 * @author \Nilesh Kushvaha
 * @version \1.1
 * @since \October 29, 2018, 7:12 am
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\State $state
 */
$this->assign('title',__('State ( ' . $state->name . ' ) '));
$this->Breadcrumbs->add(__('States'),['action'=>'index']);
$this->Breadcrumbs->add(__('State ( ' . $state->name . ' ) '));
?>

  <!-- Main content -->
  <section class="content state view">
     <div class="row">
        <div class="col-md-12">
           <div class="box box-solid">
              <div class="box-header with-border">
                 <i class="fa fa-info"></i>
                 <h3 class="box-title"><?= __('State ( ' . $state->name . ' ) ') ?></h3>
                 <div class="box-tools pull-right">
                  <?=$this->Html->link(
                    '<i class="glyphicon glyphicon-arrow-left"></i>',
                    ['action' => 'index'],
                    ['class' => 'btn btn-info btn-xs','title' => __('Back to State'),'escape' => false]
                  );?>
                  <?= $this->Html->link('<i class="glyphicon glyphicon-pencil"></i>', ['action' => 'edit',$state->id ],['class' => 'btn btn-warning  btn-xs', 'title' => __('Edit'), 'escape' => false]) ?>

                  <?= $this->Form->postLink(
                    '<i class="fa fa-trash-o"></i>',
                    ['action' => 'delete', $state->id],
                    ['confirm' => __('Are you sure you want to delete this State?', $state->id),'class' => 'btn btn-danger btn-xs','title' => __('Delete'),'escape' => false]
                )?>
              </div>
              </div>
              <!-- /.box-header -->
              <div class="box-body">
                <dl class="dl-horizontal">
                  <dt><?= __('Code') ?></dt>
                  <dd><?= h($state->code) ?></dd>
                  <dt><?= __('Name') ?></dt>
                  <dd><?= h($state->name) ?></dd>
                  <dt><?= __('Id') ?></dt>
                  <dd><?= $this->Number->format($state->id) ?></dd>
                  <dt><?= __('Flag') ?></dt>
                  <dd><?= $state->flag ? __('Active') : __('InActive') ?></dd>
                  <dt><?= __('Created') ?></dt>
                  <dd><?= date('Y-m-d H:i A',strtotime($state->created)) ?></dd>
                </dl>
              </div>
              <!-- /.box-body -->
           </div>
           <!-- /.box -->
        </div>
        <!-- ./col -->
      </div>
      <!-- div -->
      <div class="related-districts view">
        <div class="row">
          <div class="col-xs-12">
            <div class="box">
              <div class="box-header">
                 <i class="fa fa-share-alt"></i>
                 <h3 class="box-title"><?= __('Related Districts') ?></h3>
              </div>
              <!-- /.box-header -->
              <div class="box-body table-responsive no-padding">
                 <table class="table table-hover table-bordered">
                    <thead>
                       <tr>
                          <th scope="col"><?= __('Id') ?></th>
                          <th scope="col"><?= __('Name') ?></th>
                          <th scope="col"><?= __('Flag') ?></th>
                          <th scope="col" class="actions"><?= __('Actions') ?></th>
                       </tr>
                   </thead>
                       <tbody>
                      <?php if (!empty($state->districts)): ?>
                          <?php foreach ($state->districts as $districts): ?>
                          <tr>
                              <td><?= h($districts->id) ?></td>
                              <td><?= h($districts->name) ?></td>
                              <td><?= $districts->flag ? __('Active') : __('InActive') ?></td>
                              <td class="actions">
                                  <?= $this->Html->link('<i class="fa fa-eye"></i>', ['controller' => 'Districts', 'action' => 'view', $districts->id],['class' => 'btn btn-info btn-xs', 'title' => __('View'), 'escape' => false]) ?>
                                  <?= $this->Html->link('<i class="fa fa-edit"></i>', ['controller' => 'Districts', 'action' => 'edit', $districts->id],['class' => 'btn btn-warning btn-xs', 'title' => __('Edit'), 'escape' => false]) ?>
                                  <?= $this->Form->postLink('<i class="fa fa-trash-o"></i>', ['controller' => 'Districts', 'action' => 'delete', $districts->id], ['confirm' => __('Are you sure you want to delete this Districts?', $districts->id),'class' => 'btn btn-danger btn-xs', 'title' => __('Delete'), 'escape' => false]) ?>
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