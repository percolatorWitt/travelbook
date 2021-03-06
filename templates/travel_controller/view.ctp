<!-- File: src/Template/Travels/view.ctp -->

<script type="application/javascript">
        
function jumpTo(lon, lat, zoom) {
    var x = Lon2Merc(lon);
    var y = Lat2Merc(lat);
    map.setCenter(new OpenLayers.LonLat(x, y), zoom);
    return false;
}

function Lon2Merc(lon) {
    return 20037508.34 * lon / 180;
}

function Lat2Merc(lat) {
    var PI = 3.14159265358979323846;
    lat = Math.log(Math.tan( (90 + lat) * PI / 360)) / (PI / 180);
    return 20037508.34 * lat / 180;
}

function addMarker(layer, lon, lat, popupContentHTML) {

    var ll = new OpenLayers.LonLat(Lon2Merc(lon), Lat2Merc(lat));
    var feature = new OpenLayers.Feature(layer, ll); 
    feature.closeBox = true;
    feature.popupClass = OpenLayers.Class(OpenLayers.Popup.FramedCloud, {minSize: new OpenLayers.Size(300, 180) } );
    feature.data.popupContentHTML = popupContentHTML;
    feature.data.overflow = "hidden";
            
    var marker = new OpenLayers.Marker(ll);
    marker.feature = feature;

    var markerClick = function(evt) {
        if (this.popup == null) {
            this.popup = this.createPopup(this.closeBox);
            map.addPopup(this.popup);
            this.popup.show();
        } else {
            this.popup.toggle();
        }
        OpenLayers.Event.stop(evt);
    };
    marker.events.register("mousedown", feature, markerClick);
 //this.popup.toggle();
    layer.addMarker(marker);
    map.addPopup(feature.createPopup(feature.closeBox));
}

function getCycleTileURL(bounds) {
   var res = this.map.getResolution();
   var x = Math.round((bounds.left - this.maxExtent.left) / (res * this.tileSize.w));
   var y = Math.round((this.maxExtent.top - bounds.top) / (res * this.tileSize.h));
   var z = this.map.getZoom();
   var limit = Math.pow(2, z);

   if (y < 0 || y >= limit)
   {
     return null;
   }
   else
   {
     x = ((x % limit) + limit) % limit;

     return this.url + z + "/" + x + "/" + y + "." + this.type;
   }
}
        
function init() {
    map = new OpenLayers.Map("basicMap");
    var mapnik = new OpenLayers.Layer.OSM();
    
    layer_markers = new OpenLayers.Layer.Markers("Address", { projection: new OpenLayers.Projection("EPSG:4326"), 
                            visibility: true, displayInLayerSwitcher: false });
    
    
    map.addLayers([mapnik, layer_markers]);
    map.setCenter(new OpenLayers.LonLat(13.41,52.52) // Center of the map
      .transform(
        new OpenLayers.Projection("EPSG:4326"), // transform from WGS 1984
        new OpenLayers.Projection("EPSG:900913") // to Spherical Mercator Projection
      ), 1 // Zoom level
    );

                        
    //jumpTo(lon, lat, zoom);
    var popuptext="<font color=\"black\"><b>Text im Popup-Fenster</b>";
    // Position des Markers
    <?php foreach ($days as $day): ?>
    addMarker(layer_markers, <?= h($day['location']['lon']) ?>, <?= h($day['location']['lat']) ?>, popuptext);
    <?php endforeach; ?>
    //addMarker(layer_markers, 6.641389, 49.756667, popuptext);

}
</script>

<script type="application/javascript">
$(function() {
    adressSearch();
});

		
//Adresse suchen        
function adressSearch(){


    jQuery('#suche').bind('keypress', function(e) {

        if( e.keyCode == 13){    
            //var value = $("#suche").val();
            var ajax_url = "http://nominatim.openstreetmap.org/search/"+encodeURIComponent($("#suche").val());
            jQuery.ajax({
                   asyncBoolean: false,
                   type: "GET",
                   url: ajax_url,
                   data: "format=json&polygon=1&addressdetails=1",
                   dataType: "html",
                   contentType: "text/html;charset=ISO-8859-1",
                   success: function(response){
                        console.log("toll");
                        //document.cookie="response_id="+responseId+";path=/;";
                        var jsonObj = JSON.parse(response);


                        displaySearch(jsonObj);
                   }
            });
        }
    });
    //return false;
}
    
function displaySearch(obj){
    var count = obj.length;

    //$("#daten").append('<ul id="selectLocation">');

    for(var i = 0;i<10;i++){
        if(i > count){
            break;
        }

        $("#selectLocation").append('<li class=\"'+obj[i].type+'\" onclick=\"showInMap('+obj[i].lon+', '+obj[i].lat+');\">'+obj[i].display_name+'</li>');

       // console.log(obj[i].lon);
        //console.log(obj[i].lat);
    }
    //$("#daten").append('/<ul>');
}
    
    function showInMap(lonString, latString){
        //console.log(lon);
        var data = '{ "lon":"'+lonString+'", "lat":"'+latString+'"}';
        addDay(data);
    }

function addDay(data){
    var ajax_url = "http://www.travel.com/days/addAjax/";
    jQuery.ajax({
      asyncBoolean: true,
      type: "POST",
      url: ajax_url,
      data: data,
      dataType: "html",
      contentType: "text/html;charset=ISO-8859-1",
      success: function(response){
        //document.cookie="response_id="+responseId+";path=/;";
        //var jsonObj = JSON.parse(response);
        console.log(response);
        //Wenn Speichern ok, dann auf Karte anzeigen

        //auf Karte anzeigen
        addMarker(layer_markers, jsonObj["lon"], jsonObj["lat"], "asdf");
      }
    });

/*
$.post("http://www.travel.com/days/addAjax/",
        {
          name: "Donald Duck",
          city: "Duckburg"
        },
        function(data,status){
            alert("Data: " + data + "\nStatus: " + status);
        }
);*/
}

</script>

<p><?= h($travel->days[0]->location) ?></p>

<h1>Travel - <?php echo $travel->name;?></h1>
<div id="basicMap"></div>

<br/>
<?php// echo $this->Form->create($day);?>

<input id="suche" type="text" name="suche"/>




<div id="daten">
    <ul id="selectLocation">
        
    </ul>
</div>

<?php 
    if(!empty($travel->days)){
        echo '<ul id="selectDay">';
    
        foreach($travel->days as $day){
            echo '<li id="day'.$day['id'].'">'.$day['location'].'</li>';
        }
    
      echo '</ul>';
    }
?>