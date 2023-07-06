<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Dashboard_operaciones extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if($this->uri->segment("1")==""){
			redirect("inicio");
		}
		$this->load->model("back_end/Dashboard_operacionesmodel");
		$this->load->model("back_end/Iniciomodel");
		$this->load->helper(array('fechas','str'));
		$this->load->library('user_agent');
	}

	public function visitas($modulo,$id_aplicacion){
		$this->load->library('user_agent');
		$data=array("id_usuario"=>$this->session->userdata('id'),
			"id_aplicacion"=>$id_aplicacion,
			"modulo"=>$modulo,
	     	"fecha"=>date("Y-m-d G:i:s"),
	    	"navegador"=>"navegador :".$this->agent->browser()."\nversion :".$this->agent->version()."\nos :".$this->agent-> platform()."\nmovil :".$this->agent->mobile(),
	    	"ip"=>$this->input->ip_address(),
    	);
    	$this->Dashboard_operacionesmodel->insertarVisita($data);
	}



	public function checkLogin(){
		// if($this->session->userdata('rutUsuario')==""){
		// 	echo json_encode(array('res'=>"sess"));exit;
		// }
	}

	public function acceso(){
		 if(!$this->session->userdata('id')){
	      	redirect("./login");
	      }
	}


	/***********PRODUCTIVIDAD Y CALIDAD XR3*****************/

		public function indexDashboardOP(){
	    	$this->acceso();
		    $datos = array(
		        'titulo' => "Documentación XR3",
		        'contenido' => "dashboard_operaciones/inicio",
		        'perfiles' => $this->Iniciomodel->listaPerfiles(),
			);  
			$this->load->view('plantillas/plantilla_back_end',$datos);
			
		}
		
		public function productividadCalidadXr3(){
			$this->visitas("Inicio",4);

			$datos=array(
				'mes_inicio' => date('Y') . '-01',
				'mes_termino' => date('Y-m'),
			);

			$this->load->view('back_end/dashboard_operaciones/productividad_calidad_xr3',$datos);
		}

		public function produccionCalidadEPS(){
			$this->visitas("Inicio",4);

			$datos=array(
				'mes_inicio' => date('Y') . '-01',
				'mes_termino' => date('Y-m'),
			);

			$this->load->view('back_end/dashboard_operaciones/productividad_calidad_eps',$datos);
		}

		
		public function graficosProductividadXR3(){
			$mes_inicio=$this->security->xss_clean(strip_tags($this->input->get_post("mes_inicio")));
			$mes_termino=$this->security->xss_clean(strip_tags($this->input->get_post("mes_termino")));

			$tiposProductividad = [
				"nac" => [
					"campos" => ['hfc_na', 'ftth_na'],
					"cabeceras" => ["mes", "HFC", ['role' => 'annotation'], "FTTH", ['role' => 'annotation'], ['role' => 'annotationText'], ['role' => 'annotationText']]
				],
				"nortehfc" => [
					"campos" => ['hfc_nor', 'meta'],
					"cabeceras" => ["mes", "HFC Norte", ['role' => 'annotation'], "Meta", ['role' => 'annotation'], ['role' => 'annotationText'], ['role' => 'annotationText']]
				],
				"norteftth" => [
					"campos" => ['ftth_nor', 'meta'],
					"cabeceras" => ["mes", "FTTH Norte", ['role' => 'annotation'], "Meta", ['role' => 'annotation'], ['role' => 'annotationText'], ['role' => 'annotationText']]
				],
				"surhfc" => [
					"campos" => ['hfc_sur', 'meta'],
					"cabeceras" => ["mes", "HFC Sur", ['role' => 'annotation'], "Meta", ['role' => 'annotation'], ['role' => 'annotationText'], ['role' => 'annotationText']]
				],
				"surftth" => [
					"campos" => ['ftth_sur', 'meta'],
					"cabeceras" => ["mes", "FTTH Sur", ['role' => 'annotation'], "Meta", ['role' => 'annotation'], ['role' => 'annotationText'], ['role' => 'annotationText']]
				]
			];

	 
			$datos = array(
				'productividadnacional' => $this->Dashboard_operacionesmodel->getDataProductividad($tiposProductividad["nac"], $mes_inicio, $mes_termino),
				'productividadnorteHFC' => $this->Dashboard_operacionesmodel->getDataProductividad($tiposProductividad["nortehfc"], $mes_inicio, $mes_termino),
				'productividadnorteFTTH' => $this->Dashboard_operacionesmodel->getDataProductividad($tiposProductividad["norteftth"], $mes_inicio, $mes_termino),
				'productividadsurHFC' => $this->Dashboard_operacionesmodel->getDataProductividad($tiposProductividad["surhfc"], $mes_inicio, $mes_termino),
				'productividadsurFTTH' => $this->Dashboard_operacionesmodel->getDataProductividad($tiposProductividad["surftth"], $mes_inicio, $mes_termino),

				'calidadnacional' => $this->Dashboard_operacionesmodel->getDataCalidad($tiposProductividad["nac"], $mes_inicio, $mes_termino),
				'calidadnorteHFC' => $this->Dashboard_operacionesmodel->getDataCalidad($tiposProductividad["nortehfc"], $mes_inicio, $mes_termino),
				'calidadnorteFTTH' => $this->Dashboard_operacionesmodel->getDataCalidad($tiposProductividad["norteftth"], $mes_inicio, $mes_termino),
				'calidadsurHFC' => $this->Dashboard_operacionesmodel->getDataCalidad($tiposProductividad["surhfc"], $mes_inicio, $mes_termino),
				'calidadsurFTTH' => $this->Dashboard_operacionesmodel->getDataCalidad($tiposProductividad["surftth"], $mes_inicio, $mes_termino),

			);
			
			echo json_encode($datos);

		}
		

		public function graficosProductividadEps(){
			$mes_inicio=$this->security->xss_clean(strip_tags($this->input->get_post("mes_inicio")));
			$mes_termino=$this->security->xss_clean(strip_tags($this->input->get_post("mes_termino")));

			$campos = [
				"nac" => [
					"campos" => ['xr3_x_eps', 'jr_x_eps', 'emetel_x_eps'],
					"cabeceras" => ["mes", "XR3", ['role' => 'annotation'], "JR", ['role' => 'annotation'], "EMETEL", ['role' => 'annotation'], ['role' => 'annotationText'],['role' => 'annotationText']]
				],
				"hfc" => [
					"campos" => ['xr3_x_eps_hfc', 'jr_x_eps_hfc', 'emetel_x_eps_hfc'],
					"cabeceras" => ["mes", "XR3", ['role' => 'annotation'], "JR", ['role' => 'annotation'], "EMETEL", ['role' => 'annotation'], ['role' => 'annotationText'],['role' => 'annotationText']]
				],
				"ftth" => [
					"campos" => ['xr3_x_eps_ftth', 'jr_x_eps_ftth', 'emetel_x_eps_ftth'],
					"cabeceras" => ["mes", "XR3", ['role' => 'annotation'], "JR", ['role' => 'annotation'], "EMETEL", ['role' => 'annotation'], ['role' => 'annotationText'],['role' => 'annotationText']]
				],
			 
			];

			$datos = array(
				'productividadnacional' => $this->Dashboard_operacionesmodel->getDataProductividadEPS($campos["nac"], $mes_inicio, $mes_termino),
				'productividadHFC' => $this->Dashboard_operacionesmodel->getDataProductividadEPS($campos["hfc"], $mes_inicio, $mes_termino),
				'productividadFTTH' => $this->Dashboard_operacionesmodel->getDataProductividadEPS($campos["ftth"], $mes_inicio, $mes_termino),

				'calidadnacional' => $this->Dashboard_operacionesmodel->getDataCalidadEPS($campos["nac"], $mes_inicio, $mes_termino),
				'calidadHFC' => $this->Dashboard_operacionesmodel->getDataCalidadEPS($campos["hfc"], $mes_inicio, $mes_termino),
				'calidadFTTH' => $this->Dashboard_operacionesmodel->getDataCalidadEPS($campos["ftth"], $mes_inicio, $mes_termino),

			);
			
			echo json_encode($datos);

		}


		public function dotacion(){
			$this->visitas("Inicio",4);

			$datos=array(
				'mes_inicio' => date('Y') . '-01',
				'mes_termino' => date('Y-m'),
			);

			$this->load->view('back_end/dashboard_operaciones/dotacion',$datos);
		}

		public function graficoDotacion(){
			$mes_inicio=$this->security->xss_clean(strip_tags($this->input->get_post("mes_inicio")));
			$mes_termino=$this->security->xss_clean(strip_tags($this->input->get_post("mes_termino")));
 
			echo json_encode($this->Dashboard_operacionesmodel->getDataDotacion($mes_inicio, $mes_termino));
		}

		
		public function analisisCalidad(){
			$this->visitas("Inicio",4);

			$datos=array(
				'mes_inicio' => date('Y') . '-01',
				'mes_termino' => date('Y-m'),
			);

			$this->load->view('back_end/dashboard_operaciones/analisis_calidad',$datos);
		}

		public function graficoAnalisisCalidad(){
			$mes_inicio=$this->security->xss_clean(strip_tags($this->input->get_post("mes_inicio")));
			$mes_termino=$this->security->xss_clean(strip_tags($this->input->get_post("mes_termino")));
 
			echo json_encode($this->Dashboard_operacionesmodel->getDataAnalisisCalidad($mes_inicio, $mes_termino));
		}

		
		public function cargaDashboardProductividadXR3() {
			$archivo = $_FILES['userfile']['tmp_name'];
			$spreadsheet = IOFactory::load($archivo);

			//PRODUCTIVIDAD
				$hoja = $spreadsheet->getSheet(0);
				$ultima_fila = $hoja->getHighestRow();
				
				$this->db->query("TRUNCATE TABLE dashboard_productividad");
				$filas_productividad = 0;

				for ($fila = 2; $fila <= $ultima_fila; $fila++) {
					$datos = array();
			
					$columnas = array(
						'mes', 'anio', 'nmes', 'hfc_na', 'ftth_na', 'hfc_nor', 'ftth_nor', 'hfc_sur', 'ftth_sur',
						'xr3_x_eps', 'jr_x_eps', 'emetel_x_eps', 'xr3_x_eps_hfc', 'jr_x_eps_hfc', 'emetel_x_eps_hfc',
						'xr3_x_eps_ftth', 'jr_x_eps_ftth', 'emetel_x_eps_ftth', 'xr3_x_eps_nor', 'jr_x_eps_nor',
						'emetel_x_eps_nor', 'xr3_x_eps_hfc_nor', 'jr_x_eps_hfc_nor', 'emetel_x_eps_hfc_nor',
						'xr3_x_eps_ftth_nor', 'jr_x_eps_ftth_nor', 'emetel_x_eps_ftth_nor', 'xr3_x_eps_sur',
						'jr_x_eps_sur', 'emetel_x_eps_sur', 'xr3_x_eps_hfc_sur', 'jr_x_eps_hfc_sur',
						'emetel_x_eps_hfc_sur', 'xr3_x_eps_ftth_sur', 'jr_x_eps_ftth_sur', 'emetel_x_eps_ftth_sur',
						'meta'
					);
			
					$mes = '';
					$anio = '';
			
					foreach ($columnas as $index => $columna) {
						if ($index === 0) {
							$mes = obtenerNumeroMes(trim($hoja->getCellByColumnAndRow($index + 1, $fila)->getValue()));
							$datos[$columna] = $mes;
						} elseif ($index === 1) {
							$anio = $hoja->getCellByColumnAndRow($index + 1, $fila)->getValue();
							$datos[$columna] = $anio;
						} else {
							$datos[$columna] = $hoja->getCellByColumnAndRow($index + 1, $fila)->getValue();
						}
					}
			
					$datos["fecha"] = $anio . '-' . $mes . '-01';
					
					$this->Dashboard_operacionesmodel->formProductividadXr3($datos);
					$filas_productividad++;
				}

			//CALIDAD 

				$hoja_calidad = $spreadsheet->getSheet(1);
				$ultima_fila_calidad = $hoja_calidad->getHighestRow();

				$this->db->query("TRUNCATE TABLE dashboard_calidad");
				$filas_calidad = 0;

				for ($fila = 2; $fila <= $ultima_fila_calidad; $fila++) {
					$datos = array();
			
					$columnas_calidad = [
						"mes", "anio", "n_mes", "hfc_na", "ftth_na", "hfc_nor", "ftth_nor",
						"hfc_sur", "ftth_sur", "xr3_x_eps", "jr_x_eps", "emetel_x_eps", "xr3_x_eps_hfc", "jr_x_eps_hfc", "emetel_x_eps_hfc",
						"xr3_x_eps_ftth", "jr_x_eps_ftth", "emetel_x_eps_ftth", "xr3_x_eps_nor", "jr_x_eps_nor", "emetel_x_eps_nor",
						"xr3_x_eps_hfc_nor", "jr_x_eps_hfc_nor", "emetel_x_eps_hfc_nor", "xr3_x_eps_ftth_nor", "jr_x_eps_ftth_nor",
						"emetel_x_eps_ftth_nor", "xr3_x_eps_sur", "jr_x_eps_sur", "emetel_x_eps_sur", "xr3_x_eps_hfc_sur", "jr_x_eps_hfc_sur",
						"emetel_x_eps_hfc_sur", "xr3_x_eps_ftth_sur", "jr_x_eps_ftth_sur", "emetel_x_eps_ftth_sur", "meta"
					];
			
					$mes = '';
					$anio = '';
			
					foreach ($columnas_calidad as $index => $columna) {
						if ($index === 0) {
							$mes = obtenerNumeroMes(trim($hoja_calidad->getCellByColumnAndRow($index + 1, $fila)->getValue()));
							$datos[$columna] = $mes;
						} elseif ($index === 1) {
							$anio = $hoja_calidad->getCellByColumnAndRow($index + 1, $fila)->getValue();
							$datos[$columna] = $anio;
						} else {
							$datos[$columna] = str_replace('%', '', $hoja_calidad->getCellByColumnAndRow($index + 1, $fila)->getFormattedValue());
						}
					}
			
					$datos["fecha"] = $anio . '-' . $mes . '-01';
					
					$this->Dashboard_operacionesmodel->formCalidadXr3($datos);
					$filas_calidad++;
				}

			/* 	print_r($datos);exit; */

				
			//DOTACION 

				$hoja_dotacion = $spreadsheet->getSheet(2);
				$ultima_fila_dotacion = $hoja_dotacion->getHighestRow();

				$this->db->query("TRUNCATE TABLE dashboard_dotacion");
				$filas_dotacion = 0;

				for ($fila = 2; $fila <= $ultima_fila_calidad; $fila++) {
					$datos = array();
			
					$columnas_dotacion = [
						"mes", "anio", "n_mes", "promedio_sur", "promedio_norte",
						"total_operativo", "fte_sur", "fte_norte", "total_mov_fte",
						"acc", "claro"
					];
			
					$mes = '';
					$anio = '';
			
					foreach ($columnas_dotacion as $index => $columna) {
						if ($index === 0) {
							$mes = obtenerNumeroMes(trim($hoja_dotacion->getCellByColumnAndRow($index + 1, $fila)->getValue()));
							$datos[$columna] = $mes;
						} elseif ($index === 1) {
							$anio = $hoja_dotacion->getCellByColumnAndRow($index + 1, $fila)->getValue();
							$datos[$columna] = $anio;
						} else {
							$datos[$columna] = $hoja_dotacion->getCellByColumnAndRow($index + 1, $fila)->getFormattedValue();
						}
					}
			
					$datos["fecha"] = $anio . '-' . $mes . '-01';
					
					$this->Dashboard_operacionesmodel->formDotacion($datos);
					$filas_dotacion++;
				}

			//ANALISIS CALIDAD

				$hoja_analisis_cal = $spreadsheet->getSheet(3);
				$ultima_fila_analisis_cal  = $hoja_analisis_cal->getHighestRow();

				$this->db->query("TRUNCATE TABLE dashboard_analisis_calidad");
				$filas_analisis_cal= 0;

				for ($fila = 2; $fila <= $ultima_fila_analisis_cal; $fila++) {
					$datos = array();
			
					$columnas_analisis_cal= ["anio", "mes", "orden_mes", "zona", "supervisor", "comuna", "ftth", "hfc", "total_general", "tecnologia"];
			
					$mes = '';
					$anio = '';
			
					foreach ($columnas_analisis_cal as $index => $columna) {
						if ($index === 1) {
							$mes = obtenerNumeroMesCompleto(trim($hoja_analisis_cal->getCellByColumnAndRow($index + 1, $fila)->getFormattedValue()));
							$datos[$columna] = $mes;
						} elseif ($index === 0) {
							$anio = $hoja_analisis_cal->getCellByColumnAndRow($index + 1, $fila)->getValue();
							$datos[$columna] = $anio;
						} else {
							$datos[$columna] = str_replace('%', '', $hoja_analisis_cal->getCellByColumnAndRow($index + 1, $fila)->getFormattedValue());
						}
					}
			
					$datos["fecha"] = $anio . '-' . $mes . '-01';
					
					$this->Dashboard_operacionesmodel->formAnalisisCalidad($datos);
					$filas_analisis_cal++;
				}


			
			echo json_encode(array(
				'res' => 'ok',
				'tipo' => 'success',
				'msg' => 'Archivo cargado con éxito.
				'.$filas_productividad.' filas de productividad insertadas,
				'.$filas_calidad.' filas de calidad insertadas,
				'.$filas_dotacion.' filas de dotacion insertadas,
				'.$filas_analisis_cal.' filas de analisis de calidad insertadas'
			));

			exit;
		}
		

}