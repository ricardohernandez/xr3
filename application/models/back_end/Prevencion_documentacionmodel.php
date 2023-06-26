<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Prevencion_documentacionmodel extends CI_Model {

	public function __construct(){
		parent::__construct();
	}
	
	public function insertarVisita($data){
		if($this->db->insert('aplicaciones_visitas', $data)){
			return TRUE;
		}
		return FALSE;
	}

	/**********NORMATIVAS************/
		
		public function getNormativasList($tipo){
			$this->db->select("sha1(c.id) as hash_id,
					c.*,
					CONCAT(u.nombres,' ',u.apellidos)  'autor'");
			$this->db->join('usuarios u', 'u.id = c.id_autor', 'left');
			$this->db->where('c.id_categoria', $tipo);
			$res=$this->db->get('documentacion_prevencion_modulos as c');
			
			return $res->result_array();
		}

		
		public function getDataNormativas($hash){
			$this->db->select("sha1(c.id) as hash_id,
					c.*");
			$this->db->where('sha1(c.id)', $hash);
			$res=$this->db->get('documentacion_prevencion_modulos as c');
			return $res->result_array();
		}


		public function formActualizarNormativas($id,$data){
			$this->db->where('sha1(id)', $id);
		    if($this->db->update('documentacion_prevencion_modulos', $data)){
		    	return TRUE;
		    }
		    return FALSE;
		}

		public function formIngresoNormativas($data){
			if($this->db->insert('documentacion_prevencion_modulos', $data)){
				return $this->db->insert_id();
			}
			return FALSE;
		} 
		
		public function eliminaNormativas($hash){
			$this->db->where('sha1(id)', $hash);
			if($this ->db->delete('documentacion_prevencion_modulos')){
			  	return TRUE;
			}
			return FALSE;
		}

		
		public function getImagen($hash){
			$this->db->select('archivo');
			$this->db->where('sha1(id)', $hash);
			$res=$this->db->get('documentacion_prevencion_modulos');
			$row=$res->row_array();
			return $row["archivo"];
		}


	}