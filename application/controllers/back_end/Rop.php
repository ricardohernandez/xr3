<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rop extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model("back_end/Ropmodel");
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
	      	if($this->session->userdata('id_perfil')>3){
	      		redirect("./login");
	      	}
	      }else{
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
    	$this->Ropmodel->insertarVisita($data);
	}

	public function index(){
		$this->visitas("Rop");
    	$this->acceso();

	    $datos = array(
	        'titulo' => "Rop (Requerimientos operacionales de personas)",
	        'contenido' => "rop/inicio",
	        'perfiles' => $this->Iniciomodel->listaPerfiles(),
	        'tipos' => $this->Ropmodel->listaTipos()
		);  

		$this->load->view('plantillas/plantilla_back_end',$datos);
	}

    public function getRopInicio(){
		if($this->input->is_ajax_request()){
			$desde = date('Y-m-d', strtotime('-365 day', strtotime(date("d-m-Y"))));
	    	$hasta = date('Y-m-d');

			
	    	$datos = array(
				'desde' => $desde,
				'hasta' => $hasta,
				'tipos' => $this->Ropmodel->listaTipos()
			);
			
			$this->load->view('back_end/rop/rop',$datos);
		}
	}

	public function getRopList(){
		$estado=$this->security->xss_clean(strip_tags($this->input->get_post("estado")));
		$desde=$this->security->xss_clean(strip_tags($this->input->get_post("desde")));
		$hasta=$this->security->xss_clean(strip_tags($this->input->get_post("hasta")));

		if($desde!=""){$desde=date("Y-m-d",strtotime($desde));}else{$desde="";}
		if($hasta!=""){$hasta=date("Y-m-d",strtotime($hasta));}else{$hasta="";}	

		$responsable=$this->security->xss_clean(strip_tags($this->input->get_post("responsable")));
		echo json_encode($this->Ropmodel->getRopList($desde,$hasta,$estado,$responsable));
	}

	public function listaRequerimientos(){
		$tipo=$this->security->xss_clean(strip_tags($this->input->get_post("tipo")));
		echo $this->Ropmodel->listaRequerimientos($tipo);exit;
	}

	public function listaPersonas(){
		echo $this->Ropmodel->listaPersonas();exit;
	}

	public function listaResponsables(){
		echo $this->Ropmodel->listaResponsables();exit;
	}

	public function formRop(){
		if($this->input->is_ajax_request()){
			$this->checkLogin();	
			$hash = $this->security->xss_clean(strip_tags($this->input->post("hash")));
			$tipo = $this->security->xss_clean(strip_tags($this->input->post("tipo")));
			$requerimiento = $this->security->xss_clean(strip_tags($this->input->post("requerimiento")));
			$descripcion = $this->security->xss_clean(strip_tags($this->input->post("descripcion")));

			$usuario_asignado = $this->security->xss_clean(strip_tags($this->input->post("usuario_asignado")));
			$estado = $this->security->xss_clean(strip_tags($this->input->post("estado")));
			$validador_sistema = $this->security->xss_clean(strip_tags($this->input->post("validador_sistema")));
			$horas_estimadas = $this->security->xss_clean(strip_tags($this->input->post("horas_estimadas")));
			$observacion = $this->security->xss_clean(strip_tags($this->input->post("observacion")));
			
			$adjunto_req1 =  @$_FILES["adjunto_req1"]["name"];
			$adjunto_req2 =  @$_FILES["adjunto_req2"]["name"];
			$adjunto_resp =  @$_FILES["adjunto_respuesta"]["name"];
			
		    $checkcorreo = $this->security->xss_clean(strip_tags($this->input->post("checkcorreo")));
		    $checkvalidar = $this->security->xss_clean(strip_tags($this->input->post("checkvalidar")));
		    $checkfin = $this->security->xss_clean(strip_tags($this->input->post("checkfin")));

			$id_solicitante = $this->session->userdata("id");
			$comuna = $this->Ropmodel->getComunaSolicitante($id_solicitante);
			$estado = $this->security->xss_clean(strip_tags($this->input->post("estado")));
			$ultima_actualizacion = date("Y-m-d G:i:s")." | ".$this->session->userdata("nombre_completo");

   			if ($this->form_validation->run("formRop") == FALSE){
				echo json_encode(array('res'=>"error", 'msg' => strip_tags(validation_errors())));exit;
			}else{	

		    	if($hash==""){

					//VALIDA REQUERIMIENTO VALIDACION, NULO O JEFE
					$requiere_validacion = $this->Ropmodel->getRequiereValidacion($requerimiento);
					
					if($requiere_validacion == 0){
						$validador_sistema = NULL;
					}else{
						$validador_sistema = $this->Ropmodel->getJefeSolicitante($id_solicitante);
					}

		    		$data = array(
		    		 	'id_requerimiento' => $requerimiento,
		    		 	'id_estado' => "0",
						'id_solicitante'=> $id_solicitante,
						'comuna' => $comuna,
						'descripcion' => $descripcion,
						'validador_sistema' => $validador_sistema,
						'fecha_ingreso' => date("Y-m-d"),
		    		 	'hora_ingreso' =>  date("G:i:s"),
		    		 	"ultima_actualizacion" => $ultima_actualizacion);

		    		if($adjunto_req1!=""){
						$nombre=$this->procesaArchivo($_FILES["adjunto_req1"],$id_usuario_asignado."_".date("ymdHis"),1);
						$data["adjunto_requerimiento_1"]=$nombre;
					}else{
						$data["adjunto_requerimiento_1"]="";
					}

					$insert_id=$this->Ropmodel->formIngreso($data);
					if($insert_id!=FALSE){

						if($checkcorreo=="on"){
							$this->EnviaCorreo(sha1($insert_id),"0");
						}

						echo json_encode(array('res'=>"ok",  'msg' => OK_MSG));exit;

					}else{
						echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
					}

				}else{
					
					$data_mod = array(
						'id_requerimiento' => $requerimiento,
						'id_usuario_asignado' => $usuario_asignado,
						'horas_estimadas' => $horas_estimadas,
						'observacion_fin' => $observacion,
						"ultima_actualizacion" => $ultima_actualizacion);
					
					$usuarioAsignadoBD = $this->Ropmodel->getUsuarioAsignadoRop($hash);
					$validadorRealRop = $this->Ropmodel->getValidadorRealRop($hash);
					$estadoRop = $this->Ropmodel->getEstadoRop($hash);
					
					
					//SI VIENE USUARIO ASIGNADO Y EL ESTADO ES DIFERENTE A PENDIENTE Y ASIGNADO

					if(!empty($usuario_asignado) and !$usuarioAsignadoBD){
						$data_mod["id_estado"] = 1;
					}

					//SI VIENE CHECK VALIDADO Y EL USUARIO ACTUAL ES DIFERENTE AL YA GUARDADO
					if($checkvalidar=="on" and $this->session->userdata("id")<>$validadorRealRop){
						$id_validador_real = $this->session->userdata("id");
						$data_mod["id_validador_real"] = $id_validador_real;
						$data_mod["fecha_validacion"] = date("Y-d-m");
						$data_mod["hora_validacion"] = date("G:i:s");
					}

					//SI VIENE CHECK FINALIZADO 

					if($checkfin=="on"){
						$data_mod["id_estado"] = 2;
						$data_mod["fecha_fin"] = date("Y-d-m");
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

					$adjunto_req2 = $_FILES["adjunto_req2"]["name"];
					if($adjunto_req2!=""){
						$nombre=$this->procesaArchivo($_FILES["adjunto_req2"],rand(0,10000)."_".date("ymdHis"));
						$data_mod["adjunto_requerimiento_2"]=$nombre;
					}

					$adjunto_respuesta = $_FILES["adjunto_respuesta"]["name"];
					if($adjunto_respuesta!=""){
						$nombre=$this->procesaArchivo($_FILES["adjunto_respuesta"],rand(0,10000)."_".date("ymdHis"));
						$data_mod["adjunto_respuesta_1"]=$nombre;
					}

					if($this->Ropmodel->formActualizar($hash,$data_mod)){

						if($checkcorreo=="on"){
							$this->EnviaCorreo($hash,"1");
						}

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
		return TRUE;

		$this->load->library('email');
		$data=$this->Ropmodel->getDataRop($hash);
		$prueba=FALSE;

		foreach($data as $key){
		    $config = array (
	       	  'mailtype' => 'html',
	          'charset'  => 'utf-8',
	          'priority' => '1',
	          'wordwrap' => TRUE,
	          'protocol' => "smtp",
	          'smtp_port' => 587,
	          'smtp_host' => 'mail.xr3t.cl',
		      'smtp_user' => 'soporteplataforma@xr3t.cl',
		      'smtp_pass' => '9mWj.RUhL&3)'
	        );

		    $this->email->initialize($config);
			$asunto ="Rop plataforma XR3 (".$key["estado"].") : ".$key["titulo"]." | ".date('d-m-Y', strtotime($key["fecha_ingreso"]));
			$datos=array("data"=>$data,"titulo"=>$asunto);
			$html=$this->load->view('back_end/rop/correo',$datos,TRUE);

			if($prueba){
				$para=array("ricardo.hernandez@splice.cl");
				$copias=array("ricardo.hernandez@km-t.cl");
				$this->email->from("soporteplataforma@xr3t.cl","Soporte plataforma XR3");
			}else{
				$para=array("german.cortes@km-telecomunicaciones.cl","ricardo.hernandez@km-telecomunicaciones.cl","sebastian.celis@splice.cl");
				$copias=array("roberto.segovia@xr3.cl","cristian.cortes@xr3.cl");
				$this->email->from("soporteplataforma@xr3t.cl","Soporte plataforma XR3");
			}

			$this->email->to($para);
			$this->email->cc($copias);
	    	$this->email->bcc(array("ricardo.hernandez@km-telecomunicaciones.cl","soporteplataforma@xr3t.cl"));
			$this->email->subject($asunto);
			$this->email->message($html); 
			/*if($key["adjunto"]!="") {$this->email->attach(base_url()."lic/".$key["adjunto"]);}*/

			$resp=$this->email->send();

			if ($resp) {
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
			$config['upload_path'] = './archivos/rop';
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
	    if($this->Ropmodel->eliminaRop($hash)){
	      echo json_encode(array("res" => "ok" , "msg" => "Registro eliminado correctamente."));
	    }else{
	      echo json_encode(array("res" => "error" , "msg" => "Problemas eliminando el registro, intente nuevamente."));
	    }
	}

	public function getDataRop(){
		if($this->input->is_ajax_request()){
			$this->checkLogin();	
			$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
			$data=$this->Ropmodel->getDataRop($hash);
			if($data){
				echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
			}else{
				echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
			}	
		}else{
			exit('No direct script access allowed');
		}
	}

	public function excel_rop(){
		$desde = $this->uri->segment(2);
		$hasta = $this->uri->segment(3);
		$estado = $this->uri->segment(4);
		$responsable = $this->uri->segment(5);

		if($desde!=""){$desde=date("Y-m-d",strtotime($desde));}else{$desde="";}
		if($hasta!=""){$hasta=date("Y-m-d",strtotime($hasta));}else{$hasta="";}
		if($estado=="-"){$estado="";}
		if($responsable=="-"){$responsable="";}

	 	$data = $this->Ropmodel->getRopList($desde,$hasta,$estado,$responsable);

		if(!$data){
			return FALSE;
		}else{

			$nombre="reporte-rop.xls";
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

			<h3>Reporte Rop (Requerimientos operacionales de personas)</h3>
			<table align='center' border="1"> 
				<tr style="background-color:#F9F9F9">
					<th class="head">Estado </th>    
					<th class="head">Solicitante </th>    
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
								<td><?php echo utf8_decode($d["estado"]); ?></td>
								<td><?php echo utf8_decode($d["solicitante"]); ?></td>
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

}