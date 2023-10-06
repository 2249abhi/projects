<div class="row">
    <div class="col-sm-4">
        <div id="gp_pie_graph" class="w100">
        </div>
        <p class="highcharts-description"
            style="text-align:center;font-weight: bold;background-color:#3c7046 ;  padding:10px;">
            <?= $this->Html->link(__('View All'),['controller'=>'GramPanchayatChart','action'=>'index'],['class'=>'']); ?>
        </p>
    </div>
    <div class="col-sm-4">
        <div id="member_chart" class="w100">
        </div>
        <p class="highcharts-description"
            style="text-align:center;font-weight: bold;background-color: #3c7046;padding:10px;">
            <?= $this->Html->link(__('View All'),['controller'=>'RegisterWisePac','action'=>'index','primary_activity'=>1],['class'=>'']); ?>
        </p>
    </div>
    <div class="col-sm-4">
        <div id="so_af_pacs" class="w100">
        </div>
        <p class="highcharts-description"
            style="text-align:center;font-weight: bold;background-color:#3c7046;padding:10px;">
            <?= $this->Html->link(__('View All'),['controller'=>'FederationReports','action'=>'viewchart?primary_activity=1'],['class'=>'']); ?>
        </p>
    </div>
</div>
<div class="row col-sm-12 box" style="text-align:center;margin-top:-15px;margin-left:0px;">
    <h4 style="font-weight:bold;">Functional Status of Cooperative Society</h4>
    <?php //$this->Html->link(__('All India Report'),['controller'=>'all-india-reports','action'=>'index']); ?>
</div>
<div class="row">
    <div class="col-sm-4">
        <div id="pacs_bar" class="w100">
        </div>
        <p class="highcharts-description"
            style="text-align:center;font-weight: bold;background-color: #3c7046;padding:10px;">
            <?= $this->Html->link(__('View All'),['controller'=>'function-list-report','action'=>'index','primary_activity'=>1],['class'=>'']); ?>
        </p>
    </div>
    <div class="col-sm-4">
        <div id="dairy_bar" class="w100">
        </div>
        <p class="highcharts-description"
            style="text-align:center;font-weight: bold;background-color:#3c7046;padding:10px;">
            <?= $this->Html->link(__('View All'),['controller'=>'function-list-report','action'=>'index','primary_activity'=>9],['class'=>'']); ?>
        </p>
    </div>
    <div class="col-sm-4">
        <div id="fishery_bar" class="w100">
        </div>
        <p class="highcharts-description"
            style="text-align:center;font-weight: bold;background-color: #3c7046;padding:10px;">
            <?= $this->Html->link(__('View All'),['controller'=>'function-list-report','action'=>'index','primary_activity'=>10],['class'=>'']); ?>
        </p>
    </div>
</div>