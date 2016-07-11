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

if ($_SESSION["permisos"]==2) { $mostrar="text"; } else {  $mostrar="none"; } // Si eres administrador puedes ver cierto contenido...

// Las variables de sesión se establecen en los scripts AJAX en 

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="es">	
<head profile="http://www.w3.org/2005/10/profile">
  <!-- *** Principio del HEAD *************************************-->	
  <meta content="text/html; charset=iso-8859-15" http-equiv="content-type">
  <title>Enviar email al equipo educativo</title>
  <link rel="icon" 
        type="image/png" 
        href="./imagenes/logoA.png">
  <meta content="Aurelio Gallardo Rodríguez" name="author">  
  <meta content="Obtengo los datos de tutoría" name="description">

  <!-- ***************************** -->
  <!-- Linkar a hojas de estilo CSS -->
  <!-- ***************************** -->
  <?php include_once("./css/cargarestiloscss.php"); ?>
  <link rel="stylesheet" href="./css/estiloenviarEmail.css"> <!-- Efectos aplicados a esta hoja -->
  
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
            <div id="profesorado" class="effect7">
				<h1>PARA...</h1>
				<div id="todos" class="divasignacionEmail" title="Selecciona los emails de todo el equipo educativo">Todos/as</div>
				<div id="ninguno" class="divasignacionEmail" title="Deselecciona los emails de todo el equipo educativo">Ninguno/a</div>
				<?php 
				$asignacionesEstaTutoria = $asignacion->devuelveAsignacionesDeUnaTutoria($_SESSION["idasignacion"],$_SESSION["profesor"]);
				$profesoradoEmail=[];
				foreach ($asignacionesEstaTutoria as $clave => $valor) {
					$profesoradoEmail[]=$asignacion->asignacionDIV($valor);
				}
				$profesoradoEmail=array_unique($profesoradoEmail); // todos los divs los filtros y no los repito, si son iguales.
				foreach ($profesoradoEmail as $clave => $valor) { echo $valor; } // Presento los divs en pantalla								
				?>

            </div>
            <input id="Para" size="80" width="80" style="display: none;">
		</div>
		
		<div id="escribir" class="effect7">
			<h2></h2>
			<h2>Asunto</h2>
			<div class="zonaEscrituraEmail" id="zonaEscrituraEmailAsunto">
				<div id="editorAsunto" class="froala-view" title="Escribe asunto"></div>
			</div>
			<h2>Cuerpo del Mensaje</h2>
			<div class="zonaEscrituraEmail" >
				<div id="editorMensaje" class="froala-view" title="Escribe el mensaje a enviar"></div>
			</div>
			<button id="enviarMensaje"><i class="fa fa-envelope" style="font-size: 4em;"></i></button>
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
		
		<div id="notificacionEnviado">
			<div><h1>Aquí va la notificación</h1></div>
		</div>
		
		<div id="notificacionNoEnviado">
			<div><h1>Aquí va la notificación</h1></div>
		</div>
		
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
		
			 
		// ========================================================================================
		// Defino diálogos y/o notificaciones
		// ========================================================================================	
		
		 $("#notificacionEnviado").jqxNotification({
                width: 1000, position: "top-right", opacity: 0.9,
                autoOpen: false, animationOpenDelay: 300, autoClose: true, autoCloseDelay: 10000, template: "info"
         });
         
         $("#notificacionNoEnviado").jqxNotification({
                width: 700, position: "top-right", opacity: 0.9,
                autoOpen: false, animationOpenDelay: 300, autoClose: true, autoCloseDelay: 5000, template: "warning"
         });
		 
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
		 
		 $("#enviarMensaje").button();
		 
		 // ========================================================================================
		 // Al pulsar sobre el botón Enviar mensaje
		 // ========================================================================================
		 $('#enviarMensaje').click(function(e) {
			var SPara = $("#Para").val();
			var SAsunto = $("#editorAsunto").editable("getHTML", false, false);
			var SMensaje = $("#editorMensaje").editable("getHTML", false, false);
			$.when(sendEmail(SPara, SAsunto, SMensaje)).done(function(data){
				try {
					// alert(data);
					var datos = jQuery.parseJSON(data);
					if (datos.valido==1) {
						$("#notificacionEnviado").html(datos.informacion);
						$("#notificacionEnviado").jqxNotification("open");
					} else {
						$("#notificacionNoEnviado").html('<h1 style="font-size: 3em;">'+datos.error+'</h1>');
						$("#notificacionNoEnviado").jqxNotification("open");
					}
				} catch(err) {
					console.log(err.message);
				}
			});
		 });
		 
		 // **********************************		 
		 // Cuadro de escritura 
		 // **********************************

		 $('#editorMensaje').editable({ // idioma también cargando el es.js 
				 inlineMode: false, language: 'es', maxCharacters: 3000,
				 placeholder: 'Escribe el cuerpo del mensaje. Hasta 3000 caracteres...', 
				 heightMin: 100, heightMax: 300, height: 200,
				 buttons: ["bold", "italic", "underline", "strikeThrough","sep"
						   ,"fontFamily", "fontSize", "formatBlock", "color","sep"
						   ,"insertOrderedList", "insertUnorderedList", "outdent", "indent", "sep"
						   ,"createLink", "insertHorizontalRule", "table","html"],
		  });
		 // **********************************
		 $('#editorAsunto').editable({ // idioma también cargando el es.js 
			 inlineMode: false, language: 'es', maxCharacters: 1000,
			 placeholder: 'Escribe el asunto del mensaje. Hasta 1000 caracteres...', 
			 heightMin: 60, heightMax: 100, height: 60,
			 buttons: ["bold", "italic", "underline", "strikeThrough","sep"
					   ,"fontFamily", "fontSize", "formatBlock", "color","sep"
					   ,"insertOrderedList", "insertUnorderedList", "outdent", "indent", "sep"
					   ,"createLink", "insertHorizontalRule", "table","html"]
		 });
		 // **********************************
	 
		// Al pulsar sobre un div de profesor, se cambia su color y se añade un dato al div Para
		$('.divasignacionEmail').click(function(e) { 
			if (!($(this).attr("id")=="todos" || $(this).attr("id")=="ninguno")) {
				if ($(this).hasClass("divasignacionEmailSeleccionada")) {
					$(this).removeClass("divasignacionEmailSeleccionada"); // se la quita a las demás
				} else {
					$(this).addClass("divasignacionEmailSeleccionada"); // se la añade a la que se ha hecho click.
				}
				$('#ninguno').removeClass("divasignacionEmailSeleccionada");
				$('#todos').removeClass("divasignacionEmailSeleccionada");
				rellenarPara();
		    }  // Si no es todos o ninguno
		}); 
		
		
		// Seleccionados todos
		$('#todos').click(function(e) { 
			$('.divasignacionEmail').each(function(e) {
				$(this).addClass("divasignacionEmailSeleccionada"); // se la quita a las demás
		    });
		    $('#ninguno').removeClass("divasignacionEmailSeleccionada");
		    rellenarPara();
		});
		
		// Deselecciona todos
		$('#ninguno').click(function(e) { 
			$('.divasignacionEmail').each(function(e) {
				$(this).removeClass("divasignacionEmailSeleccionada"); // se la quita a las demás
		    });
		    $('#ninguno').addClass("divasignacionEmailSeleccionada");
		    rellenarPara();
		});
		
		// Rellena el input PARA
		function rellenarPara() {
			var cadenaEmail = "";
			$('.divasignacionEmail').each(function(e) {
				if ($(this).hasClass("divasignacionEmailSeleccionada") && !($(this).attr("id")=="todos" || $(this).attr("id")=="ninguno")) {
					cadenaEmail = cadenaEmail + $(this).attr("id")+";";
				}
			});
			$('#Para').val(cadenaEmail.slice(0,-1));
		} // Fin de rellena el INPUT Para
		
				
	 }); // fin del document ready
	 
	 // Al terminar de cargar el DOM
	 $(window).load(function() {
		 $('#editorMensaje').editable({fontSizeDefaultSelection: '40px'});
	 });
	 
	 // ******************************************************
	 // Funciones en la página *******************************
	 // ******************************************************	 

	 //************************************
	 // F1) Obtener datos del filtro
	 function sendEmail(para,asunto,mensaje) {
		 console.log("****************** Obtiene Mensaje a enviar *******************");
		 console.log("Asunto: "+asunto);
		 console.log("Para: "+para);
		 console.log("Mensaje: "+mensaje);
		 return $.ajax({
			  type: 'POST',
			  dataType: 'text',	
		      url: "./tutorias/scripts/sendEmail.php", // En el script se construye la tabla...
		      data: { 
				 para: para,
				 asunto: asunto,
				 mensaje: mensaje,
		      },
		      success: function(data, textStatus, jqXHR){ 			  
				// alert(data);
				return data;
		      },
		  });
	  } // Fin de la función Obtener datos del filtro  

	  
 <!-- * =======================================================================================================   * --> 	

  </script>
  
<!-- **************************************************************************************** -->
</body></html>
