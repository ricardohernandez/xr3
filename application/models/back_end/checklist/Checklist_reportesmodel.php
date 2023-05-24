<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Checklist_reportesmodel extends CI_Model {

		public function __construct(){
		parent::__construct();
	}

	public function insertarVisita($data){
	  if($this->db->insert('aplicaciones_visitas', $data)){
			return TRUE;
		}
		return FALSE;
	}

	public function listaReporteChecklist($desde, $hasta, $supervisor) {
		$this->db->select('CONCAT(u.nombres," ",u.apellidos) as lider, COUNT(c.id) AS suma_hfc');
		$this->db->join('usuarios u', 'u.id = c.auditor_id');
	
		if (!empty($supervisor)) {
			$this->db->where('c.auditor_id', $supervisor);
		}
	
		if (!empty($desde) && !empty($hasta)) {
			$this->db->where('c.fecha >=', $desde);
			$this->db->where('c.fecha <=', $hasta);
		}
	
		$this->db->group_by('c.auditor_id');
		$query = $this->db->get('checklist_hfc c');
		$result['checklist_hfc'] = $query->result();
	
		$this->db->select('CONCAT(u.nombres," ",u.apellidos) as lider, COUNT(c.id) AS suma_herramientas');
		$this->db->join('usuarios u', 'u.id = c.auditor_id');
	
		if (!empty($supervisor)) {
			$this->db->where('c.auditor_id', $supervisor);
		}
	
		if (!empty($desde) && !empty($hasta)) {
			$this->db->where('c.fecha >=', $desde);
			$this->db->where('c.fecha <=', $hasta);
		}
	
		$this->db->group_by('c.auditor_id');
		$query = $this->db->get('checklist_herramientas c');
		$result['checklist_herramientas'] = $query->result();
	
		$this->db->select('CONCAT(u.nombres," ",u.apellidos) as lider, COUNT(c.id) AS suma_ftth');
		$this->db->join('usuarios u', 'u.id = c.auditor_id');
	
		if (!empty($supervisor)) {
			$this->db->where('c.auditor_id', $supervisor);
		}
	
		if (!empty($desde) && !empty($hasta)) {
			$this->db->where('c.fecha >=', $desde);
			$this->db->where('c.fecha <=', $hasta);
		}
		
		$this->db->group_by('c.auditor_id');
		$query = $this->db->get('checklist_ftth c');
		$result['checklist_ftth'] = $query->result();
	
		// Crear el nuevo array con las cantidades para cada líder en el formato requerido
		$reporte = array();
		foreach ($result['checklist_hfc'] as $item) {
			$lider = $item->lider;
			$reporte[] = array(
				'lider' => $lider,
				'suma_hfc' => $item->suma_hfc,
				'suma_herramientas' => 0, // Inicializar con 0
				'suma_ftth' => 0 // Inicializar con 0
			);
		}
	
		foreach ($result['checklist_herramientas'] as $item) {
			$lider = $item->lider;
			$key = array_search($lider, array_column($reporte, 'lider'));
			if ($key !== false) {
				$reporte[$key]['suma_herramientas'] = $item->suma_herramientas;
			} else {
				$reporte[] = array(
					'lider' => $lider,
					'suma_hfc' => 0, // Inicializar con 0
					'suma_herramientas' => $item->suma_herramientas,
					'suma_ftth' => 0 // Inicializar con 0
				);
			}
		}
	
		foreach ($result['checklist_ftth'] as $item) {
			$lider = $item->lider;
			$key = array_search($lider, array_column($reporte, 'lider'));
			if ($key !== false) {
				$reporte[$key]['suma_ftth'] = $item->suma_ftth;
			} else {
				$reporte[] = array(
					'lider' => $lider,
					'suma_hfc' => 0, // Inicializar con 0
					'suma_herramientas' => 0, // Inicializar con 0
					'suma_ftth' => $item->suma_ftth
				);
			}
		}
	
		return ($reporte);
	}
	

	public function graficoReporteChecklist($desde, $hasta, $supervisor) {
	/* 	$desde_str= date('d-m', strtotime($desde));
		$hasta_str= date('d-m', strtotime($hasta));
		$desde_c = date('Y-m-d', strtotime('+1 month', strtotime($desde)));
		$mes_str= mesesCorto(date('m', strtotime($desde_c)));
		$anio_str= date('Y', strtotime($desde));
		$periodo_str= $mes_str."-".$anio_str; */


		$this->db->select("
				CONCAT(u.nombres, ' ', u.apellidos) as lider,
				(
					SELECT COUNT(c.id) AS suma_hfc
					FROM checklist_hfc c
					LEFT JOIN usuarios us ON c.auditor_id = us.id
					WHERE us.id = u.id
					" . (!empty($supervisor) ? "AND c.auditor_id = '$supervisor'" : "") . "
					" . (!empty($desde) && !empty($hasta) ? "AND c.fecha BETWEEN '$desde' AND '$hasta'" : "") . "
				) as suma_hfc,
			
				CONCAT(u.nombres, ' ', u.apellidos) as lider,
				(
					SELECT COUNT(c.id) AS suma_herramientas
					FROM checklist_herramientas c
					LEFT JOIN usuarios us ON c.auditor_id = us.id
					WHERE us.id = u.id
					" . (!empty($supervisor) ? "AND c.auditor_id = '$supervisor'" : "") . "
					" . (!empty($desde) && !empty($hasta) ? "AND c.fecha BETWEEN '$desde' AND '$hasta'" : "") . "
				) as suma_herramientas,
			
				CONCAT(u.nombres, ' ', u.apellidos) as lider,
				(
					SELECT COUNT(c.id) AS suma_ftth
					FROM checklist_ftth c
					LEFT JOIN usuarios us ON c.auditor_id = us.id
					WHERE us.id = u.id
					" . (!empty($supervisor) ? "AND c.auditor_id = '$supervisor'" : "") . "
					" . (!empty($desde) && !empty($hasta) ? "AND c.fecha BETWEEN '$desde' AND '$hasta'" : "") . "
				) as suma_ftth
			");
			
			
		$this->db->where('u.estado', "1");
		$this->db->where('u.id_perfil=3');
		
		$this->db->order_by('u.nombres', 'asc');
			$res = $this->db->get('usuarios u');
			$cabeceras = array("Lider","CHL",array('role'=> 'annotation'),"CHFC",array('role'=> 'annotation'),"CFTTH",array('role'=> 'annotation'));
			$array = array();
			$array[] = $cabeceras;
			$contador=0;

			foreach($res->result_array() as $key){
				$temp=array();
				$temp[] = (string)$key["lider"];
				$temp[] = (int)$key["suma_herramientas"];
				$temp[] = (int)$key["suma_herramientas"];
				$temp[] = (int)$key["suma_hfc"];
				$temp[] = (int)$key["suma_hfc"];
				$temp[] = (int)$key["suma_ftth"];
				$temp[] = (int)$key["suma_ftth"];
	
				$array[]=$temp;
			}
			return $array;
	
	}
	
	
	/* public function graficoTotalItems($tecnico,$zona,$comuna,$item){

		$this->db->select("
			cl.descripcion as descripcion,

			SUM(CASE 
			 WHEN cd.estado = 'si' THEN 1
			 ELSE 0
			END) AS cantidad_ok,
			SUM(CASE 
			 WHEN cd.estado = 'no' THEN 1
			 ELSE 0
			END) AS cantidad_nook,
			
			");

 		$this->db->group_by('cl.descripcion');
		$this->db->order_by('cantidad_nook', 'desc');
		$this->db->join('ast_checklist_listado cl', 'cd.id_check = cl.id', 'left');	
		$this->db->join('ast_checklist c', 'c.id = cd.id_ast', 'left');	
		$this->db->join('usuarios u', 'u.id = c.tecnico_id', 'left');


		if(!empty($tecnico)){
			$this->db->where('c.tecnico_id', $tecnico);
		}

		if(!empty($zona)){
			$this->db->where('u.id_area', $zona);
		}

		if(!empty($comuna)){
			$this->db->where('u.id_proyecto', $comuna);
		}

		if(!empty($item)){
			$this->db->where('cl.descripcion', $item);
		}

		$res = $this->db->get('ast_checklist_detalle cd');

		$cabeceras = array("Descripcion","Ok",array('role'=> 'annotation'),"No ok",array('role'=> 'annotation'));
		$array = array();
		$array[] = $cabeceras;
		$contador=0;

		foreach($res->result_array() as $key){
			$temp=array();
			$temp[] = (string)$key["descripcion"];
			$temp[] = (int)$key["cantidad_ok"];
			$temp[] = (int)$key["cantidad_ok"];
			$temp[] = (int)$key["cantidad_nook"];
			$temp[] = (int)$key["cantidad_nook"];
			$array[]=$temp;
		}
		return $array;
	} */


		
	public function listaSupervisores(){
		$this->db->select("id,CONCAT(nombres,'  ',apellidos) as 'nombre_completo'");
		$this->db->where('u.estado', "1");
		$this->db->where('id_perfil=3');
		$this->db->order_by('u.nombres', 'asc');
		$res=$this->db->get("usuarios u");
		if($res->num_rows()>0){
			return $res->result_array();
		}
		return FALSE;
	}

	


}