<style>
    fieldset{
        margin-bottom: 1em;
        width: 50%;
    }
    .form{
        flaot: left;
        display: block;
        padding-top: 5px;
        padding-bottom: 5px;
    }
    .form label{
        width: 150px;
        display: block;
        float: left;
    }
    .form input{
        margin-left: 50px;
    }
    .form #validate{
        color: #1BC115;
        margin-left: 200px;
    }
    
    .submit{
        background-color: white;
        font-weight: bold;
        padding: 5px;
    }
    
    #loginform{
        float: left;
        padding-bottom: 1em;
    }   
</style>

<h1>Login</h1>

<form action="?login=1" method="post">
    <div id="loginform">
        <fieldset>
            <span class="form">
                <label for="email">E-Mail</label>
                <input id="email" type="email" size="40" maxlength="250" name="email"/>
            </span>
            <span class="form">
                <label for="password">Dein Passwort</label>
                <input id="password" type="password" size="40"  maxlength="250" name="password"/>
            </span>
            <span class="form">
                {$errorMessage}
            </span>
        </fieldset>
        
        <input class="submit" type="submit" value="Login"/>
    </div>
    
    <div id="adabout">
        <img src="" width="400px" height="200px" alt="Werbebild"/>
    </div>
    <p id="promotion">Wo warst du? Was hast du gemacht? Zeig es uns!</p>
</form>