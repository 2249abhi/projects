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
?>
<!-- Main content -->
<section class="content Cooperative Registration index">
    <div class="row">
        <div class="col-xs-12">
            <?= $this->Flash->render(); ?>
            <?= $this->Flash->render('auth'); ?>
            <div class="box box-primary">
                <div class="box-header" >
                    <h3 class="box-title titleSrch"><?= __('Search - Report'); ?></h3>
					<div class="SrchBx">
						<button id="TglSrch" class="btn btn-info is-active"></button>
					</div>
				</div>
                <?= $this->Form->create('searchBanner',['id' =>'searchBanner','type'=>'get']); ?>
                <div class="box-body table-responsive">
                    <?= $this->element('errorlist'); ?>                    
                    
                    <div class="row2">        
                        <div class="col-md-1"><label for="page_name">Location</label></div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <?php $arr_location = ['2'=>'Rural']; ?>
                                <?=$this->form->select('location',$arr_location,['class'=>'form-control','id'=>'location','empty'=>'--ALL--','value'=>$s_location])?>
                            </div>
                        </div>                   
                        <!-- <div class="col-md-1"><label for="page_name">State</label></div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <?=$this->form->select('state',$sOption,['class'=>'form-control select2','id'=>'state','empty'=>'--All--','value'=>$s_state])?>
                            </div>
                        </div> -->

                         <div class="col-md-1"><label for="distict">District</label></div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <?=$this->form->select('district',$dist_opt,['class'=>'form-control select2','id'=>'district','empty'=>'--All--','value'=>$district])?>
                            </div>
                        </div>


                    </div>
                    
                    <div class="clearfix"></div>
					
					<div class="row3">
						<div class="col-md-12 text-center">
							<button name="search_button" value="search_button" type="submit" class="btn btn-primary btn-green">Search</button>
							  <?= $this->Html->link(__('Reset'),['controller'=>'Reports','action'=>'index'],['class'=>'btn btn-danger mx-1']); ?>
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
    <?php if(isset($sOption) && !empty($sOption)){ ?>
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary box-st">
            <h3 style="line-height: 3.1;"><?= $sOption[$this->request->session()->read('Auth.User.state_code')] ?? '' ?> 
            <?php if($district !='' ){ echo "-".$arr_districts[$district]; } ?>
            </h3> 
            </div>
        </div>
    </div>
    <?php } ?>
    <?php  if($s_location == 2 || $s_location == ''){ ?>
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary box-st">
                <p><?php 
                    foreach($first as $key => $val)
                    {
                        echo $val.'</br>';
                    }
                ?></p>
                <!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table class="table table-hover table-bordered table-striped">
                        <thead>
                            <tr>
                                <th scope="col"><?= $this->Paginator->sort('id','Sr.No.') ?></th>
                                <th scope="col">
                                    <?= $this->Paginator->sort('district','District') ?>
                                </th>
                                <th scope="col"><?= $this->Paginator->sort('panchayat','Panchayat') ?> 
                                    <br>
                                    <?php 
                                    echo "Total  = ".array_sum($panchayats)."<br>";
                                    ?>
                                </th>
                                <th scope="col"><?= $this->Paginator->sort('villages','Villages') ?>
                                     <br>
                                    <?php 
                                    echo "Total  = ".array_sum($villages)."<br>";
                                    ?>                                 

                                </th>
                                <th scope="col" style="text-align: center;  width: 20%;">
                                    <table class="table table-hover table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th colspan="3"  style="text-align: center;">
                                                <?= $this->Paginator->sort('PACS','PACS')?></th>
                                            </tr>
                                            <tr>
                                                <th width="33%">
                                    <?php 
                                       
                                        $total_pac = array_sum($district_nodal_tatal['pacs'][$state_code]);

                                        if($total_pac == '')
                                        {
                                            $total_pac='0';
                                        }
                                          echo "Total Record   = ". $total_pac."<br>";
                                        ?></th>
                                        <th width="33%"> Data Entered Today <br> 
                                        <?php 
                                        echo array_sum($pacs_today);
                                        ?>
                                </th> 
                                <th>Total Data Entered   <br>
                                    <?php 
                                    echo "Total  = ".array_sum($pacs)."<br>";
                                    ?>
                                </th>
                              </tr>
                                </thead></table> 
                               </th>
                                <th scope="col" style="text-align: center;  width: 20%;">
                                 <table class="table table-hover table-bordered table-striped"><thead><tr><th colspan="3"  style="text-align: center;"><?= $this->Paginator->sort('dairy','Dairy') ?></th></tr><tr><th width="25%">
                                    <?php 
                                       
                                        $total_dairy = array_sum($district_nodal_tatal['dairy'][$state_code]);
                                        if($total_dairy == '')
                                        {
                                            $total_dairy='0';
                                        }

                                    echo "Total Record   = ".$total_dairy."<br>";
                                    ?></th>
                                    <th width="33%"> Data Entered Today <br>  <?php 
                                    echo array_sum($dairies_today);
                                    ?> </th>
                                    
                                    <th>Total Data Entered 
                                    <?php 
                                    echo "Total  = ".array_sum($dairies)."<br>";
                                    ?>
                               </th>
                            </tr>
                                </thead></table>   </th>                               
                                <th scope="col" style="text-align: center; width: 20%;">   <table class="table table-hover table-bordered table-striped"><thead><tr><th colspan="3"  style="text-align: center;"><?= $this->Paginator->sort('fisheries','Fisheries') ?></th></tr><tr><th width="33%"> <?php 
                                    
                                        $total_fisfhery = array_sum($district_nodal_tatal['fisfhery'][$state_code]);

                                         if($total_fisfhery == '')
                                        {
                                            $total_fisfhery='0';
                                        }
                                          
                                    echo "Total Record   = ".$total_fisfhery."<br>";
                                    ?></th>
                                    <th width="33%"> Data Entered Today   <?php 
                                    echo array_sum($fisheries_today);
                                    ?> </th>
                                    
                                    
                                    <th>Total Data Entered <br>  <?php 
                                    echo "Total  = ".array_sum($fisheries)."<br>";
                                    ?></th></tr>
                                </thead></table></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                $i=1;
                  $paginatorInformation = $this->Paginator->params();
                  $pageOffset = ($paginatorInformation['page'] - 1);
                  $perPage = $paginatorInformation['perPage'];
                  $counter = (($pageOffset * $perPage));
                     foreach ($district_codes as $key=>$district_code): 
                ?>
                            <tr>
                                <td> <?php  
                        echo $this->Number->format($i + $counter) 
                      ?>

                                </td>
                                <td><?= h($arr_districts[$district_code['district_code']]) ?></td>
                                <td><?= h($panchayats[$district_code['district_code']] ?? 0) ?></td>
                                <td><?= h($villages[$district_code['district_code']] ?? 0) ?></td>
                                  <td style="text-align: center;">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td><?= h($district_nodal_tatal['pacs'][$state_code][$district_code['district_code']] ?? 0) ?></td>
                                                <td width="33%"><?= h($pacs_today[$district_code['district_code']] ?? 0) ?></td>

                                                <td width="33%">  <?= h($pacs[$district_code['district_code']] ?? 0) ?></td>
                                             </tr>
                                        </tbody>
                                    </table> 
                                    </td>
                                <td style="text-align: center;">  

                                         <table class="table">
                                            <tbody>
                                                <tr>
                                                    <td><?= h($district_nodal_tatal['dairy'][$state_code][$district_code['district_code']] ?? 0) ?></td>
                                                    <td width="30%">  <?= h($dairies_today[$district_code['district_code']] ?? 0) ?></th>
                                                    <td width="33%">  <?= h($dairies[$district_code['district_code']] ?? 0) ?></td> 
                                                </tr>
                                    </tbody></table>

                                  </td>
                              
                                <td style="text-align: center;width: 223px;">

                                       <table class="table">
                                        <tbody>
                                            <tr>
                                                <td><?= h($district_nodal_tatal['fisfhery'][$state_code][$district_code['district_code']] ?? 0) ?></td>
                                                <td width="33%">  <?= h($fisheries_today[$district_code['district_code']] ?? 0) ?></td>
                                                <td width="30%">  <?= h($fisheries[$district_code['district_code']] ?? 0) ?></td>
                                             </tr>
                                        </tbody>
                                    </table>

                                  </td>
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
	<?php } 
    if($s_location == 1){        
        ?>
        <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary box-st">                
                <!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table class="table table-hover table-bordered table-striped">
                        <thead>
                            <tr>
                                <th scope="col"><?= $this->Paginator->sort('id','Sr.No.') ?></th>
                                <th scope="col">
                                    <?= $this->Paginator->sort('category','Category') ?>
                                </th>
                                <th scope="col"><?= $this->Paginator->sort('localbody','Local Body') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('ward','Ward') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('dairy','Dairy') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('pacs','Pacs') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('fisheries','Fisheries') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                                   
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
    <?php } ?>

</section>
<?php $this->append('bottom-script');?>
<script type="text/javascript">
        $(document).ready(function(){

            $('#searchBanner').show();
            $('.select2').select2();

			$("#TglSrch").click(function(){
			$("#searchBanner").slideToggle(1500);
			});
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
                      
                        $("#district").html(response);
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
<?php $this->end(); ?>