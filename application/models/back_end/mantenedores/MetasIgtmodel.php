<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MetasIgtmodel extends CI_Model {

	public function __construct(){
		parent::__construct();
	}
	
	public function insertarVisita($data){
		if($this->db->insert('aplicaciones_visitas', $data)){
			return TRUE;
		}
		return FALSE;
	}

	public function listaMetasIgt(){
		$this->db->select("sha1(um.id) as hash_metas_igt,
			um.*,
			utn.nivel as nivel,
			utnm.indicador as indicador");

		$this->db->join('usuarios_tecnicos_niveles utn', 'utn.id = um.id_nivel', 'left');
		$this->db->join('usuarios_tecnicos_niveles_metas_indicadores utnm', 'utnm.id = um.id_indicador', 'left');
		$this->db->order_by('um.id_nivel', 'asc');
		$this->db->order_by('um.id_indicador', 'asc');
		$res=$this->db->get('usuarios_tecnicos_niveles_metas um');
		if($res->num_rows()>0){
			return $res->result_array();
		}
		return FALSE;
	}


	public function getDataMetasIgt($hash){
		$this->db->select("sha1(um.id) as hash_metas_igt,
			um.*,
			utn.nivel as nivel,
			utnm.indicador as indicador");

		$this->db->join('usuarios_tecnicos_niveles utn', 'utn.id = um.id_nivel', 'left');
		$this->db->join('usuarios_tecnicos_niveles_metas_indicadores utnm', 'utnm.id = um.id_indicador', 'left');
		$this->db->order_by('um.id_nivel', 'asc');
		$this->db->order_by('um.id_indicador', 'asc');
		$this->db->where('sha1(um.id)', $hash);
		$res=$this->db->get('usuarios_tecnicos_niveles_metas um');

		if($res->num_rows()>0){
			return $res->result_array();
		}
		return FALSE;
	}


	public function formMetasIgt($data){
		if($this->db->insert('usuarios_tecnicos_niveles_metas', $data)){
			return $this->db->insert_id();
		}
		return FALSE;
	}

	public function actualizarMetasIgt($hash,$data){
		$this->db->where('sha1(id)', $hash);
		if($this->db->update('usuarios_tecnicos_niveles_metas', $data)){
			return TRUE;
		}
		return FALSE;
	}

	
	public function eliminaMetasIgt($hash){
		$this->db->where('sha1(id)', $hash);
	    if($this ->db->delete('usuarios_tecnicos_niveles_metas')){
	    	return TRUE;
	    }
	    return FALSE;
	}

	public function listaNiveles(){
		$this->db->order_by('id', 'asc');
		$res = $this->db->get('usuarios_tecnicos_niveles');
		return $res->result_array();
	}

	public function listaIndicadores(){
		$this->db->order_by('id', 'asc');
		$res = $this->db->get('usuarios_tecnicos_niveles_metas_indicadores');
		return $res->result_array();
	}

	public function existeMeta($hash,$nivel,$indicador){
		$this->db->where('un.id_nivel', $nivel);
		$this->db->where('un.id_indicador', $indicador);
		$this->db->where('sha1(un.id)<>', $hash);
		$res = $this->db->get('usuarios_tecnicos_niveles_metas un');
		if($res->num_rows()>0){
			return TRUE;
		}
		return FALSE;
	}
		

}

