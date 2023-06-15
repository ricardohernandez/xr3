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

	public function listaReporteChecklist($desde, $hasta, $supervisor,$area) {
		$this->db->select('CONCAT(u.nombres," ",u.apellidos) as lider, COUNT(c.id) AS suma_hfc');
		$this->db->join('usuarios u', 'u.id = c.auditor_id');
	
		if (!empty($supervisor)) {
			$this->db->where('c.auditor_id', $supervisor);
		}

		if (!empty($area)) {
			$this->db->where('u.id_area', $area);
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

		if (!empty($area)) {
			$this->db->where('u.id_area', $area);
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

		if (!empty($area)) {
			$this->db->where('u.id_area', $area);
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
	

	public function graficoReporteChecklist($desde, $hasta, $supervisor,$zona) {
 
		$this->db->select("
				CONCAT(u.nombres, ' ', u.apellidos) as lider,
				(
					SELECT COUNT(c.id) AS suma_hfc
					FROM checklist_hfc c
					LEFT JOIN usuarios us ON c.auditor_id = us.id
					WHERE us.id = u.id
					" . (!empty($supervisor) ? "AND c.auditor_id = '$supervisor'" : "") . "
					" . (!empty($zona) ? "AND us.id_area = '$zona'" : "") . "
					" . (!empty($desde) && !empty($hasta) ? "AND c.fecha BETWEEN '$desde' AND '$hasta'" : "") . "
				) as suma_hfc,
			
				CONCAT(u.nombres, ' ', u.apellidos) as lider,
				(
					SELECT COUNT(c.id) AS suma_herramientas
					FROM checklist_herramientas c
					LEFT JOIN usuarios us ON c.auditor_id = us.id
					WHERE us.id = u.id
					" . (!empty($supervisor) ? "AND c.auditor_id = '$supervisor'" : "") . "
					" . (!empty($zona) ? "AND us.id_area = '$zona'" : "") . "
					" . (!empty($desde) && !empty($hasta) ? "AND c.fecha BETWEEN '$desde' AND '$hasta'" : "") . "
				) as suma_herramientas,
			
				CONCAT(u.nombres, ' ', u.apellidos) as lider,
				(
					SELECT COUNT(c.id) AS suma_ftth
					FROM checklist_ftth c
					LEFT JOIN usuarios us ON c.auditor_id = us.id
					WHERE us.id = u.id
					" . (!empty($supervisor) ? "AND c.auditor_id = '$supervisor'" : "") . "
					" . (!empty($zona) ? "AND us.id_area = '$zona'" : "") . "
					" . (!empty($desde) && !empty($hasta) ? "AND c.fecha BETWEEN '$desde' AND '$hasta'" : "") . "
				) as suma_ftth
			");
			
			
		$this->db->where('u.estado', "1");
		$this->db->where('u.id_perfil=3');
		
		$this->db->order_by('u.nombres', 'asc');
			$res = $this->db->get('usuarios u');
			$cabeceras = array("Lider","Suma de CHL",array('role'=> 'annotation'),"Suma de HFC",array('role'=> 'annotation'),"Suma de FTTH",array('role'=> 'annotation'));
			$array = array();
			$array[] = $cabeceras;
			$contador=0;

			foreach($res->result_array() as $key) {
				$temp = array();
				$lider = (string)$key["lider"];
				if ($key["suma_herramientas"] != 0 || $key["suma_hfc"] != 0 || $key["suma_ftth"] != 0) {
					$temp[] = $lider;
					$temp[] = ($key["suma_herramientas"] != 0) ? (int)$key["suma_herramientas"] : 0;
					$temp[] = ($key["suma_herramientas"] != 0) ? (int)$key["suma_herramientas"] : 0;
					$temp[] = ($key["suma_hfc"] != 0) ? (int)$key["suma_hfc"] : 0;
					$temp[] = ($key["suma_hfc"] != 0) ? (int)$key["suma_hfc"] : 0;
					$temp[] = ($key["suma_ftth"] != 0) ? (int)$key["suma_ftth"] : 0;
					$temp[] = ($key["suma_ftth"] != 0) ? (int)$key["suma_ftth"] : 0;
					$array[] = $temp;
				}
			}
			
			return $array;
	
	}
	
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

	public function listaAreas(){
		$this->db->order_by('area', 'asc');
		$res=$this->db->get("usuarios_areas");
		if($res->num_rows()>0){
			return $res->result_array();
		}
		return FALSE;
	}

	

	


}