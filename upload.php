<?php
header('Content-Type: text/html; charset=ISO-8859-15'); // importante; especifica el charset de caracteres.

// importante incluir al principio de cada una, lo de las funciones
include_once("../../configuracion/config.php"); // funciones de configuración
include_once("../../funciones/funciones.php"); // funciones varias de conexión a base de datos, etc.

// A) Paso la fotografía a la carpeta upload
$ds = "/";  //1 
$storeFolder = 'upload';   //2 
if (!empty($_FILES)) { 
	$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION); // Obtiene la extensión
    $tempFile = $_FILES['file']['tmp_name'];          // Fichero temporal          
    $targetPath = dirname( __FILE__ ). $ds. $storeFolder . $ds;  //Directorio donde se almacenará
    $targetFile =  $targetPath.$_POST["idAlFoto"].'.'.$ext;  //Ruta completa y nombre de lfichero a guardar.
    move_uploaded_file($tempFile,$targetFile); //6     
}   

// B) Convertir la foto en un archivo binario a guardar en MySQL

?>



