<?php
/* template head */
if (function_exists('Dwoo_Plugin_include')===false)
	$this->getLoader()->loadPlugin('include');
/* end template head */ ob_start(); /* template body */ ?><html>
    <head>
        <link rel="stylesheet" href="/public/css/general.css"/>
    </head>
    <body>
        <div id="content">
            <div id="mainmenu">
                <div id="center">
                    <ul>
                        <li><a href="/user/<?php echo $this->scope["user_id"];?>"><?php echo $this->scope["userdata"]["vorname"];?> <?php echo $this->scope["userdata"]["nachname"];?></a></li>
                        <li id="settings"><a href="/user/settings/">Settings</a></li>
                        <li><a href="/user/travels/">Travels</a></li>
                        <li><a href="/travel/add/">add Travel</a></li>
                        <li><a href="/user/logout">logout</a></li>
                    </ul>
                </div>
            </div>
            
            <?php echo Dwoo_Plugin_include($this, (isset($this->scope["template"]) ? $this->scope["template"] : null), null, null, null, '_root', null);?>

        </div>
    </body>
</html><?php  /* end template body */
return $this->buffer . ob_get_clean();
?>