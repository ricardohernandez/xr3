<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rcdcmodel extends CI_Model {

		public function __construct(){
		parent::__construct();
	}

	public function insertarVisita($data){
	  if($this->db->insert('aplicaciones_visitas', $data)){
			return TRUE;
		}
		return FALSE;
	}
	
	/**********RCDC*************/

		public function getRcdcList($desde,$hasta,$coordinador,$comuna,$zona,$empresa){
			$this->db->select(
				"sha1(r.id) as hash,
				pl.titulo as comuna,
				CONCAT(SUBSTRING_INDEX(u.nombres, ' ', '1'),'  ',SUBSTRING_INDEX(SUBSTRING_INDEX(u.apellidos, ' ', '-2'), ' ', '1')) as nombre_tecnico,
				CONCAT(SUBSTRING_INDEX(u2.nombres, ' ', '1'),'  ',SUBSTRING_INDEX(SUBSTRING_INDEX(u2.apellidos, ' ', '-2'), ' ', '1')) as nombre_coordinador,
                a.area as zona,
				p.proyecto as proyecto,
				r.*,
				CONCAT(r.hora,':',r.minuto) as duracion,
				rt.tipo as tipo,
				rm.motivo as motivo,
			");

			$this->db->join('comunas_rcdc as pl', 'pl.id = r.id_comuna', 'left');
			$this->db->join('rcdc_tipos as rt', 'rt.id = r.id_tipo', 'left');
			$this->db->join('rcdc_motivos as rm', 'rm.id = r.id_motivo', 'left');
			$this->db->join('usuarios as u', 'r.id_tecnico = u.id', 'left');
			$this->db->join('usuarios as u2', 'r.id_coordinador = u2.id', 'left');
			$this->db->join('usuarios_areas as a', 'r.id_zona = a.id', 'left');
			$this->db->join('usuarios_proyectos as p', 'r.id_proyecto = p.id', 'left');

			if($desde!="" and $hasta!=""){
				$this->db->where("r.fecha_ingreso BETWEEN '".$desde."' AND '".$hasta."'");
			}
			if($coordinador!=""){
				$this->db->where('r.id_coordinador',$coordinador);
			}
			if($comuna!=""){
				$this->db->where('r.id_comuna',$comuna);
			}
			if($zona!=""){
				$this->db->where('r.id_zona',$zona);
			}
			if($empresa!=""){
				$this->db->where('r.id_proyecto',$empresa);
			}

			$this->db->order_by('r.fecha_ingreso', 'desc');
			$res = $this->db->get('rcdc as r');
			return $res->result_array();
		}
		
		public function getDataRcdc($hash){
			$this->db->select('
				sha1(r.id) as hash,
                r.*,
			');
			$this->db->where('sha1(r.id)', $hash);
			$res=$this->db->get('rcdc as r');
			return $res->result_array();
		}

		public function formActualizar($id,$data){
			$this->db->where('sha1(id)', $id);
			if($this->db->update('rcdc', $data)){
				
				return TRUE;
			}
			return FALSE;
		}

		public function formIngreso($data){
			if($this->db->insert('rcdc', $data)){
				return $this->db->insert_id();
			}
			return FALSE;
		} 
		
		public function eliminaRcdc($hash){
			$this->db->where('sha1(id)', $hash);
			if($this ->db->delete('rcdc')){
				return TRUE;
			}
			return FALSE;
		}

	/************** OTROS ****************/

	public function listaComunas(){
		$this->db->select('c.*');	
		$this->db->from('comunas_rcdc as c');
		$this->db->order_by('titulo', 'asc');
		$res=$this->db->get();
		if($res->num_rows()>0){
			return $res->result_array();
		}
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

	public function listaZonas(){
		$this->db->select('id,area');
		$this->db->order_by('area', 'asc');
		$res=$this->db->get('usuarios_areas');
		if($res->num_rows()>0){
			return $res->result_array();
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

	public function listaTramos(){
		$this->db->order_by('tramo', 'asc');
		$res=$this->db->get('rcdc_tramos');
		if($res->num_rows()>0){
			return $res->result_array();
		}
		return FALSE;
	}

	public function listaTipos(){
		$this->db->order_by('tipo', 'asc');
		$res=$this->db->get('rcdc_tipos');
		if($res->num_rows()>0){
			return $res->result_array();
		}
		return FALSE;
	}

	public function listaMotivos($tipo){
		if($tipo != ""){
			$this->db->where('id_tipo', $tipo);
		}
		$this->db->order_by('motivo', 'asc');
		$res=$this->db->get('rcdc_motivos');
		if($res->num_rows()>0){
			return $res->result_array();
		}
		return FALSE;
	}

	public function listaPlazas(){
		$this->db->select('*');
		$this->db->order_by('plaza', 'asc');
		$res=$this->db->get('usuarios_plazas');
		if($res->num_rows()>0){
			return $res->result_array();
		}
		return FALSE;
	}

	/**********MANTENEDOR*************/

	/**** COMUNAS ****/

		public function getComunasRcdcList(){
			$this->db->select(
				"sha1(r.id) as hash,
				r.*,
			");

			$this->db->order_by('r.titulo', 'desc');
			$res = $this->db->get('comunas_rcdc as r');
			return $res->result_array();
		}

		public function getDataComunasRcdc($hash){
			$this->db->select('
				sha1(r.id) as hash,
				r.*,
			');
			$this->db->where('sha1(r.id)', $hash);
			$res=$this->db->get('comunas_rcdc as r');
			return $res->result_array();
		}

		public function formActualizarComuna($id,$data){
			$this->db->where('sha1(id)', $id);
			if($this->db->update('comunas_rcdc', $data)){
				
				return TRUE;
			}
			return FALSE;
		}

		public function formIngresoComuna($data){
			if($this->db->insert('comunas_rcdc', $data)){
				return $this->db->insert_id();
			}
			return FALSE;
		} 
		
		public function eliminaComunasRcdc($hash){
			$this->db->where('sha1(id)', $hash);
			if($this ->db->delete('comunas_rcdc')){
				return TRUE;
			}
			return FALSE;
		}

	/***** TIPOS *****/

		public function getTiposRcdcList(){
			$this->db->select(
				"sha1(r.id) as hash,
				r.*,
			");

			$this->db->order_by('r.tipo', 'desc');
			$res = $this->db->get('rcdc_tipos as r');
			return $res->result_array();
		}

		public function getDataTiposRcdc($hash){
			$this->db->select('
				sha1(r.id) as hash,
				r.*,
			');
			$this->db->where('sha1(r.id)', $hash);
			$res=$this->db->get('rcdc_tipos as r');
			return $res->result_array();
		}

		public function formActualizarTipo($id,$data){
			$this->db->where('sha1(id)', $id);
			if($this->db->update('rcdc_tipos', $data)){
				
				return TRUE;
			}
			return FALSE;
		}

		public function formIngresoTipo($data){
			if($this->db->insert('rcdc_tipos', $data)){
				return $this->db->insert_id();
			}
			return FALSE;
		} 
		
		public function eliminaTiposRcdc($hash){
			$this->db->where('sha1(id)', $hash);
			if($this ->db->delete('rcdc_tipos')){
				return TRUE;
			}
			return FALSE;
		}

		/***** TIPOS *****/

		public function getMotivosRcdcList(){
			$this->db->select(
				"sha1(r.id) as hash,
				r.*,
				t.tipo as tipo,
			");
			$this->db->join('rcdc_tipos t','r.id_tipo = t.id','left');
			$this->db->order_by('r.id_tipo,r.motivo', 'desc');
			$res = $this->db->get('rcdc_motivos as r');
			return $res->result_array();
		}

		public function getDataMotivosRcdc($hash){
			$this->db->select('
				sha1(r.id) as hash,
				r.*,
			');
			$this->db->where('sha1(r.id)', $hash);
			$res=$this->db->get('rcdc_motivos as r');
			return $res->result_array();
		}

		public function formActualizarMotivo($id,$data){
			$this->db->where('sha1(id)', $id);
			if($this->db->update('rcdc_motivos', $data)){
				
				return TRUE;
			}
			return FALSE;
		}

		public function formIngresoMotivo($data){
			if($this->db->insert('rcdc_motivos', $data)){
				return $this->db->insert_id();
			}
			return FALSE;
		} 
		
		public function eliminaMotivosRcdc($hash){
			$this->db->where('sha1(id)', $hash);
			if($this ->db->delete('rcdc_motivos')){
				return TRUE;
			}
			return FALSE;
		}

}