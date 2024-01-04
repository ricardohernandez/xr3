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

		public function listaCombustible($desde,$hasta,$chofer,$supervisor,$vehiculo,$comuna){
			$this->db->select("
			sha1(f.id) as hash,
			f.*,
			FORMAT(SUM(f.volumen),0) AS litros_cargados,
			FORMAT(SUM(f.kms_recorridos),0) AS kms_recorridos_total,
			FORMAT(SUM(f.monto),0) AS monto_total,
			FORMAT(f.meta_monto, 0) as meta_monto,
			");

			if($desde!="" and $hasta!=""){$this->db->where("f.fecha BETWEEN '".$desde."' AND '".$hasta."'");}
			if($chofer!=""){	$this->db->where('f.nombre_chofer', $chofer);}
			if($supervisor!=""){	$this->db->where('f.nombre_supervisor', $supervisor);}
			if($vehiculo!=""){	$this->db->where('f.patente', $vehiculo);}
			if($comuna!=""){	$this->db->where('f.region', $comuna);}
			$res=$this->db->group_by('f.patente');
			$res=$this->db->get('flota_combustible as f');
			if($res->num_rows()>0){
				return $res->result_array();
			}else{
				return FALSE;
			}
		}

		public function listaMax($desde,$hasta,$chofer,$supervisor,$vehiculo,$comuna){
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
			if($comuna!=""){	$this->db->where('f.region', $comuna);}

			$res=$this->db->get('flota_combustible as f');
			if($res->num_rows()>0){
				return $res->result_array();
			}else{
				return FALSE;
			}
		}

		public function listaCarga($desde,$hasta,$chofer,$supervisor,$vehiculo,$comuna){
			$this->db->select(
				"
				MONTH(f.fecha) as 'mes',
				SUM(f.monto) as 'monto',
				SUM(f.volumen) as 'carga',
			");
			$this->db->from('flota_combustible as f');
			if($desde!="" and $hasta!=""){$this->db->where("f.fecha BETWEEN '".$desde."' AND '".$hasta."'");}
			if($chofer!=""){$this->db->where('f.nombre_chofer', $chofer);}
			if($supervisor!=""){$this->db->where('f.nombre_supervisor', $supervisor);}
			if($vehiculo!=""){$this->db->where('f.patente', $vehiculo);}
			if($comuna!=""){$this->db->where('f.region', $comuna);}

			$this->db->group_by('mes');
			$this->db->order_by('mes', 'asc');
			$res=$this->db->get();

			$mes = array(
				"0" => 'Nulo',
				"01" => 'Ene',
				"02" => 'Feb',
				"03" => 'Mar',
				"04" => 'Abr',
				"05" => 'May',
				"06" => 'Jun',
				"07" => 'Jul',
				"08" => 'Ago',
				"09" => 'Sept',
				"10" => 'Oct',
				"11" => 'Nov',
				"12" => 'Dic'
			);

			$array = array();
			$array[]= array(
				"mes",
				"monto",
				"carga",
			);
			if($res->num_rows()>0){
				foreach($res->result_array() as $key){
					$temp = array();
					$temp[] = $mes[$key['mes']];
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

		public function getActualizacionCombustible(){
			$this->db->select("
				MAX(f.ultima_actualizacion) as ultima_actualizacion,
				FORMAT(SUM(f.monto), 0) as total,
			");

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
				return $res->result_array();
			}
			return FALSE;
		}
		public function getSupervisorCombustible(){
			$this->db->distinct();
			$this->db->select('nombre_supervisor');
			$res = $this->db->get('flota_combustible');
			$this->db->order_by('nombre_supervisor', 'asc');
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}
		public function getChoferCombustible(){
			$this->db->distinct();
			$this->db->select('nombre_chofer');
			$res = $this->db->get('flota_combustible');
			$this->db->order_by('nombre_chofer', 'asc');
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}
		public function getComunaCombustible(){
			$this->db->distinct();
			$this->db->select('region');
			$res = $this->db->get('flota_combustible');
			$this->db->order_by('region', 'asc');
			if($res->num_rows()>0){
				return $res->result_array();
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

		public function listaGPS($desde,$hasta,$asignacion,$supervisor,$vehiculo,$gps){
			$this->db->select("
			f.*,
			sha1(f.id) as hash,
			FORMAT(COUNT(*), 0) as infracciones,
			");

			if($desde!="" and $hasta!=""){$this->db->where("f.fecha BETWEEN '".$desde."' AND '".$hasta."'");}
			if($supervisor!=""){	$this->db->where('f.nombre_supervisor', $supervisor);}
			if($asignacion!=""){	$this->db->where('f.nombre_chofer', $asignacion);}
			if($vehiculo!=""){	$this->db->where('f.patente', $vehiculo);}
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

		public function listaDetalleFlota($desde,$hasta,$asignacion,$supervisor,$vehiculo,$gps){
			$this->db->select("
			sha1(f.id) as hash,
			f.*");

			if($desde!="" and $hasta!=""){$this->db->where("f.fecha BETWEEN '".$desde."' AND '".$hasta."'");}
			if($supervisor!=""){	$this->db->where('f.nombre_supervisor', $supervisor);}
			if($asignacion!=""){	$this->db->where('f.nombre_chofer', $asignacion);}
			if($vehiculo!=""){	$this->db->where('f.patente', $vehiculo);}
			if($gps!=""){	$this->db->where('f.gps', $gps);}

			$this->db->order_by('velocidad', 'desc');
			$res=$this->db->get('flota_gps as f');
			if($res->num_rows()>0){
				return $res->result_array();
			}else{
				return FALSE;
			}
		}

		public function listaTotal($desde,$hasta,$asignacion,$supervisor,$vehiculo,$gps){
			$this->db->select(
				"
				f.patente as 'patente',
				COUNT(f.velocidad) as 'infracciones',
			");
			$this->db->from('flota_gps as f');
			if($desde!="" and $hasta!=""){$this->db->where("f.fecha BETWEEN '".$desde."' AND '".$hasta."'");}
			if($supervisor!=""){	$this->db->where('f.nombre_supervisor', $supervisor);}
			if($asignacion!=""){	$this->db->where('f.nombre_chofer', $asignacion);}
			if($vehiculo!=""){	$this->db->where('f.patente', $vehiculo);}
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

		public function getActualizacionGPS($gps){
			$this->db->select("
				FORMAT(MAX(velocidad), 0) as max_velocidad,
				FORMAT(COUNT(*), 0) as conteo_infracciones,
				FORMAT(COUNT(DISTINCT patente), 0) as vehiculos_infractores,
			");
			if($gps!=""){	$this->db->where('gps', $gps);}
			$res = $this->db->get('flota_gps');
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

		public function getPatenteGPS(){
			$this->db->distinct();
			$this->db->select('patente');
			$res = $this->db->get('flota_gps');
			$this->db->order_by('patente', 'asc');
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}
		public function getSupervisorGPS(){
			$this->db->distinct();
			$this->db->select('nombre_supervisor');
			$res = $this->db->get('flota_gps');
			$this->db->order_by('nombre_supervisor', 'asc');
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}
		public function getChoferGPS(){
			$this->db->distinct();
			$this->db->select('nombre_chofer');
			$res = $this->db->get('flota_gps');
			$this->db->order_by('nombre_chofer', 'asc');
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

}