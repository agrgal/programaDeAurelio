<?php header('Content-type: text/html; charset=iso-8859-15'); ?>
<?php
// Codigo que se ejecuta al principio de la página
// importante incluir al principio de cada una, lo de las funciones
include_once("./funciones/funciones.php"); // funciones varias de conexión a base de datos, etc.

// Incluyo además las clases que se van a usar
include_once("./clases/class.micalendario.php"); //clase mi calendario que presenta fechas formateadas
include_once("./clases/class.profesores.php"); //clase que recupera datos de profesores

// incluyo las variables de esas clases
$calendario = New micalendario(); // variable de calendario. Lo necesito para la cabecera
$profesorado = New profesores(); //variable de la clase profesores

// Variables de sesión
session_start();
if (!isset($_SESSION['permisos'])) { $_SESSION['permisos']=0; }
if (!isset($_SESSION['profesor'])) { $_SESSION['profesor']=0; }
if (!isset($_SESSION['menuIZQ'])) { $_SESSION['menuIZQ']=0; }
// Las variables de sesión se establecen en los scripts AJAX en /profesor/scripts/compruebacontrasenna y cerrarsesion

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="es">	
<head profile="http://www.w3.org/2005/10/profile">
  <!-- *** Principio del HEAD *************************************-->	
  <meta http-equiv="content-type" content="text/html; charset=iso-8859-15" >
  <title>Índice - página de tutoría</title>
  <link rel="icon" 
        type="image/png" 
        href="./imagenes/logoA.png">
  <meta content="Aurelio Gallardo Rodríguez" name="author">  
  <meta content="Página índice de la web de tutoría" name="description">

  <!-- ***************************** -->
  <!-- Linkar a hojas de estilo CSS -->
  <!-- ***************************** -->
  <?php include_once("./css/cargarestiloscss.php"); ?>
  <link rel="stylesheet" href="./css/frase.css"> <!-- Efectos aplicados a esta hoja -->
 
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
			<h1 style="text-align: center;">Introduce tu contraseña para identificarte en el sistema...</h1>
			</br>
			<div id="form" style="text-align: center; border: 0px solid black;"> 		     
				 <form action="#">
					<h3>Escribe tu contraseña de acceso &nbsp;&nbsp;
					<input type="password" id="introducecontraseña" maxlength="10" size="12" onfocus="this.value='';">
					&nbsp;&nbsp;y pulsa&nbsp;&nbsp;<a id="comprueba">Acceder</a></h3>
				 </form> 
			</div>
	<?php } else { ?>
			<h1 style="text-align: center;">				
				<?php 
				   $profesorado->idprofesor = $_SESSION["profesor"];
				   echo "¡Bienvenido/a ".cambiarnombre($profesorado->nombreEmpleado())."!";
				?>			
			</h1>
			<h2 style="text-align: center;">Has accedido correctamente al sistema</h2>
			<p  style="text-align: center;"><a id="abandonar">Cerrar sesión</a></p>
			
			<!-- Frase aleatoria; SCRIPT COPIADO DE https://es.wikiquote.org/wiki/Wikiquote:Frase_del_d%C3%ADa -->
			<?php 
			$registros = array();
			if (($fichero = fopen("./otros/frases.csv", "r")) !== FALSE) {
				// Lee los nombres de los campos
				$nombres_campos = fgetcsv($fichero, 0, "###", "\"", "\"");
				$num_campos = count($nombres_campos);
				// Lee los registros
				while (($datos = fgetcsv($fichero, 0, "###", "\"", "\"")) !== FALSE) {
				// Crea un array asociativo con los nombres y valores de los campos
					for ($icampo = 0; $icampo < $num_campos; $icampo++) {
						$registro[$nombres_campos[$icampo]] = $datos[$icampo];
						// echo $nombres_campos[$icampo];
					}
					// Añade el registro leido al array de registros
					$registros[] = $registro;
				}
				fclose($fichero);

				$aleatorio = rand(0,count($registros)-1); 
				$frase = $registros[$aleatorio]["Frase"];
				$autor = trim($registros[$aleatorio]["Autor"]);
								
			} ?>
			<!-- Fin de frase aleatoria -->
						
			<div id="frase" class="effect7" style="width: 80%; margin: 10px auto; padding: 10px 10px;" >
				<?php echo '<p>'.iconv("UTF-8","ISO-8859-15", "\"".trim($frase)."\", de ".$autor).'</p>'; ?>
			
			
			<?php
			$title = str_replace(" ", "_", $autor);
			$results = array();
			$pais=array(); $pais[0]="en"; $pais[1]="es";
			foreach ($pais as $p) {
				$imagesQuery = "http://".$p.".wikipedia.org/w/api.php?action=query&titles=".$title."&prop=images&format=json&imlimit=3";
				$jsonResponse = file_get_contents($imagesQuery);
				// echo $jsonResponse;
				$json_array = json_decode($jsonResponse, true);
				foreach($json_array['query']['pages'] as $page){
					if(count($page['images']) > 0) {
						foreach($page['images'] as $image){
							// echo $image["title"];
							// echo str_replace("_"," ",normaliza($title));
							if (strpos($image["title"],str_replace("_"," ",$title))>0 or 
							     strpos($image["title"],str_replace("_"," ",normaliza($title)))>0 
							     ) {
								$title2 = str_replace(" ", "_", $image["title"]);
								$imageinfourl = "http://".$p.".wikipedia.org/w/api.php?action=query&titles=".$title2."&prop=imageinfo&iiprop=url&format=json&imlimit=5";
								$imageinfo = file_get_contents($imageinfourl);
								// echo $imageinfo;
								$image_array = json_decode($imageinfo, true);
								$image_pages = $image_array["query"]["pages"];
								foreach($image_pages as $a){
									$results["url"][] = $a["imageinfo"][0]["url"];
								}
							} // primero mira si el nombre coincide
						} // Si hay fotos pero no resultados
												
						/* if(count($results["url"])<=0) {
							foreach($page['images'] as $image){
								$title2 = str_replace(" ", "_", $image["title"]);
								$imageinfourl = "http://".$p.".wikipedia.org/w/api.php?action=query&titles=".$title2."&prop=imageinfo&iiprop=url&format=json&imlimit=5";
								$imageinfo = file_get_contents($imageinfourl);
								// echo $imageinfo;
								$image_array = json_decode($imageinfo, true);
								$image_pages = $image_array["query"]["pages"];
								foreach($image_pages as $a){
									$results["url"][] = $a["imageinfo"][0]["url"];
								}
							} // Si hay fotos pero no resultados
						} // Fin de si no hay resultados */
						
					}
				}
			}
			
			// echo count($results["url"]);
			//Si existe una imagen en wikipedia
			if (count($results["url"])>0) {
				$imagenAleatoria = rand(0,count($results["url"])-1);
				echo '<p><img src="'.$results["url"][$imagenAleatoria].'"></p>';			
			} 
			
			/* foreach ($results["url"] as $clave => $direccion) {
				echo '<p>'.$clave.'<img src="'.$direccion.'"></p>';
			} */
			
			echo '</div>'; // Fin del div de frases...
			?>
    
			
	<?php } ?>
	
	<div id="aviso" class="effect7" style="margin-top: 40px; padding: 20px;">
	<h1 style="text-align: justify; padding: 10px;">Por favor, lee las "<span style="color: #0000ff;">consideraciones importantes</span>" (<span style="color: #993300;">&lt;--</span>). Recuerda: puedes aumentar el tama&ntilde;o de la letra pulsando, a la vez, las teclas <span style="color: #993300;">CTRL</span> y "<span style="color: #993300;">+</span>", y para reducir el tama&ntilde;o de la letra pulsando <span style="color: #993300;">CTRL</span> y "<span style="color: #993300;">-</span>".</h1>
	</div>
	
	</div> <!-- &&&& -->
	
	<!-- Comprobar el paso de parámetros si se activa -->
	<?php // echo  "<p>Nivel: ".$_SESSION['permisos']."</p>"; ?>
	<?php // echo  "<p>Profesor: ".$_SESSION['profesor']."</p>"; ?>
	
	<!-- ********************************************************** -->
	<!-- ZONA DE DIALOGS O Notificaciones-->
	<!-- ********************************************************** -->
	
		<div id="notificacionFallo"  class="notificacion">
			<div><h1 style="font-size: 3em;">La contraseña no es correcta</h1></div>
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
  <!-- <script src="./htmlsuelto/js_menu.js"></script>   Incorpora al script los menús a la izquierda -->
  <script type="text/javascript" src="./jquery/jqx/jqxcore.js"></script>
  <script type="text/javascript" src="./jquery/jqx/jqx-all.js"></script>
 
   <script>    
     $(document).ready(function() {  
		
		// Incorpora la funcionalidad del menú
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
		
		// Función para click del button de comprueba contraseña 
		$("#comprueba")
		.button()
		.click(function(event) {	   
		   $.when(recuperaInformacionProfesor()).done(function(data){
				try {
					var datos = jQuery.parseJSON(data);
					// alert(datos.idprofesor);
					if (datos.idprofesor>0) {
						location.reload();  // cambia la página.
					} else {
						$("#notificacionFallo").jqxNotification("open");						
					}
				} catch(err) {
					console.log(err.message);
				}	
		   });
		});
		
		// Función para click del button de comprueba contraseña. Así coge el estilo del botón
		$("#introducecontraseña").button();
		
		// Función para click del button de cerrar sesión
		$("#abandonar")
		.button()
		.click(function(event) {
		   cerrarSesion(); // llama a la informacion del profesorado.
		});	
		
		// Al empezar pone un valor a cero
		// $("#introducecontraseña").val("");
				
	 }); // Fin del document ready
	 
	 $(window).load(function() {
		 // Me aseguro que tras recargar la página, se borran los cambios de autocompletar
		 $("#introducecontraseña").val("");
	 });
	 
	 // ******************************************************
	 // Funciones en la página *******************************
	 // ******************************************************
	 
	 // F1) Llama al script y recupera informacion del profesor
		function recuperaInformacionProfesor() {
				 var id = $("#introducecontraseña").val();
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
