<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inicio extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if($this->uri->segment("1")==""){
			redirect("inicio");
		}
		$this->load->model("back_end/Iniciomodel");
		$this->load->library('user_agent');
		$this->load->helper(array('fechas','str','rut'));

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
	       'titulo' => "PTO Plataforma técnica operacional",
      	   'subtitulo' => "Plataforma técnica operacional",
	       'fecha_anio_atras' => $fecha_anio_atras,	   
	       'fecha_hoy' => $fecha_hoy
		);  
		$this->load->view('login',$datos);
	}	
	
	/*7026*/
	/*******LOGIN*********/

		public function login(){
			$this->visitas("Login");
			
		    $datos = array(
	      	    'titulo' => "PTO Plataforma técnica operacional",
      	  	 	'subtitulo' => "Plataforma técnica operacional",
		       'contenido' => "login",
			);  
			$this->load->view('login',$datos);
		}

		public function validaLogin(){
			if($this->input->is_ajax_request()){
				$rut=$this->security->xss_clean($this->input->post("usuario"));

				if(!valida_rut($rut)){
					echo json_encode(array("res" => "error" , "msg" => "Formato de rut invalido."));exit;
				}
			
				$rut1=str_replace('.', '', $rut);
				$rut2=str_replace('.', '', $rut1);
				$rut=str_replace('-', '', $rut2);
				$pass=sha1($this->security->xss_clean(strip_tags(trim($this->input->post("pass")))));

				if ($rut=="" || $pass=="") {
					echo json_encode(array("res" => "error" , "msg" => "Debe ingresar usuario y contraseña."));exit;
				}

				$cuentas=$this->Iniciomodel->checkLogin($rut,$pass);
				if($cuentas==1){
					if($pass=="509bbc096944957de731602adabef7bc2c4e57e3"){

						if($this->Iniciomodel->logear($rut,$pass,true)!=FALSE){

							foreach($this->Iniciomodel->logear($rut,$pass,true) as $dato){
								$this->session->set_userdata("id",$dato["id"]);	
								$this->session->set_userdata("rut",$dato["rut"]);	
								$this->session->set_userdata("nombres",$dato["nombres"]);	
								$this->session->set_userdata("apellidos",$dato["apellidos"]);	
								$this->session->set_userdata("nombre_completo",$dato["nombres"]." " .$dato["apellidos"]);	
								$this->session->set_userdata("correo_empresa",$dato["correo_empresa"]);	
								$this->session->set_userdata("correo_personal",$dato["correo_personal"]);	
								$this->session->set_userdata("id_perfil",$dato["id_perfil"]);	
								$this->session->set_userdata("cargo",$dato["id_cargo"]);	
								$this->session->set_userdata("foto",$dato["foto"]);	

								if($this->Iniciomodel->verificacionJefe($dato["id"])){
									$this->session->set_userdata("verificacionJefe","1");	
									$this->session->set_userdata("id_jefe",$this->Iniciomodel->idJefe($dato["id"]));	
								}else{
									$this->session->set_userdata("verificacionJefe","0");	
								}

								echo json_encode(array("res" => "ok" ,  "tipo" => "ok" , "msg" => "Ingresando al sistema..."));exit;
							}

						}else{
							echo json_encode(array("res" => "error" , "msg" => "Usuario no encontrado."));exit;
						}

					}else{

						if(!$this->Iniciomodel->existeCuenta($rut,$pass)){
							echo json_encode(array("res" => "error" , "msg" => "Contraseña incorrecta."));exit;
						}

						if($this->Iniciomodel->logear($rut,$pass,false)!=FALSE){

							foreach($this->Iniciomodel->logear($rut,$pass,false) as $dato){
								$this->session->set_userdata("id",$dato["id"]);	
								$this->session->set_userdata("rut",$dato["rut"]);	
								$this->session->set_userdata("nombres",$dato["nombres"]);	
								$this->session->set_userdata("apellidos",$dato["apellidos"]);	
								$this->session->set_userdata("nombre_completo",$dato["nombres"]." " .$dato["apellidos"]);	
								$this->session->set_userdata("correo_empresa",$dato["correo_empresa"]);	
								$this->session->set_userdata("correo_personal",$dato["correo_personal"]);	
								$this->session->set_userdata("id_perfil",$dato["id_perfil"]);	
								$this->session->set_userdata("foto",$dato["foto"]);	
								$this->session->set_userdata("cargo",$dato["id_cargo"]);	
								
								if($this->Iniciomodel->verificacionJefe($dato["id"])){
									$this->session->set_userdata("verificacionJefe","1");	
									$this->session->set_userdata("id_jefe",$this->Iniciomodel->idJefe($dato["id"]));	
								}else{
									$this->session->set_userdata("verificacionJefe","0");	
								}

								echo json_encode(array("res" => "ok" ,  "tipo" => "ok" , "msg" => "Ingresando al sistema..."));exit;
							}

						}else{
							echo json_encode(array("res" => "error" , "msg" => "Usuario no encontrado."));exit;
						}
					}
					

				}else{
					echo json_encode(array("res" => "error" , "msg" => "Usuario no encontrado."));exit;
				}
			}else{
				exit('No direct script access allowed');
			}
		}

		public function cambiarPass(){
			$id=$this->session->userdata("id");
			$rut=$this->session->userdata('rut');
			$passactual=$this->security->xss_clean(strip_tags(trim($this->input->post("c_actual"))));
			$pass_nueva=$this->security->xss_clean(strip_tags(trim($this->input->post("nueva_c"))));
			$pass_nueva2=$this->security->xss_clean(strip_tags(trim($this->input->post("confirma_c"))));
			$passbd=$this->Iniciomodel->getUserPass($id);

			if($passactual=="" or $pass_nueva=="" or $pass_nueva2==""){
				echo json_encode(array("res" => "error" , "msg" => "Debe completar los campos."));exit;
			}

			if($pass_nueva!=$pass_nueva2){
				echo json_encode(array("res" => "error" , "msg" => "Las contraseñas no coinciden."));exit;
			}

			if($passbd["contrasena"]==sha1($pass_nueva)){
				echo json_encode(array("res" => "error" , "msg" => "La contraseña nueva no puede ser igual a la antigua."));exit;
			}

			if(sha1($rut)==sha1($pass_nueva)){
				echo json_encode(array("res" => "error" , "msg" => "La contraseña no puede ser el rut."));exit;
			}

			if($passbd["contrasena"]!=sha1($passactual)){
				echo json_encode(array("res" => "error" , "msg" => "La contraseña actual no coincide con la del usuario."));exit;
			}
			
			$data=array("contrasena"=>sha1($pass_nueva));

			if($this->Iniciomodel->updatePass($id,$data)){
				echo json_encode(array("res" => "ok" , "msg" => "Contraseña actualizada correctamente."));exit;
			}else{
				echo json_encode(array("res" => "error" , "msg" => "No se puede actualizar la contraseña, intente más tarde."));exit;
			}

			
			
		}

		public function recuperarPass(){
			$rut=$this->security->xss_clean(strip_tags($this->input->post("usuario_recuperar")));

			if(!valida_rut($rut)){
				echo json_encode(array("res" => "error" , "msg" => "Formato de rut invalido."));exit;
			}

			$rut=str_replace('.', '', $rut);
			$rut=str_replace('-', '', $rut);
			$correos=$this->Iniciomodel->getCorreoUsuario($rut);
			$nombre=$this->Iniciomodel->getNombreUsuario($rut);
			$prueba=FALSE;	

			if($correos==FALSE){
				echo json_encode(array("res" => "error" , "msg" => "Usuario no encontrado."));exit;
			}else{

				foreach($correos as $c){
					
					$this->load->library('email');

					$config = array (
						'mailtype' => 'html',
						'charset'  => 'utf-8',
						'priority' => '1',
						'wordwrap' => TRUE,
						'protocol' => "smtp",//sendmail
						'smtp_port' => 587,//587
						'smtp_host' => $this->config->item('rep_smtp_host'),
						'smtp_user' => $this->config->item('rep_smtp_user'),
						'smtp_pass' => $this->config->item('rep_smtp_pass')
				 	);

					$this->email->initialize($config);
					$asunto ="Recuperación de contraseña XR3-PTO : " . $nombre;
					$this->email->from("reportes@xr3t.cl","Soporte plataforma XR3");

					$para=array();
					if (filter_var($c["correo_personal"], FILTER_VALIDATE_EMAIL)) {
					   $para[]=$c["correo_personal"];
					}
					if (filter_var($c["correo_empresa"], FILTER_VALIDATE_EMAIL)) {
					   $para[]=$c["correo_empresa"];
					}

					$this->email->to($para);

					/* if($prueba){
						$this->email->bcc(array("ricardo.hernandez@splice.cl","ricardo.hernandez@km-t.cl","soporteplataforma@xr3t.cl"));
					}else{
						$this->email->bcc(array("ricardo.hernandez@splice.cl","ricardo.hernandez@km-t.cl","soporteplataforma@xr3t.cl"));
					} */

					$pass=rand(100000,999999);
					$datos=array("nombre"=>$nombre,"titulo"=>$asunto,"pass"=>$pass);
					$html=$this->load->view('front_end/recuperar_contrasena',$datos,TRUE);
					
					$this->email->subject($asunto);
					$this->email->message($html); 
					$resp=$this->email->send();

					if ($resp) {
						$data=array("contrasena"=>sha1($pass));
						$this->Iniciomodel->recuperarpass($rut,$data);
						echo json_encode(array("res" => "ok" , "msg" => "Solicitud de recuperación enviada a su correo."));exit;
					}else{
						echo json_encode(array("res" => "error" , "msg" => "Error enviando la contraseña, intente más tarde."));exit;
					}
				}
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
		       'titulo' => "PTO XR3",
		       'perfiles' => $this->Iniciomodel->listaPerfiles(),
			);  
			$this->load->view('inicio',$datos);
		}	
		
		public function cargaInicio(){
			$noticias = $this->Iniciomodel->getNoticias("");
			$categorias = $this->Iniciomodel->cargaCategorias();
			$ingresos = $this->Iniciomodel->cargaIngresos();
			$informaciones = $this->Iniciomodel->cargaInformaciones();
			$cumpleanios = $this->Iniciomodel->cargaCumpleanios();
			$visitas_hoy = $this->Iniciomodel->dataVisitasHoy();
			$visitas_ayer = $this->Iniciomodel->dataVisitasAyer();

		    echo json_encode(array(
		    	"noticias" => $this->load->view('front_end/widgets/noticias',array("noticias"=>$noticias),TRUE),
		    	"cumpleanios" => $this->load->view('front_end/widgets/cumpleanios',array("cumpleanios"=>$cumpleanios),TRUE),
		    	"categorias" => $this->load->view('front_end/widgets/menu_categorias',array("categorias"=>$categorias),TRUE),
		    	"ingresos" => $this->load->view('front_end/widgets/ultimos_ingresos',array("ingresos"=>$ingresos),TRUE),
		    	"informaciones" => $this->load->view('front_end/widgets/informaciones',array("informaciones"=>$informaciones),TRUE),
		    	"visitas" => $this->load->view('front_end/widgets/visitas',
		    		array(
		    			"visitas_hoy"=>$visitas_hoy,
		    			"visitas_ayer"=>$visitas_ayer,
		    			"totalVisitasHoy"=>$this->Iniciomodel->totalVisitasHoy(),
		    			"totalVisitasAyer"=>$this->Iniciomodel->totalVisitasAyer()
		    			),TRUE),
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

		public function listaUsuarios(){
			 echo $this->Iniciomodel->listaUsuarios();exit;
		}
		public function listaJefatura(){
			 echo $this->Iniciomodel->listaJefatura();exit;
		}
		

		public function verComo(){
		      $perfil=$this->security->xss_clean(strip_tags($this->input->post("p")));
		      $this->session->set_userdata("id_perfil",$perfil); 
		      echo json_encode(array('res'=>"success"));exit;
	    }

		
		public function saludoCumpleanios(){
			$fecha=substr(date("Y-m-d"), 5,10);
			/*$fecha = substr("2022-08-16", 5,10);*/
			$data = $this->Iniciomodel->correoCumpleanios($fecha);
			$this->load->library('email');	
			$prueba=FALSE;

			if($data!=FALSE){
			   	foreach($data as $key){
					$config = array (
						'mailtype' => 'html',
						'charset'  => 'utf-8',
						'priority' => '1',
						'wordwrap' => TRUE,
						'protocol' => "smtp",//sendmail
						'smtp_port' => 587,//587
						'smtp_host' => $this->config->item('rep_smtp_host'),
						'smtp_user' => $this->config->item('rep_smtp_user'),
						'smtp_pass' => $this->config->item('rep_smtp_pass')
					);

					$this->email->initialize($config);
					$datos=array("data"=>$data);
				    $html=$this->load->view('back_end/cron/cumpleanios',$datos,TRUE);
				    /*  echo $html;exit;*/

					$this->email->from("reportes@xr3t.cl","Reporte plataforma XR3");

					if($prueba){
						$para = array("ricardo.hernandez@km-telecomunicaciones.cl","soporteplataforma@xr3t.cl");
						$copias = array("ricardo.hernandez@splice.cl");

					}else{
						$para = array();
						$para[] = $key["correo_empresa"]!="" ? $key["correo_empresa"] : $key["correo_personal"];
						$para[] = $key["correo_jefe_empresa"]!="" ? $key["correo_jefe_empresa"] : $key["correo_jefe_personal"];
						$copias = array("roberto.segovia@xr3.cl","cristian.cortes@xr3.cl");
					}

					/*print_r($para);
					print_r($copias);
					exit;*/


					$this->email->to($para);
					$this->email->cc($copias);
		    		//$this->email->bcc(array("ricardo.hernandez@km-telecomunicaciones.cl","ricardo.hernandez@splice.cl"));
					$this->email->subject($key["nombre_corto"].", saludos en tu cumpleaños.");
					$this->email->message($html); 
					$resp=$this->email->send();

					if ($resp) {
						return TRUE;
					}else{
						print_r($this->email->print_debugger());
					}
				}	
			}		
		}


}