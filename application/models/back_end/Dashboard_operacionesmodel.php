<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard_operacionesmodel extends CI_Model {

	public function __construct(){
		parent::__construct();
	}
	
	public function insertarVisita($data){
		if($this->db->insert('aplicaciones_visitas', $data)){
			return TRUE;
		}
		return FALSE;
	}

	/**********PRODUCTIVIDAD XR3************/

		public function getDataProductividad($tipo,$mes_inicio,$mes_termino) {
			
			$campos = $tipo['campos'];
			$cabeceras = $tipo['cabeceras'];
		
			$this->db->select("fecha," . implode(",", $campos));
	
			if($mes_inicio!=""){
				$this->db->where('fecha >=', date('Y-m-01', strtotime($mes_inicio)));
				$this->db->where('fecha <=', date('Y-m-t', strtotime($mes_termino)));
			}else{
				$this->db->where('fecha >=', date('Y-m-01', strtotime('January')));
			}

			$res = $this->db->get('dashboard_productividad');

			$array = [];
			$array[] = $cabeceras;
		
			foreach ($res->result_array() as $key) {
				$temp = [];
				$temp[] = mesesCorto(date("n", strtotime($key["fecha"]))) . "-" . date("y", strtotime($key["fecha"]));
				
				foreach ($campos as $campo) {
					$temp[] = ($key[$campo] != 0) ? (float)$key[$campo] : 0;
					$temp[] = ($key[$campo] != 0) ? (float)$key[$campo] : 0;
				}
				
				$temp[] = strtotime($key["fecha"]);
				$temp[] = "productividad";
				$array[] = $temp;
			}
		
			return $array;
		}

		public function getDataCalidad($tipo,$mes_inicio,$mes_termino) {
			$campos = $tipo['campos'];
			$cabeceras = $tipo['cabeceras'];
		
			$this->db->select("fecha," . implode(",", $campos));
	
			if($mes_inicio!=""){
				$this->db->where('fecha >=', date('Y-m-01', strtotime($mes_inicio)));
				$this->db->where('fecha <=', date('Y-m-t', strtotime($mes_termino)));
			}else{
				$this->db->where('fecha >=', date('Y-m-01', strtotime('January')));
			}
			
			$this->db->group_by('fecha');
			
			$res = $this->db->get('dashboard_calidad');

			$array = [];
			$array[] = $cabeceras;
		
			foreach ($res->result_array() as $key) {
				$temp = [];
				$temp[] = mesesCorto(date("n", strtotime($key["fecha"]))) . "-" . date("y", strtotime($key["fecha"]));
				
				foreach ($campos as $campo) {
					$temp[] = ($key[$campo] != 0) ? (float)$key[$campo] : 0;
					$temp[] = ($key[$campo] != 0) ? (float)$key[$campo] : 0;
				}
				
				$temp[] = strtotime($key["fecha"]);
				$temp[] = "calidad";
				
				$array[] = $temp;
			}
		
			return $array;
		}

		public function getDataProductividadEPS($tipo,$mes_inicio,$mes_termino) {
			
			$campos = $tipo['campos'];
			$cabeceras = $tipo['cabeceras'];
		
			$this->db->select("fecha," . implode(",", $campos));
	
			if($mes_inicio!=""){
				$this->db->where('fecha >=', date('Y-m-01', strtotime($mes_inicio)));
				$this->db->where('fecha <=', date('Y-m-t', strtotime($mes_termino)));
			}else{
				$this->db->where('fecha >=', date('Y-m-01', strtotime('January')));
			}

			$res = $this->db->get('dashboard_productividad');

			$array = [];
			$array[] = $cabeceras;
		
			foreach ($res->result_array() as $key) {
				$temp = [];
				$temp[] = mesesCorto(date("n", strtotime($key["fecha"]))) . "-" . date("y", strtotime($key["fecha"]));
				
				foreach ($campos as $campo) {
					$temp[] = ($key[$campo] != 0) ? (float)$key[$campo] : 0;
				}
				$temp[] = "prod";
				$array[] = $temp;
			}
		
			return $array;
		}



		public function getDataCalidadEPS($tipo,$mes_inicio,$mes_termino) {
			$campos = $tipo['campos'];
			$cabeceras = $tipo['cabeceras'];
		
			$this->db->select("fecha," . implode(",", $campos));
	
			if($mes_inicio!=""){
				$this->db->where('fecha >=', date('Y-m-01', strtotime($mes_inicio)));
				$this->db->where('fecha <=', date('Y-m-t', strtotime($mes_termino)));
			}else{
				$this->db->where('fecha >=', date('Y-m-01', strtotime('January')));
			}

			$res = $this->db->get('dashboard_calidad');

			$array = [];
			$array[] = $cabeceras;
		
			foreach ($res->result_array() as $key) {
				$temp = [];
				$temp[] = mesesCorto(date("n", strtotime($key["fecha"]))) . "-" . date("y", strtotime($key["fecha"]));
				
				foreach ($campos as $campo) {
					$temp[] = ($key[$campo] != 0) ? (float)$key[$campo] : 0;
				}
				$temp[] = "calidad";
				$array[] = $temp;
			}
		
			return $array;
		}
		

		public function getDataDotacion($mes_inicio,$mes_termino,$tipo) {
			if($mes_inicio!=""){
				$this->db->where('fecha >=', date('Y-m-01', strtotime($mes_inicio)));
				$this->db->where('fecha <=', date('Y-m-t', strtotime($mes_termino)));
			}else{
				$this->db->where('fecha >=', date('Y-m-01', strtotime('January')));
			}

			$res = $this->db->get('dashboard_dotacion');
			$array = [];

			if($tipo!=""){

				if($tipo=="norte"){

					$cabeceras = [
						"mes",
						"Promedio Norte", ['role' => 'annotation'], 
						['role' => 'annotationText']
					];

				}elseif($tipo=="sur"){

					$cabeceras = [
						"mes",
						"Promedio Sur", ['role' => 'annotation'], 
						['role' => 'annotationText']
					];

				}elseif($tipo=="fte"){

					$cabeceras = [
						"mes",
						"FTE Sur", ['role' => 'annotation'], 
						"FTE Norte", ['role' => 'annotation'], 
						['role' => 'annotationText']
					];

				}

			}else{

				$cabeceras = ["mes", 
					"Promedio Sur", ['role' => 'annotation'], 
					"Promedio Norte", ['role' => 'annotation'], 
					"Promedio Claro", ['role' => 'annotation'], 
					"FTE Sur", ['role' => 'annotation'], 
					"Total Operativo", ['role' => 'annotation'], 
					"FTE Norte", ['role' => 'annotation'], 
					"Total Mov FTE", ['role' => 'annotation'], 
					"ACC", ['role' => 'annotation'], 
					"Operativo Claro", ['role' => 'annotation'], 
					['role' => 'annotationText']];
			} 

			/* $cabeceras = ["mes", 
					"Promedio Sur", ['role' => 'annotation'], 
					"Promedio Norte", ['role' => 'annotation'], 
					"Claro", ['role' => 'annotation'], 
					"FTE Sur", ['role' => 'annotation'], 
					"Total Operativo", ['role' => 'annotation'], 
					"FTE Norte", ['role' => 'annotation'], 
					"Total Mov FTE", ['role' => 'annotation'], 
					"ACC", ['role' => 'annotation'], 
					['role' => 'annotationText']];
 			*/
			
			$array[] = $cabeceras;

			foreach ($res->result_array() as $key) {
				$temp = [];
				$temp[] = mesesCorto(date("n", strtotime($key["fecha"]))) . "-" . date("y", strtotime($key["fecha"]));
				
				if($tipo!=""){

					if($tipo=="norte"){
						
						$temp[] = ($key["promedio_norte"] != 0) ? (float)$key["promedio_norte"] : 0;
						$temp[] = ($key["promedio_norte"] != 0) ? (float)$key["promedio_norte"] : 0;
					
						/* $temp[] = 0;
						$temp[] = 0;

						$temp[] = 0;
						$temp[] = 0;

						$temp[] = 0;
						$temp[] = 0;

						$temp[] = 0;
						$temp[] = 0;

						$temp[] = 0;
						$temp[] = 0;

						$temp[] = 0;
						$temp[] = 0;

						$temp[] = 0;
						$temp[] = 0;
 						*/


					}elseif($tipo=="sur"){
	
						$temp[] = ($key["promedio_sur"] != 0) ? (float)$key["promedio_sur"] : 0;
						$temp[] = ($key["promedio_sur"] != 0) ? (float)$key["promedio_sur"] : 0;
	
					}elseif($tipo=="fte"){
	
						$temp[] = ($key["fte_sur"] != 0) ? (float)$key["fte_sur"] : 0;
						$temp[] = ($key["fte_sur"] != 0) ? (float)$key["fte_sur"] : 0;

						$temp[] = ($key["fte_norte"] != 0) ? (float)$key["fte_norte"] : 0;
						$temp[] = ($key["fte_norte"] != 0) ? (float)$key["fte_norte"] : 0;
					}
	
				}else{
					
					$temp[] = ($key["promedio_sur"] != 0) ? (float)$key["promedio_sur"] : 0;
					$temp[] = ($key["promedio_sur"] != 0) ? (float)$key["promedio_sur"] : 0;

					$temp[] = ($key["promedio_norte"] != 0) ? (float)$key["promedio_norte"] : 0;
					$temp[] = ($key["promedio_norte"] != 0) ? (float)$key["promedio_norte"] : 0;
					
					$temp[] = ($key["promedio_claro"] != 0) ? (float)$key["promedio_claro"] : 0;
					$temp[] = ($key["promedio_claro"] != 0) ? (float)$key["promedio_claro"] : 0;
									
					$temp[] = ($key["fte_sur"] != 0) ? (float)$key["fte_sur"] : 0;
					$temp[] = ($key["fte_sur"] != 0) ? (float)$key["fte_sur"] : 0;

					$temp[] = ($key["total_operativo"] != 0) ? (float)$key["total_operativo"] : 0;
					$temp[] = ($key["total_operativo"] != 0) ? (float)$key["total_operativo"] : 0;

					$temp[] = ($key["fte_norte"] != 0) ? (float)$key["fte_norte"] : 0;
					$temp[] = ($key["fte_norte"] != 0) ? (float)$key["fte_norte"] : 0;


					$temp[] = ($key["total_mov_fte"] != 0) ? (float)$key["total_mov_fte"] : 0;
					$temp[] = ($key["total_mov_fte"] != 0) ? (float)$key["total_mov_fte"] : 0;

					$temp[] = ($key["acc"] != 0) ? (float)$key["acc"] : 0;
					$temp[] = ($key["acc"] != 0) ? (float)$key["acc"] : 0;
				
					$temp[] = ($key["claro"] != 0) ? (float)$key["claro"] : 0;
					$temp[] = ($key["claro"] != 0) ? (float)$key["claro"] : 0;

				} 

				$temp[] = strtotime($key["fecha"]);
				$array[] = $temp;
			}
		
			return $array;
		}

		public function getDataAnalisisCalidad($zona,$comuna,$supervisor,$tecnologia,$mes_inicio,$mes_termino){
			$this->db->where('fecha >=', $mes_inicio."-01");
			$this->db->where('fecha <=', date('Y-m-t', strtotime($mes_termino)));

			if($zona!=""){
				$this->db->where('zona', $zona);
			}

			if($comuna!=""){
				$this->db->where('comuna', $comuna);
			}

			if($supervisor!=""){
				$this->db->where('supervisor', $supervisor);
			}

			if($tecnologia!=""){
				$this->db->where('tecnologia', $tecnologia);
			}

			
			$res = $this->db->get('dashboard_analisis_calidad');
			$array = [];

			if ($tecnologia === "HFC") {
				$cabeceras = [
					"comuna",
					"HFC",
					['role' => 'annotation'],
				];
			} elseif ($tecnologia === "FTTH") {
				$cabeceras = [
					"comuna",
					"FTTH",
					['role' => 'annotation'],
				];
			}

			$cabeceras[] = ['role' => 'annotationText'];
			$cabeceras[] = "meta";

			$array[] = $cabeceras;

			foreach ($res->result_array() as $key) {
				$temp = [];

				if ($tecnologia === "HFC") {
					$temp[] = (string)$key["comuna"]." ".mesesCorto(date("n", strtotime($key["fecha"]))) . "-" . date("y", strtotime($key["fecha"]));
					$temp[] = ($key["hfc"] != 0) ? (float)round($key["hfc"],1) : null;
					$temp[] = ($key["hfc"] != 0) ? (float)round($key["hfc"],1) : null;

				} elseif ($tecnologia === "FTTH") {
					$temp[] = (string)$key["comuna"]." ".mesesCorto(date("n", strtotime($key["fecha"]))) . "-" . date("y", strtotime($key["fecha"]));
					$temp[] = ($key["ftth"] != 0) ? (float)round($key["ftth"],1) : null;
					$temp[] = ($key["ftth"] != 0) ? (float)round($key["ftth"],1) : null;
				}
				
				$temp[] = strtotime($key["fecha"]);
				$temp[] = ($key["meta"] != 0) ? (float)round($key["meta"],0) : NULL;
				$array[] = $temp;
			}
		
			return $array;
		}

		public function getDataAnalisisCalidadTotal($zona,$comuna,$supervisor,$mes_inicio,$mes_termino){
			$this->db->where('fecha >=', $mes_inicio."-01");
			$this->db->where('fecha <=', date('Y-m-t', strtotime($mes_termino)));

			if($zona!=""){
				$this->db->where('zona', $zona);
			}

			if($comuna!=""){
				$this->db->where('comuna', $comuna);
			}

			if($supervisor!=""){
				$this->db->where('supervisor', $supervisor);
			}

			$this->db->order_by('comuna', 'asc');
			$this->db->order_by('fecha', 'asc');
			
			$res = $this->db->get('dashboard_analisis_calidad');

			$array = [];

			$cabeceras = [
				"comuna",
				"Total",
				['role' => 'annotation'],
				['role' => 'annotationText'],
				"meta"
			];

			$array[] = $cabeceras;

			foreach ($res->result_array() as $key) {
				$temp = [];

				$temp[] = (string)$key["comuna"]." ".mesesCorto(date("n", strtotime($key["fecha"]))) . "-" . date("y", strtotime($key["fecha"]));
				$temp[] = ($key["total_general"] != 0) ? (float)round($key["total_general"],1) : NULL;
				$temp[] = ($key["total_general"] != 0) ? (float)round($key["total_general"],1) : NULL;
				$temp[] = strtotime($key["fecha"]);
				$temp[] = ($key["meta"] != 0) ? (float)round($key["meta"],1) : NULL;
				$array[] = $temp;
			}
			
			$nuevoArray = $this->eliminarElementosNull($array);

			return $nuevoArray;
		}

		function eliminarElementosNull($array) {
			$resultado = [];
		
			foreach ($array as $elemento) {
				// Verificar si los índices 1 y 2 son NULL
				if ($elemento[1] !== null && $elemento[2] !== null) {
					$resultado[] = $elemento;
				}
			}
		
			return $resultado;
		}
		
		public function graficoProdxEps($tipo,$mes_inicio,$mes_termino) {
			$campos = $tipo['campos'];
			$cabeceras = $tipo['cabeceras'];

			$this->db->select("fecha," . implode(",", $campos));
			$this->db->where('fecha >=', $mes_inicio."-01");
			$this->db->where('fecha <=', date('Y-m-t', strtotime($mes_termino)));

			$res = $this->db->get('dashboard_prod_cal_claro');
			$array = [];

			$array[] = $cabeceras;
		
			foreach ($res->result_array() as $key) {
				$temp = [];
				$temp[] =  (string)mesesCorto(date("n", strtotime($key["fecha"]))) . "-" . date("y", strtotime($key["fecha"]));
				
				foreach ($campos as $campo) {
					$temp[] = ($key[$campo] != 0) ? (float)$key[$campo] : 0;
					$temp[] = ($key[$campo] != 0) ? (float)$key[$campo] : 0;
				}
				
				$temp[] = strtotime($key["fecha"]);
				$array[] = $temp;
			}
		
			return $array;
		}

		public function graficoProdxCiudad($comuna,$supervisor,$tecnologia,$mes_inicio,$mes_termino){

			$this->db->where('fecha >=', $mes_inicio."-01");
			$this->db->where('fecha <=', date('Y-m-t', strtotime($mes_termino)));

			if($comuna!=""){
				$this->db->where('comuna', $comuna);
			}

			if($supervisor!=""){
				$this->db->where('supervisor', $supervisor);
			}

			if($tecnologia!=""){
				$this->db->where('tecnologia', $tecnologia);
			}

			$this->db->order_by('comuna', 'asc');
			$this->db->order_by('fecha', 'asc');

			$res = $this->db->get('dashboard_px_claro_ciudad');
			
			$array = [];
			$cabeceras = [
				"Mes",
				"Suma PX",['role' => 'annotation'],
				['role' => 'annotationText']
			];
			
			$array[] = $cabeceras;

			foreach ($res->result_array() as $key) {
				$temp = [];
				$temp[] = (string)mb_convert_case($key["comuna"], MB_CASE_TITLE, "UTF-8")." ".mesesCorto(date("n", strtotime($key["fecha"]))) . "-" . date("y", strtotime($key["fecha"]));
				$temp[] = ($key["px"] != 0) ? (float)$key["px"] : 0;
				$temp[] = ($key["px"] != 0) ? (float)$key["px"] : 0;
				$temp[] = strtotime($key["fecha"]);
				$array[] = $temp;
			}
		
			return $array;
		} 

		public function getDataProductividadComuna($mes_inicio,$mes_termino,$zona,$comuna,$tecnologia,$empresa){
			$this->db->where('fecha >=', $mes_inicio."-01");
			$this->db->where('fecha <=', date('Y-m-t', strtotime($mes_termino)));

			if($zona!=""){
				$this->db->where('zona', $zona);
			}

			if($comuna!=""){
				$this->db->where('comuna', $comuna);
			}

			if($tecnologia!=""){
				$this->db->where('tecnologia', $tecnologia);
			}

		 
			$this->db->order_by('comuna', 'asc');
			$this->db->order_by('fecha', 'asc');
			
			$res = $this->db->get('dashboard_comparacion_comuna');
			
			$array = [];

			if($empresa!=""){
				if($empresa=="xr3"){
					$cabeceras = [
						"comuna",
						"XR3",['role' => 'annotation']
					];
				}elseif($empresa=="emetel"){
					$cabeceras = [
						"comuna",
						"EMETEL",['role' => 'annotation']
					];
				}
			}else{

				$cabeceras = [
					"comuna",
					"XR3",['role' => 'annotation'],
					"EMETEL",['role' => 'annotation']
				];
			} 

			
			$array[] = $cabeceras;

			foreach ($res->result_array() as $key) {
				$temp = [];
				$temp[] = (string)$key["comuna"]." ".mesesCorto(date("n", strtotime($key["fecha"]))) . "-" . date("y", strtotime($key["fecha"]));

				if($empresa!=""){
					if($empresa=="xr3"){
						$temp[] = ($key["xr3_inversion"] != 0) ? (float)$key["xr3_inversion"] : NULL;
						$temp[] = ($key["xr3_inversion"] != 0) ? (float)$key["xr3_inversion"] : NULL;
					}elseif($empresa=="emetel"){
						$temp[] = ($key["emetel"] != 0) ? (float)$key["emetel"] : NULL;
						$temp[] = ($key["emetel"] != 0) ? (float)$key["emetel"] : NULL;
					}
				}else{
					$temp[] = ($key["xr3_inversion"] != 0) ? (float)$key["xr3_inversion"] : NULL;
					$temp[] = ($key["xr3_inversion"] != 0) ? (float)$key["xr3_inversion"] : NULL;
					$temp[] = ($key["emetel"] != 0) ? (float)$key["emetel"] : NULL;
					$temp[] = ($key["emetel"] != 0) ? (float)$key["emetel"] : NULL;
				}
			  
				$array[] = $temp;
			  }
			  
			
			function filterNullRows($row) {
				return !(count($row) == 3 && is_null($row[1]) && is_null($row[2]));
			}

			// Aplicar el filtro al arreglo de datos
			$result = array_filter($array, 'filterNullRows');

			// Convertir el resultado de nuevo a un arreglo indexado
			$result = array_values($result);


			return $result;
		}
		
		public function getDataCumplimientoFacturacion($anio,$jefe,$mes){
			$this->db->select('d.tecnico,
				d.mes,
				ROUND(AVG(d.px_ca),0) as avg_ca,
				ROUND(AVG(d.px_as),0) as avg_as,
				ROUND(AVG(d.px_cm),0) as avg_cm,
			');
			if($anio!=""){
				$this->db->where('anio', $anio);
			}
			if($jefe!=""){
				$this->db->where('jefe', $jefe);
			}
			if($mes!=""){
				$this->db->where('mes', $mes);
			}
			
			$this->db->group_by('tecnico, mes');
			$res = $this->db->get('dashboard_cumplimiento_facturacion d');

			$this->db->select('CONCAT(" ",d2.jefe) as tecnico,
				d2.mes as mes,
				ROUND(AVG(d2.px_ca),0) as avg_ca,
				ROUND(AVG(d2.px_as),0) as avg_as,
				ROUND(AVG(d2.px_cm),0) as avg_cm,
			');

			if($anio!=""){
				$this->db->where('anio', $anio);
			}
			if($jefe!=""){
				$this->db->where('jefe', $jefe);
			}
			if($mes!=""){
				$this->db->where('mes', $mes);
			}
			$this->db->group_by('mes');
			$res2 = $this->db->get('dashboard_cumplimiento_facturacion d2');
			$data = array_merge($res->result_array(),$res2->result_array());

			$avgArray = array();
			$monthsArray = array();

			$nmes = array(
				"0" => 'Nulo',
				"01" => 'Enero',
				"02" => 'Febrero',
				"03" => 'Marzo',
				"04" => 'Abril',
				"05" => 'Mayo',
				"06" => 'Junio',
				"07" => 'Julio',
				"08" => 'Agosto',
				"09" => 'Septiembre',
				"10" => 'Octubre',
				"11" => 'Noviembre',
				"12" => 'Diciembre'
			);

			foreach ($data as $item) {
				$tecnico = $item["tecnico"];
				$mes = $nmes[$item["mes"]];
				$avg_ca = $item["avg_ca"]; 
				$avg_as = $item["avg_as"]; 
				$avg_cm = $item["avg_cm"]; 

				if (!isset($avgArray[$tecnico])) {
					$avgArray[$tecnico] = [];
				}
				if (!in_array($mes, $monthsArray)) {
					$monthsArray[] = $mes;
				}
				$avgArray[$tecnico]["tecnico"] = $tecnico;
				$avgArray[$tecnico][$anio."_".$mes."_avg_ca"] = $avg_ca."%";
				$avgArray[$tecnico][$anio."_".$mes."_avg_as"] = $avg_as."%";
				$avgArray[$tecnico][$anio."_".$mes."_avg_cm"] = $avg_cm."%";
			}

			foreach ($monthsArray as $mes) {
				foreach ($data as $item) {
					$tecnico = $item["tecnico"];
					$avg_ca = $item["avg_ca"]; 
					$avg_as = $item["avg_as"]; 
					$avg_cm = $item["avg_cm"]; 

					if($avg_ca !="" || $avg_as !="" || $avg_cm !=""){
						if (!isset($avgArray[$tecnico])) {
							$avgArray[$tecnico] = [];
						}
						if (!isset($avgArray[$tecnico][$anio."_".$mes."_avg_as"])) {
							$avgArray[$tecnico][$anio."_".$mes."_avg_as"] = "";
						}
						if (!isset($avgArray[$tecnico][$anio."_".$mes."_avg_cm"])) {
							$avgArray[$tecnico][$anio."_".$mes."_avg_cm"] = "";
						}
						if (!isset($avgArray[$tecnico][$anio."_".$mes."_avg_ca"])) {
							$avgArray[$tecnico][$anio."_".$mes."_avg_ca"] = "";
						}
					}
	
				}
			}

			$array = array();

			foreach ($avgArray as $data){
				$temp = $data;
				$array[]=$temp;
			}
			return $array;
		}
		
		public function getCabecerasCumplimientoFacturacion($anio,$jefe,$mes){
			$this->db->select('
				DISTINCT(d.mes) as mes,
			');
			if($anio!=""){
				$this->db->where('anio', $anio);
			}
			if($jefe!=""){
				$this->db->where('jefe', $jefe);
			}
			if($mes!=""){
				$this->db->where('mes', $mes);
			}
			$this->db->order_by('d.mes', 'asc');
			$res = $this->db->get('dashboard_cumplimiento_facturacion d');


			$mes = array(
				"0" => 'Nulo',
				"01" => 'Enero',
				"02" => 'Febrero',
				"03" => 'Marzo',
				"04" => 'Abril',
				"05" => 'Mayo',
				"06" => 'Junio',
				"07" => 'Julio',
				"08" => 'Agosto',
				"09" => 'Septiembre',
				"10" => 'Octubre',
				"11" => 'Noviembre',
				"12" => 'Diciembre'
			);

			$array = array();
			if($res->num_rows()>0){
				foreach($res->result_array() as $key){
					$temp = array();
					$temp[] = $mes[$key['mes']];
					$array[] = $temp;
				}
			}
			else{
				$temp = array();
				$temp[] = "";
				$array[] = $temp;
			}
			return $array;
		}

		public function getJefeCumplimientoFacturacion(){
			$this->db->distinct();
			$this->db->select('jefe');
			$res = $this->db->get('dashboard_cumplimiento_facturacion');
			return $res->result_array();
		}

		public function getAnioCumplimientoFacturacion(){
			$this->db->distinct();
			$this->db->select('anio');
			$res = $this->db->get('dashboard_cumplimiento_facturacion');

			return $res->result_array();
		}
		public function getMesesCumplimientoFacturacion(){
			$this->db->distinct();
			$this->db->select('mes');
			$this->db->order_by('mes', 'asc');
			$res = $this->db->get('dashboard_cumplimiento_facturacion');

			
			$mes = array(
				"01" => 'Enero',
				"02" => 'Febrero',
				"03" => 'Marzo',
				"04" => 'Abril',
				"05" => 'Mayo',
				"06" => 'Junio',
				"07" => 'Julio',
				"08" => 'Agosto',
				"09" => 'Septiembre',
				"10" => 'Octubre',
				"11" => 'Noviembre',
				"12" => 'Diciembre'
			);

			$array = array();
			
			foreach($res->result_array() as $key){
				$temp = array();
				$temp["mes"] = $mes[$key['mes']];
				$temp["id"] = $key['mes'];
				$array[] = $temp;
			}
			return $array;
		}

		public function getZonas(){
			$this->db->distinct();
			$this->db->select('zona');
			$res=$this->db->get('dashboard_analisis_calidad');
			return $res->result_array();
		}

		public function getComunas(){
			$this->db->distinct();
			$this->db->select('comuna');
			$res=$this->db->get('dashboard_analisis_calidad');
			return $res->result_array();
		}

		public function getSupervisores(){
			$this->db->distinct();
			$this->db->select('supervisor');
			$res=$this->db->get('dashboard_analisis_calidad');
			return $res->result_array();
		}

		public function getTecnologias(){
			$this->db->distinct();
			$this->db->select('tecnologia');
			$res=$this->db->get('dashboard_analisis_calidad');
			return $res->result_array();
		}
		
		public function getComunasProdCiudad(){
			$this->db->distinct();
			$this->db->select('comuna');
			$res=$this->db->get('dashboard_px_claro_ciudad');
			return $res->result_array();
		}

		public function getSupervisoresProdCiudad(){
			$this->db->distinct();
			$this->db->select('supervisor');
			$res=$this->db->get('dashboard_px_claro_ciudad');
			return $res->result_array();
		}

		public function getTecnologiasProdCiudad(){
			$this->db->distinct();
			$this->db->select('tecnologia');
			$res=$this->db->get('dashboard_px_claro_ciudad');
			return $res->result_array();
		}

		public function getTecnologiasXcomuna(){
			$this->db->distinct();
			$this->db->select('tecnologia');
			$res=$this->db->get('dashboard_comparacion_comuna');
			return $res->result_array();
		}

		public function getComunasXcomuna(){
			$this->db->distinct();
			$this->db->select('comuna');
			$this->db->order_by('comuna', 'asc');
			$res=$this->db->get('dashboard_comparacion_comuna');
			return $res->result_array();
		}

		public function getZonasXcomuna(){
			$this->db->distinct();
			$this->db->select('zona');
			$res=$this->db->get('dashboard_comparacion_comuna');
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


		public function listaDashboardProductividad(){
			$this->db->select("sha1(c.id) as hash_id,
					c.*,
					CONCAT(u.nombres,' ',u.apellidos)  'digitador'");
			$this->db->join('usuarios u', 'u.id = c.id_digitador', 'left');
			$res=$this->db->get('documentacion_capacitacion as c');
			return $res->result_array();
		}

		
		public function getDataRegistroCapacitacion($hash){
			$this->db->select("sha1(c.id) as hash_id,
					c.*");
			$this->db->where('sha1(c.id)', $hash);
			$res=$this->db->get('documentacion_capacitacion as c');
			return $res->result_array();
		}


		public function formActualizarCapacitacion($id,$data){
			$this->db->where('sha1(id)', $id);
		    if($this->db->update('documentacion_capacitacion', $data)){
		    	return TRUE;
		    }
		    return FALSE;
		}

		public function formProductividadXr3($data){
			if($this->db->insert('dashboard_productividad', $data)){
				return $this->db->insert_id();
			}
			return FALSE;
		} 
		
		public function formCalidadXr3($data){
			if($this->db->insert('dashboard_calidad', $data)){
				return $this->db->insert_id();
			}
			return FALSE;
		} 

		public function formDotacion($data){
			if($this->db->insert('dashboard_dotacion', $data)){
				return $this->db->insert_id();
			}
			return FALSE;
		} 

		public function formAnalisisCalidad($data){
			if($this->db->insert('dashboard_analisis_calidad', $data)){
				return $this->db->insert_id();
			}
			return FALSE;
		} 

		public function formProdCalClaro($data){
			if($this->db->insert('dashboard_prod_cal_claro', $data)){
				return $this->db->insert_id();
			}
			return FALSE;
		} 

		public function formPxClaroCiudad($data){
			if($this->db->insert('dashboard_px_claro_ciudad', $data)){
				return $this->db->insert_id();
			}
			return FALSE;
		} 

		
		public function formXComuna($data){
			if($this->db->insert('dashboard_comparacion_comuna', $data)){
				return $this->db->insert_id();
			}
			return FALSE;
		} 

		public function formCumpFact($data){
			if($this->db->insert('dashboard_cumplimiento_facturacion', $data)){
				return $this->db->insert_id();
			}
			return FALSE;
		} 

		
		public function eliminaCapacitacion($hash){
			$this->db->where('sha1(id)', $hash);
			if($this ->db->delete('documentacion_capacitacion')){
			  	return TRUE;
			}
			return FALSE;
		}

}