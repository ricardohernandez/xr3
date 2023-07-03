<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard_operacionesmodel extends CI_Model {

	public function __construct(){
		parent::__construct();
	}
	
	public function insertarVisita($data){
		if($this->db->insert('aplicaciones_visitas', $data)){
			return TRUE;
		}
		return FALSE;
	}

	/**********PRODUCTIVIDAD XR3************/

		public function getDataProductividad($tipo,$mes_inicio,$mes_termino) {
			
			$campos = $tipo['campos'];
			$cabeceras = $tipo['cabeceras'];
		
			$this->db->select("fecha," . implode(",", $campos));
	
			if($mes_inicio!=""){
				$this->db->where('fecha >=', date('Y-m-01', strtotime($mes_inicio)));
				$this->db->where('fecha <=', date('Y-m-t', strtotime($mes_termino)));
			}else{
				$this->db->where('fecha >=', date('Y-m-01', strtotime('January')));
			}

			$res = $this->db->get('dashboard_productividad');

			$array = [];
			$array[] = $cabeceras;
		
			foreach ($res->result_array() as $key) {
				$temp = [];
				$temp[] = mesesCorto(date("n", strtotime($key["fecha"]))) . "-" . date("y", strtotime($key["fecha"]));
				
				foreach ($campos as $campo) {
					$temp[] = ($key[$campo] != 0) ? (float)$key[$campo] : 0;
					$temp[] = ($key[$campo] != 0) ? (float)$key[$campo] : 0;
				}
				
				$temp[] = strtotime($key["fecha"]);
				$array[] = $temp;
			}
		
			return $array;
		}
	

		public function listaDashboardProductividad(){
			$this->db->select("sha1(c.id) as hash_id,
					c.*,
					CONCAT(u.nombres,' ',u.apellidos)  'digitador'");
			$this->db->join('usuarios u', 'u.id = c.id_digitador', 'left');
			$res=$this->db->get('documentacion_capacitacion as c');
			return $res->result_array();
		}

		
		public function getDataRegistroCapacitacion($hash){
			$this->db->select("sha1(c.id) as hash_id,
					c.*");
			$this->db->where('sha1(c.id)', $hash);
			$res=$this->db->get('documentacion_capacitacion as c');
			return $res->result_array();
		}


		public function formActualizarCapacitacion($id,$data){
			$this->db->where('sha1(id)', $id);
		    if($this->db->update('documentacion_capacitacion', $data)){
		    	return TRUE;
		    }
		    return FALSE;
		}

		public function formProductividadCalidadXr3($data){
			if($this->db->insert('dashboard_productividad', $data)){
				return $this->db->insert_id();
			}
			return FALSE;
		} 
		
		public function eliminaCapacitacion($hash){
			$this->db->where('sha1(id)', $hash);
			if($this ->db->delete('documentacion_capacitacion')){
			  	return TRUE;
			}
			return FALSE;
		}

	
	

}