<?php
header('Content-Type: text/html; charset=ISO-8859-15'); // importante; especifica el charset de caracteres.

session_start();

// Destruir todas las variables de sesión.
$_SESSION = array();

// No estoy usando cookies

// Finalmente, destruir la sesión.
session_destroy();

?>
