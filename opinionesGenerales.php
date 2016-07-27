<?php header('Content-type: text/html; charset=iso-8859-15'); ?>
<?php
// Codigo que se ejecuta al principio de la página
// importante incluir al principio de cada una, lo de las funciones
include_once("./funciones/funciones.php"); // funciones varias de conexión a base de datos, etc.

// Incluyo además las clases que se van a usar
include_once("./clases/class.micalendario.php"); //clase mi calendario que presenta fechas formateadas
// include_once("./clases/class.profesores.php"); //clase que recupera datos de profesores
// include_once("./clases/class.formulario.php"); //clase que gestiona diversos formularios
include_once("./clases/class.cursos.php"); //clase que recupera datos de cursos
include_once("./clases/class.alumnos.php"); //clase que recupera datos de alumnos
// include_once("./clases/class.materias.php"); //clase que recupera datos de materias
// include_once("./clases/class.asignaciones.php"); //clase que recupera datos de materias
// include_once("./clases/class.opiniones.php"); //clase que recupera datos de opiniones
include_once("./clases/class.evaluaciones.php"); //clase que recupera datos de evaluaciones

// incluyo las variables de esas clases
$calendario = New micalendario(); // variable de calendario. Lo necesito para la cabecera
// $profesorado = New profesores(); //variable de la clase profesores
// $formulario = New formulario(); // variable de la clase formulario
$curso = New misCursos(); // variable de la clase curso
$alumno = New misAlumnos(); // variable de la clase alumnos
// $materia = New misMaterias(); // variable de la clase materia
// $opiniones = New misOpiniones(); // variable de la clase opiniones
// $asignacion = New misAsignaciones($curso, $profesorado, $materia); // Uso el constructor para pasarle la clase curso, profesorado y materias a Asignaciones
$evaluacion = New misEvaluaciones(); // variable de la clase Evaluaciones

// Variables de sesión
session_start();

if ($_SESSION['permisos']<1) { // en caso que no tenga permisos para entrar
	echo header("Location: ./index.php");
}

// Las variables de sesión se establecen en los scripts AJAX en 

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="es">	
<head profile="http://www.w3.org/2005/10/profile">
  <!-- *** Principio del HEAD *************************************-->	
  <meta content="text/html; charset=iso-8859-15" http-equiv="content-type">
  <title>Opinión General de una clase</title>
  <link rel="icon" 
        type="image/png" 
        href="./imagenes/logoA.png">
  <meta content="Aurelio Gallardo Rodríguez" name="author">  
  <meta content="Página índice de la web de tutoría" name="description">

  <!-- ***************************** -->
  <!-- Linkar a hojas de estilo CSS -->
  <!-- ***************************** -->
  <?php include_once("./css/cargarestiloscss.php"); ?>
   <!-- Estilo de letra de la familia Averia Gruesa Libre -->
  <link href='http://fonts.googleapis.com/css?family=Averia+Gruesa Libre' rel='stylesheet' type='text/css'>
 
  <!-- *** Final del HEAD, antes los ficheros de enlace a CSS ******-->
</head>

<body>
<!-- **************************************************************************-->	
<!-- *** Principio del BODY ***************************************************-->	
<!-- **************************************************************************-->	

<div id="container"> <!-- CONTENEDOR PRINCIPAL -->
	
	<!-- HTML suelto: barra superior, menu superior, menu lateral,   -->
	<?php include_once("./htmlsuelto/cabecera.php"); ?>
	<?php include_once("./htmlsuelto/barrasuperior.php"); // y dentro de él el include al menú correspondiente ?> 
	<div id="menu" style="text-align: justify; padding-right: 1em;">
		<?php include_once("./htmlsuelto/menuIZQUIERDO.php"); ?> 
 	</div>     
    
    <!-- ******************************************* -->
    <!-- ******** ZONA DE CÁLCULOS PREVIOS ********* -->
    <!-- ******************************************* -->
    <?php // Zona en la que se extraen variables de las distintas clases
       // $fechadehoy="2016-12-12";
       $fechadehoy=date("Y-m-d"); // Es la fecha del día de hoy. 
    ?>    
    <!-- *********************************************************** -->
     
	<div id="test"> <!-- TESTER -->
	    <p id="testear">
	    </p>
	    <input id="idasignacion" value="<?php echo $_SESSION["idasignacion"]; ?>" style="display: none;"> 
	    <input id="idevaluacion" value="<?php echo $evaluacion->calculaFecha($fechadehoy,true); ?>" style="display: none;"> 
	    <input id="fechaevaluacion" value="<?php echo $evaluacion->calculaFecha($fechadehoy,false); ?>" style="display: none;"> 	    
    </div>	<!-- TESTER -->
    
    <!-- ********************************************************** -->
    <!-- Contenido Principal -->
    <!-- ********************************************************** --> 
    
    <div id="contents"> <!-- &&&& -->  
		
		<div id="pestañas">
            <ul>
				<li><a href="#opinionesGenerales">Opinión General de un grupo de Alumnos/as</a></li>
				<li><a href="#Instrucciones">Instrucciones</a></li>
			</ul> 
	<!-- ******************************************************************************************** --> 
		 
		<div id="opinionesGenerales">			
			<div id="opinar">
				<!-- Botón de grabar los datos -->
				<div id="grabar" class="grabar" title="Hasta que no se pulsa el botón GRABAR, no queda guardado ningún dato o modificación">
						<i class="fa fa-plus-square" style="color: darkgreen;"></i>&nbsp;&nbsp;Grabar
				</div>
				<h2>Opinión sobre la clase</h2>
					<!-- Editor FROALA. Importante, añadir la clase froala-view -->
					<div class="zonaEscrituraOpiniones" >
						 <div id="editorOpinion" class="froala-view" title="Inserta una OPINIÓN sobre la clase en GENERAL"></div>
					</div>
				<h2>Actuaciones llevadas a cabo</h2>
					<div class="zonaEscrituraOpiniones">
						<div id="editorActuaciones" class="froala-view" title="¿Qué ACTUACIONES has llevado a cabo en esta evaluación con este grupo?"></div>
					</div>
				<h2>Propuestas de mejora</h2>
					<div class="zonaEscrituraOpiniones">
						<div id="editorMejora" class="froala-view" title="¿Qué opinas que debe MEJORAR en este grupo?"></div>
					</div>	
			</div>
			
	<!-- ******************************************************************************************** --> 
			
			<div id="eligeEval">
				<?php 
				   // $evaluacion->listaEvaluaciones(); No hace falta porque lo meto en el constructor
				   foreach ($evaluacion->listadoDeEvaluaciones["div"] as $clave => $valor) {
				    	 	echo $valor;
				   }				   
				?>
			</div>
			
	    </div>
	<!-- ******************************************************************************************** --> 
	
		<div id="Instrucciones">
			<div id="instruccionesOG">
				<ol>
					<li>Selecciona primero una evaluación de la lista de la derecha.</li>
					<li>Escribe tu opinión o modifica un contenido previo.</li>
					<li>IMPORTANTE: acepta los cambios pulsando GRABAR.</li>
				</ol>
			</div>
			<p style="text-align: center; margin: 40px;">
				<iframe width="800" height="500" src="https://www.youtube.com/embed/dvRLffYElB4" frameborder="0" allowfullscreen></iframe>
			</p>	
		</div>
		
	   </div> <!-- &&&& FIN DE PESTAÑAS-->	
    <!-- ================================================================================================== --> 
    
    <!-- * ==========================  DIALOGOS   =====================================   * --> 			
		
		<!-- Dialogo de Confirmación de copiar los datos -->	
		<div id="dialog-confirm" title="Copiar datos">
		   <p><span class="fa fa-spinner fa-pulse fa-2x" style="float:left; margin:0 7px 20px 0;">
		   </span>
		   ¿De verdad quieres copiar estos datos a la nueva fecha?</p>
		</div>	
		
		<!-- Dialogo de Confirmación de borrar los datos -->	
		<div id="dialog-confirm-borrar" title="Borrar datos">
		   <p><span class="fa fa-trash fa-2x" style="float:left; margin:0 7px 20px 0;">
		   </span>
		   ¿De verdad quieres borrar las opiniones seleccionadas?<span class="hoverAsignacion"></span>
		   </p>
		</div>
		
		<!-- Dialogo de Alerta que no hay datos seleccionados-->	
		<div id="dialog-confirm-nohaydatos" title="No hay datos">
		   <p><span class="fa fa-exclamation-triangle fa-2x" style="float:left; margin:0 7px 20px 0;">
		   </span>
		   No se han seleccionado datos...<span class="hoverAsignacion"></span>
		   </p>
		</div>
		
		<!-- Notificaciones -->
		<div id="notificacionGuardado">
		<div><h1>Se ha registrado el dato y guardado</h1></div>
		</div>

		<div id="notificacionModificar">
		<div><h1>El dato existente se ha salvado y/o modificado</h1></div>
		</div>

		<div id="notificacionBorrar">
		<div><h1>El dato se ha borrado (se borra cuando no escribes nada o no añades opiniones pre-establecidas)<h1></div>
		</div>
		
		<div id="notificacionFuera">
		<div><h1>La fecha actual está fuera de los períodos de evaluación establecidos<h1></div>
		</div>
			
	</div> <!-- &&&& FIN DEL CONTENEDOR-->	

	<!-- ********************************************************** -->
	<!-- FIN DEL CONTENIDO PRINCIPAL -->
	<!-- ********************************************************** -->
    
    <!-- HTML suelto: pie de página *******************************  -->
    <?php include_once("./htmlsuelto/pie.php"); ?> 
    <!-- ********************************************************** -->
    
</div> <!-- FIN del CONTENEDOR PRINCIPAL -->

<!-- **************************************************************************-->	
<!-- *** Final del BODY, antes los scripts ************************************-->
<!-- **************************************************************************-->	

<!-- *************************** -->
<!-- Scripts JQUERY y Javascript -->
<!-- *************************** -->
  
  <script src="./jquery/external/jquery/jquery.js"></script>
  <script src="./jquery/jquery-ui.min.js"></script> <!-- version 1.11.2 -->
  <!-- <script src="//code.jquery.com/ui/1.11.0/jquery-ui.min.js"></script> <!-- version 1.11.0 -->
  <!-- <script src="./htmlsuelto/js_menu.js"></script>   Incorpora al script los menús a la izquierda -->  
  <script type="text/javascript" src="./jquery/jqx/jqxcore.js"></script>
  <script type="text/javascript" src="./jquery/jqx/jqx-all.js"></script> 
  <script type="text/javascript" src="./jquery/jqx/jqxpanel.js"></script>  
  <script type="text/javascript" src="./jquery/jqx/jqxscrollbar.js"></script> 
  <!-- Owl carousel --> 
  <script type="text/javascript" src="./owl-carousel/owl.carousel.js"></script>
  <!-- Editor de texto froala. Non commercial use -->
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
  <script src="./jquery/froala/js/plugins/video.min.js"></script>
  
  <script>     
     
     $(document).ready(function() {  
		
		var evalPulsada = $('#idevaluacion').val(); // define la evaluación por defecto. 
		 
        // ========================================================================================
        // Incorpora otros scripts
        // ========================================================================================
        
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
		
		// 1d) Definición del diálogo de confirmación de grabar datos, borrar y modificar asignacion y confirmar que no hay datos
		  $("#dialog-confirm, #dialog-confirm-borrar, #dialog-confirm-nohaydatos").dialog({
			autoOpen: false,
			modal: true,
			maxWidth:600,
            maxHeight: 300,
            width: 600,
            height: 300,
			position: { my: "center center-100", at: "center center", of: "#container" }
			// el "centro arriba" de mi cuadro de diálogo (my) , en el centro arriba (at) del contenedor (of)
		 });	
		 
	// ========================================================================================
	// Defino diálogos y/o notificaciones
	// ========================================================================================	
	 $("#notificacionGuardado, #notificacionModificar").jqxNotification({
			width: 400, position: "top-right", opacity: 0.9,
			autoOpen: false, animationOpenDelay: 300, autoClose: true, autoCloseDelay: 2000, template: "info"
	 });
	 $("#notificacionBorrar , #notificacionFuera").jqxNotification({
			width: 400, position: "top-right", opacity: 0.9,
			autoOpen: false, animationOpenDelay: 500, autoClose: true, autoCloseDelay: 4000, template: "warning"
	 });	 	 
    
	 // ========================================================================================
     // DEFINO tabs. LLAMO Pestañas. Defino editor en zonaEscribir
     // DEFINO jqxButton. Botón de grabar
     // ========================================================================================
	
	 $('#pestañas').tabs(); 
	 
	 // $('#grabar').jqxButton(); 
	 $('#grabar').jqxButton({ theme: 'ui-sunny'} ); 
	 $('#grabar.jqx-widget-ui-sunny').css({"font-size":"30px"}); // Modifico esta propiedad del botón
	 
	 // ========================================================================================
     // DEFINO tabs. LLAMO Pestañas. Defino editor en zonaEscribir
     // ========================================================================================
	 
	 // Cuadro de escritura 
	 $('#editorOpinion, #editorActuaciones, #editorMejora').editable({ // idioma también cargando el es.js 
			 inlineMode: false, language: 'es', maxCharacters: 3000,
			 placeholder: 'Escribe algo. Hasta 3000 caracteres...', 
			 buttons: ["bold", "italic", "underline", "strikeThrough","sep"
			           ,"fontFamily", "fontSize", "formatBlock", "color","sep"
			           ,"insertOrderedList", "insertUnorderedList", "outdent", "indent", "sep"
			           ,"createLink", "insertHorizontalRule", "table","html"]
	 });

	 // ========================================================================================	 
	 // Evaluación pulsada por defecto al INICIAR LA APLICACIÓN 
	 // ========================================================================================
	 // Depende del valor INICIAL de evalPulsada
	 	 if (evalPulsada>0) {	 // Si existe la evaluación pulsada y es mayor que cero 	 
			 $('.divNombreEval').each(function(e) {
				$(this).removeClass("divNombreEvalSeleccionado"); // Las quita por defecto.
				// Comprueba cual es y ya lo selecciona...	
				if ($(this).attr("id")==evalPulsada) {
					$(this).addClass("divNombreEvalSeleccionado");
					obop(); // llama a esa función
				}
			 });		 
	     } else { // caso que sea cero o negativo
			 $("#notificacionFuera").jqxNotification("open"); 
		 }
	  
	 	 
	 // ========================================================================================
     // Al pulsar sobre una de las evaluaciones
     // ========================================================================================
	 $('.divNombreEval').click(function(e) { 
		 // alert($(this).attr("name"));
		 $('.divNombreEval').each(function(e) {
			$(this).removeClass("divNombreEvalSeleccionado"); // se la quita a las demás
		 });
		 $(this).addClass("divNombreEvalSeleccionado"); // se la añade a la que se ha hecho click.
		 evalPulsada = $(this).attr("id"); // seleccionada la evaluación dada...
		 // recupera datos si los tuviese
         obop(); // llama a esa función
	 }); 
	 
	 // ========================================================================================
     // Al pulsar sobre el botón grabar
     // ========================================================================================
	 $('#grabar').click(function(e) { 
		$.when(insertarOpinionGeneral(evalPulsada)).done(function(data2){
			try {
				var datos2 = jQuery.parseJSON(data2);
				// alert(datos2.devolver+" "+datos2.notificacion);
				notificaciones(datos2.devolver,datos2.notificacion);
			} catch(err) {
				console.log(err.message);
			}
		});
	 });
	
	 // =======================================================================================
     // Funciones
     // ========================================================================================
	 function obop() { // Método de obtener opinión
		   $.when(obtenerOpinionGeneral(evalPulsada)).done(function(data){
				try {
				   var datos = jQuery.parseJSON(data); // recupera en el array datos los valores
				   $("#editorOpinion").editable("setHTML", datos.opinion, true); // obtiene la opinion
				   $("#editorActuaciones").editable("setHTML", datos.actuaciones, true); // obtiene las actuaciones
				   $("#editorMejora").editable("setHTML", datos.mejora, true); // obtiene las mejoras 
				   notificaciones(4,'<div><h1><i class="fa fa-info-circle" style="color: darkblue;"></i>&nbsp;Dato recuperado correctamente</h1></div>');
				} catch(err) {
				   console.log(err.message);
				   $("#editorOpinion").editable("setHTML", "", true); // opinion vacía
				   $("#editorActuaciones").editable("setHTML", "", true); // actuaciones vacías
				   $("#editorMejora").editable("setHTML", "", true); // mejoras vacía
				   notificaciones(5,'<div><h1><i class="fa fa-exclamation-circle" style="color: red;"></i>&nbsp;El dato no existe aún</h1></div>');
				}	
		   });
	 } // Fin de la funciones Obtener Opinion
	 
	 // Retorno de notificaciones. Datos provienen de insertar Alumno opinión
		 function notificaciones(devolver,notificacion) {
			if (devolver==1 || devolver==4)  { // 4, notificacion de lectura
				$("#notificacionGuardado").html(notificacion); 
				$("#notificacionGuardado").jqxNotification("open"); 				
			}
			if (devolver==2) { 
				$("#notificacionModificar").html(notificacion); 
				$("#notificacionModificar").jqxNotification("open"); 
			}
			if (devolver==3 || devolver==5)  { // 5, notificación de dato inexistente
				$("#notificacionBorrar").html(notificacion);
				$("#notificacionBorrar").jqxNotification("open"); 
			}			
		 } // fin de la función de notificaciones
	
	 // * ================================================================================================ */	
	 }); // fin del document ready
	 
// * ================================================================================================ */	 
	 // ******************************************************
	 // Eventos que se cargan tras cargar la página **********
	 // ******************************************************	
	 // $(document).on('click','.eleccion',function(event){

     // });         


// * ================================================================================================ */	 	 
	 // ******************************************************
	 // Funciones en la página *******************************
	 // ******************************************************	 	
	 // F1) Graba/modifica una opinion...
	 function insertarOpinionGeneral(evaluacion) {
		     console.log("****************** Grabar Opinión *******************");
		     console.log("eval: "+evaluacion);
		     console.log("opinion: "+$("#editorOpinion").editable("getHTML", false, false));
		     console.log("actuaciones: "+$("#editorActuaciones").editable("getHTML", false, false));
		     console.log("mejora: "+$("#editorMejora").editable("getHTML", false, false));
		     console.log("idasignacion: "+$("#idasignacion").val());
		     return $.ajax({
			      type: 'POST',
			      dataType: 'text',
			      url: "./opiniones/scripts/insertarOpinionGeneral.php", 
			      data: { // Parece que las llamadas con ajax van mejor que con POST...
				  evaluacion: evaluacion, // variable con la evaluación escogida
				  opinion: $("#editorOpinion").editable("getHTML", false, false),
		          actuaciones: $("#editorActuaciones").editable("getHTML", false, false),
		          mejora: $("#editorMejora").editable("getHTML", false, false),
				  idasignacion: $("#idasignacion").val(),
				  },					 		 
		          success: function(data, textStatus, jqXHR){
					 // alert(data);
				     return data;
			      },
			      error: function (jqXHR , textStatus, errorThrown) {
					 return "error";
				  }
			  }); 
	  } // Fin de la función grabar/modifica dato...
	  
	 //************************** 
	 // F2) Obtener opinion
	 function obtenerOpinionGeneral(evaluacion) {
		 console.log("****************** Obtiene Opinión *******************");
		 console.log("eval: "+evaluacion);
		 console.log("Asignacion: "+$("#idasignacion").val());
		 return $.ajax({ 
			  type: 'POST',
			  dataType: 'text',			      
		      url: "./opiniones/scripts/obtenerOpinionGeneral.php", 
		      data: { 
			  evaluacion: evaluacion, // variable con la evaluación escogida
			  idasignacion: $("#idasignacion").val(),
		      }, 
		      success: function(data, textStatus, jqXHR){
				// alert(data+" // estado: "+textStatus+" // jqXHR: "+jqXHR);
				return data;
			  },
			  error: function (jqXHR , textStatus, errorThrown) {
				 return "error";
			  }
		  }); 
	  } // Fin de la función Obtener dato 
	  
 <!-- * =======================================================================================================   * --> 	

  </script>
  
<!-- **************************************************************************************** -->
</body></html>
