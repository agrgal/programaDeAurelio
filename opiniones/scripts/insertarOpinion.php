<?php
header('Content-Type: text/html; charset=ISO-8859-15'); // importante; especifica el charset de caracteres.

// importante incluir al principio de cada una, lo de las funciones
include_once("../../configuracion/config.php"); // funciones de configuración
include_once("../../funciones/funciones.php"); // funciones varias de conexión a base de datos, etc.

// Incluyo además las clases que se van a usar
// include_once("../../clases/class.micalendario.php"); //clase mi calendario que presenta fechas formateadas
include_once("../../clases/class.formulario.php"); //clase que recupera datos de formularios
// include_once("../../clases/class.profesores.php"); //clase que recupera datos de profesores
include_once("../../clases/class.alumnos.php"); //clase que recupera datos de cursos
include_once("../../clases/class.opiniones.php"); //clase que recupera datos de las opiniones

// $calendario= New micalendario(); // variable de calendario.
$formulario = New formulario(); // variable de la clase formulario
// $profesores = New profesores(); // variable de la clase profesores
$alumnos = New misAlumnos(); // variable de la clase profesores
$opiniones = New misOpiniones(); // variable de la clase opiniones

session_start(); //activo variables de sesion

// echo $_POST["fecha"]." / ".$_POST["alumno"]
//   ." / ".$_POST["idasignacion"]." / ".$_POST["items"]." / "
//   .iconv("UTF-8","ISO-8859-15",$_POST["observaciones"]);

$formulario->nombreTabla="tb_opiniones"; // Lo primero que hay que nombrar la tabla
$formulario->nombreCampos(); // obtener el nombre de los campos
$formulario->nombreClavePrimaria();
$nC = $formulario->arrayNombreCampos; // y lo graba en la variable $nC
$valoresclaveprimaria = $formulario->datosPK(); // Obtiene los valores de clave primaria.

if (isset($_POST["idasignacion"]) && !(empty($_POST["fecha"])) && $_POST["alumno"]>0) {
	
	$alumnos->devuelveAlumno($_POST["alumno"]);
	$nombreAlumno = '<b>'.iconv("ISO-8859-15", "UTF-8", $alumnos->esteAlumno["nombre2"]).'</b>';
	$enFecha = '<b>'.fechaMySQL2DatePicker($_POST["fecha"]).'</b>';
	
	$valorid = $opiniones->checkOpinion($_POST["fecha"],$_POST["idasignacion"],$_POST["alumno"]); // ¿Cuánto vale el id, si es que existe?
        
	if (!(empty($_POST["items"])) || !(empty($_POST["observaciones"]))) { // Si no están vacíos los items ó las observaciones

       if ($valorid<=0) { // Si no existe la opinión se crea...
			
			// A) Grabar o modificar
			// 1º) Obtiene el valor de la clave primaria que hay que poner
			$escribeclaveprimaria = 1+$formulario->minmaxClavePrimaria(1); // añade uno al valor mayor de clave primaria
			// 2º) Obtiene el valor de la cadena Sql
			array_shift($nC["campo"]); // quita el primer elemento del array de los campos
			$valores=array($_POST["fecha"],$_POST["alumno"],$_POST["idasignacion"],$_POST["items"]
					      ,htmlentities($_POST["observaciones"])); // datos menos el idopinion
			// IMPORTANTE: pasar a HTML entities para escribir una cadena HTML en formato TEXTO PLANO... 
			// http://php.net/manual/en/function.htmlentities.php
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
			
			$htmlNotificacion = '<div><h1>Se ha guardado el dato para '.$nombreAlumno.' el día '.$enFecha.'</h1></div>';
			
			$dato=array("devolver"=>1,"observaciones"=>$_POST["observaciones"],"notificacion"=>$htmlNotificacion); // Un 1 es insertar
			// $dato=array("devolver"=>1,"observaciones"=>$_POST["observaciones"]); // Un 1 es insertar
			$dato_json=json_encode($dato);
			echo $dato_json;
			
		
	    } else { // en cambio, si existe la opinion se modifica
			
			// B) Modificar
			$valores=array($valorid,$_POST["fecha"],$_POST["alumno"],$_POST["idasignacion"],$_POST["items"]
					      ,htmlentities($_POST["observaciones"])); // datos con el idopinion	
			// IMPORTANTE: pasar a HTML entities para escribir una cadena HTML en formato TEXTO PLANO... 
			// http://php.net/manual/en/function.htmlentities.php	
			$Sql = "UPDATE ".$formulario->nombreTabla." SET ";
			foreach ($nC["campo"] as $clave => $valor)	{
				// $cadena.='<p>'.$clave.' -> '.$valor.'</p>';
				if ($valor!=$formulario->nombrePK)  { $Sql.=$valor.'="'.$valores[$clave].'", '; }
			}
			$Sql= substr($Sql,0,-2);
			$Sql.=' WHERE '.$formulario->nombrePK.'="'.$valorid.'"'; // recuerdo que el valor de sesión es el del ID.
			
			// echo $Sql;
			
			// Conexion
			$link=Conectarse(); // conect
			mysqli_query($link,iconv("UTF-8","ISO-8859-15", $Sql)); // ejecuto el Sql
			  // IMPORTANTE: como json_decode trata los caracteres como UTF-8, el resultado (cadena Sql) 
			  // hay que transformarlo a ISO-8859-15 para poder pasarlo a la base de datos.
			mysqli_close($link); // cierro la conexión
			
			$htmlNotificacion = '<div><h1>El dato existente de '.$nombreAlumno.' se ha salvado y/o modificado. Fecha: '.$enFecha.'</h1></div>';
			
			$dato=array("devolver"=>2,"observaciones"=>$_POST["observaciones"],"notificacion"=>$htmlNotificacion); // Un 2 es modificar
			// $dato=array("devolver"=>2,"observaciones"=>$_POST["observaciones"]); // Un 2 es modificar
			$dato_json=json_encode($dato);
			echo $dato_json;
			
		} // fin de modificar la opinion 	
	
	 } else { // else de la comprobación si existe o comentario u opiniones
		
		if ($valorid>0) { // Si no hay opiniones ni comentarios y existe el id, se borra
			
			if (IsNullOrEmptyString($_POST["items"]) || IsNullOrEmptyString(htmlentities($_POST["observaciones"]))) { // doble comprobación.
			
				// C) Borrado
				$Sql="DELETE FROM tb_opiniones WHERE id='%s'";
				// Conexion
				$link=Conectarse(); // conect
				$Sql = sprintf($Sql, mysqli_real_escape_string($link,$valorid)); // ojo, después de activar link...
				mysqli_query($link, $Sql); // ejecuto el Sql
				mysqli_close($link); // cierro la conexión
		
				$htmlNotificacion = '<div><h1>El dato se ha borrado (se borra cuando no escribes nada o no añades opiniones pre-establecidas). Alumno: '.$nombreAlumno.' Fecha: '.$enFecha.'</h1></div>';
		        $htmlNotificacion2 = '<div><h1>El dato se ha borrado. Alumno: '.$nombreAlumno.' Fecha: '.$enFecha.'</h1></div>';
		
				$dato=array("devolver"=>3,"observaciones"=>"Dato borrado"
				            ,"notificacion"=>$htmlNotificacion,"notificacion2"=>$htmlNotificacion2); // Un 3 es Borrar
				// $dato=array("devolver"=>3,"observaciones"=>"Dato borrado"); // Un 3 es Borrar
				$dato_json=json_encode($dato);
				echo $dato_json;
			
			} else {
				
				echo "No ha pasado la prueba de la doble comprobación";
			}
			
		} else { 
			
			echo "No se ha grabado nada puesto que nada has opinado...";
			
		} // fin del if de borrado
	 
	 } // fin del if que comprueba si hay comentarios u opiniones
	
} else {

	echo "Petición incorrecta";

} // fin de comprobar si existen esos datos...

?>



