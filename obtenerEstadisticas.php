<?php header('Content-type: text/html; charset=iso-8859-15'); ?>
<?php
// Codigo que se ejecuta al principio de la p�gina
// importante incluir al principio de cada una, lo de las funciones
include_once("./funciones/funciones.php"); // funciones varias de conexi�n a base de datos, etc.

// Incluyo adem�s las clases que se van a usar
include_once("./clases/class.micalendario.php"); //clase mi calendario que presenta fechas formateadas
include_once("./clases/class.profesores.php"); //clase que recupera datos de profesores
// include_once("./clases/class.formulario.php"); //clase que gestiona diversos formularios
include_once("./clases/class.cursos.php"); //clase que recupera datos de cursos
include_once("./clases/class.alumnos.php"); //clase que recupera datos de alumnos
include_once("./clases/class.materias.php"); //clase que recupera datos de materias
include_once("./clases/class.asignaciones.php"); //clase que recupera datos de materias
include_once("./clases/class.opiniones.php"); //clase que recupera datos de opiniones
include_once("./clases/class.evaluaciones.php"); //clase que recupera datos de evaluaciones

// incluyo las variables de esas clases
$calendario = New micalendario(); // variable de calendario. Lo necesito para la cabecera
$profesorado = New profesores(); //variable de la clase profesores
// $formulario = New formulario(); // variable de la clase formulario
$curso = New misCursos(); // variable de la clase curso
$alumno = New misAlumnos(); // variable de la clase alumnos
$materia = New misMaterias(); // variable de la clase materia
$opiniones = New misOpiniones(); // variable de la clase opiniones
$asignacion = New misAsignaciones($curso, $profesorado, $materia, $alumno); // Uso el constructor para pasarle la clase curso, profesorado, alumno y materias a Asignaciones
$evaluacion = New misEvaluaciones(); // variable de la clase evaluaciones

// Variables de sesi�n
session_start();

if (!(($_SESSION["permisos"]>=1) && ($_SESSION["tutor"]>=1))) { // en caso que no tenga permisos para entrar
	echo header("Location: ./index.php");
}

if ($_SESSION["permisos"]==2) { $mostrar="text"; } else {  $mostrar="none"; } // Si eres administrador puedes ver cierto contenido...

// Las variables de sesi�n se establecen en los scripts AJAX en 

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="es">	
<head profile="http://www.w3.org/2005/10/profile">
  <!-- *** Principio del HEAD *************************************-->	
  <meta content="text/html; charset=iso-8859-15" http-equiv="content-type">
  <title>Obtengo datos estad�sticos</title>
  <link rel="icon" 
        type="image/png" 
        href="./imagenes/logoA.png">
  <meta content="Aurelio Gallardo Rodr�guez" name="author">  
  <meta content="Obtengo datos estad�sticos" name="description">

  <!-- ***************************** -->
  <!-- Linkar a hojas de estilo CSS -->
  <!-- ***************************** -->
  <?php include_once("./css/cargarestiloscss.php"); ?>
  <link rel="stylesheet" href="./css/estiloobtenerDatosEstadisticas.css"> <!-- Efectos aplicados a esta hoja -->
  
  <!-- *** Final del HEAD, antes los ficheros de enlace a CSS ******-->
</head>

<body>
<!-- **************************************************************************-->	
<!-- *** Principio del BODY ***************************************************-->	
<!-- **************************************************************************-->	

<div id="container"> <!-- CONTENEDOR PRINCIPAL -->
	
	<!-- HTML suelto: barra superior, menu superior, menu lateral,   -->
	<?php include_once("./htmlsuelto/cabecera.php"); ?>
	<?php include_once("./htmlsuelto/barrasuperior.php"); // y dentro de �l se incluye el men� correspondiente ?> 
	<div id="menu" style="text-align: justify; padding-right: 1em;">
		<?php include_once("./htmlsuelto/menuIZQUIERDO.php"); ?> 
 	</div>     
    
    <!-- ******************************************* -->
    <!-- ******** ZONA DE C�LCULOS PREVIOS ********* -->
    <!-- ******************************************* -->
    <?php // Zona en la que se extraen variables de las distintas clases

    ?>    
    <!-- *********************************************************** -->
     
	<div id="test"> <!-- TESTER -->
	    <p id="testear">
	    </p>
    </div>	<!-- TESTER -->
    
    <!-- ********************************************************** -->
    <!-- Contenido Principal -->
    <!-- ********************************************************** --> 
    
    <div id="contents"> <!-- &&&& -->   
		
		<div id="pesta�as">
            <ul>
				<li><a href="#FiltrodeDatos">Filtro de Datos</a></li>
				<li><a href="#EstITEMS">Estad�sticas de items</a></li>
				<li><a href="#EstALUMNOS">Estad�sticas por alumno/a</a></li>
				<li><a href="#Instrucciones">Instrucciones</a></li>				
			</ul> 
			<!-- ********************************************************** -->
			<!-- Filtrado de datos -->
			<!-- ********************************************************** --> 			
			<div id="FiltrodeDatos">
				<!-- Selecci�n de datos -->
				<div id="contienepestannas2">
				<div id="pesta�as2">
					<ul>
					<li><a href="#PorAsignacion">Por asignaci�n</a></li>
					<li><a href="#Fechas">Por Fecha</a></li>	
					<li><a href="#Opciones">Opciones</a></li>	
					</ul>
						<div id="PorAsignacion">
							<?php // Select para seleccionar asignaciones
							$asignacionesmiTutoria = $asignacion->devuelveAsignacionesDeUnaTutoria($_SESSION["idasignacion"],$_SESSION["profesor"]);
							echo '<div id="asignaciones" title="Escoge la asignaci�n de Filtrado">';
							echo '<select name="EscogerAsignaciones" id="EscogerAsignacion" class="seleccionaidasignacion">';							
							echo '<option value="0">Todas las asignaciones</option>';
							// asignacion del option
							foreach($asignacionesmiTutoria as $clave => $valor) {
								echo '<option value="'.$valor.'">'.$asignacion->asignacionDescripcion($valor).'</option>';
							}
							echo '</select>';
							echo '</div>';
							?>
						</div>
						<div id="Fechas">
							<div id="cualquierFecha"><div class="divNombreEval" style="padding: 10px;">Cualquier fecha</div></div>
							<hr style="text-align: center; width: 80%;">
							<div id="fechaInicio">
								Inicial: 
								<input id="fechaINI" READONLY alt="Pulsa para obtener o cambiar la fecha inicial del intervalo" title="Pulsa para obtener o cambiar la fecha inicial del intervalo">
								<input id="muestrafechaINI" style="display: <?php echo $mostrar; ?> ;">
							</div>
							<div id="fechaFinal">
								Final: 
								<input id="fechaFIN" READONLY alt="Pulsa para obtener o cambiar la fecha final del intervalo" title="Pulsa para obtener o cambiar la fecha final del intervalo">
								<input id="muestrafechaFIN" style="display: <?php echo $mostrar; ?>  ;">
							</div>
							<hr style="text-align: center; width: 80%;">
							<div id="primerTrimestre"><?php echo $evaluacion->listadoDeEvaluaciones["div"][0]; ?></div>
							<div id="segundoTrimestre"><?php echo $evaluacion->listadoDeEvaluaciones["div"][1]; ?></div>
							<div id="tercerTrimestre"><?php echo $evaluacion->listadoDeEvaluaciones["div"][2]; ?></div>
							<div id="quincedias"><div class="divNombreEval" style="padding: 10px;">Hace quince d�as</div></div>
							<div id="haceunmes"><div class="divNombreEval" style="padding: 10px;">Hace un mes</div></div>
							<div id="hacedosmeses"><div class="divNombreEval" style="padding: 10px;">Hace dos meses</div></div>
							<p id="textoFecha" style="display: <?php echo $mostrar; ?>  ;">Cualquier Fecha</p>
							<hr style="text-align: center; width: 80%;">
						</div>
						<div id="Opciones">
							<div id="QuieroPosNegSN"><h3>Elige si quieres los resultados de Items Positivos-Negativos o los otros</h3></div>
						    <div id="PosNeg" title="Elige si quieres en la estad�stica items positivos-negativos o de otro tipo"></div>						   
						</div> 
					<!-- <h3>Por Item</h3>
						<div>Items</div> NO SE SI PONER POR ITEMS --> 
				</div> <!-- Fin de pesta�as2 -->
				</div> <!-- Contiene pesta�as2 -->
				<!-- Escritura de cadena SQL -->
				<div id="CadenaSQL">
					<h1>Condiciones</h1>
					<p id="condiciones"></p>
					<h1 style="display: <?php echo $mostrar; ?> ;">Cadena SQL</h1>
					<p style="display: <?php echo $mostrar; ?> ;" id="SQL"></p> <!-- poner 'none' para ocultarlo -->
					<!-- <p style="display: <?php echo $mostrar; ?> ;" id="SQL2"></p> <!-- poner 'none' para ocultarlo -->
					<button id="go">Mostrar Datos</button>
				</div>
			</div>

			<!-- ********************************************************** -->
			<!-- Mostrar los datos que se han filtrado -->
			<!-- ********************************************************** --> 	
			<div id="EstITEMS">
				<!-- Dibujo de la impresora -->
				<!-- 
				<div id="printer" title="Imprime resultados" title="Imprime resultados" >
					<!-- <a id="imprimir" href="./pdf/scripts/listadoResultadosPDF.php" ><img src="./imagenes/iconos/printer_pdf.png"></a> -->
					<!-- <form id="formularioImprimir" action="./pdf/scripts/listadoResultadosPDF.php" method="POST">
						<input id="sendCabecera" name="sendCabecera" type="text" style="display: none;" value="">
						<input id="sendContenido" name="sendContenido" type="text" style="display: none;" value="">
						<!-- <button type="submit"><img src="./imagenes/iconos/printer_pdf.png"></button> -->
						<!-- <a id="imprimir" ><img src="./imagenes/iconos/printer_pdf.png"></a>
					</form>
				</div> -->
				<div id="MostrarDatos">	<!-- Aqu� se inserta el HTML que muestra los datos -->			
					<!-- <canvas id="Grafica" width="400" height="200"></canvas> <!-- Gr�fica con las estad�sticas -->
					<div id="Grafica"></div>
				</div>
			</div>
			<!-- ********************************************************** -->
			<!-- Muestra datos por alumnos -->
			<!-- ********************************************************** --> 
			<div id="EstALUMNOS">
				<div id="MostrarDatos2">	<!-- Aqu� se inserta el HTML que muestra los datos -->			
					<!-- <canvas id="Grafica" width="400" height="200"></canvas> <!-- Gr�fica con las estad�sticas -->
					<div id="Grafica2"></div>
				</div>
			</div>
			<!-- ********************************************************** -->
			<!-- Insertar instrucciones -->
			<!-- ********************************************************** --> 
			<div id="Instrucciones">
				
			</div>
		</div>
			
	</div> <!-- &&&& FIN DEL CONTENEDOR-->	

	<!-- ********************************************************** -->
	<!-- FIN DEL CONTENIDO PRINCIPAL -->
	<!-- ********************************************************** -->
    
    <!-- HTML suelto: pie de p�gina *******************************  -->
    <?php include_once("./htmlsuelto/pie.php"); ?> 
    <!-- ********************************************************** -->
    
    <!-- ********************************************************** -->
	<!-- Notificaciones -->
	<!-- ********************************************************** -->
		
		<div id="notificacionObtenido">
			<div><h1>Se han obtenido los datos requeridos</h1></div>
		</div>
		
		<div id="notificacionNoObtenido">
			<div><h1>Lo siento, no hay datos para esta combinaci�n de opciones</h1></div>
		</div>
		
	<!-- ********************************************************** -->
	<!-- Di�logos -->
	<!-- ********************************************************** -->
    	
    	<!-- <div id="dialog-confirm-nohaydatos" title="No hay datos">
		   <p><span class="fa fa-exclamation-triangle fa-2x" style="float:left; margin:0 7px 20px 0;">
		   </span>
		   No se han seleccionado datos...<span class="hoverAsignacion"></span>
		   </p>
		</div> -->
    
</div> <!-- FIN del CONTENEDOR PRINCIPAL -->

<!-- **************************************************************************-->	
<!-- *** Final del BODY, antes los scripts ************************************-->
<!-- **************************************************************************-->	

<!-- *************************** -->
<!-- Scripts JQUERY y Javascript -->
<!-- *************************** -->
  
  <script src="./jquery/external/jquery/jquery.js"></script>
  <!-- <script src="./jquery/jquery-ui.min.js"></script> <!-- version 1.11.2 -->
  <script src="//code.jquery.com/ui/1.11.0/jquery-ui.min.js"></script> <!-- version 1.11.0 -->
  <!-- <script src="./htmlsuelto/js_menu.js"></script>   Incorpora al script los men�s a la izquierda -->  
  <script type="text/javascript" src="./jquery/jqx/jqxcore.js"></script>
  <script type="text/javascript" src="./jquery/jqx/jqx-all.js"></script> 
  <script type="text/javascript" src="./jquery/fusioncharts/js/fusioncharts.js"></script>
  <script type="text/javascript" src="./jquery/fusioncharts/js/fusioncharts.js"></script>
  <script type="text/javascript" src="./jquery/fusioncharts/js/fusioncharts.charts.js"></script>
  <script type="text/javascript" src="./jquery/fusioncharts/js/themes/fusioncharts.theme.ocean.js"></script>
    
  <!-- <script type="text/javascript" src="./jquery/chart/Chart.js"></script> -->
  <!-- Owl carousel 
  <script type="text/javascript" src="./owl-carousel/owl.carousel.js"></script>
  <!-- Editor de texto froala. Non commercial use 
  <script src="./jquery/froala/js/froala_editor.min.js"></script>  
  <script src="./jquery/froala/js/langs/es.js"></script>  
  <script src="./jquery/froala/js/plugins/char_counter.min.js"></script>
  <script src="./jquery/froala/js/plugins/tables.min.js"></script>
  <script src="./jquery/froala/js/plugins/lists.min.js"></script>
  <script src="./jquery/froala/js/plugins/colors.min.js"></script>
  <script src="./jquery/froala/js/plugins/media_manager.min.js"></script>
  <script src="./jquery/froala/js/plugins/font_family.min.js"></script>
  <script src="./jquery/froala/js/plugins/font_size.min.js"></script>
  <script src="./jquery/froala/js/plugins/block_styles.min.js"></script> 
  <script src="./jquery/froala/js/plugins/video.min.js"></script> --> 
  
  <script>     
     
     $(document).ready(function() { 
		 
		 // Variables Generales
		 var textoFecha = "cualquier Fecha";
		 var fechaINI = "#";
		 var fechaFIN = "#";
		 var cadenaSQL = "SELECT * FROM `tb_opiniones` ";
		 var miGrafica;
		 
		// 1a) Incorpora la funcionalidad del men�
		$.getScript( "./htmlsuelto/js_menu.js", function( data, textStatus, jqxhr ) {
			console.log( data ); // Data returned
			console.log( textStatus ); // Success
			console.log( jqxhr.status ); // 200
			console.log( "Load was performed." );
		}); 
		
		// 1b) Y del datepicker en espa�ol
			$.getScript( "./htmlsuelto/js_datepicker_espannol.js", function( data, textStatus, jqxhr ) {
			console.log( data ); // Data returned
			console.log( textStatus ); // Success
			console.log( jqxhr.status ); // 200
			console.log( "Load was performed." );
		}); 
				 
		// 1c) Activa los ToolTIP en el documento
			$.getScript( "./htmlsuelto/js_tooltips.js", function( data, textStatus, jqxhr ) {
			console.log( data ); // Data returned
			console.log( textStatus ); // Success
			console.log( jqxhr.status ); // 200
			console.log( "Load was performed." );
		});		
		
		// 2) Botones de tutor�a
		$("#PosNeg").buttonset(); // escribo los inputs directamente 
		
		 // ===========================================================================================
		 // DEFINO tabs. LLAMO Pesta�as. y tambi�n el acordeon, y los botones de fecha. Lista ordenable
		 // ===========================================================================================
		
		$('#pesta�as').tabs({
			active: 0,
			create: function(event,ui) {
				$("#pesta�as").tabs("disable", 1); // desactiva la pesta�a 1
				$("#pesta�as").tabs("disable", 2); // desactiva la pesta�a 1
			},
			activate: function(event, ui){ //detecta la pesta�a pulsada...
				if(ui.newTab.index()=='0') { // Si se pulsa la pesta�a 0... Filtro de Datos
					$("#pesta�as").tabs("disable", 1); // desactiva la pesta�a 1
					$("#pesta�as").tabs("disable", 2); // desactiva la pesta�a 1
				}
			},
		}); 
		
		$('#pesta�as2').tabs({
			active: 0,
		}); 

	    $("#fechaINI, #fechaFIN, #go").button({
			width: 'auto',
		}); // para que lo reconozca como del tema sunny
	 
		// ========================================================================================
		// Defino di�logos y/o notificaciones
		// ========================================================================================	
		
		 $("#notificacionObtenido").jqxNotification({
                width: 500, position: "top-right", opacity: 0.9,
                autoOpen: false, animationOpenDelay: 300, autoClose: true, autoCloseDelay: 2000, template: "info"
         });         
         
         $("#notificacionNoObtenido").jqxNotification({
                width: 500, position: "top-right", opacity: 0.9,
                autoOpen: false, animationOpenDelay: 300, autoClose: true, autoCloseDelay: 2000, template: "warning"
         });
		 
		 /* 1d) Definici�n del di�logo de confirmaci�n de grabar datos, borrar y modificar asignacion y confirmar que no hay datos
		  $("#dialog-confirm, #dialog-confirm-borrar, #dialog-confirm-nohaydatos").dialog({
			autoOpen: false,
			modal: true,
			maxWidth:600,
            maxHeight: 300,
            width: 600,
            height: 300,
			position: { my: "center center-100", at: "center center", of: "#container" }
			// el "centro arriba" de mi cuadro de di�logo (my) , en el centro arriba (at) del contenedor (of)
		 }); */	

		// ========================================================================================
		// Defino 
		// ========================================================================================	

		//1) Definici�n del SELECT de asignaciones
    	$( "#EscogerAsignacion" )
			.selectmenu({
				width:700, 
				style: 'popup',
			})			
			.selectmenu("menuWidget")
			   .addClass("overflow4"); // carga un estilo que est� en /css/estiloSelectMenuOverflow.css
  
			   
	   	// ******************************************************  
	   	
	   	$( "#EscogerAsignacion" ).selectmenu({
			change: function(event,ui) {
				rellenarCondiciones();
			}
		});  
		
		// Al pulsar sobre alguna de los botones de Fecha
		$("#cualquierFecha").click(function(event,ui){
			textoFecha = "cualquier Fecha";
			fechaINI = "#";
			fechaFIN = "#";
			rellenarCondiciones() ;
		});   
		
		$("#primerTrimestre").click(function(event){
			textoFecha = "Primera Evaluaci�n";
			fechaFIN = $(this).children().attr("fechafin"); // children porque es otro DIV dentro.
			var fecFIN = new Date(fechaFIN);
			fechaINI = fecFIN.getFullYear() + "-09-01" // Desde el 1 de Septiembre de ese a�o, seguro.
			rellenarCondiciones() ;
		});  
		
		$("#segundoTrimestre").click(function(event){
			textoFecha = "Segunda Evaluaci�n";
			fechaFIN = $(this).children().attr("fechafin"); // children porque es otro DIV dentro.
			var fecFIN = new Date($("#primerTrimestre").children().attr("fechafin"));
			// alert(fecFIN);			
			fecFIN.setDate(fecFIN.getDate()+1);
			// alert(fecFIN);
			fechaINI = fecFIN.getFullYear() + "-"+("0" + (fecFIN.getMonth()+1)).slice(-2)+"-"+("0" + fecFIN.getDate()).slice(-2);
			rellenarCondiciones() ;
		});    
		
		$("#tercerTrimestre").click(function(event){
			textoFecha = "Tercera Evaluaci�n";
			fechaFIN = $(this).children().attr("fechafin"); // children porque es otro DIV dentro.
			var fecFIN = new Date($("#segundoTrimestre").children().attr("fechafin"));
			// alert(fecFIN);			
			fecFIN.setDate(fecFIN.getDate()+1);
			// alert(fecFIN);
			fechaINI = fecFIN.getFullYear() + "-"+("0" + (fecFIN.getMonth()+1)).slice(-2)+"-"+("0" + fecFIN.getDate()).slice(-2);
			rellenarCondiciones() ;
		});   
		
		$("#quincedias").click(function(event){
			textoFecha = "Hace quince d�as";
			var fecFIN = new Date();
			var fecINI = new Date();
			fechaFIN = fecFIN.toJSON().slice(0,10)
			// alert(fecFIN);			
			fecINI.setDate(fecFIN.getDate()-15);
			// alert(fecFIN);
			fechaINI = fecINI.getFullYear() + "-"+("0" + (fecINI.getMonth()+1)).slice(-2)+"-"+("0" + fecINI.getDate()).slice(-2);
			rellenarCondiciones() ;
		});  
		
		$("#haceunmes").click(function(event){
			textoFecha = "Hace un mes";
			var fecFIN = new Date();
			var fecINI = new Date();
			fechaFIN = fecFIN.toJSON().slice(0,10)
			// alert(fecFIN);			
			fecINI.setDate(fecFIN.getDate()-30);
			// alert(fecFIN);
			fechaINI = fecINI.getFullYear() + "-"+("0" + (fecINI.getMonth()+1)).slice(-2)+"-"+("0" + fecINI.getDate()).slice(-2);
			rellenarCondiciones() ;
		}); 
		
		$("#hacedosmeses").click(function(event){
			textoFecha = "Hace dos meses";
			var fecFIN = new Date();
			var fecINI = new Date();
			fechaFIN = fecFIN.toJSON().slice(0,10)
			// alert(fecFIN);			
			fecINI.setDate(fecFIN.getDate()-(30+31));
			// alert(fecFIN);
			fechaINI = fecINI.getFullYear() + "-"+("0" + (fecINI.getMonth()+1)).slice(-2)+"-"+("0" + fecINI.getDate()).slice(-2);
			rellenarCondiciones() ;
		}); 
		
		// *******************************************
		// Fechas	
		// ******************************************* 
		 $("#fechaINI").datepicker({  			 
				   dateFormat: 'dd-mm-yy',
				   altField: "#muestrafechaINI", // campo relacionado con el data picker
				   altFormat: 'yy-mm-dd', // Formato del campo relacionado. Tipo MySQL
				   changeMonth: true,
				   changeYear: true,
				   theme: 'ui-sunny',
				   beforeShow: function(event) { // Justo antes de mostrarlo, guarda lo de la fecha anterior...
					   event.preventDefault;  					   
				   },
				   onSelect: function (event) {
					   event.preventDefault;
					   fechaINI = $("#muestrafechaINI").val(); // children porque es otro DIV dentro.
					   var fecINI = new Date(fechaINI).toLocaleDateString();
					   var fecFIN = new Date(fechaFIN).toLocaleDateString();
					   if (fecFIN == "Invalid Date") { fecFIN = 'Fecha sin determinar'; };
					   // alert(fechaINI+" - ");
					   textoFecha = "Intervalo entre " + fecINI+ " y " +fecFIN;
					   rellenarCondiciones() ;
				   },					   
		 }); 
		 $("#fechaFIN").datepicker({  			 
				   dateFormat: 'dd-mm-yy',
				   altField: "#muestrafechaFIN", // campo relacionado con el data picker
				   altFormat: 'yy-mm-dd', // Formato del campo relacionado. Tipo MySQL
				   changeMonth: true,
				   changeYear: true,
				   theme: 'ui-sunny',
				   beforeShow: function(event) { // Justo antes de mostrarlo, guarda lo de la fecha anterior...
					   event.preventDefault;  					   
				   },
				   onSelect: function (event) {
					   event.preventDefault;
					   fechaFIN = $("#muestrafechaFIN").val(); // children porque es otro DIV dentro.
					   var fecINI = new Date(fechaINI).toLocaleDateString();
					   var fecFIN = new Date(fechaFIN).toLocaleDateString();
					   if (fecINI == "Invalid Date") { fecINI = 'Fecha sin determinar'; };
					   // alert(fechaINI+" - ");
					   textoFecha = "Intervalo entre " + fecINI + " y " + fecFIN;
					   rellenarCondiciones() ;
				   },					   
		 }); 
 
		 $("#fechaINI").datepicker("setDate", fechaINI); // pone la fecha de hoy
		 $("#fechaFIN").datepicker("setDate", fechaFIN); // pone la fecha de hoy

		// *******************************************
		// Opciones	
		// ******************************************* 		
		//1) Bot�n para el SwitchButton de fotos
            $('#PosNeg').jqxSwitchButton({ 
				height: 100, 
				width: 290,  
				checked: false, 
				theme:'ui-sunny',
				offLabel:'Items +/-',
				onLabel:'Otros items',
				// rtl: true, // de derecha a izquierda
				// orientation: 'vertical'
			});
		
		// ************************************
		// Pulso el bot�n de obtenci�n de datos
		// ************************************
		$("#go").click(function(event,ui){
			$.when(obtenerDatos(),obtenerDatosPorAlumno()).done(function(data1,data2){
				try { // se reciben en formato de div
				   // alert(data1[0]);
			       dibujarGraficaA(data1[0]); // primera gr�fica. Recupera los valores en el array de datos	
				} catch(err) {
				   console.log(err.message);
				}	
				// Para la segunda gr�fica....
				try {
				   // alert(data2[0]);			
				   dibujarGraficaB(data2[0]); // segunda gr�fica	   
				} catch(err) {
				   console.log(err.message);
				}
			});
		});  
		
	
		// ************************************
		// Al hacer click en el icono impresora
		// ************************************

		$("#imprimir").click(function(event,ui){
			// alert("Imprime...");
			document.getElementById("formularioImprimir").submit();
		}); 
		
		
		// *******************************************
		// Funciones dentro del document ready
		// *******************************************
		function rellenarCondiciones() {
			var escogerAsignacion = $("#EscogerAsignacion option:selected").text();
			$("#condiciones").html(escogerAsignacion+", "+ textoFecha.toLowerCase()+". "); // muestra texto
			// Cadena SQL
			escogerAsignacion = $( "#EscogerAsignacion option:selected").val();			
			$("#SQL").html(escogerAsignacion);
			cadenaSQL = 'SELECT alumno, items FROM tb_opiniones ';
			$("#SQL").html(fechaINI+" - "+fechaFIN);
			if (escogerAsignacion>=0 || fechaINI!="#" || fechaFIN!="#") {
				cadenaSQL = cadenaSQL + "WHERE ";
			} 			
			if (escogerAsignacion>0) { cadenaSQL = cadenaSQL + 'asignacion = "'+escogerAsignacion+'" AND ';	}
			if (escogerAsignacion==0) { // Permite elegir TODAS las asignaciones PERO de mi tutor�a....
				cadenaSQL = cadenaSQL + "(";
				$("#EscogerAsignacion option").each(function(){
					if ($(this).val()>0) {cadenaSQL = cadenaSQL + 'asignacion = "'+$(this).val()+'" OR ';}
				});
				cadenaSQL = cadenaSQL.slice(0,-4)+") "; // Quitar el �ltimo OR y a�ade par�ntesis
				cadenaSQL = cadenaSQL + ' AND '; // Poner el AND
			} // Fin del IF de "Todas las asignaciones"
			// Comprueba se fechaINI > fechaFIN. Si lo es, dar la vuelta
			if (fechaINI!="#" && fechaFIN!="#" && fechaINI>fechaFIN) {
					// alert("doy la vuelta"); LANZAR UN MENSAJE
					// aprovecho la variable ordenado que ya no sirve, para hacer el intercambio.
					ordenado = fechaINI; fechaINI = fechaFIN; fechaFIN = ordenado;
			}
			// Coloca fecha en cadena SQL.			
			if (fechaINI!="#" && fechaFIN!="#") { cadenaSQL = cadenaSQL + "fecha BETWEEN '"+fechaINI+"' AND '"+fechaFIN+"' AND ";}
			if (fechaINI!="#" && fechaFIN=="#") { cadenaSQL = cadenaSQL + "fecha> '"+fechaINI+"' AND ";}
			if (fechaINI=="#" && fechaFIN!="#") { cadenaSQL = cadenaSQL + "fecha< '"+fechaFIN+"' AND ";}			
			// SELECT * FROM `tb_opiniones` WHERE `fecha` BETWEEN '2015-03-15' AND '2016-05-11' AND `alumno` = 90 AND `asignacion` = 2 
			if (cadenaSQL.slice(-5)==" AND ") { cadenaSQL = cadenaSQL.slice(0,-5);}
			// $("#SQL").html(cadenaSQL); // Hasta aqu� la cla�sula WHERE
			cadenaSQL = cadenaSQL + " ORDER BY alumno"; // Prefiero ordenar por alumno...
			$("#SQL").html(cadenaSQL);
		};		
		
		/* **************************************** */
		/* Dibujar la gr�fica de datos estad�sticos totales */
		function dibujarGraficaA(data) {
				// Redibujo un canvas din�micamente, para poder refrescar datos. No me funciona mejor otra forma...
				   // $("#Grafica").remove();
				   // $('#MostrarDatos').append('<canvas id="Grafica" width="400" height="200"></canvas>');	
				   var recupera = jQuery.parseJSON(data);
				   
				   if (recupera.tipo == 0) { // Si no hay resultados
					   $("#notificacionNoObtenido").jqxNotification("open");	
					   return; 
				   }
				   				   
				   $("#pesta�as").tabs("enable", 1); // activa la pesta�a 1
				   $("#pesta�as").tabs("enable", 2); // activa la pesta�a 1
				   // $("#MostrarDatos").html('<h1>'+$("#condiciones").html()+'</h1>'+datos); // coloca los datos...
				   // var sCabecera =  $("#condiciones").html();
				   // sCabecera = sCabecera.replace(/&/g, "&amp;").replace(/>/g, "&gt;").replace(/</g, "&lt;").replace(/"/g, "&quot;");
				   // $("#sendCabecera").val(sCabecera);
				   // var sContenido = datos;
				   // sContenido = sContenido.replace(/&/g, "&amp;").replace(/>/g, "&gt;").replace(/</g, "&lt;").replace(/"/g, "&quot;");
				   // $("#sendContenido").val(sContenido);
				   $('#pesta�as a[href="#EstITEMS"]').trigger('click'); // simula el click en la pesta�a 1
				   
				   var etiquetas = recupera.items;  		   
				   var datos = recupera.frecuencias;
				   var posneg = recupera.posneg; 
				   var colores = [];

				   $.each(posneg, function( index, value ) {
						// alert( index + ": " + value );
						if (value==0) { colores.push("6A1515"); } // rojo, negativo
						if (value==1) { colores.push("115511"); } // verde, positivo
						if (value==2) { colores.push("3D2E7E"); } // morado, neutro
				   });
				   				   		   
				   datosJSON=[];
				   var contar = 100;
				   $.each(etiquetas, function( index, value ) {
						item = {}
						item ["label"] = value;
						item ["value"] = datos[index];
						item ["color"] = colores[index];
						contar = contar + 80; // calcular altura de la gr�fica
						datosJSON.push(item);
				   });
				   contarSTR = contar.toString()+"px"
				   
				   /* Estad�sticas... Media, Desviacion Est�ndar etc... */
				   var tipo = recupera.tipo;
				   if (tipo==1) { // positivo y negativo
					   item={}; item["label"]="MEDIA POSITIVOS: "; item["color"]="2D1E6E"; item["value"]=recupera.promedioPOS; datosJSON.push(item);
					   item={}; item["label"]="DESVIACI�N EST�NDARD POSITIVOS: "; item["color"]="2D1E6E"; item["value"]=recupera.desestandardPOS; datosJSON.push(item);
					   item={}; item["label"]="MEDIA NEGATIVOS: "; item["color"]="2D1E6E"; item["value"]=recupera.promedioNEG; datosJSON.push(item);
					   item={}; item["label"]="DESVIACI�N EST�NDARD NEGATIVOS: "; item["color"]="2D1E6E"; item["value"]=recupera.desestandardNEG; datosJSON.push(item);
				   } else if (tipo==2) { // neutro
					   item={}; item["label"]="MEDIA:"; item["color"]="2D1E6E"; item["value"]=recupera.promedio; datosJSON.push(item);
					   item={}; item["label"]="DESVIACI�N EST�NDARD:"; 
					            item["color"]="2D1E6E"; item["value"]=recupera.desestandard; datosJSON.push(item);
				   }
				   
				   
				   /* ================================================================================= */
  
				   /* Empieza la gr�fica FUSION */
						var revenueChart = new FusionCharts({
							"type": "bar2d",
							"renderAt": "Grafica",
							"width": "95%",
							"height": contarSTR,
							"dataFormat": "json",
							"dataSource":  {
							  "chart": {
								"baseFontSize": 28, "baseFont":"Times New Roman", // Tama�o de todos los componentes por defecto
								"bgColor":"#FFE991","bgAlpha":50, // Colores de fondo
								"canvasBgColor":"#FFE991","canvasBgAlpha":60, // Colores de fondo
								// "usePlotGradientColor":"1","plotGradientColor":"#ffffff", // Colores de gradientes -> �NO FUNCIONA?
								"caption": $('#condiciones').text(), "captionFontSize": 28,
								"alignCaptionWithCanvas": 0, // 0-> Alinea con todo el area, no con la gr�fica
								"subCaption": 'Tutor�a de la asignaci�n: '+$('#descripcion').attr("title"), // Se carga en barra superior
								"subcaptionFontSize": 24,
								"xAxisName": "", "yAxisName": "",
								"valueFontSize": 24, "valueFontBold":1, "valueFontColor": "#FFFFFF", // Para los valores dentro de las barras
								"labelDisplay": "wrap", // "labelFontSize": 24,
								// "divLineColor":"000000", "divLineThickness":20, "divLineAlpha":100,
								"showBorder":1, "borderColor":"9E9789", "borderThickness":3,
								 //Canvas Border Properties
								"showCanvasBorder": "1", "canvasBorderColor": "#666666", "canvasBorderThickness": "2", "canvasBorderAlpha": "80",
								"plotGradientColor": "",
								"exportAtClientSide": "1", "exportEnabled": "1",
								"toolbarButtonWidth":60, "toolbarButtonHeight":60, // , 'toolbarButtonColor'.
								"toolbarHAlign":"left", // "toolbarX": "85%", 
								"exportFileName":"Grafica", "exportShowMenuItem":"1",
								"exportFormats": "PNG=Imagen HQ PNG|PDF=Exportar PDF|JPG=Imagen JPG",
								"exportTargetWindow": "_self",
								"chartLeftMargin":20, "chartRightMargin":20, "chartTopMargin":20, "chartBottomMargin":20, 
								"captionPadding":20,"labelPadding": 20,
								 //Logo TR -> Top Right... BR, BL, CC
								"logoURL": "./imagenes/logoA.png","logoAlpha": "20","logoScale": "50","logoPosition": "TR",
								"tooltipbgcolor": "F3F3F3",	"tooltipbordercolor": "111111", "tooltipcolor": "000000",
           						"theme": "ocean"
							 },
							 "data": datosJSON,
						  },
						  "events": {
							"beforeRender": function(evt, args) {
								var controllers = document.createElement("div"),
									labels;
								controllers.innerHTML = "<label for='set-dimensions-height'>Regula la altura de la gr�fica</label>&nbsp;<input id='set-dimensions-height' class='input-small' type='text' value='1000' width='8em'/>&nbsp;&nbsp;<input id='set-dimensions-change' class='resizebtn' type='Button' value='Redimensionar' /><p>Ejemplo: 2000 producir� una ventana de 2000 p�xeles de altura, '50%' producir� una ventana de alto 50% de la p�gina web</p>";
								controllers.style.cssText = "position: inherit;left: 10px;font-family: Verdana, sans;font-size: 24px;font-style: normal;font-weight: bold;";
								controllers.getElements
								args.container.appendChild(controllers);
								labels = controllers.getElementsByTagName("label");
								for (var i in labels) {
									labels[i].style.cssText = "display: inline;"
								}
							},
							"renderComplete": function(evt, args) {
								var setDimensionOnClick = function() {
										var h = parseInt(document.getElementById("set-dimensions-height").value, 10) || 1000;
										if (h) {
											FusionCharts.items[evt.sender.id].resizeTo("95%", h);
										}
									},
									changeBtn = document.getElementById("set-dimensions-change");

								if (changeBtn.addEventListener) {
									changeBtn.addEventListener("click", setDimensionOnClick);
								} else {
									changeBtn.onclick && changeBtn.onclick(setDimensionOnClick);
								}
							}

						} // fin del events

					  });
					revenueChart.render();
					
					/* Termina la gr�fica FUSION */
					/* =================================================================================== */

				   	
				    // Activar notificaci�n
				    $("#notificacionObtenido").jqxNotification("open");
		} // Fin del dibujo de la gr�fica A
		
/* ------------------------------------------------------------------------------------------------------------- */
/* - Separando las dos gr�ficas -------------------------------------------------------------------------------- */
/* ------------------------------------------------------------------------------------------------------------- */
		
		/* *************************************************** */
		/* Dibujar la gr�fica de datos estad�sticos por alumno */
		function dibujarGraficaB(data) {
	
				   var recupera = jQuery.parseJSON(data);
				   
				   if (recupera.tipo == 0) { // Si no hay resultados
					   $("#notificacionNoObtenido").jqxNotification("open");	
					   return; 
				   }				   
		   
				   var etiquetas = recupera.alumnos;  		   
				   var positivos = recupera.positivos;
				   var negativos = recupera.negativos;
		   				   		   
				   datosCategoria=[];
				   var contar = 100;
				   $.each(etiquetas, function( index, value ) {
						item = {};
						item ["label"] = value;
						contar = contar + 80; // calcular altura de la gr�fica
						datosCategoria.push(item);
				   });
				   contarSTR = contar.toString()+"px";
				   
				   datosPositivos=[];
				   $.each(positivos, function( index, value ) {
					   item = {};
					   item ["value"] = value;
					   if (value>0) {item["showValue"]="1";item["tooltext"]= value.toString()+" valores positivos ("+datosCategoria[index].label+")"; } 
						  else {item["showValue"]="0"; item["tooltext"]= "-";} // POR FIN: esa etiqueta muestra o no los valores.
					   datosPositivos.push(item);
				   });
				   
				   datosNegativos=[];
				   $.each(negativos, function( index, value ) {
					   item = {}
					   if (value>0) {item ["value"] = -1*value; item["showValue"]="1"; item["tooltext"]= value.toString()+" valores negativos ("+datosCategoria[index].label+")";} 
					      else {item ["value"]=-0.1; item["showValue"]="0"; item["tooltext"]= "-";} // esto lo dibuja siempre a la izquierda si es cero;
					   datosNegativos.push(item);
				   });

					// Medias, desviaci�n est�ndard, etc... Se obtiene en obtenerDatosEstadisticosPorAlumno...
				   
				   /* ================================================================================= */
  
				   /* Empieza la gr�fica FUSION */
						var revenueChart2 = new FusionCharts({
							"type": "stackedbar2d",
							"renderAt": "Grafica2",
							"width": "95%",
							"height": contarSTR,
							"dataFormat": "json",
							"dataSource":  {
								 "chart": {
									    "baseFontSize": 28, "baseFont":"Times New Roman", // Tama�o de todos los componentes por defecto
										"bgColor":"#FFE991","bgAlpha":50, // Colores de fondo
										"canvasBgColor":"#FFE991","canvasBgAlpha":60, // Colores de fondo
										// "usePlotGradientColor":"1","plotGradientColor":"#ffffff", // Colores de gradientes -> �NO FUNCIONA?
										"caption": $('#condiciones').text(), "captionFontSize": 28,
										"alignCaptionWithCanvas": 0, // 0-> Alinea con todo el area, no con la gr�fica
										"subCaption": 'Tutor�a de la asignaci�n: '+$('#descripcion').attr("title"), // Se carga en barra superior
										"subcaptionFontSize": 24,
										"xAxisName": "", "yAxisName": "",
										"labelDisplay": "wrap", // "labelFontSize": 24,
										// "divLineColor":"000000", "divLineThickness":20, "divLineAlpha":100,
										"showBorder":1, "borderColor":"9E9789", "borderThickness":3,
										 //Canvas Border Properties
										"showCanvasBorder": "1", "canvasBorderColor": "#666666", "canvasBorderThickness": "2", "canvasBorderAlpha": "80",
										"plotGradientColor": "",
										"exportAtClientSide": "1", "exportEnabled": "1",
										"toolbarButtonWidth":60, "toolbarButtonHeight":60, // , 'toolbarButtonColor'.
										"toolbarHAlign":"left", // "toolbarX": "85%", 
										"exportFileName":"Grafica", "exportShowMenuItem":"1",
										"exportFormats": "PNG=Imagen HQ PNG|PDF=Exportar PDF|JPG=Imagen JPG",
										"exportTargetWindow": "_self",
										"chartLeftMargin":20, "chartRightMargin":20, "chartTopMargin":20, "chartBottomMargin":20, 
										"captionPadding":20,"labelPadding": 20,
										 //Logo TR -> Top Right... BR, BL, CC
										"logoURL": "./imagenes/logoA.png","logoAlpha": "20","logoScale": "50","logoPosition": "TR",
										"theme": "ocean",
										"tooltipbgcolor": "F3F3F3",	"tooltipbordercolor": "111111", "tooltipcolor": "000000",
       									"animation": "1",
										"numdivlines": "2",
										"yaxisminvalue": -1*(recupera.maxnegativos+2),
										"yaxismaxvalue": (recupera.maxpositivos+2),
										"valueFontSize": 24, "valueFontBold":1, "valueFontColor": "#FFFFFF", // Para los valores dentro de las barras										
										"plotHighlightEffect": "fadeout", "legendIconScale":3, "legendItemFontSize": 24,
										"showborder": "1",											
									},
									"categories": [	{"category": datosCategoria,}], // fin de CATEGORIES
									"dataset": [
										{ // primera serie de datos
											"seriesname": "Positivos",
											"color": "115511",
											"data": datosPositivos,
										},
										{ // Segunda serie de datos
											"seriesname": "Negativos",
											"color": "6A1515",
											"data": datosNegativos,
										},
	
									] // fin del dataset
									
						     }, // Fin del data source
						  "events": {
							"beforeRender": function(evt, args) {
								var controllers = document.createElement("div"),
									labels;
								controllers.innerHTML = "<label for='set-dimensions-height2'>Regula la altura de la gr�fica</label>&nbsp;<input id='set-dimensions-height2' class='input-small' type='text' value='1000' width='8em'/>&nbsp;&nbsp;<input id='set-dimensions-change2' class='resizebtn' type='Button' value='Redimensionar' /><p>Ejemplo: 2000 producir� una ventana de 2000 p�xeles de altura, '50%' producir� una ventana de alto 50% de la p�gina web</p>";
								controllers.style.cssText = "position: inherit;left: 10px;font-family: Verdana, sans;font-size: 24px;font-style: normal;font-weight: bold;";
								controllers.getElements
								args.container.appendChild(controllers);
								labels = controllers.getElementsByTagName("label");
								for (var i in labels) {
									labels[i].style.cssText = "display: inline;"
								}
							},
							"renderComplete": function(evt, args) {
								var setDimensionOnClick2 = function() {
										var h = parseInt(document.getElementById("set-dimensions-height2").value, 10) || 1000;
										if (h) {
											FusionCharts.items[evt.sender.id].resizeTo("95%", h);
										}
									},
									changeBtn = document.getElementById("set-dimensions-change2");

								if (changeBtn.addEventListener) {
									changeBtn.addEventListener("click", setDimensionOnClick2);
								} else {
									changeBtn.onclick && changeBtn.onclick(setDimensionOnClick2);
								}
							}

						} // fin del events

					  });
					
					revenueChart2.render();
					
					/* Termina la gr�fica FUSION */
					/* =================================================================================== */
				   	
				    // Activar notificaci�n
				    // $("#notificacionObtenido").jqxNotification("open");
		} // Fin del dibujo de la gr�fica B
		
		// ***************************
		// Tras cargarlo todo. Inicio.
		// ***************************
		rellenarCondiciones(); // al principio, con las opciones por defecto.
			
	 }); // fin del document ready
	 
	 // ******************************************************
	 // Funciones en la p�gina *******************************
	 // ******************************************************	 

	 //************************************
	 // F1) Obtener datos del filtro
	 function obtenerDatos() {
		 if ($("#PosNeg").jqxSwitchButton('checked')) { var PosNegSN = 1; } else { var PosNegSN=0; }
		 // alert(PosNegSN);
		 // alert($("#SQL").text());
		 console.log("****************** Obtiene SQL *******************");
		 console.log("SQL: "+$("#SQL").text());
		 console.log("Fotos: ");
		 return $.ajax({
			  type: 'POST',
			  dataType: 'text',	
		      url: "./tutorias/scripts/obtenerDatosEstadisticos.php", // En el script se construye la tabla...
		      data: { 
			  SQL: $("#SQL").text(),
			  posneg: PosNegSN,
		      },
		      success: function(data, textStatus, jqXHR){ 			  
				// alert(data);
				return data;
		      },
		  });
	  } // Fin de la funci�n Obtener datos del filtro  
	  
	  //************************************
	 // F2) Obtener datos del filtro por Alumno
	 function obtenerDatosPorAlumno() {
		 // alert($("#SQL").text());
		 console.log("****************** Obtiene SQL *******************");
		 console.log("SQL: "+$("#SQL").text());
		 console.log("Fotos: ");
		 return $.ajax({
			  type: 'POST',
			  dataType: 'text',	
		      url: "./tutorias/scripts/obtenerDatosEstadisticosPorAlumno.php", // En el script se construye la tabla...
		      data: { 
			  SQL: $("#SQL").text(),
			  asignacion: $("#EscogerAsignacion option:selected").val(),
		      },
		      success: function(data, textStatus, jqXHR){ 			  
				// alert(data);
				return data;
		      },
		  });
	  } // Fin de la funci�n Obtener datos del filtro  

	  
 <!-- * =======================================================================================================   * --> 	

  </script>
  
<!-- **************************************************************************************** -->
</body></html>
