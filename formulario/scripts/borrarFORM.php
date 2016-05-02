<?php
header('Content-Type: text/html; charset=ISO-8859-15'); // importante; especifica el charset de caracteres.

// importante incluir al principio de cada una, lo de las funciones
include_once("../../configuracion/config.php"); // funciones de configuración
include_once("../../funciones/funciones.php"); // funciones varias de conexión a base de datos, etc.

// Incluyo además las clases que se van a usar
// include_once("../../clases/class.micalendario.php"); //clase mi calendario que presenta fechas formateadas
include_once("../../clases/class.formulario.php"); //clase que recupera datos de profesores

// $calendario= New micalendario(); // variable de calendario.
$formulario = New formulario(); // variable de la clase formulario

session_start(); //activo variables de sesion

$formulario->nombreTabla=$_SESSION['nombreTabla'];
$formulario->nombreClavePrimaria(); // calcular el nombre de la clave primaria
$valoresclaveprimaria = $formulario->datosPK();
$min = $formulario->minmaxClavePrimaria(0);
$max = $formulario->minmaxClavePrimaria(1);

$id=$_SESSION["IDform"];

if ($id>0) { // como viene encerrado en dos llaves [ ] adicionales hay que hacer substring para dejar las { }
	
	// 1) Averigua en que lugar está el dato a borrar
	$clave=array_search($id,$valoresclaveprimaria);

	// 2) Comparar
	if ($min<$max) {       
	   if ($min==$id) { $clave++;} // Si estoy borrando el más pequeño..., cojo el número siguiente
	   if ($min<$id) { $clave--;} // Si otro, muestro el anterior.
	   $Sql="DELETE FROM ".$formulario->nombreTabla." WHERE ".$formulario->nombrePK."='%s'";
	   
	   	// Conexion
		$link=Conectarse(); // conect
		$Sql = sprintf($Sql, mysqli_real_escape_string($link,$id)); // ojo, después de activar link...
		mysqli_query($link,iconv("UTF-8","ISO-8859-15", $Sql)); // ejecuto el Sql
		  // IMPORTANTE: como json_decode trata los caracteres como UTF-8, el resultado (cadena Sql) 
		  // hay que transformarlo a ISO-8859-15 para poder pasarlo a la base de datos.
		mysqli_close($link); // cierro la conexión	
	    
	    // Nueva variable de sesión
	    $_SESSION["IDform"]=$valoresclaveprimaria[$clave]; // el valor calculado de la clave nuevo.

        // echo $min.":".$max." -->".$Sql." - ".$_SESSION["IDform"];
  
	} else if ($min==$max){
	  exit();
	} 
	
} else {
	echo "Hola";
}


?>
