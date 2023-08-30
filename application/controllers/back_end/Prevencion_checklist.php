<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use setasign\Fpdi\Fpdi;

require_once(APPPATH.'libraries/fpdf/fpdf.php');
require_once(APPPATH.'libraries/fpdf/fpdi/src/autoload.php');

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class Prevencion_checklist extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model("back_end/Prevencion_checklistmodel");
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
	      	if($this->session->userdata('id_perfil')==""){
	      		redirect("./login");
	      	}
	      }else{
	      	redirect("./login");
	    }
	}

	public function visitas($modulo){
		$this->load->library('user_agent');
		$data=array("id_usuario"=>$this->session->userdata('id'),
			"id_aplicacion"=>24,
			"modulo"=>$modulo,
	     	"fecha"=>date("Y-m-d G:i:s"),
	    	"navegador"=>"navegador :".$this->agent->browser()."\nversion :".$this->agent->version()."\nos :".$this->agent-> platform()."\nmovil :".$this->agent->mobile(),
	    	"ip"=>$this->input->ip_address(),
    	);
    	$this->Prevencion_checklistmodel->insertarVisita($data);
	}

	public function index(){
		$this->visitas("Prevencion_checklist");
    	$this->acceso();
	    $datos = array(
	        'titulo' => "Prevención Terreno",
	        'contenido' => "prevencion_checklist/inicio",
	        'perfiles' => $this->Iniciomodel->listaPerfiles(),
	    );  
		$this->load->view('plantillas/plantilla_back_end',$datos);
	}

	/***** EPPS Y CONDICION FINAL *****/

		public function getPrevencion_EPPSInicio(){
			if($this->input->is_ajax_request()){
				$fecha_hoy = date('d-m-Y');	
				$epp=$this->Prevencion_checklistmodel->listaepps();
				$trabajadores=$this->Prevencion_checklistmodel->listaTrabajadores();
				$riesgos=$this->Prevencion_checklistmodel->listariesgos();
				$acciones=$this->Prevencion_checklistmodel->listacciones();
				$cargos=$this->Prevencion_checklistmodel->listacargos();
				$plazas=$this->Prevencion_checklistmodel->listaplazas();
				$proyectos=$this->Prevencion_checklistmodel->listaproyectos();
				$areas=$this->Prevencion_checklistmodel->listaAreas();

				$datos = array(
					'hoy' => $fecha_hoy,
					'checklist' => $epp,
					'trabajadores' => $trabajadores,
					'riesgos' => $riesgos,
					'acciones' => $acciones,
					'cargos' => $cargos,
					'plazas' => $plazas,
					'proyectos' => $proyectos,
					'areas' => $areas,
				);
				$this->load->view('back_end/prevencion_checklist/epps',$datos);
			}
		}

		public function formCondiciones(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();	
				//$this->security->xss_clean(strip_tags());
				$hash = $this->input->post("hash_liqui");
				$responsable_inspeccion = $this->input->post("responsable_inspeccion");
				$cargo = $this->input->post("cargo");
				$fecha_inspeccion = $this->input->post("fecha_inspeccion");
				$fecha_generacion = $this->input->post("fecha_generacion");
				$tecnico_auditado = $this->input->post("tecnico_auditado");
				$rut_tecnico_auditado = $this->security->xss_clean(strip_tags($this->input->post("rut_tecnico_auditado")));
				$zona = $this->input->post("zona");
				$plaza = $this->input->post("plaza");
				$proyecto = $this->input->post("proyecto");
				$ultima_actualizacion=date("Y-m-d G:i:s")." | ".$this->session->userdata("nombresUsuario")." ".$this->session->userdata("apellidosUsuario");
				$adjunto = $_FILES["userfile"]["name"];

				$herramientas=$this->input->post("herramientas");
				$estado_epps=$this->input->post("estado_epps");
				$uso_epps=$this->input->post("uso_epps");
				$observacion_epps=$this->input->post("observacion_epps");
				$epps = array();

				for ($i = 0; $i < count($herramientas); $i++) {
					$herramienta = array(
						"id" => $herramientas[$i],
						"estado" => $estado_epps[$i],
						"uso" => $uso_epps[$i],
						"observacion" => $observacion_epps[$i]
					);
					$epps[] = $herramienta;
				}
				$JSONepps = json_encode($epps);

				$riesgos=$this->input->post("riesgos");
				$resultado_riesgos=$this->input->post("resultado_riesgos");
				$riesgos_nivel=$this->input->post("riesgos_nivel");
				$observacion_riesgos=$this->input->post("observacion_riesgos");
				$lriesgos = array();

				for ($i = 0; $i < count($riesgos); $i++) {
					$riesgo = array(
						"id" => $riesgos[$i],
						"resultado" => $resultado_riesgos[$i],
						"riesgo" => $riesgos_nivel[$i],
						"observacion" => $observacion_riesgos[$i]
					);
					$lriesgos[] = $riesgo;
				}
				$JSONriesgos = json_encode($lriesgos);

				$acciones=$this->input->post("acciones");
				$acciones_estado=$this->input->post("acciones_estado");
				$responsable_accion=$this->input->post("responsable_accion");
				$observacion_accion=$this->input->post("observacion_accion");
				$lacciones = array();

				for ($i = 0; $i < count($acciones); $i++) {
					$accion = array(
						"id" => $acciones[$i],
						"accion" => $acciones_estado[$i],
						"responsable" => $responsable_accion[$i],
						"observacion" => $observacion_accion[$i]
					);
					$lacciones[] = $accion;
				}
				$JSONacciones = json_encode($lacciones);
				$firma=$this->input->post("firma");

				if($herramientas == null){
					echo json_encode(array('res'=>"error", 'msg' => 'Debe subir al menos un epps.'));exit;
				}
				if($riesgos == null){
					echo json_encode(array('res'=>"error", 'msg' => 'Debe subir al menos un riesgo.'));exit;
				}
				if($acciones == null){
					echo json_encode(array('res'=>"error", 'msg' => 'Debe subir al menos una acción.'));exit;
				}
				
				if ($this->form_validation->run("formCondiciones") == FALSE){
					echo json_encode(array('res'=>"error", 'msg' => strip_tags(validation_errors())));
					exit;
				}else{
					if($hash==""){
						if($adjunto==""){
							echo json_encode(array('res'=>"error", 'msg' => 'Debe subir el archivo.'));exit;
						}
						$data = array(
							'responsable' => $responsable_inspeccion,
							'cargo_responsable' => $cargo,
							'fecha_inspeccion' => $fecha_inspeccion,
							'fecha_reporte' => $fecha_generacion,
							'tecnico_auditado' => $tecnico_auditado,
							'rut_tecnico' => $rut_tecnico_auditado,
							'zona' => $zona,
							'plaza' => $plaza,
							'proyecto' => $proyecto,
							'epps' => $JSONepps,
							'riesgos' => $JSONriesgos,
							'acciones' => $JSONacciones,
							'firma' => $firma,
							'ultima_actualizacion' => $ultima_actualizacion,
						);
						$path = $_FILES['userfile']['name'];
						$ext = pathinfo($path, PATHINFO_EXTENSION);
						$carpeta = "archivos/prevencion/";
						$archivo =  date("ymdHis").".".$ext;
						$adjunto = $_FILES["userfile"]["name"];
						if($adjunto!=""){
							$nombre=$this->procesaArchivo($_FILES["userfile"],$archivo,$carpeta);
							$data["archivo"]=$carpeta.$archivo;
						}
						$insert_id=$this->Prevencion_checklistmodel->ingresarCondicion($data);
						if($insert_id!=FALSE){
							echo json_encode(array('res'=>"ok",  'msg' => OK_MSG));exit;
						}else{
							echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
						}
					}else{
						if($adjunto==""){
							echo json_encode(array('res'=>"error", 'msg' => 'Debe subir el archivo.'));exit;
						}
						$data_mod= array(
							'responsable' => $responsable_inspeccion,
							'cargo_responsable' => $cargo,
							'fecha_inspeccion' => $fecha_inspeccion,
							'fecha_reporte' => $fecha_generacion,
							'tecnico_auditado' => $tecnico_auditado,
							'rut_tecnico' => $rut_tecnico_auditado,
							'zona' => $zona,
							'plaza' => $plaza,
							'proyecto' => $proyecto,
							'epps' => $JSONepps,
							'riesgos' => $JSONriesgos,
							'acciones' => $JSONacciones,
							'firma' => $firma,
							'ultima_actualizacion' => $ultima_actualizacion,
						);
						$path = $_FILES['userfile']['name'];
						$ext = pathinfo($path, PATHINFO_EXTENSION);
						$carpeta = "archivos/prevencion/";
						$archivo =  date("ymdHis").".".$ext;
						$adjunto = $_FILES["userfile"]["name"];
						if($adjunto!=""){
							$nombre=$this->procesaArchivo($_FILES["userfile"],$archivo,$carpeta);
							$data_mod["archivo"]=$carpeta.$archivo;
						}
						if($this->Prevencion_checklistmodel->ActualizarCondicion($hash,$data_mod)){
							echo json_encode(array('res'=>"ok",  'msg' => OK_MSG));exit;
						}else{
							echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
						}
					}
				}
			}
			else{
				exit('No direct script access allowed');
			}
			
		}

		public function getCondicionesList(){
			echo json_encode($this->Prevencion_checklistmodel->getCondicionesList());
		}

		public function eliminaCondicion(){
			$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
			if($this->Prevencion_checklistmodel->eliminaCondicion($hash)){
				  echo json_encode(array("res" => "ok" , "msg" => "Registro eliminado correctamente."));
			}else{
				  echo json_encode(array("res" => "error" , "msg" => "Problemas eliminando el registro, intente nuevamente."));
			}
		}

		public function getDataCondicion(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();	
				$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
				$data=$this->Prevencion_checklistmodel->getDataCondicion($hash);
				$epps=json_decode($data[0]['epps']);
				$data[0]['epps']= $epps;
				$riesgos=json_decode($data[0]['riesgos']);
				$data[0]['riesgos']= $riesgos;
				$acciones=json_decode($data[0]['acciones']);
				$data[0]['acciones']= $acciones;
				if($data){
					echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
				}else{
					echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
				}	
			}else{
				exit('No direct script access allowed');
			}
		}
	

	/***** INVESTIGACION ACCIDENTES *****/

		public function getPrevencion_IAInicio(){
			if($this->input->is_ajax_request()){
				$fecha_anio_atras = date('d-m-Y', strtotime('-365 day', strtotime(date("d-m-Y"))));
				$fecha_hoy = date('d-m-Y');	
				$comunas=$this->Prevencion_checklistmodel->listaComunas();
				$trabajadores=$this->Prevencion_checklistmodel->listaTrabajadores();
				$cargos=$this->Prevencion_checklistmodel->listacargos();
				$datos = array(
					'hoy' => $fecha_hoy,
					'comunas' => $comunas,
					'trabajadores' => $trabajadores,
					'cargos' => $cargos,
				);
				$this->load->view('back_end/prevencion_checklist/investigacion_accidentes',$datos);
			}
		}

		public function formInvestigaciones(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();	
				//$this->security->xss_clean(strip_tags());
				$hash = $this->input->post("hash_liqui");
				$fecha=$this->input->post("fecha");
				$hora=$this->input->post("hora");
				$tipo=$this->input->post("tipo");
				$lugar=$this->input->post("lugar");
				$this->security->xss_clean(strip_tags($direccion=$this->input->post("direccion")));
				$comuna=$this->input->post("comuna");
				$nombre_informante=$this->input->post("nombre_informante");
				$cargo_informante=$this->input->post("cargo_informante");
				$this->security->xss_clean(strip_tags($descripcion=$this->input->post("descripcion")));
				$nombre_afectado=$this->input->post("nombre_afectado");
				$cargo_afectado=$this->input->post("cargo_afectado");
				$this->security->xss_clean(strip_tags($rut_afectado=$this->input->post("rut_afectado")));
				$horas_trabajadas=$this->input->post("horas_trabajadas");
				$gravedad_lesion=$this->input->post("gravedad_lesion");
				$tipo_lesion=$this->input->post("tipo_lesion");
				$this->security->xss_clean(strip_tags($nombre_testigo_1=$this->input->post("nombre_testigo_1")));
				$this->security->xss_clean(strip_tags($relacion_testigo_1=$this->input->post("relacion_testigo_1")));
				$this->security->xss_clean(strip_tags($nombre_testigo_2=$this->input->post("nombre_testigo_2")));
				$this->security->xss_clean(strip_tags($relacion_testigo_2=$this->input->post("relacion_testigo_2")));
				$this->security->xss_clean(strip_tags($nombre_testigo_3=$this->input->post("nombre_testigo_3")));
				$this->security->xss_clean(strip_tags($relacion_testigo_3=$this->input->post("relacion_testigo_3")));
				$this->security->xss_clean(strip_tags($observacion=$this->input->post("observacion")));
				$ultima_actualizacion=date("Y-m-d G:i:s")." | ".$this->session->userdata("nombresUsuario")." ".$this->session->userdata("apellidosUsuario");
				$adjunto = $_FILES["userfile"]["name"];

				if ($this->form_validation->run("formInvestigaciones") == FALSE)
					{echo json_encode(array('res'=>"error", 'msg' => strip_tags(validation_errors())));exit;}
				else{
					if($hash==""){
						if($adjunto==""){
							echo json_encode(array('res'=>"error", 'msg' => 'Debe subir el archivo.'));exit;
						}
						$data = array(
							'fecha' => $fecha,
							'hora' => $hora,
							'tipo' => $tipo,
							'lugar' => $lugar,
							'direccion' => $direccion,
							'comuna' => $comuna,
							'nombre_informante' => $nombre_informante,
							'cargo_informante' => $cargo_informante,
							'descripcion' => $descripcion,
							'nombre_afectado' => $nombre_afectado,
							'cargo_afectado' => $cargo_afectado,
							'rut_afectado' => $rut_afectado,
							'horas_trabajadas' => $horas_trabajadas,
							'gravedad_lesion' => $gravedad_lesion,
							'tipo_lesion' => $tipo_lesion,
							'nombre_testigo_1' => $nombre_testigo_1,
							'relacion_testigo_1' => $relacion_testigo_1,
							'nombre_testigo_2' => $nombre_testigo_2,
							'relacion_testigo_2' => $relacion_testigo_2,
							'nombre_testigo_3' => $nombre_testigo_3,
							'relacion_testigo_3' => $relacion_testigo_3,
							'observacion' => $observacion,
							'ultima_actualizacion' => $ultima_actualizacion
						);
	
						$path = $_FILES['userfile']['name'];
						$ext = pathinfo($path, PATHINFO_EXTENSION);
						$carpeta = "archivos/prevencion/";
						$archivo =  date("ymdHis").".".$ext;
						$adjunto = $_FILES["userfile"]["name"];
						if($adjunto!=""){
							$nombre=$this->procesaArchivo($_FILES["userfile"],$archivo,$carpeta);
							$data["archivo"]=$carpeta.$archivo;
						}
	
						$insert_id=$this->Prevencion_checklistmodel->ingresarInvestigacion($data);
	
						if($insert_id!=FALSE){
							echo json_encode(array('res'=>"ok",  'msg' => OK_MSG));exit;
						}else{
							echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
						}
					}else{
						if($adjunto==""){
							echo json_encode(array('res'=>"error", 'msg' => 'Debe subir el archivo.'));exit;
						}
						$data_mod= array(
							'fecha' => $fecha,
							'hora' => $hora,
							'tipo' => $tipo,
							'lugar' => $lugar,
							'direccion' => $direccion,
							'comuna' => $comuna,
							'nombre_informante' => $nombre_informante,
							'cargo_informante' => $cargo_informante,
							'descripcion' => $descripcion,
							'nombre_afectado' => $nombre_afectado,
							'cargo_afectado' => $cargo_afectado,
							'rut_afectado' => $rut_afectado,
							'horas_trabajadas' => $horas_trabajadas,
							'gravedad_lesion' => $gravedad_lesion,
							'tipo_lesion' => $tipo_lesion,
							'nombre_testigo_1' => $nombre_testigo_1,
							'relacion_testigo_1' => $relacion_testigo_1,
							'nombre_testigo_2' => $nombre_testigo_2,
							'relacion_testigo_2' => $relacion_testigo_2,
							'nombre_testigo_3' => $nombre_testigo_3,
							'relacion_testigo_3' => $relacion_testigo_3,
							'observacion' => $observacion,
							'ultima_actualizacion' => $ultima_actualizacion
						);
	
						$path = $_FILES['userfile']['name'];
						$ext = pathinfo($path, PATHINFO_EXTENSION);
						$carpeta = "archivos/prevencion/";
						$archivo =  date("ymdHis").".".$ext;
						$adjunto = $_FILES["userfile"]["name"];
						if($adjunto!=""){
							$nombre=$this->procesaArchivo($_FILES["userfile"],$archivo,$carpeta);
							$data_mod["archivo"]=$carpeta.$archivo;
						}
						if($this->Prevencion_checklistmodel->ActualizarInvestigacion($hash,$data_mod)){
							echo json_encode(array('res'=>"ok",  'msg' => OK_MSG));exit;
						}else{
							echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
						}
					}
				}
			}
			else{
				exit('No direct script access allowed');
			}
			
		}

		public function getInvestigacionesList(){
			echo json_encode($this->Prevencion_checklistmodel->getInvestigacionesList());
		}

		public function eliminaInvestigacion(){
			$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
			if($this->Prevencion_checklistmodel->eliminaInvestigacion($hash)){
				  echo json_encode(array("res" => "ok" , "msg" => "Registro eliminado correctamente."));
			}else{
				  echo json_encode(array("res" => "error" , "msg" => "Problemas eliminando el registro, intente nuevamente."));
			}
		}

		public function getDataInvestigacion(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();	
				$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
				$data=$this->Prevencion_checklistmodel->getDataInvestigacion($hash);
				if($data){
					echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
				}else{
					echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
				}	
			}else{
				exit('No direct script access allowed');
			}
		}
	
	/***** REUNIONES DE EQUIPO *****/
	
		public function getPrevencion_REInicio(){
			if($this->input->is_ajax_request()){
				$fecha_anio_atras = date('d-m-Y', strtotime('-365 day', strtotime(date("d-m-Y"))));
				$fecha_hoy = date('Y-m-d');	
				$trabajadores=$this->Prevencion_checklistmodel->listaTrabajadores();
				$cargos=$this->Prevencion_checklistmodel->listacargos();
				$datos = array(
					'fecha_hoy' => $fecha_hoy,
					'trabajadores' => $trabajadores,
					'cargos' => $cargos,
				);
				$this->load->view('back_end/prevencion_checklist/reuniones_equipo',$datos);
			}
		}

		public function formReuniones(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();	
				//$this->security->xss_clean(strip_tags());$hash = $this->input->post("hash_liqui");
				$this->security->xss_clean(strip_tags($hash = $this->input->post("hash_liqui")));
				$fecha_reunion=$this->input->post("fecha");
				$fecha_generacion=$this->input->post("fecha_generacion");
				$inicio=$this->input->post("inicio");
				$termino=$this->input->post("termino");
				$area=$this->input->post("area");
				$this->security->xss_clean(strip_tags($objetivo=$this->input->post("objetivo")));
				$nombre_asistentes=$this->input->post("nombre_asistentes");
				$cargos_asistentes=$this->input->post("cargos");
				$this->security->xss_clean(strip_tags($tema1=$this->input->post("tema_1")));
				$this->security->xss_clean(strip_tags($tema2=$this->input->post("tema_2")));
				$this->security->xss_clean(strip_tags($tema3=$this->input->post("tema_3")));
				$this->security->xss_clean(strip_tags($tema4=$this->input->post("tema_4")));
				$this->security->xss_clean(strip_tags($tema5=$this->input->post("tema_5")));
				$this->security->xss_clean(strip_tags($observacion=$this->input->post("observacion")));
				$responsable_inspeccion=$this->input->post("responsable_inspeccion");
				$cargo_prevencionista=$this->input->post("cargo_prevencionista");
				$firma=$this->input->post("firma");

				if($nombre_asistentes == null){
					echo json_encode(array('res'=>"error", 'msg' => 'Debe subir al menos un asistente.'));exit;
				}
				elseif($cargos_asistentes == null){
					echo json_encode(array('res'=>"error", 'msg' => 'Agregar cargo a los asistentes.'));exit;
				}

				if ($this->form_validation->run("formReuniones") == FALSE){
					echo json_encode(array('res'=>"error", 'msg' => strip_tags(validation_errors())));
					exit;}
				else{

					$asistentes = array();
					for ($i = 0; $i < count($nombre_asistentes); $i++) {
						$asistente = array(
							"nombre" => $nombre_asistentes[$i],
							"cargo" => $cargos_asistentes[$i]
						);
						$asistentes[] = $asistente;
					}
					$JSONasistentes = json_encode($asistentes);

					if($hash==""){
						$data = array(
							"fecha_reunion" => $fecha_reunion,
							"fecha_generacion" => $fecha_generacion,
							"inicio" => $inicio,
							"termino" => $termino,
							"area" => $area,
							"objetivo" => $objetivo,
							"asistentes" => $JSONasistentes,
							"tema_1" => $tema1,
							"tema_2" => $tema2,
							"tema_3" => $tema3,
							"tema_4" => $tema4,
							"tema_5" => $tema5,
							"observacion" => $observacion,
							"responsable_inspeccion" => $responsable_inspeccion,
							"cargo_prevencionista" => $cargo_prevencionista,
							"firma" => $firma,
						);
	
						$insert_id=$this->Prevencion_checklistmodel->ingresarReunion($data);
	
						if($insert_id!=FALSE){
							echo json_encode(array('res'=>"ok",  'msg' => OK_MSG));exit;
						}else{
							echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
						}
					}else{
						$data_mod= array(
							"fecha_reunion" => $fecha_reunion,
							"fecha_generacion" => $fecha_generacion,
							"inicio" => $inicio,
							"termino" => $termino,
							"area" => $area,
							"objetivo" => $objetivo,
							"asistentes" => $JSONasistentes,
							"tema_1" => $tema1,
							"tema_2" => $tema2,
							"tema_3" => $tema3,
							"tema_4" => $tema4,
							"tema_5" => $tema5,
							"observacion" => $observacion,
							"responsable_inspeccion" => $responsable_inspeccion,
							"cargo_prevencionista" => $cargo_prevencionista,
							"firma" => $firma,
						);
						if($this->Prevencion_checklistmodel->ActualizarReunion($hash,$data_mod)){
							echo json_encode(array('res'=>"ok",  'msg' => OK_MSG));exit;
						}else{
							echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
						}
					}
				}
			}
			else{
				exit('No direct script access allowed');
			}
		}

		public function getReunionesList(){
			echo json_encode($this->Prevencion_checklistmodel->getReunionesList());
		}

		public function eliminaReunion(){
			$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
			if($this->Prevencion_checklistmodel->eliminaReunion($hash)){
				  echo json_encode(array("res" => "ok" , "msg" => "Registro eliminado correctamente."));
			}else{
				  echo json_encode(array("res" => "error" , "msg" => "Problemas eliminando el registro, intente nuevamente."));
			}
		}

		public function getDataReunion(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();	
				$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
				$data=$this->Prevencion_checklistmodel->getDataReunion($hash);
				$asistentes=json_decode($data[0]['asistentes']);
				$data[0]['asistentes']= $asistentes;
				if($data){
					echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
				}else{
					echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
				}	
			}else{
				exit('No direct script access allowed');
			}
		}

	/**/ 

	public function getPrevencion_checklistList(){
		$jefe=$this->security->xss_clean(strip_tags($this->input->get_post("jefe")));
		$trabajador=$this->security->xss_clean(strip_tags($this->input->get_post("trabajador")));
		$periodo=$this->security->xss_clean(strip_tags($this->input->get_post("periodo")));
		if($periodo!=""){$periodo=date("m-Y",strtotime($periodo));}else{$periodo="";}
		echo json_encode($this->Prevencion_checklistmodel->getPrevencion_checklistList($jefe,$trabajador,$periodo));
	}

	
	public function formPrevencion_checklist(){

		if($this->input->is_ajax_request()){
			$this->checkLogin();	
			$hash = $this->security->xss_clean(strip_tags($this->input->post("hash_liqui")));
			$id_trabajador = $this->security->xss_clean(strip_tags($this->input->post("trabajadores")));
			$periodo = $this->security->xss_clean(strip_tags($this->input->post("periodo")));
			$fecha_subida = date("d-m-Y");
			$id_digitador = $this->session->userdata("id");
			$adjunto = $_FILES["userfile"]["name"];
			$ultima_actualizacion = date("Y-m-d G:i:s")." | ".$this->session->userdata("nombre_completo");

   			if ($this->form_validation->run("formPrevencion_checklist") == FALSE){
				echo json_encode(array('res'=>"error", 'msg' => strip_tags(validation_errors())));exit;
			}else{	

				$area = $this->Prevencion_checklistmodel->getAreaUsuario($id_trabajador);

				$rut = $this->Prevencion_checklistmodel->getRutUsuario($id_trabajador);

				$mes = date("m", strtotime($periodo));

				$anio = date("Y", strtotime($periodo));

				$fecha_creacion_carpeta = $mes.'-'.$anio;

				if($hash==""){

					if($adjunto==""){

						echo json_encode(array('res'=>"error", 'msg' => 'Debe subir el archivo.'));exit;

					}else{

						$path = $_FILES['userfile']['name'];

						$ext = pathinfo($path, PATHINFO_EXTENSION);
	
						$carpeta = "archivos/Prevencion_checklist/".$fecha_creacion_carpeta.'_'.convert_accented_characters(trim($area));

						$nombre_archivo = $carpeta.'/'.$rut.'_'.$periodo.".".$ext;

						$archivo =  $rut.'_'.$periodo.".".$ext;

						if (!file_exists($carpeta)) { 
							mkdir($carpeta, 0777, true); 
						}
						
						$data = array('id_usuario' => $id_trabajador,
							'id_digitador' => $id_digitador,
							'archivo' => $nombre_archivo,
							'fecha' =>  date("Y-m-d"),
							'fecha_carpeta' => $fecha_creacion_carpeta,
							'carpeta' => $carpeta,
							'periodo' => $fecha_creacion_carpeta,
							'ultima_actualizacion'=>$ultima_actualizacion
						);
	
						
						if($adjunto!=""){
							$nombre=$this->procesaArchivo($_FILES["userfile"],$archivo,$carpeta);
							$data["nombre_archivo"]=$nombre;
						}
	
						$insert_id=$this->Prevencion_checklistmodel->ingresarLiquidacion($data);

						if($insert_id!=FALSE){

							echo json_encode(array('res'=>"ok",  'msg' => OK_MSG));exit;

						}else{

							echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;

						}

					}

				}else{

					if($adjunto==""){

						echo json_encode(array('res'=>"error", 'msg' => 'Debe subir el archivo.'));exit;

					}else{
							
						$path = $_FILES['userfile']['name'];

						$ext = pathinfo($path, PATHINFO_EXTENSION);

						$carpeta = "archivos/Prevencion_checklist/".$fecha_creacion_carpeta.'_'.convert_accented_characters(trim($area));

						$nombre_archivo = $carpeta.'/'.$rut.'_'.$periodo.".".$ext;

						$archivo =  $rut.'_'.$periodo.".".$ext;

						if (!file_exists($carpeta)) { 
							mkdir($carpeta, 0777, true); 
						}

						$data_mod= array('id_usuario' => $id_trabajador,
							'id_digitador' => $id_digitador,
							'archivo' => $nombre_archivo,
							'fecha' =>  date("Y-m-d"),
							'fecha_carpeta' => $fecha_creacion_carpeta,
							'carpeta' => $carpeta,
							'periodo' => $fecha_creacion_carpeta,
							'ultima_actualizacion'=>$ultima_actualizacion
						);

						if($adjunto!=""){
							$nombre=$this->procesaArchivo($_FILES["userfile"],$archivo,$carpeta);
							$data_mod["nombre_archivo"]=$nombre;
						}

					}

					if($this->Prevencion_checklistmodel->formActualizar($hash,$data_mod)){

						echo json_encode(array('res'=>"ok",  'msg' => OK_MSG));exit;
					}else{
						echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;

					}

				}

			}

		}else{
			exit('No direct script access allowed');
		}
		
	}
	

	public function listaTrabajadores(){
		$jefe=$this->security->xss_clean(strip_tags($this->input->get_post("jefe")));
		echo $this->Prevencion_checklistmodel->listaTrabajadores($jefe);exit;
	}
	
	public function procesaArchivo($file,$titulo,$carpeta){
			$path = $file['name'];
 			$config['upload_path'] = $carpeta;
			$config['allowed_types'] = 'pdf|PDF|jpg|JPG|jpeg|JPEG|png|PNG';
		    $config['file_name'] = $titulo;
			$config['max_size']	= '5300';
			$config['overwrite']	= FALSE;
			$this->load->library('upload', $config);
			$_FILES['userfile']['name'] = $titulo;
		    $_FILES['userfile']['type'] = $file['type'];
		    $_FILES['userfile']['tmp_name'] = $file['tmp_name'];
		    $_FILES['userfile']['error'] = $file['error'];
			$_FILES['userfile']['size'] = $file['size'];
			$this->upload->initialize($config);

			if (!$this->upload->do_upload()){ 
			    echo json_encode(array('res'=>"error", 'msg' =>strip_tags($this->upload->display_errors())));exit;
		    }else{
		    	unset($config);
		    	return $titulo;
		    }
    }

	public function eliminaPrevencion_checklist(){
		$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
		$ruta = $this->Prevencion_checklistmodel->getRutaLiquidacion($hash);
	    if($this->Prevencion_checklistmodel->eliminaPrevencion_checklist($hash)){
			if (file_exists($ruta)){
				$this->EliminaArchivo($ruta);
			}
	      	echo json_encode(array("res" => "ok" , "msg" => "Registro eliminado correctamente."));
	    }else{
	      	echo json_encode(array("res" => "error" , "msg" => "Problemas eliminando el registro, intente nuevamente."));
	    }
	}

	public function getDataPrevencion_checklist(){
		if($this->input->is_ajax_request()){
			$this->checkLogin();	
			$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
			$data=$this->Prevencion_checklistmodel->getDataPrevencion_checklist($hash);
			if($data){
				echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
			}else{
				echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
			}	
		}else{
			exit('No direct script access allowed');
		}
	}

	private function EliminaArchivo($ruta){
        if (file_exists($ruta)) {
            unlink($ruta);
        }
    }
	
}