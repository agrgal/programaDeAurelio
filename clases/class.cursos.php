<?php
/*
 * misCursos.php
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

class misCursos
{

	var $listaDeCursos; // array que guarda una lista con los cursos existentes
	var $listaDeNiveles; // array que guarda una lista con los cursos existentes
	var $esteCurso; // array que guarda el curso actual, con su curso - nivel - letra; ejemplo: 1 ESO C
	
	// *******************************************************
	// 1) Funcion que retorna los distintos cursos que existen en el listado de alumnos
	public function listarCursos () {
		// Recupera el valor de la base de datos
        $link=Conectarse(); // y me conecto. //dependiendo del tipo recupero uno u otro.
	    $Sql='SELECT DISTINCT unidad FROM tb_alumno ORDER BY unidad';
        // echo $Sql;
	    $result=mysqli_query($link,$Sql);// ejecuta la cadena sql y almacena el resultado el $result
	    $ii=0; // contador 
	    while ($row=mysqli_fetch_array($result)) {
			$this->listaDeCursos['largo'][$ii]=$row['unidad']; 
			$this->listaDeCursos['curso'][$ii]=substr($row['unidad'],0,1); 	
			$this->listaDeCursos['clase'][$ii]=strtoupper(substr($row['unidad'],-1));
			$this->listaDeCursos['nivel'][$ii]=strtoupper(trim(substr($row['unidad'],1,-1)));
			$this->listaDeCursos['alumnado'][$ii]=$this->devuelveListaAlumnos($row['unidad']); 
			$this->listaDeCursos['corto'][$ii]=$this->listaDeCursos["curso"][$ii].$this->listaDeCursos["nivel"][$ii].$this->listaDeCursos["clase"][$ii];
			$ii++;
			}
			if (!is_null($this->listaDeCursos)) { // si no lo recupera, el valor por defecto)
		    return $this->listaDeCursos; //envia el valor dado
		    } else {
		    return NULL;
	        }
	   mysqli_free_result($result); 
	   mysqli_close($link);
	}	
	// *******************************************************
	
	// 2) Funcion que retorna los distintos valores de niveles dentro de la lista de cursos
	public function listarNiveles () {
	   $this->listarCursos(); // llama a la función que lista los cursos
	   $this->listaDeNiveles["nivel"] = array_unique($this->listaDeCursos['nivel']);	   
	   sort($this->listaDeNiveles["nivel"]);
	   foreach($this->listaDeNiveles["nivel"] as $clave => $valor) {
		   $this->listaDeNiveles["input"][$clave]=
		   	      '<input type="radio" id="'.$valor.'" name="niveles"><label for="'.$valor.'">'.$valor.'</label>';
	   }
	}
	// *******************************************************
	
	// 3) Devuelve un curso por clave
	public function devuelveCurso ($cual) {
	   $this->listarNiveles(); // carga cursos y niveles.
	   $this->esteCurso["curso"] = $this->listaDeCursos["curso"][$cual];
	   $this->esteCurso["clase"] = $this->listaDeCursos["clase"][$cual];
	   $this->esteCurso["nivel"] = $this->listaDeCursos["nivel"][$cual];
	   $this->esteCurso["alumnado"] = $this->listaDeCursos["alumnado"][$cual];
	   $id=$this->listaDeCursos["curso"][$cual].$this->listaDeCursos["nivel"][$cual].$this->listaDeCursos["clase"][$cual];
	   $this->esteCurso["corto"] = $id;
	   $this->esteCurso["div"]=
	      '<div id="'.$id.'" name="'.$cual.'" class="divcurso" nivel="'.$this->listaDeCursos["nivel"][$cual].'" alumnado="'.$this->listaDeCursos["alumnado"][$cual].'">'
	      .'<p>'.$this->listaDeCursos["curso"][$cual].'º '.$this->listaDeCursos["clase"][$cual]
	      .'</br>'.$this->listaDeCursos["nivel"][$cual].'</p></div>';
	      // OJO el div lleva una propiedad INVENTADA "alumnado", que pasa información a asignaciones.php
	   /* $this->esteCurso["input"]=
	      '<input type="radio" name="cursos" id="'.$id.'">'
	      .'<label for="'.$id.'">'.$this->listaDeCursos["curso"][$cual].'º '.$this->listaDeCursos["clase"][$cual]
	      .'</br>'.$this->listaDeCursos["nivel"][$cual].'</label>'; */       	   
	}
	
	// 3b) Devuelve clave del curso por nombre corto
	public function devuelveCursoCorto ($nombreCorto) {
		$this->listarNiveles(); // carga cursos y niveles.
        return array_search($nombreCorto,$this->listaDeCursos["corto"]);
	}
	
	// 3c) Devuelve curso corto por nombre unidad. Doy 1 ESO E , y me devuelve 1ESOE
	public function devuelveCursoCortoPorUnidad ($nombreUnidad) {
		$this->listarNiveles(); // carga cursos y niveles.
		$clave = array_search($nombreUnidad,$this->listaDeCursos["largo"]);
        return $this->listaDeCursos["corto"][$clave];
	}
	
	// 3d) Devuelve unidad por nombre corto. Doy 1ESOE , y me devuelve 1 ESO E
	public function devuelverUnidadPorCursoCorto ($corto) {
		$this->listarNiveles(); // carga cursos y niveles.
		$clave = array_search($corto,$this->listaDeCursos["corto"]);
        return $this->listaDeCursos["largo"][$clave];
	}
	
	// *******************************************************
	
	
	
	// 4) Devuelve la lista del alumnado que pertenece a un curso, separados por #
	public function devuelveListaAlumnos ($curso) {
		// Recupera el valor de la base de datos
		$listado="";
        $link=Conectarse(); // y me conecto. //dependiendo del tipo recupero uno u otro.
	    $Sql='SELECT idalumno FROM tb_alumno WHERE unidad LIKE "%s" ORDER BY idalumno';
	    $Sql = sprintf($Sql, mysqli_real_escape_string($link,$curso)); // Seguridad que evita los ataques SQL Injection		
        // echo $Sql;
	    $result=mysqli_query($link,$Sql);// ejecuta la cadena sql y almacena el resultado el $result
	    $ii=0; // contador 
	    while ($row=mysqli_fetch_array($result)) {
			$listado.=$row["idalumno"]."#";
			}
			if (!is_null($listado)) { // si no lo recupera, el valor por defecto)
				return substr($listado,0,-1); //envia el valor dado
		    } else {
		    return NULL;
	        }
	   mysqli_free_result($result); 
	   mysqli_close($link);
	}
	
	// 5) Dada una asignación, dar el listado de los alumnos, viendo si hay repetidos
	public function devuelveAsignacionLarga ($asignacionCorta) {
		$arrayAsignacionCorta=explode("#",$asignacionCorta);
		$this->listarCursos(); // llama a los cursos	
		// a) obtiene una cadena de alumnos..
		foreach($arrayAsignacionCorta as $clave => $valor) {
			if (!(is_numeric($valor))) { // Si no es numérico (es curso, entonces...)
				$claveCurso=$this->devuelveCursoCorto($valor); // obtiene su clave
				$arrayAsignacionCorta[$clave] = $this->devuelveListaAlumnos($this->listaDeCursos["largo"][$claveCurso]);
				// cambia el array por el resultado de devuelvelistaAlumnos...
				// $arrayAsignacionCorta[$clave]=$this->listaDeCursos["largo"][$claveCurso];
			} 
			$b.=$arrayAsignacionCorta[$clave]."#";
		}
		$b = substr($b,0,-1); // Quita el último comodín antes de pasar al siguiente paso... Si no, reconoce un elemento vacío
		// b) Pasar a array de nuevo. Quita duplicados y ordena.
		$arrayAsignacionCorta=array_unique(explode("#",$b)); // ya lo tengo otra vez en forma de [número]#. Quito duplicados
		sort($arrayAsignacionCorta); // ordeno el array...
		// c) Paso el array a cadena 
		$b=implode("#",$arrayAsignacionCorta);
		return $b; // Quita el primer caracter que es un comodín
	}
	
	// 6) Curso de una asignacion Larga
	// Una retahila de ID de alumnos, extraer los distintos cursos 
	public function devuelveCursosdeAsignacion ($asignacionLarga, $tipo) {
		$arrayAsignacionLarga=explode("#",$asignacionLarga); // alumnos de la asignacion
		$this->listarCursos(); // llama a los cursos	
		foreach($this->listaDeCursos["alumnado"] as $clave => $valor) {
			 $alumnosCurso=explode("#",$valor); // Obtiene un array con los alumnos de ese curso
			 $interseccion=array_intersect($alumnosCurso,$arrayAsignacionLarga); // intersecta ambos arrays
			 if (count($interseccion)>0) { // Si el número de elementos del array es mayor que cero...
				 if ($tipo=="0") {$cursos[]=$this->listaDeCursos["corto"][$clave]; } //añade a $cursos los NOMBRES CORTOS de los cursos...
				 if ($tipo=="1") {$cursos[]=$this->listaDeCursos["largo"][$clave]; } //añade a $cursos los NOMBRES CORTOS de los cursos...
			 }
		} // fin del foreach
		$b=implode("#",$cursos);
		return $b; // Quita el primer caracter que es un comodín	
	}
	
	// 7) Del ID de una asignación, devuelve los datos
	// Lista de alumnados de la asignacion
	public function devuelveAlumnosAsignacion ($idasignacion) {
		$link=Conectarse(); // y me conecto. //dependiendo del tipo recupero uno u otro.
	    $Sql ='SELECT datos FROM tb_asignaciones WHERE idasignacion="'.$idasignacion.'"';
	    // $Sql = sprintf($Sql, mysqli_real_escape_string($link,$fecha)); // Seguridad que evita los ataques SQL Injection  	
	    $result=mysqli_query($link,$Sql); // ejecuta la cadena sql y almacena el resultado el $result
        $row=mysqli_fetch_array($result);
        	  if (!is_null($row["datos"]) or !empty($row["datos"])) { // si no lo recupera, el valor por defecto)
				  $datos = $this->devuelveAsignacionLarga($row["datos"]);
			      return $datos; //envia el valor dado como una cadena de caracteres 
			  } else {
			      return NULL; // si no envía, cero
			  }
	    mysqli_free_result($result); 
	    mysqli_close($link); 
	}
	
}
