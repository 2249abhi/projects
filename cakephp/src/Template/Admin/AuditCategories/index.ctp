<?php
/**
 * @author \Nilesh Kushvaha
 * @version \1.1
 * @since \October 29, 2018, 7:06 am
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SecondaryActivities[]|\Cake\Collection\CollectionInterface $SecondaryActivities
 */
$this->assign('title',__('Audit Categories'));
$this->assign('content_header',__('List of Audit Categories'));
$this->Breadcrumbs->add(__('Audit Categories'));
?>
<!-- Main content -->
<section class="content pactivity index">

    <div class="row">
        <div class="col-xs-12">
            <?= $this->Flash->render(); ?>
            <?= $this->Flash->render('auth'); ?>
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title"><?= __('Search - Audit Categories'); ?></h3>
                    <!--<div class="SrchBx">
                        <button id="TglSrch" class="btn btn-info">
                            <i class=" fa fa-angle-double-down" aria-hidden="true"></i>&nbsp;Search
                        </button>
                    </div>-->
                </div>   
                <?= $this->Form->create('searchsactivity',['id' =>'searchsactivity','type'=>'get']); ?>
                <div class="box-body table-responsive">
                    <?= $this->element('errorlist'); ?>
                    <div class="row2">
                        <div class="col-md-1"><label for="page_name">Name</label></div>
                        <div class="col-md-3">
                            <div class="form-group">
                            <input type="text" name="name" id="name" maxlength="40"
                                    placeholder="Enter Audit Categories Name" class="form-control" value=<?=$s_name?>>
                            </div>
                        </div>
                        <div class="col-md-1"><label for="page_name">Status</label></div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <?=$this->form->select('status',$status,['class'=>'form-control','empty'=>'--Select--','value'=>$s_status])?>
                            </div>
                        </div>
                    </div>
					<br>
                    <div class="clearfix"></div>
                    <div class="row mt-10 text-center">
                  
                      <button name="search_button" value="search_button" type="submit"
                                class="btn btn-primary btn-green">Search</button>
                       
                            <?= $this->Html->link(__('Reset'),['controller'=>'AuditCategories','action'=>'index'],['class'=>'btn btn-danger mx-1']); ?>
                      
                    </div>  </div>  </div>  </div>
                    <div class="clearfix"></div>
                    <br>
                    <input type="hidden" name="page_length" value="<?=@$selectedLen?>">
                </div>
                <?= $this->Form->end(); ?>
				
				
                <!-- /.box-header -->
				<div class="box box-primary box-st">
                <div class="box-body table-responsive">
				    <div class="box-tools pull-right">
                        <?=$this->Html->link(
                      __('<i class="glyphicon glyphicon-plus"></i> New Audit Categories'),
                      ['action' => 'add'],
                      ['class' => 'btn btn-success', 'escape' => false, 'title' => __('Add Audit Category')]
                  );?>
                    </div>
                    <table class="table table-hover table-bordered table-striped">
                        <thead>
                            <tr>
                                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('order') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('status') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
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

                foreach ($auditcategories as $activity): ?>
                            <tr>
                                <td><?= $this->Number->format($i + $counter) ?></td>
                                <td><?= h($activity->name) ?></td>
                                <td><?= h($activity->orderseq) ?></td>
                                <td><?= h($status[$activity->status]) ?></td>
                                <td><?= h($activity->created_at) ?></td>
                                <td class="actions">
                                    <?= $this->Html->link('<i class="fa fa-eye"></i>', ['action' => 'view', $activity->id],['class' => 'btn btn-info btn-xs', 'title' => __('View'), 'escape' => false]) ?>
                                    <?= $this->Html->link('<i class="fa fa-edit"></i>', ['action' => 'edit', $activity->id],['class' => 'btn btn-warning btn-xs', 'title' => __('Edit'), 'escape' => false]) ?>
                                    <?= $this->Form->postLink('<i class="fa fa-trash-o"></i>', ['action' => 'delete', $activity->id], ['confirm' => __('Are you sure you want to delete this Audit Category?', $activity->id),'class' => 'btn btn-danger btn-xs', 'title' => __('Delete'), 'escape' => false]) ?>
                                </td>
                            </tr>
                            <?php $i++;endforeach; ?>
                            <?php if(count($sactivities) == 0):?>
                            <tr>
                                <td colspan="6"><?= __('Record not found!'); ?></td>
                            </tr>
                            <?php endif;?>
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
                <div class="box-footer clearfix">
                    <div style="float:left;">
                        <?php echo 'Showing '.$paginatorInformation['start'].' to '.$paginatorInformation['end'].' of, '.$paginatorInformation['count'].' entries'; ?>
                    </div>
                    <div>
                        <ul class="pagination pagination-sm no-margin pull-right">
                            <?= $this->Paginator->first('<<') ?>
                            <?= $this->Paginator->prev('<') ?>
                            <?= $this->Paginator->numbers() ?>
                            <?= $this->Paginator->next('>') ?>
                            <?= $this->Paginator->last('>>') ?>
                        </ul>
                    </div>
  </div>  </div></div>
                </div>
            </div>   </div>
            <!-- /.box -->
        </div>
    </div>
</section>
<?php $this->append('bottom-script');?>
<script type="text/javascript">
$(document).ready(function(){
			$("#TglSrch").click(function(){
			$("#searchsactivity").slideToggle(1500);
			});
        

		$("#TglSrch").on('click', function() {
            $(this).toggleClass('is-active').next("#searchsactivity").stop().slideToggle(500);
        });
});
</script>
<?php $this->end(); ?>