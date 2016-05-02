<?php
header('Content-Type: text/html; charset=ISO-8859-15'); // importante; especifica el charset de caracteres.

// importante incluir al principio de cada una, lo de las funciones
include_once("../../configuracion/config.php"); // funciones de configuración
include_once("../../funciones/funciones.php"); // funciones varias de conexión a base de datos, etc.

include_once("../../clases/class.opiniones.php"); //clase que recupera datos de las opiniones

$opiniones = New misOpiniones(); // variable de la clase opiniones

$opiniones->listaFechasGlobal($_POST["idasignacion"]);
$global["fechaMySQL"] = $opiniones->listadoDeFechasGlobal["fechaMySQL"];
$global["fechadatepicker"] = $opiniones->listadoDeFechasGlobal["fechaDatepicker"];

foreach ($global["fechadatepicker"] as $clave => $valor) {
	// Si encuentra el valor en el array de fechas particulares
	$elegido=NULL;
	if ($clave=="0") {
		// Si el valor está en el otro array
		$elegido=" selected ";
    }
	$select.='<option value="'.$valor.'" mysql="'.$global["fechaMySQL"][$clave].'" id="'.$global["fechaMySQL"][$clave].'" '.$elegido.'>'.$valor.'</option>';
}

echo $select; 

// echo "Ninguno";
 
?>



