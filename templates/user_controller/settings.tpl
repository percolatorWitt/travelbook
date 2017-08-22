<!-- grundsaetzliches -->
<script src="https://code.jquery.com/jquery-latest.js"></script>

<!-- eigene Skripte -->
<script type="text/javascript" src="/public/js/settings.js"></script>

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
            <label labelfor="nickname" title="selbst eingeben oder von Gravatar">nickname*</label>
            <input id="nickname" name="nickname" type="input" value="{$nickname}" required="yes"/>
        </span>
        <span class="settings">
            <label labelfor="first_name">first name</label>
            <input id="first_name" name="first_name" type="input" value="{$first_name}" required/>
        </span>
        <span class="settings">
            <label labelfor="surname">surname</label>
            <input id="surname" name="surname" type="input" value="{$surname}" required/>
        </span>
        <span class="settings">
            <label labelfor="displayname">select display name</label>
            <select id="displayname">
                <option value="firstname.surname" selected/>first name surname</option>
                <option value="nickname"/>nickname</option>    
                <option value="first_name"/>first name</option>
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