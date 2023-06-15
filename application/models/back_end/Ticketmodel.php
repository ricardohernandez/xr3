<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ticketmodel extends CI_Model {

		public function __construct(){
		parent::__construct();
	}

	public function insertarVisita($data){
	  if($this->db->insert('aplicaciones_visitas', $data)){
			return TRUE;
		}
		return FALSE;
	}
	
	public function getTicketList($estado){
		$this->db->select('t.*,
			sha1(t.id) as hash,
			CONCAT(us.nombres," " ,us.apellidos) as "digitador",
			CONCAT(us2.nombres," ",us2.apellidos) as "usuario_respuesta",
			tk.tipo as tipo,
			tk.id as id_tipo,
			if(t.fecha_ingreso!="1970-01-01" and t.fecha_ingreso!="0000-00-00",DATE_FORMAT(t.fecha_ingreso,"%d-%m-%Y"),"") as "fecha_ingreso",
			if(t.fecha_respuesta!="1970-01-01" and t.fecha_respuesta!="0000-00-00",DATE_FORMAT(t.fecha_respuesta,"%d-%m-%Y"),"") as "fecha_respuesta",
			');
		$this->db->join('ticket_tipos as tk', 'tk.id = t.tipo', 'left');
		$this->db->join('usuarios as us', 'us.id = t.id_usuario', 'left');
		$this->db->join('usuarios as us2', 'us2.id = t.id_respuesta', 'left');
		if($estado!=""){
			$this->db->where('t.estado', $estado);
		}
		$this->db->order_by('t.fecha_ingreso', 'desc');
		$res=$this->db->get('ticket as t');
		return $res->result_array();
	}

	
	public function getDataTicket($hash){
		$this->db->select('t.*,
			sha1(t.id) as hash,
			CONCAT(us.nombres," " ,us.apellidos) as "digitador",
			tk.tipo as tipo,
			tk.id as id_tipo,
			CONCAT(us2.nombres," ",us2.apellidos) as "usuario_respuesta",
			if(t.fecha_ingreso!="1970-01-01" and t.fecha_ingreso!="0000-00-00",DATE_FORMAT(t.fecha_ingreso,"%d-%m-%Y"),"") as "fecha_ingreso",
			if(t.fecha_respuesta!="1970-01-01" and t.fecha_respuesta!="0000-00-00",DATE_FORMAT(t.fecha_respuesta,"%d-%m-%Y"),"") as "fecha_respuesta",
			');
		$this->db->join('ticket_tipos as tk', 'tk.id = t.tipo', 'left');
		$this->db->join('usuarios as us', 'us.id = t.id_usuario', 'left');
		$this->db->join('usuarios as us2', 'us2.id = t.id_respuesta', 'left');
		$this->db->where('sha1(t.id)', $hash);
		$res=$this->db->get('ticket as t');
		return $res->result_array();
	
	}

	public function formActualizar($id,$data){
		$this->db->where('sha1(id)', $id);
	    if($this->db->update('ticket', $data)){
	    	
	    	return TRUE;
	    }
	    return FALSE;
	}

	public function formIngreso($data){
		if($this->db->insert('ticket', $data)){
			return $this->db->insert_id();
		}
		return FALSE;
	} 
	
	public function eliminaTicket($hash){
		$this->db->where('sha1(id)', $hash);
		if($this ->db->delete('ticket')){
		  	return TRUE;
		}
		return FALSE;
	}

	public function listaTipos(){
		$this->db->order_by('tipo', 'asc');
		$res=$this->db->get('ticket_tipos');
		return $res->result_array();
	}
}