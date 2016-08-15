<?php
header('Content-Type: text/html; charset=ISO-8859-15'); // importante; especifica el charset de caracteres.

// importante incluir al principio de cada una, lo de las funciones
include_once("../../configuracion/config.php"); // funciones de configuración
include_once("../../funciones/funciones.php"); // funciones varias de conexión a base de datos, etc.

// Incluyo además las clases que se van a usar
include_once("../../clases/class.micalendario.php"); //clase mi calendario que presenta fechas formateadas
include_once("../../clases/class.profesores.php"); //clase que recupera datos de profesores
// include_once("./clases/class.formulario.php"); //clase que gestiona diversos formularios
include_once("../../clases/class.cursos.php"); //clase que recupera datos de cursos
include_once("../../clases/class.alumnos.php"); //clase que recupera datos de alumnos
include_once("../../clases/class.materias.php"); //clase que recupera datos de materias
include_once("../../clases/class.asignaciones.php"); //clase que recupera datos de asignaciones
// include_once("./clases/class.opiniones.php"); //clase que recupera datos de opiniones

// incluyo las variables de esas clases
$calendario = New micalendario(); // variable de calendario. Lo necesito para la cabecera
$profesorado = New profesores(); //variable de la clase profesores
// $formulario = New formulario(); // variable de la clase formulario
$curso = New misCursos(); // variable de la clase curso
$alumno = New misAlumnos(); // variable de la clase alumnos
$materia = New misMaterias(); // variable de la clase materia
// $opiniones = New misOpiniones(); // variable de la clase opiniones
$asignacion = New misAsignaciones($curso, $profesorado, $materia); // Uso el constructor para pasarle la clase curso, profesorado y materias a Asignaciones

// Incluir biblioteca mpdf

include("../../mpdf/mpdf.php");

// Variables de sesión
session_start();

// *********************
// Construcción del html
// *********************

$asignacion->listarAsignaciones($_SESSION['profesor']); 
$clave=array_search($_SESSION['idasignacion'],$asignacion->listaDeAsignaciones['idasignacion']);  
				

// Empiezo el pdf
// Declaro variables
$title1 = 'Tutoría de '.$asignacion->listaDeAsignaciones['cursosAfectados'][$clave];
$title2= 'Lista de clase. Ordenados por Apellidos.';
$horafecha='Fecha: '.$calendario->fechaformateada($calendario->fechadehoy());
$horafecha.=' - Hora: '.$calendario->horactual();
$h=$horafecha;

// Cabecera
$cabecera='<table style="width: 100%;"><tr><td rowspan="2" style="width: 20% text-align: left;">';
$cabecera.='<img width="15%" heigth="auto" src="../../imagenes/logo.png"></td>';
$cabecera.='<td rowspan="2" style="width: 5% text-align: left;"></td>';
$cabecera.='<td style="width: 80%; text-align: center;"><h1 class="titulo" style="font-size:15px;">'.$title1.'</h1></tr>';
$cabecera.='<tr><td style="width: 80%; text-align: center;"><h1 class="subtitulo" style="font-size:13px;">'.$title2.'</h1></td></tr></tr></table>';
// $cabecera.='<h2 class="subtitulo" style="font-size:14px;">'.$alumno['unidad'][$_SESSION['contador']].'</h2></td></tr></table>';

// Contenido central html

$numcolumnasAnexas = 10;

$html ='<table class="tg" style="undefined;table-layout: fixed; width: 800px;">
<colgroup>
<col style="width: 20px;">
<col style="width: 60px;">';
for ($i=0;$i<$numcolumnasAnexas;$i++) {
  $html.='<col style="width: 20px;">';
}
$html.='</colgroup>
  <tr>
    <th class="tg-zk0t">Id<br></th>
    <th class="tg-bzvj">Nombre del alumno/a<br></th>';
	for ($i=0;$i<$numcolumnasAnexas;$i++) {
	  $html.='<th class="tg-h0x1"></td>';
	}
$html.=' </tr>';

$alumnado = $asignacion->devuelveListadoAlumnosdeEstaAsignacion($_SESSION["idasignacion"],$_SESSION["profesor"]);
$alumnadoArray=explode("#",$alumnado); // devuelve un array con los números de alumnos...
  $norden = 0;
  foreach ($alumnadoArray as $clave => $i) {
	  $norden++;
	  $alumno->devuelveAlumno($i);
	  // $html.='<tr><td class="tg-1y0x">'.$norden.' ('.$alumno->esteAlumno["idalumno"].')</td>';
	  $html.='<tr><td class="tg-1y0x">'.$norden.'</td>';
	  $html.='<td class="tg-zmy4">'.$alumno->esteAlumno["nombre2"].'</td>';
	  for ($i=0;$i<$numcolumnasAnexas;$i++) {
		  $html.='<td class="tg-h0x1"></td>';
	  }
	  $html.='</tr>';
  }

$html.="</table>"; // Fin del html 

// $html = "<h1>".$_SESSION['permisos']." - ".$_SESSION['tutor']." - ".$_SESSION['profesor']." - ".$_SESSION['idasignacion']."</h1>";

// *********************

// *********************
// Generar PDF
// *********************

$mpdf=new mPDF('es', 'A4', 0, '', 10, 10, 20, 15, 5, 5); 
// The last parameters are all margin values in millimetres: left-margin, right-margin, top-margin, bottom-margin, header-margin, footer-margin.
// A4-L en horizontal
$mpdf->allow_charset_conversion=true;  // Set by default to TRUE
$mpdf->charset_in='windows-1252';

$mpdf->SetDisplayMode('fullpage');
$mpdf->shrink_tables_to_fit=0;

// Empieza la página
$stylesheet = file_get_contents('../../css/verprint.css'); // carga hoja de estilos
$mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text

$mpdf->SetHTMLHeader($cabecera); // Imprime cabecera
$mpdf->SetHTMLFooter('<div class="footer"><p>Página  {PAGENO}/{nbpg} - '.$horafecha.'</p></div>'); // imprime pie de página

include_once('watermark.php'); // Incluye la marca de agua

$mpdf->WriteHTML(utf8_encode($html));
$mpdf->Output('../../pdf/listadoAlumnos.pdf','F');
header("Location: ../../pdf/scripts/descargaficheropdf.php?fichero=listadoAlumnos.pdf&ruta=../../pdf/&nombre=".$title1.".pdf");	
exit;
?>



