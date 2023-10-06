<?php
/**
 * @author \Abhay Singh
 * @version \1.1
 * @since \July 07, 2020, 10:44 am
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Role $role
 */
$this->assign('title',__('Event ( ' . $doc_type->name . ' ) '));
$this->Breadcrumbs->add(__('Events'),['action'=>'index']);
$this->Breadcrumbs->add(__('Event ( ' . $doc_type->name . ' ) '));
?>

  <!-- Main content -->
  <section class="content role view">
     <div class="row">
        <div class="col-md-12">
           <div class="box box-solid">
              <div class="box-header with-border">
                 <i class="fa fa-info"></i>
                 <h3 class="box-title"><?= __('Event ( ' . $doc_type->name . ' ) ') ?></h3>
                 <div class="box-tools pull-right">
                  <?=$this->Html->link(
                    '<i class="glyphicon glyphicon-arrow-left"></i>',
                    ['action' => 'index'],
                    ['class' => 'btn btn-info btn-xs','title' => __('Back to Event'),'escape' => false]
                  );?>
                  <?= $this->Html->link('<i class="glyphicon glyphicon-pencil"></i>', ['action' => 'edit',$doc_type->id ],['class' => 'btn btn-warning  btn-xs', 'title' => __('Edit'), 'escape' => false]) ?>

                  <?= $this->Form->postLink(
                    '<i class="fa fa-trash-o"></i>',
                    ['action' => 'delete', $doc_type->id],
                    ['confirm' => __('Are you sure you want to delete this Event?', $doc_type->id),'class' => 'btn btn-danger btn-xs','title' => __('Delete'),'escape' => false]
                )?>
              </div> 
              </div>
			  
              <!-- /.box-header -->
              <div class="box-body">
                <table class="table table-striped table-bordered">
                    <tr>
                      <th scope="row"><?= __('Event Title') ?></th>
                      <td><?= h($doc_type->event_title) ?></td>
                      <th><?= __('Event description') ?></th>
                      <td><?= html_entity_decode($doc_type->event_description) ?></td>
                    </tr> 
                    <tr>
                      <th scope="row"><?= __('Event order') ?></th>
                      <td><?= h($doc_type->event_order) ?></td>
                      <th><?= __('Event Date') ?></th>
                      <td><?= date('Y-m-d',strtotime($doc_type->event_date)) ?></td>
                    </tr>
                    <tr>
                      <th scope="row"><?= __('Created') ?></th>
                      <td><?= date('Y-m-d H:i A',strtotime($doc_type->created)) ?></td>
                      <th><?= __('Status') ?></th>
                      <td><?= $doc_type->status ? __('Active') : __('Inactive'); ?></td>
                    </tr>
                    <tr>
					<th scope="row"><?= __('Event Image') ?></th>
                      <td><a title="View" class="btn btn-primary btn-sm" target="_blank" href="<?= $this->Url->build('/files/events/'. $doc_type->event_image);?>">View</a></td>
					
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