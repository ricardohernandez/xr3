<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use Dompdf\Dompdf;
use Dompdf\Options;

class Checklist_reportes extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if($this->uri->segment("1")==""){
			redirect("inicio");
		}
		$this->load->model("back_end/checklist/Checklist_reportesmodel");
		$this->load->model("back_end/Iniciomodel");
		$this->load->helper(array('fechas','str'));
		$this->load->library('user_agent');
	}

	public function visitas($modulo){
		$this->load->library('user_agent');
		$data=array("id_usuario"=>$this->session->userdata('id'),
			"id_aplicacion"=>22,
			"modulo"=>$modulo,
	     	"fecha"=>date("Y-m-d G:i:s"),
	    	"navegador"=>"navegador :".$this->agent->browser()."\nversion :".$this->agent->version()."\nos :".$this->agent-> platform()."\nmovil :".$this->agent->mobile(),
	    	"ip"=>$this->input->ip_address(),
    	);
    	$this->Checklist_reportesmodel->insertarVisita($data);
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


	/**********CHECKLIST HERRAMIENTAS************/
		
	    public function index(){
	    	$this->acceso();
    	    $datos = array(
		        'titulo' => "Reporte de checkList",
		        'contenido' => "checklist/checklist_reportes/inicio",
		        'perfiles' => $this->Iniciomodel->listaPerfiles(),
			);  
			$this->load->view('plantillas/plantilla_back_end',$datos);
		}
		
		public function getChecklistReportesInicio(){
			$this->visitas("Inicio");
			$desde = date('d-m-Y', strtotime('-365 day', strtotime(date("d-m-Y"))));
	    	$hasta = date('d-m-Y');
			$supervisores=$this->Checklist_reportesmodel->listaSupervisores();
    
			$datos=array(
				'desde' => $desde,	   
		        'hasta' => $hasta,
				'supervisores' => $supervisores,
				'mes_actual' => mesesPeriodo("actual"),
		        'mes_anterior' =>mesesPeriodo("anterior"),
		   	);

			$this->load->view('back_end/checklist/checklist_reportes/reportes',$datos);
		}

		public function listaReporteChecklist(){
			$periodo = $this->security->xss_clean(strip_tags($this->input->get_post("periodo")));
			$supervisor = $this->security->xss_clean(strip_tags($this->input->get_post("supervisor")));

			if(date("d")>"24"){

				if($periodo == "actual"){
					$desde = date('Y-m-d', strtotime(date('Y-m-25')));
					$hasta = date('Y-m-d', strtotime('+1 month', strtotime(date('Y-m-24'))));
				}elseif($periodo == "anterior"){
					$desde = date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-25'))));
					$hasta = date('Y-m-d', strtotime(date('Y-m-24')));
				}

			}else{
				if($periodo == "actual"){
					$desde = date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-25'))));
					$hasta = date('Y-m-d');
				}elseif($periodo == "anterior"){
					$desde = date('Y-m-d', strtotime('-2 month', strtotime(date('Y-m-25'))));
					$hasta = date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-24'))));
				}
			}

			echo json_encode($this->Checklist_reportesmodel->listaReporteChecklist($desde,$hasta,$supervisor));
		}

		public function graficoReporteChecklist(){
			/* $periodo=$this->security->xss_clean(strip_tags($this->input->get_post("periodo")));
			$trabajador=$this->security->xss_clean(strip_tags($this->input->get_post("trabajador")));
			$jefe=$this->security->xss_clean(strip_tags($this->input->get_post("jefe")));
			$perfil_tecnico = $this->Igtmodel->getPerfilTecnico($trabajador);
	
			$rut=str_replace('-', '', $trabajador);
			$id_tecnico = $this->Igtmodel->getIdTecnico($rut);
	
			$array_data = array();
	
	
			$data_calidad_hfc = array();
			
			if($this->Igtmodel->graficoHFC(getFechasPeriodo("actual")["desde_calidad"],getFechasPeriodo("actual")["hasta_calidad"],$trabajador)!=FALSE){
				$data_calidad_hfc[] = $this->Igtmodel->graficoHFC(getFechasPeriodo("actual")["desde_calidad"],getFechasPeriodo("actual")["hasta_calidad"],$trabajador);
			}

		
			$cabeceras_calidad = array(
				"Periodo",
				"Calidad",
				"Ordenes",
				"Fallos",
				array('role'=> 'annotationText'),
			);

			$list = array();
			$list[] = $cabeceras_calidad;

			foreach($data_calidad_hfc as $arr) {
				if(is_array($arr)) {
					$list = array_merge($list, $arr);
				}
			}

			echo json_encode($array_data); */

			$periodo = $this->security->xss_clean(strip_tags($this->input->get_post("periodo")));
			$supervisor = $this->security->xss_clean(strip_tags($this->input->get_post("supervisor")));

			if(date("d")>"24"){

				if($periodo == "actual"){
					$desde = date('Y-m-d', strtotime(date('Y-m-25')));
					$hasta = date('Y-m-d', strtotime('+1 month', strtotime(date('Y-m-24'))));
				}elseif($periodo == "anterior"){
					$desde = date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-25'))));
					$hasta = date('Y-m-d', strtotime(date('Y-m-24')));
				}

			}else{
				if($periodo == "actual"){
					$desde = date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-25'))));
					$hasta = date('Y-m-d');
				}elseif($periodo == "anterior"){
					$desde = date('Y-m-d', strtotime('-2 month', strtotime(date('Y-m-25'))));
					$hasta = date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-24'))));
				}
			}
 
			echo json_encode($this->Checklist_reportesmodel->graficoReporteChecklist($desde,$hasta,$supervisor));

		}
 
		public function excel_reporte_checklist(){
			$desde=$this->uri->segment(2);
			$hasta=$this->uri->segment(3);
			$desde=date("Y-m-d",strtotime($desde));
			$hasta=date("Y-m-d",strtotime($hasta));

			$data=$this->Checklist_reportesmodel->listaOTSDetalle($desde,$hasta);

			if(!$data){
				return FALSE;
			}else{

				$nombre="reporte-checklist-herramientas".date("d-m-Y",strtotime($desde))."-".date("d-m-Y",strtotime($hasta)).".xls";
				header("Content-type: application/vnd.ms-excel;  charset=utf-8");
				header("Content-Disposition: attachment; filename=$nombre");
				?>
				<style type="text/css">
				.head{font-size:13px;height: 30px; background-color:#32477C;color:#fff; font-weight:bold;padding:10px;margin:10px;vertical-align:middle;}
				td{font-size:12px;text-align:center;   vertical-align:middle;}
				
				</style>
				<h3>Reporte checklist herramientas <?php echo date("d-m-Y",strtotime($desde)); ?> - <?php echo date("d-m-Y",strtotime($hasta)); ?></h3>

					<table align='center' border="1"> 

				        <tr style="background-color:#F9F9F9">
			                <th class="head">Auditor</th> 
				            <th class="head">Auditor cargo</th>   
				            <th class="head">Fecha</th>   
				            <th class="head">T&eacute;cnico</th>   
				            <th class="head">T&eacute;cnico zona</th>   
				            <th class="head">T&eacute;cnico c&oacute;digo</th>   
				            <th class="head">T&eacute;cnico Proyecto</th>   
				          
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
									 <td><?php echo utf8_decode($d["proyecto"]); ?></td>

									 <td><?php echo utf8_decode($d["tipo"]); ?></td>
									 <td><?php echo utf8_decode($d["descripcion"]); ?></td>
									 <td><?php echo utf8_decode($d["estado_str"]); ?></td>
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