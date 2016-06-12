<?php 
/* ****************************************************************
Incluyo una funci�n con los datos de conexi�n
****************************************************************** */
include_once("./configuracion/config.php");

/* ****************************************************************
Esta funci�n conecta a una base de datos en concreto
****************************************************************** */
function Conectarse()
{ // para conectarse a una base de datos, que ya se define en config.php
global $mysql_server, $mysql_login, $mysql_pass, $bd; // defino variables como globales
/* ANTIGUO mysql ********************************************************
if
// (!($link=mysql_connect("","pepe","pepa")))
(!($link=mysql_connect($mysql_server,$mysql_login,$mysql_pass)))
{
echo "<p>Error conectando a la base de datos. Datos incorrectos de servidor, login o contrase�a</p>";
exit();
}
if (!mysql_select_db($bd,$link)) //base de datos:conexi�n
{
echo "<p>Error cuando selecciono la base de datos. No existe esa base de datos.</p>";
exit();
}
return $link;
********************************************************************** */

$con=mysqli_connect($mysql_server, $mysql_login, $mysql_pass, $bd);
// Check connection
if (mysqli_connect_errno())
  {
  echo mysqli_connect_error();
  } else {
  return $con; 
  }
}

/* ****************************************************************
Esta funci�n recupera el valor de un campo en concreto...
****************************************************************** */
function dado_Id($Id,$tipo,$tabla,$nombreid) 
	{ // Recupera el valor de la base de datos	
	$link=Conectarse(); // y me conecto. //dependiendo del tipo recupero uno u otro.
	// $Sql="SELECT ".$tipo." from ".$tabla." WHERE ".$nombreid."='".$Id."'";
    $Sql="SELECT ".$tipo." from ".$tabla." WHERE ".$nombreid."='%s'"; 
    $Sql = sprintf($Sql, mysqli_real_escape_string($link, $Id)); // Seguridad que evita los ataques SQL Injection
	// echo $Sql;
    $result=mysqli_query($link,$Sql); // ejecuta la cadena sql y almacena el resultado el $result
	$row=mysqli_fetch_array($result);
	if (!is_null($row[$tipo]) or !empty($row[$tipo])) { // si no lo recupera, el valor por defecto)
					  return $row[$tipo];
					  } else {
				      return "Error";
					  }
	mysqli_free_result($result);
	mysqli_close($link);
	}
	
/* *****************************************************************************
Esta funci�n cambiar el nombre del tipo Apll1 Apll2, Nombre a Nombre y apellidos
*********************************************************************************/
function cambiarnombre($nombre) {
    $palabras = preg_split('/,/', $nombre);
    if (count($palabras)==1) { return $nombre; }
    elseif (count($palabras)==2) {
       return trim($palabras[1]).' '.trim($palabras[0]);
    } else { return NULL; }
}

/* ********************************************************************************************
Estas funciones retornan el nombre (lo que hay tras la coma) o los apellidos (antes de la coma)
***********************************************************************************************/
function retornaNombre($nombre) {
    $palabras = preg_split('/,/', $nombre);
    if (count($palabras)==1) { return $nombre; }
    elseif (count($palabras)==2) {
       return trim($palabras[1]);
    } else { return NULL; }
}

function retornaApellidos($nombre) {
    $palabras = preg_split('/,/', $nombre);
    if (count($palabras)==1) { return $nombre; }
    elseif (count($palabras)==2) {
       return trim($palabras[0]);
    } else { return NULL; }
}

/* ********************************************************************************************
Comprueba si una cadena es o no vac�a
***********************************************************************************************/
function IsNullOrEmptyString($question){
    return (!isset($question) || trim($question)==='');
}

/* ********************************************************************************************
Cambiar fecha MySQL a formato elegido datepicker
* 
***********************************************************************************************/
function fechaMySQL2DatePicker($fec) {
    return date("d-m-Y", strtotime($fec));
}

/* ********************************************************************************************
Cambiar fecha MySQL a formato largo
***********************************************************************************************/
function fechaMySQL2Larga($fec) {
	// Mejor as�, por si el servidor no tiene SETLOCALE activado...	
	$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","S�bado");
	$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
	return $dias[date('w',strtotime($fec))].", ".date('d',strtotime($fec))." de ".$meses[date('n',strtotime($fec))-1]. " del ".date('Y',strtotime($fec)) ;
    // return date("l, d \d\e M \d\e Y", strtotime($fec));
}

/* ********************************************************************************************
Cambiar fecha MySQL a formato todo junto
***********************************************************************************************/
function fechaMySQL2together($fec) {
    return date("Ymd", strtotime($fec));
}

/* ********************************************************************************************
Dada una fecha, te calcula el uno de septiembre de ese a�o
***********************************************************************************************/
function unoSeptiembre($fechaDada) {
		$anno = date('Y',strtotime($fechaDada));
		$fechaUnoSeptiembre=date('Y-m-d',strtotime($anno."-09-01")); // del uno de septiembre de ese a�o
		return $fechaUnoSeptiembre;
}
?>
