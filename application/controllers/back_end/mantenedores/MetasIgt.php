<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MetasIgt extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if($this->uri->segment("1")==""){
			redirect("inicio");
		}
		$this->load->model("back_end/mantenedores/MetasIgtmodel");
		$this->load->model("back_end/Iniciomodel");
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
    	$this->MetasIgtmodel->insertarVisita($data);
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
		$this->visitas("Mantenedor metas igt");
    	$this->acceso();
	    $datos = array(
	        'titulo' => "Mantenedor metas igt",
	        'contenido' => "mantenedores/metas_igt/inicio",
	        'perfiles' => $this->Iniciomodel->listaPerfiles(),
		);  
		$this->load->view('plantillas/plantilla_back_end',$datos);
	}

    public function vistaMetasIgt(){

		if($this->input->is_ajax_request()){
			$fecha_anio_atras=date('d-m-Y', strtotime('-365 day', strtotime(date("d-m-Y"))));
	    	$fecha_hoy=date('d-m-Y');

			$datos=array(	
				'fecha_anio_atras' => $fecha_anio_atras,	   
		        'fecha_hoy' => $fecha_hoy,
		        'niveles' => $this->MetasIgtmodel->listaNiveles(),
	           'indicadores' => $this->MetasIgtmodel->listaIndicadores(),
		    );

			$this->load->view('back_end/mantenedores/metas_igt/metas_igt',$datos);
		}
	}

	public function listaMetasIgt(){
		echo json_encode($this->MetasIgtmodel->listaMetasIgt());
	}

	public function formMetasIgt(){
		if($this->input->is_ajax_request()){
			$this->checkLogin();
			$hash_metas_igt=$this->security->xss_clean(strip_tags($this->input->post("hash_metas_igt")));
			$nivel=$this->security->xss_clean(strip_tags($this->input->post("nivel")));
			$indicador=$this->security->xss_clean(strip_tags($this->input->post("indicador")));
			$meta_actual=$this->security->xss_clean(strip_tags($this->input->post("meta_actual")));
			$meta_anterior=$this->security->xss_clean(strip_tags($this->input->post("meta_anterior")));

			if ($this->form_validation->run("formMetasIgt") == FALSE){
				echo json_encode(array('res'=>"error", 'msg' => strip_tags(validation_errors())));exit;
			}else{	

				$data=array("id_nivel"=>$nivel,
					"id_indicador"=>$indicador,
					"meta_actual"=>$meta_actual,
					"meta_anterior"=>$meta_anterior
				);	

				if($hash_metas_igt==""){

					$id=$this->MetasIgtmodel->formMetasIgt($data);
					if($id!=FALSE){
						echo json_encode(array('res'=>"ok", 'msg' => MOD_MSG));exit;
					}else{
						echo json_encode(array('res'=>"ok", 'msg' => MOD_MSG));exit;
					}
					
				}else{

					if($this->MetasIgtmodel->existeMeta($hash_metas_igt,$nivel,$indicador)){
						echo json_encode(array('res'=>"error", 'msg' => "Ya existe un registro para este nivel e indicador."));exit;
					}

					if($this->MetasIgtmodel->actualizarMetasIgt($hash_metas_igt,$data)){
						echo json_encode(array('res'=>"ok", 'msg' => MOD_MSG));exit;
					}else{
						echo json_encode(array('res'=>"ok",  'msg' => MOD_MSG));exit;
					}
				}
    		}	
		}
	}


	public function getDataMetasIgt(){
		if($this->input->is_ajax_request()){
			$this->checkLogin();
			$hash_metas_igt=$this->security->xss_clean(strip_tags($this->input->post("hash_metas_igt")));
			$data=$this->MetasIgtmodel->getDataMetasIgt($hash_metas_igt);
		
			if($data){
				echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
			}else{
				echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
			}	
		}else{
			exit('No direct script access allowed');
		}
	}

	public function eliminaMetasIgt(){
		$hash=$this->security->xss_clean(strip_tags($this->input->post("hash_metas_igt")));
	    if($this->MetasIgtmodel->eliminaMetasIgt($hash)){
	      echo json_encode(array("res" => "ok" , "msg" => "Registro eliminado correctamente."));
	    }else{
	      echo json_encode(array("res" => "error" , "msg" => "Problemas eliminando el registro, intente nuevamente."));
	    }
	}

	public function actualizarMetaActual(){
		$data = $this->MetasIgtmodel->listaMetasIgt();

		foreach($data as $d){
			$data = array("meta_anterior" => $d["meta_actual"]);
			$this->MetasIgtmodel->actualizarMetasIgt(sha1($d["id"]),$data);
		}

	}
	
}