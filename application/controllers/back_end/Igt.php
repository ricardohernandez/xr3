<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;

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
			if($this->session->userdata('id_perfil')==""){
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
        $chk_ext = explode(".",$fname);

        if(strtolower(end($chk_ext)) == "xlsx")  {


			$archivo = $_FILES['userfile']['tmp_name'];
			$spreadsheet = IOFactory::load($archivo);
			$i = 0;

			//GENERAL
				$hoja = $spreadsheet->getSheet(0);
				$ultima_fila = $hoja->getHighestRow();
				$filas_general = 0;

				$columnas = array(
					'mes',
					'id_tecnico',
					'q_ftth',
					'fallas_ftth',
					'calidad_ftth',
					'q_hfc',
					'fallas_hfc',
					'calidad_hfc',
					'cumplimiento_ot',
					'prom_ot_ftth',
					'dias_produccion_ftth',
					'promedio_puntos_hfc',
					'dias_produccion_hfc',
					'porcentaje_produccion_hfc',
					'porcentaje_calidad_hfc',
					'porcentaje_produccion_ftth',
					'porcentaje_calidad_ftth',
					'indice_asistencia',
					'derivaciones',
					'proyecto',
				);

				for ($fila = 2; $fila <= $ultima_fila; $fila++) {
					if($hoja->getCellByColumnAndRow(1, $fila)->getValue() != ""){
						$datos = array();
						$proyecto = $hoja->getCellByColumnAndRow(20, $fila)->getValue();
						foreach ($columnas as $index => $columna) {
							if ($index === 0) { //MES-AÑO
								$mes_string = explode('-', $hoja->getCellByColumnAndRow($index + 1, $fila)->getValue());
								$mes_numero = mesesTextoaNumero($mes_string[1]);
								$mes_completo = $mes_string[0]."-".$mes_numero."-01";
								$datos[$columna] = $mes_completo;
							} elseif ($index === 1) { //RUT -> ID
								$rut=str_replace('-', '', $hoja->getCellByColumnAndRow($index + 1, $fila)->getValue());
								$id_tecnico = $this->Igtmodel->getIdTecnico($rut);
								$datos[$columna] = $id_tecnico;
							} else {
								$datos[$columna] = str_replace('%', '', $hoja->getCellByColumnAndRow($index + 1, $fila)->getValue());
							}
						}

						if($this->Igtmodel->existeMes($mes_completo,$proyecto,$id_tecnico)){
							$this->Igtmodel->borrarMesActual($mes_completo,$proyecto,$id_tecnico);
						}

						$this->Igtmodel->insertarIgt($datos);
						$filas_general++;
						$i++;
					}
				}

			//DTV
				$hoja = $spreadsheet->getSheet(1);
				$ultima_fila = $hoja->getHighestRow();
				$filas_dtv = 0;
		
				$columnas = array(
					'mes',
					'id_tecnico',
					'work_orden',
					'calidad_sin_30',
					'encuesta_3_3',
					'cicle_time',
					'optimus',
				);
		
				for ($fila = 2; $fila <= $ultima_fila; $fila++) {
					$datos = array();
					if($hoja->getCellByColumnAndRow(1, $fila)->getValue() != ""){
						foreach ($columnas as $index => $columna) {
							
							if ($index === 0) { //MES-AÑO
								$mes_string = explode('-', $hoja->getCellByColumnAndRow($index + 1, $fila)->getValue());
								$mes_numero = mesesTextoaNumero($mes_string[1]);
								$mes_completo = $mes_string[0]."-".$mes_numero."-01";
								$datos[$columna] = $mes_completo;
							} elseif ($index === 1) { //RUT -> ID
								$rut=str_replace('-', '', $hoja->getCellByColumnAndRow($index + 1, $fila)->getValue());
								$id_tecnico = $this->Igtmodel->getIdTecnico($rut);
								$datos[$columna] = $id_tecnico;
							} else {
								$datos[$columna] = str_replace('%', '', $hoja->getCellByColumnAndRow($index + 1, $fila)->getValue());
							}
						}

						if($this->Igtmodel->existeMesDTV($mes_completo,$id_tecnico)){
							$this->Igtmodel->borrarMesActualDTV($mes_completo,$id_tecnico);
						}

						$this->Igtmodel->insertarIgtDTV($datos);
						$filas_dtv++;
						$i++;
					}
				}
			
			//TABLA DTV
				$hoja = $spreadsheet->getSheet(2);
				$ultima_fila = $hoja->getHighestRow();
				$filas_tabla_dtv = 0;
		
				$columnas = array(
					'n_work_orden',
					'id_tecnico',
					'nombre_tecnico',
					'domicilio',
					'ciudad',
					'servicio',
					'fecha_finalizacion',
					'estado',
				);
		
				for ($fila = 2; $fila <= $ultima_fila; $fila++) {
					$datos = array();
					if($hoja->getCellByColumnAndRow(1, $fila)->getValue() != ""){
						foreach ($columnas as $index => $columna) {
							if ($index === 1) { //RUT -> ID
								$rut=str_replace('-', '', $hoja->getCellByColumnAndRow($index + 1, $fila)->getValue());
								$id_tecnico = $this->Igtmodel->getIdTecnico($rut);
								$datos[$columna] = $id_tecnico;
							}
							else if ($index === 0) { //NUMERO WORK ORDEN
								$n_work_orden = $hoja->getCellByColumnAndRow($index + 1, $fila)->getValue();
								$datos[$columna] = $n_work_orden;
							}
							else if ($index === 2) { //nombre_tecnico
							}
							else if ($index === 6) { //fecha
								$fecha=$hoja->getCellByColumnAndRow($index + 1, $fila)->getValue();
								$fecha = ($fecha - 25569) * 86400;
								$fecha = new DateTime("@$fecha");
								$fecha = $fecha->format('Y-m-d');
								$datos[$columna] = $fecha;
							}
							else{
								$datos[$columna] = $hoja->getCellByColumnAndRow($index + 1, $fila)->getValue();
							}
						}

						if($this->Igtmodel->existeMesTablaDTV($n_work_orden,$id_tecnico)){
							$this->Igtmodel->borrarMesActualTablaDTV($n_work_orden,$id_tecnico);
						}

						$this->Igtmodel->insertarTablaIgtDTV($datos);
						$filas_tabla_dtv++;
						$i++;
					}
				}

				echo json_encode(array(
					'res' => 'ok',
					'tipo' => 'success',
					'msg' => 'Archivo cargado con éxito.
					'.$filas_general.' filas generales insertadas,
					'.$filas_dtv.' filas de dtv insertadas,
					'.$filas_tabla_dtv.' filas de tabla dtv insertadas'
					
				));exit;

           	echo json_encode(array('res'=>'ok', "tipo" => "success", 'msg' => "Archivo cargado con éxito, ".$i." filas insertadas."));exit;
        }else{
        	echo json_encode(array('res'=>'error', "tipo" => "error", 'msg' => "Archivo inválido."));exit;
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
				$desde_anterior2_calidad = date('d-m-Y', strtotime('-3 month', strtotime(date('Y-m-25'))));
				$hasta_anterior2_calidad = date('d-m-Y', strtotime('-2 month', strtotime(date('Y-m-24'))));

				$desde_actual_prod = date('d-m-Y', strtotime(date('Y-m-25')));
				$hasta_actual_prod = date('d-m-Y', strtotime('+1 month', strtotime(date('Y-m-24'))));
				$desde_anterior_prod = date('d-m-Y', strtotime('-1 month', strtotime(date('Y-m-25'))));
				$hasta_anterior_prod = date('d-m-Y', strtotime(date('Y-m-24')));
				$desde_anterior2_prod = date('d-m-Y', strtotime('-2 month', strtotime(date('Y-m-25'))));
				$hasta_anterior2_prod = date('d-m-Y', strtotime('-1 month', strtotime(date('Y-m-24'))));

			}else{
				$desde_actual_calidad = date('d-m-Y', strtotime('-2 month', strtotime(date('Y-m-25'))));
				$hasta_actual_calidad = date('d-m-Y', strtotime('-1 month', strtotime(date('Y-m-24'))));
				$desde_anterior_calidad = date('d-m-Y', strtotime('-3 month', strtotime(date('Y-m-25'))));
				$hasta_anterior_calidad = date('d-m-Y', strtotime('-2 month', strtotime(date('Y-m-24'))));
				$desde_anterior2_calidad = date('d-m-Y', strtotime('-4 month', strtotime(date('Y-m-25'))));
				$hasta_anterior2_calidad = date('d-m-Y', strtotime('-3 month', strtotime(date('Y-m-24'))));

				$desde_actual_prod = date('d-m-Y', strtotime('-1 month', strtotime(date('Y-m-25'))));
				$hasta_actual_prod = date('d-m-Y',  strtotime(date('Y-m-24')));
				$desde_anterior_prod = date('d-m-Y', strtotime('-2 month', strtotime(date('Y-m-25'))));
				$hasta_anterior_prod = date('d-m-Y', strtotime('-1 month', strtotime(date('Y-m-24'))));
				$desde_anterior2_prod = date('d-m-Y', strtotime('-3 month', strtotime(date('Y-m-25'))));
				$hasta_anterior2_prod = date('d-m-Y', strtotime('-2 month', strtotime(date('Y-m-24'))));

			}
			

			$datos = array(	
				'desde_actual_calidad' => $desde_actual_calidad,
		        'hasta_actual_calidad' => $hasta_actual_calidad,
		        'desde_anterior_calidad' => $desde_anterior_calidad,
		        'hasta_anterior_calidad' => $hasta_anterior_calidad,
				'desde_anterior2_calidad' => $desde_anterior2_calidad,
		        'hasta_anterior2_calidad' => $hasta_anterior2_calidad,

		        'desde_actual_prod' => $desde_actual_prod,
		        'hasta_actual_prod' => $hasta_actual_prod,
		        'desde_anterior_prod' => $desde_anterior_prod,
		        'hasta_anterior_prod' => $hasta_anterior_prod,
				'desde_anterior2_prod' => $desde_anterior2_prod,
		        'hasta_anterior2_prod' => $hasta_anterior2_prod,

		        'desde_actual_relojes' => $desde_actual_prod,
		        'hasta_actual_relojes' => $hasta_actual_prod,
		        'desde_anterior_relojes' => $desde_anterior_prod,
		        'hasta_anterior_relojes' => $hasta_anterior_prod,
				'desde_anterior2_relojes' => $desde_anterior2_prod,
		        'hasta_anterior2_relojes' => $hasta_anterior2_prod,
		        
		        'mes_actual' => mesesPeriodo("actual"),
		        'mes_anterior' =>mesesPeriodo("anterior"),
		        'mes_anterior2' =>mesesPeriodo("anterior2"),
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
				$porcentajeProduccionFTTH = $this->Igtmodel->porcentajeProduccionFTTH(getPeriodo($periodo),$id_tecnico);
		
				if($data_prom_ftth!=FALSE){
					$array_data["data_prom_ftth"] = array("data" => $data_prom_ftth , "meta" => $meta_prom_ftth, "cumplimiento" => $porcentajeProduccionFTTH);
				}
			}

		/***********PRODUCTIVIDAD FTTH+HFC *********************/

			$meta_prod_hfc_ftth = $this->Igtmodel->getMetaIndicador($perfil_tecnico,2,$periodo);

			if($meta_prod_hfc_ftth!="0"){
				$data_prod_hfc_ftth = $this->Igtmodel->dataProdHFCFTTH(getFechasPeriodo($periodo)["desde_prod"],getFechasPeriodo($periodo)["hasta_prod"],$trabajador,$perfil_tecnico);
				if($data_prod_hfc_ftth!=FALSE){
					$array_data["data_prod_hfc_ftth"] = array("data" => $data_prod_hfc_ftth , "meta" => $meta_prod_hfc_ftth);
				}
			}

		/***********PRODUCTIVIDAD PROM HFC *********************/

			$meta_prom_hfc = $this->Igtmodel->getMetaIndicador($perfil_tecnico,3,$periodo);

			if($meta_prom_hfc!="0"){
				$data_prom_hfc = $this->Igtmodel->dataPromHFC(getPeriodo($periodo),$id_tecnico);
				$porcentajeProduccionHFC = $this->Igtmodel->porcentajeProduccionHFC(getPeriodo($periodo),$id_tecnico);

				if($data_prom_hfc!=FALSE){
					$array_data["data_prom_hfc"] = array("data" => $data_prom_hfc , "meta" => $meta_prom_hfc, "cumplimiento" => $porcentajeProduccionHFC);
				}
			}


		/***********DIAS TRABAJADOS**********************/

			$meta_dias = $this->Igtmodel->getMetaIndicador($perfil_tecnico,4,$periodo);

			if($meta_dias!="0"){

				$data_dias_hfc = $this->Igtmodel->dataDiasTrabajadosHFC(getPeriodo($periodo),$id_tecnico);
				$data_dias_ftth = $this->Igtmodel->dataDiasTrabajadosFTTH(getPeriodo($periodo),$id_tecnico);
				
			    $array_data["data_dias_hfc"] = array("data" => $data_dias_hfc , "meta" => $meta_dias);
			    $array_data["data_dias_ftth"] = array("data" => $data_dias_ftth , "meta" => $meta_dias);
			}


		/**************AST*******************/

			$data_ast = $this->Igtmodel->getDataAst(getFechasPeriodo("actual")["desde_prod"],getFechasPeriodo("actual")["hasta_prod"],$id_tecnico);
			$cantidad_ordenes =  $this->Igtmodel->getPorcentajeAst(getFechasPeriodo("actual")["desde_prod"],getFechasPeriodo("actual")["hasta_prod"],$rut);

			if($data_ast and $cantidad_ordenes){
				$porcentaje = round(($data_ast[1][0] / $cantidad_ordenes) * 100,1);
				$array_data["data_ast"] = array("data" => $data_ast , "meta" => 100 , "porcentaje" => $porcentaje);
			}
			
		
		/***********INDICE ASISTENCIA **********************/

			$data_asistencia = $this->Igtmodel->dataAsistencia(getPeriodo($periodo),$id_tecnico);
			$array_data["data_asistencia"] = array("data" => $data_asistencia , "meta" => 100);


		/***********CALIDAD HFC*********************/

			$meta_calidad_hfc = $this->Igtmodel->getMetaIndicador($perfil_tecnico,5,$periodo);

			if($meta_calidad_hfc!="0"){
				$data_calidad_hfc = $this->Igtmodel->dataCalidadHFC(getPeriodo($periodo),$id_tecnico);
				$porcentajeCalidadHFC = $this->Igtmodel->porcentajeCalidadHFC(getPeriodo($periodo),$id_tecnico);

				if($data_calidad_hfc!=FALSE){
					$array_data["data_calidad_hfc"] = array("data" => $data_calidad_hfc , "meta" => $meta_calidad_hfc, "cumplimiento" => $porcentajeCalidadHFC);
				}
			}


		/***********CALIDAD FTTH*********************/

			$meta_calidad_ftth = $this->Igtmodel->getMetaIndicador($perfil_tecnico,6,$periodo);

			if($meta_calidad_ftth!="0"){
				$calidad_ftth = $this->Igtmodel->dataCalidadFTTH(getPeriodo($periodo),$id_tecnico);
				$porcentajeCalidadFTTH = $this->Igtmodel->porcentajeCalidadFTTH(getPeriodo($periodo),$id_tecnico);

				if($calidad_ftth!=FALSE){
					$array_data["data_calidad_ftth"] = array("data" => $calidad_ftth , "meta" => $meta_calidad_ftth, "cumplimiento" => $porcentajeCalidadFTTH);
				}
			}


		/***********DECLARACION OT *********************/

			$meta_declaracion_ot = $this->Igtmodel->getMetaIndicador($perfil_tecnico,7,$periodo);

			if($meta_declaracion_ot!="0"){
				$data_declaracion_ot = $this->Igtmodel->dataDeclaracionOT(getPeriodo($periodo),$id_tecnico);
				if($data_declaracion_ot!=FALSE){
					$array_data["data_declaracion_ot"] = array("data" => $data_declaracion_ot , "meta" => $meta_declaracion_ot);
				}
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

			/* if($this->Igtmodel->graficoHFC(getFechasPeriodo("anterior_3")["desde_calidad"],getFechasPeriodo("anterior_3")["hasta_calidad"],$trabajador)!=FALSE){
				$data_calidad_hfc[] = $this->Igtmodel->graficoHFC(getFechasPeriodo("anterior_3")["desde_calidad"],getFechasPeriodo("anterior_3")["hasta_calidad"],$trabajador);
			}

			if($this->Igtmodel->graficoHFC(getFechasPeriodo("anterior_4")["desde_calidad"],getFechasPeriodo("anterior_4")["hasta_calidad"],$trabajador)!=FALSE){
				$data_calidad_hfc[] = $this->Igtmodel->graficoHFC(getFechasPeriodo("anterior_4")["desde_calidad"],getFechasPeriodo("anterior_4")["hasta_calidad"],$trabajador);
			}

			if($this->Igtmodel->graficoHFC(getFechasPeriodo("anterior_5")["desde_calidad"],getFechasPeriodo("anterior_5")["hasta_calidad"],$trabajador)!=FALSE){
				$data_calidad_hfc[] = $this->Igtmodel->graficoHFC(getFechasPeriodo("anterior_5")["desde_calidad"],getFechasPeriodo("anterior_5")["hasta_calidad"],$trabajador);
			} */


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

			/* if($this->Igtmodel->graficoFTTH(getFechasPeriodo("anterior_3")["desde_calidad"],getFechasPeriodo("anterior_3")["hasta_calidad"],$trabajador)!=FALSE){
				$data_calidad_ftth[] = $this->Igtmodel->graficoFTTH(getFechasPeriodo("anterior_3")["desde_calidad"],getFechasPeriodo("anterior_3")["hasta_calidad"],$trabajador);
			}

			if($this->Igtmodel->graficoFTTH(getFechasPeriodo("anterior_4")["desde_calidad"],getFechasPeriodo("anterior_4")["hasta_calidad"],$trabajador)!=FALSE){
				$data_calidad_ftth[] = $this->Igtmodel->graficoFTTH(getFechasPeriodo("anterior_4")["desde_calidad"],getFechasPeriodo("anterior_4")["hasta_calidad"],$trabajador);
			}

			if($this->Igtmodel->graficoFTTH(getFechasPeriodo("anterior_5")["desde_calidad"],getFechasPeriodo("anterior_5")["hasta_calidad"],$trabajador)!=FALSE){
				$data_calidad_ftth[] = $this->Igtmodel->graficoFTTH(getFechasPeriodo("anterior_5")["desde_calidad"],getFechasPeriodo("anterior_5")["hasta_calidad"],$trabajador);
			} */

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

		/***** DTV *****/
		
			/*******WORK ORDEN************/

			$meta_work_orden = $this->Igtmodel->getMetaIndicador($perfil_tecnico,8,$periodo);

			if($meta_work_orden!="0"){

				$data_work_orden = $this->Igtmodel->dataWorkOrden(getPeriodo($periodo),$id_tecnico,$perfil_tecnico);
				$porcentaje_work_orden = $this->Igtmodel->porcentajeWorkOrden(getPeriodo($periodo),$id_tecnico,$periodo);

				if($data_work_orden!=FALSE){
					$array_data["data_work_orden"] = array("data" => $data_work_orden , "meta" => $meta_work_orden, "cumplimiento" => $porcentaje_work_orden);
				}

			}

			/*******CALIDAD SIN 30************/

			$meta_calidad_sin_30 = $this->Igtmodel->getMetaIndicador($perfil_tecnico,9,$periodo);

			if($meta_calidad_sin_30!="0"){

				$data_calidad_sin_30 = $this->Igtmodel->dataCalidadsin30(getPeriodo($periodo),$id_tecnico,$perfil_tecnico);
				$porcentaje_calidad_sin_30 = $this->Igtmodel->porcentajeCalidadsin30(getPeriodo($periodo),$id_tecnico,$periodo);
				
				if($data_calidad_sin_30!=FALSE){
					$array_data["data_calidad_sin_30"] = array("data" => $data_calidad_sin_30 , "meta" => $meta_calidad_sin_30, "cumplimiento" => $porcentaje_calidad_sin_30);
				
				}

			}

			/*******ENCUESTA 3 DE 3************/

			$meta_encuesta_3_3 = $this->Igtmodel->getMetaIndicador($perfil_tecnico,10,$periodo);

			if($meta_encuesta_3_3!="0"){

				$data_encuesta_3_3 = $this->Igtmodel->dataencuesta_3_3(getPeriodo($periodo),$id_tecnico,$perfil_tecnico);
				$porcentaje_encuesta_3_3 = $this->Igtmodel->porcentajeEncuesta_3_3(getPeriodo($periodo),$id_tecnico,$periodo);
				
				if($data_encuesta_3_3!=FALSE){
					$array_data["data_encuesta_3_3"] = array("data" => $data_encuesta_3_3 , "meta" => $meta_encuesta_3_3, "cumplimiento" => $porcentaje_encuesta_3_3);
				}

			}

			/*******CICLE TIME************/

			$meta_cicle_time = $this->Igtmodel->getMetaIndicador($perfil_tecnico,11,$periodo);

			if($meta_cicle_time!="0"){

				$data_cicle_time = $this->Igtmodel->dataCicleTime(getPeriodo($periodo),$id_tecnico,$perfil_tecnico);
				$porcentaje_cicle_time = $this->Igtmodel->porcentajeCicleTime(getPeriodo($periodo),$id_tecnico,$periodo);
				
				if($data_cicle_time!=FALSE){
					$array_data["data_cicle_time"] = array("data" => $data_cicle_time , "meta" => $meta_cicle_time, "cumplimiento" => $porcentaje_cicle_time);
				}

			}

			/*******OPTIMUS************/

			$meta_optimus = $this->Igtmodel->getMetaIndicador($perfil_tecnico,12,$periodo);

			if($meta_optimus!="0"){

				$data_optimus = $this->Igtmodel->dataOptimus(getPeriodo($periodo),$id_tecnico,$perfil_tecnico);
				$porcentaje_optimus = $this->Igtmodel->porcentajeOptimus(getPeriodo($periodo),$id_tecnico,$periodo);
				
				if($data_optimus!=FALSE){
					$array_data["data_optimus"] = array("data" => $data_optimus , "meta" => $meta_optimus, "cumplimiento" => $porcentaje_optimus);
				}

			}

		echo json_encode($array_data);

	}

		public function listaTrabajadoresIGT(){
			$jefe=$this->security->xss_clean(strip_tags($this->input->get_post("jefe")));
	 	    echo $this->Igtmodel->listaTrabajadoresIGT($jefe);exit;
		}

		public function getProyectoTecnicoRut(){
			$rut=$this->security->xss_clean(strip_tags($this->input->get_post("trabajador")));
	 	    echo $this->Igtmodel->getProyectoTecnicoRut($rut);exit;
		}


	/*******CALIDAD*******/

		public function listaCalidadIGT(){
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
				}elseif($periodo=="anterior_2"){
					$desde = date('Y-m-d', strtotime('-3 month', strtotime(date('Y-m-25'))));
					$hasta = date('Y-m-d', strtotime('-2 month', strtotime(date('Y-m-24'))));
				}

			}else{
				if($periodo=="actual"){
					$desde = date('Y-m-d', strtotime('-2 month', strtotime(date('Y-m-25'))));
					$hasta = date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-24'))));
				}elseif($periodo=="anterior"){
					$desde = date('Y-m-d', strtotime('-3 month', strtotime(date('Y-m-25'))));
					$hasta = date('Y-m-d', strtotime('-2 month', strtotime(date('Y-m-24'))));
				}elseif($periodo=="anterior_2"){
					$desde = date('Y-m-d', strtotime('-4 month', strtotime(date('Y-m-25'))));
					$hasta = date('Y-m-d', strtotime('-3 month', strtotime(date('Y-m-24'))));
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
				}elseif($periodo=="anterior_2"){
					$desde = date('Y-m-d', strtotime('-3 month', strtotime(date('Y-m-25'))));
					$hasta = date('Y-m-d', strtotime('-2 month', strtotime(date('Y-m-24'))));
				}

			}else{
				if($periodo=="actual"){
					$desde = date('Y-m-d', strtotime('-2 month', strtotime(date('Y-m-25'))));
					$hasta = date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-24'))));
				}elseif($periodo=="anterior"){
					$desde = date('Y-m-d', strtotime('-3 month', strtotime(date('Y-m-25'))));
					$hasta = date('Y-m-d', strtotime('-2 month', strtotime(date('Y-m-24'))));
				}elseif($periodo=="anterior_2"){
					$desde = date('Y-m-d', strtotime('-4 month', strtotime(date('Y-m-25'))));
					$hasta = date('Y-m-d', strtotime('-3 month', strtotime(date('Y-m-24'))));
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

		public function listaDetalleIgt(){
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
				}elseif($periodo=="anterior_2"){
					$desde= date('Y-m-d', strtotime('-2 month', strtotime(date('Y-m-25'))));
					$hasta= date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-24'))));
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

			echo json_encode($this->Igtmodel->listaDetalle($desde,$hasta,$trabajador,$jefe));

		}	

		public function excel_detalle_prod_igt(){
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
				}elseif($periodo=="anterior_2"){
					$desde= date('Y-m-d', strtotime('-2 month', strtotime(date('Y-m-25'))));
					$hasta= date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-24'))));
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


			$data=$this->Igtmodel->listaDetalle($desde,$hasta,$trabajador,$jefe);

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
				}elseif($periodo=="anterior_2"){
					$desde= date('Y-m-d', strtotime('-2 month', strtotime(date('Y-m-25'))));
					$hasta= date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-24'))));
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
				}elseif($periodo=="anterior_2"){
					$desde= date('Y-m-d', strtotime('-2 month', strtotime(date('Y-m-25'))));
					$hasta= date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-24'))));
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

	/****** DTV ********/

	public function listaDtv(){
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
			}elseif($periodo=="anterior_2"){
				$desde= date('Y-m-d', strtotime('-2 month', strtotime(date('Y-m-25'))));
				$hasta= date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-24'))));
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

		echo json_encode($this->Igtmodel->listaDtv($desde,$hasta,$trabajador,$jefe));
	}	

	public function excel_dtv(){
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
			}elseif($periodo=="anterior_2"){
				$desde= date('Y-m-d', strtotime('-2 month', strtotime(date('Y-m-25'))));
				$hasta= date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-24'))));
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


		$data=$this->Igtmodel->listaDtv($desde,$hasta,$trabajador,$jefe);

		if(!$data){
			echo "Sin datos disponibles.";
			return FALSE;
		}else{

			$nombre="reporte-dtv".date("d-m-Y",strtotime($desde))."-".date("d-m-Y",strtotime($hasta)).".xls";
			header("Content-type: application/vnd.ms-excel;  charset=utf-8");
			header("Content-Disposition: attachment; filename=$nombre");
			?>
			<style type="text/css">
			.head{font-size:13px;height: 30px; background-color:#32477C;color:#fff; font-weight:bold;padding:10px;margin:10px;vertical-align:middle;}
			td{font-size:12px;text-align:center;   vertical-align:middle;}
			
			</style>
			<h3>Reporte DTV <?php echo date("d-m-Y",strtotime($desde)); ?> - <?php echo date("d-m-Y",strtotime($hasta)); ?></h3>
				<table align='center' border="1"> 
					<tr style="background-color:#F9F9F9">
						<th class="head">N° Work orden</th> 
						<th class="head">Rut</th> 
						<th class="head">Nombre T&eacute;cnico</th> 
						<th class="head">Domicilio</th> 
						<th class="head">Ciudad</th> 
						<th class="head">Servicio</th> 
						<th class="head">Fecha finalizaci&oacute;n</th> 
						<th class="head">Estado</th>  
					</tr>
					</thead>	
					<tbody>
					<?php 
						if($data !=FALSE){
							  foreach($data as $d){
							  ?>
							   <tr>
								 <td><?php echo utf8_decode($d["n_work_orden"]); ?></td>
								 <td><?php echo utf8_decode($d["rut_tecnico"]); ?></td>
								 <td><?php echo utf8_decode($d["nombre_tecnico"]); ?></td>
								 <td><?php echo utf8_decode($d["domicilio"]); ?></td>
								 <td><?php echo utf8_decode($d["ciudad"]); ?></td>
								 <td><?php echo utf8_decode($d["servicio"]); ?></td>
								 <td><?php echo utf8_decode($d["fecha_finalizacion"]); ?></td>
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


}