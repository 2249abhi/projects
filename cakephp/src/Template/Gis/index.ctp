<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>-->




<link rel="stylesheet" href="https://js.arcgis.com/3.42/esri/css/esri.css">
<link rel="stylesheet" href="https://js.arcgis.com/3.42/dijit/themes/claro/claro.css">
<script src="https://js.arcgis.com/3.42/"></script>



<style>
html, body{
padding: 0;
margin: 0;
height: 100%;
}

#map_Div{
padding: 0;
margin: 0;
height: 100%;
clear:both;
border: 2px solid #ccc;;
}
</style>                   

<?php
echo $this->element('map_functions'); 
?>


<center><h3>State wise information</h3></center>

<div class="row" style="padding: 2px;">

<div class="col-sm-2"><select class="form-control" id="statelist" onchange="statechange(this.value)" style="width:250px;" name="sellist1">
<option value="select">Select State</option>
<?php
foreach($states as $key=>$val)
{
?>
<option value="<?php echo $key; ?>"><?php echo firstUpper($val); ?></option>
<?php 
} 
?>
</select></div>
<div class="col-sm-2"><select class="form-control" id="districtlist" onchange="districtchange(this.value)" style="width:250px;" name="districtlist1">
<option value="select">Select District</option>
</select></div>
<div class="col-sm-2"><select class="form-control" id="block_list" onchange="show_block(this.value)" style="width:250px;" name="block_list">
<option value="select">Select Block</option>
</select></div>
<div class="col-sm-2"><?php echo $this->Html->link('Reset',['controller' => 'gis', 'action' => 'index'], array('class' => 'btn btn-success'));?></div>

</div>

<img alt="Loading.." id="bodyloadimg" src="<?php echo SITE_URL; ?>img/loader.gif" style="width: 80px; position: absolute; left: 50%; top: 50%; z-index: 100; display: none;" />



                            <div id='map_Div'><!--The map will load here --></div>  
                         
                            



                            <script>








                            </script>
                          
                            

                       