<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;


class Mad extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model("back_end/Madmodel");
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
			"id_aplicacion"=>20,
			"modulo"=>$modulo,
	     	"fecha"=>date("Y-m-d G:i:s"),
	    	"navegador"=>"navegador :".$this->agent->browser()."\nversion :".$this->agent->version()."\nos :".$this->agent-> platform()."\nmovil :".$this->agent->mobile(),
	    	"ip"=>$this->input->ip_address(),
    	);
    	$this->Madmodel->insertarVisita($data);
	}

	public function index(){
		$this->visitas("Mad");
    	$this->acceso();

	    $datos = array(
	        'titulo' => "MAD (Módulo auditoría despacho)",
	        'contenido' => "mad/inicio",
	        'perfiles' => $this->Iniciomodel->listaPerfiles(),
		);  

		$this->load->view('plantillas/plantilla_back_end',$datos);
	}

	/********* MAD ***********/



		public function getMadInicio(){
			if($this->input->is_ajax_request()){
				$desde = date('Y-m-d', strtotime('-365 day', strtotime(date("d-m-Y"))));
				$hasta = date('Y-m-d');
				$datos = array(
					'desde' => $desde,
					'hasta' => $hasta,
					'usuarios' => $this->Madmodel->listaTrabajadores(),
					'comunas' => $this->Madmodel->listaComunas(),
					'plazas' => $this->Madmodel->listaPlazas(),
					'zonas' => $this->Madmodel->listaZonas(),
					'proyectos' => $this->Madmodel->listaProyectos(),
					'tipos' => $this->Madmodel->listaTipos(),
					'supervisores' => $this->Iniciomodel->listaSupervisores(),
				);
				$this->load->view('back_end/mad/mad',$datos);
			}
		}

		public function getMadList(){
			$desde=$this->security->xss_clean(strip_tags($this->input->get_post("desde")));
			$hasta=$this->security->xss_clean(strip_tags($this->input->get_post("hasta")));
			$coordinador=$this->security->xss_clean(strip_tags($this->input->get_post("coordinador")));
			$comuna=$this->security->xss_clean(strip_tags($this->input->get_post("comuna")));
			$zona=$this->security->xss_clean(strip_tags($this->input->get_post("zona")));
			$empresa=$this->security->xss_clean(strip_tags($this->input->get_post("empresa")));
			$supervisor=$this->security->xss_clean(strip_tags($this->input->get_post("supervisor")));
			if($desde!=""){$desde=date("Y-m-d",strtotime($desde));}else{$desde="";}
			if($hasta!=""){$hasta=date("Y-m-d",strtotime($hasta));}else{$hasta="";}	

			echo json_encode($this->Madmodel->getMadList($desde,$hasta,$coordinador,$comuna,$zona,$empresa,$supervisor));
		}

		public function formMad(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();	
				$hash = $this->security->xss_clean(strip_tags($this->input->post("hash_detalle")));
				$fecha = $this->security->xss_clean(strip_tags($this->input->post("fecha")));
				$zona = $this->security->xss_clean(strip_tags($this->input->post("zona")));
				$comuna = $this->security->xss_clean(strip_tags($this->input->post("comuna")));
				$id_tecnico = $this->security->xss_clean(strip_tags($this->input->post("id_tecnico")));
				$id_coordinador = $this->security->xss_clean(strip_tags($this->input->post("id_coordinador")));
				$tipo = $this->security->xss_clean(strip_tags($this->input->post("tipo")));
				$proyecto = $this->security->xss_clean(strip_tags($this->input->post("proyecto")));
				$cam_con = $this->security->xss_clean(strip_tags($this->input->post("cam_con")));
				$rssi = $this->security->xss_clean(strip_tags($this->input->post("rssi")));
				$pareo_inal = $this->security->xss_clean(strip_tags($this->input->post("pareo_inal")));
				$tipo_auditoria = $this->security->xss_clean(strip_tags($this->input->post("tipo_auditoria")));
				$id_orden_ot = $this->security->xss_clean(strip_tags($this->input->post("id_orden_ot")));
				$codigo = $this->security->xss_clean(strip_tags($this->input->post("codigo")));
				$observacion = $this->security->xss_clean(strip_tags($this->input->post("observacion")));
				$ultima_actualizacion = date("Y-m-d G:i:s")." | ".$this->session->userdata("nombre_completo");

				if ($this->form_validation->run("formMad") == FALSE){
					echo json_encode(array('res'=>"error", 'msg' => strip_tags(validation_errors())));exit;
				}else{	
					if($hash==""){
						$data = array(
							'fecha_ingreso' => date("Y-m-d G:i:s"),
							'fecha' => $fecha,
							'id_zona' => $zona,
							'id_comuna' => $comuna,
							'id_tecnico' => $id_tecnico,
							'id_coordinador' => $id_coordinador,
							'id_tipo' => $tipo,
							'id_proyecto' => $proyecto,
							'cam_con' => $cam_con,
							'rssi' => $rssi,
							'pareo_inal' => $pareo_inal,
							'tipo_auditoria' => $tipo_auditoria,
							'id_orden_ot' => $id_orden_ot,
							'observacion' => $observacion,
							'ultima_actualizacion' => $ultima_actualizacion,
                        );
						if($this->Madmodel->formIngreso($data)){
							echo json_encode(array('res'=>"ok",  'msg' => OK_MSG));exit;
						}else{
							echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
						}
					}else{
						$data_mod = array(
							'fecha_ingreso' => date("Y-m-d G:i:s"),
							'fecha' => $fecha,
							'id_zona' => $zona,
							'id_comuna' => $comuna,
							'id_tecnico' => $id_tecnico,
							'id_coordinador' => $id_coordinador,
							'cam_con' => $cam_con,
							'rssi' => $rssi,
							'pareo_inal' => $pareo_inal,
							'tipo_auditoria' => $tipo_auditoria,
							'id_orden_ot' => $id_orden_ot,
							'id_tipo' => $tipo,
							'id_proyecto' => $proyecto,
							'observacion' => $observacion,
							'ultima_actualizacion' => $ultima_actualizacion,
                        );
						if($this->Madmodel->formActualizar($hash,$data_mod)){
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
	 
		public function eliminaMad(){
			$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
			if($this->Madmodel->eliminaMad($hash)){
			echo json_encode(array("res" => "ok" , "msg" => "Registro eliminado correctamente."));
			}else{
			echo json_encode(array("res" => "error" , "msg" => "Problemas eliminando el registro, intente nuevamente."));
			}
		}

		public function getDataMad(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();	
				$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
				$data=$this->Madmodel->getDataMad($hash);
				if($data){
					echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
				}else{
					echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
				}	
			}else{
				exit('No direct script access allowed');
			}
		}

		public function excelmad(){
			$desde = $this->uri->segment(2);
			$hasta = $this->uri->segment(3);
			$coordinador = $this->uri->segment(4);
			$comuna = $this->uri->segment(5);
			$zona = $this->uri->segment(6);
			$empresa = $this->uri->segment(7);
			$supervisor = $this->uri->segment(8);

			if($desde!=""){$desde=date("Y-m-d",strtotime($desde));}else{$desde="";}
			if($hasta!=""){$hasta=date("Y-m-d",strtotime($hasta));}else{$hasta="";}
			if($coordinador=="-"){$coordinador="";}
			if($comuna=="-"){$comuna="";}
			if($zona=="-"){$zona="";}
			if($empresa=="-"){$empresa="";}
			if($supervisor=="-"){$supervisor="";}
			$data = $this->Madmodel->getMadList($desde,$hasta,$coordinador,$comuna,$zona,$empresa,$supervisor);

			if(!$data){
				return FALSE;
			}else{

				$nombre="reporte-mad.xls";
				header("Content-type: application/vnd.ms-excel;  charset=utf-8");
				header("Content-Disposition: attachment; filename=$nombre");
				?>

				<style type="text/css">
					.det{
						background-color:#233294;color:#fff;
					}
					.head{font-size:13px;height: 30px; background-color:#1D7189;color:#fff; font-weight:bold;padding:10px;margin:10px;vertical-align:middle;}
					td{font-size:12px;text-align:center;   vertical-align:middle;}
				</style>

				<h3>Reporte MAD (Módulo auditoria )</h3>
				<table align='center' border="1"> 
					<tr style="background-color:#F9F9F9">
						<th class="head">ID</th>    
						<th class="head">Registro a sistema</th>    
						<th class="head">Fecha</th>  
						<th class="head">Zona</th> 
						<th class="head">Comuna </th> 
						<th class="head">Nombre t&eacute;cnico</th> 
						<th class="head">Nombre coordinador</th> 
						<th class="head">Tipo</th> 
						<th class="head">Proyecto</th> 
						<th class="head">Cert. cambio conectores</th>  
            			<th class="head">Cert. RSSI</th>   
            			<th class="head">Par&eacute;o inal&aacute;mbrico</th>   
            			<th class="head">Tipo Auditor&iacute;a</th>   
            			<th class="head">C&oacute;digo OT</th>   
						<th class="head">Observaci&oacute;n</th> 
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
									<td><?php echo utf8_decode($d["fecha_ingreso"]); ?></td>
									<td><?php echo utf8_decode($d["fecha"]); ?></td>
									<td><?php echo utf8_decode($d["zona"]); ?></td>
									<td><?php echo utf8_decode($d["comuna"]); ?></td>
									<td><?php echo utf8_decode($d["nombre_tecnico"]); ?></td>
									<td><?php echo utf8_decode($d["nombre_coordinador"]); ?></td>
									<td><?php echo utf8_decode($d["tipo"]); ?></td>
									<td><?php echo utf8_decode($d["proyecto"]); ?></td>
									<td><?php echo utf8_decode($d["cam_con"]); ?></td>
									<td><?php echo utf8_decode($d["rssi"]); ?></td>
									<td><?php echo utf8_decode($d["pareo_inal"]); ?></td>
									<td><?php echo utf8_decode($d["tipo_auditoria"]); ?></td>
									<td><?php echo utf8_decode($d["id_orden_ot"]); ?></td>
									<td><?php echo utf8_decode($d["observacion"]); ?></td>
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

		public function formCargaMasivaMad(){

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
			$chk_ext = explode(".",$fname);
	
			if(strtolower(end($chk_ext)) == "xlsx")  {
	
	
				$archivo = $_FILES['userfile']['tmp_name'];
				$spreadsheet = IOFactory::load($archivo);
				$i = 0;
	
				//GENERAL
					$hoja = $spreadsheet->getSheet(0);
					$ultima_fila = $hoja->getHighestRow();
					$filas_general = 0;
	
					$columnas = array(
						'id_orden_ot',
						'fecha',
						'id_zona',
						'id_comuna',
						'id_tecnico', //SELECT * FROM `usuarios` WHERE nombres LIKE "nombre%" AND apellidos LIKE "apellido%" AND estado=1
						'id_coordinador', //SELECT * FROM `usuarios` WHERE nombres LIKE "nombre%" AND apellidos LIKE "apellido%" AND estado=1
						'id_proyecto',
						'id_tipo',
						'cam_con',
						'rssi',
						'pareo_inal',
						'tipo_auditoria', //HABLAR CON MATIAS, SE NECESITA AGREGAR EL VALOR AL EXCEL
						'observacion',
					);

					$ultima_actualizacion = date("Y-m-d G:i:s")." | ".$this->session->userdata("nombre_completo");
					$fecha_ingreso = date("Y-m-d G:i:s");
	
					for ($fila = 2; $fila <= $ultima_fila; $fila++) {
						if($hoja->getCellByColumnAndRow(1, $fila)->getValue() != ""){
							$datos = array();
							foreach ($columnas as $index => $columna) {
								if($hoja->getCellByColumnAndRow($index + 1, $fila)->getValue() != NULL){
									if ($index === 1) { //DATE
										$fecha = date('Y-m-d', strtotime('1900-01-00') + ((intval($hoja->getCellByColumnAndRow($index + 1, $fila)->getValue()) - 1) * 86400));
										$datos[$columna] = $fecha;
									} 
									elseif ($index === 2) { //zona
										$datos[$columna] = $this->Madmodel->GetZonaMasivo($hoja->getCellByColumnAndRow($index + 1, $fila)->getValue());
									}
									elseif ($index === 3) { //ciudad
										$datos[$columna] = $this->Madmodel->GetCiudadMasivo($hoja->getCellByColumnAndRow($index + 1, $fila)->getValue());
									}
									elseif ($index === 4) { //tecnico
										$tecnico =$hoja->getCellByColumnAndRow($index + 1, $fila)->getValue();
										$nombre_apellido = explode(" ", $tecnico);
										$datos[$columna] = $this->Madmodel->GetTecnicoMasivo($nombre_apellido[0],$nombre_apellido[1]);
									}
									elseif ($index === 5) { //auditor
										$tecnico =$hoja->getCellByColumnAndRow($index + 1, $fila)->getValue();
										$nombre_apellido = explode(" ", $tecnico);
										$datos[$columna] = $this->Madmodel->GetTecnicoMasivo($nombre_apellido[0],$nombre_apellido[1]);
									}
									elseif ($index === 6) { //proyecto
										$datos[$columna] = $this->Madmodel->GetProyectoMasivo($hoja->getCellByColumnAndRow($index + 1, $fila)->getValue());
									}
									elseif ($index === 7) { //tipo
										$datos[$columna] = $this->Madmodel->GetTipoMasivo($hoja->getCellByColumnAndRow($index + 1, $fila)->getValue());
									}
									else {
										$datos[$columna] = $hoja->getCellByColumnAndRow($index + 1, $fila)->getValue();
									}
								}
								else{
									$datos[$columna] = "-";
								}
							}
							$datos["ultima_actualizacion"]=$ultima_actualizacion;
							$datos["fecha_ingreso"]=$fecha_ingreso;
							
							$this->Madmodel->formIngreso($datos);
							$filas_general++;
							$i++;
						}
					}
	
				
					echo json_encode(array(
						'res' => 'ok',
						'tipo' => 'success',
						'msg' => 'Archivo cargado con éxito.
						'.$filas_general.' filas insertadas,'
						
					));exit;
	
				   echo json_encode(array('res'=>'ok', "tipo" => "success", 'msg' => "Archivo cargado con éxito, ".$i." filas insertadas."));exit;
			}else{
				echo json_encode(array('res'=>'error', "tipo" => "error", 'msg' => "Archivo inválido."));exit;
			}   
		}

	/******* Graficos ******/

	public function getGraficosMad(){
		if($this->input->is_ajax_request()){
			$desde = date('Y-m-d', strtotime('-365 day', strtotime(date("d-m-Y"))));
			$hasta = date('Y-m-d');
			$datos = array(
				'desde' => $desde,
				'hasta' => $hasta,
				'usuarios' => $this->Madmodel->listaTrabajadores(),
				'comunas' => $this->Madmodel->listaComunas(),
				'plazas' => $this->Madmodel->listaPlazas(),
				'zonas' => $this->Madmodel->listaZonas(),
				'proyectos' => $this->Madmodel->listaProyectos(),
				'tipos' => $this->Madmodel->listaTipos(),
				'supervisores' => $this->Iniciomodel->listaSupervisores(),
			);
			$this->load->view('back_end/mad/graficos',$datos);
		}
	}

	public function getDataGraficosMad(){
		$desde=$this->security->xss_clean(strip_tags($this->input->get_post("desde")));
		$hasta=$this->security->xss_clean(strip_tags($this->input->get_post("hasta")));
		$coordinador=$this->security->xss_clean(strip_tags($this->input->get_post("coordinador")));
		$comuna=$this->security->xss_clean(strip_tags($this->input->get_post("comuna")));
		$zona=$this->security->xss_clean(strip_tags($this->input->get_post("zona")));
		$empresa=$this->security->xss_clean(strip_tags($this->input->get_post("empresa")));
		$supervisor=$this->security->xss_clean(strip_tags($this->input->get_post("supervisor")));

		if($desde!=""){$desde=date("Y-m-d",strtotime($desde));}else{$desde="";}
		if($hasta!=""){$hasta=date("Y-m-d",strtotime($hasta));}else{$hasta="";}	
		$array_data = array();	

		$DataGraficoResultadosxZona = $this->Madmodel->DataGraficoResultadosxZona($desde,$hasta,$coordinador,$comuna,$zona,$empresa,$supervisor);
		$DataGraficoResultadosxComuna = $this->Madmodel->DataGraficoResultadosxComuna($desde,$hasta,$coordinador,$comuna,$zona,$empresa,$supervisor);
		$DataGraficoResultadosxEmpresa = $this->Madmodel->DataGraficoResultadosxEmpresa($desde,$hasta,$coordinador,$comuna,$zona,$empresa,$supervisor);
		$DataGraficoResultadosxSupervisor = $this->Madmodel->DataGraficoResultadosxSupervisor($desde,$hasta,$coordinador,$comuna,$zona,$empresa,$supervisor);
		$DataGraficoResultadosxCoordinador = $this->Madmodel->DataGraficoResultadosxCoordinador($desde,$hasta,$coordinador,$comuna,$zona,$empresa,$supervisor);

		$array_data["GraficoResultadosxZona"] = array("data" => $DataGraficoResultadosxZona);
		$array_data["GraficoResultadosxComuna"] = array("data" => $DataGraficoResultadosxComuna);
		$array_data["GraficoResultadosxEmpresa"] = array("data" => $DataGraficoResultadosxEmpresa);
		$array_data["GraficoResultadosxSupervisor"] = array("data" => $DataGraficoResultadosxSupervisor);
		$array_data["GraficoResultadosxCoordinador"] = array("data" => $DataGraficoResultadosxCoordinador);
		echo json_encode($array_data);
	}



	
	/**************** MANTENEDOR ***************/

	public function getMantenedorMad(){
		if($this->input->is_ajax_request()){
			$desde = date('Y-m-d', strtotime('-365 day', strtotime(date("d-m-Y"))));
			$hasta = date('Y-m-d');
			$datos = array(
				'desde' => $desde,
				'hasta' => $hasta,
			);
			$this->load->view('back_end/mad/mantenedor',$datos);
		}
	}



		/***** COMUNA *****/

		public function getComunasMadList(){
			echo json_encode($this->Madmodel->getComunasMadList());
		}

		public function formComunasMad(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();	
				$hash = $this->security->xss_clean(strip_tags($this->input->post("hash_comuna")));
				$comuna = $this->security->xss_clean(strip_tags($this->input->post("comuna")));

				if ($this->form_validation->run("formComunasMad") == FALSE){
					echo json_encode(array('res'=>"error", 'msg' => strip_tags(validation_errors())));exit;
				}else{	
					if($hash==""){
						$data = array(
							'titulo' => $comuna,
						);
						if($this->Madmodel->formIngresoComuna($data)){
							echo json_encode(array('res'=>"ok",  'msg' => OK_MSG));exit;
						}else{
							echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
						}
					}else{
						$data_mod = array(
							'titulo' => $comuna,
						);
						if($this->Madmodel->formActualizarComuna($hash,$data_mod)){
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

		public function eliminaComunasMad(){
			$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
			if($this->Madmodel->eliminaComunasMad($hash)){
			echo json_encode(array("res" => "ok" , "msg" => "Registro eliminado correctamente."));
			}else{
			echo json_encode(array("res" => "error" , "msg" => "Problemas eliminando el registro, intente nuevamente."));
			}
		}

		public function getDataComunasMad(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();	
				$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
				$data=$this->Madmodel->getDataComunasMad($hash);
				if($data){
					echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
				}else{
					echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
				}	
			}else{
				exit('No direct script access allowed');
			}
		}

		/***** TIPO *****/
		public function getTiposMadList(){
			echo json_encode($this->Madmodel->getTiposMadList());
		}

		public function listaTipos(){
			echo json_encode($this->Madmodel->listaTipos());
		}

		public function formTiposMad(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();	
				$hash = $this->security->xss_clean(strip_tags($this->input->post("hash_tipo")));
				$tipo = $this->security->xss_clean(strip_tags($this->input->post("tipo")));

				if ($this->form_validation->run("formTiposMad") == FALSE){
					echo json_encode(array('res'=>"error", 'msg' => strip_tags(validation_errors())));exit;
				}else{	
					if($hash==""){
						$data = array(
							'tipo' => $tipo,
						);
						if($this->Madmodel->formIngresoTipo($data)){
							echo json_encode(array('res'=>"ok",  'msg' => OK_MSG));exit;
						}else{
							echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
						}
					}else{
						$data_mod = array(
							'tipo' => $tipo,
						);
						if($this->Madmodel->formActualizarTipo($hash,$data_mod)){
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

		public function eliminaTiposMad(){
			$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
			if($this->Madmodel->eliminaTiposMad($hash)){
			echo json_encode(array("res" => "ok" , "msg" => "Registro eliminado correctamente."));
			}else{
			echo json_encode(array("res" => "error" , "msg" => "Problemas eliminando el registro, intente nuevamente."));
			}
		}

		public function getDataTiposMad(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();	
				$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
				$data=$this->Madmodel->getDataTiposMad($hash);
				if($data){
					echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
				}else{
					echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
				}	
			}else{
				exit('No direct script access allowed');
			}
		}
	
		/***** MOTIVO *****/
		public function getMotivosMadList(){
			echo json_encode($this->Madmodel->getMotivosMadList());
		}

		public function listaMotivos(){
			$tipo=$this->security->xss_clean(strip_tags($this->input->get_post("tipo")));
			echo json_encode($this->Madmodel->listaMotivos($tipo));
		}

		public function formMotivosMad(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();	
				$hash = $this->security->xss_clean(strip_tags($this->input->post("hash_motivo")));
				$id_tipo = $this->security->xss_clean(strip_tags($this->input->post("tipo_m")));
				$motivo = $this->security->xss_clean(strip_tags($this->input->post("motivo")));

				if ($this->form_validation->run("formMotivosMad") == FALSE){
					echo json_encode(array('res'=>"error", 'msg' => strip_tags(validation_errors())));exit;
				}else{	
					if($hash==""){
						$data = array(
							'id_tipo' => $id_tipo,
							'motivo' => $motivo,
						);
						if($this->Madmodel->formIngresoMotivo($data)){
							echo json_encode(array('res'=>"ok",  'msg' => OK_MSG));exit;
						}else{
							echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
						}
					}else{
						$data_mod = array(
							'id_tipo' => $id_tipo,
							'motivo' => $motivo,
						);
						if($this->Madmodel->formActualizarMotivo($hash,$data_mod)){
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

		public function eliminaMotivosMad(){
			$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
			if($this->Madmodel->eliminaMotivosMad($hash)){
			echo json_encode(array("res" => "ok" , "msg" => "Registro eliminado correctamente."));
			}else{
			echo json_encode(array("res" => "error" , "msg" => "Problemas eliminando el registro, intente nuevamente."));
			}
		}

		public function getDataMotivosMad(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();	
				$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
				$data=$this->Madmodel->getDataMotivosMad($hash);
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