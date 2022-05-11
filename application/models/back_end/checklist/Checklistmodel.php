<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Checklistmodel extends CI_Model {

	public function __construct(){
		parent::__construct();
	}
	
	public function insertarVisita($data){
		if($this->db->insert('aplicaciones_visitas', $data)){
			return TRUE;
		}
		return FALSE;
	}

	/*************OTS*************/

		public function listaOTS($desde,$hasta){

			$this->db->select("sha1(o.id) as hash,
				o.*,
				CONCAT(u.nombres,' ',u.apellidos) as 'tecnico',
			    CONCAT(us.nombres,' ',us.apellidos) as 'auditor',
			    u.comuna as comuna,
			    u.codigo as codigo,
				o.ultima_actualizacion as ultima_actualizacion,
				uc.cargo as cargo,
				ua.area as area,
		        if(o.fecha!='0000-00-00', DATE_FORMAT(o.fecha,'%d-%m-%Y'),'') as 'fecha'
				");

			$this->db->join('usuarios u', 'u.id = o.tecnico_id', 'left');
			$this->db->join('usuarios us', 'us.id = o.auditor_id', 'left');
			$this->db->join('usuarios_areas ua', 'ua.id = u.id_area', 'left');
			$this->db->join('usuarios_cargos uc', 'uc.id = us.id_cargo', 'left');

			if($desde!="" and $hasta!=""){
				$this->db->where("o.fecha BETWEEN '".$desde."' AND '".$hasta."'");	
			}

			$res=$this->db->get('checklist_ots o');
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}


		public function getDataOTS($hash){
			$this->db->select("sha1(o.id) as hash,
				o.*,
				cd.*,
				CONCAT(u.nombres,' ',u.apellidos) as 'tecnico',
			    CONCAT(us.nombres,' ',us.apellidos) as 'auditor',
			    u.comuna as comuna,
			    u.codigo as codigo,
				u.ultima_actualizacion as ultima_actualizacion,
				uc.cargo as auditor_cargo,
				ua.area as area,
		        if(o.fecha!='0000-00-00', DATE_FORMAT(o.fecha,'%d-%m-%Y'),'') as 'fecha'
				");
			$this->db->join('usuarios u', 'u.id = o.tecnico_id', 'left');
			$this->db->join('usuarios us', 'us.id = o.auditor_id', 'left');
			$this->db->join('usuarios_areas ua', 'ua.id = u.id_area', 'left');
			$this->db->join('usuarios_cargos uc', 'uc.id = us.id_cargo', 'left');
			$this->db->join('checklist_detalle cd', 'cd.id_ots = o.id', 'left');

			$this->db->where('sha1(o.id)', $hash);
			$res=$this->db->get('checklist_ots o');
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

		public function listaOTSDetalle($desde,$hasta){
			$this->db->select("sha1(o.id) as hash,
				o.*,			
				u.rut as rut,
				u.comuna as comuna,
				ua.area as area,
				u.codigo as codigo,
				uc.cargo as auditor_cargo,
				CONCAT(u.nombres,' ',u.apellidos) as 'tecnico',
				CONCAT(us.nombres,' ',us.apellidos) as 'auditor',
				if(o.fecha!='0000-00-00', DATE_FORMAT(o.fecha,'%d-%m-%Y'),'') as 'fecha',

				cl.descripcion as descripcion,
				ct.tipo as tipo,

				CASE 
		          WHEN cd.estado=0 THEN 'ok'
		          WHEN cd.estado=1 THEN 'nook'
		        END AS estado,

				cd.observacion as observacion

				");

			$this->db->join('usuarios as u', 'u.id = o.tecnico_id', 'left');
			$this->db->join('usuarios as us', 'us.id = o.auditor_id', 'left');
			$this->db->join('usuarios_areas ua', 'ua.id = u.id_area', 'left');
			$this->db->join('usuarios_cargos uc', 'uc.id = us.id_cargo', 'left');
			$this->db->join('checklist_detalle cd', 'cd.id_ots = o.id', 'left');
			$this->db->join('checklist_listado cl', 'cl.id = cd.id_check', 'left');
			$this->db->join('checklist_tipos ct', 'ct.id = cl.tipo', 'left');

			if($desde!="" and $hasta!=""){
				$this->db->where("o.fecha BETWEEN '".$desde."' AND '".$hasta."'");	
			}

			$res=$this->db->get('checklist_ots o');
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}


		public function datosUsuario($id){
			$this->db->select("sha1(u.id) as hash_usuario,
				u.*,
				up.perfil as perfil,
				upr.proyecto as proyecto,
				uj.id_jefe as jefe,
				uc.cargo as cargo,
				ua.area as area,
				if(u.fecha_nacimiento!='1970-01-01' and u.fecha_nacimiento!='0000-00-00',u.fecha_nacimiento,'') as 'fecha_nacimiento',
				if(u.fecha_ingreso!='1970-01-01' and u.fecha_ingreso!='0000-00-00',u.fecha_ingreso,'') as 'fecha_ingreso',
				if(u.fecha_salida!='1970-01-01' and u.fecha_salida!='0000-00-00',u.fecha_salida,'') as 'fecha_salida',
				CASE 
		          WHEN u.estado=1 THEN 'Activo'
		          WHEN u.estado=0 THEN 'No Activo'
		          ELSE ''
		        END AS estado_str,			
				");
			
			$this->db->join('usuarios_perfiles as up', 'up.id = u.id_perfil', 'left');
			$this->db->join('usuarios_proyectos upr', 'upr.id = u.id_proyecto', 'left');
			$this->db->join('usuarios_jefes uj', 'uj.id = u.id_jefe', 'left');
			$this->db->join('usuarios_cargos uc', 'uc.id = u.id_cargo', 'left');
			$this->db->join('usuarios_areas ua', 'ua.id = u.id_area', 'left');
			$this->db->where('u.id', $id);
			$res=$this->db->get('usuarios u');
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}


		public function formOTS($data){
			if($this->db->insert('checklist_ots', $data)){
				return $this->db->insert_id();
			}
			return FALSE;
		}

		public function actualizarOTS($hash,$data){
			$this->db->where('sha1(id)', $hash);
			if($this->db->update('checklist_ots', $data)){
				return TRUE;
			}
			return FALSE;
		}

		
		public function eliminaPreventa($hash){
			$this->db->where('sha1(id)', $hash);
		    if($this ->db->delete('checklist_ots')){
		    	return TRUE;
		    }
		    return FALSE;
		}


		public function listaComunas(){
			$this->db->order_by('titulo', 'asc');
			$res = $this->db->get('comunas');
			return $res->result_array();
		}

		public function listaTecnicos(){
			$this->db->select("id,CONCAT(nombres,' ',apellidos) as 'nombre_completo'");
			$this->db->where('id_perfil', 4);
			$this->db->order_by('nombres', 'asc');
			$res=$this->db->get("usuarios");
			return $res->result_array();
		}

		public function listaAuditores(){
			$this->db->select("id,CONCAT(nombres,' ',apellidos) as 'nombre_completo'");
			$this->db->where('id_perfil', 3);
			$this->db->order_by('nombres', 'asc');
			$res=$this->db->get("usuarios");
			return $res->result_array();
		}

		public function getIdTipo($tipo){
			$this->db->where('tipo', $tipo);
			$res=$this->db->get("checklist_tipos");
			$row=$res->row_array();
			return $row["id"];
		}

		public function getIdPorHash($hash){
			$this->db->where('sha1(id)', $hash);
			$res=$this->db->get("checklist_ots");
			if($res->num_rows()>0){
				$row=$res->row_array();
				return $row["id"];
			}
			return FALSE;
			
		}
		


		public function insertarItemChecklist($data){
			if($this->db->insert('checklist_listado', $data)){
				return $this->db->insert_id();
			}
			return FALSE;
		}

		public function insertarTecnico($data){
			if($this->db->insert('usuarios', $data)){
				return TRUE;
			}
			return FALSE;
		}
		
		public function insertaDetalleOTS($data){
			if($this->db->insert('checklist_detalle', $data)){
				return TRUE;
			}
			return FALSE;
		}

		
		public function listaChecklist(){
			$this->db->select('sha1(c.id) as hash,
				c.*,
				ct.tipo as tipo');

			$this->db->join('checklist_tipos ct', 'ct.id = c.tipo', 'left');
			$res=$this->db->get('checklist_listado c');
			return $res->result_array();
		}

		public function existeDetalleOTS($id){
			$this->db->where('id_ots', $id);
			$res=$this->db->get('checklist_detalle');
			if($res->num_rows()>0){
				return TRUE;
			}
			return FALSE;
		}

		public function getIddetalle($id_ots,$id_check){
			$this->db->where('(id_ots)', $id_ots);
			$this->db->where('(id_check)', $id_check);
			$res=$this->db->get("checklist_detalle");
			if($res->num_rows()>0){
				$row=$res->row_array();
				return $row["id"];
			}
			return FALSE;
		}


		public function actualizaDetalleOTS($id,$data){
			$this->db->where('(id)', $id);
			if($this->db->update('checklist_detalle', $data)){
				// echo $this->db->last_query();;
				// echo "<br>";
				return TRUE;
			}
			return FALSE;
		}

		
		public function eliminaOTS($hash){
			$this->db->where('sha1(id)', $hash);
		    if($this ->db->delete('checklist_ots')){
		    	
		    	$this->db->where('sha1(id_ots)', $hash);
			    if($this ->db->delete('checklist_detalle')){
			    	return TRUE;
			    }

		    }
		    return FALSE;
		}

	/*************REPORTE*********/

		public function dataEstadosChecklist(){
			$this->db->select("
				CASE 
		          WHEN cd.estado=0 THEN 'OK'
		          WHEN cd.estado=1 THEN 'No Ok'
		        END AS estado,
				count(cd.id) as cantidad,
				");

			$this->db->group_by('cd.estado');
			$res=$this->db->get('checklist_detalle cd');
			$cabeceras = array("Tipo","Cantidad");
			$array=array();
			$array[]=$cabeceras;
			$contador=0;

			foreach($res->result_array() as $key){
				$temp=array();
				$temp[] = (string)$key["estado"];
				$temp[] = (int)$key["cantidad"];
				$array[]=$temp;
			}
			return $array;

		}

		public function dataTecnicos(){

			$this->db->select("
				CONCAT(u.nombres,' ',u.apellidos) as 'tecnico',

				SUM(CASE 
	             WHEN cd.estado = 0 THEN 1
	             ELSE 0
	            END) AS cantidad_ok,

	            SUM(CASE 
	             WHEN cd.estado = 1 THEN 1
	             ELSE 0
	            END) AS cantidad_nook
			
				");

			$this->db->group_by('c.tecnico_id');
			$this->db->join('checklist_ots c', 'c.id = cd.id_ots', 'left');	
			$this->db->join('usuarios u', 'u.id = c.tecnico_id', 'left');
			$res=$this->db->get('checklist_detalle cd');

			$cabeceras = array("Técnico","OK",array('role'=> 'annotation'),"No OK",array('role'=> 'annotation'));
			$array=array();
			$array[]=$cabeceras;
			$contador=0;

			foreach($res->result_array() as $key){
				$temp=array();
				$temp[] = (string)$key["tecnico"];
				$temp[] = (int)$key["cantidad_ok"];
				$temp[] = (int)$key["cantidad_ok"];
				$temp[] = (int)$key["cantidad_nook"];
				$temp[] = (int)$key["cantidad_nook"];
				$array[]=$temp;
			}
			return $array;

		}
		
	
	



}