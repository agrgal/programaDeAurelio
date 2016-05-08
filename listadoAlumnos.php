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

// incluyo las variables de esas clases
$calendario = New micalendario(); // variable de calendario. Lo necesito para la cabecera
$profesorado = New profesores(); //variable de la clase profesores
// $formulario = New formulario(); // variable de la clase formulario
$curso = New misCursos(); // variable de la clase curso
$alumno = New misAlumnos(); // variable de la clase alumnos
$materia = New misMaterias(); // variable de la clase materia
$opiniones = New misOpiniones(); // variable de la clase opiniones
$asignacion = New misAsignaciones($curso, $profesorado, $materia); // Uso el constructor para pasarle la clase curso, profesorado y materias a Asignaciones

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
  <title>Listado de los alumnos y Fotografías</title>
  <link rel="icon" 
        type="image/png" 
        href="./imagenes/logoA.png">
  <meta content="Aurelio Gallardo Rodríguez" name="author">  
  <meta content="Listado de alumnos. Tutoría." name="description">

  <!-- ***************************** -->
  <!-- Linkar a hojas de estilo CSS -->
  <!-- ***************************** -->
  <?php include_once("./css/cargarestiloscss.php"); ?>
  <link rel="stylesheet" href="./css/estiloListadoAlumnos.css"> <!-- Los pongo por separado para no recargar tanto -->
  <link href="./jquery/dropzone/dropzone.css" type="text/css" rel="stylesheet" /> <!-- Estilo de la zona de carga de fotos -->
  
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

    ?>    
    <!-- *********************************************************** -->
     
	<div id="test"> <!-- TESTER -->
	    <p id="testear">
				<?php // echo $_SESSION['permisos']." - ".$_SESSION['tutor']." - ".$_SESSION['profesor']." - ".$_SESSION['idasignacion']; ?>
	    </p>
    </div>	<!-- TESTER -->
    
    <!-- ********************************************************** -->
    <!-- Contenido Principal -->
    <!-- ********************************************************** --> 
    
    <div id="contents"> <!-- &&&& --> 
		<div id="CabeceraListadoAlumnos">
			<?php 
			    $asignacion->listarAsignaciones($_SESSION['profesor']); 
			    $clave=array_search($_SESSION['idasignacion'],$asignacion->listaDeAsignaciones['idasignacion']);
			?>
			<h1>
				Tutoría de <?php echo $asignacion->listaDeAsignaciones['cursosAfectados'][$clave]?>
			</h1>
			<div id="printer" title="Imprime listado de los alumn@s" title="Imprime listado de los alumn@s" ><img src="./imagenes/iconos/printer_pdf.png"></div>
		</div>
		<div id="ListadoAlumnos"> <!-- Zona de listado de alumnos --> 
			<?php 
				$alumnado = $asignacion->devuelveListadoAlumnosdeEstaAsignacion($_SESSION["idasignacion"],$_SESSION["profesor"]);
				$alumnadoArray=explode("#",$alumnado); // devuelve un array con los números de alumnos...
				  $norden = 0;
				  foreach ($alumnadoArray as $clave => $i) {
					  $norden++;
					  $alumno->devuelveAlumno($i);
					  $cadena=sprintf($alumno->esteAlumno["divFotos"],$i,$norden,$i); 
					  echo $cadena; 
				  } 
			?>
		</div> <!-- &&&& FIN DE LA ZONA DEL LISTADO DE ALUMNOS-->	
		
		<!-- * ==========================  DIALOGOS  Y NOTIFICACIONES =====================================   * --> 			
		
		<!-- Dialogo de Subida de fotografías -->	
		<div id="dialog-fotos" title="Subida de Fotografías. Limitado a 300KB máximo.">
		   <p><span class="fa fa-camera-retro fa-2x" style="float:left; margin:0 7px 20px 0;">
		   </span></p>
		   <p id="idAlumnoFoto"></p>
		   <form action="upload.php"
			  class="dropzone"
			  id="zonaDropzone">
			  <input type="hidden" name="idAlFoto">
		   </form>
		   <h1 id="FotoActual">Fotografía actual:&nbsp;&nbsp;<img id="FotoActualImg" src="./imagenes/iconos/chicochica.png"></h1>
		</div>	
		
		<div id="notificacionGuardado">
			<h1>Se ha guardado la fotografía.</h1>
		</div>
		
		<div id="notificacionError">
			<h1>Sólo se puede subir una fotografía por alumno.</h1>
		</div>
		
		<div id="notificacionBorrado">
			<h1>Fotografía borrada</h1>
		</div>
		
		<!-- * ==========================  DIALOGOS  Y NOTIFICACIONES =====================================   * --> 
	
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
  <!-- <script src="./jquery/jquery-ui.min.js"></script> <!-- version 1.11.2 -->
  <script src="//code.jquery.com/ui/1.11.0/jquery-ui.min.js"></script> <!-- version 1.11.0 -->
  <!-- <script src="./htmlsuelto/js_menu.js"></script>   Incorpora al script los menús a la izquierda -->  
  <script type="text/javascript" src="./jquery/jqx/jqxcore.js"></script>
  <script type="text/javascript" src="./jquery/jqx/jqx-all.js"></script> 
  <!-- Descargas con drop zone. Subida de fotos. Ver sección de CSS donde hay estilos para ello. -->
  <script type="text/javascript" src="./jquery/dropzone/dropzone.js"></script> 
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

		// Versiones de JQUERY y JQUERY-UI
		// alert($().jquery);
	    // alert($.ui.version);	
	     
	    // Variables globales
	    var respuesta = "";    		 

		// 1a) Incorpora la funcionalidad del menú de la IZQUIERDA
		$.getScript( "./htmlsuelto/js_menu.js", function( data, textStatus, jqxhr ) {
		console.log( data ); // Data returned
		console.log( textStatus ); // Success
		console.log( jqxhr.status ); // 200
		console.log( "Load was performed." );
		}); 
		
		// 1c) Definición del diálogo de confirmación de grabar datos, borrar y modificar asignacion y confirmar que no hay datos
		  $("#dialog-fotos").dialog({
			autoOpen: false,
			modal: true,
			maxWidth:1000,
            maxHeight: 675,
            width: 1000,
            height: 675,
			position: { my: "center center-100", at: "center center", of: "#container" },
			// el "centro arriba" de mi cuadro de diálogo (my) , en el centro arriba (at) del contenedor (of)
			hide: { effect: "fade", duration: 2000 }, //put the fade effect
			show: { effect: "fade", duration: 500 }, //put the fade effect
			open: function(event, ui){  // Al abrir, reinicia el dropzone
				Dropzone.forElement("#zonaDropzone").removeAllFiles(true);
			},	
		 });
		 
		// ========================================================================================
        // 1d) Defino diálogos y/o notificaciones
        // ========================================================================================	
		 $("#notificacionGuardado").jqxNotification({
                width: 500, position: "top-right", opacity: 0.9,
                autoOpen: false, animationOpenDelay: 300, autoClose: true, autoCloseDelay: 5000, template: "info"
         });
         
         $("#notificacionError, #notificacionBorrado").jqxNotification({
                width: 500, position: "top-right", opacity: 1,
                autoOpen: false, animationOpenDelay: 300, autoClose: true, autoCloseDelay: 5000, template: "error"
         });
		 
		 // 2) Al hacer click en un elemento se abre el cuadro de diálogo
		 $(".divalumno2").click(function(event) {
			   event.preventDefault();
			   // identificación del alumno
			   $("#idAlumnoFoto").html($(this).attr('title'));
			   $("#notificacionGuardado").html("<h1>Se ha guardado la fotografía de "+$(this).attr('title')+"</h1>"); // Y en la notificación
			   // Cambio el input que pasará al upload.php el identificador del alumno
			   $('input[name="idAlFoto"]').val($(this).attr('id'));
			   // Poner la imagen de la fotografía
			   // alert($(this).children('#image').children('img').attr('src'));
			   $('#FotoActualImg').attr("src",$(this).children('#image').children('img').attr('src'));
			   // incluye información...			   
			   $("#dialog-fotos").dialog({
					buttons : {
						"Borrar fotografía" : function() {
							  $.when(borrarFotografia($('input[name="idAlFoto"]').val())).done(function(data){
								 // borrado de fotografía existente...
								 // alert(data); 
								 $("#notificacionBorrado").html('<h1>'+data+'</h1>');
								 $("#notificacionBorrado").jqxNotification("open");
								 $("#dialog-fotos").dialog("close");
							     setTimeout(location.reload(), 2000); // retraso de un par de segundos.
							  });
						  },
						"No,no... Cancela" : function() {
						  $(this).dialog("close"); // no recarga la página. Simplemente anula la operación y sigue				 
						}
					} // fin del buttons
				}); // fin del dialog
			   
				$("#dialog-fotos").dialog("open"); // si no, no lo abre
				
	     }); // Fin de subir foto.
	     
		 // 3) Opciones Dropzone. Se aplica a la zona de carga con zonaDropzone es el id.
		 Dropzone.options.zonaDropzone = {
				maxFiles: 1,
				maxFilesize: 0.3, // Límite de 0.3M de tamaño de fotografías.
				uploadMultiple: false, // Para no enviar múltiple ficheros en un sólo requerimiento
				dictDefaultMessage: "<h1>Arrastra una fotografía o Pulsa aquí</h1>",
				dictResponseError: 'Error subiendo el fichero',
				acceptedFiles: "image/jpeg,image/png", // Tipos de ficheros de subida.
				accept: function(file, done) {
					console.log("Subido ya");
					done();
				},
				init: function() {
					this.on("maxfilesexceeded", function(file){
						// Por si se pone más de una fotografía
						$("#notificacionError").html("<h1>No se puede subir más de una fotografía</h1>");
						$("#notificacionError").jqxNotification("open"); 
						$("#dialog-fotos").dialog("close");
						this.removeAllFiles(true); 
					});
				    
				    this.on("error", function(file, message){
						if (file.size > 0.3*1024*1024) { 
							$("#dialog-fotos").dialog("close");
							$("#notificacionError").html("<h1>Tamaño superior a 300KB.</h1>");
							$("#notificacionError").jqxNotification("open");
							this.removeFile(file);
						}
					});				    

					this.on("success", function(file, response){						
						$("#notificacionGuardado").jqxNotification("open"); 
						$("#dialog-fotos").dialog("close");
						location.reload(); // Recargar la página
						// this.removeAllFiles(true); // Resetea el div de dropzone
					});				
				}, // Fin del init
							  
		 }; // Fin del Dropzone Options
		 
		 // ***********************************************************************************
		 
		 
		
	 }); // fin del document ready
	 
	 // ******************************************************
	 // Funciones en la página *******************************
	 // ******************************************************	 

	 //************************** 
	 // F1) Borrar Fotografia
	 function borrarFotografia(idAlumno) {
		     console.log("****************** Grabar Opinión *******************");
		     console.log("alumno: "+idAlumno);
		     // alert(idAlumno);
			 return $.ajax({
			      type: 'POST',
			      dataType: 'text',
			      url: "./tutorias/scripts/borrarFotografia.php", 
			      data: { // Parece que las llamadas con ajax van mejor que con POST...
				  lee: idAlumno,
				  },					 		 
		          success: function(data, textStatus, jqXHR){
					  // alert(data);
				      return data;
			      },
			      error: function (jqXHR , textStatus, errorThrown) {
					  return "";
				  }
			  }); 
			  
	  } // Fin de la función borrar fotografía
	 
	 
 <!-- * =======================================================================================================   * --> 	

  </script>
  
<!-- **************************************************************************************** -->
</body></html>
