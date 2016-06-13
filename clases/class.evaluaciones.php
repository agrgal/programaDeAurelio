<?php
/*
 * evaluaciones.php
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

class misEvaluaciones
{
	var $listadoDeEvaluaciones;
    
    public function __construct() //construye la clase inicializando variables...
    {
        $listadoDeEvaluaciones=$this->listaEvaluaciones();
        // $listadoDeGrupos=$this->gruposItems();
    }	
		
	// **********************************************************
	// 1) Funcion que retorna una lista con las Evaluaciones 
	public function listaEvaluaciones () {
	   // Recupera el valor de la base de datos
        $link=Conectarse(); // y me conecto. //dependiendo del tipo recupero uno u otro.
	    $Sql='SELECT * FROM tb_edicionevaluaciones ORDER BY ideval';
	    // $Sql = sprintf($Sql, mysqli_real_escape_string($link,$id)); // Seguridad que evita los ataques SQL Injection        
        // echo $Sql; 
	    $result=mysqli_query($link,$Sql);// ejecuta la cadena sql y almacena el resultado el $result
	    $ii=0;
	    while ($row=mysqli_fetch_array($result)) {
                $this->listadoDeEvaluaciones["ideval"][$ii]=$row["ideval"];
                $this->listadoDeEvaluaciones["nombreeval"][$ii]=$row["nombreeval"];
                $this->listadoDeEvaluaciones["fechafin"][$ii]=$row["fechafin"];
                if ($row["fechafin"]>0) {
					$annadetitulo='; hasta el '.fechaMySQL2DatePicker($row["fechafin"]);
				} else {
					$annadetitulo=NULL;
				}
				$this->listadoDeEvaluaciones["div"][$ii]=
					'<div id="'.$row["ideval"].'" name="'.$row["nombreeval"].'" fechafin="'.$row["fechafin"]
					.'" class="divNombreEval" title="'.$row["nombreeval"]
					.$annadetitulo.'" alt="'.$row["nombreeval"].$annadetitulo.'">
					<p>'
					.$row["nombreeval"].'</p>
					</div>';
				$ii++; 
		        } // fin del while
		        if (!is_null($this->listadoDeEvaluaciones)) { // si no lo recupera, el valor por defecto)
				return $this->listadoDeEvaluaciones; //envia el valor dado
				} else {
				return NULL;
				}
	    mysqli_free_result($result); 
	    mysqli_close($link);
	}	
	
	// *******************************************************
	// 2) Funcion que calcula la evaluación dada una fecha concreta
	public function calculaFecha ($fechaDada,$id) {
		 // Recupera el valor de la base de datos
        $link=Conectarse(); // y me conecto. //dependiendo del tipo recupero uno u otro.
	    $Sql='SELECT fechafin FROM tb_edicionevaluaciones ORDER BY fechafin ASC';
	    $result=mysqli_query($link,$Sql);// ejecuta la cadena sql y almacena el resultado el $result
		// $row=mysqli_fetch_array($result); // matriz con los valores retornados
		$fechas=array(); // crea el array de fechas 	
		while ($row=mysqli_fetch_array($result)) {
			if ($row["fechafin"]>0) {
				$fechas[]=$row["fechafin"];
				$cadena.=$row["fechafin"]." - ";
			}
		}
		mysqli_free_result($result); 
	    mysqli_close($link);
	    
	    // añade al array el 1 de septiembre al principio
		array_unshift ($fechas,unoSeptiembre($fechas[0])); // la fecha primera se supone que está al principio de curso
		if (in_array($fechaDada,$fechas)) {
			$retorna = $fechaDada; // caso de INdex 0 o máximo; retorna un valor que no va a encontrar
			// ese el caso de coincidencia. El último día sigo estando en la evaluación que corresponde.
		} else {		
		// Incluyo la fecha Dada
		array_push($fechas,$fechaDada); // incluyo la fecha dada
		sort($fechas); // ordeno el array y recalculo índices. FechaDada se reestructura en su lugar
		// Cuento los lugares que hay
		$numFechas = count($fechas);
		// El último índice es el...
		$lastIndex = $numFechas-1;
		// ¿Dónde está la fecha dada? calculo su índice
		$fechaDadaIndex = array_search($fechaDada,$fechas);
		// Calcula la fecha del índice siguiente
			if ($fechaDadaIndex>0 && $fechaDadaIndex<$lastIndex) {
				$retorna = $fechas[$fechaDadaIndex+1]; // retorna un valor de la siguiente fecha, y dirá en qué evaluación estará
			} else {
				$retorna = $fechaDada; // caso de INdex 0 o máximo; retorna un valor que no va a encontrar
			}
		} // Fin del if inicial.
		
		// Calcula el id que corresponda. Mejor llamado a la base de datos de nuevo.
		$link=Conectarse(); // y me conecto. //dependiendo del tipo recupero uno u otro.
	    $Sql='SELECT ideval FROM tb_edicionevaluaciones WHERE fechafin="'.$retorna.'"';
	    $result=mysqli_query($link,$Sql);// ejecuta la cadena sql y almacena el resultado el $result
		$row=mysqli_fetch_array($result); // matriz con los valores retornados
			$idsalida = $row["ideval"];
		mysqli_free_result($result); 
	    mysqli_close($link);
	    
	    if(is_null($idsalida)) { $idsalida=0; } // si no existe, que devuelva cero...
		
		if ($id) {
			return $idsalida;
	    } else { return $retorna; } // Si es falso, devuelve la fecha
	    
	    // $cadena = $retorna." (".$idsalida.")";	    
	    // return $cadena;
	
    } // Fin de calculafecha...  

}

?>
