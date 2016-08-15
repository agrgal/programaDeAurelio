<?php
/*
 * misOpinionesGenerales.php
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

class misOpinionesGenerales
{
    
    public function __construct() //construye la clase inicializando variables...
    {
        // $listadoDeItems=$this->listaItems();
        // $listadoDeGrupos=$this->gruposItems();
    }

		
	// **********************************************************
	// 1) Funcion que retorna una lista de los items de opiniones
	public function listaItems () {
	   // Recupera el valor de la base de datos
        $link=Conectarse(); // y me conecto. //dependiendo del tipo recupero uno u otro.
	    $Sql='SELECT * FROM tb_itemsopiniones ORDER BY iditem';
	    // $Sql = sprintf($Sql, mysqli_real_escape_string($link,$id)); // Seguridad que evita los ataques SQL Injection        
        // echo $Sql; 
	    $result=mysqli_query($link,$Sql);// ejecuta la cadena sql y almacena el resultado el $result
	    $ii=0;
	    while ($row=mysqli_fetch_array($result)) {
                $this->listadoDeItems["iditem"][$ii]=$row["iditem"];
                $this->listadoDeItems["item"][$ii]=$row["item"];
                $this->listadoDeItems["grupo"][$ii]=$row["grupo"];
				$this->listadoDeItems["positivo"][$ii]=$row["positivo"];	 
				$estilocaja=""; // anula al principio del bucle...
				if ($row["positivo"]==0) { $estilocaja="negativo";}
				if ($row["positivo"]==1) { $estilocaja="positivo";}
				$this->listadoDeItems["div"][$ii]=
					'<div id="'.$row["iditem"].'" name="'.$row["item"].'" grupo="'.$row["grupo"]
					.'" class="divitem '.$estilocaja.'" title="'.$row["item"].'" alt="'.$row["item"].'">
					<p>'
					.$row["item"].'</p>
					</div>';
				$ii++;
		        } // fin del while
		        if (!is_null($this->listadoDeItems)) { // si no lo recupera, el valor por defecto)
				return $this->listadoDeItems; //envia el valor dado
				} else {
				return NULL;
				}
	    mysqli_free_result($result); 
	    mysqli_close($link);
	}	
	// *******************************************************
	
	// **********************************************************
	// 2) Funcion que retorna los grupos de items
	public function gruposItems () {
		 // Recupera el valor de la base de datos
        $link=Conectarse(); // y me conecto. //dependiendo del tipo recupero uno u otro.
	    $Sql='SELECT DISTINCT grupo FROM tb_itemsopiniones ORDER BY grupo';
	    $result=mysqli_query($link,$Sql);// ejecuta la cadena sql y almacena el resultado el $result
	    $ii=0;
	    while ($row=mysqli_fetch_array($result)) {
                $this->listadoDeGrupos["grupo"][$ii]=$row["grupo"];
				$ii++;
		        } // fin del while
		        if (!is_null($this->listadoDeGrupos)) { // si no lo recupera, el valor por defecto)
				return $this->listadoDeGrupos; //envia el valor dado
				} else {
				return NULL;
				}
	    mysqli_free_result($result); 
	    mysqli_close($link);
	}
	
	// **********************************************************
	// 3) Funcion que comprueba si existe o no una Opinión General. Devuelve el número de id
	public function checkOpinion($evaluacion, $asignacion) {
		$link=Conectarse(); // y me conecto. //dependiendo del tipo recupero uno u otro.
		$evaluacion = mysqli_real_escape_string($link,$evaluacion); // Puedo poner así el real_escape...
		$asignacion = mysqli_real_escape_string($link,$asignacion);
	    $Sql ='SELECT idopiniongeneral FROM tb_opiniongeneral WHERE eval="'.$evaluacion.'" AND asignacion="'.$asignacion.'"';
	    // $Sql = sprintf($Sql, mysqli_real_escape_string($link,$fecha)); // Seguridad que evita los ataques SQL Injection  	
	    $result=mysqli_query($link,$Sql); // ejecuta la cadena sql y almacena el resultado el $result
        $row=mysqli_fetch_array($result);
        	    if (!is_null($row["idopiniongeneral"]) or !empty($row["idopiniongeneral"])) { // si no lo recupera, el valor por defecto)
					  return $row["idopiniongeneral"]; //envia el valor dado
					  } else {
				      return 0; // si no envía, cero
					  }
	    mysqli_free_result($result); 
	    mysqli_close($link); 
	    // return $Sql;
	} // fin de la funcion

	// ***********************************************************
	// 4) Función que acepta una cadena SQL
	// Retorna un array con la información
	public function retornaValores($cadenaSQL) {
		$link=Conectarse(); // y me conecto. //dependiendo del tipo recupero uno u otro.
	    // $Sql ='SELECT id, items, observaciones FROM tb_opiniones WHERE fecha="'.$fecha.'" AND alumno="'.$alumno.'" AND asignacion="'.$asignacion.'"';
	    // $Sql = sprintf($Sql, mysqli_real_escape_string($link,$fecha)); // Seguridad que evita los ataques SQL Injection  	
	    $result=mysqli_query($link,$cadenaSQL); // ejecuta la cadena sql y almacena el resultado el $result
        $ii=0;
        $datos=array();
        while ($row=mysqli_fetch_array($result)) {
			if (!is_null($row["idopiniongeneral"]) or !empty($row["idopiniongeneral"])) { // si no lo recupera, el valor por defecto)
				  	$datos[$ii]=array("id"=>$row["idopiniongeneral"],"eval"=>$row["eval"],"asignacion"=>$row["asignacion"]
	                   ,"opinion"=>html_entity_decode($row["opinion"])
	                   ,"actuaciones"=>html_entity_decode($row["actuaciones"])
	                   ,"mejora"=>html_entity_decode($row["mejora"]));
	        }
	        $ii++;
		}
		mysqli_free_result($result); 
	    mysqli_close($link); 
	    // Guardado todo en datos...        
		
		$datos_json=json_encode($datos); // codifica como json...
		
		if (!is_null($datos_json) or !empty($datos_json)) {
			return $datos_json; //envia el valor dado como JSON... Obtener valores como $valor->{"id"}
		} else {
			return NULL;
		}
	}

}

?>
