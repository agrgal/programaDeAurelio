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
  <title>Cambiar a un alumno de clase</title>
  <link rel="icon" 
        type="image/png" 
        href="./imagenes/logoA.png">
  <meta content="Aurelio Gallardo Rodr�guez" name="author">  
  <meta content="Cambiar a un alumno de clase" name="description">

  <!-- ***************************** -->
  <!-- Linkar a hojas de estilo CSS -->
  <!-- ***************************** -->
  <?php include_once("./css/cargarestiloscss.php"); ?>
  <link rel="stylesheet" href="./css/estiloCambiarClase.css"> <!-- Efectos aplicados a esta hoja -->
  
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
		<input id="idalumno" type="text" value="" style="display: none;">
		<input id="cursoantiguo" type="text" value="" style="display: none;">
		<input id="cursonuevo" type="text" value="" style="display: none;">
		<input id="parejas" type="text" value="" style="display: none;">
	    <p id="testear">
	    </p>
    </div>	<!-- TESTER -->
    
    <!-- ********************************************************** -->
    <!-- Contenido Principal -->
    <!-- ********************************************************** --> 
    
    <div id="contents"> <!-- &&&& -->   
		
		<div id="pesta�as">
            <ul>
				<li><a href="#EligeAlumno">1�) Elige Alumno</a></li>
				<li><a href="#EligeCurso">2�) Elige nuevo curso</a></li>
				<li><a href="#Correspondencia">3�) Corresponde asignaciones</a></li>
				<li><a href="#Instrucciones">Instrucciones</a></li>				
			</ul> 
			<!-- ********************************************************** -->
			<!-- Haz la elecci�n de un alumno -->
			<!-- ********************************************************** --> 			
			<div id="EligeAlumno">
				<?php // Select para seleccionar alumnos
				$alumnos = $asignacion->devuelveListadoAlumnosdeEstaAsignacion($_SESSION["idasignacion"],$_SESSION["profesor"]);
				$alumnosArray = explode("#",$alumnos);
				echo '<div id="alumnos" title="Escoge un alumno de tu tutor�a">';
				echo '<h2>Elige alumno/a a quien cambiar de clase</h2>';
				echo '<select name="EscogerAlumno" id="EscogerAlumno" class="seleccionaidalumno">';
				foreach($alumnosArray as $clave => $valor) {
					$alumno->devuelveAlumno($valor);
					$unidad = $alumno->devuelveUnidadDeUnAlumno($valor);
					// $unidad = $curso->devuelveCursoCortoPorUnidad($unidad);
					echo '<option unidad="'.$unidad.'" value="'.$valor.'">'.$alumno->esteAlumno["nombre2"].' ('.$unidad.')</option>';
				}
				echo '</select>';
				echo '</div>';				
				?>
				<div id="muestraAlumno">	

					<p id="muestraAlumnoActual">No hay alumno/a seleccionado/a</p>				
				</div>
			</div>

			<!-- ********************************************************** -->
			<!-- Elige un nuevo curso -->
			<!-- ********************************************************** --> 	
			<div id="EligeCurso">
				
				<div id="cursos" title="Elige un curso de destino">
					<h2 id="cursoAntiguoh2">De este curso a... </h2>
					<select name="EscogerCurso" id="EscogerCurso">
						<?php 
						$curso->listarCursos();
						$listadoDeCursos = $curso->listaDeCursos;
						foreach($listadoDeCursos["largo"] as $clave => $valor) {
							echo '<option corto="'.$listadoDeCursos["corto"][$clave].'" value="'.$valor.'">'.$valor.'</option>';
						}
						?>
					</select>
				</div>
				<div id="muestraCurso">
					
					<p id="muestraCursoActual">No hay curso seleccionado</p>
				</div>	
			</div>
			
			<!-- ********************************************************** -->
			<!-- Correspondencia entre opiniones-->
			<!-- ********************************************************** --> 	
			<div id="Correspondencia">			
				<div id="asignacionesAntiguas" class="effect7">
					<h1>Asignaciones que tiene</h1>
				</div>
				<div id="asignacionesNuevas" class="effect7">
					<h1>Asignaciones que podr�a tener</h1>
				</div>
				<div id="paraGo">
					<button id="go">Ejecutar cambio</button> 
				</div>	
			</div>
			<!-- ********************************************************** -->
			<!-- Insertar instrucciones -->
			<!-- ********************************************************** --> 
			<div id="Instrucciones">
			<p style="text-align: center; margin: 40px;">
					<iframe width="800" height="600" src="https://www.youtube.com/embed/FUoUwd_mgq4" frameborder="0" allowfullscreen></iframe>
				</p>
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
		
		<div id="notificacionGuardado">
			<div><h1>Se han modificado los datos</h1></div>
		</div>
		
	<!-- ********************************************************** -->
	<!-- Di�logos -->
	<!-- ********************************************************** -->    	
		
		<!-- Dialogo de Confirmaci�n de grabar los datos -->	
		<div id="dialog-confirm" title="Cambiar de clase">
		   <p><span class="fa fa-spinner fa-pulse fa-2x" style="float:left; margin:0 7px 20px 0;">
		   </span>
		   �De verdad quieres cambiar a este alumno de Clase?</p>
		</div>
    
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
		
		
		 // ===========================================================================================
		 // DEFINO tabs. LLAMO Pesta�as. y tambi�n el acordeon, y los botones de fecha. Lista ordenable
		 // ===========================================================================================
		
		$('#pesta�as').tabs({
			active: 0,
			create: function(event,ui) {
				$("#pesta�as").tabs("disable", 1); // desactiva la pesta�a 1
				$("#pesta�as").tabs("disable", 2); // desactiva la pesta�a 2
			},
			activate: function(event, ui){ //detecta la pesta�a pulsada...
				if(ui.newTab.index()=='0') { // Si se pulsa la pesta�a 0... Escoger Alumnos
					// $("#pesta�as").tabs("disable", 1); // desactiva la pesta�a 1
					// $("#pesta�as").tabs("disable", 2); // desactiva la pesta�a 2
					location.reload(); // mejor recargar la p�gina...
				};
				if(ui.newTab.index()=='1') { // Si se pulsa la pesta�a 1... Escoger Cursos
					seleccionDeCurso();
					$("#pesta�as").tabs("disable", 2); // desactiva la pesta�a 2
				};
				
			},
		}); 
	    	 
		// ========================================================================================
		// Defino di�logos y/o notificaciones
		// ========================================================================================	
		
		 $("#notificacionGuardado").jqxNotification({
                width: 700, position: "top-right", opacity: 0.9,
                autoOpen: false, animationOpenDelay: 300, autoClose: true, autoCloseDelay: 5000, template: "info"
         });
		 
		 // 1d) Definici�n del di�logo de confirmaci�n de grabar datos, borrar y modificar asignacion y confirmar que no hay datos
		  $("#dialog-confirm").dialog({
			autoOpen: false,
			modal: true,
			maxWidth:1500,
            maxHeight: 650,
            width: 1500,
            height: 650,
			position: { my: "center center-250", at: "center center", of: "#container" }
			// el "centro arriba" de mi cuadro de di�logo (my) , en el centro arriba (at) del contenedor (of)
		 });

		// ========================================================================================
		// Defino 
		// ========================================================================================	
		
		// 1) Definici�n del SELECT de alumnos
    	$( "#EscogerAlumno" )
			.selectmenu({
				width:700, 
				style: 'popup',
			})			
			.selectmenu("menuWidget")
			   .addClass("overflow5"); // carga un estilo que est� en /css/estiloSelectMenuOverflow.css				   
   	
	   	$( "#EscogerAlumno" ).selectmenu({
			open: function(event,ui) {
				// $('#EligeAlumno').val($('#EligeAlumno option:first').val());
				// alert($("#EligeAlumno option:selected").text());
				$("#idalumno").val($("#EligeAlumno option:selected").val()); // pone el id en el campo de texto
				$("#cursoantiguo").val($("#EligeAlumno option:selected").attr("unidad")); // pone el id en el campo de texto
				$("#muestraAlumnoActual").html("Alumno/a actual: "+$("#EligeAlumno option:selected").text());
				$("#cursoAntiguoh2").html("De la clase "+$("#cursoantiguo").val()+" a ...");	
				$("#pesta�as").tabs("enable", 1); // activo la pesta�a 1, la segunda			
			},			
			change: function(event,ui) {
				var escogerAlumno = $("#EligeAlumno option:selected").text();	
				var escogerAlumnoID = $("#EligeAlumno option:selected").val();
				var cursoAntiguo = $("#EligeAlumno option:selected").attr("unidad");
				$("#idalumno").val(escogerAlumnoID); // pone el id en el campo de texto
				$("#cursoantiguo").val(cursoAntiguo); // pone el id en el campo de texto
				$("#muestraAlumnoActual").html("Alumno/a actual: "+escogerAlumno);
				$("#cursoAntiguoh2").html("De la clase "+cursoAntiguo+" a ...");
				$("#pesta�as").tabs("enable", 1); // activo la pesta�a 1, la segunda 
			},
			focus: function(event,ui) {
				$("#pesta�as").tabs("disable", 1); // activo la pesta�a 1, la segunda
			},
		}); 
		
		// Definicion del select de cursos EscogerCurso
	    $( "#EscogerCurso" )
			.selectmenu({
				width:500, 
				style: 'popup',
			})			
			.selectmenu("menuWidget")
			   .addClass("overflow5"); // carga un estilo que est� en /css/estiloSelectMenuOverflow.css	
			   
		$( "#EscogerCurso" ).selectmenu({
			open: function(event,ui) {
				var escogerCurso = $("#EscogerCurso option:selected").text();	
				var escogerCursoCorto = $("#EscogerCurso option:selected").attr("corto");
				$("#cursonuevo").val(escogerCursoCorto);
				$("#muestraCursoActual").html("A la nueva clase: "+escogerCurso);	
				$("#pesta�as").tabs("enable", 2); // activo la pesta�a 1, la segunda
			},		
			change: function(event,ui) {
				var escogerCurso = $("#EscogerCurso option:selected").text();	
				var escogerCursoCorto = $("#EscogerCurso option:selected").attr("corto");
				$("#cursonuevo").val(escogerCursoCorto);
				$("#muestraCursoActual").html("A la nueva clase: "+escogerCurso);
				// alert(escogerCurso+" - "+escogerCursoCorto);
				$("#pesta�as").tabs("enable", 2); // activo la pesta�a 1, la segunda
				// PRIMERA LLAMADA AJAX
				 // llama a la funci�n que obtiene las asignaciones que tiene ese alumno/A
				$.when(obtenerAntiguos($("#idalumno").val()),obtenerNuevos($("#cursonuevo").val())).done(function(data1,data2){
					// primer conjunto de datos
					try { // se reciben en formato de div
						// Asignaciones antiguas del alumno/a
						var datos1 = jQuery.parseJSON(data1[0]);
						// alert(datos1.asignacionAlumno);
					    if (datos1.valido==1) {
							// Borrar si hay divs anteriores
							$("#asignacionesAntiguas").children().each(function(e){
										$(this).remove(); 
							});
							// Recupera el t�tulo
							var titulo = '<h1>Asignaciones donde estaba</h1>'							;
							$("#asignacionesAntiguas").html(titulo + datos1.divs);
							$("#asignacionesAntiguas .bloque").children().each(function(e){
								if ($(this).hasClass("destino")) {
										$(this).droppable({  
											// var factor = 0,8;
											// ************************************************
											// A) Introduzco elementos en la zona seleccionable. 
											// ************************************************     
											drop: function (event, ui) {
												// alert("Lo que dejado caer aqu� "+$(this).attr("id"));
												// alert(ui.draggable.attr("id")); // obtengo la clase, para distinguirlo);
												if (!($(this).children().hasClass("divasignacionCambio2"))) {
													// Importante, voy construyendo 
												    $(this).append(ui.draggable); // este incorpora el objeto al bloque
												    var estilo = ui.draggable.attr("class");	
													if (estilo.indexOf("divasignacionCambio2")>=0) { // S�lo afecta a los que tienen esa clase
													// alert(parseInt($(this).css("max-width")));
													ui.draggable.css("max-width",(parseInt($(this).css("max-width"))-40)); // tama�o letra
													// alert(ui.draggable.css("max-width"));
													ui.draggable.css("width",(parseInt($(this).css("width"))-30)); // tama�o letra
													ui.draggable.css("position","relative"); // en relaci�n con bloque
													ui.draggable.css("top","10px"); // posici�n esquina superior
													ui.draggable.css("left","10px"); // posici�n a la izquierda
													ui.draggable.css("margin","0px"); // margin
													ui.draggable.css("font-size","0.6em"); // tama�o letra
													ui.draggable.css("height",parseInt($(this).css("height"))-30); // tama�o letra	
													}	
												    montarParejas(); // reconoce los ID y los deja en el input...
												}
											},
											out: function (event, ui) {	
												var estilo = ui.draggable.attr("class"); // obtengo la clase, para distinguirlo
												var objeto = $(ui.draggable);
												objeto.draggable( "option", "revert", false );
												// ================================================
												// Si el objeto es de la clase divasignacionCambio2
												// ================================================
												if (estilo.indexOf("divasignacionCambio2")>=0) {
													objeto.css("max-width",""); // Quita la propiedad y se asigna la de la clase divasignacionCambio2
													objeto.css("width",""); objeto.css("margin",""); objeto.css("height",""); objeto.css("font-size","");
												objeto.appendTo("#asignacionesNuevas"); // lo asimilo otra vez a la zona de Asignaciones Nuevas.				   
												$("#asignacionesNuevas").find(".divasignacionCambio2").sort(function(a,b){
												   return $(a).attr("id")>$(b).attr("id"); // esto ordena por orden en id
												}).appendTo("#asignacionesNuevas");
												}
												montarParejas(); // reconoce los ID y los deja en el input...
											},
										});
								} // Fin si es de la clase bloque								
							});
							
						} else {
							$("#asignacionesAntiguas").html("<h1>Este alumno/a no tiene asignaciones antiguas asignadas</h1>");
						} 
					} catch(err) {
					   console.log(err.message);
					}	
					// Segundo conjunto de datos
					try { // se reciben en formato de div
						// Asignaciones antiguas del alumno/a
						var datos2 = jQuery.parseJSON(data2[0]);
					    if (datos2.valido==1 && datos2.divs.length>0) {
							$("#divasignacionCambio2").children().each(function(e){
								$(this).remove();
							});
							// alert(datos2.divs); // alert(datos2.numeros);
							var titulo2 = "<h1>Agregar asignaciones nuevas</h1>";
							$("#asignacionesNuevas").html(titulo2 + datos2.divs);
							$("#asignacionesNuevas").children().each(function(e){
								if ($(this).hasClass("divasignacionCambio")) { // Cambia los divs que ten�an divasignacionCambio por divasignacionCambio2
									$(this).removeClass("divasignacionCambio");
									$(this).addClass("divasignacionCambio2");
									// Convertirlo en DRAGGABLE...
									$(this).draggable({
									   containment: "body", // contenedor donde puede moverse
									   stack: "body", // pone su z-index por encima de todos los de esa zona
									   helper: 'clone', // mueve una copia del elemento
									   revert: 'invalid', // vuelve a su lugar original si no puedo ponerlo sobre la zona objetivo
									   appendTo: 'body', // lo a�ade al body 
									   cursor: "move",		   
									}); // los hago cuando se llaman...
								} 
							});
						} else {
							$("#asignacionesNuevas").html("<h1>Esta clase no tiene asignaciones nuevas asignadas</h1>");
						} 
					} catch(err) {
					   console.log(err.message);
					}		

				});
			},
			focus: function(event,ui) {
				$("#pesta�as").tabs("disable", 2); // desactivo la pesta�a 1, la segunda
			},
		}); 	
		
		
		// ************************************
		// Boton de ejecuci�n
		// ************************************
		$("#go").button();		

				
		// ************************************
		// Pulso el bot�n de obtenci�n de datos
		// ************************************
		$("#go").click(function(event,ui){
			   event.preventDefault();
			   // incluye informaci�n...	
			   var notificar = "El alumno/a "+$("#muestraAlumnoActual").text()+" "+$("#cursoAntiguoh2").text()+" "+$("#muestraCursoActual").text();	 
			   $("#dialog-confirm").html($("#dialog-confirm").html()+'<h1>'+notificar+'</h1><h1>Recuerda: las asignaciones NO EMPAREJADAS deben hacer un cambio manual. Avisa a los profesores/as afectados para que hagan el cambio. La p�gina se reiniciar� tras el proceso.</h1>');				 
			   // 		   
			   $("#dialog-confirm").dialog({
			    buttons : {
				"S�, Procede" : function() {
				  // alert("dato grabado...");
				  $(this).dialog("close");
					$.when(realizarCambio($("#idalumno").val(),$("#cursoantiguo").val(),$("#cursonuevo").val(),$("#parejas").val())).done(function(data){
						try { // se reciben en formato de div
							  // alert(data); 
							  var datos = jQuery.parseJSON(data);
							   if (datos.borrada=="NO") {
								   $("#notificacionGuardado").html('<h1 style="font-size: 3em; font-weight: bold;">'+notificar+'</h1>');
								   $("#notificacionGuardado").jqxNotification("open");
							  } else {
								  $("#notificacionGuardado").html('<h1 style="font-size: 3em; font-weight: bold;">'+notificar+'. Algunas asignaciones antiguas han sido borrada por quedarse sin datos.</h1>');
								  $("#notificacionGuardado").jqxNotification("open");
							  }
							  setInterval(function(){ location.reload(); },7000); // A los 7 segundos recarga
						} catch(err) {
						   console.log(err.message);
						}	
					});	      
			    },
				"No,no... Cancela" : function() {
				  $(this).dialog("close"); // no recarga la p�gina. Simplemente anula la operaci�n y sigue				 
			    },
				} //Fin de Buttons
			}); // Fin de dialog-confirm
			
			$("#dialog-confirm").dialog("open"); // si no, no lo abre
			
		});  		
	
		
		// *******************************************
		// Funciones dentro del document ready
		// *******************************************
		
		function seleccionDeCurso() {
			var curAnt = $("#cursoantiguo").val(); // Curso antiguo a Cambiar
			// recorre los options 
			$("#EscogerCurso option").each(function(){
				if ($(this).val().slice(0,-1)!=curAnt.slice(0,-1)) {$(this).remove();} // Quita los que no son de su nivel
				if ($(this).val()==curAnt) {$(this).remove();} // Quita el mismo curso, obviamente
			});
			$( "#EscogerCurso" ).selectmenu({}).selectmenu("menuWidget").addClass("overflow6"); // altura auto �? Parece que funciona
			$( "#EscogerCurso" ).selectmenu( "refresh" );
		}
		
		// ******************************************
		// reescribe el INPUT de las  parejas
		// Al hacer un DRAG and DROP
		// ******************************************
		function montarParejas() {
			$("#parejas").val(""); // Reinicio el valor de parejas 
			$("#asignacionesAntiguas .bloque").children().each(function(e){					
				if ($(this).hasClass("destino")) {
					// Si existe un bloque
					// alert("Reconozco que hay un bloque");
					if ($(this).children().hasClass("divasignacionCambio2")) {
						// Y en su interior tengo dejado un bloque...
						// alert($(this).attr("id"));
						// alert($(this).children().attr("id"));
						var parejas = $("#parejas").val()+$(this).attr("id")+"-"+$(this).children().attr("id")+"#";
						$("#parejas").val(parejas);
					}
				}
			});
		} // Fin de montar Parejas
	
			
	 }); // fin del document ready
	 
	 // ******************************************************
	 // Funciones en la p�gina *******************************
	 // ******************************************************	 

	 //************************************
	 // F1) Obtener asignaciones del alumno, curso antiguo
	 function obtenerAntiguos(alumno) {
		 console.log("****************** Obtiene SQL *******************");
		 console.log("alumnos: "+alumno);
		 return $.ajax({
			  type: 'POST',
			  dataType: 'text',	
		      url: "./tutorias/scripts/obtenerAntiguos.php", // En el script se construye la tabla...
		      data: { 
			  alumno: alumno, // La variable de sesi�n de la asignaci�n se consigue en el script.	 
		      },
		      success: function(data, textStatus, jqXHR){ 			  
				// alert(data);
				return data;
		      },
		  });
	  } // Fin de la funci�n Obtener datos del filtro  
	  
	  //************************************
	 // F2) Obtener asignaciones de la nueva clase
	 function obtenerNuevos(clase) {
		 console.log("****************** Obtiene SQL *******************");
		 console.log("clase: "+clase);
		 return $.ajax({
			  type: 'POST',
			  dataType: 'text',	
		      url: "./tutorias/scripts/obtenerNuevos.php", // En el script se construye la tabla...
		      data: { 
			  clase: clase, // La variable de sesi�n de la asignaci�n se consigue en el script.	 
		      },
		      success: function(data, textStatus, jqXHR){ 			  
				// alert(data);
				return data;
		      },
		  });
	  } // Fin de la funci�n Obtener datos del filtro  
	  
	 //************************************
	 // F3) realizarCambio
	 function realizarCambio(alumno,claseantigua,clasenueva,parejas) {
		 console.log("****************** Obtiene SQL *******************");
		 console.log("alumno: "+alumno);
		 console.log("Clase antigua: "+claseantigua);
		 console.log("Clase nueva: "+clasenueva);
		 console.log("parejas: "+parejas);
		 return $.ajax({
			  type: 'POST',
			  dataType: 'text',	
		      url: "./tutorias/scripts/realizaCambioClase.php", // En el script se construye la tabla...
		      data: { 
			  alumno: alumno, // La variable de sesi�n de la asignaci�n se consigue en el script.
			  claseantigua: claseantigua,
			  clasenueva: clasenueva,
			  parejas: parejas,	 
		      },
		      success: function(data, textStatus, jqXHR){ 			  
				// alert(data);
				return data;
		      },
		  });
	  } // Fin de la funci�n que realiza el cambio de clase.

	  
 <!-- * =======================================================================================================   * --> 	

  </script>
  
<!-- **************************************************************************************** -->
</body></html>
