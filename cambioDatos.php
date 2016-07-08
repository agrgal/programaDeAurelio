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
		
		<div id="cambioPassword">
			<h1>Cambio de contrase�as</h1>
			<div id="cambioPasswordInside">
			<label for="introducecontrase�a">Contrase�a Anterior</label>
				<input type="text" id="introducecontrase�a" maxlength="9" size="9" value="<?php // echo $DNI; ?>"></br>
			<label for="nuevacontrase�a">Nueva contrase�a</label>
				<input type="text" id="nuevacontrase�a" maxlength="9" size="9"></br>
			<label for="repitenuevacontrase�a">Repite nueva contrase�a</label>
				<input type="text" id="repitenuevacontrase�a" maxlength="9" size="9"></br>
			</div>
		</div>
		<div class="botonCambiar">
			<button id="cambiaContrase�a">Cambiar Contrase�a</button>
		</div>

		
		<div id="cambioEmail">
			<h1>Cambio de Correo electr�nico</h1>
			<div id="cambioEmailInside">
			<p id="emailantiguo" class="relieve"><?php echo "Tu email actual es: ".$email; ?></p>
			<label for="nuevoEmail">Nuevo email</label>
				<input type="text" id="nuevoEmail" maxlength="100" size="45"></br>
			<label for="repiteNuevoEmail">Repite Nuevo email</label>
				<input type="text" id="repiteNuevoEmail" maxlength="100" size="45"></br>
			</div>
		</div>
		<div class="botonCambiar">
			<button id="cambiaEmail" title="Cambio de Email">Cambiar Correo Electr�nico</button>
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

	 $("#introducecontrase�a").button(); // modelo de caja them sunny
	 $("#nuevacontrase�a").button(); // modelo de caja them sunny
	 $("#repitenuevacontrase�a").button(); // modelo de caja them sunny
	 $("#cambiaContrase�a").button(); // modelo de caja them sunny
	 
	 $("#nuevoEmail").button(); // modelo de caja them sunny
	 $("#repiteNuevoEmail").button(); // modelo de caja them sunny
	 $("#cambiaEmail").button(); // modelo de caja them sunny
	 
	 $("#cambiaEmail").click(function(){
		cambiarEmail($("#nuevoEmail").val(),$("#repiteNuevoEmail").val());
	 });
			
	 }); // fin del document ready
	 
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
					 alert(data);
				     // return data;
			      },
			      error: function (jqXHR , textStatus, errorThrown) {
					  return "";
				  }
			  });

	  } // Fin de la funci�n Cambia el email
	  
 <!-- * =======================================================================================================   * --> 	

  </script>
  
<!-- **************************************************************************************** -->
</body></html>
