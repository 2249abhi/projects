<?php
/**
 * @var \App\View\AppView $this
 * @author : Nilesh Kushvaha
 * @version : 1.1
 * @since : 24 October 2018
 */
?>
<?php
$this->assign('title',__('Modules'));
$this->Breadcrumbs->add(__('Modules'));

if(isset($selectedLen)){
    $selectedLen =$selectedLen;
}else {
    $selectedLen = '';
}
?>
<section class="content modules index">
	<div class="row">
	    <div class="col-md-12">
	    	<?= $this->Flash->render() ; ?>
		    <div class="box box-primary">
		        <div class="box-header">
		            <h3 class="box-title"><?= __('Search - Sub Modules'); ?></h3>
		        </div> 
				<?= $this->Form->create('searchRoles',['id' =>'searchRoles','type'=>'get']); ?>
			    <div class="box-body table-responsive">
			    	<?php echo $this->element('errorlist'); ?>				    	
				    <div class="col-md-6 col-md-offset-3">
				      	<div class="form-group">
				        	<label class="col-md-3 control-label" for="selectbasic">Module</label>
		                    <div class="col-md-6">
		                    	<select name="pid" id="pid" title="Please select Module" class="form-control input-md">
									<option value="">--Select--</option>
									<?php echo $this->OptionsData->list_toOption($module_result, $selectedmod) ?>
								</select>
							</div>
				      	</div>
				    </div>
			    	<div class="clearfix"></div>
			    	<br>
			    	<input type="hidden" name="page_length" value="<?=$selectedLen?>">
				    <div class="col-md-10 text-center">
				        <button type="submit" class="btn btn-primary">Search</button>
				    </div>
				</div>
				<?= $this->Form->end(); ?>
		  	</div>
		</div>
	</div>

	<div class="row">
      <div class="col-xs-12">
        <div class="box box-primary">
        <?= $this->Form->create('page_number',['id'=>'module_list','type'=>'get','class'=>'form-inline']); ?>
          	<div class="box-header">
            	<h3 class="box-title">
            		<div class="form-group">
					    <label for="page_length">Display:</label>
					    <?php $page_length = array('10'=>'10','20'=>'20','50'=>'50','100'=>'100','200'=>'200','all'=>'All'); ?>
					    <select class="form-control" name="page_length" id="page_length">
	                	<?= $this->OptionsData->list_toOption($page_length, $selectedId = $selectedLen)?>
	               		</select>
						<input type="hidden" name="pid" value="<?=@$selectedmod?>">
					</div>
	          	</h3>
	            <div class="box-tools">
	            	<?php echo $this->Html->link(
						'Add New Module',
						['controller' => 'Modules', 'action' => 'addModule', '_full' => true],
						['class' => 'mt4px btn btn-primary btn-green']
					); ?>
					<button name="export_excel" value="export_excel" class="mt4px btn btn-primary btn-green">Export to Excel</button>
	            </div>
            </div>
            <?= $this->Form->end()?>
          <!-- /.box-header -->
          <div class="box-body table-responsive">
            <table class="table table-hover table-bordered">
            	<thead>
		            <tr>
		              	<th class="hidden-xs"><?= __('Sr. No') ?></th>
						<th><?= __('Name') ?></th>				
						<?php if(@$selectedmod){?>
						<th><?= __('Description') ?></th>
						<th><?= __('Controller Name') ?></th> 
						<th><?= __('Action Name') ?></th> 
						<th><?= __('Position') ?></th> 
						<?php }else{ ?>
						<th><?= __('Sub Module') ?></th> 
						<?php } ?>				
		              	<th><?= __('Actions') ?></th>
		            </tr>
		        </thead>
		        <tbody>
	          		<?php
					$paginatorInformation = $this->Paginator->params();
	                $pageOffset=($paginatorInformation['page']-1);
	                $perPage = $paginatorInformation['perPage'];
	                $counter = (($pageOffset*$perPage));
					$totRole = count($dsdetails);
					if($totRole) {
						$i = 1;
	                     $activatedRoles = [];
	                     $deActivatedRoles = [];
	                    foreach ($dsdetails as $ddet):  ?>
		            <tr>
		              	<td align="center"><?= $this->Number->format($i+$counter) ?></td>
		              	<td><?= h($ddet['name']) ?></td>
						<?php if(@$selectedmod){?>
						<td><?= h($ddet['description']) ?></td>
		              	<td><?= h($ddet['controller']) ?></td>
		              	<td><?= h($ddet['action']) ?></td>
						<td><?= h($ddet['depth']) ?></td>
						<?php }else{ ?>
						<td><?= $this->Html->link(__('Sub Module'),['controller'=>'Modules','action'=>'index','?'=>['pid' => $ddet['id'],'page_length' => $selectedLen]]);
						?> </td>
						<?php } ?>
		              	<td><?= $this->Html->link(__('Update'),['controller'=>'Modules','action'=>'editModule',$ddet['id']]); ?> </td>
		            </tr>
		            <?php $i++;
							endforeach;
					} else { ?>
					<tr>
						<td colspan="4"><?= __('No Record Found'); ?></td>
					</tr>
					<?php } ?>
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
            $('#module_list').submit();
        })
    });
</script>
<?php $this->end();?>