<?php
// header('Content-Type: text/html; charset=UTF-8'); // importante; especifica el charset de caracteres.
header('Content-Type: text/html; charset=ISO-8859-15'); // importante; especifica el charset de caracteres.

// importante incluir al principio de cada una, lo de las funciones
include_once("../../configuracion/config.php"); // funciones de configuración
include_once("../../funciones/funciones.php"); // funciones varias de conexión a base de datos, etc.

// Incluyo además las clases que se van a usar
include_once("../../clases/class.micalendario.php"); //clase mi calendario que presenta fechas formateadas
// include_once("../../clases/class.formulario.php"); //clase que recupera datos de formularios
include_once("../../clases/class.materias.php"); //clase que recupera datos de materias
include_once("../../clases/class.profesores.php"); //clase que recupera datos de profesores
include_once("../../clases/class.cursos.php"); //clase que recupera datos de cursos
include_once("../../clases/class.asignaciones.php"); //clase que recupera datos de alumnos
include_once("../../clases/class.alumnos.php"); //clase que recupera datos de alumnos
// include_once("../../clases/class.opiniones.php"); //clase que recupera datos de las opiniones
// require_once("../../phpmailer/class.phpmailer.php"); //clase que envía datos a través del correo electrónico
require_once("../../phpmailer/PHPMailerAutoload.php"); //clase que envía datos a través del correo electrónico

$calendario= New micalendario(); // variable de calendario.
// $formulario = New formulario(); // variable de la clase formulario
$profesores = New profesores(); // variable de la clase profesores
$curso = New misCursos(); // variable de la clase profesores
$alumnos = New misAlumnos(); // variable de la clase alumnos
$materia = New misMaterias(); // variable de la clase materia
// $opiniones = New misOpiniones(); // variable de la clase opiniones
$asignacion = New misAsignaciones($curso, $profesores, $materia, $alumnos); // Uso el constructor para pasarle la clase curso, profesorado, alumno y materias a Asignaciones


session_start(); //activo variables de sesion

$data=[];
$data["valido"]=0;
$data["divs"]="";

if ($_POST["alumno"]>0) {
	$asignacionAlumno = $asignacion->devuelveAsignacionesDondeEstaUnAlumno($_POST["alumno"]);
		if (!is_null($asignacionAlumno) and !empty($asignacionAlumno)) {
			$asignacionAlumnoArray=explode("#",$asignacionAlumno);
			$cadena="";
			foreach ($asignacionAlumnoArray as $clave => $valor) {
				// $cadena.= $asignacion->asignacionDIV2($valor); // lama a los divs que están en la clase asignación
				$cadena.='<div class="bloque" id="'.$valor.'">'.$asignacion->asignacionDIV2($valor).'<div class="destino" id="destino'.$valor.'" title="by Mobiletuxedo in gestures"></div></div>';
			}
			$data["valido"]=1;
			$data["divs"]= iconv("ISO-8859-15", "UTF-8",$cadena);
			echo json_encode($data);
		} else {
			echo json_encode($data);
		}
}




/* 
 
*/

?>



