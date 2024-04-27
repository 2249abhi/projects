<?php
/**
 * @author \Nilesh Kushvaha
 * @version \1.1
 * @since \October 29, 2018, 7:12 am
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Cooperative Registration[]|\Cake\Collection\CollectionInterface $CooperativeRegistrations
 */
$this->assign('title',__('Cooperative Society'));
$this->assign('content_header',__('List of Cooperative Registrations'));
$this->Breadcrumbs->add(__('Cooperative Society'));
$this->assign('top_head',__('hide'));
?>
<!-- Main content -->
<?= $this->Form->create('searchBanner',['id' =>'searchBanner','type'=>'get']); ?>
<section class="content Cooperative Registration index">
    <div class="row">
        <div class="col-xs-12">
            <?= $this->Flash->render(); ?>
            <?= $this->Flash->render('auth'); ?>
            <div class="box box-primary">
                <div class="box-header" >
                    <h3 class="box-title titleSrch"><?= __('Search - Cooperative Society All Registration (Pending + Approved) record located near to Water Bodies'); ?></h3>
					<div class="SrchBx">
						<button id="TglSrch" class="btn btn-info"></button>
					</div>
				</div>
             
                <div class="box-body table-responsive">
                    <?= $this->element('errorlist'); ?>

                

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
                                    <input type="hidden" name="sociaty" id="sociaty" maxlength="40"
                                    placeholder="Enter Registration Number" class="form-control"
                                    value="<?=$s_primary_activity?>">
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
                      
                        <!-- <div class="col-md-1"><label for="page_name">Primary Activity</label></div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <?php $primary_activities=[1=>'Primary Activity  PACS/LAMPS/FSS',9=>'Dairy',10=>'Fishery']; ?>
                            <?=$this->form->select('primary_activity',$primary_activities[$s_primary_activity],['class'=>'form-control select2','id'=>'primary_activity','empty'=>'--Select--','value'=>$s_primary_activity])?>
                            </div>
                        </div> -->
                      
                    <div class="row2">
                        
                        <div class="col-md-1"><label for="page_name">State</label></div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <?=$this->form->select('state',$sOption,['class'=>'form-control select2','id'=>'state','empty'=>'--Select--','value'=>$state])?>
                            </div>
                        </div>
                    </div>
                  
                    </div>
                     
               
                <div class="col-md-1"><label for="page_name">Status</label></div>
                    <div class="col-md-3">
                        <div class="form-group">
                        <?php $astatus = ['1'=>'Accepted','0'=>'Pending']; ?>
                        <?=$this->form->select('is_approved',$astatus,['class'=>'form-control select2','id'=>'is_approved','empty'=>'--All--','value'=>$is_approved])?>
                        </div>
                    </div>

                    <div class="clearfix"></div>
					
					<div class="row3">
						<div class="col-md-12 text-center">
							<button name="search_button" value="search_button" type="submit" class="btn btn-primary btn-green">Search</button>
							  <?= $this->Html->link(__('Reset'),['controller'=>'CooperativeRegistrations','action'=>'index'],['class'=>'btn btn-danger mx-1']); ?>
						</div>
					</div>
					
                    <div class="clearfix"></div>
                    <br>
                    <input type="hidden" name="page_length" value="<?=@$selectedLen?>">
            </div>
              
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
                     <!-- <?php if($this->request->session()->read('Auth.User.role_id') != 11)
                                        {
                                            ?>
                        <span class="addneww btn btn-warning btn-xs"><i class="glyphicon glyphicon-plus"></i></span> 
						<?=$this->Html->link(
                __(' New Cooperative Registration'),
                ['action' => 'add'],
                ['class' => 'btn btn-success btnreg', 'escape' => false, 'title' => __('Add New Cooperative Registration')]
              );?> 
          <?php } ?> -->

          <?php if(count($CooperativeRegistrations) > 0){ ?>
                    <div style="margin-right: 3px;float: right;">
                    <button name="export_excel" value="export_excel" class="mt4px btn btn-primary1 btn-green">Export to Excel</button>
                    </div>
                <?php } ?>
                    </div>
            </div>
        </div>
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
                                <th scope="col" class="actions"><?= __('Status') ?></th>
                                <!-- <th scope="col" class="actions"><?= __('Actions') ?></th> -->
                                
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
                                <!-- <td><?= h($CooperativeRegistration->cooperative_id) ?></td> -->
                                 <td><?php echo str_pad($CooperativeRegistration->sector_of_operation, 2, "0", STR_PAD_LEFT) . str_pad($CooperativeRegistration->cooperative_id_num, 10, "0", STR_PAD_LEFT); ?></td>
                                <td><?=$CooperativeRegistration->reference_year?></td>
                                <td><?=!empty($CooperativeRegistration->date_registration)?date('d/m/Y',strtotime($CooperativeRegistration->date_registration)):""?>
                                </td>
                                <td><?= h($astatus[$CooperativeRegistration->is_approved]) ?></td>
                              
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
<?= $this->Form->end(); ?>
<?php $this->append('bottom-script');?>
<script type="text/javascript">
        $(document).ready(function(){

            $('.select2').select2();
            $("#searchBanner").show();
			$("#TglSrch").click(function(){
			//$("#searchBanner").slideToggle(1500);
			});
        });

		//$("#TglSrch").on('click', function() {
       // $(this).toggleClass('is-active').next("#searchBanner").stop().slideToggle(1500);

        $('#searchBanner').on('change','#location',function(e){
                
                $('#state').val('');
              //  $('#district').val('');
             //   $('#block').val('');
             //   $('#panchayat').val('');
              //  $('#village').val('');


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
                    type:'GET',
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
                $('#district').val('');
                $('#block').val('');
                $('#panchayat').val('');
                $('#village').val('');

                $.ajax({
                    type:'GET',
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
                    type:'GET',
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
                        type:'GET',
                        cache: false,
                        url: '<?=$this->Url->build(['action'=>'get-urban-local-body'])?>',
                        beforeSend: function (xhr) {
                        xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                        },
                        data: {urban_local_body_type_code : $(this).val(),state_code:state_code},
                        success: function(response){
                            $("#local_body").html(response);
                        },
                    });  
            });


            // $('#searchBanner').on('change','#local_body',function(e){
            //     e.preventDefault();
            //     $.ajax({
            //             type:'GET',
            //             cache: false,
            //             url: '<?=$this->Url->build(['action'=>'get-locality-ward'])?>',
            //             beforeSend: function (xhr) {
            //             xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            //             },
            //             data: {urban_local_body_code : $(this).val()},
            //             success: function(response){
            //                 $("#ward").html(response);
            //             },
            //         });  
            // });



            //on change district if rural
            // $('#searchBanner').on('change','#district',function(e){
                
            //     $('#block').val('');
            //     $('#panchayat').val('');
            //     $('#village').val('');

            //     e.preventDefault();
            //     $.ajax({
            //             type:'GET',
            //             cache: false,
            //             url: '<?=$this->Url->build(['action'=>'get-blocks'])?>',
            //             beforeSend: function (xhr) {
            //             xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            //             },
            //             data: {district_code : $(this).val()},
            //             success: function(response){
            //                 $("#block").html(response);
            //             },
            //         });  
            // });
            

            // $('#searchBanner').on('change','#block',function(e){
            //     e.preventDefault();
            //     $('#panchayat').val('');
            //     $('#village').val('');

            //     $.ajax({
            //             type:'GET',
            //             cache: false,
            //             url: '<?=$this->Url->build(['action'=>'get-gp'])?>',
            //             beforeSend: function (xhr) {
            //             xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            //             },
            //             data: {block_code : $(this).val()},
            //             success: function(response){
            //                 $("#panchayat").html(response);
            //             },
            //         });  
            // });

            // $('#searchBanner').on('change','#panchayat',function(e){
            //     e.preventDefault();
            //     $('#village').val('');
            // $.ajax({
            //         type:'GET',
            //         cache: false,
            //         url: '<?=$this->Url->build(['action'=>'get-villages'])?>',
            //         beforeSend: function (xhr) {
            //         xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            //         },
            //         data: {gp_code : $(this).val()},
            //         success: function(response){
            //             $("#village").html(response);
            //         },
            //     });  
            // });

            $('#searchBanner').on('change','#sector_operation',function(e){
            e.preventDefault();
            $.ajax({
                    type:'GET',
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

 
</script>
<style>
 
.btn-primary1 {
    background-color: #1ac34e;
    border-color: #367fa9;
    color:white;
}
</style>
<?php $this->end(); ?>