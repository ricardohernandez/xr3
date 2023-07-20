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
			$this->visitas("Productividad y calidad xr3",23);

			$datos=array(
				'mes_inicio' => date('Y') . '-01',
				'mes_termino' => date('Y-m'),
			);

			$this->load->view('back_end/dashboard_operaciones/productividad_calidad_xr3',$datos);
		}

		public function produccionCalidadEPS(){
			$this->visitas("Productividad y calidad EPS",23);

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
			$this->visitas("Dotación",23);

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
			$this->visitas("Analisis calidad",23);
			$datos=array(
				'mes_inicio' =>  date('Y-m', strtotime('-2 months', strtotime(date("Y-m-d")))),
				'mes_termino' => date('Y-m'),
				'zonas' => $this->Dashboard_operacionesmodel->getZonas(),
				'comunas' => $this->Dashboard_operacionesmodel->getComunas(),
				'supervisores' => $this->Dashboard_operacionesmodel->getSupervisores(),
				'tecnologias' => $this->Dashboard_operacionesmodel->getTecnologias()
			);
			$this->load->view('back_end/dashboard_operaciones/analisis_calidad',$datos);
		}

		public function graficoAnalisisCalidad(){
			$mes_inicio=$this->security->xss_clean(strip_tags($this->input->get_post("mes_inicio_analisis_cal")));
			$mes_termino=$this->security->xss_clean(strip_tags($this->input->get_post("mes_termino_analisis_cal")));
			$zona=$this->security->xss_clean(strip_tags($this->input->get_post("zona")));
			$comuna=$this->security->xss_clean(strip_tags($this->input->get_post("comuna")));
			$supervisor=$this->security->xss_clean(strip_tags($this->input->get_post("supervisor")));
			$tecnologia=$this->security->xss_clean(strip_tags($this->input->get_post("tecnologia")));

			$data = $this->Dashboard_operacionesmodel->getDataAnalisisCalidad($zona,$comuna,$supervisor,$tecnologia,$mes_inicio, $mes_termino);
			$total = $this->Dashboard_operacionesmodel->getDataAnalisisCalidadTotal($zona,$comuna,$supervisor,$mes_inicio, $mes_termino);

			echo json_encode(array("data" => $data , "total" => $total));
		}


		public function prodCalClaro(){
			$this->visitas("Productividad y calidad claro",23);
			$datos=array(
				'mes_inicio' =>  date('Y-m', strtotime('-3 months', strtotime(date("Y-m-d")))),
				'mes_termino' => date('Y-m'),
				'comunas' => $this->Dashboard_operacionesmodel->getComunasProdCiudad(),
				'supervisores' => $this->Dashboard_operacionesmodel->getSupervisoresProdCiudad(),
				'tecnologias' => $this->Dashboard_operacionesmodel->getTecnologiasProdCiudad()
			);
			$this->load->view('back_end/dashboard_operaciones/prod_cal_claro',$datos);
		}

		public function graficoProdxEps(){
			$mes_inicio=$this->security->xss_clean(strip_tags($this->input->get_post("mes_inicio_cal_claro")));
			$mes_termino=$this->security->xss_clean(strip_tags($this->input->get_post("mes_termino_cal_claro")));
			$comuna=$this->security->xss_clean(strip_tags($this->input->get_post("comuna")));
			$supervisor=$this->security->xss_clean(strip_tags($this->input->get_post("supervisor")));
			$tecnologia=$this->security->xss_clean(strip_tags($this->input->get_post("tecnologia")));
			
			$campos = [
				"general" => [
					"campos" => ['px_xr3', 'px_alianza_sur', 'px_red_cell'],

					"cabeceras" => 
						["mes", 
						"PX XR3", ['role' => 'annotation'], 
						"PX Alianza sur", ['role' => 'annotation'], 
						"PX Red cell", ['role' => 'annotation'],
						['role' => 'annotationText']
						]
				],
				"hfc" => [
					"campos" => ['px_hfc_xr3', 'px_hfc_alianza_sur', 'px_hfc_red_cell'],

					"cabeceras" => 
						["mes", 
						"PX HFC XR3", ['role' => 'annotation'], 
						"PX HFC Alianza sur", ['role' => 'annotation'], 
						"PX HFC Red cell", ['role' => 'annotation'],
						['role' => 'annotationText']
						]
				],
				"ftth" => [
					"campos" => ['px_ftth_xr3', 'px_ftth_alianza_sur', 'px_ftth_red_cell'],

					"cabeceras" => 
						["mes", 
						"PX XR3 FTTH", ['role' => 'annotation'], 
						"PX Alianza sur FTTH", ['role' => 'annotation'], 
						"PX Red cell FTTH", ['role' => 'annotation'],
						['role' => 'annotationText']
						]
				],
				"ca_hfc" => [
					"campos" => ['ca_hfc_xr3', 'ca_hfc_alianza_sur'],

					"cabeceras" => 
						["mes", 
							"PX HFC XR3", ['role' => 'annotation'], 
							"PX HFC Alianza sur", ['role' => 'annotation'], 
						['role' => 'annotationText']
						]
				],
				"ca_ftth" => [
					"campos" => ['ca_ftth_xr3', 'ca_ftth_alianza_sur'],

					"cabeceras" => 
						["mes", 
						"PX XR3 FTTH", ['role' => 'annotation'], 
						"PX Alianza sur FTTH", ['role' => 'annotation'], 
						['role' => 'annotationText']
					]
				]
			];
	
			$datos = array(
				'prod_ciudad' =>$this->Dashboard_operacionesmodel->graficoProdxCiudad($comuna,$supervisor,$tecnologia,$mes_inicio, $mes_termino),
				'prod_general' =>$this->Dashboard_operacionesmodel->graficoProdxEps($campos["general"], $mes_inicio, $mes_termino),
				'prod_hfc' =>$this->Dashboard_operacionesmodel->graficoProdxEps($campos["hfc"], $mes_inicio, $mes_termino),
				'prod_ftth' =>$this->Dashboard_operacionesmodel->graficoProdxEps($campos["ftth"], $mes_inicio, $mes_termino),
				'calidad_hfc' =>$this->Dashboard_operacionesmodel->graficoProdxEps($campos["ca_hfc"], $mes_inicio, $mes_termino),
				'calidad_ftth' =>$this->Dashboard_operacionesmodel->graficoProdxEps($campos["ca_ftth"], $mes_inicio, $mes_termino),
			);

			echo json_encode($datos);
		}
		
		
		public function prodXComuna(){
			$this->visitas("Productividad x comuna y eps",23);
			$datos=array(
				'mes_inicio' =>  date('Y-m', strtotime('-2 months', strtotime(date("Y-m-d")))),
				'mes_termino' => date('Y-m'),
				'comunas' => $this->Dashboard_operacionesmodel->getComunasXcomuna(),
				'zonas' => $this->Dashboard_operacionesmodel->getZonasXcomuna(),
				'tecnologias' => $this->Dashboard_operacionesmodel->getTecnologiasXcomuna()
			);
			$this->load->view('back_end/dashboard_operaciones/productividad_comuna',$datos);
		}
		
		
		public function graficoXComuna(){
			$mes_inicio=$this->security->xss_clean(strip_tags($this->input->get_post("mes_inicio")));
			$mes_termino=$this->security->xss_clean(strip_tags($this->input->get_post("mes_termino")));
			$zona=$this->security->xss_clean(strip_tags($this->input->get_post("zona")));
			$comuna=$this->security->xss_clean(strip_tags($this->input->get_post("comuna")));
			$tecnologia=$this->security->xss_clean(strip_tags($this->input->get_post("tecnologia")));

			$meses_diferencia = abs((strtotime($mes_termino) - strtotime($mes_inicio)) / (30 * 24 * 60 * 60));

			if($meses_diferencia <= 3 ){

				echo json_encode(array(
					'res' => "ok",
					'prod_comuna' => $this->Dashboard_operacionesmodel->getDataProductividadComuna($mes_inicio,$mes_termino,$zona,$comuna,$tecnologia)
				));

			}else{
				echo json_encode(array("res"=>"error" , "msg" => "La cantidad máxima de meses a mostrar es 3"));
			}
			
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


			//PROD Y CAL CLARO

				$hoja_prod_cal_claro = $spreadsheet->getSheet(4);
				$ultima_fila_prod_cal_claro  = $hoja_prod_cal_claro->getHighestRow();

				$this->db->query("TRUNCATE TABLE dashboard_prod_cal_claro");
				$filas_prod_cal_claro= 0;

				for ($fila = 2; $fila <= $ultima_fila_prod_cal_claro; $fila++) {
					$datos = array();
			
					$columnas_prod_cal_claro = ['anio','mes','orden_mes', 'px_xr3', 'px_hfc_xr3', 'px_ftth_xr3', 'px_alianza_sur', 'px_hfc_alianza_sur', 'px_ftth_alianza_sur', 'px_red_cell', 'px_hfc_red_cell', 'px_ftth_red_cell', 'ca_hfc_xr3', 'ca_ftth_xr3', 'ca_hfc_alianza_sur', 'ca_ftth_alianza_sur', 'meta_ca_ftth', 'meta_ca_hfc', 'meta_px_hfc', 'meta_px_ftth'];

					$mes = '';
					$anio = '';
			
					foreach ($columnas_prod_cal_claro as $index => $columna) {
						$valor = $hoja_prod_cal_claro->getCellByColumnAndRow($index +2, $fila)->getFormattedValue();

						if ($index === 0) {
							$anio = $valor;
							$datos[$columna] = $valor;
						}
						elseif ($index === 1) {
							$mes = obtenerNumeroMesCompleto(trim($valor));
							$datos[$columna] = $mes;
						}else{
							$valor = $hoja_prod_cal_claro->getCellByColumnAndRow($index +2, $fila)->getFormattedValue();
							$datos[$columna] = $valor;
						}

					}	

					$datos["fecha"] = $anio . '-' . $mes . '-01';
					
					$this->Dashboard_operacionesmodel->formProdCalClaro($datos);
					$filas_prod_cal_claro++;
				}
			

			//PROD Y CAL CLARO

				$hoja_px_claro_ciudad = $spreadsheet->getSheet(5);
				$ultima_fila_px_claro_ciudad  = $hoja_px_claro_ciudad->getHighestRow();

				$this->db->query("TRUNCATE TABLE dashboard_px_claro_ciudad");
				$filas_px_ciudad_claro= 0;

				for ($fila = 2; $fila <= $ultima_fila_px_claro_ciudad; $fila++) {
					$datos = array();
			
					$columnas_px_claro = ['anio','mes','orden_mes', 'comuna', 'tecnologia', 'px', 'supervisor'];

					$mes = '';
					$anio = '';

					foreach ($columnas_px_claro as $index => $columna) {
						$valor = $hoja_px_claro_ciudad->getCellByColumnAndRow($index +2, $fila)->getFormattedValue();

						if ($index === 0) {
							$anio = $valor;
							$datos[$columna] = $valor;
						}
						elseif ($index === 1) {
							$mes = obtenerNumeroMesCompleto(trim($valor));
							$datos[$columna] = $mes;
						}else{
							$valor = $hoja_px_claro_ciudad->getCellByColumnAndRow($index +2, $fila)->getFormattedValue();
							$datos[$columna] = $valor;
						}

					}	


					$datos["fecha"] = $anio . '-' . $mes . '-01';
					$this->Dashboard_operacionesmodel->formPxClaroCiudad($datos);
					$filas_px_ciudad_claro++;
				}

			//PROD COMUNA

				$hoja_px_comuna = $spreadsheet->getSheet(6);
				$ultima_fila_px_comuna  = $hoja_px_comuna->getHighestRow();

				$this->db->query("TRUNCATE TABLE dashboard_comparacion_comuna");
				$filas_px_comuna= 0;

				for ($fila = 2; $fila <= $ultima_fila_px_comuna; $fila++) {
					$datos = array();
			
					$columnas_px_comuna = ['mes','mes2','anio','orden_mes', 'zona', 'comuna', 'emetel', 'xr3_inversion', 'tecnologia'];

					$mes = '';
					$anio = '';

					foreach ($columnas_px_comuna as $index => $columna) {
						$valor = $hoja_px_comuna->getCellByColumnAndRow($index+1 , $fila)->getFormattedValue();

								if ($index === 0) {
									$mes = obtenerNumeroMes(trim($valor));
									$datos[$columna] = $mes;
								}
								elseif ($index === 1) {
									$mes = obtenerNumeroMes(trim($valor));
									$datos[$columna] = $mes;
								}elseif ($index === 2) {
									$anio = $valor;
									$datos[$columna] = $valor;
								}else{
									$valor = $hoja_px_comuna->getCellByColumnAndRow($index +1, $fila)->getFormattedValue();
									$datos[$columna] = $valor;
								}
						

					}	

					$datos["fecha"] = $anio . '-' . $mes . '-01';
					unset($datos["mes2"]);

					$this->Dashboard_operacionesmodel->formXComuna($datos);
					$filas_px_comuna++;
				}

			echo json_encode(array(
				'res' => 'ok',
				'tipo' => 'success',
				'msg' => 'Archivo cargado con éxito.
				'.$filas_productividad.' filas de productividad insertadas,
				'.$filas_calidad.' filas de calidad insertadas,
				'.$filas_dotacion.' filas de dotacion insertadas,
				'.$filas_analisis_cal.' filas de analisis de calidad insertadas,
				'.$filas_prod_cal_claro.' filas de prod. y calidad claro insertadas,
				'.$filas_px_ciudad_claro.' filas de px x ciudad claro insertadas,
				'.$filas_px_comuna.' filas de px x comuna insertadas,'
				
			));

			exit;
		}
		

}