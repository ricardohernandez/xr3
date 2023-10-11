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
					'zonas' => $this->Rcdcmodel->listaZonas(),
					'proyectos' => $this->Rcdcmodel->listaProyectos(),
					'tramos' => $this->Rcdcmodel->listaTramos(),
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
				$id_tramo = $this->security->xss_clean(strip_tags($this->input->post("id_tramo")));
				$zona = $this->security->xss_clean(strip_tags($this->input->post("zona")));
				$comuna = $this->security->xss_clean(strip_tags($this->input->post("comuna")));
				$id_tecnico = $this->security->xss_clean(strip_tags($this->input->post("id_tecnico")));
				$id_coordinador = $this->security->xss_clean(strip_tags($this->input->post("id_coordinador")));
				$proyecto = $this->security->xss_clean(strip_tags($this->input->post("proyecto")));
				$codigo = $this->security->xss_clean(strip_tags($this->input->post("codigo")));
				$id_tipo = $this->security->xss_clean(strip_tags($this->input->post("id_tipo")));
				$estado = $this->security->xss_clean(strip_tags($this->input->post("estado")));
				$costo = $this->security->xss_clean(strip_tags($this->input->post("costo")));
				$observacion = $this->security->xss_clean(strip_tags($this->input->post("observacion")));
				$ultima_actualizacion = date("Y-m-d G:i:s")." | ".$this->session->userdata("nombre_completo");

				if ($this->form_validation->run("formRcdc") == FALSE){
					echo json_encode(array('res'=>"error", 'msg' => strip_tags(validation_errors())));exit;
				}else{	
					if($hash==""){
						$data = array(
							'fecha_ingreso' => date("Y-m-d G:i:s"),
							'fecha' => $fecha,
							'id_tramo' => $id_tramo,
							'id_zona' => $zona,
							'id_comuna' => $comuna,
							'id_tecnico' => $id_tecnico,
							'id_coordinador' => $id_coordinador,
							'id_proyecto' => $proyecto,
							'codigo' => $codigo,
							'id_tipo' => $id_tipo,
							'estado' => $estado,
							'observacion' => $observacion,
							'costo_instalacion' => $costo,
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
							'id_tramo' => $id_tramo,
							'id_zona' => $zona,
							'id_comuna' => $comuna,
							'id_tecnico' => $id_tecnico,
							'id_coordinador' => $id_coordinador,
							'id_proyecto' => $proyecto,
							'codigo' => $codigo,
							'id_tipo' => $id_tipo,
							'estado' => $estado,
							'observacion' => $observacion,
							'costo_instalacion' => $costo,
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
					.finde{
						font-size:13px;height: 30px; background-color:#1D7189;color:red; font-weight:bold;padding:10px;margin:10px;vertical-align:middle;
					}
					td{font-size:12px;text-align:center;   vertical-align:middle;}
				</style>

				<h3>Reporte RCDC (Registro centro de comando)</h3>
				<table align='center' border="1"> 
					<tr style="background-color:#F9F9F9">
						<th class="head">ID</th>    
						<th class="head">Registro a sistema</th>    
						<th class="head">Fecha</th>  
						<th class="head">tramo</th>   
						<th class="head">Zona</th> 
						<th class="head">Comuna </th> 
						<th class="head">Nombre t&eacute;cnico</th> 
						<th class="head">Nombre coordinador</th> 
						<th class="head">Proyecto</th> 
						<th class="head">C&oacute;digo OT /IBS</th> 
						<th class="head">tipo</th> 
						<th class="head">Estado orden </th> 
						<th class="head">Observaci&oacute;n</th> 
						<th class="head">Costo de instalaci&oacute;n</th> 
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
									<td><?php echo utf8_decode($d["tramo"]); ?></td>
									<td><?php echo utf8_decode($d["zona"]); ?></td>
									<td><?php echo utf8_decode($d["comuna"]); ?></td>
									<td><?php echo utf8_decode($d["nombre_tecnico"]); ?></td>
									<td><?php echo utf8_decode($d["nombre_coordinador"]); ?></td>
									<td><?php echo utf8_decode($d["proyecto"]); ?></td>
									<td><?php echo utf8_decode($d["codigo"]); ?></td>
									<td><?php echo utf8_decode($d["tipo"]); ?></td>
									<td><?php echo utf8_decode($d["estado"]); ?></td>
									<td><?php echo utf8_decode($d["observacion"]); ?></td>
									<td><?php echo utf8_decode($d["costo_instalacion"]); ?></td>
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
				'usuarios' => $this->Rcdcmodel->listaTrabajadores(),
				'comunas' => $this->Rcdcmodel->listaComunas(),
				'zonas' => $this->Rcdcmodel->listaZonas(),
				'proyectos' => $this->Rcdcmodel->listaProyectos(),
				'tramos' => $this->Rcdcmodel->listaTramos(),
				'tipos' => $this->Rcdcmodel->listaTipos(),
			);
			$this->load->view('back_end/rcdc/mantenedor',$datos);
		}
	}
	
}