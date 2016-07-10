<?php
header('Content-Type: text/html; charset=ISO-8859-15'); // importante; especifica el charset de caracteres.

// importante incluir al principio de cada una, lo de las funciones
include_once("../../configuracion/config.php"); // funciones de configuración
include_once("../../funciones/funciones.php"); // funciones varias de conexión a base de datos, etc.

// Incluyo además las clases que se van a usar
include_once("../../clases/class.micalendario.php"); //clase mi calendario que presenta fechas formateadas
include_once("../../clases/class.profesores.php"); //clase que recupera datos de profesores

$calendario= New micalendario(); // variable de calendario.
$profesorado = New profesores(); //variable de la clase profesores

session_start(); //activo variables de sesion

$datos=[]; // defino vector;
$datos["valido"]=0;

// Comprueba si nuevo es válido
if (!filter_var($_POST["nuevo"], FILTER_VALIDATE_EMAIL) === false) {
  if (trim($_POST["nuevo"])==trim($_POST["repite"]) and strlen(trim($_POST["nuevo"])<=200)) {
	  // Cambio de email. Rutina.
	    $datos["valido"]=1;
	    $emailNuevo = trim($_POST["nuevo"]);
	    $link=Conectarse(); // y me conecto. //dependiendo del tipo recupero uno u otro.
        // $Sql="SELECT idprofesor FROM tb_profesores WHERE DNI='%s'"; 
        $Sql="UPDATE tb_profesores SET email='%s' WHERE idprofesor='".$_SESSION['profesor']."'"; 
	    $Sql = sprintf($Sql, mysqli_real_escape_string($link,$emailNuevo)); // Seguridad que evita los ataques SQL Injection
        // echo $Sql;
        $result=mysqli_query($link,$Sql); // ejecuta la cadena sql y almacena el resultado el $result
	    // $row=mysqli_fetch_array($result);
	    mysqli_free_result($result);
	    mysqli_close($link);
	  // Mensaje. 
	  $datos["mensaje"]="Se ha cambiado el email correctamente por ".$emailNuevo;
  } else {
	  $datos["mensaje"]="Los email no coinciden. No se han realizado cambios";
  }
} else {
  $datos["mensaje"]="El email introducido no es válido";
}

echo json_encode($datos);
?>
