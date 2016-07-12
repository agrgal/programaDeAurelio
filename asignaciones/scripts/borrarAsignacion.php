<?php
header('Content-Type: text/html; charset=ISO-8859-15'); // importante; especifica el charset de caracteres.

// importante incluir al principio de cada una, lo de las funciones
include_once("../../configuracion/config.php"); // funciones de configuración
include_once("../../funciones/funciones.php"); // funciones varias de conexión a base de datos, etc.

session_start(); //activo variables de sesion

if ($_POST["lee"]>0) { 
	
	// 1.- BORRAR las opiniones de esa asignacion
	$Sql="DELETE FROM tb_opiniones WHERE asignacion='%s'";
	// Conexion
	$link=Conectarse(); // conect
	$Sql = sprintf($Sql, mysqli_real_escape_string($link,$_POST["lee"])); // ojo, después de activar link...
	mysqli_query($link, $Sql); // ejecuto el Sql
	mysqli_close($link); // cierro la conexión
	// echo $Sql;
	
	// 2.- BORRAR las opiniones Generales de esa asignacion
	$Sql="DELETE FROM tb_opiniongeneral WHERE asignacion='%s'";
	// Conexion
	$link=Conectarse(); // conect
	$Sql = sprintf($Sql, mysqli_real_escape_string($link,$_POST["lee"])); // ojo, después de activar link...
	mysqli_query($link, $Sql); // ejecuto el Sql
	mysqli_close($link); // cierro la conexión
	// echo $Sql;
	
	// 3.- Borrar por fin la asignacion en sí
	// echo $_POST["lee"];
	$Sql="DELETE FROM tb_asignaciones WHERE idasignacion='%s'";
	// Conexion
	$link=Conectarse(); // conect
	$Sql = sprintf($Sql, mysqli_real_escape_string($link,$_POST["lee"])); // ojo, después de activar link...
	mysqli_query($link, $Sql); // ejecuto el Sql
	mysqli_close($link); // cierro la conexión
	// echo $Sql;
	
	if ($_POST["lee"]==$_SESSION["idasignacion"]) {
		unset($_SESSION["idasignacion"]); // destruye la variable de sesión SI coincide con la borrada
	}
	
} // FIN DEL IF 
   
?>



