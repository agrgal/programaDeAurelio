<?php
header('Content-Type: text/html; charset=ISO-8859-15'); // importante; especifica el charset de caracteres.

// importante incluir al principio de cada una, lo de las funciones
include_once("../../configuracion/config.php"); // funciones de configuración
include_once("../../funciones/funciones.php"); // funciones varias de conexión a base de datos, etc.

// Incluyo además las clases que se van a usar
// include_once("../../clases/class.micalendario.php"); //clase mi calendario que presenta fechas formateadas
// include_once("../../clases/class.formulario.php"); //clase que recupera datos de formularios
include_once("../../clases/class.profesores.php"); //clase que recupera datos de profesores
// include_once("../../clases/class.cursos.php"); //clase que recupera datos de cursos
include_once("../../clases/class.asignaciones.php"); //clase que recupera datos de alumnos
include_once("../../clases/class.alumnos.php"); //clase que recupera datos de alumnos
include_once("../../clases/class.materias.php"); //clase que recupera datos de materias
include_once("../../clases/class.opiniones.php"); //clase que recupera datos de las opiniones

// $calendario= New micalendario(); // variable de calendario.
// $formulario = New formulario(); // variable de la clase formulario
$profesores = New profesores(); // variable de la clase profesores
// $curso = New misCursos(); // variable de la clase profesores
$materia = New misMaterias(); // variable de la clase alumnos
$alumnos = New misAlumnos(); // variable de la clase alumnos
$asignaciones = New misAsignaciones($curso, $profesores, $materia, $alumnos);
$opiniones = New misOpiniones(); // variable de la clase opiniones

// session_start(); //activo variables de sesion

// echo $_POST["SQL"]." - ".$_POST["posneg"];

if ($_POST["SQL"]) {
	$arrayResultados = $opiniones->itemsEstadistica($_POST["SQL"]);	
	$items=[]; $frecuencias=[]; $posneg=[]; $data=[];
	foreach ($arrayResultados as $clave=>$valor) {			
		$retorna = json_decode($opiniones->retornaItem($clave)); 
		if ($_POST["posneg"]==1 and $retorna->{"positivo"}>=2) {
			$items[]=$retorna->{"item"}." (".$retorna->{"grupo"}.")";
			$frecuencias[]=$valor;
			$posneg[]=2; // color de las barras 
		} else if ($_POST["posneg"]==0 and $retorna->{"positivo"}<=1) {
			$items[]=$retorna->{"item"}." (".$retorna->{"grupo"}.")";
			$frecuencias[]=$valor;
			if ($retorna->{"positivo"}==0) {$posneg[]=0;} // color de las barras 
			if ($retorna->{"positivo"}==1) {$posneg[]=1;} // color de las barras 
		}
	}
	$data["items"]=$items;
	$data["frecuencias"]=$frecuencias;
	$data["posneg"]=$posneg;
	echo json_encode($data);
	// echo json_encode($arrayResultados);
} // Fin del $_POST...
  
?>



