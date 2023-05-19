<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Responsable_fallos extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if($this->uri->segment("1")==""){
			redirect("inicio");
		}
		$this->load->model("back_end/mantenedores/Responsablesfallosmodel");
		$this->load->model("back_end/Iniciomodel");
		$this->load->library('user_agent');
	}

	public function visitas($modulo){
		$this->load->library('user_agent');
		$data=array("id_usuario"=>$this->session->userdata('id'),
			"id_aplicacion"=>17,
			"modulo"=>$modulo,
	     	"fecha"=>date("Y-m-d G:i:s"),
	    	"navegador"=>"navegador :".$this->agent->browser()."\nversion :".$this->agent->version()."\nos :".$this->agent-> platform()."\nmovil :".$this->agent->mobile(),
	    	"ip"=>$this->input->ip_address(),
    	);
    	$this->Responsablesfallosmodel->insertarVisita($data);
	}

	public function checkLogin(){
		if(!$this->session->userdata('id')){
			echo json_encode(array('res'=>"sess"));exit;
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

	
	public function index(){
		$this->visitas("Responsables fallos herramientas");
    	$this->acceso();
	    $datos = array(
	        'titulo' => "Responsables fallos herramientas",
	        'contenido' => "mantenedores/responsables_fallos/inicio",
	        'perfiles' => $this->Iniciomodel->listaPerfiles(),
		);  
		$this->load->view('plantillas/plantilla_back_end',$datos);
	}

	public function vistaResponsablesFallosHerramientas(){
		$this->visitas("Responsables fallos herramientas");
		$fecha_anio_atras=date('d-m-Y', strtotime('-360 day', strtotime(date("d-m-Y"))));
    	$fecha_hoy=date('d-m-Y');
		$responsables=$this->Responsablesfallosmodel->listaResponsablesFallos();
		$proyectos=$this->Responsablesfallosmodel->listaProyectosC();
		$listaItemsHerramientas=$this->Responsablesfallosmodel->listaItemsHerramientas();

		$datos=array(
			'fecha_anio_atras' => $fecha_anio_atras,	   
	        'fecha_hoy' => $fecha_hoy,
	        'proyectos' => $proyectos,
	        'responsables' => $responsables,
	        'listaItemsHerramientas' => $listaItemsHerramientas,

	   	);
		$this->load->view('back_end/mantenedores/responsables_fallos/responsables_fallos',$datos);
	}

	public function listaResponsablesFallosHerramientas(){
		$proyecto=$this->security->xss_clean(strip_tags($this->input->get_post("proyecto")));
		echo json_encode($this->Responsablesfallosmodel->listaResponsablesFallosHerramientas($proyecto));
	}

	public function formResponsablesFallosHerramientas(){
		if($this->input->is_ajax_request()){
			$this->checkLogin();
			$hash_responsable=$this->security->xss_clean(strip_tags($this->input->post("hash_responsable_fallos")));
			$item=$this->security->xss_clean(strip_tags($this->input->post("item_fallos")));
			$proyecto=$this->security->xss_clean(strip_tags($this->input->post("proyecto_fallos")));
			$responsable=$this->security->xss_clean(strip_tags($this->input->post("responsable_fallos")));
			$plazo=$this->security->xss_clean(strip_tags($this->input->post("plazo_fallos")));
			$ultima_actualizacion=date("Y-m-d G:i:s");

			if ($this->form_validation->run("formResponsablesFallosHerramientas") == FALSE){
				echo json_encode(array('res'=>"error", 'msg' => strip_tags(validation_errors())));exit;
			}else{	

				$data=array("id_item"=>$item,
					"id_proyecto"=>$proyecto,
					"id_responsable"=>$responsable,
					"plazo"=>$plazo,
					"ultima_actualizacion"=>$ultima_actualizacion
				);	

				if($hash_responsable==""){

					if($this->Responsablesfallosmodel->existeResponsableItem($item,$proyecto)){
						echo json_encode(array('res'=>"error", 'msg' => "Ya existe un registro con este nombre."));exit;
					}

					if(!$this->Responsablesfallosmodel->responsableTieneCorreo($responsable)){
						echo json_encode(array('res'=>"error", 'msg' => "El responsable seleccionado no tiene correo ingresado."));exit;
					}

					$id=$this->Responsablesfallosmodel->formResponsablesFallosHerramientas($data);
					if($id!=FALSE){
						echo json_encode(array('res'=>"ok", 'msg' => MOD_MSG));exit;
					}else{
						echo json_encode(array('res'=>"ok", 'msg' => MOD_MSG));exit;
					}
					
				}else{

					if($this->Responsablesfallosmodel->existeResponsableItemMod($hash_responsable,$item,$proyecto)){
						echo json_encode(array('res'=>"error", 'msg' => "Ya existe un registro con este nombre."));exit;
					}

					if(!$this->Responsablesfallosmodel->responsableTieneCorreo($responsable)){
						echo json_encode(array('res'=>"error", 'msg' => "El responsable seleccionado no tiene correo ingresado."));exit;
					}

					if($this->Responsablesfallosmodel->formActualizarResponsablesFallosHerramientas($hash_responsable,$data)){
						echo json_encode(array('res'=>"ok", 'msg' => MOD_MSG));exit;
					}else{
						echo json_encode(array('res'=>"ok",  'msg' => MOD_MSG));exit;
					}
				}
    		}	
		}
	}

	public function getDataResponsableFallosHerramientas(){
		if($this->input->is_ajax_request()){
			$this->checkLogin();
			$hash=$this->security->xss_clean(strip_tags($this->input->post("hash_responsable_fallos")));
			$data=$this->Responsablesfallosmodel->getDataResponsableFallosHerramientas($hash);

			if($data){
				echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
			}else{
				echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
			}	
		}else{
			exit('No direct script access allowed');
		}
	}

	
	public function eliminaResponsableFallosHerramientas(){
		$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
		
	    if($this->Responsablesfallosmodel->eliminaResponsableFallosHerramientas($hash)){
	      echo json_encode(array("res" => "ok" , "msg" => "Registro eliminado correctamente."));
	    }else{
	      echo json_encode(array("res" => "error" , "msg" => "Problemas eliminando el registro, intente nuevamente."));
	    }
	}

	public function insertaDatosFallosHerramientas(){
		$this->Responsablesfallosmodel->truncateResponsablesHerramientas();
		$proyectos=$this->Responsablesfallosmodel->listaProyectosC();
		$listaItemsHerramientas=$this->Responsablesfallosmodel->listaItemsHerramientas();

		foreach($proyectos as $p){

			foreach($listaItemsHerramientas as $item){

				$data = array(
					 "id_item" => $item["id"],
					 "id_proyecto" => $p["id"], 
					 "id_responsable" => "", 
					 "plazo" => "1", 
					 "ultima_actualizacion" => date("Y-m-d G:i:s"));

				$this->Responsablesfallosmodel->formResponsablesFallosHerramientas($data);
			}
		}
	}
}