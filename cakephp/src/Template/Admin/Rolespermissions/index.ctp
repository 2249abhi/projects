<?php
/**
  * @var \App\View\AppView $this
  */
?>
<?php
$this->assign('title',__('Role Permissions'));
$this->Breadcrumbs->add(__('Role Permissions'));

if(isset($selectedLen)){
    $selectedLen =$selectedLen;
}else {
    $selectedLen = '';
}
$selectedmodule=!empty($selectedmodule)?$selectedmodule:'';
$selectedrole=!empty($selectedrole)?$selectedrole:'';
?>

<section class="content rolespermissions index">
	<div class="row">
	    <div class="col-md-12">
	    	<?= $this->Flash->render() ; ?>
		    <div class="box box-primary">
		        <div class="box-header">
		            <h3 class="box-title"><?= __('Search - User Roles'); ?></h3>
		        </div> 
				<?= $this->Form->create('searchRoles',['id' =>'searchRoles','type'=>'get']); ?>
				    <div class="box-body table-responsive">
				    	<?php echo $this->element('errorlist'); ?>				    	
					    <div class="col-md-6">
					      <div class="form-group">
					        <label class="col-md-4 control-label" for="roles">Roles</label>
					        <div class="col-md-8">
					          <select name="role" id="roles" title="Please select Role" class="form-control input-md" required>
								<option value="">--Select--</option>
								<?php echo $this->OptionsData->list_toOption($role_result, $selectedrole) ?>
							</select>
					        </div>
					      </div>
					    </div>
					    <div class="col-md-6">
					      <div class="form-group">        
					        <label class="col-md-4 control-label" for="module">Modules</label>
					        <div class="col-md-8">
					          	<select name="module" id="module" title="Please select Module" class="form-control input-md" required>
									<option value="">--Select--</option>
									<?php echo $this->OptionsData->list_toOption($module_result, $selectedmodule) ?>
								</select>
					        </div>
					      </div>    
					    </div>
				    	<div class="clearfix"></div>
				    	<br>
					    <div class="col-md-12 text-center">
					        <button type="submit" class="btn btn-primary">Search</button>
					    </div>
					</div>
				<?php echo $this->Form->end(); ?>
		  	</div>
		</div>
	</div>
<?php $this->append('bottom-script');?>
<script>
	var container = $('div.container');
	  	$("#searchRoles").validate({
      	errorContainer: container,
      	errorLabelContainer: $("ol", container),
      	wrapper: 'li',
  	});
</script>
<?php $this->end(); ?>
<?php if(@$modrolpResult){ ?>
<div class="list-table">
	<div class="row">
		<div class="col-md-12">
		  	<div class="panel-sec">
		  		<?= $this->Form->create('rolebaseform',['id' => 'rolebaseform']); ?> 
			    <div class="panel panel-default panel-table">
			    	<div class="panel-body">					    
						<input type="hidden" name="roleId" value="<?php echo $selectedrole; ?>">
				        <table class="table table-striped table-bordered">
				          	<thead>
					            <tr>
					              	<th class="hidden-xs"><?= __('Module Name') ?></th>
									<th class="hidden-xs"><?= __('Description') ?></th>
									<th><?=$role_result[$selectedrole]?></th>
									<th style="text-align: right;width: 20%;">Don't show Navigation menu</th>
					            </tr>
					        </thead>
				          	<tbody>
				          		<?php foreach($modrolpResult as $module){
				          			if(empty($module['pid'])){ ?>
				          			<tr>
								        <th><?=$module['name']?></th>
										<th><?=$module['description']?></th>
										<th align="center" style="text-align:center;"><input type="checkbox" id="selectall_<?php echo $module['id']; ?>" value="<?php echo $module['id'];?>" class="parentItem" onclick="selectAllChild(this);" /></th>
										
										<th align="center" style="text-align:center;"><input type="checkbox" id="selectallshow_<?php echo $module['id']; ?>" value="<?php echo $module['id'];?>" class="parentItem" onclick="selectAllshow(this);" /></th>
									</tr>
									<?php } foreach($module['child'] as $submodule){ $ck='';  $cks='';?>
									<tr>
									    <td><?=$submodule['name']?></td>
										<td><?=$submodule['description']?></td>
										<td align="center">
										<input type="hidden" name="chkid[<?=$submodule['id']?>]" value="0" />
										<?php if($submodule['selected'] == 'true') { $ck="checked"; } ?>    
										<input type="checkbox" name="chkid[<?=$submodule['id']?>]" value="<?=$submodule['id']?>" id="child_<?php echo $module['id'].'_'.$submodule['id']; ?>" class="child_<?php echo $module['id']; ?>"  onclick="selectParent(this);" <?=$ck?>>
										</td>								
										<td align="center" style="text-align:center;">
										<input type="hidden" name="chkid_dshow[<?=$submodule['id']?>]" value="0" />
										<?php if(!empty($submodule['navigationshow'])) { $cks="checked"; } ?>    
										<input type="checkbox" name="chkid_dshow[<?=$submodule['id']?>]" value="<?=$submodule['id']?>" id="childshow_<?php echo $module['id'].'_'.$submodule['id']; ?>" class="childshow_<?php echo $module['id']; ?>"  onclick="selectParentShow(this);" <?=$cks?>>
										</td>
									</tr>
								<?php }  } ?>
				          	</tbody>
				        </table>
				    </div>
				</div>
				<div style="text-align:center; margin-top:10px; margin-bottom:10px;">
					<button type="submit" class="btn btn-primary">Save</button>
				</div>
				<?= $this->Form->end()?>
			</div>
		</div>
	</div>
</div>
<?php $this->append('bottom-script');?>
<script type="text/javascript">
	// this function for selecting and unselectting on checked or unchecked of parent checkbox.
	function selectAllChild(element){
		var parentCheckBoxId = element.id;
		var parentCheckBoxValue = element.value;
		
		var childClassByItsParent = 'child_'+parentCheckBoxValue;

		if($('#'+parentCheckBoxId).is(':checked')){
			$('.'+childClassByItsParent).prop('checked', true);
		} else {
			$('.'+childClassByItsParent).prop('checked', false);
		}
	}
	
	function selectAllshow(element){
		var parentCheckBoxId = element.id;
		var parentCheckBoxValue = element.value;
		
		var childClassByItsParent = 'childshow_'+parentCheckBoxValue;

		if($('#'+parentCheckBoxId).is(':checked')){
			$('.'+childClassByItsParent).prop('checked', true);
		} else {
			$('.'+childClassByItsParent).prop('checked', false);
		}
	}


	// function for selecting and unselecting parent element on check or un check of child element
	function selectParent(element) {
		var childCheckBoxId = element.id;
		var childCheckBoxIdArr = childCheckBoxId.split('_');
		
		var parentId = childCheckBoxIdArr[1];
		var childId = childCheckBoxIdArr[2];

		var parentCheckBoxId = 'selectall_'+parentId;
		var ChildCheckBoxClass = 'child_'+parentId;

		if($("."+ChildCheckBoxClass).length == $("."+ChildCheckBoxClass+":checked").length) {
			$("#"+parentCheckBoxId).prop("checked",true);
		} else {
			$("#"+parentCheckBoxId).prop("checked",false);
		}
	}
	
	function selectParentShow(element) {
		var childCheckBoxId = element.id;
		var childCheckBoxIdArr = childCheckBoxId.split('_');
		
		var parentId = childCheckBoxIdArr[1];
		var childId = childCheckBoxIdArr[2];

		var parentCheckBoxId = 'selectallshow_'+parentId;
		var ChildCheckBoxClass = 'childshow_'+parentId;

		if($("."+ChildCheckBoxClass).length == $("."+ChildCheckBoxClass+":checked").length) {
			$("#"+parentCheckBoxId).prop("checked",true);
		} else {
			$("#"+parentCheckBoxId).prop("checked",false);
		}
	}
</script>
<?php $this->end(); ?>
<?php } ?>
</section>