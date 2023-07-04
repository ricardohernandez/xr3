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
						"mes", "año", "n_mes", "hfc_na", "ftth_na", "hfc_nor", "ftth_nor",
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

			
			echo json_encode(array(
				'res' => 'ok',
				'tipo' => 'success',
				'msg' => 'Archivo cargado con éxito.
				'.$filas_productividad.' filas de productividad insertadas,
				'.$filas_calidad.' filas de calidad insertadas'

			));
			exit;
		}
		

}