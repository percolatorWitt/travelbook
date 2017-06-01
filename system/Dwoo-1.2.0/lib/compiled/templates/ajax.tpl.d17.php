<?php
/* template head */
if (function_exists('Dwoo_Plugin_include')===false)
	$this->getLoader()->loadPlugin('include');
/* end template head */ ob_start(); /* template body */ ;
echo $this->scope["ajaxdata"];
echo Dwoo_Plugin_include($this, (isset($this->scope["template"]) ? $this->scope["template"] : null), null, null, null, '_root', null);?>

<?php  /* end template body */
return $this->buffer . ob_get_clean();
?>