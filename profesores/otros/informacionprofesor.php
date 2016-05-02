<?php
// importante incluir al principio de cada una, lo de las funciones
include_once("../configuracion/config.php"); // funciones de configuración
include_once("../funciones/funciones.php"); // funciones varias de conexión a base de datos, etc.

// Incluyo además las clases que se van a usar
include_once("../clases/class.micalendario.php"); //clase mi calendario que presenta fechas formateadas
include_once("../clases/class.profesores.php"); //clase que recupera datos de profesores

$calendario= New micalendario(); // variable de calendario.
$profesorado = New profesores(); //variable de la clase profesores


if ($_POST['lee']<>'') {
      // $_POST['lee']-> en esta variable se guarda el dato de la clase escogida
      $ID=$_POST['lee'];
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
         }
      }
      // echo '<p>'.iconv("ISO-8859-1", "UTF-8",  $cadena).'</p>';
      echo "{".implode(",", $datos_json)."}";
      // echo $datos_json;
} else {
      echo 'No tienes nada';
}
?>
