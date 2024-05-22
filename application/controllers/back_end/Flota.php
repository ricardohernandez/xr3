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

				$chofer=array();

				for ($fila = 2; $fila <= $ultima_fila; $fila++) {
					if($hoja->getCellByColumnAndRow(1, $fila)->getValue() != ""){
						$datos = array();
						foreach ($columnas as $index => $columna) {
							if($hoja->getCellByColumnAndRow($index + 1, $fila)->getValue() != NULL){
								if ($index === 11) {
									$fecha = date('Y-m-d', strtotime('1900-01-00') + ((intval($hoja->getCellByColumnAndRow($index + 1, $fila)->getValue()) - 1) * 86400));
									$datos[$columna] = $fecha;
								}
								elseif ($index === 0){ //PATENTE
									$patente = ($hoja->getCellByColumnAndRow($index + 1, $fila)->getValue());
									$patente_sin_guion = str_replace('-', '', $patente);
									$patente_sin_guion = str_replace(' ', '', $patente_sin_guion);
									$datos[$columna] = $patente_sin_guion;

									if (!isset($chofer[$patente_sin_guion])) {
										if($hoja->getCellByColumnAndRow(2, $fila)->getValue() == NULL){
											$rut_chofer = "-";
										}else{
											$rut_chofer = $hoja->getCellByColumnAndRow(2, $fila)->getValue();
										}
										if($hoja->getCellByColumnAndRow(3, $fila)->getValue() == NULL){
											$nombre_chofer = "-";
										}else{
											$nombre_chofer = $hoja->getCellByColumnAndRow(3, $fila)->getValue();
										}
										$chofer[$patente_sin_guion] = array(
											'rut_chofer' => $rut_chofer,
											'nombre_chofer' => $nombre_chofer
										);
									}
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

			$this->db->query("TRUNCATE TABLE flota_gps");
			
			//MUEVO

			$hoja = $spreadsheet->getSheetByName("MUEVO");
			$ultima_fila = $hoja->getHighestRow();
			$filas_muevo = 0;

			$columnas = array(
				'fecha',
				'nombre_chofer',
				'patente',
				'odometro',
				'metodo_pago',
				'direccion',
				'monto',
				'num_folio',
				'factura',
				'rut_chofer',
				'nombre_supervisor',
				'zona',
				'region',
				'meta_litros_mensual',
			);

			$this->db->query("TRUNCATE TABLE flota_gps_muevo");

			for ($fila = 2; $fila <= $ultima_fila; $fila++) {
				if($hoja->getCellByColumnAndRow(1, $fila)->getValue() != ""){
					$datos = array();
					foreach ($columnas as $index => $columna) {
						if($hoja->getCellByColumnAndRow($index + 1, $fila)->getValue() != NULL){
							if ($index === 0) {
								$fecha = date('Y-m-d', strtotime('1900-01-00') + ((intval($hoja->getCellByColumnAndRow($index + 1, $fila)->getValue()) - 1) * 86400));
								$datos[$columna] = $fecha;
							}
							elseif ($index === 2){ //PATENTE
								$patente = ($hoja->getCellByColumnAndRow($index + 1, $fila)->getValue());
								$patente_sin_guion = str_replace('-', '', $patente);
								$patente_sin_guion = str_replace(' ', '', $patente_sin_guion);
								$datos[$columna] = $patente_sin_guion;

								if (!isset($chofer[$patente_sin_guion])) {
									if($hoja->getCellByColumnAndRow(10, $fila)->getValue() == NULL){
										$rut_chofer = "-";
									}else{
										$rut_chofer = $hoja->getCellByColumnAndRow(10, $fila)->getValue();
									}
									if($hoja->getCellByColumnAndRow(2, $fila)->getValue() == NULL){
										$nombre_chofer = "-";
									}else{
										$nombre_chofer = $hoja->getCellByColumnAndRow(2, $fila)->getValue();
									}
									$chofer[$patente_sin_guion] = array(
										'rut_chofer' => $rut_chofer,
										'nombre_chofer' => $nombre_chofer
									);
								}
							}
							else{
								$datos[$columna] = strval($hoja->getCellByColumnAndRow($index + 1, $fila)->getValue());
							}
						}
						else{
							$datos[$columna] = "-";
						}
					}
					$datos["gps"] = "MUEVO";
					$datos["ultima_actualizacion"] = $ultima_actualizacion;

					$this->Flotamodel->insertarFlotaGPSMuevo($datos);
					$filas_muevo++;
					$i++;
				}
			}

			//WEBFLEET
				$hoja = $spreadsheet->getSheetByName("GPS WEBFLEET");
				$ultima_fila = $hoja->getHighestRow();
				$filas_gps = 0;

				$columnas = array(
					'fecha',
					'patente',
					'hora_inicio',
					'hora_fin',
					'rut',
					'nombre_chofer',
					'direccion_inicio',
					'direccion_fin',
					'duracion',
					'v_max',
					'lim_v',
				);

				for ($fila = 2; $fila <= $ultima_fila; $fila++) {
					if($hoja->getCellByColumnAndRow(1, $fila)->getValue() != ""){
						$datos = array();
						foreach ($columnas as $index => $columna) {
							if($hoja->getCellByColumnAndRow($index + 1, $fila)->getValue() != NULL){
								if ($index === 0) {
									$fecha = date('Y-m-d H:i:s', strtotime('1900-01-00') + ((intval($hoja->getCellByColumnAndRow($index + 1, $fila)->getValue()) - 1) * 86400));
									$datos[$columna] = $fecha;
								}
								elseif ($index === 1){ //PATENTE
									$patente = ($hoja->getCellByColumnAndRow($index + 1, $fila)->getValue());
									$patente_sin_guion = str_replace('-', '', $patente);
									$patente_sin_guion = str_replace(' ', '', $patente_sin_guion);
									$datos[$columna] = $patente_sin_guion;

									//si existe la patente en la hoja anterior
									if(isset($chofer[$patente_sin_guion])){
										$datos["rut"] = $chofer[$patente_sin_guion]["rut_chofer"];
										$datos["nombre_chofer"] = $chofer[$patente_sin_guion]["nombre_chofer"];
									}
									else{
										$datos["rut"] = "-";
										$datos["nombre_chofer"] = "-";
									}
								}
								elseif ($index === 2) {
									$fecha = date('g:i:s A', strtotime('1900-01-00') + ((($hoja->getCellByColumnAndRow($index + 1, $fila)->getValue()) - 1) * 86400));
									$datos[$columna] = $fecha;
								}
								elseif ($index === 3) {
									$fecha = date('g:i:s A', strtotime('1900-01-00') + ((($hoja->getCellByColumnAndRow($index + 1, $fila)->getValue()) - 1) * 86400));
									$datos[$columna] = $fecha;
								}
								elseif ($index === 8) {
									$fecha = date('g:i:s A', strtotime('1900-01-00') + ((($hoja->getCellByColumnAndRow($index + 1, $fila)->getValue()) - 1) * 86400));
									$datos[$columna] = $fecha;
								}
								elseif ($index === 4 or $index === 5) {
								}
								else{
									$datos[$columna] = $hoja->getCellByColumnAndRow($index + 1, $fila)->getValue();
								}
							}
							else{
								if ($index === 4 or $index === 5) {
								}else{
									$datos[$columna] = "-";
								}
							}
						}

						$datos["gps"] = "WEBFLEET";
						$datos["ultima_actualizacion"] = $ultima_actualizacion;
						//echo(json_encode($datos));exit;

						$this->Flotamodel->insertarFlotaGPS($datos);
						$filas_gps++;
						$i++;
					}
				}


			echo json_encode(array(
				'res' => 'ok',
				'tipo' => 'success',
				'msg' => 'Archivo cargado con éxito.
				'.$filas_combustible.' filas combustible insertadas,
				'.$filas_gps.' filas de gps webfleet insertadas.
				'.$filas_muevo.' filas de gps muevo insertadas.'
				
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
			$desde=date('Y-m-d', strtotime("-1 year"));
			$hasta=date('Y-m-d');
			$patentes= $this->Flotamodel->getPatenteCombustible();
			$supervisores= $this->Flotamodel->getSupervisorCombustible();
			$choferes= $this->Flotamodel->getChoferCombustible();
			$regiones= $this->Flotamodel->getRegionCombustible();
			$datos = array(	
				'desde' => $desde,
		        'hasta' => $hasta,
		        'patentes' => $patentes,
		        'supervisores' => $supervisores,
		        'choferes' => $choferes,
		        'regiones' => $regiones,
		    );
			$this->load->view('back_end/flota/flota',$datos);
		}

	}

	/************ COMBUSTIBLE ************/

		public function listaCombustible(){
			$desde=$this->security->xss_clean(strip_tags($this->input->get_post("desde")));
			$hasta=$this->security->xss_clean(strip_tags($this->input->get_post("hasta")));
			$chofer=$this->security->xss_clean(strip_tags($this->input->get_post("chofer")));
			$supervisor=$this->security->xss_clean(strip_tags($this->input->get_post("supervisor")));
			$patente=$this->security->xss_clean(strip_tags($this->input->get_post("patente")));
			$region=$this->security->xss_clean(strip_tags($this->input->get_post("region")));
			
			
			echo json_encode($this->Flotamodel->listaCombustible($desde,$hasta,$chofer,$supervisor,$patente,$region));
		}	

		public function listaMax(){
			$desde=$this->security->xss_clean(strip_tags($this->input->get_post("desde")));
			$hasta=$this->security->xss_clean(strip_tags($this->input->get_post("hasta")));
			$chofer=$this->security->xss_clean(strip_tags($this->input->get_post("chofer")));
			$supervisor=$this->security->xss_clean(strip_tags($this->input->get_post("supervisor")));
			$patente=$this->security->xss_clean(strip_tags($this->input->get_post("patente")));
			$region=$this->security->xss_clean(strip_tags($this->input->get_post("region")));
			
			
			echo json_encode($this->Flotamodel->listaMax($desde,$hasta,$chofer,$supervisor,$patente,$region));
		}	

		public function listaCarga(){
			$desde=$this->security->xss_clean(strip_tags($this->input->get_post("desde")));
			$hasta=$this->security->xss_clean(strip_tags($this->input->get_post("hasta")));
			$chofer=$this->security->xss_clean(strip_tags($this->input->get_post("chofer")));
			$supervisor=$this->security->xss_clean(strip_tags($this->input->get_post("supervisor")));
			$patente=$this->security->xss_clean(strip_tags($this->input->get_post("patente")));
			$region=$this->security->xss_clean(strip_tags($this->input->get_post("region")));
			
			
			echo json_encode(array("data"=>$this->Flotamodel->listaCarga($desde,$hasta,$chofer,$supervisor,$patente,$region)));
		}

		public function GastoRegion(){
			$desde=$this->security->xss_clean(strip_tags($this->input->get_post("desde")));
			$hasta=$this->security->xss_clean(strip_tags($this->input->get_post("hasta")));
			$chofer=$this->security->xss_clean(strip_tags($this->input->get_post("chofer")));
			$supervisor=$this->security->xss_clean(strip_tags($this->input->get_post("supervisor")));
			$patente=$this->security->xss_clean(strip_tags($this->input->get_post("patente")));
			$region=$this->security->xss_clean(strip_tags($this->input->get_post("region")));
			
			
			echo json_encode(array("data"=>$this->Flotamodel->GastoRegion($desde,$hasta,$chofer,$supervisor,$patente,$region)));
		}

		public function GastoSemana(){
			$desde=$this->security->xss_clean(strip_tags($this->input->get_post("desde")));
			$hasta=$this->security->xss_clean(strip_tags($this->input->get_post("hasta")));
			$chofer=$this->security->xss_clean(strip_tags($this->input->get_post("chofer")));
			$supervisor=$this->security->xss_clean(strip_tags($this->input->get_post("supervisor")));
			$patente=$this->security->xss_clean(strip_tags($this->input->get_post("patente")));
			$region=$this->security->xss_clean(strip_tags($this->input->get_post("region")));
			
			
			echo json_encode(array("data"=>$this->Flotamodel->GastoSemana($desde,$hasta,$chofer,$supervisor,$patente,$region)));
		}

		public function GastoCombustibleRegion(){
			$desde=$this->security->xss_clean(strip_tags($this->input->get_post("desde")));
			$hasta=$this->security->xss_clean(strip_tags($this->input->get_post("hasta")));
			$chofer=$this->security->xss_clean(strip_tags($this->input->get_post("chofer")));
			$supervisor=$this->security->xss_clean(strip_tags($this->input->get_post("supervisor")));
			$patente=$this->security->xss_clean(strip_tags($this->input->get_post("patente")));
			$region=$this->security->xss_clean(strip_tags($this->input->get_post("region")));
			
			
			echo json_encode(array("data"=>$this->Flotamodel->GastoCombustibleRegion($desde,$hasta,$chofer,$supervisor,$patente,$region)));
		}

		public function GastoCombustibleSemana(){
			$desde=$this->security->xss_clean(strip_tags($this->input->get_post("desde")));
			$hasta=$this->security->xss_clean(strip_tags($this->input->get_post("hasta")));
			$chofer=$this->security->xss_clean(strip_tags($this->input->get_post("chofer")));
			$supervisor=$this->security->xss_clean(strip_tags($this->input->get_post("supervisor")));
			$patente=$this->security->xss_clean(strip_tags($this->input->get_post("patente")));
			$region=$this->security->xss_clean(strip_tags($this->input->get_post("region")));
			
			
			echo json_encode(array("data"=>$this->Flotamodel->GastoCombustibleSemana($desde,$hasta,$chofer,$supervisor,$patente,$region)));
		}

		public function getActualizacionCombustible(){
			$desde=$this->security->xss_clean(strip_tags($this->input->get_post("desde")));
			$hasta=$this->security->xss_clean(strip_tags($this->input->get_post("hasta")));
			echo json_encode($this->Flotamodel->getActualizacionCombustible($desde, $hasta));
		}	

	/************** GPS *********************/

		public function getGPSInicio(){
			$this->visitas("Inicio");
			if($this->input->is_ajax_request()){
				$gps=$this->security->xss_clean(strip_tags($this->input->get_post("gps")));
				$desde = date('Y-m-d', strtotime("-1 month"));
				$hasta=date('Y-m-d');
				$patentes= $this->Flotamodel->getPatenteGPS($gps);
				//$supervisores= $this->Flotamodel->getSupervisorGPS($gps);
				$choferes= $this->Flotamodel->getChoferGPS($gps);
				//$comunas= $this->Flotamodel->getComunaGPS($gps);
				$datos = array(	
					'desde' => $desde,
					'hasta' => $hasta,
					'patentes' => $patentes,
					//'supervisores' => $supervisores,
					'choferes' => $choferes,
					//'comunas' => $comunas,
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
			$comuna=$this->security->xss_clean(strip_tags($this->input->get_post("comuna")));
			$gps=$this->security->xss_clean(strip_tags($this->input->get_post("gps")));
			echo json_encode($this->Flotamodel->listaGPS($desde,$hasta,$chofer,$supervisor,$patente,$comuna,$gps));
		}	

		public function listaDetalleFlota(){
			$desde=$this->security->xss_clean(strip_tags($this->input->get_post("desde")));
			$hasta=$this->security->xss_clean(strip_tags($this->input->get_post("hasta")));
			$chofer=$this->security->xss_clean(strip_tags($this->input->get_post("chofer")));
			$supervisor=$this->security->xss_clean(strip_tags($this->input->get_post("supervisor")));
			$patente=$this->security->xss_clean(strip_tags($this->input->get_post("patente")));
			$comuna=$this->security->xss_clean(strip_tags($this->input->get_post("comuna")));
			$gps=$this->security->xss_clean(strip_tags($this->input->get_post("gps")));
			echo json_encode($this->Flotamodel->listaDetalleFlota($desde,$hasta,$chofer,$supervisor,$patente,$comuna,$gps));
		}	

		public function listaTotal(){
			$desde=$this->security->xss_clean(strip_tags($this->input->get_post("desde")));
			$hasta=$this->security->xss_clean(strip_tags($this->input->get_post("hasta")));
			$chofer=$this->security->xss_clean(strip_tags($this->input->get_post("chofer")));
			$supervisor=$this->security->xss_clean(strip_tags($this->input->get_post("supervisor")));
			$patente=$this->security->xss_clean(strip_tags($this->input->get_post("patente")));
			$comuna=$this->security->xss_clean(strip_tags($this->input->get_post("comuna")));
			$gps=$this->security->xss_clean(strip_tags($this->input->get_post("gps")));
			echo json_encode(array("data"=>$this->Flotamodel->ListaTotal($desde,$hasta,$chofer,$supervisor,$patente,$comuna,$gps)));
		}	

		public function getActualizacionGPS(){
			$gps=$this->security->xss_clean(strip_tags($this->input->get_post("gps")));
			$desde=$this->security->xss_clean(strip_tags($this->input->get_post("desde")));
			$hasta=$this->security->xss_clean(strip_tags($this->input->get_post("hasta")));
			echo json_encode($this->Flotamodel->getActualizacionGPS($gps,$desde,$hasta));
		}	

	/********MUEVO ***********/

		public function getGPSInicioMuevo(){
			$this->visitas("Inicio");
			if($this->input->is_ajax_request()){
				$gps=$this->security->xss_clean(strip_tags($this->input->get_post("gps")));
				$desde=date('Y-m-d', strtotime("-1 year"));
				$hasta=date('Y-m-d');
				$patentes= $this->Flotamodel->getPatenteGPSMuevo($gps);
				$supervisores= $this->Flotamodel->getSupervisorGPSMuevo($gps);
				$choferes= $this->Flotamodel->getChoferGPSMuevo($gps);
				$regiones= $this->Flotamodel->getRegionesGPSMuevo($gps);

				$datos = array(	
					'desde' => $desde,
					'hasta' => $hasta,
					'patentes' => $patentes,
					'supervisores' => $supervisores,
					'choferes' => $choferes,
					'regiones' => $regiones,
					'gps' => $gps,
				);
	
				$this->load->view('back_end/flota/gps_muevo',$datos);
			}
		}
	
		public function listaGPSMuevo(){
			$desde=$this->security->xss_clean(strip_tags($this->input->get_post("desde")));
			$hasta=$this->security->xss_clean(strip_tags($this->input->get_post("hasta")));
			$chofer=$this->security->xss_clean(strip_tags($this->input->get_post("chofer")));
			$supervisor=$this->security->xss_clean(strip_tags($this->input->get_post("supervisor")));
			$patente=$this->security->xss_clean(strip_tags($this->input->get_post("patente")));
			$region=$this->security->xss_clean(strip_tags($this->input->get_post("region")));
			$gps=$this->security->xss_clean(strip_tags($this->input->get_post("gps")));
			echo json_encode($this->Flotamodel->listaGPSMuevo($desde,$hasta,$chofer,$supervisor,$patente,$gps,$region));
		}

		public function listaMontoMuevo(){
			$desde=$this->security->xss_clean(strip_tags($this->input->get_post("desde")));
			$hasta=$this->security->xss_clean(strip_tags($this->input->get_post("hasta")));
			$chofer=$this->security->xss_clean(strip_tags($this->input->get_post("chofer")));
			$supervisor=$this->security->xss_clean(strip_tags($this->input->get_post("supervisor")));
			$patente=$this->security->xss_clean(strip_tags($this->input->get_post("patente")));
			$region=$this->security->xss_clean(strip_tags($this->input->get_post("region")));
			$gps=$this->security->xss_clean(strip_tags($this->input->get_post("gps")));
			echo json_encode(array("data"=>$this->Flotamodel->listaMontoMuevo($desde,$hasta,$chofer,$supervisor,$patente,$gps,$region)));
		}	
	
		public function listaOdometroMuevo(){
			$desde=$this->security->xss_clean(strip_tags($this->input->get_post("desde")));
			$hasta=$this->security->xss_clean(strip_tags($this->input->get_post("hasta")));
			$chofer=$this->security->xss_clean(strip_tags($this->input->get_post("chofer")));
			$supervisor=$this->security->xss_clean(strip_tags($this->input->get_post("supervisor")));
			$patente=$this->security->xss_clean(strip_tags($this->input->get_post("patente")));
			$region=$this->security->xss_clean(strip_tags($this->input->get_post("region")));
			$gps=$this->security->xss_clean(strip_tags($this->input->get_post("gps")));
			echo json_encode(array("data"=>$this->Flotamodel->listaOdometroMuevo($desde,$hasta,$chofer,$supervisor,$patente,$gps,$region)));
		}	

		public function getActualizacionGPSMuevo(){
			$gps=$this->security->xss_clean(strip_tags($this->input->get_post("gps")));
			$desde=$this->security->xss_clean(strip_tags($this->input->get_post("desde")));
			$hasta=$this->security->xss_clean(strip_tags($this->input->get_post("hasta")));
			$region=$this->security->xss_clean(strip_tags($this->input->get_post("region")));
			echo json_encode($this->Flotamodel->getActualizacionGPSMuevo($gps,$desde,$hasta,$region));
		}	

		public function GastoRegionMuevo(){
			$desde=$this->security->xss_clean(strip_tags($this->input->get_post("desde")));
			$hasta=$this->security->xss_clean(strip_tags($this->input->get_post("hasta")));
			$chofer=$this->security->xss_clean(strip_tags($this->input->get_post("chofer")));
			$supervisor=$this->security->xss_clean(strip_tags($this->input->get_post("supervisor")));
			$patente=$this->security->xss_clean(strip_tags($this->input->get_post("patente")));
			$region=$this->security->xss_clean(strip_tags($this->input->get_post("region")));
			
			
			echo json_encode(array("data"=>$this->Flotamodel->GastoRegionMuevo($desde,$hasta,$chofer,$supervisor,$patente,$region)));
		}
	
		public function GastoSemanaMuevo(){
			$desde=$this->security->xss_clean(strip_tags($this->input->get_post("desde")));
			$hasta=$this->security->xss_clean(strip_tags($this->input->get_post("hasta")));
			$chofer=$this->security->xss_clean(strip_tags($this->input->get_post("chofer")));
			$supervisor=$this->security->xss_clean(strip_tags($this->input->get_post("supervisor")));
			$patente=$this->security->xss_clean(strip_tags($this->input->get_post("patente")));
			$region=$this->security->xss_clean(strip_tags($this->input->get_post("region")));
			
			
			echo json_encode(array("data"=>$this->Flotamodel->GastoSemanaMuevo($desde,$hasta,$chofer,$supervisor,$patente,$region)));
		}



		/******* DOCUMENTACION *******/

		public function docindex(){
			//$this->acceso();
			$datos = array(
				'titulo' => "Flota - Documentacion",
				'contenido' => "flota/documentacion/inicio",
				'perfiles' => $this->Iniciomodel->listaPerfiles(),
			);  
			$this->load->view('plantillas/plantilla_back_end',$datos);
		}

		public function getDocumentoFlotaInicio(){
			$this->visitas("Inicio");
			if($this->input->is_ajax_request()){
				$desde=date('Y-m-d', strtotime("-1 year"));
				$hasta=date('Y-m-d');
				$patentes= $this->Flotamodel->getPatenteMantenedor();
				$datos = array(	
					'desde' => $desde,
					'hasta' => $hasta,
					'patentes' => $patentes,
				);
				$this->load->view('back_end/flota/documentacion/flota',$datos);
			}
	
		}

		public function listaDocumentoFlota(){
			$patente=$this->security->xss_clean(strip_tags($this->input->get_post("patente")));
			echo json_encode($this->Flotamodel->listaDocumentoFlota($patente));
		}

		public function formDocumentoFlota(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();	
				$this->security->xss_clean(strip_tags($hash = $this->input->post("hash_liqui")));

				$ultima_actualizacion = date("Y-m-d G:i:s")." | ".$this->session->userdata("nombre_completo");
				$patentes=$this->input->post("patentes");
				$titulo_archivos=$this->input->post("titulo_archivos");
				
				$archivos = array();
				if (!isset($_FILES['archivos'])) {
					echo json_encode(array('res'=>"error", 'msg' => 'Debe subir al menos un documento.'));exit;
				}

				foreach ($_FILES['archivos'] as $key => $value) {
					for ($i = 0; $i < count($value); $i++) {
						$archivos[$i][$key] = $value[$i];
					}
				}

				if($patentes == null){
					echo json_encode(array('res'=>"error", 'msg' => 'Debe subir al menos un documento.'));exit;
				}
				elseif($titulo_archivos == null){
					echo json_encode(array('res'=>"error", 'msg' => 'Debe agregar título a los documentos.'));exit;
				}
				elseif($archivos == null){
					echo json_encode(array('res'=>"error", 'msg' => 'Debe agregar archivo a los documentos.'));exit;
				}

				$carpeta = "archivos/flota/";
				$datos = array();
				for ($i = 0; $i < count($patentes); $i++) {
					if($patentes[$i]==""){
						echo json_encode(array('res'=>"error", 'msg' => 'Debe subir la patente de todos los documentos.'));exit;
					}
					if($titulo_archivos[$i]==""){
						echo json_encode(array('res'=>"error", 'msg' => 'Debe subir el título de todos los documentos.'));exit;
					}
					if($archivos[$i]["name"]==""){
						echo json_encode(array('res'=>"error", 'msg' => 'Debe subir el archivo de todos los documentos.'));exit;
					}
				}

				if ($this->form_validation->run("formDocumentoFlota") == FALSE){
					echo json_encode(array('res'=>"error", 'msg' => strip_tags(validation_errors())));
					exit;}
				else{
					if($hash==""){
						
						for ($i = 0; $i < count($patentes); $i++) {
							$adjunto = $archivos[$i]["name"];
							$titulo = $titulo_archivos[$i];
							$patente = $patentes[$i];
			
							if($adjunto!=""){
								$path = $adjunto;
								$ext = pathinfo($path, PATHINFO_EXTENSION);
								$archivo =  date("ymdHis")."_".$titulo."_".$patente.".".$ext;
								$data = array(
									"patente" => $patente,
									"titulo" => $titulo,
									"archivo" => $archivo,
									"ultima_actualizacion" => $ultima_actualizacion,
								);
								$this->procesaArchivo($archivos[$i],$archivo,$carpeta);
								$insert_id = $this->Flotamodel->ingresarDocumento($data);   
							}
						}
			
						if($insert_id != FALSE){
							echo json_encode(array('res'=>"ok",  'msg' => OK_MSG));
							exit;
						} else {
							echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));
							exit;
						}

					}else{
						$data_mod= array(
							"patente" => $patentes[$i],
							"titulo" => $titulo_archivos[$i],
							"archivo" => $adjunto,
							"ultima_actualizacion" => $ultima_actualizacion,
						);
						if($this->Prevencion_checklistmodel->ActualizarDocumento($hash,$data_mod)){
							echo json_encode(array('res'=>"ok",  'msg' => OK_MSG));exit;
						}else{
							echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
						}
					}
				}
			}
			else{
				exit('No direct script access allowed');
			}
		}

		public function eliminaDocumentoFlota(){
			$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
			if($this->Flotamodel->eliminaDocumentoFlota($hash)){
			echo json_encode(array("res" => "ok" , "msg" => "Registro eliminado correctamente."));
			}else{
			echo json_encode(array("res" => "error" , "msg" => "Problemas eliminando el registro, intente nuevamente."));
			}
		}

		public function procesaArchivo($file,$titulo,$carpeta){
			$path = $file['name'];
 			$config['upload_path'] = $carpeta;
			$config['allowed_types'] = 'pdf|PDF|jpg|JPG|jpeg|JPEG|png|PNG';
		    $config['file_name'] = $titulo;
			$config['max_size']	= '5300';
			$config['overwrite']	= FALSE;
			$this->load->library('upload', $config);
			$_FILES['userfile']['name'] = $titulo;
		    $_FILES['userfile']['type'] = $file['type'];
		    $_FILES['userfile']['tmp_name'] = $file['tmp_name'];
		    $_FILES['userfile']['error'] = $file['error'];
			$_FILES['userfile']['size'] = $file['size'];
			$this->upload->initialize($config);

			if (!$this->upload->do_upload()){ 
			    echo json_encode(array('res'=>"error", 'msg' =>strip_tags($this->upload->display_errors())));exit;
		    }else{
		    	unset($config);
		    	return $titulo;
		    }
    	}

		/***** MANTENEDOR *****/

		public function getMantenedorFlotaInicio(){
			$this->visitas("Inicio");
			if($this->input->is_ajax_request()){
				$desde=date('Y-m-d', strtotime("-1 year"));
				$hasta=date('Y-m-d');
				$datos = array(	
					'desde' => $desde,
					'hasta' => $hasta,
				);
				$this->load->view('back_end/flota/documentacion/mantenedor',$datos);
			}
		}

		public function listaMantenedorFlota(){
			echo json_encode($this->Flotamodel->listaMantenedorFlota());
		}

		public function formMantenedorFlota(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();	
				$hash = $this->security->xss_clean(strip_tags($this->input->post("hash_liqui")));
				$patente = $this->security->xss_clean(strip_tags($this->input->post("patente")));

				if ($this->form_validation->run("formMantenedorFlota") == FALSE){
					echo json_encode(array('res'=>"error", 'msg' => strip_tags(validation_errors())));
					exit;}
				else{
					if($hash==""){
						
						$data = array(
							"patente" => $patente,
						);
						$insert_id = $this->Flotamodel->ingresarFlota($data);   
			
						if($insert_id != FALSE){
							echo json_encode(array('res'=>"ok",  'msg' => OK_MSG));
							exit;
						} else {
							echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));
							exit;
						}

					}else{
						$data_mod= array(
							"patente" => $patente,
						);
						if($this->Flotamodel->ActualizarFlota($hash,$data_mod)){
							echo json_encode(array('res'=>"ok",  'msg' => OK_MSG));exit;
						}else{
							echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
						}
					}
				}
			}
			else{
				exit('No direct script access allowed');
			}
		}

		public function eliminaMantenedorFlota(){
			$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
			if($this->Flotamodel->eliminaMantenedorFlota($hash)){
			echo json_encode(array("res" => "ok" , "msg" => "Registro eliminado correctamente."));
			}else{
			echo json_encode(array("res" => "error" , "msg" => "Problemas eliminando el registro, intente nuevamente."));
			}
		}









}