<html>
    <head>
        <link rel="stylesheet" href="/public/css/general.css"/>
    </head>
    <body>
        <div id="content">
            <div id="mainmenu">
                <div id="center">
                    <ul>
                        <li><a href="/user/{$user_id}">{$userdata['vorname']} {$userdata['nachname']}</a></li>
                        <li id="settings"><a href="/user/settings/">Settings</a></li>
                        <li><a href="/user/travels/">Travels</a></li>
                        <li><a href="/travel/add/">add Travel</a></li>
                        <li><a href="/user/logout">logout</a></li>
                    </ul>
                </div>
            </div>
            
            {include $template}
        </div>
    </body>
</html>