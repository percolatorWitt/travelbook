<!-- grundsaetzliches -->
<script src="https://code.jquery.com/jquery-latest.js"></script>

<!-- karten -->
<link rel="stylesheet" href="/public/js/leaflet/leaflet.css" />
<script type="text/javascript" src="/public/js/leaflet/leaflet.js"></script>
<script type="text/javascript" src="https://www.openlayers.org/api/OpenLayers.js"></script>
<script type="text/javascript" src="https://www.openstreetmap.org/openlayers/OpenStreetMap.js"></script>

<!-- Effekte -->
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"/>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<link href="https://cdn.quilljs.com/1.1.3/quill.snow.css" rel="stylesheet"/>
<script src="https://cdn.quilljs.com/1.1.3/quill.js"></script>

<!-- eigene Skripte -->
<script type="text/javascript" src="/public/js/add.js"></script>

<style type="text/css">
#divstartdate{
    margin-right: 1em;
}
#addlocations{
    padding-top: 1em;
    padding-bottom: 1em;
    width: 100%
}
#basicMap {
    border: 5px solid #324290;
    width: 100%;
    /*width: 60%;*/
    height: 350px;
    margin: 0 6px 0 0;
    padding-bottom: 3px;
    padding-top: 3px;
    position: relative;
    top: 0px;
    float: left;
}
#addedlocations{
    background-color: #ffffff;
    border: 5px solid #324290;
    border-radius: 3px;
    width: 38%;
    margin-left: 60%;
    min-height: 350px;
    padding: 3px;
    display: none;
}
#addedlocations ul{
    padding: 0;
}
#addedlocations li{
    background-color: #ABB9FC;
    cursor: pointer;
    list-style: none;
    margin-bottom: 2px;
    padding-bottom: 0.2em;
    padding-left: 1em;
    padding-top: 0.2em;
}
.addlocationsEntry:hover {
    font-weight: bold;
}
#selectLocation{
    padding-left:1em;
}
#selectLocation li{
    cursor: pointer;
    list-style: none;
}
#selectLocation li:hover {
    background-color: grey;
}


.clear{
    clear: both;
}
.floatleft{
    float: left;
}
.delete {
    display: block;
    float: right;
    height: 20px;
    width: 27px;
    background-image: url("/public/img/multiply-1.svg");
    background-repeat: no-repeat;
    background-position: right bottom;
}
.delete:hover {
    background-image: url("/public/img/multiply-1_gray.svg");
}

</style>

<style type="text/css">
#form-container {
  width: 100%;
}

.row {
  margin-top: 15px;
}
.row.form-group {
  padding-left: 15px;
  padding-right: 15px;
}
.btn {
  margin-left: 15px;
}

.change-link {
  background-color: #000;
  border-bottom-left-radius: 6px;
  border-bottom-right-radius: 6px;
  bottom: 0;
  color: #fff;
  opacity: 0.8;
  padding: 4px;
  position: absolute;
  text-align: center;
  width: 150px;
}
.change-link:hover {
  color: #fff;
  text-decoration: none;
}

img {
  width: 150px;
}

#editor-container {
  height: 130px;
}
#fileuploadcontrol{
    float: left;
    height: 80%;
}
#fileuploadlist{
 
}

#fileuploadlist img{
    width: 150px;
    float: left;
    margin: 5px;
}
label{
    width: 70px;
    display: inline-block;
}
#travelsave-top,
#travelsave-bottom{
    background-color: #F00;
    color: #fff;
    font-size: 1em;
    font-weight: bold;
    line-height: 1.3;
    margin-left: 0;
    padding: 5px;
}

#travelsave-top:hover,
#travelsave-bottom:hover{
    cursor: pointer;
}
</style>

<h1>Add Travel</h1>

<form  id="addform" method="get" action="/travel/add">
    <button id="travelsave-top"class="btn btn-primary" type="submit">Save it.</button>
    <div id="accordion">
        
        <h3>Whats your travelname und when you traveled? Where were you are?</h3>
        <div>
            <label for="name">Name</label>
            <input id="name" name="name" type="input" placeholder="Name of your travel."/>

            <p>
                <div id="divstartdate" class="floatleft">
                    <label labelfor="startdate">Start date</label>
                    <input type="text" id="startdate" name="startdate" placeholder="Input a start date" required/>
                </div>
                <div>
                    <label labelfor="enddate">End date</label>
                    <input type="text" id="enddate" name="enddate" placeholder="Input an end date" required/>
                </div>
            </p>
            <div class="clear"></div>

            <div id="addlocations">
                <div id="basicMap"></div>
                <div id="addedlocations">
                    <h3>Added locations</h3>
                    <ul id="addedlocationslist"></ul>
                </div>
            </div>
            <div class="clear"></div>

            <br/>
            <div id="searchlocation">
                <input id="suche" type="text" name="suche" placeholder="Add a location"/><br/>
                <div id="daten">
                    <ul id="selectLocation"></ul>
                </div>
            </div>
        </div>
        
        <h3>Write something.</h3>
        <div>
            <div id="form-container" class="container">

                <div class="row form-group">
                        <!--<label for="about">About me</label>-->
                    <input name="about" type="hidden"/>
                    <div id="editor-container">
                        <p></p>
                    </div>
                </div>
                <div class="row">
                    <button class="btn btn-primary" type="submit">Save Text</button>
                </div>

            </div>
        </div>
        
        <h3>Show your photos.</h3>
        <div>
            <p>Please save your trip first.</p>
        </div>
    </div>
    <button id="travelsave-bottom" class="btn btn-primary" type="submit">Save it.</button>
</form>

<script type="text/javascript">
//Initialize Quill editor
var quill = new Quill('#editor-container', {
  modules: {
    toolbar: [
      ['bold', 'italic'],
      ['link', 'blockquote'],
      [{ list: 'ordered' }, { list: 'bullet' }],
      [{ 'color': [] }, { 'background': [] }], 
      ['clean']  
    ]
  },
  placeholder: 'Describe you expressions.',
  theme: 'snow'
});

var form = document.querySelector('form');
form.onsubmit = function() {
  // Populate hidden form on submit
  var about = document.querySelector('input[name=about]');
  about.value = JSON.stringify(quill.getContents());
  
  //console.log("Submitted", $(form).serialize(), $(form).serializeArray());
  
  //submit
   /*$.ajax({
    type: "POST",
    url: "/travel/addajax/",
    data: $(form).serialize(),
    success: function(result){
        console.log(result);
        //window.location.href = "/travel/edit/"+result;
    }
    });*/
  
  //endsubmi
  
  
  
  // No back end to actually submit to!
  //alert('Open the console to see the submit data!')
  //return false;
};

 $('#addform').submit(function() { 
        //$(this).ajaxSubmit(options);  			
        // always return false to prevent standard browser submit and page navigation 
  var about = document.querySelector('input[name=about]');
  about.value = JSON.stringify(quill.getContents());      

   $.ajax({
    type: "POST",
    url: "/travel/addajax/",
    data: $('#addform').serialize(),
    success: function(result){
        window.location.href = "/travel/edit/"+result;
    }
    });
        
        
        return false; 
    });

</script>