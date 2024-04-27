<?php
/**
 * @author \Nilesh Kushvaha
 * @version \1.1
 * @since \October 29, 2018, 7:12 am
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Cooperative Registration[]|\Cake\Collection\CollectionInterface $CooperativeRegistrations
 */
$this->assign('title',__('Report'));
$this->assign('content_header',__('Report'));
$this->Breadcrumbs->add(__('Report'));
$replacements=['1'=>'Primary Agricultural Credit Society (PACS/FSS/LAMP)'];
unset($sectors[20]);
unset($sectors[22]);
$sectors = array_replace($sectors, $replacements);
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
                    <h3 class="box-title titleSrch"><?= __('Search - State Wise Distribution number of PACS/LAMPS/FSS, Dairy And Fishery located near to Water'); ?></h3>
					<div class="SrchBx">
						<button id="TglSrch" class="btn btn-info is-active"></button>
					</div>
				</div>
           
                <div class="box-body table-responsive">
                    <?= $this->element('errorlist'); ?>                    
                    
                    <div class="row2">
                        
                    <div class="col-md-1"><label for="page_name">sector</label></div>
                        <div class="col-md-3">
                            <div class="form-group">                        
                                <?=$this->form->select('sector',$sectors,['class'=>'form-control select2','id'=>'state','empty'=>'--Select Sector--','value'=>$s_primary_activity])?>
                            </div>
                        </div> 
                                         
                        <div class="col-md-1"><label for="page_name">State</label></div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <?=$this->form->select('state',$sOption,['class'=>'form-control select2','id'=>'state','empty'=>'--All--','value'=>$s_state])?>
                            </div>
                        </div> 
                                                 
                    </div>
                    
                    <div class="clearfix"></div>
					
					<div class="row3">
						<div class="col-md-12 text-center">
							<button name="search_button" value="search_button" type="submit" class="btn btn-primary btn-green">Search</button>
							  <?= $this->Html->link(__('Reset'),['controller'=>'all-india-reports','action'=>'index'],['class'=>'btn btn-danger mx-1']); ?>
						</div>
					</div>
					
                    <div class="clearfix"></div>
                    <br>
                    <input type="hidden" name="page_length" value="<?=@$selectedLen?>">
                </div>
              
            </div>
        </div>
    </div>
    <?php if(isset($s_state) && !empty($s_state)){ ?>
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary box-st">
            <h3 style="line-height: 3.1;"><?= $sOption[$s_state] ?? '' ?> <?php if($d_district_code !='' ) { echo " - ". $dist_opt[$d_district_code];}?></h3> 
                                
            </div>
     
        </div>
    </div>
    <?php } ?>

        <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary box-st">         
                   
                   
                <div class="box-body table-responsive">
                <button name="export_excel" value="export_excel" class="mt4px btn btn-primary1 btn-green" style="float:right;">Export to Excel</button>
                    <table class="table table-hover table-bordered table-striped">
                        <thead>
                            <tr>
                                <th scope="col" width="3%"><?= $this->Paginator->sort('id','Sr.No.') ?></th>
                              
                                <th scope="col">
                                    <?= $this->Paginator->sort('State','State') ?>
                                </th>                             
                             
                                <th scope="col" width="25%" >
                                <?php $socity_name= $sectors[$s_primary_activity] ?? "of PACS/LAMPS/FSS";?>
                                <?= $this->Paginator->sort('No. of PACS/LAMPS/FSS',"No. of ".$socity_name ."");?></th>
                               </th>
                               </th>
                               <!-- <th scope="col" width="25%">
                                <?= $this->Paginator->sort('No. of Dairy Coop','No. of Dairy Coop')?></th>
                               </th>
                               <th scope="col" width="25%" >
                                <?= $this->Paginator->sort('No. of Fishery Coop','No. of Fishery Coop')?></th>
                               </th>
                               <th scope="col" width="25%">
                                <?= $this->Paginator->sort('Total No. of Coop(PACS/Dairy/Fishery)','No. of Coop(PACS/Dairy/Fishery)')?></th>
                               </th>                            -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                   

                $i=1;
                  $paginatorInformation = $this->Paginator->params();
                  $pageOffset = ($paginatorInformation['page'] - 1);
                  $perPage = $paginatorInformation['perPage'];
                  $counter = (($pageOffset * $perPage));
                     foreach ($state_code as $key=>$district_code): 
                ?>
                            <tr>
                                <td> <?php  
                        echo $this->Number->format($i + $counter) 
                      ?>

                                </td>
                                <td width="33%"><?= h($sOption[$district_code['state_code']]) ?></td> 
                                <td width="23%">
                                    <?php if($pacs[$district_code['state_code']] !=0)
                                    {
                                        $pacs[$district_code['state_code']];
                                      //  $url=
                                      echo  $this->Html->link($pacs[$district_code['state_code']], ['action' => 'society?sociaty='.$s_primary_activity.'&state='.$district_code['state_code']],array('target'=>'_blank')); }
                                       else {echo "0";
                                     } ?>
                              </td> 
                                <!-- <td width="20%">  
                                <?php if($dairies[$district_code['state_code']] !=0)
                                    {
                                        $dairies[$district_code['state_code']];
                                      //  $url=
                                      echo  $this->Html->link($dairies[$district_code['state_code']], ['action' => 'society?sociaty=9&state='.$district_code['state_code']],array('target'=>'_blank')); }
                                       else {echo "0";
                                     } ?>
                                
                                <?php //= h($dairies[$district_code['state_code']] ?? 0) ?></th> -->
                                <!-- <td width="23%">  <?php if($fisheries[$district_code['state_code']] !=0)
                                    {
                                        $fisheries[$district_code['state_code']];
                                      //  $url=
                                      echo  $this->Html->link($fisheries[$district_code['state_code']], ['action' => 'society?sociaty=10&state='.$district_code['state_code']],array('target'=>'_blank')); }
                                       else {echo "0";
                                     } ?>
                                    
                                <?php //= h($fisheries[$district_code['state_code']] ?? 0) ?></td>  -->
                                <!-- <td width="233%">  <?= h($fisheries[$district_code['state_code']]
                                + $dairies[$district_code['state_code']] + $pacs[$district_code['state_code']]) ?></td>                             -->
                            </tr>
                            <?php $i++;endforeach; ?>
                            <?php if(count($district_codes) == 0):?>
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

            $('#searchBanner').show();
            $('.select2').select2();

			$("#TglSrch").click(function(){
			$("#searchBanner").slideToggle(1500);
			});
        });

        $('#location').on('change',  function(e) {
            $(".dst").show();
        });
        


         $('#state').on('change',  function(e) {
            e.preventDefault();

          
               if($(this).val() !='' )
               {

                $.ajax({
                    type: 'GET',
                    cache: false,
                    url: '<?=$this->Url->build(['action'=>'get-districts'])?>',
                    beforeSend: function(xhr) {
                       // xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                    },
                    data: {
                        state_code: $(this).val()
                    },
                    success: function(response) {
                      
                        $("#district_code").html(response);
                       // $(".sector_district_code").html(response);
                    },
                });
            }
           
            });



		$("#TglSrch").on('click', function() {
      /*  $(this).toggleClass('is-active').next("#searchBanner").stop().slideToggle(1500);*/

     /*   $('#searchBanner').on('change','#location',function(e){
                
                $('#state').val('');
                $('#district').val('');
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

            }else if(location_of_head_quarter==2){
                
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

            $('#searchBanner').on('change','#local_category',function(e){
                e.preventDefault();
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
                
            });*/




 

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