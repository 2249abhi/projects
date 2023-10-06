<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<?php 
use Cake\Collection\Collection;
use Cake\Core\Configure; ?>
<!-- Main content -->
<!--<section class="content">
      <div class="clearfix"></div>
      
      <ul>
          <?php //echo $this->Menu->render('main-menu'); ?>
      </ul>
      <p class="websitetitle"> <span>Welcome to</span> <small> Ministry of Cooperation<?php //echo Configure::read('Theme.title'); ?></small> </p>
    </section>-->

<section class="content Cooperative Registration index box-container-block">
  

    <?php 

    $arr_graph_allowed = [1,2,9,10,11,12,14];
    $arr_admin_user = [1,2,10,12,14];
    $arr_state_user = [11];
    $arr_district_user = [7,8];

	//if(in_array($role_id,$arr_graph_allowed)){
	?>
    <div class="row" style="display:none;">
        <div class="col-xs-12">
        <?= $this->Flash->render(); ?>
            <?= $this->Flash->render('auth'); 
                //if(!in_array($role_id,$arr_district_user)){
            ?>
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title titleSrch"><?= __('Search'); ?></h3>
                    <div class="SrchBx">
                        <button id="TglSrch" class="btn btn-info is-active"></button>
                    </div>
                </div>
                <?= $this->Form->create('searchBanner',['id' =>'searchBanner','type'=>'post']); ?>
                <div class="box-body table-responsive">
                    <?= $this->element('errorlist'); ?>

                    <div class="row2">
                        <div style="display:block:">
                            <div class="col-md-1"><label for="page_name">State</label></div>
                            <div class="col-md-3">
                                <div class="form-group">
                                <?=$this->form->select('state',$states,['class'=>'form-control select2','id'=>'state','empty'=>'--Select--','value'=>$s_state])?>
                                </div>
                            </div>
                        </div>

                        <span class="dst">
                            <div style="display:block;">
                                <div class="col-md-1"><label for="page_name">District </label></div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                    <?=$this->form->select('district',$districts,['class'=>'form-control select2','id'=>'district','empty'=>'--Select--','value'=>$s_district])?>
                                    </div>
                                </div>
                            </div>
                        </span>
                        <span class="dst">
                            <div style="display:block;">
                                <div class="col-md-1"><label for="page_name">Block </label></div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                    <?=$this->form->select('block',$blocks,['class'=>'form-control select2','id'=>'block','empty'=>'--Select--','value'=>$s_block])?>
                                    </div>
                                </div>
                            </div>
                        </span>
                        <span class="dst">
                            <div style="display:block;">
                                <div class="col-md-1"><label for="page_name">Sector </label></div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                    <?=$this->form->select('sector',$sectors,['class'=>'form-control select2','id'=>'sector','empty'=>'--Select--','value'=>$s_sector])?>
                                    </div>
                                </div>
                            </div>
                        </span>
                    </div>

                    <div class="clearfix"></div>

                    <div class="row3">
                        <div class="col-md-12 text-center">
                            <button name="search_button" value="search_button" type="submit"
                                class="btn btn-primary btn-green">Search</button>
                            <?= $this->Html->link(__('Reset'),['controller'=>'Dashboard','action'=>'index'],['class'=>'btn btn-danger mx-1']); ?>
                        </div>
                    </div>

                    <div class="clearfix"></div>
                    
                </div>
                <?= $this->Form->end(); ?>
            </div>
            <?php //} ?>
        </div>
    </div>
    
    <?php if((isset($s_state) && !empty($s_state)) || $role_id == 7 || $role_id == 8){ ?>
    <div class="row col-sm-12">
        <div class="box box-primary box-st">
            <h3 style="line-height: 2;margin-left:10px;margin-top:-10px;" >
            <?php
            // if($role_id == 7 || $role_id == 8)
            // {
            //     echo $states[$this->request->session()->read('Auth.User.state_code')].' - '.$districts[$this->request->session()->read('Auth.User.district_code')];
            // }
            ?>
            <?= $states[$s_state] ?? '' ?> <?php if($s_district !='' ) { echo " - ". $districts[$s_district];}?></h3> 
            <?php
                // echo '<pre>';
                // print_r($pacs_graph);
                // print_r($dairy_graph);
                // print_r($fishery_graph);
                
            ?>
        </div>
    </div>
    <?php } ?>
    <?php //if(!empty($s_state)) {?>
    <div class="row col-sm-12 box" style="text-align:center;margin-top:-15px;margin-left:0px;">
        <h4 style="font-weight:bold;">Coverage of GP in PACS</h4>
        <?php //$this->Html->link(__('All India Report'),['controller'=>'all-india-reports','action'=>'index']); ?>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <div id="gp_pie_graph">
            </div>
            <p class="highcharts-description" style="text-align:center;font-weight: bold;background-color: #ccc;padding:10px;">
                 View All
            </p>
        </div>
    </div>
    <?php
   // } ?>
    <!-- 
    <div class="row col-sm-12 box" style="text-align:center;margin-top:-15px;margin-left:0px;">
        <h4 style="font-weight:bold;">Progress of Data Entry</h4>
        <?php //$this->Html->link(__('All India Report'),['controller'=>'all-india-reports','action'=>'index']); ?>
    </div>    
    <div class="row">
        <div class="col-sm-4">
            <div id="pacs_graph">
            </div>
        </div>
        <div class="col-sm-4">
            <div id="dairy_graph">
            </div>
        </div>
        <div class="col-sm-4">
            <div id="fishery_graph">
            </div>
        </div>
    </div> 
    <div class="row col-sm-12" style="text-align:right;font-weight:bold;">
        <?php //echo '<h4>'.$this->Html->link(__('All India Report'),['controller'=>'all-india-reports','action'=>'index']).'</h4>'; ?>
    </div>-->
    <div class="row col-sm-12 box" style="text-align:center;margin-top:-15px;margin-left:0px;">
        <h4 style="font-weight:bold;">Functional Report</h4>
        <?php //$this->Html->link(__('All India Report'),['controller'=>'all-india-reports','action'=>'index']); ?>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <div id="pacs_bar">
            </div>
        </div>
        <div class="col-sm-4">
            <div id="dairy_bar">
            </div>
        </div>
        <div class="col-sm-4">
            <div id="fishery_bar">
            </div>
        </div>
    </div>
    <div class="row col-sm-12" style="text-align:right;font-weight:bold;">
        <?php echo '<h4>'.$this->Html->link(__('Functional Report'),['controller'=>'function-list-report','action'=>'index','?' => array('primary_activity' => 1)]).'</h4>'; ?>
    </div>

    
    <?php //print_r($arr_gp_data);

	//} 
    //else {
    ?>
    <!-- <div class="row mb-3">
        <div class="col-sm-6">
            <div id="container1"></div>
        </div>
        <div class="col-sm-6">
            <div id="container2"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div id="container3"></div>
        </div>
        <div class="col-sm-6">
            <div id="container4"></div>
        </div>
    </div> -->
    <?php //} ?>
    </section>
<?php 
	if($role_id == 8){
	?>
<!--==============Modal=====================-->
<div id="nodal_message" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Nodal Entry Message</h4>
            </div>
            <div class="modal-body">
                <p>1).<img style="height:20px;" src="<?php echo $this->Url->build('/img/new.png');?>" /> In order to facilitate further on data verification certificate generation, we have added option for generating and submitting sector wise district wise separate certificates for PACS and Dairy, by respective nodal officer.</p>
                <p>2). <img style="height:20px;" src="<?php echo $this->Url->build('/img/new.png');?>" /> <strong>Steps : How to correct  appearing duplicate Cooperative Society Name/Registration No. </strong><?php echo $this->Html->link('Click here to download steps', '/files/download/How to correct duplicate records.pdf',['download'=>'How to correct duplicate records.pdf']); ?></p>
				<p> 3). Important measures have been announced for cooperatives in Union Budget 2023-24. The related contents of Budget Speech are placed herewith for every one's attention.<br/>
                    <strong><?php echo $this->Html->link('Budget Announcement (Hindi)', '/files/download/Budget Hindi Final.pdf',['download'=>'Budget Hindi Final.pdf']); ?>, 
                    <?php echo $this->Html->link('Budget Announcement (English)', '/files/download/Budget English Final.pdf',['download'=>'Budget English Final.pdf']); ?></strong>
                </p>
                <p> 4). District nodal officer are registered to update their target details, if not updated.
                </p>
                <p> 5). State RCS and District Nodal officers, may kindly see the count of records under the urban to reconcile figures of data, entered by District Nodal Offices. District Nodal Officers are also requested to delete duplicate entries.
                </p>
                <p>6). The Primary Cooperative Society Data is open for final correction, may see the attached documents and follow the instructions.</p>
                <?php echo $this->Html->link('Steps for correction of Data', '/files/download/Steps for correction of Data.docx',['download'=>'Steps for correction of Data.docx']); ?>
                <br/><br/>
                <p>7). Please Reconfirm the Registered Cooperative Society in your District.</p>
                <?php echo $this->Html->link(__('Nodal Entry Form'),['controller'=>'DistrictNodalEntries','action'=>'edit/', $nodal_entry_id]); ?>
                
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
<?php } ?>
<!--==============Modal=====================-->


<style>
.box-container-block {
    padding: 30px 20px;
}

.box-container-block .row {
    margin-bottom: 30px;
}

.websitetitle {
    font-size: 36px;
    text-align: center;
    padding-top: 200px;
    color: #657c12;
    font-weight: bold;
    padding-bottom: 86px;
}

g.highcharts-axis-labels.highcharts-xaxis-labels {}

.content-header>h1 {
    display: inline-block;
}

.content-header>.breadcrumb {

    bottom: 0px;

    top: inherit;
    padding-bottom: 0px;
}

.content-header {
    position: relative;
    padding: 15px 15px 0 15px;
    margin-top: 0px;
    display: inline-block;
    width: 100%;
}

p.websitetitle span {
    display: block;
    color: #00793e;
    font-weight: lighter;
    font-size: 30px;
    position: relative;
    margin-bottom: 15px;
}

p.websitetitle span:after {
    content: "";
    background: rgb(164, 210, 29);
    display: block;
    bottom: 0;
    width: 93px;
    height: 3px;
    margin: 0 auto;
    margin-top: 10px;
}

.main-header .sidebar-toggle:before {
    content: "\f0c9";
    font-size: 20px;
    line-height: normal;
}

#container {
    height: 400px;
}

.highcharts-figure,
.highcharts-data-table table {
    min-width: 310px;
    max-width: 800px;
    margin: 1em auto;
}

.highcharts-data-table table {
    font-family: Verdana, sans-serif;
    border-collapse: collapse;
    border: 1px solid #ebebeb;
    margin: 10px auto;
    text-align: center;
    width: 100%;
    max-width: 500px;
}

.highcharts-data-table caption {
    padding: 1em 0;
    font-size: 1.2em;
    color: #555;
}

.highcharts-data-table th {
    font-weight: 600;
    padding: 0.5em;
}

.highcharts-data-table td,
.highcharts-data-table th,
.highcharts-data-table caption {
    padding: 0.5em;
}

.highcharts-data-table thead tr,
.highcharts-data-table tr:nth-child(even) {
    background: #f8f8f8;
}

.highcharts-data-table tr:hover {
    background: #f1f7ff;
}

text.highcharts-credits {
    display: none;
}

.content-header>h1 {
    margin: 0;
    font-size: 22px;
    font-weight: 600;
    color: #575a65;
    margin-top: 15px;
    margin-bottom: 0px;
}

.box-container-block {
    padding: 15px 20px;
}

div#container1,
div#container2,
div#container3,
div#container4 {
    padding-top: 15px;
    padding-left: 10px;
    padding-right: 10px;
    width: 100%;
    background: white;
}
</style>
<?php $this->append('bottom-script');?>
<script>
$(document).ready(function() {
    <?php 
	// if(in_array($role_id,$arr_graph_allowed))
    // {
	?>
/*
        //pie chart
    Highcharts.chart('pacs_graph', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'Pacs Details',
            align: 'left'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.2f}%</b>'
        },
        accessibility: {
            point: {
                valueSuffix: '%'
            }
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                }
            }
        },

        //total record:100,Total Data Entered:20,Data Entered Today:5
        series: [{
            name: 'Pacs Details',
            colorByPoint: true,
            data: [{
                name: "Remaining Record",
                y: <?= ($pacs_graph['total_record']-$pacs_graph['total_data_entered']) ?>
            }, {
                name: "Data Entered Today",
                y: <?= ($pacs_graph['data_entered_today']) ?>
            }, {
                name: "Data Entered Before Today",
                y: <?= ($pacs_graph['total_data_entered'] - $pacs_graph['data_entered_today']) ?>
            }]
        }]
    });

    Highcharts.chart('dairy_graph', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'Dairy Details',
            align: 'left'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.2f}%</b>'
        },
        accessibility: {
            point: {
                valueSuffix: '%'
            }
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                }
            }
        },

        //total record:100,Total Data Entered:20,Data Entered Today:5
        series: [{
            name: 'Dairy Details',
            colorByPoint: true,
            data: [{
                name: "Remaining Record",
                y: <?= ($dairy_graph['total_record'] - $dairy_graph['total_data_entered']) ?>,
            }, {
                name: "Data Entered Today",
                y: <?= ($dairy_graph['data_entered_today']) ?>,
            }, {
                name: "Data Entered Before Today",
                y: <?= ($dairy_graph['total_data_entered'] - $dairy_graph['data_entered_today']) ?>
            }]
        }]
    });

    Highcharts.chart('fishery_graph', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'Fishery Details',
            align: 'left'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.2f}%</b>'
        },
        accessibility: {
            point: {
                valueSuffix: '%'
            }
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                }
            }
        },

        //total record:100,Total Data Entered:20,Data Entered Today:5
        series: [{
            name: 'Fishery Details',
            colorByPoint: true,
            data: [{
                name: "Remaining Record",
                y: <?= ($fishery_graph['total_record']-$fishery_graph['total_data_entered']) ?>
            }, {
                name: "Data Entered Today",
                y: <?= ($fishery_graph['data_entered_today']) ?>
            }, {
                name: "Data Entered Before Today",
                y: <?= ($fishery_graph['total_data_entered'] - $fishery_graph['data_entered_today']) ?>
            }]
        }]
    });
*/

    //=========================================Bar Charts===================================

    Highcharts.chart('pacs_bar', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Pacs Details'
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            type: 'category',
            labels: {
                //rotation: -45,
                style: {
                    fontSize: '13px',
                    fontFamily: 'Verdana, sans-serif'
                }
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Total Pacs Count'
            }
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
                    colorByPoint: true
                }
            },
        series: [{
            name: '<span style="color:black;">Count</span>',
            data: [
                ['Functional', <?= $total_functional[1] ?? 0 ?>],
                ['Non-Functional / Dormant', <?= $non_functional[1] ?? 0 ?>],
                ['Under Liquidation', <?= $total_under_liquition[1] ?? 0 ?>]
            ],
            dataLabels: {
                enabled: true,
                style: {
                    fontSize: '13px',
                    fontFamily: 'Verdana, sans-serif'
                }
            }
        }]
    });

    Highcharts.chart('dairy_bar', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Dairy Details'
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            type: 'category',
            labels: {
                //rotation: -45,
                style: {
                    fontSize: '13px',
                    fontFamily: 'Verdana, sans-serif'
                }
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Total Dairy Count'
            }
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
                    colorByPoint: true
                }
            },
        series: [{
            name: '<span style="color:black;">Count</span>',
            data: [
                ['Functional', <?= $total_functional[9] ?? 0 ?>],
                ['Non-Functional / Dormant', <?= $non_functional[9] ?? 0 ?>],
                ['Under Liquidation', <?= $total_under_liquition[9] ?? 0 ?>]
            ],
            dataLabels: {
                enabled: true,
                style: {
                    fontSize: '13px',
                    fontFamily: 'Verdana, sans-serif'
                }
            }
        }]
    });

    Highcharts.chart('fishery_bar', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Fishery Details'
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            type: 'category',
            labels: {
                //rotation: -45,
                style: {
                    fontSize: '13px',
                    fontFamily: 'Verdana, sans-serif'
                }
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Total Fishery Count'
            }
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
                    colorByPoint: true
                }
            },
        series: [{
            name: '<span style="color:black;">Count</span>',
            data: [
                ['Functional', <?= $total_functional[9] ?? 0 ?>],
                ['Non-Functional / Dormant', <?= $non_functional[9] ?? 0 ?>],
                ['Under Liquidation', <?= $total_under_liquition[9] ?? 0 ?>]
            ],
            dataLabels: {
                enabled: true,
                style: {
                    fontSize: '13px',
                    fontFamily: 'Verdana, sans-serif'
                }
            }
        }]
    });

    //======================================================================================

    $('#searchBanner').show();
    $('.select2').select2();

    $("#TglSrch").click(function() {
        $("#searchBanner").slideToggle(1500);
    });
    <?php if($role_id != 7 || $role_id != 8){ ?>
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

    });

    $('#searchBanner').on('change','#district',function(e){
        if ($(this).val() != '') {
            $.ajax({
                type: 'POST',
                cache: false,
                url: '<?=$this->Url->build(['action'=>'get-blocks'])?>',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                },
                data: {
                    district_code: $(this).val()
                },
                success: function(response) {
                    $("#block").html(response);
                },
            });
        }
    });

    <?php } 

    //} else { ?>
    /*Highcharts.chart('container1', {

        chart: {
            type: 'column'
        },
        title: {
            text: 'State Wise Fisheries'
        },
        plotOptions: {
            series: {
                pointPadding: 0.2,
                groupPadding: 0

            }
        },
        colors: ['#009688 '],
        subtitle: {
            text: ''
        },
        xAxis: {
            type: 'category',
            labels: {

                style: {
                    fontSize: '12px',

                }
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: ''
            }
        },

        legend: {
            enabled: false
        },
        tooltip: {
            pointFormat: ''
        },
        series: [{
            name: 'Population',
            data: [
                ['Andaman &amp; Nicobar Islands', 400],
                ['Andhra Pradesh', 450],
                ['Daman & Diu', 300],
                ['Gujarat', 500],
                ['Karnataka', 550],
                ['Maharashtra', 450],
                ['Odisha', 750],
                ['Tamil Nadu', 800],
                ['Uttar Pradesh', 1000],
            ],
            dataLabels: {
                enabled: true,
                rotation: -90,
                color: '#FFFFFF',
                align: 'right',

                y: 10, // 10 pixels down from the top
                style: {
                    fontSize: '12px',

                }
            }
        }]
    });


    Highcharts.chart('container2', {
        chart: {
            type: 'column'
        },

        title: {
            text: 'State Wise PACS'
        },
        plotOptions: {
            series: {
                pointPadding: 0.2,
                groupPadding: 0

            }
        },
        colors: [
            '#6d5287 '
        ],
        subtitle: {
            text: ''
        },
        xAxis: {
            type: 'category',
            labels: {

                style: {
                    fontSize: '12px',

                }
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: ''
            }
        },
        legend: {
            enabled: false
        },
        tooltip: {
            pointFormat: ''
        },
        series: [{
            name: 'Population',
            data: [
                ['Andaman &amp; Nicobar Islands', 4054],
                ['Andhra Pradesh', 5033],
                ['Daman & Diu', 6031],
                ['Gujarat', 7260],
                ['Karnataka', 8040],
                ['Maharashtra', 10540],
                ['Odisha', 12330],
                ['Tamil Nadu', 14000],
                ['Uttar Pradesh', 15551],
            ],
            dataLabels: {
                enabled: true,
                rotation: -90,
                color: '#FFFFFF',
                align: 'right',

                y: 10, // 10 pixels down from the top
                style: {
                    fontSize: '12px',

                }
            }
        }]
    });



    Highcharts.chart('container3', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'State Wise Dairy '
        },
        plotOptions: {
            series: {
                pointPadding: 0.2,
                groupPadding: 0

            }
        },
        colors: ['#2a92c1'],
        subtitle: {
            text: ''
        },
        xAxis: {
            type: 'category',
            labels: {

                style: {
                    fontSize: '12px',

                }
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: ''
            }
        },
        legend: {
            enabled: false
        },
        tooltip: {
            pointFormat: ''
        },
        series: [{
            name: 'Population',
            data: [
                ['Andaman &amp; Nicobar Islands', 12789],
                ['Andhra Pradesh', 13896],
                ['Daman & Diu', 14563],
                ['Gujarat', 15000],
                ['Karnataka', 16893],
                ['Maharashtra', 17452],
                ['Odisha', 18654],
                ['Tamil Nadu', 19745],
                ['Uttar Pradesh', 20812],
            ],
            dataLabels: {
                enabled: true,
                rotation: -90,
                color: '#FFFFFF',
                align: 'right',

                y: 10, // 10 pixels down from the top
                style: {
                    fontSize: '12px',

                }
            }
        }]
    });



    Highcharts.chart('container4', {
        colors: ['#788d60'],
        chart: {
            type: 'bar'
        },
        title: {
            text: 'Top 5 Panchayat'
        },
        plotOptions: {
            series: {
                pointPadding: 0.2,
                groupPadding: 0

            }
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            type: 'category',
            labels: {

                style: {
                    fontSize: '11px',

                }
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: ''
            }
        },
        legend: {
            enabled: false
        },
        tooltip: {
            pointFormat: ''
        },
        series: [{
            name: 'Population',
            data: [
                ['AMROHA', 1114],
                ['ALIGARH', 1166],
                ['AMETHI', 8871],
                ['AMBEDKAR NAGAR', 1674],
                ['BALLIA', 2313],

            ],
            dataLabels: {
                enabled: true,

                color: '#FFFFFF',
                align: 'right',

                y: 1, // 10 pixels down from the top
                style: {
                    fontSize: '10px',


                }
            }
        }]
    });*/
    <?php //if(!empty($s_state)) { ?>
    Highcharts.chart('gp_pie_graph', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'GP Details',
            align: 'center'
        },
        subtitle: {
            text: 'Total GP in Country: <?= $arr_gp_data['all_gp'] ?>',
            align: 'center'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.y}</b>'
        },
        accessibility: {
            point: {
                valueSuffix: '%'
            }
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.y}'
                }
            },
            series: {
                cursor: 'pointer',
                point: {
                    events: {
                        click: function () {
                            location.href = 'https://en.wikipedia.org/wiki/'
                        }
                    }
                }
            }
        },

        //total record:100,Total Data Entered:20,Data Entered Today:5
        series: [{
            name: 'Count',
            colorByPoint: true,
            data: [{
                name: "Fully Covered Gram Panchayat",
                y: <?= $arr_gp_data['full_covered_gp'] ?>
            }, {
                name: "Partial Covered Gram Panchayat",
                y: <?= $arr_gp_data['partial_covered_gp'] ?>
            }, {
                name: "Not Covered Gram Panchayat",
                y: <?= $arr_gp_data['notcovered_gp'] ?>
            }]
        }]
    });
    <?php //} 
    if($role_id == 8){
	?>
    $('#nodal_message').modal('toggle');

    <?php } ?>
});
</script>
<?php $this->end(); ?>