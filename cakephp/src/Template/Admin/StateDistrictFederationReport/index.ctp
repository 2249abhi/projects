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
<!-- <style>
    .container {
  margin: 0 auto !important;
  width: 980px !important;
}

.table-container {
  margin-bottom: 10px !important;
}

html {
  box-sizing: border-box !important;
  font-family: sans-serif !important;
}

body {
  padding-bottom: 15px !important;
}

.StickyTableHeader {
  background: #fff !important;
}

.StickyTableHeader.is-scrolling {
  box-shadow: 0 3px 4px -2px #777 !important;
}

*,
*::before,
*::after {
  box-sizing: inherit !important;
}

table {
  width: 100% !important;
  /* border: 1px solid #bbb !important; */
  border-collapse: collapse !important;
}

td, th {
  padding: 10px !important;
  border: 1px solid #bbb !important;
  border-collapse: collapse !important;
}
th a{
    color:white !important;
}
thead{
    color: #fff;
    background: #3c8dbc;
}

button.dt-button:first-child, div.dt-button:first-child, a.dt-button:first-child, input.dt-button:first-child{
    margin-left: 0;
    background-color: #3c7046 !important;
    border-color: #3c7046 !important;
}



</style> -->
<!-- Main content -->
<section class="content Cooperative Registration index">
    <div class="row">
        <div class="col-xs-12">
            <?= $this->Flash->render(); ?>
            <?= $this->Flash->render('auth'); ?>
            <div class="box box-primary">
              <div class="box-header" >
                <h3 class="box-title titleSrch"><?= __('State District Federation - Sector Wise Report'); ?></h3>
                <div class="SrchBx">
                  <button id="TglSrch" class="btn btn-info is-active"></button>
                </div>
              </div>
               <?= $this->Form->create('searchBanner',['id' =>'searchBanner','type'=>'get']); ?>
                <div class="box-body table-responsive">
                    <?= $this->element('errorlist'); ?>                    
                    
                    <div class="row2">        
                        <div class="col-md-1"><label for="page_name">State Name</label></div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <?=$this->form->select('state',$sOption,['class'=>'form-control select2','id'=>'state','empty'=>'--All--','value'=>$state])?>
                            </div>
                        </div> 
                        <div class="col-md-1"><label for="page_name">District Name</label></div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <?=$this->form->select('district_code',$dist_opt,['class'=>'form-control select2','id'=>'district_code','empty'=>'--All--','value'=>$d_district_code])?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="clearfix"></div>
					
                    <div class="row3">
                      <div class="col-md-12 text-center">
                        <button name="search_button" value="search_button" type="submit" class="btn btn-primary btn-green">Search</button>
                          <?= $this->Html->link(__('Reset'),['controller'=>'StateDistrictFederationReport','action'=>'index'],['class'=>'btn btn-danger mx-1']); ?>
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


    <?php if(isset($state) && !empty($state)){ ?>
    <div class="row">
          <div class="col-xs-12">
            <div class="box box-primary box-st">
             <h3 style="line-height: 3.1;"><?= $sOption[$state] ?? '' ?> <?php if($d_district_code !='' ) { echo " - ". $dist_opt[$d_district_code];}?></h3> 
            </div>
          </div>
    </div>
    <?php } ?>

    <div class="row">

      <div class="col-md-12">
        <!-- <div class="export_excel-div">
                    <button name="export_excel" value="export_excel" class="mt4px btn btn-green">Export to Excel</button>
                </div> -->
        <div class="box box-primary">
            <div class="box-body table-responsive">
            <div class="sticky-table-demo" id="section1">  
              <table id="" class="table  table-hover table-bordered table-striped display" style="width:100%" >
                <thead>
                  <tr>
                    <th scope="col" width="3%"><?= $this->Paginator->sort('id','Sr.No.') ?></th>
                    <th><?= $this->Paginator->sort('Primary Activity','Primary Activity') ?></th>
                    <th><?= $this->Paginator->sort('federation_type','State Federation') ?></th>
                    <th><?= $this->Paginator->sort('federation_type','District Federation') ?></th>
                    <th><?= $this->Paginator->sort('federation_type','Block/Taluka/Mandal Federation') ?></th>
                    <th><?= $this->Paginator->sort('federation_type','Regional Federation') ?></th>
                    <th><?= $this->Paginator->sort('federation_type','Total') ?></th>
                  </tr>
                </thead>
                <tbody>
                    <?php 
                      $i=1;
                      $paginatorInformation = $this->Paginator->params();
                      $pageOffset = ($paginatorInformation['page'] - 1);
                      $perPage = $paginatorInformation['perPage'];
                      $counter = (($pageOffset * $perPage));
                      $namevalue=array();
                      foreach ($all_sectors as $key=>$primary_act_id): 
                        if($primary_act_id['id']==1)
                          {
                              $pid = $primary_act_id['id']==1;
                              $var1 = 'Primary Agricultural Credit Society (PACS)';
                              $pactivity[$pid] =& $var1;
                              $var1 = 'Primary Agricultural Credit Society (PACS/FSS/LAMPS)';
                                                            
                          }
                        // print_r($primary_act_id);
                    ?>
                   
                      <tr>
                        <td><?php  echo $this->Number->format($i + $counter) ?></td>
                        
                        <td value="<?= h($primary_act_id['id']) ?>"><?= h($pactivity[$primary_act_id['id']]) ?></td>

                        <td><?php echo $state_federation[$primary_act_id['id']] ?? 0;
                        $total_state_federation += $state_federation[$primary_act_id['id']] ?? 0;

                        ?></td>

                        <td><?php echo $district_federation[$primary_act_id['id']] ?? 0;
                        
                        $total_district_federation += $district_federation[$primary_act_id['id']] ?? 0;

                        ?></td>

                        <td><?php echo $block_federation[$primary_act_id['id']] ?? 0;
                        
                        $total_block_federation += $block_federation[$primary_act_id['id']] ?? 0;

                        ?></td>
                        <td><?php echo $regional_federation[$primary_act_id['id']] ?? 0;
                        
                        $total_regional_federatione += $regional_federation[$primary_act_id['id']] ?? 0;

                        ?></td>

                        <td><b><?php echo $state_federation[$primary_act_id['id']] + $district_federation[$primary_act_id['id']] + $block_federation[$primary_act_id['id']] + $regional_federation[$primary_act_id['id']];
                        ?></b></td>

                      </tr>
                      <?php $i++;endforeach; ?>
                      <tr>
                      <td></td>
                    <td> <strong>Total</strong></td>                       
                    <td><b><?php echo $total_state_federation; ?></b></td>
                    <td><b><?php echo $total_district_federation; ?></b></td>
                    <td><b><?php echo $total_block_federation; ?></b></td>
                    <td><b><?php echo $total_regional_federatione; ?></b></td>
                    <td><b><?php echo $total_state_federation + $total_district_federation + $total_block_federation + $total_regional_federatione; ?></b></td>
                         </tr>
                </tbody>
              </table>
            </div>
            </div>
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
      </div>
      <!-- <div class="col-md-8">
     
      <div id="sector_bar" class="w100" style="margin-top:2em;"></div>
    


      </div> -->
    </div>
</section>


<?php $this->append('bottom-script');?>
<script type="text/javascript">
        $(document).ready(function(){

            $('#searchBanner').show();
            $('.select2').select2();

			$("#TglSrch").click(function(){
			$("#searchBanner").slideToggle(1500);
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

        });
</script>      

<!--
<script src="<?=$this->request->webroot?>js/chart/highcharts.js"></script>
<script src="<?=$this->request->webroot?>js/chart/exporting.js"></script>
<script src="<?=$this->request->webroot?>js/chart/export-data.js"></script>
<script src="<?=$this->request->webroot?>js/chart/accessibility.js"></script>

<script src="<?=$this->request->webroot?>js/chart/highmaps.js"></script>
<script src="<?=$this->request->webroot?>js/chart/exporting.js"></script>
<script src="<?=$this->request->webroot?>js/chart/map_data.js"></script>
  <script type="text/javascript">

	
$(document).ready(function () {
Highcharts.chart('sector_bar', {
                chart: {
                    type: 'column',
                    height: 820,
                    marginBottom: 300,
                    // options3d: {
                    //     enabled: true,
                    //     alpha: 0,
                    //     beta: 332,
                    //     depth: 59,
                    //     viewDistance: 25
                    // },
                    
                
                scrollablePlotArea: {
                    minWidth: 1000,
                    scrollPositionX: 0
                }
                
                        
                },
                title: {
                    text: 'SECTOR-WISE COOPERATIVE SOCIETY'
                },
                subtitle: {
                    text: ''
                },
                xAxis: {
                    type: 'category',
                    labels: {
                        rotation: -66,
                        style: {
                            fontSize: '13px',
                            fontFamily: 'Verdana, sans-serif'
                        }
                    }
                },
                yAxis: {
                    // min: 5,

                    title: {
                        text: 'Total Society Count'
                    },
                    type:'logarithmic',
                    scrollbar: {
                        enabled: true
                    },
                    minWidth: 20,
                },
                legend: {
                    enabled: false
                },
                tooltip: {
                        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                            '<td style="padding:0"><b>{point.y} </b></td></tr>',
                        footerFormat: '</table>',
                        shared: true,
                        useHTML: true
                    },
                    plotOptions: {
                        column: {
                            pointPadding: 0.2,
                            borderWidth: 0

                        },
                        series: {
                            colorByPoint: true,
                            pointWidth: 20
                            
                        },
                        column: {
                            minPointLength: 5
                        }
                    },
                    colors: [
                        '#7cb5ec',
                        '#90ed7d',
                        '#e08d46'
                    ],
                series: [{
                    name: '<span style="color:black;">Count</span>',
                    data: [

                        <?php
                            foreach($maparray as $key => $value)
                            {
                                echo "['".ucfirst(str_replace('_',' ',$key))."',".($value ?? 0)."],";
                            }
                        ?>
                    ],
                    dataLabels: {
                        enabled: true,
                        rotation: -90,
                        align: 'right',
                        style: {
                            fontSize: '13px',
                            fontFamily: 'Verdana, sans-serif'
                        }
                    }
                }]
            });
        });
    </script> -->
          
<?php $this->end(); ?>