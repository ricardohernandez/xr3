<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Astmodel extends CI_Model {

	public function __construct(){
		parent::__construct();
	}
	
	public function insertarVisita($data){
		if($this->db->insert('aplicaciones_visitas', $data)){
			return TRUE;
		}
		return FALSE;
	}

	/*************AST*************/

		public function listaAst($desde,$hasta,$tecnico){
			$this->db->select("sha1(o.id) as hash,
				o.id as id_astt,
				o.*,
				CONCAT(u.nombres,' ',u.apellidos) as 'tecnico',
			    CONCAT(us.nombres,' ',us.apellidos) as 'auditor',
			    aca.actividad as actividad,
			    c.proyecto as localidad,
			    e.estado as estado_ast,
		        if(o.fecha!='0000-00-00', DATE_FORMAT(o.fecha,'%d-%m-%Y'),'') as 'fecha',
   				o.ultima_actualizacion as ultima_actualizacion
   				");

			$this->db->join('usuarios u', 'u.id = o.tecnico_id', 'left');
			$this->db->join('usuarios us', 'us.id = o.auditor_id', 'left');
			$this->db->join('ast_actividades aca', 'aca.id = o.id_actividad', 'left');
			$this->db->join('usuarios_proyectos c', 'c.id = o.id_comuna', 'left');
			$this->db->join('ast_estados e', 'e.id = o.id_estado', 'left');

			if($desde!="" and $hasta!=""){
				$this->db->where("o.fecha BETWEEN '".$desde."' AND '".$hasta."'");	
			}

			if($tecnico!=""){
				$this->db->where("o.tecnico_id" , $tecnico);	
			}

			$res=$this->db->get('ast_checklist o');
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

		public function getDataAst($hash){
			$this->db->select("sha1(o.id) as hash,
				o.id_estado as estado,
				o.id as id_astt,
				o.*,
				cd.*,
				CONCAT(u.nombres,' ',u.apellidos) as 'tecnico',
			    CONCAT(us.nombres,' ',us.apellidos) as 'auditor',
			    aca.actividad as actividad,
			    c.proyecto as localidad,
			    e.estado as estado_ast,
		        if(o.fecha!='0000-00-00', DATE_FORMAT(o.fecha,'%Y-%m-%d'),'') as 'fecha',
		        if(o.hora!='0000:00:00', left(o.hora,5),'') as 'hora',

   				o.ultima_actualizacion as ultima_actualizacion,
		        cl.descripcion as descripcion,
		        cl.tipo as id_tipo,
				ct.tipo as tipo,

				CASE 
		          WHEN cd.estado='si' THEN 'si'
		          WHEN cd.estado='no' THEN 'no'
		          WHEN cd.estado='no_ap' THEN 'no_ap'
		        END AS estado_str,

				cd.observacion as observacion

				");
			$this->db->join('usuarios u', 'u.id = o.tecnico_id', 'left');
			$this->db->join('usuarios us', 'us.id = o.auditor_id', 'left');
			$this->db->join('ast_actividades aca', 'aca.id = o.id_actividad', 'left');
			$this->db->join('usuarios_proyectos c', 'c.id = o.id_comuna', 'left');
			$this->db->join('ast_estados e', 'e.id = o.id_estado', 'left');
			$this->db->join('ast_checklist_detalle cd', 'cd.id_ast = o.id', 'left');
			$this->db->join('ast_checklist_listado cl', 'cl.id = cd.id_check', 'left');
			$this->db->join('ast_checklist_tipos ct', 'ct.id = cl.tipo', 'left');

			$this->db->where('sha1(o.id)', $hash);
			$res=$this->db->get('ast_checklist o');
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

		public function getDataAstCabecera($hash){
			$this->db->select("sha1(o.id) as hash,
				o.*,
				CONCAT(u.nombres,' ',u.apellidos) as 'tecnico',
			    CONCAT(us.nombres,' ',us.apellidos) as 'auditor',
			    u.comuna as comuna,
			    u.rut as rut_tecnico,
			    u.codigo as codigo,
				u.ultima_actualizacion as ultima_actualizacion,
				u.correo_empresa as correo_tecnico_empresa,
				u.correo_personal as correo_tecnico_personal,

				us.correo_empresa as correo_auditor_empresa,
				us.correo_personal as correo_auditor_personal,

				usj.correo_empresa as correo_jefe_empresa,
				usj.correo_personal as correo_jefe_personal,

				uc.cargo as auditor_cargo,
				ua.area as area,
		        if(o.fecha!='0000-00-00', DATE_FORMAT(o.fecha,'%d-%m-%Y'),'') as 'fecha'
				");
			$this->db->join('usuarios u', 'u.id = o.tecnico_id', 'left');
			$this->db->join('usuarios us', 'us.id = o.auditor_id', 'left');

			$this->db->join('usuarios_jefes uj', 'uj.id = u.id_jefe', 'left');
			$this->db->join('usuarios usj', 'usj.id = uj.id_jefe', 'left');

			$this->db->join('usuarios_areas ua', 'ua.id = u.id_area', 'left');
			$this->db->join('usuarios_cargos uc', 'uc.id = us.id_cargo', 'left');
			$this->db->where('sha1(o.id)', $hash);
			$res=$this->db->get('ast_checklist o');
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}


		

		public function listaAstDetalle($desde,$hasta,$tecnico){
			$this->db->select("sha1(o.id) as hash,
				o.*,			
				u.rut as rut,
				u.comuna as comuna,
				ua.area as area,
				up.proyecto as proyecto,
				u.codigo as codigo,
				uc.cargo as auditor_cargo,
				CONCAT(u.nombres,' ',u.apellidos) as 'tecnico',
				CONCAT(us.nombres,' ',us.apellidos) as 'auditor',
				if(o.fecha!='0000-00-00', DATE_FORMAT(o.fecha,'%d-%m-%Y'),'') as 'fecha',
				cl.descripcion as descripcion,
				ct.tipo as tipo,
				ct.id as id_tipo,

				CASE 
		          WHEN cd.estado='si' THEN 'si'
		          WHEN cd.estado='no' THEN 'no'
		          WHEN cd.estado='no_ap' THEN 'no_ap'
		        END AS estado_str,

				cd.observacion as observacion

				");

			$this->db->join('usuarios as u', 'u.id = o.tecnico_id', 'left');
			$this->db->join('usuarios as us', 'us.id = o.auditor_id', 'left');
			$this->db->join('usuarios_areas ua', 'ua.id = u.id_area', 'left');
			$this->db->join('usuarios_proyectos up', 'up.id = u.id_proyecto', 'left');
			$this->db->join('usuarios_cargos uc', 'uc.id = us.id_cargo', 'left');
			$this->db->join('ast_checklist_detalle cd', 'cd.id_ast = o.id', 'left');
			$this->db->join('ast_checklist_listado cl', 'cl.id = cd.id_check', 'left');
			$this->db->join('ast_checklist_tipos ct', 'ct.id = cl.tipo', 'left');

			if($desde!="" and $hasta!=""){
				$this->db->where("o.fecha BETWEEN '".$desde."' AND '".$hasta."'");	
			}

			if($tecnico!=""){
				$this->db->where("o.tecnico_id",$tecnico);	
			}

			$res=$this->db->get('ast_checklist o');
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


		public function formAst($data){
			if($this->db->insert('ast_checklist', $data)){
				return $this->db->insert_id();
			}
			return FALSE;
		}

		public function actualizarAst($hash,$data){
			$this->db->where('sha1(id)', $hash);
			if($this->db->update('ast_checklist', $data)){
				return TRUE;
			}
			return FALSE;
		}


		public function listaTecnicos(){
			$this->db->select("id,CONCAT(nombres,' ',apellidos) as 'nombre_completo'");
			/*$this->db->where('id_perfil', 4);*/
			$this->db->where('estado', 1);
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

		public function listaComunas(){
			$this->db->order_by('proyecto', 'asc');
			$res = $this->db->get('usuarios_proyectos');
			return $res->result_array();
		}

		public function listaEstados(){
			$this->db->where('id', 3);
			$this->db->order_by('estado', 'asc');
			$res = $this->db->get('ast_estados');
			return $res->result_array();
		}

		public function listaTipos(){
			$this->db->order_by('tipo', 'asc');
			$res = $this->db->get('ast_checklist_tipos');
			return $res->result_array();
		}

		public function listaActividades(){
			$this->db->order_by('actividad', 'asc');
			$res = $this->db->get('ast_actividades');
			return $res->result_array();
		}

		
		public function getIdTipo($tipo){
			$this->db->where('tipo', $tipo);
			$res=$this->db->get("ast_checklist_tipos");
			$row=$res->row_array();
			return $row["id"];
		}

		public function getTipoItem($item){
			$this->db->where('id', $item);
			$res=$this->db->get("ast_checklist_listado");
			$row=$res->row_array();
			return $row["tipo"];
		}

		public function getItemPorIdDetalle($item){
			$this->db->select('id_check');
			$this->db->where('id', $item);
			$res=$this->db->get("ast_checklist_detalle");
			/*echo $this->db->last_query();*/
			$row=$res->row_array();
			return $row["id_check"];
		}

		public function getFallosChecklist($hash){
			$this->db->where('sha1(acd.id_ast)', $hash);
			$this->db->where('acd.estado', "no");
			$this->db->where('(acl.tipo=2 or acl.tipo=3)');
			$this->db->join('ast_checklist_listado acl', 'acl.id = acd.id_check', 'left');
			$res = $this->db->get('ast_checklist_detalle acd');
			if($res->num_rows()>0){
				return TRUE;
			}
			return FALSE;
		}

		public function getIdPorHash($hash){
			$this->db->where('sha1(id)', $hash);
			$res=$this->db->get("ast_checklist");
			if($res->num_rows()>0){
				$row=$res->row_array();
				return $row["id"];
			}
			return FALSE;
		}


		public function insertarItemAst($data){
			if($this->db->insert('ast_checklist_listado', $data)){
				return $this->db->insert_id();
			}
			return FALSE;
		}

		
		public function insertaDetalleAst($data){
			if($this->db->insert('ast_checklist_detalle', $data)){
				return TRUE;
			}
			return FALSE;
		}


		public function listaChecklistAst($actividad){
			$this->db->select('sha1(c.id) as hash,
				c.*,
				c.tipo as id_tipo,
				ct.tipo as tipo');
			$this->db->join('ast_checklist_tipos ct', 'ct.id = c.tipo', 'left');
			$this->db->where('c.id_actividad', $actividad);
			$this->db->order_by('c.id', 'asc');
			$res=$this->db->get('ast_checklist_listado c');
			return $res->result_array();
		}

		public function getUserChecklist($hash){
			$this->db->select('sha1(a.id) as hash,
				a.*,
				ac.firmado as firmado,
				cl.tipo as id_tipo,
				cl.descripcion as descripcion,
				ct.tipo as tipo');

			$this->db->join('ast_checklist_listado cl', 'cl.id = a.id_check', 'left');
			$this->db->join('ast_checklist_tipos ct', 'ct.id = cl.tipo', 'left');
			$this->db->join('ast_checklist ac', 'ac.id = a.id_ast', 'left');
			$this->db->where('sha1(a.id_ast)', $hash);
			$this->db->order_by('a.id', 'asc');
			$res=$this->db->get('ast_checklist_detalle a');
			return $res->result_array();
		}

		public function existeDetalleAst($hash){
			$this->db->where('sha1(id_ast)', $hash);
			$res=$this->db->get('ast_checklist_detalle');
			if($res->num_rows()>0){
				return TRUE;
			}
			return FALSE;
		}

		public function getIddetalle($id_ast,$id_check){
			$this->db->where('(id_ast)', $id_ast);
			$this->db->where('(id_check)', $id_check);
			$res=$this->db->get("ast_checklist_detalle");
			if($res->num_rows()>0){
				$row=$res->row_array();
				return $row["id"];
			}
			return FALSE;
		}


		public function actualizaDetalleAst($id,$data){
			$this->db->where('(id)', $id);
			if($this->db->update('ast_checklist_detalle', $data)){
				// echo $this->db->last_query();;
				// echo "<br>";
				return TRUE;
			}
			return FALSE;
		}

		
		public function eliminaAst($hash){
			$this->db->where('sha1(id)', $hash);
		    if($this ->db->delete('ast_checklist')){
		    	
		    	$this->db->where('sha1(id_ast)', $hash);
			    if($this ->db->delete('ast_checklist_detalle')){
			    	return TRUE;
			    }

		    }
		    return FALSE;
		}

		public function getProyectoChecklist($hash){
			$this->db->select('u.id_proyecto as id_proyecto');		
			$this->db->where('sha1(c.id)', $hash);
			$this->db->join('usuarios u', 'u.id = c.tecnico_id', 'left');
			$res = $this->db->get('ast_checklist c');			
			$row = $res->row_array();
			return $row["id_proyecto"];
		}

		public function getDataItemChecklist($id_checklist,$item){
			$this->db->select("sha1(cd.id) as hash,
				o.*,			
				u.rut as rut,
				CONCAT(u.nombres, ' ', u.apellidos) as 'tecnico',
			    CONCAT(SUBSTRING_INDEX(us.nombres, ' ', '1'),'  ',SUBSTRING_INDEX(SUBSTRING_INDEX(us.apellidos, ' ', '-2'), ' ', '1')) as 'auditor',

				if(o.fecha!='0000-00-00', DATE_FORMAT(o.fecha,'%d-%m-%Y'),'') as 'fecha',
				cl.id as id_item,
				cl.descripcion as descripcion,
				ct.tipo as tipo,
				CASE 
		          WHEN cd.estado='si' THEN 'ok'
		          WHEN cd.estado='no' THEN 'nook'
		          WHEN cd.estado='no_ap' THEN 'No aplica'
		        END AS estado,
				cd.observacion as observacion,

		        CASE 
		          WHEN cd.solucion_estado=0 THEN 'Pendiente'
		          WHEN cd.solucion_estado=1 THEN 'Finalizado'
		        END AS solucion_estado,

		        u.correo_empresa as correo_tecnico_empresa,
				u.correo_personal as correo_tecnico_personal,
				us.correo_empresa as correo_auditor_empresa,
				us.correo_personal as correo_auditor_personal,
				usj.correo_empresa as correo_jefe_empresa,
				usj.correo_personal as correo_jefe_personal,

				if(cd.solucion_fecha!='0000-00-00', DATE_FORMAT(cd.solucion_fecha,'%d-%m-%Y'),'') as 'solucion_fecha',
				cd.solucion_observacion as solucion_observacion,
				cd.ultima_actualizacion as ultima_actualizacion,

				");
			$this->db->join('ast_checklist o', 'o.id=cd.id_ast', 'left');

			$this->db->join('usuarios u', 'u.id = o.tecnico_id', 'left');
			$this->db->join('usuarios us', 'us.id = o.auditor_id', 'left');

			$this->db->join('usuarios_jefes uj', 'uj.id = u.id_jefe', 'left');
			$this->db->join('usuarios usj', 'usj.id = uj.id_jefe', 'left');

			$this->db->join('ast_checklist_listado cl', 'cl.id = cd.id_check', 'left');
			$this->db->join('ast_checklist_tipos ct', 'ct.id = cl.tipo', 'left');
			$this->db->where('cd.id_check' , $item);
			$this->db->where('sha1(cd.id_ast)' , $id_checklist);
			$res=$this->db->get('ast_checklist_detalle cd');
			return $res->result_array();
		}

	/*************MANTENEDOR ACTIVIDADES*************/

		public function listaMantActividades(){
			$this->db->select("sha1(cl.id) as hash,
				cl.*,
			    aca.actividad as actividad,
			    act.tipo as tipo
   				");

			$this->db->join('ast_actividades aca', 'aca.id = cl.id_actividad', 'left');
			$this->db->join('ast_checklist_tipos act', 'act.id = cl.tipo', 'left');
			$this->db->order_by('cl.id', 'asc');
			$this->db->where('cl.tipo', 1);
			$res=$this->db->get('ast_checklist_listado cl');
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

		public function getDataMantActividades($hash){
			$this->db->select("sha1(cl.id) as hash,
				cl.*,
			    aca.actividad as actividad,
			    cl.tipo as id_tipo
   				");

			$this->db->join('ast_actividades aca', 'aca.id = cl.id_actividad', 'left');
			$this->db->join('ast_checklist_tipos act', 'act.id = cl.tipo', 'left');
			$this->db->where('sha1(cl.id)', $hash);
			$res=$this->db->get('ast_checklist_listado cl');
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

		public function formMantActividades($data){
			if($this->db->insert('ast_checklist_listado', $data)){
				return $this->db->insert_id();
			}
			return FALSE;
		}

		public function actualizarMantActividades($hash,$data){
			$this->db->where('sha1(id)', $hash);
			if($this->db->update('ast_checklist_listado', $data)){
				return TRUE;
			}
			return FALSE;
		}

		public function eliminaMantActividades($hash){
			/*$this->db->where('sha1(id)', $hash);
		    if($this ->db->delete('ast_checklist')){
		    	
		    	$this->db->where('sha1(id_ast)', $hash);
			    if($this ->db->delete('ast_checklist_detalle')){
			    	return TRUE;
			    }

		    }
		    return FALSE;*/
		}

		public function dataMant(){
			$this->db->order_by('id', 'asc');
			$res = $this->db->get('ast_checklist_listado');
			return $res->result_array();						
		}

		public function insertarMant($data){
			if($this->db->insert('ast_checklist_listado', $data)){
				return TRUE;
			}
			return FALSE;
		}

		public function getListaTipos(){
		   /*	$this->db->where('id<>1');
			$this->db->where('id<>4');*/
			$this->db->order_by('id', 'asc');
			$res = $this->db->get('ast_checklist_tipos');
			return $res->result_array();
		}

		public function getDataMantAct(){
			$this->db->order_by('id', 'asc');
			$res=$this->db->get('ast_checklist_listado');
			return $res->result_array();
		}

}