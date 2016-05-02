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

$formulario->nombreTabla=$_SESSION['nombreTabla']; // Lo primero que hay que nombrar
$formulario->nombreCampos(); // obtener el nombre de los campos
$formulario->nombreClavePrimaria();
$nC = $formulario->arrayNombreCampos; // y lo graba en la variable $nC
$valoresclaveprimaria = $formulario->datosPK(); // Obtiene los valores de clave primaria.

if ($_POST["lee"]) {
	// 1º) Obtiene el valor de la clave primaria que hay que poner
	$escribeclaveprimaria = 1+$formulario->minmaxClavePrimaria(1); // añade uno al valor mayor de clave primaria
	// 2º) Obtiene el valor de la cadena Sql
	$registro = json_decode(substr($_POST["lee"],1,-1)); // ¡Ojo! JSON convierte a UTF-8. Hay que volver a convertir a ISO-8859-15
    $Sql = "INSERT INTO ".$formulario->nombreTabla." (";
    // La clave no lleva el id
	$Sql.= $formulario->nombrePK.','; // así que hay que añadirlo
    // claves
    foreach ($registro as $clave => $valor)	{
		$Sql.=$clave.","; // introduce la cadena de claves
	}
	$Sql= substr($Sql,0,-1).") VALUES ("; // Quita 1 al final (la última coma), cierra y empieza el values.
	$Sql.= "'".$escribeclaveprimaria."',"; // escribo el valor máximo + 1, el siguiente
	foreach ($registro as $clave => $valor)	{ // y dejo el resto para introducirse
			$Sql.="'".tipologia($nC,$valor,$clave)."',"; // introduce la cadena de valores			
	}
	$Sql= substr($Sql,0,-1).")"; // Quita la última coma y cierra		
	// 3º) Ejecuta la cadena de inserción
	$link=Conectarse(); // conect
	mysqli_query($link,iconv("UTF-8","ISO-8859-15", $Sql)); // ejecuto el Sql
	  // IMPORTANTE: como json_decode trata los caracteres como UTF-8, el resultado (cadena Sql) 
	  // hay que transformarlo a ISO-8859-15 para poder pasarlo a la base de datos.
	mysqli_close($link); // cierro la conexión
	
	// 4º) Terminar y recargar
	$_SESSION["IDform"] = $escribeclaveprimaria; // recarga el nuevo dato.
	// echo $_SESSION["IDform"];
	// echo $Sql;
	
} else {
	echo "No se ha insertado nada";
}
// echo $_POST["lee"];
// echo $_SESSION["IDform"];
?>

<? 
   // La funcion permite comprobar un tipo determinado y actúa según el valor
   // Por ahora, solo un tipo entero, si recibe una cadena alfanumérica, la cambia a 0
   function tipologia($a, $valor, $clave) {
	// Obtiene el índice de la clave de ahora mismo.
	$indice=array_search($clave,$a["campo"]);
	    // En caso de ser numérico.
	    if (($a["tipo"][$indice]=="int") && !(is_numeric($valor)) ) 
		{ return 0; exit;
	    } else {
	      return $valor;
	    }
   }
?>
