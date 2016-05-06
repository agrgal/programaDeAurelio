<?php
header('Content-Type: text/html; charset=ISO-8859-15'); // importante; especifica el charset de caracteres.

// importante incluir al principio de cada una, lo de las funciones
include_once("../../configuracion/config.php"); // funciones de configuración
include_once("../../funciones/funciones.php"); // funciones varias de conexión a base de datos, etc.


if ($_POST["lee"]>0) { 
	 if (file_exists("./upload/".$_POST["lee"].".png") || file_exists("../../upload/".$_POST["lee"].".png")) {
		 unlink("../../upload/".$_POST["lee"].".png");
		 echo "Borrado del alumno con ID: ".$_POST["lee"];
	 } else if (file_exists("./upload/".$_POST["lee"].".jpg") || file_exists("../../upload/".$_POST["lee"].".jpg")) {
		 unlink("../../upload/".$_POST["lee"].".jpg");
		 echo "Borrado del alumno con ID: ".$_POST["lee"];
	 } else {
		 echo "No se ha podido borrar"; 
	 }
} // FIN DEL IF 
   
?>



