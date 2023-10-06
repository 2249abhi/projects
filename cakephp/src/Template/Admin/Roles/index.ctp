<?php
/**
 * @author \Nilesh Kushvaha
 * @version \1.1
 * @since \October 29, 2018, 7:13 am
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Role[]|\Cake\Collection\CollectionInterface $roles
 */
$this->assign('title',__('Roles'));
$this->Breadcrumbs->add(__('Roles'));
$selectedLen = isset($selectedLen)?$selectedLen:'';
$roleName = isset($roleName)?$roleName:'';
$status = isset($status)?$status:'';
?>
  <!-- Main content -->
  <section class="content role index">
    <div class="row">
      <div class="col-md-12">
        <?= $this->Flash->render(); ?>
        <?= $this->Flash->render('auth'); ?>
        <div class="box box-primary">
          <div class="box-header">
              <h3 class="box-title"><?= __('Search - Sub Modules'); ?></h3>
          </div> 
          <?= $this->Form->create('searchBanner',['id' =>'searchBanner','type'=>'get']); ?>
            <div class="box-body table-responsive">
              <?= $this->element('errorlist'); ?> 

              <div class="row2">    
                <div class="col-md-2"></div>
                <div class="col-md-1"><label for="page_name">Title</label></div>
                <div class="col-md-3">
                  <div class="form-group">
                    <input type="text" name="role_name" id="role_name" maxlength="40" placeholder="Enter title" class="form-control" value=<?=$roleName?>>
                  </div>
                </div>
                <div class="col-md-1"><label for="status">Status</label></div>
                <div class="col-md-3">
                  <div class="form-group">
                    <?php $sOption = [0=>'Inactive',1=>'Active'];?>
                    <?=$this->form->select('status',$sOption,['class'=>'form-control','empty'=>'--Select--','value'=>$status])?>
                  </div>
                </div>
              </div>
              <div class="clearfix"></div>
              <div class="row3">
                <div class="col-md-5"></div>
                <div class="col-md-1"><button name="search_button" value="search_button" type="submit" class="btn btn-primary btn-green">Search</button></div>
                <div class="col-md-1">
                  <?php if(empty($roleName) && empty($status)){ ?>
                    <button type="reset" class="btn btn-danger">Reset</button>                  
                  <?php } else { ?>
                    <?= $this->Html->link(__('Reset'),['controller'=>'roles','action'=>'index'],['class'=>'btn btn-danger']); ?>
                  <?php }?>
                </div>
              </div>
              <div class="clearfix"></div>
              <br>
              <input type="hidden" name="page_length" value="<?=@$selectedLen?>">
          </div>
          <?= $this->Form->end(); ?>
          </div>
      </div>
    </div>

    <div class="row">
      <div class="col-xs-12">
        <div class="box box-primary">
          <?= $this->Form->create('page_number',['id'=>'role_list','type'=>'get','class'=>'form-inline']); ?>
            <div class="box-header">
              <h3 class="box-title">
                <?= __('List of') ?> <?= __('Roles') ?>
              </h3>
              <div class="box-tools">
                <div class="form-group">
                  <label for="page_length">Display:</label>
                  <?php $page_length = array('10'=>'10','20'=>'20','50'=>'50','100'=>'100','200'=>'200','all'=>'All'); ?>
                  <select class="form-control" name="page_length" id="page_length">
                    <?= $this->OptionsData->list_toOption($page_length, $selectedId = $selectedLen)?>
                  </select>
                  <input type="hidden" name="role_name" value="<?=$roleName;?>">
                </div>
                <button name="export_excel" value="export_excel" class="btn btn-primary">Export to Excel</button>
                <?=$this->Html->link(
                    __('<i class="glyphicon glyphicon-plus"></i> New Role'),
                    ['action' => 'add'],
                    ['class' => 'btn btn-success', 'escape' => false, 'title' => __('Add New Role')]
                );?>                
              </div>              
            </div>
            <div class="clearfix"></div>
            <?= $this->Form->end()?>

          <!-- /.box-header -->
          <div class="box-body table-responsive">
            <table class="table table-hover table-bordered">
              <thead>
                <tr>
                    <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('status') ?></th>
                    <th scope="col" class="actions"><?= __('Actions') ?></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($roles as $role): ?>
                  <tr>
                    <td><?= $this->Number->format($role->id) ?></td>
                    <td><?= h($role->name) ?></td>
                    <td><?= date('Y-m-d H:i A',strtotime($role->created)) ?></td>
                    <td><?= $role->status ? __('Active') : __('Inactive'); ?></td>
                    <td class="actions">
                        <?= $this->Html->link('<i class="fa fa-eye"></i>', ['action' => 'view', $role->id],['class' => 'btn btn-info btn-xs', 'title' => __('View'), 'escape' => false]) ?>
                        <?= $this->Html->link('<i class="fa fa-edit"></i>', ['action' => 'edit', $role->id],['class' => 'btn btn-warning btn-xs', 'title' => __('Edit'), 'escape' => false]) ?>
                        <?= $this->Form->postLink('<i class="fa fa-trash-o"></i>', ['action' => 'delete', $role->id], ['confirm' => __('Are you sure you want to delete this Role?', $role->id),'class' => 'btn btn-danger btn-xs', 'title' => __('Delete'), 'escape' => false]) ?>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(count($roles) == 0):?>
                    <tr>
                        <td colspan="6"><?= __('Record not found!'); ?></td>
                    </tr>
                    <?php endif;?>
              </tbody>
            </table>
          </div>
          <!-- /.box-body -->
          <div class="box-footer clearfix">
            <ul class="pagination pagination-sm no-margin pull-right">
              <?= $this->Paginator->first('<<') ?>
              <?= $this->Paginator->prev('<') ?>
              <?= $this->Paginator->numbers() ?>
              <?= $this->Paginator->next('>') ?>
              <?= $this->Paginator->last('>>') ?>
            </ul>
          </div>
        </div>
        <!-- /.box -->
      </div>
    </div>
  </section>

<?php $this->append('bottom-script');?>
<script type="text/javascript">
  $(document).ready(function() {
    $('#page_length').on('change',function () {
      $('#role_list').submit();
    })
    });
</script>
<?php $this->end();?>