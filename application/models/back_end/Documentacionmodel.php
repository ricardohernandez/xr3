<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Documentacionmodel extends CI_Model {

	public function __construct(){
		parent::__construct();
	}
	
	public function insertarVisita($data){
		if($this->db->insert('aplicaciones_visitas', $data)){
			return TRUE;
		}
		return FALSE;
	}

	/**********CAPACITACION************/
		
		public function getCapacitacionList(){
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

		public function formIngresoCapacitacion($data){
			if($this->db->insert('documentacion_capacitacion', $data)){
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

	
	/**********REPORTES************/
		
		public function getReportesList(){
			$this->db->select("sha1(c.id) as hash_id,
					c.*,
					CONCAT(u.nombres,' ',u.apellidos)  'digitador'");
			$this->db->join('usuarios u', 'u.id = c.id_digitador', 'left');
			$res=$this->db->get('documentacion_reportes as c');
			return $res->result_array();
		}

		
		public function getDataRegistroReportes($hash){
			$this->db->select("sha1(c.id) as hash_id,
					c.*");
			$this->db->where('sha1(c.id)', $hash);
			$res=$this->db->get('documentacion_reportes as c');
			return $res->result_array();
		}


		public function formActualizarReportes($id,$data){
			$this->db->where('sha1(id)', $id);
		    if($this->db->update('documentacion_reportes', $data)){
		    	return TRUE;
		    }
		    return FALSE;
		}

		public function formIngresoReportes($data){
			if($this->db->insert('documentacion_reportes', $data)){
				return $this->db->insert_id();
			}
			return FALSE;
		} 
		
		public function eliminaReportes($hash){
			$this->db->where('sha1(id)', $hash);
			if($this ->db->delete('documentacion_reportes')){
			  	return TRUE;
			}
			return FALSE;
		}

	
	/**********PREVENCION************/
		
		public function getPrevencionList(){
			$this->db->select("sha1(c.id) as hash_id,
					c.*,
					CONCAT(u.nombres,' ',u.apellidos)  'digitador'");
			$this->db->join('usuarios u', 'u.id = c.id_digitador', 'left');
			$res=$this->db->get('documentacion_prevencion as c');
			return $res->result_array();
		}

		
		public function getDataRegistroPrevencion($hash){
			$this->db->select("sha1(c.id) as hash_id,
					c.*");
			$this->db->where('sha1(c.id)', $hash);
			$res=$this->db->get('documentacion_prevencion as c');
			return $res->result_array();
		}


		public function formActualizarPrevencion($id,$data){
			$this->db->where('sha1(id)', $id);
		    if($this->db->update('documentacion_prevencion', $data)){
		    	return TRUE;
		    }
		    return FALSE;
		}

		public function formIngresoPrevencion($data){
			if($this->db->insert('documentacion_prevencion', $data)){
				return $this->db->insert_id();
			}
			return FALSE;
		} 
		
		public function eliminaPrevencion($hash){
			$this->db->where('sha1(id)', $hash);
			if($this ->db->delete('documentacion_prevencion')){
			  	return TRUE;
			}
			return FALSE;
		}		

}