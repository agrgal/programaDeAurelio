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
  <title>Obtengo los datos generales de mi tutor�a</title>
  <link rel="icon" 
        type="image/png" 
        href="./imagenes/logoA.png">
  <meta content="Aurelio Gallardo Rodr�guez" name="author">  
  <meta content="Obtengo los datos generales de tutor�a" name="description">

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
	<?php include_once("./htmlsuelto/barrasuperior.php"); // y dentro de �l se incluye el men� correspondiente ?> 
	<div id="menu" style="text-align: justify; padding-right: 1em;">
		<?php include_once("./htmlsuelto/menuIZQUIERDO.php"); ?> 
 	</div>     
    
    <!-- ******************************************* -->
    <!-- ******** ZONA DE C�LCULOS PREVIOS ********* -->
    <!-- ******************************************* -->
    <?php // Zona en la que se extraen variables de las distintas clases
       // $fechadehoy="2015-12-12";
       $fechadehoy=date("Y-m-d"); // Es la fecha del d�a de hoy. 
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
				<div id="MostrarDatos">	<!-- Aqu� se inserta el HTML que muestra los datos -->			
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
		
		<div id="notificacionFuera">
			<div><h1>La fecha actual est� fuera de los per�odos de evaluaci�n establecidos<h1></div>
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
		 var textoFecha = "cualquier Fecha";
		 var cadenaSQL = "SELECT * FROM `tb_opiniones` ";
		 var conNombreAsignacion = true; // Quitar o no el nombre de la asignaci�n en los resultados.
		 var conNombreAlumno = true; // Quitar o no el nombre del alumno en los resultados.
		 var evalPulsada = $('#idevaluacion').val(); // define la evaluaci�n por defecto. 
		 
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
			},
			activate: function(event, ui){ //detecta la pesta�a pulsada...
				if(ui.newTab.index()=='0') { // Si se pulsa la pesta�a 0... Filtro de Datos
					$("#pesta�as").tabs("disable", 1); // desactiva la pesta�a 1
				}
			},
		}); 
		
		$("#go").button({
			width: 'auto',
		}); // para que lo reconozca como del tema sunny	
		
		// ========================================================================================
		// Defino di�logos y/o notificaciones
		// ========================================================================================	
		
		 $("#notificacionObtenido, #notificacionFuera").jqxNotification({
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

		 
	   	// ******************************************************  
	   	
		 // ========================================================================================	 
		 // Evaluaci�n pulsada por defecto al INICIAR LA APLICACI�N 
		 // ========================================================================================
		 // Depende del valor INICIAL de evalPulsada
			 if (evalPulsada>0) {	 // Si existe la evaluaci�n pulsada y es mayor que cero 	 
				 $('.divNombreEval2').each(function(e) {
					$(this).removeClass("divNombreEvalSeleccionado2"); // Las quita por defecto.
					// Comprueba cual es y ya lo selecciona...	
					if ($(this).attr("id")==evalPulsada) {
						$(this).addClass("divNombreEvalSeleccionado2");
						// obop(); // llama a esa funci�n
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
				$(this).removeClass("divNombreEvalSeleccionado2"); // se la quita a las dem�s
			 });
			 $(this).addClass("divNombreEvalSeleccionado2"); // se la a�ade a la que se ha hecho click.
			 evalPulsada = $(this).attr("id"); // seleccionada la evaluaci�n dada...
			 // recupera datos si los tuviese
			 // obop(); // llama a esa funci�n
		 }); 
		
		// ************************************
		// Pulso el bot�n de obtenci�n de datos
		// ************************************
		$("#go").click(function(event,ui){
			$.when(obtenerDatos(conNombreAlumno,conNombreAsignacion)).done(function(datos){
				try { // se reciben en formato de div
				   // alert(datos);
				   /*
				   $("#pesta�as").tabs("enable", 1); // activa la pesta�a 1
				   $("#MostrarDatos").html('<h1>'+$("#condiciones").html()+'</h1>'+datos); // coloca los datos...
				   var sCabecera =  $("#condiciones").html();
				   // sCabecera = sCabecera.replace(/&/g, "&amp;").replace(/>/g, "&gt;").replace(/</g, "&lt;").replace(/"/g, "&quot;");
				   $("#sendCabecera").val(sCabecera);
				   var sContenido = datos;
				   // sContenido = sContenido.replace(/&/g, "&amp;").replace(/>/g, "&gt;").replace(/</g, "&lt;").replace(/"/g, "&quot;");
				   $("#sendContenido").val(sContenido);
				   $('#pesta�as a[href="#Datos"]').trigger('click'); // simula el click en la pesta�a 1	
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
			ordenado = ordenado.replace("fecha ASC","fecha m�s antiguas primero");
			ordenado = ordenado.replace("fecha DESC","fecha m�s actuales primero");
			ordenado = ordenado.replace("alumno ASC","alumno");
			ordenado = ordenado.replace("asignacion ASC","asignaci�n"); // modifica la cadena ORDER BY para hacerla le�ble.
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
			// $("#SQL").html(cadenaSQL); // Hasta aqu� la cla�sula WHERE
			$("#SQL").html(cadenaSQL+" "+$("#orden").html());
		};		
		
		// 2) Obtener cadena ordenada
		function cadenaORDER() {
			// alert("Cambio");
			var cadena = "";
			$("#ordenable li").each(function(i, elemento){
				cadena = cadena + $(elemento).attr("dt")+", ";					
			});
			cadena = cadena.slice(0,-2); // Quitar el �ltimo elemento
			$("#orden").html("ORDER BY " + cadena);
		}
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
