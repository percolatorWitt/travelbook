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

<script type="text/javascript" src="/public/js/jquery.form.min.js"></script>


<script type="text/javascript">
    var locations = [
            {loop $locations}
                ["{$text}", {$lat}, {$lon}],
            {/loop}  
    ];
    
</script>

<link href="https://cdn.quilljs.com/1.1.3/quill.snow.css" rel="stylesheet"/>
<script src="https://cdn.quilljs.com/1.1.3/quill.js"></script>

<!-- eigene Skripte -->
<script type="text/javascript" src="/public/js/travel/edit.js"></script>

<h1>Edit Travel</h1>

<form id="editform" action="/travel/editajax/{$travel_id}" method="post" enctype="multipart/form-data">
    <button id="travelsave-top"class="btn btn-primary" type="submit">Save it.</button>
    <div id="accordion">
        
        <h3>Whats your travelname und when you traveled? Where were you are?</h3>
        <div>
            <label for="name">Name</label>
            <input id="name" name="name" type="input" placeholder="Name of your travel." value="{$name}"/>

            <p>
                <div id="divstartdate" class="floatleft">
                    <label labelfor="startdate">Start date</label>
                    <input type="text" id="startdate" name="startdate" placeholder="Input a start date" value="{$startdate}" required/>
                </div>
                <div>
                    <label labelfor="enddate">End date</label>
                    <input type="text" id="enddate" name="enddate" placeholder="Input an end date"  value="{$enddate}" required/>
                </div>
            </p>
            <div class="clear"></div>

            <div id="addlocations">
                <div id="basicMap"></div>
                <div id="addedlocations">
                    <h3>Added locations</h3>
                    <ul id="addedlocationslist">
                        {loop $locations}
                        <li id="place_{$id}" class="addlocationsEntry ui-sortable-handle">
                            <span>{$text}</span>
                            <input name="places[{$id}]" value="[{ &quot;lat&quot;: &quot;{$lat}&quot;, &quot;lon&quot;: &quot;{$lon}&quot;, &quot;text&quot;: &quot;Berlin, Deutschland&quot; }]" type="hidden">
                            <span id="{$id}" class="delete" title="delete" onclick="remove('place_{$id}');"></span>
                        </li>
                        {/loop}
                    </ul>
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
                    <p>{$description}</p>
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
            <div id="pictures">
                {loop $pictures}
                <img src="{$filename}" alt="picture" />
                {/loop}
            </div>
            <div id="fileupload">
                <input name="FileInput" id="FileInput" type="file" />
                <input type="submit"  id="submit-btn" value="Upload" />
                <img src="images/ajax-loader.gif" id="loading-img" style="display:none;" alt="Please Wait"/>
                <div id="progressbox" ><div id="progressbar"></div ><div id="statustxt">0%</div></div>
                <div id="output"></div>
            </div>
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
    //var fd = new FormData(document.getElementById("editform"));
    //console.log("Submitted", $(form).serialize(), $(form).serializeArray());
  
    //submit
    $.ajax({
        type: "POST",
        url: "/travel/editajax/{$travel_id}",
        data: $(form).serialize(),
        enctype: 'multipart/form-data',
        success: function(response){
            console.log(response);
        }
    });
  
    //endsubmi

     // No back end to actually submit to!
    //alert('Open the console to see the submit data!')
    //return false;
};

</script>
<script type="text/javascript">
$(document).ready(function() { 
    var options = { 
        target:   '#output',   // target element(s) to be updated with server response 
        beforeSubmit:  beforeSubmit,  // pre-submit callback 
        success:       afterSuccess,  // post-submit callback 
        uploadProgress: OnProgress, //upload progress callback 
        resetForm: true        // reset the form after successful submit 
    };

    $('#editform').submit(function() { 
        $(this).ajaxSubmit(options);  			
        // always return false to prevent standard browser submit and page navigation 
        return false; 
    });
		
    //function after succesful file upload (when server response)
    function afterSuccess(){
	$('#submit-btn').show(); //hide submit button
	$('#loading-img').hide(); //hide submit button
	$('#progressbox').delay( 1000 ).fadeOut(); //hide progress bar
    }

    //function to check file size before uploading.
    function beforeSubmit(){
        //check whether browser fully supports all File API
        if (window.File && window.FileReader && window.FileList && window.Blob){

                    if( !$('#FileInput').val()) //check empty input filed
                    {
                            $("#output").html("Are you kidding me?");
                            return false
                    }

                    var fsize = $('#FileInput')[0].files[0].size; //get file size
                    var ftype = $('#FileInput')[0].files[0].type; // get file type


                    //allow file types 
            switch(ftype){
                case 'image/png': 
                            case 'image/gif': 
                            case 'image/jpeg': 
                            case 'image/pjpeg':
                            case 'text/plain':
                            case 'text/html':
                            case 'application/x-zip-compressed':
                            case 'application/pdf':
                            case 'application/msword':
                            case 'application/vnd.ms-excel':
                            case 'video/mp4':
                    break;
                default:
                    $("#output").html("<b>"+ftype+"</b> Unsupported file type!");
                                    return false
            }

            //Allowed file size is less than 5 MB (1048576)
            if(fsize>5242880){
                $("#output").html("<b>"+bytesToSize(fsize) +"</b> Too big file! <br />File is too big, it should be less than 5 MB.");
                return false;
            }

            $('#submit-btn').hide(); //hide submit button
            $('#loading-img').show(); //hide submit button
            $("#output").html("");
        } else {
            //Output error to older unsupported browsers that doesn't support HTML5 File API
            $("#output").html("Please upgrade your browser, because your current browser lacks some new features we need!");
            return false;
        }
    }

    //progress bar function
    function OnProgress(event, position, total, percentComplete){
        //Progress bar
        $('#progressbox').show();
        $('#progressbar').width(percentComplete + '%') //update progressbar percent complete
        $('#statustxt').html(percentComplete + '%'); //update status text
        if(percentComplete>50){
            $('#statustxt').css('color','#000'); //change status text to white after 50%
        }
    }

    //function to format bites bit.ly/19yoIPO
    function bytesToSize(bytes) {
        var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
        if (bytes == 0) return '0 Bytes';
        var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
        return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
    }
});
</script>