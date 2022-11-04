<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Checklistftthmodel extends CI_Model {

	public function __construct(){
		parent::__construct();
	}
	
	public function insertarVisita($data){
		if($this->db->insert('aplicaciones_visitas', $data)){
			return TRUE;
		}
		return FALSE;
	}

	/*************CHECKLIST FTTH*************/

		public function listaChecklistFTTH($desde,$hasta){

			$this->db->select("sha1(o.id) as hash,
				o.*,
				CONCAT(u.nombres,' ',u.apellidos) as 'tecnico',
			    CONCAT(us.nombres,' ',us.apellidos) as 'auditor',
			    u.comuna as comuna,
			    u.codigo as codigo,
			    u.rut as rut_tecnico,
				o.ultima_actualizacion as ultima_actualizacion,
				uc.cargo as cargo,
				ua.area as area,
				cha.actividad as tipo_actividad,
		        if(o.fecha!='0000-00-00', DATE_FORMAT(o.fecha,'%d-%m-%Y'),'') as 'fecha'
				");

			$this->db->join('usuarios u', 'u.id = o.tecnico_id', 'left');
			$this->db->join('usuarios us', 'us.id = o.auditor_id', 'left');
			$this->db->join('usuarios_areas ua', 'ua.id = u.id_area', 'left');
			$this->db->join('usuarios_cargos uc', 'uc.id = us.id_cargo', 'left');
			$this->db->join('checklist_ftth_actividades cha', 'cha.id = o.tipo_actividad', 'left');

			if($desde!="" and $hasta!=""){
				$this->db->where("o.fecha BETWEEN '".$desde."' AND '".$hasta."'");	
			}

			$res=$this->db->get('checklist_ftth o');
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}
					

		public function getDataChecklistFTTHCabecera($hash){
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
			$res=$this->db->get('checklist_ftth o');
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}


		public function getDataChecklistFTTH($hash){
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
		        if(o.fecha!='0000-00-00', DATE_FORMAT(o.fecha,'%d-%m-%Y'),'') as 'fecha',

		        cl.descripcion as descripcion,
				ct.tipo as tipo,

				CASE 
		          WHEN cd.estado=0 THEN 'ok'
		          WHEN cd.estado=1 THEN 'nook'
		          WHEN cd.estado=2 THEN 'noap'
		        END AS estado_str,

				cd.observacion as observacion

				");
			$this->db->join('usuarios u', 'u.id = o.tecnico_id', 'left');
			$this->db->join('usuarios us', 'us.id = o.auditor_id', 'left');
			$this->db->join('usuarios_areas ua', 'ua.id = u.id_area', 'left');
			$this->db->join('usuarios_cargos uc', 'uc.id = us.id_cargo', 'left');
			$this->db->join('checklist_ftth_detalle cd', 'cd.id_ots = o.id', 'left');
			$this->db->join('checklist_ftth_listado cl', 'cl.id = cd.id_check', 'left');
			$this->db->join('checklist_ftth_tipos ct', 'ct.id = cl.tipo', 'left');
			$this->db->where('ct.id is not null');
			$this->db->where('sha1(o.id)', $hash);
			$res=$this->db->get('checklist_ftth o');
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

		public function listaChecklistFTTHDetalle($desde,$hasta){
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
		          WHEN cd.estado=2 THEN 'no aplica'
		        END AS estado_str,

				cd.observacion as observacion

				");

			$this->db->join('usuarios as u', 'u.id = o.tecnico_id', 'left');
			$this->db->join('usuarios as us', 'us.id = o.auditor_id', 'left');
			$this->db->join('usuarios_areas ua', 'ua.id = u.id_area', 'left');
			$this->db->join('usuarios_cargos uc', 'uc.id = us.id_cargo', 'left');
			$this->db->join('checklist_ftth_detalle cd', 'cd.id_ots = o.id', 'left');
			$this->db->join('checklist_ftth_listado cl', 'cl.id = cd.id_check', 'left');
			$this->db->join('checklist_ftth_tipos ct', 'ct.id = cl.tipo', 'left');

			if($desde!="" and $hasta!=""){
				$this->db->where("o.fecha BETWEEN '".$desde."' AND '".$hasta."'");	
			}

			$res=$this->db->get('checklist_ftth o');
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
		        END AS estado_str");
			
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


		public function formChecklistFTTH($data){
			if($this->db->insert('checklist_ftth', $data)){
				return $this->db->insert_id();
			}
			return FALSE;
		}

		public function formChecklistLista($data){
			if($this->db->insert('checklist_ftth_listado', $data)){
				return $this->db->insert_id();
			}
			return FALSE;
		}

		public function existeOT($ot){
			$this->db->where('n_ot', $ot);
			$res=$this->db->get('checklist_ftth');
			if($res->num_rows()>0){
				return TRUE;
			}
			return FALSE;
		}

		public function existeOTMod($hash,$ot){
			$this->db->where('n_ot', $ot);
			$this->db->where('sha1(id)<>', $hash);
			$res=$this->db->get('checklist_ftth');
			if($res->num_rows()>0){
				return TRUE;
			}
			return FALSE;
		}


		public function actualizarOTS($hash,$data){
			$this->db->where('sha1(id)', $hash);
			if($this->db->update('checklist_ftth', $data)){
				return TRUE;
			}
			return FALSE;
		}

		public function listaTiposActividad(){
			$this->db->order_by('actividad', 'asc');
			$res = $this->db->get('checklist_ftth_actividades');
			return $res->result_array();
		}

		public function listaComunas(){
			$this->db->order_by('titulo', 'asc');
			$res = $this->db->get('comunas');
			return $res->result_array();
		}

		public function listaTecnicos(){
			$this->db->select("id,CONCAT(nombres,' ',apellidos) as 'nombre_completo'");
			/*$this->db->where('id_perfil', 4);*/
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
			$res=$this->db->get("checklist_ftth_tipos");
			$row=$res->row_array();
			return $row["id"];
		}

		public function getIdPorHash($hash){
			$this->db->where('sha1(id)', $hash);
			$res=$this->db->get("checklist_ftth");
			if($res->num_rows()>0){
				$row=$res->row_array();
				return $row["id"];
			}
			return FALSE;
		}
		

		public function insertarItemChecklist($data){
			if($this->db->insert('checklist_ftth_listado', $data)){
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
			if($this->db->insert('checklist_ftth_detalle', $data)){
				return TRUE;
			}
			return FALSE;
		}

		
		public function listaChecklist(){
			$this->db->select('sha1(c.id) as hash,
				c.*,
				ct.tipo as tipo');

			$this->db->join('checklist_ftth_tipos ct', 'ct.id = c.tipo', 'left');
			$this->db->order_by('c.id', 'asc');
			$res=$this->db->get('checklist_ftth_listado c');
			return $res->result_array();
		}

		public function existeDetalleOTS($id){
			$this->db->where('id_ots', $id);
			$res=$this->db->get('checklist_ftth_detalle');
			if($res->num_rows()>0){
				return TRUE;
			}
			return FALSE;
		}

		public function getIddetalle($id_ots,$id_check){
			$this->db->where('(id_ots)', $id_ots);
			$this->db->where('(id_check)', $id_check);
			$res=$this->db->get("checklist_ftth_detalle");
			if($res->num_rows()>0){
				$row=$res->row_array();
				return $row["id"];
			}
			return FALSE;
		}


		public function actualizaDetalleOTS($id,$data){
			$this->db->where('(id)', $id);
			if($this->db->update('checklist_ftth_detalle', $data)){
				// echo $this->db->last_query();;
				// echo "<br>";
				return TRUE;
			}
			return FALSE;
		}

		
		public function eliminaChecklist($hash){
			$this->db->where('sha1(id)', $hash);
		    if($this ->db->delete('checklist_ftth')){
		    	$this->db->where('sha1(id_ots)', $hash);
			    if($this ->db->delete('checklist_ftth_detalle')){
			    	return TRUE;
			    }
		    }
		    return FALSE;
		}

		
		public function agregaImagenesChecklist($data){
			if($this->db->insert('checklist_ftth_galeria', $data)){
				return TRUE;
			}
			return FALSE;
		}

		public function cantidadImagenesChecklist($id){
			$this->db->where('id_checklist', $id);
			$res=$this->db->get('checklist_ftth_galeria');
			return $res->num_rows();
		}

		public function getImagenChecklist($id){
			$this->db->select("imagen");
			$this->db->where('sha1(id_checklist)', $id);
			$res=$this->db->get("checklist_ftth_galeria");
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

		public function eliminaImagenChecklist($hash){
			$this->db->where('sha1(id_checklist)', $hash);
		    if($this ->db->delete('checklist_ftth_galeria')){
			    return TRUE;
		    }
		    return FALSE;
		}

		public function eliminaImagenIChecklist($hash){
			$this->db->where('sha1(id)', $hash);
		    if($this ->db->delete('checklist_ftth_galeria')){
			    return TRUE;
		    }
		    return FALSE;
		}

		public function getChecklistGaleria($hash){
			$this->db->select('
				cg.id as id_galeria,
				cg.id_checklist as id_checklist,
				cg.titulo as titulo_galeria,
				cg.imagen as imagen');

			$this->db->where('sha1(cg.id_checklist)', $hash);
			$res=$this->db->get("checklist_ftth_galeria as cg");
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

		public function getImagenGaleria($id){
			$this->db->select('imagen');
			$this->db->where('id', $id);
			$res=$this->db->get('checklist_ftth_galeria');
			$row=$res->row_array();
			return $row["imagen"];
		}


		/*public function eliminaChecklistFTTH($hash){
			$this->db->where('sha1(id)', $hash);
		    if($this ->db->delete('checklist_ftth')){
		    	
		    	$this->db->where('sha1(id_ots)', $hash);
			    if($this ->db->delete('checklist_ftth_detalle')){
			    	return TRUE;
			    }

		    }
		    return FALSE;
		}*/

	/*************REPORTE*********/

		public function dataEstadosChecklistFTTH(){
			$this->db->select("
				CASE 
		          WHEN cd.estado=0 THEN 'OK'
		          WHEN cd.estado=1 THEN 'No Ok'
		          WHEN cd.estado=2 THEN 'No Aplica'
		        END AS estado,
				count(cd.id) as cantidad,
				");

			$this->db->group_by('cd.estado');
			$res=$this->db->get('checklist_ftth_detalle cd');
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

		public function dataTecnicosChecklistFTTH(){
			$this->db->select("
				CONCAT(u.nombres,' ',u.apellidos) as 'tecnico',

				SUM(CASE 
	             WHEN cd.estado = 0 THEN 1
	             ELSE 0
	            END) AS cantidad_ok,

	            SUM(CASE 
	             WHEN cd.estado = 1 THEN 1
	             ELSE 0
	            END) AS cantidad_nook,

	            SUM(CASE 
	             WHEN cd.estado = 2 THEN 1
	             ELSE 0
	            END) AS cantidad_noaplica

				");

			$this->db->group_by('c.tecnico_id');
			$this->db->join('checklist_ftth c', 'c.id = cd.id_ots', 'left');	
			$this->db->join('usuarios u', 'u.id = c.tecnico_id', 'left');
			$res=$this->db->get('checklist_ftth_detalle cd');

			$cabeceras = array("Técnico","OK",array('role'=> 'annotation'),"No OK",array('role'=> 'annotation'),"No aplica",array('role'=> 'annotation'));
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
				$temp[] = (int)$key["cantidad_noaplica"];
				$temp[] = (int)$key["cantidad_noaplica"];
				$array[]=$temp;
			}
			return $array;

		}
		
	
		
	/*************FALLOS FTTH****************/

		public function listaFFTTH($desde,$hasta,$solucion_estado){
			$this->db->select("sha1(cd.id) as hash,
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
		          WHEN cd.estado=2 THEN 'No aplica'
		        END AS estado,
				cd.observacion as observacion,

		        CASE 
		          WHEN cd.solucion_estado=0 THEN 'Pendiente'
		          WHEN cd.solucion_estado=1 THEN 'Finalizado'
		        END AS solucion_estado,
		        
				if(cd.solucion_fecha!='0000-00-00', DATE_FORMAT(cd.solucion_fecha,'%d-%m-%Y'),'') as 'solucion_fecha',
				cd.solucion_observacion as solucion_observacion

				");

			$this->db->join('usuarios as u', 'u.id = o.tecnico_id', 'left');
			$this->db->join('usuarios as us', 'us.id = o.auditor_id', 'left');
			$this->db->join('usuarios_areas ua', 'ua.id = u.id_area', 'left');
			$this->db->join('usuarios_cargos uc', 'uc.id = us.id_cargo', 'left');
			$this->db->join('checklist_ftth_detalle cd', 'cd.id_ots = o.id', 'left');
			$this->db->join('checklist_ftth_listado cl', 'cl.id = cd.id_check', 'left');
			$this->db->join('checklist_ftth_tipos ct', 'ct.id = cl.tipo', 'left');
			$this->db->where('cd.estado=1');
			
			if($desde!="" and $hasta!=""){
				$this->db->where("o.fecha BETWEEN '".$desde."' AND '".$hasta."'");	
			}

			if($solucion_estado!=""){
				$this->db->where('solucion_estado', $solucion_estado);
			}

			$res=$this->db->get('checklist_ftth o');
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

		public function getDataFFTTH($hash){
			$this->db->select("sha1(cd.id) as hash,
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
		          WHEN cd.estado=2 THEN 'No aplica'
		        END AS estado,
				cd.observacion as observacion,

		        CASE 
		          WHEN cd.solucion_estado=0 THEN 'Pendiente'
		          WHEN cd.solucion_estado=1 THEN 'Finalizado'
		        END AS solucion_estado,

				if(cd.solucion_fecha!='0000-00-00', DATE_FORMAT(cd.solucion_fecha,'%d-%m-%Y'),'') as 'solucion_fecha',
				cd.solucion_observacion as solucion_observacion
				");
			$this->db->join('usuarios as u', 'u.id = o.tecnico_id', 'left');
			$this->db->join('usuarios as us', 'us.id = o.auditor_id', 'left');
			$this->db->join('usuarios_areas ua', 'ua.id = u.id_area', 'left');
			$this->db->join('usuarios_cargos uc', 'uc.id = us.id_cargo', 'left');
			$this->db->join('checklist_ftth_detalle cd', 'cd.id_ots = o.id', 'left');
			$this->db->join('checklist_ftth_listado cl', 'cl.id = cd.id_check', 'left');
			$this->db->join('checklist_ftth_tipos ct', 'ct.id = cl.tipo', 'left');
			$this->db->where('cd.estado=1');
			$this->db->where('sha1(cd.id)', $hash);
			$res=$this->db->get('checklist_ftth o');
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

		public function getFechaSolucion($hash){
			$this->db->select('solucion_fecha');
			$this->db->where('sha1(id)', $hash);
			$res = $this->db->get('checklist_ftth_detalle');
			if($res->num_rows()>0){
				$row = $res->row_array();
				return $row["solucion_fecha"];
			}
			return FALSE;
		}

		public function actualizarFFTTH($hash,$data){
			$this->db->where('sha1(id)', $hash);
			if($this->db->update('checklist_ftth_detalle', $data)){
				return TRUE;
			}
			return FALSE;
		}

		
		public function listaTecnicosFFTTH(){
			$this->db->select("id,CONCAT(nombres,' ',apellidos) as 'nombre_completo'");
			$this->db->where('id_perfil', 4);
			$this->db->order_by('nombres', 'asc');
			$res=$this->db->get("usuarios");
			return $res->result_array();
		}

		public function listaAuditoresFFTTH(){
			$this->db->select("id,CONCAT(nombres,' ',apellidos) as 'nombre_completo'");
			$this->db->where('id_perfil', 3);
			$this->db->order_by('nombres', 'asc');
			$res=$this->db->get("usuarios");
			return $res->result_array();
		}


	/****************GRAFICO FALLOS************************/

		public function graficoFallos($desde,$hasta,$trabajador,$auditor){
			$this->db->select("
				MONTH(c.fecha) as mes,
				YEAR(c.fecha) as anio,
				CONCAT(01 ,'-',MONTH(c.fecha),'-',YEAR(c.fecha)) as 'fecha',

				SUM(CASE 
	        		WHEN cd.estado ='0' 
	        		THEN 1
	            ELSE 0
	            END) as cantidad_ok,

	            SUM(CASE 
	                WHEN cd.estado ='1' 
	                THEN 1
	                ELSE 0
	            END) as cantidad_nook,

		        CONCAT(ROUND((
		            SUM(CASE 
		        		WHEN cd.estado ='1' 
		        		THEN 1
		            ELSE 0
		            END)
		            /
		            SUM(CASE 
		                WHEN cd.estado ='0' 
		                THEN 1
		                ELSE 0
		            END)
		        * 100 ),2),'%') AS 'porcentaje_nook'

		    ",FALSE);


			if($desde!="" and $hasta!=""){$this->db->where("c.fecha BETWEEN '".$desde."' AND '".$hasta."'");	}
			if($trabajador!=""){$this->db->where('c.tecnico_id', $trabajador);}
			if($auditor!=""){	$this->db->where('c.auditor_id', $auditor);}

			$this->db->group_by('MONTH(c.fecha)');
			$this->db->group_by('YEAR(c.fecha)');
			/*$this->db->where('cd.estado=1');*/
			$this->db->join('checklist_ftth c', 'c.id = cd.id_ots', 'left');
			$this->db->join('usuarios u', 'u.id = c.tecnico_id', 'left');
			$this->db->join('usuarios us', 'us.id = c.auditor_id', 'left');
			$res=$this->db->get("checklist_ftth_detalle cd");
			$array = array();
			
			if($res->num_rows()>0){
				foreach($res->result_array() as $key){
						$temp = array();
					    $temp[] = (string)meses_corto($key["mes"])."-".substr($key["anio"], 2, 4); 
					    $temp[] = (float)$key["porcentaje_nook"]; 
					    $temp[] = (int) $key['cantidad_ok'];
					    $temp[] = (int) $key['cantidad_nook'];
				 	   /* $temp[] = (string) $v = ($key['calidad']==0) ? null: $key['calidad'];
				 	    $temp[] = (string) $v = ($key['ordenes']==0) ? null: $key['ordenes'];
				 	    $temp[] = (string) $v = ($key['fallos']==0) ? null: $key['fallos'];*/
				 	    $temp[] = strtotime($key["fecha"]);
					    $array[] = $temp;

				}
				return $array;
			}
		}

		public function listaJefes(){
			$this->db->select("sha1(uj.id) as hash_jefes,
				uj.id as id_jefe,
				uj.id_jefe as id_usuario_jefe,
				CONCAT(u.nombres,' ',u.apellidos)  'nombre_jefe'
			");

			$this->db->join('usuarios u', 'u.id = uj.id_jefe', 'left');

			if($this->session->userdata('id_perfil')==3){
				if($this->session->userdata('verificacionJefe')=="1"){
					$this->db->where('uj.id', $this->session->userdata('id_jefe'));
				}
			}
			
			$this->db->where('(u.id_cargo=32 or u.id_cargo=18)'); //u.id_cargo=10
			$this->db->order_by('nombre_jefe', 'asc');
			$res=$this->db->get('usuarios_jefes uj');
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}


		public function getIdPorRut($rut){
			$this->db->where('rut', $rut);
			$res=$this->db->get("usuarios");
			if($res->num_rows()>0){
				$row=$res->row_array();
				return $row["id"];
			}
			return FALSE;
		}

		public function listaTrabajadoresFTTH($jefe){
			$this->db->select("concat(substr(replace(rut,'-',''),1,char_length(replace(rut,'-',''))-1),'-',substr(replace(rut,'-',''),char_length(replace(rut,'-','')))) as 'rut_format',
				empresa,id,rut,
			    CONCAT(nombres,'  ',apellidos) as 'nombre_completo',
			    CONCAT(SUBSTRING_INDEX(nombres, ' ', '1'),'  ',SUBSTRING_INDEX(SUBSTRING_INDEX(apellidos, ' ', '-2'), ' ', '1')) as 'nombre_corto',
			");
			
			if($this->session->userdata('id_perfil')==4){
				$this->db->where('rut', $this->session->userdata('rut'));
			}

			if($jefe!=""){
				$this->db->where('id_jefe', $jefe);
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


}