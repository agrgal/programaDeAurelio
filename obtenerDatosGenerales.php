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
  <title>Obtengo los datos generales de mi tutoría</title>
  <link rel="icon" 
        type="image/png" 
        href="./imagenes/logoA.png">
  <meta content="Aurelio Gallardo Rodríguez" name="author">  
  <meta content="Obtengo los datos generales de tutoría" name="description">

  <!-- ***************************** -->
  <!-- Linkar a hojas de estilo CSS -->
  <!-- ***************************** -->
  <?php include_once("./css/cargarestiloscss.php"); ?>
  <link rel="stylesheet" href="./css/estiloobtenerDatosGenerales.css"><!-- Efectos aplicados a esta hoja -->
  
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
       // $fechadehoy="2015-12-12";
       $fechadehoy=date("Y-m-d"); // Es la fecha del día de hoy. 
    ?>
    <!-- *********************************************************** -->
     
	<div id="test"> <!-- TESTER -->
	    <p id="testear">
	    </p>
	    <input id="idevaluacion" value="<?php echo $evaluacion->calculaFecha($fechadehoy,true); ?>" style="display: text;"> 
	    <input id="fechaevaluacion" value="<?php echo $evaluacion->calculaFecha($fechadehoy,false); ?>" style="display: text;"> 
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
				<div id="contieneEvaluaciones"> <!-- Incluye las evaluaciones -->
						<?php 
							// $evaluacion->listaEvaluaciones(); No hace falta porque lo meto en el constructor
							foreach ($evaluacion->listadoDeEvaluaciones["div"] as $clave => $valor) {
								$valor = str_replace("divNombreEval","divNombreEval2",$valor); //cambio el nombre de la clase
								echo $valor;
							}				   
						?>
				</div>
				<!-- Escritura de cadena SQL -->
				<div id="CadenaSQL">
					<h1>Condiciones</h1>
					<p id="condiciones"></p>
					<h1 style="display: <?php echo $mostrar; ?> ;">Cadena SQL</h1>
					<p style="display: <?php echo $mostrar; ?> ;" id="SQL"></p> <!-- poner 'none' para ocultarlo -->
					<button id="go">Mostrar Datos</button>
				</div>
			</div>

			<!-- ********************************************************** -->
			<!-- Mostrar los datos que se han filtrado -->
			<!-- ********************************************************** --> 	
			<div id="Datos">
				<!-- Dibujo de la impresora -->
				<div id="printer" title="Imprime resultados" title="Imprime resultados" >
					<!-- <a id="imprimir" href="./pdf/scripts/listadoResultadosPDF.php" ><img src="./imagenes/iconos/printer_pdf.png"></a> -->
					<form id="formularioImprimir" action="./pdf/scripts/listadoResultadosPDF.php" method="POST">
						<input id="sendCabecera" name="sendCabecera" type="text" style="display: none;" value="">
						<input id="sendContenido" name="sendContenido" type="text" style="display: none;" value="">
						<!-- <button type="submit"><img src="./imagenes/iconos/printer_pdf.png"></button> -->
						<a id="imprimir" ><img src="./imagenes/iconos/printer_pdf.png"></a>
					</form>
				</div>
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
		
		<div id="notificacionObtenido">
			<div><h1>Se han obtenido los datos requeridos</h1></div>
		</div>
		
		<div id="notificacionFuera">
			<div><h1>La fecha actual está fuera de los períodos de evaluación establecidos<h1></div>
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
		 var cadenaSQL = "SELECT * FROM `tb_opiniones` ";
		 var conNombreAsignacion = true; // Quitar o no el nombre de la asignación en los resultados.
		 var conNombreAlumno = true; // Quitar o no el nombre del alumno en los resultados.
		 var evalPulsada = $('#idevaluacion').val(); // define la evaluación por defecto. 
		 
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
		
		$("#go").button({
			width: 'auto',
		}); // para que lo reconozca como del tema sunny	
		
		// ========================================================================================
		// Defino diálogos y/o notificaciones
		// ========================================================================================	
		
		 $("#notificacionObtenido, #notificacionFuera").jqxNotification({
                width: 500, position: "top-right", opacity: 0.9,
                autoOpen: false, animationOpenDelay: 300, autoClose: true, autoCloseDelay: 2000, template: "info"
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

		// ========================================================================================
		// Defino 
		// ========================================================================================	

		 
	   	// ******************************************************  
	   	
		 // ========================================================================================	 
		 // Evaluación pulsada por defecto al INICIAR LA APLICACIÓN 
		 // ========================================================================================
		 // Depende del valor INICIAL de evalPulsada
			 if (evalPulsada>0) {	 // Si existe la evaluación pulsada y es mayor que cero 	 
				 $('.divNombreEval2').each(function(e) {
					$(this).removeClass("divNombreEvalSeleccionado2"); // Las quita por defecto.
					// Comprueba cual es y ya lo selecciona...	
					if ($(this).attr("id")==evalPulsada) {
						$(this).addClass("divNombreEvalSeleccionado2");
						// obop(); // llama a esa función
					}
				 });		 
			 } else { // caso que sea cero o negativo
				 $("#notificacionFuera").jqxNotification("open"); 
			 }
		
		 // ========================================================================================
		 // Al pulsar sobre una de las evaluaciones
		 // ========================================================================================
		 $('.divNombreEval2').click(function(e) { 
			 // alert($(this).attr("name"));
			 $('.divNombreEval2').each(function(e) {
				$(this).removeClass("divNombreEvalSeleccionado2"); // se la quita a las demás
			 });
			 $(this).addClass("divNombreEvalSeleccionado2"); // se la añade a la que se ha hecho click.
			 evalPulsada = $(this).attr("id"); // seleccionada la evaluación dada...
			 // recupera datos si los tuviese
			 // obop(); // llama a esa función
		 }); 
		
		// ************************************
		// Pulso el botón de obtención de datos
		// ************************************
		$("#go").click(function(event,ui){
			$.when(obtenerDatos(conNombreAlumno,conNombreAsignacion)).done(function(datos){
				try { // se reciben en formato de div
				   // alert(datos);
				   /*
				   $("#pestañas").tabs("enable", 1); // activa la pestaña 1
				   $("#MostrarDatos").html('<h1>'+$("#condiciones").html()+'</h1>'+datos); // coloca los datos...
				   var sCabecera =  $("#condiciones").html();
				   // sCabecera = sCabecera.replace(/&/g, "&amp;").replace(/>/g, "&gt;").replace(/</g, "&lt;").replace(/"/g, "&quot;");
				   $("#sendCabecera").val(sCabecera);
				   var sContenido = datos;
				   // sContenido = sContenido.replace(/&/g, "&amp;").replace(/>/g, "&gt;").replace(/</g, "&lt;").replace(/"/g, "&quot;");
				   $("#sendContenido").val(sContenido);
				   $('#pestañas a[href="#Datos"]').trigger('click'); // simula el click en la pestaña 1	
				   $("#notificacionObtenido").jqxNotification("open"); */			   
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
			var escogerAsignacion = $( "#EscogerAsignacion option:selected").text();
			var escogerAlumno = $("#EscogerAlumno option:selected").text();	
			if (escogerAlumno=="Todos los alumnos/as") 
			  { escogerAlumno = escogerAlumno.toLowerCase(); conNombreAlumno = true; } 
			  else { conNombreAlumno = false;}
			if (escogerAsignacion=="Todas las asignaciones") 
			  { conNombreAsignacion = true; } else { conNombreAsignacion = false; }
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
			cadenaSQL = 'SELECT * FROM tb_opiniones ';
			$("#SQL").html(fechaINI+" - "+fechaFIN);
			if (escogerAsignacion>0 || escogerAlumno>0 || fechaINI!="#" || fechaFIN!="#") {
				cadenaSQL = cadenaSQL + "WHERE ";
			} 			
			if (escogerAsignacion>0) { cadenaSQL = cadenaSQL + 'asignacion = "'+escogerAsignacion+'" AND ';	}
			if (escogerAlumno>0) { cadenaSQL = cadenaSQL + 'alumno = "'+escogerAlumno+'" AND ';	}
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
			  SQL: $("#SQL").text(), // La variable de sesión de la asignación se consigue en el script.	
			  foto: fotoSN,		
			  conNombreAlumno: conNombreAlumno,
			  conNombreAsignacion: conNombreAsignacion,  
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
