<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vacacionesmodel extends CI_Model {

	public function __construct(){
		parent::__construct();
	}

	public function insertarVisita($data){
	  if($this->db->insert('aplicaciones_visitas', $data)){
			return TRUE;
		}
		return FALSE;
	}


	public function getVacacionesList($inactivos){
		$this->db->select('ul.*,
			sha1(ul.id) as hash_id,
			u.id as id_usuario,
			u.rut as rut,
			CONCAT(u.nombres," " ,u.apellidos) as "usuario",
			CONCAT(us.nombres," ",us.apellidos) as "digitador",
			ul.id_digitador as id_digitador,
			if(ul.fecha_registro!="1970-01-01" and ul.fecha_registro!="0000-00-00",DATE_FORMAT(ul.fecha_registro,"%d-%m-%Y"),"") as "fecha_registro",
			if(ul.fecha_inicio!="1970-01-01" and ul.fecha_inicio!="0000-00-00",DATE_FORMAT(ul.fecha_inicio,"%d-%m-%Y"),"") as "fecha_inicio",
			if(ul.fecha_termino!="1970-01-01" and ul.fecha_termino!="0000-00-00",DATE_FORMAT(ul.fecha_termino,"%d-%m-%Y"),"") as "fecha_termino",
			CONCAT(u2.nombres," ",u2.apellidos) as "jefe",
			CASE 
	          WHEN DATEDIFF((date(ul.fecha_termino)), NOW()) > 0 THEN "si"
	          WHEN DATEDIFF((date(ul.fecha_termino)), NOW()) < 0 THEN "no"
	        END AS vigencia
			');
		$this->db->join('usuarios_vacaciones as ul', 'ul.id_usuario = u.id', 'left');
		$this->db->join('usuarios as us', 'us.id = ul.id_digitador', 'left');
		$this->db->join('usuarios_jefes uj', 'uj.id = u.id_jefe', 'left');
		$this->db->join('usuarios u2', 'u2.id = uj.id_jefe', 'left');

		$this->db->order_by('u.nombres', 'asc');
		$this->db->where('ul.id_usuario is not null');

		if($inactivos!="on"){
			$this->db->where('u.estado', "1");
		}
		
		$res=$this->db->get('usuarios as u');
		return $res->result_array();
	}

	
	public function getDataRegistro($hash){
			$this->db->select('ul.*,
			sha1(ul.id) as hash_id,
			u.id as id_usuario,
			u.rut as rut,
			ul.id_digitador as id_digitador,
			if(ul.fecha_registro!="1970-01-01" and ul.fecha_registro!="0000-00-00",DATE_FORMAT(ul.fecha_registro,"%d-%m-%Y"),"") as "fecha_registro",
			if(ul.fecha_inicio!="1970-01-01" and ul.fecha_inicio!="0000-00-00",DATE_FORMAT(ul.fecha_inicio,"%d-%m-%Y"),"") as "fecha_inicio",
			if(ul.fecha_termino!="1970-01-01" and ul.fecha_termino!="0000-00-00",DATE_FORMAT(ul.fecha_termino,"%d-%m-%Y"),"") as "fecha_termino",
			');
		$this->db->join('usuarios_vacaciones as ul', 'ul.id_usuario = u.id', 'left');
		$this->db->join('usuarios as us', 'us.id = ul.id_digitador', 'left');
		$this->db->where('sha1(ul.id)', $hash);
		$res=$this->db->get('usuarios as u');
		return $res->result_array();
	}


	public function formActualizar($id,$data){
		$this->db->where('sha1(id)', $id);
	    if($this->db->update('usuarios_vacaciones', $data)){
	    	return TRUE;
	    }
	    return FALSE;
	}

	public function formIngreso($data){
		if($this->db->insert('usuarios_vacaciones', $data)){
			return $this->db->insert_id();
		}
		return FALSE;
	} 
	
	public function eliminaVacaciones($hash){
		$this->db->where('sha1(id)', $hash);
		if($this ->db->delete('usuarios_vacaciones')){
		  	return TRUE;
		}
		return FALSE;
	}


	public function listaUsuariosS2(){
		$this->db->select("concat(substr(replace(rut,'-',''),1,char_length(replace(rut,'-',''))-1),'-',substr(replace(rut,'-',''),char_length(replace(rut,'-','')))) as 'rut_format',
			rut,empresa,id,
			CONCAT(nombres,' ' ,apellidos) as nombre_completo
		");
		$this->db->where('estado',"1");
		$this->db->order_by('nombre_completo', 'asc');
		$res=$this->db->get("usuarios");
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

}