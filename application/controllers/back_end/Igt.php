<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Igt extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model("back_end/Igtmodel");
		$this->load->model("back_end/Iniciomodel");
		$this->load->model("back_end/Calidadmodel");
		$this->load->model("back_end/Productividadmodel");
		$this->load->helper(array('fechas','str'));
		$this->load->library('user_agent');
	}


	public function checkLogin(){
		if($this->session->userdata("id")==""){
			redirect("../unlogin");
		}
	}
	
	public function acceso(){
		if($this->session->userdata('id')!=""){
			if($this->session->userdata('id_perfil')>3){
	      		redirect("./login");
	      	}
	      }else{
	      	redirect("./login");
	    }
	}


	public function formCargaMasivaIgt(){

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
         	
            while (($d = fgetcsv($handle, 9999, ";")) !== FALSE) {
            	$mes_string = explode('-', $d[0]);
            	$mes_numero = mesesTextoaNumero($mes_string[1]);

            	if($mes_numero=="0"){
        			echo json_encode(array('res'=>'error', "tipo" => "error", 'msg' => " Formato fecha inválido,debe ser año-mes(texto 3 primeros caracteres), ejemplo : dic-2022"));exit;
            	}

            	$mes_completo = $mes_string[0]."-".$mes_numero."-01";

	    	  	if($this->Igtmodel->existeMes($mes_completo)){
	    	  		$this->Igtmodel->borrarMesActual($mes_completo);
	    	  	}
            }
         	
         	fclose($handle); 

         	$filename = $_FILES['userfile']['tmp_name'];
            $handle = fopen($filename, "r");

            while (($data = fgetcsv($handle, 9999, ";")) !== FALSE) {
                if(!empty($data[0])){
            	  	
            	  	$mes_string = explode('-', $data[0]);
	            	$mes_numero = mesesTextoaNumero($mes_string[1]);
	            	$mes_completo = $mes_string[0]."-".$mes_numero."-01";

            	  	$rut=str_replace('-', '', $data[1]);
            	    $id_tecnico = $this->Igtmodel->getIdTecnico($rut);

            	    if($id_tecnico!=FALSE){

            	    	$arr=array(
					    	"id_tecnico"=>$id_tecnico,
 					    	"mes"=>$mes_completo,
					    	"q_ftth"=>str_replace('%', '', $data[2]),
					    	"fallas_ftth"=>str_replace('%', '', $data[3]),
					    	"calidad_ftth"=>str_replace('%', '', $data[4]),
					    	"q_hfc"=>str_replace('%', '', $data[5]),
					    	"fallas_hfc"=>str_replace('%', '', $data[6]),
					    	"calidad_hfc"=>str_replace('%', '', $data[7]),
					    	"cumplimiento_ot"=>str_replace('%', '', $data[8]),
					    	"prom_ot_ftth"=>str_replace('%', '', $data[9]),
					    	"dias_produccion_ftth"=>str_replace('%', '', $data[10]),
					    	"promedio_puntos_hfc"=>str_replace('%', '', $data[11]),
					    	"dias_produccion_hfc"=>str_replace('%', '', $data[12]),
					    	"porcentaje_produccion_hfc"=>str_replace('%', '', $data[13]),
					    	"porcentaje_calidad_hfc"=>str_replace('%', '', $data[14]),
					    	"porcentaje_produccion_ftth"=>str_replace('%', '', $data[15]),
					    	"porcentaje_calidad_ftth"=>str_replace('%', '', $data[16]),
					    	"indice_asistencia"=>str_replace('%', '', $data[17]),
						);	

					    $this->Igtmodel->insertarIgt($arr);
					    $i++;
				  	 	$arr=array();
            	    }
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


	public function visitas($modulo){
		$this->load->library('user_agent');
		$data=array("id_usuario"=>$this->session->userdata('id'),
			"id_aplicacion"=>10,
			"modulo"=>$modulo,
	     	"fecha"=>date("Y-m-d G:i:s"),
	    	"navegador"=>"navegador :".$this->agent->browser()."\nversion :".$this->agent->version()."\nos :".$this->agent-> platform()."\nmovil :".$this->agent->mobile(),
	    	"ip"=>$this->input->ip_address(),
    	);
    	$this->Igtmodel->insertarVisita($data);
	}

	public function index(){
		$this->acceso();
	    $datos = array(
	        'titulo' => "IGT - Indicadores de gestión del técnico",
	        'contenido' => "igt/inicio",
	        'perfiles' => $this->Iniciomodel->listaPerfiles(),
	    );  
		$this->load->view('plantillas/plantilla_back_end',$datos);
	}

    public function getIgtInicio(){
		$this->visitas("Inicio");
		if($this->input->is_ajax_request()){

			if(date("d")>"24"){
				$desde_actual_calidad = date('d-m-Y', strtotime('-1 month', strtotime(date('Y-m-25'))));
				$hasta_actual_calidad = date('d-m-Y', strtotime(date('Y-m-24')));
				$desde_anterior_calidad = date('d-m-Y', strtotime('-2 month', strtotime(date('Y-m-25'))));
				$hasta_anterior_calidad = date('d-m-Y', strtotime('-1 month', strtotime(date('Y-m-24'))));

				$desde_actual_prod = date('d-m-Y', strtotime(date('Y-m-25')));
				$hasta_actual_prod = date('d-m-Y', strtotime('+1 month', strtotime(date('Y-m-24'))));
				$desde_anterior_prod = date('d-m-Y', strtotime('-1 month', strtotime(date('Y-m-25'))));
				$hasta_anterior_prod = date('d-m-Y', strtotime(date('Y-m-24')));

			}else{
				$desde_actual_calidad = date('d-m-Y', strtotime('-2 month', strtotime(date('Y-m-25'))));
				$hasta_actual_calidad = date('d-m-Y', strtotime('-1 month', strtotime(date('Y-m-24'))));
				$desde_anterior_calidad = date('d-m-Y', strtotime('-3 month', strtotime(date('Y-m-25'))));
				$hasta_anterior_calidad = date('d-m-Y', strtotime('-2 month', strtotime(date('Y-m-24'))));

				$desde_actual_prod = date('d-m-Y', strtotime('-1 month', strtotime(date('Y-m-25'))));
				$hasta_actual_prod = date('d-m-Y',  strtotime(date('Y-m-24')));
				$desde_anterior_prod = date('d-m-Y', strtotime('-2 month', strtotime(date('Y-m-25'))));
				$hasta_anterior_prod = date('d-m-Y', strtotime('-1 month', strtotime(date('Y-m-24'))));

			}
			

			$datos = array(	
				'desde_actual_calidad' => $desde_actual_calidad,
		        'hasta_actual_calidad' => $hasta_actual_calidad,
		        'desde_anterior_calidad' => $desde_anterior_calidad,
		        'hasta_anterior_calidad' => $hasta_anterior_calidad,

		        'desde_actual_prod' => $desde_actual_prod,
		        'hasta_actual_prod' => $hasta_actual_prod,
		        'desde_anterior_prod' => $desde_anterior_prod,
		        'hasta_anterior_prod' => $hasta_anterior_prod,

		        'desde_actual_relojes' => $desde_actual_prod,
		        'hasta_actual_relojes' => $hasta_actual_prod,
		        'desde_anterior_relojes' => $desde_anterior_prod,
		        'hasta_anterior_relojes' => $hasta_anterior_prod,
		        
		        'mes_actual' => mesesPeriodo("actual"),
		        'mes_anterior' =>mesesPeriodo("anterior"),
		        'jefes' => $this->Calidadmodel->listaJefes(),
		    );

			$this->load->view('back_end/igt/igt',$datos);
		}

	}


	public function dataGraficosIgt(){
		$periodo=$this->security->xss_clean(strip_tags($this->input->get_post("periodo")));
		$trabajador=$this->security->xss_clean(strip_tags($this->input->get_post("trabajador")));
		$jefe=$this->security->xss_clean(strip_tags($this->input->get_post("jefe")));
		$perfil_tecnico = $this->Igtmodel->getPerfilTecnico($trabajador);

		$rut=str_replace('-', '', $trabajador);
        $id_tecnico = $this->Igtmodel->getIdTecnico($rut);

   		$array_data = array();

		/***********PRODUCTIVIDAD PROM FTTH *********************/

			$meta_prom_ftth = $this->Igtmodel->getMetaIndicador($perfil_tecnico,1,$periodo);

			if($meta_prom_ftth!="0"){

				$data_prom_ftth = $this->Igtmodel->dataPromFTTH(getPeriodo($periodo),$id_tecnico,$perfil_tecnico);
				
				if($data_prom_ftth!=FALSE){
					$array_data["data_prom_ftth"] = array("data" => $data_prom_ftth , "meta" => $meta_prom_ftth);
				}
			}

		/***********PRODUCTIVIDAD FTTH+HFC *********************/

			$meta_prod_hfc_ftth = $this->Igtmodel->getMetaIndicador($perfil_tecnico,2,$periodo);

			if($meta_prod_hfc_ftth!="0"){
				$data_prod_hfc_ftth = $this->Igtmodel->dataProdHFCFTTH(getFechasPeriodo($periodo)["desde_prod"],getFechasPeriodo($periodo)["hasta_prod"],$trabajador,$perfil_tecnico);
				//if($data_prod_hfc_ftth!=FALSE){
					$array_data["data_prod_hfc_ftth"] = array("data" => $data_prod_hfc_ftth , "meta" => $meta_prod_hfc_ftth);
				//}
			}

		/***********PRODUCTIVIDAD PROMEDIO HFC *********************/

			$meta_prom_hfc = $this->Igtmodel->getMetaIndicador($perfil_tecnico,3,$periodo);

			if($meta_prom_hfc!="0"){
				$data_prom_hfc = $this->Igtmodel->dataPromHFC(getPeriodo($periodo),$id_tecnico);
				//if($data_prom_hfc!=FALSE){
					$array_data["data_prom_hfc"] = array("data" => $data_prom_hfc , "meta" => $meta_prom_hfc);
				//}
			}


		/***********DIAS TRABAJADOS**********************/

			$meta_dias = $this->Igtmodel->getMetaIndicador($perfil_tecnico,4,$periodo);

			if($meta_dias!="0"){
				$data_dias = $this->Igtmodel->dataDiasTrabajados(getPeriodo($periodo),$id_tecnico);
				//if($data_dias!=FALSE){
					$array_data["data_dias"] = array("data" => $data_dias , "meta" => $meta_dias);
				//}
			}

			/*$meta_dias = $this->Igtmodel->getMetaIndicador($perfil_tecnico,4,$periodo);

			if($meta_dias!="0"){
				$data_dias = $this->Igtmodel->dataPromHFC(getFechasPeriodo($periodo)["desde_prod"],getFechasPeriodo($periodo)["hasta_prod"],$trabajador,"dias");
				if($data_dias!=FALSE){
					$array_data["data_dias"] = array("data" => $data_dias , "meta" => $meta_dias);
				}
			}*/


		/***********CALIDAD HFC*********************/

			$meta_calidad_hfc = $this->Igtmodel->getMetaIndicador($perfil_tecnico,5,$periodo);

			if($meta_calidad_hfc!="0"){
				$data_calidad_hfc = $this->Igtmodel->dataCalidadHFC(getPeriodo($periodo),$id_tecnico);

				//if($data_calidad_hfc!=FALSE){
					$array_data["data_calidad_hfc"] = array("data" => $data_calidad_hfc , "meta" => $meta_calidad_hfc);
				//}
			}


		/***********CALIDAD FTTH*********************/

			$meta_calidad_ftth = $this->Igtmodel->getMetaIndicador($perfil_tecnico,6,$periodo);

			if($meta_calidad_ftth!="0"){
				$data_calidad_ftth = $this->Igtmodel->dataCalidadFTTH(getPeriodo($periodo),$id_tecnico);
				//if($data_calidad_ftth!=FALSE){
					$array_data["data_calidad_ftth"] = array("data" => $data_calidad_ftth , "meta" => $meta_calidad_ftth);
				//}
			}


		/***********DECLARACION OT *********************/

			$meta_declaracion_ot = $this->Igtmodel->getMetaIndicador($perfil_tecnico,7,$periodo);

			if($meta_declaracion_ot!="0"){
				$data_declaracion_ot = $this->Igtmodel->dataDeclaracionOT(getPeriodo($periodo),$id_tecnico);
				//if($data_declaracion_ot!=FALSE){
					$array_data["data_declaracion_ot"] = array("data" => $data_declaracion_ot , "meta" => $meta_declaracion_ot);
				//}
			}

		/*******FOTO-ACTUALIZACIONES********/

			$foto = $this->Igtmodel->getFotoTecnico($trabajador);

			if($foto!=""){
				$array_data["foto"] = $foto;
			}

			$array_data["actualizacion_calidad"] =  $this->Calidadmodel->actualizacionCalidad();
			$array_data["actualizacion_productividad"] = $this->Productividadmodel->actualizacionProductividad();

		/*******GRAFICO CALIDAD HFC PERIODOS************/

			$data_calidad_hfc = array();
			
			if($this->Igtmodel->graficoHFC(getFechasPeriodo("actual")["desde_calidad"],getFechasPeriodo("actual")["hasta_calidad"],$trabajador)!=FALSE){
				$data_calidad_hfc[] = $this->Igtmodel->graficoHFC(getFechasPeriodo("actual")["desde_calidad"],getFechasPeriodo("actual")["hasta_calidad"],$trabajador);
			}

			if($this->Igtmodel->graficoHFC(getFechasPeriodo("anterior")["desde_calidad"],getFechasPeriodo("anterior")["hasta_calidad"],$trabajador)!=FALSE){
				$data_calidad_hfc[] = $this->Igtmodel->graficoHFC(getFechasPeriodo("anterior")["desde_calidad"],getFechasPeriodo("anterior")["hasta_calidad"],$trabajador);
			}

			if($this->Igtmodel->graficoHFC(getFechasPeriodo("anterior_2")["desde_calidad"],getFechasPeriodo("anterior_2")["hasta_calidad"],$trabajador)!=FALSE){
				$data_calidad_hfc[] = $this->Igtmodel->graficoHFC(getFechasPeriodo("anterior_2")["desde_calidad"],getFechasPeriodo("anterior_2")["hasta_calidad"],$trabajador);
			}

			if($this->Igtmodel->graficoHFC(getFechasPeriodo("anterior_3")["desde_calidad"],getFechasPeriodo("anterior_3")["hasta_calidad"],$trabajador)!=FALSE){
				$data_calidad_hfc[] = $this->Igtmodel->graficoHFC(getFechasPeriodo("anterior_3")["desde_calidad"],getFechasPeriodo("anterior_3")["hasta_calidad"],$trabajador);
			}

			if($this->Igtmodel->graficoHFC(getFechasPeriodo("anterior_4")["desde_calidad"],getFechasPeriodo("anterior_4")["hasta_calidad"],$trabajador)!=FALSE){
				$data_calidad_hfc[] = $this->Igtmodel->graficoHFC(getFechasPeriodo("anterior_4")["desde_calidad"],getFechasPeriodo("anterior_4")["hasta_calidad"],$trabajador);
			}

			if($this->Igtmodel->graficoHFC(getFechasPeriodo("anterior_5")["desde_calidad"],getFechasPeriodo("anterior_5")["hasta_calidad"],$trabajador)!=FALSE){
				$data_calidad_hfc[] = $this->Igtmodel->graficoHFC(getFechasPeriodo("anterior_5")["desde_calidad"],getFechasPeriodo("anterior_5")["hasta_calidad"],$trabajador);
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


			$meta_calidad_hfc = $this->Igtmodel->getMetaIndicador($perfil_tecnico,5,$periodo);

			if($meta_calidad_hfc!="0"){
				if($data_calidad_hfc!=[]){
					$array_data["data_calidad_hfc_periodos"] = array("data" => $list , "meta" => $meta_calidad_hfc);
				} 
			}


		/*******GRAFICO CALIDAD FTTH PERIODOS************/

			$data_calidad_ftth = array();
			
			if($this->Igtmodel->graficoFTTH(getFechasPeriodo("actual")["desde_calidad"],getFechasPeriodo("actual")["hasta_calidad"],$trabajador)!=FALSE){
				$data_calidad_ftth[] = $this->Igtmodel->graficoFTTH(getFechasPeriodo("actual")["desde_calidad"],getFechasPeriodo("actual")["hasta_calidad"],$trabajador);
			}


			if($this->Igtmodel->graficoFTTH(getFechasPeriodo("anterior")["desde_calidad"],getFechasPeriodo("anterior")["hasta_calidad"],$trabajador)!=FALSE){
				$data_calidad_ftth[] = $this->Igtmodel->graficoFTTH(getFechasPeriodo("anterior")["desde_calidad"],getFechasPeriodo("anterior")["hasta_calidad"],$trabajador);
			}

			if($this->Igtmodel->graficoFTTH(getFechasPeriodo("anterior_2")["desde_calidad"],getFechasPeriodo("anterior_2")["hasta_calidad"],$trabajador)!=FALSE){
				$data_calidad_ftth[] = $this->Igtmodel->graficoFTTH(getFechasPeriodo("anterior_2")["desde_calidad"],getFechasPeriodo("anterior_2")["hasta_calidad"],$trabajador);
			}

			if($this->Igtmodel->graficoFTTH(getFechasPeriodo("anterior_3")["desde_calidad"],getFechasPeriodo("anterior_3")["hasta_calidad"],$trabajador)!=FALSE){
				$data_calidad_ftth[] = $this->Igtmodel->graficoFTTH(getFechasPeriodo("anterior_3")["desde_calidad"],getFechasPeriodo("anterior_3")["hasta_calidad"],$trabajador);
			}

			if($this->Igtmodel->graficoFTTH(getFechasPeriodo("anterior_4")["desde_calidad"],getFechasPeriodo("anterior_4")["hasta_calidad"],$trabajador)!=FALSE){
				$data_calidad_ftth[] = $this->Igtmodel->graficoFTTH(getFechasPeriodo("anterior_4")["desde_calidad"],getFechasPeriodo("anterior_4")["hasta_calidad"],$trabajador);
			}

			if($this->Igtmodel->graficoFTTH(getFechasPeriodo("anterior_5")["desde_calidad"],getFechasPeriodo("anterior_5")["hasta_calidad"],$trabajador)!=FALSE){
				$data_calidad_ftth[] = $this->Igtmodel->graficoFTTH(getFechasPeriodo("anterior_5")["desde_calidad"],getFechasPeriodo("anterior_5")["hasta_calidad"],$trabajador);
			}

			$cabeceras_calidad = array(
				"Periodo",
				"Calidad",
				"Ordenes",
				"Fallos",
				array('role'=> 'annotationText'),
			);

			$list2 = array();
			$list2[] = $cabeceras_calidad;

			foreach($data_calidad_ftth as $arr) {
			    if(is_array($arr)) {
			        $list2 = array_merge($list2, $arr);
			    }
			}

			$meta_calidad_ftth = $this->Igtmodel->getMetaIndicador($perfil_tecnico,6,$periodo);

			if($meta_calidad_ftth!="0"){
				if($data_calidad_ftth!=[]){
					$array_data["data_calidad_ftth_periodos"] = array("data" => $list2 , "meta" => $meta_calidad_ftth);
				}
			}


		/*******GRAFICO PRODUCTIVIDAD DIARIO************/

			$meta_productividad_puntos = $this->Igtmodel->getMetaIndicador($perfil_tecnico,2,$periodo);

			if($meta_productividad_puntos!="0"){
				$data_puntos_prod_diario = $this->Igtmodel->puntosPorFechas(getFechasPeriodo($periodo)["desde_prod"],getFechasPeriodo($periodo)["hasta_prod"],$trabajador,"");
				if($data_puntos_prod_diario!=FALSE){
					$array_data["data_puntos_prod_diario"] = array("data" => $data_puntos_prod_diario , "meta" => $meta_productividad_puntos);
				}

			}

			echo json_encode($array_data);

		}

		public function listaTrabajadoresIGT(){
			$jefe=$this->security->xss_clean(strip_tags($this->input->get_post("jefe")));
	 	    echo $this->Igtmodel->listaTrabajadoresIGT($jefe);exit;
		}


	/*******CALIDAD*******/

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


	/*******PRODUCTIVIDAD***/

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
				}

			}else{
				if($periodo=="actual"){
					$desde= date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-25'))));
					$hasta= date('Y-m-d');
				}elseif($periodo=="anterior"){
					$desde= date('Y-m-d', strtotime('-2 month', strtotime(date('Y-m-25'))));
					$hasta= date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-24'))));
				}
			}

			echo json_encode($this->Productividadmodel->listaDetalle($desde,$hasta,$trabajador,$jefe));

		}	

		public function excel_detalle(){
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
					$desde = date('Y-m-d', strtotime(date('Y-m-25')));
					$hasta = date('Y-m-d', strtotime('+1 month', strtotime(date('Y-m-24'))));
				}elseif($periodo=="anterior"){
					$desde = date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-25'))));
					$hasta= date('Y-m-d', strtotime(date('Y-m-24')));
				}
				
			}else{
				if($periodo=="actual"){
					$desde= date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-25'))));
					$hasta= date('Y-m-d');
				}elseif($periodo=="anterior"){
					$desde= date('Y-m-d', strtotime('-2 month', strtotime(date('Y-m-25'))));
					$hasta= date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-24'))));
				}
			}


			$data=$this->Productividadmodel->listaDetalle($desde,$hasta,$trabajador,$jefe);

			if(!$data){
				echo "Sin datos disponibles.";
				return FALSE;
			}else{

				$nombre="reporte-productividad-".date("d-m-Y",strtotime($desde))."-".date("d-m-Y",strtotime($hasta)).".xls";
				header("Content-type: application/vnd.ms-excel;  charset=utf-8");
				header("Content-Disposition: attachment; filename=$nombre");
				?>
				<style type="text/css">
				.head{font-size:13px;height: 30px; background-color:#32477C;color:#fff; font-weight:bold;padding:10px;margin:10px;vertical-align:middle;}
				td{font-size:12px;text-align:center;   vertical-align:middle;}
				
				</style>
				<h3>Reporte productividad <?php echo date("d-m-Y",strtotime($desde)); ?> - <?php echo date("d-m-Y",strtotime($hasta)); ?></h3>
					<table align='center' border="1"> 
				        <tr style="background-color:#F9F9F9">
			                <th class="head">T&eacute;cnico</th> 
				            <th class="head">Fecha</th> 
				            <th class="head">Dirección</th> 
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


	/******DETALLE OTS DRIVE******/

		public function listaDetalleOtsDrive(){
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
					$hasta= date('Y-m-d');
				}elseif($periodo=="anterior"){
					$desde= date('Y-m-d', strtotime('-2 month', strtotime(date('Y-m-25'))));
					$hasta= date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-24'))));
				}
			}

			echo json_encode($this->Igtmodel->listaDetalleOtsDrive($desde,$hasta,$trabajador,$jefe));
		}	

		public function excel_detalle_ots_drive(){
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
					$desde = date('Y-m-d', strtotime(date('Y-m-25')));
					$hasta = date('Y-m-d', strtotime('+1 month', strtotime(date('Y-m-24'))));
				}elseif($periodo=="anterior"){
					$desde = date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-25'))));
					$hasta= date('Y-m-d', strtotime(date('Y-m-24')));
				}
				
			}else{
				if($periodo=="actual"){
					$desde= date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-25'))));
					$hasta= date('Y-m-d');
				}elseif($periodo=="anterior"){
					$desde= date('Y-m-d', strtotime('-2 month', strtotime(date('Y-m-25'))));
					$hasta= date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-24'))));
				}
			}


			$data=$this->Igtmodel->listaDetalleOtsDrive($desde,$hasta,$trabajador,$jefe);

			if(!$data){
				echo "Sin datos disponibles.";
				return FALSE;
			}else{

				$nombre="reporte-ots-nodetectadas".date("d-m-Y",strtotime($desde))."-".date("d-m-Y",strtotime($hasta)).".xls";
				header("Content-type: application/vnd.ms-excel;  charset=utf-8");
				header("Content-Disposition: attachment; filename=$nombre");
				?>
				<style type="text/css">
				.head{font-size:13px;height: 30px; background-color:#32477C;color:#fff; font-weight:bold;padding:10px;margin:10px;vertical-align:middle;}
				td{font-size:12px;text-align:center;   vertical-align:middle;}
				
				</style>
				<h3>Reporte OTS no detectadas en drive <?php echo date("d-m-Y",strtotime($desde)); ?> - <?php echo date("d-m-Y",strtotime($hasta)); ?></h3>
					<table align='center' border="1"> 
				        <tr style="background-color:#F9F9F9">
			                <th class="head">T&eacute;cnico</th> 
				            <th class="head">Fecha</th> 
				            <th class="head">Dirección</th> 
				            <th class="head">Tipo actividad</th> 
				            <th class="head">Comuna</th> 
				            <th class="head">Estado</th> 
				            <th class="head">Derivado</th> 
				            <th class="head">Puntaje</th> 
				            <th class="head">Orden de Trabajo</th> 
				            <th class="head">Digitalizaci&oacute;n OT</th>  
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


}