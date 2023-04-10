<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Materialesmodel extends CI_Model {

	public function __construct(){
		parent::__construct();
	}
	
	public function insertarVisita($data){
		if($this->db->insert('aplicaciones_visitas', $data)){
			return TRUE;
		}
		return FALSE;
	}

	/*************MATERIALES*************/

		public function listaDetalle($desde,$hasta,$trabajador,$jefe){
			$this->db->select("sha1(m.id) as hash,
				m.*,
				CONCAT(SUBSTRING_INDEX(CONCAT(u.nombres, ' ', u.apellidos), ' ', 1), ' ', u.apellidos) as 'tecnico'
				");

			if($trabajador!=""){
				$this->db->where('m.id_tecnico', $trabajador);
			}
 
			$this->db->join('usuarios u', 'u.id = m.id_tecnico', 'left');
			$res=$this->db->get('materiales m');
			// echo $this->db->last_query();
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

		public function formProductividad($data){
			if($this->db->insert('productividad', $data)){
				return TRUE;
			}
			return FALSE;
		}

		public function formMateriales($data){
			if($this->db->insert('materiales', $data)){
				return TRUE;
			}
			return FALSE;
		}
		
		
		public function getIdTecnicoPorRut($rut){
			$this->db->where('rut', $rut);
			$res=$this->db->get('usuarios');
			if($res->num_rows()>0){
				$row=$res->row_array();
				return $row["id"];
			}
			
		}

		public function listaTrabajadoresMateriales(){
			$this->db->select("concat(substr(replace(rut,'-',''),1,char_length(replace(rut,'-',''))-1),'-',substr(replace(rut,'-',''),char_length(replace(rut,'-','')))) as 'rut_format',
				empresa,id,rut,
			    CONCAT(nombres,' ', apellidos) as 'nombre_completo',
				CONCAT(SUBSTRING_INDEX(nombres, ' ', 1), ' ', apellidos) as 'nombre_corto'
				");
			
			if($this->session->userdata('id_perfil')==4){
				$this->db->where('rut', $this->session->userdata('rut'));
			}
 
			$this->db->order_by('nombres', 'asc');
			$res=$this->db->get("usuarios");
			if($res->num_rows()>0){
				$array=array();
				foreach($res->result_array() as $key){
					$temp=array();
					$temp["id"]=$key["rut"];
					$temp["text"]=$key["rut_format"]."  |  ".$key["nombre_corto"];
					$array[]=$temp;
				}
				return json_encode($array);
			}
			return FALSE;
		}

		
		public function listaTecnico($desde,$hasta,$trabajador,$jefe){
			$this->db->select("sha1(m.id) as hash,
				m.*,
				COUNT(CASE WHEN tipo = 'RETIRO' THEN 1 END) as reversa,
				COUNT(CASE WHEN tipo = 'OPERATIVO' THEN 1 END) as directa,
				CONCAT(SUBSTRING_INDEX(CONCAT(u.nombres, ' ', u.apellidos), ' ', 1), ' ', u.apellidos) as 'tecnico',
			");

			if($trabajador!=""){
				$this->db->where('m.id_tecnico', $trabajador);
			}
			
			$this->db->join('usuarios u', 'u.id = m.id_tecnico', 'left');
			$this->db->group_by('m.id_tecnico');
			$this->db->order_by('u.nombres', 'asc');
			$res=$this->db->get('materiales m');

			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

		public function listaMaterial($desde,$hasta,$trabajador,$jefe){
			$this->db->select("sha1(m.id) as hash,
				m.*,
				COUNT(CASE WHEN tipo = 'RETIRO' THEN 1 END) as reversa,
				COUNT(CASE WHEN tipo = 'OPERATIVO' THEN 1 END) as disponible,
				COUNT(CASE WHEN tipo = 'ANALISIS' THEN 1 END) as analisis,
				COUNT(CASE WHEN tipo = 'TECNICO' THEN 1 END) as tecnico,
			");

			
			$this->db->group_by('m.material');
			$this->db->order_by('m.material', 'asc');
			$res=$this->db->get('materiales m');

			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

		public function listaSeriesDevolucion($desde,$hasta,$trabajador,$jefe){
			$this->db->select("sha1(m.id) as hash,
				m.*,
			");

			$this->db->where('tipo', "RETIRO");
			
			$this->db->group_by('m.serie');
			$this->db->order_by('m.material', 'asc');

			if($trabajador!=""){
				$this->db->where('m.id_tecnico', $trabajador);
			}

			$res=$this->db->get('materiales m');

			/* echo $this->db->last_query(); */
			
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

		public function listaSeriesOperativos($desde,$hasta,$trabajador,$jefe){
			$this->db->select("sha1(m.id) as hash,
				m.*,
			");

			$this->db->where('tipo', "OPERATIVO");
			
			$this->db->group_by('m.serie');
			$this->db->order_by('m.material', 'asc');

			if($trabajador!=""){
				$this->db->where('m.id_tecnico', $trabajador);
			}

			$res=$this->db->get('materiales m');
			
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}
		
		
	

}