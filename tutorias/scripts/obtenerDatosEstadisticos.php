<?php
header('Content-Type: text/html; charset=ISO-8859-15'); // importante; especifica el charset de caracteres.

// importante incluir al principio de cada una, lo de las funciones
include_once("../../configuracion/config.php"); // funciones de configuración
include_once("../../funciones/funciones.php"); // funciones varias de conexión a base de datos, etc.

// Incluyo además las clases que se van a usar
// include_once("../../clases/class.micalendario.php"); //clase mi calendario que presenta fechas formateadas
// include_once("../../clases/class.formulario.php"); //clase que recupera datos de formularios
include_once("../../clases/class.profesores.php"); //clase que recupera datos de profesores
// include_once("../../clases/class.cursos.php"); //clase que recupera datos de cursos
include_once("../../clases/class.asignaciones.php"); //clase que recupera datos de alumnos
include_once("../../clases/class.alumnos.php"); //clase que recupera datos de alumnos
include_once("../../clases/class.materias.php"); //clase que recupera datos de materias
include_once("../../clases/class.opiniones.php"); //clase que recupera datos de las opiniones

// $calendario= New micalendario(); // variable de calendario.
// $formulario = New formulario(); // variable de la clase formulario
$profesores = New profesores(); // variable de la clase profesores
// $curso = New misCursos(); // variable de la clase profesores
$materia = New misMaterias(); // variable de la clase alumnos
$alumnos = New misAlumnos(); // variable de la clase alumnos
$asignaciones = New misAsignaciones($curso, $profesores, $materia, $alumnos);
$opiniones = New misOpiniones(); // variable de la clase opiniones

// session_start(); //activo variables de sesion

// echo $_POST["SQL"]." - ".$_POST["posneg"];

if ($_POST["SQL"]) {
	$arrayResultados = $opiniones->itemsEstadistica($_POST["SQL"]);	
	$items=[]; $itemspositivos=[]; $itemsnegativos=[]; $frecuencias=[]; $posneg=[]; $data=[]; $negativos=[]; $positivos=[];
	foreach ($arrayResultados as $clave=>$valor) {			
		$retorna = json_decode($opiniones->retornaItem($clave)); 
		if ($_POST["posneg"]==1 and $retorna->{"positivo"}>=2) {
			$data["tipo"]=2; // tipo de gráfica
			$items[]=$retorna->{"item"}." (".$retorna->{"grupo"}.")";
			$frecuencias[]=$valor;
			$posneg[]=2; // color de las barras 
		} else if ($_POST["posneg"]==0 and $retorna->{"positivo"}<=1) {
			$data["tipo"]=1; // tipo de gráfica
			// $items[]=$retorna->{"item"}." (".$retorna->{"grupo"}.")";
			// $frecuencias[]=$valor;
			if ($retorna->{"positivo"}==0) { $negativos[]=$valor; $itemsnegativos[]=$retorna->{"item"}." (".$retorna->{"grupo"}.")"; } // asignacion por tipos
			if ($retorna->{"positivo"}==1) { $positivos[]=$valor; $itemspositivos[]=$retorna->{"item"}." (".$retorna->{"grupo"}.")"; } // asignacion por tipos
		}
	}
	
	// valores
	if ($data["tipo"]==2) {
		$data["items"]=$items;
		$data["frecuencias"]=$frecuencias;
		$data["posneg"]=$posneg;
		// Calculando datos
		$data["promedio"]=round(mediaArray($frecuencias),2); // definido en FUNCIONES
		$data["desestandard"]=round(desviaciontipicaArray($frecuencias),2);
		$data["varianza"]=round(varianzaArray($frecuencias),2);
	} elseif ($data["tipo"]==1) {
		$data["items"]=array_merge($itemspositivos,$itemsnegativos);
		$data["frecuencias"]=array_merge($positivos,$negativos);
		$posneg = array_merge(array_fill(0,count($positivos),1),array_fill(0,count($negativos),0));
		$data["posneg"]=$posneg;
		// Calculando datos
		$data["promedioPOS"]=round(mediaArray($positivos),2); // definido en FUNCIONES
		$data["desestandardPOS"]=round(desviaciontipicaArray($positivos),2);
		$data["varianzaPOS"]=round(varianzaArray($positivos),2);
		$data["promedioNEG"]=round(mediaArray($negativos),2); // definido en FUNCIONES
		$data["desestandardNEG"]=round(desviaciontipicaArray($negativos),2);
		$data["varianzaNEG"]=round(varianzaArray($negativos),2);
	}
		
	echo json_encode($data);
	// echo json_encode($arrayResultados);
} // Fin del $_POST...
  
?>



