<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Prevencion_documentacion extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if($this->uri->segment("1")==""){
			redirect("inicio");
		}
		$this->load->model("back_end/Prevencion_documentacionmodel");
		$this->load->model("back_end/Iniciomodel");
		$this->load->helper(array('fechas','str'));
		$this->load->library('user_agent');
	}

	public function visitas($modulo,$id_aplicacion){
		$this->load->library('user_agent');
		$data=array("id_usuario"=>$this->session->userdata('id'),
			"id_aplicacion"=>$id_aplicacion,
			"modulo"=>$modulo,
	     	"fecha"=>date("Y-m-d G:i:s"),
	    	"navegador"=>"navegador :".$this->agent->browser()."\nversion :".$this->agent->version()."\nos :".$this->agent-> platform()."\nmovil :".$this->agent->mobile(),
	    	"ip"=>$this->input->ip_address(),
    	);
    	$this->Prevencion_documentacionmodel->insertarVisita($data);
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

	/***********NORMATIVAS*****************/

		public function indexNormativas(){
	    	$this->acceso();

			$tipo = $this->uri->segment(2);

		    $datos = array(
		        'titulo' => "Prevención riesgos XR3",
		        'contenido' => "prevencion_documentacion/inicio",
				'tipo' => $tipo,
		        'perfiles' => $this->Iniciomodel->listaPerfiles(),
			);  
			$this->load->view('plantillas/plantilla_back_end',$datos);
			
		}

		public function vistaNormativas(){
			$tipo=$this->security->xss_clean(strip_tags($this->input->get_post("tipo")));
			$this->visitas("Inicio",2);

			if($this->input->is_ajax_request()){

				$datos=array(	
					"tipo" => $tipo,
					"tipo_str" => $this->getTipoCompletoDoc($tipo)
			    );

				$this->load->view('back_end/prevencion_documentacion/normativas',$datos);
			}
		}

		public function getNormativasList(){
			$tipo=$this->security->xss_clean(strip_tags($this->input->get_post("tipo")));

			$normativasList = $this->Prevencion_documentacionmodel->getNormativasList($this->getTipoDoc($tipo));

			foreach ($normativasList as &$normativa) {
				$normativa['descripcion'] = strip_tags($normativa['descripcion']);
				$normativa['descripcion'] = html_entity_decode($normativa['descripcion']);
				$normativa['descripcion'] = preg_replace('/\s+/', ' ', $normativa['descripcion']);
				$normativa['descripcion'] = trim($normativa['descripcion']);
			}

			echo json_encode($normativasList);

		}

		public function getTipoDoc($tipo) {
			$tipos = [
				'normativas' => 1,
				'identificacion_riesgos' => 2,
				'medidas_proteccion' => 3,
				'seguridad_equipos_herramientas' => 4,
				'primeros_auxilios' => 5,
				'ergonomia_y_cuidado' => 6,
				'comunicacion_conciencia' => 7
			];
		
			return $tipos[$tipo] ?? 1;
		}

		public function getTipoCompletoDoc($tipo) {
			$tipos = [
				'normativas' => 'Normativas y regulaciones de seguridad',
				'identificacion_riesgos' => 'Identificación de riesgos',
				'medidas_proteccion' => 'Medidas de prevención y protección',
				'seguridad_equipos_herramientas' => 'Seguridad en el manejo de equipos y herramientas',
				'primeros_auxilios' => 'Primeros auxilios y manejo de emergencias',
				'ergonomia_y_cuidado' => 'Ergonomía y cuidado postural',
				'comunicacion_conciencia' => 'Comunicación y conciencia situacional'
			];
		
			return $tipos[$tipo] ?? 1;
		}
		
		public function formIngresoNormativas(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();	
				$hash=$this->security->xss_clean(strip_tags($this->input->post("hash_id")));
				$titulo=$this->security->xss_clean(strip_tags($this->input->post("titulo")));
				$tipo=$this->getTipoDoc($this->security->xss_clean(strip_tags($this->input->post("tipo"))));
				$descripcion=$this->input->post("descripcion");
				$fecha = date("Y-m-d G:i:s");
				$id_autor=$this->session->userdata("id");
				$ultima_actualizacion=date("Y-m-d G:i:s")." | ".$this->session->userdata("nombre_completo");
				$archivo_principal=@$_FILES["archivo_principal"]["name"];
				$link1=@$_FILES["link1"]["name"];
				$link2=@$_FILES["link2"]["name"];
				$link3=@$_FILES["link3"]["name"];

	   			if ($this->form_validation->run("formIngresoNormativas") == FALSE){
					echo json_encode(array('res'=>"error", 'msg' => strip_tags(validation_errors())));exit;
				}else{	
					
					$archivo = $_POST['imagen']; 

			    	if($hash==FALSE){

						$data = array(
							'id_categoria' => $tipo,
							'titulo' => $titulo,
							'descripcion' => $descripcion,
							'id_autor' => $id_autor,
							'fecha' => $fecha,
							'ultima_actualizacion' => $ultima_actualizacion);
						
						if($archivo_principal!=""){

							$path = $_FILES['archivo_principal']['name'];
							$ext = pathinfo($path, PATHINFO_EXTENSION);

							if($ext!="mp4" and $ext!="MP4" and $ext!="png" and $ext!="PNG" and $ext!="jpg" and $ext!="JPG" and $ext!="jpeg" and $ext!="JPEG"){
								echo json_encode(array('res'=>'error', 'msg' => 'Formato de archivo no soportado.'));exit;
							}

							if($ext=="mp4"){

								$nombre=$this->procesaVideo($_FILES["archivo_principal"]);
								$data["archivo"]=$nombre;

							}else{
								$img = str_replace('data:image/png;base64,', '', $archivo);
								$nombre=strtolower(url_title(convert_accented_characters("doc".date("ymdHis")))).".jpg";
								file_put_contents('./archivos/prevencion_modulos/'.$nombre, base64_decode($img));
								$data["archivo"]=$nombre;
							}
						}

						
			    		if($link1!=""){
							$nombre=$this->procesaArchivo($_FILES["link1"],"link1"."_".date("ymdHis"));
							$data["link1"]=$nombre;
						}

						if($link2!=""){
							$nombre=$this->procesaArchivo($_FILES["link2"],"link2"."_".date("ymdHis"));
							$data["link2"]=$nombre;
						}

						if($link3!=""){
							$nombre=$this->procesaArchivo($_FILES["link3"],"link3"."_".date("ymdHis"));
							$data["link3"]=$nombre;
						}

						$insert_id=$this->Prevencion_documentacionmodel->formIngresoNormativas($data);
						if($insert_id!=FALSE){
							echo json_encode(array('res'=>"ok",  'msg' => OK_MSG));exit;
						}else{
							echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
						}
				

					}else{
						$archivo = $_POST['imagen']; 

						$data = array(
		    		 	'titulo' => $titulo,
						'descripcion'=>$descripcion,
		    		 	'ultima_actualizacion' => $ultima_actualizacion);
						
						 if($archivo_principal!=""){

							$path = $_FILES['archivo_principal']['name'];
							$ext = pathinfo($path, PATHINFO_EXTENSION);

							if($ext!="mp4" and $ext!="MP4" and $ext!="png" and $ext!="PNG" and $ext!="jpg" and $ext!="JPG" and $ext!="jpeg" and $ext!="JPEG"){
								echo json_encode(array('res'=>'error', 'msg' => 'Formato de archivo no soportado.'));exit;
							}

							$imagen=$this->Prevencion_documentacionmodel->getImagen($hash);
							if($imagen!=""){
								if (file_exists('./archivos/prevencion_modulos/'.$imagen)){
									unlink('./archivos/prevencion_modulos/'.$imagen);
								}
							}
							
							if($ext=="mp4"){

								$nombre=$this->procesaVideo($_FILES["archivo_principal"]);
								$data["archivo"]=$nombre;

							}else{
								$img = str_replace('data:image/png;base64,', '', $archivo);
								$nombre=strtolower(url_title(convert_accented_characters("doc".date("ymdHis")))).".jpg";
								file_put_contents('./archivos/prevencion_modulos/'.$nombre, base64_decode($img));
								$data["archivo"]=$nombre;
							}
						}

						if($link1!=""){
							$nombre=$this->procesaArchivo($_FILES["link1"],"link1"."_".date("ymdHis"));
							$data["link1"]=$nombre;
						}

						if($link2!=""){
							$nombre=$this->procesaArchivo($_FILES["link2"],"link2"."_".date("ymdHis"));
							$data["link2"]=$nombre;
						}

						if($link3!=""){
							$nombre=$this->procesaArchivo($_FILES["link3"],"link3"."_".date("ymdHis"));
							$data["link3"]=$nombre;
						}


						if($this->Prevencion_documentacionmodel->formActualizarNormativas($hash,$data)){
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

		public function procesaVideo($file){
			$path = $file['name'];
			$ext = pathinfo($path, PATHINFO_EXTENSION);
			$archivo=strtolower(url_title(convert_accented_characters("doc".date("ymdHis")))).".".$ext;
			$config['upload_path'] = './archivos/prevencion_modulos';
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


		public function procesaArchivo($file,$titulo){
			$path = $file['name'];
			$ext = pathinfo($path, PATHINFO_EXTENSION);
			$archivo=strtolower(url_title(convert_accented_characters($titulo.date("ymdHis")))).".".$ext;
			$config['upload_path'] = './archivos/prevencion_modulos';
			$config['allowed_types'] = 'jpg|jpeg|png|pdf|PDF|xls|xlsx|doc|docx';
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

		public function eliminaNormativas(){
			$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
			$data=$this->Prevencion_documentacionmodel->getDataNormativas($hash);
			foreach($data as $key){
				if($key["archivo"]!=""){
					if (file_exists("./archivos/prevencion_modulos/".$key["archivo"])){
						unlink("./archivos/prevencion_modulos/".$key["archivo"]);
					}	
				}
			}

			if($this->Prevencion_documentacionmodel->eliminaNormativas($hash)){
				echo json_encode(array("res" => "ok" , "msg" => "Registro eliminado correctamente."));
			}else{	
				echo json_encode(array("res" => "error" , "msg" => "Error eliminando el registro, intente nuevamente."));
			}
		}

		public function getDataNormativas(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();	
				$hash=$this->security->xss_clean(strip_tags($this->input->post("hash_id")));
				$data=$this->Prevencion_documentacionmodel->getDataNormativas($hash);
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