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

			$tipos = [
				"nac" => [
					"campos" => ['hfc_na', 'ftth_na'],
					"cabeceras" => ["mes", "HFC", ['role' => 'annotation'], "FTTH", ['role' => 'annotation'], ['role' => 'annotationText']]
				],
				"nortehfc" => [
					"campos" => ['hfc_nor', 'meta'],
					"cabeceras" => ["mes", "HFC Norte", ['role' => 'annotation'], "Meta", ['role' => 'annotation'], ['role' => 'annotationText']]
				],
				"norteftth" => [
					"campos" => ['ftth_nor', 'meta'],
					"cabeceras" => ["mes", "FTTH Norte", ['role' => 'annotation'], "Meta", ['role' => 'annotation'], ['role' => 'annotationText']]
				],
				"surhfc" => [
					"campos" => ['hfc_sur', 'meta'],
					"cabeceras" => ["mes", "HFC Sur", ['role' => 'annotation'], "Meta", ['role' => 'annotation'], ['role' => 'annotationText']]
				],
				"surftth" => [
					"campos" => ['ftth_sur', 'meta'],
					"cabeceras" => ["mes", "FTTH Sur", ['role' => 'annotation'], "Meta", ['role' => 'annotation'], ['role' => 'annotationText']]
				]
			];

	 
			$datos = array(
				'productividadnacional' => $this->Dashboard_operacionesmodel->getDataProductividad($tipos["nac"], $mes_inicio, $mes_termino),
				'productividadnorteHFC' => $this->Dashboard_operacionesmodel->getDataProductividad($tipos["nortehfc"], $mes_inicio, $mes_termino),
				'productividadnorteFTTH' => $this->Dashboard_operacionesmodel->getDataProductividad($tipos["norteftth"], $mes_inicio, $mes_termino),
				'productividadsurHFC' => $this->Dashboard_operacionesmodel->getDataProductividad($tipos["surhfc"], $mes_inicio, $mes_termino),
				'productividadsurFTTH' => $this->Dashboard_operacionesmodel->getDataProductividad($tipos["surftth"], $mes_inicio, $mes_termino),
			);
		
			echo json_encode($datos);

		}
		
		public function cargaDashboardProductividadXR3() {
			$archivo = $_FILES['userfile']['tmp_name'];
			$spreadsheet = IOFactory::load($archivo);
			$hoja = $spreadsheet->getSheet(0);
			$ultima_fila = $hoja->getHighestRow();
			
			$this->db->query("TRUNCATE TABLE dashboard_productividad");
			$filas_insertadas = 0;

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
				
				$this->Dashboard_operacionesmodel->formProductividadCalidadXr3($datos);
				$filas_insertadas++;
			}

			
			echo json_encode(array(
				'res' => 'ok',
				'tipo' => 'success',
				'msg' => 'Archivo cargado con éxito,'.$filas_insertadas.' filas insertadas.'
			));
			exit;
			
		}
		

}