<?php
header('Content-Type: text/html; charset=ISO-8859-15'); // importante; especifica el charset de caracteres.

// importante incluir al principio de cada una, lo de las funciones
include_once("../../configuracion/config.php"); // funciones de configuración
include_once("../../funciones/funciones.php"); // funciones varias de conexión a base de datos, etc.

// Incluyo además las clases que se van a usar
include_once("../../clases/class.micalendario.php"); //clase mi calendario que presenta fechas formateadas
include_once("../../clases/class.profesores.php"); //clase que recupera datos de profesores

$calendario= New micalendario(); // variable de calendario.
$profesorado = New profesores(); //variable de la clase profesores

$profesorado->DNI = $_POST['lee']; // asigna la variable DNI
$ID = $profesorado->compruebaPASS(); // comprueba contraseña

if ($ID>0) {
      //Variables o arrays relacionados con las clases
      $profesores = $profesorado->listaProfesores();
      foreach ($profesores['idprofesor'] as $key => $valor) {
         if ($valor==$ID) { 
             $datos_json[]='"idprofesor":"'.$valor.'"';
	         $datos_json[]='"Empleado":"'.trim($profesores['Empleado'][$key]).'"';
             $datos_json[]='"DNI":"'.trim($profesores['DNI'][$key]).'"';
	         $datos_json[]='"IDEA":"'.trim($profesores['IDEA'][$key]).'"';
	         $datos_json[]='"tutorde":"'.trim($profesores['tutorde'][$key]).'"';
             $datos_json[]='"email":"'.trim($profesores['email'][$key]).'"';
             $datos_json[]='"administrador":"'.trim($profesores['administrador'][$key]).'"';
             
             // Variables de sesion
             session_start(); // importante comenzar la sesión
             $_SESSION['permisos']=1+$profesores['administrador'][$key]; // establece la variable de permiso de la página
             // al sumar 1 queda 0-->sin acceso, 1 como profesor y 2 como administrador
             $_SESSION['profesor']=$ID; // Identificación del profesor
         }
      }            
      // echo '<p>'.iconv("ISO-8859-1", "UTF-8",  $cadena).'</p>';
      echo "{".implode(",", $datos_json)."}";
      // echo "{".implode(",", iconv("UTF-8","ISO-8859-15", $datos_json))."}";
      // echo $datos_json;      
      
} else {
      // echo 'No tienes nada';
}
?>
