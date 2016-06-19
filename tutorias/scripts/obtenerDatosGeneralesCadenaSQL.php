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
include_once("../../clases/class.opinionesGenerales.php"); //clase que recupera datos de las opiniones

// $calendario= New micalendario(); // variable de calendario.
// $formulario = New formulario(); // variable de la clase formulario
$profesores = New profesores(); // variable de la clase profesores
// $curso = New misCursos(); // variable de la clase profesores
$materia = New misMaterias(); // variable de la clase alumnos
$alumnos = New misAlumnos(); // variable de la clase alumnos
$asignaciones = New misAsignaciones($curso, $profesores, $materia, $alumnos);
$opiniones = New misOpinionesGenerales(); // variable de la clase opiniones

// session_start(); //activo variables de sesion

$devuelve="No hay datos que mostrar";

if ($_POST["SQL"]) {
	$datos = json_decode($opiniones->retornaValores($_POST["SQL"])); // retorna valores, pero de las Opiniones Generales.
	$total=count($datos);
	if ($total>0) { $apostilla = "<h2>Se han obtenido un total de ".$total." registros</h2>"; }
	$devuelve="";	
	$firmar="";
	foreach ($datos as $clave => $valor) {
		// Obtiene los resultados
		$opinion=trim(iconv("UTF-8","ISO-8859-15",strip_tags($valor->{"opinion"})));
		$actuaciones=trim(iconv("UTF-8","ISO-8859-15",strip_tags($valor->{"actuaciones"})));
		$mejora=trim(iconv("UTF-8","ISO-8859-15",strip_tags($valor->{"mejora"})));
		// EMPAQUETAR
		$nombreProfesor = $asignaciones->asignacionProfesor($valor->{"asignacion"},1);
		$nombreMateria = $asignaciones->asignacionMateria($valor->{"asignacion"},1);
		if (($mejora) || ($actuaciones) || ($opinion)) {         
			// $devuelve.='<table id="'.$valor->{"id"}.'" class="tablaDATOS" eval="'.$valor->{"eval"}.'" asignacion="'.$valor->{"asignacion"}.'">
			//  <tr><td>'.$textoPresentar.'</td></tr>
			//  </table><hr class="separador">';
			$devuelve.='<table style="width: 80%; margin-left: auto; margin-right: auto; border-color: navy;" border="2" cellspacing="2" cellpadding="2">
						<tbody>
						<tr style="background-color: lightsteelblue;"><th style="vertical-align: middle; text-align: center;" colspan="3">
						<h2><span style="color: navy;"><b>Profesor/a:&nbsp;</b>'.$nombreProfesor.'</br><b>Asignatura:&nbsp;</b>'.$nombreMateria.'</span></h2>
						</th></tr>';
						if ($opinion) { $devuelve.='<tr><td style="width: 100%; text-align: justify;"><b>Opiniones:&nbsp;</b>'.$opinion.'</td></tr>'; }
						if ($actuaciones) {$devuelve.='<tr></tr><td style="width: 100%; text-align: justify;"><b>Actuaciones:&nbsp;</b>'.$actuaciones.'</td></tr>';}
						if ($mejora) { $devuelve.='<tr></tr><td style="width: 100%; text-align: justify;"><b>Mejoras:&nbsp;</b>'.$mejora.'</td></tr>';}
			$devuelve.='</tbody>
						</table></br>';			
		} // Fin del if comprueba nulidad
	} // Fin del foreach	
} // Fin del IF 


if ($devuelve=="") { 
	echo '<h1>No hay datos que mostrar&nbsp;&nbsp;<img src="./imagenes/iconos/ohoh.png" style="vertical-align:middle; width:75px; height:auto;"></h1>'; 	
} else {
	// echo htmlspecialchars($devuelve,ENT_QUOTES);
	echo $devuelve.$apostilla;
} 
  
?>



