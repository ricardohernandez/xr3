<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ChecklistFTTH extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if($this->uri->segment("1")==""){
			redirect("inicio");
		}
		$this->load->model("back_end/mantenedores/ChecklistFTTHmodel");
		$this->load->model("back_end/Iniciomodel");
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
    	$this->ChecklistFTTHmodel->insertarVisita($data);
	}

	public function checkLogin(){
		if(!$this->session->userdata('id')){
			echo json_encode(array('res'=>"sess"));exit;
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

	public function index(){
		$this->visitas("Mantenedor Checklist FTTH");
    	$this->acceso();
	    $datos = array(
	        'titulo' => "Mantenedor Checklist FTTH",
	        'contenido' => "mantenedores/checklistFTTH/inicio",
	        'perfiles' => $this->Iniciomodel->listaPerfiles(),
		);  
		$this->load->view('plantillas/plantilla_back_end',$datos);
	}

    public function vistaMantChecklistFTTH(){

		if($this->input->is_ajax_request()){
			$fecha_anio_atras=date('d-m-Y', strtotime('-365 day', strtotime(date("d-m-Y"))));
	    	$fecha_hoy=date('d-m-Y');
			$datos=array(	
				'fecha_anio_atras' => $fecha_anio_atras,	   
		        'fecha_hoy' => $fecha_hoy,
		        'tipos' => $this->ChecklistFTTHmodel->getTipos()
		    );
			$this->load->view('back_end/mantenedores/checklistFTTH/checklistFTTH',$datos);
		}
	}

	public function listaMantChecklistFTTH(){
		$tipo=$this->security->xss_clean(strip_tags($this->input->get_post("tipo")));
		echo json_encode($this->ChecklistFTTHmodel->listaMantChecklistFTTH($tipo));
	}

	public function formMantChecklistFTTH(){
		if($this->input->is_ajax_request()){
			$this->checkLogin();
			$hash_herramienta=$this->security->xss_clean(strip_tags($this->input->post("hash_herramienta")));
			$tipo=$this->security->xss_clean(strip_tags($this->input->post("tipo")));
			$valor=$this->security->xss_clean(strip_tags($this->input->post("valor")));
			$unidad=$this->security->xss_clean(strip_tags($this->input->post("unidad")));
			$descripcion=$this->security->xss_clean(strip_tags($this->input->post("descripcion")));

			if ($this->form_validation->run("formMantChecklistFTTH") == FALSE){
				echo json_encode(array('res'=>"error", 'msg' => strip_tags(validation_errors())));exit;
			}else{	

				$data=array("tipo"=>$tipo,
					"descripcion"=>$descripcion,
					"valor"=>$valor,
					"unidad"=>$unidad
				);	

				if($hash_herramienta==""){

					$imagen=@$_FILES["imagen"]["name"];
					if($imagen!=""){
						$nombre=$this->procesaArchivoH($_FILES["imagen"],"descripcion"."_".date("ymdHis"));
						$data["imagen"]=$nombre;
					}else{
						$data["imagen"]="";
					}

					$id=$this->ChecklistFTTHmodel->formMantChecklistFTTH($data);
					if($id!=FALSE){
						echo json_encode(array('res'=>"ok", 'msg' => MOD_MSG));exit;
					}else{
						echo json_encode(array('res'=>"ok", 'msg' => MOD_MSG));exit;
					}
					
				}else{

					$imagen=@$_FILES["imagen"]["name"];
					if($imagen!=""){
						$nombre=$this->procesaArchivoH($_FILES["imagen"],"descripcion"."_".date("ymdHis"));
						$data["imagen"]=$nombre;
					}

					if($this->ChecklistFTTHmodel->actualizarMantChecklistFTTH($hash_herramienta,$data)){
						echo json_encode(array('res'=>"ok", 'msg' => MOD_MSG));exit;
					}else{
						echo json_encode(array('res'=>"ok",  'msg' => MOD_MSG));exit;
					}
				}
    		}	
		}
	}

	public function procesaArchivoH($file,$titulo){
		$path = $file['name'];
		$ext = pathinfo($path, PATHINFO_EXTENSION);
		$archivo=strtolower(url_title(convert_accented_characters($titulo.rand(10, 1000)))).".".$ext;
		$config['upload_path'] = './archivos/fotos_checklist/ftth';
		$config['allowed_types'] = 'jpg|jpeg|bmp|png|PNG|JPG|JPEG|BMP|PNG';
	    $config['file_name'] = $archivo;
		$config['max_size']	= '5300';
		$config['overwrite']	= FALSE;
		$this->load->library('upload', $config);
		$_FILES['userfile']['name'] = $archivo;
	    $_FILES['userfile']['type'] = $file['type'];
	    $_FILES['userfile']['tmp_name'] = $file['tmp_name'];
	    $_FILES['userfile']['error'] = $file['error'];
		$_FILES['userfile']['size'] = $file['size'];
		$this->upload->initialize($config);

		if (!$this->upload->do_upload()){ 
		    echo json_encode(array('res'=>"error", 'msg' =>strip_tags($this->upload->display_errors())));exit;
	    }else{
	    	unset($config);
	    	return $archivo;
	    }
	}


	public function getDataMantChecklistFTTH(){
		if($this->input->is_ajax_request()){
			$this->checkLogin();
			$hash_herramienta=$this->security->xss_clean(strip_tags($this->input->post("hash_herramienta")));
			$data=$this->ChecklistFTTHmodel->getDataMantChecklistFTTH($hash_herramienta);
		
			if($data){
				echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
			}else{
				echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
			}	
		}else{
			exit('No direct script access allowed');
		}
	}

	
	public function eliminaMantChecklistFTTH(){
		$hash=$this->security->xss_clean(strip_tags($this->input->post("hash_herramienta")));
	    if($this->ChecklistFTTHmodel->eliminaMantChecklistFTTH($hash)){
	      echo json_encode(array("res" => "ok" , "msg" => "Registro eliminado correctamente."));
	    }else{
	      echo json_encode(array("res" => "error" , "msg" => "Problemas eliminando el registro, intente nuevamente."));
	    }
	}

	public function excelMantChecklistFTTH(){
		$tipo=$this->uri->segment(2);
		if($tipo=="-"){$tipo="";}

		$data=$this->ChecklistFTTHmodel->listaMantChecklistFTTH($tipo);

		if(!$data){
			return FALSE;
		}else{

			$nombre="reporte-checklist_FTTH.xls";
			header("Content-type: application/vnd.ms-excel;  charset=utf-8");
			header("Content-Disposition: attachment; filename=$nombre");
			?>
			<style type="text/css">
			.head{font-size:13px;height: 30px; background-color:#32477C;color:#fff; font-weight:bold;padding:10px;margin:10px;vertical-align:middle;}
			td{font-size:12px;text-align:center;   vertical-align:middle;}
			
			</style>
			<h3>Reporte checklist FTTH</h3>
				<table align='center' border="1"> 
			        <tr style="background-color:#F9F9F9">
			            <th class="head">Descripci&oacute;n</th>   
			            <th class="head">Tipo</th> 
			            <th class="head">Valor</th>   
			            <th class="head">Unidad</th>   
			        </tr>
			        </thead>	
					<tbody>
			        <?php 
			        	if($data !=FALSE){
				      		foreach($data as $d){
			      			?>
			      			 <tr>
								 <td><?php echo utf8_decode($d["descripcion"]); ?></td>
								 <td><?php echo utf8_decode($d["tipo"]); ?></td>
								 <td><?php echo utf8_decode($d["valor"]); ?></td>
								 <td><?php echo utf8_decode($d["unidad"]); ?></td>
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