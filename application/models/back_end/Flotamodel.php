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
			FORMAT((SUM(f.kms_recorridos)/SUM(f.volumen)), 0) as km_lt,
			FORMAT((SUM(f.monto)/SUM(f.volumen)), 0) as clp_lt,
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
				"1" => 'Ene',
				"2" => 'Feb',
				"3" => 'Mar',
				"4" => 'Abr',
				"5" => 'May',
				"6" => 'Jun',
				"7" => 'Jul',
				"8" => 'Ago',
				"9" => 'Sept',
				"10" => 'Oct',
				"11" => 'Nov',
				"12" => 'Dic'
			);

			$array = array();
			$array[]= array(
				"Mes",
				"Monto (\$CLP)",
				"Carga (Litros)",
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

		public function GastoRegion($desde,$hasta,$chofer,$supervisor,$vehiculo,$comuna){
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
			if($comuna!=""){	$this->db->where('f.region', $comuna);}
			

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

		public function GastoSemana($desde,$hasta,$chofer,$supervisor,$vehiculo,$comuna){
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
			if($comuna!=""){	$this->db->where('f.region', $comuna);}
			

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
		public function GastoCombustibleRegion($desde,$hasta,$chofer,$supervisor,$vehiculo,$comuna){
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
			if($comuna!=""){	$this->db->where('f.region', $comuna);}
			

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

		public function GastoCombustibleSemana($desde,$hasta,$chofer,$supervisor,$vehiculo,$comuna){
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
			if($comuna!=""){	$this->db->where('f.region', $comuna);}
			

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

		public function listaDetalleFlota($desde,$hasta,$asignacion,$supervisor,$vehiculo,$comuna,$gps){
			$this->db->select("
			sha1(f.id) as hash,
			f.*");

			if($desde!="" and $hasta!=""){$this->db->where("f.fecha BETWEEN '".$desde."' AND '".$hasta."'");}
			if($supervisor!=""){	$this->db->where('f.nombre_supervisor', $supervisor);}
			if($asignacion!=""){	$this->db->where('f.nombre_chofer', $asignacion);}
			if($vehiculo!=""){	$this->db->where('f.patente', $vehiculo);}
			if($comuna!=""){	$this->db->where('f.comuna', $comuna);}
			if($gps!=""){	$this->db->where('f.gps', $gps);}

			$this->db->order_by('velocidad', 'desc');
			$res=$this->db->get('flota_gps as f');
			if($res->num_rows()>0){
				return $res->result_array();
			}else{
				return FALSE;
			}
		}

		public function listaTotal($desde,$hasta,$asignacion,$supervisor,$vehiculo,$comuna,$gps){
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
				FORMAT(MAX(velocidad), 0) as max_velocidad,
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

}