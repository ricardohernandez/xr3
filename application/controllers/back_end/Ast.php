<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use Dompdf\Dompdf;
use Dompdf\Options;

class Ast extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if($this->uri->segment("1")==""){
			redirect("inicio");
		}
		$this->load->model("back_end/Astmodel");
		$this->load->model("back_end/Iniciomodel");
		$this->load->helper(array('fechas','str'));
		$this->load->library('user_agent');
	}

	public function visitas($modulo){
		$this->load->library('user_agent');
		$data=array("id_usuario"=>$this->session->userdata('id'),
			"id_aplicacion"=>8,
			"modulo"=>$modulo,
	     	"fecha"=>date("Y-m-d G:i:s"),
	    	"navegador"=>"navegador :".$this->agent->browser()."\nversion :".$this->agent->version()."\nos :".$this->agent-> platform()."\nmovil :".$this->agent->mobile(),
	    	"ip"=>$this->input->ip_address(),
    	);
    	$this->Astmodel->insertarVisita($data);
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

	/**********AST ************/
		
		public function formCargaMasivaAst(){
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
					    	"tipo"=>3,
					    	"descripcion"=>$data[0],
						);	

					    $this->Astmodel->insertarItemAst($arr);
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

	    public function index(){
	    	$this->acceso();
    	    $datos = array(
		        'titulo' => "AST - Analisis seguro de trabajo",
		        'contenido' => "ast/inicio",
		        'perfiles' => $this->Iniciomodel->listaPerfiles(),
			);  
			$this->load->view('plantillas/plantilla_back_end',$datos);
		}
		
		public function vistaAst(){
			$this->visitas("Listado");
			$desde=date('Y-m-d', strtotime('-30 day', strtotime(date("Y-m-d"))));
	    	$hasta=date('Y-m-d');
			$tecnicos=$this->Astmodel->listaTecnicos();
			$auditores=$this->Astmodel->listaAuditores();
    		$comunas=$this->Astmodel->listaComunas();
    		$actividades=$this->Astmodel->listaActividades();
    		$estados=$this->Astmodel->listaEstados();

			$datos=array(
				'desde' => $desde,	   
		        'hasta' => $hasta,
				'tecnicos' => $tecnicos,
				'auditores' => $auditores,
				'comunas' => $comunas,
				'actividades' => $actividades,
				'estados' => $estados,
		   	);
			$this->load->view('back_end/ast/ast',$datos);
		}

		public function listaAst(){
			$desde=$this->security->xss_clean(strip_tags($this->input->get_post("desde")));
			$hasta=$this->security->xss_clean(strip_tags($this->input->get_post("hasta")));
			$tecnico=$this->security->xss_clean(strip_tags($this->input->get_post("tecnico")));
			if($desde!=""){$desde=date("Y-m-d",strtotime($desde));}else{$desde="";}
			if($hasta!=""){$hasta=date("Y-m-d",strtotime($hasta));}else{$hasta="";}	
			echo json_encode($this->Astmodel->listaAst($desde,$hasta,$tecnico));
		}
		
		public function insertaChecklist($checklist,$hash){
			$id = $this->Astmodel->getIdPorHash($hash);
			foreach($checklist as $c){
				$data_detalle = array(
					 "id_ast" => $id,
					 "id_check" => $c["id"], 
					 "estado" => "0" , 
					 "solucion_estado" => "0",
					 "solucion_fecha" => "0000-00-00",
					 "solucion_observacion" => "",
					 "solucion_digitador" => 0,	
					 "ultima_actualizacion"=>date("Y-m-d G:i:s"),
					 "observacion" =>"");

				$this->Astmodel->insertaDetalleAst($data_detalle);
				$data_detalle=array();
			}
			return TRUE;
		}

		public function formAst(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();
				/*print_r($_POST);exit;*/
				$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
				$tecnico=$this->security->xss_clean(strip_tags($this->input->post("tecnico")));
				$auditor=$this->security->xss_clean(strip_tags($this->input->post("auditor")));
				$actividad=$this->security->xss_clean(strip_tags($this->input->post("actividad")));
				$comuna=$this->security->xss_clean(strip_tags($this->input->post("comuna")));
				$fecha=$this->security->xss_clean(strip_tags($this->input->post("fecha")));
				$direccion=$this->security->xss_clean(strip_tags($this->input->post("direccion")));
				$observaciones=$this->security->xss_clean(strip_tags($this->input->post("observaciones")));
				$estado_ast=$this->security->xss_clean(strip_tags($this->input->post("estado_ast")));
				$checkcorreo=$this->security->xss_clean(strip_tags($this->input->post("checkcorreo")));
				$firmado=$this->security->xss_clean(strip_tags($this->input->post("firmado")));
				$hora=date("H:m:s");
				$ultima_actualizacion=date("Y-m-d G:i:s")." | ".$this->session->userdata("nombre_completo");
				$estado=$this->input->post("estado");
				$observacion=$this->input->post("observacion");
				$firmado = ($firmado=="on") ? "si" : "no";

				if ($this->form_validation->run("formAst") == FALSE){
					echo json_encode(array('res'=>"error", 'msg' => strip_tags(validation_errors())));exit;
				}else{	

					$data_insert=array(
						"id_actividad"=>$actividad,
						"tecnico_id"=>$tecnico,
						"auditor_id"=>0,
						"id_comuna"=>$comuna,
						"id_estado"=>1,
						"fecha"=>$fecha,
						"hora"=>$hora,
						"direccion"=>$direccion,
						"riesgos_o_controles_norealizados"=>"",
						"observaciones"=>$observaciones,
						"firmado"=>$firmado,
						"ultima_actualizacion"=>$ultima_actualizacion);	

					if($hash==""){

						if($actividad==""){
							echo json_encode(array('res'=>"error", 'msg' => "El campo actividad es obligatorio."));exit;
						}

						$id=$this->Astmodel->formAst($data_insert);
						$hash = sha1($id);

						if($hash!=FALSE){

							if(!$this->Astmodel->existeDetalleAst($hash)){
								$checklist=$this->Astmodel->listaChecklistAst($actividad);

								if($this->insertaChecklist($checklist,$hash)){
									$item = $this->input->post("item");

									$hayEstadoNo = FALSE;
									
									foreach($item as $h){
										$item = $h;
										$estado = $this->input->post("estado_".$item)[0];
										$data_actualizar = array("estado" =>  $estado, "ultima_actualizacion"=>$ultima_actualizacion);

										if($estado=="no"){
											$this->enviaCorreoFallo($hash,$item);
										}

										$id = $this->Astmodel->getIdPorHash($hash);
										$id_detalle = $this->Astmodel->getIddetalle($id,$item);
										$this->Astmodel->actualizaDetalleAst($id_detalle,$data_actualizar);

										$tipo_item = $this->Astmodel->getTipoItem($item);

										if ($estado == "no" && ($tipo_item == 2 || $tipo_item == 3)) {
											$hayEstadoNo = TRUE;
										}

									}	

									$data_fallos = array();
									$data_fallos["riesgos_o_controles_norealizados"] = ($hayEstadoNo) ? "si" : "no";
									$data_fallos["id_estado"] = ($hayEstadoNo) ? "3" : "2";
									$this->Astmodel->actualizarAst($hash, $data_fallos);

									/* if($this->Astmodel->getFallosChecklist($hash)){
										$data_fallos["riesgos_o_controles_norealizados"] = "si";
										$data_fallos["id_estado"] = "3";
										$this->Astmodel->actualizarAst($hash,$data_fallos);
									}else{
										$data_fallos["riesgos_o_controles_norealizados"] = "no";
										$data_fallos["id_estado"] = "2";
										$this->Astmodel->actualizarAst($hash,$data_fallos);
									} */


								}else{
									echo json_encode(array('res'=>"error", 'msg' => "Error ingresando el detalle del checklist, intente nuevamente."));exit;
								}

							}else{
								
								$item = $this->input->post("item");
								$hayEstadoNo = FALSE;

								foreach($item as $h){
									$item = $h;
									$estado = $this->input->post("estado_".$item)[0];
									/*$observacion = $this->input->post("observacion_".$herramienta)[0];*/
									$data_actualizar = array("estado" =>  $estado ,/* "observacion" =>  $observacion ,*/ "ultima_actualizacion"=>$ultima_actualizacion);

									$tipo_item = $this->Astmodel->getTipoItem($item);
									if($tipo_item==2 || $tipo_item==3){
										if($estado=="no"){
											$this->enviaCorreoFallo($hash,$item);
										}
									}

									$id_detalle = $this->Astmodel->getIddetalle($id,$item);
									$this->Astmodel->actualizaDetalleAst($id_detalle,$data_actualizar);

									$tipo_item = $this->Astmodel->getTipoItem($item);

									if ($estado == "no" && ($tipo_item == 2 || $tipo_item == 3)) {
										$hayEstadoNo = TRUE;
									}

								}

								$data_fallos = array();
								$data_fallos["riesgos_o_controles_norealizados"] = ($hayEstadoNo) ? "si" : "no";
								$data_fallos["id_estado"] = ($hayEstadoNo) ? "3" : "2";
								$this->Astmodel->actualizarAst($hash, $data_fallos);

							}


						    $data_correo = $this->Astmodel->getDataAstCabecera(sha1($id));

							if(!$this->generaPdfAst($data_correo)){
								echo json_encode(array('res'=>"error", 'hash' =>$hash, 'msg' => "Problemas creando el archivo pdf, intente nuevamente."));exit;
							}

							if($checkcorreo=="on"){
					    		
				     			if($this->enviaCorreoIngreso($data_correo)){
									echo json_encode(array('res'=>"ok", 'hash' =>sha1($id), 'msg' => OK_MSG));exit;
								}else{
									echo json_encode(array('res'=>"error", 'hash' =>$hash, 'msg' => "Problemas enviado el correo, intente nuevamente."));exit;
								}

							}else{
								 echo json_encode(array('res'=>"ok", 'hash' =>sha1($id), 'msg' => OK_MSG));exit;
							}

						}else{
							echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
						}
				   
					}else{

						$id = $this->Astmodel->getIdPorHash($hash);
						
						$data_mod=array(
							"tecnico_id"=>$tecnico,
							"id_comuna"=>$comuna,
							"direccion"=>$direccion,
							"riesgos_o_controles_norealizados"=>"",
							"observaciones"=>$observaciones,
							"firmado"=>$firmado,
							"ultima_actualizacion"=>$ultima_actualizacion);		

						if($estado_ast=="3"){
							$data_mod["id_estado"] = $estado_ast;
							$data_mod["auditor_id"] = $auditor;
						}

						$this->Astmodel->actualizarAst($hash,$data_mod);

						$item = $this->input->post("item");

						$hayEstadoNo = FALSE;

						foreach ($item as $h) {
							$item_id = $h;
							$item = $this->Astmodel->getItemPorIdDetalle($item_id);
							$estado = $this->input->post("estado_" . $item_id)[0];
						
							$data_actualizar = array("estado" => $estado, "ultima_actualizacion" => $ultima_actualizacion);
							$id_detalle = $this->Astmodel->getIddetalle($id, $item);
							$this->Astmodel->actualizaDetalleAst($id_detalle, $data_actualizar);

							$tipo_item = $this->Astmodel->getTipoItem($item);

							if ($estado == "no" && ($tipo_item == 2 || $tipo_item == 3)) {
								$hayEstadoNo = TRUE;
							}
						}

						$data_fallos = array();
						$data_fallos["riesgos_o_controles_norealizados"] = ($hayEstadoNo) ? "si" : "no";
						$data_fallos["id_estado"] = ($hayEstadoNo) ? "3" : "2";
						$this->Astmodel->actualizarAst($hash, $data_fallos);


						/* foreach($item as $h){
							$item_id = $h;
							$item = $this->Astmodel->getItemPorIdDetalle($item_id);
							$estado = $this->input->post("estado_".$item_id)[0]; 
							$data_actualizar = array("estado" =>  $estado, "ultima_actualizacion"=>$ultima_actualizacion);
							$id_detalle = $this->Astmodel->getIddetalle($id,$item);
							$this->Astmodel->actualizaDetalleAst($id_detalle,$data_actualizar);

							$tipo_item = $this->Astmodel->getTipoItem($item);

							if($tipo_item==2 || $tipo_item==3){
								if($estado=="no"){
									$this->enviaCorreoFallo($hash,$item);
								}
							}
							
						}
						
						if($this->Astmodel->getFallosChecklist($hash)){
							$data_fallos["riesgos_o_controles_norealizados"] = "si";
							$data_fallos["id_estado"] = "3";
							$this->Astmodel->actualizarAst($hash,$data_fallos);
						}else{
							$data_fallos["riesgos_o_controles_norealizados"] = "no";
							$data_fallos["id_estado"] = "2";
							$this->Astmodel->actualizarAst($hash,$data_fallos);
						} */


					    $data_correo=$this->Astmodel->getDataAstCabecera($hash);

						if(!$this->generaPdfAst($data_correo)){
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

		public function getDataAst(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();
				$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
				$data=$this->Astmodel->getDataAst($hash);

				if($data){
					echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
				}else{
					echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
				}	
			}else{
				exit('No direct script access allowed');
			}
		}

		public function getChecklistView(){
			$actividad=$this->security->xss_clean(strip_tags($this->input->post("actividad")));
			$checklist = $this->Astmodel->listaChecklistAst($actividad);
			$datos=array("checklist"=>$checklist);
			$this->load->view('back_end/ast/checklist', $datos);
		}

		public function getUserChecklistView(){
			$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
			$checklist = $this->Astmodel->getUserChecklist($hash);
			$datos=array("checklist"=>$checklist);
			$this->load->view('back_end/ast/checklist_user', $datos);
		}


		public function generaPdfAst($data){
			/* return TRUE; */
			foreach($data as $key){
				$detalle = $this->Astmodel->getDataAst($key["hash"]);
				$titulo = "Registro de ast en terreno para técnico : ".$key["tecnico"]."";
				$nombre = url_title(convert_accented_characters($key["id"]."-".$key["rut_tecnico"])).".pdf";
				$data = array('data' => $data , 'titulo' => $titulo , 'detalle' => $detalle);
		    	$html = $this->load->view('back_end/ast/pdf', $data, true);
		    	$dompdf = new DOMPDF(['isRemoteEnabled' => true]);
		    	$dompdf->setPaper('A4', 'portrait');
			    $dompdf->load_html($html);
			    $dompdf->render();
			    $output = $dompdf->output();
			    file_put_contents('./archivos/ast/pdf/'.$nombre.'', $output);
			    return TRUE;
			}
		}

		public function generaPdfAstURL(){
			
			$hash = $this->security->xss_clean(strip_tags($this->input->post("hash")));
			$data = $this->Astmodel->getDataAstCabecera($hash);
			foreach($data as $key){
				$detalle = $this->Astmodel->getDataAst($key["hash"]);
				$titulo = "Registro de ast en terreno para técnico : ".$key["tecnico"]."";
				$nombre = url_title(convert_accented_characters($key["id"]."-".$key["rut_tecnico"])).".pdf";
				$data = array('data' => $data , 'titulo' => $titulo , 'detalle' => $detalle);
		    	$html = $this->load->view('back_end/ast/pdf', $data, true);
		    	$dompdf = new DOMPDF(['isRemoteEnabled' => true]);
		    	$dompdf->setPaper('A4', 'portrait');
			    $dompdf->load_html($html);
			    $dompdf->render();
			    $output = $dompdf->output();
			    $url = './archivos/ast/pdf/'.$nombre;
			    if(file_put_contents($url , $output) !=FALSE){
			    	echo json_encode(array('res'=>"ok" , 'url' => $url));exit;
			    }else{
			    	echo json_encode(array('res'=>"error" , 'msg' => "Error creando el pdf, intente nuevamente."));exit;
			    }
	 	   }
		}

		public function enviaCorreoIngreso($data){
			return TRUE;
			$prueba = FALSE;
			foreach($data as $key){
				$titulo = "Registro de ast en para técnico : ".$key["tecnico"]." - ".$key["fecha"];
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
			
				$datos = array("data" => $data, "titulo" => $titulo);
				$html = $this->load->view('back_end/ast/correo',$datos,TRUE);

				if($prueba){
					$para = array("ricardo.hernandez@km-telecomunicaciones.cl","soporteplataforma@xr3t.cl");
					$copias = array("ricardo.hernandez@km-t.cl");
					$this->email->from("reportes@xr3t.cl","Reporte plataforma XR3");

				}else{
					$para = array();
					$para[] = $key["correo_auditor_empresa"]!="" ? $key["correo_auditor_empresa"] : $key["correo_auditor_personal"];
					$para[] = $key["correo_jefe_empresa"]!="" ? $key["correo_jefe_empresa"] : $key["correo_jefe_personal"];
					$copias = array("roberto.segovia@xr3.cl","cristian.cortes@xr3.cl");
					$this->email->from("reportes@xr3t.cl","Reporte plataforma XR3");
				}

				$this->email->to($para);
				$this->email->cc($copias);
				//$this->email->bcc("ricardo.hernandez@km-telecomunicaciones.cl");
				$this->email->subject($titulo);
				$this->email->message($html); 
				$nombre = url_title(convert_accented_characters($key["id"]."-".$key["rut_tecnico"])).".pdf";
				$this->email->attach(base_url()."archivos/ast/pdf/".$nombre);
				$resp=$this->email->send();

				if ($resp) {
					return TRUE;
				}else{
					print_r($this->email->print_debugger());
					return FALSE;
				}
			}
		}

		public function enviaCorreoFallo($hash,$item){
			return TRUE;
			$data = $this->Astmodel->getDataItemChecklist($hash,$item);
			$prueba = FALSE;
			foreach($data as $key){
				$titulo = "Fallo AST : Tecnico ".$key["tecnico"]." , Descripción ".$key["descripcion"];
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
			
				$datos = array("data" => $data, "titulo" => $titulo);
				$html = $this->load->view('back_end/ast/correo_fallos',$datos,TRUE);

				if($prueba){
					$para = array("ricardo.hernandez@km-telecomunicaciones.cl","soporteplataforma@xr3t.cl");
					$copias = array("ricardo.hernandez@km-t.cl","ricardo.hernandez@splice.cl");
					$this->email->from("reportes@xr3t.cl","Reporte plataforma XR3");

				}else{
					$para = array();
					$para[] = $key["correo_auditor_empresa"]!="" ? $key["correo_auditor_empresa"] : $key["correo_auditor_personal"];
					$para[] = $key["correo_jefe_empresa"]!="" ? $key["correo_jefe_empresa"] : $key["correo_jefe_personal"];
					$copias = array("roberto.segovia@xr3.cl","cristian.cortes@xr3.cl");
					$this->email->from("reportes@xr3t.cl","Reporte plataforma XR3");
				}

				$this->email->to($para);
				$this->email->cc($copias);
				//$this->email->bcc(array("ricardo.hernandez@km-telecomunicaciones.cl","soporteplataforma@xr3t.cl"));
				$this->email->subject($titulo);
				$this->email->message($html); 
				$resp=$this->email->send();

				if ($resp) {
					return TRUE;
				}else{
					print_r($this->email->print_debugger());
					return FALSE;
				}
			}
		}

		public function eliminaAst(){
			$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
			
		    if($this->Astmodel->eliminaAst($hash)){
		      echo json_encode(array("res" => "ok" , "msg" => "Registro eliminado correctamente."));
		    }else{
		      echo json_encode(array("res" => "error" , "msg" => "Problemas eliminando el registro, intente nuevamente."));
		    }
		}
		
		public function excel_ast(){
			$desde=$this->uri->segment(2);
			$hasta=$this->uri->segment(3);
			$tecnico=$this->uri->segment(4);
			
			$desde=date("Y-m-d",strtotime($desde));
			$hasta=date("Y-m-d",strtotime($hasta));
			$data=$this->Astmodel->listaAstDetalle($desde,$hasta,$tecnico);

			if(!$data){
				return FALSE;
			}else{

				$nombre="reporte-ast-".date("d-m-Y",strtotime($desde))."-".date("d-m-Y",strtotime($hasta)).".xls";
				header("Content-type: application/vnd.ms-excel;  charset=utf-8");
				header("Content-Disposition: attachment; filename=$nombre");
				?>
				<style type="text/css">
				.head{font-size:13px;height: 30px; background-color:#32477C;color:#fff; font-weight:bold;padding:10px;margin:10px;vertical-align:middle;}
				td{font-size:12px;text-align:center;   vertical-align:middle;}
				
				</style>
				<h3>Reporte AST <?php echo date("d-m-Y",strtotime($desde)); ?> - <?php echo date("d-m-Y",strtotime($hasta)); ?></h3>
					<table align='center' border="1"> 
				        <tr style="background-color:#F9F9F9">
			                <th class="head">Auditor</th> 
				            <th class="head">Fecha</th>   
				            <th class="head">T&eacute;cnico</th>   
				            <th class="head">T&eacute;cnico Zona</th>   
				            <th class="head">T&eacute;cnico Comuna</th>   
				            <th class="head">Tipo</th>   
				            <th class="head">Item</th>   
				            <th class="head">Estado</th>   
				           <!--  <th class="head">Observaci&oacute;n</th>    -->
				        </tr>
				        </thead>	
						<tbody>
				        <?php 
				        	if($data !=FALSE){
					      		foreach($data as $d){
				      			?>
				      			 <tr>
									 <td><?php echo utf8_decode($d["auditor"]); ?></td>
									 <td><?php echo utf8_decode($d["fecha"]); ?></td>
									 <td><?php echo utf8_decode($d["tecnico"]); ?></td>
									 <td><?php echo utf8_decode($d["area"]); ?></td>
									 <td><?php echo utf8_decode($d["proyecto"]); ?></td>
									 <td><?php echo utf8_decode($d["tipo"]); ?></td>
									 <td><?php echo utf8_decode($d["descripcion"]); ?></td>

									 <?php  

									 if($d["id_tipo"]=="2"){

					                  if($d["estado_str"]=="si"){
					                     $opcion = "Controlado";  
					                  }elseif($d["estado_str"]=="no"){
					                     $opcion = "No controlado";  
					                  }else{
					                     $opcion = "No aplica";  
					                  }
					                   
					                }elseif($d["id_tipo"]<>"2"){

					                  if($d["estado_str"]=="si"){
					                     $opcion = "Si";  
					                  }elseif($d["estado_str"]=="no"){
					                     $opcion = "No";  
					                  }else{
					                     $opcion = "No aplica";  
					                  }
					                
					                }

									 ?>
									 <td><?php echo utf8_decode($opcion); ?></td>
									 <!-- <td><?php echo utf8_decode($d["observacion"]); ?></td> -->
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

	/**********MANTENEDOR ACTIVIDADES ************/
		public function vistaMantActividades(){
			$this->visitas("Mantenedor actividades");
			$desde=date('d-m-Y', strtotime('-360 day', strtotime(date("d-m-Y"))));
	    	$hasta=date('d-m-Y');
			$actividades=$this->Astmodel->listaActividades();
			$tipos=$this->Astmodel->listaTipos();
			
			$datos=array(
				'desde' => $desde,	   
		        'hasta' => $hasta,
		        'actividades' => $actividades,
		        'tipos' => $tipos,
		   	);
			$this->load->view('back_end/ast/mantenedor_actividades',$datos);
		}

		public function listaMantActividades(){
			$actividad=$this->security->xss_clean(strip_tags($this->input->get_post("actividad")));
			echo json_encode($this->Astmodel->listaMantActividades($actividad));
		}
					
					
		public function formMantActividades(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();
				$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
				$actividad=$this->security->xss_clean(strip_tags($this->input->post("actividad")));
				$tipo=$this->security->xss_clean(strip_tags($this->input->post("tipo")));
				$descripcion=$this->security->xss_clean(strip_tags($this->input->post("descripcion_mant")));
				$aplica=$this->security->xss_clean(strip_tags($this->input->post("aplica")));

				if ($this->form_validation->run("formMantActividades") == FALSE){
					echo json_encode(array('res'=>"error", 'msg' => strip_tags(validation_errors())));exit;
				}else{	

					if($hash==""){

						$data=array(
							"id_actividad"=>$actividad,
							"tipo"=>$tipo,
							"descripcion"=>$descripcion,
							"aplica"=>$aplica);	

		     			if($this->Astmodel->formMantActividades($data)){
							echo json_encode(array('res'=>"ok", 'hash' =>sha1($id), 'msg' => OK_MSG));exit;
						}else{
							echo json_encode(array('res'=>"error", 'hash' =>$hash, 'msg' => "Problemas enviado el correo, intente nuevamente."));exit;
						}

					}else{

						$data_mod=array(
							/*"id_actividad"=>$actividad,
							"tipo"=>$tipo,*/
							"descripcion"=>$descripcion,
							"aplica"=>$aplica);	

		     			if($this->Astmodel->actualizarMantActividades($hash,$data_mod)){
							echo json_encode(array('res'=>"ok", 'hash' =>$hash, 'msg' => MOD_MSG));exit;
						}else{
							echo json_encode(array('res'=>"error", 'hash' =>$hash, 'msg' => "Problemas enviado el correo, intente nuevamente."));exit;
						}

					}
	    		}	
			}
		}	

		public function getDataMantActividades(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();
				$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
				$data=$this->Astmodel->getDataMantActividades($hash);

				if($data){
					echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
				}else{
					echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
				}	
			}else{
				exit('No direct script access allowed');
			}
		}
		
		
		public function eliminaformMantActividades(){
			$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
			
		    if($this->Astmodel->eliminaformMantActividades($hash)){
		      echo json_encode(array("res" => "ok" , "msg" => "Registro eliminado correctamente."));
		    }else{
		      echo json_encode(array("res" => "error" , "msg" => "Problemas eliminando el registro, intente nuevamente."));
		    }
		}

		public function llenarMant(){
			$array = $this->Astmodel->getDataMantAct();

				for ($i=2; $i <=6 ; $i++){ 
					foreach($array as $key){
						$data = array("id_actividad"=>$i,
							"tipo"=>$key["tipo"],
							"descripcion"=>$key["descripcion"],
							"aplica"=>"si");

						$this->Astmodel->insertarMant($data);
					}
				}	
		}
	
	/************GRAFICOS*****************/

		public function vistaGraficosAst(){
			$this->visitas("Graficos");

			if($this->input->is_ajax_request()){
				$fecha_anio_atras=date('Y-m-d', strtotime('-31 day', strtotime(date("d-m-Y"))));
				$fecha_hoy=date('Y-m-d');

				$tecnicos = $this->Astmodel->listaTecnicos();
				$zonas = $this->Astmodel->listaZonas();
				$comunas = $this->Astmodel->listaProyectos();
				$items = $this->Astmodel->listaItems();

				$datos=array(	
					'desde' => $fecha_anio_atras,	   
					'hasta' => $fecha_hoy,
					'tecnicos' => $tecnicos,
					'zonas' => $zonas,
					'comunas' => $comunas,
					'items' => $items,
				);
				$this->load->view('back_end/ast/graficos',$datos);
			}
		}

		public function graficoAstTecnico(){
			$tecnico = $this->security->xss_clean(strip_tags($this->input->post("tecnico_gmt")));
			$zona = $this->security->xss_clean(strip_tags($this->input->post("zona_gmt")));
			$comuna = $this->security->xss_clean(strip_tags($this->input->post("comuna_gmt")));
			echo json_encode($this->Astmodel->graficoAstTecnico($tecnico,$zona,$comuna));
		}

		public function graficoAstDetalleTecnico(){
			$tecnico = $this->security->xss_clean(strip_tags($this->input->post("tecnico_gmt")));
			$zona = $this->security->xss_clean(strip_tags($this->input->post("zona_gmt")));
			$comuna = $this->security->xss_clean(strip_tags($this->input->post("comuna_gmt")));
			echo json_encode($this->Astmodel->graficoAstDetalleTecnico($tecnico,$zona,$comuna));
		}

		public function graficoTotalTecnicos(){
			$desde = $this->security->xss_clean(strip_tags($this->input->post("desde")));
			$hasta = $this->security->xss_clean(strip_tags($this->input->post("hasta")));
			$tecnico = $this->security->xss_clean(strip_tags($this->input->post("tecnico_gmt")));
			$zona = $this->security->xss_clean(strip_tags($this->input->post("zona_gmt")));
			$comuna = $this->security->xss_clean(strip_tags($this->input->post("comuna_gmt")));
			echo json_encode($this->Astmodel->graficoTotalTecnicos($tecnico,$zona,$comuna,$desde,$hasta));
		}

		public function graficoTotalItems(){
			$tecnico = $this->security->xss_clean(strip_tags($this->input->post("tecnico_gmt")));
			$zona = $this->security->xss_clean(strip_tags($this->input->post("zona_gmt")));
			$comuna = $this->security->xss_clean(strip_tags($this->input->post("comuna_gmt")));
			$item = $this->security->xss_clean(strip_tags($this->input->post("item_gmt")));
			echo json_encode($this->Astmodel->graficoTotalItems($tecnico,$zona,$comuna,$item));
		}
		
		
		public function excel_items_ast(){
			$tecnico_gmt = $this->uri->segment(2);
			$zona_gmt = $this->uri->segment(3);
			$comuna_gmt = $this->uri->segment(4);
			$item_gmt = urldecode($this->uri->segment(5));
			
			$tecnico_gmt = ($tecnico_gmt == "-") ? "" : $tecnico_gmt;
			$zona_gmt = ($zona_gmt == "-") ? "" : $zona_gmt;
			$comuna_gmt = ($comuna_gmt == "-") ? "" : $comuna_gmt;
			$item_gmt = ($item_gmt == "-") ? "" : $item_gmt;

			$data = $this->Astmodel->dataItemsEstado($tecnico_gmt,$zona_gmt,$comuna_gmt,$item_gmt);

			if(!$data){
				return FALSE;
			}else{

				$nombre="reporte-items-ast-".date("d-m-Y").".xls";
				header("Content-type: application/vnd.ms-excel;  charset=utf-8");
				header("Content-Disposition: attachment; filename=$nombre");
				?>
				<style type="text/css">
					.head{font-size:13px;height: 30px; background-color:#32477C;color:#fff; font-weight:bold;padding:10px;margin:10px;vertical-align:middle;}
					.verde{background-color:#107C41;color:#fff;}
					.rojo{background-color:#A70A00;color:#fff;}
					td{font-size:12px;text-align:left;   vertical-align:middle;.}
				</style>
				<h3>Reporte Items AST <?php echo date("d-m-Y"); ?></h3>
					<table align='center' border="1"> 
				        <tr style="background-color:#F9F9F9">
			                <th class="head">Descripcion</th> 
				            <th class="head">OK</th>   
				            <th class="head">NO OK</th>   
				        </tr>
				        </thead>	
						<tbody>
				        <?php 
				        	if($data !=FALSE){
					      		foreach($data as $d){
				      			?>
				      			 <tr>
									 <td><?php echo utf8_decode($d["descripcion"]); ?></td>
									 <td class="verde"><?php echo utf8_decode($d["cantidad_ok"]); ?></td>
									 <td class="rojo"><?php echo utf8_decode($d["cantidad_nook"]); ?></td>
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

		public function excel_ast_totales(){
			$tecnico_gmt = $this->uri->segment(2);
			$zona_gmt = $this->uri->segment(3);
			$comuna_gmt = $this->uri->segment(4);
			$desde = urldecode($this->uri->segment(5));
			$hasta = urldecode($this->uri->segment(6));
			
			$tecnico_gmt = ($tecnico_gmt == "-") ? "" : $tecnico_gmt;
			$zona_gmt = ($zona_gmt == "-") ? "" : $zona_gmt;
			$comuna_gmt = ($comuna_gmt == "-") ? "" : $comuna_gmt;
			$desde = ($desde == "-") ? "" : $desde;
			$hasta = ($hasta == "-") ? "" : $hasta;
			$data = $this->Astmodel->graficoAstTotalTecnicos($tecnico_gmt,$zona_gmt,$comuna_gmt,$desde,$hasta);

			if(!$data){
				return FALSE;
			}else{

				$nombre="reporte-ast-totales-".date("d-m-Y").".xls";
				header("Content-type: application/vnd.ms-excel;  charset=utf-8");
				header("Content-Disposition: attachment; filename=$nombre");
				?>
				<style type="text/css">
					.head{font-size:13px;height: 30px; background-color:#32477C;color:#fff; font-weight:bold;padding:10px;margin:10px;vertical-align:middle;}
					.verde{background-color:#107C41;color:#fff;}
					.rojo{background-color:#A70A00;color:#fff;}
					td{font-size:12px;text-align:left;   vertical-align:middle;.}
				</style>
				<h3>AST Totales por t&eacute;cnico</h3>
					<table align='center' border="1"> 
				        <tr style="background-color:#F9F9F9">
							<th class="head">T&eacute;cnico</th> 
							<th class="head">Zona</th> 
							<th class="head">Comuna</th> 
				            <th class="head">Cantidad</th>   
				        </tr>
				        </thead>	
						<tbody>
				        <?php 
				        	if($data !=FALSE){
					      		foreach($data as $d){
				      			?>
				      			 <tr>
								   <td><?php echo utf8_decode($d["tecnico"]); ?></td>
								   <td><?php echo utf8_decode($d["area"]); ?></td>
								   <td><?php echo utf8_decode($d["proyecto"]); ?></td>
								   <td><?php echo utf8_decode($d["cantidad"]); ?></td>
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