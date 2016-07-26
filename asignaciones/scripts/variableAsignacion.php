<?php
header('Content-Type: text/html; charset=ISO-8859-15'); // importante; especifica el charset de caracteres.

// importante incluir al principio de cada una, lo de las funciones
include_once("../../configuracion/config.php"); // funciones de configuración
include_once("../../funciones/funciones.php"); // funciones varias de conexión a base de datos, etc.

// Incluyo además las clases que se van a usar
// include_once("../../clases/class.micalendario.php"); //clase mi calendario que presenta fechas formateadas
// include_once("../../clases/class.formulario.php"); //clase que recupera datos de profesores
// include_once("../../clases/class.profesores.php"); //clase que recupera datos de profesores
// include_once("../../clases/class.cursos.php"); //clase que recupera datos de cursos

// $calendario= New micalendario(); // variable de calendario.
// $formulario = New formulario(); // variable de la clase formulario
// $profesores = New profesores(); // variable de la clase profesores
// $curso = New misCursos(); // variable de la clase profesores

session_start(); //activo variables de sesion

if ($_POST["lee"]<>0 and $_POST["token"]==$_SESSION["token"]) { 
   // token evita ataques CSRF https://www.funcion13.com/preven-falsificacion-peticion-sitios-cruzados-csrf/
   // Si el token que se pasa no es el mismo que el token de la sesión, no asignará a la variable session y no 
   // se puede continuar en el sitio.
   $_SESSION["idasignacion"]=$_POST["lee"];
   echo $_SESSION["idasignacion"];
}

   
?>



