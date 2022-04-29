<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class checklistHFC extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if($this->uri->segment("1")==""){
			redirect("inicio");
		}
		$this->load->model("back_end/Checklisthfcmodel");
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

	

	/*********REGISTRO OTS************/
		
	    public function index(){
	    	$this->acceso();
    	    $datos = array(
		        'titulo' => "CheckList HFC",
		        'contenido' => "checklist_hfc/inicio",
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

			$datos=array(
				'fecha_anio_atras' => $fecha_anio_atras,	   
		        'fecha_hoy' => $fecha_hoy,
				'tecnicos' => $tecnicos,
		   	    'auditores' => $auditores,
		   	    'comunas' => $comunas,
		   	    'checklist' => $checklist
		   	);
			$this->load->view('back_end/checklist_hfc/checklist/inicio',$datos);
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

				$estado=$this->input->post("estado");
				$observacion=$this->input->post("observacion");

				if ($this->form_validation->run("formChecklistHFC") == FALSE){
					echo json_encode(array('res'=>"error", 'msg' => strip_tags(validation_errors())));exit;
				}else{	

					$data_insert=array("auditor_id"=>$auditor,
						"fecha"=>$fecha,
						"ultima_actualizacion"=>$ultima_actualizacion,
						"tecnico_id"=>$tecnico);	

					if($hash==""){

						$id=$this->Checklisthfcmodel->formChecklistHFC($data_insert);
						if($id!=FALSE){

							if(!$this->Checklisthfcmodel->existeDetalleOTS($id)){
								// echo "string";
								$checklist=$this->Checklisthfcmodel->listaChecklist();
								foreach($checklist as $c){
									$data_detalle = array("id_ots" => $id, "id_check" => $c["id"], "estado" => "0" , "observacion" =>"");
									$this->Checklisthfcmodel->insertaDetalleOTS($data_detalle);
									$data_detalle=array();
								}

								$index=0;
								foreach($estado as $e=>$value){
									$data_actualizar= array("estado" => $value ,"observacion" => $observacion[$index]);

									$id_detalle=$this->Checklisthfcmodel->getIddetalle($id,$e+1);

									$this->Checklisthfcmodel->actualizaDetalleOTS($id_detalle,$data_actualizar);
									$index++;	
								}


							}else{

								$index=0;
								foreach($estado as $e=>$value){
									$data_actualizar= array("estado" => $value ,
									 "observacion" => $observacion[$index]);

									$id_detalle=$this->Checklisthfcmodel->getIddetalle($id,$e+1);
									$this->Checklisthfcmodel->actualizaDetalleOTS($id_detalle,$data_actualizar);
									$index++;	
								}
							}

							if($checkcorreo=="on"){
					    		
					     		//$data_correo=$this->Checklisthfcmodel->getDataChecklistHFC($hash);
								// if($this->enviaCorreoIngreso($data_correo)){
									echo json_encode(array('res'=>"ok", 'hash' =>sha1($id), 'msg' => MOD_MSG));exit;
								// }

							}else{
								 echo json_encode(array('res'=>"ok", 'hash' =>sha1($id), 'msg' => MOD_MSG));exit;
							}

						}else{
							echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
						}
				   
					}else{

						$id = $this->Checklisthfcmodel->getIdPorHash($hash);

						$data_mod=array("auditor_id"=>$auditor,
							"fecha"=>$fecha,
						    "ultima_actualizacion"=>$ultima_actualizacion,		
							"tecnico_id"=>$tecnico);	

						$this->Checklisthfcmodel->actualizarOTS($hash,$data_mod);

						$index=0;
						foreach($estado as $e=>$value){
								$data_actualizar= array("estado" => $value , "observacion" => $observacion[$index]);

								$id_detalle=$this->Checklisthfcmodel->getIddetalle($id,$e+1);
								$this->Checklisthfcmodel->actualizaDetalleOTS($id_detalle,$data_actualizar);
							$index++;	
						}

						if($checkcorreo=="on"){
				    		
				     		//$data_correo=$this->Checklisthfcmodel->getDataChecklistHFC($hash);
							// if($this->enviaCorreoIngreso($data_correo)){
								echo json_encode(array('res'=>"ok", 'hash' =>$hash, 'msg' => MOD_MSG));exit;
							// }

						}else{
							 echo json_encode(array('res'=>"ok", 'hash' =>$hash, 'msg' => MOD_MSG));exit;
						}
					}
	    		}	
			}
		}

		public function getDataChecklistHFC(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();
				$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
				$data=$this->Checklisthfcmodel->getDataChecklistHFC($hash);
			
				if($data){
					echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
				}else{
					echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
				}	
			}else{
				exit('No direct script access allowed');
			}
		}

		public function eliminaChecklistHFC(){
			$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
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

				$nombre="reporte-ots-".date("d-m-Y",strtotime($desde))."-".date("d-m-Y",strtotime($hasta)).".xls";
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
				            <th class="head">T&eacute;cnico comuna</th>   
				          
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

									 <td><?php echo utf8_decode($d["tipo"]); ?></td>
									 <td><?php echo utf8_decode($d["descripcion"]); ?></td>
									 <td><?php echo utf8_decode($d["estado"]); ?></td>
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

					$this->load->view('back_end/checklist_hfc/graficos/inicio',$datos);
				}
			}

			public function dataEstadosChecklistHFC(){
				echo json_encode($this->Checklisthfcmodel->dataEstadosChecklistHFC());
			}

			public function dataTecnicosChecklistHFC(){
				echo json_encode($this->Checklisthfcmodel->dataTecnicosChecklistHFC());
			}



	

}