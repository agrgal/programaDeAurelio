<?php
// header('Content-Type: text/html; charset=UTF-8'); // importante; especifica el charset de caracteres.
header('Content-Type: text/html; charset=ISO-8859-15'); // importante; especifica el charset de caracteres.

// importante incluir al principio de cada una, lo de las funciones
include_once("../../configuracion/config.php"); // funciones de configuración
include_once("../../funciones/funciones.php"); // funciones varias de conexión a base de datos, etc.

// Incluyo además las clases que se van a usar
include_once("../../clases/class.micalendario.php"); //clase mi calendario que presenta fechas formateadas
// include_once("../../clases/class.formulario.php"); //clase que recupera datos de formularios
include_once("../../clases/class.profesores.php"); //clase que recupera datos de profesores
// include_once("../../clases/class.cursos.php"); //clase que recupera datos de cursos
// include_once("../../clases/class.asignaciones.php"); //clase que recupera datos de alumnos
// include_once("../../clases/class.alumnos.php"); //clase que recupera datos de alumnos
// include_once("../../clases/class.opiniones.php"); //clase que recupera datos de las opiniones
// require_once("../../phpmailer/class.phpmailer.php"); //clase que envía datos a través del correo electrónico
require_once("../../phpmailer/PHPMailerAutoload.php"); //clase que envía datos a través del correo electrónico

$calendario= New micalendario(); // variable de calendario.
// $formulario = New formulario(); // variable de la clase formulario
$profesores = New profesores(); // variable de la clase profesores
// $curso = New misCursos(); // variable de la clase profesores
// $asignaciones = New misAsignaciones(); //variable de la clase asignaciones 
// $alumnos = New misAlumnos(); // variable de la clase alumnos
// $opiniones = New misOpiniones(); // variable de la clase opiniones

session_start(); //activo variables de sesion

// echo $_POST["para"]." - ".  $_POST["asunto"]." - ".  $_POST["mensaje"];

$fechahora="<p>Enviado el ".$calendario->fechaformateada($calendario->fechadehoy())." a las ".$calendario->horactual()."</p>";

$mail  = new PHPMailer();
$mail->CharSet = "UTF-8"; // importante para que lo envíe como UTF-8
$cuerpo  = $_POST["mensaje"];

$profesores->idprofesor = $_SESSION["profesor"];
$tutor = iconv("ISO-8859-15", "UTF-8",cambiarnombre($profesores->nombreEmpleado()));

$detutor = "Tutor/a: ".$tutor;
$asunto = $detutor."; ".strip_tags($_POST["asunto"]);

$cabecera ='<p style="text-align: center;"><img width="300px" heigth="auto" src="../../imagenes/iesseritium.png"></p>';
// $cabecera.='<p style="text-align: center;">Alumnado de <strong>'.$alumno["cadenaclases"].'</strong></p>';
$cabecera.='<p style="text-align: center;">Asunto: <strong>'.$asunto.'</strong></p>';
$pie = '<hr width="80%">';
$pie.= '<p style="text-align: center;">Este es un mensaje automático. Por favor, no responder al remitente del mensaje.</p>';
$pie.= '<p style="text-align: center;">No imprimas este mensaje si no es absolutamente necesario. Contribuye así a la reducción de la huella de carbono.</p>';
$pie.= '<p style="text-align: center;"><img width="100px" heigth="auto" src="../../imagenes/huellacarbono.png"></p>';

$body = $cabecera.'<p>'.$cuerpo.'</p>'.$fechahora.$pie;

$mail->IsSMTP(); // telling the class to use SMTP

// echo $body.$address.$quien;

try {
  // echo '<div id="presentardatos2">';
  $mail->Host       = $remitenteSMTPHOST; // sets the SMTP server
  $mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
  $mail->SMTPAuth   = true;                  // enable SMTP authentication
  $mail->SMTPSecure = $remitenteSMTPSecure; // secure transfer enabled REQUIRED for GMail
  $mail->Port       = $remitentepuerto;                // set the SMTP port for the GMAIL server
  $mail->Username   = $remitente; // SMTP account username
  $mail->Password   = $remitentepass;        // SMTP account password
  // $mail->AddReplyTo('name@yourdomain.com', 'First Last'); 
  $n=0;
  $arraycorreos = explode(";",$_POST["para"]);
  foreach ($arraycorreos as $valor) { // $valor es la identificación del profesor
     $profesores->idprofesor=$valor;
     $cadenacorreo=$profesores->profesorEmail();
     $cadenanombre=cambiarnombre($profesores->nombreEmpleado());
     $mail->AddAddress($cadenacorreo, $cadenanombre);
     // Si lo quiero con copia oculta, funciona, pero después puede llegar a la bandeja de SPAM
     // if ($n==0) {$mail->AddAddress($valor, "Este");}
     // if ($n>0) {$mail->AddBCC($valor);}
     $muestracorreos.=$cadenacorreo."(".$cadenanombre."), ";
     $n++;
  }
  $muestracorreos=substr($muestracorreos,0,strlen($muestracorreos)-2);
  $mail->SetFrom($remitente, "IES Seritium");
  // $mail->AddReplyTo('name@yourdomain.com', 'First Last');
  // $mail->Subject    = "Mensaje de Tutoría del IES Seritium";
  $mail->Subject    = $asunto;
  // $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
  $mail->MsgHTML($body);
  // $mail->AddAttachment('images/phpmailer.gif');      // attachment
  // $mail->AddAttachment('../../imagenes/logo.png'); // attachment
  $mail->Send();
  // echo '<div>';
  // echo '<div id="presentardatos"><h2>Mensaje enviado con éxito</h2></div>';
  echo '<div id="presentardatos">
          <h2>Enviado a: '.$muestracorreos.'</h2>
          <h2>Asunto: '.$asunto.'</h2>
          <h2>Cuerpo del mensaje: </h2>
          <p>'.$body.'</p>
        </div>';
} catch (phpmailerException $e) {
  echo '<div id="presentardatos"><h2>Mailer Error: ' .$e->errorMessage().'</h2></div>'; 
} catch (Exception $e) {
  echo '<div id="presentardatos"><h2>' .$e->getMessage().'</h2></div>'; 
   //Boring error messages from anything else!
} 

/* 
 
*/

?>



