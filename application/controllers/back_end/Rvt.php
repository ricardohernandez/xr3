<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rvt extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model("back_end/Rvtmodel");
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
			/*
	      	if($this->session->userdata('id_perfil')>4){
	      		redirect("./login");
	      	}
			*/
	      }else{
	      	redirect("./login");
	    }
	}

	public function visitas($modulo){
		$this->load->library('user_agent');
		$data=array("id_usuario"=>$this->session->userdata('id'),
			"id_aplicacion"=>20,
			"modulo"=>$modulo,
	     	"fecha"=>date("Y-m-d G:i:s"),
	    	"navegador"=>"navegador :".$this->agent->browser()."\nversion :".$this->agent->version()."\nos :".$this->agent-> platform()."\nmovil :".$this->agent->mobile(),
	    	"ip"=>$this->input->ip_address(),
    	);
    	$this->Rvtmodel->insertarVisita($data);
	}

	public function index(){
		$this->visitas("rvt");
    	$this->acceso();

		$perfil= $this->session->userdata('id_perfil');

	    $datos = array(
	        'titulo' => "RVT (Registro de ventas)",
	        'contenido' => "rvt/inicio",
	        'perfiles' => $this->Iniciomodel->listaPerfiles(),
			'perfil' => $perfil,
		);  

		$this->load->view('plantillas/plantilla_back_end',$datos);
	}

	/*********ROP***********/

		public function getRvtInicio(){
			if($this->input->is_ajax_request()){
				$desde = date('Y-m-d', strtotime('-365 day', strtotime(date("d-m-Y"))));
				$hasta = date('Y-m-d');
				$datos = array(
					'desde' => $desde,
					'hasta' => $hasta,
				);
				
				$this->load->view('back_end/rvt/rvt',$datos);
			}
		}

		public function getRvtAdministracion(){
			if($this->input->is_ajax_request()){
				$desde = date('Y-m-d', strtotime('-365 day', strtotime(date("d-m-Y"))));
				$hasta = date('Y-m-d');
				$datos = array(
					'desde' => $desde,
					'hasta' => $hasta,
				);
				$this->load->view('back_end/rvt/rvt_adm',$datos);
			}
		}

		public function getRvtList(){
			$estado=$this->security->xss_clean(strip_tags($this->input->get_post("estado")));
			$desde=$this->security->xss_clean(strip_tags($this->input->get_post("desde")));
			$hasta=$this->security->xss_clean(strip_tags($this->input->get_post("hasta")));

			if($desde!=""){$desde=date("Y-m-d",strtotime($desde));}else{$desde="";}
			if($hasta!=""){$hasta=date("Y-m-d",strtotime($hasta));}else{$hasta="";}	

			if (in_array($this->session->userdata('id'), array(113, 372, 313, 541, 155, 347))){
				echo json_encode($this->Rvtmodel->getRvtList($desde,$hasta,$estado));exit;
			}
			if($this->session->userdata('id_perfil') < 3){
				echo json_encode($this->Rvtmodel->getRvtList($desde,$hasta,$estado));
			}
			else{
				$tecnico = $this->session->userdata('id');
				echo json_encode($this->Rvtmodel->getRvtList($desde,$hasta,$estado,$tecnico));
			}
		}

		public function listaMarcas(){
			echo $this->Rvtmodel->listaMarcas();exit;
		}

		public function listaPersonas(){
			echo $this->Rvtmodel->listaPersonas();exit;
		}

		public function listaResponsables(){
			echo $this->Rvtmodel->listaResponsables();exit;
		}

		public function formRvt(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();	
				$hash = $this->security->xss_clean(strip_tags($this->input->post("hash")));
				
				$fecha_ingreso = $this->security->xss_clean(strip_tags($this->input->post("fecha_ingreso")));
				$id_solicitante = $this->security->xss_clean(strip_tags($this->input->post("tecnico")));
				$titular_nombre = $this->security->xss_clean(strip_tags($this->input->post("titular_nombre")));
				$titular_rut = $this->security->xss_clean(strip_tags($this->input->post("titular_rut")));
				$titular_direccion = $this->security->xss_clean(strip_tags($this->input->post("titular_direccion")));
				$titular_comuna = $this->security->xss_clean(strip_tags($this->input->post("titular_comuna")));
				$titular_telefono1 = $this->security->xss_clean(strip_tags($this->input->post("titular_telefono1")));
				$titular_telefono2 = $this->security->xss_clean(strip_tags($this->input->post("titular_telefono2")));
				$observacion_solicitante = $this->security->xss_clean(strip_tags($this->input->post("observacion_solicitante")));
				$id_marca = $this->security->xss_clean(strip_tags($this->input->post("marca")));
				$pack = $this->security->xss_clean(strip_tags($this->input->post("pack")));

				$estado = $this->security->xss_clean(strip_tags($this->input->post("estado")));
				$responsable1 = $this->security->xss_clean(strip_tags($this->input->post("responsable1")));
				$responsable2 = $this->security->xss_clean(strip_tags($this->input->post("responsable2")));
				$numero_ot = $this->security->xss_clean(strip_tags($this->input->post("numero_ot")));
				$observacion_responsable = $this->security->xss_clean(strip_tags($this->input->post("observacion_responsable")));

				$ultima_actualizacion = date("Y-m-d G:i:s")." | ".$this->session->userdata("nombre_completo");

				if($hash==""){
					if ($this->form_validation->run("formRvt") == FALSE){
						echo json_encode(array('res'=>"error", 'msg' => strip_tags(validation_errors())));exit;
					}
					$data = array(
						'fecha_ingreso' => $fecha_ingreso,
						'id_solicitante' => $id_solicitante,
						'titular_nombre'=> $titular_nombre,
						'titular_rut'=> $titular_rut,
						'titular_direccion' => $titular_direccion,
						'titular_comuna' => $titular_comuna,
						'titular_telefono1' => $titular_telefono1,
						'titular_telefono2' => $titular_telefono2,
						'observacion_solicitante' => $observacion_solicitante ,
						"id_marca" => $id_marca,
						"pack" => $pack,
						"estado" => "Venta ingresada",
						"id_responsable1" => 541, //Madelyn lagos
						"id_responsable2" => 0, //Javiera Fetis
						"numero_ot" => 999999999,
						'observacion_responsable' => "",
						"ultima_actualizacion" => $ultima_actualizacion,
					);
					$insert_id=$this->Rvtmodel->formIngreso($data);
					if($insert_id!=FALSE){
						$this->EnviaCorreo(sha1($insert_id),0);
						echo json_encode(array('res'=>"ok",  'msg' => OK_MSG));exit;
					}else{
						echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
					}
				}else{
					if ($this->form_validation->run("formEditRvt") == FALSE){
						echo json_encode(array('res'=>"error", 'msg' => strip_tags(validation_errors())));exit;
					}
					$data_mod = array(
						'estado' => $estado,
						'id_responsable1' => $responsable1,
						'id_responsable2' => $responsable2,
						'numero_ot' => $numero_ot,
						'observacion_responsable' => $observacion_responsable,
						"ultima_actualizacion" => $ultima_actualizacion);
					if($estado != "Venta ingresada" and $estado != ""){
						$data_mod["fecha_respuesta"] = date("Y-m-d");
					}
					
					if($this->Rvtmodel->formActualizar($hash,$data_mod)){
						if($estado != "Venta ingresada" and $estado != ""){
							$this->EnviaCorreo($hash,1);
						}
						echo json_encode(array('res'=>"ok",  'msg' => MOD_MSG));exit;
					}else{
						echo json_encode(array('res'=>"error",'msg' => "Error actualizando el registro, intente nuevamente."));exit;
					}
				}

			}else{
				exit('No direct script access allowed');
			}
		}
	 
		public function enviaCorreo($hash,$tipo){
			$this->load->library('email');
			$data = $this->Rvtmodel->getDataRvt($hash);
			$prueba = FALSE;

			foreach($data as $key){			

				$config = array(
					'mailtype' => 'html',
					'charset' => 'UTF-8',
					'priority' => '1',
					'wordwrap' =>TRUE,
					'protocol' =>  'smtp',
					'smtp_port' => 587,
					'smtp_host' => $this->config->item('syr_smtp_host'),
					'smtp_user' => $this->config->item('syr_smtp_user'),
					'smtp_pass' => $this->config->item('syr_smtp_pass')
				);

				$this->email->initialize($config);

				if($tipo==0){ //NUEVO
					$asunto = "Solicitud de Venta N°".$key["id_rvt"]." [".$key["nombre_solicitante"]."] ";
					$cuerpo = "Le informamos que se ha ingresado la solicitud de venta N°".$key["id_rvt"]." con el siguiente detalle.";
					$cuerpo2 = "Informamos a usted que de acuerdo a los plazos preestablecidos por su organización el plazo para responder esta solicitud es en 7 días aprox, terminado dicho plazo la solicitud sera escalada al responsable superior predefinido por su organización.<br> 
					Para contestar puede ingresar al siguiente link con previo ingreso de sus credenciales de seguridad.";
					$this->email->from("syr@xr3t.cl","Solicitudes y requerimientos plataforma XR3");

					$para = !empty($key["correo_responsable1"]) ? [$key["correo_responsable1"]] : [];
					$copias = !empty($key["correo_solicitante"]) ? [$key["correo_solicitante"],"javiera.fetis@xr3.cl"] : [];
					$datos = array("dato"=>$key,"asunto"=>$asunto,"cuerpo"=>$cuerpo,"cuerpo2"=>$cuerpo2);
					$html = $this->load->view('back_end/rvt/correo',$datos,TRUE);
				}
				if($tipo!=0){ //RESPUESTA
					$asunto = "Respuesta a solicitud de Venta N°".$key["id_rvt"]." [".$key["nombre_solicitante"]."] [".$key["estado"]."]";
					$cuerpo = "Le informamos que se ha respondido la solicitud de venta N°".$key["id_rvt"]." con el siguiente detalle.";
					$cuerpo2 = "Informamos a usted que de acuerdo a los plazos preestablecidos por su organización el plazo para responder esta solicitud es en 7 días aprox, terminado dicho plazo la solicitud sera escalada al responsable superior predefinido por su organización.<br> 
					Para contestar puede ingresar al siguiente link con previo ingreso de sus credenciales de seguridad.";
					$this->email->from("syr@xr3t.cl","Solicitudes y requerimientos plataforma XR3");

					$para = !empty($key["correo_solicitante"]) ? [$key["correo_solicitante"]] : [];
					$copias = !empty($key["correo_responsable1"]) ? [$key["correo_responsable1"],"javiera.fetis@xr3.cl"] : [];
					$datos = array("dato"=>$key,"asunto"=>$asunto,"cuerpo"=>$cuerpo,"cuerpo2"=>$cuerpo2);
					$html = $this->load->view('back_end/rvt/correo',$datos,TRUE);
				}

				if($prueba){
					$para = array("sebastian.celis@splice.cl");
					$copias = array("sebastian.celis@splice.cl");
				}

				//echo $html;exit;

				$this->email->to($para);
				$this->email->cc($copias);

				//$this->email->bcc(array("sebastian.celis@splice.cl","ricardo.hernandez@km-telecomunicaciones.cl","german.cortes@km-telecomunicaciones.cl"));
				$this->email->subject($asunto); 
				$this->email->message($html); 

				if(ENVIAR_CORREO){
					$resp=$this->email->send();
					return TRUE;
				}else{
					return FALSE;
				}
				
			}
		}

		public function ReporteRvt(){
			$this->load->library('email');
			$fecha = date('Y-m-d',strtotime('-7 days')); // semana pasada
			$data = $this->Rvtmodel->getRegistrosRvt($fecha); // posean estado "Venta ingresada" y se haya ingresado la semana pasada
			$prueba = FALSE;

			$config = array(
				'mailtype' => 'html',
				'charset' => 'UTF-8',
				'priority' => '1',
				'wordwrap' =>TRUE,
				'protocol' =>  'smtp',
				'smtp_port' => 587,
				'smtp_host' => $this->config->item('syr_smtp_host'),
				'smtp_user' => $this->config->item('syr_smtp_user'),
				'smtp_pass' => $this->config->item('syr_smtp_pass')
			);

			foreach($data as $key){			

				$this->email->initialize($config);

				if($tipo==0){ //NUEVO
					$asunto = "Solicitud de Venta N°".$key["id_rvt"]." [".$key["nombre_solicitante"]."] ";
					$cuerpo = "Le informamos que se ha ingresado la solicitud de venta N°".$key["id_rvt"]." con el siguiente detalle.";
					$cuerpo2 = "Informamos a usted que de acuerdo a los plazos preestablecidos por su organización el plazo para responder esta solicitud es en 7 días aprox, terminado dicho plazo la solicitud sera escalada al responsable superior predefinido por su organización.<br> 
					Para contestar puede ingresar al siguiente link con previo ingreso de sus credenciales de seguridad.";
					$this->email->from("syr@xr3t.cl","Solicitudes y requerimientos plataforma XR3");

					$para = array("javiera.fetis@xr3.cl");
					$copias = !empty($key["correo_solicitante"]) ? [$key["correo_solicitante"],$key["correo_responsable1"]] : [];
					$datos = array("dato"=>$key,"asunto"=>$asunto,"cuerpo"=>$cuerpo,"cuerpo2"=>$cuerpo2);
					$html = $this->load->view('back_end/rvt/correo',$datos,TRUE);
				}
				if($prueba){
					$para = array("sebastian.celis@splice.cl");
					$copias = array("sebastian.celis@splice.cl");
				}

				//echo $html;exit;

				$this->email->to($para);
				$this->email->cc($copias);

				//$this->email->bcc(array("sebastian.celis@splice.cl","ricardo.hernandez@km-telecomunicaciones.cl","german.cortes@km-telecomunicaciones.cl"));
				$this->email->subject($asunto); 
				$this->email->message($html); 

				if(ENVIAR_CORREO){
					$resp=$this->email->send();
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
			$config['upload_path'] = './archivos/rvt';
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

		public function eliminaRop(){
			$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
			if($this->Rvtmodel->eliminaRop($hash)){
			echo json_encode(array("res" => "ok" , "msg" => "Registro eliminado correctamente."));
			}else{
			echo json_encode(array("res" => "error" , "msg" => "Problemas eliminando el registro, intente nuevamente."));
			}
		}

		public function getDataRvt(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();	
				$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
				$data=$this->Rvtmodel->getDataRvt($hash);
				if($data){
					echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
				}else{
					echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
				}	
			}else{
				exit('No direct script access allowed');
			}
		}

		public function excel_rvt(){
			$desde = $this->uri->segment(2);
			$hasta = $this->uri->segment(3);
			$estado = $this->uri->segment(4);

			if($desde!=""){$desde=date("Y-m-d",strtotime($desde));}else{$desde="";}
			if($hasta!=""){$hasta=date("Y-m-d",strtotime($hasta));}else{$hasta="";}
			if($estado=="-"){$estado="";}else{$estado= str_replace("_", " ", $estado);}

			$data = $this->Rvtmodel->getRvtList($desde,$hasta,$estado);

			if(!$data){
				return FALSE;
			}else{

				$nombre="reporte-syr.xls";
				header("Content-type: application/vnd.ms-excel;  charset=utf-8");
				header("Content-Disposition: attachment; filename=$nombre");
				?>

				<style type="text/css">
					.det{
						background-color:#233294;color:#fff;
					}
					.head{font-size:13px;height: 30px; background-color:#1D7189;color:#fff; font-weight:bold;padding:10px;margin:10px;vertical-align:middle;}
					.finde{
						font-size:13px;height: 30px; background-color:#1D7189;color:red; font-weight:bold;padding:10px;margin:10px;vertical-align:middle;
					}
					td{font-size:12px;text-align:center;   vertical-align:middle;}
				</style>

				<h3>Reporte SYR (Solicitudes y requerimientos)</h3>
				<table align='center' border="1"> 
						<tr style="background-color:#F9F9F9">
						<th class="head">ID</th>  
						<th class="head">Estado</th>  
						<th class="head">Fecha ingreso</th>  
						<th class="head">Nombre solicitante </th>  
						<th class="head">Nombre titular</th>  
						<th class="head">Rut titular</th>  
						<th class="head">Direcci&oacute;n titular</th>  
						<th class="head">Comuna titular</th>  
						<th class="head">Tel&eacute;fono titular 1</th>  
						<th class="head">Tel&eacute;fono titular 2</th>  
						<th class="head">Marca</th>  
						<th class="head">Pack</th>  
						<th class="head">Observaci&oacute;n solicitante</th>  
						<th class="head">Nombre de responsable 1</th>  
						<th class="head">Nombre de responsable 2</th>  
						<th class="head">Fecha respuesta</th>  
						<th class="head">n&uacute;mero ot</th>  
						<th class="head">Observaci&oacute;n responsable</th>  
						<th class="head">&Uacute;ltima actualizaci&oacute;n</th>    
					</tr>
					</thead>	
					<tbody>
					<?php 
						if($data !=FALSE){
							foreach($data as $d){
							?>
								<tr>
									<td><?php echo utf8_decode($d["id"]); ?></td>
									<td><?php echo utf8_decode($d["estado"]); ?></td>
									<td><?php echo utf8_decode($d["fecha_ingreso"]); ?></td>
									<td><?php echo utf8_decode($d["nombre_solicitante"]); ?></td>
									<td><?php echo utf8_decode($d["titular_nombre"]); ?></td>
									<td><?php echo utf8_decode($d["titular_rut"]); ?></td>
									<td><?php echo utf8_decode($d["titular_direccion"]); ?></td>
									<td><?php echo utf8_decode($d["titular_comuna"]); ?></td>
									<td><?php echo utf8_decode($d["titular_telefono1"]); ?></td>
									<td><?php echo utf8_decode($d["titular_telefono2"]); ?></td>
									<td><?php echo utf8_decode($d["marca"]); ?></td>
									<td><?php echo utf8_decode($d["pack"]); ?></td>
									<td><?php echo utf8_decode($d["observacion_solicitante"]); ?></td>
									<td><?php echo utf8_decode($d["nombre_responsable1"]); ?></td>
									<td><?php echo utf8_decode($d["nombre_responsable2"]); ?></td>
									<td><?php echo utf8_decode($d["fecha_respuesta"]); ?></td>
									<td><?php echo utf8_decode($d["numero_ot"]); ?></td>
									<td><?php echo utf8_decode($d["observacion_responsable"]); ?></td>
									<td><?php echo utf8_decode($d["ultima_actualizacion"]); ?></td>
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


}