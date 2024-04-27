<script>

var _csrfToken = '<?php echo $this->request->getParam('_csrfToken'); ?>';

function statechange(selectedvalue) {
    // alert(selectedvalue);
    if (selectedvalue === "select") {
        alert('Please Select State');
        return false;
      /*  selected_state = "select";
        selected_district = "select";
        $("#districtlist").empty();
        view.extent = (adminlayer.fullExtent);
        graphicLayer.removeAll();
        view.graphics.removeAll();
        prox_graphlayer.removeAll();
        qb_graphiclayer.removeAll();
        showschoolpoints(); */
    }
    else
    {
        $.ajax({
    type:'POST',
    async: true,
    cache: false,
    url: '<?php echo SITE_URL; ?>' + 'gis/getDistricts',
    data: {
        state_code : selectedvalue, _csrfToken: _csrfToken
       },
    success: function(response) {
        $('#districtlist').html(response);
    },
    error : function (){
      alert('Error!');
    }
   });
        /*prox_graphlayer.removeAll();
        qb_graphiclayer.removeAll();
        graphicLayer.removeAll();
        view.graphics.removeAll();
        distict_display(selectedvalue);
        selected_state = selectedvalue;
        selected_district = "select";
        showschoolpoints(); */
        zoom("stcode11='" + selectedvalue + "'", 0);      
    }
}

function show_block(selectedvalue) {
    if (selectedvalue === "select") {
        alert('Please Select Block');
       /* selected_district = "select";
        zoom("stcode11='" + selected_state + "'", 0);
        graphicLayer.removeAll();
        view.graphics.removeAll();
        prox_graphlayer.removeAll();
        qb_graphiclayer.removeAll();
        showschoolpoints(); */

    }
    else {
      // alert(selectedvalue);
       /* prox_graphlayer.removeAll();
        qb_graphiclayer.removeAll();
        graphicLayer.removeAll();
        view.graphics.removeAll();
        selected_district = selectedvalue;
        showschoolpoints(); */    
        state_code=$('#statelist').val();   
        zoom("stcode11='"+ state_code +"' and block_lgd="+ selectedvalue , 2);
    }
}

function districtchange_OLD(selectedvalue) {
    if (selectedvalue === "select") {
        alert('Please Select Distirct');
      /*  selected_district = "select";
        zoom("stcode11='" + selected_state + "'", 0);
        graphicLayer.removeAll();
        view.graphics.removeAll();
        prox_graphlayer.removeAll();
        qb_graphiclayer.removeAll();
        showschoolpoints();*/

    }
    else {
        alert(selectedvalue);
       /* prox_graphlayer.removeAll();
        qb_graphiclayer.removeAll();
        graphicLayer.removeAll();
        view.graphics.removeAll();
        selected_district = selectedvalue;
        showschoolpoints(); */
        zoom("dtcode11='" + selectedvalue + "'", 1);
        
    }
}


function districtchange(selectedvalue) {
     alert(selectedvalue);
    if (selectedvalue === "select") {
        alert('Please Select District');
        return false;
      /*  selected_district = "select";
        zoom("stcode11='" + selected_state + "'", 0);
        graphicLayer.removeAll();
        view.graphics.removeAll();
        prox_graphlayer.removeAll();
        qb_graphiclayer.removeAll();
        showschoolpoints();*/
    }
    else
    {
        $.ajax({
    type:'POST',
    async: true,
    cache: false,
    url: '<?php echo SITE_URL; ?>' + 'gis/getBlocks',
    data: {
        district_code : selectedvalue, _csrfToken: _csrfToken
       },
    success: function(response) {
        $('#block_list').html(response);
    },
    error : function (){
      alert('Error!');
    }
   });
       /* prox_graphlayer.removeAll();
        qb_graphiclayer.removeAll();
        graphicLayer.removeAll();
        view.graphics.removeAll();
        selected_district = selectedvalue;
        showschoolpoints(); */
        // zoom("dtcode11='" + selectedvalue + "'", 1);           
        //state_code=$('#statelist').val();   
        zoom(" dtcode11='" + selectedvalue + "'" , 1);
    }
}


function zoom(wherecondition,layerid)
    {
        
        show_loading();
		require(["esri/tasks/query", "esri/tasks/QueryTask"], function (Query, QueryTask) {

      /*  require(["esri/tasks/query", "esri/tasks/QueryTask", "esri/symbols/SimpleFillSymbol", "esri/symbols/SimpleLineSymbol", "esri/Color",
        "esri/symbols/Font", "esri/symbols/TextSymbol",  "esri/geometry/Extent"], function (Query, QueryTask, SimpleFillSymbol, SimpleLineSymbol, Color, Font, TextSymbol,
            Extent) { */

			var queryTask = new QueryTask("<?php echo MAP_URL; ?>/"+layerid+"?token=<?php echo MAP_TOKEN; ?>");
			var query = new Query();
			query.returnGeometry = true;
			query.outFields = ["*"];
			query.where = wherecondition;
			queryTask.execute(query, function (fset) {
				if(fset.features.length>0)
				{
					var esriExtent = new esri.geometry.Extent(fset.features[0].geometry.getExtent().expand(1.5));
                    map.setExtent(esriExtent);
                    hide_loading();
				}
				else
				{
					alert("No features found for zoom");
				}
			},
			function(error){
				alert(error);               
			});

        });

    }

function zoom_OLD(wherecondition, layerid) {
    graphicLayer.removeAll();
    show_loading();  
    require(["esri/tasks/QueryTask", "esri/tasks/support/Query", "esri/symbols/SimpleFillSymbol", "esri/symbols/SimpleLineSymbol", "esri/Color",
        "esri/symbols/Font", "esri/symbols/TextSymbol", "esri/Graphic", "esri/geometry/Extent"], function (QueryTask, Query, SimpleFillSymbol, SimpleLineSymbol, Color, Font, TextSymbol,
            Graphic,Extent) {
        var queryTask = new QueryTask(config.dynamiclayers[1].url + "/" + layerid + "?token=" + config.dynamiclayers[1].token);
        var query = new Query();
        query.returnGeometry = true;
        query.outFields = ["*"];
        query.where = wherecondition;
        queryTask.execute(query).then(function (fset) {
        if (fset.features.length > 0) {
            var geomentryTerrain = fset.features[0].geometry;
            var attr = fset.features[0].attributes;
            var geomentryType = fset.geometryType;
            var dipslayfield = fset.displayFieldName;
            var value;
            if (geomentryType == "polygon") {
                if (dipslayfield == "STNAME" || "dtname" || "block_name") {

                    //var symbol = new SimpleFillSymbol(SimpleFillSymbol.STYLE_NULL, new SimpleLineSymbol(SimpleLineSymbol.STYLE_SOLID, new Color([0, 0, 255]), 3), new Color([0, 0, 255, 0]));
                    var symbol = {
                        type: "simple-line",  // autocasts as new SimpleLineSymbol()
                        color: "green",
                        width: "2px",
                        style: "solid"
                    };
                    value = attr.STNAME_SH;
                }
              
            }
          
            let textSymbol = {
                type: "text",  // autocasts as new TextSymbol()
                text: value,
                font: {  // autocasts as new Font()
                    family: "Merriweather",
                    size: 20,
                    horizontalAlignment:"center",
                    style: "italic",
                    weight: "bold"
                }
            };
        
            var labelPoint = new Graphic(geomentryTerrain, textSymbol);
            
            //view.graphics.add(labelPoint);
            graphicLayer.add(new Graphic(geomentryTerrain, symbol, attr));
            view.map.add(graphicLayer);
            var ext1 = (fset.features[0].geometry.extent);
            view.extent = ext1.expand(1.1);
            hide_loading();
            }
            else {
                alert("No features found for zoom");
            }
        },
            function (error) {
                alert(error);
            });

    });

}

function show_loading(){
    document.getElementById("bodyloadimg").style.display = "block";
}

function hide_loading(){
    document.getElementById("bodyloadimg").style.display = "none";    
}

//-----------------------------------------------------------------

      $(window).on('load', function(){        
      // 
      });
</script>


    <script>
    var map;

    require(['esri/dijit/BasemapLayer', 'esri/dijit/Basemap', 'esri/map','esri/geometry/Extent','esri/SpatialReference',  'esri/layers/ArcGISDynamicMapServiceLayer','esri/layers/ArcGISTiledMapServiceLayer',  "esri/InfoTemplate",  "esri/layers/GraphicsLayer", "dojo/domReady!" ], 


    function (BasemapLayer, Basemap, Map,Extent,SpatialReference, ArcGISDynamicMapServiceLayer,ArcGISTiledMapServiceLayer, InfoTemplate, GraphicsLayer) {

    //  var nobasemap = new Basemap({layers: [new BasemapLayer({url: 'http://services.arcgisonline.com/arcgis/rest/services/World_Street_Map/MapServer', opacity: 0})]});

    map = new Map('map_Div', {
    logo:false,
    showAttribution:false,
    basemap: "topo-vector", // // "topo-vector" // nobasemap
    center: [78.9629,20.5937], // longitude, latitude of India
    zoom: 5,
    });                               

    //   prox_graphlayer = new GraphicsLayer();
    //   map.addLayer(prox_graphlayer);

    //   qb_graphiclayer = new GraphicsLayer();
    //   map.addLayer(qb_graphiclayer);

    map.addLayer(new ArcGISDynamicMapServiceLayer('<?php echo MAP_URL; ?>?Token=<?php echo MAP_TOKEN; ?>'));

    map.setExtent(new Extent(66.62, 5.23, 98.87, 38.59, new SpatialReference({ wkid: 4326 })),true);

    });

</script>
