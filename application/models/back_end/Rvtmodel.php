<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rvtmodel extends CI_Model {

		public function __construct(){
		parent::__construct();
	}

	public function insertarVisita($data){
	  if($this->db->insert('aplicaciones_visitas', $data)){
			return TRUE;
		}
		return FALSE;
	}
	
	/**********ROP*************/

		public function getRvtList($desde,$hasta,$estado,$usuario = null){
			$this->db->select("r.*,
				sha1(r.id) as hash,
				r.id as id_rvt,
				CONCAT(SUBSTRING_INDEX(us.nombres, ' ', '1'),'  ',SUBSTRING_INDEX(SUBSTRING_INDEX(us.apellidos, ' ', '-2'), ' ', '1')) as 'nombre_solicitante',
				us.correo_empresa as 'correo_solicitante',
				CONCAT(SUBSTRING_INDEX(ur1.nombres, ' ', '1'),'  ',SUBSTRING_INDEX(SUBSTRING_INDEX(ur1.apellidos, ' ', '-2'), ' ', '1')) as 'nombre_responsable1',
				ur1.correo_empresa as 'correo__responsable1',
				CONCAT(SUBSTRING_INDEX(ur2.nombres, ' ', '1'),'  ',SUBSTRING_INDEX(SUBSTRING_INDEX(ur2.apellidos, ' ', '-2'), ' ', '1')) as 'nombre_responsable2',
				ur2.correo_empresa as 'correo__responsable2',
				p.proyecto as marca,

			");
			if($estado != ""){
				$this->db->where('r.estado', $estado);
			}
			if($usuario != null){
				$this->db->where('us.id', $usuario);
			}
			if($desde!="" and $hasta!=""){
				$this->db->where("r.fecha_ingreso BETWEEN '".$desde."' AND '".$hasta."'");
			}

			$this->db->join("usuarios us", 'us.id = r.id_solicitante', 'left' );
			$this->db->join("usuarios ur1", 'ur1.id = r.id_responsable1', 'left');
			$this->db->join("usuarios ur2", 'ur2.id = r.id_responsable2', 'left');
			$this->db->join("usuarios_proyectos p", 'p.id = r.id_marca', 'left');

			$this->db->order_by('r.fecha_ingreso', 'desc');
			$res = $this->db->get('rvt as r');
			return $res->result_array();
		}
		
		public function esJefe($id){
			$this->db->where('id_jefe', $id);
			$res = $this->db->get('usuarios_jefes');

			if($res->num_rows()>0){
				return TRUE;
			}

			return FALSE;
			
		}

		public function getDataRvt($hash){
			$this->db->select("r.*,
				sha1(r.id) as hash,
				r.id as id_rvt,
				CONCAT(SUBSTRING_INDEX(us.nombres, ' ', '1'),'  ',SUBSTRING_INDEX(SUBSTRING_INDEX(us.apellidos, ' ', '-2'), ' ', '1')) as 'nombre_solicitante',
				CONCAT(SUBSTRING_INDEX(ur1.nombres, ' ', '1'),'  ',SUBSTRING_INDEX(SUBSTRING_INDEX(ur1.apellidos, ' ', '-2'), ' ', '1')) as 'nombre_responsable1',
				CONCAT(SUBSTRING_INDEX(ur2.nombres, ' ', '1'),'  ',SUBSTRING_INDEX(SUBSTRING_INDEX(ur2.apellidos, ' ', '-2'), ' ', '1')) as 'nombre_responsable2',
				p.proyecto as marca,
			");

			$this->db->join("usuarios us", 'us.id = r.id_solicitante', 'left' );
			$this->db->join("usuarios ur1", 'ur1.id = r.id_responsable1', 'left');
			$this->db->join("usuarios ur2", 'ur2.id = r.id_responsable2', 'left');
			$this->db->join("usuarios_proyectos p", 'p.id = r.id_marca', 'left');

			$this->db->where('sha1(r.id)', $hash);
			$res = $this->db->get('rvt as r');
			return $res->result_array();
		}
		
		public function getRvtListVencidas(){
			$this->db->select('r.*,
			sha1(r.id) as hash,
			r.id as id_rvt,
			CONCAT(LEFT(us.nombres, 1), ". ", SUBSTRING_INDEX(us.apellidos, " ", 1)) AS usuario_asignado,
			CONCAT(LEFT(us2.nombres, 1), ". ", SUBSTRING_INDEX(us2.apellidos, " ", 1)) AS validador_real,
			CONCAT(LEFT(us3.nombres, 1), ". ", SUBSTRING_INDEX(us3.apellidos, " ", 1)) AS solicitante,
			CONCAT(LEFT(usj.nombres, 1), ". ", SUBSTRING_INDEX(usj.apellidos, " ", 1)) AS jefe_solicitante,
			IF(STR_TO_DATE(r.fecha_ingreso, "%Y-%m-%d") IS NOT NULL, DATE_FORMAT(r.fecha_ingreso,"%d-%m-%Y"),"") as fecha_ingreso,
			CASE req_mant.requiere_validacion
				WHEN 0 THEN "No aplica"
				WHEN 1 THEN IF(STR_TO_DATE(r.fecha_validacion, "%Y-%m-%d") IS NOT NULL, DATE_FORMAT(r.fecha_validacion, "%d-%m-%Y"), "")
			END AS fecha_validacion,
			IF(STR_TO_DATE(r.fecha_fin, "%Y-%m-%d") IS NOT NULL, DATE_FORMAT(r.fecha_fin,"%d-%m-%Y"),"") as fecha_fin,
			IFNULL(TIMESTAMPDIFF(HOUR, TIMESTAMP(r.fecha_ingreso, r.hora_ingreso), NOW()), 0) as horas_pendientes,
			CASE
				WHEN STR_TO_DATE(r.hora_ingreso, "%H:%i") IS NULL OR r.hora_ingreso = "" THEN NULL
				ELSE LEFT(r.hora_ingreso, 5)
			END AS hora_ingreso,
			CASE WHEN TIMESTAMPDIFF(HOUR, TIMESTAMP(r.fecha_ingreso, r.hora_ingreso), NOW()) > req_mant.horas_estimadas AND r.id_estado IN (0, 1, 4) THEN "vencido" ELSE "vigente" END AS vigencia,
			CASE req_mant.requiere_validacion
				WHEN 0 THEN "No aplica"
				WHEN 1 THEN 
					CASE
						WHEN STR_TO_DATE(r.hora_validacion, "%H:%i") IS NULL OR r.hora_validacion = "" THEN NULL
						ELSE LEFT(r.hora_validacion, 5)
					END
			END AS hora_validacion,
			CASE
				WHEN STR_TO_DATE(r.hora_fin, "%H:%i") IS NULL OR r.hora_fin = "" THEN NULL
				ELSE LEFT(r.hora_fin, 5)
			END AS hora_fin,
			req_mant.*,
			CASE req_mant.requiere_validacion
				WHEN 0 THEN "No aplica"
				WHEN 1 THEN CONCAT(LEFT(usj.nombres, 1), ". ", SUBSTRING_INDEX(usj.apellidos, " ", 1))
			END AS validador_sistema,
			CONCAT(LEFT(usr.nombres, 1), ". ", SUBSTRING_INDEX(usr.apellidos, " ", 1)) AS responsable1,
			CONCAT(LEFT(usr2.nombres, 1), ". ", SUBSTRING_INDEX(usr2.apellidos, " ", 1)) AS responsable2,
			CONCAT(CASE r.id_estado
				WHEN  0 THEN "Pendiente"
				WHEN  1 THEN "Asignado"
				WHEN  2 THEN "Finalizado"
				WHEN  3 THEN "Cancelado"
				ELSE "Pendiente"
			END) AS estado,

			us3.correo_empresa as correo_solicitante,
			usr.correo_empresa as correo_responsable1,
			usr2.correo_empresa as correo_responsable2,
			usj.correo_empresa as correo_jefe,

			r.id_estado as id_estado,
			r.ultima_actualizacion as ultima_actualizacion,
			req_mant_t.tipo as tipo,
			');
			
			$this->db->join('usuarios as us', 'us.id = r.id_usuario_asignado', 'left');
			$this->db->join('usuarios as us2', 'us2.id = r.id_validador_real', 'left');
			$this->db->join('usuarios as us3', 'us3.id = r.id_solicitante', 'left');

			$this->db->join('usuarios_jefes uj', 'uj.id = us3.id_jefe', 'left');
			$this->db->join('usuarios usj', 'usj.id = uj.id_jefe', 'left');

			$this->db->join('rop_mantenedor_requerimientos req_mant', 'req_mant.id = r.id_requerimiento', 'left');
			$this->db->join('rop_tipos req_mant_t', 'req_mant_t.id = req_mant.id_tipo', 'left');
		
			$this->db->join('usuarios as usr', 'usr.id = req_mant.id_responsable1', 'left');
			$this->db->join('usuarios as usr2', 'usr2.id = req_mant.id_responsable2', 'left');

			$this->db->where("(CASE WHEN TIMESTAMPDIFF(HOUR, TIMESTAMP(r.fecha_ingreso, r.hora_ingreso), NOW()) > req_mant.horas_estimadas THEN 'vencido' ELSE 'vigente' END = 'vencido' AND (r.id_estado = 0 OR r.id_estado = 1 OR r.id_estado = 4))");
			$this->db->order_by("horas_pendientes", 'DESC');
			$res = $this->db->get('rvt as r');

			if($res->num_rows()>0){
				return $res->result_array();
			}

			return FALSE;
		}

		public function formActualizar($id,$data){
			$this->db->where('sha1(id)', $id);
			if($this->db->update('rvt', $data)){
				
				return TRUE;
			}
			return FALSE;
		}

		public function formIngreso($data){
			if($this->db->insert('rvt', $data)){
				return $this->db->insert_id();
			}
			return FALSE;
		} 
		
		public function eliminaRrv($hash){
			$this->db->where('sha1(id)', $hash);
			if($this ->db->delete('rvt')){
				return TRUE;
			}
			return FALSE;
		}

		public function listaTipos(){
			$this->db->order_by('tipo', 'asc');
			$res=$this->db->get('rop_tipos');
			return $res->result_array();
		}


		public function listaRequerimientos($tipo){
			$this->db->select("id,requerimiento");
		
			if($tipo!=""){
				$this->db->where('id_tipo', $tipo);
			}

			$this->db->where('estado', 1);
			
			$this->db->order_by('requerimiento', 'asc');
			$res=$this->db->get("rop_mantenedor_requerimientos");
			if($res->num_rows()>0){
				$array=array();
				foreach($res->result_array() as $key){
					$temp=array();
					$temp["id"]=$key["id"];
					$temp["text"]=$key["requerimiento"];
					$array[]=$temp;
				}
				return json_encode($array);
			}
			return "[]";
		}

		public function listaPersonas(){
			$this->db->select("u.id as id,
				CONCAT(nombres,'  ',apellidos) as 'nombre_completo',
				CONCAT(SUBSTRING_INDEX(nombres, ' ', '1'),'  ',SUBSTRING_INDEX(SUBSTRING_INDEX(apellidos, ' ', '-2'), ' ', '1')) as 'nombre_corto',
			");

			$this->db->order_by('u.nombres', 'asc');
			$this->db->where('u.estado', 1);
			$res=$this->db->get("usuarios u");
			if($res->num_rows()>0){
				$array=array();
				foreach($res->result_array() as $key){
					$temp=array();
					$temp["id"]=$key["id"];
					$temp["text"]=$key["nombre_completo"];
					$array[]=$temp;
				}
				return json_encode($array);
			}
			return FALSE;
		}

		public function listaMarcas(){
			$this->db->select("u.id,u.proyecto");
			$res = $this->db->get("usuarios_proyectos u");
			if($res->num_rows() > 0){
				$array = array();
				foreach($res->result_array() as $key){
					$temp = array();
					$temp["id"] = $key["id"];
					$temp["text"] = $key["proyecto"];
					$array[] = $temp;
				}
				return json_encode($array);
			}
			return FALSE;
		}
		

		public function getComunaSolicitante($id){
			$this->db->select('comuna');
			$this->db->where('id', $id);
			$res = $this->db->get('usuarios');

			if($res->num_rows()>0){
				$row = $res->row_array();
				return $row["comuna"];
			}

			return FALSE;
		}

		public function getEstadoRop($hash){
			$this->db->select('id_estado');
			$this->db->where('sha1(id)', $hash);
			$res = $this->db->get('rvt');

			if($res->num_rows()>0){
				$row = $res->row_array();
				return $row["id_estado"];
			}

			return FALSE;
		}

		public function getRequiereValidacion($id){
			$this->db->select('requiere_validacion');
			$this->db->where('id', $id);
			$res = $this->db->get('rop_mantenedor_requerimientos');

			if($res->num_rows()>0){
				$row = $res->row_array();
				return $row["requiere_validacion"];
			}

			return FALSE;
		}
		
		public function getJefeSolicitante($id){
			$this->db->select('id_jefe');
			$this->db->where('id', $id);
			$res = $this->db->get('usuarios');

			if($res->num_rows()>0){
				$row = $res->row_array();
				return $row["id_jefe"];
			}

			return FALSE;
		}
		
		public function getValidadorRealRop($hash){
			$this->db->select('id_validador_real');
			$this->db->where('sha1(id)', $hash);
			$res = $this->db->get('rvt');

			if($res->num_rows()>0){
				$row = $res->row_array();
				return $row["id_validador_real"];
			}

			return FALSE;
		}
		
		public function getUsuarioAsignadoRop($hash){
			$this->db->select('id_usuario_asignado');
			$this->db->where('sha1(id)', $hash);
			$res = $this->db->get('rvt');

			if($res->num_rows()>0){
				$row = $res->row_array();
				return $row["id_usuario_asignado"];
			}

			return FALSE;
		}
	
		
	/**********MANTENEDOR REQUERIMIENTOS************/
		
		public function getMantenedorReqList($estado){
			$this->db->select('r.*,
				sha1(r.id) as hash,
				rt.tipo as tipo,

				CONCAT(us.nombres," " ,us.apellidos) as responsable1,
				us.correo_empresa as correo_responsable1,
				CONCAT(us2.nombres," ",us2.apellidos) as responsable2,
				us2.correo_empresa as correo_responsable2,

				CONCAT(CASE r.estado
					WHEN  0 THEN "No activo"
					WHEN  1 THEN "Activo"
				END) AS estado,

				r.estado as id_estado,

				CONCAT(CASE r.requiere_validacion
					WHEN  1 THEN "Si"
					WHEN  0 THEN "No"
				END) AS requiere_validacion,

			');
			
			if($estado != ""){
				$this->db->where('r.estado', $estado);
			}

			$this->db->join('rop_tipos rt', 'rt.id = r.id_tipo', 'left');
			$this->db->join('usuarios as us', 'us.id = r.id_responsable1', 'left');
			$this->db->join('usuarios as us2', 'us2.id = r.id_responsable2', 'left');

			$this->db->order_by('rt.tipo', 'asc');
			$this->db->order_by('r.requerimiento', 'asc');
			$res = $this->db->get('rop_mantenedor_requerimientos as r');
			return $res->result_array();
		}
		
		public function getDataMantReq($hash){
			$this->db->select('r.*,
				sha1(r.id) as hash,
				rt.tipo as tipo,

				CONCAT(CASE r.estado
					WHEN  0 THEN "No activo"
					WHEN  1 THEN "Activo"
				END) AS estado,

				r.estado as id_estado,

				CONCAT(CASE r.requiere_validacion
					WHEN  1 THEN "Si"
					WHEN  0 THEN "No"
				END) AS requiere_validacion,

			');
			$this->db->join('rop_tipos rt', 'rt.id = r.id_tipo', 'left');

			$this->db->order_by('rt.tipo', 'asc');
			$this->db->order_by('r.requerimiento', 'asc');
			$this->db->where('sha1(r.id)', $hash);
			$res = $this->db->get('rop_mantenedor_requerimientos as r');
			return $res->result_array();
 
		}

		public function formActualizarMantenedorReq($id,$data){
			$this->db->where('sha1(id)', $id);
			if($this->db->update('rop_mantenedor_requerimientos', $data)){
				
				return TRUE;
			}
			return FALSE;
		}

		public function formIngresoMantenedorReq($data){
			if($this->db->insert('rop_mantenedor_requerimientos', $data)){
				return $this->db->insert_id();
			}
			return FALSE;
		} 
		
		public function eliminaMantenedorReq($hash){
			$this->db->where('sha1(id)', $hash);
			if($this ->db->delete('rop_mantenedor_requerimientos')){
				return TRUE;
			}
			return FALSE;
		}
 
		/*******TIPO**************/

			public function getMantenedorReqTipoList(){
				$this->db->select('r.*,
					sha1(r.id) as hash,
				');
				$res = $this->db->get('rop_tipos as r');
				return $res->result_array();
			}
			
			public function getDataMantReqTipo($hash){
				$this->db->select('r.*,
					sha1(r.id) as hash,
				');
				$this->db->where('sha1(r.id)', $hash);
				$res = $this->db->get('rop_tipos as r');
				return $res->result_array();
	
			}

			public function formActualizarMantenedorReqTipo($id,$data){
				$this->db->where('sha1(id)', $id);
				if($this->db->update('rop_tipos', $data)){
					
					return TRUE;
				}
				return FALSE;
			}

			public function formIngresoMantenedorReqTipo($data){
				if($this->db->insert('rop_tipos', $data)){
					return $this->db->insert_id();
				}
				return FALSE;
			} 
			
			public function eliminaMantenedorReqTipo($hash){
				$this->db->where('sha1(id)', $hash);
				if($this ->db->delete('rop_tipos')){
					return TRUE;
				}
				return FALSE;
			}

	/*********** GRAFICOS ***************/

		public function graphRequerimientos($desde,$hasta,$tipo){
			$this->db->select(
				"
				t.tipo as tipo,
				req.requerimiento as requerimiento,
				COUNT(*) as cantidad,
				MONTH(r.fecha_ingreso) as mes,
				MONTHNAME(r.fecha_ingreso) as nombre_mes,
				");
			$this->db->from('rvt as r');
			$this->db->join('rop_mantenedor_requerimientos as req', 'req.id = r.id_requerimiento','left');
			$this->db->join('rop_tipos as t', 't.id = req.id_tipo','left');

			if($desde!="" and $hasta!=""){
				$this->db->where("r.fecha_ingreso BETWEEN '".$desde."' AND '".$hasta."'");	
			}

			if($tipo!=""){	
				$this->db->where("req.id_tipo",$tipo);	
			}

			$this->db->group_by('MONTH(r.fecha_ingreso)');
			$this->db->order_by('MONTH(r.fecha_ingreso)', 'desc');
			$res=$this->db->get();

			$mes = array(
				1 => 'Enero',
				2 => 'Febrero',
				3 => 'Marzo',
				4 => 'Abril',
				5 => 'Mayo',
				6 => 'Junio',
				7 => 'Julio',
				8 => 'Agosto',
				9 => 'Septiembre',
				10 => 'Octubre',
				11 => 'Noviembre',
				12 => 'Diciembre'
			);
			
			$array = array();
			$array[]= array(
				"mes",
				"cantidad",
			);
			if($res->num_rows()>0){
				foreach($res->result_array() as $key){
					$temp = array();
					$temp[] = $mes[$key['mes']];
					$temp[] = (int) $key['cantidad'];
					$array[] = $temp;
				}
			}
			else{
				$temp = array();
				$temp[] = "";
				$temp[] = 0;
				$array[] = $temp;
			}
			return $array;

		}

		public function graphRequerimientosSeg($desde,$hasta,$tipo){
			$this->db->select(
				"
				req.requerimiento as requerimiento,
				t.tipo as tipo,
				COUNT(*) as cantidad,
				");
			$this->db->from('rvt as r');
			$this->db->join('rop_mantenedor_requerimientos as req', 'req.id = r.id_requerimiento','left');
			$this->db->join('rop_tipos as t', 't.id = req.id_tipo','left');

			if($desde!="" and $hasta!=""){
				$this->db->where("r.fecha_ingreso BETWEEN '".$desde."' AND '".$hasta."'");	
			}

			if($tipo!=""){	
				$this->db->where("req.id_tipo",$tipo);	
			}

			$this->db->group_by('req.id');
			$this->db->order_by('cantidad', 'desc');
			$res=$this->db->get();

			$array = array();
			$array[]= array(
				"requerimiento",
				"cantidad",
			);
			if($res->num_rows()>0){
				foreach($res->result_array() as $key){
					$temp = array();
					$temp[] = $key['requerimiento'];
					$temp[] = (int) $key['cantidad'];
					$array[] = $temp;
				}
			}
			else{
				$temp = array();
				$temp[] = "";
				$temp[] = 0;
				$array[] = $temp;
			}
			return $array;

		}
}