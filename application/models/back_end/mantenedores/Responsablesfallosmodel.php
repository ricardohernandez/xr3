<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Responsablesfallosmodel extends CI_Model {

	public function __construct(){
		parent::__construct();
	}
	
	public function insertarVisita($data){
		if($this->db->insert('aplicaciones_visitas', $data)){
			return TRUE;
		}
		return FALSE;
	}


	public function listaResponsablesFallosHerramientas($proyecto){
		$this->db->select("chr.*,
			sha1(chr.id) as hash_responsable_fallos,
			chl.descripcion as descripcion,
			ct.tipo as tipo,
			CONCAT(u.nombres,' ',u.apellidos)  'responsable',
			upr.proyecto as proyecto,
			");
		$this->db->join('checklist_herramientas_listado chl', 'chl.id = chr.id_item', 'left');
		$this->db->join('usuarios u', 'u.id = chr.id_responsable', 'left');
		$this->db->join('usuarios_proyectos upr', 'upr.id = chr.id_proyecto', 'left');
		$this->db->join('checklist_herramientas_tipos ct', 'ct.id = chl.tipo', 'left');

		if($proyecto!=""){
			$this->db->where('chr.id_proyecto' , $proyecto);
		}

		$res=$this->db->get('checklist_herramientas_responsables chr');
		if($res->num_rows()>0){
			return $res->result_array();
		}
		return FALSE;
	}


	public function getDataResponsableFallosHerramientas($hash){
		$this->db->select("chr.*,
			sha1(chr.id) as hash_responsable_fallos,
			chl.descripcion as descripcion,
			CONCAT(u.nombres,' ',u.apellidos)  'responsable',
			upr.proyecto as proyecto,
			");
		$this->db->join('checklist_herramientas_listado chl', 'chl.id = chr.id_item', 'left');
		$this->db->join('usuarios u', 'u.id = chr.id_responsable', 'left');
		$this->db->join('usuarios_proyectos upr', 'upr.id = chr.id_proyecto', 'left');
		$this->db->where('sha1(chr.id)', $hash);
		$res=$this->db->get('checklist_herramientas_responsables chr');
		if($res->num_rows()>0){
			return $res->result_array();
		}
		return FALSE;
	}


	public function formResponsablesFallosHerramientas($data){
		if($this->db->insert('checklist_herramientas_responsables', $data)){
			return $this->db->insert_id();
		}
		return FALSE;
	}

	public function formActualizarResponsablesFallosHerramientas($hash,$data){
		$this->db->where('sha1(id)', $hash);
		if($this->db->update('checklist_herramientas_responsables', $data)){
			return TRUE;
		}
		return FALSE;
	}

	public function listaProyectosC(){
		$this->db->order_by('proyecto', 'asc');
		$res=$this->db->get('usuarios_proyectos');
		if($res->num_rows()>0){
			return $res->result_array();
		}
		return FALSE;
	}

	public function listaItemsHerramientas(){
		$this->db->order_by('tipo', 'asc');
		$this->db->order_by('descripcion', 'asc');
		$res=$this->db->get('checklist_herramientas_listado');
		return $res->result_array();
	}

	public function listaResponsablesFallos(){
		$this->db->select("u.id,CONCAT(u.nombres,' ',u.apellidos)  'responsable'");
		$this->db->order_by('u.nombres', 'asc');
		/*$this->db->where('u.id_perfil', 1);*/
		$res=$this->db->get('usuarios u');
		if($res->num_rows()>0){
			return $res->result_array();
		}
		return FALSE;
	}

	public function existeResponsableItem($item,$proyecto){
		$this->db->where('id_item', $item);
		$this->db->where('id_proyecto', $proyecto);
		$res=$this->db->get('checklist_herramientas_responsables');
		if($res->num_rows()>0){
			return TRUE;
		}
		return FALSE;
	}

	public function responsableTieneCorreo($id){
		$this->db->select('correo_empresa');
		$this->db->where('id', $id);
		$res = $this->db->get('usuarios');

		if($res->num_rows()>0){
			$row = $res->row_array();

			if($row["correo_empresa"]!=""){
				return TRUE;
			}
		}
		
		return FALSE;
	}

	
	public function existeResponsableItemMod($hash,$item,$proyecto){
		$this->db->where('id_item', $item);
		$this->db->where('id_proyecto', $proyecto);
		$this->db->where('sha1(id)<>', $hash);
		$res=$this->db->get('checklist_herramientas_responsables');
		if($res->num_rows()>0){
			return TRUE;
		}
		return FALSE;
	}

	public function truncateResponsablesHerramientas(){
		$this->db->truncate('checklist_herramientas_responsables');
	}

}

