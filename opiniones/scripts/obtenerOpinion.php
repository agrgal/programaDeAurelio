<?php
header('Content-Type: text/html; charset=ISO-8859-15'); // importante; especifica el charset de caracteres.

// importante incluir al principio de cada una, lo de las funciones
include_once("../../configuracion/config.php"); // funciones de configuración
include_once("../../funciones/funciones.php"); // funciones varias de conexión a base de datos, etc.

// Incluyo además las clases que se van a usar
// include_once("../../clases/class.micalendario.php"); //clase mi calendario que presenta fechas formateadas
include_once("../../clases/class.formulario.php"); //clase que recupera datos de formularios
// include_once("../../clases/class.profesores.php"); //clase que recupera datos de profesores
// include_once("../../clases/class.cursos.php"); //clase que recupera datos de cursos
include_once("../../clases/class.opiniones.php"); //clase que recupera datos de las opiniones

// $calendario= New micalendario(); // variable de calendario.
$formulario = New formulario(); // variable de la clase formulario
// $profesores = New profesores(); // variable de la clase profesores
// $curso = New misCursos(); // variable de la clase profesores
$opiniones = New misOpiniones(); // variable de la clase opiniones

// session_start(); //activo variables de sesion

// echo $_POST["fecha"]." / ".$_POST["alumno"]
//  ." / ".$_POST["idasignacion"]." / ".$_POST["items"]." / ".$_POST["observaciones"];

// $formulario->nombreTabla="tb_opiniones"; // Lo primero que hay que nombrar la tabla
// $formulario->nombreCampos(); // obtener el nombre de los campos
// $formulario->nombreClavePrimaria();
// $nC = $formulario->arrayNombreCampos; // y lo graba en la variable $nC
// $valoresclaveprimaria = $formulario->datosPK(); // Obtiene los valores de clave primaria.


$valorid = $opiniones->checkOpinion($_POST["fecha"],$_POST["idasignacion"],$_POST["alumno"]); // ¿Cuánto vale el id, si es que existe?

if ($valorid>0) {
	// echo $valorid;
	$link=Conectarse(); // y me conecto. //dependiendo del tipo recupero uno u otro.
	$Sql ='SELECT observaciones, items FROM tb_opiniones WHERE id="'.$valorid.'"';
	// $Sql = sprintf($Sql, mysqli_real_escape_string($link,$valorid)); // Seguridad que evita los ataques SQL Injection  	
	// echo $Sql;
	$result=mysqli_query($link,$Sql); // ejecuta la cadena sql y almacena el resultado el $result
	$row=mysqli_fetch_array($result); // no puede ser nulo porque la bd no lo permite
	$datos=array("sql"=>$Sql,"items"=>$row["items"]
	            ,"observaciones"=>html_entity_decode($row["observaciones"]));
	// Muy importante recuperar las observaciones como UTF8 para pasarlas al array. Si no, no sale...
	// IMPORTANTE: pasar de HTML entities a decodificacion HTML...
	// http://php.net/manual/en/function.html-entity-decode.php
	$datos_json=json_encode($datos); // codifica como json...
	mysqli_free_result($result); 
	mysqli_close($link); 
	echo $datos_json;	
}
  
?>



