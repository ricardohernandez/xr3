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

		public function listaDetalle($desde,$hasta,$trabajador,$jefe){

			$this->db->select("sha1(p.id) as hash,
				p.*,
				TRIM(puntaje)+0 as puntaje,
				CONCAT(u.nombres,' ',u.apellidos) as 'tecnico',
		        if(p.fecha!='0000-00-00', DATE_FORMAT(p.fecha,'%Y-%m-%d'),'') as 'fecha'
			");


			if($desde!="" and $hasta!=""){
				$this->db->where("p.fecha BETWEEN '".$desde."' AND '".$hasta."'");	
			}

			if($trabajador!=""){
				$this->db->where('p.rut_tecnico', $trabajador);
			}


			if($jefe!=""){
				$this->db->where('u.id_jefe', $jefe);
			}

			$this->db->join('usuarios u', 'u.rut = p.rut_tecnico', 'left');
			$this->db->order_by('p.fecha', 'desc');
			$res=$this->db->get('productividad p');
			// echo $this->db->last_query();
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

		public function formDetalle($data){
			if($this->db->insert('productividad_carga', $data)){
				return TRUE;
			}
			return FALSE;
		}

		public function formProductividad($data){
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

		public function listaTrabajadores($jefe){
			$this->db->select("concat(substr(replace(rut,'-',''),1,char_length(replace(rut,'-',''))-1),'-',substr(replace(rut,'-',''),char_length(replace(rut,'-','')))) as 'rut_format',
				empresa,id,rut,
			    CONCAT(nombres,' ', apellidos) as 'nombre_completo',
			    CONCAT(nombres,' ', apellidos) as 'nombre_corto',
			");
			
			if($this->session->userdata('id_perfil')==4){
				$this->db->where('rut', $this->session->userdata('rut'));
			}

			/*if($jefe!=""){
				$this->db->where('id_jefe', $jefe);
			}*/

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

		public function eliminarPeriodoActual($desde,$hasta){
			$this->db->where("fecha BETWEEN '".$desde."' AND '".$hasta."'");
		    if($this ->db->delete('productividad')){
		    	return TRUE;
		    }
		    return FALSE;
		}

		public function truncateTablaCargas(){
		    if($this ->db->truncate('productividad_carga')){
		    	return TRUE;
		    }
		    return FALSE;
		}

		public function getDataProductividadCarga(){
		    $res = $this->db->get('productividad_carga');
		  	return $res->result_array();
		}

		public function getDataProductividadPeriodo($desde,$hasta){
			$this->db->where("fecha BETWEEN '".$desde."' AND '".$hasta."'");
		    if($this ->db->delete('productividad')){
		    	return TRUE;
		    }
		    return FALSE;
		}
		

	/********GRAFICOS*************/

		public function rankingTecnicos($desde,$hasta,$trabajador,$jefe){
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

			if($jefe!=""){
				$this->db->where('u.id_jefe', $jefe);
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
	 		    $temp[] = (float) $v = ($key['cantidad_ok']==0) ? null: round($key['cantidad_ok'],2);
	 		    $temp[] = (string) $v = ($key['cantidad_ok']==0) ? null: round($key['cantidad_ok'],2);
	 		    $temp[] = (float) $v = ($key['cantidad_gsa']==0) ? null: round($key['cantidad_gsa'],2);
	 		    $temp[] = (string) $v = ($key['cantidad_gsa']==0) ? null: round($key['cantidad_gsa'],2);
	 		    $temp[] = (float) $v = ($key['cantidad_redes']==0) ? null: round($key['cantidad_redes'],2);
	 		    $temp[] = (string) $v = ($key['cantidad_redes']==0) ? null: round($key['cantidad_redes'],2);
				$array[]=$temp;
			}
			return $array;
		}


		public function puntosPorFechas($desde,$hasta,$trabajador,$jefe){
			$this->db->select("if(fecha!='0000-00-00', DATE_FORMAT(fecha,'%d-%m-%Y'),'') as 'fecha',
				 SUM(puntaje) as puntos

		         ",
			 FALSE);

			$this->db->join('usuarios u', 'u.rut = p.rut_tecnico', 'left');

			if($desde!="" and $hasta!=""){
				$this->db->where("fecha BETWEEN '".$desde."' AND '".$hasta."'");	
			}

			if($trabajador!=""){
				$this->db->where('rut_tecnico', $trabajador);
			}

			if($jefe!=""){
				$this->db->where('u.id_jefe', $jefe);
			}

			$this->db->group_by('(fecha)');
			$this->db->order_by('fecha', 'asc');
			$res=$this->db->get("productividad p");
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
			    $temp[] = (float) $key['puntos'];
		 	    $temp[] = (string) $v = ($key['puntos']==0) ? null: round($key['puntos'],2);
		 	    $temp[] = strtotime($key["fecha"]);

			    $array[] = $temp;
			}
			return $array;
		}

		public function distribucionTipos($desde,$hasta,$trabajador,$jefe){
		
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

				if($jefe!=""){

					$this->db->select('
						(SELECT count(p.id)
							from productividad p
							LEFT JOIN usuarios as u ON u.rut=p.rut_tecnico
							where tipo_actividad REGEXP "Alta" 
							and fecha BETWEEN "'.$desde.'" AND "'.$hasta.'"
							and u.id_jefe = "'.$jefe.'"
						) as altas,
						(SELECT count(p.id)
							from productividad p
							LEFT JOIN usuarios as u ON u.rut=p.rut_tecnico
							where tipo_actividad REGEXP "Reparación" 
							and fecha BETWEEN "'.$desde.'" AND "'.$hasta.'"
							and u.id_jefe = "'.$jefe.'"
						) as reparaciones,
						(SELECT count(p.id)
							from productividad p
							LEFT JOIN usuarios as u ON u.rut=p.rut_tecnico
							WHERE tipo_actividad NOT LIKE "%Alta%" and tipo_actividad NOT LIKE "%Reparación%"
							and fecha BETWEEN "'.$desde.'" AND "'.$hasta.'"
							and u.id_jefe = "'.$jefe.'"
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

		public function distribucionOt($desde,$hasta,$trabajador,$jefe){
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

				if($jefe!=""){

					$this->db->select('
					(SELECT count(p.id)
						from productividad p
						LEFT JOIN usuarios as u ON u.rut=p.rut_tecnico
						where estado_ot REGEXP "OT OK" 
						and fecha BETWEEN "'.$desde.'" AND "'.$hasta.'"
						and u.id_jefe = "'.$jefe.'"
					) as ok,
					(SELECT count(p.id)
						from productividad p
						LEFT JOIN usuarios as u ON u.rut=p.rut_tecnico
						where estado_ot REGEXP "DRIVE NO DETECTA REGISTRO" 
						and fecha BETWEEN "'.$desde.'" AND "'.$hasta.'"
						and u.id_jefe = "'.$jefe.'"
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

		public function puntosTipoOrden($desde,$hasta,$trabajador,$jefe){
			$this->db->select("tipo_actividad,
				 SUM(puntaje) as puntos",
			 FALSE);

			$this->db->join('usuarios u', 'u.rut = p.rut_tecnico', 'left');

			if($desde!="" and $hasta!=""){
				$this->db->where("fecha BETWEEN '".$desde."' AND '".$hasta."'");	
			}

			if($trabajador!=""){
				$this->db->where('rut_tecnico', $trabajador);
			}

			if($jefe!=""){
				$this->db->where('u.id_jefe', $jefe);
			}

			$this->db->group_by('tipo_actividad');
			$this->db->order_by('tipo_actividad', 'asc');
			$res=$this->db->get("productividad p");
			$array = array();
			
			$array[]= array(
				"Tipo orden",
				"Puntos",
			);

			foreach($res->result_array() as $key){
				$temp = array();
			    $temp[] = (string)$key["tipo_actividad"]; 
			    $temp[] = (float) round($key['puntos'],2);
			    $array[] = $temp;
			}
			return $array;
		}

		public function totalPuntosPorFecha($desde,$hasta,$trabajador,$jefe){
			$this->db->select("ROUND(SUM(puntaje), 2) as puntos", FALSE);
			$this->db->join('usuarios u', 'u.rut = p.rut_tecnico', 'left');
			if($desde!="" and $hasta!=""){
				$this->db->where("fecha BETWEEN '".$desde."' AND '".$hasta."'");	
			}

			if($trabajador!=""){
				$this->db->where('rut_tecnico', $trabajador);
			}

			if($jefe!=""){
				$this->db->where('u.id_jefe', $jefe);
			}

			$this->db->order_by('fecha', 'asc');
			$res=$this->db->get("productividad p");
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

		public function listaJefes(){
			$this->db->select("sha1(uj.id) as hash_jefes,
				uj.id as id_jefe,
				uj.id_jefe as id_usuario_jefe,
				CONCAT(u.nombres,' ',u.apellidos)  'nombre_jefe'
				");

			$this->db->join('usuarios u', 'u.id = uj.id_jefe', 'left');

			if($this->session->userdata('id_perfil')==3){
				if($this->session->userdata('verificacionJefe')=="1"){
					$this->db->where('uj.id', $this->session->userdata('id_jefe'));
				}
			}

			$this->db->order_by('nombre_jefe', 'asc');
			$res=$this->db->get('usuarios_jefes uj');
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
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

		public function cabecerasResumen($desde,$hasta){
			$array_fechas = $this->date_range($desde,$hasta,"+1 day", "Y-m-d");
			$cabeceras = array();
			$cabeceras[] = "Zona";
			$cabeceras[] = "Trabajador";
			$cabeceras[] = "Días";
			$cabeceras[] = "Promedio";

			foreach($array_fechas as $fecha){
				$cabeceras[] = $this->fecha_to_str($fecha);
			}
			
			$cabeceras[] = "Total";
			return $cabeceras;
		}
		

		public function dataResumenProductividad($desde,$hasta,$trabajador,$jefe){

			if($trabajador!=""){

				$this->db->select('
					ROUND(SUM(p.puntaje),2) as total,
					p.rut_tecnico as rut_tecnico,
					CONCAT(u.nombres," ",u.apellidos) as trabajador,
					a.area as area,

					(SELECT  (count(DISTINCT p2.fecha))
					   FROM  productividad p2
					   WHERE  (p2.rut_tecnico = p.rut_tecnico) 
					   and rut_tecnico="'.$trabajador.'"
					   and (p2.fecha between "'.$desde.'" and "'.$hasta.'")
					) as dias,

					(SELECT   ROUND(AVG(puntaje),2)
					   FROM  productividad p
					   WHERE  (p.rut_tecnico = u.rut) 
					   and (DATE(fecha) between date("'.$desde.'") and date("'.$hasta.'") and rut_tecnico="'.$trabajador.'")
					) as promedio
				');

			}else{

			
				$this->db->select('
					(SELECT  (count(DISTINCT p2.fecha))
					   FROM  productividad p2
					   WHERE  (p2.rut_tecnico = p.rut_tecnico) and (p2.fecha between "'.$desde.'" and "'.$hasta.'")
					) as dias,
				
					ROUND(SUM(puntaje),2) as total,
					ROUND(avg(puntaje),2) as promedio,
					p.rut_tecnico as rut_tecnico,
					CONCAT(u.nombres," ",u.apellidos) as trabajador,
					a.area as area
					
				');
			}

			$this->db->from('productividad p');
			$this->db->join('usuarios u', 'u.rut = p.rut_tecnico', 'left');
			$this->db->join('usuarios_areas a', 'a.id = u.id_area', 'left');
			$this->db->where('(u.id_nivel_tecnico<>"" and u.id_nivel_tecnico<>"5")');
			$this->db->group_by('u.id');
			$this->db->order_by('u.nombres', 'asc');

			if($desde!="" and $hasta!=""){$this->db->where("p.fecha BETWEEN '".$desde."' AND '".$hasta."'");	}
			if($trabajador!=""){$this->db->where('p.rut_tecnico', $trabajador);}
			if($jefe!=""){	$this->db->where('u.id_jefe', $jefe);}

			$res = $this->db->get();
			return $res->result_array();

			/*$res = $this->db->query('SELECT * FROM productividad_periodo_anterior');
			return $res->result_array();*/
		}

		public function detalleDiarioProductividad($desde,$hasta,$trabajador,$jefe){

			$this->db->select('
				ROUND(IFNULL(pr.total,0),2) AS puntos,
				pc.fecha AS fecha
			');

			$this->db->from('productividad_calendario pc');

			$this->db->join('(SELECT
				SUM(p.puntaje) as total,
				DATE(p.fecha) as fecha,
				p.rut_tecnico as rut_tecnico,
				CONCAT(u.nombres," ",u.apellidos) as trabajador,
				a.area as area
				FROM productividad p
				LEFT JOIN usuarios as u ON u.rut=p.rut_tecnico
				LEFT JOIN usuarios_areas as a ON u.id_area=a.id
				LEFT JOIN usuarios_jefes as uj ON uj.id_jefe=u.id_jefe
				WHERE DATE(p.fecha) between DATE("'.$desde.'") and DATE("'.$hasta.'") and p.rut_tecnico="'.$trabajador.'" 
				GROUP BY CAST(p.fecha AS date)) as pr', 'ON pc.fecha=pr.fecha', 'left');

			$this->db->where("pc.fecha BETWEEN '".$desde."' AND '".$hasta."'");
			$this->db->join('usuarios u', 'u.rut = pr.rut_tecnico', 'left');
			
			/*and u.id_jefe="'.$jefe.'"*/
			/*if($trabajador!=""){$this->db->where('pr.rut_tecnico', $trabajador);}*/
		
			$this->db->group_by('pc.fecha');
			$this->db->order_by('pc.fecha', 'asc');
			$res = $this->db->get();
			$this->db->last_query();
			return $res->result_array();
		}

		public function listaResumen($desde,$hasta,$trabajador,$jefe){
			$this->db->select("sha1(p.id) as hash,
				p.id as id,
				CONCAT(u.nombres,' ',u.apellidos) as 'trabajador',
				a.area as area,
				if(sum(puntaje)!=0, sum(puntaje) ,'') as total,
				p.rut_tecnico as rut_tecnico,
				if(p.fecha!='0000-00-00', DATE_FORMAT(p.fecha,'%d-%m-%Y'),'') as 'fecha'");

			$this->db->join('usuarios u', 'u.rut = p.rut_tecnico', 'left');
			$this->db->join('usuarios_areas a', 'u.id_area = a.id', 'left');

			if($desde!="" and $hasta!=""){$this->db->where("p.fecha BETWEEN '".$desde."' AND '".$hasta."'");	}
			if($trabajador!=""){$this->db->where('p.rut_tecnico', $trabajador);}
			if($jefe!=""){	$this->db->where('u.id_jefe', $jefe);}

			$this->db->where("(u.id_cargo ='15' or u.id_cargo ='16' or u.id_cargo ='17' or u.id_cargo ='18' or u.id_cargo ='32')");

			$this->db->order_by('u.nombres', 'asc');
			$this->db->group_by('u.id');
			$res=$this->db->get('productividad p');

			$array_fechas = $this->date_range($desde,$hasta,"+1 day", "Y-m-d");
			$array = array();

			foreach($res->result_array() as $key){
				$temp=array();

				$temp["Zona"] = $key["area"];
				$temp["Trabajador"] = $key["trabajador"];
				$dias = 0;
				$puntajes = array();

				foreach($array_fechas as $fecha){

					$this->db->select("if(sum(puntaje)!=0,sum(puntaje),0)  as puntaje");
					$this->db->where('fecha="'.$fecha.'" AND `rut_tecnico` = "'.$key["rut_tecnico"].'"');
					$res2=$this->db->get('productividad');
					
					if($res2->num_rows()>0){
						$puntaje=0;
						foreach($res2->result_array() as $key2){
							$puntaje = $puntaje+$key2["puntaje"];
						}
						
						if($puntaje!=0){
							$dias++;
							$puntajes[] = $puntaje;
						}

						$temp[$this->fecha_to_str($fecha)] = round($puntaje,2);
					}else{
						$temp[$this->fecha_to_str($fecha)] = "";
					}

					$temp["Días"] = $dias;

					$a = array_filter($puntajes);
					if(count($a)) {
					    $temp["Promedio"] = round(array_sum($a)/count($a),2);
					}else{
						$temp["Promedio"] = 0;
					}
						
				}

				$temp["Total"] = round($key["total"],2);
				$array[]=$temp;

			}

			return $array;
		}


		public function listaResumen2($desde,$hasta,$jefe,$trabajador){
			$this->db->select("sha1(p.id) as hash,
				p.id as id,
				u.foto as foto,
				CONCAT(u.nombres,' ',u.apellidos) as 'trabajador',
				a.area as area,
				if(sum(puntaje)!=0, sum(puntaje) ,'') as total,
				p.rut_tecnico as rut_tecnico,
				if(p.fecha!='0000-00-00', DATE_FORMAT(p.fecha,'%d-%m-%Y'),'') as 'fecha'");

			$this->db->join('usuarios u', 'u.rut = p.rut_tecnico', 'left');
			$this->db->join('usuarios_areas a', 'u.id_area = a.id', 'left');

			if($desde!="" and $hasta!=""){$this->db->where("p.fecha BETWEEN '".$desde."' AND '".$hasta."'");	}
			$this->db->where('p.rut_tecnico', $trabajador);
	     	/*	if($jefe!=""){	$this->db->where('u.id_jefe', $jefe);}*/

			$this->db->order_by('u.nombres', 'asc');
			$this->db->group_by('u.id');
			$res=$this->db->get('productividad p');

			$array_fechas = $this->date_range($desde,$hasta,"+1 day", "Y-m-d");
			$array = array();
			if($res->num_rows()>0){
				foreach($res->result_array() as $key){
					$temp=array();

					$temp["Zona"] = $key["area"];
					$temp["foto"] = $key["foto"];
					$temp["Trabajador"] = $key["trabajador"];
					$dias = 0;
					$puntajes = array();

					foreach($array_fechas as $fecha){

						$this->db->select("if(sum(puntaje)!=0,sum(puntaje),0)  as puntaje");
						$this->db->where('fecha="'.$fecha.'" AND `rut_tecnico` = "'.$key["rut_tecnico"].'"');
						$res2=$this->db->get('productividad');
						
						if($res2->num_rows()>0){
							$puntaje=0;
							foreach($res2->result_array() as $key2){
								$puntaje = $puntaje+$key2["puntaje"];
							}
							
							if($puntaje!=0){
								$dias++;
								$puntajes[] = $puntaje;
							}

							$temp[$this->fecha_to_str($fecha)] = round($puntaje,2);
						}else{
							$temp[$this->fecha_to_str($fecha)] = "";
						}

						$temp["Días"] = $dias;

						$a = array_filter($puntajes);
						if(count($a)) {
						    $temp["Promedio"] = round(array_sum($a)/count($a),2);
						}else{
							$temp["Promedio"] = 0;
						}
							
					}

					$temp["Total"] = round($key["total"],2);
					$array[]=$temp;
				}

				return $array;
			}else{
				return FALSE;
			}

		}


		public function detalleResumen(){

		}

}