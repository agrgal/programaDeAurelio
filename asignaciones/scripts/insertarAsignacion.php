<?php
header('Content-Type: text/html; charset=ISO-8859-15'); // importante; especifica el charset de caracteres.

// importante incluir al principio de cada una, lo de las funciones
include_once("../../configuracion/config.php"); // funciones de configuración
include_once("../../funciones/funciones.php"); // funciones varias de conexión a base de datos, etc.

// Incluyo además las clases que se van a usar
include_once("../../clases/class.micalendario.php"); //clase mi calendario que presenta fechas formateadas
include_once("../../clases/class.formulario.php"); //clase que recupera datos de profesores
include_once("../../clases/class.profesores.php"); //clase que recupera datos de profesores
include_once("../../clases/class.cursos.php"); //clase que recupera datos de cursos

$calendario= New micalendario(); // variable de calendario.
$formulario = New formulario(); // variable de la clase formulario
$profesores = New profesores(); // variable de la clase profesores
$curso = New misCursos(); // variable de la clase profesores

session_start(); //activo variables de sesion


if (isset($_SESSION["profesor"])) { // si existe la variable de profesor...
$profesores->idprofesor=$_SESSION["profesor"];
$profesores->nombreEmpleado();
$nombreprofesor=cambiarnombre($profesores->Empleado);
// como se pone en un array , hay que cambiarlo después a UTF-8
} 

if (strlen($_POST["asignacion"])>0) { // si existe la variable de profesor...
// $retahilalarga = $curso->devuelveAsignacionLarga($_POST["asignacion"]); //
$cursosen = $curso->devuelveCursosdeAsignacion($curso->devuelveAsignacionLarga($_POST["asignacion"]),0);
// Tipo 0 -> que devuelve cursos en formatos cortos. El 1 -> la unidad tal como aparece en Séneca
$cursosen = str_replace("#",", ",$cursosen);
} 

$descripcion = strtoupper("Asig: ".$_POST["nombremateria"].", Prof: ".iconv("ISO-8859-15","UTF-8",$nombreprofesor).", Cursos:  ".$cursosen);
// formula la descripción del elemento para incluirlo en la base de datos. Todo en mayúsculas...
// como formará parte de un array hay que pasarla primero a formato UTF8, para que conserve los acentos...

$formulario->nombreTabla="tb_asignaciones"; // Lo primero que hay que nombrar
$formulario->nombreCampos(); // obtener el nombre de los campos
$formulario->nombreClavePrimaria();
$nC = $formulario->arrayNombreCampos; // y lo graba en la variable $nC
$valoresclaveprimaria = $formulario->datosPK(); // Obtiene los valores de clave primaria.

if (isset($_SESSION["profesor"]) && $_POST["idasignacion"]<=0
    && strlen($_POST["asignacion"])>0 && isset($_POST["tutor"]) 
    && is_numeric($_POST["materia"])) { // Si existe la variable del profesor, la asignación es <=0 (importante) 
	// existe asignacion, existe la variable tutor y existe materia.
	
	if ($_POST["tutor"]=="true") { $tutor=1;} else {$tutor=0;}	// obtiene variable tutor

	// 1º) Obtiene el valor de la clave primaria que hay que poner
	$escribeclaveprimaria = 1+$formulario->minmaxClavePrimaria(1); // añade uno al valor mayor de clave primaria
	// 2º) Obtiene el valor de la cadena Sql
	array_shift($nC["campo"]); // quita el primer elemento...
	$valores=array($_SESSION["profesor"],$_POST["materia"],$_POST["asignacion"],$descripcion,$tutor); // datos menos el idasignaciones
	$Sql = "INSERT INTO ".$formulario->nombreTabla." (";
    // La clave no lleva el id
	$Sql.= $formulario->nombrePK.','; // así que hay que añadirlo
    // claves
    foreach ($nC["campo"] as $clave => $valor)	{
		$Sql.=$valor.","; // introduce la cadena de claves
	}
	$Sql= substr($Sql,0,-1).") VALUES ("; // Quita 1 al final (la última coma), cierra y empieza el values.
	$Sql.= "'".$escribeclaveprimaria."',"; // escribo el valor máximo + 1, el siguiente
	foreach ($valores as $clave => $valor)	{ // y dejo el resto para introducirse
		// $Sql.="'".tipologia($nC,$valor,$clave)."',"; // introduce la cadena de valores	
		$Sql.="'".$valor."',";		
	}
	$Sql= substr($Sql,0,-1).")"; // Quita la última coma y cierra	
    // echo $Sql;
    // 3º) Ejecuta la cadena de inserción
	$link=Conectarse(); // conect
	mysqli_query($link,iconv("UTF-8","ISO-8859-15", $Sql)); // ejecuto el Sql --> IMPORTANTE: aunque no es unresultado JSON, convertirlo a ISO-8859-15
	  // IMPORTANTE: como json_decode trata los caracteres como UTF-8, el resultado (cadena Sql) 
	  // hay que transformarlo a ISO-8859-15 para poder pasarlo a la base de datos.
	mysqli_close($link); // cierro la conexión
	
	$_SESSION["idasignacion"]=$escribeclaveprimaria; // declaro la variable de asignacion
	
	echo $escribeclaveprimaria; // retorna para ponerlo en el input... idasignacion
	
} else if (isset($_SESSION["profesor"]) && $_POST["idasignacion"]>0
    && strlen($_POST["asignacion"])>0 && isset($_POST["tutor"]) 
    && is_numeric($_POST["materia"])) { // Primera parte del IF
	
	if ($_POST["tutor"]=="true") { $tutor=1;} else {$tutor=0;}	// obtiene variable tutor	

    $valores=array($_POST["idasignacion"],$_SESSION["profesor"],$_POST["materia"],$_POST["asignacion"],$descripcion,$tutor); // datos menos el idasignaciones
	$Sql = "UPDATE ".$formulario->nombreTabla." SET ";
    foreach ($nC["campo"] as $clave => $valor)	{
		// $cadena.='<p>'.$clave.' -> '.$valor.'</p>';
		if ($valor!=$formulario->nombrePK)  { $Sql.=$valor.'="'.$valores[$clave].'", '; }
	}
	$Sql= substr($Sql,0,-2);
	$Sql.=' WHERE '.$formulario->nombrePK.'="'.$_POST["idasignacion"].'"'; // recuerdo que el valor de sesión es el del ID.
	
	// echo $Sql;
	
	// Conexion
	$link=Conectarse(); // conect
	mysqli_query($link,iconv("UTF-8","ISO-8859-15", $Sql)); // ejecuto el Sql
	  // IMPORTANTE: como json_decode trata los caracteres como UTF-8, el resultado (cadena Sql) 
	  // hay que transformarlo a ISO-8859-15 para poder pasarlo a la base de datos.
	mysqli_close($link); // cierro la conexión
	
	$_SESSION["idasignacion"]=$_POST["idasignacion"]; // declaro la variable de asignacion
	
	echo $_POST["idasignacion"]; // para ponerlo en el campo input   
	
}// FIN DEL IF 
   
?>



