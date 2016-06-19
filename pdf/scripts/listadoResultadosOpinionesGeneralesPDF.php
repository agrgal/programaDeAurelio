<?php
//header('Content-Type: text/html; charset=UTF-8'); // importante; especifica el charset de caracteres.
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
include_once("../../clases/class.opinionesGenerales.php"); //clase que recupera datos de opiniones Generales

// incluyo las variables de esas clases
$calendario = New micalendario(); // variable de calendario. Lo necesito para la cabecera
$profesorado = New profesores(); //variable de la clase profesores
// $formulario = New formulario(); // variable de la clase formulario
$curso = New misCursos(); // variable de la clase curso
$alumno = New misAlumnos(); // variable de la clase alumnos
$materia = New misMaterias(); // variable de la clase materia
$opiniones = New misOpinionesGenerales(); // variable de la clase opiniones Generales
$asignacion = New misAsignaciones($curso, $profesorado, $materia, $alumno); // Uso el constructor para pasarle la clase curso, profesorado, alumno y materias a Asignaciones

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
$title1 = 'Hoja de Opiniones Generales. Tutoría de '.$asignacion->listaDeAsignaciones['cursosAfectados'][$clave];
$title2= utf8_encode($_POST["sendCabecera"]);
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

// *****************************************************************************************************

// Contenido central html
if ($_POST["sendSQL"]) {
	// $datos = $opiniones->retornaValores($_POST["sendSQL"]);
	$datos = json_decode($opiniones->retornaValores($_POST["sendSQL"])); // retorna valores, pero de las Opiniones Generales.
	// $total=count($datos);
	// if ($total>0) { $apostilla = "<h2>Se han obtenido un total de ".$total." registros</h2>"; }
	$html="";	
	$firmar="";
	foreach ($datos as $clave => $valor) {
		// Obtiene los resultados
		$opinion=trim(iconv("UTF-8","ISO-8859-15",strip_tags($valor->{"opinion"})));
		$actuaciones=trim(iconv("UTF-8","ISO-8859-15",strip_tags($valor->{"actuaciones"})));
		$mejora=trim(iconv("UTF-8","ISO-8859-15",strip_tags($valor->{"mejora"})));
		// EMPAQUETAR
		$nombreProfesor = $asignacion->asignacionProfesor($valor->{"asignacion"},1);
		$nombreMateria = $asignacion->asignacionMateria($valor->{"asignacion"},1);
		if (($mejora) || ($actuaciones) || ($opinion)) {         
			$html.='<table class="listadoAlumnoTABLEMain">
						<tbody>
						<tr><th><b>Profesor/a:&nbsp;</b>'.$nombreProfesor.'</th><th><b>Asignatura:&nbsp;</b>'.$nombreMateria.'</th></tr>';
						if ($opinion) { $html.='<tr><td colspan="2"><b>Opiniones:&nbsp;</b>'.$opinion.'</td></tr>'; }
						if ($actuaciones) {$html.='<tr><td colspan="2"><b>Actuaciones:&nbsp;</b>'.$actuaciones.'</td></tr>';}
						if ($mejora) { $html.='<tr><td colspan="2"><b>Mejoras:&nbsp;</b>'.$mejora.'</td></tr>';}
			$html.='</tbody>
						</table></br><img src="../../imagenes/iconos/divider1.png" class="separador"></br>';	
			$firmar.='<div class="firmas">'.$nombreProfesor.'</div>';
		} // Fin del if comprueba nulidad
	} // Fin del foreach 
} // Fin del IF 

$html = utf8_encode($html.$firmar);
$html = iconv("UTF-8","ISO-8859-15",$html); // No sé por qué, pero funciona mejor así,y sin embargo, $title2 no se le puede poner ¿¿??
// $html = str_replace("./upload", "../../upload", $html);
// $html = str_replace('src="./imagenes/', 'style="width: 75px; height:auto; margin-right: 10px;" src="../../imagenes/', $html);
// $html = htmlspecialchars($html, ENT_QUOTES);
// Fin del html 

// $html = "<h1>".$_SESSION['permisos']." - ".$_SESSION['tutor']." - ".$_SESSION['profesor']." - ".$_SESSION['idasignacion']."</h1>";

// *****************************************************************************************************

// *********************
// Generar PDF
// *********************

$mpdf=new mPDF('es', 'A4', 0, '', 10, 10, 30, 15, 5, 5); 
// The last parameters are all margin values in millimetres: left-margin, right-margin, top-margin, bottom-margin, header-margin, footer-margin.
// A4-L en horizontal
$mpdf->allow_charset_conversion=true;  // Set by default to TRUE
$mpdf->charset_in='windows-1252';

$mpdf->SetDisplayMode('fullpage');
$mpdf->shrink_tables_to_fit=0;

// Empieza la página
// $stylesheet = file_get_contents('../../css/estiloobtenerDatosTutor.css'); // carga hoja de estilos
$stylesheet = file_get_contents('../../css/verprintOpinionesGenerales.css'); // carga hoja de estilos
$mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text

$mpdf->SetHTMLHeader($cabecera); // Imprime cabecera
$mpdf->SetHTMLFooter('<div class="footer"><p>Página  {PAGENO}/{nbpg} - '.$horafecha.'</p></div>'); // imprime pie de página

include_once('watermark.php'); // Incluye la marca de agua

$mpdf->WriteHTML($html); // Aquí se pone el contenido...
// $mpdf->WriteHTML(utf8_encode($html));
$mpdf->Output('../../pdf/listadoDeResultadosOpinionesGenerales.pdf','F');
header("Location: ../../pdf/scripts/descargaficheropdf.php?fichero=listadoDeResultadosOpinionesGenerales.pdf&ruta=../../pdf/&nombre=".$title1.".pdf");	
exit;
?>



