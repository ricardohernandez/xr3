<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Flotamodel extends CI_Model {

		public function __construct(){
		parent::__construct();
	}

	public function insertarVisita($data){
	  if($this->db->insert('aplicaciones_visitas', $data)){
			return TRUE;
		}
		return FALSE;
	}

	public function getPerfilTecnico($rut){
		$this->db->select('id_nivel_tecnico');
		$this->db->where('rut', $rut);
		$this->db->where('estado', 1);
		$res = $this->db->get('usuarios');
		if($res->num_rows()>0){
			$row = $res->row_array();
			return $row["id_nivel_tecnico"];
		}
		return FALSE;
	}


	/****************COMBUSTIBLE ********************/

		public function insertarFlotaCombustible($data){
			if($this->db->insert('flota_combustible', $data)){
				return TRUE;
			}
			return FALSE;
		}

		public function listaCombustible($desde,$hasta,$chofer,$supervisor,$vehiculo,$region){
			$this->db->select("
				sha1(f.id) as hash,
				f.*,
				FORMAT(SUM(f.volumen),0) AS litros_cargados,
				FORMAT(SUM(f.kms_recorridos),0) AS kms_recorridos_total,
				FORMAT(SUM(f.monto),0) AS monto_total,
				FORMAT(MAX(f.meta_monto), 0) as meta_monto,
				FORMAT((SUM(f.kms_recorridos)/SUM(f.volumen)), 0) as km_lt,
				FORMAT((SUM(f.monto)/SUM(f.volumen)), 0) as clp_lt
			");
			
			$this->db->from('flota_combustible as f');
			
			// Subconsulta para obtener la fecha más reciente por patente
			$subquery = '(SELECT MAX(fecha) FROM flota_combustible WHERE patente = f.patente)';
			$this->db->where('f.fecha = ' . $subquery, NULL, FALSE);
			
			if ($desde != "" && $hasta != "") {
				$this->db->where("f.fecha BETWEEN '".$desde."' AND '".$hasta."'");
			}
			if ($chofer != "") {
				$this->db->where('f.nombre_chofer', $chofer);
			}
			if ($supervisor != "") {
				$this->db->where('f.nombre_supervisor', $supervisor);
			}
			if ($vehiculo != "") {
				$this->db->where('f.patente', $vehiculo);
			}
			if ($region != "") {
				$this->db->where('f.region', $region);
			}
			
			$this->db->group_by('f.patente');
			
			$res = $this->db->get();
			
			if ($res->num_rows() > 0) {
				return $res->result_array();
			} else {
				return FALSE;
			}
		}

		public function listaMax($desde,$hasta,$chofer,$supervisor,$vehiculo,$region){
			$this->db->select("
			sha1(f.id) as hash,
			f.patente,
			f.fecha,
			f.hora,
			FORMAT(f.odometro,0) as max,
			");

			if($desde!="" and $hasta!=""){$this->db->where("f.fecha BETWEEN '".$desde."' AND '".$hasta."'");}
			if($chofer!=""){	$this->db->where('f.nombre_chofer', $chofer);}
			if($supervisor!=""){	$this->db->where('f.nombre_supervisor', $supervisor);}
			if($vehiculo!=""){	$this->db->where('f.patente', $vehiculo);}
			if($region!=""){	$this->db->where('f.region', $region);}
			

			$res=$this->db->get('flota_combustible as f');
			if($res->num_rows()>0){
				return $res->result_array();
			}else{
				return FALSE;
			}
		}

		public function listaCarga($desde,$hasta,$chofer,$supervisor,$vehiculo,$region){
			$this->db->select(
				"
				DATE_FORMAT(f.fecha, '%Y-%m') as 'mes',
				SUM(f.monto) as 'monto',
				SUM(f.volumen) as 'carga',
			");
			$this->db->from('flota_combustible as f');
			if($desde!="" and $hasta!=""){$this->db->where("f.fecha BETWEEN '".$desde."' AND '".$hasta."'");}
			if($chofer!=""){$this->db->where('f.nombre_chofer', $chofer);}
			if($supervisor!=""){$this->db->where('f.nombre_supervisor', $supervisor);}
			if($vehiculo!=""){$this->db->where('f.patente', $vehiculo);}
			if($region!=""){$this->db->where('f.region', $region);}

			$this->db->group_by('mes');
			$this->db->order_by('mes', 'asc');
			$res=$this->db->get();

			$array = array();
			$array[]= array(
				"Mes",
				"Monto (\$CLP)",
				"Carga (Litros)",
			);
			if($res->num_rows()>0){
				foreach($res->result_array() as $key){
					$temp = array();
					$temp[] = $key['mes'];
					$temp[] = (int) $key['monto'];
					$temp[] = (int) $key['carga'];
					$array[] = $temp;
				}
			}
			else{
				$temp = array();
				$temp[] = "";
				$temp[] = 0;
				$temp[] = 0;
				$array[] = $temp;
			}

			return $array;
		}

		public function GastoRegion($desde,$hasta,$chofer,$supervisor,$vehiculo,$region){
			$this->db->select(
				"
				f.region as 'region',
				SUM(f.monto) as 'monto',
			");
			$this->db->from('flota_combustible as f');
			if($desde!="" and $hasta!=""){$this->db->where("f.fecha BETWEEN '".$desde."' AND '".$hasta."'");}
			if($desde!="" and $hasta!=""){$this->db->where("f.fecha BETWEEN '".$desde."' AND '".$hasta."'");}
			if($chofer!=""){	$this->db->where('f.nombre_chofer', $chofer);}
			if($supervisor!=""){	$this->db->where('f.nombre_supervisor', $supervisor);}
			if($vehiculo!=""){	$this->db->where('f.patente', $vehiculo);}
			if($region!=""){	$this->db->where('f.region', $region);}
			

			$this->db->group_by('region');
			$this->db->order_by('region', 'asc');
			$res=$this->db->get();

			$array = array();
			$array[]= array(
				"Región",
				"Monto (\$CLP)",
			);
			if($res->num_rows()>0){
				foreach($res->result_array() as $key){
					$temp = array();
					$temp[] = $key['region'];
					$temp[] = (int) $key['monto'];
					$array[] = $temp;
				}
			}
			else{
				$temp = array();
				$temp[] = "";
				$temp[] = 0;
				$array[] = $temp;
			}

			return $array;
		}

		public function GastoSemana($desde,$hasta,$chofer,$supervisor,$vehiculo,$region){
			$this->db->select(
				"
				DATE_FORMAT(MIN(fecha), '%m-%d') as inicio_semana, 
				DATE_FORMAT(MAX(fecha), '%m-%d') as fin_semana,
				YEAR(f.fecha) as 'anio',
				WEEK(f.fecha) as 'semana',
				SUM(f.monto) as 'monto',
			");
			$this->db->from('flota_combustible as f');
			if($desde!="" and $hasta!=""){$this->db->where("f.fecha BETWEEN '".$desde."' AND '".$hasta."'");}
			if($chofer!=""){	$this->db->where('f.nombre_chofer', $chofer);}
			if($supervisor!=""){	$this->db->where('f.nombre_supervisor', $supervisor);}
			if($vehiculo!=""){	$this->db->where('f.patente', $vehiculo);}
			if($region!=""){	$this->db->where('f.region', $region);}
			

			$this->db->group_by('WEEK(f.fecha)');
			$this->db->order_by('WEEK(f.fecha)', 'asc');
			$res=$this->db->get();

			$array = array();
			$array[]= array(
				"Semana",
				"Monto (\$CLP)",
			);
			if($res->num_rows()>0){
				foreach($res->result_array() as $key){
					$temp = array();
					$temp[] = $key['anio']." - "."Semana ".intval($key['semana'])+1;
					$temp[] = (int) $key['monto'];
					$array[] = $temp;
				}
			}
			else{
				$temp = array();
				$temp[] = "";
				$temp[] = 0;
				$array[] = $temp;
			}

			return $array;
		}
		public function GastoCombustibleRegion($desde,$hasta,$chofer,$supervisor,$vehiculo,$region){
			$this->db->select(
				"
				f.region as 'region',
				SUM(f.volumen) as 'volumen',
			");
			$this->db->from('flota_combustible as f');
			if($desde!="" and $hasta!=""){$this->db->where("f.fecha BETWEEN '".$desde."' AND '".$hasta."'");}
			if($desde!="" and $hasta!=""){$this->db->where("f.fecha BETWEEN '".$desde."' AND '".$hasta."'");}
			if($chofer!=""){	$this->db->where('f.nombre_chofer', $chofer);}
			if($supervisor!=""){	$this->db->where('f.nombre_supervisor', $supervisor);}
			if($vehiculo!=""){	$this->db->where('f.patente', $vehiculo);}
			if($region!=""){	$this->db->where('f.region', $region);}
			

			$this->db->group_by('region');
			$this->db->order_by('region', 'asc');
			$res=$this->db->get();

			$array = array();
			$array[]= array(
				"Región",
				"Combustible (Litros)",
			);
			if($res->num_rows()>0){
				foreach($res->result_array() as $key){
					$temp = array();
					$temp[] = $key['region'];
					$temp[] = (int) $key['volumen'];
					$array[] = $temp;
				}
			}
			else{
				$temp = array();
				$temp[] = "";
				$temp[] = 0;
				$array[] = $temp;
			}

			return $array;
		}

		public function GastoCombustibleSemana($desde,$hasta,$chofer,$supervisor,$vehiculo,$region){
			$this->db->select(
				"
				DATE_FORMAT(MIN(fecha), '%m-%d') as inicio_semana, 
				DATE_FORMAT(MAX(fecha), '%m-%d') as fin_semana,
				YEAR(f.fecha) as 'anio',
				WEEK(f.fecha) as 'semana',
				SUM(f.volumen) as 'volumen',
			");
			$this->db->from('flota_combustible as f');
			if($desde!="" and $hasta!=""){$this->db->where("f.fecha BETWEEN '".$desde."' AND '".$hasta."'");}
			if($chofer!=""){	$this->db->where('f.nombre_chofer', $chofer);}
			if($supervisor!=""){	$this->db->where('f.nombre_supervisor', $supervisor);}
			if($vehiculo!=""){	$this->db->where('f.patente', $vehiculo);}
			if($region!=""){	$this->db->where('f.region', $region);}
			

			$this->db->group_by('WEEK(f.fecha)');
			$this->db->order_by('WEEK(f.fecha)', 'asc');
			$res=$this->db->get();

			$array = array();
			$array[]= array(
				"Semana",
				"Combustible (Litros)",
			);
			if($res->num_rows()>0){
				foreach($res->result_array() as $key){
					$temp = array();
					$temp[] = $key['anio']." - "."Semana ".intval($key['semana'])+1;
					$temp[] = (int) $key['volumen'];
					$array[] = $temp;
				}
			}
			else{
				$temp = array();
				$temp[] = "";
				$temp[] = 0;
				$array[] = $temp;
			}

			return $array;
		}

		public function getActualizacionCombustible($desde,$hasta){
			$this->db->select("
				MAX(f.ultima_actualizacion) as ultima_actualizacion,
				FORMAT(SUM(f.monto), 0) as total,
			");
			if($desde!="" and $hasta!=""){$this->db->where("f.fecha BETWEEN '".$desde."' AND '".$hasta."'");}
			$res = $this->db->get('flota_combustible f');
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

		public function getPatenteCombustible(){
			$this->db->distinct();
			$this->db->select('patente');
			$res = $this->db->get('flota_combustible');
			$this->db->order_by('patente', 'asc');
			if($res->num_rows()>0){
				$array=array();
				foreach($res->result_array() as $key){
					$temp=array();
					$temp["id"]=$key["patente"];
					$temp["text"]=$key["patente"];
					$array[]=$temp;
				}
				return json_encode($array);
			}
			return FALSE;
		}
		public function getSupervisorCombustible(){
			$this->db->distinct();
			$this->db->select('nombre_supervisor');
			$res = $this->db->get('flota_combustible');
			$this->db->order_by('nombre_supervisor', 'asc');
			if($res->num_rows()>0){
				$array=array();
				foreach($res->result_array() as $key){
					$temp=array();
					$temp["id"]=$key["nombre_supervisor"];
					$temp["text"]=$key["nombre_supervisor"];
					$array[]=$temp;
				}
				return json_encode($array);
			}
			return FALSE;
		}
		public function getChoferCombustible(){
			$this->db->distinct();
			$this->db->select('nombre_chofer');
			$res = $this->db->get('flota_combustible');
			$this->db->order_by('nombre_chofer', 'asc');
			if($res->num_rows()>0){
				$array=array();
				foreach($res->result_array() as $key){
					$temp=array();
					$temp["id"]=$key["nombre_chofer"];
					$temp["text"]=$key["nombre_chofer"];
					$array[]=$temp;
				}
				return json_encode($array);
			}
			return FALSE;
		}

		public function getRegionCombustible(){
			$this->db->distinct();
			$this->db->select('region');
			$res = $this->db->get('flota_combustible');
			$this->db->order_by('region', 'asc');
			if($res->num_rows()>0){
				$array=array();
				foreach($res->result_array() as $key){
					$temp=array();
					$temp["id"]=$key["region"];
					$temp["text"]=$key["region"];
					$array[]=$temp;
				}
				return json_encode($array);
			}
			return FALSE;
		}

	/*************** GPS  ******************/

		public function insertarFlotaGPS($data){
			if($this->db->insert('flota_gps', $data)){
				return TRUE;
			}
			return FALSE;
		} 

		public function listaGPS($desde,$hasta,$asignacion,$supervisor,$vehiculo,$comuna,$gps){
			$this->db->select("
			f.*,
			sha1(f.id) as hash,
			FORMAT(COUNT(*), 0) as infracciones,
			");

			if($desde!="" and $hasta!=""){$this->db->where("f.fecha BETWEEN '".$desde."' AND '".$hasta."'");}
			if($supervisor!=""){	$this->db->where('f.nombre_supervisor', $supervisor);}
			if($asignacion!=""){	$this->db->where('f.nombre_chofer', $asignacion);}
			if($vehiculo!=""){	$this->db->where('f.patente', $vehiculo);}
			if($comuna!=""){	$this->db->where('f.comuna', $comuna);}
			if($gps!=""){	$this->db->where('f.gps', $gps);}

			$this->db->group_by('f.patente');
			$this->db->order_by('infracciones', 'desc');
			$res=$this->db->get('flota_gps as f');
			if($res->num_rows()>0){
				return $res->result_array();
			}else{
				return FALSE;
			}
		}

		/* public function listaDetalleFlota($desde,$hasta,$asignacion,$supervisor,$vehiculo,$comuna,$gps){
			$this->db->select("
			sha1(f.id) as hash,
			f.*,
			");

			if($desde!="" and $hasta!=""){$this->db->where("f.fecha BETWEEN '".$desde."' AND '".$hasta."'");}
			if($supervisor!=""){	$this->db->where('f.nombre_supervisor', $supervisor);}
			if($asignacion!=""){	$this->db->where('f.nombre_chofer', $asignacion);}
			if($vehiculo!=""){	$this->db->where('f.patente', $vehiculo);}
			if($comuna!=""){	$this->db->where('f.comuna', $comuna);}
			if($gps!=""){	$this->db->where('f.gps', $gps);}

			$this->db->order_by('v_max', 'desc');
			$this->db->limit(100);
			
			$res=$this->db->get('flota_gps as f');

			if($res->num_rows()>0){
				$array = array();
				foreach($res->result_array() as $key){
					$this->db->select("MAX(c.fecha) as fecha, c.rut_chofer, c.nombre_chofer");
					$this->db->where('c.patente', str_replace("-", "", $key["patente"]));
					$combustible=$this->db->get('flota_combustible as c');
					if($combustible->num_rows()>0){
						$data = $combustible->result_array();
					}

					$this->db->select("MAX(c.fecha) as fecha, c.rut_chofer, c.nombre_chofer");
					$this->db->where('c.patente', str_replace("-", "", $key["patente"]));
					$muevo=$this->db->get('flota_gps_muevo as c');
					if($muevo->num_rows()>0){
						$datam = $muevo->result_array();
					}

					if($datam[0]["fecha"] < $data[0]["fecha"]){
						$key["rut_chofer"] = $data[0]["rut_chofer"];
						$key["nombre_chofer"] = $data[0]["nombre_chofer"];
					}else{
						$key["rut_chofer"] = $datam[0]["rut_chofer"];
						$key["nombre_chofer"] = $datam[0]["nombre_chofer"];
					}
					$array[] = $key;

					 
				}
				return $array;
			}else{
				return FALSE;
			}
		} */

		public function listaDetalleFlota($desde,$hasta,$asignacion,$supervisor,$vehiculo,$comuna,$gps){
			$this->db->select("
			sha1(f.id) as hash,
			f.*,

			(SELECT c.nombre_chofer 
			FROM flota_combustible as c 
			WHERE c.patente = f.patente 
			ORDER BY c.fecha,c.hora DESC 
			LIMIT 1) as nombre_chofer,

			(SELECT c.rut_chofer 
			FROM flota_combustible as c 
			WHERE c.patente = f.patente 
			ORDER BY c.fecha,c.hora DESC 
			LIMIT 1) as rut



			");

			if($desde!="" and $hasta!=""){$this->db->where("f.fecha BETWEEN '".$desde."' AND '".$hasta."'");}
			if($supervisor!=""){	$this->db->where('f.nombre_supervisor', $supervisor);}
			if($asignacion!=""){	$this->db->where('f.nombre_chofer', $asignacion);}
			if($vehiculo!=""){	$this->db->where('f.patente', $vehiculo);}
			if($comuna!=""){	$this->db->where('f.comuna', $comuna);}
			if($gps!=""){	$this->db->where('f.gps', $gps);}

			$this->db->order_by('v_max', 'desc');
			/* $this->db->limit(100); */
			
			$res=$this->db->get('flota_gps as f');
			return $res->result_array();
		}

		public function listaTotal($desde,$hasta,$asignacion,$supervisor,$vehiculo,$comuna,$gps){
			$this->db->select(
				"
				f.patente as 'patente',
				COUNT(f.v_max) as 'infracciones',
			");
			
			$this->db->from('flota_gps as f');
			if($desde!="" and $hasta!=""){$this->db->where("f.fecha BETWEEN '".$desde."' AND '".$hasta."'");}
			if($supervisor!=""){	$this->db->where('f.nombre_supervisor', $supervisor);}
			if($asignacion!=""){	$this->db->where('f.nombre_chofer', $asignacion);}
			if($vehiculo!=""){	$this->db->where('f.patente', $vehiculo);}
			if($comuna!=""){	$this->db->where('f.comuna', $comuna);}
			if($gps!=""){	$this->db->where('f.gps', $gps);}

			$this->db->group_by('patente');
			$this->db->order_by('infracciones', 'desc');
			$this->db->limit(40);
			$res=$this->db->get();

			$array = array();
			$array[]= array(
				"patente",
				"infracciones",
			);
			if($res->num_rows()>0){
				foreach($res->result_array() as $key){
					$temp = array();
					$temp[] = $key['patente'];
					$temp[] = (int) $key['infracciones'];
					$array[] = $temp;
				}
			}
			else{
				$temp = array();
				$temp[] = "";
				$temp[] = 0;
				$array[] = $temp;
			}

			return $array;
		}

		public function getActualizacionGPS($gps,$desde,$hasta){
			$this->db->select("
				FORMAT(MAX(v_max), 0) as max_velocidad,
				FORMAT(COUNT(*), 0) as conteo_infracciones,
				FORMAT(COUNT(DISTINCT patente), 0) as vehiculos_infractores,
				MAX(ultima_actualizacion) as ultima_actualizacion,
			");
			if($gps!=""){	$this->db->where('gps', $gps);}
			if($desde!="" and $hasta!=""){$this->db->where("fecha BETWEEN '".$desde."' AND '".$hasta."'");}
			$res = $this->db->get('flota_gps');
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

		public function getPatenteGPS($gps){
			$this->db->distinct();
			$this->db->select('patente');
			if($gps!=""){	$this->db->where('gps', $gps);}
			$res = $this->db->get('flota_gps');
			$this->db->order_by('patente', 'asc');
			if($res->num_rows()>0){
				$array=array();
				foreach($res->result_array() as $key){
					$temp=array();
					$temp["id"]=$key["patente"];
					$temp["text"]=$key["patente"];
					$array[]=$temp;
				}
				return json_encode($array);
			}
			return FALSE;
		}
		public function getSupervisorGPS($gps){
			$this->db->distinct();
			$this->db->select('nombre_supervisor');
			if($gps!=""){	$this->db->where('gps', $gps);}
			$res = $this->db->get('flota_gps');
			$this->db->order_by('nombre_supervisor', 'asc');
			if($res->num_rows()>0){
				$array=array();
				foreach($res->result_array() as $key){
					$temp=array();
					$temp["id"]=$key["nombre_supervisor"];
					$temp["text"]=$key["nombre_supervisor"];
					$array[]=$temp;
				}
				return json_encode($array);
			}
			return FALSE;
		}
		public function getChoferGPS($gps){
			$this->db->distinct();
			$this->db->select('nombre_chofer');
			if($gps!=""){	$this->db->where('gps', $gps);}
			$res = $this->db->get('flota_gps');
			$this->db->order_by('nombre_chofer', 'asc');
			if($res->num_rows()>0){
				$array=array();
				foreach($res->result_array() as $key){
					$temp=array();
					$temp["id"]=$key["nombre_chofer"];
					$temp["text"]=$key["nombre_chofer"];
					$array[]=$temp;
				}
				return json_encode($array);
			}
			return FALSE;
		}
		public function getComunaGPS($gps){
			$this->db->distinct();
			$this->db->select('comuna');
			if($gps!=""){	$this->db->where('gps', $gps);}
			$res = $this->db->get('flota_gps');
			$this->db->order_by('comuna', 'asc');
			if($res->num_rows()>0){
				$array=array();
				foreach($res->result_array() as $key){
					$temp=array();
					$temp["id"]=$key["comuna"];
					$temp["text"]=$key["comuna"];
					$array[]=$temp;
				}
				return json_encode($array);
			}
			return FALSE;
		}

	/*********** MUEVO ***********/

		public function insertarFlotaGPSMuevo($data){
			if($this->db->insert('flota_gps_muevo', $data)){
				return TRUE;
			}
			return FALSE;
		} 

		public function listaGPSMuevo($desde,$hasta,$asignacion,$supervisor,$vehiculo,$gps,$region){
			$this->db->select("
			f.*,
			sha1(f.id) as hash,
			FORMAT(f.monto,0) as monto,
			FORMAT(f.odometro,0) as odometro,
			");

			if($desde!="" and $hasta!=""){$this->db->where("f.fecha BETWEEN '".$desde."' AND '".$hasta."'");}
			if($supervisor!=""){	$this->db->where('f.nombre_supervisor', $supervisor);}
			if($asignacion!=""){	$this->db->where('f.nombre_chofer', $asignacion);}
			if($vehiculo!=""){	$this->db->where('f.patente', $vehiculo);}
			if($region!=""){	$this->db->where('f.region', $region);}
			if($gps!=""){	$this->db->where('f.gps', $gps);}

			//$this->db->group_by('f.patente');
			$this->db->order_by('odometro', 'desc');
			$res=$this->db->get('flota_gps_muevo as f');
			if($res->num_rows()>0){
				return $res->result_array();
			}else{
				return FALSE;
			}
		}

		public function listaMontoMuevo($desde,$hasta,$asignacion,$supervisor,$vehiculo,$gps,$region){
			$this->db->select(
				"
				f.patente as 'patente',
				SUM(f.monto) as 'monto',
			");
			$this->db->from('flota_gps_muevo as f');
			if($desde!="" and $hasta!=""){$this->db->where("f.fecha BETWEEN '".$desde."' AND '".$hasta."'");}
			if($supervisor!=""){	$this->db->where('f.nombre_supervisor', $supervisor);}
			if($asignacion!=""){	$this->db->where('f.nombre_chofer', $asignacion);}
			if($vehiculo!=""){	$this->db->where('f.patente', $vehiculo);}
			if($region!=""){	$this->db->where('f.region', $region);}
			if($gps!=""){	$this->db->where('f.gps', $gps);}

			$this->db->group_by('patente');
			$this->db->order_by('monto', 'desc');
			$res=$this->db->get();

			$array = array();
			$array[]= array(
				"patente",
				"monto",
			);
			if($res->num_rows()>0){
				foreach($res->result_array() as $key){
					$temp = array();
					$temp[] = $key['patente'];
					$temp[] = (int) $key['monto'];
					$array[] = $temp;
				}
			}
			else{
				$temp = array();
				$temp[] = "";
				$temp[] = 0;
				$array[] = $temp;
			}

			return $array;
		}

		public function listaOdometroMuevo($desde,$hasta,$asignacion,$supervisor,$vehiculo,$gps,$region){
			$this->db->select(
				"
				f.patente as 'patente',
				MAX(f.odometro) as 'odometro',
			");
			$this->db->from('flota_gps_muevo as f');
			if($desde!="" and $hasta!=""){$this->db->where("f.fecha BETWEEN '".$desde."' AND '".$hasta."'");}
			if($supervisor!=""){	$this->db->where('f.nombre_supervisor', $supervisor);}
			if($asignacion!=""){	$this->db->where('f.nombre_chofer', $asignacion);}
			if($vehiculo!=""){	$this->db->where('f.patente', $vehiculo);}
			if($region!=""){	$this->db->where('f.region', $region);}
			if($gps!=""){	$this->db->where('f.gps', $gps);}

			$this->db->group_by('patente');
			$this->db->order_by('odometro', 'desc');
			$this->db->limit(40);
			$res=$this->db->get();

			$array = array();
			$array[]= array(
				"patente",
				"odometro",
			);
			if($res->num_rows()>0){
				foreach($res->result_array() as $key){
					$temp = array();
					$temp[] = $key['patente'];
					$temp[] = (int) $key['odometro'];
					$array[] = $temp;
				}
			}
			else{
				$temp = array();
				$temp[] = "";
				$temp[] = 0;
				$array[] = $temp;
			}

			return $array;
		}

		public function GastoRegionMuevo($desde,$hasta,$chofer,$supervisor,$vehiculo,$region){
			$this->db->select(
				"
				f.region as 'region',
				SUM(f.monto) as 'monto',
			");
			$this->db->from('flota_gps_muevo as f');
			if($desde!="" and $hasta!=""){$this->db->where("f.fecha BETWEEN '".$desde."' AND '".$hasta."'");}
			if($desde!="" and $hasta!=""){$this->db->where("f.fecha BETWEEN '".$desde."' AND '".$hasta."'");}
			if($chofer!=""){	$this->db->where('f.nombre_chofer', $chofer);}
			if($supervisor!=""){	$this->db->where('f.nombre_supervisor', $supervisor);}
			if($vehiculo!=""){	$this->db->where('f.patente', $vehiculo);}
			if($region!=""){	$this->db->where('f.region', $region);}
			

			$this->db->group_by('region');
			$this->db->order_by('region', 'asc');
			$res=$this->db->get();

			$array = array();
			$array[]= array(
				"Región",
				"Monto (\$CLP)",
			);
			if($res->num_rows()>0){
				foreach($res->result_array() as $key){
					$temp = array();
					$temp[] = $key['region'];
					$temp[] = (int) $key['monto'];
					$array[] = $temp;
				}
			}
			else{
				$temp = array();
				$temp[] = "";
				$temp[] = 0;
				$array[] = $temp;
			}

			return $array;
		}

		public function GastoSemanaMuevo($desde,$hasta,$chofer,$supervisor,$vehiculo,$region){
			$this->db->select(
				"
				DATE_FORMAT(MIN(fecha), '%m-%d') as inicio_semana, 
				DATE_FORMAT(MAX(fecha), '%m-%d') as fin_semana,
				YEAR(f.fecha) as 'anio',
				WEEK(f.fecha) as 'semana',
				SUM(f.monto) as 'monto',
			");
			$this->db->from('flota_gps_muevo as f');
			if($desde!="" and $hasta!=""){$this->db->where("f.fecha BETWEEN '".$desde."' AND '".$hasta."'");}
			if($chofer!=""){	$this->db->where('f.nombre_chofer', $chofer);}
			if($supervisor!=""){	$this->db->where('f.nombre_supervisor', $supervisor);}
			if($vehiculo!=""){	$this->db->where('f.patente', $vehiculo);}
			if($region!=""){	$this->db->where('f.region', $region);}
			

			$this->db->group_by('WEEK(f.fecha)');
			$this->db->order_by('WEEK(f.fecha)', 'asc');
			$res=$this->db->get();

			$array = array();
			$array[]= array(
				"Semana",
				"Monto (\$CLP)",
			);
			if($res->num_rows()>0){
				foreach($res->result_array() as $key){
					$temp = array();
					$temp[] = $key['anio']." - "."Semana ".intval($key['semana'])+1;
					$temp[] = (int) $key['monto'];
					$array[] = $temp;
				}
			}
			else{
				$temp = array();
				$temp[] = "";
				$temp[] = 0;
				$array[] = $temp;
			}

			return $array;
		}

		public function getActualizacionGPSMuevo($gps,$desde,$hasta,$region){
			$this->db->select("
				MAX(ultima_actualizacion) as ultima_actualizacion,
				FORMAT(SUM(monto), 0) as total,
			");
			if($gps!=""){	$this->db->where('gps', $gps);}
			if($desde!="" and $hasta!=""){$this->db->where("fecha BETWEEN '".$desde."' AND '".$hasta."'");}
			if($region!=""){	$this->db->where('f.region', $region);}
			$res = $this->db->get('flota_gps_muevo');
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

		public function getPatenteGPSMuevo($gps){
			$this->db->distinct();
			$this->db->select('patente');
			if($gps!=""){	$this->db->where('gps', $gps);}
			$res = $this->db->get('flota_gps_muevo');
			$this->db->order_by('patente', 'asc');
			if($res->num_rows()>0){
				$array=array();
				foreach($res->result_array() as $key){
					$temp=array();
					$temp["id"]=$key["patente"];
					$temp["text"]=$key["patente"];
					$array[]=$temp;
				}
				return json_encode($array);
			}
			return FALSE;
		}
		public function getSupervisorGPSMuevo($gps){
			$this->db->distinct();
			$this->db->select('nombre_supervisor');
			if($gps!=""){	$this->db->where('gps', $gps);}
			$res = $this->db->get('flota_gps_muevo');
			$this->db->order_by('nombre_supervisor', 'asc');
			if($res->num_rows()>0){
				$array=array();
				foreach($res->result_array() as $key){
					$temp=array();
					$temp["id"]=$key["nombre_supervisor"];
					$temp["text"]=$key["nombre_supervisor"];
					$array[]=$temp;
				}
				return json_encode($array);
			}
			return FALSE;
		}
		public function getChoferGPSMuevo($gps){
			$this->db->distinct();
			$this->db->select('nombre_chofer');
			if($gps!=""){	$this->db->where('gps', $gps);}
			$res = $this->db->get('flota_gps_muevo');
			$this->db->order_by('nombre_chofer', 'asc');
			if($res->num_rows()>0){
				$array=array();
				foreach($res->result_array() as $key){
					$temp=array();
					$temp["id"]=$key["nombre_chofer"];
					$temp["text"]=$key["nombre_chofer"];
					$array[]=$temp;
				}
				return json_encode($array);
			}
			return FALSE;
		}

		public function getRegionesGPSMuevo($gps){
			$this->db->distinct();
			$this->db->select('region');
			if($gps!=""){	$this->db->where('gps', $gps);}
			$res = $this->db->get('flota_gps_muevo');
			$this->db->order_by('region', 'asc');
			if($res->num_rows()>0){
				$array=array();
				foreach($res->result_array() as $key){
					$temp=array();
					$temp["id"]=$key["region"];
					$temp["text"]=$key["region"];
					$array[]=$temp;
				}
				return json_encode($array);
			}
			return FALSE;
		}


	/* DOCUMENTACION */

		public function listaDocumentoFlota($patente){
			$this->db->select("
				sha1(r.id) as hash,
				r.*,
				REPLACE(r.archivo,' ','_') as archivo,

			");
			$this->db->from('flota_documento as r');
			if($patente != ""){
				$this->db->where('r.patente',$patente);
			}
			$res=$this->db->get();
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}
	
		public function ingresarDocumento($data){
			if($this->db->insert('flota_documento', $data)){
				return TRUE;
			}
			return FALSE;
		} 

		public function ActualizarDocumento($id,$data){
			$this->db->where('sha1(id)', $id);
			if($this->db->update('flota_documento', $data)){
				
				return TRUE;
			}
			return FALSE;
		}

		public function eliminaDocumentoFlota($id){
			$this->db->where('sha1(id)', $id);
			if($this->db->delete('flota_documento')){
				
				return TRUE;
			}
			return FALSE;
		}

		public function getDocumentoFlota($hash){
			$this->db->select("
				sha1(r.id) as hash,
				r.*
			");
			$this->db->from('flota_documento as r');		
			$this->db->where('sha1(r.id)', $hash);
			$res=$this->db->get();
			return $res->result_array();
		
		}

		/**** MANTENEDOR *****/

		public function listaMantenedorFlota(){
			$this->db->select("
				sha1(r.id) as hash,
				r.*,
			");
			$this->db->from('flota_vehiculos as r');
			$res=$this->db->get();
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}
	
		public function ingresarFlota($data){
			if($this->db->insert('flota_vehiculos', $data)){
				return TRUE;
			}
			return FALSE;
		} 

		public function ActualizarFlota($id,$data){
			$this->db->where('sha1(id)', $id);
			if($this->db->update('flota_vehiculos', $data)){
				
				return TRUE;
			}
			return FALSE;
		}

		public function eliminaMantenedorFlota($id){
			$this->db->where('sha1(id)', $id);
			if($this->db->delete('flota_vehiculos')){
				
				return TRUE;
			}
			return FALSE;
		}

		public function getPatenteMantenedor(){
			$this->db->distinct();
			$this->db->select('patente');
			$res = $this->db->get('flota_vehiculos');
			$this->db->order_by('patente', 'asc');
			if($res->num_rows()>0){
				$array=array();
				foreach($res->result_array() as $key){
					$temp=array();
					$temp["id"]=$key["patente"];
					$temp["text"]=$key["patente"];
					$array[]=$temp;
				}
				return json_encode($array);
			}
			return FALSE;
		}

}