<?php
/* template head */
/* end template head */ ob_start(); /* template body */ ?><h1>Travels</h1>

<ul id="travellist">
    <?php 
$_loop0_data = (isset($this->scope["travels"]) ? $this->scope["travels"] : null);
if ($this->isTraversable($_loop0_data) == true)
{
	foreach ($_loop0_data as $tmp_key => $this->scope["-loop-"])
	{
		$_loop0_scope = $this->setScope(array("-loop-"));
/* -- loop start output */
?>
    <li><a href="/travel/edit/<?php echo $this->scope["travel_id"];?>" target="_self">
            <strong><?php echo $this->scope["name"];?></strong> - <?php if ((isset($this->scope["startdate"]) ? $this->scope["startdate"] : null)) {

echo $this->scope["startdate"];?> - <?php echo $this->scope["enddate"];

}?>

            <br/><?php echo $this->scope["description"];?></a></li>
    <?php 
/* -- loop end output */
		$this->setScope($_loop0_scope, true);
	}
}
?>

</ul><?php  /* end template body */
return $this->buffer . ob_get_clean();
?>