<?php
session_start();
session_destroy();
 
//@todo - Logoutmeldung ausgeben auf Startseite?
echo "Logout erfolgreich";

header('Location: index.php');
?>