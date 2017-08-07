<!-- grundsaetzliches -->
<script src="https://code.jquery.com/jquery-latest.js"></script>

<script type="text/javascript">
    function setGravatar(evt) {
        //aktiv Daten setzen
        var gravatar_id = $("#emailmd5").text();
        //alert(gravatar_id);
        //wenn nicht aktiv Felder leeren
        
        //getGravatarInformation(gravatar_id);
        //getGravatarInformation('205e460b479e2e5b48aec07710c08d50');
        //https://www.gravatar.com/205e460b479e2e5b48aec07710c08d50
        
        $.ajax({
            type: "POST",
            url: "/user/settingsajax",
            data: { setgrvatar : 1},
            success: function(response){
                var jsonObj = JSON.parse(response);

                //console.log(jsonObj);
                //set values
                $("#pseudonym").val(jsonObj.gravatarData.displayName);
                $("#displayname").val("nickname");
        
                //mark changes for user
                $("#pseudonym").addClass("notsaved");
                $("#displayname").addClass("notsaved");
            },
            dataType: 'html'
            
        });
    };
    

    function saveSettings() {
        //clear hints
        $(".hint").text("");
        
        //check if old password is empty
        if( ( $('input[name=newpassword]').val() !== "" ) || ( $('input[name=validatepassword]').val() !== "" ) ){
            if( $('input[name=oldpassword]').val() === "" ){
                $("#password .hint").text("Please set the old password.");

                return false;
            }
        }
        
        //validate new pasword
        if( $('input[name=newpassword]').val() !== $('input[name=validatepassword]').val() ){
            $("#password .hint").text("New password and validate password has to be equal.");
            
            return false;
        } 
        
        //get data
        var formData = {
            'nickname'  : $('input[name=nickname]').val(),
            'vorname'   : $('input[name=vorname]').val(),
            'nachname'  : $('input[name=nachname]').val(),
            'displayname': $("#displayname").val(),
            'oldpassword'  : $('input[name=oldpassword]').val(),
            'newpassword'  : $('input[name=newpassword]').val(),
            'validatepassword'  : $('input[name=validatepassword]').val(),
            
        };
        console.log(formData);
        /*$.ajax({
            type: "POST",
            url: "/user/settingsajax",
            data: { setgrvatar : 1},
            success: function(response){
                var jsonObj = JSON.parse(response);

                console.log(jsonObj);
                //set values
                $("#pseudonym").val(jsonObj.gravatarData.displayName);
                $("#displayname").val("nickname");
        
                //mark changes for user
                $("#pseudonym").addClass("notsaved");
                $("#displayname").addClass("notsaved");
        
        
                //saved data are not new
                $("#formsettings input").removeClass("notsaved");
                $("#formsettings select").removeClass("notsaved");
            },
            dataType: 'html'
            
        });*/
        
        return true;
    };

$(function() {
    document.getElementById('inputgravatar').addEventListener('click', setGravatar, false);
    
    //mark changes
    $("#formsettings input").change(function(){
        $(this).addClass("notsaved");
    });
    $("#formsettings select").change(function(){
        $(this).addClass("notsaved");
    });
   
        
    $("#formsettings").submit(function(){
        var returnavalue = saveSettings();
        
        return returnavalue;
    });
});

</script>

<h1>Settings</h1>

<form id="formsettings" name="settings" method="post" action="/user/settings" autocomplete="on">
    <fieldset>
        <span class="settings">
            <label labelfor="gravatar">gravatar</label>
            <input id="inputgravatar" name="avatar" type="button" value="get data from gravatar" />
        </span>
    </fieldset>

    <fieldset>
        <span class="settings">
            <label labelfor="user" title="selbst eingeben oder von Gravatar">nickname*</label>
            <input id="pseudonym" name="nickname" type="input"/>
        </span>
        <span class="settings">
            <label labelfor="vorname">first name</label>
            <input id="vorname" name="surname" type="input" value="{$surname}" required/>
        </span>
        <span class="settings">
            <label labelfor="nachname">surname</label>
            <input id="nachname" name="name" type="input" value="{$name}" required/>
        </span>
        <span class="settings">
            <label labelfor="displayname">select display name</label>
            <select id="displayname">
                <option value="firstname.surname" selected/>first name surname</option>
                <option value="nickname"/>nickname</option>    
                <option value="firstname"/>first name</option>
                <option value="surname"/>surname</option>
            </select>
            <span class="hint"></span>
        </span>
    </fieldset>

    <fieldset id="password">
        <span class="settings">
            <label labelfor="oldpassword">old password</label>
            <input id="oldpassword" name="oldpassword" type="password"/>
        </span>
        <span class="settings">
            <label labelfor="newpassword">new password</label>
            <input id="newpassword" name="newpassword" type="password"/>
        </span>
        <span class="settings">
            <label labelfor="validatepassword">validate password</label>
            <input id="validatepassword" name="validatepassword" type="password"/>
        </span>
        <span class="hint"></span>
    </fieldset>
    
    <fieldset>
        <span class="settings">
            <label labelfor="email">email*</label>
            <input id="email" name="email" type="email" value="{$email}" required/>
            <span id="emailmd5" class="hidden">{$emailmd5}</span>
        </span>
        <span class="settings">
            <span id="validate">Validated.</span>
        </span>
    </fieldset>
    
    <fieldset>
        <span class="settings">
            <label labelfor="avatar" title="Bild hochladen oder von Gravatar">avatar*</label>
            <input id="filetavatar" name="avatar" type="file" value="{$avatar}" />
            <input id="inputavatar" name="avatar" type="text" value="{$avatar}" />
            <output id="outputavatar"></output>
        </span>
    </fieldset>
    <input id="submit" class="submit" type="submit" value="submit"/>
</form>