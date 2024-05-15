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

		if($this->session->userdata('id_perfil')>3){
			redirect("./login");
		}
	}


	/***********PRODUCTIVIDAD Y CALIDAD XR3*****************/

		public function indexDashboardOP(){
	    	$this->acceso();
		    $datos = array(
		        'titulo' => "Dashboard operaciones XR3",
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
				'ultima_actualizacion' => $this->Dashboard_operacionesmodel->getUltimaCarga(),
			);

			$this->load->view('back_end/dashboard_operaciones/productividad_calidad_xr3',$datos);
		}
		
		public function graficosProductividadXR3(){
			$mes_inicio=$this->security->xss_clean(strip_tags($this->input->get_post("mes_inicio")));
			$mes_termino=$this->security->xss_clean(strip_tags($this->input->get_post("mes_termino")));

			$tiposProductividad = [
				"nac" => [
					"campos" => ['hfc_na', 'ftth_na','meta'],
					"cabeceras" => ["mes", "HFC", ['role' => 'annotation'], "FTTH", ['role' => 'annotation'], "Meta", ['role' => 'annotation'],['role' => 'annotationText'], ['role' => 'annotationText']]
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
		
		public function produccionCalidadEPS(){
			$this->visitas("Productividad y calidad EPS",23);

			$datos=array(
				'mes_inicio' => date('Y') . '-01',
				'mes_termino' => date('Y-m'),
				'zonas' => $this->Dashboard_operacionesmodel->getZonas(),
				'ultima_actualizacion' => $this->Dashboard_operacionesmodel->getUltimaCarga(),
				'tecnologias' => $this->Dashboard_operacionesmodel->getTecnologias()
			);

			$this->load->view('back_end/dashboard_operaciones/productividad_calidad_eps',$datos);
		}


		public function graficosProductividadEps(){
			$mes_inicio=$this->security->xss_clean(strip_tags($this->input->get_post("mes_inicio_eps")));
			$mes_termino=$this->security->xss_clean(strip_tags($this->input->get_post("mes_termino_eps")));
			$tecnologia=$this->security->xss_clean(strip_tags($this->input->get_post("tecnologia")));
			$zona=$this->security->xss_clean(strip_tags($this->input->get_post("zona")));

			$campos = [
				"nac" => [
					"campos" => ['xr3_x_eps', 'jr_x_eps', 'emetel_x_eps','meta'],
					"cabeceras" => ["mes", "XR3", "JR", "EMETEL", "Meta"]
				],
				"hfc" => [
					"campos" => ['xr3_x_eps_hfc', 'jr_x_eps_hfc', 'emetel_x_eps_hfc','meta'],
					"cabeceras" => ["mes", "XR3", "JR", "EMETEL", "Meta"]
				],
				"ftth" => [
					"campos" => ['xr3_x_eps_ftth', 'jr_x_eps_ftth', 'emetel_x_eps_ftth','meta'],
					"cabeceras" => ["mes", "XR3", "JR", "EMETEL", "Meta"]
				],
				"nor" => [
					"campos" => ['xr3_x_eps_nor', 'jr_x_eps_nor', 'emetel_x_eps_nor','meta'],
					"cabeceras" => ["mes", "XR3", "JR", "EMETEL", "Meta"]
				],
				"hfcnor" => [
					"campos" => ['xr3_x_eps_hfc_nor', 'jr_x_eps_hfc_nor', 'emetel_x_eps_hfc_nor','meta'],
					"cabeceras" => ["mes", "XR3", "JR", "EMETEL", "Meta"]
				],
				"ftthnor" => [
					"campos" => ['xr3_x_eps_ftth_nor', 'jr_x_eps_ftth_nor', 'emetel_x_eps_ftth_nor','meta'],
					"cabeceras" => ["mes", "XR3", "JR", "EMETEL", "Meta"]
				],
				"sur" => [
					"campos" => ['xr3_x_eps_sur', 'jr_x_eps_sur', 'emetel_x_eps_sur','meta'],
					"cabeceras" => ["mes", "XR3", "JR", "EMETEL", "Meta"]
				],
				"hfcsur" => [
					"campos" => ['xr3_x_eps_hfc_sur', 'jr_x_eps_hfc_sur', 'emetel_x_eps_hfc_sur','meta'],
					"cabeceras" => ["mes", "XR3", "JR", "EMETEL", "Meta"]
				],
				"ftthsur" => [
					"campos" => ['xr3_x_eps_ftth_sur', 'jr_x_eps_ftth_sur', 'emetel_x_eps_ftth_sur','meta'],
					"cabeceras" => ["mes", "XR3", "JR", "EMETEL", "Meta"]
				],
				"cal_nac" => [
					"campos" => ['xr3_x_eps', 'jr_x_eps', 'emetel_x_eps','meta_px_epps'],
					"cabeceras" => ["mes", "XR3", "JR", "EMETEL", "Meta"]
				],
				"cal_hfc" => [
					"campos" => ['xr3_x_eps_hfc', 'jr_x_eps_hfc', 'emetel_x_eps_hfc','meta_hfc_epps'],
					"cabeceras" => ["mes", "XR3", "JR", "EMETEL", "Meta"]
				],
				"cal_ftth" => [
					"campos" => ['xr3_x_eps_ftth', 'jr_x_eps_ftth', 'emetel_x_eps_ftth','meta_ftth_epps'],
					"cabeceras" => ["mes", "XR3", "JR", "EMETEL", "Meta"]
				],
				"cal_nor" => [
					"campos" => ['xr3_x_eps_nor', 'jr_x_eps_nor', 'emetel_x_eps_nor','meta_px_epps'],
					"cabeceras" => ["mes", "XR3", "JR", "EMETEL", "Meta"]
				],
				"cal_hfcnor" => [
					"campos" => ['xr3_x_eps_hfc_nor', 'jr_x_eps_hfc_nor', 'emetel_x_eps_hfc_nor','meta_hfc_epps'],
					"cabeceras" => ["mes", "XR3", "JR", "EMETEL", "Meta"]
				],
				"cal_ftthnor" => [
					"campos" => ['xr3_x_eps_ftth_nor', 'jr_x_eps_ftth_nor', 'emetel_x_eps_ftth_nor','meta_ftth_epps'],
					"cabeceras" => ["mes", "XR3", "JR", "EMETEL", "Meta"]
				],
				"cal_sur" => [
					"campos" => ['xr3_x_eps_sur', 'jr_x_eps_sur', 'emetel_x_eps_sur','meta_px_epps'],
					"cabeceras" => ["mes", "XR3", "JR", "EMETEL", "Meta"]
				],
				"cal_hfcsur" => [
					"campos" => ['xr3_x_eps_hfc_sur', 'jr_x_eps_hfc_sur', 'emetel_x_eps_hfc_sur','meta_hfc_epps'],
					"cabeceras" => ["mes", "XR3", "JR", "EMETEL", "Meta"]
				],
				"cal_ftthsur" => [
					"campos" => ['xr3_x_eps_ftth_sur', 'jr_x_eps_ftth_sur', 'emetel_x_eps_ftth_sur','meta_ftth_epps'],
					"cabeceras" => ["mes", "XR3", "JR", "EMETEL", "Meta"]
				],
			 
			];	
			
			$datos = array(
				'productividadnacional' => $this->Dashboard_operacionesmodel->getDataProductividadEPS($campos["nac"], $mes_inicio, $mes_termino),
				'productividadHFC' => $this->Dashboard_operacionesmodel->getDataProductividadEPS($campos["hfc"], $mes_inicio, $mes_termino),
				'productividadFTTH' => $this->Dashboard_operacionesmodel->getDataProductividadEPS($campos["ftth"], $mes_inicio, $mes_termino),
				'calidadnacional' => $this->Dashboard_operacionesmodel->getDataCalidadEPS($campos["cal_nac"], $mes_inicio, $mes_termino),
				'calidadHFC' => $this->Dashboard_operacionesmodel->getDataCalidadEPS($campos["cal_hfc"], $mes_inicio, $mes_termino),
				'calidadFTTH' => $this->Dashboard_operacionesmodel->getDataCalidadEPS($campos["cal_ftth"], $mes_inicio, $mes_termino),

				'productividadnor' => $this->Dashboard_operacionesmodel->getDataProductividadEPS($campos["nor"], $mes_inicio, $mes_termino),
				'productividadHFCnor' => $this->Dashboard_operacionesmodel->getDataProductividadEPS($campos["hfcnor"], $mes_inicio, $mes_termino),
				'productividadFTTHnor' => $this->Dashboard_operacionesmodel->getDataProductividadEPS($campos["ftthnor"], $mes_inicio, $mes_termino),
				'calidadnor' => $this->Dashboard_operacionesmodel->getDataCalidadEPS($campos["cal_nor"], $mes_inicio, $mes_termino),
				'calidadHFCnor' => $this->Dashboard_operacionesmodel->getDataCalidadEPS($campos["cal_hfcnor"], $mes_inicio, $mes_termino),
				'calidadFTTHnor' => $this->Dashboard_operacionesmodel->getDataCalidadEPS($campos["cal_ftthnor"], $mes_inicio, $mes_termino),

				'productividadsur' => $this->Dashboard_operacionesmodel->getDataProductividadEPS($campos["sur"], $mes_inicio, $mes_termino),
				'productividadHFCsur' => $this->Dashboard_operacionesmodel->getDataProductividadEPS($campos["hfcsur"], $mes_inicio, $mes_termino),
				'productividadFTTHsur' => $this->Dashboard_operacionesmodel->getDataProductividadEPS($campos["ftthsur"], $mes_inicio, $mes_termino),
				'calidadsur' => $this->Dashboard_operacionesmodel->getDataCalidadEPS($campos["cal_sur"], $mes_inicio, $mes_termino),
				'calidadHFCsur' => $this->Dashboard_operacionesmodel->getDataCalidadEPS($campos["cal_hfcsur"], $mes_inicio, $mes_termino),
				'calidadFTTHsur' => $this->Dashboard_operacionesmodel->getDataCalidadEPS($campos["cal_ftthsur"], $mes_inicio, $mes_termino),

			);
			echo json_encode($datos);
		}

		public function dotacion(){
			$this->visitas("Dotación",23);

			$datos=array(
				'mes_inicio' => date('Y') . '-01',
				'mes_termino' => date('Y-m'),
				'ultima_actualizacion' => $this->Dashboard_operacionesmodel->getUltimaCarga(),
			);

			$this->load->view('back_end/dashboard_operaciones/dotacion',$datos);
		}

		public function graficoDotacion(){
			$mes_inicio=$this->security->xss_clean(strip_tags($this->input->get_post("mes_inicio_dot")));
			$mes_termino=$this->security->xss_clean(strip_tags($this->input->get_post("mes_termino_dot")));
			$tipo=$this->security->xss_clean(strip_tags($this->input->get_post("tipo")));
			echo json_encode($this->Dashboard_operacionesmodel->getDataDotacion($mes_inicio, $mes_termino,$tipo));
		}
		
		public function analisisCalidad(){
			$this->visitas("Analisis calidad",23);
			$datos=array(
				'mes_inicio' =>  date('Y-m', strtotime('-2 months', strtotime(date("Y-m-d")))),
				'mes_termino' => date('Y-m'),
				'zonas' => $this->Dashboard_operacionesmodel->getZonas(),
				'comunas' => $this->Dashboard_operacionesmodel->getComunas(),
				'supervisores' => $this->Dashboard_operacionesmodel->getSupervisores(),
				'ultima_actualizacion' => $this->Dashboard_operacionesmodel->getUltimaCarga(),
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
			$meses_diferencia = abs((strtotime($mes_termino) - strtotime($mes_inicio)) / (30 * 24 * 60 * 60));


			if($meses_diferencia <= 3 ){

				echo json_encode(array(
					'res' => "ok",
					"data" => $data , 
					"total" => $total
				));

			}else{
				echo json_encode(array("res"=>"error" , "msg" => "La cantidad máxima de meses a mostrar es 3"));
			}

		}

		public function prodCalClaro(){
			$this->visitas("Productividad y calidad claro",23);
			$datos=array(
				'mes_inicio' =>  date('Y-m', strtotime('-3 months', strtotime(date("Y-m-d")))),
				'mes_termino' => date('Y-m'),
				'comunas' => $this->Dashboard_operacionesmodel->getComunasProdCiudad(),
				'supervisores' => $this->Dashboard_operacionesmodel->getSupervisoresProdCiudad(),
				'ultima_actualizacion' => $this->Dashboard_operacionesmodel->getUltimaCarga(),
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
				"prod_zona_eps" => [
					"campos" => ['px_norte_xr3','px_sur_xr3'],
					"cabeceras" => 
						["mes", 
						"PX NORTE", ['role' => 'annotation'], 
						"PX SUR", ['role' => 'annotation'], 
						['role' => 'annotationText']
					]
				],
				"cal_zona_eps" => [
					"campos" => ['ca_norte_xr3','ca_norte_red_cell','ca_sur_xr3','ca_sur_red_cell'],
					"cabeceras" => 
						["mes", 
						"PX NORTE XR3", ['role' => 'annotation'], 
						"PX NORTE RED CELL", ['role' => 'annotation'], 
						"PX SUR XR3", ['role' => 'annotation'], 
						"PX SUR RED CELL", ['role' => 'annotation'], 
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
					"campos" => ['ca_hfc_xr3', 'ca_hfc_alianza_sur', 'ca_hfc_red_cell'],

					"cabeceras" => 
						["mes", 
							"CA HFC XR3", ['role' => 'annotation'], 
							"CA HFC Alianza sur", ['role' => 'annotation'], 
							"CA HFC Red Cell", ['role' => 'annotation'], 
						['role' => 'annotationText']
						]
				],
				"ca_ftth" => [
					"campos" => ['ca_ftth_xr3', 'ca_ftth_alianza_sur', 'ca_ftth_red_cell'],

					"cabeceras" => 
						["mes", 
						"CA FTTH XR3", ['role' => 'annotation'], 
						"CA FTTH Alianza sur", ['role' => 'annotation'], 
						"CA FTTH Red Cell", ['role' => 'annotation'], 
						['role' => 'annotationText']
					]
				],
			];
	
			$datos = array(
				'prod_ciudad' =>$this->Dashboard_operacionesmodel->graficoProdxCiudad($comuna,$supervisor,$tecnologia,$mes_inicio, $mes_termino),
				'prod_general' =>$this->Dashboard_operacionesmodel->graficoProdxEps($campos["general"], $mes_inicio, $mes_termino),
				'prod_zona_eps' =>$this->Dashboard_operacionesmodel->graficoProdxEps($campos["prod_zona_eps"], $mes_inicio, $mes_termino),
				'cal_zona_eps' =>$this->Dashboard_operacionesmodel->graficoProdxEps($campos["cal_zona_eps"], $mes_inicio, $mes_termino),
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
				'ultima_actualizacion' => $this->Dashboard_operacionesmodel->getUltimaCarga(),
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
			$empresa=$this->security->xss_clean(strip_tags($this->input->get_post("empresa")));
			
			$meses_diferencia = abs((strtotime($mes_termino) - strtotime($mes_inicio)) / (30 * 24 * 60 * 60));

			if($meses_diferencia <= 3 ){

				echo json_encode(array(
					'res' => "ok",
					'prod_comuna' => $this->Dashboard_operacionesmodel->getDataProductividadComuna($mes_inicio,$mes_termino,$zona,$comuna,$tecnologia,$empresa)
				));

			}else{
				echo json_encode(array("res"=>"error" , "msg" => "La cantidad máxima de meses a mostrar es 3"));
			}
			
		}

		public function cumpl_factura(){
			$this->visitas("Cumplimieno Facturacion",23);

			$datos=array(
				'anio' =>  date('Y'),
				'anio_f' =>  date('Y'),
				'anios' =>  $this->Dashboard_operacionesmodel->getAnioCumplimientoFacturacion(),
				'meses' =>  $this->Dashboard_operacionesmodel->getMesesCumplimientoFacturacion(),
				'jefes' => $this->Dashboard_operacionesmodel->getJefeCumplimientoFacturacion(),
				'ultima_actualizacion' => $this->Dashboard_operacionesmodel->getUltimaCarga(),
			);
			$this->load->view('back_end/dashboard_operaciones/cumplimiento_facturacion',$datos);
		}

		public function graficoCumpFact(){
			$anio=$this->security->xss_clean(strip_tags($this->input->get_post("anio")));
			$anio_f=$this->security->xss_clean(strip_tags($this->input->get_post("anio_f")));
			$jefe=$this->security->xss_clean(strip_tags($this->input->get_post("jefe")));
			$mes=$this->security->xss_clean(strip_tags($this->input->get_post("mes")));

			$resultados_por_anio = array();
			for ($i = $anio; $i <= $anio_f; $i++) {
				$resultados_por_anio[$i] = $this->Dashboard_operacionesmodel->getDataCumplimientoFacturacion($i, $jefe, $mes);
			}

			$keys = array(); // Array para almacenar las claves distintas
			// Recorremos los datos por año
			foreach ($resultados_por_anio as $year => $technicians) {
				// Verificamos si hay técnicos en el año actual
				if (!empty($technicians)) {
					// Obtenemos el primer técnico del año y obtenemos todas sus claves
					$first_technician_data = reset($technicians);
					$keys = array_merge($keys, array_keys($first_technician_data));
				}
			}
			// Eliminamos las claves duplicadas y conservamos solo las claves distintas
			$keys = array_unique($keys);

			// Inicializamos el array para almacenar los datos unificados
			$merged_data = array();

			// Recorremos los datos por año
			foreach ($resultados_por_anio as $year => $technicians) {
				// Verificamos si hay técnicos en el año actual
				if (!empty($technicians)) {
					// Obtenemos el primer técnico del año
					$first_technician_data = reset($technicians);
					
					// Agregamos las claves de este técnico a $keys
					$keys = array_merge($keys, array_keys($first_technician_data));
					
					// Iteramos sobre los técnicos de este año
					foreach ($technicians as $technician_data) {
						$technician_name = $technician_data['tecnico'];

						// Verificamos si el técnico ya existe en el array unificado
						if (!isset($merged_data[$technician_name])) {
							// Si no existe, creamos un conjunto completo de claves basado en $keys
							$merged_data[$technician_name] = array_fill_keys($keys, "");
						}

						// Completamos los datos del técnico para el año actual con los valores disponibles
						foreach ($technician_data as $key => $value) {
							// Actualizamos el valor solo si no es nulo o si ya está establecido como una cadena vacía
							if ($value !== null || $merged_data[$technician_name][$key] === "") {
								$merged_data[$technician_name][$key] = $value;
							}
						}
					}
				}
			}

			// Convertimos todas las cadenas vacías a ""
			$merged_data = array_map(function ($row) {
				return array_map(function ($value) {
					return $value === null ? "" : $value;
				}, $row);
			}, $merged_data);

			// Convertimos el array asociativo en un array simple
			$merged_data = array_values($merged_data);

			$data = $this->Dashboard_operacionesmodel->getDataCumplimientoFacturacion($anio,$jefe,$mes);
			echo json_encode(array("data" =>$data, "data2" => $merged_data));exit;
		}

		public function getCabecerasCumplimientoFacturacion(){
			$data=json_decode(file_get_contents('php://input'),1);
			$jefe=$data["jefe"];
			$anio=$data["anio"];
			$anio_f=$data["anio_f"];
			$mes=$data["mes"];

			$resultados_por_anio = array();

			for ($i = $anio; $i <= $anio_f; $i++) {
				$resultados_por_anio[$i] = $this->Dashboard_operacionesmodel->getCabecerasCumplimientoFacturacion($i, $jefe, $mes);
			}

			$data = $this->Dashboard_operacionesmodel->getCabecerasCumplimientoFacturacion($anio,$jefe,$mes);

			echo json_encode(array("data" => $data, "data2" => $resultados_por_anio));
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
					$datos["ultima_actualizacion"] = $date('Y-m-d');
					
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
						"emetel_x_eps_hfc_sur", "xr3_x_eps_ftth_sur", "jr_x_eps_ftth_sur", "emetel_x_eps_ftth_sur", "meta","meta_hfc_epps","meta_ftth_epps","meta_px_epps"
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
						} 
						elseif ($index === 40 || $index === 41 || $index === 42 ) {
						} 
						else {
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
						"acc", "claro","promedio_claro"
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
			
					$columnas_analisis_cal= ["anio", "mes", "orden_mes", "zona", "supervisor", "comuna", "ftth", "hfc", "total_general", "tecnologia","meta"];
			
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
			
					$columnas_prod_cal_claro = [
					'anio','mes','orden_mes', 'px_xr3', 'px_hfc_xr3', 'px_ftth_xr3',
					'px_norte_xr3','px_sur_xr3',
					'px_alianza_sur', 'px_hfc_alianza_sur', 'px_ftth_alianza_sur', 'px_red_cell', 'px_hfc_red_cell', 'px_ftth_red_cell', 'ca_hfc_xr3', 'ca_ftth_xr3',
					'ca_norte_xr3','ca_sur_xr3', 
					'ca_hfc_alianza_sur','ca_ftth_alianza_sur', 
					'ca_hfc_red_cell','ca_ftth_red_cell','ca_norte_red_cell','ca_sur_red_cell',
					'meta_ca_ftth','meta_ca_hfc', 'meta_px_hfc', 'meta_px_ftth'
					];

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

			//CUMPLIMIENTO DE FACTURACIÓN

				$hoja_px_cumplimiento = $spreadsheet->getSheet(8);
				$ultima_fila_px_cumplimiento  = $hoja_px_cumplimiento->getHighestRow();

				$this->db->query("TRUNCATE TABLE dashboard_cumplimiento_facturacion");
				$filas_px_cumplimiento= 0;

				for ($fila = 2; $fila <= $ultima_fila_px_cumplimiento; $fila++) {
					$datos = array();
			
					$columnas_px_comuna = ['px_as','px_cm','px_ca','jefe','tecnico','mes','anio'];

					$mes = '';
					$anio = '';
					$flag = false;

					foreach ($columnas_px_comuna as $index => $columna) {
						$valor = $hoja_px_cumplimiento->getCellByColumnAndRow($index+1 , $fila)->getFormattedValue();

								if ($index === 5) { //MES
									if($valor == ""){$flag=true;}
									$mes = obtenerNumeroMes(trim($valor));
									$datos[$columna] = $mes;
								}elseif ($index === 6) { //AÑO
									if($valor == ""){$flag=true;}
									$anio = $valor;
									$datos[$columna] = $valor;
								}elseif ($index === 3) { //JEFE
									if($valor == ""){$flag=true;}
									$anio = $valor;
									$datos[$columna] = $valor;
								}elseif ($index === 4) { //TECNICO
									if($valor == ""){$flag=true;}
									$anio = $valor;
									$datos[$columna] = $valor;
								}else{
									$valor = $hoja_px_cumplimiento->getCellByColumnAndRow($index +1, $fila)->getFormattedValue();
									$datos[$columna] = $valor;
								}
					}	
					if(!$flag){
					$this->Dashboard_operacionesmodel->formCumpFact($datos);
					$filas_px_cumplimiento++;
					}
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
				'.$filas_px_comuna.' filas de px x comuna insertadas,
				'.$filas_px_cumplimiento.' filas de cumplimiento de facturación,'
				
			));

			exit;
		}
		

}