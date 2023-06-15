<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if($this->uri->segment("1")==""){
			redirect("inicio");
		}
		$this->load->model("Adminmodel");
		$this->load->helper(array('fechas','str'));
		$this->load->library('user_agent');
	}

	public function nojs(){	
		$this->load->view('nojs');
	}

    public function acceso(){
		if($this->session->userdata("id")==""){
			redirect("../");
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
    	$this->Adminmodel->insertarVisita($data);
	}


	public function index(){	
		$this->acceso();		
		$this->visitas("Admin");
    	$fecha_hoy=date('Y-m-d');
	    $datos = array(
	       'titulo' => "Inicio",
	       'contenido' => "inicio",
	       'fecha_hoy' => $fecha_hoy
		);  
		$this->load->view('plantillas/plantilla_admin',$datos);
	}	

	/***********NOTICIAS********/

		public function cargaVistaNoticiasAdmin(){
		    $datos = array(
		    	"categorias" => $this->Adminmodel->listaCategorias()
		    );  
			$this->load->view('admin/noticias',$datos);
		}

		public function listaNoticiasAdmin(){
	        echo json_encode($this->Adminmodel->listaNoticiasAdmin());
		}
		
		public function nuevaNoticiaAdmin(){
			if($this->input->is_ajax_request()){
				$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
				$titulo=$this->security->xss_clean(strip_tags($this->input->post("titulo")));
				$categoria=$this->security->xss_clean(strip_tags($this->input->post("categoria")));
				$descripcion=$this->input->post("descripcion");
				$archivo_principal=@$_FILES["archivo_principal"]["name"];
				$fecha=date("Y-m-d H:i:s", time());
				$url=strtolower(url_title(convert_accented_characters($titulo)));
				$permitidos = array('png', 'jpg', 'jpeg');
				// $checkcorreo=$this->security->xss_clean(strip_tags($this->input->post("checkcorreo")));

				if ($this->form_validation->run("nuevaNoticiaAdmin") == FALSE){
					echo json_encode(array('res'=>"error", 'msg' => strip_tags(validation_errors())));exit;
				}else{	

					$archivo = $_POST['imagen']; 

					if($hash==""){

						if($archivo_principal==""){
							echo json_encode(array('res'=>"error", 'msg' => "Debe subir una imágen."));exit;
						}

						$path = $_FILES['archivo_principal']['name'];
						$ext = pathinfo($path, PATHINFO_EXTENSION);

						if($ext!="mp4" and $ext!="MP4" and $ext!="png" and $ext!="PNG" and $ext!="jpg" and $ext!="JPG" and $ext!="jpeg" and $ext!="JPEG"){
							echo json_encode(array('res'=>'error', 'msg' => 'Formato de archivo no soportado.'));exit;
						}

						$data=array("id_autor"=>$this->session->userdata("id"),
							"titulo"=>$titulo,
							"fecha" => $fecha,
							"id_categoria"=>$categoria,
							"descripcion"=>$descripcion,
							"url"=>$url
						);	
						
						if($ext=="mp4"){

							$nombre=$this->procesaVideo($_FILES["archivo_principal"]);
							$data["imagen"]=$nombre;

						}else{
							if($archivo=="data:,"){
						  	  echo json_encode(array('res'=>"error", 'msg' => "Debe subir una imágen."));exit;
							}

							$img = str_replace('data:image/png;base64,', '', $archivo);
							$nombre=strtolower(url_title(convert_accented_characters("noticia_".rand(10, 1000)))).".jpg";
							file_put_contents('./noticias/imagenes/'.$nombre, base64_decode($img));
							$data["imagen"]=$nombre;
						}

						$id_noticia=$this->Adminmodel->nuevaNoticiaAdmin($data);

						$imagenes_secundarias=$_FILES['archivos_secundarios']['name'];

						if($imagenes_secundarias[0]!=""){
							if($this->Adminmodel->getCantidadImagenes($id_noticia)<10){

								
								for ($i=0; $i <count($imagenes_secundarias) ; $i++) { 
									$nombre_archivo = $_FILES['archivos_secundarios']['name'][$i];
									$extension = pathinfo($nombre_archivo, PATHINFO_EXTENSION);
									if (!in_array($extension, $permitidos)) {
									    echo json_encode(array('res'=>"error", 'msg' =>"Formato de imágen secundaria no soportado."));exit;
									}
								}

					    		for ($i=0; $i <count($imagenes_secundarias) ; $i++) { 

					    			$imagen=rand(1,9999).url_title(convert_accented_characters($_FILES['archivos_secundarios']['name'][$i]),'-',TRUE);
					    			$path = $_FILES['archivos_secundarios']['name'][$i];
									$ext = pathinfo($path, PATHINFO_EXTENSION);
									$archivo=$imagen.".".$ext;
									$config['upload_path'] = './noticias/imagenes';
									$config['allowed_types'] = 'jpg|jpeg|png';
								    $config['file_name'] = $archivo;
									$config['max_size']	= '5300';
									$config['overwrite']	= FALSE;
									$config['width'] = "700";      
							    	$config['height'] = "330";

									$this->load->library('upload', $config);
									$_FILES['userfile']['name'] = $archivo;
								    $_FILES['userfile']['type'] = $_FILES['archivos_secundarios']['type'][$i];
								    $_FILES['userfile']['tmp_name'] = $_FILES['archivos_secundarios']['tmp_name'][$i];
								    $_FILES['userfile']['error'] = $_FILES['archivos_secundarios']['error'][$i];
									$_FILES['userfile']['size'] = $_FILES['archivos_secundarios']['size'][$i];
									$this->upload->initialize($config);
									$this->upload->initialize($config);

									if (!$this->upload->do_upload()){ 
									    echo json_encode(array('res'=>"error", 'msg' =>strip_tags($this->upload->display_errors())));exit;
								    }else{
								    	unset($config);
								    }

									$data_imagenes=array(
								   		'id_noticia'=>$id_noticia,
								   		'titulo' 	=>"",
								   		'imagen'	=> $archivo
								   	);

								   $this->Adminmodel->agregaImagenesNoticia($data_imagenes);

					    		}
					    	}
					    }

					    if($id_noticia!=""){
					    	// if($checkcorreo=="on"){
					    	// 	$this->preparaCorreoMasivo();
					    	// }
							echo json_encode(array('res'=>"ok", 'msg' => 'Noticia ingresada correctamente.'));exit;
			    		}else{
			    			echo json_encode(array('res'=>"error", 'msg' => "Problemas ingresando la noticia, intente más tarde."));exit;
			    		}


					}else{

						$id_noticia=$this->Adminmodel->getIdPorHash($hash);

						$data_mod=array(
							"titulo"=>$titulo,
							"id_categoria"=>$categoria,
							"descripcion"=>$descripcion,
							"url"=>$url
						);	

						if($archivo_principal!=""){

							$path = $_FILES['archivo_principal']['name'];
							$ext = pathinfo($path, PATHINFO_EXTENSION);

							if($ext!="mp4" and $ext!="MP4" and $ext!="png" and $ext!="PNG" and $ext!="jpg" and $ext!="JPG" and $ext!="jpeg" and $ext!="JPEG"){
								echo json_encode(array('res'=>'error', 'msg' => 'Formato de archivo no soportado.'));exit;
							}

							$imagen=$this->Adminmodel->getImagen($hash);
							if($imagen!=""){
								if (file_exists('./noticias/imagenes/'.$imagen)){
									unlink('./noticias/imagenes/'.$imagen);
								}
							}
							

							if($ext=="mp4"){

								$nombre=$this->procesaVideo($_FILES["archivo_principal"]);
								$data_mod["imagen"]=$nombre;

							}else{
								$img = str_replace('data:image/png;base64,', '', $archivo);
								$nombre=strtolower(url_title(convert_accented_characters("noticia_".rand(10, 99999)))).".jpg";
								file_put_contents('./noticias/imagenes/'.$nombre, base64_decode($img));
								$data_mod["imagen"]=$nombre;
							}
						}

						$imagenes_secundarias=$_FILES['archivos_secundarios']['name'];
						
						if($imagenes_secundarias[0]!=""){
							if($this->Adminmodel->getCantidadImagenes($id_noticia)<10){
					    		for ($i=0; $i <count($imagenes_secundarias) ; $i++) { 
					    			$imagen=rand(1,9999).url_title(convert_accented_characters($_FILES['archivos_secundarios']['name'][$i]),'-',TRUE);
					    			$path = $_FILES['archivos_secundarios']['name'][$i];
									$ext = pathinfo($path, PATHINFO_EXTENSION);
									$archivo=$imagen.".".$ext;
									$config['upload_path'] = './noticias/imagenes';
									$config['allowed_types'] = 'jpg|jpeg|png';
								    $config['file_name'] = $archivo;
									$config['max_size']	= '5300';
									$config['overwrite']	= FALSE;
									$config['width'] = "700";      
							    	$config['height'] = "330";
									$this->load->library('upload', $config);
									$_FILES['userfile']['name'] = $archivo;
								    $_FILES['userfile']['type'] = $_FILES['archivos_secundarios']['type'][$i];
								    $_FILES['userfile']['tmp_name'] = $_FILES['archivos_secundarios']['tmp_name'][$i];
								    $_FILES['userfile']['error'] = $_FILES['archivos_secundarios']['error'][$i];
									$_FILES['userfile']['size'] = $_FILES['archivos_secundarios']['size'][$i];
									$this->upload->initialize($config);

									if (!$this->upload->do_upload()){ 
									    echo json_encode(array('res'=>"error", 'msg' =>strip_tags($this->upload->display_errors())));exit;
								    }else{
								    	unset($config);
								    }

									$data_imagenes=array(
								   		'id_noticia'=>$id_noticia,
								   		'titulo' 	=>"",
								   		'imagen'	=> $archivo
								   	);

								   $this->Adminmodel->agregaImagenesNoticia($data_imagenes);

					    		}
					    	}
				    	}


						if($this->Adminmodel->actualizaNoticia($hash,$data_mod)){
							// if($checkcorreo=="on"){
						    //   		$this->preparaCorreoMasivo();
						    //   	}
			    			echo json_encode(array('res'=>"ok", 'msg' => "Noticia actualizada correctamente."));exit;
			    		}else{
			    			echo json_encode(array('res'=>"error", 'msg' => "Problemas ingresando la noticia, intente más tarde."));exit;
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
			$config['upload_path'] = './assets/imagenes/noticias';
			$config['allowed_types'] = 'jpg|jpeg|png';
		    $config['file_name'] = $archivo;
			$config['max_size']	= '5300';
			$config['overwrite']	= FALSE;
			$config['width'] = "700";      
	    	$config['height'] = "330";

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

		public function procesaVideo($file){
				$path = $file['name'];
				$ext = pathinfo($path, PATHINFO_EXTENSION);
				$archivo=strtolower(url_title(convert_accented_characters("noticia_".rand(10, 1000)))).".".$ext;
				$config['upload_path'] = './noticias/videos';
				$config['allowed_types'] = 'mp4';
			    $config['file_name'] = $archivo;
				$config['max_size']	= '153300';
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

	    public function preparaCorreoMasivo(){
			$data=$this->Adminmodel->getUltimaNoticia();	
			$this->load->library('email');
			$prueba=FALSE;

			foreach($data as $key){
				$arrayCorreos=$this->Adminmodel->getCorreosEmpresaCron($key["empresa"],$prueba);

				foreach($arrayCorreos as $c){
					$data_correo = array("id_noticia" => $key["id"],
								"correo_usuario	" => $c["correo"],
								"empresa	" => $key["empresa"],
								"enviado" => "no");
					$this->Adminmodel->insertaNoticiaCorreo($data_correo);
				}

				if($prueba){

			        $asunto="Aviso noticia : ".$key["titulo"];
					$formcorreo="ricardo.hernandez.esp@gmail.com";
					$formnombre="KM";
				
					$config = array (
			          'mailtype' => 'html',
			          'charset'  => 'utf-8',
			          'priority' => '1',
			          'wordwrap' => TRUE,
			          'protocol' => "mail",
			          'smtp_port' => 587,
			          'smtp_host' => 'smtp.gmail.com',
				      'smtp_user' => 'ricardo.hernandez.esp@gmail.com',
				      'smtp_pass' => 'asdf12121212..'
			        );

				}else{
					$config = array (
			          'mailtype' => 'html',
			          'charset'  => 'utf-8',
			          'priority' => '1',
			          'wordwrap' => TRUE,
			          'protocol' => "mail",
			          'smtp_port' => 587,
			          'smtp_host' => 'mail.km-t.cl',
				      'smtp_user' => 'reporte@km-t.cl',
				      'smtp_pass' => 'R624enQp80O_'
			        );

					$asunto="Aviso noticia : ".$key["titulo"];
					$formcorreo="reporte@km-t.cl";
					$formnombre="";
				}
				
				$this->email->from($formcorreo,$formnombre);
				$this->email->to(array("ricardo.hernandez.esp@gmail.com"));
				$this->email->subject($asunto);
				$this->email->message(json_encode($arrayCorreos));
				$resp=$this->email->send();
			}
		}

		public function enviaCorreoMasivo(){
			$data=$this->Adminmodel->getUltimaNoticiaEnvioCorreo();	
			// print_r($data);exit;
			$this->load->library('email');	
			$prueba=FALSE;
			foreach($data as $key){

				if($prueba){
					$config = array (
			          'mailtype' => 'html',
			          'charset'  => 'utf-8',
			          'priority' => '1',
			          'wordwrap' => TRUE,
			          'protocol' => "mail",
			          'smtp_port' => 587,
			          'smtp_host' => 'smtp.gmail.com',
				      'smtp_user' => 'ricardo.hernandez.esp@gmail.com',
				      'smtp_pass' => 'asdf12121212..'
			        );

					$asunto="Nueva noticia Intranet: ".$key["titulo"];
					$formcorreo="ricardo.hernandez.esp@gmail.com";
					$formnombre="Intranet";
					

				}else{

					if($key["empresa_usuario"]=="km"){

						$config = array (
				          'mailtype' => 'html',
				          'charset'  => 'utf-8',
				          'priority' => '1',
				          'wordwrap' => TRUE,
				          'protocol' => "mail",
				          'smtp_port' => 587,
				          'smtp_host' => 'mail.km-t.cl',
					      'smtp_user' => 'reporte@km-t.cl',
					      'smtp_pass' => 'R624enQp80O_'
				        );

				        $asunto="Nueva noticia KM : ".$key["titulo"];
						$formcorreo="reporte@km-t.cl";
						$formnombre="KM-Telecomunicaciones";
						

					}else if ($key["empresa_usuario"]=="splice") {

						$config = array (
				       	  'mailtype' => 'html',
				          'charset'  => 'utf-8',
				          'priority' => '1',
				          'wordwrap' => TRUE,
				          'protocol' => "smtp",
				          'smtp_port' => 587,
				          'smtp_host' => 'mail.splice.cl',
					      'smtp_user' => 'reporte@splice.cl',
					      'smtp_pass' => 'IY3bH8iGUeJ?'
					      
				        );

				        $asunto="Nueva noticia SPLICE : ".$key["titulo"];
						$formcorreo="reporte@splice.cl";
						$formnombre="Splice";

					}

				}
				
				$datos=array("titulo"=>$key["titulo"],
					"imagen"=>$key["imagen"],
					"descripcion"=>$key["descripcion"],
					"empresa"=>$key["empresa_usuario"]);

				$html=$this->load->view('admin/correonoticias',$datos,TRUE);
				$arrayCorreos=$this->Adminmodel->getCorreosEmpresaDisponibles($key["empresa"],$key["id_noticia"]);
				$this->email->initialize($config);
				$this->email->from($formcorreo,$formnombre);
				$this->email->to($key["correo_usuario"]);
				// $this->email->to("ricardo.hernandez.esp@gmail.com");
				$this->email->bcc(array("ricardo.hernandez.esp@gmail.com"));
				$this->email->subject($asunto);
				$this->email->message($html); 

				if($this->email->send()){	
					echo "ok";
					$data_enviado = array("enviado" => "si");
					$this->Adminmodel->actualizaNoticiaCorreo($key["correo_usuario"],$data_enviado);
				}else{
					echo "error";
					echo $this->email->print_debugger();
				}
			}
		}

		public function eliminaNoticia(){
			$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));

			$imagen=$this->Adminmodel->getImagen($hash);
			if($imagen!=""){
				$ext=explode(".", $imagen);
				if($ext[1]=="mp4"){
					if (file_exists('./noticias/videos/'.$imagen)){
						unlink('./noticias/videos/'.$imagen);
					}
				}else{
					if (file_exists('./noticias/imagenes/'.$imagen)){
						unlink('./noticias/imagenes/'.$imagen);
					}	
				}
				
			}

			$galeria=$this->Adminmodel->getImagenesGaleria($hash);

			if($galeria!=FALSE){
				foreach($galeria as $g){
					if (file_exists('./noticias/imagenes/'.$g["imagen"])){
						unlink('./noticias/imagenes/'.$g["imagen"]);
					}
				}
			}

		    if($this->Adminmodel->eliminaNoticia($hash)){
		        echo json_encode(array("res" => "ok" , "msg" => "Registro eliminado correctamente."));
		    }else{
		      echo json_encode(array("res" => "error" , "msg" => "Problemas eliminando el la serie, intente nuevamente."));
		    }
		}

		public function eliminaImagen(){
			$id=$this->security->xss_clean(strip_tags($this->input->post("id")));
			$imagen=$this->Adminmodel->getImagenGaleria($id);
			if($imagen!=""){
				$ext=explode(".", $imagen);
				if (file_exists('./noticias/imagenes/'.$imagen)){
					unlink('./noticias/imagenes/'.$imagen);
				}
			}

		    if($this->Adminmodel->eliminaImagen($id)){
		      echo json_encode(array("res" => "ok" , "msg" => "Registro eliminado correctamente."));
		    }else{
		      echo json_encode(array("res" => "error" , "msg" => "Problemas eliminando el la serie, intente nuevamente."));
		    }
		}

		
		public function getDataNoticia(){
			if($this->input->is_ajax_request()){
				$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
				$data=$this->Adminmodel->getDataNoticia($hash);
				$galeria=$this->Adminmodel->getDataNoticiaGaleria($hash);
				if($data){
					echo json_encode(array('res'=>"ok", 'datos' => $data, 'galeria' => $galeria));exit;
				}else{
					echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
				}	
			}else{
				exit('No direct script access allowed');
			}
		}


	/***********INFORMACIONES********/

		public function cargaVistaInformaciones(){
		    $datos = array();  
			$this->load->view('admin/informaciones',$datos);
		}

		public function listaInformaciones(){
	        echo json_encode($this->Adminmodel->listaInformaciones());
		}
	
		public function nuevaInformacion(){
			if($this->input->is_ajax_request()){
				$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
				$titulo=$this->security->xss_clean(strip_tags($this->input->post("titulo")));
				$empresa=$this->security->xss_clean(strip_tags($this->input->post("empresa")));
				$fecha=date("Y-m-d");
				$hora=date("G:i:s");

				if ($this->form_validation->run("nuevaInformacion") == FALSE){
					echo json_encode(array('res'=>"error", 'msg' => strip_tags(validation_errors())));exit;
				}else{	

					if($hash==""){

						$data=array("id_digitador"=>$this->session->userdata("id"),
							"titulo"=>$titulo,
							"fecha" => $fecha,
							"hora" => $hora
						);	

						if($this->Adminmodel->nuevaInformacion($data)!=FALSE){
			    			echo json_encode(array('res'=>"ok", 'msg' => "Información ingresada correctamente."));exit;
			    		}else{
			    			echo json_encode(array('res'=>"error", 'msg' => "Problemas ingresando la Información, intente más tarde."));exit;
			    		}

			    		

					}else{

						$data_mod=array(
							"titulo"=>$titulo,
						);	

						if($this->Adminmodel->actualizaInformacion($hash,$data_mod)){
			    			echo json_encode(array('res'=>"ok", 'msg' => "Información actualizada correctamente."));exit;
			    		}else{
			    			echo json_encode(array('res'=>"error", 'msg' => "Problemas ingresando la información, intente más tarde."));exit;
			    		}
					}
				}
				
			}else{
				exit('No direct script access allowed');
			}
		}


		public function eliminaInformacion(){
			$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
		
		    if($this->Adminmodel->eliminaInformacion($hash)){
		      echo json_encode(array("res" => "ok" , "msg" => "Registro eliminado correctamente."));
		    }else{
		      echo json_encode(array("res" => "error" , "msg" => "Problemas eliminando el la serie, intente nuevamente."));
		    }
		}

		
		public function getDataInformacion(){
			if($this->input->is_ajax_request()){
				$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
				$data=$this->Adminmodel->getDataInformacion($hash);
				if($data){
					echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
				}else{
					echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
				}	
			}else{
				exit('No direct script access allowed');
			}
		}


	/***********NACIMIENTOS********/

		public function cargaVistaNacimientos(){
		    $datos = array(
		    );  
			$this->load->view('admin/nacimientos',$datos);
		}

		public function listaNacimientos(){
	        echo json_encode($this->Adminmodel->listaNacimientos());
		}
		
		public function listaUsuarios(){
		    echo $this->Adminmodel->listaUsuarios();exit;
		}

		public function nuevoNacimiento(){
			if($this->input->is_ajax_request()){
				$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
				$usuario=$this->security->xss_clean(strip_tags($this->input->post("usuario")));
				$esposa=$this->security->xss_clean(strip_tags($this->input->post("esposa")));
				$hijo=$this->security->xss_clean(strip_tags($this->input->post("hijo")));
				$fecha=$this->security->xss_clean(strip_tags($this->input->post("fecha")));
				$comentarios=$this->security->xss_clean(strip_tags($this->input->post("comentarios")));
				$archivo_principal=@$_FILES["userfile"]["name"];
				$permitidos = array('png', 'jpg', 'jpeg');

				if ($this->form_validation->run("nuevoNacimiento") == FALSE){
					echo json_encode(array('res'=>"error", 'msg' => strip_tags(validation_errors())));exit;
				}else{	

					if($hash==""){

						$data=array("rut_usuario"=>$usuario,
							"esposa"=>$esposa,
							"hijo" => $hijo,
							"fecha"=>$fecha,
							"comentarios"=>$comentarios
						);	
						
				   		if($archivo_principal!=""){
							$nombre=$this->procesaArchivoNacimientos($_FILES["userfile"],$usuario."_".date("ymdHis"));
							$data["imagen"]=$nombre;
						}else{
							$data["imagen"]="";
						}

						if($this->Adminmodel->nuevoNacimiento($data)!=""){
			    			echo json_encode(array('res'=>"ok", 'msg' => OK_MSG));exit;
			    		}else{
			    			echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
			    		}

			    		

					}else{

						$data_mod=array("rut_usuario"=>$usuario,
							"esposa"=>$esposa,
							"hijo" => $hijo,
							"fecha"=>$fecha,
							"comentarios"=>$comentarios
						);	

						if($archivo_principal!=""){
							$nombre=$this->procesaArchivoNacimientos($_FILES["userfile"],$usuario."_".date("ymdHis"));
							$data_mod["imagen"]=$nombre;
						}
						
						if($this->Adminmodel->actualizaNacimiento($hash,$data_mod)){
			    			echo json_encode(array('res'=>"ok", 'msg' => MOD_MSG));exit;
			    		}else{
			    			echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
			    		}
					}
				}
				
			}else{
				exit('No direct script access allowed');
			}
		}
		

		public function procesaArchivoNacimientos($file,$titulo){
			$path = $file['name'];
			$ext = pathinfo($path, PATHINFO_EXTENSION);
			$archivo=strtolower(url_title(convert_accented_characters($titulo.rand(10, 1000)))).".".$ext;
			$config['upload_path'] = './assets/imagenes/nacimientos';
			$config['allowed_types'] = 'jpg|jpeg|png';
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

		

		public function eliminaNacimientos(){
			$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
			$imagen=$this->Adminmodel->getImagenNacimiento($hash);
			if($imagen!=""){
				if (file_exists('./assets/imagenes/nacimientos/'.$imagen)){
					unlink('./assets/imagenes/nacimientos/'.$imagen);
				}	
			}

		    if($this->Adminmodel->eliminaNacimientos($hash)){
		      echo json_encode(array("res" => "ok" , "msg" => "Registro eliminado correctamente."));
		    }else{
		      echo json_encode(array("res" => "error" , "msg" => "Problemas eliminando el la serie, intente nuevamente."));
		    }
		}
		
		public function getDataNacimiento(){
			if($this->input->is_ajax_request()){
				$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
				$data=$this->Adminmodel->getDataNacimientos($hash);
				if($data){
					echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
				}else{
					echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
				}	
			}else{
				exit('No direct script access allowed');
			}
		}


	/***********VISITAS********/

		public function cargaVistaVisitas(){
		    $datos = array();  
			$this->load->view('admin/visitas',$datos);
		}

		public function listaVisitas(){
	        echo json_encode($this->Adminmodel->listaVisitas());
		}

	


}