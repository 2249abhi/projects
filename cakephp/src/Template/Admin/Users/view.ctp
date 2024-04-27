<?php
/**
 * @author \Nilesh Kushvaha
 * @version \1.1
 * @since \October 29, 2018, 7:05 am
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
$this->assign('title',__('User ( ' . $user->name . ' ) '));

$this->Breadcrumbs->add(__('Users'),['action'=>'index']);
$this->Breadcrumbs->add(__('User ( ' . $user->name . ' ) '));
?>

  <!-- Main content -->
  <section class="content user view">
     <div class="row">
        <div class="col-md-12">
           <div class="box box-solid">
              <div class="box-header with-border">
                 <i class="fa fa-info"></i>
                 <h3 class="box-title"><?= __('User ( ' . $user->name . ' ) ') ?></h3>
                 <div class="box-tools pull-right">
                  <?=$this->Html->link(
                    '<i class="glyphicon glyphicon-arrow-left"></i>',
                    ['action' => 'index'],
                    ['class' => 'btn btn-info btn-xs','title' => __('Back to User'),'escape' => false]
                  );?>
                  <?= $this->Html->link('<i class="glyphicon glyphicon-pencil"></i>', ['action' => 'edit', $user->id],['class' => 'btn btn-warning  btn-xs', 'title' => __('Edit'), 'escape' => false]) ?>

                  <?php //echo $this->Form->postLink('<i class="fa fa-trash-o"></i>',['action' => 'delete', $user->id],['confirm' => __('Are you sure you want to delete this User?', $user->id),'class' => 'btn btn-danger btn-xs','title' => __('Delete'),'escape' => false]) ?>
              </div>
              </div>
              <!-- /.box-header -->
              <div class="box-body">
                 <div class="dl-horizontal">
				   <div class="row">
                  <div class="col-sm-3"><label><?= __('Name') ?></label></div>
                  <div class="col-sm-3"><?= h($user->name) ?></div>

                  <div class="col-sm-3"><label><?= __('Username') ?></label></div>
                  <div class="col-sm-3"><?= h($user->username) ?></div>
              </div>

              <div class="row">
                  <div class="col-sm-3"><label><?= __('Email') ?></label></div>
                  <div class="col-sm-3"><?= h($user->email) ?></div>

                  <div class="col-sm-3"><label><?= __('Role') ?></label></div>
                  <div class="col-sm-3"><?= $user->has('role') ? $this->Html->link($user->role->name, ['controller' => 'Roles', 'action' => 'view', $user->role->id]) : '' ?></div>
               </div>
              
               <div class="row">
                  <div class="col-sm-3"><label><?= __('State') ?></label></div>
                  <div class="col-sm-3"><?= $states[$user->state_code] ?></div>
               
                  <div class="col-sm-3"><label><?= __('District') ?></label></div>
                  <div class="col-sm-3"><?= $districts[$user->district_code] ?></div>
               </div>

               <div class="row">
                  <div class="col-sm-3"><label><?= __('Designation') ?></label></div>
                  <div class="col-sm-3"><?= $user->designation ?></div>

                  <div class="col-sm-3"><label><?= __('Conatct No.') ?></label></div>
                  <div class="col-sm-3"><?= $user->contact_no ?></div>
               </div>
               
               <div class="row">
                  <div class="col-sm-3"><label><?= __('Created') ?></label></div>
                  <div class="col-sm-3"><?= h($user->created) ?></div>
               
                  <div class="col-sm-3"><label><?= __('Modified') ?></label></div>
                  <div class="col-sm-3"><?= h($user->modified) ?></div>
               </div>
               
               <div class="row">
                  <div class="col-sm-3"><label><?= __('Status') ?></label></div>
                  <div class="col-sm-3"><?= $user->status ? __('Active') : __('Inactive'); ?></div>

                  <?php  if($user->status == 0): ?>
                  <div class="col-sm-3"><label><?= __('Deactivation Date') ?></label></div>
                  <div class="col-sm-3"><?= h($user->deactivation_date) ?></div>
                  <?php else: ?>
                  <?php endif; ?>
               </div>

               <div class="row">
                  <?php if($user->role_id == 8): ?>
                  <div class="col-sm-3"><label><?= __('Primary Activities') ?></label></div>
                  <div class="col-sm-3">
                  <?= $pact[$user->primary_act_code] ?>
                  </div>
                  <?php elseif($user->role_id == 11): ?>
                  <div class="col-sm-3"><label><?= __('Primary Activities') ?></label></div>
                  <div class="col-sm-3">   
                  <?= $pact[$user->primary_act_code] ?>
                  </div>
                  <?php else: ?>  
                  <?php endif; ?> 
               </div>

                  
                     <?php 
                     $arr_user_type = [
                        '0'=>'User','1'=>'DEO','2'=>'Intern'
                     ];
                     if($user->role_id == 7): ?>
                  <div class="row">
                     <div class="col-sm-3"><label><?= __('Type') ?></label></div>
                     <div class="col-sm-3"><?= h($arr_user_type[$user->user_type]) ?></div>

                     <div class="col-sm-3"><label><?= __('Account Holder Name') ?></label></div>
                     <div class="col-sm-3"><?= h($user->account_holder_name) ?></div>
                  </div>

                  <div class="row">
                     <div class="col-sm-3"><label><?= __('Account Number') ?></label></div>
                     <div class="col-sm-3"><?= h($user->account_number) ?></div>

                     <div class="col-sm-3"><label><?= __('Name of the Bank and Branch') ?></label></div>
                     <div class="col-sm-3"><?= h($user->name_of_bank_and_branch) ?></div>
                  </div>

                  <div class="row">
                     <div class="col-sm-3"><label><?= __('IFSC code') ?></label></div>
                     <div class="col-sm-3"><?= h($user->ifsc_code) ?></div>

                     <div class="col-sm-3"><label><?= __('remarks') ?></label></div>
                     <div class="col-sm-3"><?= h($user->remarks) ?></div>
                  </div>
                  <?php else: ?>  
                  <?php endif; ?> 


               </div>

          </dib>
             </div>
              <!-- /.box-body -->
           </div>
           <!-- /.box -->
        </div>
        <!-- ./col -->
     </div>
     <!-- div -->
 </section>