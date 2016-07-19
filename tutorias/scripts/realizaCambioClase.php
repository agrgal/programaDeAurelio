<?php
// header('Content-Type: text/html; charset=UTF-8'); // importante; especifica el charset de caracteres.
header('Content-Type: text/html; charset=ISO-8859-15'); // importante; especifica el charset de caracteres.

// importante incluir al principio de cada una, lo de las funciones
include_once("../../configuracion/config.php"); // funciones de configuración
include_once("../../funciones/funciones.php"); // funciones varias de conexión a base de datos, etc.

// Incluyo además las clases que se van a usar
include_once("../../clases/class.micalendario.php"); //clase mi calendario que presenta fechas formateadas
// include_once("../../clases/class.formulario.php"); //clase que recupera datos de formularios
include_once("../../clases/class.materias.php"); //clase que recupera datos de materias
include_once("../../clases/class.profesores.php"); //clase que recupera datos de profesores
include_once("../../clases/class.cursos.php"); //clase que recupera datos de cursos
include_once("../../clases/class.asignaciones.php"); //clase que recupera datos de alumnos
include_once("../../clases/class.alumnos.php"); //clase que recupera datos de alumnos
// include_once("../../clases/class.opiniones.php"); //clase que recupera datos de las opiniones
// require_once("../../phpmailer/class.phpmailer.php"); //clase que envía datos a través del correo electrónico
require_once("../../phpmailer/PHPMailerAutoload.php"); //clase que envía datos a través del correo electrónico

$calendario= New micalendario(); // variable de calendario.
// $formulario = New formulario(); // variable de la clase formulario
$profesores = New profesores(); // variable de la clase profesores
$curso = New misCursos(); // variable de la clase profesores
$alumnos = New misAlumnos(); // variable de la clase alumnos
$materia = New misMaterias(); // variable de la clase materia
// $opiniones = New misOpiniones(); // variable de la clase opiniones
$asignacion = New misAsignaciones($curso, $profesores, $materia, $alumnos); // Uso el constructor para pasarle la clase curso, profesorado, alumno y materias a Asignaciones


session_start(); //activo variables de sesion

$data=[];
$data["valido"]=0;
$data["divs"]="";
$data["cadena"]="";
$data["numeros"]="";
$data["parejas"]=$_POST["parejas"];
$data["claseantigua"]=$_POST["claseantigua"];
$data["clasenueva"]=$_POST["clasenueva"];
$data["alumno"]=$_POST["alumno"];
$data["hayParejas"]="NO";
$data["borrada"]="";
$data["claveEncontrada"]="claveEncontrada";

// $_POST --> alumno, claseantigua,clasenueva, parejas

// ************************
// Para los pasos 1, 2 y 3
// ************************
if (!is_null($_POST["parejas"]) and !empty($_POST["parejas"])) {
	
	$data["hayParejas"]="SI"; // Bandera que me devuelve si ha entrado en este if
	
	$cadena="";	// construcción de String genérico
	
	// ============================
	// 0) Extraer datos de parejas
	// ============================
	$parejas = explode("#",$_POST["parejas"]);
	$parejas = array_filter($parejas); // Quita los nulos
	$antigua = array(); $nueva = array(); // define arrays con las asignaciones antiguas y nuevas
	foreach ($parejas as $valor) { // Este bucle asigna a antigua y nueva los valores de cada pareja
		$cadenarota=explode("-",$valor);
		$antigua[]=$cadenarota[0];
		$nueva[]=$cadenarota[1];
	} // Fin del foreah que obtiene los arrays nueva y antigua
	
	// ==========================================
	// 1) Por cada pareja, cambio de asignaciones
	// ==========================================
	foreach ($antigua as $clave => $valor) {
		$Sql=""; // Como ejecuta varios Sql, al principio debe ser nulo.
		$link=Conectarse(); // y me conecto. //dependiendo del tipo recupero uno u otro.
	    $Sql='UPDATE tb_opiniones SET asignacion ="'.$nueva[$clave].'"';
	    $Sql.=' WHERE asignacion = "'.$valor.'" AND alumno = "'.$_POST["alumno"].'"';
	    mysqli_query($link,iconv("UTF-8","ISO-8859-15", $Sql)); // ejecuto el Sql
	    mysqli_close($link); // Cierro el enlace...	
	    $data["divs"].=$Sql." <--> ";	    
	} // Fin del foreach de cambio de asignaciones. FUNCIONA 18-Julio
	
	// =========================================================================
	// 2) Si el alumno aparece en la asignacion antigua por id, retirar de datos
	// =========================================================================

		foreach ($antigua as $clave => $valor) {
			$enArray = false; // Bandera que me indica si lo ha encontrado
			$datosFinal=""; // Para que también me sirva de bandera, al inicio como vacío.
			$link=Conectarse(); // y me conecto. //dependiendo del tipo recupero uno u otro.
			$Sql ='SELECT datos FROM tb_asignaciones WHERE idasignacion="'.$valor.'"';
			// $data["divs"].=$Sql." <--> ";	
			$result=mysqli_query($link,$Sql); // ejecuta la cadena sql y almacena el resultado el $result
			$row = mysqli_fetch_array($result);
			if (!is_null($row["datos"]) or !empty($row["datos"])) { // si no lo recupera, el valor por defecto)
			  // a) he encontrado los datos
			  $valores = explode("#",$row["datos"]); // convierte la cadena en un array de valores
			  // b) comprueba que el alumno está en ese array
			  $claveEncontrada = array_search($_POST["alumno"],$valores);
			  $data["claveEncontrada"]= $claveEncontrada;
			  // c) Si el alumno está en el array, eleminarlo
			  if ($claveEncontrada>=0 and is_numeric($claveEncontrada)) { // Sería FALSE si no lo encuentra
				  $enArray = true;
				  unset($valores[$claveEncontrada]); // elimino el elemento de dicha clave
					// d) reconstruir cadena
					$datosFinal = implode("#",$valores);	
					$cadena = "EXISTE CAMBIO: ".$row["datos"]." - ".$datosFinal;
					$data["cadena"].=$cadena." - ";
			  } // Fin del IF de clave encontrada	  
			} // Fin del if que reconoce los datos: row datos
			mysqli_free_result($result); 
			mysqli_close($link); 	    
			
			// e) Si hay una cadena reconstruida, UPDATE para actualizarla
			if (($enArray==true) and !empty($datosFinal) ){ // Si ha existido un cambio en los datos
				$Sql=""; // Como ejecuta varios Sql, al principio debe ser nulo.
				$link=Conectarse(); // y me conecto. //dependiendo del tipo recupero uno u otro.
				$Sql='UPDATE tb_asignaciones SET datos ="'.$datosFinal.'"';
				$Sql.=' WHERE idasignacion = "'.$valor.'"';
				mysqli_query($link,iconv("UTF-8","ISO-8859-15", $Sql)); // ejecuto el Sql
				mysqli_close($link); // Cierro el enlace...	
				$asignacion->reconstruyeDescripcion($valor); // reconstruye la descripcion
				$data["divs"].="VALOR: ".$valor." SQL: ".$Sql." <--> ";
			} else if (empty($datosFinal) and ($enArray==true)) {
				$data["borrada"].="La asignación: ".$asignacion->asignacionDescripcion($valor)." se ha quedado sin datos, con lo que SE HA ELIMINADO.";
				$Sql=""; // Como ejecuta varios Sql, al principio debe ser nulo.
				$link=Conectarse(); // y me conecto. //dependiendo del tipo recupero uno u otro.
				$Sql='DELETE FROM tb_asignaciones';
				$Sql.=' WHERE idasignacion = "'.$valor.'"';
				mysqli_query($link,iconv("UTF-8","ISO-8859-15", $Sql)); // ejecuto el Sql
				mysqli_close($link); // Cierro el enlace...	
				$data["divs"].=$Sql." <--> ";					
			}
			
		} // Fin del foreach de asignación antigua	
	
	// =========================================================================
	// 3) Si en la nueva no aparece la clase, añadir el id de alumno
	// =========================================================================	

	foreach ($nueva as $clave => $valor) {
		$enArray = false; // Bandera que me indica si lo ha encontrado
		$datosFinal=""; // Para que también me sirva de bandera, al inicio como vacío.			
		$link=Conectarse(); // y me conecto. //dependiendo del tipo recupero uno u otro.
		$Sql ='SELECT datos FROM tb_asignaciones WHERE idasignacion="'.$valor.'"';
		// $data["divs"].=$Sql." <--> ";	
		$result=mysqli_query($link,$Sql); // ejecuta la cadena sql y almacena el resultado el $result
		$row = mysqli_fetch_array($result);
		if (!is_null($row["datos"]) or !empty($row["datos"])) { // si no lo recupera, el valor por defecto)
			  $datosFinal = $row["datos"]; // Guarda en esta variable el resultado
			  // a) he encontrado los datos
			  $valores = explode("#",$row["datos"]); // convierte la cadena en un array de valores
			  // b) comprueba que el alumno está en ese array
			  $claveEncontrada = array_search($_POST["clasenueva"],$valores); // Busca la clase nueva
			  $data["claveEncontrada"]= $claveEncontrada;
			  // c) Si el alumno está en el array, eleminarlo
			  if ($claveEncontrada>=0 and is_numeric($claveEncontrada)) { // Sería FALSE si no lo encuentra
				  $enArray = true;
			  } // Fin del IF de clave encontrada	  
		} // Fin del if que reconoce los datos: row datos
		mysqli_free_result($result); 
		mysqli_close($link); 
		
		if (!($enArray)) { // Si es falso, o sea, no encuentra en datos la clase nueva...
			$datosFinal = $datosFinal."#".$_POST["alumno"]; // Guarda en esta variable el resultado más el dato del alumno
			$Sql=""; // Como ejecuta varios Sql, al principio debe ser nulo.
			$link=Conectarse(); // y me conecto. //dependiendo del tipo recupero uno u otro.
			$Sql='UPDATE tb_asignaciones SET datos ="'.$datosFinal.'"';
			$Sql.=' WHERE idasignacion = "'.$valor.'"';
			mysqli_query($link,iconv("UTF-8","ISO-8859-15", $Sql)); // ejecuto el Sql
			mysqli_close($link); // Cierro el enlace...	
			$asignacion->reconstruyeDescripcion($valor); // reconstruye la descripcion
			$data["divs"].=$Sql." <--> ";	
		} // Fin del if que comprueba lo de la clase nueva...
			
	} // Fin del foreach

} // Fin del IF de parejas

// ============================================
// 4) Cambiar en tb_alumno la unidad al alumno
// ============================================

	$unidadNueva = $curso->devuelverUnidadPorCursoCorto($_POST["clasenueva"]);
	$Sql=""; // Como ejecuta varios Sql, al principio debe ser nulo.
	$link=Conectarse(); // y me conecto. //dependiendo del tipo recupero uno u otro.
	$Sql='UPDATE tb_alumno SET unidad ="'.$unidadNueva.'"';
	$Sql.=' WHERE idalumno = "'.$_POST["alumno"].'"';
	mysqli_query($link,iconv("UTF-8","ISO-8859-15", $Sql)); // ejecuto el Sql
	mysqli_close($link); // Cierro el enlace...	
	$data["divs"].=$Sql." <--> ";	

echo json_encode($data);


// DESCRIPCION. IMPORTANTE, está como función dentro de la clase asignaciones...
/* 
if (strlen($_POST["asignacion"])>0) { // si existe la variable de profesor...
// $retahilalarga = $curso->devuelveAsignacionLarga($_POST["asignacion"]); //
$cursosen = $curso->devuelveCursosdeAsignacion($curso->devuelveAsignacionLarga($_POST["asignacion"]),0);
// Tipo 0 -> que devuelve cursos en formatos cortos. El 1 -> la unidad tal como aparece en Séneca
$cursosen = str_replace("#",", ",$cursosen);
} 

$descripcion = strtoupper("Asig: ".$_POST["nombremateria"].", Prof: ".iconv("ISO-8859-15","UTF-8",$nombreprofesor).", Cursos:  ".$cursosen);  
*/


?>



