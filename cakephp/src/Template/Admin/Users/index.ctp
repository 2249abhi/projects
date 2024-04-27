<?php
/**
 * @author \Nilesh Kushvaha
 * @version \1.1
 * @since \October 29, 2018, 7:12 am
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Cooperative Registration[]|\Cake\Collection\CollectionInterface $CooperativeRegistrations
 */
$this->assign('title',__('Cooperative Society'));
$this->assign('content_header',__('List of Accepted Cooperative Registrations'));
$this->Breadcrumbs->add(__('Cooperative Society'));
$this->assign('top_head',__('hide'));
?>
<!-- Main content -->
<section class="content Cooperative Registration index">
    <div class="row">
        <div class="col-xs-12">

            <?= $this->Flash->render('auth'); ?>
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title titleSrch"><?= __('Search - Cooperative Society Accepted'); ?></h3>
                    <div class="SrchBx">
                        <button id="TglSrch" class="btn btn-info"></button>
                    </div>
                </div>
                <?= $this->Form->create('search_user',['id' =>'search','type'=>'get']); ?>
                <div class="box-body table-responsive">
                    <div class="row2">

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="name" id="name" placeholder="Enter name" class="form-control"
                                    value="<?=$name?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Email ID</label>
                                <input type="text" name="email" id="email" placeholder="Enter Email"
                                    class="form-control" value="<?=$email?>">
                            </div>
                        </div>
                        <?php if($this->request->session()->read('Auth.User.role_id') != 8)
                                      { ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Role</label>
                                <?=$this->form->select('role_id',$roles,['class'=>'form-control','empty'=>'--Select--','value'=>@$role_id])?>
                            </div>
                        </div>
                        <?php }?>

                        <?php if($this->request->session()->read('Auth.User.role_id') != 8 && $this->request->session()->read('Auth.User.role_id') != 11)
                                      { ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>State</label>

                                <?=$this->form->select('state_code',$states,['class'=>'form-control', 'id'=>'state_code','empty'=>'--Select--','value'=>@$state_code])?>

                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>District</label>
                                <?=$this->form->select('district_code',$districtlist,['class'=>'form-control','id'=>'district_code','empty'=>'--Select--','value'=>@$district_code])?>

                            </div>
                        </div>
                        <?php } ?>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Status</label>
                                <?php $status=[1=>'Active',0=>'Inactive']; ?>
                                <?=$this->form->select('status',$status,['class'=>'form-control', 'id'=>'status','empty'=>'--Select--','value'=>$statusv])?>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Primary Activities</label>
                                <?=$this->form->select('primary_act_code',$pact,['class'=>'form-control', 'empty'=>'--Select--','value'=>@$primary_act_code])?>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group" style="margin-top: 24px;">
                                <button name="search_button" value="search_button" type="submit"
                                    class="btn btn-primary btn-green">Search</button>
                                <?php if(empty($pahchan_id) && empty($events_id) && empty($event_types_id) && empty($status)){ ?>
                                <button type="reset" class="btn btn-danger">Reset</button>
                                <?php } else { ?>
                                <?= $this->Html->link(__('Reset'),['action'=>'index'],['class'=>'btn btn-danger']); ?>
                                <?php }?>
                            </div>
                        </div>

                    </div>

                    <div class="clearfix"></div>

                </div>
                <?= $this->Form->end(); ?>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-xs-12">
            <?= $this->Flash->render(); ?>
            <div class="box box-primary box-st">

                <!-- /.box-header -->
                <div class="box-body table-responsive">
                    <div class="sf">
                        <div style="float:right;">
                            <div class="box-tools">
                                <span class="addneww btn btn-warning btn-xs"><i class="glyphicon glyphicon-plus"></i></span>
                                <?= $this->Html->link(__('Create New User'), ['action' => 'add'], ['class' => 'btn btn-success', 'escape' => false, 'title' => __('Create New User')]); ?>
                                
                            </div>
                        </div>
                    </div>
                    <table class="table table-hover table-bordered table-striped">
                        <thead>
                            <tr>
                                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('username') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('email') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('role_id') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('state') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('district') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('designation') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('contact no') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('intern_join',"Joined") ?></th>
                                <th scope="col"><?= $this->Paginator->sort('date_join') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('status') ?></th>

                                <th scope="col">
                                    <?= $this->Paginator->sort('primary_act_code','Primary Activities') ?>
                                </th>
                                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('created_by') ?></th>
                                <th width="100" scope="col" class="actions"><?= __('Actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 


                                                  $i=1;
                                                  $paginatorInformation = $this->Paginator->params();
                                                  $pageOffset = ($paginatorInformation['page'] - 1);
                                                  $perPage = $paginatorInformation['perPage'];
                                                  $counter = (($pageOffset * $perPage));
                                                  foreach ($users as $user): ?>
                            <tr>
                                <td> <?php  echo $this->Number->format($i + $counter) ?> </td>
                                <td><?= h($user->name) ?></td>

                                <td><?= h($user->username) ?></td>
                                <td><?= h($user->email) ?></td>
                                <td><?php echo $roles[$user->role_id]; ?></td>
                                <td><?= $states[$user->state_code] ?></td>
                                <td><?= $districts[$user->district_code] ?></td>
                                <td><?= h($user->designation) ?></td>
                                <td><?= h($user->contact_no) ?></td>
                                <td><?= $user->intern_join == '2' ? ('No') : ('Yes'); ?></td>
                                <td><?= h($user->date_join) ?></td>
                                <td>
                                    <?php
                                                        if($user->status == 0):
                                                        ?>
                                    <span style="color:red;">Inactive</span>
                                    <?php else: ?>
                                    <span style="color:green;">Active</span>
                                    <?php endif; ?>
                                </td>


                                <td>
                                    <?php if($user->role_id == 8): ?>
                                    <?= $pact[$user->primary_act_code] ?>
                                    <?php elseif($user->role_id == 11): ?>
                                    <?= $pact[$user->primary_act_code] ?>
                                    <?php else: ?>
                                    <?php endif; ?>
                                </td>
                                <td><?= date('Y-m-d',strtotime($user->created)) ?></td>
                                <td><?= $all_user[$user->created_by]; ?></td>
                                <td class="actions">
                                    <?= $this->Html->link('<i class="fa fa-eye"></i>', ['action' => 'view', $user->id],['class' => 'btn btn-info btn-xs', 'title' => __('View'), 'escape' => false]) ?>

                                    <?php   if($this->request->session()->read('Auth.User.role_id') != 11)
                                                {?>
                                    <?= $this->Html->link('<i class="fa fa-edit"></i>', ['action' => 'edit', $user->id],['class' => 'btn btn-warning btn-xs', 'title' => __('Edit'), 'escape' => false]) ?>
                                    <?php //if ($user->role_id != 1) {
                                                    //echo $this->Form->postLink('<i class="fa fa-trash-o"></i>', ['action' => 'delete', $user->id], ['confirm' => __('Are you sure you want to delete this User?', $user->id),'class' => 'btn btn-danger btn-xs', 'title' => __('Delete'), 'escape' => false]);
                                                  //} ?>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php $i++;endforeach; ?>
                            <?php if(count($users) == 0):?>
                            <tr>
                                <td colspan="10"><?= __('Record not found!'); ?></td>
                            </tr>
                            <?php endif;?>
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
                <div class="box-footer clearfix">

                    <div class="col-md-4 text-left">
                        <?php echo 'Showing '.$paginatorInformation['start'].' to '.$paginatorInformation['end'].' of, '.$paginatorInformation['count'].' entries'; ?>
                    </div>
                    <div class="col-md-8">
                        <ul class="pagination pagination-sm no-margin pull-right">
                            <?= $this->Paginator->first('<<') ?>
                            <?= $this->Paginator->prev('<') ?>
                            <?= $this->Paginator->numbers() ?>
                            <?= $this->Paginator->next('>') ?>
                            <?= $this->Paginator->last('>>') ?>
                        </ul>
                    </div>
                </div>

            </div>
            <!-- /.box -->
        </div>

    </div>


</section>
<?php $this->append('bottom-script');?>
<script>
/*  get district */
(function($) {
    $(document).ready(function() {
        $('#state_code').on('change', function(e) {
            e.preventDefault();
            $.ajax({
                type: 'get',
                cache: false,
                url: '<?=$this->Url->build(['action'=>'get-districts'])?>',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                },
                data: {
                    state_code: $(this).val()
                },
                success: function(response) {
                    $("#district_code").html(response);
                },
            });
        });
    });
})($);
</script>
<?php $this->end(); ?>