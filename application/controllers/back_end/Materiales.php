<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Materiales extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if($this->uri->segment("1")==""){
			redirect("inicio");
		}
		$this->load->model("back_end/Materialesmodel");
		$this->load->model("back_end/Iniciomodel");
		$this->load->helper(array('fechas','str'));
		$this->load->library('user_agent');
	}

	public function visitas($modulo){
		$this->load->library('user_agent');
		$data=array("id_usuario"=>$this->session->userdata('id'),
			"id_aplicacion"=>19,
			"modulo"=>$modulo,
	     	"fecha"=>date("Y-m-d G:i:s"),
	    	"navegador"=>"navegador :".$this->agent->browser()."\nversion :".$this->agent->version()."\nos :".$this->agent-> platform()."\nmovil :".$this->agent->mobile(),
	    	"ip"=>$this->input->ip_address(),
    	);
    	$this->Materialesmodel->insertarVisita($data);
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
	
	/*********MATERIALES************/
		
	    public function index(){
	    	$this->acceso();
    	    $datos = array(
		        'titulo' => "Materiales XR3",
		        'contenido' => "materiales/inicio",
		        'perfiles' => $this->Iniciomodel->listaPerfiles(),
			);
			
			$this->load->view('plantillas/plantilla_back_end',$datos);
		}

		public function vistaMaterialesDetalle(){
			$this->visitas("detalle");
			if($this->input->is_ajax_request()){
			
				$datos = array(	
					
			    );

				$this->load->view('back_end/materiales/detalle',$datos);
			}
		}

		public function cargaPlanillaMateriales(){
			if (!function_exists('str_contains')) {
				function str_contains(string $haystack, string $needle): bool
				{
					return '' === $needle || false !== strpos($haystack, $needle);
				}
			}

			if (empty($_FILES['userfile']['name'])) {
				echo json_encode(array('res' => 'error', 'tipo' => 'error', 'msg' => 'Debe seleccionar un archivo.'));
				exit;
			}

			$fname = $_FILES['userfile']['name'];
			if (!str_contains($fname, ".")) {
				echo json_encode(array('res' => 'error', 'tipo' => 'error', 'msg' => 'Debe seleccionar un archivo CSV válido.'));
				exit;
			}

			$chk_ext = explode(".", $fname);
			if ($chk_ext[1] !== "csv") {
				echo json_encode(array('res' => 'error', 'tipo' => 'error', 'msg' => 'Debe seleccionar un archivo CSV.'));
				exit;
			}

			$fname = $_FILES['userfile']['name'];
			$chk_ext = explode(".", $fname);

			if (strtolower(end($chk_ext)) === "csv") {
				$filename = $_FILES['userfile']['tmp_name'];
				$handle = fopen($filename, "r");


				$bom = fread($handle, 3);
				if ($bom !== "\xEF\xBB\xBF") {
					rewind($handle); // Retroceder al inicio del archivo si no se encontró el BOM
				}
 
				$i = 0;
				$z = 0;
				$y = 0;

				$this->Materialesmodel->truncateMateriales();

				$dataArr = array(); // Array para almacenar los datos a insertar
				$rutNoExistentes = array(); // Array para almacenar los RUTs no existentes


				while (($data = fgetcsv($handle, 9999, ";")) !== FALSE) {
					$ultima_actualizacion = date("Y-m-d G:i:s") . " | " . $this->session->userdata("nombre_completo");

					if (count($data) != 7) {
						echo json_encode(array('res' => 'error', 'tipo' => 'error', 'msg' => 'Archivo CSV inválido, se esperan 7 columnas, pero se obtuvieron ' . count($data) . '.'));
						exit;
					}

					$id_tecnico = $this->Materialesmodel->getIdTecnicoPorRut(str_replace(array('-', '.'), '', $data[0]));

					if (empty($id_tecnico)) {
						$rutNoExistentes[$data[0]] = true; // Agregar RUT no existente al array asociativo
						$id_tecnico = $data[0];
					}

					$fecha = strtotime($data[4]);
					$fechaf = ($fecha !== false) ? date('Y-m-d', $fecha) : null;				
			
					$arr = array(
						'id_tecnico' => $id_tecnico,
						'material' => utf8_encode($data[1]),
						'serie' => utf8_encode($data[2]),
						'tipo' => utf8_encode($data[3]),
						"fecha"=>$fechaf,
						"dias"=>utf8_encode($data[5]),
						"estado"=>utf8_encode($data[6]),
						'ultima_actualizacion' => $ultima_actualizacion,
					);

					$dataArr[] = $arr;
					$i++;

					if ($i % 500 === 0) {
						// Insertar en lotes de 500 filas
						$this->Materialesmodel->formMaterialesBatch($dataArr);
						$dataArr = array(); // Reiniciar el array para el próximo lote
					}
				}

				if (!empty($dataArr)) {
					// Insertar las filas restantes
					$this->Materialesmodel->formMaterialesBatch($dataArr);
				}

				if ($i == 0) {
					echo json_encode(array('res' => 'ok', 'tipo' => 'success', 'msg' => 'No se insertaron filas.'));
					exit;
				}
				
				$rutNoExistentesList = array_keys($rutNoExistentes);
				$msg = 'Archivo cargado con éxito, ' . $i . ' filas insertadas.';
				if (!empty($rutNoExistentesList)) {
					$rutNoExistentesMsg = implode("\n", $rutNoExistentesList);
					$msg .= "\n\nRUTs no existentes:\n" . $rutNoExistentesMsg;
				}

				fclose($handle);
				echo json_encode(array('res' => 'ok', 'tipo' => 'success', 'msg' => $msg));
				exit;
			} else {
				echo json_encode(array('res' => 'error', 'tipo' => 'error', 'msg' => 'Archivo CSV inválido.'));
				exit;
			}
		}


		public function listaDetalleMateriales(){
			$desde = $this->security->xss_clean(strip_tags($this->input->get_post("desde")));
			$hasta = $this->security->xss_clean(strip_tags($this->input->get_post("hasta")));
			$trabajador = $this->security->xss_clean(strip_tags($this->input->get_post("trabajador")));
			$id_tecnico = $this->Materialesmodel->getIdTecnicoPorRut(str_replace(array('-', '.'), '', $trabajador));
			$jefe = $this->security->xss_clean(strip_tags($this->input->get_post("jefe")));

			echo json_encode($this->Materialesmodel->listaDetalle($desde,$hasta,$id_tecnico,$jefe));
		}	
		
		public function listaTrabajadoresMateriales(){
			$jefe=$this->security->xss_clean(strip_tags($this->input->get_post("jefe")));
     	    echo $this->Materialesmodel->listaTrabajadoresMateriales($jefe);exit;
		}


		public function excel_detalle_materiales(){
			$trabajador = $this->uri->segment(2);
			$jefe = $this->uri->segment(3);
			
			if($trabajador=="-"){
				$trabajador="";
			}

			if($jefe=="-"){
				$jefe="";
			}

			$id_tecnico = $this->Materialesmodel->getIdTecnicoPorRut(str_replace(array('-', '.'), '', $trabajador));
			$data=$this->Materialesmodel->listaDetalle("","",$id_tecnico,$jefe);

			if(!$data){
				echo "Sin datos disponibles.";
				return FALSE;
			}else{

				$nombre="reporte-detalle-materiales".".xls";
				header("Content-type: application/vnd.ms-excel;  charset=utf-8");
				header("Content-Disposition: attachment; filename=$nombre");
				?>
				<style type="text/css">
				.head{font-size:13px;height: 30px; background-color:#32477C;color:#fff; font-weight:bold;padding:10px;margin:10px;vertical-align:middle;}
				td{font-size:12px;text-align:center;   vertical-align:middle;}
				</style>
				<h3>Reporte detalle materiales</h3>
					<table align='center' border="1"> 
				        <tr style="background-color:#F9F9F9">
							<th class="head">T&eacute;cnico</th> 
							<th class="head">RUT</th> 
							<th class="head">Descripci&oacute;n</th> 
				            <th class="head">Serie</th> 
				            <th class="head">Tipo</th> 
				            <th class="head">Fecha</th> 
				            <th class="head">D&iacute;as</th> 
				            <th class="head">Estado</th> 
				        </tr>
				        </thead>	
						<tbody>
				        <?php 
				        	if($data !=FALSE){
					      		foreach($data as $d){
				      			?>
				      			 <tr>
								    <td><?php echo utf8_decode($d["tecnico"]); ?></td>
								    <td><?php echo utf8_decode($d["rut_format"]); ?></td>
									<td><?php echo utf8_decode($d["material"]); ?></td>
									<td><?php echo utf8_decode($d["serie"]); ?></td>
									<td><?php echo utf8_decode($d["tipo"]); ?></td>
									<td><?php echo utf8_decode($d["fecha"]); ?></td>
									<td><?php echo utf8_decode($d["dias"]); ?></td>
									<td><?php echo utf8_decode($d["estado"]); ?></td>
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


		public function actualizacionMateriales(){
		    if($this->input->is_ajax_request()){
		      $data=$this->Materialesmodel->actualizacionMateriales();
		      if($data){
		        echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
		      }else{
		        echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
		      } 
		    }else{
		      exit('No direct script access allowed');
		    }
		}


	/********* POR TECNICO************/
		
		public function vistaMaterialesPorTecnico(){
			$this->visitas("tecnicos");
			if($this->input->is_ajax_request()){
				$datos = array();
				$this->load->view('back_end/materiales/tecnico',$datos);
			}
		}

		
		public function listaTecnico(){
			$desde = $this->security->xss_clean(strip_tags($this->input->get_post("desde")));
			$hasta = $this->security->xss_clean(strip_tags($this->input->get_post("hasta")));
			$trabajador = $this->security->xss_clean(strip_tags($this->input->get_post("trabajador")));
			$id_tecnico = $this->Materialesmodel->getIdTecnicoPorRut(str_replace(array('-', '.'), '', $trabajador));
			$jefe = $this->security->xss_clean(strip_tags($this->input->get_post("jefe")));
			echo json_encode($this->Materialesmodel->listaTecnico($desde,$hasta,$id_tecnico,$jefe));
		}	
 
		public function excel_tecnico(){
			$trabajador = $this->uri->segment(2);
			$jefe = $this->uri->segment(3);
			
			if($trabajador=="-"){
				$trabajador="";
			}

			if($jefe=="-"){
				$jefe="";
			}

			$id_tecnico = $this->Materialesmodel->getIdTecnicoPorRut(str_replace(array('-', '.'), '', $trabajador));

			$data=$this->Materialesmodel->listaTecnico("","",$id_tecnico,$jefe);

			if(!$data){
				echo "Sin datos disponibles.";
				return FALSE;
			}else{

				$nombre="reporte-tecnico-".".xls";
				header("Content-type: application/vnd.ms-excel;  charset=utf-8");
				header("Content-Disposition: attachment; filename=$nombre");
				?>
				<style type="text/css">
				.head{font-size:13px;height: 30px; background-color:#32477C;color:#fff; font-weight:bold;padding:10px;margin:10px;vertical-align:middle;}
				td{font-size:12px;text-align:center;   vertical-align:middle;}
				</style>
				<h3>Reporte materiales por t&eacute;cnico </h3>
					<table align='center' border="1"> 
				        <tr style="background-color:#F9F9F9">
							<th class="head">T&eacute;cnico</th> 
							<th class="head">RUT</th> 
				            <th class="head">Reversa</th> 
				            <th class="head">Directa</th> 
				        </tr>
				        </thead>	
						<tbody>
				        <?php 
				        	if($data !=FALSE){
					      		foreach($data as $d){
				      			?>
				      			 <tr>
								     <td><?php echo utf8_decode($d["tecnico"]); ?></td>
									 <td><?php echo utf8_decode($d["rut_format"]); ?></td>
									 <td><?php echo utf8_decode($d["reversa"]); ?></td>
									 <td><?php echo utf8_decode($d["directa"]); ?></td>
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



	/********* POR MATERIAL************/

		public function vistaMaterialesPorMaterial(){
			$this->visitas("material");
			if($this->input->is_ajax_request()){
				$datos = array();
				$this->load->view('back_end/materiales/material',$datos);
			}
		}

		
		public function listaMaterial(){
			$desde = $this->security->xss_clean(strip_tags($this->input->get_post("desde")));
			$hasta = $this->security->xss_clean(strip_tags($this->input->get_post("hasta")));
			$trabajador = $this->security->xss_clean(strip_tags($this->input->get_post("trabajador")));
			$id_tecnico = $this->Materialesmodel->getIdTecnicoPorRut(str_replace(array('-', '.'), '', $trabajador));
			$jefe = $this->security->xss_clean(strip_tags($this->input->get_post("jefe")));
			echo json_encode($this->Materialesmodel->listaMaterial($desde,$hasta,$id_tecnico,$jefe));
		}	
 
		public function excel_material(){
			$trabajador = $this->uri->segment(2);
			$jefe = $this->uri->segment(3);
			
			if($trabajador=="-"){
				$trabajador="";
			}

			if($jefe=="-"){
				$jefe="";
			}

			$id_tecnico = $this->Materialesmodel->getIdTecnicoPorRut(str_replace(array('-', '.'), '', $trabajador));
			$data=$this->Materialesmodel->listaMaterial("","",$id_tecnico,$jefe);

			if(!$data){
				echo "Sin datos disponibles.";
				return FALSE;
			}else{

				$nombre="reporte-material".".xls";
				header("Content-type: application/vnd.ms-excel;  charset=utf-8");
				header("Content-Disposition: attachment; filename=$nombre");
				?>
				<style type="text/css">
				.head{font-size:13px;height: 30px; background-color:#32477C;color:#fff; font-weight:bold;padding:10px;margin:10px;vertical-align:middle;}
				td{font-size:12px;text-align:center;   vertical-align:middle;}
				</style>
				<h3>Reporte materiales por t&eacute;cnico </h3>
					<table align='center' border="1"> 
				        <tr style="background-color:#F9F9F9">
			                <th class="head">Descripci&oacute;n</th> 
				            <th class="head">Analisis</th> 
				            <th class="head">Bodega técnico</th> 
				            <th class="head">Disponible</th> 
				            <th class="head">Reversa</th> 
				        </tr>
				        </thead>	
						<tbody>
				        <?php 
				        	if($data !=FALSE){
					      		foreach($data as $d){
				      			?>
				      			 <tr>
									 <td><?php echo utf8_decode($d["material"]); ?></td>
									 <td><?php echo utf8_decode($d["analisis"]); ?></td>
									 <td><?php echo utf8_decode($d["tecnico"]); ?></td>
									 <td><?php echo utf8_decode($d["disponible"]); ?></td>
									 <td><?php echo utf8_decode($d["reversa"]); ?></td>
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

	/*********SERIES POR TECNICO************/

		public function vistaSeriesPorTecnico(){
			$this->visitas("series");
			if($this->input->is_ajax_request()){
				$datos = array(
					"comunas" => $this->Materialesmodel->listaComunasTrabajadores()
				);
				$this->load->view('back_end/materiales/series',$datos);
			}
		}

		public function listaSeriesDevolucion(){
			$desde = $this->security->xss_clean(strip_tags($this->input->get_post("desde")));
			$hasta = $this->security->xss_clean(strip_tags($this->input->get_post("hasta")));
			$trabajador = $this->security->xss_clean(strip_tags($this->input->get_post("trabajador")));
			$id_tecnico = $this->Materialesmodel->getIdTecnicoPorRut(str_replace(array('-', '.'), '', $trabajador));
			$jefe = $this->security->xss_clean(strip_tags($this->input->get_post("jefe")));
			$comuna = $this->security->xss_clean(strip_tags($this->input->get_post("comuna")));
			echo json_encode($this->Materialesmodel->listaSeriesDevolucion($desde,$hasta,$id_tecnico,$jefe,$comuna));
		}	
 
		public function excel_series_devolucion(){
			$trabajador = $this->uri->segment(2);
			$jefe = $this->uri->segment(3);
			$comuna = $this->uri->segment(4);
			
			if($trabajador=="-"){
				$trabajador="";
			}

			if($jefe=="-"){
				$jefe="";
			}

			if($comuna=="-"){
				$comuna="";
			}

			$id_tecnico = $this->Materialesmodel->getIdTecnicoPorRut(str_replace(array('-', '.'), '', $trabajador));
			$data=$this->Materialesmodel->listaSeriesDevolucion("","",$id_tecnico,$jefe,$comuna);

			if(!$data){
				echo "Sin datos disponibles.";
				return FALSE;
			}else{

				$nombre="reporte-equipos-devolucion".".xls";
				header("Content-type: application/vnd.ms-excel;  charset=utf-8");
				header("Content-Disposition: attachment; filename=$nombre");
				?>
				<style type="text/css">
				.head{font-size:13px;height: 30px; background-color:#32477C;color:#fff; font-weight:bold;padding:10px;margin:10px;vertical-align:middle;}
				td{font-size:12px;text-align:center;   vertical-align:middle;}
				</style>
				<h3>Reporte equipos para devoluci&oacute;n (Retiro)</h3>
					<table align='center' border="1"> 
				        <tr style="background-color:#F9F9F9">
							<th class="head">RUT</th> 
							<th class="head">Descripci&oacute;n</th> 
				            <th class="head">Serie</th> 
				        </tr>
				        </thead>	
						<tbody>
				        <?php 
				        	if($data !=FALSE){
					      		foreach($data as $d){
				      			?>
				      			 <tr>
								     <td><?php echo utf8_decode($d["rut_format"]); ?></td>
									 <td><?php echo utf8_decode($d["material_comuna"]); ?></td>
									 <td><?php echo utf8_decode($d["serie"]); ?></td>
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

		public function listaSeriesOperativos(){
			$desde = $this->security->xss_clean(strip_tags($this->input->get_post("desde")));
			$hasta = $this->security->xss_clean(strip_tags($this->input->get_post("hasta")));
			$trabajador = $this->security->xss_clean(strip_tags($this->input->get_post("trabajador")));
			$id_tecnico = $this->Materialesmodel->getIdTecnicoPorRut(str_replace(array('-', '.'), '', $trabajador));
			$jefe = $this->security->xss_clean(strip_tags($this->input->get_post("jefe")));
			$comuna = $this->security->xss_clean(strip_tags($this->input->get_post("comuna")));
			echo json_encode($this->Materialesmodel->listaSeriesOperativos($desde,$hasta,$id_tecnico,$jefe,$comuna));
		}	
 
		public function excel_series_operativos(){
			$trabajador = $this->uri->segment(2);
			$jefe = $this->uri->segment(3);
			$comuna = $this->uri->segment(4);
			
			if($trabajador=="-"){
				$trabajador="";
			}

			if($jefe=="-"){
				$jefe="";
			}

			if($comuna=="-"){
				$comuna="";
			}

			$id_tecnico = $this->Materialesmodel->getIdTecnicoPorRut(str_replace(array('-', '.'), '', $trabajador));
			$data=$this->Materialesmodel->listaSeriesOperativos("","",$id_tecnico,$jefe,$comuna);

			if(!$data){
				echo "Sin datos disponibles.";
				return FALSE;
			}else{

				$nombre="reporte-equipos-operativos".".xls";
				header("Content-type: application/vnd.ms-excel;  charset=utf-8");
				header("Content-Disposition: attachment; filename=$nombre");
				?>
				<style type="text/css">
				.head{font-size:13px;height: 30px; background-color:#32477C;color:#fff; font-weight:bold;padding:10px;margin:10px;vertical-align:middle;}
				td{font-size:12px;text-align:center;   vertical-align:middle;}
				</style>
				<h3>Reporte equipos operativos</h3>
					<table align='center' border="1"> 
				        <tr style="background-color:#F9F9F9">
							<th class="head">RUT</th> 
							<th class="head">Descripci&oacute;n</th> 
				            <th class="head">Serie</th> 
				        </tr>
				        </thead>	
						<tbody>
				        <?php 
				        	if($data !=FALSE){
					      		foreach($data as $d){
				      			?>
				      			 <tr>
								  	 <td><?php echo utf8_decode($d["rut_format"]); ?></td>
									 <td><?php echo utf8_decode($d["material_comuna"]); ?></td>
									 <td><?php echo utf8_decode($d["serie"]); ?></td>
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