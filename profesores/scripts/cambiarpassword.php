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

$profesorado->idprofesor = $_SESSION["profesor"]; // me aseguro que la clase tiene ese parámetro
$clave = trim(strtoupper($profesorado->profesorDNI())); // clave actual
$antiguo = trim(strtoupper($_POST["antiguo"]));
$nuevo = trim(strtoupper($_POST["nuevo"]));
$repite = trim(strtoupper($_POST["repite"]));

// Comprueba si nuevo es válido
if ($clave==$antiguo) { // Primero se comprueba la integridad de la antigua clave
	
	// Comprueba la integridad de la clave nueva. Usa la comparación regular preg_match
	// De 5 a 9 caracteres en la que tiene que haber una letra (recuerdo que se han pasado a Mayúsculas), y un número como mínimo
	if (preg_match("/^.*(?=.{5,9})(?=.*\d)(?=.*[A-Z]).*$/", $nuevo)) {
		
		// Comprueba la coincidencia de la nueva con la repetida, para evitar cambios con equívocos
		
		if ($nuevo==$repite) {
			
			// Se produce el cambio
			$link=Conectarse(); // y me conecto. //dependiendo del tipo recupero uno u otro.
			// $Sql="SELECT idprofesor FROM tb_profesores WHERE DNI='%s'"; 
			$Sql="UPDATE tb_profesores SET DNI='%s' WHERE idprofesor='".$_SESSION['profesor']."'"; 
			$Sql = sprintf($Sql, mysqli_real_escape_string($link,$_POST["nuevo"])); // Seguridad que evita los ataques SQL Injection
			// echo $Sql;
			$result=mysqli_query($link,$Sql); // ejecuta la cadena sql y almacena el resultado el $result
			// $row=mysqli_fetch_array($result);
			mysqli_free_result($result);
			mysqli_close($link);
			$datos["valido"] = 1;
			$datos["mensaje"]="Contraseña cambiada correctamente";
			
		} else {
			$datos["mensaje"]="No coinciden la nueva contraseña con la repetición de la contraseña";
		}		
		
	} else {
		$datos["mensaje"]="La nueva contraseña no presenta los requisitos de seguridad. Debe tener entre 5 y 9 caracteres y presentar al menos una letra y un número";
	} 
	
  
} else {
	$datos["mensaje"]="La clave anterior no coincide con la registrada. Si no puedes cambiarla, avisa al administrador/a del sistema";
}

echo json_encode($datos); 
?>
