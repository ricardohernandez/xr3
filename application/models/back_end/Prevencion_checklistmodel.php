<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Prevencion_checklistmodel extends CI_Model {

		public function __construct(){
		parent::__construct();
	}

	public function insertarVisita($data){
	  if($this->db->insert('aplicaciones_visitas', $data)){
			return TRUE;
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

	public function listaComunas(){
		$this->db->select('c.*');	
		$this->db->from('comunas as c');
		$this->db->order_by('titulo', 'asc');
		$res=$this->db->get();
		if($res->num_rows()>0){
			return $res->result_array();
		}
	}

	public function listacargos(){
		$this->db->select('sha1(c.id) as hash,
			c.*,');
		$this->db->order_by('c.cargo', 'asc');
		$res=$this->db->get('usuarios_cargos c');
		return $res->result_array();
	}

	public function listaplazas(){
		$this->db->select('sha1(p.id) as hash,
			p.*,');
		$this->db->order_by('p.plaza', 'asc');
		$res=$this->db->get('usuarios_plazas p');
		return $res->result_array();
	}

	public function listaproyectos(){
		$this->db->select('sha1(p.id) as hash,
			p.*,');
		$this->db->order_by('p.proyecto', 'asc');
		$res=$this->db->get('usuarios_proyectos p');
		return $res->result_array();
	}

/********* EPPS Y CONDICIONES ENCONTRADAS **************/ 

	public function getCondicionesList(){
		$this->db->select("
			sha1(c.id) as hash,
			c.*
		");
		$this->db->from('prevencion_condiciones as c');	
		$res=$this->db->get();
		if($res->num_rows()>0){
			return $res->result_array();
		}
		return FALSE;
	}

	public function getDataCondicion($hash){
		$this->db->select("
			sha1(c.id) as hash,
			c.*
		");
		$this->db->from('prevencion_condiciones as c');		
		$this->db->where('sha1(c.id)', $hash);
		$res=$this->db->get();
		return $res->result_array();
	
	}

	public function formCondiciones($data){
		if($this->db->insert('prevencion_condiciones', $data)){
			return $this->db->insert_id();
		}
		return FALSE;
	} 

	public function ingresarCondicion($data){
		if($this->db->insert('prevencion_condiciones', $data)){
			return TRUE;
		}
		return FALSE;
	} 

	public function ActualizarCondicion($id,$data){
		$this->db->where('sha1(id)', $id);
	    if($this->db->update('prevencion_condiciones', $data)){
	    	
	    	return TRUE;
	    }
	    return FALSE;
	}

	public function eliminaCondicion($hash){
		$this->db->where('sha1(id)', $hash);
		if($this ->db->delete('prevencion_condiciones')){
		  	return TRUE;
		}
		return FALSE;
	}

	public function listaepps(){
		$this->db->select('sha1(c.id) as hash,
			c.*,
			ct.tipo as tipo');
		$this->db->join('checklist_herramientas_tipos ct', 'ct.id = c.tipo', 'left');
		$this->db->order_by('ct.tipo', 'asc');
		$this->db->order_by('c.descripcion', 'asc');
		$this->db->where('c.tipo', 3);
		$res=$this->db->get('checklist_herramientas_listado c');
		return $res->result_array();
	}

	public function listariesgos(){
		$this->db->select('sha1(p.id) as hash,
			p.*,');
		$this->db->order_by('p.riesgo', 'asc');
		$res=$this->db->get('prevencion_riesgo p');
		return $res->result_array();
	}

	public function listacciones(){
		$this->db->select('sha1(p.id) as hash,
			p.*,');
		$this->db->order_by('p.accion', 'asc');
		$res=$this->db->get('prevencion_accion p');
		return $res->result_array();
	}

/********* INVESTIGACION FINAL **************/ 

	public function getInvestigacionesList(){
		$this->db->select("
			sha1(i.id) as hash,
			CONCAT(i.nombre_testigo_1,' - ',i.relacion_testigo_1) as testigo1,
			CONCAT(i.nombre_testigo_2,' - ',i.relacion_testigo_2) as testigo2,
			CONCAT(i.nombre_testigo_3,' - ',i.relacion_testigo_3) as testigo3,
			i.*
		");
		$this->db->from('prevencion_investigacion_accidentes as i');	
		$res=$this->db->get();
		if($res->num_rows()>0){
			return $res->result_array();
		}
		return FALSE;
	}

	public function getDataInvestigacion($hash){
		$this->db->select("
			sha1(i.id) as hash,
			i.*
		");
		$this->db->from('prevencion_investigacion_accidentes as i');		
		$this->db->where('sha1(i.id)', $hash);
		$res=$this->db->get();
		return $res->result_array();
	
	}

	public function formInvestigaciones($data){
		if($this->db->insert('prevencion_investigacion_accidentes', $data)){
			return $this->db->insert_id();
		}
		return FALSE;
	} 

	public function ingresarInvestigacion($data){
		if($this->db->insert('prevencion_investigacion_accidentes', $data)){
			return TRUE;
		}
		return FALSE;
	} 

	public function ActualizarInvestigacion($id,$data){
		$this->db->where('sha1(id)', $id);
	    if($this->db->update('prevencion_investigacion_accidentes', $data)){
	    	
	    	return TRUE;
	    }
	    return FALSE;
	}

	public function eliminaInvestigacion($hash){
		$this->db->where('sha1(id)', $hash);
		if($this ->db->delete('prevencion_investigacion_accidentes')){
		  	return TRUE;
		}
		return FALSE;
	}

/********* REUNIONES DE EQUIPO **************/ 

	public function getReunionesList(){
		$this->db->select("
			sha1(r.id) as hash,
			r.*
		");
		$this->db->from('prevencion_reuniones as r');	
		$res=$this->db->get();
		if($res->num_rows()>0){
			return $res->result_array();
		}
		return FALSE;
	}

	public function getDataReunion($hash){
		$this->db->select("
			sha1(r.id) as hash,
			r.*
		");
		$this->db->from('prevencion_reuniones as r');		
		$this->db->where('sha1(r.id)', $hash);
		$res=$this->db->get();
		return $res->result_array();
	
	}

	public function formReuniones($data){
		if($this->db->insert('prevencion_reuniones', $data)){
			return $this->db->insert_id();
		}
		return FALSE;
	} 

	public function ingresarReunion($data){
		if($this->db->insert('prevencion_reuniones', $data)){
			return TRUE;
		}
		return FALSE;
	} 

	public function ActualizarReunion($id,$data){
		$this->db->where('sha1(id)', $id);
	    if($this->db->update('prevencion_reuniones', $data)){
	    	
	    	return TRUE;
	    }
	    return FALSE;
	}

	public function eliminaReunion($hash){
		$this->db->where('sha1(id)', $hash);
		if($this ->db->delete('prevencion_reuniones')){
		  	return TRUE;
		}
		return FALSE;
	}
}