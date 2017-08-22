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
            $("#password .hint").removeClass("hidden");
            
            return false;
        }
    }

    //validate new pasword
    if( $('input[name=newpassword]').val() !== $('input[name=validatepassword]').val() ){
        $("#password .hint").text("New password and validate password has to be equal.");
        $("#password .hint").removeClass("hidden");
        
        return false;
    } 

    //get data

    var form = $("form").serialize();

    $.ajax({
        type: "POST",
        url: "/user/settingsajax",
        data: form,
        success: function(response){
            var jsonObj = JSON.parse(response);

            //console.log(jsonObj);
            //set values
    /*        $("#pseudonym").val(jsonObj.gravatarData.displayName);
            $("#displayname").val("nickname");

            //mark changes for user
            $("#pseudonym").addClass("notsaved");
            $("#displayname").addClass("notsaved");


            //saved data are not new
            $("#formsettings input").removeClass("notsaved");
            $("#formsettings select").removeClass("notsaved");*/
        },
        dataType: 'html'

    });

    return false;
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