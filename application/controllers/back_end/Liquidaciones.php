<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use setasign\Fpdi\Fpdi;

require_once(APPPATH.'libraries/fpdf/fpdf.php');
require_once(APPPATH.'libraries/fpdf/fpdi/src/autoload.php');

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class Liquidaciones extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model("back_end/Liquidacionesmodel");
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
    	$this->Liquidacionesmodel->insertarVisita($data);
	}

	public function index(){
		$this->visitas("Liquidaciones");
    	$this->acceso();
	    $datos = array(
	        'titulo' => "Liquidaciones",
	        'contenido' => "liquidaciones/inicio",
	        'perfiles' => $this->Iniciomodel->listaPerfiles(),
	    );  
		$this->load->view('plantillas/plantilla_back_end',$datos);
	}

	public function carga_masiva() {
		redirect('./carga_masiva');
	}

    public function getLiquidacionesInicio(){
		if($this->input->is_ajax_request()){
			$fecha_anio_atras = date('d-m-Y', strtotime('-365 day', strtotime(date("d-m-Y"))));
	    	$fecha_hoy = date('d-m-Y');
			
	    	$datos = array(
				'jefes' => $this->Liquidacionesmodel->listaJefes(),
			);

			$this->load->view('back_end/liquidaciones/liquidaciones',$datos);
		}
	}

	public function getLiquidacionesList(){
		$jefe=$this->security->xss_clean(strip_tags($this->input->get_post("jefe")));
		$trabajador=$this->security->xss_clean(strip_tags($this->input->get_post("trabajador")));
		$periodo=$this->security->xss_clean(strip_tags($this->input->get_post("periodo")));
		if($periodo!=""){$periodo=date("m-Y",strtotime($periodo));}else{$periodo="";}
		echo json_encode($this->Liquidacionesmodel->getLiquidacionesList($jefe,$trabajador,$periodo));
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

				$area = $this->Liquidacionesmodel->getAreaUsuario($id_trabajador);

				$rut = $this->Liquidacionesmodel->getRutUsuario($id_trabajador);

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
	
						$insert_id=$this->Liquidacionesmodel->ingresarLiquidacion($data);

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

					if($this->Liquidacionesmodel->formActualizar($hash,$data_mod)){

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
		echo $this->Liquidacionesmodel->listaTrabajadores($jefe);exit;
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

	public function eliminaLiquidaciones(){
		$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
		$ruta = $this->Liquidacionesmodel->getRutaLiquidacion($hash);
	    if($this->Liquidacionesmodel->eliminaLiquidaciones($hash)){
			if (file_exists($ruta)){
				$this->EliminaArchivo($ruta);
			}
	      	echo json_encode(array("res" => "ok" , "msg" => "Registro eliminado correctamente."));
	    }else{
	      	echo json_encode(array("res" => "error" , "msg" => "Problemas eliminando el registro, intente nuevamente."));
	    }
	}

	public function getDataLiquidaciones(){
		if($this->input->is_ajax_request()){
			$this->checkLogin();	
			$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
			$data=$this->Liquidacionesmodel->getDataLiquidaciones($hash);
			if($data){
				echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
			}else{
				echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
			}	
		}else{
			exit('No direct script access allowed');
		}
	}

	private function EliminaArchivo($ruta){
        if (file_exists($ruta)) {
            unlink($ruta);
        }
    }
	
}