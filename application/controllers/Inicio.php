<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inicio extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if($this->uri->segment("1")==""){
			redirect("inicio");
		}
		$this->load->model("back_end/Iniciomodel");
		$this->load->library('user_agent');
		$this->load->helper(array('fechas','str'));

	}

	public function nojs(){	
		$this->load->view('back_end/nojs');
	}

    public function acceso(){
		if(!$this->session->userdata("id")){
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
    	$this->Iniciomodel->insertarVisita($data);
	}

	public function index(){	
		$this->acceso();
    	$fecha_anio_atras=date('d-m-Y', strtotime('-360 day', strtotime(date("d-m-Y"))));
    	$fecha_hoy=date('Y-m-d');
	    $datos = array(
	       'titulo' => "KMO-XR3 planificación y control operacional",
	       'fecha_anio_atras' => $fecha_anio_atras,	   
	       'fecha_hoy' => $fecha_hoy
		);  
		$this->load->view('login',$datos);
	}	

	
	/*******LOGIN*********/

		public function login(){
			$this->visitas("Login");
			
		    $datos = array(
		       'titulo' => "KMO-XR3 planificación y control operacional",
		       'contenido' => "login",
			);  
			$this->load->view('login',$datos);
		}

		public function validaLogin(){
			if($this->input->is_ajax_request()){
				$rut=$this->security->xss_clean($this->input->post("usuario"));
				$rut1=str_replace('.', '', $rut);
				$rut2=str_replace('.', '', $rut1);
				$rut=str_replace('-', '', $rut2);
				$pass=sha1($this->security->xss_clean(strip_tags($this->input->post("pass"))));

				if ($rut=="" || $pass=="") {
					echo json_encode(array("res" => "error" , "msg" => "Debe ingresar usuario y contraseña."));exit;
				}

				$cuentas=$this->Iniciomodel->checkLogin($rut,$pass);
				if($cuentas==1){

					if(!$this->Iniciomodel->existeCuenta($rut,$pass)){
						echo json_encode(array("res" => "error" , "msg" => "Contraseña incorrecta."));exit;
					}

					if($this->Iniciomodel->logear($rut,$pass)!=FALSE){

						foreach($this->Iniciomodel->logear($rut,$pass) as $dato){
							$this->session->set_userdata("id",$dato["id"]);	
							$this->session->set_userdata("rut",$dato["rut"]);	
							$this->session->set_userdata("nombres",$dato["nombres"]);	
							$this->session->set_userdata("apellidos",$dato["apellidos"]);	
							$this->session->set_userdata("nombre_completo",$dato["nombres"]." " .$dato["apellidos"]);	
							$this->session->set_userdata("correo_empresa",$dato["correo_empresa"]);	
							$this->session->set_userdata("correo_personal",$dato["correo_personal"]);	
							$this->session->set_userdata("id_perfil",$dato["id_perfil"]);	
							$this->session->set_userdata("foto",$dato["foto"]);	
							echo json_encode(array("res" => "ok" ,  "tipo" => "ok" , "msg" => "Ingresando al sistema."));exit;
						}

					}else{
						echo json_encode(array("res" => "error" , "msg" => "Usuario no encontrado."));exit;
					}

				}else{
					echo json_encode(array("res" => "error" , "msg" => "Usuario no encontrado."));exit;
				}
			}else{
				exit('No direct script access allowed');
			}
		}

		
		public function unlogin(){
			$this->session->unset_userdata("id"); 
			$this->session->unset_userdata("rut"); 
			$this->session->unset_userdata("nombres"); 
		    $this->session->unset_userdata("apellidos"); 
			$this->session->unset_userdata("correo_empresa"); 
			$this->session->unset_userdata("correo_personal"); 
			$this->session->unset_userdata("id_perfil");
			$this->session->unset_userdata("foto");
			$this->session->sess_destroy();
			redirect("login");
		}

		
	/*******INICIO*********/	

	    public function inicio(){	
			$this->acceso();
			$this->visitas("Inicio");
		    $datos = array(
		       'titulo' => "Inicio",
			);  
			$this->load->view('inicio',$datos);
		}	
		
		public function cargaInicio(){
			$noticias = $this->Iniciomodel->getNoticias("");
			$categorias = $this->Iniciomodel->cargaCategorias();
			$ingresos = $this->Iniciomodel->cargaIngresos();
			$informaciones = $this->Iniciomodel->cargaInformaciones();
			$cumpleanios = $this->Iniciomodel->cargaCumpleanios();

		    echo json_encode(array(
		    	"noticias" => $this->load->view('front_end/widgets/noticias',array("noticias"=>$noticias),TRUE),
		    	"cumpleanios" => $this->load->view('front_end/widgets/cumpleanios',array("cumpleanios"=>$cumpleanios),TRUE),
		    	"categorias" => $this->load->view('front_end/widgets/menu_categorias',array("categorias"=>$categorias),TRUE),
		    	"ingresos" => $this->load->view('front_end/widgets/ultimos_ingresos',array("ingresos"=>$ingresos),TRUE),
		    	"informaciones" => $this->load->view('front_end/widgets/informaciones',array("informaciones"=>$informaciones),TRUE),

		    ));exit;

		}

		public function cargaVistaNoticias(){
			$cat=$this->security->xss_clean(strip_tags($this->input->get_post("cat")));
			$noticias = $this->Iniciomodel->getNoticias($cat);
		    $datos = array(
		       'noticias' => $noticias
			);  
			$this->load->view('front_end/widgets/noticias',$datos);
		}


		public function cargaVistaNoticia(){
			$hash=$this->security->xss_clean(strip_tags($this->input->get_post("hash")));
			$noticia = $this->Iniciomodel->datosNoticia($hash);
			$galeria = $this->Iniciomodel->getGaleria($hash);
		    $datos = array(
		       'noticia' => $noticia,
		       'galeria' => $galeria
			);  
			$this->load->view('front_end/widgets/noticia',$datos);
		}

		public function cargaCategorias(){
			$categorias = $this->Iniciomodel->cargaCategorias();
		    $datos = array(
		       'categorias' => $categorias
			);  
			$this->load->view('front_end/widgets/menu_categorias',$datos);
		}

		public function cargaIngresos(){
			$ingresos = $this->Iniciomodel->cargaIngresos();
		    $datos = array(
		       'ingresos' => $ingresos
			);  
			$this->load->view('front_end/widgets/ultimos_ingresos',$datos);
		}

		public function cargaInformaciones(){
			$informaciones = $this->Iniciomodel->cargaInformaciones();
		    $datos = array(
		       'informaciones' => $informaciones
			);  
			$this->load->view('front_end/widgets/informaciones',$datos);
		}

		public function infoUsuario(){
			$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
			if($this->Iniciomodel->infoUsuario($hash)){
				echo json_encode(array("res" => "ok","usuario" => $this->Iniciomodel->infoUsuario($hash)));exit;
			}else{
		        echo json_encode(array("res" => "error","msg" => "No se han encontrado resultados"));exit;
			}
		}
		



		


}