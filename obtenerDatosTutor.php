<?php header('Content-type: text/html; charset=iso-8859-15'); ?>
<?php
// Codigo que se ejecuta al principio de la página
// importante incluir al principio de cada una, lo de las funciones
include_once("./funciones/funciones.php"); // funciones varias de conexión a base de datos, etc.

// Incluyo además las clases que se van a usar
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

// Variables de sesión
session_start();

if (!(($_SESSION["permisos"]>=1) && ($_SESSION["tutor"]>=1))) { // en caso que no tenga permisos para entrar
	echo header("Location: ./index.php");
}

// Las variables de sesión se establecen en los scripts AJAX en 

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="es">	
<head profile="http://www.w3.org/2005/10/profile">
  <!-- *** Principio del HEAD *************************************-->	
  <meta content="text/html; charset=iso-8859-15" http-equiv="content-type">
  <title>Obtengo los datos de tutoría</title>
  <link rel="icon" 
        type="image/png" 
        href="./imagenes/logoA.png">
  <meta content="Aurelio Gallardo Rodríguez" name="author">  
  <meta content="Obtengo los datos de tutoría" name="description">

  <!-- ***************************** -->
  <!-- Linkar a hojas de estilo CSS -->
  <!-- ***************************** -->
  <?php include_once("./css/cargarestiloscss.php"); ?>
  <link rel="stylesheet" href="./css/estiloobtenerDatosTutor.css"> <!-- Efectos aplicados a esta hoja -->
  
  <!-- *** Final del HEAD, antes los ficheros de enlace a CSS ******-->
</head>

<body>
<!-- **************************************************************************-->	
<!-- *** Principio del BODY ***************************************************-->	
<!-- **************************************************************************-->	

<div id="container"> <!-- CONTENEDOR PRINCIPAL -->
	
	<!-- HTML suelto: barra superior, menu superior, menu lateral,   -->
	<?php include_once("./htmlsuelto/cabecera.php"); ?>
	<?php include_once("./htmlsuelto/barrasuperior.php"); // y dentro de él se incluye el menú correspondiente ?> 
	<div id="menu" style="text-align: justify; padding-right: 1em;">
		<?php include_once("./htmlsuelto/menuIZQUIERDO.php"); ?> 
 	</div>     
    
    <!-- ******************************************* -->
    <!-- ******** ZONA DE CÁLCULOS PREVIOS ********* -->
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
		
		<div id="pestañas">
            <ul>
				<li><a href="#FiltrodeDatos">Filtro de Datos</a></li>
				<li><a href="#Datos">Datos</a></li>
				<li><a href="#Instrucciones">Instrucciones</a></li>				
			</ul> 
			<!-- ********************************************************** -->
			<!-- Filtrado de datos -->
			<!-- ********************************************************** --> 			
			<div id="FiltrodeDatos">
				<!-- Selección de datos -->
				<div id="acordeon">
					<h3>Por asignación</h3>
						<div>
							<?php // Select para seleccionar asignaciones
							$asignacionesmiTutoria = $asignacion->devuelveAsignacionesDeUnaTutoria($_SESSION["idasignacion"],$_SESSION["profesor"]);
							echo '<div id="asignaciones" title="Escoge la asignación de Filtrado">';
							echo '<select name="EscogerAsignaciones" id="EscogerAsignacion" class="seleccionaidasignacion">';
							echo '<option value="0">Todas las asignaciones</option>';
							foreach($asignacionesmiTutoria as $clave => $valor) {
								echo '<option value="'.$valor.'">'.$asignacion->asignacionDescripcion($valor).'</option>';
							}
							echo '</select>';
							echo '</div>';
							?>
						</div>
					<h3>Por alumno</h3>
						<div>
							<?php // Select para seleccionar alumnos
							$alumnos = $asignacion->devuelveListadoAlumnosdeEstaAsignacion($_SESSION["idasignacion"],$_SESSION["profesor"]);
							$alumnosArray = explode("#",$alumnos);
							echo '<div id="alumnos" title="Escoge un alumno de tu tutoría">';
							echo '<select name="EscogerAlumno" id="EscogerAlumno" class="seleccionaidalumno">';
							echo '<option value="0">Todos los alumnos/as</option>';
							foreach($alumnosArray as $clave => $valor) {
								$alumno->devuelveAlumno($valor);
								echo '<option value="'.$valor.'">'.$alumno->esteAlumno["nombre2"].'</option>';
							}
							echo '</select>';
							echo '</div>';
							?>
						</div>
					<h3>Por Fechas</h3>
						<div id="Fechas">
							<div id="cualquierFecha"><div class="divNombreEval" style="padding: 10px;">Cualquier fecha</div></div>
							<hr style="text-align: center; width: 80%;">
							<div id="fechaInicio">
								Inicial: 
								<input id="fechaINI" READONLY alt="Pulsa para obtener o cambiar la fecha inicial del intervalo" title="Pulsa para obtener o cambiar la fecha inicial del intervalo">
								<input id="muestrafechaINI" style="display:none ;">
							</div>
							<div id="fechaFinal">
								Final: 
								<input id="fechaFIN" READONLY alt="Pulsa para obtener o cambiar la fecha final del intervalo" title="Pulsa para obtener o cambiar la fecha final del intervalo">
								<input id="muestrafechaFIN" style="display:none ;">
							</div>
							<hr style="text-align: center; width: 80%;">
							<div id="primerTrimestre"><?php echo $evaluacion->listadoDeEvaluaciones["div"][0]; ?></div>
							<div id="segundoTrimestre"><?php echo $evaluacion->listadoDeEvaluaciones["div"][1]; ?></div>
							<div id="tercerTrimestre"><?php echo $evaluacion->listadoDeEvaluaciones["div"][2]; ?></div>
							<div id="quincedias"><div class="divNombreEval" style="padding: 10px;">Hace quince días</div></div>
							<div id="haceunmes"><div class="divNombreEval" style="padding: 10px;">Hace un mes</div></div>
							<div id="hacedosmeses"><div class="divNombreEval" style="padding: 10px;">Hace dos meses</div></div>
							<p id="textoFecha" style="display:none ;">Cualquier Fecha</p>
							<hr style="text-align: center; width: 80%;">
						</div>
					<h3>Opciones</h3>
						<div id="Opciones">
							<div id="QuieroFotoSN"><h3>Elige si quieres los resultados con o sin fotografías</h3></div>
						    <div id="fotoYN" title="Elige si quieres que en la lista a parezcan o no las fotografías"></div>
						    <div id="ListaDeOpciones">
								<ul id="ordenable">
									  <li dt="fecha ASC" id="OrdenFecha" class="ui-state-default" title="Docle Click si quieres cambiar el orden de las fechas"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Ordenar por Fecha "las más antiguas primero"</li>
									  <li dt="alumno ASC" class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Ordenar por Nombre</li>
									  <li dt="asignacion ASC" class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Ordenar por Asignación</li>
								</ul>
								<p style="display: none;" id="orden">ORDER BY fecha ASC, alumno ASC, asignacion ASC</p> <!-- None para ocultarlo -->
						    </div>
						</div> 
					<!-- <h3>Por Item</h3>
						<div>Items</div> NO SE SI PONER POR ITEMS --> 
				</div>
				<!-- Escritura de cadena SQL -->
				<div id="CadenaSQL">
					<h1>Condiciones</h1>
					<p id="condiciones"></p>
					<h1 style="display: text;">Cadena SQL</h1>
					<p style="display: text;" id="SQL"></p> <!-- poner 'none' para ocultarlo -->
					<button id="go">Mostrar Datos</button>
				</div>
			</div>

			<!-- ********************************************************** -->
			<!-- Mostrar los datos que se han filtrado -->
			<!-- ********************************************************** --> 	
			<div id="Datos">
				<div id="MostrarDatos">	<!-- Aquí se inserta el HTML que muestra los datos -->			
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
    
    <!-- HTML suelto: pie de página *******************************  -->
    <?php include_once("./htmlsuelto/pie.php"); ?> 
    <!-- ********************************************************** -->
    
    <!-- ********************************************************** -->
	<!-- Notificaciones -->
	<!-- ********************************************************** -->
		
		<!-- <div id="notificacionGuardado">
			<div><h1>Se ha registrado el dato y guardado</h1></div>
		</div> -->
		
	<!-- ********************************************************** -->
	<!-- Diálogos -->
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
  <!-- <script src="./htmlsuelto/js_menu.js"></script>   Incorpora al script los menús a la izquierda -->  
  <script type="text/javascript" src="./jquery/jqx/jqxcore.js"></script>
  <script type="text/javascript" src="./jquery/jqx/jqx-all.js"></script> 
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

		// 1a) Incorpora la funcionalidad del menú
		$.getScript( "./htmlsuelto/js_menu.js", function( data, textStatus, jqxhr ) {
			console.log( data ); // Data returned
			console.log( textStatus ); // Success
			console.log( jqxhr.status ); // 200
			console.log( "Load was performed." );
		}); 
		
		// 1b) Y del datepicker en español
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
		
		// 2) Botones de tutoría
		$("#fotoYN").buttonset(); // escribo los inputs directamente 
		
		 // ===========================================================================================
		 // DEFINO tabs. LLAMO Pestañas. y también el acordeon, y los botones de fecha. Lista ordenable
		 // ===========================================================================================
		
		$('#pestañas').tabs({
			active: 0,
			create: function(event,ui) {
				$("#pestañas").tabs("disable", 1); // desactiva la pestaña 1
			},
			activate: function(event, ui){ //detecta la pestaña pulsada...
				if(ui.newTab.index()=='0') { // Si se pulsa la pestaña 0... Filtro de Datos
					$("#pestañas").tabs("disable", 1); // desactiva la pestaña 1
				}
			},
		}); 
		
		$('#acordeon').accordion({
		 icons: { "header": "ui-icon-arrowthick-1-e", "activeHeader": "ui-icon-star" },
		 animate: 800,
		}); 
		// $('#acordeon.ui-accordion').css({"width":"50%"}) // ancho del acordeón
	    $("#fechaINI, #fechaFIN, #go").button({
			width: 'auto',
		}); // para que lo reconozca como del tema sunny	
		
		// Lista que se ordena...
        $("#ordenable").sortable({
			stop: function( event, ui ) {
				cadenaORDER();
				rellenarCondiciones();
			},
		});
		$("#ordenable").disableSelection();	

		// Al pulsar sobre un elemento de la lista, cambio de fechas ASC a DESC
		$("#OrdenFecha").dblclick(function(ui){
			// alert($(this).attr("dt"));
			if ($(this).attr("dt")=="fecha ASC") {
				$(this).attr("dt","fecha DESC");
				// alert($(this).attr("dt"));
				$(this).html('<span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Ordenar por Fecha "las más actuales primero"');
			} else if ($(this).attr("dt")=="fecha DESC") {
				$(this).attr("dt","fecha ASC");
				$(this).html('<span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Ordenar por Fecha "las más antiguas primero"');
			}
			cadenaORDER();
			rellenarCondiciones();
		});
	 
		// ========================================================================================
		// Defino diálogos y/o notificaciones
		// ========================================================================================	
		
		/* $("#notificacionGuardado, #notificacionModificar").jqxNotification({
				width: 400, position: "top-right", opacity: 0.9,
				autoOpen: false, animationOpenDelay: 300, autoClose: true, autoCloseDelay: 2000, template: "info"
		 }); */
		 
		 /* 1d) Definición del diálogo de confirmación de grabar datos, borrar y modificar asignacion y confirmar que no hay datos
		  $("#dialog-confirm, #dialog-confirm-borrar, #dialog-confirm-nohaydatos").dialog({
			autoOpen: false,
			modal: true,
			maxWidth:600,
            maxHeight: 300,
            width: 600,
            height: 300,
			position: { my: "center center-100", at: "center center", of: "#container" }
			// el "centro arriba" de mi cuadro de diálogo (my) , en el centro arriba (at) del contenedor (of)
		 }); */	

		// ========================================================================================
		// Defino 
		// ========================================================================================	

		//1) Definición del SELECT de asignaciones
    	$( "#EscogerAsignacion" )
			.selectmenu({
				width:700, 
				style: 'popup',
			})			
			.selectmenu("menuWidget")
			   .addClass("overflow4"); // carga un estilo que está en /css/estiloSelectMenuOverflow.css

		//2) Definición del SELECT de alumnos
    	$( "#EscogerAlumno" )
			.selectmenu({
				width:700, 
				style: 'popup',
			})			
			.selectmenu("menuWidget")
			   .addClass("overflow5"); // carga un estilo que está en /css/estiloSelectMenuOverflow.css		   
			   
	   	// ******************************************************  
	   	
	   	$( "#EscogerAsignacion, #EscogerAlumno" ).selectmenu({
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
			textoFecha = "Primera Evaluación";
			fechaFIN = $(this).children().attr("fechafin"); // children porque es otro DIV dentro.
			var fecFIN = new Date(fechaFIN);
			fechaINI = fecFIN.getFullYear() + "-09-01" // Desde el 1 de Septiembre de ese año, seguro.
			rellenarCondiciones() ;
		});  
		
		$("#segundoTrimestre").click(function(event){
			textoFecha = "Segunda Evaluación";
			fechaFIN = $(this).children().attr("fechafin"); // children porque es otro DIV dentro.
			var fecFIN = new Date($("#primerTrimestre").children().attr("fechafin"));
			// alert(fecFIN);			
			fecFIN.setDate(fecFIN.getDate()+1);
			// alert(fecFIN);
			fechaINI = fecFIN.getFullYear() + "-"+("0" + (fecFIN.getMonth()+1)).slice(-2)+"-"+("0" + fecFIN.getDate()).slice(-2);
			rellenarCondiciones() ;
		});    
		
		$("#tercerTrimestre").click(function(event){
			textoFecha = "Tercera Evaluación";
			fechaFIN = $(this).children().attr("fechafin"); // children porque es otro DIV dentro.
			var fecFIN = new Date($("#segundoTrimestre").children().attr("fechafin"));
			// alert(fecFIN);			
			fecFIN.setDate(fecFIN.getDate()+1);
			// alert(fecFIN);
			fechaINI = fecFIN.getFullYear() + "-"+("0" + (fecFIN.getMonth()+1)).slice(-2)+"-"+("0" + fecFIN.getDate()).slice(-2);
			rellenarCondiciones() ;
		});   
		
		$("#quincedias").click(function(event){
			textoFecha = "Hace quince días";
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
		//1) Botón para el SwitchButton de fotos
            $('#fotoYN').jqxSwitchButton({ 
				height: 100, 
				width: 290,  
				checked: false, 
				theme:'ui-sunny',
				onLabel:'Con fotos',
				offLabel:'Sin fotos',
				// rtl: true, // de derecha a izquierda
				// orientation: 'vertical'
			});
		
		// ************************************
		// Pulso el botón de obtención de datos
		// ************************************
		$("#go").click(function(event,ui){
			$.when(obtenerDatos()).done(function(datos){
				try { // se reciben en formato de div
				   $("#pestañas").tabs("enable", 1); // activa la pestaña 1
				   $("#MostrarDatos").html('<h1>'+$("#condiciones").html()+'</h1>'+datos); // coloca los datos...
				   $('#pestañas a[href="#Datos"]').trigger('click'); // simula el click en la pestaña 1
				   // alert(datos);
				} catch(err) {
				   console.log(err.message);
				}	
			});
		});  
		
		// *******************************************
		// Funciones dentro del document ready
		// *******************************************
		function rellenarCondiciones() {
			var escogerAsignacion = $( "#EscogerAsignacion option:selected").text();
			var escogerAlumno = $("#EscogerAlumno option:selected").text();	
			if (escogerAlumno=="Todos los alumnos/as") { escogerAlumno = escogerAlumno.toLowerCase();}
			var ordenado = $("#orden").text();
			ordenado = ordenado.replace("ORDER BY","Ordenado por");
			ordenado = ordenado.replace("fecha ASC","fecha más antiguas primero");
			ordenado = ordenado.replace("fecha DESC","fecha más actuales primero");
			ordenado = ordenado.replace("alumno ASC","alumno");
			ordenado = ordenado.replace("asignacion ASC","asignación"); // modifica la cadena ORDER BY para hacerla leíble.
			$("#condiciones").html(escogerAsignacion+", "+escogerAlumno+", "+ textoFecha.toLowerCase()+". " + ordenado); // muestra texto
			// Cadena SQL
			escogerAsignacion = $( "#EscogerAsignacion option:selected").val();
			escogerAlumno = $( "#EscogerAlumno option:selected").val(); // Redefino con los valores
			cadenaSQL = "SELECT * FROM `tb_opiniones` ";
			$("#SQL").html(fechaINI+" - "+fechaFIN);
			if (escogerAsignacion>0 || escogerAlumno>0 || fechaINI!="#" || fechaFIN!="#") {
				cadenaSQL = cadenaSQL + "WHERE ";
			} 			
			if (escogerAsignacion>0) { cadenaSQL = cadenaSQL + '`asignacion` = '+escogerAsignacion+' AND ';	}
			if (escogerAlumno>0) { cadenaSQL = cadenaSQL + '`alumno` = '+escogerAlumno+' AND ';	}
			// Comprueba se fechaINI > fechaFIN. Si lo es, dar la vuelta
			if (fechaINI!="#" && fechaFIN!="#" && fechaINI>fechaFIN) {
					// alert("doy la vuelta"); LANZAR UN MENSAJE
					// aprovecho la variable ordenado que ya no sirve, para hacer el intercambio.
					ordenado = fechaINI; fechaINI = fechaFIN; fechaFIN = ordenado;
			}
			// Coloca fecha en cadena SQL.			
			if (fechaINI!="#" && fechaFIN!="#") { cadenaSQL = cadenaSQL + "`fecha` BETWEEN '"+fechaINI+"' AND '"+fechaFIN+"' AND ";}
			if (fechaINI!="#" && fechaFIN=="#") { cadenaSQL = cadenaSQL + "`fecha`> '"+fechaINI+"' AND ";}
			if (fechaINI=="#" && fechaFIN!="#") { cadenaSQL = cadenaSQL + "`fecha`< '"+fechaFIN+"' AND ";}			
			// SELECT * FROM `tb_opiniones` WHERE `fecha` BETWEEN '2015-03-15' AND '2016-05-11' AND `alumno` = 90 AND `asignacion` = 2 
			if (cadenaSQL.slice(-5)==" AND ") { cadenaSQL = cadenaSQL.slice(0,-5);}
			// $("#SQL").html(cadenaSQL); // Hasta aquí la claúsula WHERE
			$("#SQL").html(cadenaSQL+" "+$("#orden").html());
		};		
		
		// 2) Obtener cadena ordenada
		function cadenaORDER() {
			// alert("Cambio");
			var cadena = "";
			$("#ordenable li").each(function(i, elemento){
				cadena = cadena + $(elemento).attr("dt")+", ";					
			});
			cadena = cadena.slice(0,-2); // Quitar el último elemento
			$("#orden").html("ORDER BY " + cadena);
		}
		// ***************************
		// Tras cargarlo todo. Inicio.
		// ***************************
		rellenarCondiciones(); // al principio, con las opciones por defecto.
			
	 }); // fin del document ready
	 
	 // ******************************************************
	 // Funciones en la página *******************************
	 // ******************************************************	 

	 //************************************
	 // F1) Obtener datos del filtro
	 function obtenerDatos() {
		 if ($("#fotoYN").jqxSwitchButton('checked')) { var fotoSN = 1; } else { var fotoSN=0; }
		 // alert(fotoSN);
		 console.log("****************** Obtiene SQL *******************");
		 console.log("SQL: "+$("#SQL").text());
		 console.log("Fotos: ");
		 return $.ajax({
			  type: 'POST',
			  dataType: 'text',	
		      url: "./tutorias/scripts/obtenerDatos.php", // En el script se construye la tabla...
		      data: { 
			  SQL: $("#SQL").text(), // La variable de sesión de la asignación se consigue en el script.	
			  foto: fotoSN,		  
		      },
		      success: function(data, textStatus, jqXHR){ 			  
				// alert(data);
				return data;
		      },
		  });
	  } // Fin de la función Obtener fechas de un alumno - asignación
	  

	  
	  
 <!-- * =======================================================================================================   * --> 	

  </script>
  
<!-- **************************************************************************************** -->
</body></html>
