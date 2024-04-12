<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rcdc extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model("back_end/Rcdcmodel");
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
    	$this->Rcdcmodel->insertarVisita($data);
	}

	public function index(){
		$this->visitas("Rcdc");
    	$this->acceso();

	    $datos = array(
	        'titulo' => "RCDC (Registro centro de comando)",
	        'contenido' => "rcdc/inicio",
	        'perfiles' => $this->Iniciomodel->listaPerfiles(),
		);  

		$this->load->view('plantillas/plantilla_back_end',$datos);
	}

	/********* RCDC ***********/

		public function getRcdcInicio(){
			if($this->input->is_ajax_request()){
				$desde = date('Y-m-d', strtotime('-365 day', strtotime(date("d-m-Y"))));
				$hasta = date('Y-m-d');
				$datos = array(
					'desde' => $desde,
					'hasta' => $hasta,
					'usuarios' => $this->Rcdcmodel->listaTrabajadores(),
					'comunas' => $this->Rcdcmodel->listaComunas(),
					'plazas' => $this->Rcdcmodel->listaPlazas(),
					'zonas' => $this->Rcdcmodel->listaZonas(),
					'proyectos' => $this->Rcdcmodel->listaProyectos(),
					'tipos' => $this->Rcdcmodel->listaTipos(),
				);
				$this->load->view('back_end/rcdc/rcdc',$datos);
			}
		}

		public function getRcdcList(){
			$desde=$this->security->xss_clean(strip_tags($this->input->get_post("desde")));
			$hasta=$this->security->xss_clean(strip_tags($this->input->get_post("hasta")));
			$coordinador=$this->security->xss_clean(strip_tags($this->input->get_post("coordinador")));
			$comuna=$this->security->xss_clean(strip_tags($this->input->get_post("comuna")));
			$zona=$this->security->xss_clean(strip_tags($this->input->get_post("zona")));
			$empresa=$this->security->xss_clean(strip_tags($this->input->get_post("empresa")));
			if($desde!=""){$desde=date("Y-m-d",strtotime($desde));}else{$desde="";}
			if($hasta!=""){$hasta=date("Y-m-d",strtotime($hasta));}else{$hasta="";}	

			echo json_encode($this->Rcdcmodel->getRcdcList($desde,$hasta,$coordinador,$comuna,$zona,$empresa));
		}

		public function formRcdc(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();	
				$hash = $this->security->xss_clean(strip_tags($this->input->post("hash_detalle")));
				$fecha = $this->security->xss_clean(strip_tags($this->input->post("fecha")));
				$zona = $this->security->xss_clean(strip_tags($this->input->post("zona")));
				$comuna = $this->security->xss_clean(strip_tags($this->input->post("comuna")));
				$id_tecnico = $this->security->xss_clean(strip_tags($this->input->post("id_tecnico")));
				$id_coordinador = $this->security->xss_clean(strip_tags($this->input->post("id_coordinador")));
				$tipo = $this->security->xss_clean(strip_tags($this->input->post("tipo")));
				$motivo = $this->security->xss_clean(strip_tags($this->input->post("motivo")));
				$proyecto = $this->security->xss_clean(strip_tags($this->input->post("proyecto")));
				$codigo = $this->security->xss_clean(strip_tags($this->input->post("codigo")));
				$hora = $this->security->xss_clean(strip_tags($this->input->post("hora")));
				$minuto = $this->security->xss_clean(strip_tags($this->input->post("minuto")));
				$observacion = $this->security->xss_clean(strip_tags($this->input->post("observacion")));
				$ultima_actualizacion = date("Y-m-d G:i:s")." | ".$this->session->userdata("nombre_completo");

				if ($this->form_validation->run("formRcdc") == FALSE){
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
							'id_motivo' => $motivo,
							'id_proyecto' => $proyecto,
							'codigo' => $codigo,
							'hora' => $hora,
							'minuto' => $minuto,
							'observacion' => $observacion,
							'ultima_actualizacion' => $ultima_actualizacion,
                        );
						if($this->Rcdcmodel->formIngreso($data)){
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
							'id_tipo' => $tipo,
							'id_motivo' => $motivo,
							'id_proyecto' => $proyecto,
							'codigo' => $codigo,
							'hora' => $hora,
							'minuto' => $minuto,
							'observacion' => $observacion,
							'ultima_actualizacion' => $ultima_actualizacion,
                        );
						if($this->Rcdcmodel->formActualizar($hash,$data_mod)){
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
	 
		public function eliminaRcdc(){
			$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
			if($this->Rcdcmodel->eliminaRcdc($hash)){
			echo json_encode(array("res" => "ok" , "msg" => "Registro eliminado correctamente."));
			}else{
			echo json_encode(array("res" => "error" , "msg" => "Problemas eliminando el registro, intente nuevamente."));
			}
		}

		public function getDataRcdc(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();	
				$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
				$data=$this->Rcdcmodel->getDataRcdc($hash);
				if($data){
					echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
				}else{
					echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
				}	
			}else{
				exit('No direct script access allowed');
			}
		}

		public function excelrcdc(){
			$desde = $this->uri->segment(2);
			$hasta = $this->uri->segment(3);
			$coordinador = $this->uri->segment(4);
			$comuna = $this->uri->segment(5);
			$zona = $this->uri->segment(6);
			$empresa = $this->uri->segment(7);

			if($desde!=""){$desde=date("Y-m-d",strtotime($desde));}else{$desde="";}
			if($hasta!=""){$hasta=date("Y-m-d",strtotime($hasta));}else{$hasta="";}
			if($coordinador=="-"){$coordinador="";}
			if($comuna=="-"){$comuna="";}
			if($zona=="-"){$zona="";}
			if($empresa=="-"){$empresa="";}
			$data = $this->Rcdcmodel->getRcdcList($desde,$hasta,$coordinador,$comuna,$zona,$empresa);

			if(!$data){
				return FALSE;
			}else{

				$nombre="reporte-rcdc.xls";
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

				<h3>Reporte RCDC (Registro centro de comando)</h3>
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
						<th class="head">Motivo</th> 
						<th class="head">Proyecto</th> 
						<th class="head">C&oacute;digo OT /IBS</th> 
						<th class="head">Duraci&oacute;n</th> 
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
									<td><?php echo utf8_decode($d["motivo"]); ?></td>
									<td><?php echo utf8_decode($d["proyecto"]); ?></td>
									<td><?php echo utf8_decode($d["codigo"]); ?></td>
									<td><?php echo utf8_decode($d["duracion"]); ?></td>
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

	
	/**************** MANTENEDOR ***************/

		public function getMantenedorRcdc(){
			if($this->input->is_ajax_request()){
				$desde = date('Y-m-d', strtotime('-365 day', strtotime(date("d-m-Y"))));
				$hasta = date('Y-m-d');
				$datos = array(
					'desde' => $desde,
					'hasta' => $hasta,
				);
				$this->load->view('back_end/rcdc/mantenedor',$datos);
			}
		}

		/***** COMUNA *****/

		public function getComunasRcdcList(){
			echo json_encode($this->Rcdcmodel->getComunasRcdcList());
		}

		public function formComunasRcdc(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();	
				$hash = $this->security->xss_clean(strip_tags($this->input->post("hash_comuna")));
				$comuna = $this->security->xss_clean(strip_tags($this->input->post("comuna")));

				if ($this->form_validation->run("formComunasRcdc") == FALSE){
					echo json_encode(array('res'=>"error", 'msg' => strip_tags(validation_errors())));exit;
				}else{	
					if($hash==""){
						$data = array(
							'titulo' => $comuna,
						);
						if($this->Rcdcmodel->formIngresoComuna($data)){
							echo json_encode(array('res'=>"ok",  'msg' => OK_MSG));exit;
						}else{
							echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
						}
					}else{
						$data_mod = array(
							'titulo' => $comuna,
						);
						if($this->Rcdcmodel->formActualizarComuna($hash,$data_mod)){
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

		public function eliminaComunasRcdc(){
			$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
			if($this->Rcdcmodel->eliminaComunasRcdc($hash)){
			echo json_encode(array("res" => "ok" , "msg" => "Registro eliminado correctamente."));
			}else{
			echo json_encode(array("res" => "error" , "msg" => "Problemas eliminando el registro, intente nuevamente."));
			}
		}

		public function getDataComunasRcdc(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();	
				$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
				$data=$this->Rcdcmodel->getDataComunasRcdc($hash);
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
		public function getTiposRcdcList(){
			echo json_encode($this->Rcdcmodel->getTiposRcdcList());
		}

		public function listaTipos(){
			echo json_encode($this->Rcdcmodel->listaTipos());
		}

		public function formTiposRcdc(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();	
				$hash = $this->security->xss_clean(strip_tags($this->input->post("hash_tipo")));
				$tipo = $this->security->xss_clean(strip_tags($this->input->post("tipo")));

				if ($this->form_validation->run("formTiposRcdc") == FALSE){
					echo json_encode(array('res'=>"error", 'msg' => strip_tags(validation_errors())));exit;
				}else{	
					if($hash==""){
						$data = array(
							'tipo' => $tipo,
						);
						if($this->Rcdcmodel->formIngresoTipo($data)){
							echo json_encode(array('res'=>"ok",  'msg' => OK_MSG));exit;
						}else{
							echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
						}
					}else{
						$data_mod = array(
							'tipo' => $tipo,
						);
						if($this->Rcdcmodel->formActualizarTipo($hash,$data_mod)){
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

		public function eliminaTiposRcdc(){
			$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
			if($this->Rcdcmodel->eliminaTiposRcdc($hash)){
			echo json_encode(array("res" => "ok" , "msg" => "Registro eliminado correctamente."));
			}else{
			echo json_encode(array("res" => "error" , "msg" => "Problemas eliminando el registro, intente nuevamente."));
			}
		}

		public function getDataTiposRcdc(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();	
				$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
				$data=$this->Rcdcmodel->getDataTiposRcdc($hash);
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
		public function getMotivosRcdcList(){
			echo json_encode($this->Rcdcmodel->getMotivosRcdcList());
		}

		public function listaMotivos(){
			$tipo=$this->security->xss_clean(strip_tags($this->input->get_post("tipo")));
			echo json_encode($this->Rcdcmodel->listaMotivos($tipo));
		}

		public function formMotivosRcdc(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();	
				$hash = $this->security->xss_clean(strip_tags($this->input->post("hash_motivo")));
				$id_tipo = $this->security->xss_clean(strip_tags($this->input->post("tipo_m")));
				$motivo = $this->security->xss_clean(strip_tags($this->input->post("motivo")));

				if ($this->form_validation->run("formMotivosRcdc") == FALSE){
					echo json_encode(array('res'=>"error", 'msg' => strip_tags(validation_errors())));exit;
				}else{	
					if($hash==""){
						$data = array(
							'id_tipo' => $id_tipo,
							'motivo' => $motivo,
						);
						if($this->Rcdcmodel->formIngresoMotivo($data)){
							echo json_encode(array('res'=>"ok",  'msg' => OK_MSG));exit;
						}else{
							echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
						}
					}else{
						$data_mod = array(
							'id_tipo' => $id_tipo,
							'motivo' => $motivo,
						);
						if($this->Rcdcmodel->formActualizarMotivo($hash,$data_mod)){
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

		public function eliminaMotivosRcdc(){
			$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
			if($this->Rcdcmodel->eliminaMotivosRcdc($hash)){
			echo json_encode(array("res" => "ok" , "msg" => "Registro eliminado correctamente."));
			}else{
			echo json_encode(array("res" => "error" , "msg" => "Problemas eliminando el registro, intente nuevamente."));
			}
		}

		public function getDataMotivosRcdc(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();	
				$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
				$data=$this->Rcdcmodel->getDataMotivosRcdc($hash);
				if($data){
					echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
				}else{
					echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
				}	
			}else{
				exit('No direct script access allowed');
			}
		}
	

	/********* GRAFICOS *******/

		public function getGraficosRcdc(){
			if($this->input->is_ajax_request()){
				$datos = array(
					'anio' => $this->Rcdcmodel->anioRegistros(),
					'mes' => $this->Rcdcmodel->MesRegistros(),
					'comuna' => $this->Rcdcmodel->listaComunas(),
					'zona' => $this->Rcdcmodel->listaZonas(),
					'coordinador' => $this->Rcdcmodel->listaTrabajadores(),
				);
				$this->load->view('back_end/rcdc/graficos',$datos);
			}
		}

		public function getDataGraficosRcdc(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();	

				$anio=$this->security->xss_clean(strip_tags($this->input->post("anio")));
				$mes=$this->security->xss_clean(strip_tags($this->input->post("mes")));
				$comuna=$this->security->xss_clean(strip_tags($this->input->post("comuna")));
				$zona=$this->security->xss_clean(strip_tags($this->input->post("zona")));
				$encargado=$this->security->xss_clean(strip_tags($this->input->post("encargado")));

				$lista_motivos = $this->Rcdcmodel->listaMotivos("");
				$detalle = $this->Rcdcmodel->getDataGraficoMotivosxTecnicoDetalle($anio,$mes,$comuna,$zona,$encargado);

				// Crear un array con todos los IDs de motivo
				$ids_motivos = array_column($lista_motivos, 'id');
				$nombre_motivos = array_column($lista_motivos, 'motivo');

				// Array para almacenar el resultado
				$resultado = [];

				// Iterar sobre el detalle para contar las veces que cada usuario ha registrado cada motivo
				foreach ($detalle as $registro) {
					$usuario = $registro[0];
					$id_motivo = $registro[1];
					$cantidad = $registro[2];

					// Si el usuario no existe en el resultado, inicializarlo
					if (!isset($resultado[$usuario])) {
						$resultado[$usuario] = [];
						// Agregar el nombre de usuario como primer valor
						$resultado[$usuario]["nombre"] = $usuario;
					}

					// Obtener el motivo por su ID
					$motivo = $lista_motivos[array_search($id_motivo, $ids_motivos)]["motivo"] ?? null;

					// Almacenar la cantidad
					$resultado[$usuario][$motivo] = $cantidad;
				}

				// Rellenar con 0 para los motivos que no están presentes en el detalle
				foreach ($resultado as &$usuario_motivos) {
					foreach ($lista_motivos as $motivo) {
						if (!isset($usuario_motivos[$motivo['motivo']])) {
							$usuario_motivos[$motivo['motivo']] = 0;
						}
					}
				}

				$array = array();
				array_unshift($nombre_motivos, "Motivo");
				$array[] = $nombre_motivos;
				foreach ($resultado as $res) {
					$temp = array();
					$temp[] = $res["nombre"];
					foreach ($lista_motivos as $motivo) {
						$temp[] = $res[$motivo["motivo"]];
					}
					$array[] = $temp;
				}
				// Calcular la suma de cada fila y almacenarla junto con el índice de la fila
				$sumas = [];
				foreach ($array as $indice => $fila) {
					if ($indice === 0) continue; // Saltar el encabezado
					$sumas[$indice] = array_sum(array_slice($fila, 1)); // Sumar desde el segundo elemento en adelante
				}
				// Ordenar el array en función de las sumas de cada fila de manera descendente
				arsort($sumas);
				// Crear un nuevo array ordenado en función de los índices obtenidos
				$array_ordenado = [];
				foreach ($sumas as $indice => $suma) {
					$array_ordenado[] = $array[$indice];
				}
				// Agregar el encabezado al nuevo array
				$array_ordenado = array_merge([$array[0]], $array_ordenado);

				$data=array(
					"graficoMotivosxZona" => $this->Rcdcmodel->getDataGraficoMotivosxZona($anio,$mes,$comuna,$zona,$encargado),
					"graficoMotivos" => $this->Rcdcmodel->getDataGraficoMotivos($anio,$mes,$comuna,$zona,$encargado),
					"graficoMotivosxTecnico" => $this->Rcdcmodel->getDataGraficoMotivosxTecnico($anio,$mes,$comuna,$zona,$encargado),
					"graficoMotivosxComuna" => $this->Rcdcmodel->getDataGraficoMotivosxComuna($anio,$mes,$comuna,$zona,$encargado),
					"graficoMotivosxTecnicoDetalle" => $array_ordenado,
					"graficoMotivosxCoordinador" => $this->Rcdcmodel->getDataGraficoMotivosxCoordinador($anio,$mes,$comuna,$zona,$encargado),
				);

				echo json_encode($data);exit;

			}else{
				exit('No direct script access allowed');
			}
		}

}