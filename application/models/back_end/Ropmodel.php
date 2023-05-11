<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ropmodel extends CI_Model {

		public function __construct(){
		parent::__construct();
	}

	public function insertarVisita($data){
	  if($this->db->insert('aplicaciones_visitas', $data)){
			return TRUE;
		}
		return FALSE;
	}
	
	public function getRopList($desde,$hasta,$estado,$responsable){
		$this->db->select('r.*,
			sha1(r.id) as hash,
			CONCAT(us.nombres," " ,us.apellidos) as usuario_asignado,
			CONCAT(us2.nombres," ",us2.apellidos) as validador_real,
			CONCAT(us3.nombres," ",us3.apellidos) as solicitante,
			CONCAT(usj.nombres," " ,usj.apellidos) as jefe_solicitante,

			IF(STR_TO_DATE(r.fecha_ingreso, "%Y-%m-%d") IS NOT NULL, DATE_FORMAT(r.fecha_ingreso,"%d-%m-%Y"),"") as fecha_ingreso,
			
			CASE req_mant.requiere_validacion
				WHEN 0 THEN "No aplica"
				WHEN 1 THEN IF(STR_TO_DATE(r.fecha_validacion, "%Y-%m-%d") IS NOT NULL, DATE_FORMAT(r.fecha_validacion, "%d-%m-%Y"), "")
			END AS fecha_validacion,

		
			IF(STR_TO_DATE(r.fecha_fin, "%Y-%m-%d") IS NOT NULL, DATE_FORMAT(r.fecha_fin,"%d-%m-%Y"),"") as fecha_fin,
			IFNULL(LEAST(TIMESTAMPDIFF(HOUR, CONCAT(r.fecha_ingreso, " ", r.hora_ingreso), NOW()), 0), 0) as horas_pendientes,

			CASE
			WHEN STR_TO_DATE(r.hora_ingreso, "%H:%i") IS NULL OR r.hora_ingreso = "" THEN NULL
				ELSE LEFT(r.hora_ingreso, 5)
			END AS hora_ingreso,

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
				WHEN 1 THEN CONCAT(usj.nombres, " ", usj.apellidos)
			END AS validador_sistema,

			CONCAT(usr.nombres," " ,usr.apellidos) as responsable1,
			CONCAT(usr2.nombres," ",usr2.apellidos) as responsable2,
			
			CONCAT(CASE r.id_estado
				WHEN  0 THEN "Pendiente"
				WHEN  1 THEN "Asignado"
				WHEN  2 THEN "Finalizado"
				WHEN  3 THEN "Cancelado"
				ELSE "Pendiente"

			END) AS estado,
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
	
		if($estado != ""){
			$this->db->where('r.id_estado', $estado);
		}

		if($desde!="" and $hasta!=""){
			$this->db->where("r.fecha_ingreso BETWEEN '".$desde."' AND '".$hasta."'");
		}

		if ($responsable != "") {
			$this->db->group_start();
			$this->db->where('req_mant.id_responsable1', $responsable);
			$this->db->or_where('req_mant.id_responsable2', $responsable);
			$this->db->group_end();
		}
	
		$this->db->order_by('r.fecha_ingreso', 'desc');
		$res = $this->db->get('rop as r');
		return $res->result_array();
	}
	
	public function getDataRop($hash){
		$this->db->select('r.*,
		sha1(r.id) as hash,
			CONCAT(us.nombres," " ,us.apellidos) as usuario_asignado,
			CONCAT(us2.nombres," ",us2.apellidos) as validador_real,
			CONCAT(us3.nombres," ",us3.apellidos) as solicitante,
			CONCAT(usj.nombres," " ,usj.apellidos) as jefe_solicitante,

			IF(STR_TO_DATE(r.fecha_ingreso, "%Y-%m-%d") IS NOT NULL, DATE_FORMAT(r.fecha_ingreso,"%d-%m-%Y"),"") as fecha_ingreso,
			
			CASE req_mant.requiere_validacion
				WHEN 0 THEN "No aplica"
				WHEN 1 THEN IF(STR_TO_DATE(r.fecha_validacion, "%Y-%m-%d") IS NOT NULL, DATE_FORMAT(r.fecha_validacion, "%d-%m-%Y"), "")
			END AS fecha_validacion,

		
			IF(STR_TO_DATE(r.fecha_fin, "%Y-%m-%d") IS NOT NULL, DATE_FORMAT(r.fecha_fin,"%d-%m-%Y"),"") as fecha_fin,
			IFNULL(LEAST(TIMESTAMPDIFF(HOUR, CONCAT(r.fecha_ingreso, " ", r.hora_ingreso), NOW()), 0), 0) as horas_pendientes,

			CASE
			WHEN STR_TO_DATE(r.hora_ingreso, "%H:%i") IS NULL OR r.hora_ingreso = "" THEN NULL
				ELSE LEFT(r.hora_ingreso, 5)
			END AS hora_ingreso,

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
				WHEN 1 THEN CONCAT(usj.nombres, " ", usj.apellidos)
			END AS validador_sistema,

			CONCAT(usr.nombres," " ,usr.apellidos) as responsable1,
			CONCAT(usr2.nombres," ",usr2.apellidos) as responsable2,
			
			CONCAT(CASE r.id_estado
				WHEN  0 THEN "Pendiente"
				WHEN  1 THEN "Asignado"
				WHEN  2 THEN "Finalizado"
				WHEN  3 THEN "Cancelado"
				ELSE "Pendiente"

			END) AS estado,
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

		$this->db->where('sha1(r.id)', $hash);
		$res=$this->db->get('rop as r');
		return $res->result_array();
	}

	public function formActualizar($id,$data){
		$this->db->where('sha1(id)', $id);
	    if($this->db->update('rop', $data)){
	    	
	    	return TRUE;
	    }
	    return FALSE;
	}

	public function formIngreso($data){
		if($this->db->insert('rop', $data)){
			return $this->db->insert_id();
		}
		return FALSE;
	} 
	
	public function eliminaRop($hash){
		$this->db->where('sha1(id)', $hash);
		if($this ->db->delete('rop')){
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
		return FALSE;
	}

	public function listaPersonas(){
		$this->db->select("u.id as id,
		    CONCAT(nombres,'  ',apellidos) as 'nombre_completo',
		    CONCAT(SUBSTRING_INDEX(nombres, ' ', '1'),'  ',SUBSTRING_INDEX(SUBSTRING_INDEX(apellidos, ' ', '-2'), ' ', '1')) as 'nombre_corto',
		");

		$this->db->order_by('u.nombres', 'asc');
		$res=$this->db->get("usuarios u");
		if($res->num_rows()>0){
			$array=array();
			foreach($res->result_array() as $key){
				$temp=array();
				$temp["id"]=$key["id"];
				$temp["text"]=$key["nombre_corto"];
				$array[]=$temp;
			}
			return json_encode($array);
		}
		return FALSE;
	}

	public function listaResponsables(){
		$this->db->select("u.id as id,
			CONCAT(nombres, ' ', apellidos) as 'nombre_completo',
			CONCAT(SUBSTRING_INDEX(nombres, ' ', '1'), ' ', SUBSTRING_INDEX(SUBSTRING_INDEX(apellidos, ' ', '-2'), ' ', '1')) as 'nombre_corto',
		");
	
		$this->db->order_by('u.nombres', 'asc');
		$this->db->join('rop_mantenedor_requerimientos rm', 'rm.id_responsable1 = u.id OR rm.id_responsable2 = u.id', 'inner');
		$this->db->group_by('u.id'); // Agrupar por la columna u.id
		
		$res = $this->db->get("usuarios u");
		if($res->num_rows() > 0){
			$array = array();
			foreach($res->result_array() as $key){
				$temp = array();
				$temp["id"] = $key["id"];
				$temp["text"] = $key["nombre_corto"];
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
		$res = $this->db->get('rop');

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
		$res = $this->db->get('rop');

		if($res->num_rows()>0){
			$row = $res->row_array();
			return $row["id_validador_real"];
		}

		return FALSE;
	}
	
	public function getUsuarioAsignadoRop($hash){
		$this->db->select('id_usuario_asignado');
		$this->db->where('sha1(id)', $hash);
		$res = $this->db->get('rop');

		if($res->num_rows()>0){
			$row = $res->row_array();
			return $row["id_usuario_asignado"];
		}

		return FALSE;
	}

}