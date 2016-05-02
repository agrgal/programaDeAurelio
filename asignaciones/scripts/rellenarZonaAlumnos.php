<?php
header('Content-Type: text/html; charset=ISO-8859-15'); // importante; especifica el charset de caracteres.

// importante incluir al principio de cada una, lo de las funciones
include_once("../../configuracion/config.php"); // funciones de configuración
include_once("../../funciones/funciones.php"); // funciones varias de conexión a base de datos, etc.

// Incluyo además las clases que se van a usar
//include_once("../../clases/class.micalendario.php"); //clase mi calendario que presenta fechas formateadas
include_once("../../clases/class.alumnos.php"); //clase que recupera datos de alumnos
// include_once("../../clases/class.cursos.php"); //clase que recupera datos de cursos

// $calendario= New micalendario(); // variable de calendario.
// $curso = New misCursos(); // variable de la clase curso
$alumno = New misAlumnos(); // variable de la clase curso

// session_start(); //activo variables de sesion

if ($_POST["lee"]) {
	$arrayAlumnos=explode("#",$_POST["lee"]); // obtiene el array de valores
	$html="<h3>Elige Alumnado</h3>"; // encabezado
	$ii=1;
	foreach ($arrayAlumnos as $valor) { // por cada valor del array
		$alumno->devuelveAlumno($valor); // lama a la función que obtiene los datos de cada alumno
		$cadena=sprintf($alumno->esteAlumno["div"],$ii,$ii); // viene formateado para ponerle el número
		    // dos veces, una para el parámetro orden y otra para ponerlo y que se muestre
		$html.=$cadena; // escribe un div por cada uno
		$ii++; // aumenta el contador
	}
	echo $html; // presenta la información
} else {
	echo "<h3>Elige Alumnado</h3>";
}

?>
