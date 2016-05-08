<?php
$enlace=$_GET['fichero'];
$ruta=$_GET['ruta'];
$nombre = $_GET['nombre'];
// $enlace='.'.chr(47).$ruta.chr(47).$nombre;
header("Content-Disposition: attachment; filename=\"".$nombre."\""); // Aquí va el nombre del fichero
header ("Content-Type: application/pdf");
// header ("Content-Type: application/octet-stream");
// header ("Content-Type: binary");
header ("Content-Length: ".filesize($ruta.$enlace));
readfile($ruta.$enlace); // Esto es la lectura
unlink($ruta.$enlace); // Borrado de fichero */
?>
