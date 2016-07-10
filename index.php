<?php header('Content-type: text/html; charset=iso-8859-15'); ?>
<?php
// Codigo que se ejecuta al principio de la p�gina
// importante incluir al principio de cada una, lo de las funciones
include_once("./funciones/funciones.php"); // funciones varias de conexi�n a base de datos, etc.

// Incluyo adem�s las clases que se van a usar
include_once("./clases/class.micalendario.php"); //clase mi calendario que presenta fechas formateadas
include_once("./clases/class.profesores.php"); //clase que recupera datos de profesores

// incluyo las variables de esas clases
$calendario = New micalendario(); // variable de calendario. Lo necesito para la cabecera
$profesorado = New profesores(); //variable de la clase profesores

// Variables de sesi�n
session_start();
if (!isset($_SESSION['permisos'])) { $_SESSION['permisos']=0; }
if (!isset($_SESSION['profesor'])) { $_SESSION['profesor']=0; }
if (!isset($_SESSION['menuIZQ'])) { $_SESSION['menuIZQ']=0; }
// Las variables de sesi�n se establecen en los scripts AJAX en /profesor/scripts/compruebacontrasenna y cerrarsesion

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="es">	
<head profile="http://www.w3.org/2005/10/profile">
  <!-- *** Principio del HEAD *************************************-->	
  <meta http-equiv="content-type" content="text/html; charset=iso-8859-15" >
  <title>�ndice - p�gina de tutor�a</title>
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
	<?php // include_once("./htmlsuelto/menu.php"); ?> 
	<div id="menu" style="text-align: justify; padding-right: 1em;">
		<?php 
		    unset($_SESSION["menuIZQ"]);
		    include_once("./htmlsuelto/menuIZQUIERDO.php"); 
		?> 
 	</div> 


    <!-- *********************************************************** -->
    
    <!-- ********************************************************** -->
    <!-- Contenido Principal -->
    <!-- ********************************************************** -->
 
    <div id="test"> <!-- TESTER -->
		<p id="testear"></p>
    </div>	<!-- TESTER -->
    
    <div id="contents"> <!-- &&&& -->
    <?php if (!($_SESSION['permisos']>0 && $_SESSION['profesor']>0)) { // si NO el permiso>0 y el idprofesor>0...?>
			<h1 style="text-align: center;">Introduce tu contrase�a para identificarte en el sistema...</h1>
			</br>
			<div id="form" style="text-align: center; border: 0px solid black;"> 		     
				 <form action="#">
					<h3>Escribe tu contrase�a de acceso &nbsp;&nbsp;
					<input type="password" id="introducecontrase�a" maxlength="10" size="12" onfocus="this.value='';">
					&nbsp;&nbsp;y pulsa&nbsp;&nbsp;<a id="comprueba">Acceder</a></h3>
				 </form> 
			</div>
	<?php } else { ?>
			<h1 style="text-align: center;">				
				<?php 
				   $profesorado->idprofesor = $_SESSION["profesor"];
				   echo "�Bienvenido/a ".cambiarnombre($profesorado->nombreEmpleado())."!";
				?>			
			</h1>
			<h2 style="text-align: center;">Has accedido correctamente al sistema</h2>
			<p  style="text-align: center;"><a id="abandonar">Cerrar sesi�n</a></p>
			<p  style="text-align: center;">Pensar en si se puede poner alguna frase aleatoria...</p>
	<?php } ?>
	</div> <!-- &&&& -->
	
	<!-- Comprobar el paso de par�metros si se activa -->
	<?php // echo  "<p>Nivel: ".$_SESSION['permisos']."</p>"; ?>
	<?php // echo  "<p>Profesor: ".$_SESSION['profesor']."</p>"; ?>
	
	<!-- ********************************************************** -->
	<!-- ZONA DE DIALOGS O Notificaciones-->
	<!-- ********************************************************** -->
	
		<div id="notificacionFallo"  class="notificacion">
			<div><h1 style="font-size: 3em;">La contrase�a no es correcta</h1></div>
		</div>
	
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
  <script src="./jquery/jquery-ui.min.js"></script>    
  <!-- <script src="./htmlsuelto/js_menu.js"></script>   Incorpora al script los men�s a la izquierda -->
  <script type="text/javascript" src="./jquery/jqx/jqxcore.js"></script>
  <script type="text/javascript" src="./jquery/jqx/jqx-all.js"></script>
 
   <script>    
     $(document).ready(function() {  
		
		// Incorpora la funcionalidad del men�
		$.getScript( "./htmlsuelto/js_menu.js", function( data, textStatus, jqxhr ) {
		console.log( data ); // Data returned
		console.log( textStatus ); // Success
		console.log( jqxhr.status ); // 200
		console.log( "Load was performed." );
		});
		
		// Activa los ToolTIP en el documento
	    $.getScript( "./htmlsuelto/js_tooltips.js", function( data, textStatus, jqxhr ) {
		console.log( data ); // Data returned
		console.log( textStatus ); // Success
		console.log( jqxhr.status ); // 200
		console.log( "Load was performed." );
		}); 
		
		 $("#notificacionFallo").jqxNotification({
                width: 500, position: "top-right", opacity: 0.9,
                autoOpen: false, animationOpenDelay: 500, autoClose: true, autoCloseDelay: 4000, template: "error"
         });
		
		// Funci�n para click del button de comprueba contrase�a 
		$("#comprueba")
		.button()
		.click(function(event) {	   
		   $.when(recuperaInformacionProfesor()).done(function(data){
				try {
					var datos = jQuery.parseJSON(data);
					// alert(datos.idprofesor);
					if (datos.idprofesor>0) {
						location.reload();  // cambia la p�gina.
					} else {
						$("#notificacionFallo").jqxNotification("open");						
					}
				} catch(err) {
					console.log(err.message);
				}	
		   });
		});
		
		// Funci�n para click del button de comprueba contrase�a. As� coge el estilo del bot�n
		$("#introducecontrase�a").button();
		
		// Funci�n para click del button de cerrar sesi�n
		$("#abandonar")
		.button()
		.click(function(event) {
		   cerrarSesion(); // llama a la informacion del profesorado.
		});	
		
		// Al empezar pone un valor a cero
		// $("#introducecontrase�a").val("");
				
	 }); // Fin del document ready
	 
	 $(window).load(function() {
		 // Me aseguro que tras recargar la p�gina, se borran los cambios de autocompletar
		 $("#introducecontrase�a").val("");
	 });
	 
	 // ******************************************************
	 // Funciones en la p�gina *******************************
	 // ******************************************************
	 
	 // F1) Llama al script y recupera informacion del profesor
		function recuperaInformacionProfesor() {
				 var id = $("#introducecontrase�a").val();
				 // alert(id);
				 return $.ajax({
					  type: 'POST',
					  dataType: 'text',
					  url: "./profesores/scripts/compruebacontrasenna.php", 
					  data: { // Parece que las llamadas con ajax van mejor que con POST...
					  lee: id, 
					  },					 		 
						  success: function(data, textStatus, jqXHR){
							 // alert(data);
							 return data;
						  },
						  error: function (jqXHR , textStatus, errorThrown) {
							  return "";
						  }
			      });
				 
		} 
		
		// F2) Llama al script y recupera informacion del profesor
		function cerrarSesion() {
				 var posting = $.post( "./profesores/scripts/cerrarsesion.php", {});
				  posting.done(function(data,status) { 
					  location.reload();
				  });
		} 

  </script>
  
<!-- **************************************************************************************** -->
</body></html>
