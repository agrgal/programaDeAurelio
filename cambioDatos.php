<?php header('Content-type: text/html; charset=iso-8859-15'); ?>
<?php
// Codigo que se ejecuta al principio de la p�gina
// importante incluir al principio de cada una, lo de las funciones
include_once("./funciones/funciones.php"); // funciones varias de conexi�n a base de datos, etc.

// Incluyo adem�s las clases que se van a usar
include_once("./clases/class.micalendario.php"); //clase mi calendario que presenta fechas formateadas
include_once("./clases/class.profesores.php"); //clase que recupera datos de profesores
// include_once("./clases/class.formulario.php"); //clase que gestiona diversos formularios
// include_once("./clases/class.cursos.php"); //clase que recupera datos de cursos
// include_once("./clases/class.alumnos.php"); //clase que recupera datos de alumnos
// include_once("./clases/class.materias.php"); //clase que recupera datos de materias
// include_once("./clases/class.asignaciones.php"); //clase que recupera datos de materias
// include_once("./clases/class.opiniones.php"); //clase que recupera datos de opiniones

// incluyo las variables de esas clases
$calendario = New micalendario(); // variable de calendario. Lo necesito para la cabecera
$profesorado = New profesores(); //variable de la clase profesores
// $formulario = New formulario(); // variable de la clase formulario
// $curso = New misCursos(); // variable de la clase curso
// $alumno = New misAlumnos(); // variable de la clase alumnos
// $materia = New misMaterias(); // variable de la clase materia
// $opiniones = New misOpiniones(); // variable de la clase opiniones
// $asignacion = New misAsignaciones($curso, $profesorado, $materia); // Uso el constructor para pasarle la clase curso, profesorado y materias a Asignaciones

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
  <title>Cambiar de contrase�a y de email. Datos personales</title>
  <link rel="icon" 
        type="image/png" 
        href="./imagenes/logoA.png">
  <meta content="Aurelio Gallardo Rodr�guez" name="author">  
  <meta content="P�gina �ndice de la web de tutor�a" name="description">

  <!-- ***************************** -->
  <!-- Linkar a hojas de estilo CSS -->
  <!-- ***************************** -->
  <?php include_once("./css/cargarestiloscss.php"); ?>
   <link rel="stylesheet" href="./css/estiloCambioDatos.css"> <!-- Efectos aplicados a esta hoja -->
  
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
		$profesorado->idprofesor = $_SESSION["profesor"]; // establezco la variable id profesor
		$email = $profesorado->profesorEmail(); // obtengo su email
		$DNI = $profesorado->profesorDNI();
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
		
		<div id="cambioPassword" class="effect7">
			<h1>Cambio de contrase�as</h1>
			<div id="cambioPasswordInside">
			<label for="introducecontrase�aparacambiar">Contrase�a Anterior</label>
				<input type="password" id="introducecontrase�aparacambiar" maxlength="9" size="9" onload="this.value='';" onfocus="this.value='';"
				title="Introduce la contrase�a anterior" alt="Introduce la contrase�a anterior"></br>
			<label for="nuevacontrase�a">Nueva contrase�a</label>
				<input type="password" id="nuevacontrase�a" maxlength="9" size="9"
				title="Introduce la nueva contrase�a. De 5 a 9 caracteres; debe contener una letra y un n�mero, al menos" 
				alt="Introduce la nueva contrase�a. De 5 a 9 caracteres; debe contener una letra y un n�mero, al menos"
				>
			<label for="repitenuevacontrase�a">Repite nueva contrase�a</label>
				<input type="password" id="repitenuevacontrase�a" maxlength="9" size="9"
				title="Repite la nueva contrase�a" alt="Repite la nueva contrase�a""></br>
			</div>
			<div class="botonCambiar">
				<button id="cambiaContrase�a" title="Ejecuta el cambio de contrase�a" alt="Ejecuta el cambio de contrase�a">Cambiar Contrase�a &nbsp;<i class="fa fa-key fa-fw"></i></button>
			</div>
		</div>

		
		<div id="cambioEmail" class="effect7">
			<h1>Cambio de Correo electr�nico</h1>
			<div id="cambioEmailInside">
			<p id="emailantiguo" class="relieve"><?php echo "Tu email actual es: ".$email; ?></p>
			<label for="nuevoEmail">Nuevo email</label>
				<input type="text" id="nuevoEmail" maxlength="100" size="45"
				title="Introduce el nuevo email" alt="Introduce el nuevo email" ></br>
			<label for="repiteNuevoEmail">Repite Nuevo email</label>
				<input type="text" id="repiteNuevoEmail" maxlength="100" size="45"
				title="Repite el nuevo email" alt="Repite el nuevo email"></br>
			</div>
			<div class="botonCambiar">
				<button id="cambiaEmail" title="Ejecuta el cambio de Email" alt="Ejecuta el cambio de Email">Cambiar Correo Electr�nico &nbsp;<i class="fa fa-envelope-o fa-fw"></i></button>
			</div>
		</div>

			
	</div> <!-- &&&& FIN DEL CONTENEDOR-->	

	<!-- ********************************************************** -->
	<!-- FIN DEL CONTENIDO PRINCIPAL -->
	<!-- ********************************************************** -->
	
	<!-- ********************************************************** -->
	<!-- ZONA DE DIALOGS O Notificaciones-->
	<!-- ********************************************************** -->
	

		<div id="notificacionGuardoEmail" class="notificacion">
			<div><h1>Se ha registrado el dato y guardado</h1></div>
		</div>
		
		<div id="notificacionFalloEmail"  class="notificacion">
			<div><h1>El dato existente se ha salvado y/o modificado</h1></div>
		</div>
		
		<div id="notificacionGuardoPassword" class="notificacion">
			<div><h1>Se ha registrado el dato y guardado</h1></div>
		</div>
		
		<div id="notificacionFalloPassword"  class="notificacion">
			<div><h1>El dato existente se ha salvado y/o modificado</h1></div>
		</div>
	
   
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
  <!-- <script type="text/javascript" src="./owl-carousel/owl.carousel.js"></script> -->
  <!-- Editor de texto froala. Non commercial use -->
  <!-- 
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
		
		// 1c) Activa los ToolTIP en el documento
	    $.getScript( "./htmlsuelto/js_tooltips.js", function( data, textStatus, jqxhr ) {
		console.log( data ); // Data returned
		console.log( textStatus ); // Success
		console.log( jqxhr.status ); // 200
		console.log( "Load was performed." );
		});	 
					 
     // Variables y elementos principales
     
	 $("#introducecontrase�aparacambiar").button(); // modelo de caja them sunny
	 $("#nuevacontrase�a").button(); // modelo de caja them sunny
	 $("#repitenuevacontrase�a").button(); // modelo de caja them sunny
	 $("#cambiaContrase�a").button(); // modelo de caja them sunny
	 
	 $("#nuevoEmail").button(); // modelo de caja them sunny
	 $("#repiteNuevoEmail").button(); // modelo de caja them sunny
	 $("#cambiaEmail").button(); // modelo de caja them sunny	 
	 	 
	
	 
	 // ========================================================================================
     // Defino di�logos y/o notificaciones
     // ========================================================================================	
		 $("#notificacionGuardoEmail, #notificacionGuardoPassword").jqxNotification({
                width: 500, position: "top-right", opacity: 0.9,
                autoOpen: false, animationOpenDelay: 300, autoClose: true, autoCloseDelay: 2000, template: "info"
         });
         $("#notificacionFalloEmail, #notificacionFalloPassword").jqxNotification({
                width: 500, position: "top-right", opacity: 0.9,
                autoOpen: false, animationOpenDelay: 500, autoClose: true, autoCloseDelay: 4000, template: "warning"
         });
	 
	 // Funcionalidad botones
	 // cambio de email
	 $("#cambiaEmail").click(function(){
		   $.when(cambiarEmail($("#nuevoEmail").val(),$("#repiteNuevoEmail").val())).done(function(data){
				try {
					var datos = jQuery.parseJSON(data);
					if (datos.valido==1) {
						$("#notificacionGuardoEmail").html('<div><h1>'+datos.mensaje+'</h1></div>');
						$("#notificacionGuardoEmail").jqxNotification("open");
						setInterval(function(){ location.reload(); },4000); // Al poco, cambia la p�gina. 
					} else if (datos.valido==0) {
						$("#notificacionFalloEmail").html('<div><h1>'+datos.mensaje+'</h1></div>');
						$("#notificacionFalloEmail").jqxNotification("open");
					}

				} catch(err) {
					console.log(err.message);
				}	
		   });
	 }); // Fin de cambiar el email
	 
	 // cambio de contrase�a
	 $("#cambiaContrase�a").click(function(){
		   $.when(cambiarPassword($("#introducecontrase�aparacambiar").val(),$("#nuevacontrase�a").val(),$("#repitenuevacontrase�a").val())).done(function(data){
				try {
					var datos = jQuery.parseJSON(data);
					if (datos.valido==1) {
						$("#notificacionGuardoPassword").html('<div><h1>'+datos.mensaje+'</h1></div>');
						$("#notificacionGuardoPassword").jqxNotification("open");
						setInterval(function(){ location.reload(); },4000); // Al poco, cambia la p�gina.
					} else if (datos.valido==0) {
						$("#notificacionFalloPassword").html('<div><h1>'+datos.mensaje+'</h1></div>');
						$("#notificacionFalloPassword").jqxNotification("open");						
					}
				} catch(err) {
					console.log(err.message);
				}	
		   });
	 }); // Fin de cambiar contrase�a 
			
	 }); // fin del document ready
	 
	 
	 $(window).load(function() {
		 // Me aseguro que tras recargar la p�gina, se borran los cambios de autocompletar
		 // alert($("#introducecontrase�aparacambiar").val());
		 $("#introducecontrase�aparacambiar").val([]); // asegurarse que no se autocompleta al empezar
		 // alert($("#introducecontrase�aparacambiar").val());
		 $("#nuevacontrase�a").val(""); // asegurarse que no se autocompleta al empezar
		 $("#repitenuevacontrase�a").val(""); // asegurarse que no se autocompleta al empezar
		 $("#nuevoEmail").val(""); // asegurarse que no se autocompleta al empezar
		 $("#repiteNuevoEmail").val(""); // asegurarse que no se autocompleta al empezar
		 $('#introducecontrase�aparacambiar').focus();
	 });
	 
	 // ******************************************************
	 // Funciones en la p�gina *******************************
	 // ******************************************************	 

	 //************************** 
	 // F1) Cambia el email
	 function cambiarEmail(nuevo, repite) {
		     console.log("****************** Cambiar email *******************");
		     console.log("Nuevo: "+nuevo);
		     console.log("Repite: "+repite);
		     return $.ajax({
			      type: 'POST',
			      dataType: 'text',
			      url: "./profesores/scripts/cambiaremail.php", 
			      data: { // Parece que las llamadas con ajax van mejor que con POST...
				  nuevo: nuevo, // Nuevo EMAIL
				  repite: repite, // Repite EMAIL
				  },					 		 
		          success: function(data, textStatus, jqXHR){
					 //alert(data);
				     return data;
			      },
			      error: function (jqXHR , textStatus, errorThrown) {
					  return "";
				  }
			  });

	  } // Fin de la funci�n Cambia el email
	  
	 //************************** 
	 // F2) Cambia el password
	 function cambiarPassword(antiguo, nuevo, repite) {
		     console.log("****************** Cambiar password *******************");
		     console.log("Antiguo: "+antiguo);
		     console.log("Nuevo: "+nuevo);
		     console.log("Repite: "+repite);
		     return $.ajax({
			      type: 'POST',
			      dataType: 'text',
			      url: "./profesores/scripts/cambiarpassword.php", 
			      data: { // Parece que las llamadas con ajax van mejor que con POST...
				  antiguo: antiguo, // Antiguo PASS
				  nuevo: nuevo, // Nuevo PASS
				  repite: repite, // Repite PASS
				  },					 		 
		          success: function(data, textStatus, jqXHR){
					 // alert(data);
				     return data;
			      },
			      error: function (jqXHR , textStatus, errorThrown) {
					  return "";
				  }
			  });

	  } // Fin de la funci�n Cambia el password
	  
 <!-- * =======================================================================================================   * --> 	

  </script>
  
<!-- **************************************************************************************** -->
</body></html>
