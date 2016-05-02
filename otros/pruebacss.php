<?php
// Codigo que se ejecuta al principio de la página
include_once("./clases/class.micalendario.php");
$calendario= New micalendario(); // variable de calendario.
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="es">	
<head>
  <!-- *** Principio del HEAD *************************************-->	
  <meta content="text/html; charset=iso-8859-15" http-equiv="content-type">
  <title>Índice - página de tutoría</title>    
  <meta content="Aurelio Gallardo Rodríguez" name="author">  
  <meta content="Página índice de la web de tutoría" name="description">

  <!-- ***************************** -->
  <!-- Linkar a hojas de estilo CSS -->
  <!-- ***************************** -->
  <link rel="stylesheet" href="./jquery/jquery-ui.min.css">
  <link rel="stylesheet" href="./css/estilobasico.css">
 
  <!-- *** Final del HEAD, antes los ficheros de enlace a CSS ******-->
</head>

<body>
<!-- **************************************************************************-->	
<!-- *** Principio del BODY ***************************************************-->	
<!-- **************************************************************************-->	

<div id="container"> <!-- CONTENEDOR PRINCIPAL -->
	
	<!-- HTML suelto: barra superior, menu superior, menu lateral,   -->
	<?php include_once("./htmlsuelto/cabecera.php"); ?>
	<?php include_once("./htmlsuelto/barrasuperior.php"); ?> 
	<?php include_once("./htmlsuelto/menu.php"); ?> 

    <!-- *********************************************************** -->
    
    <!-- ********************************************************** -->
    <!-- Contenido Principal -->
    <!-- ********************************************************** -->
	<div id="contents">
		<h1>Escribir</h1>
	</div>
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
  <script src="./jquery/jquery-ui.min.js"></script>     
  <script>
    $(function() {
    $("#datepicker").datepicker();
    });
   </script>
<!-- **************************************************************************************** -->
</body></html>
