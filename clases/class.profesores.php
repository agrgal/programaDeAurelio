<?php

class profesores
{
   
	// Mis variables
	var $idprofesor;
	var $Empleado;	
	var $listaprofesorado; 
	var $DNI;
	var $Email;


    // *******************************************
    // 1) retorna el nombre del Empleado dado su id	
	function nombreEmpleado()
	{
		// las funciones se deben llamar desde la página original
		$this->Empleado = dado_Id($this->idprofesor,"Empleado","tb_profesores","idprofesor");
		return $this->Empleado;
	}
	// *******************************************
	
	// *******************************************
    // 1BIS) retorna el email del profesor dado su id
	function profesorEmail()
	{
		// las funciones se deben llamar desde la página original
		$this->Email = dado_Id($this->idprofesor,"email","tb_profesores","idprofesor");
		return $this->Email;
	}
	// *******************************************
	
	// *******************************************
    // 1BIS) retorna la contraseña del profesor dado su id
	function profesorDNI()
	{
		// las funciones se deben llamar desde la página original
		$this->DNI = dado_Id($this->idprofesor,"DNI","tb_profesores","idprofesor");
		return $this->DNI;
	}
	// *******************************************
	
	// *******************************************
	// 2) retorna un array con la lista del profesorado
	function listaProfesores() {
		// Recupera el valor de la base de datos
        $link=Conectarse(); // y me conecto. //dependiendo del tipo recupero uno u otro.
	    $Sql='SELECT * FROM tb_profesores ORDER BY Empleado';
        // echo $Sql; 
	    $result=mysqli_query($link,$Sql); // ejecuta la cadena sql y almacena el resultado el $result
	    $ii=0; // contador 
	    while ($row=mysqli_fetch_array($result)) {
			$this->listaprofesorado['idprofesor'][$ii]=$row['idprofesor']; 
			// $profesor['Empleado'][$ii]=iconv("UTF-8","ISO-8859-1",$row['Empleado']);
			// $profesor['Empleado'][$ii]= iconv("ISO-8859-1","UTF-8",$row['Empleado']);			
			$this->listaprofesorado['Empleado'][$ii]= $row['Empleado']; 
			$this->listaprofesorado['email'][$ii]=$row['email'];
			$this->listaprofesorado['IDEA'][$ii]=$row['IDEA']; 
			$this->listaprofesorado['DNI'][$ii]=$row['DNI'];
			$this->listaprofesorado['tutorde'][$ii]=$row['tutorde'];
			$this->listaprofesorado['administrador'][$ii]=$row['administrador'];
			$ii++;
			}
			if (!is_null($this->listaprofesorado)) { // si no lo recupera, el valor por defecto)
		    return $this->listaprofesorado; //envia el valor dado
		    } else {
		    return NULL;
	   }
	   mysqli_free_result($result); 
	   mysqli_close($link);
	}
	// **********************************************
	
	// *******************************************
	// 3) Funcion que retorna un valor de ID dado un DNI (clave)
	function compruebaPASS () { // el valor DNI se pasa a través del argumento de la clase
		// Recupera el valor de la base de datos
        $link=Conectarse(); // y me conecto. //dependiendo del tipo recupero uno u otro.
        $Sql="SELECT idprofesor FROM tb_profesores WHERE DNI='%s'"; 
	    // echo $Sql;
	    $this->DNI = strtoupper($this->DNI); // convertirla en mayúsculas
	    $this->DNI = trim(strip_tags(htmlspecialchars($this->DNI))); // evita ataques XSS
	    $Sql = sprintf($Sql, mysqli_real_escape_string($link,$this->DNI)); // Seguridad que evita los ataques SQL Injection
        // echo $Sql;
        $result=mysqli_query($link,$Sql); // ejecuta la cadena sql y almacena el resultado el $result
	    $row=mysqli_fetch_array($result);
	    if (!is_null($row["idprofesor"]) or !empty($row["idprofesor"])) { // si no lo recupera, el valor por defecto)
					  return $row["idprofesor"]; //envia el valor dado
					  } else {
				      return 0; // si no envía, cero
					  }
	    mysqli_free_result($result);
	    mysqli_close($link);
	}
		// **********************************************
}
?>
