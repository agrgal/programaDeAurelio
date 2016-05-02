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

if ($particular["fechadatepicker"][0]=="") { // caso que no hay ninguna fecha particular
	$select='<optgroup label="Estandard">
	<option id="now" value="'.fechaMySQL2DatePicker(date("Y-m-d H:i:s")).'" selected>La de hoy</option>
    <option id="last" value="'.$_POST["fechaLast"].' ">La última que escribí</option>';
    
} else { // en caso que si lo haya
	
	$select='<optgroup label="Estandard">
	<option id="now" value="'.fechaMySQL2DatePicker(date("Y-m-d H:i:s")).'">La de hoy</option>';
	
	if ($_POST["last"]=="last") {
		$select.='<option id="last" value="'.$particular["fechadatepicker"][0].'" selected>La última que escribí</option>';
	} else {
		$select.='<option id="last" value="'.$particular["fechadatepicker"][0].' ">La última que escribí</option>';
	}
}

$select.= '</optgroup><optgroup label="Concreta">';

$seleccionado = NULL;
foreach ($global["fechadatepicker"] as $clave => $valor) {
	// Si encuentra el valor en el array de fechas particulares
	if (in_array($valor,$particular["fechadatepicker"])) {
		// Si el valor está en el otro array
		$disabled = NULL;
		if ($valor==$_POST["fechaIni"] && $_POST["last"]!="last") { $disabled="selected"; } // Si además coincide con la fecha inicial lo preselecciona
	} else {
		$disabled="disabled";
	}
	$select.='<option value="'.$valor.'" id="'.$global["fechaMySQL"][$clave].'" '.$disabled.'>'.$valor.'</option>';
}

$select.='</optgroup>'; 

$datos=array("fechaIni"=>$_POST["fechaIni"],"last"=>$_POST["last"],"fechalast"=>$_POST["fechaLast"],"seleccion"=>$select);

echo iconv("UTF-8","ISO-8859-15",json_encode($datos)); 

// echo "Ninguno";
 
?>



