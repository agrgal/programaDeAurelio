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

$opiniones->listaFechasOpinion($_POST["idasignacion"],$_POST["alumno"]);
$particular["fechaMySQL"] = $opiniones->listadoDeFechasOpinion["fechaMySQL"];
$particular["fechadatepicker"] = $opiniones->listadoDeFechasOpinion["fechaDatepicker"];

$ii = 0; // No hay elegidos

$disabled = NULL; 

// La segunda parte del Select
$select= '</optgroup><optgroup label="Concreta">';

foreach ($global["fechadatepicker"] as $clave => $valor) {
	// Si encuentra el valor en el array de fechas particulares
	$elegido=NULL;
	if (in_array($valor,$particular["fechadatepicker"])) {
		// Si el valor está en el otro array
		$disabled = NULL; 
		if ($valor==$_POST["fechaSelectedDP"]) { 
			$elegido="selected"; 
			$ii++; //Elijo y sumo uno
		} 
	} else {
		$disabled="disabled";
	}
	$select.='<option value="'.$valor.'" mysql="'.$global["fechaMySQL"][$clave].'" id="'.$global["fechaMySQL"][$clave].'" '.$disabled.' '.$elegido.'>'.$valor.'</option>';
}

$select.='</optgroup>'; 

// Primera parte del select
if ($ii>0) { $elegido="selected";} // Si no se seleccionó todavía, ahora sí, y es la fecha de hoy...

$selecthead='<optgroup label="Estandard">
<option id="now" mysql="'.date("Y-m-d").'" value="'.fechaMySQL2DatePicker(date("Y-m-d H:i:s")).' '.$elegido.'">La de hoy</option>';

$select = $selecthead.$select;

$datos=array("fechaSelectedMySQL"=>$_POST["fechaSelectedMySQL"],"fechaSelectedDP"=>$_POST["fechaSelectedDP"],"seleccion"=>$select);

echo iconv("UTF-8","ISO-8859-15",json_encode($datos)); 

// echo "Ninguno";
 
?>



