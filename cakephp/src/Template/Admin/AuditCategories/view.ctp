<?php
/**
 * @author \Nilesh Kushvaha
 * @version \1.1
 * @since \October 29, 2018, 7:06 am
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Designation $sactivity
 */
$this->assign('title',__('Audit Category ( ' . $acategories->name . ' ) '));
$this->Breadcrumbs->add(__('Audit Category'),['action'=>'index']);
$this->Breadcrumbs->add(__('Audit Category ( ' . $acategories->name . ' ) '));
?>

  <!-- Main content -->
  <section class="content sactivity view">
     <div class="row">
        <div class="col-md-12">
           <div class="box box-solid">
              <div class="box-header with-border">
                 <i class="fa fa-info"></i>
                 <h3 class="box-title"><?= __('Audit Category ( ' . $acategories->name . ' ) ') ?></h3>
                 <div class="box-tools pull-right">
                  <?=$this->Html->link(
                    '<i class="glyphicon glyphicon-arrow-left"></i>',
                    ['action' => 'index'],
                    ['class' => 'btn btn-info btn-xs','title' => __('Back to Audit Category'),'escape' => false]
                  );?>
                  <?= $this->Html->link('<i class="glyphicon glyphicon-pencil"></i>', ['action' => 'edit', $acategories->id],['class' => 'btn btn-warning  btn-xs', 'title' => __('Edit'), 'escape' => false]) ?>

                  <?= $this->Form->postLink(
                    '<i class="fa fa-trash-o"></i>',
                    ['action' => 'delete', $acategories->id],
                    ['confirm' => __('Are you sure you want to delete this Audit Category?', $acategories->id),'class' => 'btn btn-danger btn-xs','title' => __('Delete'),'escape' => false]
                )?>
              </div>
              </div>
              <!-- /.box-header -->
              <div class="box-body">
                      <div class="dl-horizontal">
						<div class="row">
                          
                              <div class="col-sm-3"><label><?= __('Name') ?></label></div>
                              <div class="col-sm-3"><?= h($acategories->name) ?></div>
                            <div class="col-sm-3"><label><?= __('Status') ?></label></div>
                            <div class="col-sm-3"><?= $acategories->status ? __('Active') : __('Inactive'); ?></div>
                           </dl>
                 
                      
                             <div class="col-sm-3"><label><?= __('Order') ?></label></div>
                             <div class="col-sm-3"><?= $this->Number->format($acategories->orderseq) ?></div>
                             <div class="col-sm-3"><label><?= __('Created') ?></label></div>
                              <div class="col-sm-3"><?= h($acategories->created_at) ?></div>
                           </div>      
                     
              </div>
              <!-- /.box-body -->
           </div>
           <!-- /.box -->
        </div>
        <!-- ./col -->
     </div>
     <!-- div -->
 </section>