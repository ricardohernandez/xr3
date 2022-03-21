<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Licencias extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model("back_end/Licenciasmodel");
		$this->load->helper(array('fechas','str'));
		$this->load->library('user_agent');
	}


	public function checkLogin(){
		if($this->session->userdata("id")==""){
			redirect("../unlogin");
		}

		$perfil = $this->session->userdata("id_perfil");
		$id_aplicacion = 1;
		
	}
	
	public function acceso(){
		if(!$this->session->userdata('id')){
	      	redirect("./login");
	    }

	    if($this->session->userdata('id_perfil')>2){
	     	redirect("./inicio");
	    }
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
    	$this->Licenciasmodel->insertarVisita($data);
	}

	public function index(){
		$this->visitas("Licencias");
    	$this->acceso();
	    $datos = array(
	        'titulo' => "Licencias",
	        'contenido' => "licencias/inicio",
		);  
		$this->load->view('plantillas/plantilla_back_end',$datos);
		
	}

    public function getLicenciasInicio(){
		if($this->input->is_ajax_request()){
			$fecha_anio_atras=date('d-m-Y', strtotime('-365 day', strtotime(date("d-m-Y"))));
	    	$fecha_hoy=date('d-m-Y');
		    $datos=array('tipos' => $this->Licenciasmodel->listaTiposLicencias());
			$this->load->view('back_end/licencias/licencias',$datos);
		}
	}


	public function getLicenciasList(){
		$inactivos=$this->security->xss_clean(strip_tags($this->input->get_post("inactivos")));
		echo json_encode($this->Licenciasmodel->getLicenciasList($inactivos));
	}
	

	public function formIngreso(){
		if($this->input->is_ajax_request()){
			$this->checkLogin();	
			$hash=$this->security->xss_clean(strip_tags($this->input->post("hash_id")));
			$id_usuario=$this->security->xss_clean(strip_tags($this->input->post("usuarios")));
			$fecha_inicio=$this->security->xss_clean(strip_tags($this->input->post("fecha_inicio")));
	   		if($fecha_inicio!=""){$fecha_inicio=date("Y-m-d",strtotime($fecha_inicio));}else{$fecha_inicio="";}	
	   		$fecha_termino=$this->security->xss_clean(strip_tags($this->input->post("fecha_termino")));
	   		if($fecha_termino!=""){$fecha_termino=date("Y-m-d",strtotime($fecha_termino));}else{$fecha_termino="";}	
			$tipo_licencia=$this->security->xss_clean(strip_tags($this->input->post("tipo_licencia")));
			$id_digitador=$this->session->userdata("id");
			$ultima_actualizacion=date("Y-m-d G:i:s")." | ".$this->session->userdata("nombre_completo");
	   		$adjunto=@$_FILES["userfile"]["name"];
		    $checkcorreo=$this->security->xss_clean(strip_tags($this->input->post("checkcorreo")));

			if(!empty($fecha_inicio) and !empty($fecha_termino)){
				if($fecha_inicio>$fecha_termino){
					echo json_encode(array('res'=>"error",'msg' => "La fecha de término debe ser mayor a la de inicio."));exit;
				}else{
					$start = strtotime($fecha_inicio);
					$end = strtotime($fecha_termino);

					$dias = ceil(abs($end - $start) / 86400)+1;
				}
			
			}else{
				$dias=0;
			}

   			if ($this->form_validation->run("formIngresoLicencia") == FALSE){
				echo json_encode(array('res'=>"error", 'msg' => strip_tags(validation_errors())));exit;
			}else{	

				$data = array(
	    		 	'id_usuario' => $id_usuario,
	    		 	'id_tipo_licencia' => $tipo_licencia,
	    		 	'fecha_inicio' => $fecha_inicio,
	    		 	'fecha_termino' => $fecha_termino,
	    		 	'dias' => $dias,
	    		 	"ultima_actualizacion" => $ultima_actualizacion);

		    	if($hash==FALSE){

		    		$data["fecha_registro"] = date("Y-m-d");
		    		$data["id_digitador"] = $id_digitador;
		    		
		    		if($adjunto!=""){
						$nombre=$this->procesaArchivo($_FILES["userfile"],$id_usuario."_".date("ymdHis"),1);
						$data["adjunto"]=$nombre;
					}else{
						$data["adjunto"]="";
					}


					$insert_id=$this->Licenciasmodel->formIngreso($data);
					if($insert_id!=FALSE){
						echo json_encode(array('res'=>"ok",  'msg' => OK_MSG));exit;

					}else{
						echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
					}


				}else{

					if($adjunto!=""){
						$nombre=$this->procesaArchivo($_FILES["userfile"],$id_usuario."_".date("ymdHis"),1);
						$data["adjunto"]=$nombre;
					}

					
					if($this->Licenciasmodel->formActualizar($hash,$data)){
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

	
	public function procesaArchivo($file,$titulo){
			$path = $file['name'];
			$ext = pathinfo($path, PATHINFO_EXTENSION);
			$archivo=strtolower(url_title(convert_accented_characters($titulo.rand(10, 1000)))).".".$ext;
			$config['upload_path'] = './archivos_licencias';
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

	public function listaUsuariosS2(){
 	     echo $this->Licenciasmodel->listaUsuariosS2();exit;
	}


	public function eliminaLicencias(){
		$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
			
	    if($this->Licenciasmodel->eliminaLicencias($hash)){
	      echo json_encode(array("res" => "ok" , "msg" => "Registro eliminado correctamente."));
	    }else{
	      echo json_encode(array("res" => "error" , "msg" => "Problemas eliminando el registro, intente nuevamente."));
	    }
	}

	public function getDataRegistro(){
		if($this->input->is_ajax_request()){
			$this->checkLogin();	
			$hash=$this->security->xss_clean(strip_tags($this->input->post("hash_id")));
			$data=$this->Licenciasmodel->getDataRegistro($hash);
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