var map;

function init(){
    
    if(locations.length == 0){
        //Standardsicht, falls keine Orte eingetragen
        map = L.map('basicMap').setView([51.0834196,10.4234469], 4);
    }else{
        
        map = L.map('basicMap').setView([locations[0][1],locations[0][2]], 8);
    }
    mapLink = '<a href="https://openstreetmap.org">OpenStreetMap</a>';
    L.tileLayer(
        'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; ' + mapLink + ' Contributors',
        maxZoom: 18,
        }).addTo(map);
    
    if(locations.length == 0){
        $("#basicMap").css("width", "60%");
        //$("#addedlocations").css("display", "block");
        $("#addedlocations").fadeIn(1000, "linear");
        
    }else{
    
        for (var i = 0; i < locations.length; i++) {
            marker = new L.marker([locations[i][1],locations[i][2]])
            .bindPopup(locations[i][0])
            .addTo(map);
        }
    }
}

/*
 * Wenn man ein Datum, von, bis, einträgt, dann soll unter dem Ort eine Checkbox rein,
 * dass man eintragen kann, ob man dann da war
 * Die Orte sollen in XML/JSON abgespeichert werden. Dann kann man Datum gut hinzufügen
 */			

	//alternativfunktion
function submitform(address) {        
    OpenLayers.Request.POST({
        url: "http://www.openrouteservice.org/php/OpenLSLUS_Geocode.php",
        scope: this,
        failure: this.requestFailure,
        success: this.requestSuccess,
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        data: "FreeFormAdress=" + encodeURIComponent(address) + "&MaxResponse=1"
    });
}

//add saved locations
function initialAddedLocations(){
    var lists = $("#addedlocationslist li");

    var listcount = lists.length;

    if(listcount > 0){
        //dragable
        $( "#addedlocationslist" ).sortable({
          placeholder: "addlocationsEntry"
        });
        $( "#addedlocationslist" ).disableSelection();
        
        $("#basicMap").css("width", "60%");
        //$("#addedlocations").css("display", "block");
        $("#addedlocations").fadeIn(1000, "linear");
        
        //move position of map to entry
        $(".addlocationsEntry").click(function(){
            var id = this.id;
            var val = $("#"+id+" input").val();
            var obj = JSON.parse(val);

            setView(obj[0].lat, obj[0].lon);
        });
    }
}

$(function() {
    initialAddedLocations();
    //$('form').submit(function(event){event.preventDefault();});
    //upload
    // Auf neue Auswahl reagieren und gegebenenfalls Funktion dateiauswahl neu ausführen.
    //document.getElementById('files').addEventListener('change', dateiauswahl, false);
    
    $( "#accordion" ).accordion({
      heightStyle: "content"
    });
    
    //date
    $( "#startdate" ).datepicker({
        onSelect: function(selected) {
            $("#enddate").datepicker("option","minDate", selected)
        }
    });
    $( "#enddate" ).datepicker({
        onSelect: function(selected) {
            $("#startdate").datepicker("option","maxDate", selected)
        }
    });
    
    
    init();
    //myNewLayer(test);
    $(".olPopup").css("display", "none");
    adressSearch();
    
    
    //nach Speichern umleiten auf "edit" um weiter zu bearbeiten
});

		
//Adresse suchen        
function adressSearch(){


    jQuery('#suche').bind('keydown  onpaste', function(e) {

        if( e.keyCode == 13){    
            //var value = $("#suche").val();
            var ajax_url = "https://nominatim.openstreetmap.org/search/"+encodeURIComponent($("#suche").val());
            jQuery.ajax({
                   asyncBoolean: false,
                   type: "GET",
                   url: ajax_url,
                   data: "format=json&polygon=1&addressdetails=1",
                   dataType: "html",
                   contentType: "text/html;charset=ISO-8859-1",
                   success: function(response){

                        //document.cookie="response_id="+responseId+";path=/;";
                        var jsonObj = JSON.parse(response);

                        displaySearch(jsonObj);
                   }
            });
        }
    });
    //return false;
}

function remove(id){
    $( "#"+id ).remove();
}

function displaySearch(obj){
    var count = obj.length;

    //$("#daten").append('<ul id="selectLocation">');

    for(var i = 0;i<10;i++){
        if(i > count){
            break;
        }
        $("#basicMap").css("width", "60%");
        $("#addedlocations").fadeIn(1000, "linear");
        //Ausgabe in Ergebnisliste
        $("#selectLocation").append('<li class=\"'+obj[i].type+'\" onclick=\"showInMap('+obj[i].lon+', '+obj[i].lat+', \''+obj[i].display_name+'\', '+obj[i].place_id+');\">'+obj[i].display_name+'</li>');

    }
    //$("#daten").append('/<ul>');
}
    
function showInMap(lonString, latString, displayName, place_id){
    //var data = '{ "lon":"'+lonString+'", "lat":"'+latString+'"}';
    var data = '[{ &quot;lat&quot;: &quot;'+latString+'&quot;, &quot;lon&quot;: &quot;'+lonString+'&quot;, &quot;text&quot;: &quot;'+displayName+'&quot; }]';
    //Daten (Koordinaten/Adresse in Formular speichern
    //Bei Absenden Formulars, Daten speichern und bei Anzeige erneut anzeigen
        
    //Ausgabe in "added locations"
    //bei PlaceId die Nummer direkt am Namen anhängen
    var textelement = '<span>'+displayName+'</span>';
    var inputelement = '<input name="places[place_id'+place_id+']" type="hidden" value="'+data+'"/>';
    $("#addedlocations ul").append('<li id="place_id'+place_id+'" class="addlocationsEntry">'+textelement+inputelement+'<span id="'+place_id+'" class="delete" title="delete" onclick="remove(\'place_id'+place_id+'\');"></span></li>');
    
    marker = new L.marker([latString, lonString]).bindPopup(displayName).addTo(map);
     //Bei jedem hinzufügen darauf zoomen
    setView(latString, lonString);
    //Sucheingabe leeren
    $("#suche").val("");
    $("#selectLocation li").remove();
    
    //move position of map to entry
    $(".addlocationsEntry").click(function(){
        var id = this.id;
        var val = $("#"+id+" input").val();
        var obj = JSON.parse(val);
        
        setView(obj[0].lat, obj[0].lon);
    });
    
    //dragable
    $( "#addedlocationslist" ).sortable({
      placeholder: "addlocationsEntry"
    });
    $( "#addedlocationslist" ).disableSelection();
}

function setView(lat, lon){
    map.setView([lat, lon], 8);
}

function addDay(data){
    var ajax_url = "http://www.travel.com/days/addajax/";
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
}

function dateiauswahl(evt) {
  var dateien = evt.target.files; // FileList object

  // Auslesen der gespeicherten Dateien durch Schleife
  for (var i = 0, f; f = dateien[i]; i++) {

    // nur Bild-Dateien
    if (!f.type.match('image.*')) {
      continue;
    }

    var reader = new FileReader();

    reader.onload = (function(theFile) {
      return function(e) {
        // erzeuge Thumbnails.
        var vorschau = document.createElement('img');
        vorschau.className = 'vorschau';
        vorschau.src   = e.target.result;
        vorschau.title = theFile.name;
        //document.getElementById('list').insertBefore(vorschau, null);
        
        var file = document.createElement('input');
        file.type = 'file';
        file.name = 'file';
        file.value = e.target.result;
        document.getElementById('list').insertBefore(file, null);
      };
    })(f);

    // Bilder als Data URL auslesen.
    reader.readAsDataURL(f);
  }
}