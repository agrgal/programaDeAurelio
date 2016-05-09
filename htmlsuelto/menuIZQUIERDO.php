<?php
header('Content-Type: text/html; charset=ISO-8859-15'); // importante; especifica el charset de caracteres.
session_start();

    // Visuliza que POST y que SESSION está recibiendo.
    // echo "POST: ".$_POST["lee"]." - SESSION: ".$_SESSION["menuIZQ"];

    if ($_POST["lee"]=="1" || (!isset($_POST["lee"]) && $_SESSION["menuIZQ"]=="1" )) { // Menú del Profesor
	$_SESSION["menuIZQ"]=1; // la establece por si acaso
	echo '
		<h2 style="text-align: center; font-weight: bold;">Menú Profesores/as </h2>
		<ul class="menuIZQul">
			<li><a href="./asignaciones.php">Asignaciones</a></li>';
			if ($_SESSION["idasignacion"]>1) { // sólo si está establecida la variable de sesión
				echo '<li><a href="./opiniones.php">Opiniones alumno por alumno</a></li>';
				echo '<li><a href="./opinionesHistorico.php">Histórico de opiniones</a></li>';
				echo '<li><a href="./opinionesGenerales.php">Opinión General de una clase</a></li>';
		    }
	echo '</ul>';
	} else if ($_POST["lee"]=="3" || (!isset($_POST["lee"]) && $_SESSION["menuIZQ"]=="3")) { // Menú del Administrador
	$_SESSION["menuIZQ"]=3; // la establece por si acaso
	echo'
		<h2 style="text-align: center; font-weight: bold;"> Menú del Administrador/a </h2>
		<ul class="menuIZQul">
			<li><a href="./editaFormulario.php?tabla=tb_profesores">Lista del Profesorado</a></li>
			<li><a href="./editaFormulario.php?tabla=tb_alumno">Lista del Alumnado</a></li>
			<li><a href="./editaFormulario.php?tabla=tb_asignaturas">Lista de Asignaturas</a></li>
			<li><a href="./editaFormulario.php?tabla=tb_edicionevaluaciones">Evaluaciones</a></li>
			<li><a href="./editaFormulario.php?tabla=tb_itemsopiniones">Items Opiniones</a></li>
		</ul>
	    ';
	
	} else if ($_POST["lee"]=="2" || (!isset($_POST["lee"]) && $_SESSION["menuIZQ"]=="2")) { // Menú del Tutor
	$_SESSION["menuIZQ"]=2; // la establece por si acaso
	echo'
		<h2 style="text-align: center; font-weight: bold;"> Menú del Tutor/a </h2>
		<ul class="menuIZQul">
			<li><a href="./listadoAlumnos.php">Listado de los alumnos/as de mi tutoría</a></li>
			<li><a href="./obtenerDatosTutor.php">Datos en mi tutoría</a></li>
		</ul>
	    ';
	} else {
		include_once("./configuracion/introduccion.php");
	}// Fin del IF

?>
        


