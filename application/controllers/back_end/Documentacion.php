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

	public function visitas($modulo,$id_aplicacion){
		$this->load->library('user_agent');
		$data=array("id_usuario"=>$this->session->userdata('id'),
			"id_aplicacion"=>$id_aplicacion,
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

	/***********CAPACITACION*****************/

		public function indexCapacitacion(){
	    	$this->acceso();
		    $datos = array(
		        'titulo' => "Documentación XR3",
		        'contenido' => "documentacion/inicio",
		        'perfiles' => $this->Iniciomodel->listaPerfiles(),
			);  
			$this->load->view('plantillas/plantilla_back_end',$datos);
			
		}

		public function vistaCapacitacion(){
			$this->visitas("Inicio",2);
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
				$config['allowed_types'] = 'pdf|jpg|jpeg|bmp|png|doc|docx|xls|xlsx|ppt|pptx|html|htm|XLS|XLSX|DOC|mp4|MP4';
			    $config['file_name'] = $archivo;
				$config['max_size']	= '80300';
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

	/***********REPORTES*****************/

		public function indexReportes(){
	    	$this->acceso();
		    $datos = array(
		        'titulo' => "Documentación XR3",
		        'contenido' => "documentacion/inicio",
		        'perfiles' => $this->Iniciomodel->listaPerfiles(),
			);  
			$this->load->view('plantillas/plantilla_back_end',$datos);
			
		}
		
		public function vistaReportes(){
			$this->visitas("Inicio",4);
			if($this->input->is_ajax_request()){
				$datos=array();
				$this->load->view('back_end/documentacion/reportes',$datos);
			}
		}

		public function getReportesList(){
			echo json_encode($this->Documentacionmodel->getReportesList());
		}
		
		public function formIngresoReportes(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();	
				$hash=$this->security->xss_clean(strip_tags($this->input->post("hash_id")));
				$nombre=$this->security->xss_clean(strip_tags($this->input->post("nombre_archivo")));
				$fecha = date("Y-m-d");
				$digitador=$this->session->userdata("id");
				$ultima_actualizacion=date("Y-m-d G:i:s")." | ".$this->session->userdata("nombre_completo");
		   		$adjunto=@$_FILES["userfile"]["name"];

	   			if ($this->form_validation->run("formIngresoReportes") == FALSE){
					echo json_encode(array('res'=>"error", 'msg' => strip_tags(validation_errors())));exit;
				}else{	

					
			    	if($hash==FALSE){

			    		$data = array(
		    		 	'nombre' => $nombre,
		    		 	'id_digitador' => $digitador,
		    		 	'fecha' => $fecha,
		    		 	'ultima_actualizacion' => $ultima_actualizacion);


			    		if($adjunto!=""){
							$nombre=$this->procesaArchivoReportes($_FILES["userfile"],$nombre."_".date("ymdHis"),1);
							$data["archivo"]=$nombre;
						}else{
							$data["archivo"]="";
						}

						$insert_id=$this->Documentacionmodel->formIngresoReportes($data);
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
							$nombre=$this->procesaArchivoReportes($_FILES["userfile"],$nombre."_".date("ymdHis"),1);
							$data["archivo"]=$nombre;

							$archivo_bd=$this->Documentacionmodel->getDataRegistroReportes($hash);

							foreach($archivo_bd as $a){
								if($a["archivo"]!=""){
									if (file_exists("./archivos_documentacion/reportes/".$a["archivo"])){
										unlink("./archivos_documentacion/reportes/".$a["archivo"]);
									}	
								}
							}

						}

						if($this->Documentacionmodel->formActualizarReportes($hash,$data)){
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

		public function procesaArchivoReportes($file,$titulo){
			/*	var_dump($_FILES);*/
			$path = $file['name'];
			$ext = pathinfo($path, PATHINFO_EXTENSION);
			$archivo=strtolower(url_title(convert_accented_characters($titulo.rand(10, 1000)))).".".$ext;
			$config['upload_path'] = './archivos_documentacion/reportes';
			$config['allowed_types'] = 'xlsx|csv|xls|pdf|PDF|XLS|XLSX|jpg|jpeg|bmp|png|doc|docx|ppt|pptx|html|htm|DOC|mp4|MP4';
		    $config['file_name'] = $archivo;
			$config['max_size']	= '26000';
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

		public function eliminaReportes(){
			$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
			$data=$this->Documentacionmodel->getDataRegistroReportes($hash);
			foreach($data as $key){
				if($key["archivo"]!=""){
					if (file_exists("./archivos_documentacion/reportes/".$key["archivo"])){
						unlink("./archivos_documentacion/reportes/".$key["archivo"]);
					}	
				}
			}

			if($this->Documentacionmodel->eliminaReportes($hash)){
				echo json_encode(array("res" => "ok" , "msg" => "Registro eliminado correctamente."));
			}else{	
				echo json_encode(array("res" => "error" , "msg" => "Error eliminando el registro, intente nuevamente."));
			}
		}

		public function getDataRegistroReportes(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();	
				$hash=$this->security->xss_clean(strip_tags($this->input->post("hash_id")));
				$data=$this->Documentacionmodel->getDataRegistroReportes($hash);
				if($data){
					echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
				}else{
					echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
				}	
			}else{
				exit('No direct script access allowed');
			}
		}

	/***********PREVENCION*****************/

		public function indexPrevencion(){
	    	$this->acceso();
		    $datos = array(
		        'titulo' => "Documentación XR3",
		        'contenido' => "documentacion/inicio",
		        'perfiles' => $this->Iniciomodel->listaPerfiles(),
			);  
			$this->load->view('plantillas/plantilla_back_end',$datos);
		}

		public function vistaPrevencion(){
			$this->visitas("Inicio",3);
			if($this->input->is_ajax_request()){
				$datos=array();
				$this->load->view('back_end/documentacion/prevencion_riesgos',$datos);
			}
		}

		public function getPrevencionList(){
			echo json_encode($this->Documentacionmodel->getPrevencionList());
		}
		
		public function formIngresoPrevencion(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();	
				$hash=$this->security->xss_clean(strip_tags($this->input->post("hash_id")));
				$nombre=$this->security->xss_clean(strip_tags($this->input->post("nombre_archivo")));
				$fecha = date("Y-m-d");
				$digitador=$this->session->userdata("id");
				$ultima_actualizacion=date("Y-m-d G:i:s")." | ".$this->session->userdata("nombre_completo");
		   		$adjunto=@$_FILES["userfile"]["name"];

	   			if ($this->form_validation->run("formIngresoPrevencion") == FALSE){
					echo json_encode(array('res'=>"error", 'msg' => strip_tags(validation_errors())));exit;
				}else{	
					
			    	if($hash==FALSE){

			    		$data = array(
		    		 	'nombre' => $nombre,
		    		 	'id_digitador' => $digitador,
		    		 	'fecha' => $fecha,
		    		 	'ultima_actualizacion' => $ultima_actualizacion);


			    		if($adjunto!=""){
							$nombre=$this->procesaArchivoPrevencion($_FILES["userfile"],$nombre."_".date("ymdHis"),1);
							$data["archivo"]=$nombre;
						}else{
							$data["archivo"]="";
						}

						$insert_id=$this->Documentacionmodel->formIngresoPrevencion($data);
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
							$nombre=$this->procesaArchivoPrevencion($_FILES["userfile"],$nombre."_".date("ymdHis"),1);
							$data["archivo"]=$nombre;

							$archivo_bd=$this->Documentacionmodel->getDataRegistroPrevencion($hash);

							foreach($archivo_bd as $a){
								if($a["archivo"]!=""){
									if (file_exists("./archivos_documentacion/prevencion/".$a["archivo"])){
										unlink("./archivos_documentacion/prevencion/".$a["archivo"]);
									}	
								}
							}

						}

						if($this->Documentacionmodel->formActualizarPrevencion($hash,$data)){
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

		public function procesaArchivoPrevencion($file,$titulo){
				$path = $file['name'];
				$ext = pathinfo($path, PATHINFO_EXTENSION);
				$archivo=strtolower(url_title(convert_accented_characters($titulo.rand(10, 1000)))).".".$ext;
				$config['upload_path'] = './archivos_documentacion/prevencion';
				$config['allowed_types'] = 'pdf|jpg|jpeg|bmp|png|doc|docx|xls|xlsx|ppt|pptx|html|htm|XLS|XLSX|DOC|mp4|MP4';
			    $config['file_name'] = $archivo;
				$config['max_size']	= '10300';
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

		public function eliminaPrevencion(){
			$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
			$data=$this->Documentacionmodel->getDataRegistroPrevencion($hash);
			foreach($data as $key){
				if($key["archivo"]!=""){
					if (file_exists("./archivos_documentacion/prevencion/".$key["archivo"])){
						unlink("./archivos_documentacion/prevencion/".$key["archivo"]);
					}	
				}
			}

			if($this->Documentacionmodel->eliminaPrevencion($hash)){
				echo json_encode(array("res" => "ok" , "msg" => "Registro eliminado correctamente."));
			}else{	
				echo json_encode(array("res" => "error" , "msg" => "Error eliminando el registro, intente nuevamente."));
			}
		}

		public function getDataRegistroPrevencion(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();	
				$hash=$this->security->xss_clean(strip_tags($this->input->post("hash_id")));
				$data=$this->Documentacionmodel->getDataRegistroPrevencion($hash);
				if($data){
					echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
				}else{
					echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
				}	
			}else{
				exit('No direct script access allowed');
			}
		}


		/***********DATAS MANDANTE*****************/

		public function indexDatas(){
	    	$this->acceso();
		    $datos = array(
		        'titulo' => "Datas mandante XR3",
		        'contenido' => "documentacion/inicio",
		        'perfiles' => $this->Iniciomodel->listaPerfiles(),
			);  
			$this->load->view('plantillas/plantilla_back_end',$datos);
			
		}

		public function vistaDatas(){
			$this->visitas("Inicio",18);
			if($this->input->is_ajax_request()){
				$datos=array(	
			    );
				$this->load->view('back_end/documentacion/datas',$datos);
			}
		}

		public function getDatasList(){
			echo json_encode($this->Documentacionmodel->getDatasList());
		}
		
		public function formIngresoDatas(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();	
				$hash=$this->security->xss_clean(strip_tags($this->input->post("hash_id")));
				$nombre=$this->security->xss_clean(strip_tags($this->input->post("nombre_archivo")));
				$fecha = date("Y-m-d");
				$digitador=$this->session->userdata("id");
				$ultima_actualizacion=date("Y-m-d G:i:s")." | ".$this->session->userdata("nombre_completo");
		   		$adjunto=@$_FILES["userfile"]["name"];

	   			if ($this->form_validation->run("formIngresoDatas") == FALSE){
					echo json_encode(array('res'=>"error", 'msg' => strip_tags(validation_errors())));exit;
				}else{	

					
			    	if($hash==FALSE){

			    		$data = array(
		    		 	'nombre' => $nombre,
		    		 	'id_digitador' => $digitador,
		    		 	'fecha' => $fecha,
		    		 	'ultima_actualizacion' => $ultima_actualizacion);


			    		if($adjunto!=""){
							$nombre=$this->procesaArchivoDatas($_FILES["userfile"],$nombre."_".date("ymdHis"),1);
							$data["archivo"]=$nombre;
						}else{
							$data["archivo"]="";
						}

						$insert_id=$this->Documentacionmodel->formIngresoDatas($data);
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
							$nombre=$this->procesaArchivoDatas($_FILES["userfile"],$nombre."_".date("ymdHis"),1);
							$data["archivo"]=$nombre;

							$archivo_bd=$this->Documentacionmodel->getDataRegistroDatas($hash);

							foreach($archivo_bd as $a){
								if($a["archivo"]!=""){
									if (file_exists("./archivos_documentacion/datas/".$a["archivo"])){
										unlink("./archivos_documentacion/datas/".$a["archivo"]);
									}	
								}
							}

						}

						if($this->Documentacionmodel->formActualizarDatas($hash,$data)){
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

		public function procesaArchivoDatas($file,$titulo){
				$path = $file['name'];
				$ext = pathinfo($path, PATHINFO_EXTENSION);
				$archivo=strtolower(url_title(convert_accented_characters($titulo.rand(10, 1000)))).".".$ext;
				$config['upload_path'] = './archivos_documentacion/datas';
				$config['allowed_types'] = 'pdf|jpg|jpeg|bmp|png|doc|docx|xls|xlsx|ppt|pptx|html|htm|XLS|XLSX|DOC|mp4|MP4';
			    $config['file_name'] = $archivo;
				$config['max_size']	= '80300';
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

		public function eliminaDatas(){
			$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
			$data=$this->Documentacionmodel->getDataRegistroDatas($hash);
			foreach($data as $key){
				if($key["archivo"]!=""){
					if (file_exists("./archivos_documentacion/datas/".$key["archivo"])){
						unlink("./archivos_documentacion/datas/".$key["archivo"]);
					}	
				}
			}

			if($this->Documentacionmodel->eliminaDatas($hash)){
				echo json_encode(array("res" => "ok" , "msg" => "Registro eliminado correctamente."));
			}else{	
				echo json_encode(array("res" => "error" , "msg" => "Error eliminando el registro, intente nuevamente."));
			}
		}

		public function getDataRegistroDatas(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();	
				$hash=$this->security->xss_clean(strip_tags($this->input->post("hash_id")));
				$data=$this->Documentacionmodel->getDataRegistroDatas($hash);
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