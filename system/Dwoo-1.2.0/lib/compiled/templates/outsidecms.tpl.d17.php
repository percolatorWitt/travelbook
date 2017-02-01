<?php
/* template head */
if (function_exists('Dwoo_Plugin_include')===false)
	$this->getLoader()->loadPlugin('include');
/* end template head */ ob_start(); /* template body */ ?><html>
    <head>
        <link rel="stylesheet" href="/public/css/outside.css"/>
    </head>
    <body>
        <div id="contentoutside">
            
            <?php echo Dwoo_Plugin_include($this, (isset($this->scope["template"]) ? $this->scope["template"] : null), null, null, null, '_root', null);?>

        </div>
    </body>
</html><?php  /* end template body */
return $this->buffer . ob_get_clean();
?>