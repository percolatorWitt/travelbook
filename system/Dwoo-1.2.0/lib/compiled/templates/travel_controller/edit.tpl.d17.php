<?php
/* template head */
/* end template head */ ob_start(); /* template body */ ?><!-- grundsaetzliches -->
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

<script type="text/javascript">
    var locations = [
            <?php 
$_loop0_data = (isset($this->scope["locations"]) ? $this->scope["locations"] : null);
if ($this->isTraversable($_loop0_data) == true)
{
	foreach ($_loop0_data as $tmp_key => $this->scope["-loop-"])
	{
		$_loop0_scope = $this->setScope(array("-loop-"));
/* -- loop start output */
?>
                ["<?php echo $this->scope["text"];?>", <?php echo $this->scope["lat"];?>, <?php echo $this->scope["lon"];?>],
            <?php 
/* -- loop end output */
		$this->setScope($_loop0_scope, true);
	}
}
?>  
    ];
    
</script>

<!-- eigene Skripte -->
<script type="text/javascript" src="/public/js/travel/edit.js"></script>

<style type="text/css">
#accordion{
    padding-bottom: 1em;
}    
    
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

<form method="get" action="/travel/add">
    <button id="travelsave-top"class="btn btn-primary" type="submit">Save it.</button>
    <div id="accordion">
        <h3>Whats your travelname und when you traveled? Where were you are?</h3>
        <div>
            <label for="name">Name</label>
            <input id="name" name="name" type="input" placeholder="Name of your travel." value="<?php echo $this->scope["name"];?>"/>

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

            <!--<form method="get" action="/user/add/">-->

                <div id="addlocations">
                    <div id="basicMap"></div>
                    <div id="addedlocations">
                        <h3>Added locations</h3>
                        <ul id="addedlocationslist">
                            <?php 
$_loop1_data = (isset($this->scope["locations"]) ? $this->scope["locations"] : null);
if ($this->isTraversable($_loop1_data) == true)
{
	foreach ($_loop1_data as $tmp_key => $this->scope["-loop-"])
	{
		$_loop1_scope = $this->setScope(array("-loop-"));
/* -- loop start output */
?>
                            <li id="place_<?php echo $this->scope["id"];?>" class="addlocationsEntry ui-sortable-handle"><span><?php echo $this->scope["text"];?></span><input name="places[place_id<?php echo $this->scope["id"];?>]" value="[{ &quot;lat&quot;: &quot;<?php echo $this->scope["lat"];?>&quot;, &quot;lon&quot;: &quot;<?php echo $this->scope["lon"];?>&quot;, &quot;text&quot;: &quot;Berlin, Deutschland&quot; }]" type="hidden"><span id="<?php echo $this->scope["id"];?>" class="delete" title="delete" onclick="remove('place_<?php echo $this->scope["id"];?>');"></span></li>
                            <?php 
/* -- loop end output */
		$this->setScope($_loop1_scope, true);
	}
}
?>

                        </ul>
                    </div>
                </div>
                <div class="clear"></div>

            <!--</form>-->

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
                    <p><?php echo $this->scope["description"];?></p>
                  </div>
                </div>
                <div class="row">
                  <button class="btn btn-primary" type="submit">Save Text</button>
                </div>

            </div>
        </div>
        <h3>Show your photos.</h3>
        <div>
            <p>Wählen Sie Dateien aus. Von Bildern werden Vorschaubilder erzeugt.</p>
            <div id="fileupload">
                <div id="fileuploadcontrol">
                    <input type="file" id="files" name="files[]" multiple />
                </div>
                <div id="fileuploadlist">
                    <output id="list"></output>
                </div>
            </div>
        </div>
    </div>
    <button id="travelsave-bottom" class="btn btn-primary" type="submit">Save it.</button>
</form>
<script>
<!-- Initialize Quill editor -->
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
   $.ajax({
    type: "POST",
    url: "/travel/editajax/<?php echo $this->scope["travel_id"];?>",
    data: $(form).serialize(),
    success: console.log("juhu")
    });
  
  //endsubmi
  
   // No back end to actually submit to!
  //alert('Open the console to see the submit data!')
  //return false;
};

</script><?php  /* end template body */
return $this->buffer . ob_get_clean();
?>