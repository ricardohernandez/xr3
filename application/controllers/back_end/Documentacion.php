<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Documentacion extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if($this->uri->segment("1")==""){
			redirect("inicio");
		}
		$this->load->model("back_end/Documentacionmodel");
		$this->load->model("back_end/Iniciomodel");
		$this->load->helper(array('fechas','str'));
		$this->load->library('user_agent');
	}

	public function visitas($modulo){
		$this->load->library('user_agent');
		$data=array("id_usuario"=>$this->session->userdata('id'),
			"id_aplicacion"=>1,
			"modulo"=>$modulo,
	     	"fecha"=>date("Y-m-d G:i:s"),
	    	"navegador"=>"navegador :".$this->agent->browser()."\nversion :".$this->agent->version()."\nos :".$this->agent-> platform()."\nmovil :".$this->agent->mobile(),
	    	"ip"=>$this->input->ip_address(),
    	);
    	$this->Documentacionmodel->insertarVisita($data);
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
	        'titulo' => "Documentación XR3",
	        'contenido' => "documentacion/inicio",
	        'perfiles' => $this->Iniciomodel->listaPerfiles(),
		);  
		$this->load->view('plantillas/plantilla_back_end',$datos);
		
	}

	/***********CAPACITACION*****************/

		public function vistaCapacitacion(){
			$this->visitas("c");
			if($this->input->is_ajax_request()){
				$datos=array(	
			    );
				$this->load->view('back_end/documentacion/capacitacion',$datos);
			}
		}

		public function getCapacitacionList(){
			echo json_encode($this->Documentacionmodel->getCapacitacionList());
		}
		
		public function formIngresoCapacitacion(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();	
				$hash=$this->security->xss_clean(strip_tags($this->input->post("hash_id")));
				$nombre=$this->security->xss_clean(strip_tags($this->input->post("nombre_archivo")));
				$fecha = date("Y-m-d");
				$digitador=$this->session->userdata("id");
				$ultima_actualizacion=date("Y-m-d G:i:s")." | ".$this->session->userdata("nombre_completo");
		   		$adjunto=@$_FILES["userfile"]["name"];

	   			if ($this->form_validation->run("formIngresoCapacitacion") == FALSE){
					echo json_encode(array('res'=>"error", 'msg' => strip_tags(validation_errors())));exit;
				}else{	

					
			    	if($hash==FALSE){

			    		$data = array(
		    		 	'nombre' => $nombre,
		    		 	'id_digitador' => $digitador,
		    		 	'fecha' => $fecha,
		    		 	'ultima_actualizacion' => $ultima_actualizacion);


			    		if($adjunto!=""){
							$nombre=$this->procesaArchivoCapacitacion($_FILES["userfile"],$nombre."_".date("ymdHis"),1);
							$data["archivo"]=$nombre;
						}else{
							$data["archivo"]="";
						}

						$insert_id=$this->Documentacionmodel->formIngresoCapacitacion($data);
						if($insert_id!=FALSE){
							echo json_encode(array('res'=>"ok",  'msg' => OK_MSG));exit;
						}else{
							echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
						}

					}else{
						
						$data = array(
		    		 	'nombre' => $nombre,
		    		 	'ultima_actualizacion' => $ultima_actualizacion);

						if($adjunto!=""){
							$nombre=$this->procesaArchivoCapacitacion($_FILES["userfile"],$nombre."_".date("ymdHis"),1);
							$data["archivo"]=$nombre;

							$archivo_bd=$this->Documentacionmodel->getDataRegistroCapacitacion($hash);

							foreach($archivo_bd as $a){
								if($a["archivo"]!=""){
									if (file_exists("./archivos_documentacion/capacitacion/".$a["archivo"])){
										unlink("./archivos_documentacion/capacitacion/".$a["archivo"]);
									}	
								}
							}

						}

						if($this->Documentacionmodel->formActualizarCapacitacion($hash,$data)){
							echo json_encode(array('res'=>"ok",  'msg' => OK_MSG));exit;
						}else{
							echo json_encode(array('res'=>"error",'msg' => "Error actualizando el registro, intente nuevamente."));exit;
						}
					}
				}

			}else{
				exit('No direct script access allowed');
			}
		}

		public function procesaArchivoCapacitacion($file,$titulo){
				$path = $file['name'];
				$ext = pathinfo($path, PATHINFO_EXTENSION);
				$archivo=strtolower(url_title(convert_accented_characters($titulo.rand(10, 1000)))).".".$ext;
				$config['upload_path'] = './archivos_documentacion/capacitacion';
				$config['allowed_types'] = 'pdf|jpg|jpeg|bmp|png|doc|docx|xls|xlsx|ppt|pptx|html|htm|XLS|XLSX|DOC';
			    $config['file_name'] = $archivo;
				$config['max_size']	= '5300';
				$config['overwrite']	= FALSE;
				$this->load->library('upload', $config);
				$_FILES['userfile']['name'] = $archivo;
			    $_FILES['userfile']['type'] = $file['type'];
			    $_FILES['userfile']['tmp_name'] = $file['tmp_name'];
			    $_FILES['userfile']['error'] = $file['error'];
				$_FILES['userfile']['size'] = $file['size'];
				$this->upload->initialize($config);

				if (!$this->upload->do_upload()){ 
				    echo json_encode(array('res'=>"error", 'msg' =>strip_tags($this->upload->display_errors())));exit;
			    }else{
			    	unset($config);
			    	return $archivo;
			    }
	    }

		public function eliminaCapacitacion(){
			$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
			$data=$this->Documentacionmodel->getDataRegistroCapacitacion($hash);
			foreach($data as $key){
				if($key["archivo"]!=""){
					if (file_exists("./archivos_documentacion/capacitacion/".$key["archivo"])){
						unlink("./archivos_documentacion/capacitacion/".$key["archivo"]);
					}	
				}
			}

			if($this->Documentacionmodel->eliminaCapacitacion($hash)){
				echo json_encode(array("res" => "ok" , "msg" => "Registro eliminado correctamente."));
			}else{	
				echo json_encode(array("res" => "error" , "msg" => "Error eliminando el registro, intente nuevamente."));
			}
		}

		public function getDataRegistroCapacitacion(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();	
				$hash=$this->security->xss_clean(strip_tags($this->input->post("hash_id")));
				$data=$this->Documentacionmodel->getDataRegistroCapacitacion($hash);
				if($data){
					echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
				}else{
					echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
				}	
			}else{
				exit('No direct script access allowed');
			}
		}
}