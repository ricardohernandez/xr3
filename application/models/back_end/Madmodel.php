<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Madmodel extends CI_Model {

		public function __construct(){
		parent::__construct();
	}

	public function insertarVisita($data){
	  if($this->db->insert('aplicaciones_visitas', $data)){
			return TRUE;
		}
		return FALSE;
	}
	
	/**********MAD*************/

		public function getMadList($desde,$hasta,$coordinador,$comuna,$zona,$empresa,$supervisor){
			$this->db->select(
				"sha1(r.id) as hash,
				pl.titulo as comuna,
				CONCAT(SUBSTRING_INDEX(u.nombres, ' ', '1'),'  ',SUBSTRING_INDEX(SUBSTRING_INDEX(u.apellidos, ' ', '-2'), ' ', '1')) as nombre_tecnico,
				CONCAT(SUBSTRING_INDEX(u2.nombres, ' ', '1'),'  ',SUBSTRING_INDEX(SUBSTRING_INDEX(u2.apellidos, ' ', '-2'), ' ', '1')) as nombre_coordinador,
                a.area as zona,
				p.proyecto as proyecto,
				r.*,
				rt.tipo as tipo,
			");

			$this->db->join('comunas_mad as pl', 'pl.id = r.id_comuna', 'left');
			$this->db->join('mad_tipos as rt', 'rt.id = r.id_tipo', 'left');
			$this->db->join('usuarios as u', 'r.id_tecnico = u.id', 'left');
			$this->db->join('usuarios as u2', 'r.id_coordinador = u2.id', 'left');
			$this->db->join('usuarios_areas as a', 'r.id_zona = a.id', 'left');
			$this->db->join('usuarios_proyectos as p', 'r.id_proyecto = p.id', 'left');

			if($desde!="" and $hasta!=""){
				$this->db->where("r.fecha BETWEEN '".$desde."' AND '".$hasta."'");
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
			if ($supervisor != "") {
				$this->db->where('u.id_jefe', $supervisor);
			}

			$this->db->order_by('r.fecha', 'desc');
			$res = $this->db->get('mad as r');
			return $res->result_array();
		}
		
		public function getDataMad($hash){
			$this->db->select('
				sha1(r.id) as hash,
                r.*,
			');
			$this->db->where('sha1(r.id)', $hash);
			$res=$this->db->get('mad as r');
			return $res->result_array();
		}

		public function formActualizar($id,$data){
			$this->db->where('sha1(id)', $id);
			if($this->db->update('mad', $data)){
				
				return TRUE;
			}
			return FALSE;
		}

		public function formIngreso($data){
			if($this->db->insert('mad', $data)){
				return $this->db->insert_id();
			}
			return FALSE;
		} 
		
		public function eliminaMad($hash){
			$this->db->where('sha1(id)', $hash);
			if($this ->db->delete('mad')){
				return TRUE;
			}
			return FALSE;
		}

	/***** GRAFICOS *****/

		public function DataGraficoResultadosxZona($desde,$hasta,$coordinador,$comuna,$zona,$empresa,$supervisor){
			$this->db->select('
				COUNT(*) as cantidad,
				a.area as zona,
			');
			$this->db->from('mad');
			if($desde!="" and $hasta!=""){$this->db->where("fecha BETWEEN '".$desde."' AND '".$hasta."'");}
			if($coordinador!=""){	$this->db->where('id_coordinador', $coordinador);}
			if($comuna!=""){	$this->db->where('id_comuna', $comuna);}
			if($zona!=""){	$this->db->where('id_zona', $zona);}
			if($empresa!=""){	$this->db->where('id_proyecto', $empresa);}
			if($supervisor!=""){	$this->db->where('id_tecnico', $supervisor);}

			$this->db->join('usuarios_areas as a', 'id_zona = a.id', 'left');
			$this->db->group_by('zona');
			$res=$this->db->get();
			$array = array();
			$array[]= array(
				"Zona",
				"Resultado",
			);
			if($res->num_rows()>0){
				foreach($res->result_array() as $key){
					$temp = array();
					$temp[] =$key['zona'];
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
		public function DataGraficoResultadosxComuna($desde,$hasta,$coordinador,$comuna,$zona,$empresa,$supervisor){
			$this->db->select('
				COUNT(*) as cantidad,
				a.titulo as comuna,
			');
			$this->db->from('mad');
			if($desde!="" and $hasta!=""){$this->db->where("fecha BETWEEN '".$desde."' AND '".$hasta."'");}
			if($coordinador!=""){	$this->db->where('id_coordinador', $coordinador);}
			if($comuna!=""){	$this->db->where('id_comuna', $comuna);}
			if($zona!=""){	$this->db->where('id_zona', $zona);}
			if($empresa!=""){	$this->db->where('id_proyecto', $empresa);}
			if($supervisor!=""){	$this->db->where('id_tecnico', $supervisor);}

			$this->db->join('comunas_mad as a', 'id_comuna = a.id', 'left');
			$this->db->group_by('comuna');
			$res=$this->db->get();
			$array = array();
			$array[]= array(
				"Comuna",
				"Resultado",
			);
			if($res->num_rows()>0){
				foreach($res->result_array() as $key){
					$temp = array();
					$temp[] =$key['comuna'];
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
		public function DataGraficoResultadosxEmpresa($desde,$hasta,$coordinador,$comuna,$zona,$empresa,$supervisor){
			$this->db->select('
				COUNT(*) as cantidad,
				a.proyecto as proyecto,
			');
			$this->db->from('mad');
			if($desde!="" and $hasta!=""){$this->db->where("fecha BETWEEN '".$desde."' AND '".$hasta."'");}
			if($coordinador!=""){	$this->db->where('id_coordinador', $coordinador);}
			if($comuna!=""){	$this->db->where('id_comuna', $comuna);}
			if($zona!=""){	$this->db->where('id_zona', $zona);}
			if($empresa!=""){	$this->db->where('id_proyecto', $empresa);}
			if($supervisor!=""){	$this->db->where('id_tecnico', $supervisor);}

			$this->db->join('usuarios_proyectos as a', 'id_proyecto = a.id', 'left');
			$this->db->group_by('proyecto');
			$res=$this->db->get();
			$array = array();
			$array[]= array(
				"Proyecto",
				"Resultado",
			);
			if($res->num_rows()>0){
				foreach($res->result_array() as $key){
					$temp = array();
					$temp[] =$key['proyecto'];
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
		public function DataGraficoResultadosxSupervisor($desde,$hasta,$coordinador,$comuna,$zona,$empresa,$supervisor){
			$this->db->select("
				COUNT(*) as cantidad,
				CONCAT(SUBSTRING_INDEX(a.nombres, ' ',1), ' ',SUBSTRING_INDEX(a.apellidos, ' ', 1)) as usuario,
				mt.tipo as tipo,
			");
			$this->db->from('mad');
			if($desde!="" and $hasta!=""){$this->db->where("fecha BETWEEN '".$desde."' AND '".$hasta."'");}
			if($coordinador!=""){	$this->db->where('id_coordinador', $coordinador);}
			if($comuna!=""){	$this->db->where('id_comuna', $comuna);}
			if($zona!=""){	$this->db->where('id_zona', $zona);}
			if($empresa!=""){	$this->db->where('id_proyecto', $empresa);}
			if($supervisor!=""){	$this->db->where('id_tecnico', $supervisor);}

			$this->db->join('usuarios as a', 'id_tecnico = a.id', 'left');
			$this->db->join('mad_tipos as mt', 'id_tipo = mt.id', 'left');
			$this->db->group_by('usuario');
			$res=$this->db->get();

			$array = array();
			$array[]= array(
				"Supervisor",
				"Cantidad",
			);
			if($res->num_rows()>0){
				foreach($res->result_array() as $key){
					$temp = array();
					$temp[] = $key['usuario'];
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
		public function DataGraficoResultadosxCoordinador($desde,$hasta,$coordinador,$comuna,$zona,$empresa,$supervisor){
			$this->db->select("
				COUNT(*) as cantidad,
				CONCAT(SUBSTRING_INDEX(a.nombres, ' ', 1), ' ', SUBSTRING_INDEX(a.apellidos, ' ', 1)) as usuario,
				mt.tipo as tipo,
			");
			$this->db->from('mad');
			if($desde!="" and $hasta!=""){$this->db->where("fecha BETWEEN '".$desde."' AND '".$hasta."'");}
			if($coordinador!=""){	$this->db->where('id_coordinador', $coordinador);}
			if($comuna!=""){	$this->db->where('id_comuna', $comuna);}
			if($zona!=""){	$this->db->where('id_zona', $zona);}
			if($empresa!=""){	$this->db->where('id_proyecto', $empresa);}
			if($supervisor!=""){	$this->db->where('id_tecnico', $supervisor);}

			$this->db->join('usuarios as a', 'id_coordinador = a.id', 'left');
			$this->db->join('mad_tipos as mt', 'id_tipo = mt.id', 'left');
			$this->db->group_by('usuario');
			$res=$this->db->get();

			$array = array();
			$array[]= array(
				"Coordinador",
				"Cantidad",
			);
			if($res->num_rows()>0){
				foreach($res->result_array() as $key){
					$temp = array();
					$temp[] = $key['usuario'];
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

	/************** OTROS ****************/

		/*MASIVA*/
		public function GetZonaMasivo($zona){
			$this->db->select('a.id as id,a.area');	
			$this->db->from('usuarios_areas as a');
			$this->db->where('a.area LIKE', '%' . $zona . '%');
			$res=$this->db->get();
			if($res->num_rows()>0){
				foreach($res->result_array() as $key){
					return $key["id"];
				}
			}
			return 0;
		}

		public function GetCiudadMasivo($ciudad){
			$this->db->select('c.id as id,c.titulo');	
			$this->db->from('comunas_mad as c');
			$this->db->where('c.titulo LIKE', '%' . $ciudad . '%');
			$res=$this->db->get();
			if($res->num_rows()>0){
				foreach($res->result_array() as $key){
					return $key["id"];
				}
			}
			return 0;
		}

		public function GetTecnicoMasivo($nombre,$apellido){
			$this->db->select('u.id as id');	
			$this->db->from('usuarios as u');
			$this->db->where('u.nombres LIKE', '%' . $nombre . '%');
			$this->db->where('u.apellidos LIKE', '%' . $apellido . '%');
			$this->db->where('u.estado',1);
			$res=$this->db->get();
			if($res->num_rows()>0){
				foreach($res->result_array() as $key){
					return $key["id"];
				}
			}
			return 0;
		}

		public function GetProyectoMasivo($proyecto){
			$this->db->select('p.id as id');	
			$this->db->from('usuarios_proyectos as p');
			$this->db->where('p.proyecto LIKE', '%' . $proyecto . '%');
			$res=$this->db->get();
			if($res->num_rows()>0){
				foreach($res->result_array() as $key){
					return $key["id"];
				}
			}
			return 0;
		}

		public function GetTipoMasivo($tipo){
			$this->db->select('t.id as id');	
			$this->db->from('mad_tipos as t');
			$this->db->where('t.tipo LIKE', '%' . $tipo . '%');
			$res=$this->db->get();
			if($res->num_rows()>0){
				foreach($res->result_array() as $key){
					return $key["id"];
				}
			}
			return 0;
		}

	public function listaComunas(){
		$this->db->select('c.*');	
		$this->db->from('comunas_mad as c');
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
		$res=$this->db->get('mad_tramos');
		if($res->num_rows()>0){
			return $res->result_array();
		}
		return FALSE;
	}

	public function listaTipos(){
		$this->db->order_by('tipo', 'asc');
		$res=$this->db->get('mad_tipos');
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
		$res=$this->db->get('mad_motivos');
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

		public function getComunasMadList(){
			$this->db->select(
				"sha1(r.id) as hash,
				r.*,
			");

			$this->db->order_by('r.titulo', 'desc');
			$res = $this->db->get('comunas_mad as r');
			return $res->result_array();
		}

		public function getDataComunasMad($hash){
			$this->db->select('
				sha1(r.id) as hash,
				r.*,
			');
			$this->db->where('sha1(r.id)', $hash);
			$res=$this->db->get('comunas_mad as r');
			return $res->result_array();
		}

		public function formActualizarComuna($id,$data){
			$this->db->where('sha1(id)', $id);
			if($this->db->update('comunas_mad', $data)){
				
				return TRUE;
			}
			return FALSE;
		}

		public function formIngresoComuna($data){
			if($this->db->insert('comunas_mad', $data)){
				return $this->db->insert_id();
			}
			return FALSE;
		} 
		
		public function eliminaComunasMad($hash){
			$this->db->where('sha1(id)', $hash);
			if($this ->db->delete('comunas_mad')){
				return TRUE;
			}
			return FALSE;
		}

	/***** TIPOS *****/

		public function getTiposMadList(){
			$this->db->select(
				"sha1(r.id) as hash,
				r.*,
			");

			$this->db->order_by('r.tipo', 'desc');
			$res = $this->db->get('mad_tipos as r');
			return $res->result_array();
		}

		public function getDataTiposMad($hash){
			$this->db->select('
				sha1(r.id) as hash,
				r.*,
			');
			$this->db->where('sha1(r.id)', $hash);
			$res=$this->db->get('mad_tipos as r');
			return $res->result_array();
		}

		public function formActualizarTipo($id,$data){
			$this->db->where('sha1(id)', $id);
			if($this->db->update('mad_tipos', $data)){
				
				return TRUE;
			}
			return FALSE;
		}

		public function formIngresoTipo($data){
			if($this->db->insert('mad_tipos', $data)){
				return $this->db->insert_id();
			}
			return FALSE;
		} 
		
		public function eliminaTiposMad($hash){
			$this->db->where('sha1(id)', $hash);
			if($this ->db->delete('mad_tipos')){
				return TRUE;
			}
			return FALSE;
		}

		/***** TIPOS *****/

		public function getMotivosMadList(){
			$this->db->select(
				"sha1(r.id) as hash,
				r.*,
				t.tipo as tipo,
			");
			$this->db->join('mad_tipos t','r.id_tipo = t.id','left');
			$this->db->order_by('r.id_tipo,r.motivo', 'desc');
			$res = $this->db->get('mad_motivos as r');
			return $res->result_array();
		}

		public function getDataMotivosMad($hash){
			$this->db->select('
				sha1(r.id) as hash,
				r.*,
			');
			$this->db->where('sha1(r.id)', $hash);
			$res=$this->db->get('mad_motivos as r');
			return $res->result_array();
		}

		public function formActualizarMotivo($id,$data){
			$this->db->where('sha1(id)', $id);
			if($this->db->update('mad_motivos', $data)){
				
				return TRUE;
			}
			return FALSE;
		}

		public function formIngresoMotivo($data){
			if($this->db->insert('mad_motivos', $data)){
				return $this->db->insert_id();
			}
			return FALSE;
		} 
		
		public function eliminaMotivosMad($hash){
			$this->db->where('sha1(id)', $hash);
			if($this ->db->delete('mad_motivos')){
				return TRUE;
			}
			return FALSE;
		}

}