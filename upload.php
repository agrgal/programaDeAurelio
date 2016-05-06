<?php
header('Content-Type: text/html; charset=ISO-8859-15'); // importante; especifica el charset de caracteres.

// importante incluir al principio de cada una, lo de las funciones
include_once("./configuracion/config.php"); // funciones de configuración
include_once("./funciones/funciones.php"); // funciones varias de conexión a base de datos, etc.

// A) Paso la fotografía a la carpeta upload
$ds = "/";  //1 
$storeFolder = 'upload';   //2 
if (!empty($_FILES)) { 
	$targetPath = dirname( __FILE__ ). $ds. $storeFolder . $ds;  //Directorio donde se almacenará
	// Si existe antes una foto con ese nombre, la borra
	if (file_exists($targetPath.$_POST["idAlFoto"].".png")) { // Comprueba un tipo, y si existe, lo borrra
		unlink($targetPath.$_POST["idAlFoto"].".png");
	} else if (file_exists($targetPath.$_POST["idAlFoto"].".jpg")) { // Comprueba el otro tipo, y si existe, lo borrra
		unlink($targetPath.$_POST["idAlFoto"].".jpg");
	}
	// *************************************************	
	$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION); // Obtiene la extensión
	$ext = strtolower($ext); // por si acaso, pasarlo a minúsculas.
    $tempFile = $_FILES['file']['tmp_name'];          // Fichero temporal 
    $targetFile =  $targetPath.$_POST["idAlFoto"].'.'.$ext;  //Ruta completa y nombre del fichero a guardar.
    move_uploaded_file($tempFile,$targetFile); //6     
}  


?>



