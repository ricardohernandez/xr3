<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usuarios extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if($this->uri->segment("1")==""){
			redirect("inicio");
		}
		$this->load->model("back_end/mantenedores/Usuariosmodel");
		$this->load->model("back_end/Iniciomodel");
		$this->load->library('user_agent');
	}

	public function visitas($modulo){
		$this->load->library('user_agent');
		$data=array("id_usuario"=>$this->session->userdata('id'),
			"id_aplicacion"=>13,
			"modulo"=>$modulo,
	     	"fecha"=>date("Y-m-d G:i:s"),
	    	"navegador"=>"navegador :".$this->agent->browser()."\nversion :".$this->agent->version()."\nos :".$this->agent-> platform()."\nmovil :".$this->agent->mobile(),
	    	"ip"=>$this->input->ip_address(),
    	);
    	$this->Usuariosmodel->insertarVisita($data);
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

	//USUARIOS

		public function formCargaMasiva(){
			sleep(1);
			if($_FILES['userfile']['name']==""){
			    echo json_encode(array('res'=>'error',  "tipo" => "error" , 'msg'=>"Debe seleccionar un archivo."));exit;
			}
			$fname = $_FILES['userfile']['name'];
			if (strpos($fname, ".") == false) {
	        	 echo json_encode(array('res'=>'error', "tipo" => "error" , 'msg'=>"Debe seleccionar un archivo válido."));exit;
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
	                $i++;

	                if(!empty($data[0])){
                	  
                	  	$rut_tecnico = $data[0];
                	  	$rut_jefe = $data[1];

                	  	$id_usuario = $this->Usuariosmodel->getIdUsuarioPorRut($rut_tecnico);

                	  	if($id_usuario!=FALSE){
                	  		
                	  		$id_jefe = $this->Usuariosmodel->getIdJefe($rut_jefe);

	                	  	if($id_jefe!=FALSE){
	                	  		
	                	  		$arr = array(
									"id_jefe"=>$id_jefe,
								);	
								 
						  	 	$this->Usuariosmodel->actualizarUsuario(sha1($id_usuario),$arr);
						  	 	$arr=array();
	                	  	}
                	  	}
							
		            }
	            }

	            fclose($handle); 
	           	echo json_encode(array('res'=>'ok', "tipo" => "success", 'msg' => "Archivo cargado con éxito."));exit;

	        }else{
	        	echo json_encode(array('res'=>'error', "tipo" => "error", 'msg' => "Archivo CSV inválido."));exit;
	        }   
		}

		public function index(){
			$this->visitas("Usuarios");
	    	$this->acceso();
		    $datos = array(
		        'titulo' => "Mantenedor Usuarios",
		        'contenido' => "mantenedores/usuarios/inicio",
		        'perfiles' => $this->Iniciomodel->listaPerfiles(),
			);  
			$this->load->view('plantillas/plantilla_back_end',$datos);
		}

	    public function vistaUsuarios(){
			if($this->input->is_ajax_request()){
				$fecha_anio_atras=date('d-m-Y', strtotime('-365 day', strtotime(date("d-m-Y"))));
		    	$fecha_hoy=date('d-m-Y');

				$datos=array(	
					'fecha_anio_atras' => $fecha_anio_atras,
			        'fecha_hoy' => $fecha_hoy,
			        'perfiles' => $this->Usuariosmodel->getPerfiles(),
			        'cargos' => $this->Usuariosmodel->getCargos(),
			        'areas' => $this->Usuariosmodel->getAreas(),
			        'plazas' => $this->Usuariosmodel->getPlazas(),
			        'proyectos' => $this->Usuariosmodel->getProyectos(),
			        'jefes' => $this->Usuariosmodel->getJefes(),
					'tipos_contrato' => $this->Usuariosmodel->listaTiposContratos(),
			        'nivelesTecnicos' => $this->Usuariosmodel->listaNivelesTecnicos(),
			    );
				$this->load->view('back_end/mantenedores/usuarios/usuarios',$datos);
			}
		}

		public function listaUsuarios(){
			$estado=$this->security->xss_clean(strip_tags($this->input->get_post("estado")));
			echo json_encode($this->Usuariosmodel->listaUsuarios($estado));
		}

		public function formUsuario(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();
				$hash_usuario=$this->security->xss_clean(strip_tags($this->input->post("hash_usuario")));
				$nombres=$this->security->xss_clean(strip_tags($this->input->post("nombres")));
				$apellidos=$this->security->xss_clean(strip_tags($this->input->post("apellidos")));
				$empresa=$this->security->xss_clean(strip_tags($this->input->post("empresa")));
				$rut_format=$this->security->xss_clean(strip_tags($this->input->post("rut")));
				$rut=str_replace('.', '', $rut_format);
				$rutf=str_replace('-', '', $rut);
				$sexo=$this->security->xss_clean(strip_tags($this->input->post("sexo")));
				$nacionalidad=$this->security->xss_clean(strip_tags($this->input->post("nacionalidad")));
				$estado_civil=$this->security->xss_clean(strip_tags($this->input->post("estado_civil")));
				$codigo=$this->security->xss_clean(strip_tags($this->input->post("codigo")));
				$nivel_tecnico=$this->security->xss_clean(strip_tags($this->input->post("nivel_tecnico")));
				$cargo=$this->security->xss_clean(strip_tags($this->input->post("cargo")));
				$area=$this->security->xss_clean(strip_tags($this->input->post("area")));
				$plaza=$this->security->xss_clean(strip_tags($this->input->post("plaza")));
				$proyecto=$this->security->xss_clean(strip_tags($this->input->post("proyecto")));
				$tipo_contrato=$this->security->xss_clean(strip_tags($this->input->post("tipo_contrato")));
				$jefe=$this->security->xss_clean(strip_tags($this->input->post("jefe")));
				$proyecto=$this->security->xss_clean(strip_tags($this->input->post("proyecto")));
				$jefe=$this->security->xss_clean(strip_tags($this->input->post("jefe")));
				$domicilio=$this->security->xss_clean(strip_tags($this->input->post("domicilio")));
				$comuna=$this->security->xss_clean(strip_tags($this->input->post("comuna")));
				$ciudad=$this->security->xss_clean(strip_tags($this->input->post("ciudad")));
				$sucursal=$this->security->xss_clean(strip_tags($this->input->post("sucursal")));
				$celular_empresa=$this->security->xss_clean(strip_tags($this->input->post("celular_empresa")));
				$celular_personal=$this->security->xss_clean(strip_tags($this->input->post("celular_personal")));
				$correo_empresa=$this->security->xss_clean(strip_tags($this->input->post("correo_empresa")));
				$correo_personal=$this->security->xss_clean(strip_tags($this->input->post("correo_personal")));
				$fecha_nacimiento=$this->security->xss_clean(strip_tags($this->input->post("fecha_nacimiento")));
				$fecha_ingreso=$this->security->xss_clean(strip_tags($this->input->post("fecha_ingreso")));
				$fecha_salida=$this->security->xss_clean(strip_tags($this->input->post("fecha_salida")));
				$perfil=$this->security->xss_clean(strip_tags($this->input->post("perfil")));
				$talla_zapato=$this->security->xss_clean(strip_tags($this->input->post("zapato")));
				$talla_pantalon=$this->security->xss_clean(strip_tags($this->input->post("pantalon")));
				$talla_polera=$this->security->xss_clean(strip_tags($this->input->post("polera")));
				$talla_cazadora=$this->security->xss_clean(strip_tags($this->input->post("cazadora")));
				$estado=$this->security->xss_clean(strip_tags($this->input->post("estado")));
				$ultima_actualizacion=date("Y-m-d G:i:s")." | ".$this->session->userdata("nombres")." ".$this->session->userdata("apellidos");

				// if($fecha_termino!=""){$fecha_termino=date("Y-m-d",strtotime($fecha_termino));}else{$fecha_termino="1970-01-01";}
		  		//if($fecha_termino!=""){$fecha_termino=date("Y-m-d",strtotime($fecha_termino));}else{$fecha_termino="1970-01-01";}

				if ($this->form_validation->run("formUsuario") == FALSE){
					echo json_encode(array('res'=>"error", 'msg' => strip_tags(validation_errors())));exit;
				}else{	

					$data=array("id_cargo"=>$cargo,
						"id_proyecto"=>$proyecto,
						"id_area"=>$area,
						"id_perfil"=>$perfil,
						"id_jefe"=>$jefe,
						"id_tipo_contrato"=>$tipo_contrato,
						"id_plaza" => $plaza,
						"nombres"=>$nombres,
						"apellidos"=>$apellidos,
						"rut"=>$rutf,
						"empresa"=>$empresa,
						"comuna"=>$comuna,
						"codigo"=>$codigo,
						"id_nivel_tecnico"=>$nivel_tecnico,
						"sexo"=>$sexo,
						"nacionalidad"=>$nacionalidad,
						"estado_civil"=>$estado_civil,
						"domicilio"=>$domicilio,
						"ciudad"=>$ciudad,
						"sucursal"=>$sucursal,
						"celular_empresa"=>$celular_empresa,
						"celular_personal"=>$celular_personal,
						"correo_empresa"=>$correo_empresa,
						"correo_personal"=>$correo_personal,
						"fecha_nacimiento"=>$fecha_nacimiento,
						"fecha_ingreso"=>$fecha_ingreso,
						"fecha_salida"=>"0000-00-00",
						"talla_zapato"=>$talla_zapato,
						"talla_pantalon"=>$talla_pantalon,
						"talla_polera"=>$talla_polera,
						"talla_cazadora"=>$talla_cazadora,
						"estado"=>$estado,
						"ultima_actualizacion"=>$ultima_actualizacion,
					);	

					if($hash_usuario==""){

						$foto=@$_FILES["foto"]["name"];
						if($foto!=""){
							$foto=$this->procesaArchivo($_FILES["foto"],$rutf);
							$data["foto"]=$foto;
						}else{
							$data["foto"]="";
						}

						$data["contrasena"] = sha1($rutf);

						$exists=$this->Usuariosmodel->existeUsuario($data["rut"]);
						if($exists){
							echo json_encode(array("res" => "error" , "msg" => "El rut ingresado ya está asignado a un usuario"));exit;
						}

						$id=$this->Usuariosmodel->formUsuario($data);
						if($id!=FALSE){
							echo json_encode(array('res'=>"ok", 'msg' => MOD_MSG));exit;
						}else{
							echo json_encode(array('res'=>"ok", 'msg' => MOD_MSG));exit;
						}
						
					}else{

						if($estado=="0"){
							$data["fecha_salida"]=$fecha_salida;
						}

						$foto=@$_FILES["foto"]["name"];
						if($foto!=""){
							$foto=$this->procesaArchivo($_FILES["foto"],$rutf);
							$data["foto"]=$foto;
						}

						if($this->Usuariosmodel->actualizarUsuario($hash_usuario,$data)){
							echo json_encode(array('res'=>"ok", 'msg' => MOD_MSG));exit;
						}else{
							echo json_encode(array('res'=>"ok",  'msg' => MOD_MSG));exit;
						}
					}
	    		}	
			}
		}

		public function procesaArchivo($file,$titulo){
			$path = $file['name'];
			$ext = pathinfo($path, PATHINFO_EXTENSION);
			$archivo=strtolower(url_title(convert_accented_characters($titulo.rand(10, 1000)))).".".$ext;
			$config['upload_path'] = './fotos_usuarios';
		
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


		public function getDataUsuarios(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();
				$hash_usuario=$this->security->xss_clean(strip_tags($this->input->post("hash_usuario")));
				$data=$this->Usuariosmodel->getDataUsuarios($hash_usuario);
			
				if($data){
					echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
				}else{
					echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
				}	
			}else{
				exit('No direct script access allowed');
			}
		}

		public function excelUsuarios(){
			$estado=$this->uri->segment(2);
			$data=$this->Usuariosmodel->listaUsuarios($estado);

			if(!$data){
				return FALSE;
			}else{

				$nombre="reporte-usuarios.xls";
				header("Content-type: application/vnd.ms-excel;  charset=utf-8");
				header("Content-Disposition: attachment; filename=$nombre");
				?>
				<style type="text/css">
				.head{font-size:13px;height: 30px; background-color:#32477C;color:#fff; font-weight:bold;padding:10px;margin:10px;vertical-align:middle;}
				td{font-size:12px;text-align:center;   vertical-align:middle;}
				</style>
				<h3>Reporte Usuarios </h3>
					<table align='center' border="1"> 
				        <tr style="background-color:#F9F9F9">
						    <th class="head">Nombre</th> 
						    <th class="head">Apellidos</th> 
						    <th class="head">Empresa</th> 
						    <th class="head">Rut</th> 
						    <th class="head">Sexo</th> 
						    <th class="head">Nacionalidad</th> 
						    <th class="head">Estado civil</th> 
						    <th class="head">Cargo</th> 
						    <th class="head">Zona</th> 
						    <th class="head">Proyecto</th> 
						    <th class="head">Jefe</th> 
						    <th class="head">Tipo contrato</th> 
						    <th class="head">C&oacute;digo</th> 
						    <th class="head">Nivel t&eacute;cnico</th> 
						    <th class="head">Domicilio</th> 
						    <th class="head">Comuna</th> 
						    <th class="head">Ciudad</th> 
						    <th class="head">Sucursal</th> 
						    <th class="head">Celular empresa</th> 
						    <th class="head">Celular personal</th> 
						    <th class="head">Correo empresa</th> 
						    <th class="head">Correo personal</th> 
						    <th class="head">Fecha nacimiento</th> 
						    <th class="head">Fecha ingreso</th> 
						    <th class="head">Fecha salida</th> 
						    <th class="head">Perfil</th> 
						    <th class="head">Talla zapato</th> 
						    <th class="head">Talla pantalon</th> 
						    <th class="head">Talla polera</th>   
						    <th class="head">Talla cazadora</th>   
						    <th class="head">Estado</th>   
						    <th class="head">&Uacute;ltima actualizaci&oacute;n</th>   
				        </tr>
				        </thead>	
						<tbody>
				        <?php 
				        	if($data !=FALSE){
					      		foreach($data as $d){
				      			?>
				      			 <tr>
									 <td><?php echo utf8_decode($d["nombres"]); ?></td>
									 <td><?php echo utf8_decode($d["apellidos"]); ?></td>
									 <td><?php echo utf8_decode($d["empresa"]); ?></td>
									 <td><?php echo utf8_decode($d["rut"]); ?></td>
									 <td><?php echo utf8_decode($d["sexo"]); ?></td>
									 <td><?php echo utf8_decode($d["nacionalidad"]); ?></td>
									 <td><?php echo utf8_decode($d["estado_civil"]); ?></td>
									 <td><?php echo utf8_decode($d["cargo"]); ?></td>
									 <td><?php echo utf8_decode($d["area"]); ?></td>
									 <td><?php echo utf8_decode($d["proyecto"]); ?></td>
									 <td><?php echo utf8_decode($d["jefe"]); ?></td>
									 <td><?php echo utf8_decode($d["tipo_contrato"]); ?></td>
									 <td><?php echo utf8_decode($d["codigo"]); ?></td>
									 <td><?php echo utf8_decode($d["nivel_tecnico"]); ?></td>
									 <td><?php echo utf8_decode($d["domicilio"]); ?></td>
									 <td><?php echo utf8_decode($d["comuna"]); ?></td>
									 <td><?php echo utf8_decode($d["ciudad"]); ?></td>
									 <td><?php echo utf8_decode($d["sucursal"]); ?></td>
									 <td><?php echo utf8_decode($d["celular_empresa"]); ?></td>
									 <td><?php echo utf8_decode($d["celular_personal"]); ?></td>
									 <td><?php echo utf8_decode($d["correo_empresa"]); ?></td>
									 <td><?php echo utf8_decode($d["correo_personal"]); ?></td>
									 <td><?php echo utf8_decode($d["fecha_nacimiento"]); ?></td>
									 <td><?php echo utf8_decode($d["fecha_ingreso"]); ?></td>
									 <td><?php echo utf8_decode($d["fecha_salida"]); ?></td>
									 <td><?php echo utf8_decode($d["perfil"]); ?></td>
									 <td><?php echo utf8_decode($d["talla_zapato"]); ?></td>
									 <td><?php echo utf8_decode($d["talla_pantalon"]); ?></td>
									 <td><?php echo utf8_decode($d["talla_polera"]); ?></td>
									 <td><?php echo utf8_decode($d["talla_cazadora"]); ?></td>
									 <td><?php echo utf8_decode($d["estado_str"]); ?></td>
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

		public function eliminaUsuario(){
			$hash_usuario=$this->security->xss_clean(strip_tags($this->input->post("hash_usuario")));
		    if($this->Usuariosmodel->eliminaUsuario($hash_usuario)){
		      echo json_encode(array("res" => "ok" , "msg" => "Usuario eliminado correctamente."));
		    }else{
		      echo json_encode(array("res" => "error" , "msg" => "Problemas eliminando el usuario, intente nuevamente."));
		    }
		}

	//CARGOS
       
		public function vistaCargos(){
			if($this->input->is_ajax_request()){
				$fecha_anio_atras=date('d-m-Y', strtotime('-365 day', strtotime(date("d-m-Y"))));
		    	$fecha_hoy=date('d-m-Y');
				$datos=array(	
					'fecha_anio_atras' => $fecha_anio_atras,
			        'fecha_hoy' => $fecha_hoy,
			    );
				$this->load->view('back_end/mantenedores/usuarios/cargos',$datos);
			}
		}

		public function listaCargos(){
			echo json_encode($this->Usuariosmodel->listaCargos());
		}

		public function formCargos(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();
				$hash_cargo=$this->security->xss_clean(strip_tags($this->input->post("hash_cargo")));
				$cargo=$this->security->xss_clean(strip_tags($this->input->post("cargo")));
				$ultima_actualizacion=date("Y-m-d G:i:s");

				if ($this->form_validation->run("formCargos") == FALSE){
					echo json_encode(array('res'=>"error", 'msg' => strip_tags(validation_errors())));exit;
				}else{	

					$data=array("cargo"=>$cargo);	

					if($hash_cargo==""){

						if($this->Usuariosmodel->existeCargo($cargo)){
							echo json_encode(array('res'=>"error", 'msg' => "Ya existe un cargo con este nombre."));exit;
						}

						$id=$this->Usuariosmodel->formCargos($data);
						if($id!=FALSE){
							echo json_encode(array('res'=>"ok", 'msg' => MOD_MSG));exit;
						}else{
							echo json_encode(array('res'=>"ok", 'msg' => MOD_MSG));exit;
						}
						
					}else{

						if($this->Usuariosmodel->existeCargoMod($cargo,$hash_cargo)){
							echo json_encode(array('res'=>"error", 'msg' => "Ya existe un cargo con este nombre."));exit;
						}

						if($this->Usuariosmodel->actualizarCargos($hash_cargo,$data)){
							echo json_encode(array('res'=>"ok", 'msg' => MOD_MSG));exit;
						}else{
							echo json_encode(array('res'=>"ok",  'msg' => MOD_MSG));exit;
						}
					}
	    		}	
			}
		}

		public function getDataCargos(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();
				$hash_cargo=$this->security->xss_clean(strip_tags($this->input->post("hash_cargo")));
				$data=$this->Usuariosmodel->getDataCargos($hash_cargo);
			
				if($data){
					echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
				}else{
					echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
				}	
			}else{
				exit('No direct script access allowed');
			}
		}


		public function eliminaCargo(){
			$hash_cargo=$this->security->xss_clean(strip_tags($this->input->post("hash_cargo")));
		    if($this->Usuariosmodel->eliminaCargo($hash_cargo)){
		      echo json_encode(array("res" => "ok" , "msg" => "Registro eliminado correctamente."));
		    }else{
		      echo json_encode(array("res" => "error" , "msg" => "Problemas eliminando el registro, intente nuevamente."));
		    }
		}

	//PROYECTOS
       
		public function vistaProyectos(){
			if($this->input->is_ajax_request()){
				$fecha_anio_atras=date('d-m-Y', strtotime('-365 day', strtotime(date("d-m-Y"))));
		    	$fecha_hoy=date('d-m-Y');
				$datos=array(	
					'fecha_anio_atras' => $fecha_anio_atras,
			        'fecha_hoy' => $fecha_hoy
			    );
				$this->load->view('back_end/mantenedores/usuarios/proyectos',$datos);
			}
		}

		public function listaProyectos(){
			echo json_encode($this->Usuariosmodel->listaProyectos());
		}

		public function formProyectos(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();
				$hash_proyecto=$this->security->xss_clean(strip_tags($this->input->post("hash_proyecto")));
				$proyecto=$this->security->xss_clean(strip_tags($this->input->post("proyecto")));
				$ultima_actualizacion=date("Y-m-d G:i:s");

				if ($this->form_validation->run("formProyectos") == FALSE){
					echo json_encode(array('res'=>"error", 'msg' => strip_tags(validation_errors())));exit;
				}else{	

					$data=array("proyecto"=>$proyecto);	

					if($hash_proyecto==""){

						if($this->Usuariosmodel->existeProyecto($proyecto)){
							echo json_encode(array('res'=>"error", 'msg' => "Ya existe un proyecto con este nombre."));exit;
						}

						$id=$this->Usuariosmodel->formProyectos($data);
						if($id!=FALSE){
							echo json_encode(array('res'=>"ok", 'msg' => MOD_MSG));exit;
						}else{
							echo json_encode(array('res'=>"ok", 'msg' => MOD_MSG));exit;
						}
						
					}else{

						if($this->Usuariosmodel->existeProyectoMod($proyecto,$hash_proyecto)){
							echo json_encode(array('res'=>"error", 'msg' => "Ya existe un proyecto con este nombre."));exit;
						}

						if($this->Usuariosmodel->actualizarProyectos($hash_proyecto,$data)){
							echo json_encode(array('res'=>"ok", 'msg' => MOD_MSG));exit;
						}else{
							echo json_encode(array('res'=>"ok",  'msg' => MOD_MSG));exit;
						}
					}
	    		}	
			}
		}

		public function getDataProyectos(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();
				$hash_proyecto=$this->security->xss_clean(strip_tags($this->input->post("hash_proyecto")));
				$data=$this->Usuariosmodel->getDataProyectos($hash_proyecto);
			
				if($data){
					echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
				}else{
					echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
				}	
			}else{
				exit('No direct script access allowed');
			}
		}


		public function eliminaProyectos(){
			$hash_proyecto=$this->security->xss_clean(strip_tags($this->input->post("hash_proyecto")));
		    if($this->Usuariosmodel->eliminaProyectos($hash_proyecto)){
		      echo json_encode(array("res" => "ok" , "msg" => "Registro eliminado correctamente."));
		    }else{
		      echo json_encode(array("res" => "error" , "msg" => "Problemas eliminando el registro, intente nuevamente."));
		    }
		}
	
	//AREAS

		public function vistaAreas(){
			if($this->input->is_ajax_request()){
				$fecha_anio_atras=date('d-m-Y', strtotime('-365 day', strtotime(date("d-m-Y"))));
		    	$fecha_hoy=date('d-m-Y');
				$datos=array(	
					'fecha_anio_atras' => $fecha_anio_atras,
			        'fecha_hoy' => $fecha_hoy
			    );
				$this->load->view('back_end/mantenedores/usuarios/areas',$datos);
			}
		}

		public function listaAreas(){
			echo json_encode($this->Usuariosmodel->listaAreas());
		}

		public function formAreas(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();
				$hash_area=$this->security->xss_clean(strip_tags($this->input->post("hash_area")));
				$area=$this->security->xss_clean(strip_tags($this->input->post("area")));
				$ultima_actualizacion=date("Y-m-d G:i:s");

				if ($this->form_validation->run("formAreas") == FALSE){
					echo json_encode(array('res'=>"error", 'msg' => strip_tags(validation_errors())));exit;
				}else{	

					$data=array("area"=>$area);	

					if($hash_area==""){

						if($this->Usuariosmodel->existeArea($area)){
							echo json_encode(array('res'=>"error", 'msg' => "Ya existe un area con este nombre."));exit;
						}

						$id=$this->Usuariosmodel->formAreas($data);
						if($id!=FALSE){
							echo json_encode(array('res'=>"ok", 'msg' => MOD_MSG));exit;
						}else{
							echo json_encode(array('res'=>"ok", 'msg' => MOD_MSG));exit;
						}
						
					}else{

						if($this->Usuariosmodel->existeAreaMod($area,$hash_area)){
							echo json_encode(array('res'=>"error", 'msg' => "Ya existe un area con este nombre."));exit;
						}

						if($this->Usuariosmodel->actualizarAreas($hash_area,$data)){
							echo json_encode(array('res'=>"ok", 'msg' => MOD_MSG));exit;
						}else{
							echo json_encode(array('res'=>"ok",  'msg' => MOD_MSG));exit;
						}
					}
	    		}	
			}
		}

		public function getDataAreas(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();
				$hash_area=$this->security->xss_clean(strip_tags($this->input->post("hash_area")));
				$data=$this->Usuariosmodel->getDataAreas($hash_area);
			
				if($data){
					echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
				}else{
					echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
				}	
			}else{
				exit('No direct script access allowed');
			}
		}


		public function eliminaAreas(){
			$hash_area=$this->security->xss_clean(strip_tags($this->input->post("hash_area")));
		    if($this->Usuariosmodel->eliminaAreas($hash_area)){
		      echo json_encode(array("res" => "ok" , "msg" => "Registro eliminado correctamente."));
		    }else{
		      echo json_encode(array("res" => "error" , "msg" => "Problemas eliminando el registro, intente nuevamente."));
		    }
		}

	//PLAZAS

		public function vistaPlazas(){
			if($this->input->is_ajax_request()){
				$fecha_anio_atras=date('d-m-Y', strtotime('-365 day', strtotime(date("d-m-Y"))));
				$fecha_hoy=date('d-m-Y');
				$datos=array(	
					'fecha_anio_atras' => $fecha_anio_atras,
					'fecha_hoy' => $fecha_hoy
				);
				$this->load->view('back_end/mantenedores/usuarios/plazas',$datos);
			}
		}

		public function listaPlazas(){
			echo json_encode($this->Usuariosmodel->listaPlazas());
		}

		public function formPlazas(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();
				$hash_plaza=$this->security->xss_clean(strip_tags($this->input->post("hash_plaza")));
				$plaza=$this->security->xss_clean(strip_tags($this->input->post("plaza")));
				$ultima_actualizacion=date("Y-m-d G:i:s");

				if ($this->form_validation->run("formPlazas") == FALSE){
					echo json_encode(array('res'=>"error", 'msg' => strip_tags(validation_errors())));exit;
				}else{	

					$data=array("plaza"=>$plaza);	

					if($hash_plaza==""){

						if($this->Usuariosmodel->existePlaza($plaza)){
							echo json_encode(array('res'=>"error", 'msg' => "Ya existe una plaza con este nombre."));exit;
						}

						$id=$this->Usuariosmodel->formPlazas($data);
						if($id!=FALSE){
							echo json_encode(array('res'=>"ok", 'msg' => MOD_MSG));exit;
						}else{
							echo json_encode(array('res'=>"ok", 'msg' => MOD_MSG));exit;
						}
						
					}else{

						if($this->Usuariosmodel->existePlazaMod($plaza,$hash_plaza)){
							echo json_encode(array('res'=>"error", 'msg' => "Ya existe una plaza con este nombre."));exit;
						}

						if($this->Usuariosmodel->actualizarPlazas($hash_plaza,$data)){
							echo json_encode(array('res'=>"ok", 'msg' => MOD_MSG));exit;
						}else{
							echo json_encode(array('res'=>"ok",  'msg' => MOD_MSG));exit;
						}
					}
				}	
			}
		}

		public function getDataPlazas(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();
				$hash_plaza=$this->security->xss_clean(strip_tags($this->input->post("hash_plaza")));
				$data=$this->Usuariosmodel->getDataPlazas($hash_plaza);
			
				if($data){
					echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
				}else{
					echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
				}	
			}else{
				exit('No direct script access allowed');
			}
		}


		public function eliminaPlazas(){
			$hash_plaza=$this->security->xss_clean(strip_tags($this->input->post("hash_plaza")));
			if($this->Usuariosmodel->eliminaPlazas($hash_plaza)){
			echo json_encode(array("res" => "ok" , "msg" => "Registro eliminado correctamente."));
			}else{
			echo json_encode(array("res" => "error" , "msg" => "Problemas eliminando el registro, intente nuevamente."));
			}
		}
		
	//JEFES

		public function vistaJefes(){
			if($this->input->is_ajax_request()){
				$fecha_anio_atras=date('d-m-Y', strtotime('-365 day', strtotime(date("d-m-Y"))));
		    	$fecha_hoy=date('d-m-Y');
				$datos=array(	
					'fecha_anio_atras' => $fecha_anio_atras,
			        'fecha_hoy' => $fecha_hoy,
			        'usuarios' => $this->Usuariosmodel->listaUsuariosJefes()
			    );
				$this->load->view('back_end/mantenedores/usuarios/jefes',$datos);
			}
		}

		public function listaJefes(){
			echo json_encode($this->Usuariosmodel->listaJefes());
		}

		public function formJefes(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();
				$hash_jefe=$this->security->xss_clean(strip_tags($this->input->post("hash_jefe")));
				$jefe=$this->security->xss_clean(strip_tags($this->input->post("jefe")));
				$ultima_actualizacion=date("Y-m-d G:i:s");

				if ($this->form_validation->run("formJefes") == FALSE){
					echo json_encode(array('res'=>"error", 'msg' => strip_tags(validation_errors())));exit;
				}else{	

					$data=array("id_jefe"=>$jefe);	

					if($hash_jefe==""){

						if($this->Usuariosmodel->existeJefes($jefe)){
							echo json_encode(array('res'=>"error", 'msg' => "Ya existe un jefe con este nombre."));exit;
						}

						$id=$this->Usuariosmodel->formJefes($data);
						if($id!=FALSE){
							echo json_encode(array('res'=>"ok", 'msg' => MOD_MSG));exit;
						}else{
							echo json_encode(array('res'=>"ok", 'msg' => MOD_MSG));exit;
						}
						
					}else{

						if($this->Usuariosmodel->existeJefesMod($jefe,$hash_jefe)){
							echo json_encode(array('res'=>"error", 'msg' => "Ya existe un jefe con este nombre."));exit;
						}

						if($this->Usuariosmodel->actualizarJefes($hash_jefe,$data)){
							echo json_encode(array('res'=>"ok", 'msg' => MOD_MSG));exit;
						}else{
							echo json_encode(array('res'=>"ok",  'msg' => MOD_MSG));exit;
						}
					}
	    		}	
			}
		}

		public function getDataJefes(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();
				$hash_jefe=$this->security->xss_clean(strip_tags($this->input->post("hash_jefe")));
				$data=$this->Usuariosmodel->getDataJefes($hash_jefe);
			
				if($data){
					echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
				}else{
					echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
				}	
			}else{
				exit('No direct script access allowed');
			}
		}


		public function eliminaJefes(){
			$hash_jefe=$this->security->xss_clean(strip_tags($this->input->post("hash_jefe")));
		    if($this->Usuariosmodel->eliminaJefes($hash_jefe)){
		      echo json_encode(array("res" => "ok" , "msg" => "Registro eliminado correctamente."));
		    }else{
		      echo json_encode(array("res" => "error" , "msg" => "Problemas eliminando el registro, intente nuevamente."));
		    }
		}

	//PERFILES

		public function vistaPerfiles(){
			if($this->input->is_ajax_request()){
				$fecha_anio_atras=date('d-m-Y', strtotime('-365 day', strtotime(date("d-m-Y"))));
		    	$fecha_hoy=date('d-m-Y');
				$datos=array(	
					'fecha_anio_atras' => $fecha_anio_atras,
			        'fecha_hoy' => $fecha_hoy
			    );
				$this->load->view('back_end/mantenedores/usuarios/perfiles',$datos);
			}
		}

		public function listaPerfiles(){
			echo json_encode($this->Usuariosmodel->listaPerfiles());
		}

		public function formPerfiles(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();
				$hash_perfil=$this->security->xss_clean(strip_tags($this->input->post("hash_perfil")));
				$perfil=$this->security->xss_clean(strip_tags($this->input->post("perfil")));
				$ultima_actualizacion=date("Y-m-d G:i:s");

				if ($this->form_validation->run("formPerfiles") == FALSE){
					echo json_encode(array('res'=>"error", 'msg' => strip_tags(validation_errors())));exit;
				}else{	

					$data=array("perfil"=>$perfil);	

					if($hash_perfil==""){

						if($this->Usuariosmodel->existePerfil($perfil)){
							echo json_encode(array('res'=>"error", 'msg' => "Ya existe un perfil con este nombre."));exit;
						}

						$id=$this->Usuariosmodel->formPerfiles($data);
						if($id!=FALSE){
							echo json_encode(array('res'=>"ok", 'msg' => MOD_MSG));exit;
						}else{
							echo json_encode(array('res'=>"ok", 'msg' => MOD_MSG));exit;
						}
						
					}else{

						if($this->Usuariosmodel->existePerfilMod($perfil,$hash_perfil)){
							echo json_encode(array('res'=>"error", 'msg' => "Ya existe un perfil con este nombre."));exit;
						}

						if($this->Usuariosmodel->actualizarPerfiles($hash_perfil,$data)){
							echo json_encode(array('res'=>"ok", 'msg' => MOD_MSG));exit;
						}else{
							echo json_encode(array('res'=>"ok",  'msg' => MOD_MSG));exit;
						}
					}
	    		}	
			}
		}

		public function getDataPerfiles(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();
				$hash_perfil=$this->security->xss_clean(strip_tags($this->input->post("hash_perfil")));
				$data=$this->Usuariosmodel->getDataPerfiles($hash_perfil);
			
				if($data){
					echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
				}else{
					echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
				}	
			}else{
				exit('No direct script access allowed');
			}
		}


		public function eliminaPerfiles(){
			$hash_perfil=$this->security->xss_clean(strip_tags($this->input->post("hash_perfil")));
		    if($this->Usuariosmodel->eliminaPerfiles($hash_perfil)){
		      echo json_encode(array("res" => "ok" , "msg" => "Registro eliminado correctamente."));
		    }else{
		      echo json_encode(array("res" => "error" , "msg" => "Problemas eliminando el registro, intente nuevamente."));
		    }
		}


	/*********HERRAMIENTAS************/
		
	    public function vistaHerramientas(){

			if($this->input->is_ajax_request()){
				$fecha_anio_atras=date('d-m-Y', strtotime('-365 day', strtotime(date("d-m-Y"))));
		    	$fecha_hoy=date('d-m-Y');

				$datos=array(	
					'fecha_anio_atras' => $fecha_anio_atras,	   
			        'fecha_hoy' => $fecha_hoy,
			        'tipos' => $this->Usuariosmodel->getTipos()
			    );

				$this->load->view('back_end/mantenedores/usuarios/herramientas',$datos);
			}
		}

		public function listaHerramientas(){
			$tipo=$this->security->xss_clean(strip_tags($this->input->get_post("tipo")));
			echo json_encode($this->Usuariosmodel->listaHerramientas($tipo));
		}

		public function formHerramientas(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();
				$hash_herramienta=$this->security->xss_clean(strip_tags($this->input->post("hash_herramienta")));
				$tipo=$this->security->xss_clean(strip_tags($this->input->post("tipo")));
				$valor=$this->security->xss_clean(strip_tags($this->input->post("valor")));
				$unidad=$this->security->xss_clean(strip_tags($this->input->post("unidad")));
				$descripcion=$this->security->xss_clean(strip_tags($this->input->post("descripcion")));

				if ($this->form_validation->run("formHerramientas") == FALSE){
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

						$id=$this->Usuariosmodel->formHerramientas($data);
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

						if($this->Usuariosmodel->actualizarHerramientas($hash_herramienta,$data)){
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
			$config['upload_path'] = './fotos_herramientas';
		
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


		public function getDataHerramientas(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();
				$hash_herramienta=$this->security->xss_clean(strip_tags($this->input->post("hash_herramienta")));
				$data=$this->Usuariosmodel->getDataHerramientas($hash_herramienta);
			
				if($data){
					echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
				}else{
					echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
				}	
			}else{
				exit('No direct script access allowed');
			}
		}



	/**********MANTENEDOR RESPONSABLES FALLOS ************/
		
		
		public function vistaResponsablesFallosHerramientas(){
			$this->visitas("Responsables fallos herramientas");
			$fecha_anio_atras=date('d-m-Y', strtotime('-360 day', strtotime(date("d-m-Y"))));
	    	$fecha_hoy=date('d-m-Y');
			$responsables=$this->Usuariosmodel->listaResponsablesFallos();
			$proyectos=$this->Usuariosmodel->listaProyectosC();
			$listaItemsHerramientas=$this->Usuariosmodel->listaItemsHerramientas();

			$datos=array(
				'fecha_anio_atras' => $fecha_anio_atras,	   
		        'fecha_hoy' => $fecha_hoy,
		        'proyectos' => $proyectos,
		        'responsables' => $responsables,
		        'listaItemsHerramientas' => $listaItemsHerramientas,

		   	);
			$this->load->view('back_end/mantenedores/usuarios/responsables_fallos',$datos);
		}

		public function listaResponsablesFallosHerramientas(){
			$proyecto=$this->security->xss_clean(strip_tags($this->input->get_post("proyecto")));
			echo json_encode($this->Usuariosmodel->listaResponsablesFallosHerramientas($proyecto));
		}

		public function formResponsablesFallosHerramientas(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();
				$hash_responsable=$this->security->xss_clean(strip_tags($this->input->post("hash_responsable_fallos")));
				$item=$this->security->xss_clean(strip_tags($this->input->post("item_fallos")));
				$proyecto=$this->security->xss_clean(strip_tags($this->input->post("proyecto_fallos")));
				$responsable=$this->security->xss_clean(strip_tags($this->input->post("responsable_fallos")));
				$plazo=$this->security->xss_clean(strip_tags($this->input->post("plazo_fallos")));
				$ultima_actualizacion=date("Y-m-d G:i:s");

				if ($this->form_validation->run("formResponsablesFallosHerramientas") == FALSE){
					echo json_encode(array('res'=>"error", 'msg' => strip_tags(validation_errors())));exit;
				}else{	

					$data=array("id_item"=>$item,
						"id_proyecto"=>$proyecto,
						"id_responsable"=>$responsable,
						"plazo"=>$plazo,
						"ultima_actualizacion"=>$ultima_actualizacion
					);	

					if($hash_responsable==""){

						if($this->Usuariosmodel->existeResponsableItem($item,$proyecto)){
							echo json_encode(array('res'=>"error", 'msg' => "Ya existe un registro con este nombre."));exit;
						}

						$id=$this->Usuariosmodel->formResponsablesFallosHerramientas($data);
						if($id!=FALSE){
							echo json_encode(array('res'=>"ok", 'msg' => MOD_MSG));exit;
						}else{
							echo json_encode(array('res'=>"ok", 'msg' => MOD_MSG));exit;
						}
						
					}else{

						if($this->Usuariosmodel->existeResponsableItemMod($hash_responsable,$item,$proyecto)){
							echo json_encode(array('res'=>"error", 'msg' => "Ya existe un registro con este nombre."));exit;
						}

						if($this->Usuariosmodel->formActualizarResponsablesFallosHerramientas($hash_responsable,$data)){
							echo json_encode(array('res'=>"ok", 'msg' => MOD_MSG));exit;
						}else{
							echo json_encode(array('res'=>"ok",  'msg' => MOD_MSG));exit;
						}
					}
	    		}	
			}
		}

		public function getDataResponsableFallosHerramientas(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();
				$hash=$this->security->xss_clean(strip_tags($this->input->post("hash_responsable_fallos")));
				$data=$this->Usuariosmodel->getDataResponsableFallosHerramientas($hash);

				if($data){
					echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
				}else{
					echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
				}	
			}else{
				exit('No direct script access allowed');
			}
		}

		
		public function eliminaResponsableFallosHerramientas(){
			$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
			
		    if($this->Usuariosmodel->eliminaResponsableFallosHerramientas($hash)){
		      echo json_encode(array("res" => "ok" , "msg" => "Registro eliminado correctamente."));
		    }else{
		      echo json_encode(array("res" => "error" , "msg" => "Problemas eliminando el registro, intente nuevamente."));
		    }
		}

		public function insertaDatosFallosHerramientas(){
			$this->Usuariosmodel->truncateResponsablesHerramientas();
			$proyectos=$this->Usuariosmodel->listaProyectosC();
			$listaItemsHerramientas=$this->Usuariosmodel->listaItemsHerramientas();

			foreach($proyectos as $p){

				foreach($listaItemsHerramientas as $item){

					$data = array(
						 "id_item" => $item["id"],
						 "id_proyecto" => $p["id"], 
						 "id_responsable" => "", 
						 "plazo" => "1", 
						 "ultima_actualizacion" => date("Y-m-d G:i:s"));

					$this->Usuariosmodel->formResponsablesFallosHerramientas($data);
				}
			}
		}
}