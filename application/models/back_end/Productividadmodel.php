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
			);

			foreach($res->result_array() as $key){
				$temp = array();
			    $temp[] = (string)$key["fecha"]; 
			    $temp[] = (int) $key['puntos'];
		 	    $temp[] = (string) $v = ($key['puntos']==0) ? null: $key['puntos'];
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


	/*************CALIDAD*******************/

		public function listaCalidad($desde,$hasta,$trabajador){

			$this->db->select("sha1(p.id) as hash,
				p.*,
				CONCAT(u.nombres,' ',u.apellidos) as 'tecnico',
		        if(p.fecha!='0000-00-00', DATE_FORMAT(p.fecha,'%Y-%m-%d'),'') as 'fecha',
		        if(p.fecha_2davisita!='0000-00-00', DATE_FORMAT(p.fecha_2davisita,'%Y-%m-%d'),'') as 'fecha_2davisita'
			");

			$this->db->join('usuarios u', 'u.rut = p.rut_tecnico', 'left');

			if($desde!="" and $hasta!=""){
				$this->db->where("p.fecha BETWEEN '".$desde."' AND '".$hasta."'");	
			}

			if($trabajador!=""){
				$this->db->where('p.rut_tecnico', $trabajador);
			}

			$this->db->order_by('p.fecha', 'desc');
			$res=$this->db->get('productividad_calidad p');
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

		public function formCalidad($data){
			if($this->db->insert('productividad_calidad', $data)){
				return TRUE;
			}
			return FALSE;
		}

		public function existeOrdenCalidad($orden){
			$this->db->where('ot', $orden);
			$res=$this->db->get('productividad_calidad');
			if($res->num_rows()>0){
				return TRUE;
			}
			return FALSE;
		}

		public function actualizacionCalidad(){
		    $this->db->select('ultima_actualizacion');
		    $this->db->order_by('id', 'desc');
		    $res=$this->db->get('productividad_calidad',1);
		    $row = $res->row_array();
		    return $row["ultima_actualizacion"];
		}

}