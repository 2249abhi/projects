<?php
/**
 * @author \Nilesh Kushvaha
 * @version \1.1
 * @since \October 29, 2018, 7:05 am
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User[]|\Cake\Collection\CollectionInterface $users
 */
$this->assign('title',__('Users'));
$this->Breadcrumbs->add(__('Users'));
?>
  <!-- Main content -->
  <section class="content user index">
    <div class="row">
      <div class="col-xs-12">
        <?= $this->Flash->render(); ?>
        <?= $this->Flash->render('auth'); ?>
        <div class="box box-primary">
          <div class="box-header">
            <h3 class="box-title"><?= __('List of') ?> <?= __('Users') ?></h3>
            <div class="box-tools">
              <?=$this->Html->link(
                      __('<i class="glyphicon glyphicon-plus"></i> Create New User'),
                      ['action' => 'add'],
                      ['class' => 'btn btn-success', 'escape' => false, 'title' => __('Create New User')]
                  );?>
            </div>
          </div>
          <!-- /.box-header -->
        <div class="row">
          <div class="col-xs-12">
         <div class="box-header">
              <h3 class="box-title"><?= __('Search'); ?></h3>
          </div> 
          <?= $this->Form->create('search_user',['id' =>'search','type'=>'post']); ?>
            <div class="box-body table-responsive">
              <div class="row2"> 

                <div class="col-md-3">
                  <div class="form-group">
                   <label>Name</label>
                    <input type="text" name="name" id="name" placeholder="Enter name" class="form-control" value="<?=$name?>">
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
                  <div class="form-group" style="margin-top: 24px;">
                    <button name="search_button" value="search_button" type="submit" class="btn btn-primary btn-green">Search</button>
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
          <div class="box-body table-responsive">
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
                    <th scope="col"><?= $this->Paginator->sort('status') ?></th>
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
                        <td><?= $user->status ? __('Active') : __('Inactive'); ?></td>
                        <td><?= date('Y-m-d',strtotime($user->created)) ?></td>
                          <td><?= $all_user[$user->created_by]; ?></td>
                        <td class="actions">
                            <?= $this->Html->link('<i class="fa fa-eye"></i>', ['action' => 'view', $user->id],['class' => 'btn btn-info btn-xs', 'title' => __('View'), 'escape' => false]) ?>

                           <?php   if($this->request->session()->read('Auth.User.role_id') != 11)
                          {?>
                            <?= $this->Html->link('<i class="fa fa-edit"></i>', ['action' => 'edit', $user->id],['class' => 'btn btn-warning btn-xs', 'title' => __('Edit'), 'escape' => false]) ?>
                            <?php //if ($user->role_id != 1) {
                              echo $this->Form->postLink('<i class="fa fa-trash-o"></i>', ['action' => 'delete', $user->id], ['confirm' => __('Are you sure you want to delete this User?', $user->id),'class' => 'btn btn-danger btn-xs', 'title' => __('Delete'), 'escape' => false]);
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
(function($){
 $(document).ready(function(){
$('#state_code').on('change',function(e){
    e.preventDefault();
   var base_urla= "<?=BASE_URL?>"; 

    var base_url= "<?php echo $this->Url->build('/admin/en/users/getDistricts', false);?>"; 
   
    $.ajax({
            type:'POST',
            cache: false,
            url: base_url,
            beforeSend: function (xhr) {
              xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            data: {state_code : $(this).val()},
            success: function(response){
                $("#district_code").html(response);
               
            },
        }); 
});
});
})($);

  </script>
  <?php $this->end(); ?>