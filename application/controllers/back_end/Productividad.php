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
			"id_aplicacion"=>1,
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
			);  
			$this->load->view('plantillas/plantilla_back_end',$datos);
			
		}

		public function vistaDetalle(){
			$this->visitas("Productividad detalle");
			if($this->input->is_ajax_request()){
				$fecha_anio_atras= date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-25'))));
		    	$fecha_hoy=date('Y-m-d');
				$datos=array(	
					'fecha_anio_atras' => $fecha_anio_atras,
			        'fecha_hoy' => $fecha_hoy,
			    );
				$this->load->view('back_end/productividad/productividad_detalle',$datos);
			}
		}

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
	         
	            while (($data = fgetcsv($handle, 9999, ";")) !== FALSE) {
				    $ultima_actualizacion=date("Y-m-d G:i:s")." | ".$this->session->userdata("nombre_completo");

	            	if(count($data)!=10){
	            		echo json_encode(array('res'=>'error', "tipo" => "error", 'msg' => "Archivo CSV inválido, 10 columnas esperadas,".count($data)." obtenidas."));exit;
	            	}

	                // if(!empty($data[0])){
                	    $rut = $data[0];
						$rutf=str_replace('-', '', $rut);

						if (str_contains($data[1], '/')) { 

							$fecha=explode('/',trim($data[1]));
						    $dia=$fecha[0];
							$mes=$fecha[1]; 
							$anio=$fecha[2]; 
							$fechaf = $anio."/".$mes."/".$dia;

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
							"ultima_actualizacion"=>$ultima_actualizacion
						);	

					    if(!$this->Productividadmodel->existeOrden($data[8])){
					    	$this->Productividadmodel->formDetalle($arr);
					    	$i++;
					    }
				  	 	
				  	 	$arr=array();
		            // }

		            if($i==0){
		            	echo json_encode(array('res'=>'ok', "tipo" => "success", 'msg' => $i." filas insertadas."));exit;
		            }
	            }
	            fclose($handle); 
	           	echo json_encode(array('res'=>'ok', "tipo" => "success", 'msg' => "Archivo cargado con éxito, ".$i." filas insertadas."));exit;
	        }else{
	        	echo json_encode(array('res'=>'error', "tipo" => "error", 'msg' => "Archivo CSV inválido."));exit;
	        }   
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
			$desde=$this->security->xss_clean(strip_tags($this->input->get_post("desde")));
			$hasta=$this->security->xss_clean(strip_tags($this->input->get_post("hasta")));
			$trabajador=$this->security->xss_clean(strip_tags($this->input->get_post("trabajador")));
			if($desde!=""){$desde=date("Y-m-d",strtotime($desde));}else{$desde="";}
			if($hasta!=""){$hasta=date("Y-m-d",strtotime($hasta));}else{$hasta="";}	
			echo json_encode($this->Productividadmodel->listaDetalle($desde,$hasta,$trabajador));
		}	

		public function vistaGraficosProd(){
			$this->visitas("Productividad graficos");
			if($this->input->is_ajax_request()){
				$fecha_anio_atras= date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-25'))));
		    	$fecha_hoy=date('Y-m-d');
				$datos=array(	
					'fecha_anio_atras' => $fecha_anio_atras,
			        'fecha_hoy' => $fecha_hoy,
			    );
				$this->load->view('back_end/productividad/graficos',$datos);
			}
		}

		public function dataGraficos(){
			$desde=$this->security->xss_clean(strip_tags($this->input->get_post("desde")));
			$hasta=$this->security->xss_clean(strip_tags($this->input->get_post("hasta")));
			$trabajador=$this->security->xss_clean(strip_tags($this->input->get_post("trabajador")));
			if($desde!=""){$desde=date("Y-m-d",strtotime($desde));}else{$desde="";}
			if($hasta!=""){$hasta=date("Y-m-d",strtotime($hasta));}else{$hasta="";}	
			echo json_encode(array(
				"ranking" => $this->Productividadmodel->rankingTecnicos($desde,$hasta,$trabajador),
				"totalpuntos" => $this->Productividadmodel->totalPuntosPorFecha($desde,$hasta,$trabajador),
				"puntosPorFechas" => $this->Productividadmodel->puntosPorFechas($desde,$hasta,$trabajador),
				"puntosTipoOrden" => $this->Productividadmodel->puntosTipoOrden($desde,$hasta,$trabajador),
				"distribucionTipos" => $this->Productividadmodel->distribucionTipos($desde,$hasta,$trabajador),
				"distribucionOt" => $this->Productividadmodel->distribucionOt($desde,$hasta,$trabajador),
			));
		}

		public function listaTrabajadores(){
     	     echo $this->Productividadmodel->listaTrabajadores();exit;
		}

		public function excel_detalle(){
			$desde=$this->uri->segment(2);
			$hasta=$this->uri->segment(3);
			$trabajador=$this->uri->segment(4);

			if($trabajador=="-"){
				$trabajador="";
			}

			$desde=date("Y-m-d",strtotime($desde));
			$hasta=date("Y-m-d",strtotime($hasta));

			$data=$this->Productividadmodel->listaDetalle($desde,$hasta,$trabajador);

			if(!$data){
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
			                <th class="head">Técnico</th> 
				            <th class="head">Fecha</th> 
				            <th class="head">Dirección</th> 
				            <th class="head">Tipo actividad</th> 
				            <th class="head">Comuna</th> 
				            <th class="head">Estado</th> 
				            <th class="head">Derivado</th> 
				            <th class="head">Puntaje</th> 
				            <th class="head">Orden de Trabajo</th> 
				            <th class="head">Digitalizacion OT</th>   
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


	/*********CALIDAD************/
		
	  
		public function vistaCalidad(){
			$this->visitas("Productividad calidad");
			if($this->input->is_ajax_request()){
				$fecha_anio_atras= date('Y-m-d', strtotime('-2 month', strtotime(date('Y-m-25'))));
		    	$fecha_hoy=date('Y-m-d');
				$datos=array(	
					'fecha_anio_atras' => $fecha_anio_atras,
			        'fecha_hoy' => $fecha_hoy,
			    );
				$this->load->view('back_end/productividad/calidad_detalle',$datos);
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
	            $i=0;$z=0;$y=0;     
	         
	            while (($data = fgetcsv($handle, 9999, ";")) !== FALSE) {
				    $ultima_actualizacion=date("Y-m-d G:i:s")." | ".$this->session->userdata("nombre_completo");

	            	if(count($data)!=11){
	            		echo json_encode(array('res'=>'error', "tipo" => "error", 'msg' => "Archivo CSV inválido, 11 columnas esperadas,".count($data)." obtenidas."));exit;
	            	}

	                // if(!empty($data[0])){
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


						// echo $fechaf;
						// echo "<br>";

					    $arr=array(
					    	"rut_tecnico"=>$rutf,
							"comuna"=>$data[1],
							"ot"=>$data[2],
							"fecha"=>$fechaf1,
							"descripcion"=>utf8_encode($data[4]),
							"cierre"=>$data[5],
							"ot_2davisita"=>$data[6],
							"fecha_2davisita"=>$fechaf2,
							"descripcion_2davisita"=>utf8_encode($data[8]),
							"cierre_2davisita"=>$data[9],
							"diferencia_dias"=>$data[10],
							"ultima_actualizacion"=>$ultima_actualizacion
						);	

					    if(!$this->Productividadmodel->existeOrdenCalidad($data[8])){
					    	$this->Productividadmodel->formCalidad($arr);
					    	$i++;
					    }
				  	 	
				  	 	$arr=array();
		            // }

		            if($i==0){
		            	echo json_encode(array('res'=>'ok', "tipo" => "success", 'msg' => $i." filas insertadas."));exit;
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
		      $data=$this->Productividadmodel->actualizacionCalidad();
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
			$desde=$this->security->xss_clean(strip_tags($this->input->get_post("desde")));
			$hasta=$this->security->xss_clean(strip_tags($this->input->get_post("hasta")));
			$trabajador=$this->security->xss_clean(strip_tags($this->input->get_post("trabajador")));
			if($desde!=""){$desde=date("Y-m-d",strtotime($desde));}else{$desde="";}
			if($hasta!=""){$hasta=date("Y-m-d",strtotime($hasta));}else{$hasta="";}	
			echo json_encode($this->Productividadmodel->listaCalidad($desde,$hasta,$trabajador));
		}	


		public function excel_calidad(){
			$desde=$this->uri->segment(2);
			$hasta=$this->uri->segment(3);
			$trabajador=$this->uri->segment(4);

			if($trabajador=="-"){
				$trabajador="";
			}

			$desde=date("Y-m-d",strtotime($desde));
			$hasta=date("Y-m-d",strtotime($hasta));

			$data=$this->Productividadmodel->listaCalidad($desde,$hasta,$trabajador);

			if(!$data){
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

		public function vistaGraficosCalidad(){
			$this->visitas("Calidad graficos");
			if($this->input->is_ajax_request()){
				$fecha_anio_atras= date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-25'))));
		    	$fecha_hoy=date('Y-m-d');
				$datos=array(	
					'fecha_anio_atras' => $fecha_anio_atras,
			        'fecha_hoy' => $fecha_hoy,
			    );
				$this->load->view('back_end/productividad/graficos_calidad',$datos);
			}
		}

		public function dataGraficosCalidad(){
			$desde=$this->security->xss_clean(strip_tags($this->input->get_post("desde")));
			$hasta=$this->security->xss_clean(strip_tags($this->input->get_post("hasta")));
			$trabajador=$this->security->xss_clean(strip_tags($this->input->get_post("trabajador")));
			if($desde!=""){$desde=date("Y-m-d",strtotime($desde));}else{$desde="";}
			if($hasta!=""){$hasta=date("Y-m-d",strtotime($hasta));}else{$hasta="";}	
			echo json_encode(array(
				"ranking" => $this->Productividadmodel->rankingTecnicos($desde,$hasta,$trabajador),
				"totalpuntos" => $this->Productividadmodel->totalPuntosPorFecha($desde,$hasta,$trabajador),
				"puntosPorFechas" => $this->Productividadmodel->puntosPorFechas($desde,$hasta,$trabajador),
				"puntosTipoOrden" => $this->Productividadmodel->puntosTipoOrden($desde,$hasta,$trabajador),
				"distribucionTipos" => $this->Productividadmodel->distribucionTipos($desde,$hasta,$trabajador),
				"distribucionOt" => $this->Productividadmodel->distribucionOt($desde,$hasta,$trabajador),
			));
		}


	}