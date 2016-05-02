<?php
header('Content-Type: text/html; charset=ISO-8859-15'); // importante; especifica el charset de caracteres.

// importante incluir al principio de cada una, lo de las funciones
include_once("../../configuracion/config.php"); // funciones de configuración
include_once("../../funciones/funciones.php"); // funciones varias de conexión a base de datos, etc.

// Incluyo además las clases que se van a usar
include_once("../../clases/class.micalendario.php"); //clase mi calendario que presenta fechas formateadas
include_once("../../clases/class.formulario.php"); //clase que recupera datos de profesores

$calendario= New micalendario(); // variable de calendario.
$formulario = New formulario(); // variable de la clase formulario

session_start(); //activo variables de sesion

$formulario->nombreTabla=$_SESSION['nombreTabla'];
$formulario->nombreClavePrimaria(); // Llama y crea el nombre de la clave primaria en $this->nombrePK;
$valoresclaveprimaria = $formulario->datosPK();

if ($_POST["lee"]) { // como viene encerrado en dos llaves [ ] adicionales hay que hacer substring para dejar las { }
	$registro = json_decode(substr($_POST["lee"],1,-1)); // ¡Ojo! JSON convierte a UTF-8. Hay que volver a convertir a ISO-8859-15
    $Sql = "UPDATE ".$formulario->nombreTabla." SET ";
    foreach ($registro as $clave => $valor)	{
		// $cadena.='<p>'.$clave.' -> '.$valor.'</p>';
		if ($clave!=$formulario->nombrePK)  { $Sql.=$clave.'="'.$valor.'", '; }
	}
	$Sql= substr($Sql,0,-2);
	$Sql.=' WHERE '.$formulario->nombrePK.'="'.$_SESSION["IDform"].'"'; // recuerdo que el valor de sesión es el del ID.
	
	// Conexion
	$link=Conectarse(); // conect
	mysqli_query($link,iconv("UTF-8","ISO-8859-15", $Sql)); // ejecuto el Sql
	  // IMPORTANTE: como json_decode trata los caracteres como UTF-8, el resultado (cadena Sql) 
	  // hay que transformarlo a ISO-8859-15 para poder pasarlo a la base de datos.
	mysqli_close($link); // cierro la conexión
	
	// echo $registro;
	// echo iconv("UTF-8","ISO-8859-15", $Sql); // ya que JSON transforma a UTF8
	// echo $cadena;	
} else {
	echo NULL;
}


?>
