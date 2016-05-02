<?php
/*
 * formulario.php
 * 
 * Copyright 2015 root <root@aurelio-desktop>
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 * 
 * 
 */

class formulario
{

	var $nombreTabla; //guarda el nombre de la tabla
	var $arrayNombreCampos;	//guarda un array con el nombre de los campos, el tipo, lo que ocupa...
	var $nombrePK; // nombre de la clave primaria
	var $mivalor; // array que guarda los valores de un registro
	
	// *******************************************************
	// 1) Funcion que retorna el nombre de los campos en la bd
	public function nombreCampos () {
		// Recupera el valor de la base de datos
        $link=Conectarse(); // y me conecto. //dependiendo del tipo recupero uno u otro.
	    $Sql='SHOW FULL COLUMNS FROM '.$this->nombreTabla;
        // echo $Sql;
	    $result=mysqli_query($link,$Sql);// ejecuta la cadena sql y almacena el resultado el $result
	    $ii=0; // contador 
	    while ($row=mysqli_fetch_array($result)) {
			$this->arrayNombreCampos['campo'][$ii]=$row['Field']; 		
			$this->arrayNombreCampos['tipo'][$ii]= $this->tipoCampo($row['Type'],0); 
			$this->arrayNombreCampos['comentario'][$ii]= $row['Comment']; 
			$this->arrayNombreCampos['longitud'][$ii]= $this->tipoCampo($row['Type'],1); 
			$this->arrayNombreCampos['claveprimaria'][$ii]= $row['Key'];
			$ii++;
			}
			if (!is_null($this->arrayNombreCampos)) { // si no lo recupera, el valor por defecto)
		    return $this->arrayNombreCampos; //envia el valor dado
		    } else {
		    return NULL;
	        }
	   mysqli_free_result($result); 
	   mysqli_close($link);	
	}	
	// *******************************************************

	// *******************************************************
	// 2) Funcion que, del tipo de campo, extrae el tipo en sí y la longitud. 
	public function tipoCampo ($a, $cual) {
		if (substr($a,-1)==")") {
			$a = substr($a,0,-1); // 1º) Quito el último caracter
			$b = explode('(', $a);
			return $b[$cual];	// 3º) retorno el dato 0--> el tipo, 1 --> longitud 
		} 
		if  (substr($a,-1)!=")" && $cual=="0") { return $a;}
		if  (substr($a,-1)!=")" && $cual=="1") { return NULL;}
	}
	// *******************************************************
	
	// *********************************************************************
	// 3) Funcion que me devuelve de la tabla el nombre de la clave primaria
	public function nombreClavePrimaria () {
		$this->arrayNombreCampos = $this->nombreCampos();
		$campos = $this->arrayNombreCampos ;
		foreach($campos["campo"] as $clave => $valor) {
			if ($campos["claveprimaria"][$clave]=="PRI") { $this->nombrePK = $valor; }
		}
		return $this->nombrePK;
	}
	// **********************************************************************
	
	// **********************************************************************
	// 4) Funcion que me devuelve el mínimo o el maximo de la clave primaria
	public function minmaxClavePrimaria ($cual) { // 0 es un minimo y 1 es un maximo
		// Recupera el valor de la base de datos
        $link=Conectarse(); // y me conecto. //dependiendo del tipo recupero uno u otro.
	    if ($cual==0) { $Sql='SELECT MIN('.$this->nombreClavePrimaria().') AS valor FROM '.$this->nombreTabla; }
	    if ($cual==1) { $Sql='SELECT MAX('.$this->nombreClavePrimaria().') AS valor FROM '.$this->nombreTabla; }
	    // llamando a $this->nombreClavePrimaria() ya hay una llamada y se define $this->arrayNombreCampos.
        // echo $Sql;	 
        $result=mysqli_query($link,$Sql); // ejecuta la cadena sql y almacena el resultado el $result
	    $row=mysqli_fetch_array($result);
	    if (!is_null($row["valor"]) or !empty($row["valor"])) { // si no lo recupera, el valor por defecto)
					  return $row["valor"]; //envia el valor dado
					  } else {
				      return 0; // si no envía, cero
					  }
	    mysqli_free_result($result);  
	    mysqli_close($link);
	}
	// ***********************************************************************	
	

	// *******************************************************
	// 5) Función que devuelve los valores de la tabla
	public function valoresTabla($id) {
		$this->arrayNombreCampos = $this->nombreCampos();
		// Recupera el valor de la base de datos
        $link=Conectarse(); // y me conecto. //dependiendo del tipo recupero uno u otro.
	    $Sql="SELECT * FROM ".$this->nombreTabla." WHERE ".$this->nombreClavePrimaria()."='%s'";
	    $Sql = sprintf($Sql, mysqli_real_escape_string($link,$id)); // Seguridad que evita los ataques SQL Injection
		// echo $Sql;
        $result=mysqli_query($link,$Sql); // ejecuta la cadena sql y almacena el resultado el $result	    
	    $ii=0; // contador 
	          while ($row=mysqli_fetch_array($result)) { 
				   foreach ($this->arrayNombreCampos["campo"] as $campo) { // por cada uno de los valores de la lista de campos
					   $this->mivalor[$campo] = $row[$campo];
				   }
			  }
			if (!is_null($this->mivalor)) { // si no lo recupera, el valor por defecto)
		    return $this->mivalor; //envia el valor dado
		    } else {
		    return NULL;
	        }
	   mysqli_free_result($result); 
	   mysqli_close($link);
	}
	// *******************************************************
	
	// *******************************************************
	// 6) Array con los valores de PK
	public function datosPK() {
		// Recupera el valor de la base de datos
		$this->nombreClavePrimaria(); // me aseguro que obtengo el nombre de la clave primaria
        $link=Conectarse(); // y me conecto. //dependiendo del tipo recupero uno u otro.
	    $Sql='SELECT '.$this->nombrePK.' FROM '.$this->nombreTabla;
        // echo $Sql;
	    $result=mysqli_query($link,$Sql); // ejecuta la cadena sql y almacena el resultado el $result
        $ii=0;
        while ($row=mysqli_fetch_array($result)) {
			  $datoid[$ii]=$row[$this->nombrePK];
			  $ii++;
		      }
		if (!is_null($datoid)) { // si no lo recupera, el valor por defecto)
		    return $datoid; //envia el valor dado
		} else {
		    return NULL;
	    }
	    mysqli_free_result($result);
	    mysqli_close($link); 		
	}
	// *******************************************************
}
