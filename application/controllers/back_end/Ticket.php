<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ticket extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model("back_end/Ticketmodel");
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
	      	if($this->session->userdata('id_perfil')>3){
	      		redirect("./login");
	      	}
	      }else{
	      	redirect("./login");
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
    	$this->Ticketmodel->insertarVisita($data);
	}

	public function index(){
		$this->visitas("Ticket");
    	$this->acceso();
	    $datos = array(
	        'titulo' => "Ticket",
	        'contenido' => "ticket/inicio",
	        'perfiles' => $this->Iniciomodel->listaPerfiles(),
	    );  
		$this->load->view('plantillas/plantilla_back_end',$datos);
	}

    public function getTicketInicio(){
		if($this->input->is_ajax_request()){
			$fecha_anio_atras=date('d-m-Y', strtotime('-365 day', strtotime(date("d-m-Y"))));
	    	$fecha_hoy=date('d-m-Y');
	    	$datos = array('tipos' => $this->Ticketmodel->listaTipos());
			$this->load->view('back_end/ticket/ticket',$datos);
		}
	}

	public function getTicketList(){
		$estado=$this->security->xss_clean(strip_tags($this->input->get_post("estado")));
		echo json_encode($this->Ticketmodel->getTicketList($estado));
	}
	
	public function formTicket(){
		if($this->input->is_ajax_request()){
			$this->checkLogin();	
			$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
			$id_usuario=$this->security->xss_clean(strip_tags($this->input->post("usuarios")));
			$fecha_inicio=date("Y-m-d");
			$titulo=$this->security->xss_clean(strip_tags($this->input->post("titulo")));
			$descripcion=$this->security->xss_clean(strip_tags($this->input->post("descripcion")));
			$tipo=$this->security->xss_clean(strip_tags($this->input->post("tipo")));
			$estado=$this->security->xss_clean(strip_tags($this->input->post("estado")));
			$id_digitador=$this->session->userdata("id");
			$ultima_actualizacion=date("Y-m-d G:i:s")." | ".$this->session->userdata("nombre_completo");
	   		$adjunto=@$_FILES["userfile"]["name"];
		    $checkcorreo=$this->security->xss_clean(strip_tags($this->input->post("checkcorreo")));

   			if ($this->form_validation->run("formTicket") == FALSE){
				echo json_encode(array('res'=>"error", 'msg' => strip_tags(validation_errors())));exit;
			}else{	

		    	if($hash==""){

		    		$data = array(
		    		 	'titulo' => $titulo,
		    		 	'descripcion' => $descripcion,
		    		 	'fecha_ingreso' => date("Y-m-d"),
		    		 	'tipo' => $tipo,
		    		 	'estado' => "pendiente",
		    		 	'id_usuario' => $id_digitador,
		    		 	'id_respuesta' => "",
		    		 	"ultima_actualizacion" => $ultima_actualizacion);

		    		if($adjunto!=""){
						$nombre=$this->procesaArchivo($_FILES["userfile"],$id_usuario."_".date("ymdHis"),1);
						$data["adjunto"]=$nombre;
					}else{
						$data["adjunto"]="";
					}

					$insert_id=$this->Ticketmodel->formIngreso($data);
					if($insert_id!=FALSE){

						if($checkcorreo=="on"){
							$this->EnviaCorreo(sha1($insert_id),"0");
						}

						echo json_encode(array('res'=>"ok",  'msg' => OK_MSG));exit;

					}else{
						echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
					}

				}else{
					
					$data_mod = array(
						'titulo' => $titulo,
		    		 	'descripcion' => $descripcion,
		    		 	'tipo' => $tipo,
		    		 	'estado' => $estado,
		    		 	'id_respuesta' => $this->session->userdata("id"),
		    		 	"ultima_actualizacion" => $ultima_actualizacion);


					if($estado=="finalizado"){
						$data_mod['fecha_respuesta'] = date("Y-m-d");	
					}
					
					if($this->Ticketmodel->formActualizar($hash,$data_mod)){
						if($checkcorreo=="on"){
							$this->EnviaCorreo($hash,"1");
						}
						echo json_encode(array('res'=>"ok",  'msg' => MOD_MSG));exit;
					}else{
						echo json_encode(array('res'=>"error",'msg' => "Error actualizando el registro, intente nuevamente."));exit;
					}
				}
			}

		}else{
			exit('No direct script access allowed');
		}
	}


	public function enviaCorreo($hash,$tipo){
		$this->load->library('email');
		$data=$this->Ticketmodel->getDataTicket($hash);
		$prueba=FALSE;

		foreach($data as $key){
		    $config = array (
	       	  'mailtype' => 'html',
	          'charset'  => 'utf-8',
	          'priority' => '1',
	          'wordwrap' => TRUE,
	          'protocol' => "smtp",
	          'smtp_port' => 587,
	          'smtp_host' => 'mail.xr3t.cl',
		      'smtp_user' => 'soporteplataforma@xr3t.cl',
		      'smtp_pass' => '9mWj.RUhL&3)'
	        );

		    $this->email->initialize($config);
			$asunto ="Ticket plataforma XR3 (".$key["estado"].") : ".$key["titulo"]." | ".date('d-m-Y', strtotime($key["fecha_ingreso"]));
			$datos=array("data"=>$data,"titulo"=>$asunto);
			$html=$this->load->view('back_end/ticket/correo',$datos,TRUE);

			if($prueba){
				$para=array("ricardo.hernandez@splice.cl");
				$copias=array("ricardo.hernandez@km-t.cl");
				$this->email->from("soporteplataforma@xr3t.cl","Soporte plataforma XR3");
			}else{
				$para=array("german.cortes@km-telecomunicaciones.cl","ricardo.hernandez@km-telecomunicaciones.cl");
				$copias=array("roberto.segovia@xr3.cl","cristian.cortes@xr3.cl");
				$this->email->from("soporteplataforma@xr3t.cl","Soporte plataforma XR3");
			}

			$this->email->to($para);
			$this->email->cc($copias);
	    	$this->email->bcc(array("ricardo.hernandez@km-telecomunicaciones.cl","soporteplataforma@xr3t.cl"));
			$this->email->subject($asunto);
			$this->email->message($html); 
			/*if($key["adjunto"]!="") {$this->email->attach(base_url()."lic/".$key["adjunto"]);}*/

			$resp=$this->email->send();

			if ($resp) {
				return TRUE;
			}else{
				return FALSE;
			}
		}
	}

	public function procesaArchivo($file,$titulo){
			$path = $file['name'];
			$ext = pathinfo($path, PATHINFO_EXTENSION);
			$archivo=strtolower(url_title(convert_accented_characters($titulo.rand(10, 1000)))).".".$ext;
			$config['upload_path'] = './archivos/ticket';
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

	public function eliminaTicket(){
		$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
	    if($this->Ticketmodel->eliminaTicket($hash)){
	      echo json_encode(array("res" => "ok" , "msg" => "Registro eliminado correctamente."));
	    }else{
	      echo json_encode(array("res" => "error" , "msg" => "Problemas eliminando el registro, intente nuevamente."));
	    }
	}

	public function getDataTicket(){
		if($this->input->is_ajax_request()){
			$this->checkLogin();	
			$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
			$data=$this->Ticketmodel->getDataTicket($hash);
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