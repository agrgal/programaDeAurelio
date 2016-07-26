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
$asignacion = New misAsignaciones($curso, $profesorado, $materia, $alumno); // Uso el constructor para pasarle la clase curso, profesorado, alumno y materias a Asignaciones

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
  <title>Opiniones alumno/a por alumno/a</title>
  <link rel="icon" 
        type="image/png" 
        href="./imagenes/logoA.png">
  <meta content="Aurelio Gallardo Rodríguez" name="author">  
  <meta content="Página índice de la web de tutoría" name="description">

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
			<?php $asignacion->retiraHuerfanos($_SESSION["idasignacion"]); // Retira opiniones huérfanas. ?>
			<?php // echo $_SESSION["idasignacion"]." - Prof: ".$_SESSION["profesor"]; ?>
			<!-- <a id="grabar">Grabar</a> -->
	    </p>
	    <!-- Así, para obtener la asignacion y pasarla como POST ¿futuro en Android? -->
	    <input id="idasignacion" value="<?php echo $_SESSION["idasignacion"]; ?>" style="display: none;"> 
	    <input id="idalumno" value="<?php echo $_SESSION["idalumno"]; ?>" style="display: none;">  
    </div>	<!-- TESTER -->
    
    <!-- ********************************************************** -->
    <!-- Contenido Principal -->
    <!-- ********************************************************** --> 
    
    <div id="contents"> <!-- &&&& --> 	  	   
	   		
	   		<div id="pestañas">
            <ul>
				<li><a href="#opinionesAlumno">Opinión alumno/a por alumno/a</a></li>
				<li><a href="#Instrucciones">Instrucciones</a></li>
				<li><a href="#InstruccionesHistorico">Inst. Hist. de Opiniones</a></li>
			</ul>
 
    <!-- ******************************************************************************************** -->            
            
				<div id="opinionesAlumno">	
					<div id="contenedorAlumno" class="effect7">		
						<h3 align="center">Elige un alumno/a</h3>	
						<?php // Elijo los alumnos de esta asignacion				
						// De esta sesión con este profesor
						$alumnado = $asignacion->devuelveListadoAlumnosdeEstaAsignacion($_SESSION["idasignacion"],$_SESSION["profesor"]);
						$alumnadoArray=explode("#",$alumnado); // devuelve un array con los números de alumnos...
					    echo '<div id="carrusel">';
						  foreach ($alumnadoArray as $clave => $i) {
							  $alumno->devuelveAlumno($i);
							  $cadena=sprintf($alumno->esteAlumno["div"],$i,$i); 
							  echo $cadena; // y los pone en el carrusel...
						  } 
						echo '</div>'; 
					    ?>
					    <div id="zonaSlider">
					        <div id="selectorAlumnos"></div>
					    </div>
					    <div id="zonaBotones">
							<a id="primero"><i class="fa fa-hand-o-down"></i></a>&nbsp;&nbsp;
							<a id="previo"><i class="fa fa-hand-o-left"></i></a>&nbsp;&nbsp;
							<a id="siguiente"><i class="fa fa-hand-o-right"></i></a>&nbsp;&nbsp;
							<a id="ultimo"><i class="fa fa-hand-o-up"></i></a>
					    </div>
					    <hr width="80%" align="center">					   
					    <div id="zonaFecha">
							 <h4>Escoger fecha de trabajo y opciones rápidas</h4>
								<select id="listaFechas">									
								</select>
					    </div>
					    <!-- <hr width="80%" align="center"> -->
					   		<div id="zonaOrdenes">
								<!-- <h3>De un sólo click...</h3> -->
								<div id="positivo" otro="negativo" class="ordenes">
									<i class="fa fa-plus" style="color: darkgreen;"></i>&nbsp;&nbsp;Positivos de este grupo
								</div>
								<div id="negativo" otro="positivo" class="ordenes">
									<i class="fa fa-minus" style="color: firebrick;"></i>&nbsp;&nbsp;Negativos de este grupo
								</div>
								<div id="todospositivos" principal="positivo" secundario="negativo" class="ordenes">
									<i class="fa fa-plus-square" style="color: darkgreen;" ></i>&nbsp;&nbsp;Todo es positivo
								</div>
								<div id="todosnegativos" principal="negativo" secundario="positivo" class="ordenes">
									<i class="fa fa-minus-square" style="color: firebrick;"></i>&nbsp;&nbsp;Todo es negativo
								</div>
								<div id="borrartodo" class="ordenes">
									<i class="fa fa-eraser" style="color: darkblue;"></i>&nbsp;&nbsp;Retirar todos los items
								</div>
							</div>						
					</div>
	<!-- ********************************************************** -->					
					<div id="contenedorOpiniones" class="effect7">
						<!-- <div id="zonaFecha">
							 <table width="80%"><tr>
							 <td align="left" width="30%"><h3>Escoger fecha de trabajo</h3></td>
							 <td><select id="listaFechas"></select></td>
							 </tr></table>
					    </div> -->
						<div id="contenedorOpinionesElegidas">
						  <!-- Opciones de las fechas -->							      
						  <!-- Cómo elegir fecha -->	
						  <h4>Elige fecha 
						      <input id="fecha" READONLY alt="Pulsa para obtener/cambiar una fecha" title="Pulsa para obtener o cambiar una fecha">
						      <input id="muestrafecha" style="display:none ;">
						      ,y, o bien puedes escribir una opinión o...</h4>
						  <!-- Zona de escritura -->
						  <div id="zonaEscribir">
							  <!-- Editor FROALA. Importante, añadir la clase froala-view -->
							  <div id="editor" class="froala-view"></div>
						  </div>
					    </div>
						<h3>...bien puedes eligir una pre-establecida</h3>
						<!-- Elegir un item  -->
						<?php 
						// Define el menú de los grupos
						echo '<div id="menuGrupos" style="visibility: hidden;"><ul>';
							foreach ($opiniones->listadoDeGrupos["grupo"] as $clave => $valor) {
								echo '<li id="'.$valor.'"><img style="margin-right: 15px; width:15px;" src="imagenes/iconos/item.png"><a id="'.$valor.'" class="grupos">'.$valor.'</a></li>';
							}								
						echo '</ul></div>'; 						
						echo '<div id="zonaItems">';
						    foreach($opiniones->listadoDeItems["iditem"] as $clave => $valor) {
							   echo $opiniones->listadoDeItems["div"][$clave];
						    }						     
						echo '</div>'; 
						?>
						
					</div> <!-- *** FIN DEL CONTENEDOR OPINIONES*** -->
					
					<!-- Imprimir los resultados -->
					<div id="printer" title="Imprime informe para el profesor/a" title="Imprime informe para el profesor/a" >
						<!-- <a id="imprimir" href="./pdf/scripts/listadoResultadosPDF.php" ><img src="./imagenes/iconos/printer_pdf.png"></a> -->
						<form id="formularioImprimir" action="./pdf/scripts/listadoInformeProfesor.php" method="POST">
							<input id="sendFecha" name="sendFecha" type="text" style="display: none;" value="">
							<!-- <button type="submit"><img src="./imagenes/iconos/printer_pdf.png"></button> -->
							<a id="imprimir" ><img src="./imagenes/iconos/printer_pdf.png"></a>
						</form>
					</div>
				
	<!-- ********************************************************** -->
				</div> <!-- *** FIN DEL CONTENEDOR OPINION UN ALUMNO *** -->
				
    <!-- ******************************************************************************************** --> 	
    <!-- *********** Segunda parte: Muchos alumnos  ************* --> 	
    <!-- ******************************************************************************************** --> 				
				
				<div id="Instrucciones">
					<p style="text-align: center; margin: 40px;"><iframe width="800" height="500" src="https://www.youtube.com/embed/htei6ygu5yQ" frameborder="0" allowfullscreen></iframe></p>
				</div>
				
				<div id="InstruccionesHistorico">
					<p style="text-align: center; margin: 40px;"></p>
				</div>
			
			</div>

	<!-- ********************************************************** -->
	<!-- ZONA DE DIALOGS O Notificaciones-->
	<!-- ********************************************************** -->
		
	<div id="notificacionGuardado">
		<div><h1>Se ha registrado el dato y guardado</h1></div>
	</div>
	
	<div id="notificacionModificar">
		<div><h1>El dato existente se ha salvado y/o modificado</h1></div>
	</div>
	
	<div id="notificacionBorrar">
		<div><h1>El dato se ha borrado (se borra cuando no escribes nada o no añades opiniones pre-establecidas)<h1></div>
	</div>
	
	<!-- Dialogo de Confirmación de borrar los datos -->	
	<!-- <div id="dialog-confirm-borrar" title="Borrar datos">
	   <p><span class="fa fa-trash fa-2x" style="float:left; margin:0 7px 20px 0;">
	   </span>
	   O bien no has introducido datos o los has borrado, o bien el sistema no ha registrado datos. En
	   el caso de que hubiera datos y no fuese tu intención borrarlos, pulsa Cancelar.
	   </p>
	</div>  -->
		
			
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
		 
		 // Versiones de JQUERY y JQUERY-UI
		 // alert($().jquery);
	     // alert($.ui.version);	
	     
	    // Variables globales       		 
        var opcionMenu; // Menú actualmente elegido de items
        var indexAlumno; //Alumno actualmente en la zona del carrusel 
        var fechaTrabajo = new Date();
        var RefrescaSelectMenuFechas = true; // Esta variable permite refrescar o no el select menu...

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
	
	    // ========================================================================================
        // Al principio...
        // ========================================================================================
	    $("#zonaBotones").buttonset(); // botones primero, siguiente, último, etc...
	    $("#fecha").button({
			width: '20px',
		}); // para que lo reconozca como del tema sunny			
		
		// Guarda los datos cada 20s. Esta función permite hacerlo en segundo plano...
		setInterval(function(){
		  if (indexAlumno>0) {
			   $.when(insertarOpinion(indexAlumno,fechaDada,getItemsElegidos())).done(function(data){
				   try {
					console.log("Interval Activo. Fecha Dada: "+fechaDada+ " Indice alumno: "+indexAlumno);
					var recupera = jQuery.parseJSON(data);
					recupera.notificacion = '<div><h1><b>Automático</b></h1></div>' + recupera.notificacion;
					notificaciones(recupera.devolver,recupera.notificacion); // pasa los datos a la función...
					}
					catch(err) {
					console.log(err.message);
					}
			   });
		  } // Guarda o modifica los datos que sean..
		},20000);

	    // ========================================================================================
        // Defino diálogos y/o notificaciones
        // ========================================================================================	
		 $("#notificacionGuardado, #notificacionModificar").jqxNotification({
                width: 500, position: "top-right", opacity: 0.9,
                autoOpen: false, animationOpenDelay: 300, autoClose: true, autoCloseDelay: 2000, template: "info"
         });
         $("#notificacionBorrar").jqxNotification({
                width: 500, position: "top-right", opacity: 0.9,
                autoOpen: false, animationOpenDelay: 500, autoClose: true, autoCloseDelay: 4000, template: "warning"
         });
         
        // ************************************
		// Al hacer click en el icono impresora
		// ************************************

		$("#imprimir").click(function(event,ui){
			// alert("Imprime...");
			document.getElementById("formularioImprimir").submit();
		}); 

		// ========================================================================================
        // DEFINO tabs. LLAMO Pestañas. Defino editor en zonaEscribir. Defino fecha
        // ========================================================================================
        
         $('#pestañas').tabs();      
		 
		 // Cuadro de escritura 
		 $('#editor').editable({ // idioma también cargando el es.js 
			 inlineMode: false, language: 'es', maxCharacters: 5000,
			 placeholder: 'Escribe algo. Hasta 5000 caracteres...', 
			 buttons: ["bold", "italic", "underline", "strikeThrough","sep"
			           ,"fontFamily", "fontSize", "formatBlock", "color","sep"
			           ,"insertOrderedList", "insertUnorderedList", "outdent", "indent", "sep"
			           ,"createLink", "insertHorizontalRule", "table","html"]
		 });
		 
		 // Fecha		 
		 $("#fecha").datepicker({  			 
				   dateFormat: 'dd-mm-yy',
				   altField:"#muestrafecha", // campo relacionado con el data picker
				   altFormat: 'yy-mm-dd', // Formato del campo relacionado. Tipo MySQL
				   changeMonth: true,
				   changeYear: true,
				   theme: 'ui-sunny',
				   beforeShow: function(event) { // Justo antes de mostrarlo, guarda lo de la fecha anterior...
					   event.preventDefault;  
					   $.when(insertarOpinion(indexAlumno,fechaDada,getItemsElegidos())).done(function(data){
						   try {
							console.log("Before Show. Fecha Dada: "+fechaDada+ " Indice alumno: "+indexAlumno);
    						var recupera = jQuery.parseJSON(data);
    						notificaciones(recupera.devolver,recupera.notificacion); // pasa los datos a la función...
						    }
						    catch(err) {
							console.log(err.message);
						    }
					   });
				   },
				   onSelect: function (event) {
					   event.preventDefault;
					   $("#borrartodo").click(); // borra todos los items
					   $("#editor").editable("setHTML", "", true); // campo texto lo pone a cero
					   inicializaItems(); // Pone en pantalla el primer grupo de items...					   
					   fechaDada = $("#muestrafecha").val();
					   $("#sendFecha").val(fechaDada); // SENDFECHA
					   fechaTrabajo = convertirFecha(fechaDada); //???
					   $.when(obtenerOpinion(indexAlumno,fechaDada)).done(function(data2){
							try {
							   var datos2 = jQuery.parseJSON(data2); // recupera en el array datos los valores
							   opiniones(datos2.observaciones,datos2.items);
							} catch(err) {
								console.log(err.message);
							}	
					   });
				   },
					   
		 }); 		 
		 $("#fecha").datepicker("setDate", fechaTrabajo); // pone la fecha de hoy
		 
		 // Listado de fechas
		 $("#listaFechas")
			.selectmenu({
				width:300, 
				style:'dropdown',
				select: function( event, ui ) {		
				
			    if (RefrescaSelectMenuFechas) { // Permite o no refrescar datos según esta variable...
					
				var fechaNueva = $("#listaFechas option:selected").attr("mysql"); // fecha en formato mysql
				// alert("Estoy en select menu... Dada: "+fechaDada+" Nueva: "+fechaNueva);				
				
				$.when(insertarOpinion(indexAlumno,fechaDada,getItemsElegidos()),
			           obtenerOpinion(indexAlumno,fechaNueva)).done(function(data1,data2,data3){
						// ¡¡OJO!! cada data1 es el resultado de devolver la petición ajax COMPLETA
						// Y es un array con los datos, con el status y con un objeto jqXHR
						// por eso los datos están en el lugar 0
						// alert(data1[0]);
						// parsea datos de lo primero que retorna
						try {
							console.log("SELECT 1.- Notificación");
    						var datos1 = jQuery.parseJSON(data1[0]);
							notificaciones(datos1.devolver,datos1.notificacion); // pasa los datos a la función...
						}
						catch(err) {
							console.log(err.message);
						}
				        // Limpieza de datos en pantalla
				        $("#borrartodo").click(); // borra todos los items
				        $("#editor").editable("setHTML", "", true); // campo texto lo pone a cero
			            inicializaItems(); // Pone en pantalla el primer grupo de items...
			            // Segundo conjunto de datos.
			      		// alert(data2[0]);
			      		try {
						   console.log("SELECT 2.- Poner los datos del anterior...");
			      		   var datos2 = jQuery.parseJSON(data2[0]); // recupera en el array datos los valores
						   opiniones(datos2.observaciones,datos2.items);
						} catch(err) {
							console.log(err.message);
						}		
						// tercer conjunto de datos	
						// alert(data3[0]);
						fechaTrabajo = $("#listaFechas option:selected").val();
						$("#fecha").datepicker("setDate",fechaTrabajo);
						fechaDada = $("#muestrafecha").val();	
						$("#sendFecha").val(fechaDada); // SENDFECHA
					}); // Fin del when	
					
				    } // Fin del IF RefrescaSelectMenuFechas				    

				},
			})
			.selectmenu("menuWidget")
			   .addClass("overflow3"); // carga un estilo que está en /css/estiloSelectMenuOverflow.css
            
		// ========================================================================================
        // DEFINO EL MENÚ DE LOS GRUPOS
        // ========================================================================================
		$("#menuGrupos").jqxMenu({ mode: 'horizontal' , theme:'ui-sunny'});
		$("#menuGrupos").css('visibility', 'visible');
		// $("#menuGrupos li a:first").trigger("click");
		$('#menuGrupos').on('itemclick', function (event) {
				event.preventDefault;
				// var element = event.args;
				// alert($(event.target).attr("class")); alert($(event.target).attr("id"));
				opcionMenu = $(event.target).attr("id");
				$("#zonaItems").hide();
		  	    eligeGrupoItems(); // función que oculta los items menos los escogidos
				$("#zonaItems").fadeIn("slow");
				// $("#testear").html("Opcion Menu "+$("#menuGrupos li a:first").attr("id"));
		});
		
		// ========================================================================================
        // DEFINO Carousel
        // ========================================================================================
		 var owl = $("#carrusel"); // variable que representa el Carousel		 
		 owl.owlCarousel({
		    items: 1,
		    navigation: false,
		    navigationText: [
			  "<i class='icon-chevron-left icon-white' style='font-size: 2em;' ><</i>",
			  "<i class='icon-chevron-right icon-white' style='font-size: 2em;'>></i>",
			 ], // Cambia los botones de izquierda y derecha.
			pagination: false, // Quita los botoncitos
			// paginationNumbers: true,	 
			beforeInit: function (elemento) {
				$(".divalumno").each(function(index){
					$(this).addClass("divalumnoOpiniones"); // cambia la clase del divalumno...
					$(this).children("#image").css("background-size","90px 90px"); // tamaño imagen div
			  		$(this).children("#image").css("width","200px"); // tamaño imagen div
			        $(this).children("#image").css("height","125px"); // tamaño imagen div
			 		$(this).children("#texto").css("width","200px"); // tamaño letra
			        $(this).children("#texto").children("p").css("font-size","1.1em"); // tamaño letra			   
				});
			}, 
		    afterAction: function (elemento) { 			
							
				if (indexAlumno>0) {
					var indexOld = indexAlumno; // valor que trae antes de cambiarlo...
				}
				
				// Obtiene el siguiente valor del indexAlumno
				var current = this.owl.currentItem; // valor de este div en el carousel
				indexAlumno = elemento.find(".owl-item").eq(current).find(".divalumno").attr('id'); // Lo que funciona
                var owlNumber = elemento.find(".owl-item").eq(current).index(); // por índice
			    console.log(owlNumber);
			    $('#selectorAlumnos').val(owlNumber+1);
			    
			    // Y obtener si la hubiera nueva opinion
			    $("#idalumno").val(indexAlumno); // cambia el id del alumno
			    fechaDada = $("#muestrafecha").val();
			   
			    $.when(insertarOpinion(indexOld,fechaDada,getItemsElegidos()),
			           obtenerOpinion(indexAlumno,fechaDada),
			           obtenerListaFechas(indexAlumno)).done(function(data1,data2,data3){
						// ¡¡OJO!! cada data1 es el resultado de devolver la petición ajax COMPLETA
						// Y es un array con los datos, con el status y con un objeto jqXHR
						// por eso los datos están en el lugar 0
						// alert(data1[0]);
						// parsea datos de lo primero que retorna
						try {
							console.log("Carosuel 1.- Notificación...");
    						var datos1 = jQuery.parseJSON(data1[0]);
							notificaciones(datos1.devolver,datos1.notificacion); // pasa los datos a la función...
						}
						catch(err) {
							console.log(err.message);
						}
				        // Limpieza de datos en pantalla
				        $("#borrartodo").click(); // borra todos los items
				        $("#editor").editable("setHTML", "", true); // campo texto lo pone a cero
				        // $("#fecha").datepicker("setDate",fechaTrabajo); // pone la fecha escogida 
			            inicializaItems(); // Pone en pantalla el primer grupo de items...
			            // Segundo conjunto de datos.
			      		// alert(data2[0]);
			      		try {
						   console.log("Carosuel 2.- Poner datos...");
			      		   var datos2 = jQuery.parseJSON(data2[0]); // recupera en el array datos los valores
						   opiniones(datos2.observaciones,datos2.items);
						} catch(err) {
							console.log(err.message);
						}
						// tercer conjunto de datos	
						// alert(data3[0]);
						console.log("Carosuel 3.- Refrescar lista de fechas");
						try {
							var datos3 = jQuery.parseJSON(data3[0]);			  
							$("#listaFechas").html(datos3.seleccion); // pone el select que calcula el script
							console.log("Carosuel 4 A. FechaSelectedMySQL: "+datos3.fechaSelectedMySQL);
							console.log("Carosuel 4 B. FechaSelectedMDP: "+datos3.fechaSelectedMDP);
							console.log("Carosuel 4 C. HTML: "+datos3.seleccion);
							RefrescaSelectMenuFechas = false; // desactiva el obtener datos por cambio de fechas
							$("#listaFechas").selectmenu("refresh");
							// $("#testear").text($("#listaFechas").html());							
							// fechaTrabajo = $("#listaFechas option:selected").val();
							// $("#fecha").datepicker("setDate",fechaTrabajo);
							RefrescaSelectMenuFechas = true; // Activa el obtener datos por cambio de fechas
						}
						catch(err) {
							console.log(err.message);
						}
				});	
			},
		 });
		// Custom Navigation Events
			$("#siguiente").click(function(){
			    owl.trigger('owl.next');
			})
			$("#previo").click(function(){
			    owl.trigger('owl.prev');
			})
			$("#primero").click(function(){
			    owl.trigger('owl.goTo',0); // El primero empieza en 0.
			})				
			$("#ultimo").click(function(){
			    owl.trigger('owl.goTo',1000); // Se pone un número grande que no se va a alcanzar...
			})				
		
		// ========================================================================================
        // DEFINO Zona de los Items
        // ========================================================================================
        $("#zonaItems .divitem").each(function() {
			$(this).hide(); // oculta al principio todos los items...
		});
		
		// ========================================================================================
        // DEFINO botones de órdenes y sus funciones
        // ========================================================================================
        
         $('.ordenes').jqxButton({ theme: 'ui-sunny'} );   
         
         // Botón positivos del grupo
         $("#positivo, #negativo").click(function(e) { 
				e.preventDefault();
				var principal = $(this).attr("id");
				var secundario = $(this).attr("otro");
				$(".divitem").each(function(){
					if ($(this).attr("grupo")==opcionMenu && $(this).hasClass(principal) 
					    && $(this).parent().attr("id")=="zonaItems") {
						$(this).hide().appendTo("#contenedorOpinionesElegidas").fadeIn("slow"); 
					} // pone los positivos en el contenedor de abajo --> viceversa con los negativos
					if ($(this).attr("grupo")==opcionMenu && $(this).hasClass(secundario) 
					    && $(this).parent().attr("id")=="contenedorOpinionesElegidas") {
					    $(this).hide().appendTo("#zonaItems").fadeIn("slow"); 
					} // y si hubiese negativos de ese grupo, los pone arriba --> viceversa al pulsar negativo
				});	
				$("#zonaEscribir").appendTo("#contenedorOpinionesElegidas");			
	     });	     

	     // Cuando se pulsa o todos positivos o negativos
	     $("#todospositivos, #todosnegativos").click(function(e) { 
				e.preventDefault();
				var principal=$(this).attr("principal"); // obtiene la clase positivo o negativo, qu lo define
				var secundario=$(this).attr("secundario");// obtiene la clase contraria
				// alert(principal+" "+secundario);
				$(".divitem").each(function(){
					if ($(this).hasClass(principal) && $(this).parent().attr("id")=="zonaItems") {
						$(this).hide().appendTo("#contenedorOpinionesElegidas").fadeIn("slow");
					} // Si es de la clase positivo y está en la zona Items pasarlo a contenedorElegidas
					if ($(this).hasClass(secundario) && $(this).parent().attr("id")=="contenedorOpinionesElegidas") {
						$(this).hide().appendTo("#zonaItems");
						if ($(this).attr("grupo")==opcionMenu) { $(this).fadeIn("slow");}
					} // Si es de la otra clase y pertenece a las Elegidas, las oculta y las pone en la zonaItems
					// Y además, si pertenecen al grupo elegido las muestra...
				});
				$("#zonaEscribir").appendTo("#contenedorOpinionesElegidas");
	     });
	     
		 // Retira todos los items de la zona Elegida
	     $("#borrartodo").click(function(e) { 
				e.preventDefault();
				$(".divitem").each(function(){
					if ($(this).parent().attr("id")=="contenedorOpinionesElegidas") {
						$(this).hide().appendTo("#zonaItems"); // Los pasa a la zona items
						if ($(this).attr("grupo")==opcionMenu) { $(this).fadeIn("slow");}
					}
				});
	     });
		 
		// ========================================================================================
        // Selector de alumnos
        // ========================================================================================
		 var ultimo = owl.data('owlCarousel').itemsAmount; // Tiene que estar en este orden
		 $('#selectorAlumnos').jqxSlider({ 
			 min: 1, 
			 max: ultimo, 
			 ticksFrequency: 5, 
			 value: 1, 
			 step: 1,
			 theme: 'ui-sunny',
			 showButtons:false, // no muestra los botones. El evento change desactivado...
			 width: 300,
			 height: 80,
			 tooltip: true,
			 mode: 'fixed',
		 });         

         $('#selectorAlumnos').on('slideEnd', function (event) { // slideEnd mejor que change
              owl.trigger('owl.goTo',$(this).val()-1); // Del 1 al ultimo -1 es el índice
         });       

		
		// =======================================================================================
		// Eventos ONCLICK
		// =======================================================================================
		
         // al pulsar sobre alumno		
		 $(".divalumno").click(function(event){
			event.preventDefault();
			// alert("Hola"); // debe disparar funciones que recuperen lo grabado...
		 });
		 // Ver esto: http://jsfiddle.net/adeneo/gdNue/10/
		 
		 // Al pulsar en el botón divitem
		 $(".divitem").click(function(event) {
			event.preventDefault();
			if($(this).parent().attr("id")=="zonaItems") {
				$(this).appendTo("#contenedorOpinionesElegidas"); 
			} else if ($(this).parent().attr("id")=="contenedorOpinionesElegidas") {
				$(this).appendTo("#zonaItems");
			}
			eligeGrupoItems();
			// Se ordenan los items...
			$("#contenedorOpinionesElegidas").find(".divitem").sort(function(a,b){
					return $(a).attr("id")-$(b).attr("id"); // para con números operar con el menos "-" a - b
			 }).appendTo("#contenedorOpinionesElegidas"); //ordenamos los items

			$("#zonaEscribir").appendTo("#contenedorOpinionesElegidas"); // pongo la zona escribir al final
			
		 }); // fin de pulsar el botón divitem
		 
		 $(".enlaceFecha").click(function(event){
			event.preventDefault();
			alert($(this).attr("id")); 
		 });
		 
		 // *********************
		 // provisonal: al grabar
		 // $("#grabar").click(function(event) {
		 //	 event.preventDefault();
			 // alert("He pulsado grabar");
		 //	 if (indexAlumno>0) {
		 //			insertarOpinion(indexAlumno,getItemsElegidos());
		 //	 } // Guarda o modifica los datos que sean..
		 // }); // *********************
		 
		// =======================================================================================
		// Drag and drop
		// =======================================================================================
		
		//a) Convierte los ".divitem" en draggable...
		$(".divitem").draggable({
		   containment: "#contenedorOpiniones", // contenedor donde puede moverse
		   stack: "#contenedorOpiniones", // pone su z-index por encima de todos los de esa zona
		   helper: 'clone', // mueve una copia del elemento
		   revert: 'invalid', // vuelve a su lugar original si no puedo ponerlo sobre la zona objetivo
           appendTo: 'body', // lo añade al body 
           cursor: "move",		  
		}); 
		
		// b) Zona donde se "dejan" caer los items...
		$("#contenedorOpinionesElegidas").droppable({  
			// var factor = 0,8;
			// ************************************************
			// A) Introduzco elementos en la zona seleccionable. 
			// ************************************************     
		    drop: function (event, ui) {
			   var estilo = ui.draggable.attr("class"); // obtengo la clase, para distinguirlo
			   $("#contenedorOpinionesElegidas").append(ui.draggable);
			   // ordenando los elementos de la lista
			   $("#contenedorOpinionesElegidas").find(".divitem").sort(function(a,b){
					   return $(a).attr("id")-$(b).attr("id"); // para con números operar con el menos "-" a - b
			   }).appendTo("#contenedorOpinionesElegidas");
			   $("#zonaEscribir").appendTo("#contenedorOpinionesElegidas"); // pongo la zona escribir al final			   
		    }, // fin del drop
			out: function (event, ui) { // si saco un elemento de la selección			   
			   var objeto = $(ui.draggable);
			   objeto.draggable( "option", "revert", false ); // No vuelve a su posición inicial...
			    if (objeto.attr("grupo")!=opcionMenu) {
					   objeto.hide(); // Si el grupo NO ES  el elegido en ese momento, lo oculta
					   // ya aparecerá cuando 
				} 
			   objeto.appendTo("#zonaItems"); //vuelve a la zona de los items
			   $("#zonaEscribir").appendTo("#contenedorOpinionesElegidas"); // pongo la zona escribir al final
		    } // fin del out
		 });
	     
	     // ******************************************************
         // Rutinas DENTRO del Document Ready
         // ******************************************************
         function eligeGrupoItems() { // Pequeña rutina que sirve para mostrar/ocultar los divitem en la zona de elección zonaItems
		 	$("#zonaItems .divitem").each(function() {					
				if ($(this).attr("grupo")==opcionMenu) {
					$(this).show(); // mostrar
				} else { $(this).hide(); }				
			});
		 } // fin de la función
		 
		 // Función que obtiene los items en la zona de elegidos
		 function getItemsElegidos() {
			 var cadena="";
			 $("#contenedorOpinionesElegidas").find(".divitem").sort(function(a,b){
					return $(a).attr("id")-$(b).attr("id"); // para con números operar con el menos "-" a - b
			 }).appendTo("#contenedorOpinionesElegidas"); //ordenamos los items
			 $("#contenedorOpinionesElegidas .divitem").each(function(){
				 cadena=cadena+"#"+ $(this).attr("id"); // obtenemos la cadena...
			 });
			 return cadena.substr(1);
		 } // fin de la función

		 // Función que carga el primer grupo de Items por defecto
		 function inicializaItems() {
			opcionMenu = $("#menuGrupos li a:first").attr("id");
			eligeGrupoItems();
		 }
		 
		 // Convierte una fecha del tipo 03-05-2015 en la del datePicker;
		 function convertirFecha(datofecha) {
			// alert(datofecha);
			dateParts = datofecha.match(/(\d+)/g)
		    realDate = new Date(dateParts[2], dateParts[1] - 1, dateParts[0]); 
		    // alert(realDate);
		    return realDate;
		 }		
		 
		 // Retorno de notificaciones. Datos provienen de insertar Alumno opinión
		 function notificaciones(devolver,notificacion) {
            if (devolver==1)  { 
				$("#notificacionGuardado").html(notificacion); 
				$("#notificacionGuardado").jqxNotification("open"); 
			}
			if (devolver==2) { 
				$("#notificacionModificar").html(notificacion); 
				$("#notificacionModificar").jqxNotification("open"); 
			}
			if (devolver==3)  { 
				$("#notificacionBorrar").html(notificacion);
				$("#notificacionBorrar").jqxNotification("open"); 
			}
			$("#zonaEscribir").appendTo("#contenedorOpinionesElegidas"); // pongo la zona escribir al final	
		 } 
		 
		 // Retorno de Obtener opinion. Datos proceden de la obtención mediante la llamada a opinionesAlumnos
		 function opiniones(observaciones,itemsCadena) {
			   $("#editor").editable("setHTML", observaciones, true); // obtiene las observaciones...
			   var items=itemsCadena.split("#"); // obtiene un array con los items...
			   //los items tienen que venir ya borrados... llamada a la función borrar todo...
			   // alert(datos2.items);
			   $(".divitem").each(function(){
				 if (jQuery.inArray($(this).attr("id"),items)>=0) {
				   $(this).show().appendTo("#contenedorOpinionesElegidas"); // agrega a elegidas...
				 }	
			   });	
			   $("#zonaEscribir").appendTo("#contenedorOpinionesElegidas"); // pongo la zona escribir al final		 
		 }
			
		 // *****************************************************************************
		 // Condiciones de CARGA AL INICIO, cuando ya todo se ha ejecutado... ¡¡Pues sí!!
		 // *****************************************************************************
		 // Al inicio elige una opción del grupo Items
         inicializaItems();
         var fechaDada = $("#muestrafecha").val(); // inicializa la variable fechaDada
         $("#sendFecha").val(fechaDada); // SENDFECHA
         // alert(fechaDada);
			
	 }); // fin del document ready
	 
	 // ******************************************************
	 // Funciones en la página *******************************
	 // ******************************************************
	 
	 //************************** 
	 // F1) Graba/modifica una opinion...
	 function insertarOpinion(idAlumno,fechaEscogida,cadenaItems) {
		     console.log("****************** Grabar Opinión *******************");
		     console.log("fechaMySQL: "+fechaEscogida);
		     console.log("alumno: "+idAlumno);
		     console.log("cadenaItems: "+cadenaItems);
		     console.log("observaciones: "+$("#editor").editable("getHTML", false, false));
		     console.log("idasignacion: "+$("#idasignacion").val());
			 return $.ajax({
			      type: 'POST',
			      dataType: 'text',
			      url: "./opiniones/scripts/insertarOpinion.php", 
			      data: { // Parece que las llamadas con ajax van mejor que con POST...
				  fecha: fechaEscogida, // el campo oculto, que tiene la fecha formateada tipo MySQL
				  alumno: idAlumno, // La variable de sesión de la asignación se consigue en el script.
				  items: cadenaItems,
				  observaciones: $("#editor").editable("getHTML", false, false), // ver FROALA methods
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

	  } // Fin de la función grabar/modifica dato...
	  
	 //************************** 
	 // F2) Obtener opinion
	 function obtenerOpinion(idAlumno,fechaEscogida) {
		 console.log("****************** Obtiene Opinión *******************");
		 console.log("Id alumno: "+idAlumno);
		 console.log("fechaMySQL: "+fechaEscogida);
		 console.log("Asignacion: "+$("#idasignacion").val());
		 return $.ajax({ 
			  type: 'POST',
			  dataType: 'text',			      
		      url: "./opiniones/scripts/obtenerOpinion.php", 
		      data: { 
			  fecha: fechaEscogida, // el campo oculto, que tiene la fecha formateada tipo MySQL
			  alumno: idAlumno, // La variable de sesión de la asignación se consigue en el script.
			  idasignacion: $("#idasignacion").val(),
		      }, 
		      success: function(data, textStatus, jqXHR){
				return data;
			  },
		  }); 
	  } // Fin de la función Obtener dato
	  
	 //************************************
	 // F3) fechas de un alumno - asignación
	 function obtenerListaFechas(idAlumno) {
		 // alert("Sí, llego aquí");
		 console.log("****************** Obtiene FECHAS *******************");
		 console.log("Id alumno: "+idAlumno);
		 console.log("muestraFecha: "+$("#muestrafecha").val());
		 console.log("fechaSelectedMySQL: "+ $("#listaFechas option:selected").attr("mysql"));
		 console.log("fechaSelectedDP: "+$("#listaFechas option:selected").val());	 
		 return $.ajax({
			  type: 'POST',
			  dataType: 'text',	
		      url: "./opiniones/scripts/obtenerFechas.php", 
		      data: { 
			  alumno: idAlumno, // La variable de sesión de la asignación se consigue en el script.
			  idasignacion: $("#idasignacion").val(),
			  fechaSelectedMySQL: $("#listaFechas option:selected").attr("id"),
			  fechaSelectedDP: $("#listaFechas option:selected").val(),
		      },
		      success: function(data, textStatus, jqXHR){ 			  
				return data;
		      },
		  });  
	  } // Fin de la función Obtener fechas de un alumno - asignación

     // ************************************
	 // F4) Establece variable de sesión de idalumno
	 // Lo dejo, por si hace falta un cambio, pero no uso esta función
	 function setVariableSesionAlumno(idAlumno) {
		  var posting = $.post( "./opiniones/scripts/setVariableSesionAlumno.php", { 
			  idalumno: idAlumno, // La variable de sesión de la asignación se consigue en el script.
			  });
		  posting.done(function(data,status) { 
			  alert(data);
			  location.reload();
		  });
	 } // Fin de la función de obtener 
	  
 <!-- * =======================================================================================================   * --> 	

  </script>
  
<!-- **************************************************************************************** -->
</body></html>
