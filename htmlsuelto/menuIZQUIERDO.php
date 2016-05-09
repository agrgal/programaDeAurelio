<?php
header('Content-Type: text/html; charset=ISO-8859-15'); // importante; especifica el charset de caracteres.
session_start();

    // Visuliza que POST y que SESSION est� recibiendo.
    // echo "POST: ".$_POST["lee"]." - SESSION: ".$_SESSION["menuIZQ"];

    if ($_POST["lee"]=="1" || (!isset($_POST["lee"]) && $_SESSION["menuIZQ"]=="1" )) { // Men� del Profesor
	$_SESSION["menuIZQ"]=1; // la establece por si acaso
	echo '
		<h2 style="text-align: center; font-weight: bold;">Men� Profesores/as </h2>
		<ul class="menuIZQul">
			<li><a href="./asignaciones.php">Asignaciones</a></li>';
			if ($_SESSION["idasignacion"]>1) { // s�lo si est� establecida la variable de sesi�n
				echo '<li><a href="./opiniones.php">Opiniones alumno por alumno</a></li>';
				echo '<li><a href="./opinionesHistorico.php">Hist�rico de opiniones</a></li>';
				echo '<li><a href="./opinionesGenerales.php">Opini�n General de una clase</a></li>';
		    }
	echo '</ul>';
	} else if ($_POST["lee"]=="3" || (!isset($_POST["lee"]) && $_SESSION["menuIZQ"]=="3")) { // Men� del Administrador
	$_SESSION["menuIZQ"]=3; // la establece por si acaso
	echo'
		<h2 style="text-align: center; font-weight: bold;"> Men� del Administrador/a </h2>
		<ul class="menuIZQul">
			<li><a href="./editaFormulario.php?tabla=tb_profesores">Lista del Profesorado</a></li>
			<li><a href="./editaFormulario.php?tabla=tb_alumno">Lista del Alumnado</a></li>
			<li><a href="./editaFormulario.php?tabla=tb_asignaturas">Lista de Asignaturas</a></li>
			<li><a href="./editaFormulario.php?tabla=tb_edicionevaluaciones">Evaluaciones</a></li>
			<li><a href="./editaFormulario.php?tabla=tb_itemsopiniones">Items Opiniones</a></li>
		</ul>
	    ';
	
	} else if ($_POST["lee"]=="2" || (!isset($_POST["lee"]) && $_SESSION["menuIZQ"]=="2")) { // Men� del Tutor
	$_SESSION["menuIZQ"]=2; // la establece por si acaso
	echo'
		<h2 style="text-align: center; font-weight: bold;"> Men� del Tutor/a </h2>
		<ul class="menuIZQul">
			<li><a href="./listadoAlumnos.php">Listado de los alumnos/as de mi tutor�a</a></li>
			<li><a href="./obtenerDatosTutor.php">Datos en mi tutor�a</a></li>
		</ul>
	    ';
	} else {
		include_once("./configuracion/introduccion.php");
	}// Fin del IF

?>
        


