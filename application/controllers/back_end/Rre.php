<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rre extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model("back_end/Rremodel");
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
	      	if($this->session->userdata('id_perfil')>=5){
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
    	$this->Rremodel->insertarVisita($data);
	}

	public function index(){
		$this->visitas("Rre");
    	$this->acceso();

	    $datos = array(
	        'titulo' => "Rre (Registro retiro de equipos)",
	        'contenido' => "rre/inicio",
	        'perfiles' => $this->Iniciomodel->listaPerfiles(),
		);  

		$this->load->view('plantillas/plantilla_back_end',$datos);
	}

	/********* Rre ***********/

		public function getRreInicio(){
			if($this->input->is_ajax_request()){
				$datos = array(
					'proyectos' => $this->Rremodel->listaProyectos(),
					'usuarios' => $this->Rremodel->listaTrabajadores(),
				);
				$this->load->view('back_end/rre/rre',$datos);
			}
		}

		public function getRreList(){
			echo json_encode($this->Rremodel->getRreList());
		}

		public function formRre(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();	
				$hash = $this->security->xss_clean(strip_tags($this->input->post("hash_detalle")));
				$fecha = $this->security->xss_clean(strip_tags($this->input->post("fecha")));
				$comuna = $this->security->xss_clean(strip_tags($this->input->post("comuna")));
				$usuario = $this->security->xss_clean(strip_tags($this->input->post("tecnico")));
				$serie = $this->security->xss_clean(strip_tags($this->input->post("serie")));
				$modelo = $this->security->xss_clean(strip_tags($this->input->post("modelo")));
				$num_cliente = $this->security->xss_clean(strip_tags($this->input->post("num_cliente")));
				$proyecto = $this->security->xss_clean(strip_tags($this->input->post("proyecto")));
				$observacion = $this->security->xss_clean(strip_tags($this->input->post("observacion")));

				if ($this->form_validation->run("formRre") == FALSE){
					echo json_encode(array('res'=>"error", 'msg' => strip_tags(validation_errors())));exit;
				}else{	
					if($hash==""){
						$data = array(
							'fecha' => $fecha,
							'serie' => $serie,
							'comuna' => $comuna,
							'id_usuario' => $usuario,
							'modelo' => $modelo,
							'num_cliente' => $num_cliente,
							'id_proyecto' => $proyecto,
							'observacion' => $observacion,
                        );
						if($this->Rremodel->formIngreso($data)){
							echo json_encode(array('res'=>"ok",  'msg' => OK_MSG));exit;
						}else{
							echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
						}
					}else{
						$data_mod = array(
							'fecha' => $fecha,
							'serie' => $serie,
							'comuna' => $comuna,
							'id_usuario' => $usuario,
							'modelo' => $modelo,
							'num_cliente' => $num_cliente,
							'id_proyecto' => $proyecto,
							'observacion' => $observacion,
                        );
						if($this->Rremodel->formActualizar($hash,$data_mod)){
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
	 
		public function eliminaRre(){
			$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
			if($this->Rremodel->eliminaRre($hash)){
			echo json_encode(array("res" => "ok" , "msg" => "Registro eliminado correctamente."));
			}else{
			echo json_encode(array("res" => "error" , "msg" => "Problemas eliminando el registro, intente nuevamente."));
			}
		}

		public function getDataRre(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();	
				$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
				$data=$this->Rremodel->getDataRre($hash);
				if($data){
					echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
				}else{
					echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
				}	
			}else{
				exit('No direct script access allowed');
			}
		}

		public function excelRre(){
			$data = $this->Rremodel->getRreList();

			if(!$data){
				return FALSE;
			}else{

				$nombre="reporte-Rre.xls";
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

				<h3>Reporte RRE (Registro retiro de equipos)</h3>
				<table align='center' border="1"> 
					<tr style="background-color:#F9F9F9">
						<th class="head">Fecha</th>    
						<th class="head">Usuario</th>    
						<th class="head">Comuna</th>    
						<th class="head">Serie</th>    
						<th class="head">Modelo</th>  
						<th class="head">N&uacute;mero de cliente</th> 
						<th class="head">Proyecto </th> 
						<th class="head">Observaci&oacute;n</th> 
					</tr>
					</thead>	
					<tbody>
					<?php 
						if($data !=FALSE){
							foreach($data as $d){
							?>
								<tr>
									<td><?php echo utf8_decode($d["fecha"]); ?></td>
									<td><?php echo utf8_decode($d["nombre_completo"]); ?></td>
									<td><?php echo utf8_decode($d["comuna"]); ?></td>
									<td><?php echo utf8_decode($d["serie"]); ?></td>
									<td><?php echo utf8_decode($d["modelo"]); ?></td>
									<td><?php echo utf8_decode($d["num_cliente"]); ?></td>
									<td><?php echo utf8_decode($d["proyecto"]); ?></td>
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
}