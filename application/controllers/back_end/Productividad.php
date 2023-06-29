<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Productividad extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if($this->uri->segment("1")==""){
			redirect("inicio");
		}
		$this->load->model("back_end/Productividadmodel");
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
    	$this->Productividadmodel->insertarVisita($data);
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

	
	/*********PRODUCTIVIDAD************/
		
	    public function index(){
	    	$this->acceso();

    	    $datos = array(
		        'titulo' => "Productividad XR3",
		        'contenido' => "productividad/inicio",
		        'perfiles' => $this->Iniciomodel->listaPerfiles(),
			);
			
			$this->load->view('plantillas/plantilla_back_end',$datos);

		}

		public function vistaDetalle(){
			$this->visitas("detalle");
			if($this->input->is_ajax_request()){
				if(date("d")>"24"){

					$desde_actual = date('d-m-Y', strtotime(date('Y-m-25')));
					$hasta_actual = date('d-m-Y', strtotime('+1 month', strtotime(date('Y-m-24'))));

					$desde_anterior = date('d-m-Y', strtotime('-1 month', strtotime(date('Y-m-25'))));
					$hasta_anterior = date('d-m-Y', strtotime(date('Y-m-24')));

					$desde_anterior_2 = date('d-m-Y', strtotime('-2 month', strtotime(date('Y-m-25'))));
					$hasta_anterior_2 = date('d-m-Y', strtotime('-1 month', strtotime(date('Y-m-24'))));

				}else{
					
					$desde_actual = date('d-m-Y', strtotime('-1 month', strtotime(date('Y-m-25'))));
					$hasta_actual = date('d-m-Y',  strtotime(date('Y-m-24')));

					$desde_anterior = date('d-m-Y', strtotime('-2 month', strtotime(date('Y-m-25'))));
					$hasta_anterior = date('d-m-Y', strtotime('-1 month', strtotime(date('Y-m-24'))));

					$desde_anterior_2 = date('d-m-Y', strtotime('-3 month', strtotime(date('Y-m-25'))));
					$hasta_anterior_2 = date('d-m-Y', strtotime('-2 month', strtotime(date('Y-m-24'))));
				}
				
				$datos = array(	
					'desde_actual' => $desde_actual,
			        'hasta_actual' => $hasta_actual,

			        'desde_anterior' => $desde_anterior,
			        'hasta_anterior' => $hasta_anterior,

					'desde_anterior2' => $desde_anterior_2,
			        'hasta_anterior2' => $hasta_anterior_2,

       		        'jefes' => $this->Productividadmodel->listaJefes(),

       		        'mes_actual' => mesesPeriodo("actual"),
		       	    'mes_anterior' =>mesesPeriodo("anterior"),
		       	    'mes_anterior2' =>mesesPeriodo("anterior2"),
			    );

				$this->load->view('back_end/productividad/productividad_detalle',$datos);
			}
		}

		/* public function formCargaMasivaDetalle(){

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
	         	

	         	if(date("d")>"24"){
					$desde = date('Y-m-d', strtotime('-0 month', strtotime(date('Y-m-25'))));
					$hasta = date('Y-m-d', strtotime(date('Y-m-24')));
				}else{
					$desde = date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-25'))));
					$hasta = date('Y-m-d', strtotime('-0 month', strtotime(date('Y-m-24'))));
				}

	         	$this->Productividadmodel->eliminarPeriodoActual($desde,$hasta);
	         	$this->Productividadmodel->truncateTablaCargas();

	            while (($data = fgetcsv($handle, 9999, ";")) !== FALSE) {
				    $ultima_actualizacion=date("Y-m-d G:i:s")." | ".$this->session->userdata("nombre_completo");

	            	if(count($data)!=13){
	            		echo json_encode(array('res'=>'error', "tipo" => "error", 'msg' => "Archivo CSV inválido, 13 columnas esperadas,".count($data)." obtenidas."));exit;
	            	}

	                // if(!empty($data[0])){
                	    $rut = $data[0];
						$rutf=str_replace('-', '', $rut);

						if (str_contains($data[1], '/')) { 

							$fecha=explode('/',trim($data[1]));
						    $dia=$fecha[0];
							$mes=$fecha[1]; 
							$anio=$fecha[2]; 
							$fechaf = $anio."-".$mes."-".$dia;

						}elseif(str_contains($data[1], '-')){

							$fecha=explode('-',trim($data[1]));
							$dia=$fecha[0];
							$mes=$fecha[1]; 
							$anio=$fecha[2]; 
							$fechaf = $anio."-".$mes."-".$dia;

						}else{
							$fechaf = "1970-01-01";
						}

						// echo $fechaf;
						// echo "<br>";

					    $arr=array("rut_tecnico"=>$rutf,
							"fecha"=>$fechaf,
							"direccion"=>$data[2],
							"tipo_actividad"=>utf8_encode($data[3]),
							"comuna"=>$data[4],
							"estado"=>$data[5],
							"derivado"=>$data[6],
							"puntaje"=>$data[7],
							"ot"=>$data[8],
							"estado_ot"=>$data[9],
							"categoria"=>$data[10],
							"equivalente"=>$data[11],
							"tecnologia"=>$data[12],
							"ultima_actualizacion"=>$ultima_actualizacion
						);	

				    	$this->Productividadmodel->formDetalle($arr);
				    	$i++;
				
				  	 	$arr=array();
		            // }
	            }

	            if($i==0){
	            	echo json_encode(array('res'=>'ok', "tipo" => "success", 'msg' => $i." filas insertadas."));exit;
	            }

	            fclose($handle); 
	            
	            $this->enviaCorreo();

	           	echo json_encode(array('res'=>'ok', "tipo" => "success", 'msg' => "Archivo cargado con éxito, ".$i." filas insertadas."));exit;
	        }else{
	        	echo json_encode(array('res'=>'error', "tipo" => "error", 'msg' => "Archivo CSV inválido."));exit;
	        }   
		} */

		public function formCargaMasivaDetalle(){

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
	         	

	         	if(date("d")>"24"){
					$desde = date('Y-m-d', strtotime('-0 month', strtotime(date('Y-m-25'))));
					$hasta = date('Y-m-d', strtotime(date('Y-m-24')));
				}else{
					$desde = date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-25'))));
					$hasta = date('Y-m-d', strtotime('-0 month', strtotime(date('Y-m-24'))));
				}

	         	$this->Productividadmodel->eliminarPeriodoActual($desde,$hasta);

	            while (($data = fgetcsv($handle, 9999, ";")) !== FALSE) {
				    $ultima_actualizacion=date("Y-m-d G:i:s")." | ".$this->session->userdata("nombre_completo");

	            	if(count($data)!=13){
	            		echo json_encode(array('res'=>'error', "tipo" => "error", 'msg' => "Archivo CSV inválido, 13 columnas esperadas,".count($data)." obtenidas."));exit;
	            	}

	                // if(!empty($data[0])){
                	    $rut = $data[0];
						$rutf=str_replace('-', '', $rut);

						if (str_contains($data[1], '/')) { 

							$fecha=explode('/',trim($data[1]));
						    $dia=$fecha[0];
							$mes=$fecha[1]; 
							$anio=$fecha[2]; 
							$fechaf = $anio."-".$mes."-".$dia;

						}elseif(str_contains($data[1], '-')){

							$fecha=explode('-',trim($data[1]));
							$dia=$fecha[0];
							$mes=$fecha[1]; 
							$anio=$fecha[2]; 
							$fechaf = $anio."-".$mes."-".$dia;

						}else{
							$fechaf = "1970-01-01";
						}

						// echo $fechaf;
						// echo "<br>";

					    $arr=array("rut_tecnico"=>$rutf,
							"fecha"=>$fechaf,
							"direccion"=>$data[2],
							"tipo_actividad"=>utf8_encode($data[3]),
							"comuna"=>$data[4],
							"estado"=>$data[5],
							"derivado"=>$data[6],
							"puntaje"=>$data[7],
							"ot"=>$data[8],
							"estado_ot"=>$data[9],
							"categoria"=>$data[10],
							"equivalente"=>$data[11],
							"tecnologia"=>$data[12],
							"ultima_actualizacion"=>$ultima_actualizacion
						);	

					    if(!$this->Productividadmodel->existeOrden($data[8])){
					    	$this->Productividadmodel->formDetalle($arr);
					    	$i++;
					    }
				  	 	
				  	 	$arr=array();
		            // }
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



		public function cargaPlanillaProductividad(){

			/*if(date("d")>"24"){
				$desde = date('Y-m-d', strtotime('-0 month', strtotime(date('Y-m-25'))));
				$hasta = date('Y-m-d', strtotime(date('Y-m-24')));
			}else{
				$desde = date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-25'))));
				$hasta = date('Y-m-d', strtotime('-0 month', strtotime(date('Y-m-24'))));
			}
			*/
			$data = $this->Productividadmodel->getDataProductividadCarga();
			/*$data_prod = $this->Productividadmodel->getDataProductividadCarga($desde,$hasta);*/
			$i=0;
			foreach($data as $data){

			    if(!$this->Productividadmodel->existeOrden($data["ot"])){

				    $arr=array("rut_tecnico"=>$data["rut_tecnico"],
						"fecha"=>$data["fecha"],
						"direccion"=>$data["direccion"],
						"tipo_actividad"=>$data["tipo_actividad"],
						"comuna"=>$data["comuna"],
						"estado"=>$data["estado"],
						"derivado"=>$data["derivado"],
						"puntaje"=>$data["puntaje"],
						"ot"=>$data["ot"],
						"estado_ot"=>$data["estado_ot"],
						"categoria"=>$data["categoria"],
						"equivalente"=>$data["equivalente"],
						"tecnologia"=>$data["tecnologia"],
						"ultima_actualizacion"=>$data["ultima_actualizacion"]
					);	

					$this->Productividadmodel->formProductividad($arr);
					$i++;
				}
			}

			echo $i." Filas insertadas";

		   /* $host = $_SERVER['HTTP_HOST'];
			$ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
			$html = 'productividad';
			$url = "http://$host$ruta/$html";
			header("Location: $url");*/

		}

		public function actualizacionProductividad(){
		    if($this->input->is_ajax_request()){
		      $data=$this->Productividadmodel->actualizacionProductividad();
		      if($data){
		        echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
		      }else{
		        echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
		      } 
		    }else{
		      exit('No direct script access allowed');
		    }
		}

		public function listaDetalle(){
			$periodo=$this->security->xss_clean(strip_tags($this->input->get_post("periodo")));
			$trabajador=$this->security->xss_clean(strip_tags($this->input->get_post("trabajador")));
			$jefe=$this->security->xss_clean(strip_tags($this->input->get_post("jefe")));

			if(date("d")>"24"){

				if($periodo=="actual"){

					$desde = date('Y-m-d', strtotime(date('Y-m-25')));
					$hasta = date('Y-m-d', strtotime('+1 month', strtotime(date('Y-m-24'))));

				}elseif($periodo=="anterior"){

					$desde = date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-25'))));
					$hasta= date('Y-m-d', strtotime(date('Y-m-24')));
					
				}elseif($periodo=="anterior2"){

					$desde = date('Y-m-d', strtotime('-2 month', strtotime(date('Y-m-25'))));
					$hasta = date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-24'))));

				}

			}else{

				if($periodo=="actual"){

					$desde= date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-25'))));
					$hasta= date('Y-m-d');

				}elseif($periodo=="anterior"){

					$desde= date('Y-m-d', strtotime('-2 month', strtotime(date('Y-m-25'))));
					$hasta= date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-24'))));

				}elseif($periodo=="anterior_2"){

					$desde= date('Y-m-d', strtotime('-3 month', strtotime(date('Y-m-25'))));
					$hasta= date('Y-m-d', strtotime('-2 month', strtotime(date('Y-m-24'))));
				}
			}

			echo json_encode($this->Productividadmodel->listaDetalle($desde,$hasta,$trabajador,$jefe));

		}	

		public function vistaGraficosProd(){
			$this->visitas("Graficos");
			if($this->input->is_ajax_request()){

				if(date("d")>"24"){
					$desde_actual = date('d-m-Y', strtotime(date('Y-m-25')));
					$hasta_actual = date('d-m-Y', strtotime('+1 month', strtotime(date('Y-m-24'))));
					$desde_anterior = date('d-m-Y', strtotime('-1 month', strtotime(date('Y-m-25'))));
					$hasta_anterior = date('d-m-Y', strtotime(date('Y-m-24')));
				}else{
					$desde_actual = date('d-m-Y', strtotime('-1 month', strtotime(date('Y-m-25'))));
					$hasta_actual = date('d-m-Y',  strtotime(date('Y-m-24')));
					$desde_anterior = date('d-m-Y', strtotime('-2 month', strtotime(date('Y-m-25'))));
					$hasta_anterior = date('d-m-Y', strtotime('-1 month', strtotime(date('Y-m-24'))));
				}

				$datos=array(	
					'desde_actual' => $desde_actual,
			        'hasta_actual' => $hasta_actual,
			        'desde_anterior' => $desde_anterior,
			        'hasta_anterior' => $hasta_anterior,
     		        'jefes' => $this->Productividadmodel->listaJefes(),
     		        
     		        'mes_actual' => mesesPeriodo("actual"),
		       	    'mes_anterior' =>mesesPeriodo("anterior"),
		       	    'mes_anterior2' =>mesesPeriodo("anterior2"),
			    );
				$this->load->view('back_end/productividad/graficos',$datos);
			}
		}

		public function dataGraficos(){
			$periodo=$this->security->xss_clean(strip_tags($this->input->get_post("periodo")));
			$trabajador=$this->security->xss_clean(strip_tags($this->input->get_post("trabajador")));
			$jefe=$this->security->xss_clean(strip_tags($this->input->get_post("jefe")));

			if(date("d")>"24"){
				if($periodo=="actual"){
					$desde = date('Y-m-d', strtotime(date('Y-m-25')));
					$hasta = date('Y-m-d', strtotime('+1 month', strtotime(date('Y-m-24'))));
				}elseif($periodo=="anterior"){
					$desde = date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-25'))));
					$hasta= date('Y-m-d', strtotime(date('Y-m-24')));
				}
				
			}else{
				if($periodo=="actual"){
					$desde= date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-25'))));
					$hasta= date('Y-m-24');
				}elseif($periodo=="anterior"){
					$desde= date('Y-m-d', strtotime('-2 month', strtotime(date('Y-m-25'))));
					$hasta= date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-24'))));
				}
			}

			echo json_encode(array(
				"desde" => date('d-m-Y', strtotime($desde)),
				"hasta" => date('d-m-Y', strtotime($hasta)),
				"ranking" => $this->Productividadmodel->rankingTecnicos($desde,$hasta,$trabajador,$jefe),
				"totalpuntos" => $this->Productividadmodel->totalPuntosPorFecha($desde,$hasta,$trabajador,$jefe),
				"puntosPorFechas" => $this->Productividadmodel->puntosPorFechas($desde,$hasta,$trabajador,$jefe),
				"puntosTipoOrden" => $this->Productividadmodel->puntosTipoOrden($desde,$hasta,$trabajador,$jefe),
				"distribucionTipos" => $this->Productividadmodel->distribucionTipos($desde,$hasta,$trabajador,$jefe),
				"distribucionOt" => $this->Productividadmodel->distribucionOt($desde,$hasta,$trabajador,$jefe),
			));
		}

		public function listaTrabajadoresProd(){
			$jefe=$this->security->xss_clean(strip_tags($this->input->get_post("jefe")));
     	    echo $this->Productividadmodel->listaTrabajadoresProd($jefe);exit;
		}

		public function excel_detalle(){
			$periodo = $this->uri->segment(2);
			$trabajador = $this->uri->segment(3);
			$jefe = $this->uri->segment(4);
			
			if($trabajador=="-"){
				$trabajador="";
			}

			if($jefe=="-"){
				$jefe="";
			}

			if(date("d")>"24"){
				if($periodo == "actual"){
					$desde = date('Y-m-d', strtotime(date('Y-m-25')));
					$hasta = date('Y-m-d', strtotime('+1 month', strtotime(date('Y-m-24'))));
				}elseif($periodo == "anterior"){
					$desde = date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-25'))));
					$hasta = date('Y-m-d', strtotime(date('Y-m-24')));
				}elseif($periodo == "anterior2"){
					$desde = date('Y-m-d', strtotime('-2 month', strtotime(date('Y-m-25'))));
					$hasta = date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-24'))));
				}
				
			}else{
				if($periodo == "actual"){
					$desde = date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-25'))));
					$hasta = date('Y-m-d');
				}elseif($periodo == "anterior"){
					$desde = date('Y-m-d', strtotime('-2 month', strtotime(date('Y-m-25'))));
					$hasta = date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-24'))));
				}elseif($periodo == "anterior2"){
					$desde = date('Y-m-d', strtotime('-3 month', strtotime(date('Y-m-25'))));
					$hasta = date('Y-m-d', strtotime('-2 month', strtotime(date('Y-m-24'))));
				}
			}

			$data=$this->Productividadmodel->listaDetalle($desde,$hasta,$trabajador,$jefe);

			if(!$data){
				echo "Sin datos disponibles.";
				return FALSE;
			}else{

				$nombre="reporte-detalle-".date("d-m-Y",strtotime($desde))."-".date("d-m-Y",strtotime($hasta)).".xls";
				header("Content-type: application/vnd.ms-excel;  charset=utf-8");
				header("Content-Disposition: attachment; filename=$nombre");

				?>
				<style type="text/css">
				.head{font-size:13px;height: 30px; background-color:#32477C;color:#fff; font-weight:bold;padding:10px;margin:10px;vertical-align:middle;}
				td{font-size:12px;text-align:center;   vertical-align:middle;}
				</style>
				<h3>Reporte detalle productividad <?php echo date("d-m-Y",strtotime($desde)); ?> - <?php echo date("d-m-Y",strtotime($hasta)); ?></h3>
					<table align='center' border="1"> 
				        <tr style="background-color:#F9F9F9">
			                <th class="head">T&eacute;cnico</th> 
				            <th class="head">Fecha</th> 
				            <th class="head">Direcci&oacute;n</th> 
				            <th class="head">Tipo actividad</th> 
				            <th class="head">Comuna</th> 
				            <th class="head">Estado</th> 
				            <th class="head">Derivado</th> 
				            <th class="head">Puntaje</th> 
				            <th class="head">Orden de Trabajo</th> 
				            <th class="head">Digitalizaci&oacute;n OT</th>  
				            <th class="head">Categor&iacute;a</th>   
				            <th class="head">Equivalente</th>   
				            <th class="head">Tecnolog&iacute;a</th>   
				        </tr>
				        </thead>	
						<tbody>
				        <?php 
				        	if($data !=FALSE){
					      		foreach($data as $d){
				      			?>
				      			 <tr>
									 <td><?php echo utf8_decode($d["tecnico"]); ?></td>
									 <td><?php echo utf8_decode($d["fecha"]); ?></td>
									 <td><?php echo utf8_decode($d["direccion"]); ?></td>
									 <td><?php echo utf8_decode($d["tipo_actividad"]); ?></td>
									 <td><?php echo utf8_decode($d["comuna"]); ?></td>
									 <td><?php echo utf8_decode($d["estado"]); ?></td>
									 <td><?php echo utf8_decode($d["derivado"]); ?></td>
									 <td><?php echo utf8_decode($d["puntaje"]); ?></td>
									 <td><?php echo utf8_decode($d["ot"]); ?></td>
									 <td><?php echo utf8_decode($d["estado_ot"]); ?></td>
									 <td><?php echo utf8_decode($d["categoria"]); ?></td>
									 <td><?php echo utf8_decode($d["equivalente"]); ?></td>
									 <td><?php echo utf8_decode($d["tecnologia"]); ?></td>
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


	/*********RESUMEN****************/


		public function vistaResumen(){
			$this->visitas("resumen");
			if($this->input->is_ajax_request()){
				$datos=array(	
			        'jefes' => $this->Productividadmodel->listaJefes(),
			        'mes_actual' => mesesPeriodo("actual"),
		       	    'mes_anterior' =>mesesPeriodo("anterior"),
		       	    'mes_anterior2' =>mesesPeriodo("anterior2"),
			    );
				$this->load->view('back_end/productividad/resumen',$datos);
			}
		}

		public function getCabeceras(){
			$data=json_decode(file_get_contents('php://input'),1);
			$periodo=@$data["periodo"];

			/* echo "<pre>";
			print_r($this->Productividadmodel->cabecerasResumen(getFechasPeriodo($periodo)["desde_prod"],getFechasPeriodo($periodo)["hasta_prod"]));exit; */
		 	echo json_encode(array("data" =>$this->Productividadmodel->cabecerasResumen(getFechasPeriodo($periodo)["desde_prod"],getFechasPeriodo($periodo)["hasta_prod"])));
		}

		public function listaResumen(){
			$periodo = $this->input->get_post("periodo");
			$trabajador = $this->input->get_post("trabajador");
			$jefe = $this->input->get_post("jefe");
			
			$fechasPeriodo = getFechasPeriodo($periodo);
			$res = $this->Productividadmodel->getResumen($fechasPeriodo["desde_prod"], $fechasPeriodo["hasta_prod"], $jefe, $trabajador);
			
			$data = array_map(function($row) {
				
				
				$detalleArray = array_map(fn($item) => [fecha_to_str(explode(':', $item)[0]) => explode(':', $item)[1]], explode(',', $row['Detalle']));

				return [
					'Zona' => $row['Zona'],
					'Trabajador' => $row['Trabajador'],
					'Total' => $row['Total'],
					'Días' => $row['Días'],
				] + array_merge(...$detalleArray);

			}, $res);
			
			echo json_encode([
				'data' => $data,
				'periodo' => periodoFechas($fechasPeriodo['desde_prod'], $fechasPeriodo['hasta_prod']),
				'actualizacion' => $this->Productividadmodel->actualizacionProductividad()
			]);exit;
			
		}

		public function listaResumenOld(){
			$periodo=$this->input->get_post("periodo");
			$trabajador=$this->input->get_post("trabajador");
			$jefe=$this->input->get_post("jefe");

			$data = $this->Productividadmodel->dataResumenProductividad(getFechasPeriodo($periodo)["desde_prod"],getFechasPeriodo($periodo)["hasta_prod"],$trabajador,$jefe);
			$array = array();


			
			
		 /* 	$periodo = "actual";
			$data2 = $this->Productividadmodel->dataResumenProductividad(getFechasPeriodo($periodo)["desde_prod"],getFechasPeriodo($periodo)["hasta_prod"],"","");
			echo "<pre>";
			print_r($data2);exit;*/
 
		
 
			$puntajes = array();
			foreach($data as $dato){
				$temp = array();
				$temp["Zona"] = $dato["area"];
				$temp["Trabajador"] = $dato["trabajador"];
				/*$temp["Promedio"] = $dato["promedio"];*/
				$temp["Total"] = $dato["total"];
				$temp["Días"] = $dato["dias"];
				$dias = $this->Productividadmodel->detalleDiarioProductividad(getFechasPeriodo($periodo)["desde_prod"],getFechasPeriodo($periodo)["hasta_prod"],$dato["rut_tecnico"],$jefe);

 				foreach($dias as $dia){
					$temp[fecha_to_str($dia["fecha"])] = $dia["puntos"];
					
					if($dia["puntos"]!=0){
						$puntajes[] = $dia["puntos"];
					}

 				} 
				$array[] = $temp;
 				$puntajes = [];
			}

			echo json_encode(array(
				"data" =>($array),
				"periodo" =>periodoFechas(getFechasPeriodo($periodo)["desde_prod"],getFechasPeriodo($periodo)["hasta_prod"]),
				"actualizacion" => $this->Productividadmodel->actualizacionProductividad()
			));exit;
		}

	}