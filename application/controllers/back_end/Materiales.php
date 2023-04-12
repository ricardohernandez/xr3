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
			"id_aplicacion"=>12,
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
	         	
	       	   /* $this->Materialesmodel->eliminarPeriodoActual($desde,$hasta); */

	            while (($data = fgetcsv($handle, 9999, ";")) !== FALSE) {
				    $ultima_actualizacion=date("Y-m-d G:i:s")." | ".$this->session->userdata("nombre_completo");

	            	if(count($data)!=4){
	            		echo json_encode(array('res'=>'error', "tipo" => "error", 'msg' => "Archivo CSV inválido, 4 columnas esperadas,".count($data)." obtenidas."));exit;
	            	}

					$id_tecnico = $this->Materialesmodel->getIdTecnicoPorRut(str_replace(array('-', '.'), '', $data[0]));

					if(!empty($id_tecnico)){

						$arr=array("id_tecnico"=>$id_tecnico,
							"material"=>utf8_encode($data[1]),
							"serie"=>utf8_encode($data[2]),
							"tipo"=>utf8_encode($data[3]),
							"ultima_actualizacion"=>$ultima_actualizacion,
							
						);	
						$i++;
						$this->Materialesmodel->formMateriales($arr);
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
							<th class="head">Descripci&oacute;n</th> 
				            <th class="head">Serie</th> 
				            <th class="head">Tipo</th> 
				        </tr>
				        </thead>	
						<tbody>
				        <?php 
				        	if($data !=FALSE){
					      		foreach($data as $d){
				      			?>
				      			 <tr>
									<td><?php echo utf8_decode($d["tecnico"]); ?></td>
									<td><?php echo utf8_decode($d["material"]); ?></td>
									<td><?php echo utf8_decode($d["serie"]); ?></td>
									<td><?php echo utf8_decode($d["tipo"]); ?></td>
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


	
	/********* POR TECNICO************/
		
		public function vistaMaterialesPorTecnico(){
			$this->visitas("tecnico");
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
				$datos = array();
				$this->load->view('back_end/materiales/series',$datos);
			}
		}

		public function listaSeriesDevolucion(){
			$desde = $this->security->xss_clean(strip_tags($this->input->get_post("desde")));
			$hasta = $this->security->xss_clean(strip_tags($this->input->get_post("hasta")));
			$trabajador = $this->security->xss_clean(strip_tags($this->input->get_post("trabajador")));
			$id_tecnico = $this->Materialesmodel->getIdTecnicoPorRut(str_replace(array('-', '.'), '', $trabajador));
			$jefe = $this->security->xss_clean(strip_tags($this->input->get_post("jefe")));
			echo json_encode($this->Materialesmodel->listaSeriesDevolucion($desde,$hasta,$id_tecnico,$jefe));
		}	
 
		public function excel_series_devolucion(){
			$trabajador = $this->uri->segment(2);
			$jefe = $this->uri->segment(3);
			
			if($trabajador=="-"){
				$trabajador="";
			}

			if($jefe=="-"){
				$jefe="";
			}

			$id_tecnico = $this->Materialesmodel->getIdTecnicoPorRut(str_replace(array('-', '.'), '', $trabajador));
			$data=$this->Materialesmodel->listaSeriesDevolucion("","",$id_tecnico,$jefe);

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
									 <td><?php echo utf8_decode($d["material"]); ?></td>
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
			echo json_encode($this->Materialesmodel->listaSeriesOperativos($desde,$hasta,$id_tecnico,$jefe));
		}	
 
		public function excel_series_operativos(){
			$trabajador = $this->uri->segment(2);
			$jefe = $this->uri->segment(3);
			
			if($trabajador=="-"){
				$trabajador="";
			}

			if($jefe=="-"){
				$jefe="";
			}

			$id_tecnico = $this->Materialesmodel->getIdTecnicoPorRut(str_replace(array('-', '.'), '', $trabajador));
			$data=$this->Materialesmodel->listaSeriesOperativos("","",$id_tecnico,$jefe);

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
									 <td><?php echo utf8_decode($d["material"]); ?></td>
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