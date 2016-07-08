<?php
header('Content-Type: text/html; charset=ISO-8859-15'); // importante; especifica el charset de caracteres.

// importante incluir al principio de cada una, lo de las funciones
include_once("../../configuracion/config.php"); // funciones de configuración
include_once("../../funciones/funciones.php"); // funciones varias de conexión a base de datos, etc.

// Incluyo además las clases que se van a usar
include_once("../../clases/class.micalendario.php"); //clase mi calendario que presenta fechas formateadas
include_once("../../clases/class.profesores.php"); //clase que recupera datos de profesores

$calendario= New micalendario(); // variable de calendario.
$profesorado = New profesores(); //variable de la clase profesores

echo "He llegado aquí".$_POST["nuevo"].$_POST["repite"];

// Comprueba si nuevo es válido
if (!filter_var($_POST["nuevo"], FILTER_VALIDATE_EMAIL) === false) {
  if (trim($_POST["nuevo"])==trim($_POST["repite"]) and strlen(trim($_POST["nuevo"])<=200)) {
	  echo ("Cambio de email");
  } else {
	  echo ("Los email no coinciden");
  }
} else {
  echo("NO Válido");
}
?>
