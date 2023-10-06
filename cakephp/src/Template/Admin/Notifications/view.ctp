<?php
/**
 * @author \Abhay Singh
 * @version \1.1
 * @since \July 07, 2020, 10:44 am
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Role $role
 */
$this->assign('title',__('News ( ' . $doc_type->name . ' ) '));
$this->Breadcrumbs->add(__('News'),['action'=>'index']);
$this->Breadcrumbs->add(__('News ( ' . $doc_type->name . ' ) '));
?>

  <!-- Main content -->
  <section class="content role view">
     <div class="row">
        <div class="col-md-12">
           <div class="box box-solid">
              <div class="box-header with-border">
                 <i class="fa fa-info"></i>
                 <h3 class="box-title"><?= __('News ( ' . $doc_type->name . ' ) ') ?></h3>
                 <div class="box-tools pull-right">
                  <?=$this->Html->link(
                    '<i class="glyphicon glyphicon-arrow-left"></i>',
                    ['action' => 'index'],
                    ['class' => 'btn btn-info btn-xs','title' => __('Back to News'),'escape' => false]
                  );?>
                  <?= $this->Html->link('<i class="glyphicon glyphicon-pencil"></i>', ['action' => 'edit',$doc_type->id ],['class' => 'btn btn-warning  btn-xs', 'title' => __('Edit'), 'escape' => false]) ?>

                  <?= $this->Form->postLink(
                    '<i class="fa fa-trash-o"></i>',
                    ['action' => 'delete', $doc_type->id],
                    ['confirm' => __('Are you sure you want to delete this News?', $doc_type->id),'class' => 'btn btn-danger btn-xs','title' => __('Delete'),'escape' => false]
                )?>
              </div> 
              </div>
			  <?php $doc_priority = [3=>'High',2=>'Medium',1=>'Low'];?>
              <!-- /.box-header -->
              <div class="box-body">
                <table class="table table-striped table-bordered">
                    <tr>
                      <th scope="row"><?= __('News Description') ?></th>
                      <td><?= html_entity_decode($doc_type->description) ?></td>
                    </tr>
                    <tr>
						<th><?= __('News Date') ?></th>
						<td><?= date('Y-m-d H:i A',strtotime($doc_type->notification_date)) ?></td>
						<th scope="row"><?= __('News Order') ?></th>
						<td><?= h($doc_type->notification_order) ?></td>
                      
                    </tr>
                    <tr>
                      <th scope="row"><?= __('Created') ?></th>
                      <td><?= date('Y-m-d H:i A',strtotime($doc_type->created)) ?></td>
                      <th><?= __('Status') ?></th>
                      <td><?= $doc_type->status ? __('Active') : __('Inactive'); ?></td>
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

 </section>