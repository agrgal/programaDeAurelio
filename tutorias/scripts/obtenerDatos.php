<?php
header('Content-Type: text/html; charset=ISO-8859-15'); // importante; especifica el charset de caracteres.

// importante incluir al principio de cada una, lo de las funciones
include_once("../../configuracion/config.php"); // funciones de configuración
include_once("../../funciones/funciones.php"); // funciones varias de conexión a base de datos, etc.

// Incluyo además las clases que se van a usar
// include_once("../../clases/class.micalendario.php"); //clase mi calendario que presenta fechas formateadas
// include_once("../../clases/class.formulario.php"); //clase que recupera datos de formularios
// include_once("../../clases/class.profesores.php"); //clase que recupera datos de profesores
// include_once("../../clases/class.cursos.php"); //clase que recupera datos de cursos
include_once("../../clases/class.asignaciones.php"); //clase que recupera datos de alumnos
include_once("../../clases/class.alumnos.php"); //clase que recupera datos de alumnos
include_once("../../clases/class.opiniones.php"); //clase que recupera datos de las opiniones

// $calendario= New micalendario(); // variable de calendario.
// $formulario = New formulario(); // variable de la clase formulario
// $profesores = New profesores(); // variable de la clase profesores
// $curso = New misCursos(); // variable de la clase profesores
$asignaciones = New misAsignaciones(); //variable de la clase asignaciones 
$alumnos = New misAlumnos(); // variable de la clase alumnos
$opiniones = New misOpiniones(); // variable de la clase opiniones

// session_start(); //activo variables de sesion

$devuelve="No hay datos que mostrar";

/* echo $_POST["conNombreAlumno"]." - ".$_POST["conNombreAsignacion"] */

if ($_POST["SQL"]) {
	$datos = json_decode($opiniones->retornaValores($_POST["SQL"]));
	$total=count($datos);
	if ($total>0) { $apostilla = "<h2>Se han obtenido un total de ".$total." registros</h2>"; }
	$devuelve="";	
	foreach ($datos as $clave => $valor) {
		// Obtiene el nombre del alumno
		$alumnos->devuelveAlumno($valor->{"alumno"});
		$nombreAlumno = $alumnos->esteAlumno["nombre2"];
		if ($_POST["foto"]==1) { $imagen = '<img src="'.$alumnos->esteAlumno["foto"].'" >';  }
		// Obtiene el nombre de la asignacion
		$descripcion = $asignaciones->asignacionDescripcion($valor->{"asignacion"});
		// Obtiene la fecha
		$fecha = fechaMySQL2DatePicker($valor->{"fecha"});
		// Obtiene los items
		$retahila=$opiniones->itemsElegidos($valor->{"items"});
		// Obtiene las observaciones		
		$observaciones=iconv("UTF-8","ISO-8859-15",strip_tags($valor->{"observaciones"}));
		// EMPAQUETAR
		$textoPresentar="";
		if ($retahila) { $textoPresentar.='<b>ITEMS:&nbsp;</b>'.$retahila." // "; }
		if ($observaciones) { $textoPresentar.='<b>OBSERVACIONES:&nbsp;</b>'.$observaciones." // "; }
		if ($textoPresentar) { $textoPresentar=substr($textoPresentar,0,-4); }		
		if ($textoPresentar) {         
			$devuelve.='<table id="'.$valor->{"id"}.'" class="tablaDATOS" elegir="0" alumno="'.$valor->{"alumno"}.'" items="'.$valor->{"items"}.'" observaciones="'.$observaciones.'" >
			  <tr>';
			  if ($_POST["foto"]==1) { $devuelve.='<td id="'.$valor->{"id"}.'" rowspan="3" class="TDimagen" >'.$imagen.'</td>'; }
			  // Las variables conNombreAlumno y conNombreAsignacion se pasan como texto.
			  if ($_POST["conNombreAlumno"]=="true") {$devuelve.='<td class="TDnombreAlumno"><h2>'.$nombreAlumno.'</h2></td></tr>'; }
			  $devuelve.='<tr><td>';
			  if ($_POST["conNombreAsignacion"]=="true") {$devuelve.='<b>ASIGNADO A:&nbsp;</b>'.$descripcion.'&nbsp;'; }  			  
			  $devuelve.='<b>EN FECHA:&nbsp;</b>'.$fecha.'</td></tr>
			  <tr><td>'.$textoPresentar.'</td></tr>
			  </table><hr class="separador">';
		} // Fin del if comprueba nulidad
		
		/* 
		$devuelve.='<div class="obtenerDato">
			<h3>'.$descripcion.'</h3>
			<h2>'.$nombreAlumno.'</h2>
			<h4>'.$fecha.'</h4>
			<p>'.$retahila.'</p>
			'.$observaciones.'
			</div>'; */
	} // Fin del foreach	
} // Fin del IF 


if ($devuelve=="") { 
	echo '<h1>No hay datos que mostrar&nbsp;&nbsp;<img src="./imagenes/iconos/ohoh.png" style="vertical-align:middle; width:75px; height:auto;"></h1>'; 	
} else {
	// echo htmlspecialchars($devuelve,ENT_QUOTES);
	echo $devuelve.$apostilla;
} 
  
?>



