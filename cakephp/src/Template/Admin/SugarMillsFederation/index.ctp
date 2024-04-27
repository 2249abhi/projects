<?php
/**
 * @author \Nilesh Kushvaha
 * @version \1.1
 * @since \October 29, 2018, 7:12 am
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\National Federation[]|\Cake\Collection\CollectionInterface $NationalFederations
 */
$this->assign('title',__('National Federations Data'));
$this->assign('content_header',__('List of National Federations Data'));
$this->Breadcrumbs->add(__('National Federations Data'));
$this->assign('top_head',__('hide'));
?>
<!-- Main content -->
<?= $this->Form->create('searchBanner',['id' =>'searchBanner','type'=>'get']); ?>
<section class="content National Federation index">
    <div class="row">
        <div class="col-xs-12">
            <?= $this->Flash->render(); ?>
            <?= $this->Flash->render('auth'); ?>
            <div class="box box-primary">
                <div class="box-header" >
                    <h3 class="box-title titleSrch"><?= __('Sugar Mills Federation List'); ?></h3>
					
				</div>
              
                <div class="box-body table-responsive">
                    <?=$this->element('errorlist'); ?>
                        <div class="row">                              
                        <div class="col-md-4">
                            <label>Sugar Mill Name</label>
                            <div class="form-group">
                                <input type="text" name="sugar_mill_name" id="sugar_mill_name" placeholder="Enter Sugar Mills Name" class="form-control" value="<?php echo $sugar_mill_name; ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label>Location</label>
                            <div class="form-group">
                                <input type="text" name="short_name" id="short_name"
                                    placeholder="Enter Name location" class="form-control" value=<?=$short_name?>>
                            </div>
                        </div>
                                               
                       
                        <div class="col-md-4">
                        <label>State Name</label>
                            <div class="form-group">
                                <?=$this->form->select('state',$sOption,['class'=>'form-control select2','id'=>'state','empty'=>'--All--','value'=>$s_state])?>
                            </div>
                        </div>
                                
                    </div>
                    <div class="col-md-4">
                            <label>Operational Status</label>
                            <?php $operational_status_array=['Running'=>'Running','Closed'=>'Closed','Liquidation'=>'Liquidation','Windup'=>'Windup']; ?>
                            <div class="form-group">
                            <?=$this->form->select('operational_status',$operational_status_array,['class'=>'form-control select2','id'=>'operational_status','empty'=>'--All--','value'=>$operational_status])?>
                            </div>
                    </div>
                    <div class="col-md-4">
                            <label>Ownership</label>
                            <?php $ownership_arry=['Cooperative'=>'Cooperative','Private'=>'Private','Liquidation'=>'Liquidation','Leased'=>'Leased','Windup'=>'Windup'] ?>
                            <div class="form-group">
                            <?=$this->form->select('ownership',$ownership_arry,['class'=>'form-control select2','id'=>'ownership','empty'=>'--All--','value'=>$ownership])?>
                            </div>
                    </div>
                  

                    <input type="hidden" name="page_length" value="<?=@$selectedLen?>">
                </div>
                <div class="box-footer">
                    <div class="row3">
                        <div class="col-md-12 text-center">
                            <button name="search_button" value="search_button" type="submit" class="btn btn-primary btn-green">Search</button>
                              <?= $this->Html->link(__('Reset'),['controller'=>'SugarMillsFederation','action'=>'index'],['class'=>'btn btn-danger mx-1']); ?>
                        </div>
                    </div>
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
            <?php if(count($district_codes) > 0){ ?>
                    <div style="margin-right: 20px;float: right;">
                    <button name="export_excel" value="export_excel" class="mt4px btn btn-primary1 btn-green">Export to Excel</button>
                    </div>
                <?php } ?>
                     
                    </div>
            </div>
        </div>
                    <table class="table table-hover table-bordered table-striped">
                        <thead>
                            <tr>
                                <th scope="col"  width="2%"><?= $this->Paginator->sort('id','Sr.No.') ?></th>
                                <th scope="col" width="20%">
                                    <?= $this->Paginator->sort('sugar_mill_name','Sugar Mill Name') ?>       </th>
                             
                                <th scope="col" width="8%"><?= $this->Paginator->sort('state_name','State Name') ?></th>
                                <th scope="col" width="8%"><?= $this->Paginator->sort('district_code','District Name') ?></th>
                                <th scope="col" width="8%"><?= $this->Paginator->sort('phone_number_of_contact_person','Contact Number') ?></th>
                                <th scope="col" width="8%"><?= $this->Paginator->sort('email','Email') ?></th>                                
                                <th scope="col" width="8%"><?= $this->Paginator->sort('short_name','Location') ?></th>                                                            
                                <th scope="col" width="12%"><?= $this->Paginator->sort('operational_status','Operational Status') ?></th>
                                <th scope="col" width="8%"><?= $this->Paginator->sort('ownership_status','Ownership') ?></th>
                                <th scope="col" width="8%"><?= $this->Paginator->sort('actual_capacity','Actual Capacity') ?></th>
                                <th scope="col" width="8%"><?= $this->Paginator->sort('ethanol_capacity','Ethanol Capacity(KLPD)') ?></th>
                                <th scope="col" width="8%"><?= $this->Paginator->sort('cogen_capacity','Cogen Capacity(MW)') ?></th>
                                <th scope="col" width="8%"><?= $this->Paginator->sort('profit_loss','Profit/Loss(IN LAKHS)') ?></th>
                                
                                
                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                $i=1;
                  $paginatorInformation = $this->Paginator->params();
                  $pageOffset = ($paginatorInformation['page'] - 1);
                  $perPage = $paginatorInformation['perPage'];
                  $counter = (($pageOffset * $perPage));
                  foreach ($district_codes as $NationalFederation): 
                ?>
                            <tr>
                                <td> 
                               <?php echo $this->Number->format($i + $counter) ?>

                                </td>
                                <td><?= h($NationalFederation->sugar_mill_name) ?></td>
                                <td><?= h($sOption[$NationalFederation->state_code]) ?></td>
                                <td><?= $Districts[$NationalFederation->district_code]?></td>
                                <td><?= h($NationalFederation->phone_number_of_contact_person) ?></td>
                                <td><?= h($NationalFederation->email) ?></td>
                                
                                <td><?= h($NationalFederation->short_name) ?></td>
                               
                                <td><?= h($NationalFederation->operational_status) ?></td>
                                <td><?= h($NationalFederation->ownership_status) ?></td>                                
                                <td align="right"><?= h($NationalFederation->actual_capacity) ?></td>
                                <td align="right"><?= h($NationalFederation->ethanol_capacity) ?></td>
                                <td align="right"><?= h($NationalFederation->cogen_capacity) ?></td>
                                <td align="right"><?= h($NationalFederation->profit_loss) ?></td>                          
                                
                               
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

            $(".all_date").datepicker({
                dateFormat: 'dd/mm/yy',
                changeMonth: true,
                changeYear: true,
                //maxDate: new Date(),
            });

          $("#searchBanner").show();
            $("#TglSrch").click(function(){
                $("#searchBanner").slideToggle(1500);
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

        });

       
</script>
<style>
    .btn-primary1 {
    background-color: #1ac34e;
    border-color: #367fa9;
    color: white;
}
    </style>
<?php $this->end(); ?>