<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ChecklistFTTHmodel extends CI_Model {

	public function __construct(){
		parent::__construct();
	}
	
	public function insertarVisita($data){
		if($this->db->insert('aplicaciones_visitas', $data)){
			return TRUE;
		}
		return FALSE;
	}

	public function listaMantChecklistFTTH($tipo){
		$this->db->select("sha1(c.id) as hash_herramienta,
			c.*,
			ct.tipo as tipo");

		if($tipo!=""){
			$this->db->where('c.tipo', $tipo);
		}

		$this->db->join('checklist_ftth_tipos ct', 'ct.id = c.tipo', 'left');
		$this->db->order_by('c.descripcion', 'asc');
		$this->db->order_by('ct.tipo', 'asc');
		$res=$this->db->get('checklist_ftth_listado c');
		if($res->num_rows()>0){
			return $res->result_array();
		}
		return FALSE;
	}


	public function getDataMantChecklistFTTH($hash){
		$this->db->select("sha1(c.id) as hash_herramienta,
			c.*,
			ct.id as id_tipo,
			ct.tipo as tipo
			");
		$this->db->join('checklist_ftth_tipos ct', 'ct.id = c.tipo', 'left');
		$this->db->where('sha1(c.id)', $hash);
		$res=$this->db->get('checklist_ftth_listado c');
		if($res->num_rows()>0){
			return $res->result_array();
		}
		return FALSE;
	}


	public function formMantChecklistFTTH($data){
		if($this->db->insert('checklist_ftth_listado', $data)){
			return $this->db->insert_id();
		}
		return FALSE;
	}

	public function actualizarMantChecklistFTTH($hash,$data){
		$this->db->where('sha1(id)', $hash);
		if($this->db->update('checklist_ftth_listado', $data)){
			return TRUE;
		}
		return FALSE;
	}

	public function getTipos(){
		$this->db->order_by('tipo', 'asc');
		$res=$this->db->get('checklist_ftth_tipos');
		return $res->result_array();
	}

		public function eliminaMantChecklistFTTH($hash){
		$this->db->where('sha1(id)', $hash);
	    if($this ->db->delete('checklist_ftth_listado')){
	    	return TRUE;
	    }
	    return FALSE;
	}
		

}

