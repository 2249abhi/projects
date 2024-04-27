<?php
/**
 * @author \Nilesh Kushvaha
 * @version \1.1
 * @since \October 29, 2018, 7:12 am
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Cooperative Registration[]|\Cake\Collection\CollectionInterface $CooperativeRegistrations
 */
$this->assign('title',__('Cooperative Society'));
$this->assign('content_header',__('List of Cooperative Registrations Return'));
$this->Breadcrumbs->add(__('Cooperative Society'));
$this->assign('top_head',__('hide'));
use Cake\I18n\FrozenTime;
?>
<!-- Main content -->
<section class="content Cooperative Registration index">
    <div class="row">
        <div class="col-xs-12">
            <?= $this->Flash->render(); ?>
            <?= $this->Flash->render('auth'); ?>
            <div class="box box-primary">
                <div class="box-header" >
                    <h3 class="box-title titleSrch"><?= __('Search - Cooperative Society Retrurn'); ?></h3>
					<div class="SrchBx">
						<button id="TglSrch" class="btn btn-info"></button>
					</div>
				</div>
                <?= $this->Form->create('searchBanner',['id' =>'searchBanner','type'=>'get']); ?>
                <div class="box-body table-responsive">
                    <?= $this->element('errorlist'); ?>

                 <?php  if($this->request->session()->read('Auth.User.role_id') == 11)
                     {
                     ?>

                        <div class="row1">  
                            
                        <div class="col-md-1"><label for="page_name">Society Name</label></div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" name="society_name" id="society_name" maxlength="40"
                                    placeholder="Enter Society Name" class="form-control" value=<?=$societyName?>>
                            </div>
                        </div>
                       
                        <div class="col-md-1"><label for="status">Registration Number</label></div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" name="registration_number" id="registration_number" maxlength="40"
                                    placeholder="Enter Registration Number" class="form-control"
                                    value=<?=$registrationNumber?>>
                            </div>
                        </div>   

                        <div class="col-md-1"><label for="page_name">District</label></div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <?=$this->form->select('district',$districts,['class'=>'form-control select2','id'=>'district','empty'=>'--Select--','value'=>$s_district])?>
                                </div>
                            </div>                  
                    </div>

                 <?php } else { ?>

<div class="row1">
    <div class="col-md-1"><label for="page_name">Society Name</label></div>
    <div class="col-md-3">
        <div class="form-group">
            <input type="text" name="society_name" id="society_name" maxlength="100"
                placeholder="Enter Society Name" class="form-control" value="<?=$societyName?>">
        </div>
    </div>
    <div class="col-md-1"><label for="status">Registration Number</label></div>
    <div class="col-md-3">
        <div class="form-group">
            <input type="text" name="registration_number" id="registration_number" maxlength="40"
                placeholder="Enter Registration Number" class="form-control"
                value="<?=$registrationNumber?>">
        </div>
    </div>
    <!-- <div class="col-md-1"><label for="status">Cooperative ID</label></div>
    <div class="col-md-3">
        <div class="form-group">
            <input type="text" name="cooperative_id" id="cooperative_id" maxlength="40"
                placeholder="Enter Cooperative ID" class="form-control" value=<?=$cooperativeId?>>
        </div>
    </div> -->
</div>
<div class="row2">
   <div class="col-md-1"><label for="page_name">Sector of Operation</label></div>
    <div class="col-md-3">
        <div class="form-group">
            <?=$this->form->select('sector_operation',$sectorOperations,['class'=>'form-control','id'=>'sector_operation','empty'=>'--Select--','value'=>$s_sector_operation])?>
        </div>
    </div>
    <div class="col-md-1"><label for="page_name">Primary Activity</label></div>
    <div class="col-md-3">
        <div class="form-group">
        <?=$this->form->select('primary_activity',$primary_activities,['class'=>'form-control select2','id'=>'primary_activity','empty'=>'--Select--','value'=>$s_primary_activity])?>
        </div>
    </div>
   <!-- <div class="col-md-1"><label for="page_name">Secondary Activity</label></div>
    <div class="col-md-3">
        <div class="form-group">
        <?=$this->form->select('secondary_activity',$secondary_activities,['class'=>'form-control select2','empty'=>'--Select--','value'=>$s_secondary_activity])?>
        </div>
    </div>
</div> -->
<div class="row2">
    <!-- <div class="col-md-1"><label for="page_name">Reference Year</label></div>
    <div class="col-md-3">
        <div class="form-group">
            <input type="text" name="reference_year" id="reference_year" maxlength="40"
                placeholder="Enter Reference Year" class="form-control" value=<?=$referenceYear?>>
        </div>
    </div> -->
    <div class="col-md-1"><label for="page_name">Location</label></div>
    <div class="col-md-3">
        <div class="form-group">
            <?php $arr_location = ['1'=>'Urban','2'=>'Rural']; ?>
        <?=$this->form->select('location',$arr_location,['class'=>'form-control','id'=>'location','empty'=>'--Select--','value'=>$location])?>
        </div>
    </div>
    <div class="col-md-1"><label for="page_name">State</label></div>
    <div class="col-md-3">
        <div class="form-group">
            <?=$this->form->select('state',$sOption,['class'=>'form-control select2','id'=>'state','empty'=>'--Select--','value'=>$state])?>
        </div>
    </div>
</div>
<div class="rural_filter1" style="display:<?= $_GET['location'] == 2 ? 'block' : 'block' ; ?>;">
    <div class="row2">
        <div class="col-md-1"><label for="page_name">District</label></div>
        <div class="col-md-3">
            <div class="form-group">
                <?=$this->form->select('district',$districts,['class'=>'form-control select2','id'=>'district','empty'=>'--Select--','value'=>$s_district])?>
            </div>
        </div>
        <!-- <div class="col-md-1"><label for="page_name">Block</label></div>
        <div class="col-md-3">
            <div class="form-group">
            <?=$this->form->select('block',$blocks,['class'=>'form-control select2','id'=>'block','empty'=>'--Select--','value'=>$s_block])?>
            </div>
        </div>
        <div class="col-md-1"><label for="page_name">Panchayat</label></div>
        <div class="col-md-3">
            <div class="form-group">
            <?=$this->form->select('panchayat',$panchayats,['class'=>'form-control select2','id'=>'panchayat','empty'=>'--Select--','value'=>$s_panchayat])?>
            </div>
        </div> -->
    </div>
    <!-- <div class="row2 ">
        <div class="col-md-1"><label for="page_name">Village</label></div>
        <div class="col-md-3">
            <div class="form-group">
                <?=$this->form->select('village',$villages,['class'=>'form-control','id'=>'village','empty'=>'--Select--','value'=>$s_village])?>
            </div>
        </div>
    </div> -->
</div>
 <div class="urban_filter" style="display:<?= $_GET['location'] == 1 ? 'block' : 'none' ; ?>;">
    <div class="row2">
        <div class="col-md-1"><label for="page_name">Local Category</label></div>
        <div class="col-md-3">
            <div class="form-group">
                <?=$this->form->select('local_category',$local_categories,['class'=>'form-control','id'=>'local_category','empty'=>'--Select--','value'=>$s_local_category])?>
            </div>
        </div>
        <div class="col-md-1"><label for="page_name">Local Name</label></div>
        <div class="col-md-3">
            <div class="form-group">
            <?=$this->form->select('local_body',$localbodies,['class'=>'form-control select2','id'=>'local_body','empty'=>'--Select--','value'=>$s_local_body])?>
            </div>
        </div>
        <!-- <div class="col-md-1"><label for="page_name">Ward</label></div>
        <div class="col-md-3">
            <div class="form-group">
            <?=$this->form->select('ward',$wards,['class'=>'form-control select2','id'=>'ward','empty'=>'--Select--','value'=>$s_ward])?>
            </div>
        </div> -->
    </div>
</div> 
<?php } ?>

                    <div class="clearfix"></div>
					
					<div class="row3">
						<div class="col-md-12 text-center">
							<button name="search_button" value="search_button" type="submit" class="btn btn-primary btn-green">Search</button>
							  <?= $this->Html->link(__('Reset'),['controller'=>'CooperativeRegistrations','action'=>'adminRejected'],['class'=>'btn btn-danger mx-1']); ?>
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
            <div class="box box-primary box-st">
                
                <!-- /.box-header -->
                <div class="box-body table-responsive">
				   <div class="sf">
            <div style="float:right;">
            <div class="box-tools">
                     <?php if($this->request->session()->read('Auth.User.role_id') != 11)
                                        {
                                            ?>
                        <span class="addneww btn btn-warning btn-xs"><i class="glyphicon glyphicon-plus"></i></span> 
						<?=$this->Html->link(
                __(' New Cooperative Registration'),
                ['action' => 'add'],
                ['class' => 'btn btn-success btnreg', 'target' => '_blank' ,  'escape' => false, 'title' => __('Add New Cooperative Registration')]
              );?> 
          <?php } ?>
                    </div>
            </div>
        </div>
                    <?= $this->Form->create('bulkapproval',['action'=>'bulkapproval','name'=>'bulkapproval','id'=>'bulkapproval']); ?>
                    <table class="table table-hover table-bordered table-striped">
                        <thead>
                            <tr>
                                <th scope="col"><?= $this->Paginator->sort('id','Sr.No.') ?></th>
                                <th scope="col">
                                    <?= $this->Paginator->sort('cooperative_society_name','Cooperative Society Name') ?>
                                </th>
                                <th scope="col"><?= $this->Paginator->sort('location','Location') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('state','State') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('district/urban_local_body','District/Urban Local Body') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('sector','Sector') ?></th>
                                <th scope="col">
                                    <?= $this->Paginator->sort('registration_number','Registration Number') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('cooperative_id','Cooperative ID') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('reference_rear','Reference Year') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('date_registration','Date of Registration') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('return_by','Return By') ?></th>
                                <th scope="col" class="actions"><?= __('Actions') ?></th>
                            </tr>
                            <tr>
                                    <td colspan="11"></td>
                                    <td><?php echo $this->Form->checkbox('select_all', array('id'=>'checkall', 'name' => 'select_all', 'class'=>'select_all', 'checked' => false, 'hiddenField' => false)); ?>
                                        Check All</td>
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
                                <td><?= h($arr_location[$CooperativeRegistration->location_of_head_quarter]) ?></td>
                                <td><?= h($sOption[$CooperativeRegistration->state_code]) ?></td>
                                <td>
                                <?php 
                                    if($CooperativeRegistration->location_of_head_quarter == 2)
                                    {
                                        echo $arr_districts[$CooperativeRegistration->district_code] ?? '';
                                    }

                                    if($CooperativeRegistration->location_of_head_quarter == 1)
                                    {
                                       echo $arr_localbodies[$CooperativeRegistration->urban_local_body_code] ?? '';
                                    }
                                 ?>
                                </td>
                                <td><?= h($sectors[$CooperativeRegistration->sector_of_operation]) ?></td>
                                <td><?= h($CooperativeRegistration->registration_number) ?></td>
                                <td><?= str_pad($CooperativeRegistration->sector_of_operation, 2, "0", STR_PAD_LEFT) . str_pad($CooperativeRegistration->cooperative_id_num, 7, "0", STR_PAD_LEFT);
                                        //h($CooperativeRegistration->cooperative_id) ?></td>
                                <td><?=$CooperativeRegistration->reference_year?></td>
                                <td><?php if(!empty($CooperativeRegistration->date_registration)){
                                    $time = new FrozenTime($CooperativeRegistration->date_registration);
                                    echo $time->format('d/m/Y');
                                }?></td>
                                <td><?= $users[$CooperativeRegistration->approved_by] ?></td></td>
                                
                                <td class="actions" style="width:100px;">
                                <?php 
                                        if($this->request->session()->read('Auth.User.role_id') == 2 || $this->request->session()->read('Auth.User.role_id') == 14)
                                        {
                                            //Admin
                                            echo $this->Form->checkbox('arr_accept[]', array('value' => $CooperativeRegistration->id, 'name' => 'arr_accept[]', 'class'=>'approve', 'checked' => false, 'hiddenField' => false));
                                        }
                                    ?>
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
                    <?php //if($this->request->session()->read('Auth.User.role_id') == 2 && count($CooperativeRegistrations) > 0){ //For admin reject 
                        if(($this->request->session()->read('Auth.User.role_id') == 2 || $this->request->session()->read('Auth.User.role_id') == 14) && count($CooperativeRegistrations) > 0){ //For admin reject 
                        ?>
                        <div class="approval" style="margin-top:10px;">

                            <div class="col-md-12">
                                <label style="margin-left:15px;">Remarks</label>
                            </div>
                            <div class="row1 col-md-12">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <textarea id="remark" name="remark" rows="8" cols="100" required></textarea>

                                    </div>
                                </div>
                            </div>
                            <div class="row2 col-md-12">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <button name="is_approved" value="1" type="submit" class="btn btn-primary accept btn-green" formnovalidate>Accept</button>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <?php } ?>
                        <?= $this->Form->end(); ?>
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
<style>
table.table.table-hover.table-bordered a {
    color: white;
}
</style>
<?php $this->append('bottom-script');?>
<script type="text/javascript">
        $(document).ready(function(){

            $('.select2').select2();
            $("#searchBanner").show();
			$("#TglSrch").click(function(){
			//$("#searchBanner").slideToggle(1500);
			});

            $('#bulkapproval').submit(function(e) {
                //e.preventDefault();

                if($('.approve:checked').length>0) {
                    //$('form').submit();
                    return true;
                } else {
                    //checkboxes.attr('required', 'required');
                    alert('Please select atleast 1 record');
                    return false;
                }
            });
        
            $('#checkall:checkbox').change(function () {
                console.log('success');
                $("input:checkbox").prop('checked', $(this).prop("checked"));
            });

		//$("#TglSrch").on('click', function() {
       // $(this).toggleClass('is-active').next("#searchBanner").stop().slideToggle(1500);

        $('#searchBanner').on('change','#location',function(e){
                
                // $('#state').val('');
                // $('#district').val('');
                $('#block').val('');
                $('#panchayat').val('');
                $('#village').val('');


                if($(this).val() == 1)
                {
                    $('.rural_filter').hide();
                    $('.urban_filter').show();

                } else if($(this).val() == 2) {
                    $('.urban_filter').hide();
                    $('.rural_filter').show();
                } else {
                    $('.urban_filter').hide();
                    $('.rural_filter').hide();
                }
            });


            $('#searchBanner').on('change','#state',function(e){
            e.preventDefault();
            

            var location_of_head_quarter=$('select[name="location"] option:selected').val();
            if(location_of_head_quarter==1){
                $.ajax({
                    type:'POST',
                    cache: false,
                    url: '<?=$this->Url->build(['action'=>'get-urban-local-bodies'])?>',
                    beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                    },
                    data: {state_code : $(this).val()},
                    success: function(response){
                        $("#local_category").html(response);
                    },
                }); 

                $('.rural_filter').hide();
                $('.urban_filter').show();

            }else{
               // else if(location_of_head_quarter==2){
                $('#district').val('');
                $('#block').val('');
                $('#panchayat').val('');
                $('#village').val('');

                $.ajax({
                    type:'POST',
                    cache: false,
                    url: '<?=$this->Url->build(['action'=>'get-districts'])?>',
                    beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                    },
                    data: {state_code : $(this).val()},
                    success: function(response){
                        $("#district").html(response);
                    },
                }); 

                $('.rural_filter').show();
                $('.urban_filter').hide();

            }


            });


            $('#searchBanner').on('change','#state',function(e){
            e.preventDefault();  
            $.ajax({
                    type:'POST',
                    cache: false,
                    url: '<?=$this->Url->build(['action'=>'get-districts'])?>',
                    beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                    },
                    data: {state_code : $(this).val()},
                    success: function(response){
                        $("#district").html(response);
                    },
                }); 

                //$('.rural_filter').show();  
            
            });

            $('#searchBanner').on('change','#local_category',function(e){
                e.preventDefault();
                <?php if($this->request->session()->read('Auth.User.role_id') == 11)
                {?>
                    var state_code= "<?php echo $this->request->session()->read('Auth.User.state_code') ?>";
                <?php } else { ?>
                    var state_code= $('#state option:selected').val();
                <?php } ?>
                $.ajax({
                        type:'POST',
                        cache: false,
                        url: '<?=$this->Url->build(['action'=>'get-urban-local-body'])?>',
                        beforeSend: function (xhr) {
                        xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                        },
                        data: {urban_local_body_type_code : $(this).val(),state_code:$('#state option:selected').val()},
                        success: function(response){
                            $("#local_body").html(response);
                        },
                    });  
            });


            $('#searchBanner').on('change','#local_body',function(e){
                e.preventDefault();
                $.ajax({
                        type:'POST',
                        cache: false,
                        url: '<?=$this->Url->build(['action'=>'get-locality-ward'])?>',
                        beforeSend: function (xhr) {
                        xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                        },
                        data: {urban_local_body_code : $(this).val()},
                        success: function(response){
                            $("#ward").html(response);
                        },
                    });  
            });



            //on change district if rural
            $('#searchBanner').on('change','#district',function(e){
                
                $('#block').val('');
                $('#panchayat').val('');
                $('#village').val('');

                e.preventDefault();
                $.ajax({
                        type:'POST',
                        cache: false,
                        url: '<?=$this->Url->build(['action'=>'get-blocks'])?>',
                        beforeSend: function (xhr) {
                        xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                        },
                        data: {district_code : $(this).val()},
                        success: function(response){
                            $("#block").html(response);
                        },
                    });  
            });
            

            $('#searchBanner').on('change','#block',function(e){
                e.preventDefault();
                $('#panchayat').val('');
                $('#village').val('');

                $.ajax({
                        type:'POST',
                        cache: false,
                        url: '<?=$this->Url->build(['action'=>'get-gp'])?>',
                        beforeSend: function (xhr) {
                        xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                        },
                        data: {block_code : $(this).val()},
                        success: function(response){
                            $("#panchayat").html(response);
                        },
                    });  
            });

            $('#searchBanner').on('change','#panchayat',function(e){
                e.preventDefault();
                $('#village').val('');
            $.ajax({
                    type:'POST',
                    cache: false,
                    url: '<?=$this->Url->build(['action'=>'get-villages'])?>',
                    beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                    },
                    data: {gp_code : $(this).val()},
                    success: function(response){
                        $("#village").html(response);
                    },
                });  
            });

            $('#searchBanner').on('change','#sector_operation',function(e){
            e.preventDefault();
            $.ajax({
                    type:'POST',
                    cache: false,
                    url: '<?=$this->Url->build(['action'=>'get-primary-activity'])?>',
                    beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                    },
                    data: {sector_operation : $(this).val()},
                    success: function(response){
                        $("#primary_activity").html(response);
                    },
                });  
                
            });

    });

 
</script>
<?php $this->end(); ?>