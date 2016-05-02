
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
$valoresclaveprimaria = $formulario->datosPK();

if ($_POST["lee"]=="first") {
   $_SESSION["IDform"] = $formulario->minmaxClavePrimaria(0);     
} else if ($_POST["lee"]=="last") {
   $_SESSION["IDform"] = $formulario->minmaxClavePrimaria(1);  
} else if ($_POST["lee"]=="previous") {
   $clave = array_search($_SESSION["IDform"],$valoresclaveprimaria); // busca en la lista la clave o puntero del array.
   $clave = ($clave-1)*($clave>0); // las claves son numéricas, luego simplemente le resta 1 si es mayor que cero
   $_SESSION["IDform"] = $valoresclaveprimaria[$clave];
} else if ($_POST["lee"]=="next") {
   end($valoresclaveprimaria); // mueve el puntero al final
   $ultimaclave=key($valoresclaveprimaria); // obtiene la clave del último elemento
   $clave = array_search($_SESSION["IDform"],$valoresclaveprimaria); // busca en la lista la clave o puntero del array.
   $clave = ($clave+1)*($clave<$ultimaclave)+($clave)*($clave>=$ultimaclave); // las claves son numéricas, luego simplemente le suma 1 si es menor que el último
   $_SESSION["IDform"] = $valoresclaveprimaria[$clave];
   // $_SESSION["IDform"] = $ultimaclave;
} else if (is_numeric($_POST["lee"]))	 {
   $_SESSION["IDform"] = $_POST["lee"];	
}
   // echo $_SESSION["IDform"];
?>
