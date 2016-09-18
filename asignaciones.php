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

// incluyo las variables de esas clases
$calendario = New micalendario(); // variable de calendario. Lo necesito para la cabecera
$profesorado = New profesores(); //variable de la clase profesores
// $formulario = New formulario(); // variable de la clase formulario
$curso = New misCursos(); // variable de la clase curso
$alumno = New misAlumnos(); // variable de la clase alumnos
$materia = New misMaterias(); // variable de la clase materia
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
    $curso->listarNiveles(); // ya incluye el $curso->listarCursos... Genera los cursos y los niveles en esa variable. 
                             // Establecidas las variables $curso->listaDeCursos y $curso->ListaDeNiveles   
    $asignacion->listarAsignaciones($_SESSION["profesor"]); // incluye ya todas las asignaciones de ese profesor
                             // En la variable $listaDeAsignaciones;
    if ($_SESSION['idasignacion']>0) { // en caso de que exista y sea mayor que cero la variable de sesi�n de asignaci�n
		// echo $_SESSION["idasignacion"];
		$asignacion->retiraHuerfanos($_SESSION["idasignacion"]); // Retira opiniones hu�rfanas.
	}
    ?>    
    <!-- *********************************************************** -->
     
	<div id="test"> <!-- TESTER -->
	    <p id="testear">
			<?php // echo 'Imprime variable de sesi�n '.$_SESSION["idasignacion"]." - ".$_SESSION["profesor"]; 
				// foreach ($curso->listaDeCursos["corto"] as $clave=>$valor) {
				// 	echo $clave." - ".$valor.'</br>';
					// echo $curso->listaDeNiveles["input"][$clave]."&nbsp;&nbsp;";
				// }
			?>
	    </p>
    </div>	<!-- TESTER -->
    
    <!-- ********************************************************** -->
    <!-- Contenido Principal -->
    <!-- ********************************************************** --> 
    
    <div id="contents"> <!-- &&&& --> 	  	   
	    <div id="pesta�as">
			<ul>
				<li><a href="#eligeAsignacion">Elige Asignaci�n</a></li>
				<li><a href="#nuevaAsignacion">Nueva Asignaci�n</a></li>
				<li><a href="#instrucciones">Instrucciones</a></li>
			</ul>
			
<!-- * =======================================================================================================   * --> 		
			<!-- ********************************************************** --> 
			<!-- Elige asignaci�n -->
			<div id="eligeAsignacion"> 
				<input id="token" style="display: none;" value="<?php echo $_SESSION["token"]; ?>">
				<!-- // token evita ataques CSRF https://www.funcion13.com/preven-falsificacion-peticion-sitios-cruzados-csrf/ 
				Recupera en el input el token en la primera p�gina -->
				<?php 
				foreach ($asignacion->listaDeAsignaciones["idasignacion"] as $clave => $valor) { 
				  echo $asignacion->listaDeAsignaciones["div"][$clave]; // incluye el div que viene en class.asignaciones.php
				}
				?>
			</div>
			<!-- ********************************************************** --> 
			
<!-- * =======================================================================================================   * --> 		
			<!-- Crear nueva asignaci�n -->
			<div id="nuevaAsignacion"> 
				<div id="zonaSeleccionable"> <!-- A) Donde se selecciona -->
					
					<div id="zonaDatos">
						<h3>Elige una materia y si eres o no tutor/a</h3>
						<?php // Select para seleccionar materias
						$materia->listaMaterias();
						echo '<div id="materias" title="Escoge la materia de la asignaci�n">';
						echo '<select name="Escoger" id="Escoger" class="seleccionaidmateria">';
						foreach($materia->listaDeMaterias["idmateria"] as $clave => $valor) {
							echo '<option value="'.$valor.'">'.$materia->listaDeMaterias["materia"][$clave].'</option>';
						}
						echo '</select>';
						echo '</div>';
						echo '<div id="tutoria" title="Elige si eres profesor normal o tutor"></div>'; // Con jqx-switch button... 
						  // Si es tutor o no... http://www.jqwidgets.com/jquery-widgets-demo/demos/jqxbutton/index.htm#demos/jqxbutton/switchbutton.htm
						echo '<input id="idasignacion" type="hidden" value="'.$_SESSION["idasignacion"].'"></input>';
						?>
					</div>
					
					<div id="zonaCursosContenedor">
						<div id="zonaNiveles">
							<h3>Elige un curso</h3>
							<?php // Posiciona los niveles 
							foreach ($curso->listaDeNiveles["nivel"] as $clave=>$valor) {
								// echo $clave." - ".$valor;
								if (!is_null($valor) and !empty($valor)) {echo $curso->listaDeNiveles["input"][$clave]."&nbsp;&nbsp;";}
							}
							?>
						</div>
						<div id="zonaCursos">
							<?php // Posiciona los cursos 
							foreach ($curso->listaDeCursos["curso"] as $clave => $valor) {
								$curso->devuelveCurso($clave);
								echo $curso->esteCurso["div"];
							}
							?>
						</div>
					</div>
					
					<div id="zonaAlumnos">
							<h3>Elige alumnado</h3>
					</div>

					
				</div> <!-- &&&& -->
				
			    <div id="zonaSeleccion" class="effect7"> <!-- B) Donde est� la selecci�n -->
					<h3>Selecci�n</h3><h4>Arrastra cursos o alumnos y su�ltalos aqu�</h4></br>
					<div id="zonagrabar" style="clear: both; margin-top: 20px;"> <!-- C) Zona grabar, con el bot�n -->
						<input type="button" value="Grabar Asignaci�n" id='Grabar'/></br></br>
						<input type="button" value="Cancelar" id='Cancelar'/>
						<!-- http://www.jqwidgets.com/jquery-widgets-demo/demos/jqxbutton/index.htm#demos/jqxbutton/defaultfunctionality.htm -->
					</div>
			    </div> <!-- &&&& -->
			    
			</div> <!-- Fin de crear nueva asignaci�n -->
			<!-- ********************************************************** --> 
			
			<div id="instrucciones">
				<p style="text-align: center; margin: 40px;"><iframe width="800" height="500" src="https://www.youtube.com/embed/NKlwwFvMwiA" frameborder="0" allowfullscreen></iframe></p>
			</div>
			
		</div> <!-- &&&& FIN DE LAS PESTA�AS-->
		
<!-- * =======================================================================================================   * --> 	

<!-- * ==========================  DIALOGOS   =====================================   * --> 			
		
		<!-- Dialogo de Confirmaci�n de grabar los datos -->	
		<div id="dialog-confirm" title="Grabar datos">
		   <p><span class="fa fa-spinner fa-pulse fa-2x" style="float:left; margin:0 7px 20px 0;">
		   </span>
		   �De verdad quieres grabar esta Asignaci�n?</p>
		</div>
		
		<!-- Dialogo de Confirmaci�n de modificar los datos -->	
		<div id="dialog-confirm-modificar" title="Modificar datos">
		   <p><span class="fa fa-edit fa-2x" style="float:left; margin:0 7px 20px 0;">
		   </span>
		   �De verdad quieres modificar esta Asignaci�n?<span class="hoverAsignacion"></span>
		   </p>
		</div>
		
		<!-- Dialogo de Confirmaci�n de borrar los datos -->	
		<div id="dialog-confirm-borrar" title="Borrar datos">
		   <p><span class="fa fa-trash fa-2x" style="float:left; margin:0 7px 20px 0;">
		   </span>
		   �De verdad quieres borrar esta Asignaci�n? Deber�as cancelar y obtener un PDF de las opiniones y opiniones generales referidas
		   a esta asignaci�n, pues se borrar�n a su vez.<span class="hoverAsignacion"></span>
		   </p>
		</div>
		
		<!-- Dialogo de Alerta que no hay datos -->	
		<div id="dialog-confirm-nohaydatos" title="No hay datos">
		   <p><span class="fa fa-exclamation-triangle fa-2x" style="float:left; margin:0 7px 20px 0;">
		   </span>
		   En este di�logo no hay datos...<span class="hoverAsignacion"></span>
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
  
  <!-- <script src="//code.jquery.com/jquery-1.11.2.js"></script> -->
  <script src="./jquery/external/jquery/jquery.js"></script>
  
  <script src="./jquery/jquery-ui.min.js"></script> <!-- version 1.11.2 -->
  <!-- <script src="//code.jquery.com/ui/1.11.0/jquery-ui.min.js"></script> <!-- version 1.11.0 -->
  
  <!-- <script src="./htmlsuelto/js_menu.js"></script>   Incorpora al script los men�s a la izquierda -->  
  <script type="text/javascript" src="./jquery/jqx/jqxcore.js"></script>
  <script type="text/javascript" src="./jquery/jqx/jqx-all.js"></script>
  

  
  <script>
     
     
     $(document).ready(function() {  		 
		 
		 // Versiones de JQUERY y JQUERY-UI
		 // alert($().jquery);
	     // alert($.ui.version);	
	     
	    // Variables globales       		 

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
		
		// 1c) Definici�n del di�logo de confirmaci�n de grabar datos, borrar y modificar asignacion y confirmar que no hay datos
		  $("#dialog-confirm, #dialog-confirm-modificar, #dialog-confirm-borrar, #dialog-confirm-nohaydatos").dialog({
			autoOpen: false,
			modal: true,
			maxWidth:1000,
            maxHeight: 600,
            width: 1000,
            height: 600,
			position: { my: "center center-100", at: "center center", of: "#container" }
			// el "centro arriba" de mi cuadro de di�logo (my) , en el centro arriba (at) del contenedor (of)
		 });
		  
		  $("#dialog-confirm").dialog({ // especifico altura para el tercero
			maxHeight: 300,
            height: 300,
		  });
		   
		  $("#dialog-confirm-nohaydatos").dialog({ // especifico altura para el tercero
			maxHeight: 400,
            height: 400,
		  });
  
		  
		// 2) Men� de pesta�as
		$("#pesta�as").tabs({
			active: 0,
			activate: function(event, ui){ //detecta la pesta�a pulsada...
				if(ui.newTab.index()=='1') { // Si se pulsa la pesta�a 1... ("NUEVA ASIGNACI�N").
					$("#idasignacion").val(0);
					$("#pesta�as").tabs("disable", 0); // desactiva la pesta�a 0
				}
			},
		}); // Active: pesta�a que se activa por defecto... 
		
		// 3) Niveles: tipo Toggle botton. Ver como son los elementos input en class.cursos.php
		$("#zonaNiveles").buttonset();
		
		// 3b) Botones de tutor�a
		$("#tutoria").buttonset(); // escribo los inputs directamente
		
		// 4) Filtrar los datos de cursos al pulsar un bot�n de nivel
		$("#zonaNiveles :radio").click(function(e) {
			// alert($(this).attr("id"));
			var nivel = $(this).attr("id");
			$(".divcurso").each(function() {
				var clase = $(this).attr("id"); // a�ade que si SU PADRE es el div zonaCursos
				if (clase.toUpperCase().indexOf(nivel)>=0) {
					// alert("Mostrar");
					$(this).show();
				} else { 
					// alert("Ocultar"); 
					$(this).hide();
				} 				
				
				if ($(this).parent().attr("id")=="zonaSeleccion") {
					$(this).show(); // los que est�n en la zona selecci�n los muestra
				}
			}); // fin del each			
		});
		
		$("#ESO").click(); // Si existe, lo refresca, y lo activa al principio...
		
		// 5) Al pulsar sobre un curso, elige a los alumnos...		
		$(".divcurso").click(function(e) {
			// alert($(this).attr("alumnado"));
			rellenarZonaAlumnos($(this).attr("alumnado"));
		});
		
		// 5a) Convierte los ".divcurso" en draggable...
		$(".divcurso").draggable({
		   containment: "#nuevaAsignacion", // contenedor donde puede moverse
		   stack: "#nuevaAsignacion", // pone su z-index por encima de todos los de esa zona
		   helper: 'clone', // mueve una copia del elemento
		   revert: 'invalid', // vuelve a su lugar original si no puedo ponerlo sobre la zona objetivo
           appendTo: 'body', // lo a�ade al body 
           cursor: "move",		   
		}); // los divalumno hay que convertirlos en tiempo de ejecuci�n, cuando se llaman. Verlo en function rellenarZonaAlumnos
		
		// ********************************************************************
		// 5b) Convierte la zona seleccion en droppable, y asigna las funciones
		$("#zonaSeleccion").droppable({  
			// var factor = 0,8;
			// ************************************************
			// A) Introduzco elementos en la zona seleccionable. 
			// ************************************************     
		    drop: function (event, ui) {
			   var estilo = ui.draggable.attr("class"); // obtengo la clase, para distinguirlo
			   $("#zonaSeleccion").append(ui.draggable);
			   // ordenando los elementos de la lista
			   $("#zonaSeleccion").find(".divcurso").sort(function(a,b){
					   return $(a).attr("id")>$(b).attr("id"); // esto ordena por orden alfab�tico, no por n�meros.
			   }).appendTo("#zonaSeleccion");
			   $("#zonaSeleccion").find(".divalumno").sort(function(a,b){
					   // return $(a).attr("name")>$(b).attr("name"); 
					   return $(a).attr("name").localeCompare($(b).attr("name")); 
					   // En la lista de selecci�n mejor ordenar alfab�ticamente, ya que puede haber alumnos mezclados...
					   // atributo NAME contiene el nombre completo del alumno como Apellidos, Nombre.
			   }).appendTo("#zonaSeleccion");
			   // Cambiar tama�o de las cajas de  los alumnos
			   if (estilo.indexOf("divalumno")>=0) { // S�lo afecta a los alumnos
			   ui.draggable.children("#texto").css("width","107px"); // tama�o letra
			   ui.draggable.children("#texto").children("p").css("font-size","0.7em"); // tama�o letra
			   ui.draggable.children("#image").css("background-size","40px 40px"); // tama�o imagen div
			   ui.draggable.children("#image").css("width","107px"); // tama�o imagen div
			   ui.draggable.children("#image").css("height","55px"); // tama�o imagen div
			   ui.draggable.animate({"width":"-=20px"},{"height":"-=20px"},"slow");	
		       }	   
			   // alert(ui.draggable.children("#texto").children("p").html());
			   // asigna valores a una propiedad de zonaSeleccion
			   $("#zonagrabar").appendTo("#zonaSeleccion"); // pone el bot�n al final
               // asignaValores(); 
			},
			// ****************************************************
			// B) Para eliminar elementos en la zona seleccionable.
			// ****************************************************
			out: function (event, ui) { // si saco un elemento de la selecci�n
			   var estilo = ui.draggable.attr("class"); // obtengo la clase, para distinguirlo
			   var objeto = $(ui.draggable);
			   objeto.draggable( "option", "revert", false );
			   // ====================================
			   // Si el objeto es de la clase divcurso
			   // ====================================
			   if (estilo.indexOf("divcurso")>=0) {		   
				   objeto.appendTo("#zonaCursos"); // lo asimilo otra vez a la zona de Cursos.				   
				   if (objeto.attr("nivel")!=$("#zonaNiveles :radio:checked").attr("id")) {
					   // En el caso de que sean distintos el nivel, atributo de curso, y el bot�n pulsado
					   objeto.hide(); // as� "desaparece" de la selecci�n y tampoco est� en la lista de cursos.
					   // alert("Lo he ocultado CURSO");
				   } // esto permite, por ejemplo, que un curso de ESO no aparezca en la lista BACHILLERATO,
				   // pero cuando se vuelva a pulsar el bot�n, aparecer� en su lista, no en la selecci�n
                   // ordenar los divs ��Eureka!! (alfab�ticamente)
                   $("#zonaCursos").find(".divcurso").sort(function(a,b){
					   return $(a).attr("id")>$(b).attr("id"); // esto ordena por orden alfab�tico, no por n�meros.
				   }).appendTo("#zonaCursos");
			   // =============================
			   // Y si es de la clase divalumno
			   // =============================
			   } else if (estilo.indexOf("divalumno")>=0) {
				   objeto.appendTo("#zonaAlumnos"); // lo asimilo otra vez a la zona de Alumnos.
				   if (objeto.attr("unidad")!=$('#zonaAlumnos').find('.divalumno').attr("unidad")) {
					   // En el caso de que sean distintos, lo oculto
					   objeto.hide(); // as� "desaparece" de la selecci�n y tampoco est� en la lista de alumnos.
					   // alert("Lo he ocultado");
				   } // esto permite, por ejemplo, que un alumno de 1�ESOC no aparezca en la lista de los de 1�ESOA.				   
                   // ordenar los divs ��Eureka!! (por n�meros)
                   $("#zonaAlumnos").find(".divalumno").sort(function(a,b){
					   // return $(a).attr("orden")-$(b).attr("orden"); // para con n�meros operar con el menos "-" a - b
					   return $(a).attr("id")-$(b).attr("id"); // para con n�meros operar con el menos "-" a - b
				   }).appendTo("#zonaAlumnos");
			   // Cambiar tama�o de las cajas de  los alumnos
			   objeto.children("#texto").css("width","127px"); // tama�o letra
			   objeto.children("#texto").children("p").css("font-size","0.8em"); // tama�o letra
			   objeto.children("#image").css("background-size","50px 50px"); // tama�o imagen div
			   objeto.children("#image").css("width","127px"); // tama�o imagen div
			   objeto.children("#image").css("height","75px"); // tama�o imagen div
			   objeto.animate({"width":"+=20px"},{"height":"+=20px"},"slow");	
			   } // Fin si es un objeto de la zona alumnos
			   
			   // asigna valores a una propiedad de zonaSeleccion
               // asignaValores();		   
			} // Fin del out.
			// ************************************************
        });        
	
		// 6) Activa los ToolTIP en el documento
	    $.getScript( "./htmlsuelto/js_tooltips.js", function( data, textStatus, jqxhr ) {
		console.log( data ); // Data returned
		console.log( textStatus ); // Success
		console.log( jqxhr.status ); // 200
		console.log( "Load was performed." );
		}); 	
	
	    //7) Definici�n del SELECT de materias
    	$( "#Escoger" )
			.selectmenu({width:600, style:'dropdown'})
			.selectmenu("menuWidget")
			   .addClass("overflow"); // carga un estilo que est� en /css/estiloSelectMenuOverflow.css

	    //8) Bot�n para el SwitchButton de tutor�a
            // var label = {'tutoria': 'Tutor' };
            $('#tutoria').jqxSwitchButton({ 
				height: 54, 
				width: 160,  
				checked: false, 
				theme:'ui-sunny',
				onLabel:'Tutor/a',
				offLabel:'Prof./a',
				// rtl: true, // de derecha a izquierda
				// orientation: 'vertical'
			});
            
          //9) Bot�n de grabar
          $("#Grabar").jqxButton({
			  width: '260',
			  theme: 'ui-sunny',
			  template: 'primary',
		  });
		  
		   $("#Grabar").click(function(event) {
			   event.preventDefault();
			   // incluye informaci�n...			   
			   $("#dialog-confirm").dialog({
			    buttons : {
				"S�, Graba" : function() {
				  // alert("dato grabado...");
				  $(this).dialog("close");
				  asignaValores(); // activa la asignaci�n de valores
			      insertarAsignacion(); // inserta la asignaci�n. Implica recargar la p�gina y cerrar el di�logo		      
			    },
				"No,no... Cancela" : function() {
				  $(this).dialog("close"); // no recarga la p�gina. Simplemente anula la operaci�n y sigue				 
			    }
			} // fin del buttons
            }); // fin del dialog
            
            $("#dialog-confirm").dialog("open"); // si no, no lo abre

		   }); // Fin de grabar onclick
		   
		  //9BIS) Bot�n de cancelar 
          $("#Cancelar").jqxButton({
			  width: '260',
			  theme: 'ui-sunny',
			  template: 'primary',
		  });
		  
		  $("#Cancelar").click(function(event) {
			   event.preventDefault();
			   location.reload();
		  });
<!-- * =======================================================================================================   * --> 
		   
		   // 10) Botones para elegir la asignacion
		   $(".divasignacionbutton").jqxButton({
			  width: '80',
			  theme: 'ui-sunny',
			  template: 'info',
		  });
		  
		  $(".divasignacionbutton").on('click', function () { // botones del div de asignaciones
				// alert($(this).attr('id'));
				var colorinicial;
				colorinicial = $(this).parent().parent().css("background"); // dos atr�s...
				$(".divasignacion").each(function(){
					$(this).css("background", colorinicial);
				});
				$(this).parent().parent().css("background","#908cdb"); // color complementario a #82a3c3
				// Asigna la variable de sesi�n
				variablesesionAsignacion($(this).attr('id'),$("#token").val());
		  });		  
		   
		  // 11BIS) Escoge la asignaci�n elegida al cargar la p�gina...
		   if ($("#idasignacion").val()>0) {
			  // A) Pone el div correspondiente en el color adecuado
			  // alert($("#idasignacion").val());
			  // http://api.jquery.com/category/selectors/attribute-selectors/
			  // http://stackoverflow.com/questions/6131119/jquery-attribute-selector-variable
			  var sessionidasignacion = $("#idasignacion").val();
			  $('.divasignacion[id='+sessionidasignacion+']').css("background", "#908cdb");   
		   }
		   
		  // 12) Onclick sobre un bot�n de editar
		  $(".editar").click(function(){
			    var objeto = $(this);
			    $("#dialog-confirm-modificar").dialog({
					buttons : {
						"S�, Modifica" : function() {
						$(this).dialog("close");
						// alert($(this).attr("id"));
						// $("#pesta�as").tabs("enable",1); // activa la pesta�a 1
						$('#pesta�as ul:first li:eq(1) a').text("Modificar Asignaci�n");
						$("#pesta�as").tabs({active: 1}); // y la activa	
						$("#pesta�as").tabs("disable", 0); // desactiva la pesta�a 0	
						$("#idasignacion").val(objeto.attr("id")); // pone el identificador en el input idasignacion	  
						// alert("dato grabado...");
						// B) En la zona seleccion, coloca los elementos del divasignacion propiedad corto.
						// Esto s� que va a ser dif�cil...
						// var datos = $('.divasignacion[id='+sessionidasignacion+']').attr("corto");
						var datos = $('.divasignacion[id='+objeto.attr("id")+']').attr("corto");
						// alert(datos);
						var arrayDatos = datos.split("#"); // obtiene el array con los datos
						// B1) Obtiene el alumnado...
						var cadena = [];
						$.each(arrayDatos,function(index,value){
							 if (value>0) { cadena.push(value); } // Si son num�ricos y no del tipo 1ESOC...
						});
						// B2) Los escribe en la zona alumnos...
						var cadenaString = cadena.join("#");
						// rellenarZonaSeleccionAlumnos(cadenaString); // rellena la zona Seleccion con esos datos...	
						// Esta modificaci�n de la funci�n permite rellenar la zona Seleccion con los datos.		  
						// B3) Mueve los cursos que ya est�n a la zona selecci�n. Se supone que est�n ordenados...
						$(".divcurso").each(function(e,ui){
						 if(jQuery.inArray($(this).attr("id"),arrayDatos) >=0) {
							 // alert($(this).attr("id"));
							 // $("#zonaSeleccion").append($(this)); // Simlemente los traspasa a la otra zona...	
							 $(this).appendTo($("#zonaSeleccion"));		
						 }
						}); 
						// C) Cambiar la asignaci�n o no de tutor�a
						var tutor = objeto.parent().parent().children(".divasignaciontutor").attr("id");
						if (tutor=='1') { // Solo funciona casi defini�ndolo de nuevo... 
							$("#tutoria").jqxSwitchButton({
							checked: true,
							onLabel:'Tutor/a',
							offLabel:'Prof./a', 
							}); // Uff que aut�ntico co�azo dar con las propiedades correctas de cada botoncito...
						}
						
						// D) Cambiar la materia o asignatura
						// alert($(this).parent().parent().children(".divasignacionmateria").attr("id"));
						var escogerMateria = objeto.parent().parent().children(".divasignacionmateria").attr("id");
						$("#Escoger").val(escogerMateria);
						$("#Escoger").selectmenu("refresh"); // Uff! hay que llamar al m�todo refresh...
						 
						$("#zonagrabar").appendTo("#zonaSeleccion"); // pone el bot�n al final 
						rellenarZonaSeleccionAlumnos(cadenaString); // rellena la zona Seleccion con esos datos...
						// No me ha resultado posible hacerlo de otra forma. Parece que hay que hacerlo todo "dentro" 
  					    // de la llamada, y, por tanto hay que copiar c�digo en rellenarZonaSeleccionAlumnos   
						},
						"No,no... Cancela" : function() {
						  $(this).dialog("close");	
						} ,
					}, // fin de buttons
			    }); // fin de dialog-confirm-modificar
			    
			    $(".hoverAsignacion").html("</br></br>"+objeto.parent().parent().attr("descripcion"));
			    $("#dialog-confirm-modificar").dialog("open"); // si no, no lo abre
			    
		  }); // fin de pulsar sobre editar
		  
		  //13) Pulso sobre borrar un dato
		  $(".borrar").click(function(){
			  var objeto = $(this);
			 	$("#dialog-confirm-borrar").dialog({
					buttons : {
						"S�, Borrar" : function() {
							borrarAsignacion(objeto.attr("id"));
							$(this).dialog("close");
						},
						"No,no... Cancela" : function() {
							$(this).dialog("close");
						},
					}
				});	
				
				$(".hoverAsignacion").html("</br></br>"+objeto.parent().parent().attr("descripcion"));
				$("#dialog-confirm-borrar").dialog("open"); // si no, no lo abre					
		  });
		  
		  
			
	 }); // fin del document ready
	 
	 // ******************************************************
	 // Funciones en la p�gina *******************************
	 // ******************************************************
	 
	 // F1) Alumnos elegidos: rellenar zonaAlumnos
	 function rellenarZonaAlumnos(puntero) {
				 var posting = $.post( "./asignaciones/scripts/rellenarZonaAlumnos.php", { 
					  lee: puntero,
				  });
				  posting.done(function(data,status) { 
					  // alert(data);
					  $("#zonaAlumnos").html(data); 
					  $(".divalumno").each(function(){
						  $(this).draggable ({
							  containment: "#nuevaAsignacion", // contenedor donde puede moverse
							  stack: "#nuevaAsignacion", // pone su z-index por encima de todos los de esa zona
							  helper: 'clone', // mueve una copia del elemento
							  revert: 'invalid', // vuelve a su lugar original si no puedo ponerlo sobre la zona objetivo
							  appendTo: 'body', // lo a�ade al body 
							  cursor: "move",
						  });
					  });
					  // Comprueba si hay en zona selecci�n y los retira de la zona alumnos
					  $("#zonaSeleccion .divalumno").each(function(){
						  var seleccionado =$(this).attr("id");
						  $("#zonaAlumnos .divalumno").each(function(){
							  if ($(this).attr("id")==seleccionado) {
								  $(this).remove();
							  }
						  }); 
					  });
				  });
	 } /* FIN DE FUNCION */	 
	 
	 // F1bis) Alumnos elegidos: rellenar zonaSeleccion
	 function rellenarZonaSeleccionAlumnos(puntero) {
				 var posting = $.post( "./asignaciones/scripts/rellenarZonaAlumnos.php", { 
					  lee: puntero,
				  });
				  posting.done(function(data,status) { 
					  // alert(data);
					  $("#zonaSeleccion").append(data); 
					  $("#zonaSeleccion h3").remove();
					  $("#zonaSeleccion .divalumno").each(function(){
						  $(this).draggable ({
							  containment: "#nuevaAsignacion", // contenedor donde puede moverse
							  stack: "#nuevaAsignacion", // pone su z-index por encima de todos los de esa zona
							  helper: 'clone', // mueve una copia del elemento
							  revert: 'invalid', // vuelve a su lugar original si no puedo ponerlo sobre la zona objetivo
							  appendTo: 'body', // lo a�ade al body 
							  cursor: "move",
						  });
						 $(this).children("#texto").css("width","107px"); // tama�o letra
						 $(this).children("#texto").children("p").css("font-size","0.7em"); // tama�o letra
						 $(this).children("#image").css("background-size","40px 40px"); // tama�o imagen div
						 $(this).children("#image").css("width","107px"); // tama�o imagen div
						 $(this).children("#image").css("height","55px"); // tama�o imagen div
						 $(this).animate({"width":"-=20px"},{"height":"-=20px"},"slow");
					  });
					  $("#zonagrabar").appendTo("#zonaSeleccion"); // pone el bot�n al final 
				  });
	 } /* FIN DE FUNCION */	 
 
	 // F2) Asigna valores a la zona seleccion
	 function asignaValores() {
	   var alumnado="";
	   // Asigna a la variable alumnado cada curso...
	   $("#zonaSeleccion").find(".divcurso").each(function() {
		  alumnado = alumnado + $(this).attr("id")+"#"; 
	   });
	   // Asigna a la variable alumnado la cadena de IDs de cada alumno
	   $("#zonaSeleccion").find(".divalumno").each(function() {
		  alumnado = alumnado + $(this).attr("id")+"#"; 
	   });
	   // Quita la �ltima almohadilla
	   alumnado = alumnado.substring(0,alumnado.length-1);
	   // A la zona selecion le asigna el par�metro seleccion como un atributo m�s
	   $("#zonaSeleccion").attr("seleccion",alumnado);
	   // alert($("#zonaSeleccion").attr("seleccion"));
     } /* FIN DE FUNCION */
     
     // F3) Grabar asignaci�n
	 function insertarAsignacion() {
				 var datos = $("#zonaSeleccion").attr("seleccion");
				 
				 if (!(datos.length)) { 
					 // alert("No hay datos");
					 $("#dialog-confirm-nohaydatos").dialog({
				  	 buttons : {
						 "�Upss! S�, ahora introduzco datos" : function() {
						    $(this).dialog("close");
						 },
						  "Cancela. No quiero grabar datos": function() {
							location.reload();  
						  },
				     } // fin del buttons
					 }); // fin del dialog            
                     $("#dialog-confirm-nohaydatos").dialog("open"); // si no, no lo abre 
                                                      
				 }	// fin del if que comprueba que no ha ydatos	
				 else {		 
				 // grabar
				 var posting = $.post( "./asignaciones/scripts/insertarAsignacion.php", { 
					  idasignacion: $("#idasignacion").val(),
					  tutor: $("#tutoria").jqxSwitchButton('checked'),
					  asignacion: $("#zonaSeleccion").attr("seleccion"),
					  materia: $("#Escoger option:selected").val(),
					  nombremateria:  $("#Escoger option:selected").text(),
				  });
				  posting.done(function(data,status) { 
					  // alert(data);
					  $("#idasignacion").val(data); // aunque lo pongo, defino una variable de sesi�n y lo vuelvo a cargar...
					  // Pulsar escoger TAB
					  location.reload(); // recarga la p�gina de la asignaci�n
				  });
				  
			  } // fin del if que comprueba que no ha ydatos
	  }

 <!-- * =======================================================================================================   * --> 
	  
	  // F4) Asignar a la variable de sesi�n
	   function variablesesionAsignacion(valor,token) { // token, para evitar ataques CSRF
				 var posting = $.post( "./asignaciones/scripts/variableAsignacion.php", { 
					  lee: valor,
					  token: token, // token evita ataques CSRF https://www.funcion13.com/preven-falsificacion-peticion-sitios-cruzados-csrf/
					  // pasa el valor del token por POST a variableAsignacion
				  });
				  posting.done(function(data,status) { 
					  // alert(data);
					  $("#idasignacion").val(data); // aunque lo pongo, defino una variable de sesi�n y lo vuelvo a cargar...
					  // Pulsar escoger TAB
					  location.reload(); // recarga la p�gina de la asignaci�n
				  });
	   }
	   
	   // F5) Borrar asignacion
	   function borrarAsignacion(valor) {
				 var posting = $.post( "./asignaciones/scripts/borrarAsignacion.php", { 
					  lee: valor,
				  });
				  posting.done(function(data,status) { 
					  // alert(data);
					  // $("#idasignacion").val(data); // aunque lo pongo, defino una variable de sesi�n y lo vuelvo a cargar...
					  // Pulsar escoger TAB
					  location.reload(); // recarga la p�gina de la asignaci�n
				  });
	   }
	  
 <!-- * =======================================================================================================   * --> 	

  </script>
  
<!-- **************************************************************************************** -->
</body></html>
