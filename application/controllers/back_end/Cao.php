<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cao extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if($this->uri->segment("1")==""){
			redirect("inicio");
		}
		$this->load->model("back_end/Caomodel");
		$this->load->model("back_end/Iniciomodel");
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
    	$this->Caomodel->insertarVisita($data);
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

	public function index(){
		$this->visitas("CAO");
    	$this->acceso();
	    $datos = array(
	        'titulo' => "CAO (Control de asistencia operacional)",
	        'contenido' => "cao/inicio",
	        'perfiles' => $this->Caomodel->listaPerfiles(),
		);  
		$this->load->view('plantillas/plantilla_back_end',$datos);
		
	}

    public function vistaTurnos(){
		if($this->input->is_ajax_request()){
			$desde=date('Y-m-d', strtotime('-10 day', strtotime(date("d-m-Y"))));
			$hasta=date('Y-m-d', strtotime('+30 day', strtotime(date("d-m-Y"))));
	    	/*$hasta=date('Y-m-d');*/

			$datos=array(	
				'desde' => $desde,
		        'hasta' => $hasta,
		        'perfiles' => $this->Caomodel->listaPerfiles(),
		        'nivelesTecnico' => $this->Caomodel->listaNivelesTecnicos(),
		        'proyectos' => $this->Caomodel->listaProyectos(),
		        'jefes' => $this->Caomodel->listaJefes(),
		        'turnos' => $this->Caomodel->listaTurnosTipos(),
		    );
			$this->load->view('back_end/cao/turnos',$datos);
		}
	}

	public function getCabecerasTurnos(){
		$data=json_decode(file_get_contents('php://input'),1);
		$desde=$data["desde"];
		$hasta=$data["hasta"];
		if($desde!=""){$desde=date("Y-m-d",strtotime($desde));}else{$desde="";}
		if($hasta!=""){$hasta=date("Y-m-d",strtotime($hasta));}else{$hasta="";}	

	 	echo json_encode(array(
  	    	"data" =>$this->Caomodel->getCabecerasTurnos($desde,$hasta)
  	    ));
	}

	public function listaTurnos(){
		$desde=$this->input->get_post("desde");
		$hasta=$this->input->get_post("hasta");
		$jefe=$this->input->get_post("jefe");
		$trabajador=$this->input->get_post("trabajador");
		$nivel_tecnico=$this->input->get_post("nivel_tecnico");
		if($desde!=""){$desde=date("Y-m-d",strtotime($desde));}else{$desde="";}
		if($hasta!=""){$hasta=date("Y-m-d",strtotime($hasta));}else{$hasta="";}	
		echo json_encode(array("data" =>$this->Caomodel->listaTurnos($desde,$hasta,$jefe,$trabajador,$nivel_tecnico,0)));exit;
	}

	public function listaTrabajadoresTurnos(){
		$jefe=$this->security->xss_clean(strip_tags($this->input->get_post("jefe")));
 	    echo $this->Caomodel->listaTrabajadoresTurnos($jefe);exit;
	}

	public function listaUsuarios(){
		$estado=$this->security->xss_clean(strip_tags($this->input->get_post("estado")));
		echo json_encode($this->Caomodel->listaUsuarios($estado));
	}

	public function formTurnos(){
		if($this->input->is_ajax_request()){
			$this->checkLogin();
			$hash_turnos = $this->security->xss_clean(strip_tags($this->input->post("hash_turnos")));
			$rut_tecnico = $this->security->xss_clean(strip_tags($this->input->post("trabajador")));
			$fecha = $this->security->xss_clean(strip_tags($this->input->post("fecha")));
			$fecha2 = $this->security->xss_clean(strip_tags($this->input->post("fecha2")));
			$turno = $this->security->xss_clean(strip_tags($this->input->post("turno")));
			$id_digitador = $this->session->userdata("id");
			$ultima_actualizacion = date("Y-m-d G:i:s")." | ".$this->session->userdata("nombres")." ".$this->session->userdata("apellidos");

			if ($this->form_validation->run("formTurnos") == FALSE){
				echo json_encode(array('res'=>"error", 'msg' => strip_tags(validation_errors())));exit;
			}else{		

				if($hash_turnos==""){

					if($fecha2!=""){

						$fechas = arrayRangoFechas($fecha,$fecha2,"+1 day", "Y-m-d");

						foreach($fechas as $fecha){
							if($this->Caomodel->existeTurno($rut_tecnico,$fecha)){
								$this->Caomodel->eliminarTurnosPorFecha($rut_tecnico,$fecha);
							}
							
							$data_t=array("rut_tecnico" => $rut_tecnico, 
								"id_turno" => $turno,
								"fecha" => $fecha,
								"id_digitador" => $id_digitador,
								"ultima_actualizacion" => $ultima_actualizacion);
							
							$this->Caomodel->formTurnos($data_t);
							$data_t =array();
						}

						echo json_encode(array('res'=>"ok", 'msg' => OK_MSG));exit;	
					}else{
						
						if($this->Caomodel->existeTurno($rut_tecnico,$fecha)){
							echo json_encode(array('res'=>"error", 'msg' => "Ya existe registro para este técnico y fecha."));exit;
						}

						$data=array("rut_tecnico"=>$rut_tecnico,
							"id_turno"=>$turno,
							"fecha"=>$fecha,
							"id_digitador"=>$id_digitador,
							"ultima_actualizacion"=>$ultima_actualizacion
						);	

						$id=$this->Caomodel->formTurnos($data);
						if($id!=FALSE){
							echo json_encode(array('res'=>"ok", 'msg' => OK_MSG));exit;
						}else{
							echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
						}

					}

					
				}else{

					$data=array("rut_tecnico"=>$rut_tecnico,
						"id_turno"=>$turno,
						"fecha"=>$fecha,
						"id_digitador"=>$id_digitador,
						"ultima_actualizacion"=>$ultima_actualizacion
					);	

					if($this->Caomodel->existeTurnoMod($hash_turnos,$rut_tecnico,$fecha)){
						echo json_encode(array('res'=>"error", 'msg' => "Ya existe registro para este técnico y fecha."));exit;
					}

					if($this->Caomodel->actualizarTurnos($hash_turnos,$data)){
						echo json_encode(array('res'=>"ok", 'msg' => MOD_MSG));exit;
					}else{
						echo json_encode(array('res'=>"error",  'msg' => ERROR_MSG));exit;
					}
				}
    		}	
		}
	}

	public function getDataTurnos(){
		if($this->input->is_ajax_request()){
			$this->checkLogin();
			$hash_turnos=$this->security->xss_clean(strip_tags($this->input->post("hash_turnos")));
			$data=$this->Caomodel->getDataTurnos($hash_turnos);

			if($data){
				echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
			}else{
				echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
			}	

		}else{
			exit('No direct script access allowed');
		}
	}

	public function eliminarTurnos(){
		$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
	    if($this->Caomodel->eliminarTurnos($hash)){
	      echo json_encode(array("res" => "ok" , "msg" => "Registro eliminado correctamente."));
	    }else{
	      echo json_encode(array("res" => "error" , "msg" => "Problemas eliminando el registro, intente nuevamente."));
	    }
	}


	public function excel_turnos(){
		$desde=$this->uri->segment(2);
		$hasta=$this->uri->segment(3);
		$jefe=$this->uri->segment(4);
		$nivel_tecnico=$this->uri->segment(5);
		$trabajador=$this->uri->segment(6);

		if($desde!=""){$desde=date("Y-m-d",strtotime($desde));}else{$desde="";}
		if($hasta!=""){$hasta=date("Y-m-d",strtotime($hasta));}else{$hasta="";}
		if($jefe=="-"){$jefe="";}
		if($nivel_tecnico=="-"){$nivel_tecnico="";}
		if($trabajador=="-"){$trabajador="";}

	 	$cabecera= $this->Caomodel->getCabecerasTurnos($desde,$hasta);
	 	$data = $this->Caomodel->listaTurnos($desde,$hasta,$jefe,$trabajador,$nivel_tecnico,1);

		if(!$data){
			return FALSE;
		}else{

			$nombre="reporte-turnos.xls";
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

			<h3>Reporte turnos</h3>
			<table align='center' border="1"> 
				<thead>
			       <tr style="background-color:#F9F9F9">
		                <?php 
		              	foreach($cabecera as $c){
		              		$dia = str_split($c);

		              		if($dia[0]=="D"){
		              			$clase="finde";
		              		}else{
		              			$clase="head";
		              		}

		              		?>
		         		    <th class="<?php echo $clase; ?>"><?php echo utf8_decode($c); ?></th>  
		              		<?php
		              	}
		              ?>
			        </tr>
		        </thead>	
				<tbody>
	                <?php 
		                foreach ($data as $key) {
		                	echo "<tr>";
		                	foreach ($key as $k) {
			                	?> 
			                		<td class=""><?php echo utf8_decode($k); ?></td>  
			                		
			                	<?php
			                }
			                echo "</tr>";
		           		}
	                ?>
		        </tbody>
	        </table>
	    <?php
		}
	}


	/*****LICENCIAS*******/

		public function vistaLicencias(){
			if($this->input->is_ajax_request()){
				$fecha_anio_atras=date('d-m-Y', strtotime('-365 day', strtotime(date("d-m-Y"))));
		    	$fecha_hoy=date('d-m-Y');
			    $datos=array('tipos' => $this->Caomodel->listaTiposLicencias());
				$this->load->view('back_end/cao/licencias',$datos);
			}
		}

		public function getLicenciasList(){
			$inactivos=$this->security->xss_clean(strip_tags($this->input->get_post("inactivos")));
			echo json_encode($this->Caomodel->getLicenciasList($inactivos));
		}

		public function formIngresoLicencias(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();	
				$hash=$this->security->xss_clean(strip_tags($this->input->post("hash_id")));
				$id_usuario=$this->security->xss_clean(strip_tags($this->input->post("usuarios")));
				$fecha_inicio=$this->security->xss_clean(strip_tags($this->input->post("fecha_inicio")));
		   		if($fecha_inicio!=""){$fecha_inicio=date("Y-m-d",strtotime($fecha_inicio));}else{$fecha_inicio="";}	
		   		$fecha_termino=$this->security->xss_clean(strip_tags($this->input->post("fecha_termino")));
		   		if($fecha_termino!=""){$fecha_termino=date("Y-m-d",strtotime($fecha_termino));}else{$fecha_termino="";}	
				$tipo_licencia=$this->security->xss_clean(strip_tags($this->input->post("tipo_licencia")));
				$id_digitador=$this->session->userdata("id");
				$ultima_actualizacion=date("Y-m-d G:i:s")." | ".$this->session->userdata("nombre_completo");
		   		$adjunto=@$_FILES["userfile"]["name"];
			    $checkcorreo=$this->security->xss_clean(strip_tags($this->input->post("checkcorreo")));

				if(!empty($fecha_inicio) and !empty($fecha_termino)){
					if($fecha_inicio>$fecha_termino){
						echo json_encode(array('res'=>"error",'msg' => "La fecha de término debe ser mayor a la de inicio."));exit;
					}else{
						$start = strtotime($fecha_inicio);
						$end = strtotime($fecha_termino);
						$dias = ceil(abs($end - $start) / 86400)+1;
					}
				
				}else{
					$dias=0;
				}

	   			if ($this->form_validation->run("formIngresoLicencia") == FALSE){
					echo json_encode(array('res'=>"error", 'msg' => strip_tags(validation_errors())));exit;
				}else{	

					$data = array(
		    		 	'id_usuario' => $id_usuario,
		    		 	'id_tipo_licencia' => $tipo_licencia,
		    		 	'fecha_inicio' => $fecha_inicio,
		    		 	'fecha_termino' => $fecha_termino,
		    		 	'dias' => $dias,
		    		 	"ultima_actualizacion" => $ultima_actualizacion);

			    	if($hash==FALSE){

			    		$data["fecha_registro"] = date("Y-m-d");
			    		$data["id_digitador"] = $id_digitador;
			    		
			    		if($adjunto!=""){
							$nombre=$this->procesaArchivoLicencias($_FILES["userfile"],$id_usuario."_".date("ymdHis"),1);
							$data["adjunto"]=$nombre;
						}else{
							$data["adjunto"]="";
						}

						$fechas = arrayRangoFechas($fecha_inicio,$fecha_termino,"+1 day", "Y-m-d");
						$rut_tecnico = $this->Caomodel->getRutPorId($id_usuario);

						foreach($fechas as $fecha){
							if($this->Caomodel->existeTurno($rut_tecnico,$fecha)){
								$this->Caomodel->eliminarTurnosPorFecha($rut_tecnico,$fecha);
								//echo json_encode(array('res'=>"error", 'msg' => "Ya existe registro para este técnico y fecha."));exit;
							}
							
							$data_t=array("rut_tecnico" => $rut_tecnico, 
								"id_turno" => 1 ,
								"fecha" => $fecha,
								"id_digitador" => $id_digitador,
								"ultima_actualizacion" => $ultima_actualizacion);

							$this->Caomodel->formTurnos($data_t);
							$data_t =array();
						}

						$insert_id=$this->Caomodel->formIngresoLicencias($data);
						if($insert_id!=FALSE){
							echo json_encode(array('res'=>"ok",  'msg' => OK_MSG));exit;
						}else{
							echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
						}


					}else{

						if($adjunto!=""){
							$nombre=$this->procesaArchivoLicencias($_FILES["userfile"],$id_usuario."_".date("ymdHis"),1);
							$data["adjunto"]=$nombre;
						}

						$fechas = arrayRangoFechas($fecha_inicio,$fecha_termino,"+1 day", "Y-m-d");
						$rut_tecnico = $this->Caomodel->getRutPorId($id_usuario);

						foreach($fechas as $fecha){

							if($this->Caomodel->existeTurno($rut_tecnico,$fecha)){
								$this->Caomodel->eliminarTurnosPorFecha($rut_tecnico,$fecha);
							}
							
							$data_actual =$this->Caomodel->getDataRegistroLicencias($hash);
							foreach($data_actual as $d){
								$fechas_actual = arrayRangoFechas($d["fecha_inicio"],$d["fecha_termino"],"+1 day", "Y-m-d");
								foreach($fechas_actual as $f){
									if($this->Caomodel->existeTurno($rut_tecnico,$f)){
										$this->Caomodel->eliminarTurnosPorFecha($rut_tecnico,$f);
									}
								}
							}

							$data_t=array("rut_tecnico" => $rut_tecnico, 
								"id_turno" => 1 ,
								"fecha" => $fecha,
								"id_digitador" => $id_digitador,
								"ultima_actualizacion" => $ultima_actualizacion);

							$this->Caomodel->formTurnos($data_t);
							$data_t =array();
						}

						if($this->Caomodel->formActualizarLicencias($hash,$data)){
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

		
		public function procesaArchivoLicencias($file,$titulo){
				$path = $file['name'];
				$ext = pathinfo($path, PATHINFO_EXTENSION);
				$archivo=strtolower(url_title(convert_accented_characters($titulo.rand(10, 1000)))).".".$ext;
				$config['upload_path'] = './archivos_licencias';
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

		public function eliminaLicencias(){
			$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
			$data=$this->Caomodel->getDataRegistroLicencias($hash);

			foreach($data as $d){

				$fechas = arrayRangoFechas($d["fecha_inicio"],$d["fecha_termino"],"+1 day", "Y-m-d");
				$cantidad_dias = 0;

				foreach($fechas as $fecha){
					$rut_tecnico = $d["rut"];
					$fecha_inicio = date('Y-m-d', strtotime($fecha));

					if($this->Caomodel->existeTurno($rut_tecnico,$fecha_inicio)){
						$cantidad_dias++;
						$this->Caomodel->eliminarTurnosPorFecha($rut_tecnico,$fecha_inicio);
					}
				}

				$this->Caomodel->eliminaLicencias($hash);

				if($cantidad_dias>0){
					echo json_encode(array("res" => "ok" , "msg" => "Registro eliminado correctamente," .$cantidad_dias. " registros de turnos eliminados."));
				}else{	
					echo json_encode(array("res" => "ok" , "msg" => "Registro eliminado correctamente."));
				}
				
			}
		}

		public function getDataRegistroLicencias(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();	
				$hash=$this->security->xss_clean(strip_tags($this->input->post("hash_id")));
				$data=$this->Caomodel->getDataRegistroLicencias($hash);
				if($data){
					echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
				}else{
					echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
				}	
			}else{
				exit('No direct script access allowed');
			}
		}


	/******VACACIONES*********/

		public function vistaVacaciones(){
			if($this->input->is_ajax_request()){
				$fecha_anio_atras=date('d-m-Y', strtotime('-365 day', strtotime(date("d-m-Y"))));
		    	$fecha_hoy=date('d-m-Y');
			    $datos=array();
				$this->load->view('back_end/cao/vacaciones',$datos);
			}
		}

		public function getVacacionesList(){
			$inactivos=$this->security->xss_clean(strip_tags($this->input->get_post("inactivos")));
			echo json_encode($this->Caomodel->getVacacionesList($inactivos));
		}
		
		public function formIngresoVacaciones(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();	
				$hash=$this->security->xss_clean(strip_tags($this->input->post("hash_id")));
				$id_usuario=$this->security->xss_clean(strip_tags($this->input->post("usuarios")));
				$fecha_inicio=$this->security->xss_clean(strip_tags($this->input->post("fecha_inicio")));
		   		if($fecha_inicio!=""){$fecha_inicio=date("Y-m-d",strtotime($fecha_inicio));}else{$fecha_inicio="";}	
		   		$fecha_termino=$this->security->xss_clean(strip_tags($this->input->post("fecha_termino")));
		   		if($fecha_termino!=""){$fecha_termino=date("Y-m-d",strtotime($fecha_termino));}else{$fecha_termino="";}	
				$id_digitador=$this->session->userdata("id");
				$ultima_actualizacion=date("Y-m-d G:i:s")." | ".$this->session->userdata("nombre_completo");
		   		$adjunto=@$_FILES["userfile"]["name"];

				if(!empty($fecha_inicio) and !empty($fecha_termino)){
					if($fecha_inicio>$fecha_termino){
						echo json_encode(array('res'=>"error",'msg' => "La fecha de término debe ser mayor a la de inicio."));exit;
					}else{
						$start = strtotime($fecha_inicio);
						$end = strtotime($fecha_termino);

						$dias = ceil(abs($end - $start) / 86400)+1;
					}
				
				}else{
					$dias=0;
				}

	   			if ($this->form_validation->run("formIngresoVacaciones") == FALSE){
					echo json_encode(array('res'=>"error", 'msg' => strip_tags(validation_errors())));exit;
				}else{	

					$data = array(
		    		 	'id_usuario' => $id_usuario,
		    		 	'fecha_inicio' => $fecha_inicio,
		    		 	'fecha_termino' => $fecha_termino,
		    		 	'dias' => $dias,
		    		 	"ultima_actualizacion" => $ultima_actualizacion);

			    	if($hash==FALSE){

			    		$data["fecha_registro"] = date("Y-m-d");
			    		$data["id_digitador"] = $id_digitador;
			    		
			    		if($adjunto!=""){
							$nombre=$this->procesaArchivoVacaciones($_FILES["userfile"],$id_usuario."_".date("ymdHis"),1);
							$data["adjunto"]=$nombre;
						}else{
							$data["adjunto"]="";
						}

						$fechas = arrayRangoFechas($fecha_inicio,$fecha_termino,"+1 day", "Y-m-d");
						$rut_tecnico = $this->Caomodel->getRutPorId($id_usuario);

						foreach($fechas as $fecha){
							if($this->Caomodel->existeTurno($rut_tecnico,$fecha)){
								$this->Caomodel->eliminarTurnosPorFecha($rut_tecnico,$fecha);
								//echo json_encode(array('res'=>"error", 'msg' => "Ya existe este registro de turno para este técnico y fecha."));exit;
							}
							
							$data_v=array("rut_tecnico" => $rut_tecnico, 
								"id_turno" => 2 ,
								"fecha" => $fecha,
								"id_digitador" => $id_digitador,
								"ultima_actualizacion" => $ultima_actualizacion);

							$this->Caomodel->formTurnos($data_v);
							$data_v =array();
						}

						$insert_id=$this->Caomodel->formIngresoVacaciones($data);
						if($insert_id!=FALSE){
							echo json_encode(array('res'=>"ok",  'msg' => OK_MSG));exit;
						}else{
							echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
						}

					}else{

						if($adjunto!=""){
							$nombre=$this->procesaArchivoVacaciones($_FILES["userfile"],$id_usuario."_".date("ymdHis"),1);
							$data["adjunto"]=$nombre;
						}
						
						$fechas = arrayRangoFechas($fecha_inicio,$fecha_termino,"+1 day", "Y-m-d");
						$rut_tecnico = $this->Caomodel->getRutPorId($id_usuario);

						foreach($fechas as $fecha){

							if($this->Caomodel->existeTurno($rut_tecnico,$fecha)){
								$this->Caomodel->eliminarTurnosPorFecha($rut_tecnico,$fecha);
							}
							
							$data_actual =$this->Caomodel->getDataRegistroVacaciones($hash);
							foreach($data_actual as $d){
								$fechas_actual = arrayRangoFechas($d["fecha_inicio"],$d["fecha_termino"],"+1 day", "Y-m-d");
								foreach($fechas_actual as $f){
									if($this->Caomodel->existeTurno($rut_tecnico,$f)){
										$this->Caomodel->eliminarTurnosPorFecha($rut_tecnico,$f);
									}
								}
							}

							$data_t=array("rut_tecnico" => $rut_tecnico, 
								"id_turno" => 2 ,
								"fecha" => $fecha,
								"id_digitador" => $id_digitador,
								"ultima_actualizacion" => $ultima_actualizacion);

							$this->Caomodel->formTurnos($data_t);
							$data_t =array();
						}

						if($this->Caomodel->formActualizarVacaciones($hash,$data)){
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

		
		public function procesaArchivoVacaciones($file,$titulo){
				$path = $file['name'];
				$ext = pathinfo($path, PATHINFO_EXTENSION);
				$archivo=strtolower(url_title(convert_accented_characters($titulo.rand(10, 1000)))).".".$ext;
				$config['upload_path'] = './archivos_vacaciones';
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

		public function listaUsuariosS2(){
	 	     echo $this->Caomodel->listaUsuariosS2();exit;
		}

		public function eliminaVacaciones(){
			$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
			$data=$this->Caomodel->getDataRegistroVacaciones($hash);

			foreach($data as $d){

				$fechas = arrayRangoFechas($d["fecha_inicio"],$d["fecha_termino"],"+1 day", "Y-m-d");
				$cantidad_dias = 0;

				foreach($fechas as $fecha){
					$rut_tecnico = $d["rut"];
					$fecha_inicio = date('Y-m-d', strtotime($fecha));

					if($this->Caomodel->existeTurno($rut_tecnico,$fecha_inicio)){
						$cantidad_dias++;
						$this->Caomodel->eliminarTurnosPorFecha($rut_tecnico,$fecha_inicio);
					}
				}

				$this->Caomodel->eliminaVacaciones($hash);

				if($cantidad_dias>0){
					echo json_encode(array("res" => "ok" , "msg" => "Registro eliminado correctamente, " .$cantidad_dias. " registros de turnos eliminados."));
				}else{	
					echo json_encode(array("res" => "ok" , "msg" => "Registro eliminado correctamente."));
				}
			}
		}

		public function getDataRegistroVacaciones(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();	
				$hash=$this->security->xss_clean(strip_tags($this->input->post("hash_id")));
				$data=$this->Caomodel->getDataRegistroVacaciones($hash);
				if($data){
					echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
				}else{
					echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
				}	
			}else{
				exit('No direct script access allowed');
			}
		}

	/******MANTENEDOR TURNOS*********/

		public function vistaMantenedorTurnos(){
			if($this->input->is_ajax_request()){
				$fecha_anio_atras=date('d-m-Y', strtotime('-365 day', strtotime(date("d-m-Y"))));
		    	$fecha_hoy=date('d-m-Y');
			    $datos=array();
				$this->load->view('back_end/cao/mantenedor_turnos',$datos);
			}
		}

		public function getMantenedorTurnosList(){
			echo json_encode($this->Caomodel->getMantenedorTurnosList());
		}
		
		public function formMantenedorTurnos(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();	
				$hash=$this->security->xss_clean(strip_tags($this->input->post("hash_id")));
				$codigo=$this->security->xss_clean(strip_tags($this->input->post("codigo")));
				$rango_horario=$this->security->xss_clean(strip_tags($this->input->post("rango_horario")));
				$estado=$this->security->xss_clean(strip_tags($this->input->post("estado")));
				$suma=$this->security->xss_clean(strip_tags($this->input->post("suma")));
		   		
		   		if($suma!=0 and $suma!=1){
		   			echo json_encode(array('res'=>"error", 'msg' => 'La suma debe ser 0 o 1.'));exit;
		   		}

	   			if ($this->form_validation->run("formMantenedorTurnos") == FALSE){
					echo json_encode(array('res'=>"error", 'msg' => strip_tags(validation_errors())));exit;
				}else{	

					$data = array(
		    		 	'codigo' => $codigo,
		    		 	'rango_horario' => $rango_horario,
		    		 	'estado' => $estado,
		    		 	'suma' => $suma);

			    	if($hash==FALSE){

			    		if($this->Caomodel->existeMantenedorTurno($codigo)){
							echo json_encode(array('res'=>"error", 'msg' => "Ya existe registro para este código."));exit;
						}

						$insert_id=$this->Caomodel->formMantenedorTurnos($data);
						if($insert_id!=FALSE){
							echo json_encode(array('res'=>"ok",  'msg' => OK_MSG));exit;
						}else{
							echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
						}

					}else{

						if($this->Caomodel->existeMantenedorTurnoMod($hash,$codigo)){
							echo json_encode(array('res'=>"error", 'msg' => "Ya existe registro para este código."));exit;
						}
						
						if($this->Caomodel->formActualizarMantenedorTurnos($hash,$data)){
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

		public function eliminaMantenedorTurnos(){
			$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));

			if($this->Caomodel->eliminaMantenedorTurnos($hash)){
				echo json_encode(array("res" => "ok" , "msg" => "Registro eliminado correctamente."));
			}else{	
				echo json_encode(array("res" => "error" , "msg" => "Error eliminando el registro, intente nuevamente."));
			}
		}

		public function getDataMantenedorTurnos(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();	
				$hash=$this->security->xss_clean(strip_tags($this->input->post("hash_id")));
				$data=$this->Caomodel->getDataMantenedorTurnos($hash);
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