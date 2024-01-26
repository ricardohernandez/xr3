<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Api_ctc extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if($this->uri->segment("1")==""){
			redirect("inicio");
		}
		$this->load->model("back_end/Calidadmodel");
		$this->load->model("back_end/Iniciomodel");
		$this->load->helper(array('fechas','str'));
		$this->load->library('user_agent');
	}

	public function visitas($modulo){
		$this->load->library('user_agent');
		$data=array("id_usuario"=>$this->session->userdata('id'),
			"id_aplicacion"=>11,
			"modulo"=>$modulo,
	     	"fecha"=>date("Y-m-d G:i:s"),
	    	"navegador"=>"navegador :".$this->agent->browser()."\nversion :".$this->agent->version()."\nos :".$this->agent-> platform()."\nmovil :".$this->agent->mobile(),
	    	"ip"=>$this->input->ip_address(),
    	);
    	$this->Calidadmodel->insertarVisita($data);
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
	
	public function index(){
    	$this->acceso();
	    $datos = array(
	        'titulo' => "Calidad XR3",
	        'contenido' => "calidad/inicio",
	        'perfiles' => $this->Iniciomodel->listaPerfiles(),
		);  
		$this->load->view('plantillas/plantilla_back_end',$datos);
		
	}


	public function api(){
		// URL REST
		$url = 'https://www.copec.cl/WS_Tarjetas_VigentesTCT/api/WS_Tarjetas_VigentesTCT/listaTarjetaVigenteTCT';

		$rutCliente = '76642773';
		$tipoTarjeta = 3;
		$usuarioPlataforma = 'mmoyano';
		$clavePlataforma = 'Finalfantasy7@';

		$url .= "?Rut=$rutCliente&TipoTarjeta=$tipoTarjeta&Usuario=$usuarioPlataforma&Clave=$clavePlataforma";

		$response = file_get_contents($url);

		$datos = json_decode($response, true);

		if ($datos === null) {
			echo "Error al decodificar la respuesta JSON";
		} else {
			echo "<pre>";
			print_r($datos);
		exit;
			
			$spreadsheet = new Spreadsheet();

			$sheet = $spreadsheet->getActiveSheet();

			$columnaActual = 'A';
			foreach ($datos['Vigentes'][0] as $clave => $valor) {
				$sheet->setCellValue($columnaActual . '1', $clave);
				$columnaActual++;
			}

			$fila = 2;
			foreach ($datos['Vigentes'] as $tarjeta) {
				$columnaActual = 'A';
				foreach ($tarjeta as $valor) {
					$sheet->setCellValue($columnaActual . $fila, $valor);
					$columnaActual++;
				}
				$fila++;
			}

			$writer = new Xlsx($spreadsheet);
			$filename = 'reporte-ctc.xlsx';
			$writer->save($filename);

			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename=' . $filename);
			readfile($filename);
			exit();

		}



		/* $wsdl_url = 'https://www.copec.cl/WS_consTCTTACNEW/ConsultaTCTWSS/ConsultaTCTWSS.wsdl';

		$usuario = 'mmoyano';
		$rut_cliente = '76642773';
		$password = 'Finalfantasy7@';
		$producto = 'TCT'; // tct/tae/en blanco
		$fecha_ini = '2024-01-01';
		$fecha_fin = '2024-01-05';
		$patente = ''; // patente/en blanco

		$params = array(
			'usuario' => $usuario,
			'rut_cliente' => $rut_cliente,
			'password' => $password,
			'producto' => $producto,
			'fecha_ini' => $fecha_ini,
			'fecha_fin' => $fecha_fin,
			'patente' => $patente,
		);

		$options = array(
			'trace' => 1, // Habilitar el seguimiento de la solicitud y respuesta
			'exceptions' => 1, // Habilitar excepciones en caso de errores
		);

		// Crear el cliente SOAP
		$client = new SoapClient($wsdl_url, $options);

		try {
			// Llamar a cualquier método del servicio web
			$result = $client->__soapCall('consultaTCT', array($params));

			echo "<pre>";
			print_r($result);
		} catch (SoapFault $e) {
			echo "Error: " . $e->getMessage();
		} */

	}

	public function api2(){ 
		$wsdl_url = 'https://www.copec.cl/WS_consTCTTACNEW/ConsultaTCTWSS/ConsultaTCTWSS.wsdl';

		$usuario = 'mmoyano';
		$rut_cliente = '76642773';
		$password = 'Finalfantasy7@';
		$producto = 'TCT'; // tct/tae/en blanco
		$fecha_ini = '2023-12-01';
		$fecha_fin = '2024-01-05';
		$patente = ''; // patente/en blanco

		$params = array(
			'usuario' => $usuario,
			'rut_cliente' => $rut_cliente,
			'password' => $password,
			'producto' => $producto,
			'fecha_ini' => $fecha_ini,
			'fecha_fin' => $fecha_fin,
			'patente' => $patente,
		);

		$options = array(
			'trace' => 1, // Habilitar el seguimiento de la solicitud y respuesta
			'exceptions' => 1, // Habilitar excepciones en caso de errores
		);

		// Crear el cliente SOAP
		$client = new SoapClient($wsdl_url, $options);

		try {
			// Llamar a cualquier método del servicio web
			$result = $client->__soapCall('consultaTCT', array($params));

			$spreadsheet = new Spreadsheet();
			$sheet = $spreadsheet->getActiveSheet();

			// Encabezados
			$headers = array_keys(get_object_vars($result->detalle->item[0]));
			$sheet->fromArray($headers, null, 'A1');

			// Datos
			$rowData = [];
			foreach ($result->detalle->item as $item) {
				$rowData[] = array_values(get_object_vars($item));
			}
			$sheet->fromArray($rowData, null, 'A2');

			$writer = new Xlsx($spreadsheet);
			$filename = 'reporte-ctc2.xlsx';
			$writer->save($filename);

			/* header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename=' . $filename);
			readfile($filename);
			exit(); */

			echo "<pre>";
			print_r($result);
			exit;
		} catch (SoapFault $e) {
			echo "Error: " . $e->getMessage();
		}

	}

}
