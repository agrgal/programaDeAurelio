<?php
/*
 * misOpiniones.php
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

class misOpiniones
{

    var $listadoDeItems; // Todos los items...
    var $listadoDeGrupos; // Grupos de Items.
    var $listadoDeFechasOpinion; // lista con las fechas de una combinación asignación - alumno
    var $listadoDeFechasGlobal; // lista con las fechas de una asignación todos los alumnos
    
    public function __construct() //construye la clase inicializando variables...
    {
        $listadoDeItems=$this->listaItems();
        $listadoDeGrupos=$this->gruposItems();
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
	// 3) Funcion que comprueba si existe o no una Opinión. Devuelve el número de id
	public function checkOpinion($fecha, $asignacion, $alumno) {
		$link=Conectarse(); // y me conecto. //dependiendo del tipo recupero uno u otro.
		$fecha = mysqli_real_escape_string($link,$fecha); // Puedo poner así el real_escape...
		$alumno = mysqli_real_escape_string($link,$alumno);
		$asignacion = mysqli_real_escape_string($link,$asignacion);
	    $Sql ='SELECT id FROM tb_opiniones WHERE fecha="'.$fecha.'" AND alumno="'.$alumno.'" AND asignacion="'.$asignacion.'"';
	    // $Sql = sprintf($Sql, mysqli_real_escape_string($link,$fecha)); // Seguridad que evita los ataques SQL Injection  	
	    $result=mysqli_query($link,$Sql); // ejecuta la cadena sql y almacena el resultado el $result
        $row=mysqli_fetch_array($result);
        	    if (!is_null($row["id"]) or !empty($row["id"])) { // si no lo recupera, el valor por defecto)
					  return $row["id"]; //envia el valor dado
					  } else {
				      return 0; // si no envía, cero
					  }
	    mysqli_free_result($result); 
	    mysqli_close($link); 
	} // fin de la funcion
	
	// ****************************************************************
	// 4) Fechas en las que una determinada asignacion - alumno existen
	public function listaFechasOpinion($asignacion, $alumno) {
		$link=Conectarse(); // y me conecto. //dependiendo del tipo recupero uno u otro.
		$alumno = mysqli_real_escape_string($link,$alumno);
		$asignacion = mysqli_real_escape_string($link,$asignacion);
	    $Sql ='SELECT DISTINCT fecha FROM tb_opiniones WHERE alumno="'.$alumno.'" AND asignacion="'.$asignacion.'" ORDER BY fecha DESC';
	    // $Sql = sprintf($Sql, mysqli_real_escape_string($link,$fecha)); // Seguridad que evita los ataques SQL Injection  	
	    $result=mysqli_query($link,$Sql); // ejecuta la cadena sql y almacena el resultado el $result
        $ii=0;
	    while ($row=mysqli_fetch_array($result)) {
                $this->listadoDeFechasOpinion["fechaMySQL"][$ii]=$row["fecha"];
                $this->listadoDeFechasOpinion["fechaDatepicker"][$ii]=fechaMySQL2DatePicker($row["fecha"]);
                $this->listadoDeFechasOpinion["a"][$ii]='<a id="'.$row["fecha"].'" 
                       datepicker="'.$this->listadoDeFechasOpinion["fechaDatepicker"][$ii].'" 
                       class="enlaceFecha">'.$this->listadoDeFechasOpinion["fechaDatepicker"][$ii].'</a>';
				$ii++;
		        } // fin del while
		        if (!is_null($this->listadoDeFechasOpinion)) { // si no lo recupera, el valor por defecto)
				return $this->listadoDeFechasOpinion; //envia el valor dado
				} else {
				return NULL;
				}
	    mysqli_free_result($result); 
	    mysqli_close($link); 
	} // fin de la funcion
	
	// ****************************************************************
	// 5) Todas las fechas con una determinada asignacion
	public function listaFechasGlobal($asignacion) {
		$link=Conectarse(); // y me conecto. //dependiendo del tipo recupero uno u otro.
		$asignacion = mysqli_real_escape_string($link,$asignacion);
	    $Sql ='SELECT DISTINCT fecha FROM tb_opiniones WHERE asignacion="'.$asignacion.'" ORDER BY fecha DESC';
	    // $Sql = sprintf($Sql, mysqli_real_escape_string($link,$fecha)); // Seguridad que evita los ataques SQL Injection  	
	    $result=mysqli_query($link,$Sql); // ejecuta la cadena sql y almacena el resultado el $result
        $ii=0;
	    while ($row=mysqli_fetch_array($result)) {
                $this->listadoDeFechasGlobal["fechaMySQL"][$ii]=$row["fecha"];
                $this->listadoDeFechasGlobal["fechaDatepicker"][$ii]=fechaMySQL2DatePicker($row["fecha"]);
                $this->listadoDeFechasGlobal["a"][$ii]='<a id="'.$row["fecha"].'" 
                       datepicker="'.$this->listadoDeFechasGlobal["fechaDatepicker"][$ii].'" 
                       class="enlaceFecha">'.$this->listadoDeFechasGlobal["fechaDatepicker"][$ii].'</a>';
				$ii++;
		        } // fin del while
		        if (!is_null($this->listadoDeFechasGlobal)) { // si no lo recupera, el valor por defecto)
				return $this->listadoDeFechasGlobal; //envia el valor dado
				} else {
				return NULL;
				}
	    mysqli_free_result($result); 
	    mysqli_close($link); 
	} // fin de la funcion
	
	// **********************************************************
	// 6) Div con la información para el histórico
	public function divOpinionResumen($fecha, $asignacion, $alumno) {
		$link=Conectarse(); // y me conecto. //dependiendo del tipo recupero uno u otro.
		$fecha = mysqli_real_escape_string($link,$fecha); // Puedo poner así el real_escape...
		$alumno = mysqli_real_escape_string($link,$alumno);
		$asignacion = mysqli_real_escape_string($link,$asignacion);
	    $Sql ='SELECT id, items, observaciones FROM tb_opiniones WHERE fecha="'.$fecha.'" AND alumno="'.$alumno.'" AND asignacion="'.$asignacion.'"';
	    // $Sql = sprintf($Sql, mysqli_real_escape_string($link,$fecha)); // Seguridad que evita los ataques SQL Injection  	
	    $result=mysqli_query($link,$Sql); // ejecuta la cadena sql y almacena el resultado el $result
        $row=mysqli_fetch_array($result);
        	 if (!is_null($row["id"]) or !empty($row["id"])) { // si no lo recupera, el valor por defecto)
				  	$datos=array("sql"=>$Sql,"id"=>$row["id"],"items"=>$row["items"]
	                   ,"observaciones"=>html_entity_decode($row["observaciones"]));
			        $datos_json=json_encode($datos); // codifica como json...
				  return $datos_json; //envia el valor dado
				  } else {
			      return NULL; // si no envía, cero
				  }
	    mysqli_free_result($result); 
	    mysqli_close($link); 
	} // fin de la funcion
	
	// **********************************************************
	// 8) Funcion que retorna los datos de un item por ID
	// Acepta un valor de ID 
	public function retornaItem ($id) {
		// Recupera el valor de la base de datos
        $link= Conectarse(); // y me conecto. //dependiendo del tipo recupero uno u otro.
        $id = mysqli_real_escape_string($link,$id); // Puedo poner así el real_escape... Seguridad que evita los ataques SQL Injection 
	    $Sql='SELECT * FROM tb_itemsopiniones WHERE iditem="'.$id.'"';    
        // echo $Sql; 
	    $result=mysqli_query($link,$Sql);// ejecuta la cadena sql y almacena el resultado el $result
	    $row=mysqli_fetch_array($result);
		if (!is_null($row["iditem"]) or !empty($row["iditem"])) { // si no lo recupera, el valor por defecto)
				$datos=array("id"=>$row["iditem"],"item"=>iconv("ISO-8859-15", "UTF-8",$row["item"]),
				             "grupo"=>iconv("ISO-8859-15", "UTF-8",$row["grupo"]),"positivo"=>$row["positivo"]);
				// para codificar como json hace falta pasarolo a UTF8
				$salida=json_encode($datos); // codifica como json...		
		} else {					
				$salida=NULL;
		}
	    mysqli_free_result($result); 
	    mysqli_close($link); 
	    return $salida;
	}
	
	// **********************************************************
	// 7) Funcion que retorna el texto de los items
	// Acepta una cadena del tip 9#14#22... /*
	public function itemsElegidos ($cadena) {
	   // Recupera el valor de la base de datos
       $cadenaArray=explode("#",$cadena);
       $cadenaReturn = NULL; // cadena de retorno
       foreach ($cadenaArray as $clave=>$valor) {
		   $datos=json_decode($this->retornaItem($valor));
		   $cadenaReturn.=iconv( "UTF-8","ISO-8859-15",$datos->{"item"})."; ";
		   // Para mostrarlo correctamente se pasa de UTF-8 a ISO-8859-15
	   }
	   if (strlen($cadenaReturn)>2) 
	       { $cadenaReturn=substr($cadenaReturn,0,strlen($cadenaReturn)-2)."."; }
	       else { $cadenaReturn=NULL; }
	   return $cadenaReturn;
	   // return $cadena;
	}
	
	// ***********************************************************
	// 8) Función que acepta una cadena SQL
	// Retorna un array con la información
	public function retornaValores($cadenaSQL) {
		$link=Conectarse(); // y me conecto. //dependiendo del tipo recupero uno u otro.
	    // $Sql ='SELECT id, items, observaciones FROM tb_opiniones WHERE fecha="'.$fecha.'" AND alumno="'.$alumno.'" AND asignacion="'.$asignacion.'"';
	    // $Sql = sprintf($Sql, mysqli_real_escape_string($link,$fecha)); // Seguridad que evita los ataques SQL Injection  	
	    $result=mysqli_query($link,$cadenaSQL); // ejecuta la cadena sql y almacena el resultado el $result
        $ii=0;
        $datos=array();
        while ($row=mysqli_fetch_array($result)) {
			if (!is_null($row["id"]) or !empty($row["id"])) { // si no lo recupera, el valor por defecto)
				  	$datos[$ii]=array("id"=>$row["id"],"items"=>$row["items"],"asignacion"=>$row["asignacion"],"alumno"=>$row["alumno"]
	                   ,"observaciones"=>html_entity_decode($row["observaciones"]),"fecha"=>$row["fecha"]);
	                   // ,"observaciones"=>$row["observaciones"],"fecha"=>$row["fecha"]);
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
	} // Fin del 8
	
	// ***********************************************************
	// 9) Función que acepta una cadena SQL
	// Y retorna información estadistica del resultado: del tipo ITEM es la key y el valor la frecuencia que aparece.
	public function itemsEstadistica($cadenaSQL) {
		$link=Conectarse(); // y me conecto. //dependiendo del tipo recupero uno u otro.
	    $result=mysqli_query($link,$cadenaSQL); // ejecuta la cadena sql y almacena el resultado el $result
        $cadenaReturn=null;
        while ($row=mysqli_fetch_array($result)) {
			if (strlen($row["items"])>0) { // si no lo recupera, el valor por defecto)
				  	$cadenaReturn.=$row["items"]."#";
			}
		}
		mysqli_free_result($result); 
	    mysqli_close($link); 	    
	    if (!is_null($cadenaReturn)) {
			$cadenaReturn=substr($cadenaReturn,0,strlen($cadenaReturn)-1);
			// return $cadenaReturn;
			$datos= explode("#",$cadenaReturn); // convierte la cadena en array
			asort($datos);
			// $datosOrdNoRepetidos = array_unique($datos,SORT_NUMERIC);	    
			$datosOrdNoRepetidos=array_count_values($datos);	    
			return $datosOrdNoRepetidos;
		} else {
			return NULL;
		}
	} // Fin del 9
	
	// ***********************************************************
	// 10) Función que acepta una cadena SQL - La misma que la función 9 -
	// Y retorna información estadistica del resultado POR ALUMNO...
	public function itemsEstadisticaPorAlumno($cadenaSQL) {
		$link=Conectarse(); // y me conecto. //dependiendo del tipo recupero uno u otro.
	    $result=mysqli_query($link,$cadenaSQL); // ejecuta la cadena sql y almacena el resultado el $result
        $cadenaReturn=null;
        while ($row=mysqli_fetch_array($result)) {
			$cadenaReturn.=$row["alumno"]."-".$row["items"]."*";
		}
		mysqli_free_result($result); 
	    mysqli_close($link); 
	    if (!is_null($cadenaReturn)) {	    
			$cadenaReturn=substr($cadenaReturn,0,strlen($cadenaReturn)-1);
			return $cadenaReturn; 
	    } else {
			return null;	
		}
	} // Fin del 10

}

?>
