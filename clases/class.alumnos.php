<?php
/*
 * misAlumnos.php
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

class misAlumnos
{

    var $esteAlumno;
		
	// *******************************************************
	// 1) Funcion que retorna las propiedades de un Alumno y las guarda en esteAlumno
	public function devuelveAlumno ($id) {
		// Recupera el valor de la base de datos
        $link=Conectarse(); // y me conecto. //dependiendo del tipo recupero uno u otro.
	    $Sql='SELECT * FROM tb_alumno WHERE idalumno="%s"';
	    $Sql = sprintf($Sql, mysqli_real_escape_string($link,$id)); // Seguridad que evita los ataques SQL Injection        
        // echo $Sql; 
	    $result=mysqli_query($link,$Sql);// ejecuta la cadena sql y almacena el resultado el $result
	    $row=mysqli_fetch_array($result);
	    if (!is_null($row["idalumno"]) or !empty($row["idalumno"])) { // si no lo recupera, el valor por defecto)
                $this->esteAlumno["idalumno"]=$row["idalumno"];
                $this->esteAlumno["nombre"]=$row["alumno"];
                $this->esteAlumno["nombre2"]=cambiarnombre($row["alumno"]);
				$this->esteAlumno["unidad"]=$row["unidad"];	 
				/* $this->esteAlumno["div"]=
					'<div id="'.$id.'" name="'.$row["alumno"].'" class="divalumno"><p>%s: '
					.$row["alumno"].'</p>
					</div>'; */
				/* $this->esteAlumno["div"]=
					'<div id="'.$id.'" name="'.$row["alumno"].'" class="divalumno"><p>%s: '
					.$row["alumno"].'</p>
					<img src="./imagenes/iconos/chicochica.png" width="50px" 
					height="auto" title="ID: '.$row["idalumno"].'.- Curso: '.$row["unidad"].'"></div>'; */
				/* $this->esteAlumno["div"]= //formateado en tabla
					'<div id="'.$id.'" name="'.$row["alumno"].'" class="divalumno" orden="%s" unidad="'.$row["unidad"].'">
					<table title="'.cambiarnombre($row["alumno"]).'">
					<tbody><tr><td><p>%s: '.retornaNombre($row["alumno"]).'</p></td>
					<td>
					<img src="./imagenes/iconos/chicochica.png" title="ID: '.$row["idalumno"].'.- Curso: '.$row["unidad"].'">
					</td></tr>
					</tbody></table></div>'; */
					 if (file_exists("./upload/".$row["idalumno"].".png") || file_exists("../../upload/".$row["idalumno"].".png")) {
						 $this->esteAlumno["foto"]="./upload/".$row["idalumno"].".png";	
					 } else if (file_exists("./upload/".$row["idalumno"].".jpg") || file_exists("../../upload/".$row["idalumno"].".jpg")) {
						 $this->esteAlumno["foto"]="./upload/".$row["idalumno"].".jpg";
					 } else {
						 $this->esteAlumno["foto"]="./imagenes/iconos/chicochica.png";
					 } 
				 
				 // **************************************
				 // Opción para asignaciones, opiniones...
				 // ************************************** 
				  $this->esteAlumno["div"]= //formateado en tabla. Con dos parámetros , %s en orden, y antes de ponerle el nombre.
					'<div id="'.$id.'" name="'.$row["alumno"].'" 
					title="ID: '.$row["idalumno"].'.- Curso: '.$row["unidad"].' - '.cambiarnombre($row["alumno"])
					.'" class="divalumno" orden="%s" unidad="'.$row["unidad"].'">
					<div id="image"
					style="background-image: url('.$this->esteAlumno["foto"].'); 
					background-size: 50px 50px; 
					background-repeat: no-repeat; background-position: center center; opacity: 0.5;
					width: 127px; height: 75px; border: 0px solid black;">
					</div>
					<div id="texto" style="position: absolute; border: 0px solid black; bottom: 0px; left: 0px; width: 127px; height: auto;">
					<p>%s: '.retornaNombre($row["alumno"]).'</p>
					</div>
				    </div>'; 
				   // **************************************
				   // Opción para listado de alumnos: fotos...
				   // ************************************** 
				   $this->esteAlumno["divFotos"]= //formateado en tabla. Con dos parámetros , %s en orden, y antes de ponerle el nombre.
					'<div id="'.$id.'" name="'.$row["alumno"].'" 
					title="ID: '.$row["idalumno"].'.- Curso: '.$row["unidad"].' - '.cambiarnombre($row["alumno"])
					.'" class="divalumno2" orden="%s" unidad="'.$row["unidad"].'">
					<div id="image">
					  <img src="'.$this->esteAlumno["foto"].'">
					</div>
					<div id="texto">
					<p>%s: '.retornaNombre($row["alumno"]).' (%s)</p>
					</div>
				    </div>'; 
		} 
	    mysqli_free_result($result); 
	    mysqli_close($link);
	}	
	// *******************************************************	

}

?>
