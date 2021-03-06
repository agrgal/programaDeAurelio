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
$asignacion = New misAsignaciones($curso, $profesorado, $materia); // Uso el constructor para pasarle la clase curso, profesorado y materias a Asignaciones

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
  <title>Elige y crea nuevas asignaciones</title>
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
			<?php // echo $_SESSION["idasignacion"]." - Prof: ".$_SESSION["profesor"]; ?>
			<!-- <a id="grabar">Grabar</a> -->
	    </p>
	    <!-- As�, para obtener la asignacion y pasarla como POST �futuro en Android? -->
	    <input id="idasignacion" value="<?php echo $_SESSION["idasignacion"]; ?>" style="display: text;"> 
	    <input id="idalumno" value="<?php echo $_SESSION["idalumno"]; ?>" style="display: text;">  
    </div>	<!-- TESTER -->
    
    <!-- ********************************************************** -->
    <!-- Contenido Principal -->
    <!-- ********************************************************** --> 
    
    <div id="contents"> <!-- &&&& --> 	  	   
	   		
	   		<div id="pesta�as">
            <ul>
				<li><a href="#opinionesAlumno">Opini�n alumno/a por alumno/a</a></li>
				<li><a href="#opinionesMuchosAlumnos">Opiniones a varios alumnos/as</a></li>
			</ul>
 
    <!-- ******************************************************************************************** -->            
            
				<div id="opinionesAlumno">	
					<div id="contenedorAlumno">		
						<h3 align="center">Elige un alumno/a</h3>	
						<?php // Elijo los alumnos de esta asignacion				
						// De esta sesi�n con este profesor
						$alumnado = $asignacion->devuelveListadoAlumnosdeEstaAsignacion($_SESSION["idasignacion"],$_SESSION["profesor"]);
						$alumnadoArray=explode("#",$alumnado); // devuelve un array con los n�meros de alumnos...
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
							 <h3>Escoger fecha de trabajo</h3>
								<select id="listaFechas">
									<optgroup label="Estandard">
									<option id="now">La de hoy</option>
									<option id="last" value="09-03-2015">La �ltima</option>
									</optgroup>
									<optgroup label="Concreta">
									<?php
									$opiniones->listaFechasGlobal($_SESSION["idasignacion"]); // Activa la lista de fechas
									foreach ($opiniones->listadoDeFechasGlobal["fechaMySQL"] as $clave => $valor) {
									   // por cada fecha
									   echo '<option class="fechasListado" id='.$valor
									   .' value="'.$opiniones->listadoDeFechasGlobal["fechaDatepicker"][$clave].'">'
									   .$opiniones->listadoDeFechasGlobal["fechaDatepicker"][$clave].'</option>';	
									}
									?>									
									</optgroup>
								</select>
					    </div> 
					    <hr width="80%" align="center">
					   		<div id="zonaOrdenes">
								<h3>De un s�lo click...</h3>
								<div id="positivo" otro="negativo" class="ordenes">
									<i class="fa fa-plus" style="color: darkgreen;"></i>&nbsp;Positivos de este grupo
								</div>
								<div id="negativo" otro="positivo" class="ordenes">
									<i class="fa fa-minus" style="color: firebrick;"></i>&nbsp;Negativos de este grupo
								</div>
								<div id="todospositivos" principal="positivo" secundario="negativo" class="ordenes">
									<i class="fa fa-plus-square" style="color: darkgreen;" ></i>&nbsp;Todo es positivo
								</div>
								<div id="todosnegativos" principal="negativo" secundario="positivo" class="ordenes">
									<i class="fa fa-minus-square" style="color: firebrick;"></i>&nbsp;Todo es negativo
								</div>
								<div id="borrartodo" class="ordenes">
									<i class="fa fa-eraser" style="color: darkblue;"></i>&nbsp;Retirar todos los items
								</div>
							</div>						
					</div>
	<!-- ********************************************************** -->					
					<div id="contenedorOpiniones">
						<div id="contenedorOpinionesElegidas">
						  <!-- Opciones de las fechas -->							      
						  <!-- C�mo elegir fecha -->	
						  <h4>Elige fecha 
						      <input id="fecha" READONLY alt="Pulsa para obtener/cambiar una fecha" title="Pulsa para obtener o cambiar una fecha">
						      <input id="muestrafecha" style="display:none ;">
						      ,y, o bien puedes escribir una opini�n o...</h4>
						  <!-- Zona de escritura -->
						  <div id="zonaEscribir">
							  <!-- Editor FROALA. Importante, a�adir la clase froala-view -->
							  <div id="editor" class="froala-view"></div>
						  </div>
					    </div>
						<h3>...bien puedes eligir una pre-establecida</h3>
						<!-- Elegir un item  -->
						<?php 
						// Define el men� de los grupos
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
	<!-- ********************************************************** -->
				</div> <!-- *** FIN DEL CONTENEDOR OPINION UN ALUMNO *** -->
				
    <!-- ******************************************************************************************** --> 	
    <!-- *********** Segunda parte: Muchos alumnos  ************* --> 	
    <!-- ******************************************************************************************** --> 				
				
				<div id="opinionesMuchosAlumnos">
					<p>Muchos alumnos</p>
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
		<div><h1>El dato se ha borrado (se borra cuando no escribes nada o no a�ades opiniones pre-establecidas)<h1></div>
	</div>
	
	<!-- Dialogo de Confirmaci�n de borrar los datos -->	
	<!-- <div id="dialog-confirm-borrar" title="Borrar datos">
	   <p><span class="fa fa-trash fa-2x" style="float:left; margin:0 7px 20px 0;">
	   </span>
	   O bien no has introducido datos o los has borrado, o bien el sistema no ha registrado datos. En
	   el caso de que hubiera datos y no fuese tu intenci�n borrarlos, pulsa Cancelar.
	   </p>
	</div>  -->
		
			
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
  <!-- <script src="./jquery/jquery-ui.min.js"></script> <!-- version 1.11.2 -->
  <script src="//code.jquery.com/ui/1.11.0/jquery-ui.min.js"></script> <!-- version 1.11.0 -->
  <!-- <script src="./htmlsuelto/js_menu.js"></script>   Incorpora al script los men�s a la izquierda -->  
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
        var opcionMenu; // Men� actualmente elegido de items
        var indexAlumno; //Alumno actualmente en la zona del carrusel 
        var fechaTrabajo = new Date();

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
	
	    // ========================================================================================
        // Al principio...
        // ========================================================================================
	    $("#zonaBotones").buttonset(); // botones primero, siguiente, �ltimo, etc...
	    $("#fecha").button({
			width: '20px',
		}); // para que lo reconozca como del tema sunny			
		
		// Guarda los datos cada 10s. Esta funci�n permite hacerlo en segundo plano...
		setInterval(function(){
		   if (indexAlumno>0) {
		 		  insertarOpinion(indexAlumno,getItemsElegidos());
		   } // Guarda o modifica los datos que sean..
		},10000);

	    // ========================================================================================
        // Defino di�logos y/o notificaciones
        // ========================================================================================	
		 $("#notificacionGuardado, #notificacionModificar").jqxNotification({
                width: 500, position: "top-right", opacity: 0.9,
                autoOpen: false, animationOpenDelay: 300, autoClose: true, autoCloseDelay: 2000, template: "info"
         });
         $("#notificacionBorrar").jqxNotification({
                width: 500, position: "top-right", opacity: 0.9,
                autoOpen: false, animationOpenDelay: 500, autoClose: true, autoCloseDelay: 4000, template: "warning"
         });
         
         /* $("#dialog-confirm-borrar").dialog({
			autoOpen: false,
			modal: true,
			maxWidth:600,
            maxHeight: 450,
            width: 600,
            height: 450,
			position: { my: "center center-100", at: "center center", of: "#container" }
			// el "centro arriba" de mi cuadro de di�logo (my) , en el centro arriba (at) del contenedor (of)
		  }); */
		
		  
		// ========================================================================================
        // DEFINO tabs. LLAMO Pesta�as. Defino editor en zonaEscribir. Defino fecha
        // ========================================================================================
        
         $('#pesta�as').tabs();      
		 
		 // Cuadro de escritura 
		 $('#editor').editable({ // idioma tambi�n cargando el es.js 
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
					       if (indexAlumno>0) {
							  insertarOpinion(indexAlumno,getItemsElegidos());
						   } // Guarda o modifica los datos que sean..
					   },
				   onSelect: function (event) {
					   event.preventDefault;
					   $("#borrartodo").click(); // borra todos los items
					   $("#editor").editable("setHTML", "", true); // campo texto lo pone a cero
					   obtenerOpinion(indexAlumno);
				   },
		 }); 		 
		 $("#fecha").datepicker("setDate", fechaTrabajo); // pone la fecha de hoy
		 
		 // Listado de fechas
		 $("#listaFechas")
			.selectmenu({
				width:300, 
				style:'dropdown',
				select: function( event, ui ) {
					// Antes de terminar 
				    if (indexAlumno>0) {
					   insertarOpinion(indexAlumno,getItemsElegidos());
				    } // Guarda o modifica los datos que sean..
				    // alert($("#listaFechas option:selected").text());
					eligeFechaTrabajo(); // funci�n que elige la fecha de trabajo seg�n la selecci�n actual...
				    $("#borrartodo").click(); // borra todos los items
			    	$("#editor").editable("setHTML", "", true); // campo texto lo pone a cero
			    	$("#fecha").datepicker("setDate",fechaTrabajo); // pone la fecha escogida
    			    inicializaItems(); // Pone en pantalla el primer grupo de items...	    
			        obtenerOpinion(indexAlumno); // Obtiene la opini�n del alumno...	
				},
			})
			.selectmenu("menuWidget")
			   .addClass("overflow"); // carga un estilo que est� en /css/estiloSelectMenuOverflow.css

            
		// ========================================================================================
        // DEFINO EL MEN� DE LOS GRUPOS
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
		  	    eligeGrupoItems(); // funci�n que oculta los items menos los escogidos
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
					$(this).addClass("divalumno2"); // cambia la clase del divalumno...
					$(this).children("#image").css("background-size","90px 90px"); // tama�o imagen div
			  		$(this).children("#image").css("width","200px"); // tama�o imagen div
			        $(this).children("#image").css("height","125px"); // tama�o imagen div
			 		$(this).children("#texto").css("width","200px"); // tama�o letra
			        $(this).children("#texto").children("p").css("font-size","1.1em"); // tama�o letra			   
				});
			}, 
		    afterAction: function (elemento) { 
				// Antes de terminar 
				if (indexAlumno>0) {
					insertarOpinion(indexAlumno,getItemsElegidos());
				} // Guarda o modifica los datos que sean..
				// alert(fechaTrabajo);
				$("#borrartodo").click(); // borra todos los items
				$("#editor").editable("setHTML", "", true); // campo texto lo pone a cero
				$("#fecha").datepicker("setDate",fechaTrabajo); // pone la fecha escogida 
				// Obtienen el nuevo index...
			    var current = this.owl.currentItem;
                indexAlumno = elemento.find(".owl-item").eq(current).find(".divalumno").attr('id'); // Lo que funciona
                var owlNumber = elemento.find(".owl-item").eq(current).index(); // por �ndice
			    // alert(owlNumber);
			    $('#selectorAlumnos').val(owlNumber+1);
			    // Y obtener si la hubiera nueva opinion
			    $("#idalumno").val(indexAlumno); // cambia el id del alumno	
			    obtenerListaFechas(indexAlumno); // Particulariza la lista de fechas para este Alumno	
			    inicializaItems(); // Pone en pantalla el primer grupo de items...
			    // eligeFechaTrabajo(); // funci�n que elige la fecha de trabajo seg�n la selecci�n actual...	
			    // $("#fecha").datepicker("setDate",fechaTrabajo); // pone la fecha escogida 
			    // alert(fechaTrabajo);   
			    obtenerOpinion(indexAlumno); // Obtiene la opini�n del alumno...				
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
			    owl.trigger('owl.goTo',1000); // Se pone un n�mero grande que no se va a alcanzar...
			})				
		
		// ========================================================================================
        // DEFINO Zona de los Items
        // ========================================================================================
        $("#zonaItems .divitem").each(function() {
			$(this).hide(); // oculta al principio todos los items...
		});
		
		// ========================================================================================
        // DEFINO botones de �rdenes y sus funciones
        // ========================================================================================
        
         $('.ordenes').jqxButton({ theme: 'ui-sunny'} );   
         
         // Bot�n positivos del grupo
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
					} // Si es de la clase positivo y est� en la zona Items pasarlo a contenedorElegidas
					if ($(this).hasClass(secundario) && $(this).parent().attr("id")=="contenedorOpinionesElegidas") {
						$(this).hide().appendTo("#zonaItems");
						if ($(this).attr("grupo")==opcionMenu) { $(this).fadeIn("slow");}
					} // Si es de la otra clase y pertenece a las Elegidas, las oculta y las pone en la zonaItems
					// Y adem�s, si pertenecen al grupo elegido las muestra...
				});
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
			 width: 300,
			 height: 80,
			 tooltip: true,
			 mode: 'fixed',
		 });         

         $('#selectorAlumnos').on('change', function (event) {
              owl.trigger('owl.goTo',$(this).val()-1); // Del 1 al ultimo -1 es el �ndice
         });   
		
		// =======================================================================================
		// Eventos ONCLICK
		// =======================================================================================
		
         // al pulsar sobre alumno		
		 $(".divalumno").click(function(event){
			event.preventDefault();
			alert("Hola"); // debe disparar funciones que recuperen lo grabado...
		 });
		 // Ver esto: http://jsfiddle.net/adeneo/gdNue/10/
		 
		 // Al pulsar en el bot�n divitem
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
					return $(a).attr("id")-$(b).attr("id"); // para con n�meros operar con el menos "-" a - b
			 }).appendTo("#contenedorOpinionesElegidas"); //ordenamos los items
		 }); // fin de pulsar el bot�n divitem
		 
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
           appendTo: 'body', // lo a�ade al body 
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
					   return $(a).attr("id")-$(b).attr("id"); // para con n�meros operar con el menos "-" a - b
			   }).appendTo("#contenedorOpinionesElegidas");
		    }, // fin del drop
			out: function (event, ui) { // si saco un elemento de la selecci�n			   
			   var objeto = $(ui.draggable);
			   objeto.draggable( "option", "revert", false ); // No vuelve a su posici�n inicial...
			    if (objeto.attr("grupo")!=opcionMenu) {
					   objeto.hide(); // Si el grupo NO ES  el elegido en ese momento, lo oculta
					   // ya aparecer� cuando 
				} 
			   objeto.appendTo("#zonaItems"); //vuelve a la zona de los items
		    } // fin del out
		 });
	     
	     // ******************************************************
         // Rutinas DENTRO del Document Ready
         // ******************************************************
         function eligeGrupoItems() { // Peque�a rutina que sirve para mostrar/ocultar los divitem en la zona de elecci�n zonaItems
		 	$("#zonaItems .divitem").each(function() {					
				if ($(this).attr("grupo")==opcionMenu) {
					$(this).show(); // mostrar
				} else { $(this).hide(); }				
			});
		 } // fin de la funci�n
		 
		 // Funci�n que obtiene los items en la zona de elegidos
		 function getItemsElegidos() {
			 var cadena="";
			 $("#contenedorOpinionesElegidas").find(".divitem").sort(function(a,b){
					return $(a).attr("id")-$(b).attr("id"); // para con n�meros operar con el menos "-" a - b
			 }).appendTo("#contenedorOpinionesElegidas"); //ordenamos los items
			 $("#contenedorOpinionesElegidas .divitem").each(function(){
				 cadena=cadena+"#"+ $(this).attr("id"); // obtenemos la cadena...
			 });
			 return cadena.substr(1);
		 } // fin de la funci�n

		 // Funci�n que carga el primer grupo de Items por defecto
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
		 
		 // Elecci�n de una fecha de trabajo...
		 function eligeFechaTrabajo() {
				if ($("#listaFechas option:selected").attr("id")=="now") {
					// alert("Hoy");
					fechaTrabajo = new Date(); // caso que sea la de hoy
				} else if ($("#listaFechas option:selected").attr("id")=="last") {					
					fechaTrabajo = convertirFecha($("#listaFechas").find("#last").val());
				} else {
					fechaTrabajo =  convertirFecha($("#listaFechas option:selected").text());
				}							
		 }
			
		 // *****************************************************************************
		 // Condiciones de CARGA AL INICIO, cuando ya todo se ha ejecutado... ��Pues s�!!
		 // *****************************************************************************
		 // Al inicio elige una opci�n del grupo Items
         inicializaItems();
			
	 }); // fin del document ready
	 
	 // ******************************************************
	 // Funciones en la p�gina *******************************
	 // ******************************************************
	 
	 //************************** 
	 // F1) Graba/modifica una opinion...
	 function insertarOpinion(idAlumno,cadenaItems) {
		     // alert($("#editor").editable("getHTML", false, false)); // testeo c�mo se env�a el HTML del campo de escritura
		     // Detectar dato en blanco...
		     // if (cadenaItems=="" && $("#editor").editable("getText")=="") {
			 // }
			 var posting = $.post( "./opiniones/scripts/insertarOpinion.php", { 
				  fecha: $("#muestrafecha").val(), // el campo oculto, que tiene la fecha formateada tipo MySQL
				  alumno: idAlumno, // La variable de sesi�n de la asignaci�n se consigue en el script.
				  items: cadenaItems,
				  observaciones: $("#editor").editable("getHTML", false, false), // ver FROALA methods
				  idasignacion: $("#idasignacion").val(),
				  });
			  posting.done(function(data,status) { 
				  // alert(data);
				  var datos = jQuery.parseJSON(data);
				  if (datos.devolver==1)  { $("#notificacionGuardado").jqxNotification("open"); }
				  if (datos.devolver==2) { $("#notificacionModificar").jqxNotification("open"); }
				  if (datos.devolver==3)  { $("#notificacionBorrar").jqxNotification("open"); }
				  // alert(datos.observaciones);
				  // No hay que hacer nada...
				  // alert(data)...
			  }); 
	  } // Fin de la funci�n grabar/modifica dato...
	  
	 //************************** 
	 // F2) Obtener opinion
	 function obtenerOpinion(idAlumno) {
		 // alert(idAlumno);
		 // alert($("#muestrafecha").val());
		 // alert($("#idasignacion").val());
		 var posting = $.post( "./opiniones/scripts/obtenerOpinion.php", { 
			  fecha: $("#muestrafecha").val(), // el campo oculto, que tiene la fecha formateada tipo MySQL
			  alumno: idAlumno, // La variable de sesi�n de la asignaci�n se consigue en el script.
			  idasignacion: $("#idasignacion").val(),
		      });
		  posting.done(function(data,status) { 
			  // alert(data);
			  var datos = jQuery.parseJSON(data); // recupera en el array datos los valores
			  $("#editor").editable("setHTML", datos.observaciones, true); // obtiene las observaciones...
			  var items=datos.items.split("#"); // obtiene un array con los items...
			  // los items tienen que venir ya borrados... llamada a la funci�n borrar todo...
			  // alert(datos.items);
			  $(".divitem").each(function(){
					if (jQuery.inArray($(this).attr("id"),items)>=0) {
						$(this).show().appendTo("#contenedorOpinionesElegidas"); // agrega a elegidas...
					}	
			  }); 			  
			  // $("#idasignacion").val(data); // aunque lo pongo, defino una variable de sesi�n y lo vuelvo a cargar...
			  // Pulsar escoger TAB
			  // location.reload(); // recarga la p�gina de la asignaci�n
		  }); 
	  } // Fin de la funci�n Obtener dato
	  
	 //************************************
	 // F3) fechas de un alumno - asignaci�n
	 function obtenerListaFechas(idAlumno) {
		 // alert("S�, llego aqu�");
		 var posting = $.post( "./opiniones/scripts/obtenerFechas.php", { 
			  alumno: idAlumno, // La variable de sesi�n de la asignaci�n se consigue en el script.
			  idasignacion: $("#idasignacion").val(),
		      });
		  posting.done(function(data,status) { 
			  // alert(data);
			  var datos = jQuery.parseJSON(data);
			  $("#listaFechas").find("#last").val(datos.last); // dato de la �ltima fecha en formato DatePicker
			  // alert($("#listaFechas").find("#last").val());
			  $(".fechasListado").each(function(){ // por cada elemento de esta clase...
				  if($.inArray($(this).attr("id"),datos.fecha)>=0) {
					  $(this).attr('disabled',false);
					  // alert("S� "+$(this).attr("id"));					  
				  } else {
					  $(this).attr('disabled',true);
					  // alert("No "+$(this).attr("id"));	
				  }	  
			  }); 
			  $("#listaFechas").selectmenu( "refresh" ); // ���Dios dos d�as hasta conseguir �sto !!
		  });  
	  } // Fin de la funci�n Obtener fechas de un alumno - asignaci�n

     // ************************************
	 // F4) Establece variable de sesi�n de idalumno
	 function setVariableSesionAlumno(idAlumno) {
		  var posting = $.post( "./opiniones/scripts/setVariableSesionAlumno.php", { 
			  idalumno: idAlumno, // La variable de sesi�n de la asignaci�n se consigue en el script.
			  });
		  posting.done(function(data,status) { 
			  alert(data);
			  location.reload();
		  });
	 } // Fin de la funci�n de obtener 
	  
 <!-- * =======================================================================================================   * --> 	

  </script>
  
<!-- **************************************************************************************** -->
</body></html>
