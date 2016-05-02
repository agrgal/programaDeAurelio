<?php
header('Content-Type: text/html; charset=ISO-8859-15'); // importante; especifica el charset de caracteres.

// importante incluir al principio de cada una, lo de las funciones
include_once("../../configuracion/config.php"); // funciones de configuración
include_once("../../funciones/funciones.php"); // funciones varias de conexión a base de datos, etc.

// Incluyo además las clases que se van a usar
// include_once("../../clases/class.micalendario.php"); //clase mi calendario que presenta fechas formateadas
include_once("../../clases/class.formulario.php"); //clase que recupera datos de formularios
// include_once("../../clases/class.profesores.php"); //clase que recupera datos de profesores
// include_once("../../clases/class.alumnos.php"); //clase que recupera datos de cursos
include_once("../../clases/class.opinionesGenerales.php"); //clase que recupera datos de las opiniones

// $calendario= New micalendario(); // variable de calendario.
$formulario = New formulario(); // variable de la clase formulario
// $profesores = New profesores(); // variable de la clase profesores
// $alumnos = New misAlumnos(); // variable de la clase profesores
$opinionGeneral = New misOpinionesGenerales(); // variable de la clase opiniones generales

$valorid = $opinionGeneral->checkOpinion($_POST["evaluacion"],$_POST["idasignacion"]); // ¿Cuánto vale el id, si es que existe?

if ($valorid>0) {
	// echo $valorid;
	$link=Conectarse(); // y me conecto. //dependiendo del tipo recupero uno u otro.
	$Sql ='SELECT opinion,actuaciones,mejora FROM tb_opiniongeneral WHERE idopiniongeneral="'.$valorid.'"';
	// $Sql = sprintf($Sql, mysqli_real_escape_string($link,$valorid)); // Seguridad que evita los ataques SQL Injection  	
	// echo $Sql;
	$result=mysqli_query($link,$Sql); // ejecuta la cadena sql y almacena el resultado el $result
	$row=mysqli_fetch_array($result); 	

	$datos=array("sql"=>$Sql
	            ,"opinion"=>html_entity_decode($row["opinion"])
	            ,"actuaciones"=>html_entity_decode($row["actuaciones"])
	            ,"mejora"=>html_entity_decode($row["mejora"])	            
	            );
	// Muy importante recuperar las observaciones como UTF8 para pasarlas al array. Si no, no sale...
	// IMPORTANTE: pasar de HTML entities a decodificacion HTML...
	// http://php.net/manual/en/function.html-entity-decode.php	
    
	$datos_json=json_encode($datos); // codifica como json...

	mysqli_free_result($result); 
	mysqli_close($link); 
	echo $datos_json; 

}
  
?>



