<?php
/*
 * misMaterias.php
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

class misMaterias
{

    var $estaMateria;
    var $listaDeMaterias;
		
	// *******************************************************
	// 1) Funcion que retorna las propiedades de una materia y las guarda en estaMateria
	public function devuelveMateria ($id) {
		// Recupera el valor de la base de datos
        $link=Conectarse(); // y me conecto. //dependiendo del tipo recupero uno u otro.
	    $Sql='SELECT * FROM tb_asignaturas WHERE idmateria="%s"';
	    $Sql = sprintf($Sql, mysqli_real_escape_string($link,$id)); // Seguridad que evita los ataques SQL Injection        
        // echo $Sql; 
	    $result=mysqli_query($link,$Sql);// ejecuta la cadena sql y almacena el resultado el $result
	    $row=mysqli_fetch_array($result);
	    if (!is_null($row["idmateria"]) or !empty($row["idmateria"])) { // si no lo recupera, el valor por defecto)
                $this->estaMateria["idmateria"]=$row["idmateria"];
                $this->estaMateria["materia"]=$row["Materias"];
                $this->estaMateria["abr"]=strtoupper($row["Abr"]);
 
		} 
	    mysqli_free_result($result); 
	    mysqli_close($link);
	}	
	// **********************************************************
	
	// *******************************************************
	// 2) Funcion que retorna las propiedades de una materia y las guarda en estaMateria
	public function listaMaterias () {
		// Recupera el valor de la base de datos
        $link=Conectarse(); // y me conecto. //dependiendo del tipo recupero uno u otro.
	    $Sql='SELECT * FROM tb_asignaturas ORDER BY Materias';
	    $ii=0; // contador 
	    $result=mysqli_query($link,$Sql);// ejecuta la cadena sql y almacena el resultado el $result
	    while ($row=mysqli_fetch_array($result)) {
			$this->listaDeMaterias['idmateria'][$ii]=$row['idmateria']; 
			$this->listaDeMaterias['materia'][$ii]=strtoupper($row['Materias']); 	
			$this->listaDeMaterias['abr'][$ii]=strtoupper($row['Abr']);	
			$ii++;		
		}
		if (!is_null($this->listaDeMaterias)) { // si no lo recupera, el valor por defecto)
		    return $this->listaDeMaterias; //envia el valor dado
		    } else {
		    return NULL;
	        }
	   mysqli_free_result($result); 
	   mysqli_close($link);
	}
	

}

?>
