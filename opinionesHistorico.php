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


// incluyo las variables de esas clases
$calendario = New micalendario(); // variable de calendario. Lo necesito para la cabecera
$profesorado = New profesores(); //variable de la clase profesores
// $formulario = New formulario(); // variable de la clase formulario
$curso = New misCursos(); // variable de la clase curso
$alumno = New misAlumnos(); // variable de la clase alumnos
$materia = New misMaterias(); // variable de la clase materia
$opiniones = New misOpiniones(); // variable de la clase opiniones
$asignacion = New misAsignaciones($curso, $profesorado, $materia, $alumno); // Uso el constructor para pasarle la clase curso, profesorado, alumno y materias a Asignaciones


// Variables de sesi�n
session_start();

if ($_SESSION['permisos']<1) { // en caso que no tenga permisos para entrar
	echo header("Location: ./index.php");
}

// Las variables de sesi�n se establecen en los scripts AJAX en 

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="es">	
<head profile="http://www.w3.org/2005/10/profile">
  <!-- *** Principio del HEAD *************************************-->	
  <meta content="text/html; charset=iso-8859-15" http-equiv="content-type">
  <title>Hist�rico de opiniones</title>
  <link rel="icon" 
        type="image/png" 
        href="./imagenes/logoA.png">
  <meta content="Aurelio Gallardo Rodr�guez" name="author">  
  <meta content="P�gina �ndice de la web de tutor�a" name="description">

  <!-- ***************************** -->
  <!-- Linkar a hojas de estilo CSS -->
  <!-- ***************************** -->
  <?php include_once("./css/cargarestiloscss.php"); ?>
 
  <!-- *** Final del HEAD, antes los ficheros de enlace a CSS ******-->
</head>

<body>
<!-- **************************************************************************-->	
<!-- *** Principio del BODY ***************************************************-->	
<!-- **************************************************************************-->	

<div id="container"> <!-- CONTENEDOR PRINCIPAL -->
	
	<!-- HTML suelto: barra superior, menu superior, menu lateral,   -->
	<?php include_once("./htmlsuelto/cabecera.php"); ?>
	<?php include_once("./htmlsuelto/barrasuperior.php"); // y dentro de �l el include al men� correspondiente ?> 
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
			<?php 
			$asignacion->retiraHuerfanos($_SESSION["idasignacion"]); // Retira opiniones hu�rfanas.
			?>
	    </p>
	    <input id="idasignacion" value="<?php echo $_SESSION["idasignacion"]; ?>" style="display: none;"> 
    </div>	<!-- TESTER -->
    
    <!-- ********************************************************** -->
    <!-- Contenido Principal -->
    <!-- ********************************************************** --> 
    
    <div id="contents"> <!-- &&&& -->   
		
		<div id="contenedorDividido" class="effect7">     <!-- Contenedor de los paneles --> 
			
    <!-- ********************************************************** --> 			
			<div id="panel1" class="splitter-panel"> <!-- Panel a la izquierda --> 
				<div id="cabecera1">	
					 <table id="menu1">
						 <tr>
							 <td><div id="todosNinguno" title="Elige seleccionar todos o ninguno"></div></td>
							 <td width="20%"><h6>Elige una fecha de la que recuperar datos</h6></td>
							 <td>				 	 
								 <div id="zonaFecha2">					    
									<select id="listaFechas">									
									</select>
									<button id="borrar" alt="Borra todos los datos de esta lista" title="Borra todos los datos de esta lista">Borrar</button>
								 </div>								 
 				             </td>
							 <td></td>
							 <td width="20%"><h6>Elige una fecha a la que copiar datos</h6></td>
							 <td>
								 <input id="fecha2" READONLY alt="Pulsa para obtener una fecha" title="Pulsa para obtener una fecha">
						         <input id="muestrafecha2" style="display:none;">
						         <button id="copiar" alt="Copia todos los datos elegidos a la nueva fecha"  title="Copia todos los datos elegidos a la nueva fecha">Copiar</button>
						     </td>					 
						 </tr>
					 </table>
				</div>
				<div id="lista1">
				</div>		
				</ul>
			</div> <!-- Fin del panel a la izquierda  --> 
	<!-- ********************************************************** --> 		
	
	<!-- ********************************************************** --> 		
			<div id="panel2" class="splitter-panel"><!-- Panel a la derecha --> 
				<div id="cabecera2"><h1>Historial</h1></div>
		        <div id="lista2">
				</div>
			</div> <!-- Fin del panel a la derecha  --> 
	<!-- ********************************************************** --> 	
		
		</div>     <!-- Fin del contenedor de los paneles --> 
    <!-- ================================================================================================== --> 
    
    <!-- * ==========================  DIALOGOS   =====================================   * --> 			
		
		<!-- Dialogo de Confirmaci�n de copiar los datos -->	
		<div id="dialog-confirm" title="Copiar datos">
		   <p><span class="fa fa-spinner fa-pulse fa-2x" style="float:left; margin:0 7px 20px 0;">
		   </span>
		   �De verdad quieres copiar estos datos a la nueva fecha?</p>
		</div>	
		
		<!-- Dialogo de Confirmaci�n de borrar los datos -->	
		<div id="dialog-confirm-borrar" title="Borrar datos">
		   <p><span class="fa fa-trash fa-2x" style="float:left; margin:0 7px 20px 0;">
		   </span>
		   �De verdad quieres borrar las opiniones seleccionadas?<span class="hoverAsignacion"></span>
		   </p>
		</div>
		
		<!-- Dialogo de Alerta que no hay datos seleccionados-->	
		<div id="dialog-confirm-nohaydatos" title="No hay datos">
		   <p><span class="fa fa-exclamation-triangle fa-2x" style="float:left; margin:0 7px 20px 0;">
		   </span>
		   No se han seleccionado datos...<span class="hoverAsignacion"></span>
		   </p>
		</div>
			
	</div> <!-- &&&& FIN DEL CONTENEDOR-->	

	<!-- ********************************************************** -->
	<!-- FIN DEL CONTENIDO PRINCIPAL -->
	<!-- ********************************************************** -->
    
    <!-- HTML suelto: pie de p�gina *******************************  -->
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
  <!-- <script src="./htmlsuelto/js_menu.js"></script>   Incorpora al script los men�s a la izquierda -->  
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
		 
        // ========================================================================================
        // Incorpora otros scripts
        // ========================================================================================
        
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
		
		// 1d) Definici�n del di�logo de confirmaci�n de grabar datos, borrar y modificar asignacion y confirmar que no hay datos
		  $("#dialog-confirm, #dialog-confirm-borrar, #dialog-confirm-nohaydatos").dialog({
			autoOpen: false,
			modal: true,
			maxWidth:600,
            maxHeight: 300,
            width: 600,
            height: 300,
			position: { my: "center center-100", at: "center center", of: "#container" }
			// el "centro arriba" de mi cuadro de di�logo (my) , en el centro arriba (at) del contenedor (of)
		 });		 
    
    // ****************************************************** 
    // <!-- * === CONTENEDOR DIVIDIDO ===   * --> 	    
    // ******************************************************
		 $('#contenedorDividido').jqxSplitter({ 
			 width: "100%", 
			 height: 620, 
			 splitBarSize: 15,
			 panels: [{ size: "80%", collapsible: true, min: 100},{ size: "20%", collapsible: true, min: 100}],
			 theme: 'ui-sunny',
		 });

     // <!-- * === FIN DEL CONTENEDOR ===   * --> 	
     // ******************************************************
     
    // ****************************************************** 
    // <!-- * === PANEL IZQUIERDO ===   * --> 	    
    // ******************************************************
    // A) Listado de fechas
     
     $("#listaFechas")
			.selectmenu({
				width:200, 
				style:'dropdown',
				select: function( event, ui ) {	
					getListaPaneles($("#listaFechas option:selected").attr("mysql"),"#lista1");
					$('#todosNinguno').jqxSwitchButton('uncheck'); // Por defecto, est�n todos sin seleccionar
			    },
			 })
			 .selectmenu("menuWidget")
			   .addClass("overflow3"); // carga un estilo que est� en /css/estiloSelectMenuOverflow.css

    // B) Listado de botones
    
    $("#borrar, #copiar").button();
    
    // **************************
    // B1) Borrar todos los datos
    // **************************
    $("#borrar").click(function(e){
		e.preventDefault();
		if (checkSeleccionados()<=0) { dialogoNohaydatos(); } else {		
		$("#dialog-confirm-borrar").dialog({
					buttons : {
						"S�, Borrar" : function() {

						var results = []; // tengo un array
						var indexAlumno = 0;
						var fechaDada = $("#listaFechas option:selected").attr("mysql");
						$("#panel1").find(".eleccion").each(function() {
						   var Si = $(this).parent().parent().parent().parent().attr("elegir");
						   var idTabla = $(this).parent().parent().parent().parent().attr("id"); // alcanzo el nivel de tabla
						   indexAlumno = $(this).parent().parent().parent().parent().attr("alumno"); // atributo con el valor del alumno
						   if (Si==1) {
								results.push(insertarOpinion(indexAlumno,fechaDada,null,null)); // inserto los resultados del ajax en el array
						   } // Fin del IF de si est� escogido		
						});	// Fin del reconocimiento de elementos escogidos o no.		

							// Ahora aplico las llamadas en orden	
							$.when.apply(this, results).done(function () {
								// alert(Object.keys(this).length);
								$.each(arguments,function(index,value){ // los datos de retorno quedan establecidos en arguments...
									// alert(value); // Si hay m�s de dos valores, value es un array con {data, success, jqxhr }
									// Si solo es un valor, entonces value vale data, success o jqxhr...
									try {
									var datos = jQuery.parseJSON(value[0]);
									// alert(datos.notificacion2);
									$("#lista2").append(datos.notificacion2+'<hr width="80%" align="center">');
									} catch(e) {
										try { // Si da un error, intenta hacerlo con value directamente. Si es JSON funcionar� ��??
											var datos = jQuery.parseJSON(value);
											$("#lista2").append(datos.notificacion2+'<hr width="80%" align="center">');
										} catch (e) {
											console.log(e.message);
										}
									}
								});
								obtenerListaFechas("Inicio"); // y al final del proceso se reinicia la lista de fechas...
							});	// FIN DEL WHEN APPLY 	
							
							results=[]; // limpia el array

							$(this).dialog("close");
						},
						"No,no... Cancela" : function() {
							$(this).dialog("close");
						},
					}
				});	
				
		$("#dialog-confirm-borrar").dialog("open"); // si no, no lo abre	
		
	    } // Fin de comprobar si hay o no datos 
	    
	}); // Fin del bot�n Borrar
	
    // **************************
    // B2) Copiar los datos
    // **************************
	$("#copiar").click(function(e){
		e.preventDefault();
		if (checkSeleccionados()<=0) { dialogoNohaydatos(); } else {
		$("#dialog-confirm").dialog({
			    buttons : {
				"S�, Graba" : function() {
				  // alert("dato grabado...");
				  $(this).dialog("close");
					var results=[];
					var fechaDada = $("#muestrafecha2").val();
					var indexAlumno = 0;
					$("#panel1").find(".eleccion").each(function() {
					   var Si = $(this).parent().parent().parent().parent().attr("elegir");
					   var idTabla = $(this).parent().parent().parent().parent().attr("id"); // alcanzo el nivel de tabla
					   indexAlumno = $(this).parent().parent().parent().parent().attr("alumno"); // atributo con el valor del alumno
					   var items = $(this).parent().parent().parent().parent().attr("items"); // atributo con el valor del alumno
					   var observaciones = $(this).parent().parent().parent().parent().attr("observaciones"); // atributo con el valor del alumno
					   if (Si==1) {
							results.push(insertarOpinion(indexAlumno,fechaDada,items,observaciones)); // inserto los resultados del ajax en el array
							// alert(indexAlumno+": fecha nueva:"+fechaDada+"; items: "+items+"; observaciones: "+observaciones);
					   } // Fin del IF de si est� escogido	
		   		   });	// Fin del reconocimiento de elementos escogidos o no.		
		    
					// Ahora aplico las llamadas en orden	
					$.when.apply(this, results).done(function () {
						// alert(Object.keys(this).length);
						$.each(arguments,function(index,value){ // los datos de retorno quedan establecidos en arguments...
							// alert(value); // Si hay m�s de dos valores, value es un array con {data, success, jqxhr }
							// Si solo es un valor, entonces value vale data, success o jqxhr...
							try {
							var datos = jQuery.parseJSON(value[0]);
							// alert(datos.notificacion);
							$("#lista2").append(datos.notificacion+'<hr width="80%" align="center">');
							} catch(e) {
								try { // Si da un error, intenta hacerlo con value directamente. Si es JSON funcionar� ��??
									var datos = jQuery.parseJSON(value);
									$("#lista2").append(datos.notificacion+'<hr width="80%" align="center">');
								} catch (e) {
									console.log(e.message);
								}
							}
						});
						obtenerListaFechas("Inicio"); // y al final del proceso se reinicia la lista de fechas...
					});	// FIN DEL WHEN APPLY 	
			
					results=[]; // limpia el array
			    },
				"No,no... Cancela" : function() {
				  $(this).dialog("close"); // no recarga la p�gina. Simplemente anula la operaci�n y sigue				 
			    }
			} // fin del buttons
        }); // fin del dialog
		
		$("#dialog-confirm").dialog("open"); // si no, no lo abre
		
		} // Fin de comprobar si hay o no datos 
		
	});  // Fin del bot�n copiar
    
    // **************************
    // B3) Otros botones
    // **************************
    $("#todosNinguno").buttonset(); // escribo los inputs directamente
	$('#todosNinguno').jqxSwitchButton({ 
		height: 55, 
		width: 160,  
		checked: false, 
		theme:'ui-sunny',
		onLabel:'Todos',
		offLabel:'Ninguno',
	});
	$('#todosNinguno').jqxSwitchButton('uncheck'); // Por defecto, est�n todos sin seleccionar
	
	// Coloca todos los items a falso
	$('#todosNinguno').bind('checked', function (event) { 
		// alert("Este es NO "+$(this).jqxSwitchButton('checked'));
		$("#panel1").find(".eleccion").each(function() {
		   // alert($(this).attr("src"));
		   $(this).attr("src","./imagenes/iconos/no.png");
		   $(this).parent().parent().parent().parent().attr("elegir","0");
		});
	}); 
	
	// Coloca todos los items a verdadero
	$('#todosNinguno').bind('unchecked', function (event) { 
		// TODOS: alert("Este es SI "+$(this).jqxSwitchButton('checked'));
		$("#panel1").find(".eleccion").each(function() {
		   // alert($(this).attr("src"));
		   $(this).attr("src","./imagenes/iconos/ok.png");
		   $(this).parent().parent().parent().parent().attr("elegir","1");
		});
	}); 
    
    // C) Datepicker fecha
     $("#fecha2").button({ width: '20px' }); // As� lo reconoce como del tema sunny 		 
	 $("#fecha2").datepicker({  			 
			   dateFormat: 'dd-mm-yy',
			   altField:"#muestrafecha2", // campo relacionado con el data picker
			   altFormat: 'yy-mm-dd', // Formato del campo relacionado. Tipo MySQL
			   changeMonth: true,
			   changeYear: true,
			   theme: 'ui-sunny',
			   beforeShow: function(event) {
			   },
			   onSelect: function (event) {
			   },
	 });
	 $("#fecha2").datepicker("setDate", new Date()); // pone la fecha de hoy

     
     // ****************************************************** 
     // <!-- * Funciones * --> 	    
     // ******************************************************     
      function checkSeleccionados() {
		 var number = 0;
		 $("#panel1").find(".eleccion").each(function() {
		   var Si = $(this).parent().parent().parent().parent().attr("elegir");
		   if (Si==1) {
			   number = number + 1;
		   }
	     }); 
         return number;		
	 };
	 
	 function dialogoNohaydatos() {
		$("#dialog-confirm-nohaydatos").dialog({
			buttons : {
				"Aceptar" : function() {
					$(this).dialog("close");
				},
			}
		});	
		$("#dialog-confirm-nohaydatos").dialog("open"); // si no, no lo abre	
	 }

     
     // ****************************************************** 
     // <!-- * Funciones al INICIO  * --> 	    
     // ******************************************************   
     obtenerListaFechas("Inicio");
     // getListaPaneles("2015-03-11","#lista1");
			
	 }); // fin del document ready
	 
// * ================================================================================================ */	 
	 // ******************************************************
	 // Eventos que se cargan tras cargar la p�gina **********
	 // ******************************************************	
	 $(document).on('click','.eleccion',function(event){
		 event.preventDefault(); 
		 var idTabla = $(this).parent().parent().parent().parent().attr("id"); // alcanzo el nivel de tabla
		 var eleccion = $(this).parent().parent().parent().parent().attr("elegir"); // Si est� elegido o no...
		 var idLista = $(this).parent().parent().parent().parent().parent().attr("id"); // alcanzo el nivel de lista
		 if (idLista=="lista1" && eleccion=="0") {
			 // cambia el icono del item
			 $(this).attr("src","./imagenes/iconos/ok.png");
			 // cambia el estado de la tabla a elegir = 1	
			 $(this).parent().parent().parent().parent().attr("elegir","1");		 
		 } else if (idLista=="lista1" && eleccion=="1") {
			 // cambia el icono del item
			 $(this).attr("src","./imagenes/iconos/no.png");
			 // cambia el estado de la tabla a elegir=0
			 $(this).parent().parent().parent().parent().attr("elegir","0"); // Lo vuelve a cambiar a cero
		 }
     });         


// * ================================================================================================ */	 	 
	 // ******************************************************
	 // Funciones en la p�gina *******************************
	 // ******************************************************	 
	 
	 //************************** 
	 // F1) Graba/modifica una opinion...
	 function insertarOpinion(idAlumno,fechaEscogida,cadenaItems,Observaciones) {
		     console.log("****************** Grabar Opini�n *******************");
		     console.log("fechaMySQL: "+fechaEscogida);
		     console.log("alumno: "+idAlumno);
		     console.log("cadenaItems: "+cadenaItems);
		     console.log("observaciones: "+Observaciones);
		     console.log("idasignacion: "+$("#idasignacion").val());
			 return $.ajax({
			      type: 'POST',
			      dataType: 'text',
			      url: "./opiniones/scripts/insertarOpinion.php", 
			      data: { // Parece que las llamadas con ajax van mejor que con POST...
				  fecha: fechaEscogida, // el campo oculto, que tiene la fecha formateada tipo MySQL
				  alumno: idAlumno, // La variable de sesi�n de la asignaci�n se consigue en el script.
				  items: cadenaItems,
				  observaciones: Observaciones,
				  idasignacion: $("#idasignacion").val(),
				  },					 		 
		          success: function(data, textStatus, jqXHR){
					 // alert(data);
				     return data;
			      },
			      error: function (jqXHR , textStatus, errorThrown) {
					  return "";
				  }
			  });

	  } // Fin de la funci�n grabar/modifica dato...

	 // ************************************
	 // F2) Establece variable de sesi�n de idalumno
	 // Lo dejo, por si hace falta un cambio, pero no uso esta funci�n
	 function getListaPaneles(fechaDada,lista) {
		  var posting = $.post( "./opiniones/scriptsHIS/listaPanel.php", { 
			  fecha: fechaDada, // La variable de sesi�n de la asignaci�n se consigue en el script.
			  });
		  posting.done(function(data,status) { 
			  // alert(data);
			  $(lista).html(data);
			  // $(lista).refresh();
			  // location.reload();
		  });
	 } // Fin de la funci�n de obtener 
	 
	 //************************************
	 // F3) fechas de un alumno - asignaci�n
	 function obtenerListaFechas(cuando) {
		  // alert("S�, llego aqu�");
		  var posting = $.post( "./opiniones/scriptsHIS/obtenerFechasHIS.php", { 
			  idasignacion: $("#idasignacion").val(),
			  });
		  posting.done(function(data,status) { 
			  // alert(data);
			  $("#listaFechas").html(data);
				  if (cuando.indexOf("Inicio")>= 0) {
					  getListaPaneles($("#listaFechas option:selected").attr("mysql"),"#lista1");
				  }
			  $("#listaFechas").selectmenu("refresh");
			  // location.reload();
		  });
	  } // Fin de la funci�n Obtener fechas de un alumno - asignaci�n
	 
	 
 <!-- * =======================================================================================================   * --> 	

  </script>
  
<!-- **************************************************************************************** -->
</body></html>
