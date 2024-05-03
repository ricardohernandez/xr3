<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Es extends CI_Controller {



	public function __construct(){

		parent::__construct();

		$this->load->model("back_end/Esmodel");

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

    	$this->Esmodel->insertarVisita($data);

	}



	public function index(){

		$this->visitas("es");

    	$this->acceso();



		$perfil= $this->session->userdata('id_perfil');

		$tipos = $this->Esmodel->listaTipos();

		foreach($tipos as $key => $tipo){

			if($perfil >= 4 && $tipo['id'] == 9){

					unset($tipos[$key]);

			}

		}



	    $datos = array(

	        'titulo' => "MES (Módulo escalamiento de soporte)",

	        'contenido' => "es/inicio",

	        'perfiles' => $this->Iniciomodel->listaPerfiles(),

	        'tipos' => $tipos,

		);  





		$this->load->view('plantillas/plantilla_back_end',$datos);

	}



	/*********ROP***********/



		public function getEsInicio(){

			if($this->input->is_ajax_request()){

				$desde = date('Y-m-d', strtotime('-365 day', strtotime(date("d-m-Y"))));

				$hasta = date('Y-m-d');

				$perfil= $this->session->userdata('id_perfil');

				$tipos = $this->Esmodel->listaTipos();

				$zonas = $this->Esmodel->listaZonas();

				$ciudades = $this->Esmodel->listaCiudades();

				$cierres = $this->Esmodel->listaCierres();



				$datos = array(

					'desde' => $desde,

					'hasta' => $hasta,

					'tipos' => $tipos,

					'zonas' => $zonas,

					'ciudades' => $ciudades,

					'cierres' => $cierres,

					'supervisores' => $this->Iniciomodel->listaSupervisores(),

				);

				

				$this->load->view('back_end/es/es',$datos);

			}

		}



		public function getEsList(){

			$estado=$this->security->xss_clean(strip_tags($this->input->get_post("estado")));

			$desde=$this->security->xss_clean(strip_tags($this->input->get_post("desde")));

			$hasta=$this->security->xss_clean(strip_tags($this->input->get_post("hasta")));

			$supervisor=$this->security->xss_clean(strip_tags($this->input->get_post("supervisor")));



			if($desde!=""){$desde=date("Y-m-d",strtotime($desde));}else{$desde="";}

			if($hasta!=""){$hasta=date("Y-m-d",strtotime($hasta));}else{$hasta="";}	



			$responsable=$this->security->xss_clean(strip_tags($this->input->get_post("responsable")));

			echo json_encode($this->Esmodel->getEsList($desde,$hasta,$estado,$responsable,$supervisor));

		}



		public function listaRequerimientos(){

			$tipo=$this->security->xss_clean(strip_tags($this->input->get_post("tipo")));

			echo $this->Esmodel->listaRequerimientos($tipo);exit;

		}



		public function listaPersonas(){

			echo $this->Esmodel->listaPersonas();exit;

		}



		public function listaResponsables(){

			echo $this->Esmodel->listaResponsables();exit;

		}


		function horaDisponible() {
			$current_time = date('h:i a'); // Obtiene la hora actual en formato AM/PM
			$start_time = "08:00 am"; // Hora de inicio (8:00 AM)
			$end_time = "05:15 pm"; // Hora de fin (5:15 PM)
		
			// Crea objetos DateTime a partir de las cadenas de tiempo
			$date1 = DateTime::createFromFormat('h:i a', $current_time);
			$date2 = DateTime::createFromFormat('h:i a', $start_time);
			$date3 = DateTime::createFromFormat('h:i a', $end_time);
		
			// Verifica si la hora actual está entre los límites
			if ($date1 >= $date2 && $date1 <= $date3) {
				return true;
			} else {
				return false;
			}
		}

		public function formEs(){

			if($this->input->is_ajax_request()){

				$this->checkLogin();	

				$hash = $this->security->xss_clean(strip_tags($this->input->post("hash")));

				$tipo = $this->security->xss_clean(strip_tags($this->input->post("tipo")));

				$zona = $this->security->xss_clean(strip_tags($this->input->post("zona")));

				$ciudad = $this->security->xss_clean(strip_tags($this->input->post("ciudad")));

				$cierre = $this->security->xss_clean(strip_tags($this->input->post("cierre")));

				$requerimiento = $this->security->xss_clean(strip_tags($this->input->post("requerimiento")));

				$descripcion = $this->security->xss_clean(strip_tags($this->input->post("observacion")));



				$usuario_asignado = $this->security->xss_clean(strip_tags($this->input->post("usuario_asignado")));

				$estado = $this->security->xss_clean(strip_tags($this->input->post("estado")));

				$validador_sistema = $this->security->xss_clean(strip_tags($this->input->post("validador_sistema")));

				$horas_estimadas = $this->security->xss_clean(strip_tags($this->input->post("horas_estimadas")));

				$observacion = $this->security->xss_clean(strip_tags($this->input->post("observacion_respuesta")));

				$tecnico = $this->security->xss_clean(strip_tags($this->input->post("tecnico")));

				

				$adjunto_req1 =  @$_FILES["adjunto_req1"]["name"];

				$adjunto_resp =  @$_FILES["adjunto_respuesta"]["name"];

				

				$checkcorreo = $this->security->xss_clean(strip_tags($this->input->post("checkcorreo")));

				$checkvalidar = $this->security->xss_clean(strip_tags($this->input->post("checkvalidar")));

				$checkfin = $this->security->xss_clean(strip_tags($this->input->post("checkfin")));



				$id_solicitante = $this->session->userdata("id");

				$comuna = $this->Esmodel->getComunaSolicitante($id_solicitante);

				$estado = $this->security->xss_clean(strip_tags($this->input->post("estado")));

				$ultima_actualizacion = date("Y-m-d G:i:s")." | ".$this->session->userdata("nombre_completo");



				if ($this->form_validation->run("formEs") == FALSE){

					echo json_encode(array('res'=>"error", 'msg' => strip_tags(validation_errors())));exit;

				}else{	



					if($hash==""){

						if(!$this->horaDisponible()){
							echo json_encode(array('res'=>"error", 'msg' => 'No se puede enviar solicitud comuníquese directamente con su supervisor.'));exit;
						}

						//VALIDA REQUERIMIENTO VALIDACION, NULO O JEFE

						$requiere_validacion = $this->Esmodel->getRequiereValidacion($requerimiento);

						

						if($requiere_validacion == 0){

							$validador_sistema = NULL;

						}else{

							$validador_sistema = $this->Esmodel->getJefeSolicitante($id_solicitante);

						}



						if(empty($tecnico)){

							echo json_encode(array('res'=>"error", 'msg' => 'El campo técnico es obligatorio.'));exit;

						}



						$data = array(

							'id_requerimiento' => $requerimiento,

							'id_estado' => "0",

							'id_tecnico'=> $tecnico,

							'id_solicitante'=> $id_solicitante,

							'comuna' => $comuna,

							'id_tipo' => $tipo,

							'id_zona' => $zona,

							'ciudad' => $ciudad,

							'id_cierre' => $cierre,

							'descripcion' => $descripcion,

							'validador_sistema' => $validador_sistema,

							'fecha_ingreso' => date("Y-m-d"),

							'hora_ingreso' =>  date("G:i:s"),

							"ultima_actualizacion" => $ultima_actualizacion);



						if($adjunto_req1!=""){

							$nombre=$this->procesaArchivo($_FILES["adjunto_req1"],rand(0,10000)."_".date("ymdHis"),1);

							$data["adjunto_requerimiento_1"]=$nombre;

						}else{

							$data["adjunto_requerimiento_1"]="";

						}



						$insert_id=$this->Esmodel->formIngreso($data);

						if($insert_id!=FALSE){



							//if($checkcorreo=="on"){

								$this->EnviaCorreo(sha1($insert_id),"0");

							//}



							echo json_encode(array('res'=>"ok",  'msg' => OK_MSG));exit;



						}else{

							echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;

						}



					}else{

						

						$data_mod = array(

							'id_requerimiento' => $requerimiento,

							'id_tecnico'=> $tecnico,

							'id_solicitante'=> $id_solicitante,

							'comuna' => $comuna,

							'id_tipo' => $tipo,

							'id_zona' => $zona,

							'ciudad' => $ciudad,

							'id_cierre' => $cierre,

							'descripcion' => $descripcion,

							'validador_sistema' => $validador_sistema,

							'id_requerimiento' => $requerimiento,

							'id_usuario_asignado' => $usuario_asignado,

							'horas_estimadas' => $horas_estimadas,

							'observacion_fin' => $observacion,

							"ultima_actualizacion" => $ultima_actualizacion);

						

						$usuarioAsignadoBD = $this->Esmodel->getUsuarioAsignadoEs($hash);

						$validadorRealEs = $this->Esmodel->getValidadorRealEs($hash);

						$estadoEs = $this->Esmodel->getEstadoEs($hash);

						

						//SI VIENE USUARIO ASIGNADO Y EL ESTADO ES DIFERENTE A PENDIENTE Y ASIGNADO

						

						if(!empty($usuario_asignado) and $usuarioAsignadoBD!=$usuario_asignado){

							$data_mod["id_estado"] = 1;

						}



						//SI VIENE CHECK VALIDADO Y EL USUARIO ACTUAL ES DIFERENTE AL YA GUARDADO

						// and $this->session->userdata("id")<>$validadorRealEs



						if($checkvalidar=="on"){//

							$id_validador_real = $this->session->userdata("id");

							$data_mod["id_validador_real"] = $id_validador_real;

							$data_mod["fecha_validacion"] = date("Y-m-d");

							$data_mod["hora_validacion"] = date("G:i:s");

							$data_mod["id_estado"] = 4;

						}



						//SI VIENE CHECK FINALIZADO 



						if($checkfin=="on"){

							$data_mod["id_estado"] = 2;

							$data_mod["fecha_fin"] = date("Y-m-d");

							$data_mod["hora_fin"] = date("G:i:s");

						}



						//SI ES CANCELADO Y EL ESTADO ES DIFERENTE A CANCELADO

						if($estado!=""){

							$data_mod["id_estado"] = 3;

						}



						$adjunto_req1 = $_FILES["adjunto_req1"]["name"];

						if($adjunto_req1!=""){

							$nombre=$this->procesaArchivo($_FILES["adjunto_req1"],rand(0,10000)."_".date("ymdHis"));

							$data_mod["adjunto_requerimiento_1"]=$nombre;

						}



						$adjunto_respuesta = $_FILES["adjunto_respuesta"]["name"];

						if($adjunto_respuesta!=""){

							$nombre=$this->procesaArchivo($_FILES["adjunto_respuesta"],rand(0,10000)."_".date("ymdHis"));

							$data_mod["adjunto_respuesta_1"]=$nombre;

						}



						if($this->Esmodel->formActualizar($hash,$data_mod)){



							$estadoBD = $this->Esmodel->getEstadoEs($hash);



							//if($checkcorreo=="on"){

								$this->EnviaCorreo($hash,$estadoBD);

							//}

				

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

			$data = $this->Esmodel->getDataEs($hash);

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

					$asunto = "Solicitud Soporte N°".$key["id_rop"]." [".$key["tipo"]."]";

					$cuerpo = "Le informamos que se ha ingresado la solicitud Soporte N°".$key["id_rop"]." [".$key["tipo"]." |  ".$key["requerimiento"]."] con el siguiente detalle.";

					$cuerpo2 = "Informamos a usted que de acuerdo a los plazos preestablecidos por su organización el plazo para responder esta solicitud es en  ".$key["horas_estimadas"]." hrs aprox, terminado dicho plazo la solicitud sera escalada al responsable superior predefinido por su organización.<br> 

					Para contestar puede ingresar al siguiente link con previo ingreso de sus credenciales de seguridad.";

					$this->email->from("syr@xr3t.cl","Escalamiento y soporte plataforma XR3");



					$para = "cristian.aravena@xr3.cl";



				}



				if($tipo==1){ //ASIGNADO

					$asunto = "Asignación solicitud Soporte N°".$key["id_rop"]." [".$key["tipo"]."]";

					$cuerpo = "Le informamos que se ha asignado la solicitud Soporte N°".$key["id_rop"]." [".$key["tipo"]." |  ".$key["requerimiento"]."] con el siguiente detalle.";

					$cuerpo2 = "Informamos a usted que de acuerdo a los plazos preestablecidos por su organización el plazo para responder esta solicitud es en  ".$key["horas_estimadas"]." hrs aprox, terminado dicho día la solciitud sera escalada al responsable superior predefinido por su organización.<br> 

					Para contestar puede ingresar al siguiente link con previo ingreso de sus credenciales de seguridad.";

					$this->email->from("syr@xr3t.cl","Escalamiento y soporte plataforma XR3");



					$para = "cristian.aravena@xr3.cl";



				}



				if($tipo==2){//FINALIZAR

					$asunto = "Finalización solicitud Soporte N°".$key["id_rop"]." [".$key["tipo"]."]";

					$cuerpo = "Le informamos que se la solicitud Soporte N°".$key["id_rop"]." [".$key["tipo"]." |  ".$key["requerimiento"]."] ha sido finalizada.";

					$cuerpo2 = "Para más detalles puede ingresar al siguiente link con previo ingreso de sus credenciales de seguridad.";

					$this->email->from("syr@xr3t.cl","Escalamiento y soporte plataforma XR3");



					$para = $key["correo_tecnico"];



				}



				if($tipo==3){//CANCELADA

					$asunto = "Cancelación solicitud Soporte N°".$key["id_rop"]." [".$key["tipo"]."]";

					$cuerpo = "Le informamos que se la solicitud Soporte N°".$key["id_rop"]." [".$key["tipo"]." |  ".$key["requerimiento"]."] ha sido cancelada.";

					$cuerpo2 = "Para más detalles puede ingresar al siguiente link con previo ingreso de sus credenciales de seguridad.";

					$this->email->from("syr@xr3t.cl","Escalamiento y soporte plataforma XR3");



					$para = $key["correo_tecnico"];



				}



				if($tipo==4){//VALIDAR

					$asunto = "Validación previa solicitud Soporte N°".$key["id_rop"]." [".$key["tipo"]."]";

					$cuerpo = "Le informamos que se la solicitud Soporte N°".$key["id_rop"]." [".$key["tipo"]." |  ".$key["requerimiento"]."] ya cuenta con la validación requerida por su organización por lo que ahora comienza el plazo de  ".$key["horas_estimadas"]."  horas para su finalización.";

					$cuerpo2 = "Para más detalles puede ingresar al siguiente link con previo ingreso de sus credenciales de seguridad.";

					$this->email->from("syr@xr3t.cl","Escalamiento y soporte plataforma XR3");



					$para = "cristian.aravena@xr3.cl";

				}

				

				if($prueba){

					$para = array("sebastian.celis@splice.cl");

				}



				$datos = array("dato"=>$key,"asunto"=>$asunto,"cuerpo"=>$cuerpo,"cuerpo2"=>$cuerpo2);

				$html = $this->load->view('back_end/es/correo',$datos,TRUE);



				$this->email->to($para);

				//$this->email->cc($copias);



				//$this->email->bcc(array("ricardo.hernandez@km-telecomunicaciones.cl","german.cortes@km-telecomunicaciones.cl"));

				$this->email->subject($asunto); 

				$this->email->message($html); 



				if($key["adjunto_requerimiento_1"]!="") {$this->email->attach(base_url()."archivos/es/".$key["adjunto_requerimiento_1"]);}

				if($key["adjunto_respuesta_1"]!="") {$this->email->attach(base_url()."archivos/es/".$key["adjunto_respuesta_1"]);}



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

			$config['upload_path'] = './archivos/es';

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



		public function eliminaEs(){

			$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));

			if($this->Esmodel->eliminaEs($hash)){

			echo json_encode(array("res" => "ok" , "msg" => "Registro eliminado correctamente."));

			}else{

			echo json_encode(array("res" => "error" , "msg" => "Problemas eliminando el registro, intente nuevamente."));

			}

		}



		public function getDataEs(){

			if($this->input->is_ajax_request()){

				$this->checkLogin();	

				$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));

				$data=$this->Esmodel->getDataEs($hash);

				if($data){

					echo json_encode(array('res'=>"ok", 'datos' => $data));exit;

				}else{

					echo json_encode(array('res'=>"ok", 'datos' => $data));exit;

				}	

			}else{

				exit('No direct script access allowed');

			}

		}



		public function excel_Es(){

			$desde = $this->uri->segment(2);

			$hasta = $this->uri->segment(3);

			$estado = $this->uri->segment(4);

			$responsable = $this->uri->segment(5);

			$supervisor=$this->uri->segment(6);



			if($desde!=""){$desde=date("Y-m-d",strtotime($desde));}else{$desde="";}

			if($hasta!=""){$hasta=date("Y-m-d",strtotime($hasta));}else{$hasta="";}

			if($estado=="-"){$estado="";}

			if($responsable=="-"){$responsable="";}

			if($supervisor=="-"){$supervisor="";}

			$data = $this->Esmodel->getEsList($desde,$hasta,$estado,$responsable,$supervisor);



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



				<h3>Reporte SYR (Escalamiento y soporte)</h3>

				<table align='center' border="1"> 

					<tr style="background-color:#F9F9F9">

						<th class="head">ID </th>    

						<th class="head">Estado </th>    

						<th class="head">Solicitante </th>    

						<th class="head">T&eacute;cnico afectado </th>    

						<th class="head">Comuna </th>    

						<th class="head">Fecha ingreso </th> 

						<th class="head">Hora de ingreso </th> 

						<th class="head">Tipo</th> 

						<th class="head">Requerimiento </th> 

						<th class="head">Responsable </th> 

						<th class="head">Validador por sistema  </th> 

						<th class="head">Validador real  </th> 

						<th class="head">Fecha validaci&oacute;n  </th> 

						<th class="head">Hora validaci&oacute;n  </th> 

						<th class="head">Persona asignada  </th> 

						<th class="head">Horas estimadas  </th> 

						<th class="head">Horas pendiente  </th> 

						<th class="head">Detalle de requerimiento  </th> 

						<th class="head">Fecha finalizado  </th> 

						<th class="head">Hora finalizado   </th> 

						<th class="head">Observaci&oacute;n de finalizado   </th> 

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

									<td><?php echo utf8_decode($d["solicitante"]); ?></td>

									<td><?php echo utf8_decode($d["tecnico"]); ?></td>

									<td><?php echo utf8_decode($d["comuna"]); ?></td>

									<td><?php echo utf8_decode($d["fecha_ingreso"]); ?></td>

									<td><?php echo utf8_decode($d["hora_ingreso"]); ?></td>

									<td><?php echo utf8_decode($d["tipo"]); ?></td>

									<td><?php echo utf8_decode($d["requerimiento"]); ?></td>

									<td><?php echo utf8_decode($d["responsable1"]); ?></td>

									<td><?php echo utf8_decode($d["validador_sistema"]); ?></td>

									<td><?php echo utf8_decode($d["validador_real"]); ?></td>

									<td><?php echo utf8_decode($d["fecha_validacion"]); ?></td>

									<td><?php echo utf8_decode($d["hora_validacion"]); ?></td>

									<td><?php echo utf8_decode($d["usuario_asignado"]); ?></td>

									<td><?php echo utf8_decode($d["horas_estimadas"]); ?></td>

									<td><?php echo utf8_decode($d["horas_pendientes"]); ?></td>

									<td><?php echo utf8_decode($d["descripcion"]); ?></td>

									<td><?php echo utf8_decode($d["fecha_fin"]); ?></td>

									<td><?php echo utf8_decode($d["hora_fin"]); ?></td>

									<td><?php echo utf8_decode($d["observacion_fin"]); ?></td>

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



	/*********MANTENEDOR REQUERIMIENTOS************/



		public function getMantenedorReq(){

			if($this->input->is_ajax_request()){

				$desde = date('Y-m-d', strtotime('-365 day', strtotime(date("d-m-Y"))));

				$hasta = date('Y-m-d');

				$perfil= $this->session->userdata('id_perfil');

				$tipos = $this->Esmodel->listaTipos();



				foreach($tipos as $key => $tipo){

					if($perfil >= 4 && $tipo['id'] == 9){

							unset($tipos[$key]);

					}

				}



				$datos = array(

					'desde' => $desde,

					'hasta' => $hasta,

					'tipos' => $tipos

				);

				

				$this->load->view('back_end/es/mantenedor_requerimientos',$datos);

			}

		}



		public function getMantenedorReqList(){ 

			$estado = $this->security->xss_clean(strip_tags($this->input->get_post("estado")));

 			echo json_encode($this->Esmodel->getMantenedorReqList($estado));

		}



		public function formMantenedorReq(){

			if($this->input->is_ajax_request()){

				$this->checkLogin();	

				$hash = $this->security->xss_clean(strip_tags($this->input->post("hash")));

				$tipo = $this->security->xss_clean(strip_tags($this->input->post("tipo")));

				$requerimiento = $this->security->xss_clean(strip_tags($this->input->post("requerimiento")));

				$responsable1 = $this->security->xss_clean(strip_tags($this->input->post("responsable1")));

				$responsable2 = $this->security->xss_clean(strip_tags($this->input->post("responsable2")));

				$horas_estimadas = $this->security->xss_clean(strip_tags($this->input->post("horas_estimadas")));

				$estado = $this->security->xss_clean(strip_tags($this->input->post("estado")));

				$requiere_validacion = $this->security->xss_clean(strip_tags($this->input->post("requiere_validacion")));

				$ultima_actualizacion = date("Y-m-d G:i:s")." | ".$this->session->userdata("nombre_completo");



				if ($this->form_validation->run("formMantenedorReq") == FALSE){

					echo json_encode(array('res'=>"error", 'msg' => strip_tags(validation_errors())));exit;

				}else{	



					if($hash==""){

 

						$data = array(

							'id_tipo' => $tipo,

							'requerimiento' => $requerimiento,

							'id_responsable1'=> $responsable1,

							'id_responsable2' => $responsable2,

							'horas_estimadas' => $horas_estimadas,

							'requiere_validacion' =>$requiere_validacion,

							'estado' => $estado,

							"ultima_actualizacion" => $ultima_actualizacion);

  

						$insert_id=$this->Esmodel->formIngresoMantenedorReq($data);

						if($insert_id!=FALSE){

 

							echo json_encode(array('res'=>"ok",  'msg' => OK_MSG));exit;



						}else{

							echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;

						}



					}else{

					

						$data_mod = array(

							'id_tipo' => $tipo,

							'requerimiento' => $requerimiento,

							'id_responsable1'=> $responsable1,

							'id_responsable2' => $responsable2,

							'horas_estimadas' => $horas_estimadas,

							'estado' => $estado,

							'requiere_validacion' =>$requiere_validacion,

							"ultima_actualizacion" => $ultima_actualizacion);

					   

						if($this->Esmodel->formActualizarMantenedorReq($hash,$data_mod)){

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



		public function eliminaMantenedorReq(){

			$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));

			if($this->Esmodel->eliminaMantenedorReq($hash)){

				echo json_encode(array("res" => "ok" , "msg" => "Registro eliminado correctamente."));

			}else{

				echo json_encode(array("res" => "error" , "msg" => "Problemas eliminando el registro, intente nuevamente."));

			}

		}



		public function getDataMantReq(){

			if($this->input->is_ajax_request()){

				$this->checkLogin();	

				$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));

				$data=$this->Esmodel->getDataMantReq($hash);



				if($data){

					echo json_encode(array('res'=>"ok", 'datos' => $data));exit;

				}else{

					echo json_encode(array('res'=>"ok", 'datos' => $data));exit;

				}	



			}else{

				exit('No direct script access allowed');

			}

		}



		public function excelMantReq(){

			$estado = $this->uri->segment(4);

			if($estado=="-"){$estado="";}



			$data = $this->Esmodel->getMantenedorReqList($estado);



			if(!$data){

				return FALSE;

			}else{



				$nombre="reporte-mantenedor-requerimientos.xls";

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



				<h3>Reporte Mantenedor requerimientos ROP</h3>

				<table align='center' border="1"> 

					<tr style="background-color:#F9F9F9">

						<th class="head">Tipo </th>    

						<th class="head">Requerimiento </th>    

						<th class="head">Responsable1 </th>    

						<th class="head">Correo Responsable1 </th>    

						<th class="head">Responsable2 </th> 

						<th class="head">Correo Responsable2 </th> 

						<th class="head">horas estimadas</th> 

						<th class="head">Requiere validacion</th> 

						<th class="head">Estado</th> 

						<th class="head">&Uacute;ltima actualizaci&oacute;n</th> 

					</tr>

					</thead>	

					<tbody>

					<?php 

						if($data !=FALSE){

							foreach($data as $d){

							?>

								<tr>

									<td><?php echo utf8_decode($d["tipo"]); ?></td>

									<td><?php echo utf8_decode($d["requerimiento"]); ?></td>

									<td><?php echo utf8_decode($d["responsable1"]); ?></td>

									<td><?php echo utf8_decode($d["correo_responsable1"]); ?></td>

									<td><?php echo utf8_decode($d["responsable2"]); ?></td>

									<td><?php echo utf8_decode($d["correo_responsable2"]); ?></td>

									<td><?php echo utf8_decode($d["horas_estimadas"]); ?></td>

									<td><?php echo utf8_decode($d["requiere_validacion"]); ?></td>

									<td><?php echo utf8_decode($d["estado"]); ?></td>

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



		/********** TIPO **********/

		public function getMantenedorReqTipoList(){ 

 			echo json_encode($this->Esmodel->getMantenedorReqTipoList());

		}



		public function formMantenedorReqTipo(){

			if($this->input->is_ajax_request()){

				$this->checkLogin();	

				$hash = $this->security->xss_clean(strip_tags($this->input->post("hash_tipo")));

				$tipo = $this->security->xss_clean(strip_tags($this->input->post("tipo")));





				if ($this->form_validation->run("formMantenedorReqTipo") == FALSE){

					echo json_encode(array('res'=>"error", 'msg' => strip_tags(validation_errors())));exit;

				}else{	



					if($hash==""){

						$data = array(

							'tipo' => $tipo,);

  

						$insert_id=$this->Esmodel->formIngresoMantenedorReqTipo($data);

						if($insert_id!=FALSE){

 

							echo json_encode(array('res'=>"ok",  'msg' => OK_MSG));exit;



						}else{

							echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;

						}



					}else{

					

						$data_mod = array(

							'tipo' => $tipo,);

					   

						if($this->Esmodel->formActualizarMantenedorReqTipo($hash,$data_mod)){

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



		public function eliminaMantenedorReqTipo(){

			$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));

			if($this->Esmodel->eliminaMantenedorReqTipo($hash)){

				echo json_encode(array("res" => "ok" , "msg" => "Registro eliminado correctamente."));

			}else{

				echo json_encode(array("res" => "error" , "msg" => "Problemas eliminando el registro, intente nuevamente."));

			}

		}



		public function getDataMantReqTipo(){

			if($this->input->is_ajax_request()){

				$this->checkLogin();	

				$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));

				$data=$this->Esmodel->getDataMantReqTipo($hash);



				if($data){

					echo json_encode(array('res'=>"ok", 'datos' => $data));exit;

				}else{

					echo json_encode(array('res'=>"ok", 'datos' => $data));exit;

				}	



			}else{

				exit('No direct script access allowed');

			}

		}





	/******************* GRAFICOS *******************/



	public function getResumenSyr(){

		$desde=date('Y-m-d', strtotime('-35 day', strtotime(date("d-m-Y"))));

		$hasta=date('Y-m-d');

		$perfil= $this->session->userdata('id_perfil');

		$tipos = $this->Esmodel->listaTipos();



		foreach($tipos as $key => $tipo){

			if($perfil >= 4 && $tipo['id'] == 9){

					unset($tipos[$key]);

			}

		}



		if($this->input->is_ajax_request()){

			$datos=array(	

				'desde' => $desde,

				'hasta' => $hasta,

				'tipos' => $tipos,			

			);

			$this->load->view('back_end/es/resumen',$datos);

		}

	}



	public function graphRequerimientos(){

		$desde=date('Y-m-d', strtotime('-35 day', strtotime(date("d-m-Y"))));

		$hasta=date('Y-m-d');

		$tipo=$this->security->xss_clean(strip_tags($this->input->get_post("tipo")));



		if($desde!=""){$desde=date("Y-m-d",strtotime($desde));

		}else{$desde="";}

		if($hasta!=""){$hasta=date("Y-m-d",strtotime($hasta));

		}else{$hasta="";}



		echo json_encode(array("data"=>$this->Esmodel->graphRequerimientos($desde,$hasta,$tipo)));

	}



	public function graphRequerimientosSeg(){

		$desde=date('Y-m-d', strtotime('-35 day', strtotime(date("d-m-Y"))));

		$hasta=date('Y-m-d');

		$tipo=$this->security->xss_clean(strip_tags($this->input->get_post("tipo")));



		if($desde!=""){$desde=date("Y-m-d",strtotime($desde));

		}else{$desde="";}

		if($hasta!=""){$hasta=date("Y-m-d",strtotime($hasta));

		}else{$hasta="";}



		echo json_encode(array("data"=>$this->Esmodel->graphRequerimientosSeg($desde,$hasta,$tipo)));

	}



}