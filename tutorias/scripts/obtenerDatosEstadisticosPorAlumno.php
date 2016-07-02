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

$data=[];
$data["tipo"]=0; // por defecto, para que retorne algo, aunque sea tipo cero

if ($_POST["SQL"]) {
	$arrayResultados = $opiniones->itemsEstadisticaPorAlumno($_POST["SQL"]);	
	$alum=[]; $itemspositivos=[]; $itemsnegativos=[];
	// Separo cada alumno de los demás
	$resultados=explode("*",$arrayResultados);
	foreach ($resultados as $clave=>$valor) {
		$dato=explode("-",$valor);
		$alumn[]=iconv("ISO-8859-15", "UTF-8",$alumnos->devuelveNombreAlumno($dato[0]));
		$cadenaitems=explode("#",$dato[1]);
		$pos=0; $neg=0;
		foreach ($cadenaitems as $clave2=>$valor2) {
			if (!is_null($valor2)) {
				$data["tipo"]=1; // tiene datos...
				$informacionItem = json_decode($opiniones->retornaItem($valor2));
				if ($informacionItem->{"positivo"}==0 and !is_null($informacionItem->{"positivo"})) { $neg+=1; }	// Suma uno si es negativo
				if ($informacionItem->{"positivo"}==1 and !is_null($informacionItem->{"positivo"})) { $pos+=1; }	// Suma uno si es positivo
			}
		} // Fin del for each de cadena de items...
		$itemspositivos[]=$pos;
		$itemsnegativos[]=$neg;
	} // Fin del for each de resultados
	$data["alumnos"]= $alumn;	
	$data["positivos"]=$itemspositivos;
	$data["negativos"]=$itemsnegativos;
} // Fin del $_POST...

	echo json_encode($arrayResultados);
	
?>



