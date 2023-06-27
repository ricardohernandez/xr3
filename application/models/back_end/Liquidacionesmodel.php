<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Liquidacionesmodel extends CI_Model {

		public function __construct(){
		parent::__construct();
	}

	public function insertarVisita($data){
	  if($this->db->insert('aplicaciones_visitas', $data)){
			return TRUE;
		}
		return FALSE;
	}
	

	public function getLiquidacionesList($jefe,$trabajador,$periodo){

		$this->db->select("l.*,
				sha1(l.id) as hash,
				CONCAT(u.nombres,' ' ,u.apellidos) as 'usuario',
				CONCAT(us.nombres,' ' ,us.apellidos) as 'digitador',
				CONCAT(SUBSTRING_INDEX(u2.nombres, ' ', 1), ' ', SUBSTRING_INDEX(u2.apellidos, ' ', 1)) AS jefe ,
				CONCAT(SUBSTRING(u.rut, 1, LENGTH(u.rut) - 1), '-', RIGHT(u.rut, 1)) AS rut_usuario,
				u.empresa as empresa,
				c.cargo as cargo,
				u.comuna as plaza,
				a.area as area,
				");

		$this->db->from('liquidaciones as l');	
		$this->db->join('usuarios as u', 'l.id_usuario = u.id', 'left');
		$this->db->join('usuarios as us', 'l.id_digitador = us.id', 'left');

		$this->db->join('usuarios_jefes uj', 'uj.id = u.id_jefe', 'left');
		$this->db->join('usuarios u2', 'u2.id = uj.id_jefe', 'left');

		$this->db->join('usuarios_areas a', 'a.id = u.id_area', 'left');
		
		$this->db->join('usuarios_cargos as c', 'c.id = u.id_cargo', 'left');		
		$this->db->order_by('l.id', 'desc');

		if($this->session->userdata('id_perfil')==4){

			//$this->db->where('l.id_usuario', $this->session->userdata('id'));

		}else{

			if($jefe!=""){
				$this->db->where('u.id_jefe', $jefe);
			}
		}

		if($trabajador!=""){
			$this->db->where('l.id_usuario', $trabajador);
		}

		if($periodo!=""){
			$this->db->where('l.periodo', $periodo);
		}

		$res=$this->db->get();

		if($res->num_rows()>0){
			return $res->result_array();
		}
		return FALSE;
	}

	
	public function getDataLiquidaciones($hash){
		$this->db->select("l.*,
				sha1(l.id) as hash,
				CONCAT(u.nombres,' ' ,u.apellidos) as 'usuario',
				CONCAT(us.nombres,' ' ,us.apellidos) as 'digitador',
				CONCAT(SUBSTRING(u.rut, 1, LENGTH(u.rut) - 1), '-', RIGHT(u.rut, 1)) AS rut_usuario,
				u.empresa as empresa,
				CONCAT(SUBSTRING('04-2023', 4), '-', SUBSTRING('04-2023', 1, 2)) AS periodo,
				c.cargo as cargo,
				");
		$this->db->from('liquidaciones as l');	
		$this->db->join('usuarios as u', 'l.id_usuario = u.id', 'left');
		$this->db->join('usuarios as us', 'l.id_digitador = us.id', 'left');
		$this->db->join('usuarios_cargos as c', 'c.id = u.id_cargo', 'left');		
		$this->db->where('sha1(l.id)', $hash);
		$res=$this->db->get();
		return $res->result_array();
	
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

		$this->db->order_by('nombre_jefe', 'asc');
		$res=$this->db->get('usuarios_jefes uj');
		if($res->num_rows()>0){
			return $res->result_array();
		}
		return FALSE;
	}

	
	public function listaTrabajadores($jefe){
		
		$this->db->select("concat(substr(replace(rut,'-',''),1,char_length(replace(rut,'-',''))-1),'-',substr(replace(rut,'-',''),char_length(replace(rut,'-','')))) as 'rut_format',
			empresa,id,rut,
			CONCAT(nombres,'  ',apellidos) as 'nombre_completo',
			CONCAT(SUBSTRING_INDEX(nombres, ' ', '1'),'  ',SUBSTRING_INDEX(SUBSTRING_INDEX(apellidos, ' ', '-2'), ' ', '1')) as 'nombre_corto'");
		
		if($this->session->userdata('id_perfil')==4){
			$this->db->where('rut', $this->session->userdata('rut'));
		}else{

			if($jefe!=""){
				$this->db->where('id_jefe', $jefe);
			}
		}

		$this->db->order_by('nombres', 'asc');
		$res = $this->db->get("usuarios");

		if($res->num_rows()>0){
			$array=array();
			foreach($res->result_array() as $key){
				$temp=array();
				$temp["id"]=$key["id"];
				$temp["text"]=$key["rut_format"]."  |  ".$key["nombre_completo"];
				$array[]=$temp;
			}
			return json_encode($array);
		}
		return FALSE;
	}

	public function getMisLiquidaciones($id){
		$this->db->select('l.id as id,
			l.archivo as archivo,
			l.carpeta as carpeta,
			l.periodo as periodo');	
		$this->db->from('liquidaciones as l');
		$this->db->where('id_usuario', $id);
		$this->db->order_by('id', 'desc');
		$res=$this->db->get();
		if($res->num_rows()>0){
			return $res->result_array();
		}
	}


	public function formActualizar($id,$data){
		$this->db->where('sha1(id)', $id);
	    if($this->db->update('liquidaciones', $data)){
	    	
	    	return TRUE;
	    }
	    return FALSE;
	}

	public function formIngreso($data){
		if($this->db->insert('liquidaciones', $data)){
			return $this->db->insert_id();
		}
		return FALSE;
	} 
	
	public function eliminaLiquidaciones($hash){
		$this->db->where('sha1(id)', $hash);
		if($this ->db->delete('liquidaciones')){
		  	return TRUE;
		}
		return FALSE;
	}

	public function ingresarLiquidacion($data){
		if($this->db->insert('liquidaciones', $data)){
			return TRUE;
		}
		return FALSE;
	} 

	public function actualizarLiquidacion($id,$data){
		$this->db->where('id', $id);
	    if($this->db->update('liquidaciones', $data)){
	    	return TRUE;
	    }
	    return FALSE;
	}

	public function existeLiqu($archivo){
		$this->db->select('id');	
		$this->db->from('liquidaciones');
		$this->db->where('nombre_archivo', $archivo);
		$res=$this->db->get();
		if($res->num_rows()>0){
			$row=$res->row_array();
			return $row["id"];
		}
		return FALSE;
	}

	
	public function getAreaUsuario($id){
		$this->db->select('a.area as area');
		$this->db->join('usuarios_areas as a', 'a.id = u.id_area', 'left');
		$this->db->where('(u.id)', $id);
		$res=$this->db->get('usuarios as u');
		
		$row=$res->row_array();
		return $row["area"];
	}

	public function getRutUsuario($id){
		$this->db->select('rut');
		$this->db->where('(id)', $id);
		$res=$this->db->get('usuarios');
		$row=$res->row_array();
		return $row["rut"];
	}

	public function getUsuarioRut($rut){
		$this->db->select('u.id');
		$this->db->where('u.rut', $rut);
		$this->db->from('usuarios as u');
		$res=$this->db->get();
		if($res->num_rows()>0){
			$row=$res->row_array();
			return $row["id"];
		}
		return FALSE;
	}

 
}