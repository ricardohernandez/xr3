<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Calidad extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if($this->uri->segment("1")==""){
			redirect("inicio");
		}
		$this->load->model("back_end/Calidadmodel");
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
    	$this->Calidadmodel->insertarVisita($data);
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
    	$this->acceso();
	    $datos = array(
	        'titulo' => "Calidad XR3",
	        'contenido' => "calidad/inicio",
	        'perfiles' => $this->Iniciomodel->listaPerfiles(),
		);  
		$this->load->view('plantillas/plantilla_back_end',$datos);
		
	}

	/***********DETALLE CALIDAD*****************/

		public function vistaCalidad(){

			$this->visitas("Calidad");
			if($this->input->is_ajax_request()){

				if(date("d")>"24"){
					$desde_actual = date('d-m-Y', strtotime('-1 month', strtotime(date('Y-m-25'))));
					$hasta_actual = date('d-m-Y', strtotime(date('Y-m-24')));
					$desde_anterior = date('d-m-Y', strtotime('-2 month', strtotime(date('Y-m-25'))));
					$hasta_anterior = date('d-m-Y', strtotime('-1 month', strtotime(date('Y-m-24'))));
				}else{
					$desde_actual = date('d-m-Y', strtotime('-2 month', strtotime(date('Y-m-25'))));
					$hasta_actual = date('d-m-Y', strtotime('-1 month', strtotime(date('Y-m-24'))));
					$desde_anterior = date('d-m-Y', strtotime('-3 month', strtotime(date('Y-m-25'))));
					$hasta_anterior = date('d-m-Y', strtotime('-2 month', strtotime(date('Y-m-24'))));
				}
				
				$datos=array(	
					'desde_actual' => $desde_actual,
			        'hasta_actual' => $hasta_actual,
			        'desde_anterior' => $desde_anterior,
			        'hasta_anterior' => $hasta_anterior,
			        'jefes' => $this->Calidadmodel->listaJefes(),

			        'mes_actual' => mesesPeriodoCalidad("actual"),
		       	    'mes_anterior' =>mesesPeriodoCalidad("anterior"),

			    );
				$this->load->view('back_end/calidad/calidad_detalle',$datos);
			}
		}

		public function formCargaMasivaCalidad(){

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
	            $i=0;$z=0;  
	         	

	         	if(date("d")>"24"){
					$desde = date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-25'))));
					$hasta = date('Y-m-d', strtotime(date('Y-m-24')));
				}else{
					$desde = date('Y-m-d', strtotime('-2 month', strtotime(date('Y-m-25'))));
					$hasta = date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-24'))));
				}

	         	$this->Calidadmodel->eliminarPeriodoActual($desde,$hasta);

	            while (($data = fgetcsv($handle, 9999, ";")) !== FALSE) {
				    $ultima_actualizacion=date("Y-m-d G:i:s")." | ".$this->session->userdata("nombre_completo");

	            	if(count($data)!=13){
	            		echo json_encode(array('res'=>'error', "tipo" => "error", 'msg' => "Archivo CSV inválido, 13 columnas esperadas,".count($data)." obtenidas."));exit;
	            	}

	                if(!empty($data[0])){
	            	    $rut = $data[0];
						$rutf=str_replace('-', '', $rut);

						if (str_contains($data[3], '/')) { 

							$fecha=explode('/',trim($data[3]));
						    $dia=$fecha[0];
							$mes=$fecha[1]; 
							$anio=$fecha[2]; 
							$fechaf1 = $anio."/".$mes."/".$dia;

						}elseif(str_contains($data[3], '-')){

							$fecha=explode('-',trim($data[3]));
							$dia=$fecha[0];
							$mes=$fecha[1]; 
							$anio=$fecha[2]; 
							$fechaf1 = $anio."-".$mes."-".$dia;

						}else{
							$fechaf1 = "1970-01-01";
						}

						if (str_contains($data[7], '/')) { 

							$fecha=explode('/',trim($data[7]));
						    $dia=$fecha[0];
							$mes=$fecha[1]; 
							$anio=$fecha[2]; 
							$fechaf2 = $anio."/".$mes."/".$dia;

						}elseif(str_contains($data[7], '-')){

							$fecha=explode('-',trim($data[7]));
							$dia=$fecha[0];
							$mes=$fecha[1]; 
							$anio=$fecha[2]; 
							$fechaf2 = $anio."-".$mes."-".$dia;

						}else{
							$fechaf2 = "1970-01-01";
						}

						if(trim($data[11]==0) || trim($data[11]=="0")){
							$falla ="no";
						}elseif(trim($data[11]==1) || trim($data[11]=="1")){
							$falla = "si";
						}else{
							$falla = "error";
						}

						// echo $fechaf;
						// echo "<br>";

					    $arr=array(
					    	"rut_tecnico"=>$rutf,
							"comuna"=>$data[1],
							"ot"=>$data[2],
							"fecha"=>$fechaf1,
							"descripcion"=>($data[4]),
							"cierre"=>$data[5],
							"ot_2davisita"=>$data[6],
							"fecha_2davisita"=>$fechaf2,
							"descripcion_2davisita"=>($data[8]),
							"cierre_2davisita"=>$data[9],
							"diferencia_dias"=>$data[12],
							"tipo_red"=>$data[10],
							"falla"=>$falla,
							"ultima_actualizacion"=>$ultima_actualizacion
						);	


					    if(!$this->Calidadmodel->existeOrdenCalidad($data[2])){
					    	$this->Calidadmodel->formCalidad($arr);
					    	$i++;
					    }/*else{

					    	$data_bd = $this->Calidadmodel->dataPorOt(trim($data[2]));

					    	if($data_bd!=FALSE){
					    		foreach($data_bd as $d){
						    		if($d["falla"]=="no" and $falla=="si"){

						    			$data_mod = array(
											"ot_2davisita"=>$data[6],
											"fecha_2davisita"=>$fechaf2,
											"descripcion_2davisita"=>($data[8]),
											"cierre_2davisita"=>$data[9],
											"diferencia_dias"=>$data[12],
											"falla"=>$falla,
											"ultima_actualizacion"=>$ultima_actualizacion
										);	

										if($this->Calidadmodel->formActualizaCalidad($d["id"], $data_mod)){
											$data_mod = array();
											$z++;
										}
						    		}
						    	}
					    	}

					    }*/

				  	 	$arr=array();
		            }
	            }

	            fclose($handle); 
	           	echo json_encode(array('res'=>'ok', "tipo" => "success", 'msg' => "Archivo cargado con éxito, ".$i." filas insertadas."));exit;

	        }else{
	        	echo json_encode(array('res'=>'error', "tipo" => "error", 'msg' => "Archivo CSV inválido."));exit;
	        }   
		}

		public function actualizacionCalidad(){
		    if($this->input->is_ajax_request()){
		      $data=$this->Calidadmodel->actualizacionCalidad();
		      if($data){
		        echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
		      }else{
		        echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
		      } 
		    }else{
		      exit('No direct script access allowed');
		    }
		}

		public function listaCalidad(){
			$periodo=$this->security->xss_clean(strip_tags($this->input->get_post("periodo")));
			$trabajador=$this->security->xss_clean(strip_tags($this->input->get_post("trabajador")));
			$jefe=$this->security->xss_clean(strip_tags($this->input->get_post("jefe")));

			if(date("d")>"24"){
				if($periodo=="actual"){
					$desde = date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-25'))));
					$hasta = date('Y-m-d', strtotime(date('Y-m-24')));
				}elseif($periodo=="anterior"){
					$desde = date('Y-m-d', strtotime('-2 month', strtotime(date('Y-m-25'))));
					$hasta = date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-24'))));
				}

			}else{
				if($periodo=="actual"){
					$desde = date('Y-m-d', strtotime('-2 month', strtotime(date('Y-m-25'))));
					$hasta = date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-24'))));
				}elseif($periodo=="anterior"){
					$desde = date('Y-m-d', strtotime('-3 month', strtotime(date('Y-m-25'))));
					$hasta = date('Y-m-d', strtotime('-2 month', strtotime(date('Y-m-24'))));
				}
			}

			echo json_encode($this->Calidadmodel->listaCalidad($desde,$hasta,$trabajador,$jefe));
		}	

		public function listaTrabajadoresCalidad(){
			$jefe=$this->security->xss_clean(strip_tags($this->input->get_post("jefe")));
	 	    echo $this->Calidadmodel->listaTrabajadoresCalidad($jefe);exit;
		}


		public function excel_calidad(){
			$periodo=$this->uri->segment(2);
			$trabajador=$this->uri->segment(3);
			$jefe=$this->uri->segment(4);

			if($trabajador=="-"){
				$trabajador="";
			}

			if($jefe=="-"){
				$jefe="";
			}

			if(date("d")>"24"){
				if($periodo=="actual"){
					$desde = date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-25'))));
					$hasta = date('Y-m-d', strtotime(date('Y-m-24')));
				}elseif($periodo=="anterior"){
					$desde = date('Y-m-d', strtotime('-2 month', strtotime(date('Y-m-25'))));
					$hasta = date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-24'))));
				}

			}else{
				if($periodo=="actual"){
					$desde = date('Y-m-d', strtotime('-2 month', strtotime(date('Y-m-25'))));
					$hasta = date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-24'))));
				}elseif($periodo=="anterior"){
					$desde = date('Y-m-d', strtotime('-3 month', strtotime(date('Y-m-25'))));
					$hasta = date('Y-m-d', strtotime('-2 month', strtotime(date('Y-m-24'))));
				}
			}

			$data=$this->Calidadmodel->listaCalidad($desde,$hasta,$trabajador,$jefe);

			if(!$data){
				echo "Sin datos disponibles.";
				return FALSE;
			}else{

				$nombre="reporte-calidad-".date("d-m-Y",strtotime($desde))."-".date("d-m-Y",strtotime($hasta)).".xls";
				header("Content-type: application/vnd.ms-excel;  charset=utf-8");
				header("Content-Disposition: attachment; filename=$nombre");
				?>
				<style type="text/css">
				.head{font-size:13px;height: 30px; background-color:#32477C;color:#fff; font-weight:bold;padding:10px;margin:10px;vertical-align:middle;}
				td{font-size:12px;text-align:center;   vertical-align:middle;}
				
				</style>
				<h3>Reporte calidad <?php echo date("d-m-Y",strtotime($desde)); ?> - <?php echo date("d-m-Y",strtotime($hasta)); ?></h3>
					<table align='center' border="1"> 
				        <tr style="background-color:#F9F9F9">
			                <th class="head">T&eacute;cnico</th> 
				            <th class="head">Comuna</th> 
				            <th class="head">Orden</th> 
				            <th class="head">Fecha</th> 
				            <th class="head">Descripci&oacute;n</th> 
				            <th class="head">Cierre</th> 
				            <th class="head">Orden 2da vis.</th> 
				            <th class="head">Fecha 2da vis.</th> 
				            <th class="head">Descripci&oacute;n 2da vis.</th> 
				            <th class="head">Cierre 2da vis.</th> 
				            <th class="head">Diferencia D&iacute;as</th> 
				            <th class="head">Tipo red</th> 
				            <th class="head">Falla</th> 
				            <th class="head">&Uacute;ltima actualizaci&oacute;n</th>   
				        </tr>
				        </thead>	
						<tbody>
				        <?php 
				        	if($data !=FALSE){
					      		foreach($data as $d){
				      			?>
				      			 <tr>
									 <td><?php echo utf8_decode($d["tecnico"]); ?></td>
									 <td><?php echo utf8_decode($d["comuna"]); ?></td>
									 <td><?php echo utf8_decode($d["ot"]); ?></td>
									 <td><?php echo utf8_decode($d["fecha"]); ?></td>
									 <td><?php echo utf8_decode($d["descripcion"]); ?></td>
									 <td><?php echo utf8_decode($d["cierre"]); ?></td>
									 <td><?php echo utf8_decode($d["ot_2davisita"]); ?></td>
									 <td><?php echo utf8_decode($d["fecha_2davisita"]); ?></td>
									 <td><?php echo utf8_decode($d["descripcion_2davisita"]); ?></td>
									 <td><?php echo utf8_decode($d["cierre_2davisita"]); ?></td>
									 <td><?php echo utf8_decode($d["diferencia_dias"]); ?></td>
									 <td><?php echo utf8_decode($d["tipo_red"]); ?></td>
									 <td><?php echo utf8_decode($d["falla"]); ?></td>
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

	/***********GRAFICOS CALIDAD*****************/

		public function vistaGraficosCalidad(){
			
			$this->visitas("Calidad graficos");
			if($this->input->is_ajax_request()){

				if(date("d")>"24"){
					$desde_actual = date('d-m-Y', strtotime('-1 month', strtotime(date('Y-m-25'))));
					$hasta_actual = date('d-m-Y', strtotime(date('Y-m-24')));
					$desde_anterior = date('d-m-Y', strtotime('-2 month', strtotime(date('Y-m-25'))));
					$hasta_anterior = date('d-m-Y', strtotime('-1 month', strtotime(date('Y-m-24'))));
					$desde_anterior2 = date('d-m-Y', strtotime('-3 month', strtotime(date('Y-m-25'))));
					$hasta_anterior2 = date('d-m-Y', strtotime('-2 month', strtotime(date('Y-m-24'))));
				}else{
					$desde_actual = date('d-m-Y', strtotime('-2 month', strtotime(date('Y-m-25'))));
					$hasta_actual = date('d-m-Y', strtotime('-1 month', strtotime(date('Y-m-24'))));
					$desde_anterior = date('d-m-Y', strtotime('-3 month', strtotime(date('Y-m-25'))));
					$hasta_anterior = date('d-m-Y', strtotime('-2 month', strtotime(date('Y-m-24'))));
					$desde_anterior2 = date('d-m-Y', strtotime('-4 month', strtotime(date('Y-m-25'))));
					$hasta_anterior2 = date('d-m-Y', strtotime('-3 month', strtotime(date('Y-m-24'))));
				}
				
				$datos=array(	
					'desde_actual' => $desde_actual,
			        'hasta_actual' => $hasta_actual,
			        'desde_anterior' => $desde_anterior,
			        'hasta_anterior' => $hasta_anterior,
			        'desde_anterior2' => $desde_anterior2,
			        'hasta_anterior2' => $hasta_anterior2,
			        'jefes' => $this->Calidadmodel->listaJefes(),
			        'proyectos' => $this->Calidadmodel->listaProyectos(),

			        'mes_actual' => mesesPeriodoCalidad("actual"),
		       	    'mes_anterior' =>mesesPeriodoCalidad("anterior"),
		       	    'mes_anterior2' =>mesesPeriodoCalidad("anterior2"),

			    );

				$this->load->view('back_end/calidad/calidad_graficos',$datos);
			}
		}

		public function graficoHFC(){
			$periodo=$this->security->xss_clean(strip_tags($this->input->get_post("periodo")));
			$trabajador=$this->security->xss_clean(strip_tags($this->input->get_post("trabajador")));
			$jefe=$this->security->xss_clean(strip_tags($this->input->get_post("jefe")));
			$proyecto=$this->security->xss_clean(strip_tags($this->input->get_post("proyecto")));

			if($jefe!=""){
				$cargo_jefe = $this->Calidadmodel->getCargoJefe($jefe);
			}else{
				$cargo_jefe = "";
			}

			$array= array();

			if(date("d")>"24"){
				$desde_actual = date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-25'))));
				$hasta_actual= date('Y-m-24');
				$desde_anterior_1= date('Y-m-d', strtotime('-2 month', strtotime(date('Y-m-25'))));
				$hasta_anterior_1= date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-24'))));
				$desde_anterior_2= date('Y-m-d', strtotime('-3 month', strtotime(date('Y-m-25'))));
				$hasta_anterior_2= date('Y-m-d', strtotime('-2 month', strtotime(date('Y-m-24'))));

				if($cargo_jefe=="32"){
					$array[] = $this->Calidadmodel->graficoHFCCalidadLideres($desde_actual,$hasta_actual,$trabajador,$jefe,$proyecto);
					$array[] = $this->Calidadmodel->graficoHFCCalidadLideres($desde_anterior_1,$hasta_anterior_1,$trabajador,$jefe,$proyecto);
					$array[] = $this->Calidadmodel->graficoHFCCalidadLideres($desde_anterior_2,$hasta_anterior_2,$trabajador,$jefe,$proyecto);
				}else{
					$array[] = $this->Calidadmodel->graficoHFC($desde_actual,$hasta_actual,$trabajador,$jefe,$proyecto);
					$array[] = $this->Calidadmodel->graficoHFC($desde_anterior_1,$hasta_anterior_1,$trabajador,$jefe,$proyecto);
					$array[] = $this->Calidadmodel->graficoHFC($desde_anterior_2,$hasta_anterior_2,$trabajador,$jefe,$proyecto);
				}
				
			}else{
				$desde_actual = date('Y-m-d', strtotime('-2 month', strtotime(date('Y-m-25'))));
				$hasta_actual = date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-24'))));
				$desde_anterior_1= date('Y-m-d', strtotime('-3 month', strtotime(date('Y-m-25'))));
				$hasta_anterior_1= date('Y-m-d', strtotime('-2 month', strtotime(date('Y-m-24'))));
				$desde_anterior_2= date('Y-m-d', strtotime('-4 month', strtotime(date('Y-m-25'))));
				$hasta_anterior_2= date('Y-m-d', strtotime('-3 month', strtotime(date('Y-m-24'))));

				if($cargo_jefe=="32"){
					$array[] = $this->Calidadmodel->graficoHFCCalidadLideres($desde_actual,$hasta_actual,$trabajador,$jefe,$proyecto);
					$array[] = $this->Calidadmodel->graficoHFCCalidadLideres($desde_anterior_1,$hasta_anterior_1,$trabajador,$jefe,$proyecto);
					$array[] = $this->Calidadmodel->graficoHFCCalidadLideres($desde_anterior_2,$hasta_anterior_2,$trabajador,$jefe,$proyecto);
				}else{
					$array[] = $this->Calidadmodel->graficoHFC($desde_actual,$hasta_actual,$trabajador,$jefe,$proyecto);
					$array[] = $this->Calidadmodel->graficoHFC($desde_anterior_1,$hasta_anterior_1,$trabajador,$jefe,$proyecto);
					$array[] = $this->Calidadmodel->graficoHFC($desde_anterior_2,$hasta_anterior_2,$trabajador,$jefe,$proyecto);
				}
			}
			
			$list = array();

			$cabeceras= array(
				"Periodo",
				"Calidad",
				"Ordenes",
				"Fallos",
				/*array('role'=> 'annotation'),
				array('role'=> 'annotation'),
				array('role'=> 'annotation'),*/
				array('role'=> 'annotationText'),
			);

			$list [] = $cabeceras;

			foreach($array as $arr) {
			    if(is_array($arr)) {
			        $list = array_merge($list, $arr);
			    }
			}
			echo json_encode($list);exit;
		}	


		public function graficoFTTH(){
			$periodo=$this->security->xss_clean(strip_tags($this->input->get_post("periodo")));
			$trabajador=$this->security->xss_clean(strip_tags($this->input->get_post("trabajador")));
			$jefe=$this->security->xss_clean(strip_tags($this->input->get_post("jefe")));
			$proyecto=$this->security->xss_clean(strip_tags($this->input->get_post("proyecto")));

			if($jefe!=""){
				$cargo_jefe = $this->Calidadmodel->getCargoJefe($jefe);
			}else{
				$cargo_jefe = "";
			}
			
			$array= array();
			if(date("d")>"24"){
				$desde_actual = date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-25'))));
				$hasta_actual= date('Y-m-24');
				$desde_anterior_1= date('Y-m-d', strtotime('-2 month', strtotime(date('Y-m-25'))));
				$hasta_anterior_1= date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-24'))));
				$desde_anterior_2= date('Y-m-d', strtotime('-3 month', strtotime(date('Y-m-25'))));
				$hasta_anterior_2= date('Y-m-d', strtotime('-2 month', strtotime(date('Y-m-24'))));

				if($cargo_jefe=="32"){
					$array[] = $this->Calidadmodel->graficoFTTHCalidadLideres($desde_actual,$hasta_actual,$trabajador,$jefe,$proyecto);
					$array[] = $this->Calidadmodel->graficoFTTHCalidadLideres($desde_anterior_1,$hasta_anterior_1,$trabajador,$jefe,$proyecto);
					$array[] = $this->Calidadmodel->graficoFTTHCalidadLideres($desde_anterior_2,$hasta_anterior_2,$trabajador,$jefe,$proyecto);
				}else{
					$array[] = $this->Calidadmodel->graficoFTTH($desde_actual,$hasta_actual,$trabajador,$jefe,$proyecto);
					$array[] = $this->Calidadmodel->graficoFTTH($desde_anterior_1,$hasta_anterior_1,$trabajador,$jefe,$proyecto);
					$array[] = $this->Calidadmodel->graficoFTTH($desde_anterior_2,$hasta_anterior_2,$trabajador,$jefe,$proyecto);
				}
				
			}else{
				$desde_actual = date('Y-m-d', strtotime('-2 month', strtotime(date('Y-m-25'))));
				$hasta_actual = date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-24'))));
				$desde_anterior_1= date('Y-m-d', strtotime('-3 month', strtotime(date('Y-m-25'))));
				$hasta_anterior_1= date('Y-m-d', strtotime('-2 month', strtotime(date('Y-m-24'))));
				$desde_anterior_2= date('Y-m-d', strtotime('-4 month', strtotime(date('Y-m-25'))));
				$hasta_anterior_2= date('Y-m-d', strtotime('-3 month', strtotime(date('Y-m-24'))));

				if($cargo_jefe=="32"){
					$array[] = $this->Calidadmodel->graficoFTTHCalidadLideres($desde_actual,$hasta_actual,$trabajador,$jefe,$proyecto);
					$array[] = $this->Calidadmodel->graficoFTTHCalidadLideres($desde_anterior_1,$hasta_anterior_1,$trabajador,$jefe,$proyecto);
					$array[] = $this->Calidadmodel->graficoFTTHCalidadLideres($desde_anterior_2,$hasta_anterior_2,$trabajador,$jefe,$proyecto);
				}else{
					$array[] = $this->Calidadmodel->graficoFTTH($desde_actual,$hasta_actual,$trabajador,$jefe,$proyecto);
					$array[] = $this->Calidadmodel->graficoFTTH($desde_anterior_1,$hasta_anterior_1,$trabajador,$jefe,$proyecto);
					$array[] = $this->Calidadmodel->graficoFTTH($desde_anterior_2,$hasta_anterior_2,$trabajador,$jefe,$proyecto);
				}
			}
			
			$list = array();

			$cabeceras= array(
				"Periodo",
				"Calidad",
				"Ordenes",
				"Fallos",
				/*array('role'=> 'annotation'),
				array('role'=> 'annotation'),
				array('role'=> 'annotation'),*/
				array('role'=> 'annotationText'),
			);

			$list [] = $cabeceras;

			foreach($array as $arr) {
			    if(is_array($arr)) {
			        $list = array_merge($list, $arr);
			    }
			}
			
			echo json_encode($list);exit;
		}	

		public function listaResumenCalidad(){
			$periodo=$this->security->xss_clean(strip_tags($this->input->get_post("periodo")));
			$trabajador=$this->security->xss_clean(strip_tags($this->input->get_post("trabajador")));
			$jefe=$this->security->xss_clean(strip_tags($this->input->get_post("jefe")));
			$tipo_red=$this->security->xss_clean(strip_tags($this->input->get_post("tipo_red")));
			$proyecto=$this->security->xss_clean(strip_tags($this->input->get_post("proyecto")));
			
			if($jefe!=""){
				$cargo_jefe = $this->Calidadmodel->getCargoJefe($jefe);
			}else{
				$cargo_jefe = "";
			}
			$array=array();

			if($periodo=="actual"){

				if(date("d")>"24"){
					$desde_actual = date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-25'))));
					$hasta_actual= date('Y-m-24');
					$desde_actual_prod = date('Y-m-d', strtotime(date('Y-m-25')));
					$hasta_actual_prod = date('Y-m-d', strtotime('+1 month', strtotime(date('Y-m-24'))));
				}else{
					$desde_actual = date('Y-m-d', strtotime('-2 month', strtotime(date('Y-m-25'))));
					$hasta_actual = date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-24'))));
					$desde_actual_prod = date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-25'))));
					$hasta_actual_prod = date('Y-m-d',  strtotime(date('Y-m-24')));
				}

				if($cargo_jefe=="32"){
					$array[] = $this->Calidadmodel->listaResumenCalidadLideres($desde_actual,$hasta_actual,$trabajador,$jefe,$tipo_red,$desde_actual_prod,$hasta_actual_prod,$proyecto);			
				}else{
					$array[] = $this->Calidadmodel->listaResumenCalidad($desde_actual,$hasta_actual,$trabajador,$jefe,$tipo_red,$desde_actual_prod,$hasta_actual_prod,$proyecto);
				}
				
			}elseif($periodo=="anterior"){
				
				if(date("d")>"24"){
					$desde_anterior_1= date('Y-m-d', strtotime('-2 month', strtotime(date('Y-m-25'))));
					$hasta_anterior_1= date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-24'))));
					$desde_anterior_1_prod = date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-25'))));
					$hasta_anterior_1_prod = date('Y-m-d', strtotime(date('Y-m-24')));

				}else{
					$desde_anterior_1= date('Y-m-d', strtotime('-3 month', strtotime(date('Y-m-25'))));
					$hasta_anterior_1= date('Y-m-d', strtotime('-2 month', strtotime(date('Y-m-24'))));
					$desde_anterior_1_prod = date('Y-m-d', strtotime('-2 month', strtotime(date('Y-m-25'))));
					$hasta_anterior_1_prod = date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-24'))));
				}
				
				if($cargo_jefe=="32"){
					$array[]  = $this->Calidadmodel->listaResumenCalidadLideres($desde_anterior_1,$hasta_anterior_1,$trabajador,$jefe,$tipo_red,$desde_anterior_1_prod,$hasta_anterior_1_prod,$proyecto);
				}else{
					$array[]  = $this->Calidadmodel->listaResumenCalidad($desde_anterior_1,$hasta_anterior_1,$trabajador,$jefe,$tipo_red,$desde_anterior_1_prod,$hasta_anterior_1_prod,$proyecto);
				}

			}elseif($periodo=="sub_anterior"){
				
				if(date("d")>"24"){
					$desde_anterior_2= date('Y-m-d', strtotime('-3 month', strtotime(date('Y-m-25'))));
					$hasta_anterior_2= date('Y-m-d', strtotime('-2 month', strtotime(date('Y-m-24'))));
					$desde_anterior_2_prod = date('Y-m-d', strtotime('-2 month', strtotime(date('Y-m-25'))));
					$hasta_anterior_2_prod  =  date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-25'))));
				}else{
					$desde_anterior_2= date('Y-m-d', strtotime('-4 month', strtotime(date('Y-m-25'))));
					$hasta_anterior_2= date('Y-m-d', strtotime('-3 month', strtotime(date('Y-m-24'))));
					$desde_anterior_2_prod = date('Y-m-d', strtotime('-3 month', strtotime(date('Y-m-25'))));
					$hasta_anterior_2_prod = date('Y-m-d', strtotime('-2 month', strtotime(date('Y-m-24'))));
				}
				
				if($cargo_jefe=="32"){
					$array[]  = $this->Calidadmodel->listaResumenCalidadLideres($desde_anterior_2,$hasta_anterior_2,$trabajador,$jefe,$tipo_red,$desde_anterior_2_prod,$hasta_anterior_2_prod,$proyecto);
				}else{
					$array[]  = $this->Calidadmodel->listaResumenCalidad($desde_anterior_2,$hasta_anterior_2,$trabajador,$jefe,$tipo_red,$desde_anterior_2_prod,$hasta_anterior_2_prod,$proyecto);
				}

			}

			$list = array();

			foreach($array as $arr) {
			    if(is_array($arr)) {
			        $list = array_merge($list, $arr);
			    }
			}

			echo json_encode($list);exit;

		}	



}
