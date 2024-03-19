<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rremodel extends CI_Model {

		public function __construct(){
		parent::__construct();
	}

	public function insertarVisita($data){
	  if($this->db->insert('aplicaciones_visitas', $data)){
			return TRUE;
		}
		return FALSE;
	}
	
	/**********Rre*************/

		public function getRreList(){
			$this->db->select(
				"sha1(r.id) as hash,
				p.proyecto as proyecto,
				r.*,
				CONCAT(u.nombres,'  ',u.apellidos) as 'nombre_completo',
			");

			$this->db->join('usuarios_proyectos as p', 'r.id_proyecto = p.id', 'left');
			$this->db->join('usuarios as u', 'r.id_usuario = u.id', 'left');

			$this->db->order_by('r.fecha', 'desc');
			$res = $this->db->get('rre as r');
			return $res->result_array();
		}
		
		public function getDataRre($hash){
			$this->db->select('
				sha1(r.id) as hash,
                r.*,
			');
			$this->db->where('sha1(r.id)', $hash);
			$res=$this->db->get('rre as r');
			return $res->result_array();
		}

		public function formActualizar($id,$data){
			$this->db->where('sha1(id)', $id);
			if($this->db->update('rre', $data)){
				
				return TRUE;
			}
			return FALSE;
		}

		public function formIngreso($data){
			if($this->db->insert('rre', $data)){
				return $this->db->insert_id();
			}
			return FALSE;
		} 
		
		public function eliminaRre($hash){
			$this->db->where('sha1(id)', $hash);
			if($this ->db->delete('rre')){
				return TRUE;
			}
			return FALSE;
		}

		public function listaProyectos(){
			$this->db->order_by('proyecto', 'asc');
			$res=$this->db->get('usuarios_proyectos');
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}
		
		public function listaTrabajadores(){
		
			$this->db->select("concat(substr(replace(u.rut,'-',''),1,char_length(replace(u.rut,'-',''))-1),'-',substr(replace(u.rut,'-',''),char_length(replace(u.rut,'-','')))) as 'rut_format',
				u.empresa,u.id,u.rut,
				CONCAT(u.nombres,'  ',u.apellidos) as 'nombre_completo',
				CONCAT(SUBSTRING_INDEX(u.nombres, ' ', '1'),'  ',SUBSTRING_INDEX(SUBSTRING_INDEX(u.apellidos, ' ', '-2'), ' ', '1')) as 'nombre_corto',
				u.id_area,
				u.id_plaza,
				u.id_proyecto,
				u.id_cargo,
				");
	
			$this->db->join('usuarios_proyectos as p', 'p.id = u.id_proyecto', 'left');
			$this->db->join('usuarios_plazas as pl', 'pl.id = u.id_plaza', 'left');
			$this->db->join('usuarios_areas as a', 'a.id = u.id_area', 'left');
			$this->db->order_by('u.nombres', 'asc');
			$res = $this->db->get("usuarios as u");
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

}