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
					echo '<option unidad="'.$unidad.'" value="'.$valor.'">'.$alumno->esteAlumno["nombre2"].' ('.$unidad.')</option>';
				}
				echo '</select>';
				echo '</div>';				
				?>
				<div id="muestraAlumno">	
					<input id="idalumno" type="text" value="" style="display: none;">
					<input id="cursoantiguo" type="text" value="" style="display: none;">
					<p id="muestraAlumnoActual">No hay alumno/a seleccionado/a</p>				
				</div>
			</div>

			<!-- ********************************************************** -->
			<!-- Elige un nuevo curso -->
			<!-- ********************************************************** --> 	
			<div id="EligeCurso">
				<h2 id="cursoAntiguoh2">De este curso a... </h2>
				<div id="cursos" title="Elige un curso de destino">
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
			</div>
			
			<!-- ********************************************************** -->
			<!-- Correspondencia entre opiniones-->
			<!-- ********************************************************** --> 	
			<div id="Correspondencia">

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
		
		 $("#notificacionObtenido").jqxNotification({
                width: 500, position: "top-right", opacity: 0.9,
                autoOpen: false, animationOpenDelay: 300, autoClose: true, autoCloseDelay: 2000, template: "info"
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
			change: function(event,ui) {
				var escogerCurso = $("#EscogerCurso option:selected").text();	
				var escogerCursoCorto = $("#EscogerCurso option:selected").attr("corto");
				alert(escogerCurso+" - "+escogerCursoCorto);
				$("#pesta�as").tabs("enable", 2); // activo la pesta�a 1, la segunda 
			},
			focus: function(event,ui) {
				$("#pesta�as").tabs("disable", 2); // desactivo la pesta�a 1, la segunda
			},
		}); 	
		
				
		// ************************************
		// Pulso el bot�n de obtenci�n de datos
		// ************************************
		$("#go").click(function(event,ui){
			$.when(obtenerDatos(conNombreAlumno,conNombreAsignacion)).done(function(datos){
				try { // se reciben en formato de div
				   	   
				} catch(err) {
				   console.log(err.message);
				}	
			});
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
	
			
	 }); // fin del document ready
	 
	 // ******************************************************
	 // Funciones en la p�gina *******************************
	 // ******************************************************	 

	 //************************************
	 // F1) Obtener datos del filtro
	 function obtenerDatos(conNombreAlumno,conNombreAsignacion) {
		 if ($("#fotoYN").jqxSwitchButton('checked')) { var fotoSN = 1; } else { var fotoSN=0; }
		 // alert(fotoSN);
		 // alert(conNombreAlumno);
		 // alert(conNombreAsignacion);
		 console.log("****************** Obtiene SQL *******************");
		 console.log("SQL: "+$("#SQL").text());
		 console.log("Fotos: ");
		 return $.ajax({
			  type: 'POST',
			  dataType: 'text',	
		      url: "./tutorias/scripts/obtenerDatos.php", // En el script se construye la tabla...
		      data: { 
			  SQL: $("#SQL").text(), // La variable de sesi�n de la asignaci�n se consigue en el script.	
			  foto: fotoSN,		
			  conNombreAlumno: conNombreAlumno,
			  conNombreAsignacion: conNombreAsignacion,  
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