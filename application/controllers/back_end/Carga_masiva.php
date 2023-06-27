<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
ini_set('default_charset', 'UTF-8');
use setasign\Fpdi\Fpdi;

require_once(APPPATH.'libraries/fpdf/fpdf.php');
require_once(APPPATH.'libraries/fpdf/fpdi/src/autoload.php');

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class Carga_masiva extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model("back_end/Cargamasivamodel");
		$this->load->model("back_end/Iniciomodel");
		$this->load->helper(array('fechas','str'));
		$this->load->library('user_agent');
	}


	public function checkLogin(){
		if($this->session->userdata("id")==""){
			redirect("../unlogin");
		}
	}
	
	public function acceso(){
		if($this->session->userdata('id')){
	      	if($this->session->userdata('id_perfil')==""){
	      		redirect("./login");
	      	}
	      }else{
	      	redirect("./login");
	    }
	}

	public function visitas($modulo){
		$this->load->library('user_agent');
		$data=array("id_usuario"=>$this->session->userdata('id'),
			"id_aplicacion"=>20,
			"modulo"=>$modulo,
	     	"fecha"=>date("Y-m-d G:i:s"),
	    	"navegador"=>"navegador :".$this->agent->browser()."\nversion :".$this->agent->version()."\nos :".$this->agent-> platform()."\nmovil :".$this->agent->mobile(),
	    	"ip"=>$this->input->ip_address(),
    	);
    	$this->Cargamasivamodel->insertarVisita($data);
	}

	public function index(){
		$this->visitas("carga_masiva");
    	$this->acceso();
	    $datos = array(
	        'titulo' => "Carga Masiva",
	        'contenido' => "carga_masiva/inicio",
	        'perfiles' => $this->Iniciomodel->listaPerfiles(),
	    );  
		$this->load->view('plantillas/plantilla_back_end',$datos);
	}

    public function getCargamasivaInicio(){
		if($this->input->is_ajax_request()){
			$fecha_anio_atras = date('d-m-Y', strtotime('-365 day', strtotime(date("d-m-Y"))));
	    	$fecha_hoy = date('d-m-Y');
			
	    	$datos = array(
				'jefes' => $this->Cargamasivamodel->listaJefes(),
			);

			$this->load->view('back_end/liquidaciones/carga_masiva',$datos);
		}
	}

	public function getCargamasivaList(){
		$periodo=$this->security->xss_clean(strip_tags($this->input->get_post("periodo")));
		echo json_encode($this->Cargamasivamodel->getCargamasivaList($periodo));
	}


	public function formLiquidaciones(){
		if($this->input->is_ajax_request()){
			$this->checkLogin();	
			$hash = $this->security->xss_clean(strip_tags($this->input->post("hash_liqui")));
			$id_trabajador = $this->security->xss_clean(strip_tags($this->input->post("trabajadores")));
			$periodo = $this->security->xss_clean(strip_tags($this->input->post("periodo")));
			$fecha_subida = date("d-m-Y");
			$id_digitador = $this->session->userdata("id");
			$adjunto = $_FILES["userfile"]["name"];
			$ultima_actualizacion = date("Y-m-d G:i:s")." | ".$this->session->userdata("nombre_completo");

   			if ($this->form_validation->run("formLiquidaciones") == FALSE){
				echo json_encode(array('res'=>"error", 'msg' => strip_tags(validation_errors())));exit;
			}else{	

				$area = $this->Cargamasivamodel->getAreaUsuario($id_trabajador);

				$rut = $this->Cargamasivamodel->getRutUsuario($id_trabajador);

				$mes = date("m", strtotime($periodo));

				$anio = date("Y", strtotime($periodo));

				$fecha_creacion_carpeta = $mes.'-'.$anio;

				if($hash==""){

					if($adjunto==""){

						echo json_encode(array('res'=>"error", 'msg' => 'Debe subir el archivo.'));exit;

					}else{

						$path = $_FILES['userfile']['name'];

						$ext = pathinfo($path, PATHINFO_EXTENSION);
	
						$carpeta = "archivos/liquidaciones/".$fecha_creacion_carpeta.'_'.convert_accented_characters(trim($area));

						$nombre_archivo = $carpeta.'/'.$rut.'_'.$periodo.".".$ext;

						$archivo =  $rut.'_'.$periodo.".".$ext;

						if (!file_exists($carpeta)) { 
							mkdir($carpeta, 0777, true); 
						}
						
						$data = array('id_usuario' => $id_trabajador,
							'id_digitador' => $id_digitador,
							'archivo' => $nombre_archivo,
							'fecha' =>  date("Y-m-d"),
							'fecha_carpeta' => $fecha_creacion_carpeta,
							'carpeta' => $carpeta,
							'periodo' => $fecha_creacion_carpeta,
							'ultima_actualizacion'=>$ultima_actualizacion
						);
	
						if($adjunto!=""){
							$nombre=$this->procesaArchivo($_FILES["userfile"],$archivo,$carpeta);
							$data["nombre_archivo"]=$nombre;
						}
	
						$insert_id=$this->Cargamasivamodel->ingresarLiquidacion($data);
						if($insert_id!=FALSE){

							echo json_encode(array('res'=>"ok",  'msg' => OK_MSG));exit;

						}else{

							echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;

						}

					}

				}else{

					if($adjunto==""){

						echo json_encode(array('res'=>"error", 'msg' => 'Debe subir el archivo.'));exit;

					}else{
							
						$path = $_FILES['userfile']['name'];

						$ext = pathinfo($path, PATHINFO_EXTENSION);

						$carpeta = "archivos/liquidaciones/".$fecha_creacion_carpeta.'_'.convert_accented_characters(trim($area));

						$nombre_archivo = $carpeta.'/'.$rut.'_'.$periodo.".".$ext;

						$archivo =  $rut.'_'.$periodo.".".$ext;

						if (!file_exists($carpeta)) { 
							mkdir($carpeta, 0777, true); 
						}

						$data_mod= array('id_usuario' => $id_trabajador,
							'id_digitador' => $id_digitador,
							'archivo' => $nombre_archivo,
							'fecha' =>  date("Y-m-d"),
							'fecha_carpeta' => $fecha_creacion_carpeta,
							'carpeta' => $carpeta,
							'periodo' => $fecha_creacion_carpeta,
							'ultima_actualizacion'=>$ultima_actualizacion
						);

						if($adjunto!=""){
							$nombre=$this->procesaArchivo($_FILES["userfile"],$archivo,$carpeta);
							$data_mod["nombre_archivo"]=$nombre;
						}

					}

					if($this->Cargamasivamodel->formActualizar($hash,$data_mod)){

						echo json_encode(array('res'=>"ok",  'msg' => OK_MSG));exit;
					}else{
						echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;

					}

				}

			}

		}else{
			exit('No direct script access allowed');
		}
		
	}
	

	public function listaTrabajadores(){
		$jefe=$this->security->xss_clean(strip_tags($this->input->get_post("jefe")));
		echo $this->Cargamasivamodel->listaTrabajadores($jefe);exit;
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

	public function eliminaCargamasiva(){
		$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
	    if($this->Cargamasivamodel->eliminaCargamasiva($hash)){
	      echo json_encode(array("res" => "ok" , "msg" => "Registro eliminado correctamente."));
	    }else{
	      echo json_encode(array("res" => "error" , "msg" => "Problemas eliminando el registro, intente nuevamente."));
	    }
	}

	public function getDataLiquidaciones(){
		if($this->input->is_ajax_request()){
			$this->checkLogin();	
			$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
			$data=$this->Cargamasivamodel->getDataLiquidaciones($hash);
			if($data){
				echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
			}else{
				echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
			}	
		}else{
			exit('No direct script access allowed');
		}
	}

	/* CARGA MASIVA */

	public function formMasivo(){
		$this->checkLogin();	
		$hash = $this->security->xss_clean(strip_tags($this->input->post("hash_masivo")));
		$periodo = $this->security->xss_clean(strip_tags($this->input->post("periodo_carga_masiva")));
		$id_digitador = $this->session->userdata("id");
		$ultima_actualizacion = date("Y-m-d G:i:s")." | ".$this->session->userdata("nombre_completo");
		$archivo_excel = $_FILES['excel_file']['tmp_name'];
		if($archivo_excel==""){
			echo json_encode(array('res'=>"error", 'msg' => 'Debe subir el archivo.'));exit;
		}

		
		$excel = IOFactory::load($archivo_excel);
		$hoja = $excel->getActiveSheet();	
		$numFilas = $hoja->getHighestRow();
		$numColumnas = $hoja->getHighestColumn();
		$datos = [];

		$mes = date("m", strtotime($periodo));
		$anio = date("Y", strtotime($periodo));
		$fecha_creacion_carpeta = $mes.'-'.$anio;
		$ruta = "./archivos/liquidaciones/".$fecha_creacion_carpeta;
		$ruta_excel = "./archivos/liquidaciones/cargas_masivas/".$fecha_creacion_carpeta;


		if (!is_dir($ruta)) {
			mkdir($ruta, 0777, true);
		}

		if (!is_dir($ruta_excel)) {
			mkdir($ruta_excel, 0777, true);
		}


		$datos_total = -1;
		foreach ($hoja->getRowIterator() as $fila) {
			$valores = array();
			foreach ($fila->getCellIterator() as $celda) {
				$valor = $celda->getValue();
				$valor = mb_convert_encoding($valor, 'UTF-8', 'UTF-8'); // Convertir a UTF-8
				//$valores[] = utf8_encode($valor);
				if($valor == null){
					$valor = 0;
				}
				$valores[] = $valor;
			}
			// Verificar si la fila está vacía
			if (array_filter($valores)) {
				$datos_total += 1;
				$datos[] = $valores;
			}
		}

		if($this->validaExcel($datos[0]) == false){
			echo json_encode(array('res'=>"error", 'msg' => 'El formato del archivo excel no es el correcto.'));exit;
		}
		$message = "";
		$liquidaciones = array();
		$num_fallidos = 0;
		foreach ($datos as $fila) { //CREA UN PDF
			if($fila[0]!= "RUT"){
				$archivo = "liquidacion".$fila[0].".pdf";
				if (file_exists($ruta."/".$archivo)){
					$message = $message."Ya existe una liquidación creada para el rut ".$fila[0].".,";
					$num_fallidos += 1;
				} else {
					$pdf = new FPDI();
					$templatePath = './archivos/liquidaciones/template/template_pdf.pdf';
					$pdf->setSourceFile($templatePath);
					$templateId = $pdf->importPage(1);
					$pdf->AddPage();
					$pdf->useTemplate($templateId);

					$pdf->SetFont('Arial', '', 11.5);
					$pdf->SetTextColor(0, 0, 0);

					$pdf->SetXY(39,33.6);
					$pdf->Cell(100, 10, $fila[60], 0, 1); //MES DE INFORME
					$pdf->SetXY(131,33.6);
					$pdf->Cell(100, 10, $fila[61], 0, 1); //PERIODO

					$pdf->SetFont('Arial', '', 9.3);

					/*
					$pdf->SetXY(58,42);
					$pdf->Cell(100, 10, "$ ".$fila[10], 0, 1); //Comision de produccion y calidad
					$pdf->SetXY(154.8,42);
					$pdf->Cell(100, 10, "$ ".$fila[11], 0, 1); //Asignacion por impecabilidad
					$pdf->SetXY(58,49.5);
					$pdf->Cell(100, 10, "$ ".$fila[17], 0, 1); //Comision de retirio y factibilidades
					$pdf->SetXY(154.8,49.5);
					$pdf->Cell(100, 10, "$ ".$fila[12], 0, 1); //Pendiente mes anterior
					*/

					
					$pdf->SetXY(58,42);
					$pdf->Cell(100, 10, "$ ".number_format($fila[10], 0, ',', '.'), 0, 1); //Comision de produccion y calidad
					$pdf->SetXY(154.8,42);
					$pdf->Cell(100, 10, "$ ".number_format($fila[11], 0, ',', '.'), 0, 1); //Asignacion por impecabilidad
					$pdf->SetXY(58,49.5);
					$pdf->Cell(100, 10, "$ ".number_format($fila[17], 0, ',', '.'), 0, 1); //Comision de retirio y factibilidades
					$pdf->SetXY(154.8,49.5);
					$pdf->Cell(100, 10, "$ ".number_format($fila[12], 0, ',', '.'), 0, 1); //Pendiente mes anterior
					

					//Datos del trabajador

					$pdf->SetFont('Arial', '', 7.9);
					$pdf->SetXY(41.5,65);
					$pdf->Cell(100, 10, utf8_decode($fila[1]), 0, 1); //Nombre
					$pdf->SetXY(138.6,65);
					$pdf->Cell(100, 10, $fila[0], 0, 1); //Rut
					$pdf->SetXY(41.5,72);
					$pdf->Cell(100, 10, utf8_decode($fila[6]), 0, 1); //Proyecto
					$pdf->SetXY(138.6,72);
					$pdf->Cell(100, 10, utf8_decode($fila[4]), 0, 1); //Plaza operacional
					$pdf->SetXY(41.5,79.5);
					$pdf->Cell(100, 10, Date::excelToDateTimeObject($fila[2])->format('d/m/Y'), 0, 1); //Fecha de ingreso
					$pdf->SetXY(138.6,79.5);
					$pdf->Cell(100, 10, utf8_decode($fila[5]), 0, 1); //Cargo

					//Detalle de asistencia

					$pdf->SetXY(147.1,91.1);
					$pdf->Cell(100, 10, number_format($fila[59], 2) . '%', 0, 1); //Factor de asistencia

					$pdf->SetXY(58,98.5);
					$pdf->Cell(100, 10, $fila[27], 0, 1); //Dias habiles del periodo
					$pdf->SetXY(154.8,98.5);
					$pdf->Cell(100, 10, $fila[28], 0, 1); //Dias trabajados del periodo

					$pdf->SetXY(58,105.7);
					$pdf->Cell(100, 10, $fila[29], 0, 1); //Dias de licencia medica
					$pdf->SetXY(154.8,105.7);
					$pdf->Cell(100, 10, $fila[30], 0, 1); //Dias de permiso sin justificacion

					$pdf->SetXY(58,113.2);
					$pdf->Cell(100, 10, $fila[31], 0, 1); //Dias de permiso con pago
					$pdf->SetXY(154.8,113.2);
					$pdf->Cell(100, 10, $fila[32], 0, 1); //Dias de permiso sin pago

					$pdf->SetXY(58,120.5);
					$pdf->Cell(100, 10, $fila[33], 0, 1); //Dias de vacaciones

				//////////////////

					//Detalle de productividad

					//$pdf->SetXY(149.5,131.9);$pdf->Cell(100, 10, "$ ".$fila[10], 0, 1); //Comision por produccion y calidad

					$pdf->SetXY(149.5,131.9);$pdf->Cell(100, 10, "$ ".number_format($fila[10], 0, ',', '.'), 0, 1); //Comision por produccion y calidad

					$pdf->SetXY(58,139.2);
					$pdf->Cell(100, 10, $fila[57]." puntos", 0, 1); //HFC puntaje promedio
					$pdf->SetXY(154.8,139.2);
					$pdf->Cell(100, 10, number_format($fila[54], 2) . '%', 0, 1); //HFC % calidad 30 dias

					$pdf->SetXY(58,146.5);
					$pdf->Cell(100, 10, $fila[58], 0, 1); // HFC dias trabajados
					$pdf->SetXY(154.8,146.5);
					$pdf->Cell(100, 10, number_format($fila[46], 2) . '%', 0, 1); // HFC % cumplimiento calidad

					$pdf->SetXY(58,153.9);
					$pdf->Cell(100, 10, number_format($fila[45], 2) . '%', 0, 1); // HFC % cumplimiento produccion
					$pdf->SetXY(154.8,153.9);
					$pdf->Cell(100, 10, number_format($fila[53], 2) . '%', 0, 1); // FTTH % calidad 30 dias

					$pdf->SetXY(58,161.3);
					$pdf->Cell(100, 10, $fila[55], 0, 1); // FTTHOT promedio
					$pdf->SetXY(154.8,161.3);
					$pdf->Cell(100, 10, number_format($fila[48], 2) . '%', 0, 1); // FTTH % de cumplimiento calidad

					$pdf->SetXY(58,176);
					$pdf->Cell(100, 10, $fila[56], 0, 1); // FTTH Dias trabajados
					$pdf->SetXY(154.8,176);
					$pdf->Cell(100, 10, number_format($fila[44], 2) . '%', 0, 1); // Calidad  antigua

					$pdf->SetXY(58,183.3);
					$pdf->Cell(100, 10, number_format($fila[47], 2) . '%', 0, 1); // FTTH % Cumplimiento produccion
					$pdf->SetXY(154.8,183.3);
					$pdf->Cell(100, 10, $fila[43], 0, 1); // Productividad antigua

					/////////////////////

					//Detalle de actividades de retiro y factibilidades

					/*

					$pdf->SetXY(149.3,194.6);
					$pdf->Cell(100, 10, "$ ".$fila[21], 0, 1); // Comision de retiro y factibilidades

					$pdf->SetXY(58,202.3);
					$pdf->Cell(100, 10, "$ ".$fila[35], 0, 1); // Comision retiro CPE terreno
					$pdf->SetXY(154.8,202.3);
					$pdf->Cell(100, 10, $fila[38], 0, 1); // N equipos CPE terreno

					$pdf->SetXY(58,209.5);
					$pdf->Cell(100, 10, "$ ".$fila[34], 0, 1); // Comision retiro CPE sucursal
					$pdf->SetXY(154.8,209.5);
					$pdf->Cell(100, 10, $fila[37], 0, 1); // N equipos CPE sucursal

					$pdf->SetXY(58,216.8);
					$pdf->Cell(100, 10, "$ ".$fila[36], 0, 1); // Comision factibilidades
					$pdf->SetXY(154.8,216.8);
					$pdf->Cell(100, 10, $fila[39], 0, 1); // N factibilidades

					*/

					
					
					$pdf->SetXY(149.3,194.6);
					$pdf->Cell(100, 10, "$ ".number_format($fila[21], 0, ',', '.'), 0, 1); // Comision de retiro y factibilidades

					$pdf->SetXY(58,202.3);
					$pdf->Cell(100, 10, "$ ".number_format($fila[35], 0, ',', '.'), 0, 1); // Comision retiro CPE terreno
					$pdf->SetXY(154.8,202.3);
					$pdf->Cell(100, 10, $fila[38], 0, 1); // N equipos CPE terreno

					$pdf->SetXY(58,209.5);
					$pdf->Cell(100, 10, "$ ".number_format($fila[34], 0, ',', '.'), 0, 1); // Comision retiro CPE sucursal
					$pdf->SetXY(154.8,209.5);
					$pdf->Cell(100, 10, $fila[37], 0, 1); // N equipos CPE sucursal

					$pdf->SetXY(58,216.8);
					$pdf->Cell(100, 10, "$ ".number_format($fila[36], 0, ',', '.'), 0, 1); // Comision factibilidades
					$pdf->SetXY(154.8,216.8);
					$pdf->Cell(100, 10, $fila[39], 0, 1); // N factibilidades

					

					$pdf->SetXY(58,224.2);
					$pdf->Cell(100, 10, $fila[33], 0, 1); // dias vacaciones

					//detalle de impecabilidad operacional

					//$pdf->SetXY(144.5,235.7);$pdf->Cell(100, 10, "$ ".$fila[11], 0, 1); // Asignacion por impecabilidad

					$pdf->SetXY(144.5,235.7);$pdf->Cell(100, 10, "$ ".number_format($fila[11], 0, ',', '.'), 0, 1); // Asignacion por impecabilidad

					$pdf->SetXY(58,242.9);
					$pdf->Cell(100, 10, $fila[49], 0, 1); // Chequeo de OT logistica
					$pdf->SetXY(154.8,242.9);
					$pdf->Cell(100, 10, $fila[50], 0, 1); // Chequeo sin amonestacion

					$pdf->SetXY(58,250.4);
					$pdf->Cell(100, 10, $fila[51], 0, 1); // chequeo registro AST
					$pdf->SetXY(154.8,250.4);
					$pdf->Cell(100, 10, $fila[52], 0, 1); // chequeo supervisor

					//CREA LAS LIQUIDACIONES EN LA BASE DE DATOS
					$rut = str_replace('-','',$fila[0]);
					$usuario = $this->Cargamasivamodel->getUsuarioRut($rut);
					$data = array();
		
					if($usuario == ""){
						$message = $message."El rut ".$rut." no existe en la base de datos.,";
						$num_fallidos += 1;
					}
					else{
		
						$pdf->Output($ruta."/".$archivo, 'F');
						$data = array(
							'id_usuario' => $usuario, //está
							'id_digitador' => $id_digitador, //está
							'archivo' => $ruta."/".$archivo, //está
							'nombre_archivo' => $archivo, //está
							'fecha' =>  date("Y-m-d"), //está
							'fecha_carpeta' => $periodo, //está
							'carpeta' => $ruta, //está
							'periodo' => $periodo, //está
							'ultima_actualizacion'=>$ultima_actualizacion //está
						);		
						$insert_id=$this->Cargamasivamodel->ingresarLiquidacion($data);
						if($insert_id == FALSE){
							$num_fallidos += 1;
						}
						else{
							array_push($liquidaciones, $insert_id);
						}
					}
				}
			}
		}
		$message = substr($message, 0, -1); // Eliminar el último carácter

		//CREA EL DATO DE LA CARGA MASIVA

		$path = $_FILES['excel_file']['name'];
		$ext = pathinfo($path, PATHINFO_EXTENSION);
		$r=rand(1,9999);
		$archivo = $r."Carga_masiva_".$periodo.".".$ext;
		$nombre_archivo = $ruta_excel."/".$archivo;

		
		$data2 = array(
			'periodo' => $periodo, 
			'carpeta' => $ruta_excel, 
			'fecha' =>  date("Y-m-d"), 
			'datos_fallidos' => $num_fallidos,
			'datos_aceptados' => $datos_total-$num_fallidos,
			'datos_total' => $datos_total,
			'id_digitador' => $id_digitador, 
			'observaciones' => $message,
			'ultima_actualizacion'=>$ultima_actualizacion, 
			'lista_id_liquidaciones' => implode(',',$liquidaciones),
		);	

		if($archivo_excel!=""){
			$nombre=$this->procesaArchivoExcel($_FILES["excel_file"],$archivo,$ruta_excel);
			$data2["nombre_archivo"]=$nombre;
			$data2["archivo"]=  $ruta_excel."/".$nombre;
		}
		$insert_id2=$this->Cargamasivamodel->ingresarCargaMasiva($data2);
		if($message == ""){
			echo json_encode(array("res" => "ok" , "msg" => "La Carga de liquidaciones se ha realizado correctamente."));
		}
		else{
			echo json_encode(array("res" => "error" , "msg" => "La carga se ha generado con problemas (revisar detalles)"));
		}
	}

	public function procesaArchivoExcel($file,$titulo,$carpeta){
		$path = $file['name'];
		$config['upload_path'] = $carpeta;
		$config['allowed_types'] = '*';
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

	public function descargarTemplate(){
			$rutaArchivo = 'archivos/liquidaciones/template/template_excel.xlsx';
			$nombreArchivo = basename($rutaArchivo);
			$this->load->helper('download');
			force_download($nombreArchivo, file_get_contents($rutaArchivo));
	}

	public function validaExcel($columnas){
		$val = array("RUT","Nombre","Ingreso","Zona","Plaza","Cargo","Proyecto","Modificación Sueldo Base","Sobretiempo Normal en Horas","Sobretiempo Festivo en Horas","Comisión por Productividad","Asignación Impecabilidad","Pendientes mes anterior","Asignación Vehículo Propio","Bono especial apoyo Bucket","Asignación por Ventas","Asignación responsabilidad","Bono Adm. Retiro y Factibilidad","Bono Extraordinario","Bono Plan Especial G","Bono especial","Retiros & Fact.","Resumen variable (Jefes, lideres y CDC)","Viáticos CDC","Viáticos   [ Haber ]","Viáticos   [ Dscto ]","Descuento Extra ordinario","Días hábiles [ del PERIODO ]","Días trabajados [ del PERIODO ]","N° de días licencias","N° de días de permisos NO justificados","N° de días de permisos Pagados","N° de días de permisos No Pagados","N° de días de vacaciones","Valor Retiro Sucursal","Valor Retiro terreno","Valor Factibilidad","Cantidad retiro sucursal","Cantidad retiro terreno","Cantidad factibilidad","Retiros Terreno (x 50) ptos","Retiros Sucursal (x 10) ptos","Factibilidades Antiguo (x 10) ptos","TOTAL PUNTOS (Antiguo)","Porcentaje Calidad (ANTIGUO)","Porcentaje Cumplimiento Px HFC","Porcentaje Cumplimiento Cx HFC","Porcentaje Cumplimiento Px FTTH","Porcentaje Cumplimiento Cx FTTH","Ot Digital","Amonestados","AST","Supervisor","Calidad FTTH","Calidad HFC","Prom OT equivalentes (FTTH)","Dias Px FTTH","Promedio Puntos (HFC)","Dias Px HFC","Indice de Asistencia","MES/AÑO (04-2023 formato texto)" ,"PERIODO (Formato texto )");
		$num = 0;
		$largo = count($columnas);
		foreach ($val as $valor){
			if($num > $largo or $valor != $columnas[$num] ){
				return false;
			}
			$num += 1;
		}
		return true;
	}
}