<?php
/**
  * @var \App\View\AppView $this
  * @author Nilesh Kumar
  */
?>

<?php
	$pmodules = [];
	$cmodule = [];
	$i=0;
	foreach($modrolp as $module) {
		if($module['module']['pid'] == 0){
			$pmodules[$i]['id'] = $module['module']['id'];
			$pmodules[$i]['name'] = $module['module']['name'];
			$pmodules[$i]['description'] = $module['module']['description'];
			$pmodules[$i]['pid'] = $module['module']['pid'];
			$pmodules[$i]['action'] = $module['module']['action'];
			$pmodules[$i]['controller'] = $module['module']['controller'];
			$pmodules[$i]['icon'] = $module['module']['icon'];
			$pmodules[$i]['created'] = $module['module']['created'];
			$pmodules[$i]['modified'] = $module['module']['modified'];
			$pmodules[$i]['rolepermission'] = $module['module']['rolepermission'];
		}
		if($module['module']['pid'] != 0) {
			$cmodule[$i]['id'] = $module['module']['id'];
			$cmodule[$i]['name'] = $module['module']['name'];
			$cmodule[$i]['description'] = $module['module']['description'];
			$cmodule[$i]['pid'] = $module['module']['pid'];
			$cmodule[$i]['action'] = $module['module']['action'];
			$cmodule[$i]['controller'] = $module['module']['controller'];
			$cmodule[$i]['icon'] = $module['module']['icon'];
			$cmodule[$i]['created'] = $module['module']['created'];
			$cmodule[$i]['modified'] = $module['module']['modified'];
			$cmodule[$i]['rolepermission'] = $module['module']['rolepermission'];
		}
		$i++;
	}
	
	$prntmn = array_values($pmodules);
	$childmn = array_values($cmodule);
	
	//echo '<pre>'; echo 'parent array is: ';print_r($prntmn);echo '</pre>';
	//echo '<pre>'; echo 'child array is: '; print_r($childmn);echo '</pre>';
 ?>
<div class="content-wrapper">
     <div class="page-header position-relative">
		<ol class="breadcrumb">
			<li>Admin</li>
			<li class="active">Role Based Access</li>
		</ol>
    </div>
	<section class="content">
		<div class="contentbox dak">
			<?php if(isset($message)) { ?>
			<span id="lblMessage" class="success">
				 <?php echo $this->Flash->render();
					echo "<p>".$message."</p>"; ?>
			</span> <?php  }  ?>
		</div>
		<div class="contentbox">
		<form id="searchperm" name="searchperm" method="post">
			<div class="row">
				<div class="topmargin30 tableCenter " style="margin-left:-17px;">
					<div class="leftdiv">Role Name</div>
					<div class="rightdiv">
						<!-- <input type="text" name="rolename" placeholder="Enter Text"> -->
						<?php
						$rolenameId = isset($rolenameId)?$rolenameId:'';
						?>
						<select name="rolename">
							<option value=""><?php echo __('-Select-') ?></option>
							<?php foreach($roleslistv as $roleslv) {
									?>
									<option <?php if($rolenameId == $roleslv['id']): ?> selected="selected"<?php endif; ?> value="<?php echo $roleslv['id']; ?>" ><?php echo $roleslv['name']; ?></option>
									<?php
									}
							?>
						</select>
					</div>
				</div>
			</div>
			<!-- row sec start-->
			<div class="row">
				<div class="topmargin30 tableCenter">
					<div class="leftdiv"></div>
					<div class="rightdiv">
						<div class="topmargin30" style="margin-left:-16px;">
							<button class="btn btn-mini btn-primary" id="search"><i class=" "></i>search</button>
						</div>
					</div>
				</div>
			</div>
			</form>
		</div>	
		<!--table start-->
		
		<?php 
		if(!empty($roles)) {
			?>
		<div class="tablebox">
			<div class="row">
				<div class="user-table " style="width:100%; margin:0px auto 0; border:1px solid #ddd;">
					<form id="rolebaseform" name="rolebaseform" method="post">
						<input type="hidden" name="rolename" value="<?php echo $rolenameId; ?>"> <!-- 109 rid=3,mid=4 || 121 rid=3,mid=1 || 225  rid=3,mid=16,  -->
						<div id="example" class="user-section table-user" style="overflow-x:hidden !important;">        
							<table style="width:100%">
								<tr>
									<th>&nbsp;</th>
									<?php 
									///if(!empty($roles)) {
									foreach($roles as $role) { ?>
											<?php  ?>
											<th id="<?= $role['id']; ?>"><?php echo __($role['name']); ?></th>
									<?php }	?>
								</tr> 
								<?php 
									for($ij=0;$ij<count($prntmn);$ij++){
										for($jk=0;$jk<count($childmn);$jk++){
											//echo '<pre>';echo 'child '.$jk.' element is : ';print_r($childmn[$jk]);echo '</pre>';
											if($prntmn[$ij]['id'] === $childmn[$jk]['pid']) {
												$prntmn[$ij]['child'][] = $childmn[$jk];
											}
										}
									}
									$outputArr = array_values($prntmn);
									//echo '<pre>'; echo 'output array is: '; print_r($outputArr);echo '</pre>';
									
									$j = 0;
									foreach($outputArr as $output) {
										//echo '<pre>'; echo 'output element is: '; print_r($output);echo '</pre>';
										if(!empty($output['child'])) {
											?>
											<tr>
												<!-- <td colspan="<?php echo count($roles)+1; ?>"> -->
												<td colspan="<?php echo count($roles); ?>">
													<strong><?php echo $output['name']; ?></strong>
												</td>
												<td>
												<?php //debug($output); ?>
													<input type="checkbox" name="parentCheckBox[]" id="selectall_<?php echo $output['id']; ?>" value="<?php echo $output['id'];?>" class="parentItem" onclick="selectAllChild(this);" /> <!--  onclick="selectAllChild(this);" -->
												</td>
											</tr>
											<?php
											foreach($output['child'] as $child) {
												
											?>
												<tr>
													<td><?php echo $child['name']; ?></td>
													<?php foreach($roles as $role) { ?>
													<td id="<?= 'rid'.$role['id'].'_mid'.$child['id']; ?>">
													
													
													
													<?php $checkboxVal = $role['id'].','.$child['id']; ?>
													<?php														
														if(!empty($child['rolepermission'])) { //rolepermission
															foreach($child['rolepermission'] as $childperm) {
																if($role['id'] == $childperm['rid']) {
																	$checkboxcv = $childperm['rid'].','.$child['id'];
																}
															}
														}
													?>
													
														<?php //echo __($role['name']); ?>
														<?php //$checkboxVal = $role['id'].','.$child['id'];  ?>
														<input type="checkbox" name="chkid[]" value="<?php echo $checkboxVal; ?>" 
															<?php 
																if(isset($checkboxcv)) {
																	if($checkboxVal == $checkboxcv) {
																		echo ' '.'checked';
																	} 
																}  ?>    
														 id="child_<?php echo $output['id'].'_'.$child['id']; ?>" class="child_<?php echo $output['id']; ?>"  onclick="selectParent(this);" />
													</td>
											<?php }	?>
												</tr>
											<?php
											}
										?>
											<tr style="background-color:#ffffff;"><td colspan="<?php echo count($roles)+1; ?>"></td></tr>
										<?php
										} else {
											?>
											<tr>
												<td>
													<strong><?php echo $output['name']; ?></strong>
												</td>
											
												<?php 
												foreach($roles as $role) { 
													//$checkboxcv = '';
													if(!empty($output['rolepermission'])) { //rolepermission
														
													}
												?>
												<td id="<?= 'rid'.$role['id'].'_mid'.$output['id']; ?>">
													<?php $checkboxVal = $role['id'].','.$output['id']; ?>
													<?php
														if(!empty($output['rolepermission'])) { //rolepermission
															foreach($output['rolepermission'] as $childperm) {
																if($role['id'] == $childperm['rid']) {
																	$checkboxcv = $childperm['rid'].','.$output['id'];
																}
															}
														}
													?>
													<input type="checkbox" name="chkid[]" value="<?php echo $checkboxVal; //echo $role['id'].','.$output['id'];  ?>"
													<?php 
													if(isset($checkboxcv)) {
														if($checkboxVal == $checkboxcv) {
															echo ' '.'checked';
														} 
													}
													?>      
													id="child_<?php echo $output['id']; ?>" class="child_<?php echo $output['id']; ?>"
													/>
												</td>
											<?php } ?>
											</tr>
											<?php
										}
										$j++;
									}
								?>
							</table>
						</div>
						<div style="text-align:center; margin-top:10px; margin-bottom:10px;"><button type="submit" class="btn btn-mini btn-primary" style="width:120px">Save</button></div>
					</form>
				</div>
			</div>
		</div>
		<?php } ?>
		<!--table end-->
	</section>
</div>

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
/* onclick of parentItem this code will run	
	$('.parentItem').click(function(){
		var parentCheckBoxId = this.id;
		var parentCheckBoxValue = this.value;
		var childClassByItsParent = 'child_'+parentCheckBoxValue;
		
		if($('#'+parentCheckBoxId).is(':checked')){
			$('.'+childClassByItsParent).prop('checked', true);
		} else {
			$('.'+childClassByItsParent).prop('checked', false);
		}
	});
*/

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
</script>