<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class checklistFTTH extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if($this->uri->segment("1")==""){
			redirect("inicio");
		}
		$this->load->model("back_end/checklist/Checklistftthmodel");
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
    	$this->Checklistftthmodel->insertarVisita($data);
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

	public function formCargaMasivaChecklistFTTH(){
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
				    	"tipo"=>$data[1],
				    	"descripcion"=>$data[0],
				    	"valor"=>"0",
					);	

				    $this->Checklistftthmodel->formChecklistLista($arr);
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
		        'titulo' => "CheckList FTTH",
		        'contenido' => "checklist/checklist_ftth/inicio",
		        'perfiles' => $this->Iniciomodel->listaPerfiles(),
			);  
			$this->load->view('plantillas/plantilla_back_end',$datos);
		}
		
		public function vistaChecklistFTTH(){
			$this->visitas("Checklist FTTH");
			$fecha_anio_atras=date('d-m-Y', strtotime('-360 day', strtotime(date("d-m-Y"))));
	    	$fecha_hoy=date('d-m-Y');
			$tecnicos=$this->Checklistftthmodel->listaTecnicos();
    		$auditores=$this->Checklistftthmodel->listaAuditores();
    		$comunas=$this->Checklistftthmodel->listaComunas();
    		$checklist=$this->Checklistftthmodel->listaChecklist();

			$datos=array(
				'fecha_anio_atras' => $fecha_anio_atras,	   
		        'fecha_hoy' => $fecha_hoy,
				'tecnicos' => $tecnicos,
		   	    'auditores' => $auditores,
		   	    'comunas' => $comunas,
		   	    'checklist' => $checklist
		   	);
			$this->load->view('back_end/checklist/checklist_ftth/checklist/inicio',$datos);
		}

		public function listaChecklistFTTH(){
			$desde=$this->security->xss_clean(strip_tags($this->input->get_post("desde")));
			$hasta=$this->security->xss_clean(strip_tags($this->input->get_post("hasta")));
			if($desde!=""){$desde=date("Y-m-d",strtotime($desde));}else{$desde="";}
			if($hasta!=""){$hasta=date("Y-m-d",strtotime($hasta));}else{$hasta="";}	
			echo json_encode($this->Checklistftthmodel->listaChecklistFTTH($desde,$hasta));
		}

		public function formChecklistFTTH(){
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

				if ($this->form_validation->run("formChecklistFTTH") == FALSE){
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

						$id=$this->Checklistftthmodel->formChecklistFTTH($data_insert);
						if($id!=FALSE){

							if(!$this->Checklistftthmodel->existeDetalleOTS($id)){
								// echo "string";
								$checklist=$this->Checklistftthmodel->listaChecklist();
								foreach($checklist as $c){
									$data_detalle = array("id_ots" => $id, "id_check" => $c["id"], "estado" => "0" , "observacion" =>"");
									$this->Checklistftthmodel->insertaDetalleOTS($data_detalle);
									$data_detalle=array();
								}

								$index=0;
								foreach($estado as $e=>$value){
									$data_actualizar= array("estado" => $value ,"observacion" => $observacion[$index]);

									$id_detalle=$this->Checklistftthmodel->getIddetalle($id,$e+1);

									$this->Checklistftthmodel->actualizaDetalleOTS($id_detalle,$data_actualizar);
									$index++;	
								}


							}else{

								$index=0;
								foreach($estado as $e=>$value){
									$data_actualizar= array("estado" => $value ,
									 "observacion" => $observacion[$index]);

									$id_detalle=$this->Checklistftthmodel->getIddetalle($id,$e+1);
									$this->Checklistftthmodel->actualizaDetalleOTS($id_detalle,$data_actualizar);
									$index++;	
								}
							}

							if($checkcorreo=="on"){
					    		
					     		//$data_correo=$this->Checklistftthmodel->getDataChecklistFTTH($hash);
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

						$id = $this->Checklistftthmodel->getIdPorHash($hash);

						$data_mod=array("auditor_id"=>$auditor,
							"fecha"=>$fecha,
						    "ultima_actualizacion"=>$ultima_actualizacion,		
						    "n_ot"=>$n_ot,
							"tipo_actividad"=>$tipo_actividad,
							"direccion"=>$direccion,
							"tecnico_id"=>$tecnico);	

						$this->Checklistftthmodel->actualizarOTS($hash,$data_mod);

						$index=0;
						foreach($estado as $e=>$value){
								$data_actualizar= array("estado" => $value , "observacion" => $observacion[$index]);

								$id_detalle=$this->Checklistftthmodel->getIddetalle($id,$e+1);
								$this->Checklistftthmodel->actualizaDetalleOTS($id_detalle,$data_actualizar);
							$index++;	
						}

						if($checkcorreo=="on"){
				    		
				     		//$data_correo=$this->Checklistftthmodel->getDataChecklistFTTH($hash);
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

		public function getDataChecklistFTTH(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();
				$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
				$data=$this->Checklistftthmodel->getDataChecklistFTTH($hash);
			
				if($data){
					echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
				}else{
					echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
				}	
			}else{
				exit('No direct script access allowed');
			}
		}

		public function eliminaChecklistFTTH(){
			$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
		    if($this->Checklistftthmodel->eliminaChecklistFTTH($hash)){
		      echo json_encode(array("res" => "ok" , "msg" => "Registro eliminado correctamente."));
		    }else{
		      echo json_encode(array("res" => "error" , "msg" => "Problemas eliminando el registro, intente nuevamente."));
		    }
		}
		

		public function datosAuditorChecklistFTTH(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();
				$auditor=$this->security->xss_clean(strip_tags($this->input->post("auditor")));
				$data=$this->Checklistftthmodel->datosUsuario($auditor);
			
				if($data){
					echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
				}else{
					echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
				}	
			}else{
				exit('No direct script access allowed');
			}
		}
		
		public function datosTecnicoChecklistFTTH(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();
				$tecnico=$this->security->xss_clean(strip_tags($this->input->post("tecnico")));
				$data=$this->Checklistftthmodel->datosUsuario($tecnico);
			
				if($data){
					echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
				}else{
					echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
				}	
			}else{
				exit('No direct script access allowed');
			}
		}
		
		
		public function excel_checklistFTTH(){
			$desde=$this->uri->segment(2);
			$hasta=$this->uri->segment(3);
			$desde=date("Y-m-d",strtotime($desde));
			$hasta=date("Y-m-d",strtotime($hasta));

			$data=$this->Checklistftthmodel->listaChecklistFTTHDetalle($desde,$hasta);

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
				<h3>Reporte checklist FTTH <?php echo date("d-m-Y",strtotime($desde)); ?> - <?php echo date("d-m-Y",strtotime($hasta)); ?></h3>

					<table align='center' border="1"> 

				        <tr style="background-color:#F9F9F9">
			                <th class="head">Auditor</th> 
				            <th class="head">Auditor cargo</th>   
				            <th class="head">Fecha</th>   
				            <th class="head">T&eacute;cnico</th>   
				            <th class="head">T&eacute;cnico zona</th>   
				            <th class="head">T&eacute;cnico c&oacute;digo</th>   
				            <th class="head">T&eacute;cnico comuna</th>   
				          	
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


			public function vistaGraficosChecklistFTTH(){
				if($this->input->is_ajax_request()){
					$fecha_anio_atras=date('d-m-Y', strtotime('-365 day', strtotime(date("d-m-Y"))));
			    	$fecha_hoy=date('d-m-Y');
			    	// echo "<pre>";
			    	// print_r($this->Checklistftthmodel->dataEstadosChecklist());
					$datos=array(	
						'fecha_anio_atras' => $fecha_anio_atras,	   
				        'fecha_hoy' => $fecha_hoy,
				   	);

					$this->load->view('back_end/checklist/checklist_ftth/graficos/inicio',$datos);
				}
			}

			public function dataEstadosChecklistFTTH(){
				echo json_encode($this->Checklistftthmodel->dataEstadosChecklistFTTH());
			}

			public function dataTecnicosChecklistFTTH(){
				echo json_encode($this->Checklistftthmodel->dataTecnicosChecklistFTTH());
			}



	

}