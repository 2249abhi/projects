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
      <?= $this->Flash->render();?>
      <ul>
          <?php //echo $this->Menu->render('main-menu'); ?>
      </ul>
      <p class="websitetitle"> <span>Welcome to</span> <small> Ministry of Cooperation<?php //echo Configure::read('Theme.title'); ?></small> </p>
    </section>-->

<div class="box-container-block">

    <?php 
	if($role_id == 2 || $role_id == 10){
	?>
    <div class="row col-sm-12 box" style="text-align:center;height:40px;">
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
        <?php echo '<h4>'.$this->Html->link(__('All India Report'),['controller'=>'all-india-reports','action'=>'index']).'</h4>'; ?>
    </div>
    <?php
	} else {
    ?>
    <div class="row mb-3">
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
    </div>
    <?php } ?>
</div>
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
                <p>Please Reconfirm the Registered Cooperative Society in your District.</p>
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
	if($role_id == 2){
	?>


    Highcharts.chart('pacs_graph', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'Pacs Detail',
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
            name: 'Pacs Detail',
            colorByPoint: true,
            data: [{
                name: 'Remaining Record',
                y: <?= ($pacs_graph['total_record']-$pacs_graph['total_data_entered']) ?>
            }, {
                name: 'Data Entered Today',
                y: <?= ($pacs_graph['data_entered_today']) ?>
            }, {
                name: 'Data Entered Before Today',
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
            text: 'Dairy Detail',
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
            name: 'Dairy Detail',
            colorByPoint: true,
            data: [{
                name: 'Remaining Record',
                y: <?= ($dairy_graph['total_record'] - $dairy_graph['total_data_entered']) ?>,
            }, {
                name: 'Data Entered Today',
                y: <?= ($dairy_graph['data_entered_today']) ?>,
            }, {
                name: 'Data Entered Before Today',
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
            text: 'Fishery Detail',
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
            name: 'Fishery Detail',
            colorByPoint: true,
            data: [{
                name: 'Remaining Record',
                y: <?= ($fishery_graph['total_record']-$fishery_graph['total_data_entered']) ?>
            }, {
                name: 'Data Entered Today',
                y: <?= ($fishery_graph['data_entered_today']) ?>
            }, {
                name: 'Data Entered Before Today',
                y: <?= ($fishery_graph['total_data_entered'] - $fishery_graph['data_entered_today']) ?>
            }]
        }]
    });
    <?php } else { ?>
    Highcharts.chart('container1', {

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
    });
    <?php } 
    if($role_id == 8){
	?>
    $('#nodal_message').modal('toggle');

    <?php } ?>
});
</script>
<?php $this->end(); ?>