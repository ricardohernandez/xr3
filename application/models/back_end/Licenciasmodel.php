
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Licenciasmodel extends CI_Model {

		public function __construct(){
		parent::__construct();
	}

	public function insertarVisita($data){
	  if($this->db->insert('aplicaciones_visitas', $data)){
			return TRUE;
		}
		return FALSE;
	}


	public function getLicenciasList($inactivos){
		$this->db->select('ul.*,
			sha1(ul.id) as hash_id,
			u.id as id_usuario,
			u.rut as rut,
			u.empresa as empresa,
			CONCAT(u.nombres," " ,u.apellidos) as "usuario",
			CONCAT(us.nombres," ",us.apellidos) as "digitador",
			ul.id_digitador as id_digitador,
			if(ul.fecha_registro!="1970-01-01" and ul.fecha_registro!="0000-00-00",DATE_FORMAT(ul.fecha_registro,"%d-%m-%Y"),"") as "fecha_registro",
			if(ul.fecha_inicio!="1970-01-01" and ul.fecha_inicio!="0000-00-00",DATE_FORMAT(ul.fecha_inicio,"%d-%m-%Y"),"") as "fecha_inicio",
			if(ul.fecha_termino!="1970-01-01" and ul.fecha_termino!="0000-00-00",DATE_FORMAT(ul.fecha_termino,"%d-%m-%Y"),"") as "fecha_termino",
			CONCAT(u2.nombres," ",u2.apellidos) as "jefe",
			ult.id as id_tipo,
			ult.tipo as tipo_licencia,
			CASE 
	          WHEN DATEDIFF((date(ul.fecha_termino)), NOW()) > 0 THEN "si"
	          WHEN DATEDIFF((date(ul.fecha_termino)), NOW()) < 0 THEN "no"
	        END AS vigencia

			');
		$this->db->join('usuarios_licencias as ul', 'ul.id_usuario = u.id', 'left');
		$this->db->join('usuarios_licencias_tipos as ult', 'ult.id = ul.id_tipo_licencia', 'left');
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

	public function getCorreoJefeUsuario($id_jefe,$empresa){
		$this->db->select('rut_jefatura');
		$this->db->where('id', $id_jefe);
		$res=$this->db->get('mantenedor_nombrejefatura');
		$row=$res->row_array();

		$this->db->select('correo');
		$this->db->where('rut', $row["rut_jefatura"]);
		$this->db->where('empresa', $empresa);
		$res2=$this->db->get('usuarios');
		if($res2->num_rows()>0){
			$row2=$res2->row_array();
			return $row2["correo"];
		}
		return FALSE;
	}


	public function getDataRegistro($hash){
			$this->db->select('ul.*,
			sha1(ul.id) as hash_id,
			u.id as id_usuario,
			u.rut as rut,
			u.empresa as empresa,
			ul.id_digitador as id_digitador,
			if(ul.fecha_registro!="1970-01-01" and ul.fecha_registro!="0000-00-00",DATE_FORMAT(ul.fecha_registro,"%d-%m-%Y"),"") as "fecha_registro",
			if(ul.fecha_inicio!="1970-01-01" and ul.fecha_inicio!="0000-00-00",DATE_FORMAT(ul.fecha_inicio,"%d-%m-%Y"),"") as "fecha_inicio",
			if(ul.fecha_termino!="1970-01-01" and ul.fecha_termino!="0000-00-00",DATE_FORMAT(ul.fecha_termino,"%d-%m-%Y"),"") as "fecha_termino",
			ult.id as id_tipo,
			ult.tipo as tipo_licencia
			');
		$this->db->join('usuarios_licencias as ul', 'ul.id_usuario = u.id', 'left');
		$this->db->join('usuarios_licencias_tipos as ult', 'ult.id = ul.id_tipo_licencia', 'left');
		$this->db->join('usuarios as us', 'us.id = ul.id_digitador', 'left');
		$this->db->where('sha1(ul.id)', $hash);
		$res=$this->db->get('usuarios as u');
		return $res->result_array();
	}



	public function formActualizar($id,$data){
		$this->db->where('sha1(id)', $id);
	    if($this->db->update('usuarios_licencias', $data)){
	    	return TRUE;
	    }
	    return FALSE;
	}

	public function formIngreso($data){
		if($this->db->insert('usuarios_licencias', $data)){
			return $this->db->insert_id();
		}
		return FALSE;
	} 
	
	public function eliminaLicencias($hash){
		$this->db->where('sha1(id)', $hash);
		if($this ->db->delete('usuarios_licencias')){
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

	public function listaTiposLicencias(){
		$this->db->order_by('tipo', 'asc');
		$res=$this->db->get('usuarios_licencias_tipos');
		return $res->result_array();
	}
}