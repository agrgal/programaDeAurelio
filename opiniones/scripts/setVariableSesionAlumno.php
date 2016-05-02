<?php
header('Content-Type: text/html; charset=ISO-8859-15'); // importante; especifica el charset de caracteres.

// importante incluir al principio de cada una, lo de las funciones
include_once("../../configuracion/config.php"); // funciones de configuración
include_once("../../funciones/funciones.php"); // funciones varias de conexión a base de datos, etc.

session_start();

$_SESSION["idalumno"] = $_POST["idalumno"];

echo "He estado en lo de la variable de sesión ".$_POST["idalumno"]." Y la asignación es la: ".$_POST["idasignacion"];
 
?>



