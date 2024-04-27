<?php
/**
 * @author \Nilesh Kushvaha
 * @version \1.1
 * @since \October 29, 2018, 7:12 am
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Cooperative Registration[]|\Cake\Collection\CollectionInterface $CooperativeRegistrations
 */
$this->assign('title',__('My Registrations'));
$this->Breadcrumbs->add(__('My Registrations'));
use Cake\I18n\FrozenTime;
?>
<!-- Main content -->
<section class="content Cooperative Registration index">
    <div class="row">
        <div class="col-xs-12">
            
            <?= $this->Flash->render('auth'); ?>
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title"><?= __('Search - List of Cooperative Society Return For Correction'); ?></h3>
                </div>
                <?= $this->Form->create('searchBanner',['id' =>'searchBanner','type'=>'get']); ?>
                <div class="box-body table-responsive">
                    <?= $this->element('errorlist'); ?>

                    <div class="row1">
                        <div class="col-md-1"><label for="page_name">Society Name</label></div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" name="society_name" id="society_name" maxlength="40" placeholder="Enter Society Name" class="form-control" value=<?=$societyName?>>
                            </div>
                        </div>
                        <div class="col-md-1"><label for="status">Registration Number</label></div>
                        <div class="col-md-3">
                            <div class="form-group">
                            <input type="text" name="registration_number" id="registration_number" maxlength="40" placeholder="Enter Registration Number" class="form-control" value=<?=$registrationNumber?>>
                            </div>
                        </div>
                        <!-- <div class="col-md-1"><label for="status">Cooperative ID</label></div>
                        <div class="col-md-3">
                            <div class="form-group">
                            <input type="text" name="cooperative_id" id="cooperative_id" maxlength="40" placeholder="Enter Cooperative ID" class="form-control" value=<?=$cooperativeId?>>
                            </div>
                        </div> -->
                    </div>
                    <div class="row2">
                        <div class="col-md-1"><label for="page_name">Reference Year</label></div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" name="reference_year" id="reference_year" maxlength="40" placeholder="Enter Reference Year" class="form-control" value=<?=$referenceYear?>>
                            </div>
                        </div>
                        <div class="col-md-1"><label for="page_name">State</label></div>
                        <div class="col-md-3">
                            <div class="form-group">
                            <?=$this->form->select('state',$sOption,['class'=>'form-control','empty'=>'--Select--','value'=>$state])?>
                            </div>
                        </div>
                        <div class="col-md-1"><label for="page_name">Location</label></div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <?php $arr_location = ['1'=>'Urban','2'=>'Rural']; ?>
                            <?=$this->form->select('location',$arr_location,['class'=>'form-control','empty'=>'--Select--','value'=>$location])?>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="row3">
                        <div class="col-md-5"></div>
                        <div class="col-md-1"><button name="search_button" value="search_button" type="submit"
                                class="btn btn-primary btn-green">Search</button></div>
                        <div class="col-md-1">
                            <?= $this->Html->link(__('Reset'),['controller'=>'CooperativeRegistrations','action'=>'dataEntryRejected'],['class'=>'btn btn-danger']); ?>
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
        <?= $this->Flash->render(); ?>
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title"><?= __('List of') ?> <?= __('Cooperative Registrations Return For Correction') ?></h3>
                    <div class="box-tools">
                        <?=$this->Html->link(
                __('<i class="glyphicon glyphicon-plus"></i> New Cooperative Registration'),
                ['action' => 'add'],
                ['class' => 'btn btn-success', 'escape' => false, 'title' => __('Add New Cooperative Registration')]
              );?>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th scope="col"><?= $this->Paginator->sort('id','SNo.') ?></th>
                                <th scope="col">
                                    <?= $this->Paginator->sort('cooperative_society_name','Cooperative Society Name') ?>
                                </th>
                                <th scope="col"><?= $this->Paginator->sort('state','State') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('location','Location') ?></th>
                                <th scope="col">
                                    <?= $this->Paginator->sort('registration_number','Registration Number') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('cooperative_id','Cooperative ID') ?></th>
                               
                                <th scope="col">
                                    <?= $this->Paginator->sort('date_registration','Date of Registration') ?></th>
                                <th scope="col" class="actions"><?= __('Actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                $i=1;
                  $paginatorInformation = $this->Paginator->params();
                  $pageOffset = ($paginatorInformation['page'] - 1);
                  $perPage = $paginatorInformation['perPage'];
                  $counter = (($pageOffset * $perPage));
                  foreach ($CooperativeRegistrations as $CooperativeRegistration): 
                ?>
                            <tr>
                                <td> <?php  
                        echo $this->Number->format($i + $counter) 
                      ?>

                                </td>
                                <td><?= h($CooperativeRegistration->cooperative_society_name) ?></td>
                                <td><?= h($sOption[$CooperativeRegistration->state_code]) ?></td>
                                <td><?= h($arr_location[$CooperativeRegistration->location_of_head_quarter]) ?></td>
                                <td><?= h($CooperativeRegistration->registration_number) ?></td>
                                <td><?= str_pad($CooperativeRegistration->sector_of_operation, 2, "0", STR_PAD_LEFT) . str_pad($CooperativeRegistration->cooperative_id_num, 7, "0", STR_PAD_LEFT);
                                        //h($CooperativeRegistration->cooperative_id) ?></td>
                                <td><?php if(!empty($CooperativeRegistration->date_registration)){
                                    $time = new FrozenTime($CooperativeRegistration->date_registration);
                                    echo $time->format('d/m/Y');
                                }?></td>
                                <td class="actions">
                                    <?= $this->Html->link('<i class="fa fa-eye"></i>', ['action' => 'view', $CooperativeRegistration->id],['class' => 'btn btn-info btn-xs', 'title' => __('View'), 'escape' => false]) ?>
                                    <?= $this->Html->link('<i class="fa fa-edit"></i>', ['action' => 'edit', $CooperativeRegistration->id],['class' => 'btn btn-warning btn-xs', 'title' => __('Edit'), 'escape' => false]) ?>
                                    <?= $this->Form->postLink('<i class="fa fa-trash-o"></i>', ['action' => 'delete', $CooperativeRegistration->id], ['confirm' => __('Are you sure you want to delete this Cooperative Registration?', $CooperativeRegistration->id),'class' => 'btn btn-danger btn-xs', 'title' => __('Delete'), 'escape' => false]) ?>
                                </td>
                            </tr>
                            <?php $i++;endforeach; ?>
                            <?php if(count($CooperativeRegistrations) == 0):?>
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