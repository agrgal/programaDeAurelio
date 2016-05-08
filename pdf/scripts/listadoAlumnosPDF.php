<?php
// header('Content-Type: text/html; charset=ISO-8859-15'); // importante; especifica el charset de caracteres.

// importante incluir al principio de cada una, lo de las funciones
include_once("../../configuracion/config.php"); // funciones de configuración
include_once("../../funciones/funciones.php"); // funciones varias de conexión a base de datos, etc.

// Incluir biblioteca mpdf

include("../../mpdf/mpdf.php");

// Construcción del html

$html = '<h1>Hola, de nuevo</h1>';

// Generar PDF

$mpdf=new mPDF('c'); 
$mpdf->WriteHTML($html);
$mpdf->Output('../../pdf/listadoAlumnos.pdf','F');
header("Location: ../../pdf/scripts/descargaficheropdf.php?fichero=listadoAlumnos.pdf&ruta=../../pdf/&nombre=listaAlumNOS.pdf");	
exit;
?>



