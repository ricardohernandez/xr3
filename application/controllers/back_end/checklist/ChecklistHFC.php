<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use Dompdf\Dompdf;
use Dompdf\Options;


class checklistHFC extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if($this->uri->segment("1")==""){
			redirect("inicio");
		}
		$this->load->model("back_end/checklist/Checklisthfcmodel");
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
    	$this->Checklisthfcmodel->insertarVisita($data);
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

	    // if($this->session->userdata('id_perfil')==4){
	    //  	redirect("./productividad");
	    // }
	}

	public function formCargaMasivaChecklistHFC(){
		if (!function_exists('str_contains')) {
		    function str_contains(string $haystack, string $needle): bool
		    {
		        return '' === $needle || false !== strpos($haystack, $needle);
		    }
		}

		if($_FILES['userfile']['name']==""){
		    echo json_encode(array('res'=>'error',  "tipo" => "error" , 'msg'=>"Debe seleccionar un archivo."));exit;
		}
		$fname = $_FILES['userfile']['name'];
		if (strpos($fname, ".") == false) {
	        	 echo json_encode(array('res'=>'error', "tipo" => "error" , 'msg'=>"Debe seleccionar un archivo CSV válido."));exit;
        }
        $chk_ext = explode(".",$fname);

        if($chk_ext[1]!="csv"){
        	 echo json_encode(array('res'=>'error', "tipo" => "error" , 'msg'=>"Debe seleccionar un archivo CSV."));exit;
        }

        $fname = $_FILES['userfile']['name'];
        $chk_ext = explode(".",$fname);

        if(strtolower(end($chk_ext)) == "csv")  {
            $filename = $_FILES['userfile']['tmp_name'];
            $handle = fopen($filename, "r");
            $i=0;$z=0;$y=0;     
         
            while (($data = fgetcsv($handle, 9999, ";")) !== FALSE) {

                if(!empty($data[0])){
            	  
				    $arr=array(
				    	"tipo"=>"0",
				    	"descripcion"=>$data[0],
				    	"valor"=>"0",
					);	

				    $this->Checklisthfcmodel->formChecklistLista($arr);
				    $i++;
			  	 	$arr=array();
	            }
	            
            }

            if($i==0){
            	echo json_encode(array('res'=>'ok', "tipo" => "success", 'msg' => $i." filas insertadas."));exit;
            }

            fclose($handle); 
           	echo json_encode(array('res'=>'ok', "tipo" => "success", 'msg' => "Archivo cargado con éxito, ".$i." filas insertadas."));exit;
        }else{
        	echo json_encode(array('res'=>'error', "tipo" => "error", 'msg' => "Archivo CSV inválido."));exit;
        }   
	}


	/*********REGISTRO OTS************/
		
	    public function index(){
	    	$this->acceso();
    	    $datos = array(
		        'titulo' => "CheckList HFC",
		        'contenido' => "checklist/checklist_hfc/inicio",
		        'perfiles' => $this->Iniciomodel->listaPerfiles(),
			);  
			$this->load->view('plantillas/plantilla_back_end',$datos);
		}
		
		public function vistaChecklistHFC(){
			$this->visitas("Checklist HFC");
			$fecha_anio_atras=date('d-m-Y', strtotime('-360 day', strtotime(date("d-m-Y"))));
	    	$fecha_hoy=date('d-m-Y');
			$tecnicos=$this->Checklisthfcmodel->listaTecnicos();
    		$auditores=$this->Checklisthfcmodel->listaAuditores();
    		$comunas=$this->Checklisthfcmodel->listaComunas();
    		$checklist=$this->Checklisthfcmodel->listaChecklist();
    		$tipos_actividad=$this->Checklisthfcmodel->listaTiposActividad();

			$datos=array(
				'fecha_anio_atras' => $fecha_anio_atras,	   
		        'fecha_hoy' => $fecha_hoy,
				'tecnicos' => $tecnicos,
		   	    'auditores' => $auditores,
		   	    'comunas' => $comunas,
		   	    'checklist' => $checklist,
		   	    'tipos_actividad' => $tipos_actividad
		   	);
			$this->load->view('back_end/checklist/checklist_hfc/checklist/inicio',$datos);
		}

		public function listaChecklistHFC(){
			$desde=$this->security->xss_clean(strip_tags($this->input->get_post("desde")));
			$hasta=$this->security->xss_clean(strip_tags($this->input->get_post("hasta")));
			if($desde!=""){$desde=date("Y-m-d",strtotime($desde));}else{$desde="";}
			if($hasta!=""){$hasta=date("Y-m-d",strtotime($hasta));}else{$hasta="";}	
			echo json_encode($this->Checklisthfcmodel->listaChecklistHFC($desde,$hasta));
		}

		public function formChecklistHFC(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();
				$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
				$auditor=$this->security->xss_clean(strip_tags($this->input->post("auditor")));
				$fecha=$this->security->xss_clean(strip_tags($this->input->post("fecha")));
				if($fecha!=""){$fecha=date("Y-m-d",strtotime($fecha));}else{$fecha="";}
				$tecnico=$this->security->xss_clean(strip_tags($this->input->post("tecnico")));
				$checkcorreo=$this->security->xss_clean(strip_tags($this->input->post("checkcorreo")));
				$ultima_actualizacion=date("Y-m-d G:i:s");
				$n_ot=$this->security->xss_clean(strip_tags($this->input->post("n_ot")));
				$tipo_actividad=$this->security->xss_clean(strip_tags($this->input->post("tipo_actividad")));
				$direccion=$this->security->xss_clean(strip_tags($this->input->post("direccion")));

				$estado=$this->input->post("estado");
				$observacion=$this->input->post("observacion");

				if ($this->form_validation->run("formChecklistHFC") == FALSE){
					echo json_encode(array('res'=>"error", 'msg' => strip_tags(validation_errors())));exit;
				}else{	

					$data_insert=array("auditor_id"=>$auditor,
						"fecha"=>$fecha,
						"ultima_actualizacion"=>$ultima_actualizacion,
						"n_ot"=>$n_ot,
						"tipo_actividad"=>$tipo_actividad,
						"direccion"=>$direccion,
						"tecnico_id"=>$tecnico);	

					if($hash==""){

						if($n_ot!=""){
							if($this->Checklisthfcmodel->existeOT($n_ot)){
								echo json_encode(array('res'=>"error", 'msg' => "Ya existe esta OT en la base de datos."));exit;
							}
						}
					
						$id=$this->Checklisthfcmodel->formChecklistHFC($data_insert);

						if($id!=FALSE){

							if(!$this->Checklisthfcmodel->existeDetalleOTS($id)){
								/*$checklist=$this->Checklisthfcmodel->listaChecklist();
								foreach($checklist as $c){
									$data_detalle = array("id_ots" => $id,
								   "id_check" => $c["id"], 
								   "estado" => "0" , 
								   "solucion_estado" => "0",
								   "solucion_fecha" => "0000-00-00",
								   "solucion_observacion" => "",
								   "solucion_digitador" => 0,	
								   "observacion" =>"");

									$this->Checklisthfcmodel->insertaDetalleOTS($data_detalle);
									$data_detalle=array();
								}

								$index=0;
								foreach($estado as $e=>$value){
									$data_actualizar= array("estado" => $value,
										 "observacion" => $observacion[$index],
										 "solucion_estado" => "0",
										 "solucion_fecha" => "0000-00-00",
										 "solucion_observacion" => "",
										 "solucion_digitador" => 0
									);

									$id_detalle=$this->Checklisthfcmodel->getIddetalle($id,$e+1);

									$this->Checklisthfcmodel->actualizaDetalleOTS($id_detalle,$data_actualizar);
									$index++;	
								}*/

								$checklist=$this->Checklisthfcmodel->listaChecklist();

								foreach($checklist as $c){
									$data_detalle = array("id_ots" => $id,
									 "id_check" => $c["id"], 
									 "estado" => "0" , 
									 "solucion_estado" => "0",
									 "solucion_fecha" => "0000-00-00",
									 "solucion_observacion" => "",
									 "solucion_digitador" => 0,	
									 "ultima_actualizacion"=>$ultima_actualizacion,
									 "observacion" =>"");
									$this->Checklisthfcmodel->insertaDetalleOTS($data_detalle);
									$data_detalle=array();
								}

								$herramientas = $this->input->post("herramientas");

								foreach($herramientas as $h){
									$herramienta = $h;
									$estado = $this->input->post("estado_".$herramienta)[0];
									$observacion = $this->input->post("observacion_".$herramienta)[0];
									$data_actualizar = array("estado" =>  $estado, "observacion" =>  $observacion, "ultima_actualizacion"=>$ultima_actualizacion);
									$id_detalle = $this->Checklisthfcmodel->getIddetalle($id,$herramienta);
									$this->Checklisthfcmodel->actualizaDetalleOTS($id_detalle,$data_actualizar);
								}


							}else{

								$herramientas = $this->input->post("herramientas");

								foreach($herramientas as $h){
									$herramienta = $h;
									$estado = $this->input->post("estado_".$herramienta)[0];
									$observacion = $this->input->post("observacion_".$herramienta)[0];
									$data_actualizar = array("estado" =>  $estado , "observacion" =>  $observacion , "ultima_actualizacion"=>$ultima_actualizacion);

									$id_detalle = $this->Checklisthfcmodel->getIddetalle($id,$herramienta);
									$this->Checklisthfcmodel->actualizaDetalleOTS($id_detalle,$data_actualizar);
								}

								/*$index=0;
								foreach($estado as $e=>$value){
									$data_actualizar= array("estado" => $value,
									 "observacion" => $observacion[$index],
									 "solucion_estado" => "0",
									 "solucion_fecha" => "0000-00-00",
									 "solucion_observacion" => "",
									 "solucion_digitador" => 0
									);

									$id_detalle=$this->Checklisthfcmodel->getIddetalle($id,$e+1);
									$this->Checklisthfcmodel->actualizaDetalleOTS($id_detalle,$data_actualizar);
									$index++;	
								}*/
							}

							$imagenes_secundarias=$_FILES['archivos_secundarios']['name'];

							if($imagenes_secundarias[0]!=""){
								if($this->Checklisthfcmodel->cantidadImagenesChecklist($id)<5){

									for ($i=0; $i <count($imagenes_secundarias); $i++) { 
										$nombre_archivo = $_FILES['archivos_secundarios']['name'][$i];
										$extension = pathinfo($nombre_archivo, PATHINFO_EXTENSION);
										$permitidos = array('png', 'jpg', 'jpeg','PNG', 'JPG', 'JPEG');
										if (!in_array($extension, $permitidos)) {
										    echo json_encode(array('res'=>"error", 'msg' =>"Formato de imágen secundaria no soportado."));exit;
										}
									}

						    		for ($i=0; $i <count($imagenes_secundarias); $i++) { 
						    			$imagen=$id."-".url_title(convert_accented_characters($_FILES['archivos_secundarios']['name'][$i]),'-',TRUE);
						    			
						    			$path = $_FILES['archivos_secundarios']['name'][$i];
										$ext = pathinfo($path, PATHINFO_EXTENSION);
										$archivo=$imagen.".".$ext;
										$config['upload_path'] = './archivos/checklist_hfc';
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
									   		'id_checklist'=>$id,
									   		'titulo' 	=>"",
									   		'imagen'	=> $archivo);

									   $this->Checklisthfcmodel->agregaImagenesChecklist($data_imagenes);
							        }
						    	}
						    }

						    $data_correo = $this->Checklisthfcmodel->getDataChecklistHFCCabecera(sha1($id));

						    $hash = sha1($id);
						    
							if(!$this->generaPdfChecklistHFC($data_correo)){
								echo json_encode(array('res'=>"error", 'hash' =>$hash, 'msg' => "Problemas creando el archivo pdf, intente nuevamente."));exit;
							}

							if($checkcorreo=="on"){
					    		
								if($this->enviaCorreoIngreso($data_correo)){
									echo json_encode(array('res'=>"ok", 'hash' =>$hash, 'msg' => MOD_MSG));exit;
								}else{
									echo json_encode(array('res'=>"error", 'hash' =>$hash, 'msg' => "Problemas enviado el correo, intente nuevamente."));exit;
								}

							}else{
								 echo json_encode(array('res'=>"ok", 'hash' =>$hash, 'msg' => MOD_MSG));exit;
							}

						}else{
							echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
						}
				   
					}else{
						
						if($n_ot!=""){
							if($this->Checklisthfcmodel->existeOTMod($hash,$n_ot)){
								echo json_encode(array('res'=>"error", 'msg' => "Ya existe esta OT en la base de datos."));exit;
							}
						}


						$id = $this->Checklisthfcmodel->getIdPorHash($hash);

						$data_mod=array("auditor_id"=>$auditor,
							"fecha"=>$fecha,
							"n_ot"=>$n_ot,
							"tipo_actividad"=>$tipo_actividad,
							"direccion"=>$direccion,
						    "ultima_actualizacion"=>$ultima_actualizacion,		
							"tecnico_id"=>$tecnico);	

						$this->Checklisthfcmodel->actualizarOTS($hash,$data_mod);
						$herramientas = $this->input->post("herramientas");

						foreach($herramientas as $h){
							$herramienta = $h;
							$estado = $this->input->post("estado_".$herramienta)[0];
							$observacion = $this->input->post("observacion_".$herramienta)[0];
							$data_actualizar = array("estado" =>  $estado,"observacion" =>  $observacion, "ultima_actualizacion"=>$ultima_actualizacion);
							$id_detalle = $this->Checklisthfcmodel->getIddetalle($id,$herramienta);
							$this->Checklisthfcmodel->actualizaDetalleOTS($id_detalle,$data_actualizar);
						}
						
						/*$index=0;
						foreach($estado as $e=>$value){
							$data_actualizar= array("estado" => $value, 
								 "observacion" => $observacion[$index]
							);

							$id_detalle=$this->Checklisthfcmodel->getIddetalle($id,$e+1);
							$this->Checklisthfcmodel->actualizaDetalleOTS($id_detalle,$data_actualizar);
							$index++;	
						}*/

						$imagenes_secundarias=$_FILES['archivos_secundarios']['name'];

						if($imagenes_secundarias[0]!=""){
							/*echo $this->Checklisthfcmodel->cantidadImagenesChecklist($id);exit;*/

							if($this->Checklisthfcmodel->cantidadImagenesChecklist($id)<5){

								for ($i=0; $i <count($imagenes_secundarias); $i++) { 
									$nombre_archivo = $_FILES['archivos_secundarios']['name'][$i];
									$extension = pathinfo($nombre_archivo, PATHINFO_EXTENSION);
									$permitidos = array('png', 'jpg', 'jpeg','PNG', 'JPG', 'JPEG');
									if (!in_array($extension, $permitidos)) {
									    echo json_encode(array('res'=>"error", 'msg' =>"Formato de imágen secundaria no soportado."));exit;
									}
								}

					    		for ($i=0; $i <count($imagenes_secundarias); $i++) { 
					    			$imagen=$id."-".url_title(convert_accented_characters($_FILES['archivos_secundarios']['name'][$i]),'-',TRUE);
					    			
					    			$path = $_FILES['archivos_secundarios']['name'][$i];
									$ext = pathinfo($path, PATHINFO_EXTENSION);
									$archivo=$imagen.".".$ext;
									$config['upload_path'] = './archivos/checklist_hfc';
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
								   		'id_checklist'=>$id,
								   		'titulo' 	=>"",
								   		'imagen'	=> $archivo);

								   $this->Checklisthfcmodel->agregaImagenesChecklist($data_imagenes);
						        }
					    	}
					    }

					    $data_correo = $this->Checklisthfcmodel->getDataChecklistHFCCabecera($hash);

						if(!$this->generaPdfChecklistHFC($data_correo)){
							echo json_encode(array('res'=>"error", 'hash' =>$hash, 'msg' => "Problemas creando el archivo pdf, intente nuevamente."));exit;
						}

						if($checkcorreo=="on"){
				    		
							if($this->enviaCorreoIngreso($data_correo)){
								echo json_encode(array('res'=>"ok", 'hash' =>$hash, 'msg' => MOD_MSG));exit;
							}else{
								echo json_encode(array('res'=>"error", 'hash' =>$hash, 'msg' => "Problemas enviado el correo, intente nuevamente."));exit;
							}

						}else{
							 echo json_encode(array('res'=>"ok", 'hash' =>$hash, 'msg' => MOD_MSG));exit;
						}
					}
	    		}	
			}
		}

		public function generaPdfChecklistHFCURL(){
			$hash = $this->security->xss_clean(strip_tags($this->input->post("hash")));
			$data = $this->Checklisthfcmodel->getDataChecklistHFCCabecera($hash);

			foreach($data as $key){
				$detalle = $this->Checklisthfcmodel->getDataChecklistHFC($key["hash"]);
				$titulo = "Registro de auditoria en terreno para técnico : ".$key["tecnico"]."";
				$nombre = url_title(convert_accented_characters($key["id"]."-".$key["rut_tecnico"])).".pdf";
				$data = array('data' => $data , 'titulo' => $titulo , 'detalle' => $detalle);
		    	$html = $this->load->view('back_end/checklist/checklist_hfc/checklist/pdf', $data, true);
		    	$dompdf = new DOMPDF(['isRemoteEnabled' => true]);
		    	$dompdf->setPaper('A4', 'portrait');
			    $dompdf->load_html($html);
			    $dompdf->render();
			    $output = $dompdf->output();
			    $url = './archivos/checklist_hfc/pdf/'.$nombre;

			    if(file_put_contents($url , $output) !=FALSE){
			    	echo json_encode(array('res'=>"ok" , 'url' => $url));exit;
			    }else{
			    	echo json_encode(array('res'=>"error" , 'msg' => "Error creando el pdf, intente nuevamente."));exit;
			    }
		   	  
	 	   }
		}
		
		public function generaPdfChecklistHFC($data){
			foreach($data as $key){
				$detalle = $this->Checklisthfcmodel->getDataChecklistHFC($key["hash"]);
				$titulo = "Registro de auditoria en terreno para técnico : ".$key["tecnico"]."";
				$nombre = url_title(convert_accented_characters($key["id"]."-".$key["rut_tecnico"])).".pdf";
				$data = array('data' => $data , 'titulo' => $titulo , 'detalle' => $detalle);
		    	$html = $this->load->view('back_end/checklist/checklist_hfc/checklist/pdf', $data, true);
		    	$dompdf = new DOMPDF(['isRemoteEnabled' => true]);
		    	$dompdf->setPaper('A4', 'portrait');
			    $dompdf->load_html($html);
			    $dompdf->render();
			    $output = $dompdf->output();
			    file_put_contents('./archivos/checklist_hfc/pdf/'.$nombre.'', $output);
			    return TRUE;
			}
		}

		public function enviaCorreoIngreso($data){
			$prueba = FALSE;

			foreach($data as $key){
				$titulo = "Registro de auditoria en terreno para técnico : ".$key["tecnico"]." - ".$key["fecha"];
				$this->load->library('email');

			    $config = array (
		       	  'mailtype' => 'html',
		          'charset'  => 'utf-8',
		          'priority' => '1',
		          'wordwrap' => TRUE,
		          'protocol' => "smtp",
		          'smtp_port' => 587,
		          'smtp_host' => 'mail.xr3t.cl',
			      'smtp_user' => 'reporte@xr3t.cl',
			      'smtp_pass' => '5aK*2uGJNBd3'
		        );

			    $this->email->initialize($config);
			
				$datos = array("data" => $data, "titulo" => $titulo);
				$html = $this->load->view('back_end/checklist/checklist_hfc/checklist/correo',$datos,TRUE);

				if($prueba){

					$para = array("ricardo.hernandez@km-telecomunicaciones.cl");
					$copias = array("ricardo.hernandez@km-t.cl");
					$this->email->from("reporte@xr3t.cl","Reporte plataforma XR3");

				}else{

					$para = array();
					$para[] = $key["correo_jefe_empresa"]!="" ? $key["correo_jefe_empresa"] : $key["correo_jefe_personal"];

					$copias = array();
					$copias[]="roberto.segovia@xr3.cl";
					$copias[] = $key["correo_auditor_empresa"]!="" ? $key["correo_auditor_empresa"] : $key["correo_auditor_personal"];
					$this->email->from("reporte@xr3t.cl","Reporte plataforma XR3");

				}

				$this->email->to($para);
				$this->email->cc($copias);
				$this->email->bcc("ricardo.hernandez@km-telecomunicaciones.cl","german.cortes@km-telecomunicaciones.cl");
				$this->email->subject($titulo);
				$this->email->message($html); 

				$nombre = url_title(convert_accented_characters($key["id"]."-".$key["rut_tecnico"])).".pdf";
				$this->email->attach(base_url()."archivos/checklist_hfc/pdf/".$nombre);
				$resp=$this->email->send();

				if ($resp) {
					return TRUE;
				}else{
					return FALSE;
				}
			}
			
		}


		public function getDataChecklistHFC(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();
				$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
				$data=$this->Checklisthfcmodel->getDataChecklistHFC($hash);
				$galeria=$this->Checklisthfcmodel->getChecklistHFCGaleria($hash);

				if($data){
					echo json_encode(array('res'=>"ok", 'datos' => $data , 'galeria' => $galeria));exit;
				}else{
					echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
				}	

			}else{
				exit('No direct script access allowed');
			}
		}

		public function eliminaImagenChecklistHFC(){
			$id=$this->security->xss_clean(strip_tags($this->input->post("id")));
			$imagen=$this->Checklisthfcmodel->getImagenGaleria($id);
			if($imagen!=""){
				$ext=explode(".", $imagen);
				if (file_exists('./archivos/checklist_hfc/'.$imagen)){
					unlink('./archivos/checklist_hfc/'.$imagen);
				}
			}

		    if($this->Checklisthfcmodel->eliminaImagenIChecklistHFC(sha1($id))){
		      echo json_encode(array("res" => "ok" , "msg" => "Registro eliminado correctamente."));
		    }else{
		      echo json_encode(array("res" => "error" , "msg" => "Problemas eliminando el la serie, intente nuevamente."));
		    }
		}


		public function eliminaChecklistHFC(){
			$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));

			if($this->Checklisthfcmodel->getImagenChecklist($hash)!=FALSE){
				foreach($this->Checklisthfcmodel->getImagenChecklist($hash) as $imagen){
					if (file_exists("./archivos/checklist_hfc/".$imagen["imagen"])){ 
		    		 	unlink("./archivos/checklist_hfc/".$imagen["imagen"]);
		    		}
		    		$this->Checklisthfcmodel->eliminaImagenChecklistHFC($hash);
				}
	    	}

		    if($this->Checklisthfcmodel->eliminaChecklistHFC($hash)){
		      echo json_encode(array("res" => "ok" , "msg" => "Registro eliminado correctamente."));
		    }else{
		      echo json_encode(array("res" => "error" , "msg" => "Problemas eliminando el registro, intente nuevamente."));
		    }
		}
		

		public function datosAuditorChecklistHFC(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();
				$auditor=$this->security->xss_clean(strip_tags($this->input->post("auditor")));
				$data=$this->Checklisthfcmodel->datosUsuario($auditor);
			
				if($data){
					echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
				}else{
					echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
				}	
			}else{
				exit('No direct script access allowed');
			}
		}
		
		public function datosTecnicoChecklistHFC(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();
				$tecnico=$this->security->xss_clean(strip_tags($this->input->post("tecnico")));
				$data=$this->Checklisthfcmodel->datosUsuario($tecnico);
			
				if($data){
					echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
				}else{
					echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
				}
					
			}else{
				exit('No direct script access allowed');
			}
		}
			
		public function excel_checklistHFC(){
			$desde=$this->uri->segment(2);
			$hasta=$this->uri->segment(3);
			$desde=date("Y-m-d",strtotime($desde));
			$hasta=date("Y-m-d",strtotime($hasta));

			$data=$this->Checklisthfcmodel->listaChecklistHFCDetalle($desde,$hasta);

			if(!$data){
				return FALSE;
			}else{

				$nombre="reporte-checklist-hfc".date("d-m-Y",strtotime($desde))."-".date("d-m-Y",strtotime($hasta)).".xls";
				header("Content-type: application/vnd.ms-excel;  charset=utf-8");
				header("Content-Disposition: attachment; filename=$nombre");
				?>
				<style type="text/css">
				.head{font-size:13px;height: 30px; background-color:#32477C;color:#fff; font-weight:bold;padding:10px;margin:10px;vertical-align:middle;}
				td{font-size:12px;text-align:center;   vertical-align:middle;}
				
				</style>
				<h3>Reporte checklist HFC <?php echo date("d-m-Y",strtotime($desde)); ?> - <?php echo date("d-m-Y",strtotime($hasta)); ?></h3>

					<table align='center' border="1"> 

				        <tr style="background-color:#F9F9F9">
			                <th class="head">Auditor</th> 
				            <th class="head">Auditor cargo</th>   
				            <th class="head">Fecha</th>   
				            <th class="head">T&eacute;cnico</th>   
				            <th class="head">T&eacute;cnico zona</th>   
				            <th class="head">T&eacute;cnico c&oacute;digo</th>   
				            <th class="head">T&eacute;cnico Proyecto</th>   
				            <th class="head">N OT</th>   
				            <th class="head">Tipo actividad</th>   
				            <th class="head">Direcci&oacute;n</th>   
				            <th class="head">Tipo</th>   
				            <th class="head">Descripci&oacute;n Check</th>   
				            <th class="head">Estado</th>   
				            <th class="head">Observaci&oacute;n</th>   

				        </tr>
				        </thead>	
						<tbody>
				        <?php 
				        	if($data !=FALSE){
					      		foreach($data as $d){
				      			?>
				      			 <tr>
									 <td><?php echo utf8_decode($d["auditor"]); ?></td>
									 <td><?php echo utf8_decode($d["auditor_cargo"]); ?></td>
									 <td><?php echo utf8_decode($d["fecha"]); ?></td>
									 <td><?php echo utf8_decode($d["tecnico"]); ?></td>
									 <td><?php echo utf8_decode($d["area"]); ?></td>
									 <td><?php echo utf8_decode($d["codigo"]); ?></td>
									 <td><?php echo utf8_decode($d["comuna"]); ?></td>
									 <td><?php echo utf8_decode($d["n_ot"]); ?></td>
									 <td><?php echo utf8_decode($d["tipo_actividad"]); ?></td>
									 <td><?php echo utf8_decode($d["direccion"]); ?></td>
									 <td><?php echo utf8_decode($d["tipo"]); ?></td>
									 <td><?php echo utf8_decode($d["descripcion"]); ?></td>
									 <td><?php echo utf8_decode($d["estado_str"]); ?></td>
									 <td><?php echo utf8_decode($d["observacion"]); ?></td>

								 </tr>
				      			<?php
				      			}
				      		}
				          ?>
				        </tbody>
			        </table>
			    <?php
			}
		}

	/**************GRAFICOS**************/

		public function vistaGraficosChecklistHFC(){
			if($this->input->is_ajax_request()){
				$fecha_anio_atras=date('d-m-Y', strtotime('-365 day', strtotime(date("d-m-Y"))));
		    	$fecha_hoy=date('d-m-Y');
		    	// echo "<pre>";
		    	// print_r($this->Checklisthfcmodel->dataEstadosChecklist());
				$datos=array(	
					'fecha_anio_atras' => $fecha_anio_atras,	   
			        'fecha_hoy' => $fecha_hoy,
			   	);

				$this->load->view('back_end/checklist/checklist_hfc/graficos/inicio',$datos);
			}
		}

		public function dataEstadosChecklistHFC(){
			echo json_encode($this->Checklisthfcmodel->dataEstadosChecklistHFC());
		}

		public function dataTecnicosChecklistHFC(){
			echo json_encode($this->Checklisthfcmodel->dataTecnicosChecklistHFC());
		}



	/**********FALLOS HFC************/
		
		
		public function vistaFHFC(){
			$this->visitas("Checklist");
			$fecha_anio_atras=date('d-m-Y', strtotime('-360 day', strtotime(date("d-m-Y"))));
	    	$fecha_hoy=date('d-m-Y');
			$tecnicos=$this->Checklisthfcmodel->listaTecnicos();
    		$auditores=$this->Checklisthfcmodel->listaAuditores();

			$datos=array(
				'fecha_anio_atras' => $fecha_anio_atras,	   
		        'fecha_hoy' => $fecha_hoy,
				'tecnicos' => $tecnicos,
		   	    'auditores' => $auditores,
		   	);
			$this->load->view('back_end/checklist/checklist_hfc/fallos/inicio',$datos);
		}

		public function listaFHFC(){
			$desde=$this->security->xss_clean(strip_tags($this->input->get_post("desde")));
			$hasta=$this->security->xss_clean(strip_tags($this->input->get_post("hasta")));
			$solucion_estado=$this->security->xss_clean(strip_tags($this->input->get_post("solucion_estado")));
			if($desde!=""){$desde=date("Y-m-d",strtotime($desde));}else{$desde="";}
			if($hasta!=""){$hasta=date("Y-m-d",strtotime($hasta));}else{$hasta="";}	
			echo json_encode($this->Checklisthfcmodel->listaFHFC($desde,$hasta,$solucion_estado));
		}

		public function formFHFC(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();
				$hash = $this->security->xss_clean(strip_tags($this->input->post("hash")));
				
				$solucion_fecha = $this->security->xss_clean(strip_tags($this->input->post("solucion_fecha")));
				if($solucion_fecha!=""){$solucion_fecha=date("Y-m-d",strtotime($solucion_fecha));}else{$solucion_fecha="";}
				$solucion_observacion = $this->security->xss_clean(strip_tags($this->input->post("solucion_observacion")));
				$solucion_digitador = $this->session->userdata('id');

				$checkcorreo = $this->security->xss_clean(strip_tags($this->input->post("checkcorreo")));
				$ultima_actualizacion = date("Y-m-d G:i:s");

				if ($this->form_validation->run("formFHFC") == FALSE){
					echo json_encode(array('res'=>"error", 'msg' => strip_tags(validation_errors())));exit;
				}else{	

					if($hash==""){

						echo json_encode(array('res'=>"error", 'msg' => "Error al actualizar el registro, intente nuevamente."));exit;
				   
					}else{

						$data_mod=array(
							"solucion_fecha"=>$solucion_fecha,
							"solucion_observacion"=>$solucion_observacion,
							"solucion_digitador"=>$solucion_digitador,
							"ultima_actualizacion"=>$ultima_actualizacion
						);	

						$solucion_fecha_bd = $this->Checklisthfcmodel->getFechaSolucion($hash);

						if($solucion_fecha!="" and $solucion_fecha_bd=="0000-00-00"){
							$data_mod["solucion_estado"] = 1;
						}

						$this->Checklisthfcmodel->actualizarFHFC($hash,$data_mod);

						if($checkcorreo=="on"){
				    		
				     		//$data_correo=$this->Checklisthfcmodel->getDataOTS($hash);
							// if($this->enviaCorreoIngresoFH($data_correo)){
								echo json_encode(array('res'=>"ok", 'hash' =>$hash, 'msg' => MOD_MSG));exit;
							// }

						}else{
							 echo json_encode(array('res'=>"ok", 'hash' =>$hash, 'msg' => MOD_MSG));exit;
						}
					}
	    		}	
			}
		}

		public function getDataFHFC(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();
				$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
				$data=$this->Checklisthfcmodel->getDataFHFC($hash);

				if($data){
					echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
				}else{
					echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
				}	
			}else{
				exit('No direct script access allowed');
			}
		}

		
		public function eliminaFHFC(){
			$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
			
		    if($this->Checklisthfcmodel->eliminaFHFC($hash)){
		      echo json_encode(array("res" => "ok" , "msg" => "Registro eliminado correctamente."));
		    }else{
		      echo json_encode(array("res" => "error" , "msg" => "Problemas eliminando el registro, intente nuevamente."));
		    }
		}	


	/**********FALLOS GRAFICOS**************/

		public function vistaGraficosFallosHFC(){

			if($this->input->is_ajax_request()){
				$desde=date('Y-m-d', strtotime('-365 day', strtotime(date("d-m-Y"))));
		    	$hasta=date('Y-m-d');
		    	// echo "<pre>";
		    	// print_r($this->Checklisthfcmodel->dataEstadosChecklist());
				$datos=array(	
					'desde' => $desde,	   
			        'hasta' => $hasta,
			        'auditores' => $this->Checklisthfcmodel->listaAuditores(),
			   	);

				$this->load->view('back_end/checklist/checklist_hfc/fallos/graficos',$datos);
			}
		}


		public function graficoFallosHFC(){
			$desde = $this->security->xss_clean(strip_tags($this->input->get_post("desde")));
			$hasta = $this->security->xss_clean(strip_tags($this->input->get_post("hasta")));
			if($desde!=""){$desde=date("Y-m-d",strtotime($desde));}else{$desde="";}
			if($hasta!=""){$hasta=date("Y-m-d",strtotime($hasta));}else{$hasta="";}	

			$trabajador=$this->security->xss_clean(strip_tags($this->input->get_post("trabajador")));
			if($trabajador!=""){
				$trabajador = $this->Checklisthfcmodel->getIdPorRut($trabajador);
			}
			
			$auditor = $this->security->xss_clean(strip_tags($this->input->get_post("auditor")));
			$array = array();
			$array[] = $this->Checklisthfcmodel->graficoFallos($desde,$hasta,$trabajador,$auditor);
			$list = array();

			$cabeceras= array(
				"Mes",
				"Porcentaje",
				"Cantidad OK",
				"Cantidad NO OK",
				/*array('role'=> 'annotation'),
				array('role'=> 'annotation'),
				array('role'=> 'annotation'),*/
				array('role'=> 'annotationText'),
			);

			$list [] = $cabeceras;

			foreach($array as $arr) {
			    if(is_array($arr)) {
			        $list = array_merge($list, $arr);
			    }
			}
			echo json_encode($list);exit;
		}	

		public function listaTrabajadoresHFC(){
			$jefe=$this->security->xss_clean(strip_tags($this->input->get_post("jefe")));
	 	    echo $this->Checklisthfcmodel->listaTrabajadoresHFC($jefe);exit;
		}

		/*public function dataEstadosChecklist(){
			echo json_encode($this->Checklisthfcmodel->dataEstadosChecklist());
		}

		public function dataTecnicos(){
			echo json_encode($this->Checklisthfcmodel->dataTecnicos());
		}*/
}