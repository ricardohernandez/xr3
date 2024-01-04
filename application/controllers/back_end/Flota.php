<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Flota extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model("back_end/Flotamodel");
		$this->load->model("back_end/Iniciomodel");
		$this->load->model("back_end/Calidadmodel");
		$this->load->helper(array('fechas','str'));
		$this->load->library('user_agent');
	}


	public function checkLogin(){
		if($this->session->userdata("id")==""){
			redirect("../unlogin");
		}
	}
	
	public function acceso(){
		if($this->session->userdata('id')!=""){
			if($this->session->userdata('id_perfil')!=1){
	      		redirect("./login");
	      	}
	      }else{
	      	redirect("./login");
	    }
	}

	public function formCargaMasivaFlota(){

		if (!function_exists('str_contains')) {
		    function str_contains(string $haystack, string $needle): bool
		    {
		        return '' === $needle || false !== strpos($haystack, $needle);
		    }
		}

		if($_FILES['userfile']['name']==""){
		    echo json_encode(array('res'=>'error',  "tipo" => "error" , 'msg'=>"Debe seleccionar un archivo."));exit;
		}
        $fname = $_FILES['userfile']['name'];
        $chk_ext = explode(".",$fname);

        if(strtolower(end($chk_ext)) == "xlsx")  {

			$archivo = $_FILES['userfile']['tmp_name'];
			$spreadsheet = IOFactory::load($archivo);
			$i = 0;
			$ultima_actualizacion = date("Y-m-d G:i:s");

			//COMBUSTIBLE
				$hoja = $spreadsheet->getSheet(0);
				$ultima_fila = $hoja->getHighestRow();
				$filas_combustible = 0;

				$columnas = array(
					'patente',
					'rut_chofer',
					'nombre_chofer',
					'nombre_supervisor',
					'region',
					'meta_litros_mensual',
					'volumen',
					'meta_kms_mensual',
					'kms_recorridos',
					'meta_monto',
					'monto',
					'fecha',
					'hora',
					'odometro',
				);

				$this->db->query("TRUNCATE TABLE flota_combustible");

				for ($fila = 2; $fila <= $ultima_fila; $fila++) {
					if($hoja->getCellByColumnAndRow(1, $fila)->getValue() != ""){
						$datos = array();
						foreach ($columnas as $index => $columna) {
							if($hoja->getCellByColumnAndRow($index + 1, $fila)->getValue() != NULL){
								if ($index === 11) {
									$fecha = date('Y-m-d', strtotime('1900-01-00') + (($hoja->getCellByColumnAndRow($index + 1, $fila)->getValue() - 1) * 86400));
									$datos[$columna] = $fecha;
								}
								elseif ($index === 12){
									$fecha = date('H:i:s', strtotime('1900-01-00') + (($hoja->getCellByColumnAndRow($index + 1, $fila)->getValue() - 1) * 86400));
									$datos[$columna] = $fecha;
								}
								else{
									$datos[$columna] = $hoja->getCellByColumnAndRow($index + 1, $fila)->getValue();
								}
							}
							else{
								$datos[$columna] = "-";
							}
						}

						$datos["ultima_actualizacion"] = $ultima_actualizacion;

						$this->Flotamodel->insertarFlotaCombustible($datos);
						$filas_combustible++;
						$i++;
					}
				}


			//BLACK
				$hoja = $spreadsheet->getSheet(1);
				$ultima_fila = $hoja->getHighestRow();
				$filas_black = 0;

				$columnas = array(
					'patente',
					'velocidad',
					'fecha',
					'direccion',
					'nombre_chofer',
					'nombre_supervisor',
					'comuna',
					'zona',
					'num_semana',
				);

				$this->db->query("TRUNCATE TABLE flota_gps");

				for ($fila = 2; $fila <= $ultima_fila; $fila++) {
					if($hoja->getCellByColumnAndRow(1, $fila)->getValue() != ""){
						$datos = array();
						foreach ($columnas as $index => $columna) {
							if($hoja->getCellByColumnAndRow($index + 1, $fila)->getValue() != NULL){
								if ($index === 2) {
									$fecha = date('Y-m-d H:i:s', strtotime('1900-01-00') + (($hoja->getCellByColumnAndRow($index + 1, $fila)->getValue() - 1) * 86400));
									$datos[$columna] = $fecha;
								}
								else{
									$datos[$columna] = $hoja->getCellByColumnAndRow($index + 1, $fila)->getValue();
								}
							}
							else{
								$datos[$columna] = "-";
							}
						}

						$datos["gps"] = "BLACK";
						$datos["ultima_actualizacion"] = $ultima_actualizacion;

						$this->Flotamodel->insertarFlotaGPS($datos);
						$filas_black++;
						$i++;
					}
				}


			
			//SALFA
				$hoja = $spreadsheet->getSheet(2);
				$ultima_fila = $hoja->getHighestRow();
				$filas_salfa = 0;

				$columnas = array(
					'patente',
					'velocidad',
					'fecha',
					'direccion',
					'nombre_chofer',
					'nombre_supervisor',
					'comuna',
					'zona',
					'num_semana',
				);

				for ($fila = 2; $fila <= $ultima_fila; $fila++) {
					if($hoja->getCellByColumnAndRow(1, $fila)->getValue() != ""){
						$datos = array();
						foreach ($columnas as $index => $columna) {
							if($hoja->getCellByColumnAndRow($index + 1, $fila)->getValue() != NULL){
								if ($index === 2) {
									$fecha = date('Y-m-d H:i:s', strtotime('1900-01-00') + (($hoja->getCellByColumnAndRow($index + 1, $fila)->getValue() - 1) * 86400));
									$datos[$columna] = $fecha;
								}
								else{
									$datos[$columna] = $hoja->getCellByColumnAndRow($index + 1, $fila)->getValue();
								}
							}
							else{
								$datos[$columna] = "-";
							}
						}

						$datos["gps"] = "SALFA";
						$datos["ultima_actualizacion"] = $ultima_actualizacion;

						$this->Flotamodel->insertarFlotaGPS($datos);
						$filas_salfa++;
						$i++;
					}
				}

				echo json_encode(array(
					'res' => 'ok',
					'tipo' => 'success',
					'msg' => 'Archivo cargado con éxito.
					'.$filas_combustible.' filas combustible insertadas,
					'.$filas_black.' filas de black gps insertadas,
					'.$filas_salfa.' filas de gps salfa insertadas'
					
				));exit;

           	echo json_encode(array('res'=>'ok', "tipo" => "success", 'msg' => "Archivo cargado con éxito, ".$i." filas insertadas."));exit;
        }else{
        	echo json_encode(array('res'=>'error', "tipo" => "error", 'msg' => "Archivo inválido."));exit;
        }   
	}

	public function visitas($modulo){
		$this->load->library('user_agent');
		$data=array("id_usuario"=>$this->session->userdata('id'),
			"id_aplicacion"=>10,
			"modulo"=>$modulo,
	     	"fecha"=>date("Y-m-d G:i:s"),
	    	"navegador"=>"navegador :".$this->agent->browser()."\nversion :".$this->agent->version()."\nos :".$this->agent-> platform()."\nmovil :".$this->agent->mobile(),
	    	"ip"=>$this->input->ip_address(),
    	);
    	$this->Flotamodel->insertarVisita($data);
	}

	public function index(){
		//$this->acceso();
	    $datos = array(
	        'titulo' => "Flota - Indicadores de gestión de flota",
	        'contenido' => "flota/inicio",
	        'perfiles' => $this->Iniciomodel->listaPerfiles(),
	    );  
		$this->load->view('plantillas/plantilla_back_end',$datos);
	}

	public function getFlotaInicio(){
		$this->visitas("Inicio");
		if($this->input->is_ajax_request()){
			$desde=date('Y-m-01');
			$hasta=date('Y-12-t');
			$patentes= $this->Flotamodel->getPatenteCombustible();
			$supervisores= $this->Flotamodel->getSupervisorCombustible();
			$choferes= $this->Flotamodel->getChoferCombustible();
			$comunas= $this->Flotamodel->getComunaCombustible();
			$datos = array(	
				'desde' => $desde,
		        'hasta' => $hasta,
		        'patentes' => $patentes,
		        'supervisores' => $supervisores,
		        'choferes' => $choferes,
		        'comunas' => $comunas,

		    );
			$this->load->view('back_end/flota/flota',$datos);
		}

	}

	/************ COMBUSTIBLE ************/

	public function listaCombustible(){
		$desde=$this->security->xss_clean(strip_tags($this->input->get_post("desde")));
		$hasta=$this->security->xss_clean(strip_tags($this->input->get_post("hasta")));
		$asignacion=$this->security->xss_clean(strip_tags($this->input->get_post("asignacion")));
		$supervisor=$this->security->xss_clean(strip_tags($this->input->get_post("supervisor")));
		$patente=$this->security->xss_clean(strip_tags($this->input->get_post("patente")));
		$comuna=$this->security->xss_clean(strip_tags($this->input->get_post("comuna")));
		echo json_encode($this->Flotamodel->listaCombustible($desde,$hasta,$asignacion,$supervisor,$patente,$comuna));
	}	

	public function listaMax(){
		$desde=$this->security->xss_clean(strip_tags($this->input->get_post("desde")));
		$hasta=$this->security->xss_clean(strip_tags($this->input->get_post("hasta")));
		$asignacion=$this->security->xss_clean(strip_tags($this->input->get_post("asignacion")));
		$supervisor=$this->security->xss_clean(strip_tags($this->input->get_post("supervisor")));
		$patente=$this->security->xss_clean(strip_tags($this->input->get_post("patente")));
		$comuna=$this->security->xss_clean(strip_tags($this->input->get_post("comuna")));
		echo json_encode($this->Flotamodel->listaMax($desde,$hasta,$asignacion,$supervisor,$patente,$comuna));
	}	

	public function listaCarga(){
		$desde=$this->security->xss_clean(strip_tags($this->input->get_post("desde")));
		$hasta=$this->security->xss_clean(strip_tags($this->input->get_post("hasta")));
		$asignacion=$this->security->xss_clean(strip_tags($this->input->get_post("asignacion")));
		$supervisor=$this->security->xss_clean(strip_tags($this->input->get_post("supervisor")));
		$patente=$this->security->xss_clean(strip_tags($this->input->get_post("patente")));
		$comuna=$this->security->xss_clean(strip_tags($this->input->get_post("comuna")));
		echo json_encode(array("data"=>$this->Flotamodel->listaCarga($desde,$hasta,$asignacion,$supervisor,$patente,$comuna)));
	}

	public function getActualizacionCombustible(){
		echo json_encode($this->Flotamodel->getActualizacionCombustible());
	}	

	/************** GPS *********************/

	public function getGPSInicio(){
		$this->visitas("Inicio");
		if($this->input->is_ajax_request()){
			$gps=$this->security->xss_clean(strip_tags($this->input->get_post("gps")));
			$desde=date('Y-m-01');
			$hasta=date('Y-12-t');
			$patentes= $this->Flotamodel->getPatenteGPS();
			$supervisores= $this->Flotamodel->getSupervisorGPS();
			$choferes= $this->Flotamodel->getChoferGPS();
			$datos = array(	
				'desde' => $desde,
		        'hasta' => $hasta,
		        'patentes' => $patentes,
		        'supervisores' => $supervisores,
		        'choferes' => $choferes,
				'gps' => $gps,
		    );

			$this->load->view('back_end/flota/gps',$datos);
		}

	}

	public function listaGPS(){
		$desde=$this->security->xss_clean(strip_tags($this->input->get_post("desde")));
		$hasta=$this->security->xss_clean(strip_tags($this->input->get_post("hasta")));
		$chofer=$this->security->xss_clean(strip_tags($this->input->get_post("chofer")));
		$supervisor=$this->security->xss_clean(strip_tags($this->input->get_post("supervisor")));
		$patente=$this->security->xss_clean(strip_tags($this->input->get_post("patente")));
		$gps=$this->security->xss_clean(strip_tags($this->input->get_post("gps")));
		echo json_encode($this->Flotamodel->listaGPS($desde,$hasta,$chofer,$supervisor,$patente,$gps));
	}	
	public function listaDetalleFlota(){
		$desde=$this->security->xss_clean(strip_tags($this->input->get_post("desde")));
		$hasta=$this->security->xss_clean(strip_tags($this->input->get_post("hasta")));
		$chofer=$this->security->xss_clean(strip_tags($this->input->get_post("chofer")));
		$supervisor=$this->security->xss_clean(strip_tags($this->input->get_post("supervisor")));
		$patente=$this->security->xss_clean(strip_tags($this->input->get_post("patente")));
		$gps=$this->security->xss_clean(strip_tags($this->input->get_post("gps")));
		echo json_encode($this->Flotamodel->listaDetalleFlota($desde,$hasta,$chofer,$supervisor,$patente,$gps));
	}	

	public function listaTotal(){
		$desde=$this->security->xss_clean(strip_tags($this->input->get_post("desde")));
		$hasta=$this->security->xss_clean(strip_tags($this->input->get_post("hasta")));
		$chofer=$this->security->xss_clean(strip_tags($this->input->get_post("chofer")));
		$supervisor=$this->security->xss_clean(strip_tags($this->input->get_post("supervisor")));
		$patente=$this->security->xss_clean(strip_tags($this->input->get_post("patente")));
		$gps=$this->security->xss_clean(strip_tags($this->input->get_post("gps")));
		echo json_encode(array("data"=>$this->Flotamodel->ListaTotal($desde,$hasta,$chofer,$supervisor,$patente,$gps)));
	}	
	public function getActualizacionGPS(){
		$gps=$this->security->xss_clean(strip_tags($this->input->get_post("gps")));
		echo json_encode($this->Flotamodel->getActualizacionGPS($gps));
	}	
}