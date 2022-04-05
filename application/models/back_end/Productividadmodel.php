<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Productividadmodel extends CI_Model {

	public function __construct(){
		parent::__construct();
	}
	
	public function insertarVisita($data){
		if($this->db->insert('aplicaciones_visitas', $data)){
			return TRUE;
		}
		return FALSE;
	}

	/*************PRODUCTIVIDAD*************/

		public function listaDetalle($desde,$hasta,$trabajador){

			$this->db->select("sha1(p.id) as hash,
				p.*,
				CONCAT(u.nombres,' ',u.apellidos) as 'tecnico',
		        if(p.fecha!='0000-00-00', DATE_FORMAT(p.fecha,'%Y-%m-%d'),'') as 'fecha'
			");

			$this->db->join('usuarios u', 'u.rut = p.rut_tecnico', 'left');

			if($desde!="" and $hasta!=""){
				$this->db->where("p.fecha BETWEEN '".$desde."' AND '".$hasta."'");	
			}

			if($trabajador!=""){
				$this->db->where('p.rut_tecnico', $trabajador);
			}
			$this->db->order_by('p.fecha', 'desc');
			$res=$this->db->get('productividad p');
			// echo $this->db->last_query();
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

		public function formDetalle($data){
			if($this->db->insert('productividad', $data)){
				return TRUE;
			}
			return FALSE;
		}

		public function existeOrden($orden){
			$this->db->where('ot', $orden);
			$res=$this->db->get('productividad');
			if($res->num_rows()>0){
				return TRUE;
			}
			return FALSE;
		}

		public function getIdTecnicoPorRut($rut){
			$this->db->where('rut', $rut);
			$res=$this->db->get('usuarios');
			$row=$res->row_array();
			return $row["id"];
		}

		public function listaTrabajadores(){
			$this->db->select("concat(substr(replace(rut,'-',''),1,char_length(replace(rut,'-',''))-1),'-',substr(replace(rut,'-',''),char_length(replace(rut,'-','')))) as 'rut_format',
				empresa,id,rut,
			    CONCAT(nombres,'  ',apellidos) as 'nombre_completo',
			    CONCAT(SUBSTRING_INDEX(nombres, ' ', '1'),'  ',SUBSTRING_INDEX(SUBSTRING_INDEX(apellidos, ' ', '-2'), ' ', '1')) as 'nombre_corto',
			");
			
			if($this->session->userdata('id_perfil')==4){
				$this->db->where('rut', $this->session->userdata('rut'));
			}

			$this->db->order_by('nombres', 'asc');
			$res=$this->db->get("usuarios");
			if($res->num_rows()>0){
				$array=array();
				foreach($res->result_array() as $key){
					$temp=array();
					$temp["id"]=$key["rut"];
					$temp["text"]=$key["rut_format"]."  |  ".$key["nombre_corto"];
					$array[]=$temp;
				}
				return json_encode($array);
			}
			return FALSE;
		}


		public function rankingTecnicos($desde,$hasta,$trabajador){
			$this->db->select("
			    CONCAT(SUBSTRING_INDEX(u.nombres, ' ', '1'),'  ',SUBSTRING_INDEX(SUBSTRING_INDEX(u.apellidos, ' ', '-2'), ' ', '1')) as 'tecnico',

				SUM(CASE 
	             WHEN p.estado = 'Completado' THEN p.puntaje
	             ELSE 0
	            END) AS cantidad_ok,

	            SUM(CASE 
	             WHEN p.derivado = 'GSA' THEN p.puntaje
	             ELSE 0
	            END) AS cantidad_gsa,

	            SUM(CASE 
	             WHEN p.derivado = 'Redes' THEN p.puntaje
	             ELSE 0
	            END) AS cantidad_redes");

			if($desde!="" and $hasta!=""){
				$this->db->where("p.fecha BETWEEN '".$desde."' AND '".$hasta."'");	
			}

			if($trabajador!=""){
				$this->db->where('p.rut_tecnico', $trabajador);
			}

			$this->db->group_by('p.rut_tecnico');
			$this->db->join('usuarios u', 'u.rut = p.rut_tecnico', 'left');

			$res=$this->db->get('productividad p');
			$cabeceras = array(
				"Técnico",
				"Completado",array('role'=> 'annotation'),
				"GSA",array('role'=> 'annotation'),
				"Redes",array('role'=> 'annotation')
			);
			$array=array();
			$array[]=$cabeceras;
			$contador=0;

			foreach($res->result_array() as $key){
				$temp=array();
				$temp[] = (string)$key["tecnico"];
	 		    $temp[] = (int) $v = ($key['cantidad_ok']==0) ? null: $key['cantidad_ok'];
	 		    $temp[] = (string) $v = ($key['cantidad_ok']==0) ? null: $key['cantidad_ok'];
	 		    $temp[] = (int) $v = ($key['cantidad_gsa']==0) ? null: $key['cantidad_gsa'];
	 		    $temp[] = (string) $v = ($key['cantidad_gsa']==0) ? null: $key['cantidad_gsa'];
	 		    $temp[] = (int) $v = ($key['cantidad_redes']==0) ? null: $key['cantidad_redes'];
	 		    $temp[] = (string) $v = ($key['cantidad_redes']==0) ? null: $key['cantidad_redes'];
				$array[]=$temp;
			}
			return $array;
		}

		// public function graficoLicencias($empresa){
			// 	$this->db->select("MONTH(fecha_inicio) as mes,
			// 		 YEAR(fecha_inicio) as anio,
			// 		 count(if(id_tipo_licencia= '1', 1 , NULL)) as 'Enfermedad',
			// 		 count(if(id_tipo_licencia = '2', 1 , NULL)) as 'Accidente',
			// 		 count(if(id_tipo_licencia = '3', 1 , NULL)) as 'Pre/post natal' ",
			// 	 FALSE);

			// 	$this->db->from('usuario_licencias lic');
			// 	$this->db->join('usuario u', 'u.id = lic.id_usuario', 'left');
			// 	$this->db->where('fecha_inicio > (NOW() - INTERVAL 5 MONTH)');
			// 	$this->db->where('u.empresa', $empresa);
			// 	$this->db->group_by('MONTH(fecha_inicio)');
			// 	$this->db->group_by('YEAR(fecha_inicio)');
			// 	$this->db->order_by('fecha_inicio', 'asc');
			// 	$res=$this->db->get();
			// 	$array = array();
				
			// 	$array[]= array(
			// 		"Fecha",
			// 		"Enfermedad",
			// 		array('role'=> 'annotation'),
			// 		"Accidente",
			// 		array('role'=> 'annotation'),
			// 		"Pre/post natal",
			// 		array('role'=> 'annotation')
			// 	);

			// 	foreach($res->result_array() as $key){
			// 		$temp = array();
			// 	    $temp[] = (string)$this->mes_corto($key["mes"])."-".substr($key["anio"], 2, 4); 
			// 	    $temp[] = (int) $key['Enfermedad'];
			//  	    $temp[] = (string) $v = ($key['Enfermedad']==0) ? null: $key['Enfermedad'];
			// 	    $temp[] = (int) $key['Accidente'];
			//  	    $temp[] = (string) $v = ($key['Accidente']==0) ? null: $key['Accidente'];
			// 	    $temp[] = (int) $key['Pre/post natal'];
			//  	    $temp[] = (string) $v = ($key['Pre/post natal']==0) ? null: $key['Pre/post natal'];
			// 	    $array[] = $temp;
			// 	}

			// 	return $array;
		// }
		

		public function puntosPorFechas($desde,$hasta,$trabajador){
			$this->db->select("if(fecha!='0000-00-00', DATE_FORMAT(fecha,'%d-%m-%Y'),'') as 'fecha',
				 SUM(puntaje) as puntos

		         ",
			 FALSE);

			if($desde!="" and $hasta!=""){
				$this->db->where("fecha BETWEEN '".$desde."' AND '".$hasta."'");	
			}

			if($trabajador!=""){
				$this->db->where('rut_tecnico', $trabajador);
			}

			$this->db->group_by('(fecha)');
			$this->db->order_by('fecha', 'asc');
			$res=$this->db->get("productividad");
			$array = array();
			
			$array[]= array(
				"Fecha",
				"Puntos",
				array('role'=> 'annotation'),
				array('role'=> 'annotationText'),
			);

			foreach($res->result_array() as $key){
				$temp = array();
			    $temp[] = (string)$key["fecha"]; 
			    $temp[] = (int) $key['puntos'];
		 	    $temp[] = (string) $v = ($key['puntos']==0) ? null: $key['puntos'];
		 	    $temp[] = strtotime($key["fecha"]);

			    $array[] = $temp;
			}
			return $array;
		}

		public function distribucionTipos($desde,$hasta,$trabajador){
		
			if($trabajador!=""){
				$this->db->select('
					(SELECT count(id)
						from productividad 
						WHERE tipo_actividad REGEXP "Alta" 
						and fecha BETWEEN "'.$desde.'" AND "'.$hasta.'"
						and rut_tecnico="'.$trabajador.'"
					) as altas,

					(SELECT count(id)
						from productividad 
						WHERE tipo_actividad REGEXP "Reparación" 
						and fecha BETWEEN "'.$desde.'" AND "'.$hasta.'"
						and rut_tecnico="'.$trabajador.'"
					) as reparaciones,

					(SELECT count(id)
						from productividad 
						WHERE tipo_actividad NOT LIKE "%Alta%" and tipo_actividad NOT LIKE "%Reparación%"
						and fecha BETWEEN "'.$desde.'" AND "'.$hasta.'"
						and rut_tecnico="'.$trabajador.'"
					) as otros
				');

			}else{
				$this->db->select('
					(SELECT count(id)
						from productividad 
						where tipo_actividad REGEXP "Alta" 
						and fecha BETWEEN "'.$desde.'" AND "'.$hasta.'"
					) as altas,
					(SELECT count(id)
						from productividad 
						where tipo_actividad REGEXP "Reparación" 
						and fecha BETWEEN "'.$desde.'" AND "'.$hasta.'"
					) as reparaciones,
					(SELECT count(id)
						from productividad 
						WHERE tipo_actividad NOT LIKE "%Alta%" and tipo_actividad NOT LIKE "%Reparación%"
						and fecha BETWEEN "'.$desde.'" AND "'.$hasta.'"
					) as otros
				');
			}

			$this->db->group_by('altas');
			$res=$this->db->get('productividad');
			$temp=array();
			$temp[] = array("Tipo Actividad","Cantidad"); 
			
			foreach($res->result_array() as $key){
				$temp[] = array("Altas",(int)$key["altas"]); 
				$temp[] = array("Reparación",(int)$key["reparaciones"]); 
				$temp[] = array("Otros",(int)$key["otros"]); 
			}

		    $filas = $temp;
			return $filas;
		}

		public function distribucionOt($desde,$hasta,$trabajador){
			if($trabajador!=""){

				$this->db->select('
					(SELECT count(id)
						from productividad 
						where estado_ot REGEXP "OT OK" 
						and fecha BETWEEN "'.$desde.'" AND "'.$hasta.'"
						and rut_tecnico="'.$trabajador.'"
					) as ok,
					(SELECT count(id)
						from productividad 
						where estado_ot REGEXP "DRIVE NO DETECTA REGISTRO" 
						and fecha BETWEEN "'.$desde.'" AND "'.$hasta.'"
						and rut_tecnico="'.$trabajador.'"
					) as no_ok');

			}else{

				$this->db->select('
					(SELECT count(id)
						from productividad 
						where estado_ot REGEXP "OT OK" 
						and fecha BETWEEN "'.$desde.'" AND "'.$hasta.'"
					) as ok,
					(SELECT count(id)
						from productividad 
						where estado_ot REGEXP "DRIVE NO DETECTA REGISTRO" 
						and fecha BETWEEN "'.$desde.'" AND "'.$hasta.'"
					) as no_ok');

			}

			$this->db->group_by('ok');
			$res=$this->db->get('productividad');
			$temp=array();
			$temp[] = array("Estado OT","Cantidad"); 
			
			foreach($res->result_array() as $key){
				$temp[] = array("OT OK",(int)$key["ok"]); 
				$temp[] = array("OT Sin registro",(int)$key["no_ok"]); 
			}

		    $filas = $temp;
			return $filas;
		}

		public function puntosTipoOrden($desde,$hasta,$trabajador){
			$this->db->select("tipo_actividad,
				 SUM(puntaje) as puntos",
			 FALSE);

			if($desde!="" and $hasta!=""){
				$this->db->where("fecha BETWEEN '".$desde."' AND '".$hasta."'");	
			}

			if($trabajador!=""){
				$this->db->where('rut_tecnico', $trabajador);
			}

			$this->db->group_by('tipo_actividad');
			$this->db->order_by('tipo_actividad', 'asc');
			$res=$this->db->get("productividad");
			$array = array();
			
			$array[]= array(
				"Tipo orden",
				"Puntos",
			);

			foreach($res->result_array() as $key){
				$temp = array();
			    $temp[] = (string)$key["tipo_actividad"]; 
			    $temp[] = (int) $key['puntos'];
			    $array[] = $temp;
			}
			return $array;
		}

		public function totalPuntosPorFecha($desde,$hasta,$trabajador){
			$this->db->select("format(SUM(puntaje),0,'de_DE') as puntos", FALSE);

			if($desde!="" and $hasta!=""){
				$this->db->where("fecha BETWEEN '".$desde."' AND '".$hasta."'");	
			}

			if($trabajador!=""){
				$this->db->where('rut_tecnico', $trabajador);
			}

			$this->db->order_by('fecha', 'asc');
			$res=$this->db->get("productividad");
			$row=$res->row_array();
			return $row["puntos"];
		}

		public function actualizacionProductividad(){
		    $this->db->select('ultima_actualizacion');
		    $this->db->order_by('id', 'desc');
		    $res=$this->db->get('productividad',1);
		    $row = $res->row_array();
		    return $row["ultima_actualizacion"];
		}


	

	/*************RESUMEN*******************/

	
		public function date_range($first, $last, $step = '+1 day', $output_format = 'd/m/Y' ) {
		    $dates = array();
		    $current = strtotime($first);
		    $last = strtotime($last);
		    while( $current <= $last ) {
		        $dates[] = date($output_format, $current);
		        $current = strtotime($step, $current);
		    }

		    return $dates;
		}

		

		function dia($dia){
			switch ($dia) {
				case '1':$dia="L";break;
				case '2':$dia="M";break;
				case '3':$dia="M";break;
				case '4':$dia="J";break;
				case '5':$dia="V";break;
				case '6':$dia="S";break;
				case '0':$dia="D";break;

			}
			return $dia;
		}

		function meses($mes){
			switch ($mes) {
				case '1':$mes="En";break;
				case '2':$mes="Feb";break;
				case '3':$mes="Mar";break;
				case '4':$mes="Ab";break;
				case '5':$mes="May";break;
				case '6':$mes="Jun";break;
				case '7':$mes="Jul";break;
				case '8':$mes="Ago";break;
				case '9':$mes="Sep";break;
				case '10':$mes="Oct";break;
				case '11':$mes="Nov";break;
				case '12':$mes="Dic";break;
			}
			return $mes;
		}

		function fecha_to_str($fecha){
			$fecha1=explode('-',$fecha);
			$anio=$fecha1[0];  
			$mes=$fecha1[1];  
			$dia=$fecha1[2];  
			$dia_semana=date('w', strtotime($fecha));
			// return $this->dia($dia_semana)."".$this->meses($mes)." ".$dia;
			return $this->dia($dia_semana)."".$dia."-".$mes;
		}

		public function cabecerasResumen($desde,$hasta,$trabajador){
			$array_fechas = $this->date_range($desde,$hasta,"+1 day", "Y-m-d");
			$cabeceras = array();
			$cabeceras[] = "Zona";
			$cabeceras[] = "Trabajador";

			foreach($array_fechas as $fecha){
				$cabeceras[] = $this->fecha_to_str($fecha);
			}
			
			$cabeceras[] = "Total";
			return $cabeceras;
		}
		
		public function listaResumen($desde,$hasta,$trabajador){
			$this->db->select("sha1(p.id) as hash,
				p.id as id,
				CONCAT(u.nombres,' ',u.apellidos) as 'trabajador',
				a.area as area,
				if(sum(puntaje)!=0,sum(puntaje),'')  as total,
				p.rut_tecnico as rut_tecnico,
				if(p.fecha!='0000-00-00', DATE_FORMAT(p.fecha,'%d-%m-%Y'),'') as 'fecha'");
			$this->db->join('usuarios u', 'u.rut = p.rut_tecnico', 'left');
			$this->db->join('usuarios_areas a', 'u.id_area = a.id', 'left');

			if($desde!="" and $hasta!=""){$this->db->where("p.fecha BETWEEN '".$desde."' AND '".$hasta."'");	}
			if($trabajador!=""){$this->db->where('p.rut_tecnico', $trabajador);}

			$this->db->order_by('u.nombres', 'asc');
			$this->db->group_by('u.id');
			$res=$this->db->get('productividad p');

			$array_fechas = $this->date_range($desde,$hasta,"+1 day", "Y-m-d");
			$array = array();

			foreach($res->result_array() as $key){
				$temp=array();

				$temp["Zona"] = $key["area"];
				$temp["Trabajador"] = $key["trabajador"];

				foreach($array_fechas as $fecha){
						$this->db->select("if(sum(puntaje)!=0,sum(puntaje),0)  as puntaje");
						$this->db->where('fecha="'.$fecha.'" AND `rut_tecnico` = "'.$key["rut_tecnico"].'"');
						$res2=$this->db->get('productividad');
						
						if($res2->num_rows()>0){
							$puntaje=0;
							foreach($res2->result_array() as $key2){
								$puntaje = $puntaje+$key2["puntaje"];
							}
						
							$temp[$this->fecha_to_str($fecha)] = $puntaje;
						}else{
							$temp[$this->fecha_to_str($fecha)] = "";
						}
				}
				$temp["Total"] = $key["total"];
				$array[]=$temp;

			}
			return $array;
		}



		public function detalleResumen(){

		}

}