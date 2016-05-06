<?php
header('Content-Type: text/html; charset=ISO-8859-15'); // importante; especifica el charset de caracteres.

// importante incluir al principio de cada una, lo de las funciones
include_once("../../configuracion/config.php"); // funciones de configuraciÃ³n
include_once("../../funciones/funciones.php"); // funciones varias de conexiÃ³n a base de datos, etc.

// Incluyo ademÃ¡s las clases que se van a usar
include_once("../../clases/class.micalendario.php"); //clase mi calendario que presenta fechas formateadas
 include_once("../../clases/class.profesores.php"); //clase que recupera datos de profesores
// include_once("./clases/class.formulario.php"); //clase que gestiona diversos formularios
include_once("../../clases/class.cursos.php"); //clase que recupera datos de cursos
include_once("../../clases/class.alumnos.php"); //clase que recupera datos de alumnos
include_once("../../clases/class.materias.php"); //clase que recupera datos de materias
include_once("../../clases/class.asignaciones.php"); //clase que recupera datos de materias
include_once("../../clases/class.opiniones.php"); //clase que recupera datos de opiniones
include_once("../../clases/class.opinionesHistorico.php"); //clase que recupera datos de opiniones

// incluyo las variables de esas clases
$calendario = New micalendario(); // variable de calendario. Lo necesito para la cabecera
$profesorado = New profesores(); //variable de la clase profesores
// $formulario = New formulario(); // variable de la clase formulario
$curso = New misCursos(); // variable de la clase curso
$alumno = New misAlumnos(); // variable de la clase alumnos
$materia = New misMaterias(); // variable de la clase materia
$opiniones = New misOpiniones(); // variable de la clase opiniones
$asignacion = New misAsignaciones($curso, $profesorado, $materia); // Uso el constructor para pasarle la clase curso, profesorado y materias a Asignaciones
// $opinionesHistorico = New misOpinionesHistorico($curso,$opiniones); // Uso el constructor y le paso la clase opiniones y alumno

session_start(); //activo variables de sesion

$devuelve="";

$alumnado =  explode("#",$asignacion->devuelveListadoAlumnosdeEstaAsignacion($_SESSION["idasignacion"],$_SESSION["profesor"]));

// Imagen de elección negativa
$elegir = '<img class="eleccion" src="./imagenes/iconos/no.png" style="padding: 3px 3px;">';

foreach($alumnado as $clave=>$valor) {
   $alumno->devuelveAlumno($valor);
   // retahíla de datos
   $nombre = $alumno->esteAlumno["nombre2"];
   // Incluir foto, si existe
   // $foto = '<img src="./imagenes/iconos/chicochica.png" width="75" height="100" style="padding: 3px 3px;">';   
   // $imagen = '<img src="'.buscarFoto($valor).'" width="75" height="100" style="padding: 3px 3px;">'; 
   $imagen = '<img src="'.$alumno->esteAlumno["foto"].'" width="75" height="100" style="padding: 3px 3px;">';   
   $datos = json_decode($opiniones->divOpinionResumen($_POST["fecha"],$_SESSION["idasignacion"],$valor),true); // recupera los datos de ese alumno en esa fecha y esa asignacion
   // id de la opinion
   $idopinion=$datos["id"];
   // Retahíla de items
   $items = $opiniones->itemsElegidos($datos["items"]);
   if (is_null($items)) { $items="No hay items escritos en la base de datos"; }
   else { $items="<b>ITEMS:&nbsp;</b>".$items; }
   // Observaciones
   $observaciones = $datos["observaciones"];
   if (is_null($observaciones)) { $observaciones="No se ha escrito ninguna observación en la base de datos"; }
   else { 
	   $observaciones="<b>OBSERVACIONES:&nbsp;</b>".iconv( "UTF-8","ISO-8859-15",strip_tags($datos["observaciones"])); 
       $observaciones2 = iconv( "UTF-8","ISO-8859-15",$datos["observaciones"]);
   }
   
   // $devuelve.='<div id="'.$valor.'">'.$divAlumno.$datos["div"].'</div>';	
   
   if (!(is_null($opiniones->itemsElegidos($datos["items"])) && is_null($datos["observaciones"]))) {         
   $devuelve.='<table id="'.$idopinion.'" class="tablaOH" elegir="0" alumno="'.$valor.'" items="'.$datos["items"].'" observaciones="'.$observaciones2.'" >
              <tr><td rowspan="3">'.$elegir.'</td><td id="'.$valor.'" rowspan="3">'.$imagen.'</td><td class="nombreAlumno"><p>'.$nombre.'</p></td></tr>
              <tr><td>'.$items.'</td></tr>
              <tr><td>'.$observaciones.'</td></tr>
              </table><hr class="separador">';
   } // Fin del if comprueba nulidad
}		
		
echo $devuelve;

/* ANULADA. Puesto el reconocimiento de la foto en class.alumnos
// Necesario para encontrar la fotografía.. Eso de los dos puntos y la ruta...
function buscarFoto($id) {
	 if (file_exists("../../upload/".$id.".png")) {
		 $foto = "./upload/".$id.".png";	
	 } else if (file_exists("../../upload/".$id.".jpg")) {
		 $foto = "./upload/".$id.".jpg";
	 } else {
		$foto = "./imagenes/iconos/chicochica.png";
	 } 
	 return $foto;
} */
  
?>



