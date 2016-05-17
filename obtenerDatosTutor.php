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

// Las variables de sesi�n se establecen en los scripts AJAX en 

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="es">	
<head profile="http://www.w3.org/2005/10/profile">
  <!-- *** Principio del HEAD *************************************-->	
  <meta content="text/html; charset=iso-8859-15" http-equiv="content-type">
  <title>Obtengo los datos de tutor�a</title>
  <link rel="icon" 
        type="image/png" 
        href="./imagenes/logoA.png">
  <meta content="Aurelio Gallardo Rodr�guez" name="author">  
  <meta content="Obtengo los datos de tutor�a" name="description">

  <!-- ***************************** -->
  <!-- Linkar a hojas de estilo CSS -->
  <!-- ***************************** -->
  <?php include_once("./css/cargarestiloscss.php"); ?>
  <link rel="stylesheet" href="./css/estiloobtenerDatosTutor.css"> <!-- Efectos aplicados a los DIVs -->
  
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
				<li><a href="#Datos">Datos</a></li>
				<li><a href="#Instrucciones">Instrucciones</a></li>				
			</ul> 
			<!-- ********************************************************** -->
			<!-- Filtrado de datos -->
			<!-- ********************************************************** --> 			
			<div id="FiltrodeDatos">
				<!-- Selecci�n de datos -->
				<div id="acordeon">
					<h3>Por asignaci�n</h3>
						<div>
							<?php // Select para seleccionar asignaciones
							$asignacionesmiTutoria = $asignacion->devuelveAsignacionesDeUnaTutoria($_SESSION["idasignacion"],$_SESSION["profesor"]);
							echo '<div id="asignaciones" title="Escoge la asignaci�n de Filtrado">';
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
							echo '<div id="alumnos" title="Escoge un alumno de tu tutor�a">';
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
							<div id="fechaInicio">Fecha de Inicio</div>
							<div id="fechaFinal">Fecha Final</div>
							<div id="primerTrimestre"><?php echo $evaluacion->listadoDeEvaluaciones["div"][0]; ?></div>
							<div id="segundoTrimestre"><?php echo $evaluacion->listadoDeEvaluaciones["div"][1]; ?></div>
							<div id="tercerTrimestre"><?php echo $evaluacion->listadoDeEvaluaciones["div"][2]; ?></div>
							<div id="quincedias"><div class="divNombreEval" style="padding: 10px;">Hace quince d�as</div></div>
							<div id="haceunmes"><div class="divNombreEval" style="padding: 10px;">Hace un mes</div></div>
							<div id="hacedosmeses"><div class="divNombreEval" style="padding: 10px;">Hace dos meses</div></div>
							<p id="textoFecha">Cualquier Fecha</p>
						</div>
					<h3>Por Item</h3>
						<div>Items</div>
				</div>
				<!-- Escritura de cadena SQL -->
				<div id="CadenaSQL">
					<h1>Condiciones</h1>
					<p id="condiciones"></p>
					<h1>Cadena SQL</h1>
					<p id="SQL"></p>
				</div>
			</div>

			<!-- ********************************************************** -->
			<!-- Mostrar los datos que se han filtrado -->
			<!-- ********************************************************** --> 	
			<div id="Datos">
			
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
		
		<!-- <div id="notificacionGuardado">
			<div><h1>Se ha registrado el dato y guardado</h1></div>
		</div> -->
		
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
		 var textoFecha = "Cualquier Fecha";
		 var fechaINI = "#";
		 var fechaFIN = "#";

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
		
		 // ========================================================================================
		 // DEFINO tabs. LLAMO Pesta�as. y tambi�n el acordeon
		 // ========================================================================================
		
		$('#pesta�as').tabs(); 
		$('#acordeon').accordion({
		 icons: { "header": "ui-icon-arrowthick-1-e", "activeHeader": "ui-icon-star" },
		 animate: 800,
		}); 
		// $('#acordeon.ui-accordion').css({"width":"50%"}) // ancho del acorde�n
	 
		// ========================================================================================
		// Defino di�logos y/o notificaciones
		// ========================================================================================	
		
		/* $("#notificacionGuardado, #notificacionModificar").jqxNotification({
				width: 400, position: "top-right", opacity: 0.9,
				autoOpen: false, animationOpenDelay: 300, autoClose: true, autoCloseDelay: 2000, template: "info"
		 }); */
		 
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

		//2) Definici�n del SELECT de alumnos
    	$( "#EscogerAlumno" )
			.selectmenu({
				width:700, 
				style: 'popup',
			})			
			.selectmenu("menuWidget")
			   .addClass("overflow5"); // carga un estilo que est� en /css/estiloSelectMenuOverflow.css		   
			   
	   	// ******************************************************  
	   	
	   	$( "#EscogerAsignacion, #EscogerAlumno" ).selectmenu({
			change: function(event,ui) {
				rellenarCondiciones();
			}
		});  
		
		// Al pulsar sobre alguna de los botones de Fecha
		$("#cualquierFecha").click(function(event,ui){
			textoFecha = "Cualquier Fecha";
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
		
		// Funciones dentro del document ready
		function rellenarCondiciones() {
			var escogerAsignacion = $( "#EscogerAsignacion option:selected").text();
			var escogerAlumno = $( "#EscogerAlumno option:selected").text();
			$("#condiciones").html(escogerAsignacion+", "+escogerAlumno+", "+textoFecha);
			$("#SQL").html(fechaINI+" - "+fechaFIN);
		};
		
		
		// ***************************
		// Tras cargarlo todo. Inicio.
		// ***************************
		rellenarCondiciones(); // al principio, con las opciones por defecto.
			
	 }); // fin del document ready
	 
	 // ******************************************************
	 // Funciones en la p�gina *******************************
	 // ******************************************************	 

	  
 <!-- * =======================================================================================================   * --> 	

  </script>
  
<!-- **************************************************************************************** -->
</body></html>
