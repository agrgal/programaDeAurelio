<?php
header('Content-Type: text/html; charset=ISO-8859-15'); // importante; especifica el charset de caracteres.

// importante incluir al principio de cada una, lo de las funciones
include_once("../../configuracion/config.php"); // funciones de configuración
include_once("../../funciones/funciones.php"); // funciones varias de conexión a base de datos, etc.

// Incluyo además las clases que se van a usar
// include_once("../../clases/class.micalendario.php"); //clase mi calendario que presenta fechas formateadas
// include_once("../../clases/class.formulario.php"); //clase que recupera datos de formularios
include_once("../../clases/class.profesores.php"); //clase que recupera datos de profesores
include_once("../../clases/class.cursos.php"); //clase que recupera datos de cursos
include_once("../../clases/class.asignaciones.php"); //clase que recupera datos de alumnos
include_once("../../clases/class.alumnos.php"); //clase que recupera datos de alumnos
include_once("../../clases/class.materias.php"); //clase que recupera datos de materias
include_once("../../clases/class.opiniones.php"); //clase que recupera datos de las opiniones

// $calendario= New micalendario(); // variable de calendario.
// $formulario = New formulario(); // variable de la clase formulario
$profesores = New profesores(); // variable de la clase profesores
$curso = New misCursos(); // variable de la clase profesores
$materia = New misMaterias(); // variable de la clase alumnos
$alumnos = New misAlumnos(); // variable de la clase alumnos
$asignaciones = New misAsignaciones($curso, $profesores, $materia, $alumnos);
$opiniones = New misOpiniones(); // variable de la clase opiniones

session_start(); //activo variables de sesion

// echo $_POST["SQL"]." - ".$_POST["posneg"];

$data=[];
$data["tipo"]=0; // por defecto, para que retorne algo, aunque sea tipo cero

if ($_POST["asignacion"]==0) {$asignacion=$_SESSION["idasignacion"];} else {$asignacion=$_POST["asignacion"];}

$data["curso"]=$curso->devuelveAlumnosAsignacion($asignacion);

if ($_POST["SQL"]) {
	$arrayResultados = $opiniones->itemsEstadisticaPorAlumno($_POST["SQL"]);	
	if (count($arrayResultados)>0) { // Si obtiene resultados...
		$alum=[]; $itemspositivos=[]; $itemsnegativos=[]; $cadenas=[]; $items=[]; $alumnNombres=[];
		// Separo cada alumno de los demás
		$resultados=explode("*",$arrayResultados);
		// 1º) Obtiene una lista de todos los datos
		foreach ($resultados as $clave=>$valor) {
			$dato=explode("-",$valor);
			// $alumn[]=iconv("ISO-8859-15", "UTF-8",$alumnos->devuelveNombreAlumno($dato[0]));
			$alumn[]=$dato[0]; //
			$cadenas[]=$dato[1];
		} // Fin del foreach
		// 2º) alumnado cadenas únicas. Ordenado.
		$alumnadoNoRepetido=array_unique($alumn); sort($alumnadoNoRepetido);
		// 2ºbis) Alumnado de la asignación, que es el QUE DEBE APARECER EN LA ESTADíSTICA, no el extraído.
		$alumnadoAsignacion = explode("#",$curso->devuelveAlumnosAsignacion($asignacion));
		// 3º) Por cada alumnado no repetido, consigo en items una cadena 
		foreach ($alumnadoAsignacion as $clave => $valor) { // Debe aparecer aquí alumnadoAsignación, NO alumnadoNoRepetido.
			$items[$clave]="";
			$alumnNombres[]=iconv("ISO-8859-15", "UTF-8",$alumnos->devuelveNombreAlumno($valor)." (".($clave+1).")");
			foreach ($alumn as $clave2 => $valor2) { // por cada alumno
				if ($valor==$valor2) {
					if (strlen($cadenas[$clave2])>0) { $items[$clave].=$cadenas[$clave2]."#";}
				}
			} // fin del foreach de cada alumno
			
			if (strlen($items[$clave])>0 and substr($items[$clave],-1)=="#") {	
				$items[$clave]=substr($items[$clave],0,strlen($items[$clave])-1); 
			} // Quitar el último #
					
		} // Fin del foreach de cada alumnado no repetido
		

		// 4º) Extraer los datos de los positivos y negativos

		foreach ($items as $clave => $valor) {	
			$cadenaitems=explode("#",$valor); 
			$pos=0; $neg=0;
			foreach ($cadenaitems as $clave2=>$valor2) {
				if (!is_null($valor2)) {
					$data["tipo"]=1; // tiene datos...
					$informacionItem = json_decode($opiniones->retornaItem($valor2));
					if ($informacionItem->{"positivo"}==0 and !is_null($informacionItem->{"positivo"})) { $neg+=1; }	// Suma uno si es negativo
					if ($informacionItem->{"positivo"}==1 and !is_null($informacionItem->{"positivo"})) { $pos+=1; }	// Suma uno si es positivo
				}
			} // Fin del for each de cadena de items...
			$itemspositivos[$clave]=$pos;
			$itemsnegativos[$clave]=$neg; 
		 } // Fin del for each de resultados 
			
		// $data["alumnos"]= $alumn;	
		// $data["positivos"]=$cadenas;
		
		$mediapositivos = mediaArray($itemspositivos);
		$desviaciontipicapositivos = desviaciontipicaArray($itemspositivos);
		$medianegativos = mediaArray($itemsnegativos);
		$desviaciontipicanegativos = desviaciontipicaArray($itemsnegativos);
		
		// Medida de una clase
		$medida = (array_sum($itemspositivos)-array_sum($itemsnegativos));
		if ($medida>0) { $medida = round($medida*100/(array_sum($itemspositivos)+0.1),2); }
		if ($medida<=0) { $medida = round($medida*100/(array_sum($itemsnegativos)+0.1),2); }
		
		// Mostrar las medias
		$alumnNombres[]="MEDIAS [POS:".round($mediapositivos,2)." , NEG:".round($medianegativos,2)."]";
		$itemspositivos[]=round($mediapositivos,2);
		$itemsnegativos[]=round($medianegativos,2);
		
		// Mostrar las desviaciones típicas
		$alumnNombres[]="DES.EST. [POS:".round($desviaciontipicapositivos,2)." , NEG:".round($desviaciontipicanegativos,2)."]";
		$itemspositivos[]=round($desviaciontipicapositivos,2);
		$itemsnegativos[]=round($desviaciontipicanegativos,2);
		
		// Mostrar la medida del curso
		$alumnNombres[]="MEDIDA DEL CURSO: ".$medida."%";
		if ($medida>0) {$itemspositivos[]=$medida; } else {$itemspositivos[]=0; }
		if ($medida<=0) {$itemsnegativos[]=abs($medida); } else {$itemsnegativos[]=0; }
		
		// información estadística
		$data["maxpositivos"]=max($itemspositivos); // máximo de uno
		$data["maxnegativos"]=max($itemsnegativos); // máximo de otro	
		
		$data["alumnos"]= $alumnNombres;	// Alumnos sin repetir, sus nombres
		// $data["positivos"]=$items;
		
		// $data["alumnos"]= $alumn;	
		$data["positivos"]=$itemspositivos; // Items positivos
		$data["negativos"]=$itemsnegativos; // Items negativos
		
		$data["asignacion"]=$_POST["asignacion"];
	
	} // Fin de si no es null. Si es NULL retornara tipo 0...
	
} // Fin del $_POST...

	echo json_encode($data);
	
?>



